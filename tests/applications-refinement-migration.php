<?php
require __DIR__.'/local-only.php';
$page=get_page_by_path('applications',OBJECT,'page');
if (!$page || !tio2_owns_page($page->ID,'_tio2_hub_key','applications')) WP_CLI::error('Owned Applications hub is required.');
$title=get_post_meta($page->ID,'_tio2_seo_title',true);
$description=get_post_meta($page->ID,'_tio2_seo_description',true);
$run=static fn()=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-applications-hub.php',['return'=>'all','exit_error'=>false,'launch'=>true]);
$failure=null;
try {
    $custom=$page->post_content.'<!-- application-editor-fixture -->';
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($custom)]);
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code!==0 || get_post_field('post_content',$page->ID)!==$custom) throw new RuntimeException('Repeat migration did not preserve unrelated editor content.');
    $edited=str_replace('Key evaluation factors</strong>','Editor changed evaluation factors</strong>',$custom);
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($edited)]);
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code===0 || get_post_field('post_content',$page->ID)!==$edited) throw new RuntimeException('Edited target passage was overwritten or silently accepted.');
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($custom)]);
    update_post_meta($page->ID,'_tio2_seo_title','Editor SEO fixture');
    $result=$run();clean_post_cache($page->ID);
    if ($result->return_code===0 || get_post_field('post_content',$page->ID)!==$custom || get_post_meta($page->ID,'_tio2_seo_title',true)!=='Editor SEO fixture') throw new RuntimeException('Edited SEO was overwritten or silently accepted.');
} catch (Throwable $e) {$failure=$e->getMessage();}
finally {
    wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)]);
    update_post_meta($page->ID,'_tio2_seo_title',$title);
    update_post_meta($page->ID,'_tio2_seo_description',$description);
}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('Repeat migration and editor-content/SEO protection verified; fixture restored.');
