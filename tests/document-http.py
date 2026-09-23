"""Read-only actual WordPress HTTP regression; run after local import."""
import json
from pathlib import Path
import requests
from bs4 import BeautifulSoup
root=Path(__file__).resolve().parents[1]
base='http://localhost:8080'
for file in sorted((root/'data/documents').glob('*.json')):
    seed=json.loads(file.read_text(encoding='utf-8'))
    response=requests.get(base+seed['path'],timeout=8);response.raise_for_status()
    soup=BeautifulSoup(response.text,'html.parser')
    assert len(soup.select('h1'))==1 and soup.h1.get_text()==seed['title']
    assert soup.title.get_text()==seed['seo_title']
    assert len(soup.select('meta[name=description]'))==1
    assert soup.select_one('meta[name=description]')['content']==seed['seo_description']
    assert soup.select_one('link[rel=canonical]')['href']==base+seed['path']
    assert 'noindex' in soup.select_one('meta[name=robots]')['content']
    assert not soup.select('main [download], main a[aria-disabled]')
    for a in soup.select('main a[href]'):
        href=a['href']
        if href.startswith(base):
            linked=requests.get(href,timeout=8);assert linked.status_code==200,(seed['identity'],href)
    print('PASS HTTP:',seed['identity'])
