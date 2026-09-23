<?php
/** Local-only synthetic mail test; pre_wp_mail prevents any network delivery. */
require __DIR__.'/local-only.php';
$original=[];
foreach (['TIO2_MAIL_USER','TIO2_MAIL_TO','TIO2_MAIL_APP_PASSWORD'] as $key) $original[$key]=getenv($key);
$posts=[];$captured=[];$calls=0;
$allow=static function ($return,$atts) use (&$captured,&$calls) { $calls++;$captured[]=$atts;return true; };
$reject=static function () use (&$calls) { $calls++;return false; };
$reject_accepted=static function ($check,$id,$key,$value) { return $key==='_tio2_notification_status' && $value==='accepted'?false:$check; };
$original_mailer=$GLOBALS['phpmailer']??null;
$had_mailer=array_key_exists('phpmailer',$GLOBALS);
$sentinel=new stdClass();$GLOBALS['phpmailer']=$sentinel;
$failure=null;
try {
    putenv('TIO2_MAIL_USER=notification-fixture@gmail.com');
    putenv('TIO2_MAIL_TO=notification-fixture@gmail.com');
    putenv('TIO2_MAIL_APP_PASSWORD=synthetic-test-password');
    $make=static function () use (&$posts) {
        $id=wp_insert_post(['post_type'=>'tio2_request','post_status'=>'private','post_title'=>'Codex mail fixture',
            'post_content'=>wp_slash(wp_json_encode(['company'=>'Codex mail fixture','business_email'=>'fixture@invalid.example','document_types'=>['safety']]))],true);
        if (is_wp_error($id)||!$id) throw new RuntimeException('Could not create synthetic request.');
        $posts[]=$id;update_post_meta($id,'_tio2_request_kind','documents');update_post_meta($id,'_tio2_notification_status','not_configured');
        return $id;
    };
    $id=$make();
    add_filter('pre_wp_mail',$allow,10,2);
    if (tio2_request_send_notification($id)!=='accepted' || $calls!==1) throw new RuntimeException('Successful mail was not recorded.');
    if (($GLOBALS['phpmailer']??null)!==$sentinel) throw new RuntimeException('Gmail transport leaked into other WordPress mail.');
    if (tio2_request_send_notification($id)!=='accepted' || $calls!==1) throw new RuntimeException('Accepted mail was resent.');
    if (($captured[0]['to']??'')!=='notification-fixture@gmail.com' || !str_contains($captured[0]['message']??'','Codex mail fixture')) throw new RuntimeException('Message recipient or body is wrong.');
    remove_filter('pre_wp_mail',$allow,10);
    $id=$make();
    add_filter('pre_wp_mail',$reject,10,2);
    if (tio2_request_send_notification($id)!=='failed' || get_post_meta($id,'_tio2_notification_status',true)!=='failed') throw new RuntimeException('Failed transport was not recorded.');
    remove_filter('pre_wp_mail',$reject,10);
    add_filter('pre_wp_mail',$allow,10,2);
    if (tio2_request_send_notification($id)!=='accepted' || get_post_meta($id,'_tio2_notification_attempts',true)!=2) throw new RuntimeException('Retry did not recover.');
    remove_filter('pre_wp_mail',$allow,10);
    $id=$make();update_post_meta($id,'_tio2_notification_status','sending');
    $before=$calls;
    if (tio2_request_send_notification($id)!=='unknown' || $calls!==$before) throw new RuntimeException('Uncertain send was blindly retried.');
    add_filter('pre_wp_mail',$allow,10,2);
    if (tio2_request_send_notification($id,true)!=='accepted') throw new RuntimeException('Explicitly reconciled retry failed.');
    remove_filter('pre_wp_mail',$allow,10);
    $id=$make();
    add_filter('pre_wp_mail',$allow,10,2);
    add_filter('update_post_metadata',$reject_accepted,10,4);
    if (tio2_request_send_notification($id)!=='unknown') throw new RuntimeException('Failed acceptance write was not marked uncertain.');
    $before=$calls;
    if (tio2_request_send_notification($id)!=='unknown' || $calls!==$before) throw new RuntimeException('Uncertain acceptance was resent.');
    remove_filter('update_post_metadata',$reject_accepted,10);
    remove_filter('pre_wp_mail',$allow,10);
    putenv('TIO2_MAIL_APP_PASSWORD');
    $id=$make();
    if (tio2_request_send_notification($id)!=='not_configured') throw new RuntimeException('Missing credential tried to send.');
} catch (Throwable $error) { $failure=$error->getMessage(); }
finally {
    remove_filter('pre_wp_mail',$allow,10);remove_filter('pre_wp_mail',$reject,10);
    remove_filter('update_post_metadata',$reject_accepted,10);
    if ($had_mailer) $GLOBALS['phpmailer']=$original_mailer;else unset($GLOBALS['phpmailer']);
    foreach ($posts as $id) { delete_option('tio2_request_mail_lock_'.$id);wp_delete_post($id,true); }
    foreach ($original as $key=>$value) putenv($value===false?$key:$key.'='.$value);
}
if ($failure!==null) WP_CLI::error($failure);
WP_CLI::success('Mail acceptance, no duplicate, failure, retry and missing configuration verified; fixtures removed.');
