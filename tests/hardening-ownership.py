"""Exercise importer collision in a separate CLI process; restore markers/options."""
import json
import subprocess

def wp(*args,check=True):
    p=subprocess.run(['docker','compose','run','--rm','cli',*args],capture_output=True,text=True)
    if check and p.returncode: raise RuntimeError(p.stdout+p.stderr)
    return p

snapshot=wp('eval',"$p=get_page_by_path('resources'); echo json_encode(['id'=>$p->ID,'key'=>get_post_meta($p->ID,'_tio2_hub_key',true),'source'=>get_post_meta($p->ID,'_tio2_source',true),'front'=>get_option('show_on_front'),'home'=>get_option('page_on_front'),'content'=>$p->post_content]);").stdout
state=json.loads(snapshot)
try:
    for mutation in ["delete_post_meta($p->ID,'_tio2_source');", "update_post_meta($p->ID,'_tio2_hub_key','foreign');"]:
        wp('eval',"$s=json_decode("+json.dumps(snapshot)+",true); $p=get_post($s['id']); update_post_meta($p->ID,'_tio2_source',$s['source']); "+mutation+" update_option('show_on_front','posts');")
        result=wp('eval-file','/workspace/scripts/import-pages.php',check=False)
        assert result.returncode!=0 and 'ownership' in (result.stdout+result.stderr).lower(),'Importer accepted foreign page'
        current=json.loads(wp('eval',"$p=get_page_by_path('resources'); echo json_encode(['front'=>get_option('show_on_front'),'content'=>$p->post_content]);").stdout)
        assert current['front']=='posts' and current['content']==state['content'],'Importer wrote before detecting collision'
finally:
    wp('eval',"$s=json_decode("+json.dumps(snapshot)+",true); update_post_meta($s['id'],'_tio2_hub_key',$s['key']); update_post_meta($s['id'],'_tio2_source',$s['source']); update_option('show_on_front',$s['front']); update_option('page_on_front',$s['home']);")
result=wp('eval-file','/workspace/scripts/import-pages.php')
assert result.stdout.count('Preserved ')==7
print('PASS: foreign/mismatched ownership stops before writes; seven owned pages preserved; original state restored.')
