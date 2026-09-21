"""Adapt approved visual content into editable WP content, with scoped styles.

Deliberately exclude prototype JS, Chrome, simulations and governance attributes.
"""
import csv,json,re,shutil,urllib.parse,urllib.request,hashlib
from pathlib import Path
from bs4 import BeautifulSoup,Comment
import tinycss2
ROOT=Path(__file__).resolve().parents[1];SOURCE=ROOT/'.local/batch-source'
ASSETS=ROOT/'wp-content/themes/tio2/assets';OUT=ROOT/'data/pages';OUT.mkdir(exist_ok=True)
PAGES={
 'home':('HOME-001','pages/home/04_planning/visual-designs/home-root-page-hero-v1.4/homepage-root-page-hero-preview-v1.4.html'),
 'products':('PRODUCT-000','pages/products/04_planning/d32-gate4-v0.1/product-visual.html'),
 'applications':('APP-000','pages/applications/04_planning/d32-gate4-v0.1/application-visual.html'),
 'markets':('MARKET-000','pages/markets/04_planning/d32-gate4-v0.3/market-visual.html'),
 'documents':('DOC-000','pages/documents/04_planning/d32-gate4-v0.2/DOC-000_D32_structure.html'),
 'resources':('RES-000','pages/resources/04_planning/d32-gate4-v0.2/RES-000_D32_GATE4.html'),
 'about':('ABOUT-001','pages/about-contact/04_visual/d32-gate4-v0.1/ABOUT-001_D32_GATE4.html')}
seo={r['page_id']:r for r in csv.DictReader((SOURCE/'docs/architecture/TIO2_MY_57_INDEXABLE_PAGE_SEO_DELIVERY_V1.0.csv').open(encoding='utf-8-sig'))}

def scope_css(text,key):
    scope='.hub-'+key
    def rules(nodes):
        out=[]
        for rule in nodes:
            if rule.type=='qualified-rule':
                selectors=tinycss2.serialize(rule.prelude).strip()
                # Split only top-level commas; :is(a,button) is a single token.
                parts=[[]]
                for token in rule.prelude:
                    if token.type=='literal' and token.value==',':parts.append([])
                    else:parts[-1].append(token)
                scoped=[]
                for part in parts:
                    s=tinycss2.serialize(part)
                    s=s.strip()
                    if not s:continue
                    if key=='resources' and re.match(r'^\.res-main(?=[\s.#:\[]|$)',s):
                        s=scope+s
                    elif re.match(r'^(?:html|body|:root|main)(?=[\s.#:\[]|$)',s):
                        s=re.sub(r'^(?:html|body|:root|main)',scope,s)
                    else:s=scope+' '+s
                    scoped.append(s)
                out.append(','.join(scoped)+'{'+tinycss2.serialize(rule.content)+'}')
            elif rule.type=='at-rule' and rule.lower_at_keyword in ('media','supports'):
                out.append('@'+rule.lower_at_keyword+' '+tinycss2.serialize(rule.prelude)+'{'+rules(tinycss2.parse_rule_list(rule.content))+'}')
        return '\n'.join(out)
    return rules(tinycss2.parse_stylesheet(text,skip_comments=True,skip_whitespace=True))

