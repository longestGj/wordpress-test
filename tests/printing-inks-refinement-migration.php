<?php
/** Local fixture: Printing Inks migration is exact, idempotent and editor-safe. */
require __DIR__.'/local-only.php';
$page=get_page_by_path('applications/titanium-dioxide-for-printing-inks',OBJECT,'page');
if (!$page || !tio2_owns_page($page->ID,'_tio2_page_id','APP-INK')) WP_CLI::error('Owned Printing Inks page is required.');
$original_content=$page->post_content;
$original_title=get_post_meta($page->ID,'_tio2_seo_title',true);
$original_description=get_post_meta($page->ID,'_tio2_seo_description',true);
$run=static fn()=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-printing-inks-detail.php',['return'=>'all','exit_error'=>false,'launch'=>true]);
$patch=json_decode(file_get_contents('/workspace/data/printing-inks-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$seed=json_decode(file_get_contents('/workspace/data/process-applications/printing-inks.json'),true,512,JSON_THROW_ON_ERROR);
$old_content=$seed['content'];
foreach ($patch as $entry) $old_content=str_replace($entry['new'],$entry['old'],$old_content);
$failure=null;
try {
    $fixture=$old_content.'<!-- inks-editor-fixture -->';
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($fixture)]);
    update_post_meta($page->ID,'_tio2_seo_title','Titanium Dioxide for Printing Inks | TiO2 Malaysia');
    update_post_meta($page->ID,'_tio2_seo_description','Compare titanium dioxide candidates in a defined white-ink and print system. Review Product Grades and prepare a document, sample or quotation request.');
    $result=$run();clean_post_cache($page->ID);
    $updated=get_post_field('post_content',$page->ID,'raw');
    if ($result->return_code!==0 || !str_contains($updated,'Printing Inks Evaluation at a Glance') || !str_contains($updated,'inks-editor-fixture')) throw new RuntimeException('Migration did not preserve editor content and add summary.');
    if (get_post_meta($page->ID,'_tio2_seo_title',true)!==$seed['seo_title'] || get_post_meta($page->ID,'_tio2_seo_description',true)!==$seed['seo_description']) throw new RuntimeException('SEO fields were not updated.');
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$updated) throw new RuntimeException('Repeat migration changed content.');
    $mixed=$updated.$patch[2]['old'];
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
WP_CLI::success('Printing Inks exact migration, SEO, idempotence and editor protection verified; fixture restored.');
