<?php
defined('ABSPATH') || exit;
function tio2_utility_id($id = 0) { return get_post_meta($id ?: get_queried_object_id(), '_tio2_page_id', true); }
function tio2_is_utility_page($id) { return in_array(tio2_utility_id($id), ['CONTACT-001','LEGAL-PRIV-EN','LEGAL-PRIV-MS','LEGAL-COOKIE-EN','CONV-THANK'], true); }
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('tio2-utility', get_template_directory_uri().'/assets/utility.js', [], filemtime(__DIR__.'/assets/utility.js'), true);
    if (!is_404() && !tio2_is_utility_page(get_queried_object_id())) return;
    wp_enqueue_style('tio2-utility', get_template_directory_uri().'/assets/utility.css', ['tio2'], filemtime(__DIR__.'/assets/utility.css'));
});
add_filter('pre_get_document_title', function ($title) {
    if (is_404()) return 'Page Not Found | TiO2 Malaysia';
    $id = get_queried_object_id();
    return tio2_is_utility_page($id) ? (get_post_meta($id, '_tio2_seo_title', true) ?: $title) : $title;
}, 30);
add_filter('wp_robots', function ($robots) {
    if (is_404()) {
        $robots['noindex'] = true;
        if (get_option('blog_public')) { unset($robots['nofollow']); $robots['follow'] = true; }
        else unset($robots['follow']);
    }
    if (is_page() && tio2_utility_id() === 'CONV-THANK') { $robots['noindex'] = true; $robots['nofollow'] = true; unset($robots['follow']); }
    return $robots;
}, 30);
add_filter('wp_sitemaps_posts_query_args', function ($args, $post_type) {
    if ($post_type === 'page') {
        foreach (['CONV-THANK','SYS-MS-ROOT'] as $identity) {
            $page = get_posts(['post_type'=>'page','post_status'=>'publish','meta_key'=>'_tio2_page_id','meta_value'=>$identity,'fields'=>'ids','posts_per_page'=>1]);
            if ($page) $args['post__not_in'] = array_merge($args['post__not_in'] ?? [], $page);
        }
    }
    return $args;
}, 10, 2);
add_filter('language_attributes', function ($output) {
    if (is_page() && tio2_utility_id() === 'LEGAL-PRIV-MS') return preg_replace('/lang="[^"]+"/', 'lang="ms-MY"', $output);
    return $output;
});
add_action('template_redirect', function () {
    if (is_page() && tio2_utility_id() === 'SYS-MS-ROOT') { wp_safe_redirect(home_url('/ms/privacy-policy/'), 302); exit; }
    if (is_page() && tio2_utility_id() === 'CONV-THANK') { nocache_headers(); header('Referrer-Policy: no-referrer'); }
});
add_action('wp_head', function () {
    if (!is_page() || !tio2_is_utility_page(get_queried_object_id())) return;
    $id = get_queried_object_id(); $page_id = tio2_utility_id($id);
    $title = get_post_meta($id, '_tio2_seo_title', true);
    $description = get_post_meta($id, '_tio2_seo_description', true);
    if ($description) echo '<meta name="description" content="'.esc_attr($description).'">';
    if ($page_id === 'CONV-THANK') return;
    $url = get_permalink($id);
    $graph = [['@type'=>$page_id === 'CONTACT-001' ? 'ContactPage' : 'WebPage', '@id'=>$url.'#page', 'url'=>$url, 'name'=>$title, 'description'=>$description, 'inLanguage'=>$page_id === 'LEGAL-PRIV-MS' ? 'ms-MY' : 'en'], ['@type'=>'BreadcrumbList','itemListElement'=>[['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>home_url('/')],['@type'=>'ListItem','position'=>2,'name'=>get_the_title($id),'item'=>$url]]]];
    echo '<script type="application/ld+json">'.wp_json_encode(['@context'=>'https://schema.org','@graph'=>$graph], JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE).'</script>';
});
add_shortcode('tio2_thank_state', function ($atts, $content = '') {
    if (!is_page() || tio2_utility_id() !== 'CONV-THANK') return '';
    $kind = tio2_request_receipt_kind() ?: 'invalid';
    return ($atts['kind'] ?? '') === $kind ? do_shortcode($content) : '';
});
add_shortcode('tio2_cookie_settings', function ($atts) {
    $label = ($atts['lang'] ?? '') === 'ms' ? 'Urus Tetapan Kuki' : 'Manage Cookie Settings';
    return '<button type="button" class="button cookie-settings-open'.(!empty($atts['primary']) ? ' primary' : '').'">'.esc_html($label).'</button>';
});
function tio2_render_utility_page($id) {
    $page_id = tio2_utility_id($id);
    $class = in_array($page_id, ['LEGAL-PRIV-EN','LEGAL-PRIV-MS','LEGAL-COOKIE-EN'], true) ? 'utility-legal' : 'utility-standard';
    echo '<main id="main" tabindex="-1" class="utility-page '.esc_attr($class).'">';
    if ($page_id !== 'CONV-THANK') echo '<nav class="wrap utility-crumb" aria-label="Breadcrumb"><a href="'.esc_url(home_url('/')).'">Home</a><span aria-hidden="true">/</span><span>'.esc_html(get_the_title($id)).'</span></nav>';
    if ($class === 'utility-legal') {
        $html = do_shortcode(do_blocks(get_post_field('post_content', $id)));
        $heading = get_the_title($id);
        if (preg_match('/<h1\b[^>]*>(.*?)<\/h1>/is', $html, $match)) {
            $heading = wp_strip_all_tags($match[1]);
            $html = preg_replace('/<h1\b[^>]*>.*?<\/h1>/is', '', $html, 1);
        }
        $toc = []; $seen = [];
        $html = preg_replace_callback('/<h2\b[^>]*>(.*?)<\/h2>/is', function ($match) use (&$toc, &$seen) {
            $label = wp_strip_all_tags($match[1]); $base = sanitize_title($label); $number = ($seen[$base] ?? 0) + 1; $seen[$base] = $number;
            $anchor = $base.($number > 1 ? '-'.$number : ''); $toc[] = [$anchor, $label];
            return '<h2 id="'.esc_attr($anchor).'">'.$match[1].'</h2>';
        }, $html);
        echo '<section class="utility-legal-hero"><div class="wrap"><h1>'.esc_html($heading).'</h1></div></section>';
        echo '<div class="wrap utility-body utility-legal-layout"><aside class="utility-toc" aria-label="Page contents"><h2>'.($page_id === 'LEGAL-PRIV-MS' ? 'Kandungan' : 'On this page').'</h2><nav aria-label="Sections">';
        foreach ($toc as [$anchor, $label]) echo '<a href="#'.esc_attr($anchor).'">'.esc_html($label).'</a>';
        echo '</nav><details><summary>'.($page_id === 'LEGAL-PRIV-MS' ? 'Kandungan halaman' : 'Page contents').'</summary><nav aria-label="Sections">';
        foreach ($toc as [$anchor, $label]) echo '<a href="#'.esc_attr($anchor).'">'.esc_html($label).'</a>';
        echo '</nav></details></aside><article class="utility-legal-content">'.$html.'</article></div>';
    } else {
        echo '<div class="utility-body">';
        $html = do_shortcode(do_blocks(get_post_field('post_content', $id)));
        $processor = new WP_HTML_Tag_Processor($html);
        while ($processor->next_tag('a')) {
            $href = $processor->get_attribute('href');
            if (!is_string($href) || !str_starts_with($href, '/') || str_starts_with($href, '//')) continue;
            if (!tio2_route_ready($href)) {
                $processor->remove_attribute('href');
                $processor->set_attribute('aria-disabled', 'true');
                $processor->set_attribute('title', 'This request route is not available yet.');
            }
        }
        echo $processor->get_updated_html();
        if ($page_id === 'CONV-THANK' && !tio2_request_receipt_kind() && !tio2_route_ready('/request-a-quote/') && !tio2_route_ready('/request-documents/') && !tio2_route_ready('/request-sample/')) echo '<div class="wrap utility-fallback"><p>These request forms are not available yet. For a general business question, <a href="'.esc_url(home_url('/contact/')).'">contact our team</a>.</p></div>';
        echo '</div>';
    }
    echo '</main>';
}
