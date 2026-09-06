<?php
/** Approved System 06 real WooCommerce account body. */
while ( have_posts() ) : the_post(); ?>
<main id="main" class="rhn-store-page rhn-account-page">
	<div class="rhn-store-wrap">
		<nav class="rhn-store-crumbs" aria-label="Breadcrumb"><a href="<?php echo esc_url( rhn_phase_two_shop_url() ); ?>">Online Store</a><span>/</span><span>My Account</span></nav>
		<p class="rhn-store-kicker">Customer account access</p><h1>My Account</h1>
		<p class="rhn-store-lede">Guest checkout stays available. Customers may also create a simple optional account for saved addresses, order history, and convenient self-service access.</p>
		<div class="rhn-account-shell"><?php the_content(); ?></div>
	</div>
</main>
<?php endwhile;
