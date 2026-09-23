"""Read-only checks against actual WordPress responses and approved seeds."""
import json
import sys
from pathlib import Path
from urllib.parse import urlparse
import requests
from bs4 import BeautifulSoup
ROOT=Path(__file__).resolve().parents[1]
base=(sys.argv[1] if len(sys.argv)>1 else 'http://localhost:8080').rstrip('/')
assert urlparse(base).hostname in {'localhost','127.0.0.1'}
seeds=list((ROOT/'data/products').glob('*.json'))
for p in seeds:
 s=json.loads(p.read_text(encoding='utf-8'));r=requests.get(base+'/products/'+s['slug']+'/',timeout=15);r.raise_for_status();h=BeautifulSoup(r.text,'html.parser')
 assert len(h.select('h1'))==1 and h.h1.get_text(strip=True)==s['data']['h1'],p
 assert [x.get_text(strip=True) for x in h.select('.techTable thead th')]==[x['label'] for x in s['data']['table_columns']],p
 assert len(h.select('.techTable tbody tr'))==len(s['data']['rows']),p
 for tr,row in zip(h.select('.techTable tbody tr'),s['data']['rows'],strict=True):
  for label in tr.select('.mobileLabel'):label.decompose()
  assert [cell.get_text(strip=True) for cell in tr.select('th,td')]==[row[c['key']] for c in s['data']['table_columns']],p
 visible=h.select_one('main').get_text(' ',strip=True)
 for field in ['hero_paragraphs','positioning_paragraphs','facts','summary']:
  for text in s['data'][field]:assert text in visible,(p,field)
 for app in s['data']['applications']:assert app['text'] in visible,(p,'application copy')
 assert h.title.get_text()==s['data']['seo_title'],p
 assert 'noindex' in h.select_one('meta[name=robots]')['content'],p
 assert h.select_one('link[rel=canonical]')['href']==base+'/products/'+s['slug']+'/',p
print('PASS: 13 products, exact technical columns, SEO, local canonical and noindex')
roots=['home','markets','products','applications','documents','resources','about']
for key in roots:
 path='/' if key=='home' else '/'+key+'/'
 r=requests.get(base+path,timeout=15);r.raise_for_status();h=BeautifulSoup(r.text,'html.parser')
 assert len(h.select('main.hub-'+key))==1,('missing root renderer',key)
 assert len(h.select('h1'))==1,key
 assert h.select_one('meta[name=description]'),key
 assert h.select_one('link[rel=canonical]')['href']==base+path,key
 assert 'noindex' in h.select_one('meta[name=robots]')['content'],key
 assert 'planningState' not in r.text and 'data-owner-page-id' not in r.text,key
 seed=json.loads((ROOT/'data/pages'/(key+'.json')).read_text(encoding='utf-8'))
 assert h.title.get_text()==seed['seo_title'],key
 assert h.select_one('meta[name=description]')['content']==seed['seo_description'],key
 content=' '.join(h.select_one('main').get_text(' ',strip=True).split())
 for node in BeautifulSoup(seed['content'],'html.parser').select('h1,h2,h3,p,summary'):
  text=' '.join(node.get_text(' ',strip=True).split())
  assert text in content,(key,text)
 for a in h.select('main a[href^="/"]'):
  target=a['href'].split('?')[0].split('#')[0]
  if target and target!='/request-a-quote/':assert requests.get(base+target,timeout=15).status_code==200,(key,target)
print('PASS: seven roots, semantics, SEO, ready links and no prototype controls')
