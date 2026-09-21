<?php
get_header();the_post();$id=get_the_ID();$key=get_post_meta($id,'_tio2_hub_key',true);
if($key){echo '<main id="main" tabindex="-1" class="'.esc_attr(get_post_meta($id,'_tio2_main_class',true)).'">'.tio2_hub_content($id).'</main>';}
else{echo '<main id="main" class="wrap section"><h1>'.esc_html(get_the_title()).'</h1>';the_content();echo '</main>';}
get_footer();
