<?php
defined( 'ABSPATH' ) || exit;

/** One lease for every Product Review/Catalog writer. */
function rhn_catalog_writer_lock_key() {
	return 'rhn_catalog_product_review_writer_lock';
}

/** Compare-and-delete one exact lease value so an expired owner cannot erase its replacement. */
function rhn_catalog_writer_lock_delete_if_matches( $expected ) {
	global $wpdb;
	$key     = rhn_catalog_writer_lock_key();
	$deleted = $wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name = %s AND option_value = %s",
			$key,
			maybe_serialize( $expected )
		)
	);
	if ( 1 !== $deleted ) {
		return false;
	}
	wp_cache_delete( $key, 'options' );
	return true;
}

/**
 * Acquire the shared Product Review/Catalog lease.
 *
 * add_option is the atomic compare-and-set. The random token prevents an old
 * process from releasing a lease that was reclaimed after expiry.
 */
function rhn_catalog_writer_lock_acquire( $owner, $ttl = 1800 ) {
	$owner = sanitize_key( (string) $owner );
	$ttl   = max( 60, min( 3600, (int) $ttl ) );
	if ( '' === $owner ) {
		return new WP_Error( 'rhn_catalog_writer_owner', 'A writer owner is required.' );
	}

	$now   = time();
	$token = function_exists( 'wp_generate_uuid4' ) ? wp_generate_uuid4() : hash( 'sha256', $owner . '|' . $now . '|' . wp_rand() );
	$lease = array(
		'owner'      => $owner,
		'token'      => $token,
		'acquired_at' => $now,
		'expires_at' => $now + $ttl,
	);
	$key = rhn_catalog_writer_lock_key();
	if ( add_option( $key, $lease, '', false ) ) {
		return $token;
	}

	$current = get_option( $key, array() );
	$expires = is_array( $current ) ? (int) ( $current['expires_at'] ?? 0 ) : 0;
	if ( $expires >= $now ) {
		return new WP_Error(
			'rhn_catalog_writer_locked',
			'Another Product Review/Catalog writer owns the shared lease.',
			array( 'owner' => sanitize_key( (string) ( $current['owner'] ?? 'unknown' ) ), 'expires_at' => $expires )
		);
	}

	if ( ! rhn_catalog_writer_lock_delete_if_matches( $current ) ) {
		return new WP_Error( 'rhn_catalog_writer_locked', 'The expired writer lease changed before it could be reclaimed.' );
	}
	if ( ! add_option( $key, $lease, '', false ) ) {
		return new WP_Error( 'rhn_catalog_writer_locked', 'The expired writer lease was reclaimed by another process.' );
	}
	return $token;
}

/** Release only the lease owned by this exact process token. */
function rhn_catalog_writer_lock_release( $token ) {
	$key     = rhn_catalog_writer_lock_key();
	$current = get_option( $key, array() );
	$current_token = is_array( $current ) ? (string) ( $current['token'] ?? '' ) : '';
	if ( '' === $current_token || ! hash_equals( $current_token, (string) $token ) ) {
		return false;
	}
	return rhn_catalog_writer_lock_delete_if_matches( $current );
}
