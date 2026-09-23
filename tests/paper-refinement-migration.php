<?php
/** Local fixture: Paper migration is exact, idempotent and editor-safe. */
require __DIR__.'/local-only.php';
$page=get_page_by_path('applications/titanium-dioxide-for-paper',OBJECT,'page');
if (!$page || !tio2_owns_page($page->ID,'_tio2_page_id','APP-PAPER')) WP_CLI::error('Owned Paper page is required.');
$original_content=$page->post_content;
$original_title=get_post_meta($page->ID,'_tio2_seo_title',true);
$original_description=get_post_meta($page->ID,'_tio2_seo_description',true);
$run=static fn()=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-paper-detail.php',['return'=>'all','exit_error'=>false,'launch'=>true]);
$patch=json_decode(file_get_contents('/workspace/data/paper-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$seed=json_decode(file_get_contents('/workspace/data/process-applications/paper.json'),true,512,JSON_THROW_ON_ERROR);
$old_content=$seed['content'];
foreach (array_reverse($patch) as $entry) $old_content=str_replace($entry['new'],$entry['old'],$old_content);
$failure=null;
try {
    $fixture=$old_content.'<!-- paper-editor-fixture -->';
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($fixture)]);
    update_post_meta($page->ID,'_tio2_seo_title','Titanium Dioxide for Paper Evaluation | TiO2 Malaysia');
    update_post_meta($page->ID,'_tio2_seo_description','Evaluate titanium dioxide for paper in a defined system. Compare method-matched results, review Product Grades, and prepare document, sample or RFQ details.');
    $result=$run();clean_post_cache($page->ID);
    $updated=get_post_field('post_content',$page->ID,'raw');
    if ($result->return_code!==0 || !str_contains($updated,'Paper Evaluation at a Glance') || !str_contains($updated,'paper-editor-fixture')) throw new RuntimeException('Migration did not preserve editor content and add summary. '.$result->stderr);
    if (get_post_meta($page->ID,'_tio2_seo_title',true)!==$seed['seo_title'] || get_post_meta($page->ID,'_tio2_seo_description',true)!==$seed['seo_description']) throw new RuntimeException('SEO fields were not updated.');
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$updated) throw new RuntimeException('Repeat migration changed content.');
    $mixed=$updated.$patch[3]['old'];
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($mixed)]);
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$mixed) throw new RuntimeException('Mixed old/new RFQ was accepted.');
    $edited=str_replace('Unknown details may remain unknown','Editor changed unknown handling',$updated);
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
WP_CLI::success('Paper exact migration, SEO, idempotence and editor protection verified; fixture restored.');
