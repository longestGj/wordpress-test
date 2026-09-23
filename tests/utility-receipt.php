<?php
// Standalone pure-function regression; never connects to WordPress or its database.
define('ABSPATH', __DIR__);
define('MINUTE_IN_SECONDS', 60);
define('HOUR_IN_SECONDS', 3600);
define('DAY_IN_SECONDS', 86400);
$transients = [];
function add_action(...$args) {}
function add_shortcode(...$args) {}
function wp_unslash($value) { return stripslashes($value); }
function sanitize_text_field($value) { return trim(strip_tags($value)); }
function sanitize_textarea_field($value) { return trim(strip_tags($value)); }
function is_email($value) { return filter_var($value, FILTER_VALIDATE_EMAIL); }
function wp_salt($scheme) { return 'test-salt'; }
function get_transient($key) { global $transients; return $transients[$key] ?? false; }
function set_transient($key, $value, $duration) { global $transients, $transient_fail; if (!empty($transient_fail)) return false; $transients[$key] = $value; return true; }
function home_url($path) { return 'http://localhost:8080'.$path; }
function add_query_arg($key, $value, $url) { return $url.'?'.$key.'='.$value; }
function is_ssl() { return false; }
class WP_Error { public function __construct($code, $message) {} }
require __DIR__.'/../wp-content/plugins/tio2-products/utility.php';
function check($condition, $message) { if (!$condition) { fwrite(STDERR, "FAIL: $message\n"); exit(1); } }
$valid = ['full_name'=>'Ana Lee','company'=>'Example Ltd','business_email'=>'ana@example.com','country'=>'Malaysia','subject'=>'Partnership','message'=>'Please contact us.'];
[$values, $errors] = tio2_validate_contact($valid);
check(!$errors && $values['business_email'] === 'ana@example.com', 'valid inquiry rejected');
$invalid = $valid; $invalid['business_email'] = 'invalid'; $invalid['message'] = str_repeat('x', 2001);
[, $errors] = tio2_validate_contact($invalid);
check(isset($errors['business_email'], $errors['message']), 'invalid inquiry accepted');
$_GET['receipt'] = str_repeat('a', 48);
check(tio2_request_receipt_kind() === '', 'forged URL without session accepted');
$_COOKIE['tio2_flow'] = str_repeat('b', 64);
check(tio2_request_receipt_kind() === '', 'forged URL with session accepted');
$url = tio2_issue_request_receipt('documents');
parse_str(parse_url($url, PHP_URL_QUERY), $_GET);
check(tio2_request_receipt_kind() === 'documents', 'acknowledged request rejected');
$_COOKIE['tio2_flow'] = str_repeat('c', 64);
check(tio2_request_receipt_kind() === '', 'different browser session accepted');
$transient_fail = true;
check(tio2_issue_request_receipt('documents') instanceof WP_Error, 'failed receipt storage reported success');
$transient_fail = false;
check(tio2_issue_request_receipt('contact') instanceof WP_Error, 'unsupported receipt kind accepted');
echo "PASS: contact validation and browser-bound receipt checks\n";
