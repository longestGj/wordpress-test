<?php
/** Exact, editor-preserving Resources Hub migration. Default mode is read-only. */
$mode=$args[0]??'check';
if (!in_array($mode,['check','apply'],true)) WP_CLI::error('Use check or apply.');
$page=get_page_by_path('resources',OBJECT,'page');
if (!$page || $page->post_status!=='publish' || !tio2_owns_page($page->ID,'_tio2_hub_key','resources')) WP_CLI::error('Owned published Resources hub is required.');
$seed=json_decode(file_get_contents('/workspace/data/pages/resources.json'),true,512,JSON_THROW_ON_ERROR);
if (get_post_meta($page->ID,'_tio2_seo_title',true)!==$seed['seo_title']
    || get_post_meta($page->ID,'_tio2_seo_description',true)!==$seed['seo_description']) WP_CLI::error('Frozen Resources SEO fields differ; no content changed.');
$content=$page->post_content;
if (substr_count($content,'<h1 id="res-title">Resources for Titanium Dioxide Procurement Decisions</h1>')!==1) WP_CLI::error('Frozen Resources H1 differs; no content changed.');
foreach (['01 / SOURCING','02 / TECHNICAL EVALUATION','03 / TRADE &amp; MARKET',
    'Comparisons describe defined technical criteria and should not be interpreted as automatic product equivalence.',
    'Grade suitability depends on application and processing requirements',
    'official source, applicable scope, source date and review date'] as $frozen) {
    if (!str_contains($content,$frozen)) WP_CLI::error('Frozen research path or evidence wording differs; no content changed.');
}
$patch=json_decode(file_get_contents('/workspace/data/resources-hub-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$states=[];
foreach ($patch as $entry) {
    $old_count=substr_count($content,$entry['old']);
    $new_count=substr_count($content,$entry['new']);
    if ($old_count===1 && $new_count===0) $states[]='old';
    elseif ($old_count===0 && $new_count===1) $states[]='new';
    else WP_CLI::error('Resources '.$entry['id'].' passage was edited or duplicated; no content changed.');
}
if (count(array_unique($states))!==1) WP_CLI::error('Resources passages are in a mixed state; no content changed.');
if ($mode==='check' || $states[0]==='new') {
    WP_CLI::success($states[0]==='old'?'Four exact Resources changes pending.':'Resources Hub refinement already current.');
    return;
}
foreach ($patch as $entry) $content=str_replace($entry['old'],$entry['new'],$content);
$saved=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($content)],true);
if (is_wp_error($saved) || get_post_field('post_content',$page->ID,'raw')!==$content) {
    $restored=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)],true);
    WP_CLI::error(!is_wp_error($restored) && get_post_field('post_content',$page->ID,'raw')===$page->post_content
        ? 'Resources update failed; original content restored.' : 'Resources update failed; automatic rollback incomplete.');
}
WP_CLI::success('Resources procurement routes and three FAQ links updated; other editor content and SEO preserved.');
