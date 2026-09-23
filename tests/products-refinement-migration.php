<?php
require __DIR__.'/local-only.php';
$id=(int)get_option('tio2_product_hub');$post=get_post($id);$discovery=get_post_meta($id,'_tio2_discovery',true);
$run=static fn()=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-products-hub.php',['return'=>'all','exit_error'=>false,'launch'=>true]);
$failure=null;
try {
    $custom=$post->post_content.'<!-- editor-preservation-fixture -->';
    wp_update_post(['ID'=>$id,'post_content'=>wp_slash($custom)]);
    $result=$run();clean_post_cache($id);
    if ($result->return_code || get_post_field('post_content',$id)!==$custom || get_post_meta($id,'_tio2_discovery',true)!==$discovery) throw new RuntimeException('Repeat refinement changed editor content or discovery.');
    $edited=str_replace('Explore 14 rutile titanium dioxide pigment grades','Editor revised the portfolio introduction',$custom);
    wp_update_post(['ID'=>$id,'post_content'=>wp_slash($edited)]);
    $result=$run();clean_post_cache($id);
    if (!$result->return_code || get_post_field('post_content',$id)!==$edited) throw new RuntimeException('Edited target passage was overwritten or silently accepted.');
} catch (Throwable $e) {$failure=$e->getMessage();}
finally {wp_update_post(['ID'=>$id,'post_content'=>wp_slash($post->post_content)]);}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('Refinement preserves editor content/discovery and refuses edited targets; fixture restored.');
