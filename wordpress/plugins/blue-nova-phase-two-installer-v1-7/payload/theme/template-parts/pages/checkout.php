<?php
/** Approved System 05 checkout and confirmation body. */
while ( have_posts() ) : the_post();
	$is_received = function_exists( 'is_order_received_page' ) && is_order_received_page();
	?>
<main id="main" class="rhn-store-page rhn-purchase-page">
	<div class="rhn-store-wrap rhn-purchase-wrap">
		<nav class="rhn-store-crumbs" aria-label="Breadcrumb"><a href="<?php echo esc_url( rhn_phase_two_shop_url() ); ?>">Online Store</a><span>/</span><span><?php echo $is_received ? 'Confirmation' : 'Checkout'; ?></span></nav>
		<p class="rhn-store-kicker">Secure online checkout</p><h1><?php echo $is_received ? 'Thank you for your order' : 'Checkout'; ?></h1>
		<?php if ( ! $is_received ) : ?><p class="rhn-store-lede">Guest checkout is available. Review your contact, shipping, and order details before placing an order.</p><?php endif; ?>
		<?php rhn_phase_two_purchase_stepper(); ?>
		<div class="rhn-purchase-card"><?php the_content(); ?></div>
	</div>
</main>
<?php endwhile;
