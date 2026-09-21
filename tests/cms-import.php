<?php
function cms_check($ok,$message){if(!$ok)throw new RuntimeException($message);}
wp_set_current_user(1);
$home_id=(int)get_option('page_on_front');$home=get_post($home_id);$seo=get_post_meta($home_id,'_tio2_seo_title',true);$meta=get_post_meta($home_id,'_tio2_seo_description',true);
$m350=get_page_by_path('m-350',OBJECT,'product');$m350_before=tio2_product_data($m350->ID);
$m510=get_page_by_path('m-510',OBJECT,'product');$original=tio2_product_data($m510->ID);
$hub_id=(int)get_option('tio2_product_hub');$discovery=tio2_discovery_data();
try{
 $edited=$original;$edited['rows'][0]['typical_value']='EDITOR PRESERVATION TEST';update_post_meta($m510->ID,'_tio2_product',$edited);
 $_POST=['tio2_page_nonce'=>wp_create_nonce('tio2_page_seo'),'tio2_seo'=>['title'=>'Temporary CMS test title','description'=>$meta]];
 $content=str_replace('Malaysia Titanium Dioxide for Industrial Buyers','Temporary CMS test heading',$home->post_content);
 wp_update_post(['ID'=>$home_id,'post_content'=>wp_slash($content)]);
 $response=wp_remote_get('http://wordpress/',['timeout'=>20,'headers'=>['Host'=>'localhost:8080']]);cms_check(!is_wp_error($response),'HTTP unavailable');$body=wp_remote_retrieve_body($response);
 cms_check(str_contains($body,'<title>Temporary CMS test title</title>')&&str_contains($body,'Temporary CMS test heading'),'Native page and SEO edits failed on real HTTP');
 $_POST=[];
 require '/workspace/scripts/import-batch.php';require '/workspace/scripts/import-pages.php';
 cms_check(tio2_product_data($m510->ID)===$edited,'Product reimport overwrote an edit');
 cms_check(get_post_field('post_content',$home_id)===$content,'Page reimport overwrote an edit');
 cms_check(tio2_product_data($m350->ID)===$m350_before,'M350 changed across bulk imports');
 $changed=$discovery;$changed['not_sure'][0]='Edited through save hook';
 $_POST=['tio2_discovery_nonce'=>wp_create_nonce('tio2_discovery'),'tio2_discovery'=>$changed];
 wp_update_post(['ID'=>$hub_id,'post_title'=>get_post_field('post_title',$hub_id)]);
 cms_check(tio2_discovery_data()['not_sure'][0]==='Edited through save hook','Discovery admin save failed');
 $changed['applications']['Paper']=['UNKNOWN'];$_POST['tio2_discovery']=$changed;wp_update_post(['ID'=>$hub_id,'post_title'=>get_post_field('post_title',$hub_id)]);
 cms_check(tio2_discovery_data()['applications']['Paper']===$discovery['applications']['Paper'],'Invalid discovery edit was not rejected');
}finally{
 $_POST=[];wp_update_post(['ID'=>$home_id,'post_content'=>wp_slash($home->post_content)]);update_post_meta($home_id,'_tio2_seo_title',$seo);update_post_meta($home_id,'_tio2_seo_description',$meta);
 update_post_meta($m510->ID,'_tio2_product',$original);update_post_meta($hub_id,'_tio2_discovery',$discovery);delete_transient('tio2_error_1');
}
WP_CLI::success('Native content/SEO save -> real HTTP; discovery save/reject; repeated imports preserve edits and M350; restored.');
