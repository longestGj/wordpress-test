<?php
/** Local fixture: exact Plastics migration preserves editor content and rejects drift. */
require __DIR__.'/local-only.php';
$page=get_page_by_path('applications/titanium-dioxide-for-plastics',OBJECT,'page');
if (!$page || !tio2_owns_page($page->ID,'_tio2_page_id','APP-PLAS')) WP_CLI::error('Owned Plastics page is required.');
$run=static fn()=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-plastics-detail.php',['return'=>'all','exit_error'=>false,'launch'=>true]);
$patch=json_decode(file_get_contents('/workspace/data/plastics-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$failure=null;
try {
    $original=$page->post_content;
    foreach ($patch as $entry) {
        if (!str_contains($original,$entry['old']) || !str_contains($original,$entry['new'])) {
            // Create the pre-migration fixture from the accepted seed, not editor content.
            $seed=json_decode(file_get_contents('/workspace/data/process-applications/plastics.json'),true,512,JSON_THROW_ON_ERROR);
            $original=$seed['content'];
            foreach ($patch as $reverse) $original=str_replace($reverse['new'],$reverse['old'],$original);
            break;
        }
    }
    $custom=$original.'<!-- plastics-editor-fixture -->';
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($custom)]);
    $result=$run();clean_post_cache($page->ID);
    $updated=get_post_field('post_content',$page->ID,'raw');
    if ($result->return_code!==0 || !str_contains($updated,'Plastics Evaluation at a Glance') || !str_contains($updated,'plastics-editor-fixture')) throw new RuntimeException('Migration did not preserve editor content and add summary.');
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$updated) throw new RuntimeException('Repeat migration changed content.');
    $both=$updated.$patch[2]['old'];
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($both)]);
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$both) throw new RuntimeException('Mixed old/new RFQ was accepted.');
    $edited=str_replace('Final Grade selection and suitability','Editor changed suitability',$updated);
    if ($edited===$updated) throw new RuntimeException('Edited fixture target is missing.');
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($edited)]);
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$edited) throw new RuntimeException('Edited RFQ was overwritten or accepted.');
} catch (Throwable $error) {$failure=$error->getMessage();}
finally {wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)]);}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('Plastics exact migration, idempotence and editor protection verified; fixture restored.');
