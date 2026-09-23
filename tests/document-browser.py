"""Browser checks on isolated seed previews (not a WordPress runtime acceptance)."""
import json
from pathlib import Path
from urllib.parse import urlparse,parse_qs
from playwright.sync_api import sync_playwright
ROOT=Path(__file__).resolve().parents[1];OUT=ROOT/'.local/document-preview';OUT.mkdir(parents=True,exist_ok=True)
assets=ROOT/'wp-content/themes/tio2/assets'
with sync_playwright() as p:
    browser=p.chromium.launch();page=browser.new_page();errors=[]
    page.on('pageerror',lambda error:errors.append(str(error)))
    for file in sorted((ROOT/'data/documents').glob('*.json')):
        seed=json.loads(file.read_text(encoding='utf-8'));body=seed['content']
        grade='<label for="document-grade">Product Grade</label><select id="document-grade"><option value="">Choose a Grade</option><option value="M-2196" data-url="/products/m-2196/">M-2196</option></select><a id="document-grade-detail" hidden>View selected Grade</a>'
        body=body.replace('[tio2_document_grades]',grade)
        html=f'<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="{(assets/"site.css").as_uri()}"><link rel="stylesheet" href="{(assets/"documents.css").as_uri()}"></head><body><main class="document-guide {seed["identity"].lower()}">{body}</main><script src="{(assets/"documents.js").as_uri()}"></script></body></html>'
        target=OUT/(seed['identity']+'.html');target.write_text(html,encoding='utf-8')
        for width in (1440,768,390):
            page.set_viewport_size({'width':width,'height':1000});page.goto(target.as_uri());page.evaluate('document.fonts.ready')
            assert page.evaluate('document.documentElement.scrollWidth<=innerWidth+1'),(seed['identity'],width,'overflow')
            page.screenshot(path=str(OUT/f'{seed["identity"]}-{width}.png'),full_page=True)
            print('PASS isolated geometry:',seed['identity'],width)
        if page.locator('details').count():
            summary=page.locator('summary').first;summary.focus();page.keyboard.press('Enter')
            assert page.locator('details').first.get_attribute('open') is not None
            assert summary.evaluate('(e)=>parseFloat(getComputedStyle(e).outlineWidth)>=3')
        if seed['identity']=='DOC-TDS':
            page.locator('input[value="safety"]').check();page.locator('input[value="quality_coa"]').check()
            href=page.locator('[data-document-request]').first.get_attribute('href');q=parse_qs(urlparse(href).query)
            assert q['prefill.document_types[]']==['safety','quality_coa'] and 'prefill.product_grade' not in q
            page.select_option('#document-grade','M-2196');q=parse_qs(urlparse(page.locator('[data-document-request]').first.get_attribute('href')).query)
            assert q['prefill.product_grade']==['M-2196']
            assert page.locator('#document-grade-detail').is_visible()
            page.select_option('#document-grade','');assert page.locator('#document-grade-detail').is_hidden()
            print('PASS selection: multi-document, optional grade, clear grade, detail link')
    assert not errors,errors
    browser.close()
