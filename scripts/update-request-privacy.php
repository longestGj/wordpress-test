<?php
/** Apply only the factual request-form changes to owned local policy Pages. */
require '/workspace/tests/local-only.php';
$patch=json_decode(file_get_contents('/workspace/data/request-privacy-patch.json'),true,512,JSON_THROW_ON_ERROR);
$email_patch=json_decode(file_get_contents('/workspace/data/request-email-policy-patch.json'),true,512,JSON_THROW_ON_ERROR);
$paths=['LEGAL-PRIV-EN'=>'privacy-policy','LEGAL-PRIV-MS'=>'ms/privacy-policy','LEGAL-COOKIE-EN'=>'cookie-policy'];
$updates=[];
foreach ($paths as $identity=>$path) {
    $post=get_page_by_path($path,OBJECT,'page');
    if (!$post || !tio2_owns_page($post->ID,'_tio2_page_id',$identity)) WP_CLI::error('Policy ownership mismatch: '.$identity);
    $content=$post->post_content;
    foreach ($patch[$identity] as $entry) {
        if (str_contains($content,$entry['new'])) continue;
        $previous=$entry['new'];
        foreach ($email_patch[$identity]??[] as $email_entry) $previous=str_replace($email_entry['new'],$email_entry['old'],$previous);
        if ($previous!==$entry['new'] && str_contains($content,$previous)) continue;
        if (!str_contains($content,$entry['old'])) WP_CLI::error('Policy paragraph was edited; review manually: '.$identity);
        $content=str_replace($entry['old'],$entry['new'],$content);
    }
    $updates[]=[$post,$content];
}
$changed=[];
foreach ($updates as [$post,$content]) {
    if ($post->post_content===$content) continue;
    $result=wp_update_post(['ID'=>$post->ID,'post_content'=>wp_slash($content)],true);
    if (is_wp_error($result)) {
        foreach ($changed as $original) wp_update_post(['ID'=>$original->ID,'post_content'=>wp_slash($original->post_content)]);
        WP_CLI::error('Policy update failed: '.$result->get_error_message());
    }
    $changed[]=$post;
}
WP_CLI::success('Updated '.count($changed).' owned policy Pages; unchanged editor text preserved.');
