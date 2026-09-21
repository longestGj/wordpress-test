<?php
// Isolated records only. No approved product is modified.
if (!in_array(wp_parse_url(home_url(), PHP_URL_HOST), ['localhost','127.0.0.1','[::1]'], true)) WP_CLI::error('Local test only');
wp_set_current_user(1);
$failures=[];
$check=function($ok,$message)use(&$failures){if(!$ok)$failures[]=$message;};
$seed=json_decode(file_get_contents('/workspace/data/m350.json'),true)['data'];
foreach(['seo_title','seo_description','applications_title','technical_title'] as $field){$d=$seed;$d[$field]=' ';$check(is_wp_error(tio2_validate_product($d)), 'Blank accepted: '.$field);}
$d=$seed;$d['applications'][0]['text']='';$check(is_wp_error(tio2_validate_product($d)), 'Enabled empty application accepted');
$d['applications'][0]['enabled']=false;$check(!is_wp_error(tio2_validate_product($d)), 'Disabled application draft rejected');
$id=wp_insert_post(['post_type'=>'product','post_status'=>'draft','post_title'=>'Integrity test A','meta_input'=>['_tio2_product'=>$seed]]);
$page=wp_insert_post(['post_type'=>'page','post_status'=>'draft','post_title'=>'SEO test A']);
$terms=[];
try {
    foreach(['a','b'] as $suffix){$t=wp_insert_term('Integrity '.$suffix,'product_application',['slug'=>'integrity-'.$suffix]);$terms[]=(int)$t['term_id'];}
    wp_set_object_terms($id,[$terms[0]],'product_application');
    wp_set_object_terms($id,['chloride'],'product_process');
    wp_update_post(['ID'=>$id,'post_title'=>'Integrity A']);
    $revisions=wp_get_post_revisions($id);$a=reset($revisions);
    wp_set_object_terms($id,[$terms[1]],'product_application');
    wp_set_object_terms($id,['sulfate'],'product_process');
    // No post field or product meta changes: terms alone must produce a revision on save.
    wp_update_post(['ID'=>$id]);
    $revisions=wp_get_post_revisions($id);$b=reset($revisions);
    $check($a->ID!==$b->ID,'Term-only save has no revision');
    wp_update_term($terms[0],'product_application',['slug'=>'integrity-renamed']);
    wp_restore_post_revision($a->ID);
    $check(wp_get_object_terms($id,'product_application',['fields'=>'ids'])===[$terms[0]],'Application revision failed after term rename');
    $check(has_term('chloride','product_process',$id),'Process revision failed');
    // A historical revision without snapshot must leave assignments unchanged.
    delete_metadata('post',$b->ID,'_tio2_term_snapshot');
    wp_restore_post_revision($b->ID);
    $check(wp_get_object_terms($id,'product_application',['fields'=>'ids'])===[$terms[0]],'Legacy revision cleared assignments');
    // Deleted historical term: do not recreate it or partially replace current relations.
    wp_set_object_terms($id,[$terms[1]],'product_application');
    wp_set_object_terms($id,['sulfate'],'product_process');
    wp_delete_term($terms[0],'product_application');
    wp_restore_post_revision($a->ID);
    $check(wp_get_object_terms($id,'product_application',['fields'=>'ids'])===[$terms[1]] && has_term('sulfate','product_process',$id),'Deleted term caused partial restore');
    foreach(['_tio2_seo_title'=>'SEO A','_tio2_seo_description'=>'Description A','_tio2_discovery'=>['rows'=>[['grade'=>'A']]]] as $k=>$v) update_post_meta($page,$k,$v);
    wp_update_post(['ID'=>$page,'post_title'=>'SEO A']);$revisions=wp_get_post_revisions($page);$r=reset($revisions);
    update_post_meta($page,'_tio2_seo_title','SEO B');update_post_meta($page,'_tio2_seo_description','Description B');update_post_meta($page,'_tio2_discovery',['rows'=>[['grade'=>'B']]]);
    wp_update_post(['ID'=>$page,'post_title'=>'SEO B']);wp_restore_post_revision($r->ID);
    $check(get_post_meta($page,'_tio2_seo_title',true)==='SEO A','SEO title revision failed');
    $check(get_post_meta($page,'_tio2_seo_description',true)==='Description A','SEO description revision failed');
    $check(get_post_meta($page,'_tio2_discovery',true)===['rows'=>[['grade'=>'A']]],'Discovery revision failed');
    $before=count(wp_get_post_revisions($page));update_post_meta($page,'_tio2_seo_title','SEO only');wp_update_post(['ID'=>$page]);
    $check(count(wp_get_post_revisions($page))>$before,'SEO-only save has no revision');
    $before=count(wp_get_post_revisions($page));update_post_meta($page,'_tio2_discovery',['rows'=>[['grade'=>'C']]]);wp_update_post(['ID'=>$page]);
    $check(count(wp_get_post_revisions($page))>$before,'Discovery-only save has no revision');
    $check(!metadata_exists('post',$id,'_tio2_term_snapshot'),'Taxonomy snapshot leaked onto runtime product');
} finally { wp_delete_post($id,true);wp_delete_post($page,true);foreach($terms as $term)wp_delete_term($term,'product_application');delete_transient('tio2_revision_notice_'.get_current_user_id()); }
if($failures) WP_CLI::error(implode("\n",$failures));
WP_CLI::success('Required fields, term-only revisions, rename/deletion/legacy handling, Page SEO and Discovery restore.');
