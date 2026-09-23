<?php
/** One-time Home update. Refuse to replace edited content or unowned pages. */
$host = wp_parse_url(home_url('/'), PHP_URL_HOST);
$local = in_array($host, ['localhost', '127.0.0.1'], true);
$production = $host === 'tio2products.com'
    && wp_get_environment_type() === 'production'
    && getenv('TIO2_HOME_GLOBAL_PRODUCTION_MIGRATION') === '1';
if (!$local && !$production) {
    WP_CLI::error('Home migration requires loopback or an explicitly authorized production run.');
}

$id = (int) get_option('page_on_front');
if (!$id || !tio2_owns_page($id, '_tio2_hub_key', 'home') || get_post_status($id) !== 'publish') {
    WP_CLI::error('The published front page is not the owned Home page.');
}

$target = json_decode(file_get_contents('/workspace/data/pages/home.json'), true, 512, JSON_THROW_ON_ERROR);
if (($target['key'] ?? '') !== 'home') {
    WP_CLI::error('The Home seed identity is invalid.');
}
$current = get_post_field('post_content', $id, 'raw');
$current_hash = hash('sha256', $current);
$allowed_hashes = [
    // Former approved Home seed with the 14 linked product grades.
    'e9c160c32e846e2196af266fd13129ad9d757046a0f7bfe245950ee38f5b7dd5',
    // Former Home seed before the one-time grade-link migration.
    '445d46c812e2670b7065ec71573f66b67497440c6724a7ea79edb443919e164e',
];
$target_content = $target['content'];
if ($current !== $target_content && !in_array($current_hash, $allowed_hashes, true)) {
    WP_CLI::error('Home editor content differs from the reviewed baseline; no content was changed.');
}

$old_title = 'Malaysia Titanium Dioxide Supplier | TiO₂ Malaysia';
$old_description = 'Explore titanium dioxide grades, applications, destination markets and document request paths through TiO₂ Malaysia for international industrial buyers.';
$title = get_post_meta($id, '_tio2_seo_title', true);
$description = get_post_meta($id, '_tio2_seo_description', true);
if (!in_array($title, [$old_title, $target['seo_title']], true)
    || !in_array($description, [$old_description, $target['seo_description']], true)) {
    WP_CLI::error('Home SEO fields were edited; no content was changed.');
}

$content_changed = $current !== $target_content;
$seo_changed = $title !== $target['seo_title'] || $description !== $target['seo_description'];
if (!$content_changed && !$seo_changed) {
    WP_CLI::success('Home positioning already current; editor content preserved.');
    return;
}
if (getenv('TIO2_HOME_GLOBAL_DRY_RUN') === '1') {
    WP_CLI::success('Dry run: would update Home content=' . ($content_changed ? 'yes' : 'no')
        . ', SEO=' . ($seo_changed ? 'yes' : 'no') . '; no data changed.');
    return;
}

update_post_meta($id, '_tio2_seo_title', $target['seo_title']);
update_post_meta($id, '_tio2_seo_description', $target['seo_description']);
if (get_post_meta($id, '_tio2_seo_title', true) !== $target['seo_title']
    || get_post_meta($id, '_tio2_seo_description', true) !== $target['seo_description']) {
    update_post_meta($id, '_tio2_seo_title', $title);
    update_post_meta($id, '_tio2_seo_description', $description);
    WP_CLI::error('Home SEO update failed; original metadata restored.');
}
if ($content_changed) {
    $result = wp_update_post(['ID' => $id, 'post_content' => wp_slash($target_content)], true);
    if (is_wp_error($result) || get_post_field('post_content', $id, 'raw') !== $target_content) {
        update_post_meta($id, '_tio2_seo_title', $title);
        update_post_meta($id, '_tio2_seo_description', $description);
        WP_CLI::error('Home content update failed; original SEO metadata restored.');
    }
} elseif ($seo_changed) {
    wp_save_post_revision($id);
}
$revisions = wp_get_post_revisions($id, ['posts_per_page' => 1]);
$latest = $revisions ? reset($revisions) : null;
if (!$latest || get_post_meta($latest->ID, '_tio2_seo_title', true) !== $target['seo_title']
    || get_post_meta($latest->ID, '_tio2_seo_description', true) !== $target['seo_description']) {
    WP_CLI::error('Home is updated, but its latest revision lacks matching SEO metadata; inspect revisions.');
}
WP_CLI::success('Updated the owned Home page content and SEO fields.');
