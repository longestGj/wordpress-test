<?php
/** Local-only exact-copy migration for the owned Coatings detail page. */
require '/workspace/tests/local-only.php';
$page=get_page_by_path('applications/titanium-dioxide-for-coatings',OBJECT,'page');
if (!$page || $page->post_status!=='publish' || !tio2_owns_page($page->ID,'_tio2_page_id','APP-COAT') || get_post_meta($page->ID,'_tio2_topic',true)!=='coatings') WP_CLI::error('Owned published Coatings detail page is required.');
$patch=json_decode(file_get_contents('/workspace/data/coatings-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$content=$page->post_content;
foreach ($patch as $entry) {
    if (str_contains($content,$entry['new'])) continue;
    if (substr_count($content,$entry['old'])!==1) WP_CLI::error('Coatings passage was edited; review manually. No content changed.');
    $content=str_replace($entry['old'],$entry['new'],$content);
}
$old_title='Titanium Dioxide for Coatings | Grade Evaluation';
$new_title='Titanium Dioxide for Paints & Coatings | Grade Evaluation';
$old_description='Compare TiO2 grades in your coating system by formulation, dispersion, film, exposure and test basis. Review Grades, documents, samples and RFQ inputs.';
$new_description='Evaluate titanium dioxide for paints and coatings by formulation, dispersion and prepared-film optical properties. Compare candidate Grades using declared test conditions.';
$title=get_post_meta($page->ID,'_tio2_seo_title',true);
$description=get_post_meta($page->ID,'_tio2_seo_description',true);
if (!in_array($title,[$old_title,$new_title],true) || !in_array($description,[$old_description,$new_description],true)) WP_CLI::error('Coatings SEO fields were edited; review manually. No content changed.');
$changed=false;
try {
    if ($content!==$page->post_content) {
        $saved=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($content)],true);
        if (is_wp_error($saved) || get_post_field('post_content',$page->ID,'raw')!==$content) throw new RuntimeException('Coatings content write failed.');
        $changed=true;
    }
    if ($title!==$new_title && (!update_post_meta($page->ID,'_tio2_seo_title',$new_title) || get_post_meta($page->ID,'_tio2_seo_title',true)!==$new_title)) throw new RuntimeException('SEO title write failed.');
    if ($description!==$new_description && (!update_post_meta($page->ID,'_tio2_seo_description',$new_description) || get_post_meta($page->ID,'_tio2_seo_description',true)!==$new_description)) throw new RuntimeException('SEO description write failed.');
} catch (Throwable $error) {
    if ($changed) wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)]);
    update_post_meta($page->ID,'_tio2_seo_title',$title);
    update_post_meta($page->ID,'_tio2_seo_description',$description);
    WP_CLI::error('Coatings update failed; original content and SEO restored.');
}
WP_CLI::success($changed?'Coatings entry and RFQ refined; other editor content preserved.':'Coatings copy current; SEO checked.');
