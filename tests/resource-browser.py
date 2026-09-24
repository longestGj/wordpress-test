"""Read-only browser geometry, keyboard and Gutenberg serialization checks."""
import json
import sys
from pathlib import Path
from playwright.sync_api import sync_playwright

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / '.local/resource-work/browser'
OUT.mkdir(parents=True, exist_ok=True)
BASE = 'http://localhost:8080'
seed_dir = ROOT / ('data/process-applications' if '--topics' in sys.argv else 'data/resources')
seeds = [json.loads(path.read_text(encoding='utf-8')) for path in sorted(seed_dir.glob('*.json'))]
results = []
with sync_playwright() as p:
    browser = p.chromium.launch()
    context = browser.new_context()
    page = context.new_page()
    errors = []
    page.on('pageerror', lambda error: errors.append(str(error)))
    for seed in ([] if '--editor-only' in sys.argv else seeds):
        for width in (1440, 768, 390):
            page.set_viewport_size({'width':width,'height':1000})
            page.goto(BASE+seed['path'], wait_until='networkidle')
            page.evaluate('document.fonts.ready')
            dimensions = page.evaluate('''() => ({width:innerWidth, scroll:document.documentElement.scrollWidth,
                height:document.documentElement.scrollHeight,
                overflow:[...document.querySelectorAll('main *')].filter(e=>{
                  const r=e.getBoundingClientRect(),s=getComputedStyle(e);
                  return r.width>0 && s.position!=='absolute' && (r.right>innerWidth+1 || r.left< -1);
                }).map(e=>e.tagName+'.'+e.className).slice(0,12)})''')
            results.append({'page':seed['identity'],'width':width,**dimensions})
            if '--quick' not in sys.argv:
                page.screenshot(path=str(OUT/f"{seed['identity']}-{width}.png"), full_page=True)
            if dimensions['scroll'] > width+1:
                print('FAIL overflow:', seed['identity'], width, dimensions['overflow'])
            else:
                print('PASS geometry:', seed['identity'], width)
        disclosures = page.locator('main details')
        if disclosures.count():
            first = disclosures.first
            before = first.get_attribute('open') is not None
            first.locator('summary').focus()
            page.keyboard.press('Enter')
            assert (first.get_attribute('open') is not None) != before, 'native disclosure keyboard failed'
    assert not errors, errors
    if '--editor-only' not in sys.argv:
        for width in (1440, 768, 390):
            page.set_viewport_size({'width':width,'height':1000})
            page.goto(BASE+'/resources/',wait_until='networkidle')
            assert page.locator('.resource-hub-links a').count() == 8
            assert page.evaluate('document.documentElement.scrollWidth <= innerWidth+1'), 'Resource Hub overflow'
            assert page.locator('section.res-next h2').inner_text() == 'Continue Your Procurement Review'
            assert page.locator('section.res-next a').count() == 4
            for path in ('/products/', '/applications/', '/documents/', '/markets/'):
                link = page.locator(f'section.res-next a[href="{path}"]')
                assert link.count() == 1 and link.is_visible(), (width, path)
                assert context.request.get(BASE+path).status == 200, path
        links = page.locator('.resource-hub-links a')
        links.nth(0).focus();page.keyboard.press('Tab')
        assert links.nth(1).evaluate('(e)=>e===document.activeElement'), 'Hub keyboard order'
        assert links.nth(1).evaluate('(e)=>parseFloat(getComputedStyle(e).outlineWidth)>=3'), 'Hub focus not visible'
        next_links = page.locator('section.res-next a')
        next_links.nth(0).focus();page.keyboard.press('Tab')
        assert next_links.nth(1).evaluate('(e)=>e===document.activeElement'), 'Procurement routing keyboard order'
        assert next_links.nth(1).evaluate('(e)=>parseFloat(getComputedStyle(e).outlineWidth)>=3'), 'Procurement routing focus not visible'
        for path in ('/products/', '/applications/', '/documents/', '/markets/'):
            page.locator(f'section.res-next a[href="{path}"]').click()
            assert page.url == BASE+path, ('Procurement routing click failed', path)
            page.go_back(wait_until='domcontentloaded')
        page.locator('#research-paths').scroll_into_view_if_needed()
        page.screenshot(path=str(OUT/'hub-mobile-final.png'))
        print('PASS: Hub 1440/768/390, eight links, keyboard order and visible focus')
    if results:
        (OUT/'geometry.json').write_text(json.dumps(results,indent=2),encoding='utf-8')
    # Load Gutenberg once; parsing seeds is read-only and does not save records.
    env = {}
    for line in (ROOT/'.env').read_text(encoding='utf-8-sig').splitlines():
        if '=' in line and not line.startswith('#'):
            key,value=line.split('=',1);env[key]=value.strip().strip('"').strip("'")
    context.request.get(BASE+'/wp-login.php')
    login = context.request.post(BASE+'/wp-login.php', form={'log':env['WP_ADMIN_USER'],'pwd':env['WP_ADMIN_PASSWORD'],'wp-submit':'Log In','redirect_to':BASE+'/wp-admin/','testcookie':'1'})
    assert login.ok, 'Local admin login failed'
    page_id = context.request.get(BASE+'/wp-json/wp/v2/pages?slug=chloride-vs-sulfate-titanium-dioxide').json()[0]['id']
    page.goto(BASE+f'/wp-admin/post.php?post={page_id}&action=edit', wait_until='domcontentloaded', timeout=60000)
    page.wait_for_function('() => Boolean(window.wp && wp.blocks && wp.blocks.parse)', timeout=30000)
    block_results = []
    editor_seeds = seeds + ([] if '--topics' in sys.argv else [json.loads((ROOT/'data/pages/resources.json').read_text(encoding='utf-8'))])
    for seed in editor_seeds:
        validation = page.evaluate('''content => {
          const blocks=wp.blocks.parse(content), invalid=[];let total=0,editable=0;
          function walk(items){for(const b of items){total++;if(b.name!=='core/html')editable++;
            if(b.isValid===false)invalid.push({name:b.name,attributes:b.attributes,original:b.originalContent.slice(0,220),expected:wp.blocks.getSaveContent(b.name,b.attributes,b.innerBlocks).slice(0,220)});
            walk(b.innerBlocks||[]);}}
          walk(blocks);return {total,editable,invalid};
        }''', seed['content'])
        identity = seed.get('identity', seed.get('page_id', seed.get('key')))
        block_results.append({'page':identity,**validation})
        print('Gutenberg:',identity,validation['total'],'blocks;',len(validation['invalid']),'invalid')
    report = 'topic-blocks.json' if '--topics' in sys.argv else 'blocks.json'
    (OUT/report).write_text(json.dumps(block_results,ensure_ascii=False,indent=2),encoding='utf-8')
    context.close();browser.close()
assert all(row['scroll']<=row['width']+1 for row in results), 'Horizontal overflow; see geometry.json'
assert all(not row['invalid'] for row in block_results), 'Invalid native blocks; see blocks.json'
print('PASS: native block validation' if '--editor-only' in sys.argv else 'PASS: 8 pages and Hub x 3 widths, keyboard, no JS errors, native block validation')
