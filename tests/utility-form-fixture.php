<?php
/** Verify and remove only the uniquely named Contact HTTP test record. */
require __DIR__.'/local-only.php';
$subject = getenv('TIO2_TEST_SUBJECT');
if (!is_string($subject) || !preg_match('/^Codex utility QA [a-f0-9]{32}$/D', $subject)) WP_CLI::error('Missing exact test subject; refusing cleanup.');
global $wpdb;
$rows = $wpdb->get_results($wpdb->prepare("SELECT ID, post_content FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s AND post_title = %s", 'tio2_inquiry', 'private', $subject));
if (count($rows) > 1) WP_CLI::error('Multiple matching inquiries; refusing automatic cleanup.');
if (!$rows) { WP_CLI::success('No matching Contact test record to clean.'); return; }
$fields = json_decode($rows[0]->post_content, true);
$valid = is_array($fields) && ($fields['subject'] ?? '') === $subject && ($fields['full_name'] ?? '') === 'Acceptance Test' && ($fields['business_email'] ?? '') === 'qa@example.com';
if (!$valid) WP_CLI::error('Test record fields do not match; refusing automatic cleanup.');
wp_delete_post((int) $rows[0]->ID, true);
WP_CLI::success('Verified and removed one private local inquiry #'.$rows[0]->ID.'.');
