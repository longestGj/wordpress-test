<?php
/** Preserve WordPress's untouched starter privacy draft under a non-conflicting slug.
 * This does not adopt or publish the draft. Run before import-utility-pages.php.
 */
if (!in_array(wp_parse_url(home_url(), PHP_URL_HOST), ['localhost','127.0.0.1','[::1]'], true)) WP_CLI::error('Local migration only.');
$id = (int) get_option('wp_page_for_privacy_policy');
$post = $id ? get_post($id) : null;
if ($post && tio2_owns_page($id, '_tio2_page_id', 'LEGAL-PRIV-EN')) {
    $draft = get_page_by_path('privacy-policy-wordpress-draft', OBJECT, 'page');
    if ($draft && $draft->post_status === 'draft' && get_post_meta($draft->ID, '_tio2_core_privacy_draft_relocated', true) === '1') WP_CLI::success('WordPress privacy starter draft was already relocated and the owned privacy page is selected.');
    else WP_CLI::error('Owned privacy page is selected, but the preserved starter draft could not be verified.');
    return;
}
if (!$post || $post->post_type !== 'page' || $post->post_status !== 'draft' || $post->post_parent) WP_CLI::error('Expected an unpublished WordPress privacy draft; no changes made.');
if ($post->post_name === 'privacy-policy-wordpress-draft' && get_post_meta($id, '_tio2_core_privacy_draft_relocated', true) === '1') { WP_CLI::success('WordPress privacy starter draft was already relocated.'); return; }
if ($post->post_name !== 'privacy-policy' || $post->post_title !== 'Privacy Policy' || get_post_meta($id, '_tio2_owner', true) !== '' || get_post_meta($id, '_tio2_page_id', true) !== '') WP_CLI::error('Privacy page is not the untouched WordPress starter draft; no changes made.');
$content = $post->post_content;
if (substr_count($content, 'privacy-policy-tutorial') < 5 || !str_contains($content, 'Who we are') || !str_contains($content, 'Comments') || !str_contains($content, 'Embedded content from other websites')) WP_CLI::error('Starter draft content has changed; review manually before moving it.');
if (get_page_by_path('privacy-policy-wordpress-draft', OBJECT, 'page')) WP_CLI::error('Preservation slug already exists; no changes made.');
$moved = wp_update_post(['ID'=>$id,'post_name'=>'privacy-policy-wordpress-draft'], true);
if (is_wp_error($moved) || !$moved) WP_CLI::error('Could not relocate the WordPress starter draft.');
update_post_meta($id, '_tio2_core_privacy_draft_relocated', '1');
WP_CLI::success('Preserved WordPress starter draft #'.$id.' at /privacy-policy-wordpress-draft/ (still draft).');
