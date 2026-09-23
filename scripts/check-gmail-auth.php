<?php
/** Local-only authentication check. Never prints credentials or sends mail. */
require '/workspace/tests/local-only.php';
$config=tio2_request_mail_settings();
if (!$config) WP_CLI::error('Mail configuration is incomplete.');
require_once ABSPATH.WPINC.'/PHPMailer/Exception.php';
require_once ABSPATH.WPINC.'/PHPMailer/PHPMailer.php';
require_once ABSPATH.WPINC.'/PHPMailer/SMTP.php';
$mailer=new PHPMailer\PHPMailer\PHPMailer(true);
$ok=false;
try {
    tio2_configure_gmail_smtp($mailer,$config);
    $ok=$mailer->smtpConnect();
} catch (Throwable $error) {
    $ok=false;
} finally {
    $mailer->smtpClose();
}
if (!$ok) WP_CLI::error('Gmail connection or authentication failed; credentials were not displayed.');
WP_CLI::success('Gmail authenticated over verified TLS; no email was sent.');
