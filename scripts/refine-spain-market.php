<?php
/** Apply reviewed Spain modules to the owned local Page, preserving other editor content. */
if (!in_array(wp_parse_url(home_url(), PHP_URL_HOST), ['localhost', '127.0.0.1', '[::1]'], true)) {
    WP_CLI::error('Local site only.');
}

$identity = 'MARKET-EU-ES';
$page = get_page_by_path('markets/spain', OBJECT, 'page');
if (!$page || $page->post_status !== 'publish'
    || !tio2_owns_page($page->ID, '_tio2_market_id', $identity)) {
    WP_CLI::error('Owned published Spain Page required.');
}
$seed = json_decode(file_get_contents('/workspace/data/markets/MARKET-EU-ES.json'), true, 512, JSON_THROW_ON_ERROR);
if (($seed['identity'] ?? '') !== $identity || ($seed['path'] ?? '') !== '/markets/spain/') {
    WP_CLI::error('Spain seed identity or path mismatch.');
}

function tio2_spain_module(string $content, int $number): string {
    $pattern = '~<!-- wp:html -->\R<section class="market-section market-module-'.$number.'".*?<!-- /wp:html -->~s';
    if (preg_match_all($pattern, $content, $matches) !== 1) {
        WP_CLI::error("Spain module $number missing or duplicated; Page not updated.");
    }
    return $matches[0][0];
}

$old_hashes = [
    2 => '3ac6d6a188c4e755a75efffd6ddc6a74f43c577f3580426685433a26cb9f6577',
    3 => '1e24a2f8f0d8f73c08bfbdf9bc1cfb426ff5c9eebb47630ccee78736121155bf',
    4 => 'badec62c35c2cfaf0ddf9cde7d51a453d75152f977e59def6f52a7c44fd6f420',
];
$content = $page->post_content;
foreach ($old_hashes as $number => $old_hash) {
    $existing = tio2_spain_module($content, $number);
    $replacement = tio2_spain_module($seed['content'], $number);
    $current_hash = hash('sha256', $existing);
    if ($current_hash === hash('sha256', $replacement)) continue;
    if ($current_hash !== $old_hash) {
        WP_CLI::error("Spain module $number was edited; Page not updated.");
    }
    $content = str_replace($existing, $replacement, $content);
}

if ($page->post_content === $content) {
    WP_CLI::success('Spain Page already current.');
    return;
}
$result = wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash($content)], true);
if (is_wp_error($result)) WP_CLI::error($result->get_error_message());
clean_post_cache($page->ID);
if (get_post_field('post_content', $page->ID) !== $content) {
    WP_CLI::error('Updated Spain content did not persist.');
}
WP_CLI::success('Spain modules updated; other editor content preserved.');
