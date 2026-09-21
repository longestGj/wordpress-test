<?php
/**
 * Plugin Name: Starter Local Safety
 * Description: Blocks outbound WordPress mail and indexing only in explicitly local environments.
 */
defined('ABSPATH') || exit;
if (!defined('SITE_STARTER_LOCAL') || SITE_STARTER_LOCAL !== true) return;
add_filter('pre_wp_mail', '__return_false', PHP_INT_MAX);
add_filter('pre_option_blog_public', '__return_zero', PHP_INT_MAX);
add_filter('wp_robots', function () { return ['noindex'=>true,'nofollow'=>true]; }, PHP_INT_MAX);
add_filter('wp_sitemaps_enabled', '__return_false', PHP_INT_MAX);
add_action('send_headers', function () { header('X-Robots-Tag: noindex, nofollow', true); });
add_action('admin_notices', function () {
    if (current_user_can('manage_options')) {
        echo '<div class="notice notice-warning"><p>Local environment: WordPress mail and search indexing are disabled. This does not provide access control.</p></div>';
    }
});
