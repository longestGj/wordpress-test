<?php
/** Exact, local-only Contact mail disclosures; preserve unrelated editor content. */
require '/workspace/tests/local-only.php';
$patch=json_decode(file_get_contents('/workspace/data/contact-mail-policy-patch.json'),true,512,JSON_THROW_ON_ERROR);
$paths=['LEGAL-PRIV-EN'=>'privacy-policy','LEGAL-PRIV-MS'=>'ms/privacy-policy','LEGAL-COOKIE-EN'=>'cookie-policy'];
$changes=[];
foreach ($paths as $identity=>$path) {
    $post=get_page_by_path($path,OBJECT,'page');
    if (!$post || !tio2_owns_page($post->ID,'_tio2_page_id',$identity)) WP_CLI::error('Policy ownership mismatch: '.$identity);
    $content=$post->post_content;
    foreach ($patch[$identity] as $entry) {
        if (str_contains($content,$entry['new'])) continue;
        $matches=array_values(array_filter((array)$entry['old'],static fn($old)=>substr_count($content,$old)===1));
        if (count($matches)!==1) WP_CLI::error('Policy passage was edited; review manually: '.$identity);
        $content=str_replace($matches[0],$entry['new'],$content);
    }
    if ($content!==$post->post_content) $changes[]=[$post,$content];
}
$done=[];
foreach ($changes as [$post,$content]) {
    $result=wp_update_post(['ID'=>$post->ID,'post_content'=>wp_slash($content)],true);
    if (is_wp_error($result) || get_post_field('post_content',$post->ID,'raw')!==$content) {
        wp_update_post(['ID'=>$post->ID,'post_content'=>wp_slash($post->post_content)]);
        foreach ($done as $original) wp_update_post(['ID'=>$original->ID,'post_content'=>wp_slash($original->post_content)]);
        WP_CLI::error('Policy write failed; review revisions.');
    }
    $done[]=$post;
}
WP_CLI::success('Updated '.count($done).' owned policy Pages; other editor text preserved.');
