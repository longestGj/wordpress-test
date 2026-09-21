<?php
// wp eval-file /workspace/scripts/import-product.php
$seed=json_decode(file_get_contents('/workspace/data/m350.json'),true,512,JSON_THROW_ON_ERROR);
if(get_page_by_path($seed['slug'],OBJECT,'product')) { WP_CLI::success('Existing product preserved; import skipped.'); return; }
$valid=tio2_validate_product($seed['data']);
if(is_wp_error($valid)) WP_CLI::error($valid->get_error_message());
$id=wp_insert_post(['post_type'=>'product','post_title'=>$seed['title'],'post_name'=>$seed['slug'],'post_excerpt'=>$seed['excerpt'],'post_status'=>'publish'],true);
if(is_wp_error($id)) WP_CLI::error($id->get_error_message());
update_post_meta($id,'_tio2_product',$seed['data']);
wp_set_object_terms($id,$seed['applications'],'product_application');
wp_set_object_terms($id,$seed['process'],'product_process');
WP_CLI::success('Imported product '.$id.' without changing other content.');
