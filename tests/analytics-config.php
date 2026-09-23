<?php
/** Production-only GA4 gate, exercised without bootstrapping WordPress. */
define('ABSPATH', '/test/');
$environment = 'production';
$home = 'https://tio2products.com/';
$public = true;
$request_host = 'tio2products.com';
$secure = true;
function wp_get_environment_type() { return $GLOBALS['environment']; }
function home_url($path = '/') { return $GLOBALS['home']; }
function get_option($name) { return $name === 'blog_public' ? $GLOBALS['public'] : null; }
function wp_parse_url($url, $component = -1) { return parse_url($url, $component); }
function is_ssl() { return $GLOBALS['secure']; }
require dirname(__DIR__).'/wp-content/themes/tio2/analytics.php';

$cases = [
    ['production','https://tio2products.com/',true,'tio2products.com',true,'G-SY6PZPX0VR'],
    ['staging','https://tio2products.com/',true,'tio2products.com',true,''],
    ['production','http://tio2products.com/',true,'tio2products.com',true,''],
    ['production','https://www.tio2products.com/',true,'tio2products.com',true,''],
    ['production','http://localhost:18080/',true,'localhost:18080',false,''],
    ['production','https://tio2products.com/',false,'tio2products.com',true,''],
    ['production','https://tio2products.com/',true,'preview.tio2products.com',true,''],
    ['production','https://tio2products.com/',true,'tio2products.com',false,''],
];
foreach ($cases as [$environment, $home, $public, $request_host, $secure, $expected]) {
    $_SERVER['HTTP_HOST'] = $request_host;
    $actual = tio2_ga4_measurement_id();
    if ($actual !== $expected) {
        fwrite(STDERR, "FAIL: {$environment}, {$home}, request={$request_host}, HTTPS=".(int)$secure." produced {$actual}\n");
        exit(1);
    }
}
echo "PASS: GA4 ID only on indexable HTTPS production host\n";
