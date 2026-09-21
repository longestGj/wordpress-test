<?php
/** Presentation for the eight approved Resource Pages; content stays in post_content. */
defined('ABSPATH') || exit;

function tio2_is_resource_page($id) {
    return get_post_type($id) === 'page'
        && in_array(get_post_meta($id, '_tio2_resource_id', true), [
            'RES-ORIGIN', 'RES-PROC', 'RES-CHEMOURS', 'RES-R706',
            'RES-TRADE-EU', 'RES-TRADE-UK', 'RES-TRADE-IN', 'RES-TRADE-BR',
        ], true);
}

add_action('wp_enqueue_scripts', function () {
    $id = get_queried_object_id();
    if (is_page() && get_post_meta($id, '_tio2_hub_key', true) === 'resources') {
        wp_enqueue_style('resource-hub', get_template_directory_uri().'/assets/resource-hub.css', ['tio2-hub'], filemtime(__DIR__.'/assets/resource-hub.css'));
    }
    if (!is_page() || !tio2_is_resource_page($id)) return;
    $key = strtolower(get_post_meta($id, '_tio2_resource_id', true));
    foreach (['resource-'.$key, 'resource-common'] as $name) {
        $path = __DIR__.'/assets/'.$name.'.css';
        if (is_file($path)) wp_enqueue_style($name, get_template_directory_uri().'/assets/'.$name.'.css', ['tio2'], filemtime($path));
    }
});

/** Add published research titles to the Hub's three existing research paths. */
add_filter('render_block_core/html', function ($html) {
    $id = get_queried_object_id();
    if (!is_page() || get_post_meta($id, '_tio2_hub_key', true) !== 'resources' || !str_contains($html, 'id="research-paths"')) return $html;
    $groups = [[], [], []];
    $order = array_flip(['RES-ORIGIN','RES-PROC','RES-CHEMOURS','RES-R706','RES-TRADE-EU','RES-TRADE-UK','RES-TRADE-IN','RES-TRADE-BR']);
    foreach (get_posts(['post_type'=>'page', 'post_status'=>'publish', 'post_parent'=>$id,
        'numberposts'=>-1, 'meta_key'=>'_tio2_resource_id', 'orderby'=>'title', 'order'=>'ASC']) as $post) {
        if (!tio2_is_resource_page($post->ID) || post_password_required($post)) continue;
        $identity = get_post_meta($post->ID, '_tio2_resource_id', true);
        if (!tio2_owns_page($post->ID,'_tio2_resource_id',$identity)) continue;
        $group = $identity === 'RES-ORIGIN' ? 0 : (str_starts_with($identity, 'RES-TRADE-') ? 2 : 1);
        $groups[$group][$order[$identity]] = $post;
    }
    $dom = new DOMDocument('1.0', 'UTF-8');
    $previous = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?><div id="resource-hub-root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();libxml_use_internal_errors($previous);
    $xpath = new DOMXPath($dom);
    $cards = $xpath->query('//*[@id="research-paths"]//article[p[contains(concat(" ",normalize-space(@class)," ")," res-label ")]]');
    if ($cards->length !== 3) return $html; // Respect an editor's structural change.
    $labels = ['01 / SOURCING'=>0, '02 / TECHNICAL EVALUATION'=>1, '03 / TRADE & MARKET'=>2];
    $by_category = [];
    foreach ($cards as $card) {
        $label = trim($xpath->query('./p[contains(concat(" ",normalize-space(@class)," ")," res-label ")]', $card)->item(0)->textContent);
        if (!isset($labels[$label]) || isset($by_category[$labels[$label]])) return $html;
        $by_category[$labels[$label]] = $card;
    }
    foreach ($groups as $index=>$posts) {
        $card = $by_category[$index];
        foreach (iterator_to_array($xpath->query('./ul[contains(concat(" ",normalize-space(@class)," ")," resource-hub-links ")]', $card)) as $previous_list) $card->removeChild($previous_list);
        if (!$posts) continue;
        ksort($posts);
        $list = $dom->createElement('ul');$list->setAttribute('class','resource-hub-links');
        foreach ($posts as $post) {
            $item = $dom->createElement('li');$link = $dom->createElement('a');
            $link->setAttribute('href',get_permalink($post));
            $link->appendChild($dom->createTextNode($post->post_title));
            $item->appendChild($link);$list->appendChild($item);
        }
        $card->appendChild($list);
    }
    $result = '';
    foreach ($dom->getElementById('resource-hub-root')->childNodes as $node) $result .= $dom->saveHTML($node);
    return $result;
});

