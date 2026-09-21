<?php
/** One-time native Page creation. All collisions fail before any writes. */
$root = get_page_by_path('resources', OBJECT, 'page');
if (!$root || get_post_status($root) !== 'publish' || !tio2_owns_page($root->ID,'_tio2_hub_key','resources')) {
    WP_CLI::error('Resources parent ownership is missing or mismatched; no changes made.');
}
$seeds = [];
$paths = [];
foreach (glob('/workspace/data/resources/*.json') as $file) {
    $s = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    if (!preg_match('/^RES-(ORIGIN|PROC|CHEMOURS|R706|TRADE-(EU|UK|IN|BR))$/D', $s['identity'] ?? '')
        || !preg_match('/^[a-z0-9-]+$/D', $s['slug'] ?? '')
        || ($s['path'] ?? '') !== '/resources/'.$s['slug'].'/' || empty($s['content']) || empty($s['source'])
        || isset($paths[$s['path']])) WP_CLI::error('Invalid or duplicate resource seed; no changes made.');
    $paths[$s['path']] = true;
    $existing = get_page_by_path('resources/'.$s['slug'], OBJECT, 'page');
    if ($existing && !tio2_owns_page($existing->ID,'_tio2_resource_id',$s['identity'])) {
        WP_CLI::error('Resource ownership collision at '.$s['path'].'; no changes made.');
    }
    $seeds[] = [$s, $existing];
}
if (count($seeds) !== 8) WP_CLI::error('Exactly eight resource seeds required; no changes made.');
$created = [];
try {
    foreach ($seeds as [$s, $existing]) {
        if ($existing) { WP_CLI::log('Preserved '.$s['identity']); continue; }
        $id = wp_insert_post([
            'post_type'=>'page', 'post_parent'=>$root->ID, 'post_status'=>'publish',
            'post_title'=>$s['title'], 'post_name'=>$s['slug'], 'post_content'=>wp_slash($s['content']),
            'comment_status'=>'closed', 'ping_status'=>'closed',
        ], true);
        if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
        $created[] = $id;
        foreach (['_tio2_owner'=>'tio2-wordpress','_tio2_resource_id'=>$s['identity'], '_tio2_resource_class'=>$s['main_class'],
            '_tio2_source'=>$s['source'], '_tio2_seo_title'=>$s['seo_title'], '_tio2_seo_description'=>$s['seo_description']] as $key=>$value) {
            if (!update_post_meta($id, $key, wp_slash($value))) throw new RuntimeException('Failed to save resource metadata: '.$key);
        }
        if (get_permalink($id) !== home_url($s['path'])) throw new RuntimeException('Unexpected resource route; rolling back created resources.');
        WP_CLI::log('Imported '.$s['identity']);
    }
} catch (Throwable $error) {
    foreach (array_reverse($created) as $id) wp_delete_post($id, true);
    WP_CLI::error($error->getMessage());
}
WP_CLI::success('Resource import complete; existing content and site settings preserved.');
