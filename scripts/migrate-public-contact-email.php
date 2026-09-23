<?php
/** Explicit one-time mailbox update for four owned Pages; preserve other editor content. */
$site = home_url('/');
$host = wp_parse_url($site, PHP_URL_HOST);
if (!in_array($host, ['localhost', '127.0.0.1', 'tio2products.com'], true)
    || ($host === 'tio2products.com' && getenv('TIO2_ALLOW_PUBLIC_CONTENT_MIGRATION') !== '1')) {
    WP_CLI::error('Run on a loopback preview, or explicitly authorize the production content migration.');
}

$paths = [
    'CONTACT-001' => 'contact',
    'LEGAL-PRIV-EN' => 'privacy-policy',
    'LEGAL-PRIV-MS' => 'ms/privacy-policy',
    'LEGAL-COOKIE-EN' => 'cookie-policy',
];
$updates = [];
foreach ($paths as $identity => $path) {
    $page = get_page_by_path($path, OBJECT, 'page');
    if (!$page || !tio2_owns_page($page->ID, '_tio2_page_id', $identity)
        || $page->post_status !== 'publish') {
        WP_CLI::error('Owned published utility Page is missing: ' . $identity);
    }
    $before = get_post_field('post_content', $page->ID, 'raw');
    $after = str_replace('info@tio2malaysia.com', 'info@tio2products.com', $before);
    if (str_contains($after, 'tio2malaysia.com')) {
        WP_CLI::error('Another legacy domain reference requires review: ' . $identity);
    }
    $updates[] = [$page->ID, $before, $after];
}

$changed = 0;
foreach ($updates as [$id, $before, $after]) {
    if ($before === $after) continue;
    $result = wp_update_post(['ID' => $id, 'post_content' => wp_slash($after)], true);
    if (is_wp_error($result) || get_post_field('post_content', $id, 'raw') !== $after) {
        WP_CLI::error('Mailbox update failed; inspect Page revisions before retrying: ' . $id);
    }
    ++$changed;
}
WP_CLI::success("Updated {$changed} owned Pages; unrelated editor content preserved.");
