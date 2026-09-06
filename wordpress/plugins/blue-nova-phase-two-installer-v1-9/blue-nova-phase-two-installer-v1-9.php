<?php
/**
 * Plugin Name: Blue Nova Phase Two Staging Installer v1.9
 * Description: Installs the complete approved Product Page v1.7 no-image gallery, preserves the corrected account layout, and quarantines every pre-connection legacy product.
 * Version: 1.9.0
 * Author: Blue Nova Marketing
 */

defined( 'ABSPATH' ) || exit;

/** Prevent WooCommerce from redirecting this controlled activation into onboarding. */
add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true' );

/** Copy a payload atomically, restoring previously written files on failure. */
function blue_nova_phase_two_v1_9_install_theme_files( $backup_root ) {
	$payload_root = plugin_dir_path( __FILE__ ) . 'payload/theme/';
	$theme_root   = trailingslashit( get_stylesheet_directory() );
	$relatives    = array(
		'functions.php',
		'header.php',
		'inc/phase-two-ecommerce.php',
		'assets/css/global-chrome.css',
		'assets/css/components/phase-two-commerce.css',
		'assets/js/components/phase-two-commerce.js',
		'assets/img/wellness-goal-card-strip-v1.1.webp',
		'woocommerce/archive-product.php',
		'woocommerce/content-product.php',
		'woocommerce/single-product.php',
		'woocommerce/content-single-product.php',
		'template-parts/pages/cart.php',
		'template-parts/pages/checkout.php',
		'template-parts/pages/my-account.php',
	);
	$backups = array();
	$written = array();

	foreach ( $relatives as $relative ) {
		$source = $payload_root . $relative;
		$target = $theme_root . $relative;
		if ( ! is_readable( $source ) ) {
			wp_die( esc_html( sprintf( 'Installer payload is incomplete: %s', $relative ) ) );
		}
		if ( file_exists( $target ) ) {
			$backup = trailingslashit( $backup_root ) . 'theme/' . $relative;
			if ( ! wp_mkdir_p( dirname( $backup ) ) || ! copy( $target, $backup ) ) {
				wp_die( esc_html( sprintf( 'Could not back up %s. No theme files were written.', $relative ) ) );
			}
			$backups[ $target ] = $backup;
		} else {
			$backups[ $target ] = '';
		}
	}

	foreach ( $relatives as $relative ) {
		$source = $payload_root . $relative;
		$target = $theme_root . $relative;
		if ( ! wp_mkdir_p( dirname( $target ) ) || ! copy( $source, $target ) ) {
			foreach ( array_reverse( $written ) as $written_target ) {
				if ( $backups[ $written_target ] ) {
					copy( $backups[ $written_target ], $written_target );
				} elseif ( file_exists( $written_target ) ) {
					unlink( $written_target );
				}
			}
			wp_die( esc_html( sprintf( 'Patch failed while writing %s. Written theme files were rolled back.', $relative ) ) );
		}
		$written[] = $target;
	}

	return $relatives;
}

/** Activate WooCommerce core only; no payment, connector, or optional extension. */
function blue_nova_phase_two_v1_9_activate_woocommerce() {
	$plugin = 'woocommerce/woocommerce.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( is_plugin_active( $plugin ) ) {
		return 'already-active';
	}
	if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
		wp_die( esc_html__( 'WooCommerce core is not installed on this staging site.', 'blue-nova-phase-two-installer' ) );
	}

	$result = activate_plugin( $plugin, '', false, true );
	if ( is_wp_error( $result ) ) {
		wp_die( esc_html( 'WooCommerce activation failed: ' . $result->get_error_message() ) );
	}
	delete_transient( '_wc_activation_redirect' );
	return 'activated';
}

