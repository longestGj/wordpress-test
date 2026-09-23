<?php
defined('ABSPATH') || exit;
/** Native form controls; all business validation stays in requests.php. */
function tio2_request_prefill($kind) {
    $query=wp_unslash($_GET);
    if (!is_array($query)) return [];
    $grade=$query['prefill_product_grade']??$query['grade_id']??$query['grade']??$query['product']??'';
    $application=$query['application_id']??$query['application']??'';
    $destination=$query['destination']??$query['destination_country']??'';
    $types=$query['prefill_document_types']??$query['document_needs']??[];
    $draft=[];
    if (is_string($grade) && in_array($grade,tio2_request_grades(),true)) $draft['product_grade']=$grade;
    if (is_string($application)) $draft['application']=$application;
    $destination=is_string($destination)?trim($destination):'';
    if ($destination!=='' && mb_strlen($destination)<=($kind==='quote'?100:120) && ($kind!=='quote' || !in_array(strtolower($destination),['european union','eu','europe','asia','southeast asia','global','worldwide'],true))) {
        $field=$kind==='quote'?'destination_country':'destination_country_market';
        if ($kind!=='documents') $draft[$field]=$destination;
    }
    if (is_array($types) && array_is_list($types) && count($types)<=5 && !array_filter($types,static fn($value)=>!is_string($value))) {
        $field=$kind==='documents'?'document_types':'document_needs';
        $allowed=$kind==='documents'?array_keys(tio2_request_document_types()):['TDS','SDS','COA','COO','Other / Not sure'];
        $draft[$field]=array_values(array_unique(array_intersect($types,$allowed)));
    }
    if ($kind==='documents' && ($query['requested_type']??'')==='TDS') {
        $draft['document_types']=array_values(array_unique(array_merge($draft['document_types']??[],['technical_product'])));
        $draft['additional_requirements']='TDS';
    }
    return $draft;
}
function tio2_request_field_html($kind,$key,$field,$value,$error) {
    [$label,$type,$max,$required]=$field;$id='request-'.$key;
    $html='<div class="request-field"><label for="'.esc_attr($id).'">'.esc_html($label).($required?' <span>(required)</span>':'').'</label>';
    $described=$error?' aria-invalid="true" aria-describedby="'.$id.'-error"':'';
    if ($type==='select') {
        $html.='<select id="'.esc_attr($id).'" name="'.esc_attr($key).'"'.$described.($required?' required':'').'>';
        foreach ($field[4] as $choice=>$text) $html.='<option value="'.esc_attr($choice).'"'.selected($value,$choice,false).'>'.esc_html($text).'</option>';
        $html.='</select>';
    } elseif ($type==='checkboxes') {
        $html='<fieldset id="'.esc_attr($id).'" class="request-field" tabindex="-1"'.($error?' aria-describedby="'.$id.'-error"':'').'><legend>'.esc_html($label).($required?' (required)':'').'</legend>';
        foreach ($field[4] as $choice=>$text) {
            $check_id=$id.'-'.sanitize_title($choice);
            $html.='<label class="request-choice" for="'.esc_attr($check_id).'"><input type="checkbox" id="'.esc_attr($check_id).'" name="'.esc_attr($key).'[]" value="'.esc_attr($choice).'"'.checked(in_array($choice,(array)$value,true),true,false).'>'.esc_html($text).'</label>';
        }
    } elseif ($type==='textarea') {
        $html.='<textarea id="'.esc_attr($id).'" name="'.esc_attr($key).'"'.$described.($required?' required':'').'>'.esc_textarea(is_string($value)?$value:'').'</textarea>';
    } else {
        $input_type=$type==='quantity'?'number':($type==='email'?'email':($type==='url'?'url':'text'));
        $placeholder=$kind==='documents' && $key==='country_region'?' placeholder="Enter your country or region"':'';
        $html.='<input id="'.esc_attr($id).'" name="'.esc_attr($key).'" type="'.$input_type.'" value="'.esc_attr(is_string($value)?$value:'').'"'.($type==='quantity'?' min="0.001" step="any"':'').$placeholder.$described.($required?' required':'').'>';
        if ($type==='quantity') $html.='<span class="request-unit">Metric tonnes (MT)</span>';
    }
    if ($key==='business_email') $html.='<small>Use an address where we can contact you about this request.</small>';
    if ($key==='test_objective') $html.='<small>Describe what you need to evaluate. Do not include a confidential formulation.</small>';
    if ($key==='country_region') $html.='<small>Enter the country or region where your company is based.</small>';
    if ($key==='additional_requirements' || $key==='additional_context') $html.='<small>Please share non-confidential details only.</small>';
    if ($error) $html.='<strong class="field-error" id="'.esc_attr($id).'-error">'.esc_html($error).'</strong>';
    return $html.($type==='checkboxes'?'</fieldset>':'</div>');
}
add_shortcode('tio2_request_form',function ($atts) {
    $kind=isset($atts['kind'])&&is_string($atts['kind'])?$atts['kind']:'';
    if (!is_page() || tio2_request_kind(get_queried_object_id())!==$kind) return '';
    if (!tio2_target_url($kind)) return '<section class="wrap request-form-section" id="request-form"><h2>Request form unavailable</h2><p>This form is not accepting requests right now. Please try again later.</p></section>';
    $result=get_transient(tio2_request_result_key(tio2_flow_session(),$kind));
    $result=is_array($result)?$result:[];
    $values=$result['values']??tio2_request_prefill($kind);$errors=$result['errors']??[];
    $fields=tio2_request_fields($kind);
    $heading=['quote'=>'Quotation request details','documents'=>'Tell us which documents you need','sample'=>'Tell us what you need to evaluate'][$kind];
    $button=['quote'=>'REQUEST QUOTE','documents'=>'Request Documents','sample'=>'Submit Sample Request for Review'][$kind];
    $privacy=['quote'=>'quotation','documents'=>'document','sample'=>'sample'][$kind];
    $html='<section class="wrap request-form-section" id="request-form"><h2>'.esc_html($heading).'</h2><p>Required fields are marked. Please use business information and avoid confidential or sensitive details.</p>';
    if (($result['state']??'')==='failure') $html.='<div class="request-notice error" role="alert" tabindex="-1"><h3>We could not confirm that your request was received.</h3><p>Your entries are still on this page. Please try again.</p></div>';
    if ($errors) {
        $html.='<div class="request-notice error" role="alert" tabindex="-1" id="request-errors"><h3>'.($kind==='quote'?'Please review the highlighted fields.':'Check the information you entered.').'</h3>'.($kind==='quote'?'<p>Correct the information below and try again. Your other entries are still here.</p>':'').'<ul>';
        foreach ($errors as $key=>$error) $html.='<li>'.($key==='form'?esc_html($error):'<a href="#request-'.esc_attr($key).'">'.esc_html($error).'</a>').'</li>';
        $html.='</ul></div>';
    }
    $html.='<form class="request-form" method="post" action="'.esc_url(admin_url('admin-post.php')).'" novalidate>';
    $html.='<input type="hidden" name="action" value="tio2_request_'.esc_attr($kind).'"><input type="hidden" name="request_token" value="'.esc_attr(bin2hex(random_bytes(32))).'">';
    $html.=wp_nonce_field('tio2_request_'.$kind,'tio2_nonce',true,false);
    $html.='<div class="request-honeypot" aria-hidden="true"><label>Leave blank<input name="website_check" tabindex="-1" autocomplete="off"></label></div>';
    $html.='<div class="request-fields">';
    $group='';
    foreach ($fields as $key=>$field) {
        $next=($kind==='quote' && in_array($key,['product_grade','company','additional_requirements'],true))
            ? ['product_grade'=>'Your requirement','company'=>'Company details','additional_requirements'=>'Additional requirements'][$key] : '';
        if ($next) $html.='<h3 class="request-group-heading">'.esc_html($next).'</h3>';
        $html.=tio2_request_field_html($kind,$key,$field,$values[$key]??($field[1]==='checkboxes'?[]:''),$errors[$key]??'');
    }
    $html.='</div><div class="request-submit"><p>We use the information you provide to review and respond to your '.esc_html($privacy).' request. Learn more in our <a href="'.esc_url(home_url('/privacy-policy/')).'">Privacy Policy</a>.</p>';
    if ($kind==='sample') $html.='<p>Submitting starts a human review. Any sample arrangement will be confirmed separately.</p>';
    $html.='<button class="button primary" type="submit">'.esc_html($button).'</button></div></form></section>';
    return $html;
});
