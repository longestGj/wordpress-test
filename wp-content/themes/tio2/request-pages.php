<?php
defined('ABSPATH') || exit;
add_action('wp_enqueue_scripts',function () {
    if (!is_page() || !tio2_request_kind(get_queried_object_id())) return;
    wp_enqueue_style('tio2-request-pages',get_template_directory_uri().'/assets/request-pages.css',['tio2'],filemtime(__DIR__.'/assets/request-pages.css'));
    wp_enqueue_script('tio2-request-pages',get_template_directory_uri().'/assets/request-pages.js',[],filemtime(__DIR__.'/assets/request-pages.js'),true);
});
add_action('wp_head',function () {
    $id=get_queried_object_id();if (!is_page() || !tio2_request_kind($id)) return;
    $url=get_permalink($id);$title=get_post_meta($id,'_tio2_seo_title',true);$description=get_post_meta($id,'_tio2_seo_description',true);
    echo '<meta name="description" content="'.esc_attr($description).'">';
    $graph=[['@type'=>'WebPage','@id'=>$url.'#page','url'=>$url,'name'=>$title,'description'=>$description,'inLanguage'=>'en'],
        ['@type'=>'BreadcrumbList','itemListElement'=>[['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>home_url('/')],['@type'=>'ListItem','position'=>2,'name'=>get_the_title($id),'item'=>$url]]]];
    echo '<script type="application/ld+json">'.wp_json_encode(['@context'=>'https://schema.org','@graph'=>$graph],JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT).'</script>';
});
function tio2_render_request_page($id) {
    if (post_password_required($id)) {
        echo '<main id="main" class="wrap section">'.get_the_password_form($id).'</main>';return;
    }
    echo '<main id="main" tabindex="-1" class="request-page request-'.esc_attr(tio2_request_kind($id)).'">'.do_shortcode(do_blocks(get_post_field('post_content',$id))).'</main>';
}
