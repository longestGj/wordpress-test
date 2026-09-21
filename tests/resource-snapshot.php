<?php
// Read-only snapshot of everything outside Resource ownership.
$state = ['posts'=>[], 'options'=>[]];
foreach (get_posts(['post_type'=>['page','product'], 'post_status'=>['publish','private','draft'], 'numberposts'=>-1, 'orderby'=>'ID', 'order'=>'ASC']) as $post) {
    if (get_post_meta($post->ID, '_tio2_resource_id', true)) continue;
    $state['posts'][$post->ID] = [
        'title'=>$post->post_title,'slug'=>$post->post_name,'parent'=>$post->post_parent,
        'content'=>$post->post_content,'excerpt'=>$post->post_excerpt,'status'=>$post->post_status,
        'meta'=>get_post_meta($post->ID),
        'applications'=>wp_get_object_terms($post->ID, 'product_application', ['fields'=>'ids']),
        'processes'=>wp_get_object_terms($post->ID, 'product_process', ['fields'=>'ids']),
    ];
}
foreach (['tio2_product_hub','tio2_product_discovery','tio2_targets','page_on_front','show_on_front','blog_public','permalink_structure'] as $name) $state['options'][$name] = get_option($name);
echo wp_json_encode($state, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
