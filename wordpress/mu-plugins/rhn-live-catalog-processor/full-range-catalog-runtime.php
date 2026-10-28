<?php
/**
 * Disabled-by-default full-range snapshot runtime.
 *
 * Nothing in this file registers a hook or schedule. Callers must explicitly
 * enable RHN_FULL_RANGE_CATALOG_ENABLED and invoke the gated entry points.
 */

defined( 'ABSPATH' ) || exit;

function rhn_full_range_feature_enabled() {
	return defined( 'RHN_FULL_RANGE_CATALOG_ENABLED' ) && true === RHN_FULL_RANGE_CATALOG_ENABLED;
}

function rhn_full_range_require_feature() {
	if ( ! rhn_full_range_feature_enabled() ) {
		throw new RuntimeException( 'Full-range catalog processing is disabled.' );
	}
}

function rhn_full_range_require_runtime_enabled() {
	if ( rhn_full_range_feature_enabled() ) {
		return;
	}
	if ( defined( 'RHN_FULL_RANGE_CATALOG_TESTING' ) && true === RHN_FULL_RANGE_CATALOG_TESTING ) {
		return;
	}
	throw new RuntimeException( 'Full-range catalog processing is disabled.' );
}

function rhn_full_range_require_callbacks( $callbacks, $required, $label ) {
	if ( ! is_array( $callbacks ) ) {
		throw new InvalidArgumentException( $label . ' adapter must be an array.' );
	}
	foreach ( $required as $name ) {
		if ( ! isset( $callbacks[ $name ] ) || ! is_callable( $callbacks[ $name ] ) ) {
			throw new InvalidArgumentException( $label . ' adapter is missing callable ' . $name . '.' );
		}
	}
}

function rhn_full_range_store_get( $store, $key ) {
	rhn_full_range_require_callbacks( $store, array( 'get', 'put_if_absent', 'compare_and_swap', 'delete' ), 'Store' );
	return call_user_func( $store['get'], (string) $key );
}

function rhn_full_range_store_put_if_absent( $store, $key, $value ) {
	return true === call_user_func( $store['put_if_absent'], (string) $key, $value );
}

function rhn_full_range_store_cas( $store, $key, $expected, $replacement ) {
	return true === call_user_func( $store['compare_and_swap'], (string) $key, $expected, $replacement );
}

function rhn_full_range_store_delete( $store, $key, $expected = null ) {
	return true === call_user_func( $store['delete'], (string) $key, $expected );
}

function rhn_full_range_snapshot_prefix( $snapshot_id ) {
	$snapshot_id = (string) $snapshot_id;
	if ( ! preg_match( '/^[A-Za-z0-9_.:-]{1,100}$/D', $snapshot_id ) ) {
		throw new InvalidArgumentException( 'Snapshot ID contains unsupported characters.' );
	}
	return 'rhn-full-range:' . $snapshot_id;
}

function rhn_full_range_wordpress_option_name( $logical_key ) {
	return 'rhn_fr_' . substr( hash( 'sha256', (string) $logical_key ), 0, 40 );
}

function rhn_full_range_option_cache_delete( $name ) {
	if ( function_exists( 'wp_cache_delete' ) ) {
		wp_cache_delete( (string) $name, 'options' );
		wp_cache_delete( 'notoptions', 'options' );
	}
}

/**
 * Durable WordPress option adapter. It is inert until a gated runtime function
 * is explicitly called. Snapshot/chunk values include their logical identity,
 * so a hash collision cannot silently return another object.
 */
