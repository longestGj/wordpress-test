<?php
defined('ABSPATH') || exit;
add_action('wp_enqueue_scripts', function () {
    if (!is_page() || !tio2_document_id(get_queried_object_id())) return;
    wp_enqueue_style('tio2-documents', get_template_directory_uri().'/assets/documents.css', ['tio2'], filemtime(__DIR__.'/assets/documents.css'));
    wp_enqueue_script('tio2-documents', get_template_directory_uri().'/assets/documents.js', [], filemtime(__DIR__.'/assets/documents.js'), true);
});
function tio2_render_document_page($id) {
    echo '<main id="main" tabindex="-1" class="document-guide '.esc_attr(strtolower(tio2_document_id($id))).'" data-document-id="'.esc_attr(tio2_document_id($id)).'">'.tio2_document_content($id);echo '<nav class="document-section topicLinks" aria-label="Related document and product paths"><a href="'.esc_url(home_url('/products/')).'">Explore Products</a>';foreach(tio2_document_links() as $link)if($link['url']!==get_permalink($id))echo '<a href="'.esc_url($link['url']).'">'.esc_html($link['label']).'</a>';echo '</nav></main>';
}
function tio2_render_document_links() {
    $links = tio2_document_links();
    if (!$links) return;
    echo '<nav class="wrap section" aria-label="Document guides"><h2>Document guides</h2><div class="topicLinks">';
    foreach ($links as $link) echo '<a href="'.esc_url($link['url']).'">'.esc_html($link['label']).'</a>';
    echo '</div></nav>';
}
add_action('wp_head', function () {
    $id = get_queried_object_id();
    if (!is_page() || !tio2_document_id($id)) return;
    $title = get_post_meta($id, '_tio2_seo_title', true); $description = get_post_meta($id, '_tio2_seo_description', true); $url = get_permalink($id);
    echo '<meta name="description" content="'.esc_attr($description).'">';
    foreach (['og:title'=>$title,'og:description'=>$description,'og:url'=>$url,'og:type'=>'website'] as $name=>$value) echo '<meta property="'.esc_attr($name).'" content="'.esc_attr($value).'">';
    $graph = [['@type'=>'WebPage','@id'=>$url.'#page','url'=>$url,'name'=>$title,'description'=>$description,'inLanguage'=>'en'],
        ['@type'=>'BreadcrumbList','itemListElement'=>[
            ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>home_url('/')],
            ['@type'=>'ListItem','position'=>2,'name'=>'Documents','item'=>home_url('/documents/')],
            ['@type'=>'ListItem','position'=>3,'name'=>get_the_title($id),'item'=>$url]]]];
    echo '<script type="application/ld+json">'.wp_json_encode(['@context'=>'https://schema.org','@graph'=>$graph], JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT).'</script>';
});
add_filter('nav_menu_link_attributes', function ($atts, $item) {
    if (is_page() && tio2_document_id(get_queried_object_id()) && untrailingslashit($item->url) === untrailingslashit(home_url('/documents/'))) $atts['aria-current'] = 'true';
    return $atts;
}, 10, 2);
