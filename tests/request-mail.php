<?php
/** Standalone mail contract: synthetic fields and fake transport, no network. */
define('ABSPATH',__DIR__);
function is_email($value) { return filter_var($value,FILTER_VALIDATE_EMAIL); }
function admin_url($path) { return 'http://localhost:8080/wp-admin/'.$path; }
function tio2_request_fields($kind) {
    return ['company'=>['Company'],'business_email'=>['Business Email'],'document_types'=>['Document Types']];
}
class FakeMailer {
    public $Host,$Port,$SMTPAuth,$Username,$Password,$SMTPSecure,$SMTPAutoTLS,$Timeout,$SMTPDebug,$SMTPOptions;
    public $smtp=false;
    public function isSMTP() { $this->smtp=true; }
}
require __DIR__.'/../wp-content/plugins/tio2-products/request-mail.php';
function check($value,$message) { if (!$value) throw new RuntimeException($message); }
putenv('TIO2_MAIL_USER=notification-fixture@gmail.com');
putenv('TIO2_MAIL_TO=notification-fixture@gmail.com');
putenv('TIO2_MAIL_APP_PASSWORD=test-only-fake-secret');
putenv('TIO2_MAIL_IPV6=0');
$config=tio2_request_mail_settings();
check($config['user']==='notification-fixture@gmail.com' && $config['to']==='notification-fixture@gmail.com','Configured addresses lost');
$mailer=new FakeMailer();tio2_configure_gmail_smtp($mailer,$config);
check($mailer->smtp && $mailer->Host==='smtp.gmail.com' && $mailer->Port===587 && $mailer->SMTPSecure==='tls' && $mailer->SMTPAuth && $mailer->Password==='test-only-fake-secret','SMTP transport configuration invalid');
check($mailer->SMTPOptions['ssl']['peer_name']==='smtp.gmail.com' && $mailer->SMTPOptions['ssl']['verify_peer_name'],'TLS identity verification missing');
$body=tio2_request_mail_body(42,'documents',['company'=>'Test Company','business_email'=>'buyer@example.test','document_types'=>['technical_product','safety']]);
check(str_contains($body,'Test Company') && str_contains($body,'buyer@example.test') && str_contains($body,'technical_product, safety') && str_contains($body,'request=42'),'Mail body missing stored fields or admin link');
putenv('TIO2_MAIL_APP_PASSWORD');
check(tio2_request_mail_settings()===[],'Missing credential must disable mail');
echo "PASS: Gmail settings, SMTP transport, field body and no-credential state\n";
