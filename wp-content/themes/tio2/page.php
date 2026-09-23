<?php
get_header();the_post();$id=get_the_ID();$key=get_post_meta($id,'_tio2_hub_key',true);
if(tio2_document_id($id)){tio2_render_document_page($id);}
elseif(function_exists('tio2_is_resource_page')&&tio2_is_resource_page($id)){tio2_render_resource_page($id);}
elseif(function_exists('tio2_market_id')&&tio2_market_id($id)){tio2_render_market($id);}
elseif($key){echo '<main id="main" tabindex="-1" class="'.esc_attr(get_post_meta($id,'_tio2_main_class',true)).'">'.tio2_hub_content($id);if($key==='documents')tio2_render_document_links();echo '</main>';}
elseif(tio2_topic_key($id)){tio2_render_topic_page($id);}
elseif(function_exists('tio2_is_utility_page')&&tio2_is_utility_page($id)){tio2_render_utility_page($id);}
else{echo '<main id="main" class="wrap section"><h1>'.esc_html(get_the_title()).'</h1>';the_content();echo '</main>';}
get_footer();
