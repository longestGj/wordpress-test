<?php
/** Apply verified hosting passages to two owned local privacy Pages. */
require '/workspace/tests/local-only.php';

$patch = json_decode(file_get_contents('/workspace/data/hosting-policy-patch.json'), true, 512, JSON_THROW_ON_ERROR);
$paths = ['LEGAL-PRIV-EN' => 'privacy-policy', 'LEGAL-PRIV-MS' => 'ms/privacy-policy'];
$changes = [];
foreach ($paths as $identity => $path) {
    $page = get_page_by_path($path, OBJECT, 'page');
    if (!$page || $page->post_status !== 'publish' || !tio2_owns_page($page->ID, '_tio2_page_id', $identity)) {
        WP_CLI::error('Owned published policy Page missing: ' . $identity);
    }
    $before = get_post_field('post_content', $page->ID, 'raw');
    $after = $before;
    foreach ($patch[$identity] as $entry) {
        if (str_contains($after, $entry['new'])) {
            if (str_contains($after, $entry['old'])) WP_CLI::error('Conflicting hosting passages: ' . $identity);
            continue;
        }
        if (substr_count($after, $entry['old']) !== 1) {
            WP_CLI::error('Hosting passage was edited; review manually: ' . $identity);
        }
        $after = str_replace($entry['old'], $entry['new'], $after);
    }
    if ($after !== $before) $changes[] = [$page->ID, $before, $after];
}

if (getenv('TIO2_HOSTING_POLICY_DRY_RUN') === '1') {
    WP_CLI::success('Preflight passed; ' . count($changes) . ' owned policy Pages would change.');
    return;
}

$done = [];
foreach ($changes as [$id, $before, $after]) {
    $result = wp_update_post(['ID' => $id, 'post_content' => wp_slash($after)], true);
    if (is_wp_error($result) || get_post_field('post_content', $id, 'raw') !== $after) {
        wp_update_post(['ID' => $id, 'post_content' => wp_slash($before)]);
        foreach ($done as [$saved_id, $saved_before]) {
            wp_update_post(['ID' => $saved_id, 'post_content' => wp_slash($saved_before)]);
        }
        WP_CLI::error('Hosting policy update failed; inspect Page revisions.');
    }
    $done[] = [$id, $before];
}
WP_CLI::success('Updated ' . count($done) . ' owned privacy Pages; unrelated editor text preserved.');
