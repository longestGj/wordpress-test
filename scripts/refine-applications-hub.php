<?php
/** Local-only exact-copy migration for the owned Applications hub. */
require '/workspace/tests/local-only.php';
$page=get_page_by_path('applications',OBJECT,'page');
if (!$page || $page->post_status!=='publish' || !tio2_owns_page($page->ID,'_tio2_hub_key','applications')) WP_CLI::error('Owned published Applications hub is required.');
$patch=json_decode(file_get_contents('/workspace/data/applications-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$content=$page->post_content;
foreach ($patch as $entry) {
    if (str_contains($content,$entry['new'])) continue;
    if (substr_count($content,$entry['old'])!==1) WP_CLI::error('Applications passage was edited; review manually. No content changed.');
    $content=str_replace($entry['old'],$entry['new'],$content);
}
$old_title='Applications | TiO2 Malaysia';
$new_title='Titanium Dioxide Applications | Coatings, Plastics & More';
$old_description='Explore titanium dioxide application paths for coatings, plastics, masterbatch, printing inks, paper and specialty materials.';
$new_description='Compare industrial titanium dioxide evaluation factors for coatings, plastics, masterbatch, printing inks and paper, then explore detailed application guidance and grades.';
$title=get_post_meta($page->ID,'_tio2_seo_title',true);
$description=get_post_meta($page->ID,'_tio2_seo_description',true);
if (!in_array($title,[$old_title,$new_title],true) || !in_array($description,[$old_description,$new_description],true)) WP_CLI::error('Applications SEO fields were edited; review manually. No content changed.');
$changed=false;
try {
    if ($content!==$page->post_content) {
        $saved=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($content)],true);
        if (is_wp_error($saved) || get_post_field('post_content',$page->ID,'raw')!==$content) throw new RuntimeException('Applications content write failed.');
        $changed=true;
    }
    if ($title!==$new_title && (!update_post_meta($page->ID,'_tio2_seo_title',$new_title) || get_post_meta($page->ID,'_tio2_seo_title',true)!==$new_title)) throw new RuntimeException('SEO title write failed.');
    if ($description!==$new_description && (!update_post_meta($page->ID,'_tio2_seo_description',$new_description) || get_post_meta($page->ID,'_tio2_seo_description',true)!==$new_description)) throw new RuntimeException('SEO description write failed.');
} catch (Throwable $error) {
    if ($changed) wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)]);
    update_post_meta($page->ID,'_tio2_seo_title',$title);
    update_post_meta($page->ID,'_tio2_seo_description',$description);
    WP_CLI::error('Applications update failed; original content and SEO restored.');
}
WP_CLI::success($changed?'Applications copy and SEO refined; other editor content preserved.':'Applications copy current; SEO checked.');