function rhn_full_range_wordpress_option_store() {
	global $wpdb;
	if ( ! is_object( $wpdb ) || empty( $wpdb->options ) || ! method_exists( $wpdb, 'query' ) || ! method_exists( $wpdb, 'prepare' ) ) {
		throw new RuntimeException( 'The atomic WordPress option store requires the WordPress database adapter.' );
	}
	$encode = static function ( $logical_key, $value ) {
		$wrapped = array( '_logical_key' => (string) $logical_key, 'value' => $value );
		return function_exists( 'maybe_serialize' ) ? maybe_serialize( $wrapped ) : serialize( $wrapped );
	};
	return array(
		'get' => static function ( $logical_key ) {
			$name = rhn_full_range_wordpress_option_name( $logical_key );
			$value = get_option( $name, null );
			if ( ! is_array( $value ) || (string) ( $value['_logical_key'] ?? '' ) !== (string) $logical_key ) {
				return null;
			}
			return $value['value'] ?? null;
		},
		'put_if_absent' => static function ( $logical_key, $value ) use ( $wpdb, $encode ) {
			$name = rhn_full_range_wordpress_option_name( $logical_key );
			$inserted = $wpdb->query( $wpdb->prepare(
				"INSERT IGNORE INTO {$wpdb->options} (option_name, option_value, autoload) VALUES (%s, %s, 'off')",
				$name,
				$encode( $logical_key, $value )
			) );
			rhn_full_range_option_cache_delete( $name );
			return 1 === (int) $inserted;
		},
		'compare_and_swap' => static function ( $logical_key, $expected, $replacement ) use ( $wpdb, $encode ) {
			$name = rhn_full_range_wordpress_option_name( $logical_key );
			$updated = $wpdb->query( $wpdb->prepare(
				"UPDATE {$wpdb->options} SET option_value = %s, autoload = 'off' WHERE option_name = %s AND BINARY option_value = BINARY %s",
				$encode( $logical_key, $replacement ),
				$name,
				$encode( $logical_key, $expected )
			) );
			rhn_full_range_option_cache_delete( $name );
			return 1 === (int) $updated;
		},
		'delete' => static function ( $logical_key, $expected = null ) use ( $wpdb, $encode ) {
			$name = rhn_full_range_wordpress_option_name( $logical_key );
			if ( null === $expected ) {
				$deleted = $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name = %s", $name ) );
			} else {
				$deleted = $wpdb->query( $wpdb->prepare(
					"DELETE FROM {$wpdb->options} WHERE option_name = %s AND BINARY option_value = BINARY %s",
					$name,
					$encode( $logical_key, $expected )
				) );
			}
			rhn_full_range_option_cache_delete( $name );
			return 1 === (int) $deleted || ( null === $expected && null === get_option( $name, null ) );
		},
	);
}

function rhn_full_range_registry_bucket_key( $sku ) {
	return 'rhn-full-range:registry:' . substr( hash( 'sha256', (string) $sku ), 0, 2 );
}

/** Return found separately so an explicit withdrawal tombstone can suppress legacy fallback. */
function rhn_full_range_registry_lookup( $sku, $store = null ) {
	$store = is_array( $store ) ? $store : rhn_full_range_wordpress_option_store();
	$bucket = rhn_full_range_store_get( $store, rhn_full_range_registry_bucket_key( $sku ) );
	if ( ! is_array( $bucket ) || ! array_key_exists( (string) $sku, $bucket ) ) { return array( 'found' => false, 'entry' => null ); }
	$value = $bucket[ (string) $sku ];
	return array( 'found' => true, 'entry' => is_array( $value ) && empty( $value['_withdrawn'] ) ? $value : null );
}

/** Atomic per-SKU registry update in one of 256 bounded shards. */
function rhn_full_range_registry_put( $sku, $entry, $store = null ) {
	$sku = (string) $sku; $store = is_array( $store ) ? $store : rhn_full_range_wordpress_option_store();
	$key = rhn_full_range_registry_bucket_key( $sku );
	for ( $attempt = 0; $attempt < 8; $attempt++ ) {
		$current = rhn_full_range_store_get( $store, $key );
		$bucket = is_array( $current ) ? $current : array();
		$bucket[ $sku ] = is_array( $entry ) ? $entry : array( '_withdrawn' => true );
		ksort( $bucket, SORT_STRING );
		if ( null === $current ? rhn_full_range_store_put_if_absent( $store, $key, $bucket ) : rhn_full_range_store_cas( $store, $key, $current, $bucket ) ) { return true; }
	}
	return false;
}

