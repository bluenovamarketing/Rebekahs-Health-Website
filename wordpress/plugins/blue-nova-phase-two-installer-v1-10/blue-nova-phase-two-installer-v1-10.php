<?php
/**
 * Plugin Name: Blue Nova Phase Two Staging Installer v1.10
 * Description: Adds the approved no-PO-box checkout validation to Rebekah's isolated Cloudways staging store.
 * Version: 1.10.0
 * Author: Blue Nova Marketing
 */

defined( 'ABSPATH' ) || exit;

/** Install the validation patch only on Rebekah's intended staging host and theme. */
function blue_nova_phase_two_install_v1_10() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) : '';
	if ( 'wordpress-1651482-6655800.cloudwaysapps.com' !== $host || 'rebekahs-2026' !== get_stylesheet() ) {
		wp_die( esc_html__( 'This installer is restricted to Rebekah’s approved Cloudways staging site and active custom theme.', 'blue-nova-phase-two-installer' ) );
	}

	if ( get_option( 'blue_nova_phase_two_staging_patch_v1_10' ) ) {
		return;
	}

	$source      = plugin_dir_path( __FILE__ ) . 'payload/theme/inc/phase-two-ecommerce.php';
	$target      = trailingslashit( get_stylesheet_directory() ) . 'inc/phase-two-ecommerce.php';
	$backup_root = trailingslashit( WP_CONTENT_DIR ) . 'uploads/blue-nova-phase-two-backups/v1.10-' . gmdate( 'Ymd-His' ) . '/';
	$backup      = $backup_root . 'theme/inc/phase-two-ecommerce.php';

	if ( ! is_readable( $source ) || ! is_readable( $target ) ) {
		wp_die( esc_html__( 'The validation payload or current theme file is unavailable. Nothing was changed.', 'blue-nova-phase-two-installer' ) );
	}
	if ( ! wp_mkdir_p( dirname( $backup ) ) || ! copy( $target, $backup ) ) {
		wp_die( esc_html__( 'The current theme file could not be backed up. Nothing was changed.', 'blue-nova-phase-two-installer' ) );
	}
	if ( ! copy( $source, $target ) ) {
		copy( $backup, $target );
		wp_die( esc_html__( 'The checkout validation patch could not be installed. The previous file was restored.', 'blue-nova-phase-two-installer' ) );
	}

	update_option(
		'blue_nova_phase_two_staging_patch_v1_10',
		array(
			'installed_at_utc' => gmdate( 'c' ),
			'host'             => $host,
			'backup_root'      => $backup_root,
			'theme_file'       => 'inc/phase-two-ecommerce.php',
			'change'           => 'po-box-delivery-validation',
			'revel_changes'    => 'none',
			'payment_changes'  => 'none',
			'live_changes'     => 'none',
		),
		false
	);
}
register_activation_hook( __FILE__, 'blue_nova_phase_two_install_v1_10' );

/** Leave a non-sensitive administrator record of the staging-only patch. */
function blue_nova_phase_two_v1_10_admin_notice() {
	if ( ! get_option( 'blue_nova_phase_two_staging_patch_v1_10' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible"><p><strong>Phase Two staging v1.10 installed.</strong> Checkout now rejects PO-box delivery addresses. No Revel, payment, shipping-rate, live-site, or paid-service setting was changed.</p></div>
	<?php
}
add_action( 'admin_notices', 'blue_nova_phase_two_v1_10_admin_notice' );

