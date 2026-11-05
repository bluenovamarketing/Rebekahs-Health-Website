<?php
/**
 * Plugin Name: RHN ElliMD Retirement Deployment
 * Description: One-time, checksum-guarded removal of the retired homepage injection link.
 * Version: 1.0.1
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function rhn_ellimd_retirement_activate() {
	$allowed_hosts = array(
		'rebekahspureliving.com',
		'www.rebekahspureliving.com',
		'wordpress-1651482-6655800.cloudwaysapps.com',
	);
	$host = strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) );
	if ( ! in_array( $host, $allowed_hosts, true ) ) {
		wp_die( esc_html( 'RHN ElliMD Retirement Deployment refused this hostname.' ) );
	}

	$target        = get_theme_root() . '/rebekahs-2026/assets/js/pages/home-sections.js';
	$backup        = $target . '.before-ellimd-retirement-20261006';
	$expected_old  = '656e54c53edec0db899d1fddf216d82116bcb73c2d75d9b8e2da32cadd363266';
	$expected_new  = '9b8cfeb6154dc57061b454f1c0d6b63f5eb41de691982c9ceb878e7f6919790a';

	if ( ! is_readable( $target ) || ! is_writable( $target ) ) {
		wp_die( esc_html( 'The homepage JavaScript file is not readable and writable.' ) );
	}

	$current_hash = strtolower( (string) hash_file( 'sha256', $target ) );
	if ( $expected_new === $current_hash ) {
		update_option( 'rhn_ellimd_retirement_result', array( 'status' => 'already-current', 'host' => $host, 'time' => gmdate( 'c' ) ), false );
		return;
	}
	if ( $expected_old !== $current_hash ) {
		wp_die( esc_html( 'The homepage JavaScript checksum does not match the approved source; no file was changed.' ) );
	}

	if ( file_exists( $backup ) ) {
		if ( $expected_old !== strtolower( (string) hash_file( 'sha256', $backup ) ) ) {
			wp_die( esc_html( 'The existing rollback backup has an unexpected checksum; no file was changed.' ) );
		}
	} elseif ( ! copy( $target, $backup ) || $expected_old !== strtolower( (string) hash_file( 'sha256', $backup ) ) ) {
		wp_die( esc_html( 'Could not create and verify the rollback backup; no file was changed.' ) );
	}

	$needle = <<<'JS'
		const box = section?.querySelector( '.partner-box' );
		if ( box && ! box.querySelector( '[data-injections]' ) ) {
			const link = document.createElement( 'a' );
			link.className = 'partner';
			link.href = '/peptides-injectables/';
			link.dataset.injections = 'true';
			link.innerHTML = '<span>Shop Injections</span><span aria-hidden="true">→</span>';
			box.appendChild( link );
		}
JS;

	$source            = file_get_contents( $target );
	$line_ending       = false !== strpos( $source, "\r\n" ) ? "\r\n" : "\n";
	$source_normalized = str_replace( "\r\n", "\n", $source );
	$needle            = str_replace( "\r\n", "\n", $needle );
	$needle           .= "\n";
	if ( 1 !== substr_count( $source_normalized, $needle ) ) {
		wp_die( esc_html( 'The approved homepage injection block was not found exactly once; no file was changed.' ) );
	}
	$updated_normalized = str_replace( $needle, '', $source_normalized );
	$updated            = "\r\n" === $line_ending ? str_replace( "\n", "\r\n", $updated_normalized ) : $updated_normalized;
	if ( $expected_new !== strtolower( hash( 'sha256', $updated ) ) ) {
		wp_die( esc_html( 'The prepared homepage JavaScript did not match the approved checksum; no file was changed.' ) );
	}

	if ( false === file_put_contents( $target, $updated, LOCK_EX ) || $expected_new !== strtolower( (string) hash_file( 'sha256', $target ) ) ) {
		copy( $backup, $target );
		wp_die( esc_html( 'The homepage JavaScript update failed verification and the rollback copy was restored.' ) );
	}
	clearstatcache( true, $target );
	update_option(
		'rhn_ellimd_retirement_result',
		array(
			'status'      => 'deployed',
			'host'        => $host,
			'time'        => gmdate( 'c' ),
			'backup_path' => $backup,
			'new_hash'    => $expected_new,
		),
		false
	);
}
register_activation_hook( __FILE__, 'rhn_ellimd_retirement_activate' );
