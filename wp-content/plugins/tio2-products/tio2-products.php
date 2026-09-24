<?php
/**
 * Plugin Name: TiO2Products
 * Description: Product records, editable technical data and shared destination settings.
 * Version: 0.1.0
 */
defined('ABSPATH') || exit;
function tio2_register_product_content() {
    register_post_type('product', ['labels'=>['name'=>'Products','singular_name'=>'Product','add_new_item'=>'Add Product','edit_item'=>'Edit Product'], 'public'=>true,'show_in_rest'=>false,'has_archive'=>'products','rewrite'=>['slug'=>'products','with_front'=>false],'menu_icon'=>'dashicons-products','supports'=>['title','excerpt','thumbnail','revisions']]);
    register_post_meta('product','_tio2_product',['type'=>'object','single'=>true,'show_in_rest'=>false,'revisions_enabled'=>true]);
    foreach(['_tio2_seo_title','_tio2_seo_description'] as $key)register_post_meta('page',$key,['type'=>'string','single'=>true,'show_in_rest'=>false,'revisions_enabled'=>true]);
    register_post_meta('page','_tio2_discovery',['type'=>'object','single'=>true,'show_in_rest'=>false,'revisions_enabled'=>true]);
    foreach (['product_application'=>'Applications','product_process'=>'Processes'] as $tax=>$label) {
        register_taxonomy($tax, 'product', ['label'=>$label,'public'=>false,'show_ui'=>true,'show_admin_column'=>true,'hierarchical'=>true,'rewrite'=>false]);
    }
    tio2_register_topic_routes();
}
add_action('init','tio2_register_product_content');
register_activation_hook(__FILE__, function () { tio2_register_product_content(); flush_rewrite_rules(); });
function tio2_product_data($id) { $d=get_post_meta($id,'_tio2_product',true); return is_array($d)?$d:[]; }
function tio2_public_rows($id) { return array_values(array_filter(tio2_product_data($id)['rows']??[],fn($r)=>!empty($r['enabled']))); }
function tio2_public_applications($id) { return array_values(array_filter(tio2_product_data($id)['applications']??[],fn($a)=>!empty($a['enabled']) && has_term($a['relation'],'product_application',$id))); }
function tio2_validate_product($data) {
    if (!is_array($data) || empty($data['h1']) || !is_string($data['h1']) || !isset($data['rows']) || !is_array($data['rows'])) return new WP_Error('invalid_product','Product heading and technical rows are required.');
    $shape=json_decode(file_get_contents(__DIR__.'/blank.json'),true);
    $matches=function($value,$expected) use (&$matches) {
        if(is_array($expected)) {
            if(!is_array($value)) return false;
            if(array_is_list($expected)) { if(!array_is_list($value))return false; foreach($value as $v)if(!$matches($v,$expected[0]))return false; return true; }
            if(array_diff(array_keys($expected),array_keys($value))||array_diff(array_keys($value),array_keys($expected)))return false;
            foreach($expected as $k=>$v)if(!$matches($value[$k],$v))return false;
            return true;
        }
        return is_bool($expected)?is_bool($value):is_string($value);
    };
    $base=$data;
    foreach(['applications_intro','evaluation_intro','technical_intro'] as $optional){if(isset($base[$optional])){if(!is_string($base[$optional]))return new WP_Error('invalid_intro','Introduction must be text.');unset($base[$optional]);}}
    if(isset($base['table_columns'])){
        if(!is_array($base['table_columns']) || count($base['table_columns'])<2 || count($base['table_columns'])>4)return new WP_Error('invalid_columns','Two to four technical columns are required.');
        $keys=[];
        foreach($base['table_columns'] as $col){if(!is_array($col)||!in_array($col['key']??'', ['property','standard','typical_value','test_method'],true)||!is_string($col['label']??null)||trim($col['label'])===''||in_array($col['key'],$keys,true))return new WP_Error('invalid_columns','Invalid or duplicate technical column.');$keys[]=$col['key'];}
        if($keys[0]!=='property')return new WP_Error('invalid_columns','Property must be the first column.');
        unset($base['table_columns']);
    }
    foreach($base['rows'] as &$row){if(isset($row['test_method'])){if(!is_string($row['test_method']))return new WP_Error('invalid_method','Test method must be text.');unset($row['test_method']);}}unset($row);
    if(!$matches($base,$shape))return new WP_Error('invalid_shape','Product field structure is incomplete or invalid. Existing data was preserved.');
    $required=['h1','seo_title','seo_description','category','summary_title','positioning_title','applications_title','evaluation_title','technical_title','markets_title'];
    // Receiver-only sections may remain blank while the receiver is unavailable.
    if(tio2_target_url('quote'))$required[]='quote_label';
    if(tio2_target_url('sample'))$required=array_merge($required,['sample_label','sample_title']);
    if(tio2_target_url('documents'))$required=array_merge($required,['documents_title','documents_label','tds_label']);
    if(trim($data['process_key'])!=='')$required=array_merge($required,['process_label','process_link_label']);
    foreach($required as $key)if(trim($data[$key])==='')return new WP_Error('required_product_field','Required product text is empty: '.$key);
    foreach($data['applications'] as $app)if($app['enabled'])foreach(['relation','title','text'] as $key)if(trim($app[$key])==='')return new WP_Error('required_application','Each enabled application needs a relation, title and description.');
    foreach($data['evaluation'] as $group)if(trim($group['title'])==='')return new WP_Error('required_evaluation','Evaluation groups need a title.');
    foreach(['application_links','market_links'] as $group)foreach($data[$group] as $link)if(trim($link['relation'])!=='' && trim($link['label'])==='')return new WP_Error('required_link_label','Configured product links need a label.');
    foreach ($data['rows'] as $row) {
        foreach (['property','standard','typical_value'] as $key) if (!isset($row[$key]) || !is_string($row[$key]) || (!empty($row['enabled'])&&trim($row[$key])==='')) return new WP_Error('invalid_row','Each enabled technical row needs a property, standard and typical value. Use — for an explicitly absent value.');
    }
    $walk=function($v) use (&$walk) { if(is_array($v)){ foreach($v as $x) if(!$walk($x)) return false; return true; } return is_string($v)||is_bool($v); };
    return $walk($data)?$data:new WP_Error('invalid_field','Product fields must contain text or visibility values.');
}
function tio2_target_keys() { return ['quote','sample','documents','chloride','sulfate','coatings','printing-inks','plastics','masterbatch','paper','specialty','eu','uk','india','brazil']; }
function tio2_target_url($key) {
    $options=get_option('tio2_targets',[]); $id=absint($options[$key]??0);
    if (!$id || get_post_type($id)!=='page' || get_post_status($id)!=='publish' || post_password_required($id)) return '';
    if(in_array($key,['quote','sample','documents'],true)) {
        if (empty($options[$key.'_ready']) || !function_exists('tio2_request_kind') || tio2_request_kind($id)!==$key) return '';
    }
    return get_permalink($id);
}
function tio2_product_schema($id) {
    $d=tio2_product_data($id);
    return ['@context'=>'https://schema.org','@type'=>'Product','@id'=>get_permalink($id).'#product','url'=>get_permalink($id),'name'=>get_the_title($id).' Titanium Dioxide','sku'=>get_the_title($id),'description'=>get_post_field('post_excerpt',$id),'brand'=>['@id'=>home_url('/').'#brand'],'manufacturer'=>['@id'=>home_url('/').'#organization'],'additionalProperty'=>array_map(function($r)use($d){$values=[];foreach(array_slice(tio2_table_columns($d),1) as $c)$values[]=$c['label'].': '.($r[$c['key']]??'—');return ['@type'=>'PropertyValue','name'=>$r['property'],'value'=>implode('; ',$values)];},tio2_public_rows($id))];
}
function tio2_table_columns($data){return $data['table_columns']??[['key'=>'property','label'=>'Property'],['key'=>'standard','label'=>'Standard'],['key'=>'typical_value','label'=>'Typical Value']];}
require __DIR__.'/admin.php';
require __DIR__.'/pages.php';
require __DIR__.'/topics.php';
require __DIR__.'/revisions.php';
require __DIR__.'/ownership.php';

require_once __DIR__.'/documents.php';
require_once __DIR__.'/utility.php';

require_once __DIR__.'/requests.php';
require_once __DIR__.'/request-mail.php';
require_once __DIR__.'/contact-mail.php';
require_once __DIR__.'/request-receiver.php';

require_once __DIR__.'/request-form.php';
