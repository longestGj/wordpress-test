<?php
/** Apply exact GA4 disclosures to owned policy Pages without replacing editor work. */
$url = home_url('/');
$host = wp_parse_url($url, PHP_URL_HOST);
if (!in_array($host, ['localhost', '127.0.0.1', 'tio2products.com'], true)
    || ($host === 'tio2products.com' && getenv('TIO2_ALLOW_PUBLIC_CONTENT_MIGRATION') !== '1')) {
    WP_CLI::error('Run on a loopback preview, or explicitly authorize the production content migration.');
}

$patch = json_decode(file_get_contents(dirname(__DIR__).'/data/analytics-policy-patch.json'), true, 512, JSON_THROW_ON_ERROR);
$paths = ['LEGAL-COOKIE-EN'=>'cookie-policy', 'LEGAL-PRIV-EN'=>'privacy-policy', 'LEGAL-PRIV-MS'=>'ms/privacy-policy'];
$updates = [];
foreach ($paths as $identity=>$path) {
    $page = get_page_by_path($path, OBJECT, 'page');
    if (!$page || $page->post_status !== 'publish' || !tio2_owns_page($page->ID, '_tio2_page_id', $identity)) {
        WP_CLI::error('Owned published policy Page is missing: '.$identity);
    }
    $before = get_post_field('post_content', $page->ID, 'raw');
    $after = $before;
    foreach ($patch[$identity] as $entry) {
        // Accept the pre-brand GA4 disclosure as a historical completed state.
        $legacy_new = str_replace('TiO2Products', 'TiO2 Malaysia', $entry['new']);
        if (str_contains($after, $entry['new']) || str_contains($after, $legacy_new)) continue;
        if (isset($entry['marker']) && str_contains($after, $entry['marker'])) {
            WP_CLI::error('Existing analytics policy passage was edited; review manually: '.$identity);
        }
        if (substr_count($after, $entry['old']) !== 1) {
            WP_CLI::error('Policy passage changed; review before migration: '.$identity);
        }
        $after = str_replace($entry['old'], $entry['new'], $after);
    }
    $updates[] = [$page->ID, $before, $after];
}

if (getenv('TIO2_ANALYTICS_POLICY_DRY_RUN') === '1') {
    WP_CLI::success('Preflight passed for '.count($updates).' owned policy Pages; no database changes.');
    return;
}

$changed = [];
foreach ($updates as [$id, $before, $after]) {
    if ($before === $after) continue;
    $result = wp_update_post(['ID'=>$id, 'post_content'=>wp_slash($after)], true);
    if (is_wp_error($result) || get_post_field('post_content', $id, 'raw') !== $after) {
        wp_update_post(['ID'=>$id, 'post_content'=>wp_slash($before)]);
        foreach ($changed as [$saved_id, $saved_content]) {
            wp_update_post(['ID'=>$saved_id, 'post_content'=>wp_slash($saved_content)]);
        }
        WP_CLI::error('Policy update failed; inspect Page revisions: '.$id);
    }
    $changed[] = [$id, $before];
}
WP_CLI::success('Updated '.count($changed).' owned policy Pages; unrelated editor text preserved.');
