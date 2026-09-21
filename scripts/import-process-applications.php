<?php
$seeds=[];$ids=[];
$parent=get_page_by_path('applications',OBJECT,'page');
if(!$parent||!tio2_owns_page($parent->ID,'_tio2_hub_key','applications')||$parent->post_status!=='publish')WP_CLI::error('Applications parent ownership/readiness mismatch.');
foreach(glob('/workspace/data/process-applications/*.json') as $file){
    $s=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
    $process=in_array($s['key'],['chloride','sulfate'],true);$slug=basename(trim($s['url'],'/'));
    $path=$process?$slug:'applications/'.$slug;$existing=get_page_by_path($path,OBJECT,'page');
    if($existing&&(!tio2_owns_page($existing->ID,'_tio2_page_id',$s['page_id'])||get_post_meta($existing->ID,'_tio2_topic',true)!==$s['key']))WP_CLI::error('Page ownership collision: '.$path);
    if($process&&get_page_by_path($slug,OBJECT,'product'))WP_CLI::error('Product route collision: '.$slug);
    if($process&&get_page_by_path('products/'.$slug,OBJECT,'page'))WP_CLI::error('Page ownership collision at public process path: '.$s['url']);
    $s['existing']=$existing?$existing->ID:0;$s['slug']=$slug;$s['parent']=$process?0:$parent->ID;$seeds[]=$s;
}
foreach($seeds as $s){
    if($s['existing']){$ids[$s['key']]=$s['existing'];WP_CLI::log('Preserved '.$s['key']);continue;}
    $id=wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>$s['title'],'post_name'=>$s['slug'],'post_parent'=>$s['parent'],'post_content'=>wp_slash($s['content'])],true);
    if(is_wp_error($id))WP_CLI::error($id->get_error_message());
    foreach(['_tio2_owner'=>'tio2-wordpress','_tio2_page_id'=>$s['page_id'],'_tio2_topic'=>$s['key'],'_tio2_source'=>$s['source'],'_tio2_main_class'=>$s['main_class'],'_tio2_seo_title'=>$s['seo_title'],'_tio2_seo_description'=>$s['seo_description']] as $k=>$v)update_post_meta($id,$k,$v);
    $ids[$s['key']]=$id;WP_CLI::log('Imported '.$s['key']);
}
// Only bind these seven published content destinations, preserving all receiver settings.
$targets=get_option('tio2_targets',[]);foreach($ids as $key=>$id)if(empty($targets[$key]))$targets[$key]=$id;update_option('tio2_targets',$targets);
flush_rewrite_rules();WP_CLI::success('Seven process/application pages ready; existing edits preserved.');
