<?php
/** Document guide identities and safe request context; presentation belongs to the theme. */
defined('ABSPATH') || exit;
function tio2_document_ids() { return ['DOC-REACH'=>'reach','DOC-TDS'=>'tds-sds-coa','DOC-COO'=>'certificate-of-origin']; }
function tio2_document_id($id) {
    $key = get_post_meta($id, '_tio2_document_id', true);
    return isset(tio2_document_ids()[$key]) && tio2_owns_page($id, '_tio2_document_id', $key) ? $key : '';
}
function tio2_document_links() {
    $labels = ['DOC-REACH'=>'REACH documentation','DOC-TDS'=>'TDS, SDS & COA','DOC-COO'=>'Certificate of Origin'];
    $links = [];
    foreach (tio2_document_ids() as $identity=>$slug) {
        $post = get_page_by_path('documents/'.$slug, OBJECT, 'page');
        if ($post && tio2_document_id($post->ID) === $identity && get_post_status($post) === 'publish' && !post_password_required($post)) {
            $links[] = ['url'=>get_permalink($post), 'label'=>$labels[$identity]];
        }
    }
    return $links;
}
add_shortcode('tio2_document_grades', function () {
    $html = '<label for="document-grade">Product Grade</label><select id="document-grade"><option value="">Choose a Grade</option>';
    // Use the existing directory; this is context, never a grade-document availability relationship.
    foreach (tio2_discovery_data()['rows'] ?? [] as $row) {
        if (!tio2_route_ready($row['url'])) continue;
        $html .= '<option value="'.esc_attr($row['grade']).'" data-url="'.esc_url(home_url($row['url'])).'">'.esc_html($row['grade']).'</option>';
    }
    return $html.'</select><p><a id="document-grade-detail" hidden>View selected Grade</a></p><p><a href="'.esc_url(home_url('/products/')).'">Explore all product Grades</a></p>';
});
function tio2_document_content($id) {
    if (post_password_required($id)) return get_the_password_form($id);
    $html = do_shortcode(do_blocks(get_post_field('post_content', $id)));
    $dom = new DOMDocument('1.0', 'UTF-8');
    $previous = libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?><div id="document-root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();libxml_use_internal_errors($previous);
    foreach (iterator_to_array($dom->getElementsByTagName('a')) as $a) {
        $href = $a->getAttribute('href');
        $parts = wp_parse_url($href);
        $site = wp_parse_url(home_url());
        $relative = str_starts_with($href, '/') && !str_starts_with($href, '//');
        $same_site = isset($parts['host']) && strtolower($parts['host']) === strtolower($site['host'])
            && ($parts['port'] ?? null) === ($site['port'] ?? null);
        if (!$relative && !$same_site) continue;
        $path = $parts['path'] ?? '/';
        $local = $path.(isset($parts['query']) ? '?'.$parts['query'] : '').(isset($parts['fragment']) ? '#'.$parts['fragment'] : '');
        if ($path === '/request-documents/') {
            $receiver = tio2_target_url('documents');
            if (!$receiver) { $a->parentNode->removeChild($a); continue; }
            $query=[];
            if (isset($parts['query'])) wp_parse_str($parts['query'],$query);
            // Keep only the request prefill keys; the receiver validates every value again.
            $context=[];
            foreach (['prefill_product_grade','prefill_document_types'] as $key) {
                if (isset($query[$key])) $context[$key]=$query[$key];
            }
            $context['source_page']=tio2_document_id($id);
            $a->setAttribute('href', add_query_arg($context,$receiver));
        } elseif (!tio2_route_ready($path)) {
            $a->parentNode->replaceChild($dom->createTextNode($a->textContent), $a);
        } else {
            $a->setAttribute('href', home_url($local));
        }
    }
    if (!tio2_target_url('documents')) {
        $xpath = new DOMXPath($dom);
        foreach (iterator_to_array($xpath->query('//*[@data-requires-document-receiver]')) as $node) if ($node->parentNode) $node->parentNode->removeChild($node);
    }
    $result = '';
    foreach ($dom->getElementById('document-root')->childNodes as $node) $result .= $dom->saveHTML($node);
    return $result;
}
