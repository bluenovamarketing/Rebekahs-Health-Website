<?php
/**
 * Plugin Name: RHN Commerce Chrome Asset Staging Hotfix v1.1
 * Description: Loads the approved ecommerce utility-row assets on every staging page and preserves a rollback backup.
 * Version: 1.1.0
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Apply the isolated asset-loading correction only to Rebekah's staging site. */
function rhn_commerce_asset_hotfix_v1_1_activate() {
	$expected_host  = 'wordpress-1651482-6655800.cloudwaysapps.com';
	$current_host   = strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );
	$expected_theme = 'rebekahs-2026';

	if ( $expected_host !== $current_host || $expected_theme !== get_stylesheet() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( esc_html__( 'This hotfix is restricted to Rebekah’s approved Cloudways staging site and active custom theme.', 'rhn-commerce-asset-hotfix' ) );
	}

	if ( get_option( 'rhn_commerce_asset_hotfix_staging_v1_1' ) ) {
		return;
	}

	$relative_path = 'inc/phase-two-ecommerce.php';
	$source        = trailingslashit( __DIR__ ) . 'payload/theme/' . $relative_path;
	$theme_root    = trailingslashit( get_stylesheet_directory() );
	$target        = $theme_root . $relative_path;
	$uploads       = wp_upload_dir();
	$backup_root   = trailingslashit( dirname( $uploads['basedir'] ) ) . 'blue-nova-backups/commerce-asset-hotfix-v1-1-' . gmdate( 'Ymd-His' ) . '/';
	$backup        = $backup_root . $relative_path;

	if ( ! is_readable( $source ) ) {
		wp_die( esc_html__( 'The commerce asset hotfix payload is incomplete.', 'rhn-commerce-asset-hotfix' ) );
	}

	if ( ! wp_mkdir_p( dirname( $backup ) ) || ! copy( $target, $backup ) ) {
		wp_die( esc_html__( 'The current ecommerce integration file could not be backed up.', 'rhn-commerce-asset-hotfix' ) );
	}

	if ( ! copy( $source, $target ) || hash_file( 'sha256', $source ) !== hash_file( 'sha256', $target ) ) {
		copy( $backup, $target );
		wp_die( esc_html__( 'The commerce asset hotfix failed and the previous file was restored.', 'rhn-commerce-asset-hotfix' ) );
	}

	update_option(
		'rhn_commerce_asset_hotfix_staging_v1_1',
		array(
			'applied_at_utc' => gmdate( 'c' ),
			'backup_root'    => $backup_root,
			'file'           => $relative_path,
		),
		false
	);
}
register_activation_hook( __FILE__, 'rhn_commerce_asset_hotfix_v1_1_activate' );

/** Leave a clear administrator record after the staging correction. */
function rhn_commerce_asset_hotfix_v1_1_notice() {
	if ( ! current_user_can( 'manage_options' ) || ! get_option( 'rhn_commerce_asset_hotfix_staging_v1_1' ) ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible"><p><strong>Commerce chrome asset staging hotfix v1.1 installed.</strong> The utility-row stylesheet and interaction helper now load on every page while Phase Two ecommerce is enabled. The previous integration file was backed up.</p></div>
	<?php
}
add_action( 'admin_notices', 'rhn_commerce_asset_hotfix_v1_1_notice' );
