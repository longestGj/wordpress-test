<?php
/** Local fixture: exact summary migration and live technical-row/Schema parity. */
require __DIR__.'/local-only.php';
$post=get_page_by_path('m-350',OBJECT,'product');
if(!$post || $post->post_status!=='publish')WP_CLI::error('Published M-350 product is required.');
$original=tio2_product_data($post->ID);
$revisions_before=array_keys(wp_get_post_revisions($post->ID));
$old=['Rutile titanium dioxide pigment · Chloride process','M-350 technical data · General grade','Product identity · documentation · formulation evaluation'];
$run=static fn($mode)=>WP_CLI::runcommand('eval-file /workspace/scripts/refine-m350-summary.php '.$mode,['return'=>'all','exit_error'=>false,'launch'=>true]);
$render=static function(){
 $response=wp_remote_get('http://wordpress/products/m-350/',['headers'=>['Host'=>'localhost:8080']]);
 if(is_wp_error($response) || wp_remote_retrieve_response_code($response)!==200)throw new RuntimeException('Could not render M-350 fixture.');
 $html=wp_remote_retrieve_body($response);
 if(!preg_match('~<table class="techTable">.*?<tbody>(.*?)</tbody>~s',$html,$table))throw new RuntimeException('Technical table missing.');
 preg_match_all('~<script type="application/ld\+json">(.*?)</script>~s',$html,$scripts);
 $product=null;
 foreach($scripts[1] as $script){$json=json_decode($script,true);$nodes=array_is_list($json)?$json:($json['@graph']??[$json]);foreach($nodes as $node)if(($node['@type']??'')==='Product')$product=$node;}
 if(!$product)throw new RuntimeException('Product schema missing.');
 return [$html,$table[1],$product];
};
$failure=null;
try{
 $fixture=$original;$fixture['summary']=$old;
 update_post_meta($post->ID,'_tio2_product',$fixture);
 $checked=$run('check');
 if($checked->return_code!==0 || tio2_product_data($post->ID)!==$fixture)throw new RuntimeException('Summary dry run failed or changed data.');
 $applied=$run('apply');$updated=tio2_product_data($post->ID);
 if($applied->return_code!==0 || $updated['summary']===$old)throw new RuntimeException('Exact summary migration failed: '.$applied->stderr);
 $unchanged=$updated;$unchanged['summary']=$old;
 if($unchanged!==$fixture)throw new RuntimeException('Migration changed unrelated product data.');
 $repeated=$run('apply');
 if($repeated->return_code!==0 || tio2_product_data($post->ID)!==$updated)throw new RuntimeException('Repeat migration changed data.');
 $edited=$fixture;$edited['summary'][0]='Editor-supplied M-350 summary';
 update_post_meta($post->ID,'_tio2_product',$edited);
 $refused=$run('apply');
 if($refused->return_code===0 || tio2_product_data($post->ID)!==$edited)throw new RuntimeException('Edited summary was overwritten or accepted.');
 $changed=$updated;$changed['rows'][0]['typical_value']='93.6';
 update_post_meta($post->ID,'_tio2_product',$changed);
 [, $table, $schema]=$render();
 if(substr_count($table,'<tr')!==15 || !str_contains($table,'93.6') || !str_contains($schema['additionalProperty'][0]['value'],'93.6'))throw new RuntimeException('Edited technical row did not reach both table and schema.');
 $changed['rows'][0]['enabled']=false;
 update_post_meta($post->ID,'_tio2_product',$changed);
 [, $table, $schema]=$render();
 if(substr_count($table,'<tr')!==14 || count($schema['additionalProperty'])!==14 || str_contains($table,'TiO₂ content, %'))throw new RuntimeException('Disabled technical row remained visible or in schema.');
}catch(Throwable $error){$failure=$error->getMessage();}
finally{
 update_post_meta($post->ID,'_tio2_product',$original);
 foreach(array_diff(array_keys(wp_get_post_revisions($post->ID)),$revisions_before) as $revision_id)wp_delete_post_revision($revision_id);
 if(tio2_product_data($post->ID)!==$original)$failure=($failure??'').' Fixture restoration failed.';
}
if($failure)WP_CLI::error($failure);
WP_CLI::success('M-350 exact summary migration, editor preservation, 15-row edit/disable parity and fixture restoration passed.');
