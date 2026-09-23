"""Read-only checks for the Applications evaluation hub and its five detail routes."""
import json,sys
from pathlib import Path
from urllib.parse import urlparse
import requests
from bs4 import BeautifulSoup
from playwright.sync_api import sync_playwright

root=Path(__file__).resolve().parents[1]
base=(sys.argv[1] if len(sys.argv)>1 else 'http://localhost:8084').rstrip('/')
assert urlparse(base).hostname in ('localhost','127.0.0.1'),'Local preview only'
seed=json.loads((root/'data/pages/applications.json').read_text(encoding='utf-8'))
discovery=json.loads((root/'data/product-discovery.json').read_text(encoding='utf-8'))
response=requests.get(base+'/applications/',timeout=20);response.raise_for_status()
soup=BeautifulSoup(response.text,'html.parser')
assert len(soup.select('main h1'))==1 and soup.select_one('main h1').get_text(strip=True)==seed['h1']
assert soup.title.get_text(strip=True)==seed['seo_title']
assert soup.select_one('link[rel="canonical"]')['href']==base+'/applications/'
expected={'coatings':('Coatings','/applications/titanium-dioxide-for-coatings/'),
 'plastics':('Plastics','/applications/titanium-dioxide-for-plastics/'),
 'masterbatch':('Masterbatch','/applications/titanium-dioxide-for-masterbatch/'),
 'printing-inks':('Printing Inks','/applications/titanium-dioxide-for-printing-inks/'),
 'paper':('Paper','/applications/titanium-dioxide-for-paper/')}
for slug,(label,path) in expected.items():
    card=soup.select_one('#application-'+slug)
    assert card and card.select_one('h3').get_text(strip=True)==label
    children=[c.name for c in card.children if getattr(c,'name',None)]
    assert children.index('h3')<children.index('p')<children.index('div')<children.index('a')<children.index('ul'),slug
    factors=card.select_one('.evaluationFactors')
    assert factors and factors.select_one('strong').get_text(strip=True)=='Key evaluation factors',slug
    assert len(factors.select('li'))>=4,slug
    assert card.select_one('a.applicationAction')['href']==path,slug
    assert requests.get(base+path,timeout=20).status_code==200,path
    observed=[li['data-grade'] for li in card.select('.gradeList li')]
    assert observed==discovery['applications'][label],slug
    detail=json.loads((root/'data/process-applications'/f'{slug}.json').read_text(encoding='utf-8'))
    approved=BeautifulSoup(detail['content'],'html.parser')
    detail_grades=[a.get_text(' ',strip=True).removeprefix('View ') for a in approved.select('a[href^="/products/"]') if a.get_text(' ',strip=True).startswith('View ')]
    assert observed==detail_grades,(slug,'hub and detail grade mappings differ')
    for li in card.select('.gradeList li'):
        assert li.select_one('a')['href']=='/products/'+li['data-grade'].lower()+'/',li['data-grade']
special=soup.select_one('#application-specialty-materials')
assert special and special.select_one('a.applicationAction')['href']=='/products/cr-901/'
assert [li['data-grade'] for li in special.select('.gradeList li')]==['CR-901']
assert not soup.select_one('a[href="/applications/titanium-dioxide-for-specialty-materials/"]')
assert requests.get(base+'/products/cr-901/',timeout=20).status_code==200
graphs=[]
for tag in soup.select('script[type="application/ld+json"]'):
    item=json.loads(tag.string);graphs.extend(item.get('@graph',[item]))
assert any(x.get('@type')=='CollectionPage' for x in graphs)
assert any(x.get('@type')=='BreadcrumbList' for x in graphs)
entries=next(x['itemListElement'] for x in graphs if x.get('@type')=='ItemList')
assert [(i['name'],i['url']) for i in entries]==[(seed_label,base+path) for seed_label,path in [('Titanium Dioxide for '+n,p) for n,p in expected.values()]]
assert [s.get('id') for s in soup.select('main section[id]')]==['application-selector']
for label,path in [('Explore Products','/products/'),('Review Documents','/documents/'),('Explore Markets','/markets/'),('Request a Quote','/request-a-quote/')]:
    links=[a for a in soup.select('main a[href]') if a.get_text(' ',strip=True)==label]
    assert links and any(a['href']==path for a in links),label
    assert requests.get(base+path,timeout=20).status_code==200,path
out=root/'.local/applications-refinement';out.mkdir(parents=True,exist_ok=True)
with sync_playwright() as p:
    browser=p.chromium.launch(headless=True)
    for width in (1440,768,390):
        page=browser.new_page(viewport={'width':width,'height':900})
        errors=[];page.on('pageerror',lambda error:errors.append(str(error)))
        page.goto(base+'/applications/',wait_until='networkidle')
        assert page.locator('body').evaluate('(e)=>e.scrollWidth<=innerWidth'),width
        for card in page.locator('.applicationCard').all():
            assert card.evaluate('(e)=>e.scrollWidth<=e.clientWidth'),width
        page.locator('#application-selector').screenshot(path=str(out/f'cards-{width}.png'))
        page.screenshot(path=str(out/f'page-{width}.png'),full_page=True)
        assert not errors,errors
        page.close()
    browser.close()
print('PASS: five detail cards/factors, unchanged grade mappings, specialty route, schema/canonical, procurement links, 1440/768/390')
