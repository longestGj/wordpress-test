<?php
/** Verify Gmail STARTTLS reachability only. No authentication or mail delivery. */
require '/workspace/tests/local-only.php';
require_once ABSPATH.WPINC.'/PHPMailer/Exception.php';
require_once ABSPATH.WPINC.'/PHPMailer/PHPMailer.php';
require_once ABSPATH.WPINC.'/PHPMailer/SMTP.php';
$mailer=new PHPMailer\PHPMailer\PHPMailer(true);
try {
    tio2_configure_gmail_smtp($mailer,['user'=>'','password'=>'','ipv6'=>getenv('TIO2_MAIL_IPV6')==='1']);
    $mailer->SMTPAuth=false;
    if (!$mailer->smtpConnect()) throw new RuntimeException('Gmail STARTTLS connection failed.');
    WP_CLI::success('Gmail SMTP connected with verified TLS; no authentication or email was sent.');
} catch (Throwable $error) { WP_CLI::error('Could not establish verified Gmail STARTTLS. Check the local network.'); }
finally { $mailer->smtpClose(); }
