<?php
/** Update only the request-email disclosure on owned local privacy Pages. */
require '/workspace/tests/local-only.php';
$patch=json_decode(file_get_contents('/workspace/data/request-email-policy-patch.json'),true,512,JSON_THROW_ON_ERROR);
$paths=['LEGAL-PRIV-EN'=>'privacy-policy','LEGAL-PRIV-MS'=>'ms/privacy-policy'];
$changes=[];
foreach ($paths as $identity=>$path) {
    $post=get_page_by_path($path,OBJECT,'page');
    if (!$post || !tio2_owns_page($post->ID,'_tio2_page_id',$identity)) WP_CLI::error('Privacy Page ownership mismatch: '.$identity);
    $content=$post->post_content;
    foreach ($patch[$identity] as $entry) {
        $old=$entry['old'];$new=$entry['new'];
        if (str_contains($content,$new)) continue;
        if (substr_count($content,$old)!==1) WP_CLI::error('Privacy paragraph was edited; review manually: '.$identity);
        $content=str_replace($old,$new,$content);
    }
    if ($content===$post->post_content) continue;
    $changes[]=[$post,$content];
}
$done=[];
foreach ($changes as [$post,$content]) {
    $saved=wp_update_post(['ID'=>$post->ID,'post_content'=>wp_slash($content)],true);
    if (is_wp_error($saved)) {
        foreach ($done as $original) wp_update_post(['ID'=>$original->ID,'post_content'=>wp_slash($original->post_content)]);
        WP_CLI::error('Privacy update failed: '.$saved->get_error_message());
    }
    $done[]=$post;
}
WP_CLI::success('Updated '.count($done).' owned privacy Pages; other editor text preserved.');