for key,(identity,path) in PAGES.items():
    src=SOURCE/path;soup=BeautifulSoup(src.read_text(encoding='utf-8-sig'),'html.parser');main=soup.find('main')
    styles=[]
    for el in soup.select('style,link[rel=stylesheet]'):
        if el.name=='style':styles.append(el.get_text())
        else:
            p=SOURCE/urllib.parse.urljoin(path,el['href'])
            if p.exists():styles.append(p.read_text(encoding='utf-8-sig'))
    # Remove duplicate fallback grade labels. Runtime readiness is handled in PHP.
    for el in main.select('[data-grade-plain],script,style') :el.decompose()
    for el in main.find_all(string=lambda t:isinstance(t,Comment)):el.extract()
    # Native details preserve all FAQ answers in initial HTML and work without JS.
    for button in list(main.select('button[aria-controls]')):
        if button.get('id')=='eu-toggle':continue
        answer=main.find(id=button['aria-controls'])
        if not answer:continue
        parent=button.parent.parent
        if answer.parent!=parent:continue
        details=soup.new_tag('details',attrs={'class':'wp-faq'})
        summary=soup.new_tag('summary')
        for badge in button.select('span'):badge.decompose()
        summary.string=button.get_text(' ',strip=True);details.append(summary)
        answer.attrs.pop('hidden',None);answer.attrs.pop('aria-labelledby',None);answer.attrs.pop('role',None)
        details.append(answer.extract());parent.replace_with(details)
    # Inline SVG paint styles before extraction: an image cannot inherit its parent's CSS.
    paint={'fill','stroke','stroke-width','stroke-linecap','stroke-linejoin','stroke-dasharray','opacity','fill-opacity','stroke-opacity','font','font-family','font-size','font-weight','letter-spacing','text-anchor'}
    for rule in tinycss2.parse_stylesheet('\n'.join(styles),skip_comments=True,skip_whitespace=True):
        if rule.type!='qualified-rule':continue
        declarations=[x for x in tinycss2.parse_declaration_list(rule.content) if x.type=='declaration' and x.lower_name in paint]
        if not declarations:continue
        try:matches=soup.select(tinycss2.serialize(rule.prelude))
        except Exception:continue
        for element in matches:
            if element.name=='svg' or element.find_parent('svg'):
                element['style']=element.get('style','')+';'+''.join(x.name+':'+tinycss2.serialize(x.value)+';' for x in declarations)
    # Decorative SVG stays an approved local asset, outside editable copy markup.
    for i,svg in enumerate(list(main.select('svg'))):
        embedded=svg.find('image')
        if embedded:
            relative=urllib.parse.urljoin(path,embedded.get('href',embedded.get('xlink:href','')))
            raster=SOURCE/relative
            if not raster.exists():
                raster.parent.mkdir(parents=True,exist_ok=True)
                raster.write_bytes(urllib.request.urlopen('https://raw.githubusercontent.com/longestGj/tio2mydesign/765c66ed2b2d9d42cacab9009c7b17830cfdedf5/'+relative).read())
            shutil.copy2(raster,ASSETS/raster.name)
            x,y,w,h=map(float,svg['viewbox'].split());iw=float(embedded['width']);ih=float(embedded['height'])
            crop=soup.new_tag('span',attrs={'class':svg.get('class',[])+['asset-crop'],'style':f'aspect-ratio:{w}/{h};'})
            if svg.get('aria-label'):crop['role']='img';crop['aria-label']=svg['aria-label']
            else:crop['aria-hidden']='true'
            crop.append(soup.new_tag('img',src='/wp-content/themes/tio2/assets/'+raster.name,alt='',loading='lazy',style=f'position:absolute;max-width:none;width:{iw/w*100}%;height:{ih/h*100}%;left:{-x/w*100}%;top:{-y/h*100}%;'))
            svg.replace_with(crop);continue
        markup=str(svg).replace('viewbox=','viewBox=').replace('preserveaspectratio=','preserveAspectRatio=')
        if 'xmlns=' not in markup:markup=markup.replace('<svg','<svg xmlns="http://www.w3.org/2000/svg"',1)
        name=f'{key}-decoration-{i}.svg';(ASSETS/name).write_text(markup,encoding='utf-8')
        image=soup.new_tag('img',src='/wp-content/themes/tio2/assets/'+name,alt='')
        if svg.get('class'):image['class']=svg['class']
        image['loading']='lazy';svg.replace_with(image)
    for img in main.select('img[src]'):
        if img['src'].startswith('/wp-content'):continue
        p=SOURCE/urllib.parse.urljoin(path,img['src'])
        if p.exists():shutil.copy2(p,ASSETS/p.name);img['src']='/wp-content/themes/tio2/assets/'+p.name
        else:raise ValueError('Unresolved asset '+str(p))
    for node in main.find_all(True):
        for attr in list(node.attrs):
            if attr.startswith('on') or (attr.startswith('data-') and attr not in ['data-app','data-grade','data-component','data-variant']):del node[attr]
    # Set scope and skip-link identity consistently.
    main['id']='main';main['tabindex']='-1';main['class']=main.get('class',[])+['hub','hub-'+key]
    if key=='products':
        discovery=json.loads((SOURCE/'product-discovery.json').read_text(encoding='utf-8'))
        results=main.select_one('#results');results.clear();results.append('[tio2_grade_results]')
        directory=main.select_one('.directory');directory.clear();directory.append('[tio2_grade_directory]')
        for r in discovery['rows']:r.pop('pageId',None)
        (ROOT/'data/product-discovery.json').write_text(json.dumps(discovery,ensure_ascii=False,indent=2),encoding='utf-8')
    if key=='documents':
        main.select_one('#continue-request')['data-document-continue']=''
        main.select_one('#closing-action')['data-document-continue']=''
    # Preserve source layout classes on the real main element; content itself is editable.
    content=''.join(str(c) for c in main.contents)
    row=seo[identity]
    seed={'key':key,'title':key.title(),'slug':'product-hub-content' if key=='products' else key,'source':path,'main_class':' '.join(main['class']),'content':'<!-- wp:html -->\n'+content+'\n<!-- /wp:html -->','seo_title':row['title'],'seo_description':row['meta_description'],'h1':main.h1.get_text(' ',strip=True)}
    previous=OUT/(key+'.json')
    if previous.exists():seed['previous_content_hash']=hashlib.sha256(json.loads(previous.read_text(encoding='utf-8'))['content'].encode()).hexdigest()
    (OUT/(key+'.json')).write_text(json.dumps(seed,ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
    (ASSETS/('hub-'+key+'.css')).write_text('/* Approved visual adapted and scoped to this WordPress page. */\n'+scope_css('\n'.join(styles),key),encoding='utf-8')
print('Prepared seven editable root page seeds and scoped styles.')
