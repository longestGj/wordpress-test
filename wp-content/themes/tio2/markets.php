<?php
/** Market presentation. The native Page editor owns copy; the theme owns rendering. */
defined('ABSPATH') || exit;

function tio2_market_id($id) {
    $identity = get_post_meta($id, '_tio2_market_id', true);
    return preg_match('/^MARKET-(?:EU-(?:001|DE|IT|ES|PL|NL|BE)|UK-001|IN-001|BR-(?:EN|PT))$/D', $identity)
        && tio2_owns_page($id, '_tio2_market_id', $identity) ? $identity : '';
}
function tio2_market_pair($id) {
    $identity = tio2_market_id($id);
    if (!in_array($identity, ['MARKET-BR-EN','MARKET-BR-PT'], true)) return null;
    $other = $identity === 'MARKET-BR-EN' ? 'MARKET-BR-PT' : 'MARKET-BR-EN';
    $path = $other === 'MARKET-BR-PT' ? 'pt-br/markets/brazil' : 'markets/brazil';
    $page = get_page_by_path($path, OBJECT, 'page');
    return $page && $page->post_status === 'publish' && !post_password_required($page)
        && tio2_owns_page($page->ID, '_tio2_market_id', $other) ? $page : null;
}
function tio2_market_content($id) {
    $html = do_shortcode(do_blocks(get_post_field('post_content', $id)));
    $p = new WP_HTML_Tag_Processor($html);
    while ($p->next_tag('a')) {
        $href = $p->get_attribute('href');
        if (!is_string($href) || !str_starts_with($href, '/') || str_starts_with($href, '//')) continue;
        if (!tio2_route_ready($href)) {
            $p->remove_attribute('href');$p->set_attribute('aria-disabled','true');
            $p->set_attribute('title','This destination is not connected in the local preview.');
        }
    }
    return $p->get_updated_html();
}
function tio2_render_market($id) {
    $pair = tio2_market_pair($id);
    $language = get_post_meta($id, '_tio2_language', true);
    echo '<main id="main" tabindex="-1" class="'.esc_attr(get_post_meta($id,'_tio2_main_class',true)).'">';
    if ($pair) {
        $pt = $language === 'pt-BR';
        echo '<nav class="market-language" aria-label="'.($pt ? 'Idioma' : 'Language').'">';
        echo '<a href="'.esc_url(get_permalink($pair)).'" hreflang="'.($pt ? 'en' : 'pt-BR').'" lang="'.($pt ? 'en' : 'pt-BR').'">'.($pt ? 'English' : 'Português (Brasil)').'</a></nav>';
    }
    echo tio2_market_content($id).'</main>';
}
add_action('wp_enqueue_scripts', function () {
    $id = get_queried_object_id();
    if (!is_page() || !tio2_market_id($id)) return;
    wp_enqueue_style('tio2-market', get_template_directory_uri().'/assets/markets.css', ['tio2'], filemtime(__DIR__.'/assets/markets.css'));
});
add_filter('pre_get_document_title', function ($title) {
    $id = get_queried_object_id();
    return is_page() && tio2_market_id($id) ? (get_post_meta($id,'_tio2_seo_title',true) ?: $title) : $title;
}, 20);
add_filter('language_attributes', function ($output) {
    $id = get_queried_object_id();
    if (!is_page() || !(tio2_market_id($id) === 'MARKET-BR-PT'
        || tio2_owns_page($id,'_tio2_market_id','MARKET-LOCALE-PT-BR')
        || tio2_owns_page($id,'_tio2_market_id','MARKET-LOCALE-PT-BR-MARKETS'))) return $output;
    return preg_replace('/lang="[^"]*"/', 'lang="pt-BR"', $output, 1);
});
add_filter('wp_robots', function ($robots) {
    $id = get_queried_object_id();
    if (is_page() && (tio2_owns_page($id,'_tio2_market_id','MARKET-LOCALE-PT-BR')
        || tio2_owns_page($id,'_tio2_market_id','MARKET-LOCALE-PT-BR-MARKETS'))) {
        unset($robots['index']);$robots['noindex'] = true;
    }
    return $robots;
});
add_filter('nav_menu_link_attributes', function ($atts, $item) {
    if (is_page() && tio2_market_id(get_queried_object_id())
        && untrailingslashit($item->url) === untrailingslashit(home_url('/markets/'))) $atts['aria-current'] = 'true';
    return $atts;
}, 10, 2);
add_action('wp_head', function () {
    $id = get_queried_object_id();if (!is_page() || !tio2_market_id($id)) return;
    $url = get_permalink($id);$title = get_post_meta($id,'_tio2_seo_title',true);
    $description = get_post_meta($id,'_tio2_seo_description',true);
    $language = get_post_meta($id,'_tio2_language',true);
    echo '<meta name="description" content="'.esc_attr($description).'">';
    foreach (['og:title'=>$title,'og:description'=>$description,'og:url'=>$url,'og:type'=>'website'] as $name=>$value)
        echo '<meta property="'.esc_attr($name).'" content="'.esc_attr($value).'">';
    $pair = tio2_market_pair($id);
    if ($pair) {
        $en = $language === 'en' ? $url : get_permalink($pair);
        $pt = $language === 'pt-BR' ? $url : get_permalink($pair);
        echo '<link rel="alternate" hreflang="en" href="'.esc_url($en).'">';
        echo '<link rel="alternate" hreflang="pt-BR" href="'.esc_url($pt).'">';
    }
    $crumb = [['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>home_url('/')],
              ['@type'=>'ListItem','position'=>2,'name'=>$language === 'pt-BR' ? 'Mercados' : 'Markets','item'=>home_url($language === 'pt-BR' ? '/pt-br/markets/' : '/markets/')],
              ['@type'=>'ListItem','position'=>3,'name'=>get_the_title($id),'item'=>$url]];
    $graph = [['@type'=>'WebPage','@id'=>$url.'#page','url'=>$url,'name'=>$title,'description'=>$description,'inLanguage'=>$language],
              ['@type'=>'BreadcrumbList','itemListElement'=>$crumb]];
    echo '<script type="application/ld+json">'.wp_json_encode(['@context'=>'https://schema.org','@graph'=>$graph],
        JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE).'</script>';
});
