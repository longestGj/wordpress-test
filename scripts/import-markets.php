<?php
/** Import eleven native market Pages. Preflight every route and identity before writing. */
$root = get_page_by_path('markets', OBJECT, 'page');
if (!$root || $root->post_status !== 'publish' || !tio2_owns_page($root->ID, '_tio2_hub_key', 'markets')) {
    WP_CLI::error('Markets parent ownership/readiness mismatch; run ownership migration and root import first.');
}
$expected = ['MARKET-EU-001','MARKET-EU-DE','MARKET-EU-IT','MARKET-EU-ES','MARKET-EU-PL','MARKET-EU-NL','MARKET-EU-BE','MARKET-UK-001','MARKET-IN-001','MARKET-BR-EN','MARKET-BR-PT'];
$seeds = [];
$paths = [];
foreach (glob('/workspace/data/markets/*.json') as $file) {
    $s = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    if (!in_array($s['identity'] ?? '', $expected, true) || isset($seeds[$s['identity']])
        || !preg_match('~^/(?:markets/[a-z-]+|pt-br/markets/brazil)/$~D', $s['path'] ?? '')
        || isset($paths[$s['path']]) || ($s['slug'] ?? '') !== basename(trim($s['path'], '/'))
        || ($s['language'] ?? '') !== ($s['identity'] === 'MARKET-BR-PT' ? 'pt-BR' : 'en')
        || empty($s['title']) || empty($s['content']) || empty($s['seo_title']) || empty($s['seo_description'])) {
        WP_CLI::error('Invalid or duplicate market seed; no changes made.');
    }
    $paths[$s['path']] = true;
    $seeds[$s['identity']] = $s;
}
if (array_diff($expected, array_keys($seeds)) || count($seeds) !== 11) WP_CLI::error('Exactly eleven market seeds required.');

// The localized route needs two native Page ancestors. They have their own stable identities.
$containers = [
    ['pt-br', 0, 'MARKET-LOCALE-PT-BR'],
    ['pt-br/markets', 'pt-br', 'MARKET-LOCALE-PT-BR-MARKETS'],
];
foreach ($containers as [$path, $parent, $identity]) {
    $p = get_page_by_path($path, OBJECT, 'page');
    if ($p && (!tio2_owns_page($p->ID, '_tio2_market_id', $identity) || $p->post_status !== 'publish')) {
        WP_CLI::error('Localized market ancestor collision: '.$path.'; no changes made.');
    }
}
foreach ($seeds as $s) {
    $path = trim($s['path'], '/');
    $p = get_page_by_path($path, OBJECT, 'page');
    if ($p && (!tio2_owns_page($p->ID, '_tio2_market_id', $s['identity']) || $p->post_status !== 'publish')) {
        WP_CLI::error('Market ownership collision: '.$path.'; no changes made.');
    }
    $by_identity = get_posts(['post_type'=>'page','post_status'=>'any','meta_key'=>'_tio2_market_id',
        'meta_value'=>$s['identity'],'numberposts'=>-1,'fields'=>'ids']);
    if ($by_identity && (!$p || count($by_identity) !== 1 || (int)$by_identity[0] !== (int)$p->ID)) {
        WP_CLI::error('Market identity already exists at another route: '.$s['identity'].'; no changes made.');
    }
}
$created = [];
try {
    $pt = get_page_by_path('pt-br', OBJECT, 'page');
    if (!$pt) {
        $id = wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_name'=>'pt-br','post_title'=>'Português (Brasil)',
            'post_content'=>'<!-- wp:paragraph --><p><a href="/pt-br/markets/brazil/">Brasil</a></p><!-- /wp:paragraph -->'], true);
        if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
        $created[] = $id;
        update_post_meta($id,'_tio2_owner','tio2-wordpress');update_post_meta($id,'_tio2_market_id','MARKET-LOCALE-PT-BR');
        $pt = get_post($id);
    }
    $pt_markets = get_page_by_path('pt-br/markets', OBJECT, 'page');
    if (!$pt_markets) {
        $id = wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_parent'=>$pt->ID,'post_name'=>'markets',
            'post_title'=>'Mercados','post_content'=>'<!-- wp:paragraph --><p><a href="/pt-br/markets/brazil/">Brasil</a></p><!-- /wp:paragraph -->'], true);
        if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
        $created[] = $id;
        update_post_meta($id,'_tio2_owner','tio2-wordpress');update_post_meta($id,'_tio2_market_id','MARKET-LOCALE-PT-BR-MARKETS');
        $pt_markets = get_post($id);
    }
    foreach ($expected as $identity) {
        $s = $seeds[$identity];$path = trim($s['path'], '/');
        if (get_page_by_path($path, OBJECT, 'page')) { WP_CLI::log('Preserved '.$identity); continue; }
        $id = wp_insert_post(['post_type'=>'page','post_parent'=>$identity === 'MARKET-BR-PT' ? $pt_markets->ID : $root->ID,
            'post_status'=>'publish','post_name'=>$s['slug'],'post_title'=>$s['title'],'post_content'=>wp_slash($s['content']),
            'comment_status'=>'closed','ping_status'=>'closed'], true);
        if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
        $created[] = $id;
        foreach (['_tio2_owner'=>'tio2-wordpress','_tio2_market_id'=>$identity,'_tio2_language'=>$s['language'],
            '_tio2_main_class'=>$s['main_class'],'_tio2_source'=>$s['source'],
            '_tio2_seo_title'=>$s['seo_title'],'_tio2_seo_description'=>$s['seo_description']] as $key=>$value) {
            if (!update_post_meta($id,$key,wp_slash($value))) throw new RuntimeException('Market metadata failed: '.$key);
        }
        if (get_permalink($id) !== home_url($s['path'])) throw new RuntimeException('Unexpected market route: '.$s['path']);
        WP_CLI::log('Imported '.$identity);
    }
} catch (Throwable $error) {
    foreach (array_reverse($created) as $id) wp_delete_post($id, true);
    WP_CLI::error($error->getMessage());
}
WP_CLI::success('Eleven market Pages ready; existing editor changes preserved.');
