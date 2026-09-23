<?php
require __DIR__.'/local-only.php';
/** Delete only the synthetic request fixture used by request-http.py. */
$posts=get_posts(['post_type'=>'tio2_request','post_status'=>'private','numberposts'=>-1]);$ids=[];
foreach($posts as $post) {
    $values=json_decode($post->post_content,true);
    if (!is_array($values) || (($values['company']??$values['company_organisation']??'')!=='Codex request test') || ($values['business_email']??'')!=='request-test@invalid.example') continue;
    $ids[]=$post->ID;
    wp_delete_post($post->ID,true);
}
global $wpdb;
$keys=$wpdb->get_col("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'tio2\\_request\\_once\\_%'");
foreach($keys as $key) {
    $value=get_option($key);
    if (is_array($value) && in_array($value['record_id']??0,$ids,true)) delete_option($key);
}
// This review instance is isolated from the user's main local site.
if (wp_parse_url(home_url(),PHP_URL_PORT)===8081) {
    $rates=$wpdb->get_col("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '\\_transient\\_tio2\\_request\\_rate\\_%'");
    foreach ($rates as $name) delete_transient(substr($name,strlen('_transient_')));
}
WP_CLI::success('Removed '.count($ids).' synthetic request records and their idempotency claims.');
