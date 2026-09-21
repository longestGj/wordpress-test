<?php
/** Serial resource-only mutation test. Never run beside another database writer. */
wp_set_current_user(get_users(['role'=>'administrator','number'=>1])[0]->ID);
$post = get_page_by_path('resources/chloride-vs-sulfate-titanium-dioxide');
if (!$post || !tio2_is_resource_page($post->ID)) WP_CLI::error('Owned RES-PROC is required.');
$id = $post->ID;
$original = ['content'=>$post->post_content, 'source'=>get_post_meta($id,'_tio2_source',true), 'identity'=>get_post_meta($id,'_tio2_resource_id',true), 'seo'=>get_post_meta($id,'_tio2_seo_title',true)];
$check = function($condition,$message) { if (!$condition) throw new RuntimeException($message); };
$run = function() { return WP_CLI::runcommand('eval-file /workspace/scripts/import-resources.php', ['return'=>'all','exit_error'=>false,'launch'=>true]); };
try {
    $result = wp_update_post(['ID'=>$id,'post_content'=>wp_slash($original['content']."\n<!-- wp:paragraph --><p>Resource preservation check.</p><!-- /wp:paragraph -->")],true);
    $check(!is_wp_error($result), 'Editing failed');
    update_post_meta($id,'_tio2_seo_title','Resource edited SEO');
    $check(str_contains(get_post_field('post_content',$id),'Resource preservation check.'),'Edit was not saved');
    $result = $run();
    $check($result->return_code === 0 && substr_count($result->stdout,'Preserved ') === 8, 'Repeated import did not preserve eight pages');
    clean_post_cache($id);
    $check(str_contains(get_post_field('post_content',$id),'Resource preservation check.'),'Repeated import overwrote body');
    $check(get_post_meta($id,'_tio2_seo_title',true)==='Resource edited SEO','Repeated import overwrote SEO');
    foreach (['_tio2_source','_tio2_resource_id'] as $field) {
        delete_post_meta($id,$field);
        $hub = wp_remote_get('http://wordpress/resources/', ['headers'=>['Host'=>'localhost:8080']]);
        $check(!is_wp_error($hub) && wp_remote_retrieve_response_code($hub)===200, 'Local container HTTP check failed');
        $check(!str_contains(wp_remote_retrieve_body($hub), 'href="'.get_permalink($id).'"'), 'Hub linked a resource without ownership evidence');
        $result = $run();
        $check($result->return_code !== 0 && str_contains($result->stderr,'ownership collision'),'Foreign page was adopted');
        update_post_meta($id,$field,wp_slash($field==='_tio2_source'?$original['source']:$original['identity']));
    }
} finally {
    wp_update_post(['ID'=>$id,'post_content'=>wp_slash($original['content'])]);
    update_post_meta($id,'_tio2_seo_title',wp_slash($original['seo']));
    update_post_meta($id,'_tio2_source',wp_slash($original['source']));
    update_post_meta($id,'_tio2_resource_id',$original['identity']);
}
$check(get_post_field('post_content',$id)===$original['content'],'Original body not restored');
WP_CLI::success('Eight resources preserved; edited body/SEO survived; missing ownership rejected; original restored.');
