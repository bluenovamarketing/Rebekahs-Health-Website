<?php
/** Approved System 05 cart body. */
while ( have_posts() ) : the_post(); ?>
<main id="main" class="rhn-store-page rhn-purchase-page">
	<div class="rhn-store-wrap rhn-purchase-wrap">
		<nav class="rhn-store-crumbs" aria-label="Breadcrumb"><a href="<?php echo esc_url( rhn_phase_two_shop_url() ); ?>">Online Store</a><span>/</span><span>Cart</span></nav>
		<p class="rhn-store-kicker">Secure online checkout</p><h1>Your Cart</h1><p class="rhn-store-lede">Review your selections before continuing to checkout.</p>
		<?php rhn_phase_two_purchase_stepper(); ?>
		<div class="rhn-purchase-card"><?php the_content(); ?></div>
	</div>
</main>
<?php endwhile;
