<?php
defined('ABSPATH') || exit;
function tio2_topic_key($id){$key=get_post_meta($id,'_tio2_topic',true);return in_array($key,['chloride','sulfate','coatings','plastics','masterbatch','printing-inks','paper'],true)?$key:'';}
function tio2_topic_content($id){
    // These new blocks use modern direct children. The classic-theme legacy
    // wrapper would collapse approved grid/flex layouts into a single child.
    $priority=has_filter('render_block_core/group','wp_restore_group_inner_container');
    if($priority!==false)remove_filter('render_block_core/group','wp_restore_group_inner_container',$priority);
    try{$html=do_shortcode(do_blocks(get_post_field('post_content',$id)));}
    finally{if($priority!==false)add_filter('render_block_core/group','wp_restore_group_inner_container',$priority,2);}
    $p=new WP_HTML_Tag_Processor($html);
    while($p->next_tag('a')){
        $href=$p->get_attribute('href');
        if(is_string($href)&&str_starts_with($href,'/')&&!str_starts_with($href,'//')&&!tio2_route_ready($href)){
            $p->remove_attribute('href');$p->set_attribute('aria-disabled','true');$p->set_attribute('title','This destination is not connected in the local preview.');
        }
    }
    return $p->get_updated_html();
}
function tio2_render_topic_page($id){echo '<main id="main" tabindex="-1" class="'.esc_attr(get_post_meta($id,'_tio2_main_class',true)).'">'.tio2_topic_content($id).'</main>';}
add_action('wp_enqueue_scripts',function(){
    $key=tio2_topic_key(get_queried_object_id());if(!$key)return;
    foreach(['topic-'.$key,'topics'] as $asset)wp_enqueue_style('tio2-'.$asset,get_template_directory_uri().'/assets/'.$asset.'.css',['tio2'],filemtime(__DIR__.'/assets/'.$asset.'.css'));
});
add_action('wp_head',function(){
    $id=get_queried_object_id();$key=tio2_topic_key($id);if(!$key)return;
    $url=get_permalink($id);$title=get_post_meta($id,'_tio2_seo_title',true);$description=get_post_meta($id,'_tio2_seo_description',true);
    echo '<meta name="description" content="'.esc_attr($description).'">';
    foreach(['og:title'=>$title,'og:description'=>$description,'og:url'=>$url,'og:type'=>'website'] as $property=>$value)echo '<meta property="'.esc_attr($property).'" content="'.esc_attr($value).'">';
    echo '<meta name="twitter:title" content="'.esc_attr($title).'"><meta name="twitter:description" content="'.esc_attr($description).'">';
    $process=in_array($key,['chloride','sulfate'],true);$parent=$process?'Products':'Applications';
    $crumb=[];foreach([['Home',home_url('/')],[$parent,home_url('/'.strtolower($parent).'/')],[get_post_field('post_title',$id),$url]] as $i=>$x)$crumb[]=['@type'=>'ListItem','position'=>$i+1,'name'=>$x[0],'item'=>$x[1]];
    $graph=[['@type'=>'WebPage','@id'=>$url.'#page','url'=>$url,'name'=>$title,'description'=>$description],['@type'=>'BreadcrumbList','itemListElement'=>$crumb]];
    if($process){
        $p=new WP_HTML_Tag_Processor(tio2_topic_content($id));$items=[];
        while($p->next_tag('a')){
            $href=$p->get_attribute('href');if(!is_string($href)||!preg_match('~^/products/([a-z0-9-]+)/$~',$href,$m))continue;
            $product=get_page_by_path($m[1],OBJECT,'product');if(!$product||$product->post_status!=='publish')continue;
            $items[$product->ID]=['@type'=>'ListItem','position'=>count($items)+1,'name'=>$product->post_title,'url'=>get_permalink($product)];
        }
        if($items)$graph[]=['@type'=>'ItemList','itemListOrder'=>'https://schema.org/ItemListUnordered','itemListElement'=>array_values($items)];
    }
    echo '<script type="application/ld+json">'.wp_json_encode(['@context'=>'https://schema.org','@graph'=>$graph],JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE).'</script>';
});
