<?php
/** Exact M-350 summary migration; check is read-only and apply is local-only. */
$mode=$args[0]??'check';
if(!in_array($mode,['check','apply'],true))WP_CLI::error('Use check or apply.');
if($mode==='apply')require '/workspace/tests/local-only.php';
$seed=json_decode(file_get_contents('/workspace/data/m350.json'),true,512,JSON_THROW_ON_ERROR);
$post=get_page_by_path('m-350',OBJECT,'product');
if(!$post || $post->post_status!=='publish' || $post->post_password!=='' || $post->post_name!=='m-350' || $post->post_title!=='M-350' || $post->post_excerpt!==$seed['excerpt'])WP_CLI::error('Expected published M-350 product identity; no data changed.');
$data=tio2_product_data($post->ID);
if(($data['h1']??'')!==$seed['data']['h1'] || ($data['seo_title']??'')!==$seed['data']['seo_title'] || ($data['seo_description']??'')!==$seed['data']['seo_description'])WP_CLI::error('Frozen M-350 headings or SEO differ; no data changed.');
if(!has_term('chloride','product_process',$post->ID))WP_CLI::error('M-350 process relationship differs; no data changed.');
$relations=wp_get_object_terms($post->ID,'product_application',['fields'=>'slugs']);
if(is_wp_error($relations))WP_CLI::error($relations->get_error_message());
sort($relations);$expected=['coatings','paper','plastics','printing-inks'];
if($relations!==$expected)WP_CLI::error('M-350 application relationships differ; no data changed.');
$old=['Rutile titanium dioxide pigment · Chloride process','M-350 technical data · General grade','Product identity · documentation · formulation evaluation'];
$new=$seed['data']['summary'];
if(($data['summary']??null)===$new){WP_CLI::success('M-350 summary already current.');return;}
if(($data['summary']??null)!==$old)WP_CLI::error('M-350 summary was edited; review manually. No data changed.');
if($mode==='check'){WP_CLI::success('Exact M-350 summary change pending.');return;}
$updated=$data;$updated['summary']=$new;
if(!update_post_meta($post->ID,'_tio2_product',$updated) || tio2_product_data($post->ID)!==$updated){
 update_post_meta($post->ID,'_tio2_product',$data);
 WP_CLI::error('M-350 summary update failed; original product data restored.');
}
wp_save_post_revision($post->ID);
WP_CLI::success('M-350 summary refined; all other editor data and taxonomy relationships preserved.');