function rhn_full_range_renew_lock( $store, $prefix, $lock, $ttl = 900 ) {
	$key = $prefix . ':lock'; $current = rhn_full_range_store_get( $store, $key );
	if ( ! is_array( $current ) || ! hash_equals( (string) ( $lock['token'] ?? '' ), (string) ( $current['token'] ?? '' ) ) ) { return false; }
	$replacement = $current; $replacement['expires'] = time() + max( 30, (int) $ttl );
	return rhn_full_range_store_cas( $store, $key, $current, $replacement ) ? $replacement : false;
}

function rhn_full_range_acquire_lock( $store, $prefix, $owner, $ttl = 300 ) {
	$key = $prefix . ':lock';
	$now = time();
	$lock = array( 'owner' => (string) $owner, 'token' => hash( 'sha256', (string) $owner . '|' . $now . '|' . uniqid( '', true ) ), 'expires' => $now + max( 30, (int) $ttl ) );
	if ( rhn_full_range_store_put_if_absent( $store, $key, $lock ) ) {
		return $lock;
	}
	$current = rhn_full_range_store_get( $store, $key );
	if ( is_array( $current ) && (int) ( $current['expires'] ?? 0 ) < $now && rhn_full_range_store_cas( $store, $key, $current, $lock ) ) {
		return $lock;
	}
	throw new RuntimeException( 'Another full-range worker owns the snapshot lock.' );
}

function rhn_full_range_release_lock( $store, $prefix, $lock ) {
	$current = rhn_full_range_store_get( $store, $prefix . ':lock' );
	if ( is_array( $current ) && hash_equals( (string) ( $lock['token'] ?? '' ), (string) ( $current['token'] ?? '' ) ) ) {
		rhn_full_range_store_delete( $store, $prefix . ':lock', $current );
	}
}

/** Parse one bounded data page through the existing production Sheet parser. */
function rhn_full_range_parse_sheet_page( $header, $rows, $start_row ) {
	if ( ! is_array( $header ) || ! array_is_list( $header ) || ! is_array( $rows ) || ! array_is_list( $rows ) ) {
		throw new InvalidArgumentException( 'Sheet header and page rows must be lists.' );
	}
	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) || ! array_is_list( $row ) ) {
			throw new InvalidArgumentException( 'Every bounded source row must be a cell list.' );
		}
	}
	$parsed = rhn_catalog_sheet_rows_to_registry( array_merge( array( $header ), $rows ) );
	$start_row = (int) $start_row;
	foreach ( $parsed['registry'] as &$record ) {
		if ( isset( $record['sheet_row'] ) ) {
			$record['sheet_row'] = (int) $record['sheet_row'] + $start_row;
		}
	}
	unset( $record );
	foreach ( $parsed['withdrawals'] as &$withdrawal ) {
		$withdrawal['row'] = (int) ( $withdrawal['row'] ?? 0 ) + $start_row;
	}
	unset( $withdrawal );
	foreach ( $parsed['skipped'] as &$skipped ) {
		$skipped['row'] = (int) ( $skipped['row'] ?? 0 ) + $start_row;
	}
	unset( $skipped );
	foreach ( $rows as $index => $cells ) {
		if ( ! array_filter( $cells, static fn( $value ) => '' !== trim( (string) $value ) ) ) {
			$parsed['skipped'][] = array( 'row' => $start_row + $index + 2, 'reason' => 'Blank source row.' );
		}
	}
	return $parsed;
}

