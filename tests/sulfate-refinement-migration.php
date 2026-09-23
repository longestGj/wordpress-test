<?php
/** Local fixture: exact Sulfate migration preserves unrelated editor content. */
require __DIR__.'/local-only.php';
$page=get_page_by_path('sulfate-process-titanium-dioxide',OBJECT,'page');
if (!$page || !tio2_owns_page($page->ID,'_tio2_page_id','PRODUCT-PROC-SU')) WP_CLI::error('Owned Sulfate page is required.');
$original=$page->post_content;
$seed=json_decode(file_get_contents('/workspace/data/process-applications/sulfate.json'),true,512,JSON_THROW_ON_ERROR);
$patch=json_decode(file_get_contents('/workspace/data/sulfate-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$old=$seed['content'];
foreach (array_reverse($patch) as $entry) $old=str_replace($entry['new'],$entry['old'],$old);
$fixture=$old.'<!-- sulfate-editor-fixture -->';
$run=static fn($mode)=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-sulfate-process.php '.$mode,['return'=>'all','exit_error'=>false,'launch'=>true]);
$failure=null;
try {
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($fixture)]);
    $checked=$run('check');clean_post_cache($page->ID);
    if ($checked->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$fixture) throw new RuntimeException('Dry run failed or wrote content: '.$checked->stderr);
    $applied=$run('apply');clean_post_cache($page->ID);
    $updated=get_post_field('post_content',$page->ID,'raw');
    if ($applied->return_code!==0 || !str_contains($updated,'Sulfate Process at a Glance') || !str_contains($updated,'sulfate-editor-fixture')) throw new RuntimeException('Exact migration failed or lost editor content: '.$applied->stderr);
    $repeated=$run('apply');clean_post_cache($page->ID);
    if ($repeated->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$updated) throw new RuntimeException('Repeat migration changed content: '.$repeated->stderr);
    $edited=str_replace($patch[4]['old'],'Editor-supplied RFQ wording',$fixture);
    if ($edited===$fixture) throw new RuntimeException('RFQ fixture target missing.');
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($edited)]);
    $refused=$run('apply');clean_post_cache($page->ID);
    if ($refused->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$edited) throw new RuntimeException('Edited RFQ was overwritten or accepted.');
} catch (Throwable $error) {$failure=$error->getMessage();}
finally {
    $restored=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($original)],true);
    clean_post_cache($page->ID);
    if (is_wp_error($restored) || get_post_field('post_content',$page->ID,'raw')!==$original) $failure=($failure??'').' Fixture restoration failed.';
}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('Sulfate dry run, exact migration, idempotence and editor protection verified; fixture restored.');
