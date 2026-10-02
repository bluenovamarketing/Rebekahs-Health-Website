<?php
/**
 * Plugin Name: RHN Fullscript Hero Staging Installer v1.1
 * Description: Applies the approved branded Fullscript bottle hero to Rebekah's Cloudways staging site with a rollback backup.
 * Version: 1.1.0
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Apply the isolated Fullscript hero revision only to the intended staging site. */
function rhn_fullscript_hero_v1_1_activate() {
	$expected_host  = 'wordpress-1651482-6655800.cloudwaysapps.com';
	$current_host   = strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );
	$expected_theme = 'rebekahs-2026';

	if ( $expected_host !== $current_host || $expected_theme !== get_stylesheet() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( esc_html__( 'This installer is restricted to Rebekah’s approved Cloudways staging site and active custom theme.', 'rhn-fullscript-hero' ) );
	}

	if ( get_option( 'rhn_fullscript_hero_staging_v1_1' ) ) {
		return;
	}

	$source_root = trailingslashit( __DIR__ ) . 'payload/theme/';
	$theme_root  = trailingslashit( get_stylesheet_directory() );
	$files       = array(
		'template-parts/pages/shop-fullscript.php',
		'assets/output/shop-fullscript/fullscript-hero-supplements-branded-v2.png',
	);
	$uploads     = wp_upload_dir();
	$backup_root = trailingslashit( dirname( $uploads['basedir'] ) ) . 'blue-nova-backups/fullscript-hero-v1-1-' . gmdate( 'Ymd-His' ) . '/';
	$backed_up   = array();

	if ( ! wp_mkdir_p( $backup_root ) ) {
		wp_die( esc_html__( 'The Fullscript hero backup directory could not be created.', 'rhn-fullscript-hero' ) );
	}

	foreach ( $files as $relative_path ) {
		$source = $source_root . $relative_path;
		$target = $theme_root . $relative_path;

		if ( ! is_readable( $source ) ) {
			wp_die( esc_html( 'Installer payload is incomplete: ' . $relative_path ) );
		}

		if ( file_exists( $target ) ) {
			$backup = $backup_root . $relative_path;
			if ( ! wp_mkdir_p( dirname( $backup ) ) || ! copy( $target, $backup ) ) {
				wp_die( esc_html( 'Could not back up: ' . $relative_path ) );
			}
			$backed_up[ $relative_path ] = $backup;
		}
	}

	foreach ( $files as $relative_path ) {
		$source = $source_root . $relative_path;
		$target = $theme_root . $relative_path;

		if ( ! wp_mkdir_p( dirname( $target ) ) || ! copy( $source, $target ) || hash_file( 'sha256', $source ) !== hash_file( 'sha256', $target ) ) {
			foreach ( $backed_up as $restore_relative => $backup ) {
				copy( $backup, $theme_root . $restore_relative );
			}
			wp_die( esc_html( 'The Fullscript hero update failed and the prior template was restored. Failed file: ' . $relative_path ) );
		}
	}

	update_option(
		'rhn_fullscript_hero_staging_v1_1',
		array(
			'applied_at_utc' => gmdate( 'c' ),
			'backup_root'    => $backup_root,
			'files'          => $files,
		),
		false
	);
}
register_activation_hook( __FILE__, 'rhn_fullscript_hero_v1_1_activate' );

/** Leave a clear staging-only administrator record after activation. */
function rhn_fullscript_hero_v1_1_notice() {
	if ( ! current_user_can( 'manage_options' ) || ! get_option( 'rhn_fullscript_hero_staging_v1_1' ) ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible"><p><strong>Fullscript hero staging v1.1 installed.</strong> The Shop Fullscript hero now uses the approved branded-bottle composite. The original image remains in the theme and the previous template was backed up.</p></div>
	<?php
}
add_action( 'admin_notices', 'rhn_fullscript_hero_v1_1_notice' );
