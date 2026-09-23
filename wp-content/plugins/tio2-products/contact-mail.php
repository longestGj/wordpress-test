<?php
defined('ABSPATH') || exit;

function tio2_contact_claim_key($session,$token) {
    return 'tio2_contact_once_'.hash_hmac('sha256',$session.'|'.$token,wp_salt('auth'));
}
/** Save a validated inquiry exactly once per browser submission token. */
function tio2_store_contact_once($values,$session,$token) {
    if (!preg_match('/^[a-f0-9]{64}$/D',$token) || !preg_match('/^[a-f0-9]{64}$/D',$session)) return new WP_Error('invalid_token','Reload the form and try again.');
    $claim=tio2_contact_claim_key($session,$token);
    if (!add_option($claim,['pending'=>time()],'',false)) {
        $saved=get_option($claim);
        if (is_array($saved) && !empty($saved['record_id'])) return ['id'=>(int)$saved['record_id'],'new'=>false];
        return new WP_Error('pending','This inquiry is already being processed. Please wait.');
    }
    $id=0;
    try {
        if (!wp_schedule_single_event(time()+DAY_IN_SECONDS,'tio2_contact_expire_claim',[$claim])) throw new RuntimeException('Claim expiry could not be scheduled.');
        $id=wp_insert_post(['post_type'=>'tio2_inquiry','post_status'=>'private','post_title'=>$values['subject'],'post_content'=>wp_slash(wp_json_encode($values,JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE))],true);
        if (is_wp_error($id) || !$id) {$id=0;throw new RuntimeException('Inquiry could not be saved.');}
        if (!update_post_meta($id,'_tio2_notification_status','not_configured') || !update_option($claim,['record_id'=>$id],false)) throw new RuntimeException('Inquiry confirmation could not be saved.');
        return ['id'=>$id,'new'=>true];
    } catch (Throwable $error) {
        if ($id) wp_delete_post($id,true);
        delete_option($claim);wp_clear_scheduled_hook('tio2_contact_expire_claim',[$claim]);
        return new WP_Error('storage_failed','We could not save your inquiry. Please try again.');
    }
}
add_action('tio2_contact_expire_claim',static function ($claim) {
    if (is_string($claim) && str_starts_with($claim,'tio2_contact_once_')) delete_option($claim);
});

function tio2_contact_mail_admin($post) {
    if (!current_user_can('manage_options')) return;
    $status=get_post_meta($post->ID,'_tio2_notification_status',true)?:'not_configured';
    echo '<p>Email notification: '.esc_html($status).'</p>';
    if ($status==='accepted') return;
    // Core's edit screen already contains a form: use a separate form attribute.
    $form='tio2-contact-retry-'.$post->ID;
    $uncertain=in_array($status,['sending','unknown'],true);
    if ($uncertain) echo '<p>The last delivery outcome is uncertain. Check the mailbox before retrying; a retry may send a duplicate.</p><p><label><input type="checkbox" form="'.esc_attr($form).'" name="confirmed_retry" value="1" required> I checked the mailbox and want to retry.</label></p>';
    echo '<button class="button" type="submit" form="'.esc_attr($form).'">Retry email notification</button>';
    add_action('admin_footer',static function () use ($post,$form) {
        echo '<form id="'.esc_attr($form).'" method="post" action="'.esc_url(admin_url('admin-post.php')).'"><input type="hidden" name="action" value="tio2_contact_retry_notification"><input type="hidden" name="inquiry_id" value="'.esc_attr($post->ID).'">'.wp_nonce_field('tio2_contact_retry_'.$post->ID,'tio2_nonce',true,false).'</form>';
    });
}
add_action('admin_post_tio2_contact_retry_notification',static function () {
    if (!current_user_can('manage_options')) wp_die('Not authorized','',['response'=>403]);
    $id=absint($_POST['inquiry_id']??0);$post=get_post($id);
    $nonce=isset($_POST['tio2_nonce']) && is_string($_POST['tio2_nonce'])?sanitize_text_field(wp_unslash($_POST['tio2_nonce'])):'';
    if (!$post || $post->post_type!=='tio2_inquiry' || $post->post_status!=='private' || !wp_verify_nonce($nonce,'tio2_contact_retry_'.$id)) wp_die('Invalid request','',['response'=>403]);
    tio2_request_send_notification($id,($_POST['confirmed_retry']??'')==='1');
    wp_safe_redirect(admin_url('post.php?post='.$id.'&action=edit'),303);exit;
});
add_filter('manage_tio2_inquiry_posts_columns',static function ($columns) {
    if (current_user_can('manage_options')) $columns['tio2_mail']='Email notification';
    return $columns;
});
add_action('manage_tio2_inquiry_posts_custom_column',static function ($column,$id) {
    if ($column==='tio2_mail' && current_user_can('manage_options')) echo esc_html(get_post_meta($id,'_tio2_notification_status',true)?:'not_configured');
},10,2);
