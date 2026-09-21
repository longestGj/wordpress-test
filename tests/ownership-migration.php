<?php
require __DIR__.'/local-only.php';
$a=get_page_by_path('about');$b=get_page_by_path('resources');
$saved=[];foreach([$a,$b] as $p)$saved[$p->ID]=get_post_meta($p->ID);
$run=fn()=>WP_CLI::runcommand('eval-file /workspace/scripts/migrate-page-ownership.php',['return'=>'all','exit_error'=>false,'launch'=>true]);
try{
    delete_post_meta($a->ID,'_tio2_owner');delete_post_meta($b->ID,'_tio2_owner');update_post_meta($b->ID,'_tio2_source','Unknown legacy source');
    $r=$run();clean_post_cache($a->ID);
    if($r->return_code===0 || metadata_exists('post',$a->ID,'_tio2_owner'))throw new RuntimeException('Migration wrote before validating all legacy pages');
    update_post_meta($b->ID,'_tio2_source',wp_slash(maybe_unserialize($saved[$b->ID]['_tio2_source'][0])));
    $r=$run();clean_post_cache($a->ID);clean_post_cache($b->ID);
    if($r->return_code!==0 || !tio2_owns_page($a->ID,'_tio2_hub_key','about') || !tio2_owns_page($b->ID,'_tio2_hub_key','resources'))throw new RuntimeException('Verified legacy pages not migrated');
    $r=$run();if($r->return_code!==0 || !str_contains($r->stdout,'0 pages'))throw new RuntimeException('Migration not idempotent');
    if(get_post_field('post_content',$a->ID)!==$a->post_content || get_post_field('post_content',$b->ID)!==$b->post_content)throw new RuntimeException('Migration changed content');
}finally{
    foreach($saved as $id=>$meta)foreach(['_tio2_owner','_tio2_source'] as $key){delete_post_meta($id,$key);foreach($meta[$key]??[] as $v)add_post_meta($id,$key,wp_slash(maybe_unserialize($v)));}
}
WP_CLI::success('Migration rejects unverified legacy pages before writes; verified identities migrate idempotently without content edits.');