// The existing hub title filter already reads _tio2_seo_title for all native Pages.
add_action('wp_head', function () {
    $id = get_queried_object_id();
    if (!is_page() || !tio2_is_resource_page($id)) return;
    $url = get_permalink($id);
    $title = get_post_meta($id, '_tio2_seo_title', true);
    $description = get_post_meta($id, '_tio2_seo_description', true);
    echo '<meta name="description" content="'.esc_attr($description).'">';
    foreach (['og:title'=>$title, 'og:description'=>$description, 'og:url'=>$url, 'og:type'=>'website'] as $name=>$value) {
        echo '<meta property="'.esc_attr($name).'" content="'.esc_attr($value).'">';
    }
    $graph = [['@type'=>'WebPage', '@id'=>$url.'#page', 'url'=>$url, 'name'=>$title, 'description'=>$description, 'inLanguage'=>'en']];
    if (tio2_route_ready('/resources/')) {
        $graph[] = ['@type'=>'BreadcrumbList', 'itemListElement'=>[
            ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>home_url('/')],
            ['@type'=>'ListItem','position'=>2,'name'=>'Resources','item'=>home_url('/resources/')],
            ['@type'=>'ListItem','position'=>3,'name'=>get_post_field('post_title',$id),'item'=>$url],
        ]];
    }
    echo '<script type="application/ld+json">'.wp_json_encode(['@context'=>'https://schema.org','@graph'=>$graph], JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE).'</script>';
});

add_filter('nav_menu_link_attributes', function ($atts, $item) {
    if (is_page() && tio2_is_resource_page(get_queried_object_id()) && untrailingslashit($item->url) === untrailingslashit(home_url('/resources/'))) {
        $atts['aria-current'] = 'true'; // Ancestor section, not a second current page.
    }
    return $atts;
}, 10, 2);

/** Remove unavailable actions without disabled buttons or guessed destinations. */
function tio2_resource_content($id) {
    $priority = has_filter('render_block_core/group', 'wp_restore_group_inner_container');
    if ($priority !== false) remove_filter('render_block_core/group', 'wp_restore_group_inner_container', $priority);
    try {
        $html = do_blocks(get_post_field('post_content', $id));
    } finally {
        if ($priority !== false) add_filter('render_block_core/group', 'wp_restore_group_inner_container', $priority, 2);
    }
    $dom = new DOMDocument('1.0', 'UTF-8');
    $previous = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?><div id="resource-content-root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    $xpath = new DOMXPath($dom);
    $pair_ready = true;
    foreach (['chloride','sulfate'] as $process) {
        $target = tio2_target_url($process);
        if (!$target || wp_parse_url($target, PHP_URL_PATH) !== '/products/'.$process.'-process-titanium-dioxide/') $pair_ready = false;
    }
    if (!$pair_ready) {
        foreach (iterator_to_array($xpath->query('//*[contains(concat(" ",normalize-space(@class)," ")," resource-process-pair ")]')) as $pair) {
            $pair->parentNode->removeChild($pair);
        }
    }
    foreach (iterator_to_array($dom->getElementsByTagName('a')) as $link) {
        $href = $link->getAttribute('href');
        if (!$href || $href[0] === '#') continue;
        $host = wp_parse_url($href, PHP_URL_HOST);
        if ($host && $host !== wp_parse_url(home_url(), PHP_URL_HOST)) continue;
        $path = wp_parse_url($href, PHP_URL_PATH);
        if ($path && tio2_route_ready($path)) continue;
        $parent = $link->parentNode;
        if (trim($parent->textContent) === trim($link->textContent) && in_array(strtolower($parent->nodeName), ['p','div'], true)) {
            $parent->parentNode->removeChild($parent);
        } else {
            // In prose/list context, retain the approved explanatory label as text.
            while ($link->firstChild) $parent->insertBefore($link->firstChild, $link);
            $parent->removeChild($link);
        }
    }
    $root = $dom->getElementById('resource-content-root');
    $result = '';
    foreach ($root->childNodes as $node) $result .= $dom->saveHTML($node);
    return $result;
}

function tio2_render_resource_page($id) {
    $classes = get_post_meta($id, '_tio2_resource_class', true);
    echo '<main id="main" tabindex="-1" class="'.esc_attr($classes).'">';
    echo '<nav class="resource-breadcrumb" aria-label="Breadcrumb"><a href="'.esc_url(home_url('/')).'">Home</a><span aria-hidden="true">/</span>';
    if (tio2_route_ready('/resources/')) echo '<a href="'.esc_url(home_url('/resources/')).'">Resources</a>';
    else echo '<span>Resources</span>';
    echo '<span aria-hidden="true">/</span><span aria-current="page">'.esc_html(get_post_field('post_title',$id)).'</span></nav>';
    echo tio2_resource_content($id).'</main>';
}
