<?php
/** Exact, editor-preserving Sulfate page migration. Default mode is read-only. */
$mode=$args[0]??'check';
if (!in_array($mode,['check','apply'],true)) WP_CLI::error('Use check or apply.');
if ($mode==='apply') require '/workspace/tests/local-only.php';
$page=get_page_by_path('sulfate-process-titanium-dioxide',OBJECT,'page');
if (!$page || $page->post_status!=='publish' || !tio2_owns_page($page->ID,'_tio2_page_id','PRODUCT-PROC-SU') || get_post_meta($page->ID,'_tio2_topic',true)!=='sulfate') WP_CLI::error('Owned published Sulfate process page is required.');
$seed=json_decode(file_get_contents('/workspace/data/process-applications/sulfate.json'),true,512,JSON_THROW_ON_ERROR);
if (get_post_meta($page->ID,'_tio2_seo_title',true)!==$seed['seo_title'] || get_post_meta($page->ID,'_tio2_seo_description',true)!==$seed['seo_description']) WP_CLI::error('Frozen Sulfate SEO fields differ; no content changed.');
if (substr_count($page->post_content,'<h1 class="wp-block-heading">Sulfate Process Titanium Dioxide</h1>')!==1) WP_CLI::error('Frozen Sulfate H1 differs; no content changed.');
if (!preg_match('~<section class="wp-block-group m3">(.*?)</section>~s',$page->post_content,$grade_section)) WP_CLI::error('Sulfate Grade section missing; no content changed.');
preg_match_all('~<article class="wp-block-group"><!-- wp:heading \{"level": 3\} --><h3 class="wp-block-heading">([^<]+)</h3>.*?<p>([^<]+)</p>.*?<a href="/products/([^/]+)/">~s',$grade_section[1],$cards,PREG_SET_ORDER);
$frozen=[
    ['M-996','Documented for evaluation in industrial coatings, powder coatings, and exterior or interior architectural coatings.','m-996'],
    ['M-2196','Documented for evaluation in solvent-based furniture and industrial paints.','m-2196'],
    ['M-108','Documented for masterbatch and compounds, polyolefin and PVC film, and plastics requiring high thermal stability.','m-108'],
    ['M-52','Documented for printing inks, can coatings and high-gloss interior architectural coatings.','m-52'],
    ['M-2377','Documented for evaluation across coatings, plastics, masterbatch, printing inks and paper.','m-2377'],
];
if (array_map(static fn($card)=>array_slice($card,1,3),$cards)!==$frozen) WP_CLI::error('Frozen five-Grade mapping or descriptions differ; no content changed.');
$patch=json_decode(file_get_contents('/workspace/data/sulfate-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$content=$page->post_content;
$states=[];
foreach ($patch as $entry) {
    $old_count=substr_count($content,$entry['old']);
    $new_count=substr_count($content,$entry['new']);
    $old_within_new=str_contains($entry['new'],$entry['old']);
    if ($new_count===1 && $old_count===($old_within_new?1:0)) $states[]='new';
    elseif ($new_count===0 && $old_count===1) $states[]='old';
    else WP_CLI::error('Sulfate '.$entry['id'].' passage was edited or duplicated; no content changed.');
}
if (count(array_unique($states))!==1) WP_CLI::error('Sulfate passages are in a mixed state; no content changed.');
if ($mode==='check') {WP_CLI::success($states[0]==='old'?'Five exact Sulfate changes pending.':'Sulfate refinement already current.');return;}
if ($states[0]==='new') {WP_CLI::success('Sulfate refinement already current.');return;}
foreach ($patch as $entry) $content=str_replace($entry['old'],$entry['new'],$content);
$saved=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($content)],true);
if (is_wp_error($saved) || get_post_field('post_content',$page->ID,'raw')!==$content) {
    $restored=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)],true);
    WP_CLI::error(!is_wp_error($restored) && get_post_field('post_content',$page->ID,'raw')===$page->post_content?'Sulfate update failed; original content restored.':'Sulfate update failed; automatic rollback incomplete.');
}
WP_CLI::success('Sulfate glance, EPA source and durable request copy refined; other editor content, SEO and Grade list preserved.');
