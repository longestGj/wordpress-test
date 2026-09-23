<?php
/** Run serially after importing the three guides; local-only and fixture-restoring. */
require __DIR__.'/local-only.php';
$post=get_page_by_path('documents/reach',OBJECT,'page');
if (!$post || tio2_document_id($post->ID)!=='DOC-REACH') WP_CLI::error('Import the owned document guides first.');
$id=$post->ID;
$original=['content'=>$post->post_content,'seo'=>get_post_meta($id,'_tio2_seo_title',true),'owner'=>get_post_meta($id,'_tio2_owner',true)];
$revisions=array_keys(wp_get_post_revisions($id));
$check=function($ok,$message){if(!$ok)throw new RuntimeException($message);};
$run=function(){return WP_CLI::runcommand('eval-file /workspace/scripts/import-documents.php',['return'=>'all','exit_error'=>false,'launch'=>true]);};
try {
    wp_update_post(['ID'=>$id,'post_content'=>wp_slash($original['content'].'<!-- document editor preservation fixture -->')]);
    update_post_meta($id,'_tio2_seo_title','Document editor SEO fixture');
    $result=$run();$check($result->return_code===0 && substr_count($result->stdout,'Preserved ')===3,'Repeat import failed');
    clean_post_cache($id);
    $check(str_contains(get_post_field('post_content',$id),'document editor preservation fixture'),'Editor body overwritten');
    $check(get_post_meta($id,'_tio2_seo_title',true)==='Document editor SEO fixture','Editor SEO overwritten');
    delete_post_meta($id,'_tio2_owner');
    $result=$run();$check($result->return_code!==0 && str_contains($result->stderr,'ownership collision'),'Ownerless page adopted');
} finally {
    wp_update_post(['ID'=>$id,'post_content'=>wp_slash($original['content'])]);
    update_post_meta($id,'_tio2_seo_title',wp_slash($original['seo']));
    update_post_meta($id,'_tio2_owner',$original['owner']);
    foreach(array_diff(array_keys(wp_get_post_revisions($id)),$revisions) as $revision)wp_delete_post_revision($revision);
}
$check(get_post_field('post_content',$id)===$original['content'],'Original content not restored');
WP_CLI::success('Document reimport preserves body and SEO, rejects ownerless pages; fixture restored.');
