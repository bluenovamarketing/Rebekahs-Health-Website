<?php
/**
 * Full-range catalog preparation helpers.
 *
 * This module is deliberately side-effect free. It does not fetch Google Sheets,
 * update WordPress options, mutate WooCommerce, or infer removals from missing rows.
 * Production integration must supply a stable source version and persist chunks and
 * manifests only after a complete, stable scan has been verified.
 */

defined( 'ABSPATH' ) || exit;

function rhn_full_range_canonicalize( $value ) {
	if ( ! is_array( $value ) ) {
		return $value;
	}
	if ( array_is_list( $value ) ) {
		return array_map( 'rhn_full_range_canonicalize', $value );
	}
	ksort( $value, SORT_STRING );
	foreach ( $value as $key => $item ) {
		$value[ $key ] = rhn_full_range_canonicalize( $item );
	}
	return $value;
}

function rhn_full_range_json( $value ) {
	$json = wp_json_encode( rhn_full_range_canonicalize( $value ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( ! is_string( $json ) ) {
		throw new RuntimeException( 'Could not encode full-range catalog state.' );
	}
	return $json;
}

/**
 * Retain the existing 1,000-record and 2 MB parser guards per chunk while
 * accepting a larger approved set. This helper only validates and merges; it
 * does not decide how the merged registry is stored in WordPress.
 */
function rhn_full_range_parse_registry_records( $records, $chunk_size = 500, $max_records = 20000, $max_total_json_bytes = 67108864 ) {
	if ( ! is_array( $records ) || ! array_is_list( $records ) ) {
		throw new InvalidArgumentException( 'Approved records must be a list.' );
	}
	$count = count( $records );
	if ( $count < 1 || $count > (int) $max_records ) {
		throw new InvalidArgumentException( 'Approved record count is outside the full-range safety ceiling.' );
	}
	$chunk_size = (int) $chunk_size;
	if ( $chunk_size < 1 || $chunk_size > 1000 ) {
		throw new InvalidArgumentException( 'Registry chunks must contain 1-1000 records.' );
	}
	$merged = array();
	$total_json_bytes = 0;
	foreach ( array_chunk( $records, $chunk_size ) as $chunk ) {
		$json = wp_json_encode( $chunk );
		if ( ! is_string( $json ) ) {
			throw new RuntimeException( 'Could not encode a registry chunk.' );
		}
		$total_json_bytes += strlen( $json );
		if ( $total_json_bytes > (int) $max_total_json_bytes ) {
			throw new InvalidArgumentException( 'Approved registry exceeds the aggregate preparation ceiling.' );
		}
		$parsed = rhn_catalog_parse_registry( $json );
		foreach ( $parsed as $sku => $record ) {
			if ( isset( $merged[ $sku ] ) ) {
				throw new InvalidArgumentException( 'Duplicate SKU across registry chunks: ' . $sku );
			}
			$merged[ $sku ] = $record;
		}
	}
	return $merged;
}

function rhn_full_range_new_manifest( $source_version, $total_source_rows, $page_size = 500, $max_source_rows = 20000, $max_actionable = 20000 ) {
	$source_version = trim( (string) $source_version );
	$total_source_rows = (int) $total_source_rows;
	$page_size = (int) $page_size;
	if ( '' === $source_version ) {
		throw new InvalidArgumentException( 'A stable source version is required.' );
	}
	if ( $total_source_rows < 1 || $total_source_rows > (int) $max_source_rows ) {
		throw new InvalidArgumentException( 'Source row count is outside the configured safety ceiling.' );
	}
	if ( $page_size < 1 || $page_size > 1000 ) {
		throw new InvalidArgumentException( 'Page size must be between 1 and 1000 rows.' );
	}
	$max_actionable = (int) $max_actionable;
	if ( $max_actionable < 1 || $max_actionable > (int) $max_source_rows ) {
		throw new InvalidArgumentException( 'Actionable SKU ceiling is invalid.' );
	}
	return array(
		'schema'             => 2,
		'source_version'     => $source_version,
		'total_source_rows'  => $total_source_rows,
		'page_size'          => $page_size,
		'max_actionable'     => $max_actionable,
		'revision'           => 0,
		'next_row'           => 0,
		'pages'              => 0,
		'approved'           => 0,
		'withdrawals'        => 0,
		'skipped'            => 0,
		'seen_actionable'    => array(),
		'page_summaries'     => array(),
		'chain_hash'         => hash( 'sha256', 'rhn-full-range-v2|' . $source_version ),
		'complete'           => false,
	);
}

function rhn_full_range_page_payload( $source_version, $start_row, $row_count, $registry, $withdrawals, $skipped, $source_rows = null ) {
	$source_row_hashes = array();
	if ( null !== $source_rows ) {
		if ( ! is_array( $source_rows ) || ! array_is_list( $source_rows ) || count( $source_rows ) !== (int) $row_count ) {
			throw new InvalidArgumentException( 'Complete source rows must match the bounded page count.' );
		}
		foreach ( $source_rows as $row ) {
			if ( ! is_array( $row ) || ! array_is_list( $row ) ) {
				throw new InvalidArgumentException( 'Every complete source row must be a cell list.' );
			}
			$source_row_hashes[] = hash( 'sha256', rhn_full_range_json( $row ) );
		}
	}
	return array(
		'schema'         => 2,
		'source_version' => (string) $source_version,
		'start_row'      => (int) $start_row,
		'row_count'      => (int) $row_count,
		'registry'       => $registry,
		'withdrawals'    => $withdrawals,
		'skipped'        => $skipped,
		'source_bound'   => null !== $source_rows,
		'source_hashes'  => $source_row_hashes,
	);
}

function rhn_full_range_payload_hash( $payload ) {
	return hash( 'sha256', rhn_full_range_json( $payload ) );
}

/**
 * Validate and summarize one already-parsed source page. Chunk payloads are
 * persisted separately by a future integration so the manifest stays small.
 */
function rhn_full_range_prepare_page( $source_version, $start_row, $row_count, $registry, $withdrawals, $skipped, $terminal = false, $source_rows = null ) {
	$source_version = trim( (string) $source_version );
	$start_row = (int) $start_row;
	$row_count = (int) $row_count;
	if ( '' === $source_version || $start_row < 0 || $row_count < 1 ) {
		throw new InvalidArgumentException( 'Invalid full-range page coordinates.' );
	}
	if ( ! is_array( $registry ) || ! is_array( $withdrawals ) || ! is_array( $skipped ) ) {
		throw new InvalidArgumentException( 'Page registry, withdrawals, and skipped rows must be arrays.' );
	}
	if ( ! array_is_list( $skipped ) ) {
		throw new InvalidArgumentException( 'Skipped rows must be a list.' );
	}
	$row_coverage = array();
	$account_row = static function ( $sheet_row ) use ( &$row_coverage, $start_row, $row_count ) {
		if ( ! is_int( $sheet_row ) ) {
			throw new InvalidArgumentException( 'Every source record requires exact integer row provenance.' );
		}
		$minimum = $start_row + 2;
		$maximum = $minimum + $row_count - 1;
		if ( $sheet_row < $minimum || $sheet_row > $maximum ) {
			throw new InvalidArgumentException( 'Source row provenance is outside the bounded page.' );
		}
		if ( isset( $row_coverage[ $sheet_row ] ) ) {
			throw new InvalidArgumentException( 'A source row was accounted for more than once.' );
		}
		$row_coverage[ $sheet_row ] = true;
	};
	$actionable = array();
	foreach ( $registry as $sku => $record ) {
		if ( ! is_string( $sku ) || '' === $sku || $sku !== trim( $sku ) ) {
			throw new InvalidArgumentException( 'Approved SKU keys must be exact nonempty text.' );
		}
		if ( ! is_array( $record ) ) {
			throw new InvalidArgumentException( 'Approved records must be parsed arrays.' );
		}
		$account_row( $record['sheet_row'] ?? null );
		$actionable[ $sku ] = 'approved';
	}
	foreach ( $withdrawals as $sku => $record ) {
		if ( ! is_string( $sku ) || '' === $sku || $sku !== trim( $sku ) ) {
			throw new InvalidArgumentException( 'Withdrawal SKU keys must be exact nonempty text.' );
		}
		if ( isset( $actionable[ $sku ] ) ) {
			throw new InvalidArgumentException( 'A SKU cannot be approved and withdrawn in the same page: ' . $sku );
		}
		if ( ! is_array( $record ) || ( isset( $record['sku'] ) && $sku !== (string) $record['sku'] ) ) {
			throw new InvalidArgumentException( 'Withdrawal records must preserve their exact SKU.' );
		}
		$account_row( $record['row'] ?? null );
		$actionable[ $sku ] = 'withdrawal';
	}
	foreach ( $skipped as $record ) {
		if ( ! is_array( $record ) ) {
			throw new InvalidArgumentException( 'Skipped records must preserve row provenance.' );
		}
		$account_row( $record['row'] ?? null );
	}
	$accounted = count( $registry ) + count( $withdrawals ) + count( $skipped );
	if ( $accounted !== $row_count || count( $row_coverage ) !== $row_count ) {
		throw new InvalidArgumentException( 'Every source row must be accounted for exactly once.' );
	}
	$payload = rhn_full_range_page_payload( $source_version, $start_row, $row_count, $registry, $withdrawals, $skipped, $source_rows );
	$summary = array(
		'source_version' => $source_version,
		'start_row'      => $start_row,
		'row_count'      => $row_count,
		'approved'       => count( $registry ),
		'withdrawals'    => count( $withdrawals ),
		'skipped'        => count( $skipped ),
		'actionable'     => $actionable,
		'payload_hash'   => rhn_full_range_payload_hash( $payload ),
		'source_bound'   => (bool) $payload['source_bound'],
		'terminal'       => (bool) $terminal,
	);
	$summary['chunk_hash'] = hash( 'sha256', rhn_full_range_json( $summary ) );
	return $summary;
}

function rhn_full_range_prepare_page_bundle( $source_version, $start_row, $row_count, $registry, $withdrawals, $skipped, $terminal = false, $source_rows = null ) {
	$page = rhn_full_range_prepare_page( $source_version, $start_row, $row_count, $registry, $withdrawals, $skipped, $terminal, $source_rows );
	return array(
		'page'    => $page,
		'payload' => rhn_full_range_page_payload( $source_version, $start_row, $row_count, $registry, $withdrawals, $skipped, $source_rows ),
	);
}

function rhn_full_range_validate_page_payload( $page, $payload ) {
	if ( ! is_array( $page ) || ! is_array( $payload ) ) {
		throw new InvalidArgumentException( 'Page and payload must be arrays.' );
	}
	foreach ( array( 'source_version', 'start_row', 'row_count' ) as $key ) {
		if ( (string) ( $page[ $key ] ?? '' ) !== (string) ( $payload[ $key ] ?? '' ) ) {
			throw new RuntimeException( 'Persisted page coordinates do not match its manifest summary.' );
		}
	}
	$actual = rhn_full_range_payload_hash( $payload );
	if ( ! hash_equals( (string) ( $page['payload_hash'] ?? '' ), $actual ) ) {
		throw new RuntimeException( 'Persisted page payload hash mismatch.' );
	}
	if ( ! array_key_exists( 'source_bound', $payload ) || (bool) ( $page['source_bound'] ?? false ) !== (bool) $payload['source_bound'] ) {
		throw new RuntimeException( 'Persisted page source-binding state mismatch.' );
	}
	return true;
}

function rhn_full_range_advance_manifest( $manifest, $page ) {
	if ( ! is_array( $manifest ) || ! is_array( $page ) || ! empty( $manifest['complete'] ) ) {
		throw new InvalidArgumentException( 'Manifest or page state is invalid.' );
	}
	if ( ! hash_equals( (string) $manifest['source_version'], (string) ( $page['source_version'] ?? '' ) ) ) {
		throw new RuntimeException( 'Source version changed during the scan; discard the partial snapshot and restart.' );
	}
	if ( (int) $page['start_row'] !== (int) $manifest['next_row'] ) {
		throw new RuntimeException( 'Page order is not contiguous.' );
	}
	if ( (int) $page['row_count'] > (int) $manifest['page_size'] ) {
		throw new RuntimeException( 'Page exceeds the configured size.' );
	}
	$next_row = (int) $manifest['next_row'] + (int) $page['row_count'];
	if ( $next_row > (int) $manifest['total_source_rows'] ) {
		throw new RuntimeException( 'Page extends beyond the declared source range.' );
	}
	foreach ( (array) $page['actionable'] as $sku => $action ) {
		if ( isset( $manifest['seen_actionable'][ $sku ] ) ) {
			throw new RuntimeException( 'Duplicate actionable SKU across pages: ' . $sku );
		}
		$manifest['seen_actionable'][ $sku ] = $action;
	}
	if ( count( $manifest['seen_actionable'] ) > (int) $manifest['max_actionable'] ) {
		throw new RuntimeException( 'Actionable SKU count exceeds the safety ceiling.' );
	}
	$manifest['next_row'] = $next_row;
	$manifest['pages']++;
	$manifest['approved'] += (int) $page['approved'];
	$manifest['withdrawals'] += (int) $page['withdrawals'];
	$manifest['skipped'] += (int) $page['skipped'];
	$manifest['chain_hash'] = hash( 'sha256', $manifest['chain_hash'] . '|' . (string) $page['chunk_hash'] );
	$manifest['page_summaries'][] = array(
		'start_row'   => (int) $page['start_row'],
		'row_count'   => (int) $page['row_count'],
		'approved'    => (int) $page['approved'],
		'withdrawals' => (int) $page['withdrawals'],
		'skipped'     => (int) $page['skipped'],
		'payload_hash'=> (string) $page['payload_hash'],
		'source_bound'=> ! empty( $page['source_bound'] ),
		'chunk_hash'  => (string) $page['chunk_hash'],
	);
	if ( ! empty( $page['terminal'] ) ) {
		if ( $next_row !== (int) $manifest['total_source_rows'] ) {
			throw new RuntimeException( 'Terminal page does not exhaust the declared source range.' );
		}
		$manifest['complete'] = true;
	} elseif ( $next_row === (int) $manifest['total_source_rows'] ) {
		throw new RuntimeException( 'The exhausted source must be marked terminal.' );
	}
	return $manifest;
}

function rhn_full_range_finalize_manifest( $manifest ) {
	if ( ! is_array( $manifest ) || empty( $manifest['complete'] ) || (int) $manifest['next_row'] !== (int) $manifest['total_source_rows'] ) {
		throw new RuntimeException( 'Incomplete source scan refused; no action plan may be published.' );
	}
	$accounted = (int) $manifest['approved'] + (int) $manifest['withdrawals'] + (int) $manifest['skipped'];
	if ( $accounted !== (int) $manifest['total_source_rows'] || count( (array) $manifest['page_summaries'] ) !== (int) $manifest['pages'] ) {
		throw new RuntimeException( 'Final manifest accounting is inconsistent; no action plan may be published.' );
	}
	return array(
		'source_version'    => (string) $manifest['source_version'],
		'total_source_rows' => (int) $manifest['total_source_rows'],
		'pages'             => (int) $manifest['pages'],
		'approved'          => (int) $manifest['approved'],
		'withdrawals'       => (int) $manifest['withdrawals'],
		'skipped'           => (int) $manifest['skipped'],
		'chain_hash'        => (string) $manifest['chain_hash'],
	);
}

