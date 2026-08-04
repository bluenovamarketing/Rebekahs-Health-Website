<?php
/**
 * Plugin Name: Blue Nova WP Memory Verifier
 * Description: Self-removing staging-only memory verification endpoint.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function() {
	if ( ! isset( $_GET['bn_memory_verify'] ) || '7a3f9c' !== $_GET['bn_memory_verify'] ) {
		return;
	}
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Cache-Control: no-store' );
	$result = array(
		'memory_limit' => ini_get( 'memory_limit' ),
		'wp_memory_limit' => defined( 'WP_MEMORY_LIMIT' ) ? WP_MEMORY_LIMIT : null,
		'wp_max_memory_limit' => defined( 'WP_MAX_MEMORY_LIMIT' ) ? WP_MAX_MEMORY_LIMIT : null,
	);
	@unlink( __FILE__ );
	echo wp_json_encode( $result );
	exit;
}, -9999 );

