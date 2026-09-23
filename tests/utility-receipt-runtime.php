<?php
/** Local transient fixture for the receipt gate; no active receiver is simulated. */
require __DIR__.'/local-only.php';
$session = bin2hex(random_bytes(32));
$_COOKIE['tio2_flow'] = $session;
$url = tio2_issue_request_receipt('documents');
if (is_wp_error($url)) WP_CLI::error('Could not issue test receipt.');
parse_str((string) wp_parse_url($url, PHP_URL_QUERY), $query);
$token = $query['receipt'] ?? '';
$key = 'tio2_receipt_'.hash('sha256', $token);
try {
    $_GET['receipt'] = $token;
    if (tio2_request_receipt_kind() !== 'documents') WP_CLI::error('Valid browser-bound receipt rejected.');
    $_COOKIE['tio2_flow'] = bin2hex(random_bytes(32));
    if (tio2_request_receipt_kind() !== '') WP_CLI::error('Another browser session accepted the receipt.');
    unset($_COOKIE['tio2_flow']);
    if (tio2_request_receipt_kind() !== '') WP_CLI::error('Direct access accepted the receipt.');
} finally {
    delete_transient($key);
    unset($_GET['receipt'], $_COOKIE['tio2_flow']);
}
WP_CLI::success('Receipt gate accepts only its originating browser session; transient fixture removed.');
