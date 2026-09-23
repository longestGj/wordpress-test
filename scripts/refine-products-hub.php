<?php
/** Local-only, exact Products content refinement; no wholesale seed replacement. */
require '/workspace/tests/local-only.php';
$id=(int)get_option('tio2_product_hub');$post=get_post($id);
if (!$post || !tio2_owns_page($id,'_tio2_hub_key','products')) WP_CLI::error('Owned Products hub is required.');
$patch=json_decode(file_get_contents('/workspace/data/products-hub-refinement-patch.json'),true,512,JSON_THROW_ON_ERROR);
$content=$post->post_content;
foreach ($patch as $entry) {
    if ($entry['new']!=='' && str_contains($content,$entry['new'])) continue;
    $count=substr_count($content,$entry['old']);
    if ($count===0 && $entry['new']==='') continue;
    if ($count!==1) WP_CLI::error('Products passage was edited; review manually. No content changed.');
    $content=str_replace($entry['old'],$entry['new'],$content);
}
if ($content!==$post->post_content) {
    $result=wp_update_post(['ID'=>$id,'post_content'=>wp_slash($content)],true);
    if (is_wp_error($result) || get_post_field('post_content',$id,'raw')!==$content) {
        wp_update_post(['ID'=>$id,'post_content'=>wp_slash($post->post_content)]);
        WP_CLI::error('Products content update failed; original content restored.');
    }
}
// Runtime fallback also covers legacy installations without modifying editor text.
WP_CLI::success($content===$post->post_content?'Products copy already current; no changes.':'Products copy refined; unrelated editor content, SEO and discovery preserved.');
