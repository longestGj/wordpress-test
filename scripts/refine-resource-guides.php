<?php
/** Exact, editor-preserving refinement for RES-PROC and RES-CHEMOURS. */
$key=$args[0]??'';
$mode=$args[1]??'check';
$pages=[
    'proc'=>['RES-PROC','chloride-vs-sulfate-titanium-dioxide'],
    'chemours'=>['RES-CHEMOURS','chemours-titanium-dioxide-alternatives'],
];
if (!isset($pages[$key]) || !in_array($mode,['check','apply'],true)) WP_CLI::error('Use proc|chemours [check|apply].');
[$identity,$slug]=$pages[$key];
$page=get_page_by_path('resources/'.$slug,OBJECT,'page');
if (!$page || $page->post_status!=='publish' || $page->post_password!==''
    || !tio2_owns_page($page->ID,'_tio2_resource_id',$identity)) WP_CLI::error('Owned published '.$identity.' page is required.');
$seed=json_decode(file_get_contents('/workspace/data/resources/'.$slug.'.json'),true,512,JSON_THROW_ON_ERROR);
if (get_post_meta($page->ID,'_tio2_seo_title',true)!==$seed['seo_title']
    || get_post_meta($page->ID,'_tio2_seo_description',true)!==$seed['seo_description']) WP_CLI::error('Frozen SEO fields differ; no content changed.');
$content=$page->post_content;
preg_match_all('/<h1\b[^>]*>(.*?)<\/h1>/s',$content,$heads);
if (count($heads[1])!==1 || html_entity_decode(wp_strip_all_tags($heads[1][0]),ENT_QUOTES|ENT_HTML5,'UTF-8')!==$seed['h1'])
    WP_CLI::error('Frozen H1 differs; no content changed.');
$frozen=$key==='proc' ? [
    'The practical question is therefore not which label wins in general',
    'Do not ignore the route, and do not stop at the route.',
    'Define the application and decision criteria',
    'Identify the exact candidate grade and route',
    'Collect current grade-specific evidence',
    'Normalize the comparison',
    'Validate in the intended system',
    'A hold is an evidence decision, not proof that a route or grade is unsuitable.',
    'Manufacturer sources are limited to what the named producer says',
] : [
    'If your current reference is a Chemours Ti-Pure grade, start with the exact grade, application and qualification requirements.',
    'A brand name alone is not enough to identify a suitable alternative.',
    'Current reference and use.',
    'Process and handling constraints.',
    'Performance and acceptance criteria.',
    'Documents required for review.',
    '1. Build a shortlist',
    '2. Compare product-specific information',
    '3. Validate in your own system',
    'We are not affiliated with, authorized by or endorsed by Chemours.',
];
foreach ($frozen as $phrase) if (!str_contains($content,$phrase)) WP_CLI::error('Frozen '.$identity.' copy differs; no content changed.');
$all=json_decode(file_get_contents('/workspace/data/resource-guide-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$patch=$all[$identity];
$states=[];
foreach ($patch as $entry) {
    $old_count=substr_count($content,$entry['old']);
    $new_count=substr_count($content,$entry['new']);
    $old_within_new=str_contains($entry['new'],$entry['old']);
    if ($new_count===1 && $old_count===($old_within_new?1:0)) $states[]='new';
    elseif ($new_count===0 && $old_count===1) $states[]='old';
    else WP_CLI::error($identity.' '.$entry['id'].' passage was edited or duplicated; no content changed.');
}
if (count(array_unique($states))!==1) WP_CLI::error($identity.' passages are in a mixed state; no content changed.');
if ($mode==='check' || $states[0]==='new') {
    WP_CLI::success($identity.' '.($states[0]==='old'?'exact refinements pending.':'refinement already current.'));
    return;
}
foreach ($patch as $entry) $content=str_replace($entry['old'],$entry['new'],$content);
$saved=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($content)],true);
if (is_wp_error($saved) || get_post_field('post_content',$page->ID,'raw')!==$content) {
    $restored=wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($page->post_content)],true);
    WP_CLI::error(!is_wp_error($restored) && get_post_field('post_content',$page->ID,'raw')===$page->post_content
        ? $identity.' update failed; original content restored.' : $identity.' update failed; automatic rollback incomplete.');
}
WP_CLI::success($identity.' refined without changing unrelated editor content or SEO.');
