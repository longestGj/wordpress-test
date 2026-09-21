<?php
// Run with wp eval-file; isolated test records are always removed.
function check($condition, $message) { if (!$condition) { throw new RuntimeException($message); } }
check(post_type_exists('product'), 'Product CPT must exist');
check(function_exists('tio2_validate_product'), 'Product validator must exist');
$seed = json_decode(file_get_contents('/workspace/data/m350.json'), true);
$data = $seed['data'];
check(!is_wp_error(tio2_validate_product($data)), 'Approved seed should validate');
$bad = $data; $bad['rows'][0]['standard'] = ['invalid'];
check(is_wp_error(tio2_validate_product($bad)), 'Malformed table value must be rejected');
$bad = $data; unset($bad['h1']);
check(is_wp_error(tio2_validate_product($bad)), 'Missing identity must be rejected');
$bad = $data; $bad['facts'] = 'not a list';
check(is_wp_error(tio2_validate_product($bad)), 'Wrong section shape must be rejected');
$id = wp_insert_post(['post_type'=>'product','post_status'=>'draft','post_title'=>'Independent test product']);
try {
    check(tio2_product_data($id) === [], 'A new product must not inherit M-350 defaults');
    update_post_meta($id, '_tio2_product', $data);
    wp_set_object_terms($id,['Coatings','Paper'],'product_application');
    check(count(tio2_public_applications($id)) === 4, 'Only assigned application directions must render');
    wp_set_object_terms($id,['Coatings'],'product_application');
    check(count(tio2_public_applications($id)) === 3, 'Removing Paper relation must remove the qualified path');
    $rows = tio2_public_rows($id);
    check(count($rows) === 15, 'All 15 approved rows must be present');
    $data['rows'][0]['enabled'] = false;
    update_post_meta($id, '_tio2_product', $data);
    check(count(tio2_public_rows($id)) === 14, 'Withdrawing a row must remove it from public data');
    $schema = tio2_product_schema($id);
    check(count($schema['additionalProperty']) === 14, 'Schema must share withdrawn row behavior');
    $data['rows'][1]['typical_value'] = '98.8';
    update_post_meta($id, '_tio2_product', $data);
    check(str_contains(tio2_product_schema($id)['additionalProperty'][0]['value'], '98.8'), 'Schema must reflect editor changes');
    check(tio2_target_url('quote') === '', 'Unconfigured RFQ must not count as ready');
    $receiver=wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Temporary test receiver']);
    $targets=get_option('tio2_targets',[]);
    try {
        update_option('tio2_targets',['quote'=>$receiver,'quote_ready'=>false]);
        check(tio2_target_url('quote')==='', 'An untested receiver must remain unavailable');
        update_option('tio2_targets',['quote'=>$receiver,'quote_ready'=>true]);
        check(tio2_target_url('quote')===get_permalink($receiver), 'Configured tested receiver should resolve');
        wp_update_post(['ID'=>$receiver,'post_status'=>'draft']);
        check(tio2_target_url('quote')==='', 'Unpublishing a receiver must immediately hide its actions');
    } finally { update_option('tio2_targets',$targets);wp_delete_post($receiver,true); }
    echo "PASS: product validation, independent records, row visibility and Schema editing\n";
} finally { wp_delete_post($id, true); }
$m350=get_page_by_path('m-350',OBJECT,'product');$original=tio2_product_data($m350->ID);
try {
    $edited=$original;$edited['rows'][0]['typical_value']='93.6';update_post_meta($m350->ID,'_tio2_product',$edited);
    include '/workspace/scripts/import-product.php';
    check(tio2_product_data($m350->ID)['rows'][0]['typical_value']==='93.6','Rerunning import must preserve editor changes');
    echo "PASS: receiver readiness, unpublished target suppression, import preserves edits\n";
} finally { update_post_meta($m350->ID,'_tio2_product',$original); }
