<?php
/** Exact, editor-preserving Non-China resource migration. Default mode is read-only. */
$mode=$args[0]??'check';
if (!in_array($mode,['check','apply'],true)) WP_CLI::error('Use check or apply.');
$page=get_page_by_path('resources/non-china-titanium-dioxide',OBJECT,'page');
if (!$page || $page->post_status!=='publish' || !tio2_owns_page($page->ID,'_tio2_resource_id','RES-ORIGIN')
    || $page->post_password!=='') WP_CLI::error('Owned published Non-China resource page is required.');
$seed=json_decode(file_get_contents('/workspace/data/resources/non-china-titanium-dioxide.json'),true,512,JSON_THROW_ON_ERROR);
if (get_post_meta($page->ID,'_tio2_seo_title',true)!==$seed['seo_title']
    || get_post_meta($page->ID,'_tio2_seo_description',true)!==$seed['seo_description']) WP_CLI::error('Frozen Non-China SEO fields differ; no content changed.');
$content=$page->post_content;
if (substr_count($content,'<h1 class="wp-block-heading">Non-China Titanium Dioxide: A Procurement Evaluation Guide</h1>')!==1) WP_CLI::error('Frozen Non-China H1 differs; no content changed.');
foreach (['Confirm the commercial counterparty','Confirm the product and grade identity',
    'Verify origin evidence and its scope','Review current technical documents and methods',
    'Define application and processing conditions','Check document, lot and destination requirements',
    'A hold is an evidence decision, not a statement that a material is technically unsuitable.'] as $frozen) {
    if (!str_contains($content,$frozen)) WP_CLI::error('Frozen due-diligence or qualification boundary differs; no content changed.');
}
$patch=json_decode(file_get_contents('/workspace/data/non-china-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$states=[];
foreach ($patch as $entry) {
    $old_count=substr_count($content,$entry['old']);
    $new_count=substr_count($content,$entry['new']);
    $old_within_new=str_contains($entry['new'],$entry['old']);
    if ($new_count===1 && $old_count===($old_within_new?1:0)) $states[]='new';
    elseif ($new_count===0 && $old_count===1) $states[]='old';
    else WP_CLI::error('Non-China '.$entry['id'].' passage was edited or duplicated; no content changed.');
}
if (count(array_unique($states))!==1) WP_CLI::error('Non-China passages are in a mixed state; no content changed.');
if ($mode==='check' || $states[0]==='new') {
    WP_CLI::success($states[0]==='old'?'Twelve exact Non-China changes pending.':'Non-China refinement already current.');
    return;
}
foreach ($patch as $entry) $content=str_replace($entry['old'],$entry['new'],$content);
$saved=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($content)],true);
if (is_wp_error($saved) || get_post_field('post_content',$page->ID,'raw')!==$content) {
    $restored=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)],true);
    WP_CLI::error(!is_wp_error($restored) && get_post_field('post_content',$page->ID,'raw')===$page->post_content
        ? 'Non-China update failed; original content restored.' : 'Non-China update failed; automatic rollback incomplete.');
}
WP_CLI::success('Non-China links, source context and decision actions refined; unrelated editor content and SEO preserved.');
