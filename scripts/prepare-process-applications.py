"""One-time adapter for seven explicitly approved visuals, never a runtime CMS."""
import json,re,urllib.parse
from pathlib import Path
from bs4 import BeautifulSoup,Comment
import tinycss2
ROOT=Path(__file__).resolve().parents[1]; SOURCE=ROOT/'.local/batch-source'
MAP={
 'chloride':('PRODUCT-PROC-CL','pages/products/chloride-process/04_planning/gate5-v0.1/source/index.html'),
 'sulfate':('PRODUCT-PROC-SU','pages/products/sulfate-process/04_planning/gate4-v0.1/PRODUCT-PROC-SU_GATE4_COMPLETE_VISUAL_V0.1.html'),
 'coatings':('APP-COAT','pages/applications/coatings/04_planning/gate4-v0.1/APP-COAT_GATE4_COMPLETE_VISUAL_V0.1.html'),
 'plastics':('APP-PLAS','pages/applications/plastics/04_planning/gate4-v0.1/APP-PLAS_GATE4_COMPLETE_VISUAL_V0.1.html'),
 'masterbatch':('APP-MB','pages/applications/masterbatch/04_planning/gate4-v0.1/APP-MB_GATE4_EDITABLE_SOURCE_V0.1.html'),
 'printing-inks':('APP-INK','pages/applications/printing-inks/04_planning/gate4-v0.1/APP-INK_GATE4_COMPLETE_VISUAL_V0.1.html'),
 'paper':('APP-PAPER','pages/applications/paper/04_planning/gate4-v0.1/APP-PAPER_GATE4_COMPLETE_VISUAL_V0.1.html')}
seo={x['page_id']:x for x in json.loads((SOURCE/'remaining-inventory.json').read_text(encoding='utf-8'))}

def scoped(css,scope):
 def walk(nodes):
  output=[]
  for r in nodes:
   if r.type=='qualified-rule':
    parts=[[]]
    for t in r.prelude:
     if t.type=='literal' and t.value==',':parts.append([])
     else:parts[-1].append(t)
    selectors=[]
    for p in parts:
     s=tinycss2.serialize(p).strip()
     if re.match(r'^(html|body|:root|main)(?=[\s.#:\[]|$)',s):s=re.sub(r'^(html|body|:root|main)',scope,s)
     else:s=scope+' '+s
     selectors.append(s)
    output.append(','.join(selectors)+'{'+tinycss2.serialize(r.content)+'}')
   elif r.type=='at-rule' and r.lower_at_keyword in ('media','supports'):
    output.append('@'+r.lower_at_keyword+' '+tinycss2.serialize(r.prelude)+'{'+walk(tinycss2.parse_rule_list(r.content))+'}')
  return '\n'.join(output)
 return walk(tinycss2.parse_stylesheet(css,skip_whitespace=True,skip_comments=True))

def block(node):
 if not getattr(node,'name',None):return str(node) if str(node).strip() else ''
 attrs={};classes=node.get('class',[])
 if node.name=='article' and node.get('aria-labelledby'):
  heading=node.find(id=node['aria-labelledby'])
  if heading:
   node['aria-label']=heading.get_text(' ',strip=True);del node['aria-labelledby']
 if classes:attrs['className']=' '.join(classes)
 if node.get('id'):attrs['anchor']=node['id']
 simple=set(node.attrs).issubset({'class','id','aria-label'} if node.name in ('div','section','article') else {'class','id'})
 kind=None
 if simple and node.name=='p':kind='paragraph'
 elif simple and re.fullmatch('h[1-6]',node.name):
  kind='heading';attrs['level']=int(node.name[1]);node['class']=['wp-block-heading']+classes
 elif simple and node.name in ('div','section','article') and not any(not getattr(c,'name',None) and str(c).strip() for c in node.contents):
  kind='group';attrs['tagName']=node.name;node['class']=['wp-block-group']+classes
  if node.get('aria-label'):attrs['ariaLabel']=node['aria-label']
  inner=''.join(block(c) for c in list(node.contents))
  opening=str(node).split('>',1)[0]+'>'
  return '<!-- wp:group '+json.dumps(attrs)+' -->'+opening+inner+'</'+node.name+'><!-- /wp:group -->'
 if kind:return '<!-- wp:'+kind+' '+json.dumps(attrs)+' -->'+str(node)+'<!-- /wp:'+kind+' -->'
 return '<!-- wp:html -->'+str(node)+'<!-- /wp:html -->'

out=ROOT/'data/process-applications';out.mkdir(exist_ok=True)
for key,(identity,path) in MAP.items():
 soup=BeautifulSoup((SOURCE/path).read_text(encoding='utf-8-sig'),'html.parser');main=soup.find('main');assert main and main.h1
 styles=[]
 for el in soup.select('style,link[rel=stylesheet]'):
  styles.append(el.get_text() if el.name=='style' else (SOURCE/urllib.parse.urljoin(path,el['href'])).read_text(encoding='utf-8-sig'))
 for el in main.select('script,style'):el.decompose()
 for c in main.find_all(string=lambda x:isinstance(x,Comment)):c.extract()
 for el in main.find_all(True):
  if el.get('data-module'):
   el['class']=el.get('class',[])+['topic-module-'+el['data-module']]
  for a in list(el.attrs):
   if a.startswith('on') or (a.startswith('data-') and a!='data-label'):del el[a]
  if el.name=='a' and el.get('href','').startswith('#'):
   target=main.find(id=el['href'][1:])
   if target:target['tabindex']='-1'
 assert not main.select('img,svg,form,button'),identity+' requires explicit component adaptation'
 row=seo[identity]
 seed={'key':key,'page_id':identity,'url':row['url'],'title':main.h1.get_text(' ',strip=True),'seo_title':row['title'],'seo_description':row['meta_description'],'source':path,'main_class':' '.join(main.get('class',[])+['topic-page','topic-'+key]),'expected_text':main.get_text(' ',strip=True),'content':'\n'.join(block(c) for c in list(main.contents))}
 (out/(key+'.json')).write_text(json.dumps(seed,ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
 css=scoped(re.sub(r'\[data-module=["\x27]([^"\x27]+)["\x27]\]',r'.topic-module-\1','\n'.join(styles)),'.topic-'+key)
 (ROOT/'wp-content/themes/tio2/assets'/('topic-'+key+'.css')).write_text(css,encoding='utf-8')
 print(identity,len(seed['content']),len(css))
