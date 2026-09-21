<?php
require __DIR__.'/local-only.php';
if(!function_exists('tio2_validate_discovery'))WP_CLI::error('Discovery editing validator missing.');
$id=(int)get_option('tio2_product_hub');$original=tio2_discovery_data();$data=$original;
$data['rows'][0]['summary']='Temporary editable directory description.';
$data['applications']['Paper']=['M-2377'];$data['not_sure'][0]='Temporary editable guidance.';
if(is_wp_error(tio2_validate_discovery($data)))WP_CLI::error('Valid edit rejected.');
try{
 update_post_meta($id,'_tio2_discovery',$data);
 $html=do_shortcode('[tio2_grade_directory][tio2_grade_results]');
 if(!str_contains($html,$data['rows'][0]['summary'])||!str_contains($html,$data['not_sure'][0]))WP_CLI::error('Saved edit not rendered.');
 $bad=$data;$bad['applications']['Paper']=['UNKNOWN'];if(!is_wp_error(tio2_validate_discovery($bad)))WP_CLI::error('Unknown discovery grade accepted.');
 $bad=$data;$bad['rows'][0]['url']='javascript:alert(1)';if(!is_wp_error(tio2_validate_discovery($bad)))WP_CLI::error('Invalid detail URL accepted.');
}finally{update_post_meta($id,'_tio2_discovery',$original);}
WP_CLI::success('Discovery edit rendering, invalid input rejection and restoration passed.');
