<?php defined('ABSPATH') || exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e('Skip to content','site-starter'); ?></a>
<header class="site-header">
  <a class="site-name" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
  <?php if (has_nav_menu('primary')) : ?>
  <nav aria-label="<?php esc_attr_e('Primary navigation','site-starter'); ?>">
    <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'fallback_cb'=>false,'depth'=>1]); ?>
  </nav>
  <?php endif; ?>
</header>
