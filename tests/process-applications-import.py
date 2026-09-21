"""Run serially in the local DB window. Preserve edits and reject public-path collision."""
import json,subprocess
def wp(*args,check=True):
 p=subprocess.run(['docker','compose','run','--rm','cli',*args],capture_output=True,text=True,encoding='utf-8')
 if check and p.returncode:raise RuntimeError(p.stdout+p.stderr)
 return p
wp('eval-file','/workspace/tests/local-only.php')

snapshot=wp('eval',"$p=get_page_by_path('applications/titanium-dioxide-for-coatings'); echo json_encode(['id'=>$p->ID,'content'=>$p->post_content,'source'=>get_post_meta($p->ID,'_tio2_page_id',true),'targets'=>get_option('tio2_targets')]);").stdout
s=json.loads(snapshot);parent=child=None
try:
 wp('eval',f"wp_update_post(['ID'=>{s['id']},'post_content'=>'<!-- wp:paragraph --><p>Editor preservation test.</p><!-- /wp:paragraph -->']);")
 wp('eval-file','/workspace/scripts/import-process-applications.php')
 assert 'Editor preservation test.' in wp('post','get',str(s['id']),'--field=post_content').stdout
 wp('post','meta','delete',str(s['id']),'_tio2_page_id')
 result=wp('eval-file','/workspace/scripts/import-process-applications.php',check=False)
 assert result.returncode and 'ownership collision' in result.stderr
 wp('post','meta','update',str(s['id']),'_tio2_page_id',s['source'])
 # A native /products/<process>/ Page must not be silently shadowed.
 existing=wp('eval',"echo get_page_by_path('products',OBJECT,'page')?'occupied':'free';").stdout.strip()
 assert existing=='free','Test requires unoccupied structural parent'
 parent=wp('post','create','--post_type=page','--post_status=draft','--post_name=products','--post_title=Collision test parent','--porcelain').stdout.strip()
 child=wp('post','create','--post_type=page','--post_status=draft','--post_name=chloride-process-titanium-dioxide','--post_title=Collision test child','--post_parent='+parent,'--porcelain').stdout.strip()
 result=wp('eval-file','/workspace/scripts/import-process-applications.php',check=False)
 assert result.returncode and 'public process path' in result.stderr
finally:
 if child:wp('post','delete',child,'--force')
 if parent:wp('post','delete',parent,'--force')
 wp('eval',"$s=json_decode("+json.dumps(snapshot)+",true); wp_update_post(['ID'=>$s['id'],'post_content'=>wp_slash($s['content'])]); update_post_meta($s['id'],'_tio2_page_id',$s['source']);")
assert json.loads(wp('option','get','tio2_targets','--format=json').stdout)==s['targets']
print('PASS: repeat import preserves edits; ownership/public-path collisions rejected; all test state restored.')
