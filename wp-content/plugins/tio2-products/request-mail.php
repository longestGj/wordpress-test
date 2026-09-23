<?php
defined('ABSPATH') || exit;
/** Staff-only Gmail notification. The request record remains authoritative. */
function tio2_request_mail_settings() {
    $user=trim((string)getenv('TIO2_MAIL_USER'));
    $to=trim((string)getenv('TIO2_MAIL_TO'));
    $password=trim((string)getenv('TIO2_MAIL_APP_PASSWORD'));
    if (!is_email($user) || !str_ends_with(strtolower($user),'@gmail.com') || !is_email($to) || $password==='') return [];
    return ['user'=>$user,'to'=>$to,'password'=>$password,'ipv6'=>getenv('TIO2_MAIL_IPV6')==='1'];
}
function tio2_configure_gmail_smtp($mailer,$config) {
    $mailer->isSMTP();
    $mailer->Host='smtp.gmail.com';
    $mailer->SMTPOptions=['ssl'=>['peer_name'=>'smtp.gmail.com','verify_peer'=>true,'verify_peer_name'=>true,'allow_self_signed'=>false]];
    if (!empty($config['ipv6'])) {
        $addresses=dns_get_record('smtp.gmail.com',DNS_AAAA);
        $address=$addresses[0]['ipv6']??'';
        if (!filter_var($address,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)) throw new RuntimeException('Gmail IPv6 address unavailable.');
        $mailer->Host='['.$address.']';
    }
    $mailer->Port=587;
    $mailer->SMTPSecure='tls';
    $mailer->SMTPAutoTLS=true;
    $mailer->SMTPAuth=true;
    $mailer->Username=$config['user'];
    $mailer->Password=$config['password'];
    $mailer->Timeout=10;
    $mailer->SMTPDebug=0;
}
function tio2_request_mail_body($id,$kind,$values) {
    $names=['quote'=>'Quotation','documents'=>'Document','sample'=>'Sample','contact'=>'General inquiry'];
    $lines=['TiO2Products — '.($names[$kind]??'Business').' request #'.$id,'','A new business request was saved in WordPress.',''];
    foreach (($kind==='contact'?tio2_contact_fields():tio2_request_fields($kind)) as $key=>$field) {
        $value=$values[$key]??'';
        $lines[]=$field[0].': '.(is_array($value)?implode(', ',$value):(string)$value);
    }
    $lines[]='';$lines[]='Review the private record:';
    $lines[]=$kind==='contact'?admin_url('post.php?post='.$id.'&action=edit'):admin_url('admin.php?page=tio2-requests&request='.$id);
    return implode("\n",$lines);
}
function tio2_request_send_notification($id,$confirmed_retry=false) {
    $post=get_post($id);
    if (!$post || !in_array($post->post_type,['tio2_request','tio2_inquiry'],true) || $post->post_status!=='private') return 'invalid';
    $previous=get_post_meta($id,'_tio2_notification_status',true);
    if ($previous==='accepted') return 'accepted';
    if (in_array($previous,['sending','unknown'],true) && !$confirmed_retry) return 'unknown';
    $config=tio2_request_mail_settings();
    if (!$config) { update_post_meta($id,'_tio2_notification_status','not_configured');return 'not_configured'; }
    $kind=$post->post_type==='tio2_inquiry'?'contact':get_post_meta($id,'_tio2_request_kind',true);
    if (!in_array($kind,['quote','documents','sample','contact'],true)) return 'invalid';
    $values=json_decode($post->post_content,true);
    if (!is_array($values)) return 'invalid';
    $lock='tio2_request_mail_lock_'.$id;
    if (!add_option($lock,time(),'',false)) {
        if (time()-(int)get_option($lock)>120) { delete_option($lock);if (!add_option($lock,time(),'',false)) return 'busy'; }
        else return 'busy';
    }
    try {
        if (get_post_meta($id,'_tio2_notification_status',true)==='accepted') return 'accepted';
        update_post_meta($id,'_tio2_notification_status','sending');
        if (get_post_meta($id,'_tio2_notification_status',true)!=='sending') return 'storage_failed';
        update_post_meta($id,'_tio2_notification_attempts',(int)get_post_meta($id,'_tio2_notification_attempts',true)+1);
        update_post_meta($id,'_tio2_notification_last_attempt_at',gmdate('Y-m-d H:i:s'));
        $from=static fn()=> $config['user'];
        $name=static fn()=> 'TiO2Products';
        $smtp=static function ($mailer) use ($config) { tio2_configure_gmail_smtp($mailer,$config); };
        add_filter('wp_mail_from',$from);
        add_filter('wp_mail_from_name',$name);
        add_action('phpmailer_init',$smtp);
        $had_mailer=array_key_exists('phpmailer',$GLOBALS);
        $previous_mailer=$GLOBALS['phpmailer']??null;
        unset($GLOBALS['phpmailer']);
        try {
            $subject=$kind==='contact'?'TiO2Products: New general inquiry #'.$id:'TiO2Products: New '.($kind==='quote'?'quote':($kind==='documents'?'document':'sample')).' request #'.$id;
            $sent=wp_mail($config['to'],$subject,tio2_request_mail_body($id,$kind,$values),['Content-Type: text/plain; charset=UTF-8']);
        } catch (Throwable $error) {
            $sent=false;
        } finally {
            if (isset($GLOBALS['phpmailer']) && method_exists($GLOBALS['phpmailer'],'smtpClose')) $GLOBALS['phpmailer']->smtpClose();
            if ($had_mailer) $GLOBALS['phpmailer']=$previous_mailer;
            else unset($GLOBALS['phpmailer']);
            remove_action('phpmailer_init',$smtp);
            remove_filter('wp_mail_from_name',$name);
            remove_filter('wp_mail_from',$from);
        }
        $status=$sent?'accepted':'failed';
        update_post_meta($id,'_tio2_notification_status',$status);
        if (get_post_meta($id,'_tio2_notification_status',true)!==$status) {
            update_post_meta($id,'_tio2_notification_status','unknown');
            return 'unknown';
        }
        if ($sent) update_post_meta($id,'_tio2_notification_accepted_at',gmdate('Y-m-d H:i:s'));
        return $status;
    } finally {
        delete_option($lock);
    }
}
