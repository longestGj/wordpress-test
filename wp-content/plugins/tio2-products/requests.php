<?php
defined('ABSPATH') || exit;
/** Approved public choices and server-side request field contracts. */
function tio2_request_grades() {
    $names = ['M-350','M-510','M-896','M-996','M-2196','M-895','M-200','M-108','M-210','M-340','M-886','M-52','M-2377','CR-901'];
    return array_values(array_filter($names, static function ($name) {
        $post = get_page_by_path(strtolower($name), OBJECT, 'product');
        return $post && get_post_status($post) === 'publish' && !post_password_required($post);
    }));
}
function tio2_request_document_types() {
    return ['technical_product'=>'Technical Product Documentation','safety'=>'Safety Documentation',
        'quality_coa'=>'Quality & COA Documentation','origin_supplier_qualification'=>'Origin & Supplier Qualification','other'=>'Other'];
}
function tio2_request_fields($kind) {
    $grade = array_combine(tio2_request_grades(), tio2_request_grades());
    $shared = [
        'company'=>['Company', 'text', 200, true],
        'business_email'=>['Business Email', 'email', 254, true],
    ];
    if ($kind === 'quote') return [
        'product_grade'=>['Product / Grade','select',64,true,[''=>'Select a product or grade']+$grade+['Not sure / Need help'=>'Not sure / Need help']],
        'application'=>['Application','select',64,true,array_combine(['','Coatings','Plastics','Masterbatch','Printing Inks','Paper','Specialty Materials','Other / Not sure'],['Select an application','Coatings','Plastics','Masterbatch','Printing Inks','Paper','Specialty Materials','Other / Not sure'])],
        'quantity_mt'=>['Required Quantity','quantity',30,true],
        'destination_country'=>['Destination Country','text',100,true],
        'destination_port_city'=>['Destination Port / City','text',120,false],
        'company'=>['Company Name','text',160,true],
        'contact_name'=>['Your Name','text',100,true],
        'business_email'=>$shared['business_email'],
        'phone_whatsapp'=>['Phone / WhatsApp','text',40,false],
        'website'=>['Website','url',2048,false],
        'additional_requirements'=>['Additional Requirements','textarea',2000,false],
    ];
    if ($kind === 'documents') return [
        'full_name'=>['Full Name','text',120,true],
        'company'=>$shared['company'],
        'business_email'=>$shared['business_email'],
        'country_region'=>['Country / Region','text',120,true],
        'product_grade'=>['Product Grade','select',64,true,[''=>'Choose a Grade']+$grade],
        'document_types'=>['Document Types','checkboxes',5,true,tio2_request_document_types()],
        'application_industry'=>['Application / Industry','text',200,false],
        'additional_requirements'=>['Additional Requirements','textarea',500,false],
    ];
    if ($kind === 'sample') return [
        'product_grade'=>['Product grade','select',64,true,[''=>'Choose a product grade']+$grade+['I do not know the grade'=>'I do not know the grade']],
        'application'=>['Application','select',64,true,[''=>'Choose an application','Coatings'=>'Coatings','Plastics'=>'Plastics','Masterbatch'=>'Masterbatch','Printing Inks'=>'Printing Inks','Paper'=>'Paper','Specialty Materials'=>'Specialty Materials','Other'=>'Other','Not sure'=>'Not sure']],
        'application_other'=>['Describe the application','text',500,false],
        'test_objective'=>['What do you need to evaluate?','textarea',2000,true],
        'current_grade_or_target'=>['Current grade or target requirement','textarea',1000,false],
        'contact_name'=>['Contact name','text',120,true],
        'company_organisation'=>['Company or organisation','text',200,true],
        'business_email'=>$shared['business_email'],
        'destination_country_market'=>['Destination country or market','text',120,true],
        'expected_project_annual_use'=>['Expected project or annual use','text',500,false],
        'document_needs'=>['Documents needed for the trial','checkboxes',5,false,['TDS'=>'TDS','SDS'=>'SDS','COA'=>'COA','COO'=>'COO','Other / Not sure'=>'Other / Not sure']],
        'additional_context'=>['Additional non-confidential context','textarea',2000,false],
    ];
    return [];
}
function tio2_request_validate($kind, $input) {
    $fields=tio2_request_fields($kind);$values=[];$errors=[];
    if (!$fields || !is_array($input)) return [[],['form'=>'Invalid request.']];
    foreach ($fields as $key=>$field) {
        [$label,$type,$max,$required]=$field;
        if ($type==='checkboxes') {
            $raw=$input[$key]??[];
            $valid=is_array($raw) && array_is_list($raw) && count($raw)<=$max;
            $list=[];
            if ($valid) foreach ($raw as $item) {
                if (!is_string($item) || !array_key_exists($item,$field[4]) || in_array($item,$list,true)) {$valid=false;break;}
                $list[]=$item;
            }
            $values[$key]=$list;
            if (!$valid || ($required && !$list)) $errors[$key]='Choose a valid '.$label.'.';
            continue;
        }
        $raw=$input[$key]??'';
        if (!is_string($raw)) {$errors[$key]='Enter a valid '.$label.'.';$raw='';}
        $value=trim($type==='textarea'?sanitize_textarea_field(wp_unslash($raw)):sanitize_text_field(wp_unslash($raw)));
        $values[$key]=$value;
        if ($required && $value==='') $errors[$key]='Enter '.$label.'.';
        elseif (mb_strlen($value)>$max) $errors[$key]='Keep '.$label.' to '.$max.' characters or fewer.';
        elseif ($type==='select' && $value!=='' && !array_key_exists($value,$field[4])) $errors[$key]='Choose a valid '.$label.'.';
        elseif ($type==='email' && $value!=='' && !is_email($value)) $errors[$key]='Enter a valid business email address.';
        elseif ($type==='url' && $value!=='' && !filter_var($value,FILTER_VALIDATE_URL)) $errors[$key]='Enter a valid website URL.';
        elseif ($type==='quantity' && $value!=='' && (!preg_match('/^(?:[0-9]+(?:\.[0-9]+)?)$/D',$value) || (float)$value<=0)) $errors[$key]='Enter a positive quantity in metric tonnes.';
    }
    if ($kind==='quote') foreach (['company','contact_name'] as $key) if (!isset($errors[$key]) && mb_strlen($values[$key])<2) $errors[$key]='Enter at least two characters for '.$fields[$key][0].'.';
    if ($kind==='quote') {
        $copy=[
            'product_grade'=>'Select a product or grade, or choose “Not sure / Need help.”',
            'application'=>'Select an application.',
            'quantity_mt'=>'Enter a quantity greater than 0.',
            'destination_country'=>($values['destination_country']===''?'Enter a destination country.':'Keep the destination country to 100 characters or fewer.'),
            'destination_port_city'=>'Keep the destination port or city to 120 characters or fewer.',
            'company'=>isset($errors['company']) && mb_strlen($values['company'])>160?'Keep your company name to 160 characters or fewer.':'Enter your company name.',
            'contact_name'=>isset($errors['contact_name']) && mb_strlen($values['contact_name'])>100?'Keep your name to 100 characters or fewer.':'Enter your name.',
            'business_email'=>$values['business_email']===''?'Enter your business email.':'Enter a business email in the format name@company.com.',
            'phone_whatsapp'=>'Keep the phone or WhatsApp number to 40 characters or fewer.',
            'website'=>'Enter a complete website address or remove this optional value.',
            'additional_requirements'=>'Keep additional requirements to 2,000 characters or fewer.',
        ];
        foreach ($errors as $key=>&$error) if (isset($copy[$key])) $error=$copy[$key];
        unset($error);
    }
    if ($kind==='documents' && ($values['document_types']??[])===['other'] && $values['additional_requirements']==='') $errors['additional_requirements']='Describe the other document need.';
    if ($kind==='documents' && isset($errors['country_region']) && $values['country_region']==='') $errors['country_region']='Enter your country or region.';
    if ($kind==='sample' && $values['application']!=='Other') $values['application_other']='';
    if ($kind==='sample' && $values['application']==='Other' && $values['application_other']==='') $errors['application_other']='Describe the application or choose another option.';
    return [$values,$errors];
}
