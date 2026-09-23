<?php
/** Standalone validation contract; no database or network. */
define('ABSPATH', __DIR__);
function add_action(...$args) {}
function add_shortcode(...$args) {}
function sanitize_text_field($value) { return trim(strip_tags($value)); }
function sanitize_textarea_field($value) { return trim(strip_tags($value)); }
function wp_unslash($value) { return $value; }
function is_email($value) { return filter_var($value, FILTER_VALIDATE_EMAIL); }
function get_page_by_path($name,$output,$type) { return (object)['ID'=>1]; }
function get_post_status($post) { return 'publish'; }
function post_password_required($post) { return false; }
define('OBJECT','OBJECT');
$plugin=__DIR__.'/../wp-content/plugins/tio2-products/requests.php';
if (!is_file($plugin)) $plugin='/var/www/html/wp-content/plugins/tio2-products/requests.php';
require $plugin;
function check($condition,$message) { if (!$condition) throw new RuntimeException($message); }
$rfq=['product_grade'=>'M-350','application'=>'Coatings','quantity_mt'=>'2.5','destination_country'=>'Malaysia','company'=>'Acme Co','contact_name'=>'Jane','business_email'=>'jane@example.com'];
[$values,$errors]=tio2_request_validate('quote',$rfq);
check(!$errors && $values['quantity_mt']==='2.5','Valid quote rejected');
$rfq['quantity_mt']='0';[, $errors]=tio2_request_validate('quote',$rfq);check(isset($errors['quantity_mt']),'Non-positive quantity accepted');
check($errors['quantity_mt']==='Enter a quantity greater than 0.','Quote quantity error copy changed');
$rfq['quantity_mt']='2';$rfq['destination_country']=str_repeat('X',101);[, $errors]=tio2_request_validate('quote',$rfq);check(isset($errors['destination_country']),'Long destination accepted');
check($errors['destination_country']==='Keep the destination country to 100 characters or fewer.','Quote destination error copy changed');
$doc=['full_name'=>'Jane','company'=>'Acme','business_email'=>'jane@example.com','country_region'=>'Malaysia','product_grade'=>'M-350','document_types'=>['other'],'additional_requirements'=>''];
[, $errors]=tio2_request_validate('documents',$doc);check(isset($errors['additional_requirements']),'Other-only empty context accepted');
$doc['document_types']=['other','safety'];[, $errors]=tio2_request_validate('documents',$doc);check(!$errors,'Mixed document choices rejected');
$doc['document_types']=['quality_coa','unknown'];[, $errors]=tio2_request_validate('documents',$doc);check(isset($errors['document_types']),'Unknown document choice accepted');
$doc['document_types']=['safety'];$doc['country_region']=' ';[, $errors]=tio2_request_validate('documents',$doc);check($errors['country_region']==='Enter your country or region.','Document country error copy changed');
$sample=['product_grade'=>'I do not know the grade','application'=>'Other','application_other'=>'Trial coating','test_objective'=>'Opacity','contact_name'=>'Jane','company_organisation'=>'Acme','business_email'=>'jane@example.com','destination_country_market'=>'Malaysia'];
[, $errors]=tio2_request_validate('sample',$sample);check(!$errors,'Valid unknown-grade sample rejected');
$sample['application_other']=' ';[, $errors]=tio2_request_validate('sample',$sample);check(isset($errors['application_other']),'Other application without detail accepted');
$sample['application_other']='Trial';$sample['test_objective']=str_repeat('x',2001);[, $errors]=tio2_request_validate('sample',$sample);check(isset($errors['test_objective']),'Oversize test objective accepted');
echo "PASS: quote, document and sample validation boundaries\n";
