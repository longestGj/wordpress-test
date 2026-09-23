<?php
/** Local fixture: reject edited brand targets while preserving unrelated editor copy. */
require __DIR__.'/local-only.php';
$paper=get_page_by_path('applications/titanium-dioxide-for-paper',OBJECT,'page');
$about=get_page_by_path('about',OBJECT,'page');
$product=get_page_by_path('m-350',OBJECT,'product');
if (!$paper || !tio2_owns_page($paper->ID,'_tio2_page_id','APP-PAPER') || !$about || !tio2_owns_page($about->ID,'_tio2_hub_key','about') || !$product || $product->post_title!=='M-350') WP_CLI::error('Owned Paper/About pages and M-350 product are required.');
$old_content=$paper->post_content;
$old_title=get_post_meta($about->ID,'_tio2_seo_title',true);
$old_product=get_post_meta($product->ID,'_tio2_product',true);
$old_parent=$paper->post_parent;
$old_site_title=get_option('blogname');
$run=static fn()=>WP_CLI::runcommand('eval-file /workspace/scripts/migrate-brand-entity.php',['return'=>'all','exit_error'=>false,'launch'=>true]);
$failure=null;
try {
    if (!str_contains($old_content,'the result of a candidate Grade')) throw new RuntimeException('Migrated Paper copy is required.');
    $marked=$old_content.'<!-- preserved-editor-marker -->';
    wp_update_post(['ID'=>$paper->ID,'post_content'=>wp_slash($marked)]);
    $result=$run();clean_post_cache($paper->ID);
    if ($result->return_code!==0 || get_post_field('post_content',$paper->ID,'raw')!==$marked) throw new RuntimeException('Repeat migration removed unrelated editor copy.');

    $edited=str_replace('the result of a candidate Grade','the result of an editor-selected Grade',$marked);
    wp_update_post(['ID'=>$paper->ID,'post_content'=>wp_slash($edited)]);
    $result=$run();clean_post_cache($paper->ID);
    if ($result->return_code===0 || get_post_field('post_content',$paper->ID,'raw')!==$edited) throw new RuntimeException('Edited Paper target was overwritten or accepted.');

    wp_update_post(['ID'=>$paper->ID,'post_content'=>wp_slash($marked)]);
    update_post_meta($about->ID,'_tio2_seo_title','Editor-customized About title');
    $result=$run();clean_post_cache($about->ID);
    if ($result->return_code===0 || get_post_meta($about->ID,'_tio2_seo_title',true)!=='Editor-customized About title') throw new RuntimeException('Edited SEO title was overwritten or accepted.');

    update_post_meta($about->ID,'_tio2_seo_title',$old_title);
    $custom=$old_product;$custom['seo_title']='Editor-customized M-350 title';
    update_post_meta($product->ID,'_tio2_product',$custom);
    $result=$run();clean_post_cache($product->ID);
    if ($result->return_code===0 || tio2_product_data($product->ID)['seo_title']!=='Editor-customized M-350 title') throw new RuntimeException('Edited product SEO was overwritten or accepted.');

    update_post_meta($product->ID,'_tio2_product',$old_product);
    wp_update_post(['ID'=>$paper->ID,'post_parent'=>$about->ID]);clean_post_cache($paper->ID);
    $result=$run();
    if ($result->return_code===0) throw new RuntimeException('Moved Paper page with the same slug was accepted.');

    wp_update_post(['ID'=>$paper->ID,'post_parent'=>$old_parent]);clean_post_cache($paper->ID);
    update_option('blogname','TiO2Products - Editor Custom');
    $result=$run();
    if ($result->return_code===0 || get_option('blogname')!=='TiO2Products - Editor Custom') throw new RuntimeException('Editor-customized site title was overwritten or accepted.');
} catch (Throwable $error) {$failure=$error->getMessage();}
finally {
    wp_update_post(['ID'=>$paper->ID,'post_content'=>wp_slash($old_content)]);
    wp_update_post(['ID'=>$paper->ID,'post_parent'=>$old_parent]);
    update_post_meta($about->ID,'_tio2_seo_title',$old_title);
    update_post_meta($product->ID,'_tio2_product',$old_product);
    update_option('blogname',$old_site_title);
    clean_post_cache($paper->ID);clean_post_cache($product->ID);
}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('Brand migration rejects edited Page/Product targets and preserves unrelated editor copy; fixtures restored.');
