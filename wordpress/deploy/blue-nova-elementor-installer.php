<?php
/**
 * Plugin Name: Blue Nova One-Time Elementor Installer
 * Description: Verifies, installs, and activates the approved Elementor package on Cloudways staging, then removes itself.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) : '';
	if ( false === strpos( $host, 'cloudwaysapps.com' ) || get_option( 'blue_nova_elementor_420_deployed' ) ) {
		return;
	}

	$application_root = dirname( rtrim( ABSPATH, '/\\' ) );
	$archive          = $application_root . '/private_html/elementor.4.2.0.zip';
	$status_file      = $application_root . '/private_html/elementor-deploy-status.json';
	$expected_hash    = '687eaa1e41178bd8bc049dfc5bbefea46941f335c806fc2bd090ea79639308e0';
	$status           = array( 'success' => false, 'version' => '4.2.0' );

	if ( ! is_readable( $archive ) || $expected_hash !== hash_file( 'sha256', $archive ) ) {
		$status['error'] = 'Archive missing or checksum mismatch.';
		file_put_contents( $status_file, wp_json_encode( $status ) );
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	WP_Filesystem();
	$result = unzip_file( $archive, WP_PLUGIN_DIR );
	if ( is_wp_error( $result ) ) {
		$status['error'] = $result->get_error_message();
		file_put_contents( $status_file, wp_json_encode( $status ) );
		return;
	}

	wp_clean_plugins_cache( true );
	$result = activate_plugin( 'elementor/elementor.php' );
	if ( is_wp_error( $result ) ) {
		$status['error'] = $result->get_error_message();
		file_put_contents( $status_file, wp_json_encode( $status ) );
		return;
	}

	update_option( 'blue_nova_elementor_420_deployed', gmdate( 'c' ), false );
	$status['success'] = true;
	file_put_contents( $status_file, wp_json_encode( $status ) );
	@unlink( $archive );
	@unlink( __FILE__ );
}, 1 );
