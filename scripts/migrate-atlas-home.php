<?php
/** Explicit local-only rebrand of the owned Home Page. Never infer ownership by slug. */
$host = wp_parse_url(home_url('/'), PHP_URL_HOST);
if (!in_array($host, ['localhost', '127.0.0.1'], true)) {
    WP_CLI::error('Atlas Home migration is local-only; no changes made.');
}

$id = (int) get_option('page_on_front');
if (!$id || get_post_status($id) !== 'publish' || !tio2_owns_page($id, '_tio2_hub_key', 'home')) {
    WP_CLI::error('Published owned Home page required; no changes made.');
}

$seed = json_decode(file_get_contents('/workspace/data/pages/home.json'), true, 512, JSON_THROW_ON_ERROR);
if (($seed['key'] ?? '') !== 'home' || ($seed['main_class'] ?? '') !== 'hub hub-home atlas-home'
    || empty($seed['content']) || empty($seed['seo_title']) || empty($seed['seo_description'])) {
    WP_CLI::error('Atlas Home seed is incomplete; no changes made.');
}

$current = get_post_field('post_content', $id, 'raw');
$currentTitle = get_post_meta($id, '_tio2_seo_title', true);
$currentDescription = get_post_meta($id, '_tio2_seo_description', true);
$currentClass = get_post_meta($id, '_tio2_main_class', true);
if ($current === $seed['content'] && $currentTitle === $seed['seo_title']
    && $currentDescription === $seed['seo_description'] && $currentClass === $seed['main_class']) {
    WP_CLI::success('Atlas Home already current; editor content preserved.');
    return;
}

$oldHashes = [
    '445d46c812e2670b7065ec71573f66b67497440c6724a7ea79edb443919e164e',
    'e9c160c32e846e2196af266fd13129ad9d757046a0f7bfe245950ee38f5b7dd5',
];
$oldTitle = 'Malaysia Titanium Dioxide Supplier | TiO₂ Malaysia';
$oldDescription = 'Explore titanium dioxide grades, applications, destination markets and document request paths through TiO₂ Malaysia for international industrial buyers.';
if (!in_array(hash('sha256', $current), $oldHashes, true)
    || $currentTitle !== $oldTitle || $currentDescription !== $oldDescription
    || $currentClass !== 'hub hub-home') {
    WP_CLI::error('Home body or SEO was edited; no changes made.');
}

$result = wp_update_post(['ID' => $id, 'post_content' => wp_slash($seed['content'])], true);
if (is_wp_error($result) || (int) $result !== $id) {
    WP_CLI::error('Home body update failed; inspect revisions before retrying.');
}
update_post_meta($id, '_tio2_main_class', $seed['main_class']);
update_post_meta($id, '_tio2_seo_title', $seed['seo_title']);
update_post_meta($id, '_tio2_seo_description', $seed['seo_description']);

clean_post_cache($id);
if (get_post_field('post_content', $id, 'raw') !== $seed['content']
    || get_post_meta($id, '_tio2_main_class', true) !== $seed['main_class']
    || get_post_meta($id, '_tio2_seo_title', true) !== $seed['seo_title']
    || get_post_meta($id, '_tio2_seo_description', true) !== $seed['seo_description']) {
    WP_CLI::error('Atlas Home read-back failed; inspect revisions before retrying.');
}
WP_CLI::success('Migrated owned Home to TiO2 Atlas; other Pages were unchanged.');
