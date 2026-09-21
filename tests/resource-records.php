<?php
// Read-only identities and fingerprints for a reviewed, development-only refresh.
$records = [];
foreach (get_posts(['post_type'=>'page','post_status'=>['publish','private','draft'],'numberposts'=>-1,'meta_key'=>'_tio2_resource_id']) as $post) {
    $records[] = ['id'=>$post->ID,'identity'=>get_post_meta($post->ID,'_tio2_resource_id',true),
        'source'=>get_post_meta($post->ID,'_tio2_source',true),'title'=>$post->post_title,
        'hash'=>hash('sha256',$post->post_content)];
}
echo wp_json_encode($records,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
