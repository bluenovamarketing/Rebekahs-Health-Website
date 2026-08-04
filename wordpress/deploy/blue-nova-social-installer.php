<?php
/**
 * Plugin Name: Blue Nova One-Time Social Feed Installer
 * Description: Verifies, installs, and activates the approved free Instagram and TikTok feed plugins on Cloudways staging, then removes itself.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) : '';
	if ( false === strpos( $host, 'cloudwaysapps.com' ) || get_option( 'blue_nova_social_feeds_deployed' ) ) {
		return;
	}

	$application_root = dirname( rtrim( ABSPATH, '/\\' ) );
	$status_file      = $application_root . '/private_html/social-feed-deploy-status.json';
	$packages         = array(
		array(
			'name'       => 'Instagram Feed',
			'version'    => '6.11.3',
			'archive'    => $application_root . '/private_html/instagram-feed.6.11.3.zip',
			'hash'       => '905ba3b5ecb9872ed729f314dd1f09c2e3191c5c51fde7cb5406f7cb1b5ab35c',
			'plugin_file'=> 'instagram-feed/instagram-feed.php',
		),
		array(
			'name'       => 'TikTok Feed',
			'version'    => '1.6.1',
			'archive'    => $application_root . '/private_html/feeds-for-tiktok.1.6.1.zip',
			'hash'       => '3885fab3acfce53cb404d8e060473b6d7a4026734d11650d18fef13fff6c6057',
			'plugin_file'=> 'feeds-for-tiktok/feeds-for-tiktok.php',
		),
	);
	$status = array( 'success' => false, 'plugins' => array() );

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	WP_Filesystem();

	foreach ( $packages as $package ) {
		$item = array( 'name' => $package['name'], 'version' => $package['version'], 'success' => false );
		if ( ! is_readable( $package['archive'] ) || $package['hash'] !== hash_file( 'sha256', $package['archive'] ) ) {
			$item['error'] = 'Archive missing or checksum mismatch.';
			$status['plugins'][] = $item;
			file_put_contents( $status_file, wp_json_encode( $status ) );
			return;
		}

		$result = unzip_file( $package['archive'], WP_PLUGIN_DIR );
		if ( is_wp_error( $result ) ) {
			$item['error'] = $result->get_error_message();
			$status['plugins'][] = $item;
			file_put_contents( $status_file, wp_json_encode( $status ) );
			return;
		}

		wp_clean_plugins_cache( true );
		$result = activate_plugin( $package['plugin_file'] );
		if ( is_wp_error( $result ) ) {
			$item['error'] = $result->get_error_message();
			$status['plugins'][] = $item;
			file_put_contents( $status_file, wp_json_encode( $status ) );
			return;
		}

		$item['success'] = true;
		$status['plugins'][] = $item;
		@unlink( $package['archive'] );
	}

	update_option( 'blue_nova_social_feeds_deployed', gmdate( 'c' ), false );
	$status['success'] = true;
	file_put_contents( $status_file, wp_json_encode( $status ) );
	@unlink( __FILE__ );
}, 1 );

