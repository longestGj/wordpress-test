<?php
/** Local fixture: exact R-706 patch preserves unrelated editor content. */
require __DIR__.'/local-only.php';
$page = get_page_by_path('resources/ti-pure-r-706-alternative', OBJECT, 'page');
if (!$page || !tio2_owns_page($page->ID, '_tio2_resource_id', 'RES-R706')) WP_CLI::error('Owned R-706 guide required.');
$original = $page->post_content;
$seed = json_decode(file_get_contents('/workspace/data/resources/ti-pure-r-706-alternative.json'), true, 512, JSON_THROW_ON_ERROR);
$patch = json_decode(file_get_contents('/workspace/data/r706-refinement-patch.json'), true, 512, JSON_THROW_ON_ERROR);
$old = $seed['content'];
foreach (array_reverse($patch) as $entry) $old = str_replace($entry['new'], $entry['old'], $old);
$fixture = $old.'<!-- r706-editor-fixture -->';
$run = static fn($mode) => WP_CLI::runcommand('eval-file /workspace/scripts/refine-r706-guide.php '.$mode,
    ['return' => 'all', 'exit_error' => false, 'launch' => true]);
$failure = null;
try {
    wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash($fixture)]);
    $checked = $run('check'); clean_post_cache($page->ID);
    if ($checked->return_code !== 0 || get_post_field('post_content', $page->ID, 'raw') !== $fixture) throw new RuntimeException('Check failed or wrote content.');
    $applied = $run('apply'); clean_post_cache($page->ID);
    $updated = get_post_field('post_content', $page->ID, 'raw');
    if ($applied->return_code !== 0 || !str_contains($updated, 'r706-editor-fixture')
        || !str_contains($updated, 'Review the Coatings Evaluation Framework')) throw new RuntimeException('Patch lost editor content or target route.');
    $repeat = $run('apply'); clean_post_cache($page->ID);
    if ($repeat->return_code !== 0 || get_post_field('post_content', $page->ID, 'raw') !== $updated) throw new RuntimeException('Repeat patch changed content.');
    $edited = str_replace($patch[2]['old'], 'Editor changed this document request passage.', $fixture);
    wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash($edited)]);
    $refused = $run('apply'); clean_post_cache($page->ID);
    if ($refused->return_code === 0 || get_post_field('post_content', $page->ID, 'raw') !== $edited) throw new RuntimeException('Edited document passage was overwritten.');
} catch (Throwable $error) {$failure = $error->getMessage();}
finally {wp_update_post(['ID' => $page->ID, 'post_content' => wp_slash($original)]);}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('R-706 patch check, exact edit, idempotence and editor protection verified; fixture restored.');
