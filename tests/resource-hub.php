<?php
// Read-only renderer check; reordered editor content must keep category meaning.
$saved_query=$GLOBALS['wp_query'];$saved_post=$GLOBALS['post']??null;
try {
    $parent=get_page_by_path('resources');
    $GLOBALS['wp_query']=new WP_Query(['page_id'=>$parent->ID]);
    $GLOBALS['post']=$parent;
    $html=do_blocks($parent->post_content);
    $dom=new DOMDocument();@$dom->loadHTML('<?xml encoding="utf-8" ?>'.$html);
    $xp=new DOMXPath($dom);$cards=$xp->query('//*[@id="research-paths"]//article');
    $grid=$cards->item(0)->parentNode;$last=$cards->item(2);$grid->insertBefore($last,$cards->item(0));
    $reordered=$dom->saveHTML();
    $rendered=apply_filters('render_block_core/html',$reordered);
    $rendered=apply_filters('render_block_core/html',$rendered);
    $check=new DOMDocument();@$check->loadHTML('<?xml encoding="utf-8" ?>'.$rendered);$x=new DOMXPath($check);
    if($x->query('//ul[contains(@class,"resource-hub-links")]')->length!==3)throw new RuntimeException('Duplicated or missing Hub lists');
    $expected=[
      '01 / SOURCING'=>['non-china-titanium-dioxide'],
      '02 / TECHNICAL EVALUATION'=>['chloride-vs-sulfate-titanium-dioxide','chemours-titanium-dioxide-alternatives','ti-pure-r-706-alternative'],
      '03 / TRADE & MARKET'=>['eu-titanium-dioxide-anti-dumping-duty','uk-titanium-dioxide-anti-dumping-investigation','india-titanium-dioxide-anti-dumping-duty','brazil-titanium-dioxide-anti-dumping-duty'],
    ];
    foreach($x->query('//*[@id="research-paths"]//article') as $card){
      $label=trim($x->query('./p[contains(@class,"res-label")]',$card)->item(0)->textContent);
      $actual=[];foreach($x->query('.//ul[contains(@class,"resource-hub-links")]//a',$card)as $link)$actual[]=basename(trim(wp_parse_url($link->getAttribute('href'),PHP_URL_PATH),'/'));
      if($actual!==$expected[$label])throw new RuntimeException('Hub category or source order changed: '.$label);
    }
    WP_CLI::success('Reordered Hub cards retain categories and approved source order; repeated rendering has no duplicate lists.');
} finally {$GLOBALS['wp_query']=$saved_query;$GLOBALS['post']=$saved_post;}
