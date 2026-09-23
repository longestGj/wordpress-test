<?php
defined('ABSPATH') || exit;
/** Private request receiver; buyer confirmation depends on saving, not on mail. */
add_action('init', function () {
    register_post_type('tio2_request', [
        'label'=>'Business requests', 'public'=>false, 'show_ui'=>false, 'show_in_rest'=>false,
        'exclude_from_search'=>true, 'supports'=>['title'],
    ]);
    if (!wp_next_scheduled('tio2_expire_requests')) wp_schedule_event(time()+DAY_IN_SECONDS,'daily','tio2_expire_requests');
});
add_action('tio2_expire_requests', function () {
    do {
        $old=get_posts(['post_type'=>'tio2_request','post_status'=>'private','date_query'=>[['before'=>'3 years ago']],'posts_per_page'=>100,'fields'=>'ids']);
        $removed=0;
        foreach ($old as $id) if (wp_delete_post($id,true)) $removed++;
    } while (count($old)===100 && $removed>0);
});
function tio2_request_page_ids() { return ['quote'=>'CONV-RFQ','documents'=>'CONV-DOC','sample'=>'CONV-SAMPLE']; }
function tio2_request_paths() { return ['quote'=>'/request-a-quote/','documents'=>'/request-documents/','sample'=>'/request-sample/']; }
function tio2_request_kind($id) {
    $identity=get_post_meta($id,'_tio2_page_id',true);
    foreach (tio2_request_page_ids() as $kind=>$expected)
        if ($identity===$expected && tio2_owns_page($id,'_tio2_page_id',$expected)) return $kind;
    return '';
}
function tio2_request_result_key($session,$kind) {
    return 'tio2_request_result_'.hash_hmac('sha256',$kind.'|'.$session,wp_salt('auth'));
}
function tio2_request_posted($kind) {
    $method=strtoupper($_SERVER['REQUEST_METHOD']??'');
    if ($method!=='POST') wp_die('Method not allowed','',['response'=>405]);
    if (!tio2_target_url($kind)) wp_die('This request form is not available right now.','Request unavailable',['response'=>503]);
    $session=tio2_flow_session();
    $result_key=tio2_request_result_key($session,$kind);
    $source=is_array($_POST)?$_POST:[];
    [$values,$errors]=tio2_request_validate($kind,$source);
    $token=isset($source['request_token']) && is_string($source['request_token']) ? (string)$source['request_token'] : '';
    $nonce=isset($source['tio2_nonce']) && is_string($source['tio2_nonce']) ? sanitize_text_field(wp_unslash($source['tio2_nonce'])) : '';
    if (!wp_verify_nonce($nonce,'tio2_request_'.$kind)) $errors['form']='Reload the form and try again.';
    if (empty($token) || !preg_match('/^[a-f0-9]{64}$/D',$token)) $errors['form']='Reload the form and try again.';
    if (!empty($source['website_check'])) $errors['form']='Please try again.';
    $claim='tio2_request_once_'.hash_hmac('sha256',$session.'|'.$kind.'|'.$token,wp_salt('auth'));
    if (!$errors) {
        $saved=get_option($claim);
        if (is_array($saved) && !empty($saved['receipt'])) {wp_safe_redirect($saved['receipt'],303);exit;}
    }
    $address=isset($_SERVER['REMOTE_ADDR'])?(string)$_SERVER['REMOTE_ADDR']:'';
    $rate_key='tio2_request_rate_'.hash_hmac('sha256',$kind.'|'.$address,wp_salt('auth'));
    if ((int)get_transient($rate_key)>=5) $errors['form']='Please wait before submitting another request.';
    if ($errors) {
        set_transient($result_key,['state'=>'error','values'=>$values,'errors'=>$errors],10*MINUTE_IN_SECONDS);
        tio2_request_redirect_back($kind);
    }
    if (!add_option($claim,['pending'=>time()],'',false)) {
        $saved=get_option($claim);
        if (is_array($saved) && !empty($saved['receipt'])) {wp_safe_redirect($saved['receipt'],303);exit;}
        set_transient($result_key,['state'=>'error','values'=>$values,'errors'=>['form'=>'This request is already being processed. Please wait.']],10*MINUTE_IN_SECONDS);
        tio2_request_redirect_back($kind);
    }
    if (!wp_schedule_single_event(time()+DAY_IN_SECONDS,'tio2_request_expire_claim',[$claim])) {
        delete_option($claim);
        set_transient($result_key,['state'=>'failure','values'=>$values],10*MINUTE_IN_SECONDS);
        tio2_request_redirect_back($kind);
    }
    $post=wp_insert_post(['post_type'=>'tio2_request','post_status'=>'private',
        'post_title'=>ucfirst($kind).' request '.gmdate('Y-m-d H:i:s').' UTC',
        'post_content'=>wp_slash(wp_json_encode($values,JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE))],true);
    if (is_wp_error($post) || !$post) {
        delete_option($claim);
        set_transient($result_key,['state'=>'failure','values'=>$values],10*MINUTE_IN_SECONDS);
        tio2_request_redirect_back($kind);
    }
    $meta=['_tio2_request_kind'=>$kind,'_tio2_request_status'=>'new','_tio2_notification_status'=>'not_configured'];
    foreach ($meta as $key=>$value) if (!update_post_meta($post,$key,$value)) {
        wp_delete_post($post,true);delete_option($claim);
        set_transient($result_key,['state'=>'failure','values'=>$values],10*MINUTE_IN_SECONDS);
        tio2_request_redirect_back($kind);
    }
    $receipt=tio2_issue_request_receipt($kind);
    if (is_wp_error($receipt)) {
        wp_delete_post($post,true);delete_option($claim);
        set_transient($result_key,['state'=>'failure','values'=>$values],10*MINUTE_IN_SECONDS);
        tio2_request_redirect_back($kind);
    }
    if (!update_option($claim,['receipt'=>$receipt,'record_id'=>$post],false)) {
        $token=wp_parse_url($receipt,PHP_URL_QUERY);parse_str($token,$params);
        if (isset($params['receipt'])) delete_transient('tio2_receipt_'.hash('sha256',$params['receipt']));
        wp_delete_post($post,true);delete_option($claim);
        set_transient($result_key,['state'=>'failure','values'=>$values],10*MINUTE_IN_SECONDS);
        tio2_request_redirect_back($kind);
    }
    set_transient($rate_key,(int)get_transient($rate_key)+1,HOUR_IN_SECONDS);
    delete_transient($result_key);
    tio2_request_send_notification($post);
    nocache_headers();wp_safe_redirect($receipt,303);exit;
}
add_action('tio2_request_expire_claim', function ($claim) {
    if (is_string($claim) && str_starts_with($claim,'tio2_request_once_')) delete_option($claim);
});
function tio2_request_redirect_back($kind) {
    nocache_headers();wp_safe_redirect(home_url(tio2_request_paths()[$kind].'#request-form'),303);exit;
}
foreach (array_keys(tio2_request_page_ids()) as $kind) {
    add_action('admin_post_nopriv_tio2_request_'.$kind, static function () use ($kind) {tio2_request_posted($kind);});
    add_action('admin_post_tio2_request_'.$kind, static function () use ($kind) {tio2_request_posted($kind);});
}
add_action('template_redirect',function () {
    if (is_page() && tio2_request_kind(get_queried_object_id())) tio2_flow_session();
});
add_action('admin_menu',function () {
    add_menu_page('Business requests','Business requests','manage_options','tio2-requests','tio2_request_admin','dashicons-email-alt',26);
});
function tio2_request_admin() {
    if (!current_user_can('manage_options')) wp_die('Not authorized');
    $id=isset($_GET['request'])?absint($_GET['request']):0;
    echo '<div class="wrap"><h1>Business requests</h1>';
    if ($id) {
        $post=get_post($id);
        if (!$post || $post->post_type!=='tio2_request') {echo '<p>Request not found.</p></div>';return;}
        $mail_status=get_post_meta($id,'_tio2_notification_status',true);
        echo '<p><a href="'.esc_url(admin_url('admin.php?page=tio2-requests')).'">All requests</a></p><h2>'.esc_html($post->post_title).'</h2><p>Status: '.esc_html(get_post_meta($id,'_tio2_request_status',true)).'</p><p>Email notification: '.esc_html($mail_status?:'not_configured').'</p>';
        $values=json_decode($post->post_content,true);$kind=get_post_meta($id,'_tio2_request_kind',true);
        echo '<table class="widefat striped"><tbody>';
        foreach (tio2_request_fields($kind) as $key=>$field) {
            $value=$values[$key]??'';
            echo '<tr><th scope="row">'.esc_html($field[0]).'</th><td>'.nl2br(esc_html(is_array($value)?implode(', ',$value):$value)).'</td></tr>';
        }
        echo '</tbody></table>';
        if (get_post_meta($id,'_tio2_request_status',true)!=='reviewed')
            echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'"><input type="hidden" name="action" value="tio2_request_mark_reviewed"><input type="hidden" name="request_id" value="'.esc_attr($id).'">'.wp_nonce_field('tio2_request_review_'.$id,'tio2_nonce',true,false).'<button class="button button-primary" type="submit">Mark reviewed</button></form>';
        if ($mail_status!=='accepted') {
            $uncertain=in_array($mail_status,['sending','unknown'],true);
            echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'"><input type="hidden" name="action" value="tio2_request_retry_notification"><input type="hidden" name="request_id" value="'.esc_attr($id).'">'.wp_nonce_field('tio2_request_retry_'.$id,'tio2_nonce',true,false);
            if ($uncertain) echo '<p>The last delivery outcome is uncertain. Check the mailbox before retrying; a retry may send a duplicate.</p><p><label><input type="checkbox" name="confirmed_retry" value="1" required> I checked the mailbox and want to retry this notification.</label></p>';
            echo '<button class="button" type="submit">Retry email notification</button></form>';
        }
        echo '</div>';return;
    }
    $page=max(1,absint($_GET['paged']??1));
    $query=new WP_Query(['post_type'=>'tio2_request','post_status'=>'private','posts_per_page'=>50,'paged'=>$page,'orderby'=>'date','order'=>'DESC']);
    echo '<table class="widefat striped"><thead><tr><th>Date</th><th>Type</th><th>Status</th><th>Email</th><th>Details</th></tr></thead><tbody>';
    foreach ($query->posts as $post) echo '<tr><td>'.esc_html(get_the_date('Y-m-d H:i',$post)).'</td><td>'.esc_html(get_post_meta($post->ID,'_tio2_request_kind',true)).'</td><td>'.esc_html(get_post_meta($post->ID,'_tio2_request_status',true)).'</td><td>'.esc_html(get_post_meta($post->ID,'_tio2_notification_status',true)).'</td><td><a href="'.esc_url(admin_url('admin.php?page=tio2-requests&request='.$post->ID)).'">View</a></td></tr>';
    echo '</tbody></table>';
    if ($query->max_num_pages>1) echo '<div class="tablenav"><div class="tablenav-pages">'.wp_kses_post(paginate_links(['base'=>add_query_arg('paged','%#%',admin_url('admin.php?page=tio2-requests')),'total'=>$query->max_num_pages,'current'=>$page,'type'=>'plain'])).'</div></div>';
    echo '</div>';
}
add_action('admin_post_tio2_request_mark_reviewed',function () {
    if (!current_user_can('manage_options')) wp_die('Not authorized');
    $id=absint($_POST['request_id']??0);$post=get_post($id);
    if (!$post || $post->post_type!=='tio2_request' || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tio2_nonce']??'')),'tio2_request_review_'.$id)) wp_die('Invalid request');
    update_post_meta($id,'_tio2_request_status','reviewed');
    wp_safe_redirect(admin_url('admin.php?page=tio2-requests&request='.$id),303);exit;
});
add_action('admin_post_tio2_request_retry_notification',function () {
    if (!current_user_can('manage_options')) wp_die('Not authorized');
    $id=absint($_POST['request_id']??0);$post=get_post($id);
    if (!$post || $post->post_type!=='tio2_request' || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tio2_nonce']??'')),'tio2_request_retry_'.$id)) wp_die('Invalid request');
    tio2_request_send_notification($id,isset($_POST['confirmed_retry']) && $_POST['confirmed_retry']==='1');
    wp_safe_redirect(admin_url('admin.php?page=tio2-requests&request='.$id),303);exit;
});
