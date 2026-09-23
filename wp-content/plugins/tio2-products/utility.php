<?php
defined('ABSPATH') || exit;

// General inquiries are private records; staff mail is attempted after saving.
add_action('init', function () {
    register_post_type('tio2_inquiry', [
        'labels' => ['name' => 'General inquiries', 'singular_name' => 'General inquiry'],
        'public' => false, 'show_ui' => true, 'show_in_rest' => false,
        'menu_icon' => 'dashicons-email-alt', 'supports' => ['title', 'editor'],
        'capabilities' => ['create_posts' => 'do_not_allow'], 'map_meta_cap' => true,
    ]);
    if (!wp_next_scheduled('tio2_expire_inquiries')) wp_schedule_event(time() + DAY_IN_SECONDS, 'daily', 'tio2_expire_inquiries');
});
add_action('tio2_expire_inquiries', function () {
    do {
        $old = get_posts(['post_type' => 'tio2_inquiry', 'post_status' => 'private', 'date_query' => [['before' => '3 years ago']], 'posts_per_page' => 100, 'fields' => 'ids']);
        $removed = 0;
        foreach ($old as $id) if (wp_delete_post($id, true)) $removed++;
    } while (count($old) === 100 && $removed > 0);
});
add_action('add_meta_boxes_tio2_inquiry', function () {
    add_meta_box('tio2_inquiry_details', 'Inquiry details', function ($post) {
        $fields = json_decode($post->post_content, true);
        if (!is_array($fields)) return;
        echo '<table class="widefat striped"><tbody>';
        foreach (tio2_contact_fields() as $key => $definition) echo '<tr><th scope="row">'.esc_html($definition[0]).'</th><td>'.nl2br(esc_html($fields[$key] ?? '')).'</td></tr>';
        echo '</tbody></table><p>Saved on this WordPress site.</p>';
        tio2_contact_mail_admin($post);
    }, 'tio2_inquiry', 'normal', 'high');
});

