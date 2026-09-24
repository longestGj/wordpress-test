<?php
/** Apply reviewed Germany and Italy modules to owned local Pages, preserving other editor content. */
if (!in_array(wp_parse_url(home_url(), PHP_URL_HOST), ['localhost', '127.0.0.1', '[::1]'], true)) {
    WP_CLI::error('Local site only.');
}

$targets = [
    'MARKET-EU-DE' => [
        'path' => 'markets/germany',
        'old' => [
            3 => '4ba41c1713a9e8625bcbedfc77bc27f4cb526eb0ca310a161aeac6973ce661b1',
            4 => 'a2d40c04c4e0d167c5f9633ddfaf01152180878f8b2f807f691eb544a99610d7',
            5 => '1dfb6a49fbaf6f3eb2bbc49ee75591fb9d3243fdc082d994c2a3f8eefb1f915e',
            7 => '9a3804f2474811fa9c104a406639be249f92ea8b8c282d0d8565d595ca5ce50e',
        ],
    ],
    'MARKET-EU-IT' => [
        'path' => 'markets/italy',
        'old' => [
            2 => '5a569ff57450c153004c455305846da20d76c8d0221d9fc01a5a6a97ee61bfbe',
            3 => '241a310bc3cff22db16f2e7eac005c583f36a9d2a70f668165b95c4ad62042ae',
            4 => 'ee241b6916d4fe57c90e6f9f7fcc1530254db2bfd40b9670774dc8067497dbeb',
            5 => '9b2b4ff8310a6b32dff934e194cde5bf696712c4d7cd4653203a73b18fce6dd6',
            7 => '46780c0f7bde7e5acd57d19ce7fb29c65ac70a669aa283ee751736557be02418',
        ],
    ],
];

function tio2_country_module(string $content, int $number): string {
    $pattern = '~<!-- wp:html -->\R<section class="market-(?:hero|section) market-module-'.$number.'".*?<!-- /wp:html -->~s';
    if (preg_match_all($pattern, $content, $matches) !== 1) {
        WP_CLI::error("Market module $number missing or duplicated; no pages updated.");
    }
    return $matches[0][0];
}

$updates = [];
foreach ($targets as $identity => $target) {
    $page = get_page_by_path($target['path'], OBJECT, 'page');
    if (!$page || $page->post_status !== 'publish'
        || !tio2_owns_page($page->ID, '_tio2_market_id', $identity)) {
        WP_CLI::error("Owned published Page required: $identity; no pages updated.");
    }
    $seed = json_decode(file_get_contents('/workspace/data/markets/'.$identity.'.json'), true, 512, JSON_THROW_ON_ERROR);
    if (($seed['identity'] ?? '') !== $identity || ($seed['path'] ?? '') !== '/'.$target['path'].'/') {
        WP_CLI::error("Seed identity or path mismatch: $identity; no pages updated.");
    }
    $content = $page->post_content;
    foreach ($target['old'] as $number => $old_hash) {
        $existing = tio2_country_module($content, $number);
        $replacement = tio2_country_module($seed['content'], $number);
        $current_hash = hash('sha256', $existing);
        if ($current_hash === hash('sha256', $replacement)) continue;
        if ($current_hash !== $old_hash) {
            WP_CLI::error("$identity module $number was edited; no pages updated.");
        }
        $content = str_replace($existing, $replacement, $content);
    }
    $updates[] = [$page, $content];
}

foreach ($updates as [$page, $content]) {
    if ($page->post_content === $content) {
        WP_CLI::log('Already current: '.$page->ID);
        continue;
    }
    $result = wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash($content)], true);
    if (is_wp_error($result)) WP_CLI::error($result->get_error_message());
    clean_post_cache($page->ID);
    if (get_post_field('post_content', $page->ID) !== $content) {
        WP_CLI::error('Updated content did not persist: '.$page->ID);
    }
    WP_CLI::log('Refined owned Page: '.$page->ID);
}
WP_CLI::success('Germany and Italy modules updated; other editor content preserved.');
