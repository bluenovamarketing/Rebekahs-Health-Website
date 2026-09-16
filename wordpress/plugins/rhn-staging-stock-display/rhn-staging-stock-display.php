<?php
/**
 * Plugin Name: RHN Staging Stock Display
 * Description: Removes the duplicate simple-product stock label in the approved staging template.
 * Version: 1.0.0
 * Author: Blue Nova Marketing
 */
defined( 'ABSPATH' ) || exit;

/** The custom summary already displays stock; retain all stock logic and other contexts. */
function rhn_staging_single_stock_label( $html, $product ) {
	$host = strtolower( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '' );
	if ( 'wordpress-1651482-6655800.cloudwaysapps.com' !== $host
		|| 'rebekahs-2026' !== get_stylesheet()
		|| ! doing_action( 'woocommerce_simple_add_to_cart' )
		|| ! $product || ! $product->is_type( 'simple' ) ) {
		return $html;
	}
	return '';
}
add_filter( 'woocommerce_get_stock_html', 'rhn_staging_single_stock_label', 20, 2 );