function rhn_full_range_prepare_snapshot_step( $source, $store, $snapshot_id, $page_size = 500, $max_pages = 1, $owner = 'worker' ) {
	rhn_full_range_require_runtime_enabled();
	rhn_full_range_require_callbacks( $source, array( 'get_version', 'get_total_rows', 'get_header', 'read_page' ), 'Source' );
	$prefix = rhn_full_range_snapshot_prefix( $snapshot_id );
	$page_size = (int) $page_size;
	$max_pages = (int) $max_pages;
	if ( $max_pages < 1 || $max_pages > 20 ) {
		throw new InvalidArgumentException( 'A preparation step may read 1-20 bounded pages.' );
	}
	$lock = rhn_full_range_acquire_lock( $store, $prefix, $owner, 900 );
	try {
		$version = trim( (string) call_user_func( $source['get_version'] ) );
		$total = (int) call_user_func( $source['get_total_rows'] );
		$header = call_user_func( $source['get_header'] );
		$manifest_key = $prefix . ':manifest';
		$manifest = rhn_full_range_store_get( $store, $manifest_key );
		if ( null === $manifest ) {
			$manifest = rhn_full_range_new_manifest( $version, $total, $page_size );
			if ( ! rhn_full_range_store_put_if_absent( $store, $manifest_key, $manifest ) ) {
				$manifest = rhn_full_range_store_get( $store, $manifest_key );
			}
		}
		if ( ! is_array( $manifest ) || 2 !== (int) ( $manifest['schema'] ?? 0 ) ) {
			throw new RuntimeException( 'Stored manifest schema is unsupported.' );
		}
		if ( ! hash_equals( (string) $manifest['source_version'], $version ) || (int) $manifest['total_source_rows'] !== $total || (int) $manifest['page_size'] !== $page_size ) {
			throw new RuntimeException( 'Source version, row count, or page size changed; start a new snapshot ID.' );
		}
		$processed = 0;
		while ( empty( $manifest['complete'] ) && $processed < $max_pages ) {
			$expected = $manifest;
			$start = (int) $manifest['next_row'];
			$limit = min( $page_size, $total - $start );
			$rows = call_user_func( $source['read_page'], $start, $limit );
			if ( ! is_array( $rows ) || ! array_is_list( $rows ) || count( $rows ) !== $limit ) {
				throw new RuntimeException( 'Bounded source read did not return every declared row.' );
			}
			$parsed = rhn_full_range_parse_sheet_page( $header, $rows, $start );
			$terminal = $start + $limit === $total;
			$bundle = rhn_full_range_prepare_page_bundle( $version, $start, $limit, $parsed['registry'], $parsed['withdrawals'], $parsed['skipped'], $terminal, $rows );
			if ( empty( $bundle['page']['source_bound'] ) ) {
				throw new RuntimeException( 'Runtime snapshots must bind every complete source row.' );
			}
			if ( ! hash_equals( $version, trim( (string) call_user_func( $source['get_version'] ) ) ) ) {
				throw new RuntimeException( 'Source version changed while reading a bounded page; discard this snapshot.' );
			}
			$chunk_key = $prefix . ':chunk:' . (int) $manifest['pages'];
			$chunk = array( 'page' => $bundle['page'], 'payload' => $bundle['payload'] );
			if ( strlen( rhn_full_range_json( $chunk ) ) > 4194304 ) {
				throw new RuntimeException( 'Durable chunk exceeds the 4 MiB option/cache safety ceiling.' );
			}
			$existing_chunk = rhn_full_range_store_get( $store, $chunk_key );
			if ( null === $existing_chunk ) {
				if ( ! rhn_full_range_store_put_if_absent( $store, $chunk_key, $chunk ) ) {
					$existing_chunk = rhn_full_range_store_get( $store, $chunk_key );
				}
			}
			$existing_chunk = rhn_full_range_store_get( $store, $chunk_key );
			if ( null === $existing_chunk || rhn_full_range_json( $existing_chunk ) !== rhn_full_range_json( $chunk ) ) {
				throw new RuntimeException( 'An immutable chunk key already contains different content.' );
			}
			rhn_full_range_validate_page_payload( $existing_chunk['page'] ?? null, $existing_chunk['payload'] ?? null );
			$manifest = rhn_full_range_advance_manifest( $manifest, $bundle['page'] );
			$manifest['revision'] = (int) $expected['revision'] + 1;
			if ( strlen( rhn_full_range_json( $manifest ) ) > 4194304 ) {
				throw new RuntimeException( 'Durable manifest exceeds the 4 MiB option/cache safety ceiling.' );
			}
			if ( ! rhn_full_range_store_cas( $store, $manifest_key, $expected, $manifest ) ) {
				throw new RuntimeException( 'Manifest compare-and-swap failed; another worker changed it.' );
			}
			$processed++;
		}
		if ( empty( $manifest['complete'] ) ) {
			return array( 'status' => 'partial', 'processed_pages' => $processed, 'manifest' => $manifest );
		}
		if ( ! hash_equals( $version, trim( (string) call_user_func( $source['get_version'] ) ) ) ) {
			throw new RuntimeException( 'Source version changed before snapshot finalization.' );
		}
		foreach ( (array) $manifest['page_summaries'] as $page_summary ) {
			if ( empty( $page_summary['source_bound'] ) ) {
				throw new RuntimeException( 'A runtime page is not bound to complete source-row hashes.' );
			}
		}
		$final = rhn_full_range_finalize_manifest( $manifest );
		$final['snapshot_hash'] = hash( 'sha256', rhn_full_range_json( $final ) );
		$final['status'] = 'prepared';
		$final_key = $prefix . ':final';
		$existing_final = rhn_full_range_store_get( $store, $final_key );
		if ( null === $existing_final ) {
			if ( ! rhn_full_range_store_put_if_absent( $store, $final_key, $final ) ) {
				$existing_final = rhn_full_range_store_get( $store, $final_key );
			}
		}
		$existing_final = rhn_full_range_store_get( $store, $final_key );
		if ( null === $existing_final || rhn_full_range_json( $existing_final ) !== rhn_full_range_json( $final ) ) {
			throw new RuntimeException( 'Final snapshot identity collision.' );
		}
		return array( 'status' => 'prepared', 'processed_pages' => $processed, 'manifest' => $manifest, 'final' => $final );
	} finally {
		rhn_full_range_release_lock( $store, $prefix, $lock );
	}
}

