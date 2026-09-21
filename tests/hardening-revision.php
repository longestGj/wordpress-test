<?php
// Real M-350 save hooks and HTTP, always restore the original content.
wp_set_current_user(1);
$p=get_page_by_path('m-350',OBJECT,'product');$original=get_post($p->ID,ARRAY_A);$data=tio2_product_data($p->ID);
$before=array_keys(wp_get_post_revisions($p->ID));
$check=function($ok,$message){if(!$ok)throw new RuntimeException($message);};
$save=function($value,$title,$excerpt)use($p,$data){
 $d=$data;$d['rows'][0]['typical_value']=$value;
 $form=function($v)use(&$form){return is_array($v)?array_map($form,$v):(is_bool($v)?($v?'1':'0'):$v);};
 $_POST=['tio2_nonce'=>wp_create_nonce('tio2_save'),'tio2'=>$form($d)];
 wp_update_post(['ID'=>$p->ID,'post_title'=>$title,'post_excerpt'=>$excerpt]);$_POST=[];
};
try{
 $save('93.6','M-350 revision A','Revision excerpt A');
 $revisions=wp_get_post_revisions($p->ID);$revision=reset($revisions);
 $save('93.7','M-350 revision B','Revision excerpt B');
 wp_restore_post_revision($revision->ID);
 $check(tio2_product_data($p->ID)['rows'][0]['typical_value']==='93.6','Revision did not restore technical meta');
 $check(get_the_title($p->ID)==='M-350 revision A'&&get_post_field('post_excerpt',$p->ID)==='Revision excerpt A','Title/excerpt restoration failed');
 $r=wp_remote_get('http://wordpress/products/m-350/',['headers'=>['Host'=>'localhost:8080']]);
 $body=wp_remote_retrieve_body($r);
 $check(wp_remote_retrieve_response_code($r)===200&&substr_count($body,'93.6')>=2&&!str_contains($body,'93.7'),'Frontend/Schema did not restore');
 // A meta-only change must create its own revision too.
 $save('93.8','M-350 revision A','Revision excerpt A');
 $revisions=wp_get_post_revisions($p->ID);$latest=reset($revisions);
 $check(get_post_meta($latest->ID,'_tio2_product',true)['rows'][0]['typical_value']==='93.8','Meta-only save missing from revision');
}finally{
 $_POST=[];update_post_meta($p->ID,'_tio2_product',$data);wp_update_post(wp_slash($original));
 foreach(array_diff(array_keys(wp_get_post_revisions($p->ID)),$before) as $id)wp_delete_post_revision($id);
}
WP_CLI::success('M-350 revision restores title, excerpt, meta, frontend and Schema; meta-only saves; original restored.');
