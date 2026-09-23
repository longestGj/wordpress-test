<?php
/** Local-only Atlas Home migration regression; every changed fixture is restored. */
require __DIR__.'/local-only.php';

$id = (int) get_option('page_on_front');
if (!$id || !tio2_owns_page($id, '_tio2_hub_key', 'home')) WP_CLI::error('Owned Home is required.');
$old = json_decode(file_get_contents(__DIR__.'/fixtures/home-pre-atlas.json'), true, 512, JSON_THROW_ON_ERROR);
$new = json_decode(file_get_contents('/workspace/data/pages/home.json'), true, 512, JSON_THROW_ON_ERROR);
$originalPost = get_post($id);
$metaKeys = ['_tio2_owner','_tio2_hub_key','_tio2_main_class','_tio2_seo_title','_tio2_seo_description'];
$originalMeta = [];
foreach ($metaKeys as $key) $originalMeta[$key] = get_post_meta($id, $key, true);
$originalHome = get_option('home');
$originalRevisions = array_keys(wp_get_post_revisions($id));
$check = static function ($ok, $message) { if (!$ok) throw new RuntimeException($message); };
$run = static function () {
    return WP_CLI::runcommand('eval-file /workspace/scripts/migrate-atlas-home.php',
        ['return'=>'all','exit_error'=>false,'launch'=>true]);
};
$read = static function () use ($id, $metaKeys) {
    clean_post_cache($id);
    wp_cache_delete($id, 'post_meta');
    $post = get_post($id);
    $meta = [];
    foreach ($metaKeys as $key) $meta[$key] = get_post_meta($id, $key, true);
    return ['content'=>$post->post_content,'modified'=>$post->post_modified,'modified_gmt'=>$post->post_modified_gmt,'meta'=>$meta];
};
$set = static function ($content, $seo, $description, $mainClass) use ($id) {
    wp_update_post(['ID'=>$id,'post_content'=>wp_slash($content)]);
    update_post_meta($id, '_tio2_seo_title', $seo);
    update_post_meta($id, '_tio2_seo_description', $description);
    update_post_meta($id, '_tio2_main_class', $mainClass);
};
$linked = $old['content'];
$check(hash('sha256', $linked) === 'e9c160c32e846e2196af266fd13129ad9d757046a0f7bfe245950ee38f5b7dd5', 'Linked old fixture hash changed.');
$unlinked = $linked;
$rows = json_decode(file_get_contents('/workspace/data/product-discovery.json'), true, 512, JSON_THROW_ON_ERROR)['rows'];
foreach ($rows as $row) {
    $grade = $row['grade'];
    $path = $row['url'];
    $unlinked = str_replace('<a href="'.$path.'">'.$grade.'</a>', '<span>'.$grade.'</span>', $unlinked);
}
$check(hash('sha256', $unlinked) === '445d46c812e2670b7065ec71573f66b67497440c6724a7ea79edb443919e164e', 'Unlinked old fixture hash changed.');

try {
    foreach ([$linked, $unlinked] as $body) {
        $set($body, $old['seo_title'], $old['seo_description'], $old['main_class']);
        $result = $run();
        $check($result->return_code === 0, 'Approved old Home did not migrate: '.$result->stderr);
        $state = $read();
        $check($state['content'] === $new['content'], 'Atlas body was not installed.');
        foreach (['_tio2_main_class'=>'main_class','_tio2_seo_title'=>'seo_title','_tio2_seo_description'=>'seo_description'] as $meta=>$seedKey) {
            $check($state['meta'][$meta] === $new[$seedKey], 'Atlas metadata mismatch: '.$meta);
        }
        $beforeRepeat = $state;
        $result = $run();
        $check($result->return_code === 0 && str_contains($result->stdout, 'already current'), 'Repeat migration failed.');
        $check($read() === $beforeRepeat, 'Repeat migration changed post or metadata.');
    }

    $set($linked.'<!-- edited -->', $old['seo_title'], $old['seo_description'], $old['main_class']);
    $before = $read();
    $result = $run();
    $check($result->return_code !== 0 && $read() === $before, 'Edited body was overwritten.');

    $set($linked, 'Editor SEO title', $old['seo_description'], $old['main_class']);
    $before = $read();
    $result = $run();
    $check($result->return_code !== 0 && $read() === $before, 'Edited SEO was overwritten.');

    $set($linked, $old['seo_title'], $old['seo_description'], $old['main_class']);
    delete_post_meta($id, '_tio2_owner');
    $before = $read();
    $result = $run();
    $check($result->return_code !== 0 && $read() === $before, 'Ownerless Home was adopted.');
    update_post_meta($id, '_tio2_owner', $originalMeta['_tio2_owner']);

    update_option('home', 'https://nonlocal.example.test');
    $before = $read();
    $result = $run();
    $check($result->return_code !== 0 && $read() === $before, 'Non-local URL was accepted.');
} finally {
    update_option('home', $originalHome);
    wp_update_post(['ID'=>$id,'post_content'=>wp_slash($originalPost->post_content)]);
    foreach ($originalMeta as $key=>$value) update_post_meta($id, $key, wp_slash($value));
    foreach (array_diff(array_keys(wp_get_post_revisions($id)), $originalRevisions) as $revision) wp_delete_post_revision($revision);
    global $wpdb;
    $wpdb->update($wpdb->posts,
        ['post_modified'=>$originalPost->post_modified,'post_modified_gmt'=>$originalPost->post_modified_gmt],
        ['ID'=>$id]);
    clean_post_cache($id);
    wp_cache_delete($id, 'post_meta');
}
$final = $read();
$check($final['content'] === $originalPost->post_content, 'Home body fixture not restored.');
foreach ($originalMeta as $key=>$value) $check($final['meta'][$key] === $value, 'Home metadata fixture not restored: '.$key);
$check($final['modified'] === $originalPost->post_modified && $final['modified_gmt'] === $originalPost->post_modified_gmt, 'Home timestamps not restored.');
WP_CLI::success('Atlas migration is idempotent, protects edits/ownership/host, and restores fixtures.');