function rhn_full_range_page_actions( $payload ) {
	$actions = array();
	foreach ( (array) ( $payload['registry'] ?? array() ) as $sku => $record ) {
		$actions[] = array( 'action' => 'approved', 'sku' => (string) $sku, 'source_row' => (int) ( $record['sheet_row'] ?? 0 ), 'record' => $record );
	}
	foreach ( (array) ( $payload['withdrawals'] ?? array() ) as $sku => $record ) {
		$actions[] = array( 'action' => 'withdrawal', 'sku' => (string) $sku, 'source_row' => (int) ( $record['row'] ?? 0 ), 'record' => $record );
	}
	usort( $actions, static function ( $left, $right ) {
		return array( (int) $left['source_row'], (string) $left['sku'], (string) $left['action'] ) <=> array( (int) $right['source_row'], (string) $right['sku'], (string) $right['action'] );
	} );
	return $actions;
}

function rhn_full_range_process_next_action( $source, $store, $snapshot_id, $apply, $owner = 'worker' ) {
	rhn_full_range_require_runtime_enabled();
	rhn_full_range_require_callbacks( $source, array( 'get_version', 'verify_action' ), 'Action source' );
	if ( ! is_callable( $apply ) ) {
		throw new InvalidArgumentException( 'Action applier must be callable.' );
	}
	$prefix = rhn_full_range_snapshot_prefix( $snapshot_id );
	$lock = rhn_full_range_acquire_lock( $store, $prefix, $owner, 900 );
	try {
		$final = rhn_full_range_store_get( $store, $prefix . ':final' );
		$manifest = rhn_full_range_store_get( $store, $prefix . ':manifest' );
		if ( ! is_array( $final ) || 'prepared' !== (string) ( $final['status'] ?? '' ) || ! is_array( $manifest ) ) {
			throw new RuntimeException( 'Only a finalized prepared snapshot may supply actions.' );
		}
		if ( ! hash_equals( (string) $final['source_version'], trim( (string) call_user_func( $source['get_version'] ) ) ) ) {
			throw new RuntimeException( 'Current source version differs from the prepared snapshot.' );
		}
		$cursor_key = $prefix . ':cursor';
		$cursor = rhn_full_range_store_get( $store, $cursor_key );
		if ( null === $cursor ) {
			$cursor = array( 'snapshot_hash' => (string) $final['snapshot_hash'], 'page' => 0, 'action' => 0, 'revision' => 0, 'done' => false );
			if ( ! rhn_full_range_store_put_if_absent( $store, $cursor_key, $cursor ) ) {
				$cursor = rhn_full_range_store_get( $store, $cursor_key );
			}
		}
		if ( ! is_array( $cursor ) || ! hash_equals( (string) $final['snapshot_hash'], (string) ( $cursor['snapshot_hash'] ?? '' ) ) ) {
			throw new RuntimeException( 'Action cursor belongs to another snapshot.' );
		}
		if ( ! empty( $cursor['done'] ) ) {
			return array( 'status' => 'complete', 'cursor' => $cursor );
		}
		$expected = $cursor;
		while ( (int) $cursor['page'] < (int) $final['pages'] ) {
			$page_index = (int) $cursor['page'];
			$chunk = rhn_full_range_store_get( $store, $prefix . ':chunk:' . $page_index );
			$page_summary = $manifest['page_summaries'][ $page_index ] ?? null;
			if ( ! is_array( $chunk ) || ! is_array( $page_summary ) ) {
				throw new RuntimeException( 'Finalized snapshot is missing a durable chunk.' );
			}
			rhn_full_range_validate_page_payload( $chunk['page'] ?? null, $chunk['payload'] ?? null );
			if ( empty( $page_summary['source_bound'] ) || ! hash_equals( (string) $page_summary['payload_hash'], (string) ( $chunk['page']['payload_hash'] ?? '' ) ) ) {
				throw new RuntimeException( 'Durable chunk does not match the finalized manifest.' );
			}
			$actions = rhn_full_range_page_actions( $chunk['payload'] );
			if ( (int) $cursor['action'] >= count( $actions ) ) {
				$cursor['page']++;
				$cursor['action'] = 0;
				continue;
			}
			$action = $actions[ (int) $cursor['action'] ];
			$action['payload_hash'] = (string) $chunk['page']['payload_hash'];
			$action_id = hash( 'sha256', (string) $final['snapshot_hash'] . '|' . rhn_full_range_json( $action ) );
			if ( true !== call_user_func( $source['verify_action'], $action ) ) {
				throw new RuntimeException( 'Action-time source verification refused the current SKU decision.' );
			}
			if ( ! hash_equals( (string) $final['source_version'], trim( (string) call_user_func( $source['get_version'] ) ) ) ) {
				throw new RuntimeException( 'Source version changed during action-time verification.' );
			}
			$receipt_key = $prefix . ':receipt:' . $action_id;
			$receipt = rhn_full_range_store_get( $store, $receipt_key );
			if ( null === $receipt ) {
				$receipt = call_user_func( $apply, $action, $action_id );
				if ( is_array( $receipt ) && true === ( $receipt['retryable'] ?? false ) ) {
					return array( 'status' => 'retryable_error', 'action' => $action, 'action_id' => $action_id, 'receipt' => $receipt, 'cursor' => $cursor );
				}
				if ( ! is_array( $receipt ) || true !== ( $receipt['verified'] ?? false ) || true !== ( $receipt['terminal'] ?? false ) ) {
					throw new RuntimeException( 'Action was not verified; cursor was not advanced.' );
				}
				$receipt['action_id'] = $action_id;
				if ( ! rhn_full_range_store_put_if_absent( $store, $receipt_key, $receipt ) ) {
					$stored = rhn_full_range_store_get( $store, $receipt_key );
					if ( rhn_full_range_json( $stored ) !== rhn_full_range_json( $receipt ) ) {
						throw new RuntimeException( 'Conflicting action receipt.' );
					}
				}
			}
			$receipt = rhn_full_range_store_get( $store, $receipt_key );
			if ( ! is_array( $receipt ) || true !== ( $receipt['verified'] ?? false ) || true !== ( $receipt['terminal'] ?? false ) || ! hash_equals( $action_id, (string) ( $receipt['action_id'] ?? '' ) ) ) {
				throw new RuntimeException( 'Persisted action receipt is missing or invalid.' );
			}
			$cursor['action']++;
			$cursor['revision'] = (int) $expected['revision'] + 1;
			if ( ! rhn_full_range_store_cas( $store, $cursor_key, $expected, $cursor ) ) {
				throw new RuntimeException( 'Action cursor compare-and-swap failed.' );
			}
			return array( 'status' => 'applied', 'action' => $action, 'action_id' => $action_id, 'receipt' => $receipt, 'cursor' => $cursor );
		}
		$cursor['done'] = true;
		$cursor['revision'] = (int) $expected['revision'] + 1;
		if ( ! rhn_full_range_store_cas( $store, $cursor_key, $expected, $cursor ) ) {
			throw new RuntimeException( 'Final action cursor compare-and-swap failed.' );
		}
		return array( 'status' => 'complete', 'cursor' => $cursor );
	} finally {
		rhn_full_range_release_lock( $store, $prefix, $lock );
	}
}

