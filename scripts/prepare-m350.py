"""One-time translation of approved Markdown into the product field structure."""
import json, re
from pathlib import Path
root = Path(__file__).resolve().parents[1]
copy = (root / 'planning/inputs/pages/products/detail-template/04_planning/GRADE-M350_GATE2_FULL_COPY_V0.1.md').read_text(encoding='utf-8')
parts = re.split(r'^### 2\.\d+ .*\n', copy, flags=re.M)[1:]
parts[-1] = parts[-1].split('\n## 3.')[0]
def clean(s): return s.replace('**','').strip()
def paragraphs(s): return [clean(x) for x in s.strip().split('\n\n') if x.strip() and not x.lstrip().startswith(('#','-','|','**'))]
def heading(s): return re.search(r'^## (.+)$', s, re.M)[1]
def bullets(s): return [clean(x) for x in re.findall(r'^- (.+)$', s, re.M)]
h,p,a,e,t,d,m,s = parts
hp = paragraphs(h)
data = dict(h1=re.search(r'^# (.+)$',h,re.M)[1], category='Coatings, Inks & Plastics', process_label='Chloride process', hero_paragraphs=hp[1:], summary_title='M-350 product data and evaluation', summary=bullets(h), quote_label='Request an M-350 Quote', sample_label='Request an M-350 Sample', positioning_title=heading(p), positioning_paragraphs=paragraphs(p), facts=bullets(p), process_link_label='Learn about chloride-process titanium dioxide', applications_title=heading(a), applications=[], evaluation_title=heading(e), evaluation=[], evaluation_note=paragraphs(e)[-1], technical_title=heading(t), technical_note=paragraphs(t)[0], tds_label='Request the M-350 TDS', rows=[], documents_title=heading(d), documents_paragraphs=paragraphs(d), documents_options=bullets(d), documents_label='Request M-350 Documents', markets_title=heading(m), markets_paragraphs=paragraphs(m), market_labels=bullets(m), sample_title=heading(s), sample_paragraphs=paragraphs(s), seo_title='M-350 Titanium Dioxide for Coatings and Inks | TiO2 Malaysia', seo_description='Evaluate M-350 titanium dioxide for coatings, printing inks and plastics. Review application directions, formulation priorities and 15-row technical data.', keyword='M-350 titanium dioxide', source_note='Approved Full Copy V0.1; TDS_M-350_V3_2023.pdf; Product Matrix V0.3. Source snapshot 765c66ed2b2d9d42cacab9009c7b17830cfdedf5. Internal only.')
for match in re.finditer(r'^#{3,4} ([^\n]+)\n\n(.*?)(?=\n#{3,4} |\Z)',a,re.M|re.S):
    title,body=match.groups()
    relation=['coatings','coatings','coatings','printing-inks','plastics','paper'][len(data['applications'])]
    data['applications'].append(dict(title=title,text=paragraphs(body)[0],relation=relation,qualified=(relation=='paper'),enabled=True))
data['application_link_labels']=bullets(a)
data['application_links']=[dict(relation=k,label=v) for k,v in zip(['coatings','printing-inks','plastics','paper'],data.pop('application_link_labels'))]
data['process_key']='chloride'
data['market_links']=[dict(relation=k,label=v) for k,v in zip(['eu','uk','india','brazil'],data.pop('market_labels'))]
for match in re.finditer(r'^### ([^\n]+)\n\n(.*?)(?=\n### |\Z)',e,re.M|re.S):
    data['evaluation'].append(dict(title=match[1],items=bullets(match[2])))
for line in t.splitlines():
    if line.startswith('|') and not line.startswith(('| Property','|---')):
        values=[x.strip() for x in line.strip('|').split('|')]
        data['rows'].append(dict(property=values[0],standard=values[1],typical_value=values[2],enabled=True))
assert len(data['rows'])==15 and len(data['applications'])==6
out=root/'data'; out.mkdir(exist_ok=True)
(out/'m350.json').write_text(json.dumps(dict(title='M-350',slug='m-350',excerpt=hp[0],applications=['Coatings','Printing Inks','Plastics','Paper'],process=['Chloride'],data=data),ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
print('Prepared M-350 import data; 15 technical rows, 6 application descriptions.')
