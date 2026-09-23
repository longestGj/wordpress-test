<?php $atlas_home = is_front_page(); ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="skip" href="#main">Skip to content</a>
<header class="header"><div class="headerInner">
<a class="logoLink" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr($atlas_home ? 'TiO2 Atlas — Home' : 'TiO2 Malaysia — Home'); ?>"><img class="logo" src="<?php echo esc_url(get_template_directory_uri().'/assets/'.($atlas_home ? 'atlas-logo.svg' : 'logo.svg')); ?>" alt="<?php echo esc_attr($atlas_home ? 'TiO2 Atlas' : 'TiO2 Malaysia'); ?>" width="180" height="60"></a>
<nav class="desktopNav" aria-label="Main navigation"><?php tio2_navigation(); ?></nav>
<a class="headerRfq" href="<?php echo esc_url(home_url('/request-a-quote/')); ?>">RFQ</a>
<button class="menuButton" type="button" aria-haspopup="dialog" aria-controls="site-menu" aria-expanded="false">Menu</button>
</div></header>
<dialog id="site-menu" aria-labelledby="menu-title"><h2 id="menu-title">Menu</h2><button type="button" class="menuClose">Close menu</button><nav aria-label="Mobile navigation"><?php tio2_navigation(); ?></nav></dialog>
