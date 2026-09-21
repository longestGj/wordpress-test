<?php
defined('ABSPATH') || exit;
// Read current saved data on every editor load, including after save/restore.
// These warnings never alter approved relationships or presentation data.
add_action('admin_notices',function(){
    global $post;
    if(!($post instanceof WP_Post)||$post->post_type!=='product'||!current_user_can('edit_post',$post->ID))return;
    $terms=wp_get_object_terms($post->ID,'product_application',['fields'=>'slugs']);
    if(is_wp_error($terms))return;
    $messages=[];$data=tio2_product_data($post->ID);
    foreach($data['applications']??[] as $a){
        $relation=$a['relation']??'';
        if(!empty($a['enabled']) && $relation && !in_array($relation,$terms,true))$messages[]=$relation.': enabled application description has no taxonomy relationship.';
    }
    // A single approved block may describe several terms (for example M-510).
    $process=wp_get_object_terms($post->ID,'product_process',['fields'=>'slugs']);
    $key=trim($data['process_key']??'');
    if(!is_wp_error($process)){
        $expected=$key===''?[]:[$key];sort($process);
        if($process!==$expected)$messages[]='Process consistency: product process key and assigned process taxonomy differ.';
    }
    if($messages)echo '<div class="notice notice-warning"><p><strong>Product relationship consistency</strong></p><p>'.implode('<br>',array_map('esc_html',array_unique($messages))).'</p><p>No relationships or copy were changed automatically.</p></div>';
});
function tio2_field_editor($value,$name,$label) {
    if(is_array($value)) {
        if(array_is_list($value) && $value) {
            echo '<fieldset class="tio2-repeater" data-prefix="'.esc_attr($name).'" style="border:1px solid #dcdcde;padding:12px;margin:12px 0"><legend><strong>'.esc_html($label).'</strong></legend><div class="tio2-items">';
            foreach($value as $k=>$v){echo '<div class="tio2-item">';tio2_field_editor($v,$name.'['.$k.']','Item '.($k+1));echo '<button type="button" class="button tio2-up">Move up</button> <button type="button" class="button tio2-remove">Remove item</button></div>';}
            echo '</div><p><button type="button" class="button tio2-add">Add item</button></p></fieldset>';return;
        }
        echo '<fieldset style="border:1px solid #dcdcde;padding:12px;margin:12px 0"><legend><strong>'.esc_html($label).'</strong></legend>';
        foreach($value as $k=>$v) tio2_field_editor($v,$name.'['.$k.']',is_int($k)?'Item '.($k+1):ucwords(str_replace('_',' ',$k)));
        echo '</fieldset>'; return;
    }
    $id='field-'.md5($name);
    echo '<p><label for="'.esc_attr($id).'">'.esc_html($label).'</label><br>';
    if(is_bool($value)) echo '<input type="hidden" name="'.esc_attr($name).'" value="0"><input id="'.esc_attr($id).'" type="checkbox" name="'.esc_attr($name).'" value="1" '.checked($value,true,false).'>';
    else echo '<textarea id="'.esc_attr($id).'" name="'.esc_attr($name).'" rows="'.(strlen($value)>130?3:1).'" style="width:100%">'.esc_textarea($value).'</textarea>';
    echo '</p>';
}
add_action('add_meta_boxes_product', function () {
    add_meta_box('tio2-data','Product content and technical data',function($post){
        wp_nonce_field('tio2_save','tio2_nonce');
        $data=tio2_product_data($post->ID);
        if(!$data) { echo '<p>Start with a blank product structure. No values are copied from another Grade.</p>'; $seed=json_decode(file_get_contents(__DIR__.'/blank.json'),true); $data=$seed; }
        echo '<p>Title and excerpt are above. Edit product sections below. Uncheck Enabled to withdraw a technical row or application description. Source Note is internal only.</p>';
        foreach($data as $k=>$v) tio2_field_editor($v,'tio2['.$k.']',ucwords(str_replace('_',' ',$k)));
    },'product','normal','high');
});
function tio2_clean_fields($data) {
    $out=[]; foreach($data as $k=>$v) { $key=sanitize_key((string)$k); $out[$key]=is_array($v)?tio2_clean_fields($v):(in_array($key,['enabled','qualified'],true)?($v==='1'):sanitize_textarea_field($v)); } return $out;
}
add_action('save_post_product',function($id){
    if(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE || wp_is_post_revision($id) || !current_user_can('edit_post',$id) || !isset($_POST['tio2_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tio2_nonce'])),'tio2_save')) return;
    if(!isset($_POST['tio2']) || !is_array($_POST['tio2'])) return;
    $data=tio2_clean_fields(wp_unslash($_POST['tio2'])); $result=tio2_validate_product($data);
    if(is_wp_error($result)){ set_transient('tio2_error_'.get_current_user_id(),$result->get_error_message(),60); return; }
    update_post_meta($id,'_tio2_product',$data);
});
add_action('admin_enqueue_scripts',function(){if(in_array(get_current_screen()->post_type,['product','page'],true))wp_enqueue_script('tio2-product-editor',plugins_url('editor.js',__FILE__),[],filemtime(__DIR__.'/editor.js'),true);});
add_action('admin_notices',function(){ $key='tio2_error_'.get_current_user_id(); if($error=get_transient($key)){echo '<div class="notice notice-error"><p>Product fields were not saved: '.esc_html($error).'</p></div>';delete_transient($key);} });
add_action('admin_menu',function(){add_submenu_page('edit.php?post_type=product','Product destinations','Destinations','manage_options','tio2-destinations','tio2_destinations_page');});
function tio2_destinations_page(){
    if(!current_user_can('manage_options')) return;
    if(isset($_POST['tio2_targets_nonce']) && check_admin_referer('tio2_targets','tio2_targets_nonce')){
        $values=[]; foreach(tio2_target_keys() as $k){$values[$k]=absint($_POST['targets'][$k]??0); if(in_array($k,['quote','sample','documents'],true)) $values[$k.'_ready']=!empty($_POST['targets'][$k.'_ready']);} update_option('tio2_targets',$values);
        echo '<div class="notice notice-success"><p>Destinations saved.</p></div>';
    }
    $values=get_option('tio2_targets',[]);
    echo '<div class="wrap"><h1>Product destinations</h1><p>Choose a published WordPress page. Enable a form destination only after its receiving workflow has been tested.</p><form method="post">';wp_nonce_field('tio2_targets','tio2_targets_nonce');
    foreach(tio2_target_keys() as $k){echo '<p><label><strong>'.esc_html(ucwords(str_replace('-',' ',$k))).'</strong><br>';wp_dropdown_pages(['name'=>'targets['.$k.']','selected'=>$values[$k]??0,'show_option_none'=>'Not configured','option_none_value'=>0]);echo '</label>';if(in_array($k,['quote','sample','documents'],true)) echo ' <label><input type="checkbox" name="targets['.esc_attr($k).'_ready]" value="1" '.checked(!empty($values[$k.'_ready']),true,false).'> Receiving workflow tested</label>';echo '</p>';}
    submit_button();echo '</form></div>';
}
