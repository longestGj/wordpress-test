<?php
/** Local-only exact-copy migration for the owned Masterbatch detail page. */
require '/workspace/tests/local-only.php';
$page=get_page_by_path('applications/titanium-dioxide-for-masterbatch',OBJECT,'page');
if (!$page || $page->post_status!=='publish' || !tio2_owns_page($page->ID,'_tio2_page_id','APP-MB') || get_post_meta($page->ID,'_tio2_topic',true)!=='masterbatch') WP_CLI::error('Owned published Masterbatch detail page is required.');
$patch=json_decode(file_get_contents('/workspace/data/masterbatch-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$content=$page->post_content;
$states=[];
foreach ($patch as $entry) {
    $old_count=substr_count($content,$entry['old']);
    $new_count=substr_count($content,$entry['new']);
    $old_within_new=str_contains($entry['new'],$entry['old']);
    if ($new_count===1 && $old_count===($old_within_new?1:0)) $states[]='new';
    elseif ($new_count===0 && $old_count===1) $states[]='old';
    else WP_CLI::error('Masterbatch passage was edited or duplicated; review manually. No content changed.');
}
if (count(array_unique($states))!==1) WP_CLI::error('Masterbatch passages are in a mixed state; review manually. No content changed.');
$old_title='Titanium Dioxide for Masterbatch Evaluation | TiO2 Malaysia';
$new_title='Titanium Dioxide for Masterbatch | TiO₂ Grade Evaluation';
$old_description='Evaluate titanium dioxide for masterbatch by separating concentrate processing from final-article evidence. Review Product Grades and prepare your request.';
$new_description='Evaluate candidate titanium dioxide Grades for masterbatch through concentrate processing, carrier and receiving resin, let-down and final-article testing.';
$title=get_post_meta($page->ID,'_tio2_seo_title',true);
$description=get_post_meta($page->ID,'_tio2_seo_description',true);
if (!in_array($title,[$old_title,$new_title],true) || !in_array($description,[$old_description,$new_description],true)) WP_CLI::error('Masterbatch SEO fields were edited; review manually. No content changed.');
if ($states[0]==='new' && $title===$new_title && $description===$new_description) {WP_CLI::success('Masterbatch refinement already current.'); return;}
if ($states[0]==='old') foreach ($patch as $entry) $content=str_replace($entry['old'],$entry['new'],$content);
$content_attempted=false;
try {
    if ($content!==$page->post_content) {
        $content_attempted=true;
        $saved=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($content)],true);
        if (is_wp_error($saved) || get_post_field('post_content',$page->ID,'raw')!==$content) throw new RuntimeException('Content write failed.');
    }
    if ($title!==$new_title && (!update_post_meta($page->ID,'_tio2_seo_title',$new_title) || get_post_meta($page->ID,'_tio2_seo_title',true)!==$new_title)) throw new RuntimeException('SEO title write failed.');
    if ($description!==$new_description && (!update_post_meta($page->ID,'_tio2_seo_description',$new_description) || get_post_meta($page->ID,'_tio2_seo_description',true)!==$new_description)) throw new RuntimeException('SEO description write failed.');
} catch (Throwable $error) {
    $restored=true;
    if ($content_attempted) {
        $rollback=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)],true);
        $restored=!is_wp_error($rollback) && get_post_field('post_content',$page->ID,'raw')===$page->post_content;
    }
    update_post_meta($page->ID,'_tio2_seo_title',$title);
    update_post_meta($page->ID,'_tio2_seo_description',$description);
    $restored=$restored && get_post_meta($page->ID,'_tio2_seo_title',true)===$title && get_post_meta($page->ID,'_tio2_seo_description',true)===$description;
    WP_CLI::error($restored?'Masterbatch update failed; original content and SEO restored.':'Masterbatch update failed; automatic rollback was incomplete. Restore the local database backup.');
}
WP_CLI::success('Masterbatch entry, RFQ and SEO refined; other editor content preserved.');
