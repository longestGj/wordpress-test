<?php
/** Explicit, one-time patch for the owned Home page; never overwrite other editor content. */
$host = wp_parse_url(home_url('/'), PHP_URL_HOST);
if (!in_array($host, ['localhost', '127.0.0.1'], true)) {
    WP_CLI::error('Home card migration is restricted to local preview sites.');
}

$id = (int) get_option('page_on_front');
if (!$id || !tio2_owns_page($id, '_tio2_hub_key', 'home') || get_post_status($id) !== 'publish') {
    WP_CLI::error('The published front page is not the owned Home page.');
}

$content = get_post_field('post_content', $id, 'raw');
$gridStart = '<div class="product-grid">';
$actionsStart = '<div class="product-actions">';
if (substr_count($content, $gridStart) !== 1 || substr_count($content, $actionsStart) !== 1) {
    WP_CLI::error('Home product card boundaries have changed; no content was modified.');
}
$start = strpos($content, $gridStart);
$end = strpos($content, $actionsStart, $start);
if ($end === false || $end <= $start) {
    WP_CLI::error('Home product card order has changed; no content was modified.');
}

$block = substr($content, $start, $end - $start);
$rows = json_decode(file_get_contents('/workspace/data/product-discovery.json'), true, 512, JSON_THROW_ON_ERROR)['rows'] ?? [];
if (count($rows) !== 14) {
    WP_CLI::error('Expected fourteen approved grade routes; no content was modified.');
}

$changed = 0;
foreach ($rows as $row) {
    $grade = $row['grade'] ?? '';
    $path = $row['url'] ?? '';
    if (!preg_match('/^(?:M-[0-9]+|CR-901)$/D', $grade)
        || !preg_match('~^/products/[a-z0-9-]+/$~D', $path)
        || $path !== '/products/' . sanitize_title($grade) . '/') {
        WP_CLI::error('A grade route does not match its grade; no content was modified.');
    }
    $product = get_page_by_path(sanitize_title($grade), OBJECT, 'product');
    if (!$product || $product->post_type !== 'product'
        || $product->post_name !== sanitize_title($grade)
        || $product->post_status !== 'publish'
        || post_password_required($product)
        || wp_parse_url(get_permalink($product), PHP_URL_PATH) !== $path
        || !tio2_route_ready($path)) {
        WP_CLI::error('A grade route is unavailable; no content was modified.');
    }
    $old = '<span>' . esc_html($grade) . '</span>';
    $new = '<a href="' . esc_attr($path) . '">' . esc_html($grade) . '</a>';
    $oldCount = substr_count($block, $old);
    $newCount = substr_count($block, $new);
    if ($oldCount + $newCount !== 1) {
        WP_CLI::error('A grade card was edited or duplicated; no content was modified.');
    }
    if ($oldCount === 1) {
        $block = str_replace($old, $new, $block);
        ++$changed;
    }
}

if ($changed === 0) {
    WP_CLI::success('Home grade links already present; editor content preserved.');
    return;
}

$updated = substr($content, 0, $start) . $block . substr($content, $end);
$result = wp_update_post(['ID' => $id, 'post_content' => wp_slash($updated)], true);
if (is_wp_error($result) || get_post_field('post_content', $id, 'raw') !== $updated) {
    WP_CLI::error('Home page update failed; inspect its revisions before retrying.');
}
WP_CLI::success("Linked {$changed} Home grade labels; other editor content preserved.");
