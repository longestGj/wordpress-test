<?php
defined('ABSPATH') || exit;
function tio2_theme_dependencies_ready(){
    foreach(['tio2_product_data','tio2_target_url','tio2_hub_key','tio2_hub_id','tio2_hub_content','tio2_discovery_data','tio2_route_ready','tio2_product_schema','tio2_public_rows','tio2_public_applications','tio2_table_columns','tio2_owns_page'] as $function)if(!function_exists($function))return false;
    return true;
}
if(!tio2_theme_dependencies_ready()){
    add_action('admin_notices',function(){if(current_user_can('activate_plugins'))echo '<div class="notice notice-error"><p>The TiO2 theme requires the TiO2 Products plugin. Activate or restore it to display the website.</p></div>';});
    add_action('template_redirect',function(){nocache_headers();wp_die('The website is temporarily unavailable. Please try again later.','Website temporarily unavailable',['response'=>503]);},0);
    // Never load presentation callbacks that require the missing plugin.
    return;
}
add_action('after_setup_theme',function(){add_theme_support('title-tag');add_theme_support('post-thumbnails');add_theme_support('html5',['search-form','gallery','caption','style','script']);register_nav_menus(['primary'=>'Primary navigation','footer'=>'Footer navigation']);});
add_action('wp_enqueue_scripts',function(){wp_enqueue_style('tio2',get_template_directory_uri().'/assets/site.css',[],filemtime(__DIR__.'/assets/site.css'));wp_enqueue_script('tio2',get_template_directory_uri().'/assets/site.js',[],filemtime(__DIR__.'/assets/site.js'),true);});
add_filter('pre_get_document_title',function($title){if(is_singular('product')&&function_exists('tio2_product_data')) return tio2_product_data(get_queried_object_id())['seo_title']??$title;return $title;});
add_filter('wp_robots',function($robots){if(!get_option('blog_public')){$robots=['noindex'=>true,'nofollow'=>true];}return $robots;});
add_filter('wp_sitemaps_enabled',fn($enabled)=>get_option('blog_public')?$enabled:false);
add_filter('wp_sitemaps_add_provider',fn($provider,$name)=>$name==='users'?false:$provider,10,2);
add_action('template_redirect',function(){
    if(!is_author())return;
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    nocache_headers();
},0);
add_action('wp_head',function(){
    if(!is_singular('product')||!function_exists('tio2_product_data'))return;
    $id=get_queried_object_id();$d=tio2_product_data($id);if(!$d)return;
    echo '<meta name="description" content="'.esc_attr($d['seo_description']).'">';
    $crumb=['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>[]];
    foreach([['Home',home_url('/')],['Products',get_post_type_archive_link('product')],[get_the_title($id),get_permalink($id)]] as $i=>$item)$crumb['itemListElement'][]=['@type'=>'ListItem','position'=>$i+1,'name'=>$item[0],'item'=>$item[1]];
    echo '<script type="application/ld+json">'.wp_json_encode([tio2_product_schema($id),$crumb],JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE).'</script>';
});
function tio2_navigation(){
    if(has_nav_menu('primary')){wp_nav_menu(['theme_location'=>'primary','container'=>false,'depth'=>1]);return;}
    echo '<a href="'.esc_url(home_url('/')).'">Home</a><a href="'.esc_url(home_url('/products/')).'" '.(is_singular('product')||is_post_type_archive('product')?'aria-current="page"':'').'>Products</a>';
}
function tio2_paragraphs($items,$class=''){foreach($items as $text)echo '<p class="'.esc_attr($class).'">'.esc_html($text).'</p>';}
function tio2_action($key,$label,$primary=false,$extra=[]){
    $url=tio2_target_url($key);if(!$url)return;
    $context=['grade'=>get_the_title(),'source_page'=>'GRADE-'.str_replace('-','',strtoupper(get_the_title()))];
    echo '<a class="button '.($primary?'primary':'').'" href="'.esc_url(add_query_arg(array_merge($context,$extra),$url)).'">'.esc_html($label).'</a>';
}

require __DIR__.'/hubs.php';
require __DIR__.'/topics.php';
if(is_file(__DIR__.'/resources.php'))require __DIR__.'/resources.php';

require __DIR__.'/documents.php';
require __DIR__.'/markets.php';
require __DIR__.'/analytics.php';
require __DIR__.'/utility.php';

require __DIR__.'/request-pages.php';
