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
$id=(int)$rows[0]->ID;
$mail=get_post_meta($id,'_tio2_notification_status',true);
$attempts=(int)get_post_meta($id,'_tio2_notification_attempts',true);
$expected=getenv('TIO2_TEST_EXPECT_MAIL');
$failure=$expected && ($mail!==$expected || $attempts!==1);
$keys=$wpdb->get_col("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'tio2\\_contact\\_once\\_%'");
foreach ($keys as $key) {
    $claim=get_option($key);
    if (is_array($claim) && (int)($claim['record_id']??0)===$id) {delete_option($key);wp_clear_scheduled_hook('tio2_contact_expire_claim',[$key]);}
}
wp_delete_post((int) $rows[0]->ID, true);
WP_CLI::log('Contact fixture #'.$id.': mail='.$mail.', attempts='.$attempts);
if ($failure) WP_CLI::error('Unexpected notification outcome; fixture removed.');
WP_CLI::success('Verified and removed one private local inquiry #'.$rows[0]->ID.'.');
