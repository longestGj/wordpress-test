<?php
// One-time repair of this batch's first import; never overwrite a later edit.
$expected=['chloride'=>'bed15b6a00dc7787c6f61edb12a5728caece54abb69b4578f9611de79644cda6','coatings'=>'119c4acf570182b90641b2c07e8a06c4e2cf9f459149ca3aae4bb8f6491f4213','masterbatch'=>'daae43a6629efa438e2ecbcdb962078668ac1bb55064e9289a2446656c2bba00','paper'=>'52bd84901326c59388b387ad35ad04b799a0286fe8cce0e468f6a5cea8f63925','plastics'=>'d9514155f988d30bd00c04252c5ec0567489bb526231d820380b56eda6b4b280','printing-inks'=>'05bf744f72890581dab4315c45986bf9eb875e4c699e1f92147c5bdf4d7b54fb','sulfate'=>'86bee2d1f89b211544d3e2e015090e001d456d3f25cd18083a814e541c09d2c1'];
$changes=[];
$expected['coatings']=[$expected['coatings'],'0c86c9985e715ebb99cfdbacbac6440e719d601929f4ba959e4634e8fa5c0b31'];
foreach(glob('/workspace/data/process-applications/*.json') as $file){
 $s=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
 $posts=get_posts(['post_type'=>'page','posts_per_page'=>2,'meta_key'=>'_tio2_topic','meta_value'=>$s['key']]);
 if(count($posts)!==1)WP_CLI::error('Expected exactly one owned topic: '.$s['key']);
 $p=$posts[0];if(get_post_meta($p->ID,'_tio2_source',true)!==$s['source'])WP_CLI::error('Ownership mismatch');
 if($p->post_content===$s['content'])continue;
 if(!in_array(hash('sha256',$p->post_content),(array)$expected[$s['key']],true))WP_CLI::error('Editor changes preserved; manual review required: '.$s['key']);
 $changes[]=[$p->ID,$s['content']];
}
foreach($changes as [$id,$content]){$r=wp_update_post(['ID'=>$id,'post_content'=>wp_slash($content)],true);if(is_wp_error($r))WP_CLI::error($r->get_error_message());}
WP_CLI::success('Initial topic blocks repaired; all hash guards passed.');
