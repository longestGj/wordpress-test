<?php defined('ABSPATH') || exit; ?>
<footer class="site-footer">
  <p><?php bloginfo('name'); ?></p>
  <?php if (has_nav_menu('footer')) : ?>
    <nav aria-label="<?php esc_attr_e('Footer navigation','site-starter'); ?>">
    <?php wp_nav_menu(['theme_location'=>'footer','container'=>false,'fallback_cb'=>false,'depth'=>1]); ?>
    </nav>
  <?php endif; ?>
</footer>
<?php wp_footer(); ?>
</body>
</html>
