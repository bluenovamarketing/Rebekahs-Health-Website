<?php
/**
 * Plugin Name: RHN Mobile Shop Improvements
 * Description: Mobile-only catalog navigation improvements requested after launch.
 * Version: 1.0.0
 * Author: Blue Nova Marketing
 */

defined( 'ABSPATH' ) || exit;

/**
 * Limit the enhancement to the public WooCommerce catalog surfaces.
 *
 * @return bool
 */
function rhn_mobile_shop_improvements_applies() {
	if ( is_admin() || ! function_exists( 'is_shop' ) ) {
		return false;
	}

	if ( is_shop() || ( function_exists( 'is_product_category' ) && is_product_category() ) || is_tax( 'product_brand' ) ) {
		return true;
	}

	if ( is_search() ) {
		$post_type = get_query_var( 'post_type' );
		return 'product' === $post_type || ( is_array( $post_type ) && in_array( 'product', $post_type, true ) );
	}

	return false;
}

/** Add a stable QA marker without changing desktop presentation. */
function rhn_mobile_shop_improvements_body_class( $classes ) {
	if ( rhn_mobile_shop_improvements_applies() ) {
		$classes[] = 'rhn-mobile-shop-v1';
	}

	return $classes;
}
add_filter( 'body_class', 'rhn_mobile_shop_improvements_body_class' );

/** Load the isolated mobile assets after the approved Phase Two catalog assets. */
function rhn_mobile_shop_improvements_assets() {
	if ( ! rhn_mobile_shop_improvements_applies() ) {
		return;
	}

	$asset_root = __DIR__ . '/rhn-mobile-shop-improvements';
	$asset_url  = content_url( '/mu-plugins/rhn-mobile-shop-improvements' );
	$css_path   = $asset_root . '/mobile-shop.css';
	$js_path    = $asset_root . '/mobile-shop.js';

	wp_enqueue_style(
		'rhn-mobile-shop-improvements',
		$asset_url . '/mobile-shop.css',
		array( 'rhn-phase-two-commerce' ),
		file_exists( $css_path ) ? filemtime( $css_path ) : '1.0.0'
	);

	wp_enqueue_script(
		'rhn-mobile-shop-improvements',
		$asset_url . '/mobile-shop.js',
		array( 'rhn-phase-two-commerce' ),
		file_exists( $js_path ) ? filemtime( $js_path ) : '1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'rhn_mobile_shop_improvements_assets', 100 );
