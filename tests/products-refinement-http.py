"""Read-only Products selector, comparison, links and responsive checks."""
import json,sys
from pathlib import Path
from urllib.parse import urlparse
import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright

root=Path(__file__).resolve().parents[1]
base=(sys.argv[1] if len(sys.argv)>1 else 'http://localhost:8083').rstrip('/')
assert urlparse(base).hostname in ('localhost','127.0.0.1'), 'Local preview only'
data=json.loads((root/'data/product-discovery.json').read_text(encoding='utf-8'))
seed=json.loads((root/'data/pages/products.json').read_text(encoding='utf-8'))
response=requests.get(base+'/products/',timeout=20);response.raise_for_status()
soup=BeautifulSoup(response.text,'html.parser')
assert len(soup.select('h1'))==1 and soup.h1.get_text()==seed['h1']
assert soup.title.get_text()==seed['seo_title']
assert soup.select_one('link[rel=canonical]')['href']==base+'/products/'
assert soup.select_one('.rootHero .primary')['href']=='#grade-selector'
assert len(soup.select('.gradeRow'))==14
for row in data['rows']:
    card=soup.select_one('[data-grade="'+row['grade']+'"]')
    assert card and card.select_one('a')['href']==base+row['url'],row['grade']
    assert row['summary'] in card.get_text(' ',strip=True),row['grade']
    assert len(card.select('dt'))==3,row['grade']
    assert requests.get(base+row['url'],timeout=15).status_code==200,row['url']
for path in ['/products/chloride-process-titanium-dioxide/','/products/sulfate-process-titanium-dioxide/']:
    assert soup.select_one('a[href="'+path+'"]')
    assert requests.get(base+path,timeout=15).status_code==200
graphs=[]
for script in soup.select('script[type="application/ld+json"]'):
    value=json.loads(script.string)
    graphs.extend(value.get('@graph',[value]))
items=next(g['itemListElement'] for g in graphs if g.get('@type')=='ItemList')
assert [(x['name'],x['url']) for x in items]==[(r['grade'],base+r['url']) for r in data['rows']]
assert len(soup.select('#faq details'))==4
out=root/'.local/products-refinement';out.mkdir(parents=True,exist_ok=True)
with sync_playwright() as p:
    browser=p.chromium.launch(headless=True)
    for width in [1440,768,390]:
        page=browser.new_page(viewport={'width':width,'height':1000})
        errors=[];page.on('pageerror',lambda error:errors.append(str(error)))
        page.goto(base+'/products/',wait_until='networkidle')
        for app,grades in data['applications'].items():
            page.locator('[data-app="'+app+'"]').click()
            panel=page.locator('[data-result-app="'+app+'"]')
            assert panel.is_visible()
            assert panel.locator('.result strong').all_text_contents()==grades,(width,app)
        page.locator('[data-app="Not Sure"]').click()
        guidance=page.locator('[data-result-app="Not Sure"]')
        assert guidance.is_visible() and len(guidance.locator('p').all_text_contents())>=2
        assert page.locator('body').evaluate('(el)=>el.scrollWidth<=innerWidth'),width
        for card in page.locator('.gradeRow').all():
            assert card.evaluate('(el)=>el.scrollWidth<=el.clientWidth'),width
        page.locator('#all-grades').screenshot(path=str(out/f'directory-{width}.png'))
        page.screenshot(path=str(out/f'products-{width}.png'),full_page=True)
        assert not errors,errors
        page.close()
    browser.close()
print('PASS: 14 grade links/claims, all selector mappings, Not Sure, process links, canonical, ItemList and 1440/768/390 layouts')
