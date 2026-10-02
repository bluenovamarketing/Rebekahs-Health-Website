<?php
/**
 * Plugin Name: RHN Fullscript Live Deployment v1.1
 * Description: Deploys the approved Fullscript hero and commerce asset correction to Rebekah's live site with verified rollback backups.
 * Version: 1.1.0
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Apply the exact staging-approved Fullscript revision to the intended live site. */
function rhn_fullscript_live_v1_1_activate() {
	$expected_host  = 'rebekahspureliving.com';
	$current_host   = strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );
	$expected_theme = 'rebekahs-2026';

	if ( $expected_host !== $current_host || $expected_theme !== get_stylesheet() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( esc_html__( 'This deployment is restricted to Rebekah’s approved live site and active custom theme.', 'rhn-fullscript-live' ) );
	}

	if ( get_option( 'rhn_fullscript_live_v1_1' ) ) {
		return;
	}

	$source_root = trailingslashit( __DIR__ ) . 'payload/theme/';
	$theme_root  = trailingslashit( get_stylesheet_directory() );
	$files       = array(
		'template-parts/pages/shop-fullscript.php',
		'assets/output/shop-fullscript/fullscript-hero-supplements-branded-v2.png',
		'inc/phase-two-ecommerce.php',
	);
	$uploads     = wp_upload_dir();
	$backup_root = trailingslashit( dirname( $uploads['basedir'] ) ) . 'blue-nova-backups/fullscript-live-v1-1-' . gmdate( 'Ymd-His' ) . '/';
	$backups     = array();
	$created     = array();

	if ( ! wp_mkdir_p( $backup_root ) ) {
		wp_die( esc_html__( 'The live Fullscript rollback directory could not be created.', 'rhn-fullscript-live' ) );
	}

	foreach ( $files as $relative_path ) {
		$source = $source_root . $relative_path;
		$target = $theme_root . $relative_path;

		if ( ! is_readable( $source ) || ! hash_file( 'sha256', $source ) ) {
			wp_die( esc_html( 'Deployment payload is incomplete: ' . $relative_path ) );
		}

		if ( file_exists( $target ) ) {
			$backup = $backup_root . $relative_path;
			if ( ! wp_mkdir_p( dirname( $backup ) ) || ! copy( $target, $backup ) || hash_file( 'sha256', $target ) !== hash_file( 'sha256', $backup ) ) {
				wp_die( esc_html( 'Could not create a verified rollback copy for: ' . $relative_path ) );
			}
			$backups[ $relative_path ] = $backup;
		} else {
			$created[] = $relative_path;
		}
	}

	foreach ( $files as $relative_path ) {
		$source = $source_root . $relative_path;
		$target = $theme_root . $relative_path;

		if ( ! wp_mkdir_p( dirname( $target ) ) || ! copy( $source, $target ) || hash_file( 'sha256', $source ) !== hash_file( 'sha256', $target ) ) {
			foreach ( $backups as $restore_relative => $backup ) {
				copy( $backup, $theme_root . $restore_relative );
			}
			foreach ( $created as $created_relative ) {
				$created_target = $theme_root . $created_relative;
				if ( file_exists( $created_target ) ) {
					unlink( $created_target );
				}
			}
			wp_die( esc_html( 'The live Fullscript deployment failed and the prior files were restored. Failed file: ' . $relative_path ) );
		}
	}

	$hashes = array();
	foreach ( $files as $relative_path ) {
		$hashes[ $relative_path ] = hash_file( 'sha256', $source_root . $relative_path );
	}

	update_option(
		'rhn_fullscript_live_v1_1',
		array(
			'applied_at_utc' => gmdate( 'c' ),
			'backup_root'    => $backup_root,
			'files'          => $files,
			'hashes'         => $hashes,
		),
		false
	);
}
register_activation_hook( __FILE__, 'rhn_fullscript_live_v1_1_activate' );

/** Leave an administrator-visible deployment record. */
function rhn_fullscript_live_v1_1_notice() {
	if ( ! current_user_can( 'manage_options' ) || ! get_option( 'rhn_fullscript_live_v1_1' ) ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible"><p><strong>Fullscript live deployment v1.1 installed.</strong> The approved branded hero and commerce asset correction are active. Verified rollback copies were preserved.</p></div>
	<?php
}
add_action( 'admin_notices', 'rhn_fullscript_live_v1_1_notice' );
