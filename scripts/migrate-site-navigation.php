<?php
/** Reorder only the known seven-link primary/footer menus; leave edited menus alone. */
$host = wp_parse_url(home_url('/'), PHP_URL_HOST);
$local = in_array($host, ['localhost', '127.0.0.1'], true);
$production = $host === 'tio2products.com'
    && wp_get_environment_type() === 'production'
    && getenv('TIO2_NAV_PRODUCTION_MIGRATION') === '1';
if (!$local && !$production) {
    WP_CLI::error('Navigation migration requires loopback or an explicitly authorized production run.');
}

$old = ['/', '/markets/', '/products/', '/applications/', '/documents/', '/resources/', '/about/'];
$target = ['/', '/products/', '/applications/', '/resources/', '/documents/', '/markets/', '/about/'];
$old_urls = array_map('home_url', $old);
$target_urls = array_map('home_url', $target);
$locations = get_nav_menu_locations();
if (empty($locations['primary'])) {
    WP_CLI::error('The primary menu is not assigned; no navigation was changed.');
}

$menus = [];
foreach (['primary', 'footer'] as $location) {
    $menu_id = (int) ($locations[$location] ?? 0);
    if (!$menu_id || isset($menus[$menu_id])) {
        continue; // The footer falls back to the primary menu when unassigned.
    }
    $items = wp_get_nav_menu_items($menu_id, ['orderby' => 'menu_order', 'order' => 'ASC']);
    if (!is_array($items) || count($items) !== count($target)) {
        WP_CLI::error("{$location} menu differs from the reviewed seven-link menu; no navigation was changed.");
    }
    $urls = [];
    foreach ($items as $item) {
        if ((int) $item->menu_item_parent !== 0 || $item->post_status !== 'publish') {
            WP_CLI::error("{$location} menu has a custom hierarchy or unpublished item; no navigation was changed.");
        }
        $urls[] = $item->url;
    }
    if ($urls !== $old_urls && $urls !== $target_urls) {
        WP_CLI::error("{$location} menu order or destinations were edited; no navigation was changed.");
    }
    $paths = array_map(fn($url) => wp_parse_url($url, PHP_URL_PATH) ?: '/', $urls);
    $menus[$menu_id] = ['location' => $location, 'items' => $items, 'paths' => $paths];
}

$pending = array_filter($menus, fn($menu) => $menu['paths'] !== $target);
if (!$pending) {
    WP_CLI::success('Header and footer navigation already follow the reviewed order.');
    return;
}
if (getenv('TIO2_NAV_DRY_RUN') === '1') {
    WP_CLI::success('Dry run: would reorder ' . implode(', ', array_column($pending, 'location')) . '; no menu was changed.');
    return;
}
foreach ($pending as $menu_id => $menu) {
    $by_path = array_combine($menu['paths'], $menu['items']);
    foreach ($target as $position => $path) {
        $result = wp_update_post(['ID' => $by_path[$path]->ID, 'menu_order' => $position + 1], true);
        if (is_wp_error($result)) {
            WP_CLI::error('Menu update failed; inspect the menu before retrying.');
        }
    }
    $items = wp_get_nav_menu_items($menu_id, ['orderby' => 'menu_order', 'order' => 'ASC']);
    $urls = array_map(fn($item) => $item->url, $items ?: []);
    if ($urls !== $target_urls) {
        WP_CLI::error('Menu order did not match the target; inspect the menu before retrying.');
    }
}
WP_CLI::success('Updated the reviewed header/footer navigation order.');
