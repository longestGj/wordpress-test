<?php
/** Local-only exact-copy migration for the owned Plastics detail page. */
require '/workspace/tests/local-only.php';
$page=get_page_by_path('applications/titanium-dioxide-for-plastics',OBJECT,'page');
if (!$page || $page->post_status!=='publish' || !tio2_owns_page($page->ID,'_tio2_page_id','APP-PLAS') || get_post_meta($page->ID,'_tio2_topic',true)!=='plastics') WP_CLI::error('Owned published Plastics detail page is required.');
$patch=json_decode(file_get_contents('/workspace/data/plastics-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$content=$page->post_content;
$states=[];
foreach ($patch as $entry) {
    $old_count=substr_count($content,$entry['old']);
    $new_count=substr_count($content,$entry['new']);
    $old_within_new=str_contains($entry['new'],$entry['old']);
    if ($new_count===1 && $old_count===($old_within_new?1:0)) $states[]='new';
    elseif ($new_count===0 && $old_count===1) $states[]='old';
    else WP_CLI::error('Plastics passage was edited or duplicated; review manually. No content changed.');
}
if (count(array_unique($states))!==1) WP_CLI::error('Plastics passages are in a mixed state; review manually. No content changed.');
if ($states[0]==='new') {WP_CLI::success('Plastics refinement already current.'); return;}
foreach ($patch as $entry) $content=str_replace($entry['old'],$entry['new'],$content);
$saved=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($content)],true);
if (is_wp_error($saved) || get_post_field('post_content',$page->ID,'raw')!==$content) {
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)]);
    WP_CLI::error('Plastics update failed; original content restored.');
}
WP_CLI::success('Plastics breadcrumb, glance and RFQ refined; other editor content preserved.');
