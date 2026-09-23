"""Build native editable Page seeds from the selected buyer copy. Build-only: pip install markdown beautifulsoup4."""
import csv, json, re, sys
from pathlib import Path
from bs4 import BeautifulSoup
ROOT = Path(__file__).resolve().parents[1]
sys.path.insert(0, str(ROOT / '.local/document-deps'))
import markdown
INPUT = ROOT / 'planning/inputs/pages/documents'
CONFIG = {
 'DOC-REACH': ('reach', 'DOC-REACH_GATE2_FULL_BUYER_CLEAN_COPY_V0.1.md', 11),
 'DOC-TDS': ('tds-sds-coa', 'DOC-TDS_GATE2_FULL_BUYER_CLEAN_COPY_V0.3.md', 10),
 'DOC-COO': ('certificate-of-origin', 'DOC-COO_GATE2_FULL_BUYER_CLEAN_COPY_V0.2.md', 6),
}
links = {'View Document Hub':'/documents/', 'Review EU REACH Information':'/documents/reach/', 'Review Origin Documentation':'/documents/certificate-of-origin/', 'Review the EU Market':'/markets/european-union/'}
def render(text):
 return markdown.markdown(text, extensions=['tables'])
def clean(text):
 text = re.sub(r'^\*\*(?:Breadcrumb|Eyebrow|H1|Hero support|Scope line|Primary CTA|Secondary CTA|Heading|Copy|Section introduction|Helper copy|Supplementary-context note|Request checklist|Request microcopy|Grade options)\*\*\s*$', '', text, flags=re.M)
 text = re.sub(r'^`Home`.*$', '', text, flags=re.M)
 text = re.sub(r'^\*\*(?:Selector label|Placeholder|Selection label|Request selection):\*\*.*$', '', text, flags=re.M)
 text = re.sub(r'^\*\*(?:Primary CTA|Secondary CTA|Link label|Note|Request note):\*\*\s*', '', text, flags=re.M)
 text = text.replace('`','')
 for label, url in links.items():
  text = re.sub(r'^'+re.escape(label)+r'\s*$', '['+label+']('+url+')', text, flags=re.M)
 text = re.sub(r'^(Request (?:REACH Documentation|TDS, SDS or COA))\s*$', r'[\1](/request-documents/)', text, flags=re.M)
 return text.strip()
for identity,(slug,filename,count) in CONFIG.items():
 source = INPUT / slug / '04_planning' / filename
 spec = (ROOT / 'planning/pages' / (identity+'.md')).read_text(encoding='utf-8')
 seo = {k:re.search('^'+field+r': (.+)$',spec,re.M).group(1) for k,field in [('seo_title','Title'),('seo_description','Meta'),('title','H1')]}
 text = source.read_text(encoding='utf-8')
 sections=[]
 if identity == 'DOC-COO':
  text=re.sub(r'<!--.*?-->','',text,flags=re.S)
  pieces=re.split(r'^## (.+)$',text,flags=re.M)
  sections=[render(pieces[0])]+[render('## '+pieces[i]+'\n'+pieces[i+1]) for i in range(1,len(pieces),2)]
 else:
  pieces=re.split(r'^## (\d+)\. (.+)$',text,flags=re.M)
  for i in range(1,len(pieces),3):
   n=int(pieces[i]); title=pieces[i+1]; body=pieces[i+2]
   if not 1<=n<=count: continue
   body=clean(body)
   if n==1:
    body=body.replace(seo['title'], '# '+seo['title'])
   elif title in ('Direct Answer','Final CTA'):
    body=re.sub(r'^([^\n]+)',r'## \1',body)
   else: body='## '+title+'\n\n'+body
   if identity=='DOC-TDS' and n==4:
    body=re.sub(r'^- (?:M-|CR-).+$','',body,flags=re.M)
    body+='\n\n[tio2_document_grades]'
   soup=BeautifulSoup(render(body),'html.parser')
   if title=='Questions Buyers Ask':
    for h in list(soup.find_all('h3')):
     detail=soup.new_tag('details');summary=soup.new_tag('summary');summary.string=h.get_text();detail.append(summary)
     sib=h.next_sibling
     while sib and getattr(sib,'name',None)!='h3':
      nxt=sib.next_sibling;detail.append(sib.extract());sib=nxt
     h.replace_with(detail)
   if identity=='DOC-TDS' and n==3:
    for h,value in zip(soup.find_all('h3')[:3], ['technical_product','safety','quality_coa']):
     label=soup.new_tag('label',attrs={'class':'document-choice'});box=soup.new_tag('input',attrs={'type':'checkbox','name':'document-types','value':value})
     label.append(box);label.append(' '+{'technical_product':'TDS','safety':'SDS','quality_coa':'COA'}[value]);h.insert_after(label)
   if identity=='DOC-REACH':
    for h in list(soup.find_all('h3')):
     if h.get_text().startswith('4. Submit'):
      panel=soup.new_tag('div',attrs={'data-requires-document-receiver':''})
      h.wrap(panel)
      while panel.next_sibling:
       panel.append(panel.next_sibling.extract())
    for para in soup.find_all('p'):
     if para.get_text(strip=True)=='Submission does not confirm document availability.':
      para['data-requires-document-receiver']=''
   grouped = (identity=='DOC-REACH' and n in (3,4,7)) or (identity=='DOC-TDS' and n in (3,7,9))
   if grouped:
    headings=list(soup.find_all('h3',recursive=False))
    if headings:
     grid=soup.new_tag('div',attrs={'class':'document-cards'})
     headings[0].insert_before(grid)
     for h in headings:
      card=soup.new_tag('article',attrs={'class':'document-card'})
      sib=h.next_sibling
      while sib and getattr(sib,'name',None)!='h3':
       nxt=sib.next_sibling;card.append(sib.extract());sib=nxt
      card.insert(0,h.extract());grid.append(card)
   sections.append(str(soup))
 content=''
 for n,html in enumerate(sections,1):
  soup=BeautifulSoup(html,'html.parser')
  for table in soup.select('table'):
   for th in table.select('thead th'):th['scope']='col'
   wrapper=soup.new_tag('div',attrs={'class':'document-table','role':'region','aria-label':'Document comparison','tabindex':'0'})
   table.wrap(wrapper)
  for a in soup.select('a[href="/request-documents/"]'):
   a['class']=['button','primary'];a['data-document-request']=''
  for a in soup.select('a[href="/documents/"]'): a['class']=['document-hub-link']
  if n==1 and identity!='DOC-COO':
   nav=soup.new_tag('nav',attrs={'class':'crumb','aria-label':'Breadcrumb'});nav.append(BeautifulSoup('<a href="/">Home</a> / <a href="/documents/">Documents</a>', 'html.parser'));soup.insert(0,nav)
  content+=f'<!-- wp:html -->\n<section class="document-section document-section-{n}" id="document-{n}">\n{soup}\n</section>\n<!-- /wp:html -->\n'
 seed={'identity':identity,'slug':slug,'path':'/documents/'+slug+'/',**seo,'content':content,'source':str(source.relative_to(ROOT)).replace('\\','/')}
 out=ROOT/'data/documents';out.mkdir(exist_ok=True)
 (out/(identity+'.json')).write_text(json.dumps(seed,ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
 print(identity, len(sections), 'modules')