/** Delete one finished/abandoned snapshot and all of its deterministic receipts. */
function rhn_full_range_delete_snapshot( $store, $snapshot_id ) {
	$prefix = rhn_full_range_snapshot_prefix( $snapshot_id );
	try { $cleanup_lock = rhn_full_range_acquire_lock( $store, $prefix, 'cleanup-' . wp_generate_uuid4(), 900 ); }
	catch ( RuntimeException $error ) { return false; }
	try {
		$manifest = rhn_full_range_store_get( $store, $prefix . ':manifest' );
		$final = rhn_full_range_store_get( $store, $prefix . ':final' );
		if ( is_array( $manifest ) ) {
		foreach ( (array) ( $manifest['page_summaries'] ?? array() ) as $page_index => $summary ) {
			$chunk_key = $prefix . ':chunk:' . (int) $page_index;
			$chunk = rhn_full_range_store_get( $store, $chunk_key );
			if ( is_array( $chunk ) ) {
				foreach ( is_array( $final ) ? rhn_full_range_page_actions( (array) ( $chunk['payload'] ?? array() ) ) : array() as $action ) {
					$action['payload_hash'] = (string) ( $chunk['page']['payload_hash'] ?? '' );
					$action_id = hash( 'sha256', (string) ( $final['snapshot_hash'] ?? '' ) . '|' . rhn_full_range_json( $action ) );
					$receipt_key = $prefix . ':receipt:' . $action_id;
					$receipt = rhn_full_range_store_get( $store, $receipt_key );
					if ( null !== $receipt ) { rhn_full_range_store_delete( $store, $receipt_key, $receipt ); }
				}
				rhn_full_range_store_delete( $store, $chunk_key, $chunk );
			}
		}
		}
		foreach ( array( 'cursor', 'final', 'manifest' ) as $suffix ) {
			$key = $prefix . ':' . $suffix; $value = rhn_full_range_store_get( $store, $key );
			if ( null !== $value ) { rhn_full_range_store_delete( $store, $key, $value ); }
		}
	} finally { rhn_full_range_release_lock( $store, $prefix, $cleanup_lock ); }
	return true;
}

function rhn_full_range_prepare_snapshot_if_enabled( $source, $store, $snapshot_id, $page_size = 500, $max_pages = 1, $owner = 'worker' ) {
	rhn_full_range_require_feature();
	return rhn_full_range_prepare_snapshot_step( $source, $store, $snapshot_id, $page_size, $max_pages, $owner );
}

function rhn_full_range_process_next_action_if_enabled( $source, $store, $snapshot_id, $apply, $owner = 'worker' ) {
	rhn_full_range_require_feature();
	return rhn_full_range_process_next_action( $source, $store, $snapshot_id, $apply, $owner );
}

