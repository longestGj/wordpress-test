<?php
if (!defined('SITE_STARTER_LOCAL') || SITE_STARTER_LOCAL !== true ||
    !in_array(wp_parse_url(home_url(),PHP_URL_HOST), ['localhost','127.0.0.1'],true)) {
    WP_CLI::error('Local starter setup refused.');
}
if (get_option('site_starter_initialized')) {
    WP_CLI::success('Existing setup preserved.');
    return;
}
$theme = wp_get_theme('site-starter');
if (!$theme->exists() || $theme->errors()) WP_CLI::error('Starter theme is missing or invalid.');
switch_theme('site-starter');
update_option('blog_public', 0);
update_option('permalink_structure', '/%postname%/');
flush_rewrite_rules();
update_option('site_starter_initialized', 1);
WP_CLI::success('Starter activated; no business records imported.');
