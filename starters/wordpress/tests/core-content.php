<?php
// This fixture must never operate on a non-local WordPress install.
if (!defined('SITE_STARTER_LOCAL') || SITE_STARTER_LOCAL !== true ||
    !in_array(wp_parse_url(home_url(), PHP_URL_HOST), ['localhost','127.0.0.1'], true)) {
    WP_CLI::error('Local-only fixture refused.');
}
function starter_check($ok, $message) { if (!$ok) throw new RuntimeException($message); }
$id = 0;
try {
    starter_check(get_stylesheet() === 'site-starter', 'Starter theme must be active.');
    starter_check(!post_type_exists('product'), 'Core must not require a Product model.');
    starter_check(wp_mail('nobody@example.test', 'Fixture', 'Must not send') === false, 'Local mail must be blocked.');
    $id = wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_title'=>'Core fixture',
        'post_content'=>'<!-- wp:paragraph --><p>Original fixture copy.</p><!-- /wp:paragraph -->'], true);
    starter_check(!is_wp_error($id), 'Insert failed.');
    $revision = wp_save_post_revision($id);
    if (!$revision) {
        $revisions = wp_get_post_revisions($id);
        $revision = array_key_first($revisions);
    }
    wp_update_post(['ID'=>$id,'post_content'=>'<!-- wp:paragraph --><p>Changed fixture copy.</p><!-- /wp:paragraph -->']);
    starter_check(str_contains(get_post_field('post_content',$id),'Changed fixture'), 'Edit failed.');
    starter_check((bool)wp_restore_post_revision($revision), 'Revision restore failed.');
    starter_check(str_contains(get_post_field('post_content',$id),'Original fixture'), 'Restore value mismatch.');
    WP_CLI::success('Core page edit/revision restore, independent theme and local mail guard passed.');
} finally {
    if (is_int($id) && $id > 0) wp_delete_post($id, true);
}
