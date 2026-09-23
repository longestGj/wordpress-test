<?php
/** Local fixture: exact Coatings migration keeps editor changes and refuses changed target copy. */
require __DIR__.'/local-only.php';
$page=get_page_by_path('applications/titanium-dioxide-for-coatings',OBJECT,'page');
if (!$page || !tio2_owns_page($page->ID,'_tio2_page_id','APP-COAT')) WP_CLI::error('Owned Coatings page is required.');
$title=get_post_meta($page->ID,'_tio2_seo_title',true);
$description=get_post_meta($page->ID,'_tio2_seo_description',true);
$run=static fn()=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-coatings-detail.php',['return'=>'all','exit_error'=>false,'launch'=>true]);
$failure=null;
try {
    $custom=$page->post_content.'<!-- coatings-editor-fixture -->';
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($custom)]);
    $result=$run();clean_post_cache($page->ID);
    $updated=get_post_field('post_content',$page->ID,'raw');
    if ($result->return_code!==0 || !str_contains($updated,'Coatings Evaluation at a Glance') || !str_contains($updated,'coatings-editor-fixture')) throw new RuntimeException('Migration did not preserve editor content or add the summary.');
    if (get_post_meta($page->ID,'_tio2_seo_title',true)!=='Titanium Dioxide for Paints & Coatings | Grade Evaluation') throw new RuntimeException('SEO title was not updated.');
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$updated) throw new RuntimeException('Repeat migration changed page content.');
    $patch=json_decode(file_get_contents('/workspace/data/coatings-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
    $both=$updated.$patch[0]['old'];
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($both)]);
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$both) throw new RuntimeException('Mixed old/new Hero was silently accepted.');
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($updated)]);
    $edited=str_replace('For new formulation work','Editor changed formulation copy',$updated);
    if ($edited===$updated) throw new RuntimeException('Hero fixture target is missing.');
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($edited)]);
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$edited) throw new RuntimeException('Edited Hero was overwritten or silently accepted.');
} catch (Throwable $error) {$failure=$error->getMessage();}
finally {
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)]);
    update_post_meta($page->ID,'_tio2_seo_title',$title);
    update_post_meta($page->ID,'_tio2_seo_description',$description);
}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('Coatings exact migration, idempotence and editor protection verified; fixture restored.');
