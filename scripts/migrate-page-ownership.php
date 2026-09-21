<?php
/** Explicit one-time migration for the frozen, previously imported pages.
 * Preflight ALL records against their original identity and source before writes.
 * Existing editor content is never compared to a seed or overwritten.
 */
$records=[];
foreach(glob('/workspace/data/pages/*.json') as $file){
    $s=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
    $records[]=[$s['slug'],'_tio2_hub_key',$s['key'],$s['source'],null];
}
foreach(glob('/workspace/data/process-applications/*.json') as $file){
    $s=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
    $path=in_array($s['key'],['chloride','sulfate'],true)?basename(trim($s['url'],'/')):trim($s['url'],'/');
    $records[]=[$path,'_tio2_topic',$s['key'],$s['source'],$s['page_id']];
}
foreach(glob('/workspace/data/resources/*.json') as $file){
    $s=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
    $records[]=[trim($s['path'],'/'),'_tio2_resource_id',$s['identity'],$s['source'],null];
}
$pending=[];
foreach($records as [$path,$key,$identity,$source,$page_id]){
    $p=get_page_by_path($path,OBJECT,'page');if(!$p)continue; // Fresh installs use normal importers.
    $owner=get_post_meta($p->ID,'_tio2_owner',true);
    if($owner==='tio2-wordpress'){
        if(get_post_meta($p->ID,$key,true)!==$identity || ($page_id && get_post_meta($p->ID,'_tio2_page_id',true)!==$page_id))WP_CLI::error('Migrated identity mismatch: '.$path);
        continue;
    }
    if($owner!=='' || get_post_meta($p->ID,$key,true)!==$identity || get_post_meta($p->ID,'_tio2_source',true)!==$source || ($page_id && metadata_exists('post',$p->ID,'_tio2_page_id') && get_post_meta($p->ID,'_tio2_page_id',true)!==$page_id))WP_CLI::error('Legacy ownership verification failed: '.$path.'. No migration writes made.');
    $pending[]=[$p->ID,$page_id];
}
foreach($pending as [$id,$page_id]){
    if($page_id)update_post_meta($id,'_tio2_page_id',$page_id);
    update_post_meta($id,'_tio2_owner','tio2-wordpress');
}
WP_CLI::success('Ownership migration complete: '.count($pending).' pages; content and provenance preserved.');