function tio2_flow_cookie($name, $value) {
    setcookie($name, $value, ['expires' => 0, 'path' => '/', 'secure' => is_ssl(), 'httponly' => true, 'samesite' => 'Lax']);
    $_COOKIE[$name] = $value;
}
function tio2_flow_session() {
    $value = isset($_COOKIE['tio2_flow']) && is_string($_COOKIE['tio2_flow']) ? $_COOKIE['tio2_flow'] : '';
    if (!preg_match('/^[a-f0-9]{64}$/D', $value)) {
        $value = bin2hex(random_bytes(32));
        tio2_flow_cookie('tio2_flow', $value);
    }
    return $value;
}
function tio2_contact_result_key($session) { return 'tio2_contact_' . hash_hmac('sha256', $session, wp_salt('auth')); }
function tio2_contact_fields() {
    return [
        'full_name' => ['Full Name', 100, 'Enter your full name.', 'Keep your name to 100 characters or fewer.'],
        'company' => ['Company', 160, 'Enter your company name.', 'Keep your company name to 160 characters or fewer.'],
        'business_email' => ['Business Email', 254, 'Enter your business email.', 'Keep your email address to 254 characters or fewer.'],
        'country' => ['Country / Region', 100, 'Enter your country or region.', 'Keep your country or region to 100 characters or fewer.'],
        'subject' => ['Subject', 120, 'Enter a subject.', 'Keep the subject to 120 characters or fewer.'],
        'message' => ['Message', 2000, 'Enter your message.', 'Keep your message to 2,000 characters or fewer.'],
    ];
}
function tio2_validate_contact($input) {
    $values = []; $errors = [];
    foreach (tio2_contact_fields() as $key => $field) {
        $raw = isset($input[$key]) && is_string($input[$key]) ? wp_unslash($input[$key]) : '';
        $value = trim($key === 'message' ? sanitize_textarea_field($raw) : sanitize_text_field($raw));
        $values[$key] = $value;
        if ($value === '') $errors[$key] = $field[2];
        elseif (mb_strlen($value) > $field[1]) $errors[$key] = $field[3];
    }
    if (empty($errors['business_email']) && !is_email($values['business_email'])) $errors['business_email'] = 'Enter a valid email address, such as name@company.com.';
    return [$values, $errors];
}
add_action('admin_post_nopriv_tio2_general_inquiry', 'tio2_receive_contact');
add_action('admin_post_tio2_general_inquiry', 'tio2_receive_contact');
add_action('template_redirect', function () {
    if (is_page() && get_post_meta(get_queried_object_id(), '_tio2_page_id', true) === 'CONTACT-001') tio2_flow_session();
});
function tio2_receive_contact() {
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') wp_die('Method not allowed', '', ['response' => 405]);
    $session = tio2_flow_session();
    $key = tio2_contact_result_key($session);
    [$values, $errors] = tio2_validate_contact($_POST);
    $nonce = isset($_POST['tio2_nonce']) && is_string($_POST['tio2_nonce']) ? sanitize_text_field(wp_unslash($_POST['tio2_nonce'])) : '';
    if (!wp_verify_nonce($nonce, 'tio2_general_inquiry')) $errors['form'] = 'Please reload the form and try again.';
    $token=isset($_POST['contact_token']) && is_string($_POST['contact_token'])?$_POST['contact_token']:'';
    if (!preg_match('/^[a-f0-9]{64}$/D',$token)) $errors['form']='Please reload the form and try again.';
    if (!empty($_POST['website'])) $errors['form'] = 'Please try again.';
    $address = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
    $limit_key = 'tio2_inquiry_limit_' . hash_hmac('sha256', $address, wp_salt('auth'));
    $previous=$errors?false:get_option(tio2_contact_claim_key($session,$token));
    if (empty($previous['record_id']) && (int) get_transient($limit_key) >= 5) $errors['form'] = 'Please wait before submitting another inquiry.';
    if ($errors) {
        set_transient($key, ['status' => 'error', 'values' => $values, 'errors' => $errors], 10 * MINUTE_IN_SECONDS);
    } else {
        $saved=tio2_store_contact_once($values,$session,$token);
        if (is_wp_error($saved)) {
            set_transient($key, ['status' => 'error', 'values' => $values, 'errors'=>['form'=>$saved->get_error_message()]], 10 * MINUTE_IN_SECONDS);
        } else {
            if ($saved['new']) set_transient($limit_key, (int) get_transient($limit_key) + 1, HOUR_IN_SECONDS);
            set_transient($key, ['status' => 'success'], 10 * MINUTE_IN_SECONDS);
            if ($saved['new']) tio2_request_send_notification($saved['id']);
        }
    }
    nocache_headers();
    wp_safe_redirect(home_url('/contact/#general-inquiry'), 303);
    exit;
}
add_shortcode('tio2_contact_form', function () {
    if (!is_page() || get_post_meta(get_queried_object_id(), '_tio2_page_id', true) !== 'CONTACT-001') return '';
    $session = tio2_flow_session();
    $result = get_transient(tio2_contact_result_key($session));
    $result = is_array($result) ? $result : [];
    if (($result['status'] ?? '') === 'success') return '<div class="utility-notice" role="status" tabindex="-1"><h3>Your inquiry has been received</h3><p>Thank you. We have received your general inquiry for review.</p></div>';
    $errors = $result['errors'] ?? []; $values = $result['values'] ?? [];
    $html = '';
    if (($result['status'] ?? '') === 'failure') $html .= '<div class="utility-notice error" role="alert" tabindex="-1"><h3>Your inquiry was not received</h3><p>We could not save your inquiry. Your information has been kept in the form. Try again when you are ready.</p></div>';
    if ($errors) {
        $html .= '<div class="utility-notice error" role="alert" tabindex="-1"><h3>Please check the form</h3><p>Review the highlighted fields and correct the items listed below.</p><ul>';
        foreach ($errors as $error) $html .= '<li>' . esc_html($error) . '</li>';
        $html .= '</ul></div>';
    }
    $html .= '<form class="contact-form" method="post" action="' . esc_url(admin_url('admin-post.php')) . '" novalidate><input type="hidden" name="action" value="tio2_general_inquiry">';
    $html .= wp_nonce_field('tio2_general_inquiry', 'tio2_nonce', true, false);
    $html .= '<input type="hidden" name="contact_token" value="'.esc_attr(bin2hex(random_bytes(32))).'">';
    $html .= '<div class="contact-honeypot" aria-hidden="true"><label>Leave this blank<input name="website" tabindex="-1" autocomplete="off"></label></div>';
    $help = ['full_name' => 'Enter the name we should use when replying.', 'company' => 'Enter the organisation you represent.', 'business_email' => 'We will use this address to reply to your inquiry.', 'country' => 'Enter the country or region where your company is based.', 'subject' => 'Summarise your question in a few words.', 'message' => 'Describe the general company or business matter you would like to discuss. Do not include passwords or payment details.'];
    foreach (tio2_contact_fields() as $key => $field) {
        $id = 'contact-' . $key; $value = esc_attr($values[$key] ?? '');
        $html .= '<div class="contact-field"><label for="' . esc_attr($id) . '">' . esc_html($field[0]) . ' <span>(required)</span></label>';
        if ($key === 'message') $html .= '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($key) . '" maxlength="2000" required aria-describedby="' . esc_attr($id) . '-help' . (isset($errors[$key]) ? ' ' . esc_attr($id) . '-error' : '') . '"' . (isset($errors[$key]) ? ' aria-invalid="true"' : '') . '>' . esc_textarea($values[$key] ?? '') . '</textarea>';
        else $html .= '<input id="' . esc_attr($id) . '" name="' . esc_attr($key) . '" type="' . ($key === 'business_email' ? 'email' : 'text') . '" value="' . $value . '" maxlength="' . (int) $field[1] . '" required aria-describedby="' . esc_attr($id) . '-help' . (isset($errors[$key]) ? ' ' . esc_attr($id) . '-error' : '') . '"' . (isset($errors[$key]) ? ' aria-invalid="true"' : '') . '>';
        $html .= '<small id="' . esc_attr($id) . '-help">' . esc_html($help[$key]) . '</small>';
        if (isset($errors[$key])) $html .= '<strong class="field-error" id="' . esc_attr($id) . '-error">' . esc_html($errors[$key]) . '</strong>';
        $html .= '</div>';
    }
    $html .= '<p>We use the information you provide to review and respond to this inquiry. Read our <a href="' . esc_url(home_url('/privacy-policy/')) . '">Privacy Policy</a> for details.</p><button class="button primary" type="submit">Send a General Inquiry</button></form>';
    return $html;
});

