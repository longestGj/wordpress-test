<?php
/** Preflight all three native Pages; import is local-only and preserves editor edits. */
if (!in_array(wp_parse_url(home_url(),PHP_URL_HOST),['localhost','127.0.0.1','[::1]'],true)) WP_CLI::error('Local import only.');
$seeds=[];$seen=[];
foreach (glob('/workspace/data/requests/*.json') as $file) {
    $s=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
    $kind=$s['kind']??'';$id=tio2_request_page_ids()[$kind]??'';$path=tio2_request_paths()[$kind]??'';
    if (!$id || ($s['page_id']??'')!==$id || ($s['path']??'')!==$path || ($s['slug']??'')!==trim($path,'/') || empty($s['content']) || isset($seen[$kind])) WP_CLI::error('Invalid request seed; no changes made.');
    $seen[$kind]=true;$existing=get_page_by_path($s['slug'],OBJECT,'page');
    if ($existing && !tio2_owns_page($existing->ID,'_tio2_page_id',$id)) WP_CLI::error('Request page ownership collision; no changes made.');
    $matches=get_posts(['post_type'=>'page','post_status'=>'any','numberposts'=>-1,'meta_key'=>'_tio2_page_id','meta_value'=>$id]);
    foreach ($matches as $match) if (!$existing || $match->ID!==$existing->ID) WP_CLI::error('Request page identity collision; no changes made.');
    $seeds[]=[$s,$existing];
}
if (count($seeds)!==3) WP_CLI::error('Exactly three request Pages required.');
$created=[];
try {
    foreach ($seeds as [$s,$existing]) {
        if ($existing) {WP_CLI::log('Preserved '.$s['page_id']);continue;}
        $id=wp_insert_post(['post_type'=>'page','post_status'=>'publish','post_name'=>$s['slug'],'post_title'=>$s['title'],
            'post_content'=>wp_slash($s['content']),'comment_status'=>'closed','ping_status'=>'closed'],true);
        if (is_wp_error($id)||!$id) throw new RuntimeException(is_wp_error($id)?$id->get_error_message():'Page creation failed');
        $created[]=$id;
        foreach (['_tio2_owner'=>'tio2-wordpress','_tio2_page_id'=>$s['page_id'],'_tio2_seo_title'=>$s['seo_title'],
            '_tio2_seo_description'=>$s['seo_description']] as $key=>$value)
            if (!update_post_meta($id,$key,wp_slash($value))) throw new RuntimeException('Metadata write failed: '.$key);
        if (get_permalink($id)!==home_url($s['path'])) throw new RuntimeException('Unexpected Page URL.');
        WP_CLI::log('Imported '.$s['page_id']);
    }
} catch (Throwable $error) {
    foreach (array_reverse($created) as $id) wp_delete_post($id,true);
    WP_CLI::error($error->getMessage());
}
WP_CLI::success('Request Pages ready; existing edits preserved. Destination ready flags remain unchanged.');
