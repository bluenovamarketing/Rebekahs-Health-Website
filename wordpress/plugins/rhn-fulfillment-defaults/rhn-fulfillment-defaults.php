<?php
/**
 * Plugin Name: RHN Fulfillment Defaults
 * Description: Adds reversible fulfillment defaults and approved package shortcuts to the WooCommerce Shipping label workflow.
 * Version: 1.0.10
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load only on the WooCommerce order editor.
 *
 * The helper never requests rates, purchases postage, changes order status, or
 * sends customer notifications. Staff can reverse every default before they
 * continue in WooCommerce Shipping.
 *
 * @param string $hook_suffix Current WordPress admin hook.
 * @return void
 */
function rhn_fulfillment_defaults_enqueue( $hook_suffix ) {
	if ( 'woocommerce_page_wc-orders' !== $hook_suffix ) {
		return;
	}

	$script_path = plugin_dir_path( __FILE__ ) . 'assets/fulfillment-defaults.js';
	$style_path  = plugin_dir_path( __FILE__ ) . 'assets/fulfillment-defaults.css';

	wp_enqueue_script(
		'rhn-fulfillment-defaults',
		plugins_url( 'assets/fulfillment-defaults.js', __FILE__ ),
		array(),
		file_exists( $script_path ) ? (string) filemtime( $script_path ) : '1.0.0',
		true
	);

	wp_enqueue_style(
		'rhn-fulfillment-defaults',
		plugins_url( 'assets/fulfillment-defaults.css', __FILE__ ),
		array(),
		file_exists( $style_path ) ? (string) filemtime( $style_path ) : '1.0.0'
	);
}
add_action( 'admin_enqueue_scripts', 'rhn_fulfillment_defaults_enqueue' );

