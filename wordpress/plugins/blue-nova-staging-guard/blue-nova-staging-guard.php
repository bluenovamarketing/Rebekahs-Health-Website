<?php
/**
 * Plugin Name: Blue Nova Staging Guard
 * Description: Prevents staging email, form delivery, payments, webhooks, indexing, and customer-facing scheduled actions.
 * Version: 1.0.0
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function rhn_is_cloudways_staging() {
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) : '';
	return false !== strpos( $host, 'cloudwaysapps.com' ) || ( defined( 'WP_ENVIRONMENT_TYPE' ) && 'staging' === WP_ENVIRONMENT_TYPE );
}

if ( ! rhn_is_cloudways_staging() ) {
	return;
}

add_action( 'send_headers', function() {
	header( 'X-Robots-Tag: noindex, nofollow, noarchive', true );
} );
add_filter( 'wp_robots', function( $robots ) {
	$robots['noindex'] = true;
	$robots['nofollow'] = true;
	$robots['noarchive'] = true;
	return $robots;
} );
add_filter( 'pre_wp_mail', '__return_true', PHP_INT_MAX );
add_filter( 'woocommerce_available_payment_gateways', '__return_empty_array', PHP_INT_MAX );
add_filter( 'woocommerce_webhook_should_deliver', '__return_false', PHP_INT_MAX );
add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );
add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

// The production live-chat widget is an external service. Keep it off staging
// so tests cannot create real conversations or obscure visual proofing.
add_action( 'wp_enqueue_scripts', function() {
	wp_dequeue_script( 'web-chat-widget-script' );
	wp_deregister_script( 'web-chat-widget-script' );
}, PHP_INT_MAX );

add_filter( 'forminator_custom_form_submit_errors', function( $errors ) {
	$errors[] = __( 'This is a private staging site. Form delivery is disabled during the build.', 'blue-nova-staging-guard' );
	return $errors;
}, PHP_INT_MAX );
add_action( 'admin_notices', function() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<div class="notice notice-warning"><p><strong>Staging Guard is active:</strong> email, form delivery, payments, webhooks, and indexing are disabled.</p></div>';
	}
} );
