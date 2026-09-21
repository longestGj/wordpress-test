<?php
// Idempotent first import. Existing records, including editor changes, are never overwritten.
foreach(glob('/workspace/data/products/*.json') as $file){
 $seed=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
 if(get_page_by_path($seed['slug'],OBJECT,'product')){WP_CLI::log('Preserved '.$seed['slug']);continue;}
 $valid=tio2_validate_product($seed['data']);if(is_wp_error($valid))WP_CLI::error($seed['slug'].': '.$valid->get_error_message());
 $id=wp_insert_post(['post_type'=>'product','post_status'=>'publish','post_title'=>$seed['title'],'post_name'=>$seed['slug'],'post_excerpt'=>$seed['excerpt']],true);
 if(is_wp_error($id))WP_CLI::error($id->get_error_message());
 update_post_meta($id,'_tio2_product',$seed['data']);
 wp_set_object_terms($id,$seed['applications'],'product_application');
 if($seed['process'])wp_set_object_terms($id,$seed['process'],'product_process');
 WP_CLI::log('Imported '.$seed['slug']);
}
WP_CLI::success('Product batch complete.');