// A receiver may call this only after its own positive acknowledgement. There is
// deliberately no public endpoint for issuing a confirmation.
function tio2_issue_request_receipt($kind) {
    if (!in_array($kind, ['quote', 'documents', 'sample'], true)) return new WP_Error('invalid_receipt_kind', 'Invalid request kind.');
    $session = tio2_flow_session();
    $token = bin2hex(random_bytes(24));
    if (!set_transient('tio2_receipt_' . hash('sha256', $token), ['kind' => $kind, 'session' => hash_hmac('sha256', $session, wp_salt('auth'))], 10 * MINUTE_IN_SECONDS)) return new WP_Error('receipt_unavailable', 'Could not confirm the request receipt.');
    return add_query_arg('receipt', $token, home_url('/thank-you/'));
}
function tio2_request_receipt_kind() {
    $token = isset($_GET['receipt']) && is_string($_GET['receipt']) ? (string) $_GET['receipt'] : '';
    $session = isset($_COOKIE['tio2_flow']) && is_string($_COOKIE['tio2_flow']) ? $_COOKIE['tio2_flow'] : '';
    if (!preg_match('/^[a-f0-9]{48}$/D', $token) || !preg_match('/^[a-f0-9]{64}$/D', $session)) return '';
    $stored = get_transient('tio2_receipt_' . hash('sha256', $token));
    if (!is_array($stored) || !hash_equals($stored['session'] ?? '', hash_hmac('sha256', $session, wp_salt('auth')))) return '';
    return in_array($stored['kind'] ?? '', ['quote', 'documents', 'sample'], true) ? $stored['kind'] : '';
}
