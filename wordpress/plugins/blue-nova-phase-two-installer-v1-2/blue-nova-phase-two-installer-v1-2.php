<?php
/**
 * Plugin Name: Blue Nova Phase Two Staging Installer v1.2
 * Description: Adds the client-approved homepage ecommerce integration to Rebekah's Cloudways staging site with automatic backup and rollback.
 * Version: 1.2.0
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Apply the versioned homepage patch only to the intended Cloudways staging theme. */
function blue_nova_phase_two_install_v1_2() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) : '';
	if ( false === strpos( $host, 'cloudwaysapps.com' ) ) {
		wp_die( esc_html__( 'This installer is restricted to the Cloudways staging hostname.', 'blue-nova-phase-two-installer' ) );
	}

	if ( 'rebekahs-2026' !== get_stylesheet() ) {
		wp_die( esc_html__( 'The expected Rebekah\'s 2026 theme is not active.', 'blue-nova-phase-two-installer' ) );
	}

	$payload_root = plugin_dir_path( __FILE__ ) . 'payload/';
	$theme_root   = trailingslashit( get_stylesheet_directory() );
	$backup_root  = trailingslashit( WP_CONTENT_DIR ) . 'uploads/blue-nova-phase-two-backups/v1.2-' . gmdate( 'Ymd-His' ) . '/';
	$targets      = array(
		'template-parts/pages/home.php'                   => $theme_root . 'template-parts/pages/home.php',
		'assets/css/components/phase-two-commerce.css'   => $theme_root . 'assets/css/components/phase-two-commerce.css',
		'assets/img/ecommerce-shopping-paths-v1.1.webp'  => $theme_root . 'assets/img/ecommerce-shopping-paths-v1.1.webp',
	);
	$backups      = array();
	$written      = array();

	foreach ( $targets as $relative => $target ) {
		$source = $payload_root . $relative;
		if ( ! is_readable( $source ) ) {
			wp_die( esc_html( sprintf( 'Installer payload is incomplete: %s', $relative ) ) );
		}

		if ( file_exists( $target ) ) {
			$backup = $backup_root . $relative;
			if ( ! wp_mkdir_p( dirname( $backup ) ) || ! copy( $target, $backup ) ) {
				wp_die( esc_html( sprintf( 'Could not back up %s. No patch files were written.', $relative ) ) );
			}
			$backups[ $target ] = $backup;
		} else {
			$backups[ $target ] = '';
		}
	}

	foreach ( $targets as $relative => $target ) {
		$source = $payload_root . $relative;
		if ( ! wp_mkdir_p( dirname( $target ) ) || ! copy( $source, $target ) ) {
			foreach ( array_reverse( $written ) as $written_target ) {
				if ( ! empty( $backups[ $written_target ] ) ) {
					copy( $backups[ $written_target ], $written_target );
				} elseif ( file_exists( $written_target ) ) {
					unlink( $written_target );
				}
			}
			wp_die( esc_html( sprintf( 'Patch failed while writing %s. Previously written files were rolled back.', $relative ) ) );
		}
		$written[] = $target;
	}

	update_option(
		'blue_nova_phase_two_staging_patch_v1_2',
		array(
			'installed_at_utc' => gmdate( 'c' ),
			'host'             => $host,
			'backup_root'      => $backup_root,
			'files'            => array_keys( $targets ),
		),
		false
	);
}
register_activation_hook( __FILE__, 'blue_nova_phase_two_install_v1_2' );
