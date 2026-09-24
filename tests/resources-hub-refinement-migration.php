<?php
/** Local fixture: the Resources patch updates only exact passages and keeps editor copy. */
require __DIR__.'/local-only.php';
$page=get_page_by_path('resources',OBJECT,'page');
if (!$page || !tio2_owns_page($page->ID,'_tio2_hub_key','resources')) WP_CLI::error('Owned Resources hub required.');
$original=$page->post_content;
$seed=json_decode(file_get_contents('/workspace/data/pages/resources.json'),true,512,JSON_THROW_ON_ERROR);
$patch=json_decode(file_get_contents('/workspace/data/resources-hub-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$old=$seed['content'];
foreach (array_reverse($patch) as $entry) $old=str_replace($entry['new'],$entry['old'],$old);
$fixture=$old.'<!-- resources-editor-fixture -->';
$run=static fn($mode)=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-resources-hub.php '.$mode,['return'=>'all','exit_error'=>false,'launch'=>true]);
$failure=null;
try {
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($fixture)]);
    $checked=$run('check');clean_post_cache($page->ID);
    if ($checked->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$fixture) throw new RuntimeException('Read-only check wrote content or failed.');
    $applied=$run('apply');clean_post_cache($page->ID);
    $updated=get_post_field('post_content',$page->ID,'raw');
    if ($applied->return_code!==0 || !str_contains($updated,'Continue Your Procurement Review') || !str_contains($updated,'resources-editor-fixture')) throw new RuntimeException('Exact patch lost content.');
    $repeat=$run('apply');clean_post_cache($page->ID);
    if ($repeat->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$updated) throw new RuntimeException('Repeat patch changed content.');
    $edited=str_replace($patch[0]['old'],'Editor-adjusted technical data wording',$fixture);
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($edited)]);
    $refused=$run('apply');clean_post_cache($page->ID);
    if ($refused->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$edited) throw new RuntimeException('Edited FAQ was overwritten or accepted.');
} catch (Throwable $error) {$failure=$error->getMessage();}
finally {wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($original)]);}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('Resources patch dry run, exact edit, idempotence and editor protection verified; fixture restored.');
