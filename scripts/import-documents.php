<?php
/** Local preview import: no adoption by slug, no overwrite of editor content. */
if (!in_array(wp_parse_url(home_url(), PHP_URL_HOST), ['localhost','127.0.0.1','[::1]'], true)) WP_CLI::error('Document import is local-only; production publication is not authorized.');
$root = get_page_by_path('documents', OBJECT, 'page');
if (!$root || !tio2_owns_page($root->ID, '_tio2_hub_key', 'documents') || get_post_status($root) !== 'publish') WP_CLI::error('Owned published Documents parent required. Run the explicit ownership migration for legacy installs.');
$seeds = []; $identities = [];
foreach (glob('/workspace/data/documents/*.json') as $file) {
    $s = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    $identity = $s['identity'] ?? '';
    if (!isset(tio2_document_ids()[$identity]) || ($s['slug'] ?? '') !== tio2_document_ids()[$identity]
        || ($s['path'] ?? '') !== '/documents/'.$s['slug'].'/' || empty($s['content']) || isset($identities[$identity])) WP_CLI::error('Invalid document seed. No changes made.');
    $identities[$identity] = true;
    $existing = get_page_by_path('documents/'.$s['slug'], OBJECT, 'page');
    $matches = get_posts(['post_type'=>'page','post_status'=>'any','numberposts'=>-1,'meta_key'=>'_tio2_document_id','meta_value'=>$identity]);
    if ($existing && !tio2_owns_page($existing->ID, '_tio2_document_id', $identity)) WP_CLI::error('Document ownership collision. No changes made.');
    foreach ($matches as $match) if (!$existing || $match->ID !== $existing->ID) WP_CLI::error('Document identity already exists at another path. No changes made.');
    $seeds[] = [$s, $existing];
}
if (count($seeds) !== 3) WP_CLI::error('Exactly three document seeds required.');
$created = [];
try {
    foreach ($seeds as [$s, $existing]) {
        if ($existing) { WP_CLI::log('Preserved '.$s['identity']); continue; }
        $id = wp_insert_post(['post_type'=>'page','post_parent'=>$root->ID,'post_status'=>'publish','post_title'=>$s['title'],
            'post_name'=>$s['slug'],'post_content'=>wp_slash($s['content']),'comment_status'=>'closed','ping_status'=>'closed'], true);
        if (is_wp_error($id)) throw new RuntimeException($id->get_error_message());
        $created[] = $id;
        foreach (['_tio2_owner'=>'tio2-wordpress','_tio2_document_id'=>$s['identity'],'_tio2_source'=>$s['source'],
            '_tio2_seo_title'=>$s['seo_title'],'_tio2_seo_description'=>$s['seo_description']] as $key=>$value) {
            if (!update_post_meta($id, $key, wp_slash($value))) throw new RuntimeException('Document metadata write failed.');
        }
        if (get_permalink($id) !== home_url($s['path'])) throw new RuntimeException('Unexpected document route.');
        WP_CLI::log('Imported '.$s['identity']);
    }
} catch (Throwable $error) {
    foreach (array_reverse($created) as $id) wp_delete_post($id, true);
    WP_CLI::error($error->getMessage());
}
WP_CLI::success('Three document guides ready; existing content preserved.');
