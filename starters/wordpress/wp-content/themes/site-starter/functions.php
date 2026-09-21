<?php
defined('ABSPATH') || exit;
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','gallery','caption','style','script']);
    add_theme_support('editor-styles');
    add_editor_style('assets/site.css');
    register_nav_menus(['primary'=>'Primary navigation','footer'=>'Footer navigation']);
});
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('site-starter', get_template_directory_uri().'/assets/site.css', [], '0.1.0');
});
