<?php
/** Exact, editor-preserving R-706 guide refinement. Default mode is read-only. */
$mode = $args[0] ?? 'check';
if (!in_array($mode, ['check', 'apply'], true)) WP_CLI::error('Use check or apply.');
$page = get_page_by_path('resources/ti-pure-r-706-alternative', OBJECT, 'page');
if (!$page || $page->post_status !== 'publish' || $page->post_password !== ''
    || !tio2_owns_page($page->ID, '_tio2_resource_id', 'RES-R706')) {
    WP_CLI::error('Owned published R-706 guide is required.');
}
$seed = json_decode(file_get_contents('/workspace/data/resources/ti-pure-r-706-alternative.json'), true, 512, JSON_THROW_ON_ERROR);
if (get_post_meta($page->ID, '_tio2_seo_title', true) !== $seed['seo_title']
    || get_post_meta($page->ID, '_tio2_seo_description', true) !== $seed['seo_description']) {
    WP_CLI::error('Frozen R-706 SEO fields differ; no content changed.');
}
$content = $page->post_content;
if (substr_count($content, '<h1 class="wp-block-heading">'.esc_html($seed['h1']).'</h1>') !== 1) {
    WP_CLI::error('Frozen R-706 H1 differs; no content changed.');
}
foreach ([
    'Turn your current R-706 use into a clear evaluation brief',
    '93 wt% minimum',
    'It labels the remaining values in its properties table as typical unless otherwise specified.',
    'https://www.tipure.com/en/products/coatings/r-706',
    'https://www.tipure.com/en/-/media/files/tipure/legacy/ti-pure-r-706-tds.pdf',
    '<strong>Current reference:</strong>',
    '<strong>Coating system:</strong>',
    '<strong>End use:</strong>',
    '<strong>Acceptance plan:</strong>',
    'This brief gives a selected Grade a defined test context. It does not identify a product for you or predict the result.',
    'Plan Tests Around the R-706 Evaluation Dimensions',
    'Understand the Scope of the TS-6706 Announcement',
    "The stated TS-6706/R-706 relationship is limited to these two Chemours grades",
    'https://investors.chemours.com/news-releases/news-release-details/chemours-launches-ti-puretm-ts-6706-tmptme-free-version-flagship',
    'Ti-Pure and R-706 identify the Chemours reference product discussed in the cited sources. This independent guide is not affiliated with or endorsed by Chemours.',
] as $frozen) {
    if (!str_contains($content, $frozen)) WP_CLI::error('Frozen R-706 evidence or independence boundary differs; no content changed.');
}
$patch = json_decode(file_get_contents('/workspace/data/r706-refinement-patch.json'), true, 512, JSON_THROW_ON_ERROR);
$states = [];
foreach ($patch as $entry) {
    $old_count = substr_count($content, $entry['old']);
    $new_count = substr_count($content, $entry['new']);
    $old_within_new = str_contains($entry['new'], $entry['old']);
    if ($new_count === 1 && $old_count === ($old_within_new ? 1 : 0)) $states[] = 'new';
    elseif ($new_count === 0 && $old_count === 1) $states[] = 'old';
    else WP_CLI::error('R-706 '.$entry['id'].' passage was edited or duplicated; no content changed.');
}
if (count(array_unique($states)) !== 1) WP_CLI::error('R-706 passages are in a mixed state; no content changed.');
if ($mode === 'check' || $states[0] === 'new') {
    WP_CLI::success($states[0] === 'old' ? 'Six exact R-706 refinements pending.' : 'R-706 refinement already current.');
    return;
}
foreach ($patch as $entry) $content = str_replace($entry['old'], $entry['new'], $content);
$saved = wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash($content)], true);
if (is_wp_error($saved) || get_post_field('post_content', $page->ID, 'raw') !== $content) {
    $restored = wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash($page->post_content)], true);
    WP_CLI::error(!is_wp_error($restored) && get_post_field('post_content', $page->ID, 'raw') === $page->post_content
        ? 'R-706 update failed; original content restored.' : 'R-706 update failed; automatic rollback incomplete.');
}
WP_CLI::success('R-706 coatings and request paths refined; unrelated editor content and SEO preserved.');
