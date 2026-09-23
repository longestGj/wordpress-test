<?php
/** Verify the three-year local inquiry cleanup using a disposable record. */
require __DIR__.'/local-only.php';
$date = gmdate('Y-m-d H:i:s', strtotime('-3 years -2 days'));
$id = wp_insert_post(['post_type'=>'tio2_inquiry','post_status'=>'private','post_title'=>'Codex retention fixture '.bin2hex(random_bytes(8)),'post_content'=>'{}','post_date'=>$date,'post_date_gmt'=>$date], true);
if (is_wp_error($id) || !$id) WP_CLI::error('Could not create retention fixture.');
try {
    do_action('tio2_expire_inquiries');
    if (get_post($id)) throw new RuntimeException('Expired inquiry was not removed.');
} catch (Throwable $error) {
    WP_CLI::warning($error->getMessage());
    $failed = true;
} finally {
    if (get_post($id)) wp_delete_post($id, true);
}
if (!empty($failed)) WP_CLI::error('Retention fixture failed; fixture removed.');
WP_CLI::success('Expired local inquiry removed; fixture cleaned.');
