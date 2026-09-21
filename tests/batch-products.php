<?php
function batch_check($ok,$message){if(!$ok)WP_CLI::error($message);}
$m350=get_page_by_path('m-350',OBJECT,'product');
$original=tio2_product_data($m350->ID);
$variant=$original;
$variant['table_columns']=[['key'=>'property','label'=>'Property'],['key'=>'typical_value','label'=>'Typical value'],['key'=>'test_method','label'=>'Test method']];
foreach($variant['rows'] as &$row)$row['test_method']='XRF';unset($row);
batch_check(!is_wp_error(tio2_validate_product($variant)),'Variable technical columns must be supported without overwriting M350.');
foreach(glob('/workspace/data/products/*.json') as $file){
 $seed=json_decode(file_get_contents($file),true);$post=get_page_by_path($seed['slug'],OBJECT,'product');
 batch_check((bool)$post,'Missing '.$seed['slug']);$d=tio2_product_data($post->ID);
 batch_check($d===$seed['data'],'Imported data differs for '.$seed['slug']);
 foreach($seed['applications'] as $term)batch_check(has_term($term,'product_application',$post->ID),'Missing application '.$term);
 batch_check(count(tio2_public_rows($post->ID))===count($seed['data']['rows']),'Technical rows differ');
}
batch_check((int)wp_count_posts('product')->publish===14,'Expected 14 published grades.');
batch_check(tio2_product_data($m350->ID)===$original,'M350 changed');
WP_CLI::success('All 14 products and variable table data passed.');
