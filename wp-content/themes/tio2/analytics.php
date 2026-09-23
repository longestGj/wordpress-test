<?php
defined('ABSPATH') || exit;

/** Expose the public Measurement ID only on the indexable canonical production host. */
function tio2_ga4_measurement_id() {
    $id = 'G-SY6PZPX0VR';
    $url = home_url('/');
    if (wp_get_environment_type() !== 'production'
        || !get_option('blog_public')
        || wp_parse_url($url, PHP_URL_SCHEME) !== 'https'
        || wp_parse_url($url, PHP_URL_HOST) !== 'tio2products.com'
        || strtolower($_SERVER['HTTP_HOST'] ?? '') !== 'tio2products.com'
        || !is_ssl()
        || !preg_match('/^G-[A-Z0-9]{7,}$/', $id)) return '';
    return $id;
}
