<?php
defined('ABSPATH') || exit;
add_action('after_setup_theme',function(){add_theme_support('title-tag');add_theme_support('post-thumbnails');add_theme_support('html5',['search-form','gallery','caption','style','script']);register_nav_menus(['primary'=>'Primary navigation','footer'=>'Footer navigation']);});
add_action('wp_enqueue_scripts',function(){wp_enqueue_style('tio2',get_template_directory_uri().'/assets/site.css',[],filemtime(__DIR__.'/assets/site.css'));wp_enqueue_script('tio2',get_template_directory_uri().'/assets/site.js',[],filemtime(__DIR__.'/assets/site.js'),true);});
add_filter('pre_get_document_title',function($title){if(is_singular('product')&&function_exists('tio2_product_data')) return tio2_product_data(get_queried_object_id())['seo_title']??$title;return $title;});
add_filter('wp_robots',function($robots){if(!get_option('blog_public')){$robots=['noindex'=>true,'nofollow'=>true];}return $robots;});
add_filter('wp_sitemaps_enabled',fn($enabled)=>get_option('blog_public')?$enabled:false);
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
