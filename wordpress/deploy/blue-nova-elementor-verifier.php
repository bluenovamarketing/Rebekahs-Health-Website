<?php
/**
 * Plugin Name: Blue Nova One-Time Elementor Verifier
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) : '';
	if ( false === strpos( $host, 'cloudwaysapps.com' ) ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$plugin_file     = WP_PLUGIN_DIR . '/elementor/elementor.php';
	$application_root = dirname( rtrim( ABSPATH, '/\\' ) );
	$status_file      = $application_root . '/private_html/elementor-active-status.json';
	$data             = file_exists( $plugin_file ) ? get_plugin_data( $plugin_file, false, false ) : array();
	$status            = array(
		'active'  => is_plugin_active( 'elementor/elementor.php' ),
		'exists'  => file_exists( $plugin_file ),
		'version' => isset( $data['Version'] ) ? $data['Version'] : '',
	);
	file_put_contents( $status_file, wp_json_encode( $status ) );
	@unlink( __FILE__ );
}, 2 );
