<?php
require __DIR__.'/local-only.php';
wp_set_current_user(1);
$id=wp_insert_post(['post_type'=>'product','post_status'=>'draft','post_title'=>'Relationship test']);
$check=function($ok,$message){if(!$ok)throw new RuntimeException($message);};
$discovery=tio2_discovery_data();
try{
 $data=tio2_product_data(get_page_by_path('m-350',OBJECT,'product')->ID);
 $data['applications']=[['relation'=>'coatings','enabled'=>true,'title'=>'Test','text'=>'Approved description']];
 update_post_meta($id,'_tio2_product',$data);
 wp_set_object_terms($id,['chloride'],'product_process');
 set_current_screen('product');$GLOBALS['post']=get_post($id);
 ob_start();do_action('admin_notices');$notice=ob_get_clean();
 $check(str_contains($notice,'coatings')&&str_contains($notice,'notice-warning'),'Missing taxonomy relationship not warned');
 $check(!has_term('coatings','product_application',$id)&&tio2_product_data($id)===$data,'Warning changed data');
 wp_set_object_terms($id,['coatings'],'product_application');$data['applications'][0]['enabled']=false;update_post_meta($id,'_tio2_product',$data);
 ob_start();do_action('admin_notices');$notice=ob_get_clean();
 $check(!str_contains($notice,'notice-warning'),'Disabled or combined copy produced a false warning');
 $data['applications'][0]['enabled']=true;update_post_meta($id,'_tio2_product',$data);
 ob_start();do_action('admin_notices');$notice=ob_get_clean();
 $check(!str_contains($notice,'notice-warning'),'Valid relationship warned');
 wp_set_object_terms($id,['sulfate'],'product_process');
 ob_start();do_action('admin_notices');$notice=ob_get_clean();
 $check(str_contains($notice,'Process consistency'),'Process mismatch not warned');
 $check(has_term('sulfate','product_process',$id)&&tio2_product_data($id)===$data,'Process warning changed data');
 foreach(['m-510','cr-901'] as $slug){$GLOBALS['post']=get_page_by_path($slug,OBJECT,'product');ob_start();do_action('admin_notices');$notice=ob_get_clean();$check(!str_contains($notice,'notice-warning'),'Approved '.$slug.' warned');}
 $check(tio2_discovery_data()===$discovery,'Discovery changed');
}finally{wp_delete_post($id,true);}
WP_CLI::success('Enabled application/process conflicts warned without mutation; M-510 and CR-901 quiet.');
