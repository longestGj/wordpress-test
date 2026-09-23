"""Read-only content/SEO/visibility contract for three request Page seeds."""
import json,re
from pathlib import Path
from bs4 import BeautifulSoup
root=Path(__file__).resolve().parents[1]
for kind,identity in [('quote','CONV-RFQ'),('documents','CONV-DOC'),('sample','CONV-SAMPLE')]:
 seed=json.loads((root/'data/requests'/f'{identity}.json').read_text(encoding='utf-8'))
 soup=BeautifulSoup(seed['content'],'html.parser')
 assert seed['kind']==kind and seed['path']=='/'+seed['slug']+'/'
 assert len(soup.select('h1'))==1
 assert soup.h1.get_text()=={'quote':'Request a Titanium Dioxide Quote','documents':'Request Documents','sample':'Request a Titanium Dioxide Sample for Technical Evaluation'}[kind]
 assert f'[tio2_request_form kind="{kind}"]' in seed['content']
 assert seed['seo_title'] and seed['seo_description']
 assert not soup.select('form,script,input,[download]')
 assert 'Web3Forms' not in seed['content'] and 'gmail' not in seed['content'].lower()
print('PASS: three native Page seeds, form bindings, headings and no external submission')
