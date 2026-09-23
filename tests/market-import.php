<?php
/** Local-only repeat-import and ownership regression; restores all edited fixtures. */
require __DIR__.'/local-only.php';
$paths = ['european-union','germany','italy','spain','poland','netherlands','belgium','united-kingdom','india','brazil'];
foreach ($paths as $path) {
    $page = get_page_by_path('markets/'.$path, OBJECT, 'page');
    if (!$page || !tio2_market_id($page->ID)) WP_CLI::error('Import the eleven owned market Pages first.');
}
$pt = get_page_by_path('pt-br/markets/brazil', OBJECT, 'page');
if (!$pt || tio2_market_id($pt->ID) !== 'MARKET-BR-PT') WP_CLI::error('Missing owned Brazil Portuguese Page.');
$post = get_page_by_path('markets/germany', OBJECT, 'page');
$id = $post->ID;
$original = ['content'=>$post->post_content, 'seo'=>get_post_meta($id,'_tio2_seo_title',true),
    'owner'=>get_post_meta($id,'_tio2_owner',true), 'modified'=>$post->post_modified,
    'modified_gmt'=>$post->post_modified_gmt];
$revisions = array_keys(wp_get_post_revisions($id));
$check = function ($ok, $message) { if (!$ok) throw new RuntimeException($message); };
$run = function () { return WP_CLI::runcommand('eval-file /workspace/scripts/import-markets.php',
    ['return'=>'all','exit_error'=>false,'launch'=>true]); };
try {
    wp_update_post(['ID'=>$id,'post_content'=>wp_slash($original['content'].'<!-- market editor preservation fixture -->')]);
    update_post_meta($id,'_tio2_seo_title','Market editor SEO fixture');
    $result = $run();
    $check($result->return_code === 0 && substr_count($result->stdout,'Preserved ') === 11, 'Repeat import failed');
    clean_post_cache($id);
    $check(str_contains(get_post_field('post_content',$id),'market editor preservation fixture'), 'Editor body overwritten');
    $check(get_post_meta($id,'_tio2_seo_title',true) === 'Market editor SEO fixture', 'Editor SEO overwritten');
    delete_post_meta($id,'_tio2_owner');
    $result = $run();
    $check($result->return_code !== 0 && str_contains($result->stderr,'ownership collision'), 'Ownerless Page adopted');
} finally {
    wp_update_post(['ID'=>$id,'post_content'=>wp_slash($original['content'])]);
    update_post_meta($id,'_tio2_seo_title',wp_slash($original['seo']));
    update_post_meta($id,'_tio2_owner',$original['owner']);
    foreach (array_diff(array_keys(wp_get_post_revisions($id)),$revisions) as $revision) wp_delete_post_revision($revision);
    global $wpdb;
    $wpdb->update($wpdb->posts,['post_modified'=>$original['modified'],'post_modified_gmt'=>$original['modified_gmt']],['ID'=>$id]);
    clean_post_cache($id);
}
$check(get_post_field('post_content',$id) === $original['content'], 'Original content not restored');
$check(get_post_meta($id,'_tio2_seo_title',true) === $original['seo'], 'Original SEO not restored');
$check(get_post_meta($id,'_tio2_owner',true) === $original['owner'], 'Original ownership not restored');
WP_CLI::success('Eleven Pages preserved; editor body/SEO retained, ownerless collision rejected, fixture restored.');
