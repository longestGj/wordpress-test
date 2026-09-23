"""Exercise real WordPress editor submission and front-end/Schema parity locally."""
import json, re
from pathlib import Path
import requests
from bs4 import BeautifulSoup
root=Path(__file__).resolve().parents[1]
seed=json.loads((root/'data/m350.json').read_text(encoding='utf-8'))
base='http://localhost:8080'
def page():
    r=requests.get(base+'/products/m-350/',timeout=20);r.raise_for_status()
    return BeautifulSoup(r.text,'html.parser')
def schema(soup):
    value=json.loads(soup.select_one('script[type="application/ld+json"]').string)
    nodes=value if isinstance(value,list) else value.get('@graph',[value])
    return next(node for node in nodes if node.get('@type')=='Product')
soup=page()
assert len(soup.select('h1'))==1
assert soup.title.string==seed['data']['seo_title']
assert soup.select_one('meta[name=description]')['content']==seed['data']['seo_description']
assert soup.select_one('link[rel=canonical]')['href']==base+'/products/m-350/'
assert 'noindex' in soup.select_one('meta[name=robots]')['content']
for tr,row in zip(soup.select('.techTable tbody tr'),seed['data']['rows'],strict=True):
    cells=tr.select('th,td')
    for label in tr.select('.mobileLabel'):label.decompose()
    assert [x.get_text(strip=True) for x in cells]==[row['property'],row['standard'],row['typical_value']]
assert len(schema(soup)['additionalProperty'])==15
assert not soup.select('main a[href*="request-"]')
assert 'TDS_M-350_V3' not in str(soup) and 'V3 2023' not in str(soup)
# Every nonconditional approved prose field should survive the import/render path.
text=soup.select_one('main').get_text(' ',strip=True)
for key in ['hero_paragraphs','positioning_paragraphs','facts','summary']:
    for value in seed['data'][key]: assert value in text,(key,value)
for app in seed['data']['applications']:assert app['text'] in text
for group in seed['data']['evaluation']:
    for value in group['items']:assert value in text
assert requests.get(base+'/products/',timeout=20).status_code==200
session=requests.Session()
credentials=dict(line.split('=',1) for line in (root/'.env').read_text(encoding='utf-8-sig').splitlines() if '=' in line)
session.get(base+'/wp-login.php',timeout=20)
response=session.post(base+'/wp-login.php',data={'log':credentials['WP_ADMIN_USER'],'pwd':credentials['WP_ADMIN_PASSWORD'],'testcookie':'1','redirect_to':base+'/wp-admin/'},timeout=20)
assert 'wpadminbar' in response.text,'Admin login failed'
listing=BeautifulSoup(session.get(base+'/wp-admin/edit.php?post_type=product',timeout=20).text,'html.parser')
link=next(a for a in listing.select('a.row-title') if a.get_text(strip=True)=='M-350')
edit_url=link['href']
editor=BeautifulSoup(session.get(edit_url,timeout=20).text,'html.parser')
form=editor.select_one('form#post')
payload=[]
for field in form.select('input[name],textarea[name],select[name]'):
    if field.has_attr('disabled') or field.get('type') in ['submit','button','file','reset']:continue
    if field.get('type') in ['checkbox','radio'] and not field.has_attr('checked'):continue
    if field.name=='textarea':value=field.get_text()
    elif field.name=='select':
        selected=field.select('option[selected]') or field.select('option')[:1]
        for option in selected:payload.append((field['name'],option.get('value',option.get_text())))
        continue
    else:value=field.get('value','')
    payload.append((field['name'],value))
target='tio2[rows][0][typical_value]'
assert any(k==target and v=='93.5' for k,v in payload)
try:
    changed=[(k,'93.6' if k==target else v) for k,v in payload]
    response=session.post(base+'/wp-admin/post.php',data=changed,timeout=30);response.raise_for_status()
    updated=page()
    assert '93.6' in updated.select('.techTable tbody tr')[0].get_text()
    assert '93.6' in schema(updated)['additionalProperty'][0]['value']
finally:
    response=session.post(base+'/wp-admin/post.php',data=payload,timeout=30);response.raise_for_status()
assert '93.5' in schema(page())['additionalProperty'][0]['value']
print('PASS: approved copy rendering, exact 15-row table, SEO, hidden unavailable actions, admin form edit -> frontend + Schema -> restored approved value.')
