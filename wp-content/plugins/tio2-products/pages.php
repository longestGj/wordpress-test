<?php
defined('ABSPATH') || exit;
// Root pages use native post_content, plus a small, editable SEO field group.
add_action('add_meta_boxes_page',function(){add_meta_box('tio2-page-seo','Page SEO',function($post){wp_nonce_field('tio2_page_seo','tio2_page_nonce');foreach(['title'=>'SEO title','description'=>'Meta description'] as $key=>$label)echo '<p><label>'.esc_html($label).'<br><textarea name="tio2_seo['.esc_attr($key).']" style="width:100%">'.esc_textarea(get_post_meta($post->ID,'_tio2_seo_'.$key,true)).'</textarea></label></p>';echo '<p>Root page copy is stored in the native content editor. The HTML block preserves the approved fixed layout. Edit its text and links without changing theme code.</p>';},'page');});
add_action('save_post_page',function($id){
 if(wp_is_post_revision($id)||!current_user_can('edit_post',$id)||empty($_POST['tio2_page_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tio2_page_nonce'])),'tio2_page_seo'))return;
 foreach(['title','description'] as $key)if(isset($_POST['tio2_seo'][$key])&&is_string($_POST['tio2_seo'][$key]))update_post_meta($id,'_tio2_seo_'.$key,sanitize_textarea_field(wp_unslash($_POST['tio2_seo'][$key])));
});
function tio2_hub_id(){return is_post_type_archive('product')?(int)get_option('tio2_product_hub'):((is_page()||is_front_page())?get_queried_object_id():0);}
function tio2_hub_key(){return get_post_meta(tio2_hub_id(),'_tio2_hub_key',true);}
function tio2_route_ready($path){
 static $cache=[];$path=wp_parse_url($path,PHP_URL_PATH);if(!$path)return false;
 if(isset($cache[$path]))return $cache[$path];
 if(in_array($path,['/','/products/'],true))return true;
 $receivers=['/request-a-quote/'=>'quote','/request-sample/'=>'sample','/request-documents/'=>'documents'];
 if(isset($receivers[$path]))return (bool)tio2_target_url($receivers[$path]);
 $id=url_to_postid(home_url($path));return $cache[$path]=$id&&get_post_status($id)==='publish'&&!post_password_required($id);
}
function tio2_hub_content($id){
 $html=do_shortcode(do_blocks(get_post_field('post_content',$id)));
 $p=new WP_HTML_Tag_Processor($html);
 while($p->next_tag('a')){
  $href=$p->get_attribute('href');if(!is_string($href)||!str_starts_with($href,'/')||str_starts_with($href,'//'))continue;
  $path=wp_parse_url($href,PHP_URL_PATH);
  // Approved fixed RFQ remains a discoverable dependency; other unavailable actions become plain labels.
  if($path!=='/request-a-quote/'&&!tio2_route_ready($path)){$p->remove_attribute('href');$p->set_attribute('aria-disabled','true');$p->set_attribute('title','This destination is not connected in the local preview.');}
 }
 return $p->get_updated_html();
}
add_shortcode('tio2_grade_results',function(){
 $d=tio2_discovery_data();$html='';
 foreach(($d['applications']??[]) as $app=>$grades){
  $html.='<div data-result-app="'.esc_attr($app).'"'.($app!=='Coatings'?' hidden':'').'>';
  foreach($grades as $grade){$slug=sanitize_title($grade);$post=get_page_by_path($slug,OBJECT,'product');$html.='<div class="result"><strong>'.esc_html($grade).'</strong>';if($post&&get_post_status($post)==='publish'&&!post_password_required($post))$html.='<a aria-label="View '.esc_attr($grade).' grade" href="'.esc_url(get_permalink($post)).'">View Grade</a>';$html.='</div>';}
  $html.='</div>';
 }
 $html.='<div data-result-app="Not Sure" hidden><div class="resultMessage">';foreach($d['not_sure']??[] as $p)$html.='<p>'.esc_html($p).'</p>';
 return $html.'<a href="#all-grades">View All Grades</a><a href="'.esc_url(home_url('/request-a-quote/')).'">Request a Quote</a></div></div>';
});
function tio2_discovery_guidance(){return ['Start with the full grade directory and open model pages for further technical evaluation.','You can also share your formulation, process, destination and document requirements for review.'];}
function tio2_discovery_data(){
 $d=get_post_meta((int)get_option('tio2_product_hub'),'_tio2_discovery',true)?:get_option('tio2_product_discovery',[]);
 if(!is_array($d))$d=[];
 $guidance=is_array($d['not_sure']??null)?array_values(array_filter($d['not_sure'],static fn($text)=>is_string($text)&&trim($text)!=='')):[];
 $d['not_sure']=$guidance?:tio2_discovery_guidance();
 return $d;
}
function tio2_validate_discovery($d){
 if(!is_array($d)||empty($d['rows'])||!is_array($d['rows'])||!isset($d['applications'],$d['not_sure'])||!is_array($d['applications'])||!is_array($d['not_sure']))return new WP_Error('discovery_shape','Discovery rows, applications and guidance are required.');
 $grades=[];
 foreach($d['rows'] as $r){
  if(!is_array($r))return new WP_Error('discovery_row','Each row must be a record.');
  foreach(['grade','group','summary','url'] as $k)if(!isset($r[$k])||!is_string($r[$k])||trim($r[$k])==='')return new WP_Error('discovery_row','Each grade needs its name, group, summary and detail path.');
  if(!preg_match('~^/products/[a-z0-9-]+/$~D',$r['url'])||in_array($r['grade'],$grades,true))return new WP_Error('discovery_url','Use a unique grade and a local product detail path.');
  $grades[]=$r['grade'];
 }
 $allowed=['Coatings','Plastics','Masterbatch','Printing Inks','Paper','Specialty Materials'];
 if(array_diff($allowed,array_keys($d['applications']))||array_diff(array_keys($d['applications']),$allowed))return new WP_Error('discovery_applications','Keep the six named discovery application groups.');
 foreach($d['applications'] as $list){if(!is_array($list))return new WP_Error('discovery_list','Application grades must be a list.');foreach($list as $grade)if(!is_string($grade)||!in_array($grade,$grades,true))return new WP_Error('discovery_grade','Application lists must use a grade in the directory.');if(count($list)!==count(array_unique($list)))return new WP_Error('discovery_duplicate','Application lists must not repeat a grade.');}
 if(!$d['not_sure'])return new WP_Error('discovery_guidance','Keep at least one helpful Not Sure guidance paragraph.');
 foreach($d['not_sure'] as $text)if(!is_string($text)||trim($text)==='')return new WP_Error('discovery_guidance','Guidance must be non-empty text.');
 return $d;
}
function tio2_grade_comparison($id){
 $applications=wp_get_object_terms($id,'product_application',['fields'=>'names']);
 $processes=wp_get_object_terms($id,'product_process',['fields'=>'names']);
 $data=tio2_product_data($id);
 return ['applications'=>is_wp_error($applications)?[]:$applications,
  'process_label'=>!is_wp_error($processes)&&$processes?'Production process':'Process descriptor',
  'process'=>!is_wp_error($processes)&&$processes?implode(' / ',$processes):($data['process_label']??'')];
}
add_shortcode('tio2_grade_directory',function(){
 $groups=[];foreach(tio2_discovery_data()['rows']??[] as $row)$groups[$row['group']][]=$row;$html='';
 foreach($groups as $group=>$rows){
  $html.='<article class="group"><h3>'.esc_html($group).'</h3>';
  foreach($rows as $r){
   $post=get_page_by_path(sanitize_title($r['grade']),OBJECT,'product');
   $visible=$post&&get_post_status($post)==='publish'&&!post_password_required($post);
   $html.='<div class="gradeRow" data-grade="'.esc_attr($r['grade']).'"><strong>'.esc_html($r['grade']).'</strong>';
   if($visible)$html.='<a aria-label="View '.esc_attr($r['grade']).' grade" href="'.esc_url(get_permalink($post)).'">View Grade <span aria-hidden="true">→</span></a>';
   $facts=$visible?tio2_grade_comparison($post->ID):['applications'=>[],'process_label'=>'Production process','process'=>''];
   // Format legacy lowercase term names without changing taxonomy membership.
   $labels=array_map(static fn($label)=>$label==='specialty'?'Specialty Materials':ucfirst($label),$facts['applications']);
   $html.='<dl class="gradeDetails"><div><dt>Listed applications</dt><dd>'.esc_html($labels?implode(' / ',$labels):'Not listed').'</dd></div><div><dt>'.esc_html($facts['process_label']).'</dt><dd>'.esc_html($facts['process']?ucfirst($facts['process']):'Not listed').'</dd></div><div class="gradeCharacteristics"><dt>Key characteristics</dt><dd>'.esc_html($r['summary']).'</dd></div></dl></div>';
  }
  $html.='</article>';
 }
 return $html;
});
add_action('add_meta_boxes_page',function($post){
 if($post->ID!==(int)get_option('tio2_product_hub'))return;
 add_meta_box('tio2-discovery','Product directory and discovery relationships',function(){wp_nonce_field('tio2_discovery','tio2_discovery_nonce');echo '<p>These are hub discovery relationships, separate from technical application facts on individual products. Directory copy and structured data use these same records. Preserve approved order.</p>';$d=tio2_discovery_data();foreach(['rows','applications','not_sure'] as $key)tio2_field_editor($d[$key],'tio2_discovery['.$key.']',ucwords(str_replace('_',' ',$key)));},'page','normal','high');
});
add_action('save_post_page',function($id){
 if($id!==(int)get_option('tio2_product_hub')||wp_is_post_revision($id)||!current_user_can('edit_post',$id)||empty($_POST['tio2_discovery_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['tio2_discovery_nonce'])),'tio2_discovery'))return;
 if(!isset($_POST['tio2_discovery'])||!is_array($_POST['tio2_discovery']))return;
 // Preserve case-sensitive application labels; they are keys, not taxonomy slugs.
 $clean=function($v)use(&$clean){if(!is_array($v))return is_string($v)?sanitize_textarea_field($v):'';$out=[];foreach($v as $k=>$x)$out[$k]=$clean($x);return $out;};
 $d=$clean(wp_unslash($_POST['tio2_discovery']));$valid=tio2_validate_discovery($d);
 if(is_wp_error($valid)){set_transient('tio2_error_'.get_current_user_id(),$valid->get_error_message(),60);return;}
 $original=tio2_discovery_data();foreach(['rows','applications','not_sure'] as $k)$original[$k]=$d[$k];update_post_meta($id,'_tio2_discovery',$original);
});
