<?php
/** Import the five native pages for CONTACT / LEGAL / THANK.
 * Run after scripts/migrate-page-ownership.php on an existing installation.
 * The MS parent is a structural route and redirects to its privacy child.
 */
$seeds = [];
foreach (glob('/workspace/data/utility/*.json') as $file) {
    $seed = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    $path = ($seed['parent'] ? $seed['parent'].'/' : '').$seed['slug'];
    $existing = get_page_by_path($path, OBJECT, 'page');
    if ($existing && !tio2_owns_page($existing->ID, '_tio2_page_id', $seed['page_id'])) WP_CLI::error('Ownership collision at /'.$path.'/. No utility pages were imported.');
    $seeds[] = [$seed, $path, $existing];
}
if (count($seeds) !== 5) WP_CLI::error('Expected five utility page seeds; no changes made.');
$ms = get_page_by_path('ms', OBJECT, 'page');
if ($ms && !tio2_owns_page($ms->ID, '_tio2_page_id', 'SYS-MS-ROOT')) WP_CLI::error('Ownership collision at /ms/. No utility pages were imported.');
if (!$ms) {
    $id = wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Bahasa Malaysia','post_name'=>'ms','post_content'=>''], true);
    if (is_wp_error($id)) WP_CLI::error($id->get_error_message());
    update_post_meta($id, '_tio2_owner', 'tio2-wordpress');
    update_post_meta($id, '_tio2_page_id', 'SYS-MS-ROOT');
    $ms = get_post($id);
}
foreach ($seeds as [$seed, $path, $existing]) {
    if ($existing) { WP_CLI::log('Preserved '.$seed['page_id']); continue; }
    $id = wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>$seed['title'],'post_name'=>$seed['slug'],'post_parent'=>$seed['parent'] ? $ms->ID : 0,'post_content'=>wp_slash($seed['content'])], true);
    if (is_wp_error($id)) WP_CLI::error($id->get_error_message());
    foreach (['_tio2_owner'=>'tio2-wordpress','_tio2_page_id'=>$seed['page_id'],'_tio2_seo_title'=>$seed['seo_title'],'_tio2_seo_description'=>$seed['seo_description'],'_tio2_source'=>'data/utility/'.$seed['page_id'].'.json'] as $key=>$value) update_post_meta($id, $key, $value);
    WP_CLI::log('Imported '.$seed['page_id'].' at /'.$path.'/');
}
$privacy = get_page_by_path('privacy-policy', OBJECT, 'page');
$current_privacy = (int) get_option('wp_page_for_privacy_policy');
if ($privacy && tio2_owns_page($privacy->ID, '_tio2_page_id', 'LEGAL-PRIV-EN') && (!$current_privacy || get_post_meta($current_privacy, '_tio2_core_privacy_draft_relocated', true) === '1')) update_option('wp_page_for_privacy_policy', $privacy->ID);
flush_rewrite_rules();
WP_CLI::success('Utility pages ready; existing editor content preserved.');
