<?php
/** Local fixture: exact Chloride migration preserves unrelated editor content. */
require __DIR__.'/local-only.php';
$page=get_page_by_path('chloride-process-titanium-dioxide',OBJECT,'page');
if (!$page || !tio2_owns_page($page->ID,'_tio2_page_id','PRODUCT-PROC-CL')) WP_CLI::error('Owned Chloride page is required.');
$original=$page->post_content;
$seed=json_decode(file_get_contents('/workspace/data/process-applications/chloride.json'),true,512,JSON_THROW_ON_ERROR);
$patch=json_decode(file_get_contents('/workspace/data/chloride-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$old=$seed['content'];
foreach (array_reverse($patch) as $entry) $old=str_replace($entry['new'],$entry['old'],$old);
$fixture=$old.'<!-- chloride-editor-fixture -->';
$run=static fn($mode)=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-chloride-process.php '.$mode,['return'=>'all','exit_error'=>false,'launch'=>true]);
$failure=null;
try {
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($fixture)]);
    $checked=$run('check');clean_post_cache($page->ID);
    if ($checked->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$fixture) throw new RuntimeException('Dry run failed or wrote content.');
    $applied=$run('apply');clean_post_cache($page->ID);
    $updated=get_post_field('post_content',$page->ID,'raw');
    if ($applied->return_code!==0 || !str_contains($updated,'Chloride Process at a Glance') || !str_contains($updated,'chloride-editor-fixture')) throw new RuntimeException('Exact migration failed or lost editor content.');
    $repeated=$run('apply');clean_post_cache($page->ID);
    if ($repeated->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$updated) throw new RuntimeException('Repeat migration changed content.');
    $edited=str_replace($patch[2]['old'],'Editor-supplied RFQ wording',$fixture);
    if ($edited===$fixture) throw new RuntimeException('RFQ fixture target missing.');
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($edited)]);
    $refused=$run('apply');clean_post_cache($page->ID);
    if ($refused->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$edited) throw new RuntimeException('Edited RFQ was overwritten or accepted.');
} catch (Throwable $error) {$failure=$error->getMessage();}
finally {
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($original)]);
}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('Chloride dry run, exact migration, idempotence and editor protection verified; fixture restored.');
