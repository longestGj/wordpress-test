<?php
// Read-only consistency report. Approved topic lists can be curated subsets.
$errors=[];
foreach(get_posts(['post_type'=>'page','numberposts'=>-1,'meta_key'=>'_tio2_topic']) as $page){
    $key=get_post_meta($page->ID,'_tio2_topic',true);
    $taxonomy=in_array($key,['chloride','sulfate'],true)?'product_process':'product_application';
    $products=get_posts(['post_type'=>'product','numberposts'=>-1,'tax_query'=>[['taxonomy'=>$taxonomy,'field'=>'slug','terms'=>$key]]]);
    $expected=array_map(fn($p)=>$p->post_name,$products);$listed=[];
    $parser=new WP_HTML_Tag_Processor($page->post_content);
    while($parser->next_tag('a')){
        $href=$parser->get_attribute('href');if(!is_string($href))continue;
        $parts=explode('/',trim(wp_parse_url($href,PHP_URL_PATH)??'','/'));
        if(count($parts)!==2||$parts[0]!=='products')continue;
        $p=get_page_by_path($parts[1],OBJECT,'product');if(!$p)continue;
        $listed[]=$p->post_name;
    }
    $listed=array_values(array_unique($listed));
    foreach(array_diff($listed,$expected) as $slug)$errors[]=$key.': linked '.$slug.' lacks the matching taxonomy; review approved navigation context.';
    $omitted=array_diff($expected,$listed);
    if($omitted)WP_CLI::warning($key.': taxonomy grades not listed (curation may be intentional): '.implode(', ',$omitted));
    WP_CLI::log($key.': '.count($listed).' listed / '.count($expected).' classified');
}
if($errors)WP_CLI::error(implode("\n",$errors));
WP_CLI::success('Topic links match classification; omitted grades are advisory, never auto-synchronized.');
