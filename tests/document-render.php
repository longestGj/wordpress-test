<?php
/** Standalone rendering regression: WP interfaces are stubbed; no database or network. */
define('ABSPATH', __DIR__);
function post_password_required($id) { return $GLOBALS['protected'] ?? false; }
function get_the_password_form($id) { return '<form>Password required</form>'; }
function add_shortcode($name,$callback) { $GLOBALS['shortcodes'][$name]=$callback; }
function get_post_meta($id,$key,$single=true) { return $GLOBALS['meta'][$key] ?? ''; }
function get_post_type($id) { return 'page'; }
function get_post_field($key,$id) { return $GLOBALS['content']; }
function do_blocks($html) { return $html; }
function do_shortcode($html) { return str_replace('[tio2_document_grades]', $GLOBALS['shortcodes']['tio2_document_grades'](), $html); }
function wp_parse_url($url,$component=-1) { return parse_url($url,$component); }
function tio2_target_url($key) { return $GLOBALS['receiver']; }
function home_url($path='') { return 'http://localhost:8080'.$path; }
function tio2_route_ready($path) { return !str_contains($path, 'missing') && !str_contains($path, '/markets/'); }
function tio2_discovery_data() { return ['rows'=>[['grade'=>'M-350','url'=>'/products/m-350/']]]; }
function esc_attr($s) { return htmlspecialchars($s,ENT_QUOTES); }
function esc_html($s) { return htmlspecialchars($s,ENT_QUOTES); }
function esc_url($s) { return htmlspecialchars($s,ENT_QUOTES); }
function add_query_arg($args,$url) { return $url.'?'.http_build_query($args); }
require __DIR__.'/../wp-content/plugins/tio2-products/ownership.php';
require __DIR__.'/../wp-content/plugins/tio2-products/documents.php';
function check($condition,$message) { if (!$condition) throw new RuntimeException($message); }
foreach (glob(__DIR__.'/../data/documents/*.json') as $file) {
    $seed=json_decode(file_get_contents($file),true,512,JSON_THROW_ON_ERROR);
    $GLOBALS['content']=$seed['content'];$GLOBALS['meta']=['_tio2_owner'=>'tio2-wordpress','_tio2_document_id'=>$seed['identity']];
    $GLOBALS['content'].='<a href="http://localhost:8080/request-documents/">Absolute request</a><a href="http://localhost:8080/missing/">Missing route</a>';
    $GLOBALS['receiver']='';$html=tio2_document_content(1);
    check(!str_contains($html,'href="/request-documents/"')&&!str_contains($html,'data-document-request'), 'Unavailable request action retained');
    check(!str_contains($html,'Absolute request') && !str_contains($html,'href="http://localhost:8080/missing/"'), 'Absolute unavailable link retained');
    check(str_contains($html,'http://localhost:8080/documents/'), 'Hub link absent');
    check(!str_contains($html,'[tio2_document_grades]'), 'Shortcode not expanded');
    check(!str_contains($html,'href="/markets/'), 'Unavailable market route linked');
    $GLOBALS['receiver']='http://localhost:8080/request-documents/';$html=tio2_document_content(1);
    check(str_contains($html,'source_page='.$seed['identity']), 'Request attribution missing');
    $GLOBALS['protected']=true;check(tio2_document_content(1)==='<form>Password required</form>', 'Protected content leaked');$GLOBALS['protected']=false;
    $GLOBALS['meta']['_tio2_owner']='foreign';check(tio2_document_id(1)==='', 'Foreign page accepted');
}
echo "PASS: three renderers; absent receiver actions removed; hub links; shortcode; unavailable routes; attribution; owner guard\n";
