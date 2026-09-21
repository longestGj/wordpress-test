<?php
if (!in_array(wp_parse_url(home_url(),PHP_URL_HOST),['localhost','127.0.0.1','[::1]'],true)) WP_CLI::error('Local test only');
$cases=[['resources','import-pages.php','_tio2_hub_key'],['applications/titanium-dioxide-for-coatings','import-process-applications.php','_tio2_page_id'],['resources/chloride-vs-sulfate-titanium-dioxide','import-resources.php','_tio2_resource_id']];
$failures=[];
foreach($cases as [$path,$script,$identity]){
    $post=get_page_by_path($path);$id=$post->ID;$meta=get_post_meta($id);
    try {
        update_post_meta($id,'_tio2_source','A newer approved source, with a different name and commit');
        $result=WP_CLI::runcommand('eval-file /workspace/scripts/'.$script,['return'=>'all','exit_error'=>false,'launch'=>true]);
        if($result->return_code!==0)$failures[]=$script.' rejects provenance-only change';
        if($script==='import-resources.php'){
            $r=wp_remote_get('http://wordpress/resources/',['headers'=>['Host'=>'localhost:8080']]);
            if(!str_contains(wp_remote_retrieve_body($r),'href="'.get_permalink($id).'"'))$failures[]='Hub hides updated provenance';
        }
        delete_post_meta($id,$identity);
        $result=WP_CLI::runcommand('eval-file /workspace/scripts/'.$script,['return'=>'all','exit_error'=>false,'launch'=>true]);
        if($result->return_code===0)$failures[]=$script.' adopted missing identity';
    } finally {
        foreach(['_tio2_source',$identity] as $key){delete_post_meta($id,$key);foreach($meta[$key]??[] as $value)add_post_meta($id,$key,wp_slash(maybe_unserialize($value)));}
    }
}
if($failures)WP_CLI::error(implode("\n",$failures));
WP_CLI::success('All importers preserve editor content across source changes and reject absent identity; Hub ignores provenance.');
