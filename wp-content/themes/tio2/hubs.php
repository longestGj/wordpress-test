<?php
defined('ABSPATH') || exit;
function tio2_site_entity_nodes(){
 $site=home_url('/');
 return [
  ['@type'=>'WebSite','@id'=>$site.'#website','url'=>$site,'name'=>'TiO2Products','publisher'=>['@id'=>$site.'#organization']],
  ['@type'=>'Organization','@id'=>$site.'#organization','name'=>'IKHLAS TITANIUM (MALAYSIA) SDN. BHD.','legalName'=>'IKHLAS TITANIUM (MALAYSIA) SDN. BHD.','url'=>$site,'brand'=>['@id'=>$site.'#brand']],
  ['@type'=>'Brand','@id'=>$site.'#brand','name'=>'TiO2Products'],
 ];
}
add_action('wp_enqueue_scripts',function(){
 $key=tio2_hub_key();if(!in_array($key,['home','products','applications','markets','documents','resources','about'],true))return;
 wp_enqueue_style('tio2-hub',get_template_directory_uri().'/assets/hub-'.$key.'.css',['tio2'],filemtime(__DIR__.'/assets/hub-'.$key.'.css'));
 wp_enqueue_style('tio2-hub-runtime',get_template_directory_uri().'/assets/hubs.css',['tio2-hub'],filemtime(__DIR__.'/assets/hubs.css'));
 wp_enqueue_script('tio2-hubs',get_template_directory_uri().'/assets/hubs.js',[],filemtime(__DIR__.'/assets/hubs.js'),true);
 wp_add_inline_script('tio2-hubs','window.tio2DocumentReceiver='.wp_json_encode(tio2_target_url('documents')).';','before');
});
add_filter('pre_get_document_title',function($title){$id=tio2_hub_id();return $id?(get_post_meta($id,'_tio2_seo_title',true)?:$title):$title;},20);
add_filter('get_canonical_url',function($url,$post){return get_post_meta($post->ID,'_tio2_hub_key',true)==='home'?home_url('/'):$url;},10,2);
add_action('wp_head',function(){
 $id=tio2_hub_id();if(!$id||!tio2_hub_key())return;$key=tio2_hub_key();$url=$key==='products'?home_url('/products/'):get_permalink($id);$title=get_post_meta($id,'_tio2_seo_title',true);$description=get_post_meta($id,'_tio2_seo_description',true);
 echo '<meta name="description" content="'.esc_attr($description).'">';
 if($key==='products')echo '<link rel="canonical" href="'.esc_url($url).'">';
 foreach(['og:title'=>$title,'og:description'=>$description,'og:url'=>$url,'og:type'=>'website'] as $property=>$value)echo '<meta property="'.esc_attr($property).'" content="'.esc_attr($value).'">';
 $graph=[['@type'=>in_array($key,['products','applications','markets','resources'],true)?'CollectionPage':'WebPage','@id'=>$url.'#page','url'=>$url,'name'=>$title,'description'=>$description]];
 if($key==='about'){
  $graph[0]['isPartOf']=['@id'=>home_url('/').'#website'];
  $graph[0]['about']=['@id'=>home_url('/').'#organization'];
 }
 if($key==='home'){
  $site=home_url('/');
  $graph[0]['inLanguage']='en';
  $graph[0]['isPartOf']=['@id'=>$site.'#website'];
  $graph[0]['publisher']=['@id'=>$site.'#organization'];
  array_push($graph,...tio2_site_entity_nodes());
 }
 if($key!=='home')$graph[]=['@type'=>'BreadcrumbList','itemListElement'=>[['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>home_url('/')],['@type'=>'ListItem','position'=>2,'name'=>get_the_title($id),'item'=>$url]]];
 if($key==='products'){$list=[];foreach((tio2_discovery_data()['rows']??[]) as $i=>$r){$item=['@type'=>'ListItem','position'=>$i+1,'name'=>$r['grade']];if(tio2_route_ready($r['url']))$item['url']=home_url($r['url']);$list[]=$item;}$graph[]=['@type'=>'ItemList','itemListElement'=>$list];}
 if($key==='applications'){
  $list=[];$identities=['coatings'=>'APP-COAT','plastics'=>'APP-PLAS','masterbatch'=>'APP-MB','printing-inks'=>'APP-INK','paper'=>'APP-PAPER'];
  $targets=get_option('tio2_targets',[]);
  foreach($identities as $topic=>$identity){
   $child=absint($targets[$topic]??0);
   if(!$child||!tio2_owns_page($child,'_tio2_page_id',$identity)||get_post_status($child)!=='publish'||post_password_required($child))continue;
   $list[]=['@type'=>'ListItem','position'=>count($list)+1,'name'=>get_the_title($child),'url'=>get_permalink($child)];
  }
  if($list)$graph[]=['@type'=>'ItemList','itemListElement'=>$list];
 }
 if($key==='resources'){
  $list=[];
  foreach(tio2_public_resource_children($id) as $child){
   $list[]=['@type'=>'ListItem','position'=>count($list)+1,'name'=>$child->post_title,'url'=>get_permalink($child)];
  }
  if($list)$graph[]=['@type'=>'ItemList','itemListElement'=>$list];
 }
 echo '<script type="application/ld+json">'.wp_json_encode(['@context'=>'https://schema.org','@graph'=>$graph],JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE).'</script>';
});
