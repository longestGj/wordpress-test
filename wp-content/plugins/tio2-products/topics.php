<?php
defined('ABSPATH') || exit;
// Two fixed process routes share /products/ with the native Product archive.
function tio2_register_topic_routes(){
    add_rewrite_rule('^products/((?:chloride|sulfate)-process-titanium-dioxide)/?$','index.php?pagename=$matches[1]','top');
}
add_filter('page_link',function($url,$id){
    $key=get_post_meta($id,'_tio2_topic',true);
    if(in_array($key,['chloride','sulfate'],true))return home_url('/products/'.$key.'-process-titanium-dioxide/');
    return $url;
},10,2);
add_action('template_redirect',function(){
    if(!is_page())return;
    $id=get_queried_object_id();$key=get_post_meta($id,'_tio2_topic',true);
    if(!in_array($key,['chloride','sulfate'],true)||is_preview())return;
    $path=wp_parse_url(wp_unslash($_SERVER['REQUEST_URI']??''),PHP_URL_PATH);
    if($path==='/'.$key.'-process-titanium-dioxide/'||$path==='/'.$key.'-process-titanium-dioxide'){
        wp_safe_redirect(get_permalink($id),301);exit;
    }
});
