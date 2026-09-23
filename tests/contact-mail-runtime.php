<?php
/** Local-only; intercepted transport sends no messages. All fixtures are removed. */
require __DIR__.'/local-only.php';
if (!function_exists('tio2_store_contact_once')) WP_CLI::error('Contact needs atomic duplicate protection before notification.');
$env=[];
foreach (['TIO2_MAIL_USER','TIO2_MAIL_TO','TIO2_MAIL_APP_PASSWORD'] as $key) $env[$key]=getenv($key);
$session=bin2hex(random_bytes(32));$claims=[];$ids=[];$calls=[];$failure=null;
$accept=static function ($result,$mail) use (&$calls) { $calls[]=$mail;return true; };
$reject=static function () { return false; };
$values=['full_name'=>'Contact Mail Fixture','company'=>'Local QA','business_email'=>'fixture@invalid.example','country'=>'Malaysia','subject'=>'Codex contact mail fixture','message'=>'Synthetic message for notification testing.'];
try {
    putenv('TIO2_MAIL_USER=notification-fixture@gmail.com');putenv('TIO2_MAIL_TO=notification-fixture@gmail.com');putenv('TIO2_MAIL_APP_PASSWORD=fixture-only');
    $store=static function ($token) use ($values,$session,&$claims,&$ids) {
        $claims[]=tio2_contact_claim_key($session,$token);
        $result=tio2_store_contact_once($values,$session,$token);
        if (!is_wp_error($result)) $ids[]=$result['id'];
        return $result;
    };
    $token=bin2hex(random_bytes(32));$saved=$store($token);
    if (is_wp_error($saved) || !$saved['new'] || get_post_status($saved['id'])!=='private') throw new RuntimeException('Inquiry not stored privately.');
    $again=$store($token);
    if (is_wp_error($again) || $again['new'] || $again['id']!==$saved['id']) throw new RuntimeException('Duplicate submission created another record.');
    add_filter('pre_wp_mail',$accept,10,2);
    if (tio2_request_send_notification($saved['id'])!=='accepted') throw new RuntimeException('Contact notification was not accepted.');
    if (tio2_request_send_notification($saved['id'])!=='accepted' || count($calls)!==1) throw new RuntimeException('Accepted Contact notification was sent twice.');
    foreach (['Full Name: Contact Mail Fixture','Subject: Codex contact mail fixture','Message: Synthetic message','post.php?post='.$saved['id']] as $expected)
        if (!str_contains($calls[0]['message'],$expected)) throw new RuntimeException('Contact notification missing field or admin link: '.$expected);
    if (!str_contains($calls[0]['subject'],'general inquiry')) throw new RuntimeException('Incorrect Contact email subject.');
    remove_filter('pre_wp_mail',$accept,10);
    $saved=$store(bin2hex(random_bytes(32)));
    add_filter('pre_wp_mail',$reject,10,2);
    if (tio2_request_send_notification($saved['id'])!=='failed' || get_post_status($saved['id'])!=='private') throw new RuntimeException('Mail failure damaged saved inquiry.');
    remove_filter('pre_wp_mail',$reject,10);add_filter('pre_wp_mail',$accept,10,2);
    if (tio2_request_send_notification($saved['id'])!=='accepted' || (int)get_post_meta($saved['id'],'_tio2_notification_attempts',true)!==2) throw new RuntimeException('Contact retry failed.');
    $saved=$store(bin2hex(random_bytes(32)));update_post_meta($saved['id'],'_tio2_notification_status','unknown');$before=count($calls);
    if (tio2_request_send_notification($saved['id'])!=='unknown' || count($calls)!==$before) throw new RuntimeException('Uncertain Contact mail retried automatically.');
    if (tio2_request_send_notification($saved['id'],true)!=='accepted') throw new RuntimeException('Confirmed retry failed.');
    remove_filter('pre_wp_mail',$accept,10);
    $token=bin2hex(random_bytes(32));$claim=tio2_contact_claim_key($session,$token);$claims[]=$claim;add_option($claim,['pending'=>time()],'',false);
    if (!is_wp_error($store($token))) throw new RuntimeException('Concurrent claim allowed a second inquiry.');
    if (!is_wp_error(tio2_store_contact_once($values,$session,'bad'))) throw new RuntimeException('Invalid submission token was accepted.');
} catch (Throwable $error) { $failure=$error->getMessage(); }
finally {
    remove_filter('pre_wp_mail',$accept,10);remove_filter('pre_wp_mail',$reject,10);
    foreach (array_unique($ids) as $id) {wp_delete_post($id,true);delete_option('tio2_request_mail_lock_'.$id);}
    foreach (array_unique($claims) as $claim) {delete_option($claim);wp_clear_scheduled_hook('tio2_contact_expire_claim',[$claim]);}
    foreach ($env as $key=>$value) putenv($value===false?$key:$key.'='.$value);
}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('Contact private storage, duplicate/concurrent protection, notification body, failure and retry verified; fixtures removed.');
