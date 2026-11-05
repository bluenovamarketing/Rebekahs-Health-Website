<?php
/**
 * Plugin Name: Blue Nova Phase Two Staging Installer v1.11
 * Description: Corrects approved product-page rendering and removes the obsolete catalog-test notice on Rebekah's isolated staging store.
 * Version: 1.11.0
 * Author: Blue Nova Marketing
 */

defined( 'ABSPATH' ) || exit;

/** Install the product-page correction only on the intended staging host. */
function blue_nova_phase_two_install_v1_11() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) : '';
	if ( 'wordpress-1651482-6655800.cloudwaysapps.com' !== $host || 'rebekahs-2026' !== get_stylesheet() ) {
		wp_die( esc_html__( 'This installer is restricted to Rebekah’s approved Cloudways staging site and active custom theme.', 'blue-nova-phase-two-installer' ) );
	}
	if ( get_option( 'blue_nova_phase_two_staging_patch_v1_11' ) ) {
		return;
	}

	$root = plugin_dir_path( __FILE__ );
	$files = array(
		array(
			'source' => $root . 'payload/theme/inc/phase-two-ecommerce.php',
			'target' => trailingslashit( get_stylesheet_directory() ) . 'inc/phase-two-ecommerce.php',
			'backup' => 'theme/inc/phase-two-ecommerce.php',
		),
		array(
			'source' => $root . 'payload/plugins/rhn-catalog-presentation/preview-samples.php',
			'target' => trailingslashit( WP_PLUGIN_DIR ) . 'rhn-catalog-presentation/preview-samples.php',
			'backup' => 'plugins/rhn-catalog-presentation/preview-samples.php',
		),
	);
	$backup_root = trailingslashit( WP_CONTENT_DIR ) . 'uploads/blue-nova-phase-two-backups/v1.11-' . gmdate( 'Ymd-His' ) . '/';

	foreach ( $files as $file ) {
		if ( ! is_readable( $file['source'] ) || ! is_readable( $file['target'] ) ) {
			wp_die( esc_html__( 'A required payload or current staging file is unavailable. Nothing was changed.', 'blue-nova-phase-two-installer' ) );
		}
		$backup = $backup_root . $file['backup'];
		if ( ! wp_mkdir_p( dirname( $backup ) ) || ! copy( $file['target'], $backup ) ) {
			wp_die( esc_html__( 'The current staging files could not be backed up. Nothing was changed.', 'blue-nova-phase-two-installer' ) );
		}
	}

	$installed = array();
	foreach ( $files as $file ) {
		$backup = $backup_root . $file['backup'];
		if ( ! copy( $file['source'], $file['target'] ) || hash_file( 'sha256', $file['source'] ) !== hash_file( 'sha256', $file['target'] ) ) {
			foreach ( $installed as $completed ) {
				copy( $backup_root . $completed['backup'], $completed['target'] );
			}
			copy( $backup, $file['target'] );
			wp_die( esc_html__( 'The product-page correction could not be verified. The previous staging files were restored.', 'blue-nova-phase-two-installer' ) );
		}
		$installed[] = $file;
	}

	update_option(
		'blue_nova_phase_two_staging_patch_v1_11',
		array(
			'installed_at_utc' => gmdate( 'c' ),
			'host'             => $host,
			'backup_root'      => $backup_root,
			'files'            => wp_list_pluck( $files, 'backup' ),
			'change'           => 'approved-product-rendering-and-test-notice-removal',
			'revel_changes'    => 'none',
			'catalog_changes'  => 'none',
			'payment_changes'  => 'none',
			'live_changes'     => 'none',
		),
		false
	);
	wp_cache_flush();
}
register_activation_hook( __FILE__, 'blue_nova_phase_two_install_v1_11' );

/** Leave a non-sensitive administrator record of the staging-only correction. */
function blue_nova_phase_two_v1_11_admin_notice() {
	if ( ! get_option( 'blue_nova_phase_two_staging_patch_v1_11' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible"><p><strong>Phase Two staging v1.11 installed.</strong> Approved legacy-matched products now use current catalog content, and the obsolete catalog-test notice is no longer rendered. No Revel, catalog, price, inventory, payment, or production-site value was changed.</p></div>
	<?php
}
add_action( 'admin_notices', 'blue_nova_phase_two_v1_11_admin_notice' );
