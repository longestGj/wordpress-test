<?php
/** Local-only fixtures: exact patches must preserve editor content and refuse altered passages. */
require __DIR__.'/local-only.php';
$patches=json_decode(file_get_contents('/workspace/data/resource-guide-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$pages=[
    'proc'=>['RES-PROC','chloride-vs-sulfate-titanium-dioxide'],
    'chemours'=>['RES-CHEMOURS','chemours-titanium-dioxide-alternatives'],
];
foreach ($pages as $key=>[$identity,$slug]) {
    $page=get_page_by_path('resources/'.$slug,OBJECT,'page');
    if (!$page || !tio2_owns_page($page->ID,'_tio2_resource_id',$identity)) WP_CLI::error('Owned '.$identity.' page required.');
    $original=$page->post_content;
    $seed=json_decode(file_get_contents('/workspace/data/resources/'.$slug.'.json'),true,512,JSON_THROW_ON_ERROR);
    $old=$seed['content'];
    foreach (array_reverse($patches[$identity]) as $entry) $old=str_replace($entry['new'],$entry['old'],$old);
    $fixture=$old.'<!-- editor-fixture-'.$key.' -->';
    $run=static fn($mode)=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-resource-guides.php '.$key.' '.$mode,
        ['return'=>'all','exit_error'=>false,'launch'=>true]);
    $failure=null;
    try {
        wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($fixture)]);
        $check=$run('check');clean_post_cache($page->ID);
        if ($check->return_code!==0) throw new RuntimeException('Read-only check failed for '.$identity.': '.$check->stderr);
        if (get_post_field('post_content',$page->ID,'raw')!==$fixture) throw new RuntimeException('Read-only check changed '.$identity.'.');
        $applied=$run('apply');clean_post_cache($page->ID);
        $updated=get_post_field('post_content',$page->ID,'raw');
        if ($applied->return_code!==0 || !str_contains($updated,'editor-fixture-'.$key)
            || !str_contains($updated,$patches[$identity][0]['new'])) throw new RuntimeException('Patch failed or lost editor content on '.$identity.'.');
        $repeat=$run('apply');clean_post_cache($page->ID);
        if ($repeat->return_code!==0 || get_post_field('post_content',$page->ID,'raw')!==$updated) throw new RuntimeException('Repeat patch changed '.$identity.'.');
        $edited=str_replace($patches[$identity][0]['old'],'editor-selected-passage',$fixture);
        wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($edited)]);
        $refused=$run('apply');clean_post_cache($page->ID);
        if ($refused->return_code===0 || get_post_field('post_content',$page->ID,'raw')!==$edited) throw new RuntimeException('Editor passage was overwritten on '.$identity.'.');
    } catch (Throwable $error) {$failure=$error->getMessage();}
    finally {wp_update_post(['ID'=>$page->ID,'post_content'=>wp_slash($original)]);}
    if ($failure) WP_CLI::error($failure);
}
WP_CLI::success('Both guide patches are read-only in check mode, exact, idempotent and editor-preserving; fixtures restored.');
