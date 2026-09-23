<?php
/** Serial local fixture test: importer preserves edits and rejects slug adoption. */
require __DIR__.'/local-only.php';
$post = get_page_by_path('contact', OBJECT, 'page');
if (!$post || !tio2_owns_page($post->ID, '_tio2_page_id', 'CONTACT-001')) WP_CLI::error('Owned Contact page required.');
$id = $post->ID;
$original_seo = get_post_meta($id, '_tio2_seo_title', true);
$original_owner = get_post_meta($id, '_tio2_owner', true);
$run = fn() => WP_CLI::runcommand('eval-file /workspace/scripts/import-utility-pages.php', ['return'=>'all','exit_error'=>false,'launch'=>true]);
$failed = '';
try {
    update_post_meta($id, '_tio2_seo_title', 'Codex editor-preservation fixture');
    $result = $run();
    if ($result->return_code !== 0 || substr_count($result->stdout, 'Preserved ') !== 5) throw new RuntimeException('Repeat import failed.');
    clean_post_cache($id);
    if (get_post_meta($id, '_tio2_seo_title', true) !== 'Codex editor-preservation fixture') throw new RuntimeException('Repeat import overwrote edited SEO.');
    delete_post_meta($id, '_tio2_owner');
    $result = $run();
    if ($result->return_code === 0 || !str_contains($result->stderr, 'Ownership collision')) throw new RuntimeException('Importer adopted an unowned slug.');
} catch (Throwable $error) {
    $failed = $error->getMessage();
} finally {
    update_post_meta($id, '_tio2_seo_title', wp_slash($original_seo));
    update_post_meta($id, '_tio2_owner', wp_slash($original_owner));
}
if ($failed) WP_CLI::error($failed.' Fixtures restored.');
if (get_post_meta($id, '_tio2_seo_title', true) !== $original_seo || get_post_meta($id, '_tio2_owner', true) !== $original_owner) WP_CLI::error('Could not restore Contact fixtures.');
WP_CLI::success('Repeat import preserved edited SEO; missing ownership was rejected; fixtures restored.');
