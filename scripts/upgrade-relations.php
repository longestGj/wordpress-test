<?php
// One-time additive migration for the initial prototype; preserves editable text.
$post=get_page_by_path('m-350',OBJECT,'product');if(!$post)return;
$data=tio2_product_data($post->ID);$seed=json_decode(file_get_contents('/workspace/data/m350.json'),true)['data'];
foreach($data['applications'] as $i=>&$app){if(!isset($app['relation'])){$app['relation']=$seed['applications'][$i]['relation'];$app['qualified']=$seed['applications'][$i]['qualified'];}}unset($app);
if(!isset($data['application_links']))$data['application_links']=$seed['application_links'];
unset($data['application_link_labels']);
if(!isset($data['process_key']))$data['process_key']='chloride';
if(!isset($data['market_links'])){
    $data['market_links']=[];
    foreach(['eu','uk','india','brazil'] as $i=>$key)$data['market_links'][]=['relation'=>$key,'label'=>$data['market_labels'][$i]??$seed['market_links'][$i]['label']];
}
unset($data['market_labels']);
update_post_meta($post->ID,'_tio2_product',$data);
WP_CLI::success('Added relationship associations without replacing copy.');
