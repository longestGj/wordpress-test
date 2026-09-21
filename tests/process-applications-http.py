"""End-to-end page contract; source adapter supplies independent frozen-source text."""
import json,re
from pathlib import Path
import requests
from bs4 import BeautifulSoup
ROOT=Path(__file__).resolve().parents[1]
for file in (ROOT/'data/process-applications').glob('*.json'):
 d=json.loads(file.read_text(encoding='utf-8'));source=ROOT/'.local/batch-source'/d['source']
 if source.exists():
  original=BeautifulSoup(source.read_text(encoding='utf-8-sig'),'html.parser').find('main')
  seed=BeautifulSoup(d['content'],'html.parser')
  assert [x['data-label'] for x in seed.select('[data-label]')]==[x['data-label'] for x in original.select('[data-label]')],(file.name,'Mobile table labels lost')
paths=['/products/chloride-process-titanium-dioxide/','/products/sulfate-process-titanium-dioxide/']+['/applications/titanium-dioxide-for-'+x+'/' for x in ['coatings','plastics','masterbatch','printing-inks','paper']]
seeds={d['url']:d for d in (json.loads(p.read_text(encoding='utf-8')) for p in (ROOT/'data/process-applications').glob('*.json'))}
normalize=lambda text: re.sub(r'\s+','',text)
for path in paths:
 r=requests.get('http://localhost:8080'+path,timeout=20)
 assert r.status_code==200,(path,r.status_code)
 s=BeautifulSoup(r.text,'html.parser')
 assert len(s.select('main'))==1 and len(s.select('h1'))==1,path
 assert 'noindex' in s.select_one('meta[name=robots]')['content']
 assert s.select_one('link[rel=canonical]')['href']=='http://localhost:8080'+path
 assert not s.select('main script:not([type="application/ld+json"])')
 d=seeds[path];expected=BeautifulSoup(d['content'],'html.parser');main=s.select_one('main')
 assert not main.select('.wp-block-group__inner-container'),(path,'Legacy group wrapper breaks approved grid/flex children')
 assert normalize(main.get_text())==normalize(expected.get_text()),(path,'Full visible copy changed')
 assert [x.get('data-label') for x in main.select('td')]==[x.get('data-label') for x in expected.select('td')],(path,'Mobile table labels differ')
 assert s.title.get_text()==d['seo_title'] and s.select_one('meta[name=description]')['content']==d['seo_description']
 assert len(s.select('meta[name=description]'))==1 and len(s.select('link[rel=canonical]'))==1
 if path.startswith('/products/'):
  alias=requests.get('http://localhost:8080/'+path.split('/')[-2]+'/',allow_redirects=False,timeout=20)
  assert alias.status_code==301 and alias.headers['Location']=='http://localhost:8080'+path,(path,'Process alias not canonicalized')
 for a in main.select('a[href]'):
  href=a['href']
  if href.startswith('#'):assert main.find(id=href[1:]),(path,'Missing anchor',href)
  elif href.startswith('/'):
   assert requests.get('http://localhost:8080'+href,timeout=20).status_code==200,(path,'Broken link',href)
 graphs=[json.loads(x.string) for x in s.select('script[type="application/ld+json"]')]
 assert not any(x.get('@type') in ('Product','Offer','FAQPage') for g in graphs if isinstance(g,dict) for x in g.get('@graph',[]))
 print('PASS',path)
