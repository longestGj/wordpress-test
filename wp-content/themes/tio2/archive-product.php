<?php
get_header();$id=(int)get_option('tio2_product_hub');
if($id){echo '<main id="main" tabindex="-1" class="'.esc_attr(get_post_meta($id,'_tio2_main_class',true)).'">'.tio2_hub_content($id);tio2_render_document_links();echo '</main>';}
else echo '<main id="main" class="wrap section"><h1>Products</h1></main>';
get_footer();
