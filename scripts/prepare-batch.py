"""One-time adapters from pinned approved planning content to native WP seeds.

No source scripts or source runtime are used by WordPress.
"""
import copy
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
SOURCE = ROOT / 'planning/inputs'
APP = {'APP-COAT':'coatings','APP-PLAS':'plastics','APP-MB':'masterbatch','APP-INK':'printing-inks','APP-PAPER':'paper'}

def prepare_products():
    selected = {}
    for p in sorted((SOURCE/'pages/products/detail-template/06_handoff').glob('*CONTENT_CONTRACT*.json')):
        c=json.loads(p.read_text(encoding='utf-8-sig'));selected[c['identity']['slug']] = (p,c)
    out=ROOT/'data/products';out.mkdir(exist_ok=True)
    for slug,(p,c) in selected.items():
        d=copy.deepcopy(json.loads((ROOT/'wp-content/plugins/tio2-products/blank.json').read_text(encoding='utf-8')))
        h=c['hero'];pos=c['positioning'];tech=c['technical'];ev=c['evaluation'];docs=c['documents'];markets=c['markets'];sample=c['sample']
        process=pos.get('contextualLink',{}).get('targetPageId','')
        process={'PRODUCT-PROC-CL':'chloride','PRODUCT-PROC-SU':'sulfate'}.get(process,'')
        d.update(h1=c['seo']['h1'],category=h['eyebrow'].split(' · ')[0],process_key=process,process_label=next((f['value'] for f in h['facts'] if f['label'].lower()=='process'),''),hero_paragraphs=[h['summaryBody']],summary_title=h.get('visual',{}).get('label',c['identity']['gradeCode']),summary=[f"{f['label']}: {f['value']}" for f in h['facts']],quote_label=h['actions'][0]['label'],sample_label=sample['actionLabel'],positioning_title=pos['heading'],positioning_paragraphs=[pos['lead'],pos['body']],facts=pos['decisionPoints'],process_link_label=pos.get('contextualLink',{}).get('label',''),applications_title=c['applications']['heading'],applications_intro=c['applications'].get('intro',''),evaluation_title=ev['heading'],evaluation_intro=ev.get('intro',''),evaluation=[{'title':g['heading'],'items':g['items']} for g in ev['groups']],evaluation_note=ev['disclaimer'],technical_title=tech['heading'],technical_intro=tech.get('intro',''),technical_note=tech['note'],tds_label=tech['action']['label'],documents_title=docs['heading'],documents_paragraphs=[docs['intro'],docs['availability']],documents_options=[x['title']+': '+x['body'] for x in docs['options']],documents_label=docs['actionLabel'],markets_title=markets['heading'],markets_paragraphs=[markets['intro'],markets['note']],market_links=[{'relation':key,'label':x['label']} for key,x in zip(['eu','uk','india','brazil'],markets['items'])],sample_title=sample['heading'],sample_paragraphs=[sample['body']],seo_title=c['seo']['title'],seo_description=c['seo']['description'],keyword=c['seo']['primaryKeyword'],source_note=p.relative_to(SOURCE).as_posix())
        d['applications']=[];rels=[];links={}
        for a in c['applications']['items']:
            targets=a.get('relatedTargets') or [{'targetPageId':a.get('targetPageId','')}]
            relations=[APP.get(t['targetPageId'],'specialty') for t in targets]
            # A combined application card has one primary relation; retain both assigned terms.
            rels.extend(relations)
            d['applications'].append({'title':a['title'],'text':a['body'],'relation':relations[0],'qualified':False,'enabled':True})
            for rel in relations:
                if rel!='specialty':links[rel]={'relation':rel,'label':rel.replace('-',' ').title()}
        d['application_links']=list(links.values())
        d['rows']=[{'property':r['property'],'standard':r.get('standard','—'),'typical_value':r.get('typical',r.get('value','—')),'test_method':r.get('testMethod','—'),'enabled':True} for r in tech['rows']]
        keys=['property']+(['standard','typical_value'] if any('standard' in r for r in tech['rows']) else ['typical_value']+(['test_method'] if len(tech['columns'])==3 else []))
        d['table_columns']=[{'key':k,'label':label} for k,label in zip(keys,tech['columns'])]
        seed={'title':c['identity']['gradeCode'],'slug':slug,'excerpt':h['summaryLead'],'applications':sorted(set(rels)),'process':process,'data':d}
        (out/(slug+'.json')).write_text(json.dumps(seed,ensure_ascii=False,indent=2)+'\n',encoding='utf-8')
    print('Prepared',len(selected),'product seeds.')

if __name__=='__main__':prepare_products()
