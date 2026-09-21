<?php
defined('ABSPATH') || exit;

// Taxonomy remains the runtime authority. Snapshots live on revision posts only.
function tio2_product_term_snapshot($id) {
    $snapshot=[];
    foreach(['product_application','product_process'] as $taxonomy){
        $ids=wp_get_object_terms($id,$taxonomy,['fields'=>'ids']);
        if(is_wp_error($ids))return $ids;
        $ids=array_map('intval',$ids);sort($ids);$snapshot[$taxonomy]=$ids;
    }
    return $snapshot;
}
add_action('_wp_put_post_revision',function($revision_id){
    $revision=get_post($revision_id);
    if(!$revision || get_post_type($revision->post_parent)!=='product')return;
    $snapshot=tio2_product_term_snapshot($revision->post_parent);
    // update_post_meta redirects revision IDs to their parent; use metadata API.
    if(!is_wp_error($snapshot))update_metadata('post',$revision_id,'_tio2_term_snapshot',$snapshot);
});
add_filter('wp_save_post_revision_post_has_changed',function($changed,$latest,$post){
    if($changed || $post->post_type!=='product')return $changed;
    $snapshot=tio2_product_term_snapshot($post->ID);
    return !is_wp_error($snapshot) && $snapshot!==get_post_meta($latest->ID,'_tio2_term_snapshot',true);
},10,3);
add_action('wp_restore_post_revision',function($post_id,$revision_id){
    if(get_post_type($post_id)!=='product')return;
    $snapshot=get_post_meta($revision_id,'_tio2_term_snapshot',true);
    $notice=function($message){set_transient('tio2_revision_notice_'.get_current_user_id(),$message,120);};
    if(!is_array($snapshot)){
        $notice('This older revision has no product relationship snapshot. Content was restored; current taxonomy relationships were retained. Review relationships before publishing.');return;
    }
    // Validate every term before any relationship write. Deleted terms are never recreated.
    foreach(['product_application','product_process'] as $taxonomy){
        if(!isset($snapshot[$taxonomy]) || !is_array($snapshot[$taxonomy])){
            $notice('The relationship snapshot is incomplete. Current taxonomy relationships were retained.');return;
        }
        foreach($snapshot[$taxonomy] as $term_id){
            if(!is_int($term_id) || !term_exists($term_id,$taxonomy)){
                $notice('A historical relationship term no longer exists. Content was restored; current taxonomy relationships were retained. Review relationships before publishing.');return;
            }
        }
    }
    $before=tio2_product_term_snapshot($post_id);
    if(is_wp_error($before)){$notice('Could not read current relationships; no relationships were restored.');return;}
    foreach($snapshot as $taxonomy=>$ids){
        if(!in_array($taxonomy,['product_application','product_process'],true))continue;
        $result=wp_set_object_terms($post_id,$ids,$taxonomy);
        if(is_wp_error($result)){
            foreach($before as $tax=>$old)wp_set_object_terms($post_id,$old,$tax);
            $notice('Relationship restoration failed. Review the current relationships before publishing.');return;
        }
    }
},20,2);
add_action('admin_notices',function(){
    $key='tio2_revision_notice_'.get_current_user_id();
    if($message=get_transient($key)){echo '<div class="notice notice-warning"><p>'.esc_html($message).'</p></div>';delete_transient($key);}
});
