<?php
defined( 'ABSPATH' ) || exit;

function rhn_fr_stage_store_prefix() {
	return 'rhn_fr_stage_preview_';
}

function rhn_fr_stage_store_key( $preview_id, $part ) {
	$preview_id = strtolower( preg_replace( '/[^a-f0-9]/', '', (string) $preview_id ) );
	$part       = strtolower( preg_replace( '/[^a-z0-9_-]/', '', (string) $part ) );
	if ( 32 !== strlen( $preview_id ) || '' === $part ) {
		throw new RuntimeException( 'Invalid staging preview storage identity.' );
	}
	return rhn_fr_stage_store_prefix() . $preview_id . '_' . $part;
}

function rhn_fr_stage_store_encode( $value ) {
	return maybe_serialize( $value );
}

function rhn_fr_stage_store_decode( $value ) {
	return maybe_unserialize( $value );
}

function rhn_fr_stage_store_create( $preview_id, $part, $value ) {
	global $wpdb;
	$key    = rhn_fr_stage_store_key( $preview_id, $part );
	$stored = rhn_fr_stage_store_encode( $value );
	$result = $wpdb->query(
		$wpdb->prepare(
			"INSERT IGNORE INTO {$wpdb->options} (option_name, option_value, autoload) VALUES (%s, %s, 'no')",
			$key,
			$stored
		)
	);
	wp_cache_delete( $key, 'options' );
	return 1 === (int) $result;
}

function rhn_fr_stage_store_get( $preview_id, $part ) {
	global $wpdb;
	$key = rhn_fr_stage_store_key( $preview_id, $part );
	$row = $wpdb->get_var( $wpdb->prepare( "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s", $key ) );
	return null === $row ? null : rhn_fr_stage_store_decode( $row );
}

function rhn_fr_stage_store_cas( $preview_id, $part, $expected, $replacement ) {
	global $wpdb;
	$key      = rhn_fr_stage_store_key( $preview_id, $part );
	$old      = rhn_fr_stage_store_encode( $expected );
	$new      = rhn_fr_stage_store_encode( $replacement );
	$affected = $wpdb->query(
		$wpdb->prepare(
			"UPDATE {$wpdb->options} SET option_value = %s, autoload = 'no' WHERE option_name = %s AND option_value = %s",
			$new,
			$key,
			$old
		)
	);
	wp_cache_delete( $key, 'options' );
	return 1 === (int) $affected;
}

function rhn_fr_stage_store_delete_exact( $preview_id, $part, $expected ) {
	global $wpdb;
	$key      = rhn_fr_stage_store_key( $preview_id, $part );
	$old      = rhn_fr_stage_store_encode( $expected );
	$affected = $wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name = %s AND option_value = %s",
			$key,
			$old
		)
	);
	wp_cache_delete( $key, 'options' );
	return 1 === (int) $affected;
}

function rhn_fr_stage_cleanup_preview( $preview_id ) {
	$removed = array();
	foreach ( array( 'manifest', 'report' ) as $part ) {
		$value = rhn_fr_stage_store_get( $preview_id, $part );
		if ( null === $value ) {
			continue;
		}
		$removed[ $part ] = rhn_fr_stage_store_delete_exact( $preview_id, $part, $value );
	}
	return $removed;
}
