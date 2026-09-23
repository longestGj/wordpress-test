<?php
/** Local fixture: Masterbatch migration is exact, idempotent and editor-safe. */
require __DIR__.'/local-only.php';
$page=get_page_by_path('applications/titanium-dioxide-for-masterbatch',OBJECT,'page');
if (!$page || !tio2_owns_page($page->ID,'_tio2_page_id','APP-MB')) WP_CLI::error('Owned Masterbatch page is required.');
$original_content=$page->post_content;
$original_title=get_post_meta($page->ID,'_tio2_seo_title',true);
$original_description=get_post_meta($page->ID,'_tio2_seo_description',true);
$run=static fn()=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-masterbatch-detail.php',['return'=>'all','exit_error'=>false,'launch'=>true]);
$patch=json_decode(file_get_contents('/workspace/data/masterbatch-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$seed=json_decode(file_get_contents('/workspace/data/process-applications/masterbatch.json'),true,512,JSON_THROW_ON_ERROR);
$old_content=$seed['content'];
foreach ($patch as $entry) $old_content=str_replace($entry['new'],$entry['old'],$old_content);
$failure=null;
try {
    $fixture=$old_content.'<!-- masterbatch-editor-fixture -->';
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($fixture)]);
    update_post_meta($page->ID,'_tio2_seo_title','Titanium Dioxide for Masterbatch Evaluation | TiO2 Malaysia');
    update_post_meta($page->ID,'_tio2_seo_description','Evaluate titanium dioxide for masterbatch by separating concentrate processing from final-article evidence. Review Product Grades and prepare your request.');
    $result=$run();clean_post_cache($page->ID);
    $updated=get_post_field('post_content',$page->ID,'raw');
    if ($result->return_code!==0 || !str_contains($updated,'Masterbatch Evaluation at a Glance') || !str_contains($updated,'masterbatch-editor-fixture')) throw new RuntimeException('Migration did not preserve editor content and add summary.');
    if (get_post_meta($page->ID,'_tio2_seo_title',true)!==$seed['seo_title'] || get_post_meta($page->ID,'_tio2_seo_description',true)!==$seed['seo_description']) throw new RuntimeException('SEO fields were not updated.');
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$updated) throw new RuntimeException('Repeat migration changed content.');
    $mixed=$updated.$patch[3]['old'];
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($mixed)]);
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$mixed) throw new RuntimeException('Mixed old/new RFQ was accepted.');
    $edited=str_replace('Unknown conditions can remain unknown','Editor changed unknown handling',$updated);
    if ($edited===$updated) throw new RuntimeException('Edited fixture target is missing.');
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($edited)]);
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$edited) throw new RuntimeException('Edited RFQ was overwritten or accepted.');
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($fixture)]);
    update_post_meta($page->ID,'_tio2_seo_title','Editor title');
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$fixture) throw new RuntimeException('Edited SEO title was overwritten or accepted.');
} catch (Throwable $error) {$failure=$error->getMessage();}
finally {
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($original_content)]);
    update_post_meta($page->ID,'_tio2_seo_title',$original_title);
    update_post_meta($page->ID,'_tio2_seo_description',$original_description);
}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('Masterbatch exact migration, SEO, idempotence and editor protection verified; fixture restored.');
