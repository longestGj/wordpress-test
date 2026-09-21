<?php defined('ABSPATH') || exit; get_header(); ?>
<main id="main-content" tabindex="-1">
<?php if (have_posts()) : ?>
  <?php if (!is_singular()) : ?>
  <h1><?php is_home() ? esc_html_e('Latest posts','site-starter') : the_archive_title(); ?></h1>
  <?php endif; ?>
  <?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <?php if (is_singular()) : ?>
    <h1><?php the_title(); ?></h1>
    <?php else : ?>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php endif; ?>
    <?php the_content(); wp_link_pages(); ?>
  </article>
  <?php endwhile; the_posts_pagination(); ?>
<?php else : ?>
  <h1><?php esc_html_e('Content not found','site-starter'); ?></h1>
  <p><?php esc_html_e('Use the navigation or return to the homepage.','site-starter'); ?></p>
  <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Homepage','site-starter'); ?></a>
<?php endif; ?>
</main>
<?php get_footer(); ?>
