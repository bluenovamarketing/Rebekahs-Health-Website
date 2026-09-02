<?php
/**
 * Phase Two ecommerce component scaffold.
 *
 * This file is intentionally dormant until the approved staging build defines
 * RHN_PHASE_TWO_ECOMMERCE_ENABLED as true and loads it from functions.php.
 *
 * @package Rebekahs_2026
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'RHN_PHASE_TWO_ECOMMERCE_ENABLED' ) || true !== RHN_PHASE_TWO_ECOMMERCE_ENABLED ) {
	return;
}

/** Determine whether the current request uses a WooCommerce customer surface. */
function rhn_phase_two_is_commerce_context() {
	return ( function_exists( 'is_woocommerce' ) && is_woocommerce() )
		|| ( function_exists( 'is_cart' ) && is_cart() )
		|| ( function_exists( 'is_checkout' ) && is_checkout() )
		|| ( function_exists( 'is_account_page' ) && is_account_page() );
}

/** Load the Phase Two styles and interaction helpers only where needed. */
function rhn_phase_two_enqueue_commerce_assets() {
	if ( ! rhn_phase_two_is_commerce_context() && ! is_front_page() ) {
		return;
	}

	$css_path = get_template_directory() . '/assets/css/components/phase-two-commerce.css';
	$js_path  = get_template_directory() . '/assets/js/components/phase-two-commerce.js';

	if ( file_exists( $css_path ) ) {
		wp_enqueue_style(
			'rhn-phase-two-commerce',
			rhn_theme_asset( 'css/components/phase-two-commerce.css' ),
			array( 'rhn-global-chrome' ),
			filemtime( $css_path )
		);
	}

	if ( file_exists( $js_path ) ) {
		wp_enqueue_script(
			'rhn-phase-two-commerce',
			rhn_theme_asset( 'js/components/phase-two-commerce.js' ),
			array(),
			filemtime( $js_path ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'rhn_phase_two_enqueue_commerce_assets', 30 );

/** Add a stable scope class without changing the approved global chrome. */
function rhn_phase_two_commerce_body_classes( $classes ) {
	if ( rhn_phase_two_is_commerce_context() ) {
		$classes[] = 'rhn-commerce';
	}
	return $classes;
}
add_filter( 'body_class', 'rhn_phase_two_commerce_body_classes' );

/** Return the live cart count without assuming a cart session is available. */
function rhn_phase_two_cart_count() {
	if ( function_exists( 'WC' ) && WC()->cart ) {
		return (int) WC()->cart->get_cart_contents_count();
	}
	return 0;
}

/** Render the approved second-row commerce utility navigation. */
function rhn_phase_two_store_utility_row() {
	$count = rhn_phase_two_cart_count();
	?>
	<nav class="rhn-store-utility" aria-label="Online store">
		<div class="rhn-store-utility__inner">
			<a class="rhn-store-utility__home" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Online Store</a>
			<div class="rhn-store-utility__actions">
				<a href="<?php echo esc_url( home_url( '/?s=&post_type=product' ) ); ?>" aria-label="Search the online store">
					<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="6.5"></circle><path d="m16 16 4 4"></path></svg><span>Search</span>
				</a>
				<a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="8" r="3.5"></circle><path d="M5.5 20c.6-4 2.9-6 6.5-6s5.9 2 6.5 6"></path></svg><span>My Account</span>
				</a>
				<a class="rhn-store-utility__cart" href="<?php echo esc_url( home_url( '/cart/' ) ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M3 4h2l1.8 10.2h9.8l2-7.2H6"></path><circle cx="9" cy="19" r="1.3"></circle><circle cx="17" cy="19" r="1.3"></circle></svg><span>Cart</span><span class="rhn-store-cart-count" aria-label="<?php echo esc_attr( sprintf( _n( '%d item', '%d items', $count, 'rebekahs-2026' ), $count ) ); ?>"><?php echo esc_html( $count ); ?></span>
				</a>
			</div>
		</div>
	</nav>
	<?php
}

/** Keep the utility cart count current after an AJAX add-to-cart action. */
function rhn_phase_two_cart_count_fragment( $fragments ) {
	$count = rhn_phase_two_cart_count();
	ob_start();
	?>
	<span class="rhn-store-cart-count" aria-label="<?php echo esc_attr( sprintf( _n( '%d item', '%d items', $count, 'rebekahs-2026' ), $count ) ); ?>"><?php echo esc_html( $count ); ?></span>
	<?php
	$fragments['.rhn-store-cart-count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'rhn_phase_two_cart_count_fragment' );

/** Render the fifth approved Online Store footer group. */
function rhn_phase_two_store_footer_group() {
	?>
	<details class="footer-group rhn-store-footer-group" open>
		<summary>Online Store</summary>
		<div class="footer-links">
			<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop All</a>
			<a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>">My Account</a>
			<a href="<?php echo esc_url( home_url( '/cart/' ) ); ?>">Cart</a>
			<a href="<?php echo esc_url( home_url( '/checkout/' ) ); ?>">Checkout</a>
			<a href="<?php echo esc_url( home_url( '/shipping-policy/' ) ); ?>">Shipping &amp; Returns</a>
		</div>
	</details>
	<?php
}

/** Render the approved missing-product-photo component. */
function rhn_phase_two_product_image_fallback( $label = '' ) {
	$label = $label ? $label : __( 'Product photo coming soon', 'rebekahs-2026' );
	?>
	<div class="rhn-product-image-fallback" role="img" aria-label="<?php echo esc_attr( $label ); ?>">
		<img src="<?php echo esc_url( rhn_theme_asset( 'img/rebekahs-site-icon-512.png' ) ); ?>" alt="" aria-hidden="true">
		<span><?php echo esc_html( $label ); ?></span>
	</div>
	<?php
}

/** Replace WooCommerce's default placeholder without printing a second image. */
function rhn_phase_two_product_image_markup( $image, $product, $size, $attr, $placeholder ) {
	if ( ! $placeholder || ! is_a( $product, 'WC_Product' ) || $product->get_image_id() ) {
		return $image;
	}

	ob_start();
	rhn_phase_two_product_image_fallback();
	return ob_get_clean();
}
add_filter( 'woocommerce_product_get_image', 'rhn_phase_two_product_image_markup', 10, 5 );

/** Add the approved friendly help line after the single-product purchase controls. */
function rhn_phase_two_product_help_line() {
	?>
	<p class="rhn-product-help">Need product help? Call <a href="tel:2488432011">248-843-2011</a>.</p>
	<?php
}
add_action( 'woocommerce_after_add_to_cart_form', 'rhn_phase_two_product_help_line', 12 );

/**
 * Render directions/warnings only when verified content exists.
 *
 * The staging content process may populate this field after source review.
 */
function rhn_phase_two_product_directions_warnings() {
	if ( ! is_product() ) {
		return;
	}

	$content = get_post_meta( get_the_ID(), '_rhn_directions_warnings', true );
	if ( ! is_string( $content ) || '' === trim( $content ) ) {
		return;
	}
	?>
	<details class="rhn-product-disclosure">
		<summary><?php esc_html_e( 'Directions & Warnings', 'rebekahs-2026' ); ?></summary>
		<div><?php echo wp_kses_post( wpautop( $content ) ); ?></div>
	</details>
	<?php
}
add_action( 'woocommerce_after_single_product_summary', 'rhn_phase_two_product_directions_warnings', 7 );
