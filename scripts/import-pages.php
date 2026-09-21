<?php
$ids=[];
foreach(glob('/workspace/data/pages/*.json') as $file){
 $s=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
 $existing=get_page_by_path($s['slug'],OBJECT,'page');
 if($existing){$ids[$s['key']]=$existing->ID;WP_CLI::log('Preserved '.$s['key']);continue;}
 $id=wp_insert_post(['post_type'=>'page','post_title'=>$s['title'],'post_name'=>$s['slug'],'post_status'=>$s['key']==='products'?'private':'publish','post_content'=>wp_slash($s['content'])],true);
 if(is_wp_error($id))WP_CLI::error($id->get_error_message());
 foreach(['_tio2_hub_key'=>$s['key'],'_tio2_main_class'=>$s['main_class'],'_tio2_seo_title'=>$s['seo_title'],'_tio2_seo_description'=>$s['seo_description'],'_tio2_source'=>$s['source']] as $k=>$v)update_post_meta($id,$k,$v);
 $ids[$s['key']]=$id;WP_CLI::log('Imported '.$s['key']);
}
if(!get_option('tio2_product_hub'))update_option('tio2_product_hub',$ids['products']);
if(!get_option('tio2_product_discovery'))update_option('tio2_product_discovery',json_decode(file_get_contents('/workspace/data/product-discovery.json'),true));
if(!get_post_meta($ids['products'],'_tio2_discovery',true)){$d=get_option('tio2_product_discovery');$d['not_sure']=['Start with the full grade directory and open model pages for further technical evaluation.','You can also share your formulation, process, destination and document requirements for review.'];update_post_meta($ids['products'],'_tio2_discovery',$d);}
if(get_option('show_on_front')!=='page'){update_option('show_on_front','page');update_option('page_on_front',$ids['home']);}
if(!has_nav_menu('primary')){
 $menu=wp_create_nav_menu('Main navigation');if(is_wp_error($menu))WP_CLI::error($menu->get_error_message());
 foreach(['home','markets','products','applications','documents','resources','about'] as $i=>$key){$args=['menu-item-title'=>ucfirst($key),'menu-item-status'=>'publish','menu-item-position'=>$i+1];if($key==='products')$args+=['menu-item-type'=>'custom','menu-item-url'=>home_url('/products/')];else $args+=['menu-item-type'=>'post_type','menu-item-object'=>'page','menu-item-object-id'=>$ids[$key]];wp_update_nav_menu_item($menu,0,$args);}
 $locations=get_theme_mod('nav_menu_locations',[]);$locations['primary']=$menu;set_theme_mod('nav_menu_locations',$locations);
}
flush_rewrite_rules();WP_CLI::success('Root pages ready; existing content preserved.');
