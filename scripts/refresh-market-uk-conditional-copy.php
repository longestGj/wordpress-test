<?php
/** One-time correction: omit the UK trade paragraph whose same-day condition is unmet. */
if (!in_array(wp_parse_url(home_url(), PHP_URL_HOST), ['localhost','127.0.0.1','[::1]'], true)) {
    WP_CLI::error('Local site only.');
}
$page = get_page_by_path('markets/united-kingdom', OBJECT, 'page');
if (!$page || !tio2_owns_page($page->ID, '_tio2_market_id', 'MARKET-UK-001')) {
    WP_CLI::error('United Kingdom ownership mismatch.');
}
$old = '5f85a839a14a8401f6353ce7aa490e41b71a35830eed703a759b0273196865ae';
$current = hash('sha256', $page->post_content);
if ($current !== $old) WP_CLI::error('Editor copy differs from imported seed; no change made.');
$seed = json_decode(file_get_contents('/workspace/data/markets/MARKET-UK-001.json'), true, 512, JSON_THROW_ON_ERROR);
if (($seed['identity'] ?? '') !== 'MARKET-UK-001'
    || str_contains($seed['content'] ?? '', 'Trade context checked 5 September 2026')) {
    WP_CLI::error('Corrected seed missing or still contains the conditional paragraph.');
}
$result = wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($seed['content'])], true);
if (is_wp_error($result)) WP_CLI::error($result->get_error_message());
clean_post_cache($page->ID);
if (get_post_field('post_content',$page->ID) !== $seed['content']) WP_CLI::error('Corrected content did not persist.');
WP_CLI::success('UK conditional trade paragraph omitted; approved evergreen import guidance retained.');
