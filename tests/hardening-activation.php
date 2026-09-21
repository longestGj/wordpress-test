<?php
$before=did_action('init');
do_action('activate_tio2-products/tio2-products.php');
if(did_action('init')!==$before)throw new RuntimeException('Activation dispatched global init again');
if(!post_type_exists('product')||!taxonomy_exists('product_application'))throw new RuntimeException('Content registration absent');
WP_CLI::success('Activation registers product content without redispatching init.');
