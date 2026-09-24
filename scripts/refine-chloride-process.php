<?php
/** Exact, editor-preserving Chloride page migration. Default mode is read-only. */
$mode=$args[0]??'check';
if (!in_array($mode,['check','apply'],true)) WP_CLI::error('Use check or apply.');
$page=get_page_by_path('chloride-process-titanium-dioxide',OBJECT,'page');
if (!$page || $page->post_status!=='publish' || !tio2_owns_page($page->ID,'_tio2_page_id','PRODUCT-PROC-CL') || get_post_meta($page->ID,'_tio2_topic',true)!=='chloride') WP_CLI::error('Owned published Chloride process page is required.');
$seed=json_decode(file_get_contents('/workspace/data/process-applications/chloride.json'),true,512,JSON_THROW_ON_ERROR);
if (get_post_meta($page->ID,'_tio2_seo_title',true)!==$seed['seo_title'] || get_post_meta($page->ID,'_tio2_seo_description',true)!==$seed['seo_description']) WP_CLI::error('Frozen Chloride SEO fields differ; no content changed.');
if (substr_count($page->post_content,'<h1 class="wp-block-heading">Chloride Process Titanium Dioxide</h1>')!==1) WP_CLI::error('Frozen Chloride H1 differs; no content changed.');
if (!preg_match('~<ul class="cl-grades">(.*?)</ul>~s',$page->post_content,$list)) WP_CLI::error('Chloride Grade list missing; no content changed.');
preg_match_all('~<a href="/products/([a-z0-9-]+)/">View (M-[0-9]+)</a>~',$list[1],$links,PREG_SET_ORDER);
$listed=array_map(static fn($match)=>$match[2],$links);
if ($listed!==['M-350','M-510','M-896','M-895','M-200','M-210','M-340','M-886']) WP_CLI::error('Frozen eight-Grade order differs; no content changed.');
$patch=json_decode(file_get_contents('/workspace/data/chloride-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$content=$page->post_content;
$states=[];
foreach ($patch as $entry) {
    $old_count=substr_count($content,$entry['old']);
    $new_count=substr_count($content,$entry['new']);
    $old_within_new=str_contains($entry['new'],$entry['old']);
    if ($new_count===1 && $old_count===($old_within_new?1:0)) $states[]='new';
    elseif ($new_count===0 && $old_count===1) $states[]='old';
    else WP_CLI::error('Chloride '.$entry['id'].' passage was edited or duplicated; no content changed.');
}
if (count(array_unique($states))!==1) WP_CLI::error('Chloride passages are in a mixed state; no content changed.');
if ($mode==='check') {WP_CLI::success($states[0]==='old'?'Three exact Chloride changes pending.':'Chloride refinement already current.');return;}
if ($states[0]==='new') {WP_CLI::success('Chloride refinement already current.');return;}
foreach ($patch as $entry) $content=str_replace($entry['old'],$entry['new'],$content);
$saved=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($content)],true);
if (is_wp_error($saved) || get_post_field('post_content',$page->ID,'raw')!==$content) {
    $restored=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)],true);
    WP_CLI::error(!is_wp_error($restored) && get_post_field('post_content',$page->ID,'raw')===$page->post_content?'Chloride update failed; original content restored.':'Chloride update failed; automatic rollback incomplete.');
}
WP_CLI::success('Chloride glance, sources and RFQ copy refined; other editor content, SEO and Grade list preserved.');
