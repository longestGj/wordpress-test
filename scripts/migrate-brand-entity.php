<?php
/** Guarded local migration for active brand copy and SEO. Historical revisions remain untouched. */
require '/workspace/tests/local-only.php';
global $wpdb;
$manifest=json_decode(file_get_contents('/workspace/data/brand-migration-patch.json'),true,512,JSON_THROW_ON_ERROR);
if (!is_array($manifest) || count($manifest)!==51) WP_CLI::error('Expected 51 reviewed brand migration records.');

$records=[];
foreach ($manifest as $item) {
    $path=$item['path'];
    if (($item['post_type']??'')==='product') {
        $post=get_page_by_path($item['slug'],OBJECT,'product');
        if (!$post || $post->post_type!=='product' || $post->post_status!=='publish' || $post->post_name!==$item['slug'] || $post->post_title!==$item['title']) WP_CLI::error('Product identity mismatch at '.$path.'. No changes made.');
        $data=get_post_meta($post->ID,'_tio2_product',true);
        if (!is_array($data) || !isset($item['changes']['seo_title'])) WP_CLI::error('Product data missing at '.$path.'. No changes made.');
        $change=$item['changes']['seo_title'];
        $value=$data['seo_title']??null;
        if ($value!==$change['old'] && $value!==$change['new']) WP_CLI::error('Product SEO was edited at '.$path.'. No changes made.');
        $records[]=['item'=>$item,'post'=>$post,'data'=>$data,'state'=>$value===$change['old']?'old':'new'];
        continue;
    }
    $matches=get_posts(['post_type'=>'page','post_status'=>['publish','private'],'meta_key'=>$item['owner_key'],'meta_value'=>$item['owner'],'posts_per_page'=>-1]);
    if (count($matches)!==1) WP_CLI::error('Page identity is missing or duplicated at '.$path.'. No changes made.');
    $post=$matches[0];
    $expected_status=$item['owner']==='products' && $item['owner_key']==='_tio2_hub_key'?'private':'publish';
    // Process pages use root-level WordPress slugs with public /products/ rewrites.
    $expected_uri=in_array($item['owner'],['PRODUCT-PROC-CL','PRODUCT-PROC-SU'],true)
        ? basename(trim($path,'/')) : trim($path,'/');
    if ($post->post_status!==$expected_status || !tio2_owns_page($post->ID,$item['owner_key'],$item['owner'])
        || ($path==='/'?(int)$post->ID!==(int)get_option('page_on_front'):get_page_uri($post)!==$expected_uri)) WP_CLI::error('Page ownership or route mismatch at '.$path.'. No changes made.');
    $states=[];
    $changes=$item['changes'];
    foreach (['seo_title','seo_description'] as $field) {
        if (!isset($changes[$field])) continue;
        $current=get_post_meta($post->ID,'_tio2_'.$field,true);
        $pair=$changes[$field];
        if ($current!==$pair['old'] && $current!==$pair['new']) WP_CLI::error('SEO field was edited at '.$path.'. No changes made.');
        $states[]=$current===$pair['old']?'old':'new';
    }
    foreach (($changes['content']??[]) as $patch) {
        $old_count=substr_count($post->post_content,$patch['old']);
        $new_count=substr_count($post->post_content,$patch['new']);
        $old_inside_new=str_contains($patch['new'],$patch['old']);
        $expected=(int)$patch['count'];
        if ($old_count===$expected && $new_count===0) $states[]='old';
        elseif ($new_count===$expected && $old_count===($old_inside_new?$expected:0)) $states[]='new';
        else WP_CLI::error('Brand passage was edited or duplicated at '.$path.'. No changes made.');
    }
    if (!$states || count(array_unique($states))!==1) WP_CLI::error('Mixed brand state at '.$path.'. No changes made.');
    $records[]=['item'=>$item,'post'=>$post,'state'=>$states[0]];
}

$site_title=get_option('blogname');
$old_site_title='TiO2 Malaysia - Local Development';
$new_site_title='TiO2Products - Local Development';
if ($site_title!==$old_site_title && $site_title!==$new_site_title) WP_CLI::error('Site title has a custom value. No changes made.');
$site_state=$site_title===$old_site_title?'old':'new';
$states=array_unique(array_merge(array_column($records,'state'),[$site_state]));
if (count($states)!==1) WP_CLI::error('Site records are in mixed brand states. No changes made.');
if ($states[0]==='new') {WP_CLI::success('Brand entity migration already current.');return;}

$wpdb->query('START TRANSACTION');
$touched=[];
try {
    foreach ($records as $record) {
        $item=$record['item'];$post=$record['post'];$changes=$item['changes'];
        if ($post->post_type==='product') {
            $data=$record['data'];$data['seo_title']=$changes['seo_title']['new'];
            if (!update_post_meta($post->ID,'_tio2_product',$data) || get_post_meta($post->ID,'_tio2_product',true)!==$data) throw new RuntimeException('Product metadata write failed at '.$item['path']);
            $touched[]=$post->ID;
            continue;
        }
        if (isset($changes['content'])) {
            $content=$post->post_content;
            foreach ($changes['content'] as $patch) $content=str_replace($patch['old'],$patch['new'],$content);
            $saved=wp_update_post(['ID'=>$post->ID,'post_content'=>wp_slash($content)],true);
            if (is_wp_error($saved) || get_post_field('post_content',$post->ID,'raw')!==$content) throw new RuntimeException('Page content write failed at '.$item['path']);
        }
        foreach (['seo_title','seo_description'] as $field) {
            if (!isset($changes[$field])) continue;
            $value=$changes[$field]['new'];
            if (!update_post_meta($post->ID,'_tio2_'.$field,$value) || get_post_meta($post->ID,'_tio2_'.$field,true)!==$value) throw new RuntimeException('Page SEO write failed at '.$item['path']);
        }
        $touched[]=$post->ID;
    }
    if (!update_option('blogname',$new_site_title) || get_option('blogname')!==$new_site_title) throw new RuntimeException('Site title update failed.');
    $wpdb->query('COMMIT');
} catch (Throwable $error) {
    $wpdb->query('ROLLBACK');
    foreach ($touched as $id) clean_post_cache($id);
    WP_CLI::error('Brand migration rolled back: '.$error->getMessage());
}
foreach ($touched as $id) clean_post_cache($id);
WP_CLI::success('Updated '.count($records).' owned active records and the local site title; editor changes outside reviewed passages were preserved.');
