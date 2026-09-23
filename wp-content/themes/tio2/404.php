<?php
get_header();
?>
<main id="main" class="utility-page utility-recovery" tabindex="-1">
  <section class="utility-hero"><div class="wrap utility-hero-inner">
    <p class="eyebrow">404 · Page not found</p>
    <h1>Let’s help you find what you need.</h1>
    <p class="lead">The page you’re looking for may have moved or is no longer available. You can continue by exploring our titanium dioxide products, requesting technical documents, or contacting our team.</p>
    <div class="utility-actions">
      <a class="button primary" href="<?php echo esc_url(home_url('/products/')); ?>">Explore Products</a>
      <a class="button" href="<?php echo esc_url(home_url('/')); ?>">Go to Homepage</a>
    </div>
  </div></section>
  <section class="wrap utility-recovery-links" aria-label="Other routes">
    <?php if (tio2_route_ready('/request-documents/')) : ?><a href="<?php echo esc_url(home_url('/request-documents/')); ?>">Request Documents <span aria-hidden="true">→</span></a><?php endif; ?>
    <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact Our Team <span aria-hidden="true">→</span></a>
    <?php if (tio2_route_ready('/request-a-quote/')) : ?><a href="<?php echo esc_url(home_url('/request-a-quote/')); ?>">Request a Quote <span aria-hidden="true">→</span></a><?php endif; ?>
  </section>
</main>
<?php get_footer();