/** Preserve a durable inventory and move all legacy active products to Draft. */
function blue_nova_phase_two_v1_9_isolate_legacy_products( $backup_root ) {
	global $wpdb;
	$rows = $wpdb->get_results(
		"SELECT ID, post_title, post_name, post_status, post_date, post_modified FROM {$wpdb->posts} WHERE post_type = 'product' ORDER BY ID ASC",
		ARRAY_A
	);
	$inventory = array();

	foreach ( $rows as $row ) {
		$row['backorders'] = get_post_meta( (int) $row['ID'], '_backorders', true );
		$inventory[]       = $row;
	}

	$inventory_path = trailingslashit( $backup_root ) . 'legacy-product-inventory.json';
	if ( ! wp_mkdir_p( dirname( $inventory_path ) ) || false === file_put_contents( $inventory_path, wp_json_encode( $inventory, JSON_PRETTY_PRINT ) ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		wp_die( esc_html__( 'Could not save the legacy product inventory. No product status was changed.', 'blue-nova-phase-two-installer' ) );
	}

	$changed = array();
	foreach ( $inventory as $product_row ) {
		$id = (int) $product_row['ID'];
		update_post_meta( $id, '_rhn_phase_two_legacy_quarantined', 'yes' );
		if ( in_array( $product_row['post_status'], array( 'publish', 'private', 'pending', 'future' ), true ) ) {
			$result = wp_update_post( array( 'ID' => $id, 'post_status' => 'draft' ), true );
			if ( is_wp_error( $result ) ) {
				wp_die( esc_html( sprintf( 'Legacy product %d could not be moved to Draft. Review the saved inventory before retrying.', $id ) ) );
			}
			$changed[] = $id;
		}
		update_post_meta( $id, '_backorders', 'no' );
	}

	return array(
		'inventory_path' => $inventory_path,
		'total'          => count( $inventory ),
		'moved_to_draft' => $changed,
	);
}

/** Disable every known gateway option without activating any gateway extension. */
function blue_nova_phase_two_v1_9_disable_gateways() {
	$gateway_ids = array( 'bacs', 'cheque', 'cod', 'paypal', 'stripe', 'woocommerce_payments', 'nmi', 'clover' );
	foreach ( $gateway_ids as $gateway_id ) {
		$key      = 'woocommerce_' . $gateway_id . '_settings';
		$settings = get_option( $key, array() );
		$settings = is_array( $settings ) ? $settings : array();
		$settings['enabled'] = 'no';
		update_option( $key, $settings, false );
	}
}

/** Configure only decisions already approved for the customer-facing core flow. */
function blue_nova_phase_two_v1_9_configure_core( $backup_root ) {
	$options = array(
		'woocommerce_enable_guest_checkout'              => 'yes',
		'woocommerce_enable_checkout_login_reminder'     => 'yes',
		'woocommerce_enable_signup_and_login_from_checkout' => 'yes',
		'woocommerce_enable_myaccount_registration'      => 'yes',
		'woocommerce_registration_generate_username'     => 'yes',
		'woocommerce_registration_generate_password'     => 'no',
		'woocommerce_enable_reviews'                     => 'no',
		'woocommerce_currency'                           => 'USD',
	);
	$option_backup = array();
	foreach ( $options as $key => $value ) {
		$option_backup[ $key ] = get_option( $key, null );
		update_option( $key, $value, false );
	}

	$page_contents = array();
	foreach ( array( 'cart' => '[woocommerce_cart]', 'checkout' => '[woocommerce_checkout]', 'myaccount' => '[woocommerce_my_account]' ) as $page_key => $shortcode ) {
		$page_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( $page_key ) : 0;
		if ( $page_id > 0 ) {
			$page_contents[ $page_id ] = get_post_field( 'post_content', $page_id );
			wp_update_post( array( 'ID' => $page_id, 'post_content' => $shortcode ) );
		}
	}

	$config_path = trailingslashit( $backup_root ) . 'woocommerce-core-settings.json';
	file_put_contents( // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		$config_path,
		wp_json_encode( array( 'options' => $option_backup, 'page_contents' => $page_contents ), JSON_PRETTY_PRINT )
	);
	blue_nova_phase_two_v1_9_disable_gateways();
	delete_transient( 'wc_products_onsale' );
	if ( function_exists( 'wc_delete_product_transients' ) ) {
		wc_delete_product_transients();
	}

	return array_keys( $page_contents );
}

/** Run the complete approved staging-only installation. */
function blue_nova_phase_two_install_v1_9() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) : '';
	if ( false === strpos( $host, 'cloudwaysapps.com' ) || 'rebekahs-2026' !== get_stylesheet() ) {
		wp_die( esc_html__( 'This installer is restricted to Rebekah’s active custom theme on the Cloudways staging hostname.', 'blue-nova-phase-two-installer' ) );
	}
	if ( get_option( 'blue_nova_phase_two_staging_patch_v1_9' ) ) {
		return;
	}

	$backup_root = trailingslashit( WP_CONTENT_DIR ) . 'uploads/blue-nova-phase-two-backups/v1.9-' . gmdate( 'Ymd-His' ) . '/';
	$files       = blue_nova_phase_two_v1_9_install_theme_files( $backup_root );
	$woo_state   = blue_nova_phase_two_v1_9_activate_woocommerce();
	$products    = blue_nova_phase_two_v1_9_isolate_legacy_products( $backup_root );
	$pages       = blue_nova_phase_two_v1_9_configure_core( $backup_root );

	flush_rewrite_rules();
	update_option(
		'blue_nova_phase_two_staging_patch_v1_9',
		array(
			'installed_at_utc' => gmdate( 'c' ),
			'host'             => $host,
			'backup_root'      => $backup_root,
			'theme_files'      => $files,
			'woocommerce'      => $woo_state,
			'legacy_products'  => $products,
			'commerce_pages'   => $pages,
			'payments'         => 'all-known-gateways-disabled',
			'connections'      => 'none',
		),
		false
	);
}
register_activation_hook( __FILE__, 'blue_nova_phase_two_install_v1_9' );

/** Leave an auditable, non-sensitive completion notice for administrators. */
function blue_nova_phase_two_v1_9_admin_notice() {
	$record = get_option( 'blue_nova_phase_two_staging_patch_v1_9' );
	if ( ! $record || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible"><p><strong>Phase Two staging v1.9 installed.</strong> The complete approved Product Page v1.7 three-view no-image gallery is present, pre-connection products remain quarantined, and payments and external connections remain off.</p></div>
	<?php
}
add_action( 'admin_notices', 'blue_nova_phase_two_v1_9_admin_notice' );
