<?php
// Controlled initial-build correction only. Never overwrite intervening editor changes.
foreach(['about','products'] as $key){
 $s=json_decode(file_get_contents('/workspace/data/pages/'.$key.'.json'),true);$p=get_page_by_path($s['slug'],OBJECT,'page');
 if($p->post_content===$s['content'])continue;
 if(hash('sha256',$p->post_content)!==$s['previous_content_hash'])WP_CLI::error('Content changed since import: '.$key);
 $r=wp_update_post(['ID'=>$p->ID,'post_content'=>wp_slash($s['content'])],true);if(is_wp_error($r))WP_CLI::error($r->get_error_message());
}
$id=(int)get_option('tio2_product_hub');
if(!get_post_meta($id,'_tio2_discovery',true)){
 $d=get_option('tio2_product_discovery',[]);
 $d['not_sure']=$d['not_sure']??tio2_discovery_guidance();
 update_post_meta($id,'_tio2_discovery',$d);
}
WP_CLI::success('Initial-build corrections applied with content-preservation checks.');
