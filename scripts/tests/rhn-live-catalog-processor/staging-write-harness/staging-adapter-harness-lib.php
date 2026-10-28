<?php
/** Pure validation helpers for the temporary rollback-only staging harness. */
defined( 'ABSPATH' ) || exit;

function rhn_frh_canonicalize( $value ) {
	if ( is_array( $value ) ) {
		if ( array_is_list( $value ) ) {
			return array_map( 'rhn_frh_canonicalize', $value );
		}
		ksort( $value, SORT_STRING );
		foreach ( $value as $key => $item ) {
			$value[ $key ] = rhn_frh_canonicalize( $item );
		}
	}
	return $value;
}

function rhn_frh_canonical_json( $value ) {
	$json = json_encode( rhn_frh_canonicalize( $value ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	if ( ! is_string( $json ) ) {
		throw new RuntimeException( 'Could not encode canonical storage state.' );
	}
	return $json;
}

function rhn_frh_extract_global_functions( $files, $directory ) {
	$symbols = array();
	foreach ( $files as $name ) {
		$source = file_get_contents( rtrim( $directory, '/\\' ) . '/' . $name );
		if ( ! is_string( $source ) ) {
			throw new RuntimeException( 'Could not read release source: ' . $name );
		}
		if ( preg_match_all( '/^function\s+([A-Za-z_][A-Za-z0-9_]*)\s*\(/m', $source, $matches ) ) {
			$symbols = array_merge( $symbols, $matches[1] );
		}
	}
	$symbols = array_values( array_unique( $symbols ) );
	sort( $symbols, SORT_STRING );
	return $symbols;
}

function rhn_frh_validate_config( $config ) {
	$required = array( 'run_id', 'token_hash', 'release_dir', 'probe_event_id', 'probe_terminal_status', 'probe_checked_at_utc', 'hook_fingerprint', 'targets' );
	foreach ( $required as $key ) {
		if ( ! array_key_exists( $key, $config ) ) {
			throw new InvalidArgumentException( 'Missing harness config: ' . $key );
		}
	}
	if ( ! preg_match( '/^[A-Za-z0-9_.-]{8,80}$/D', (string) $config['run_id'] ) || ! preg_match( '/^[a-f0-9]{64}$/D', (string) $config['token_hash'] ) ) {
		throw new InvalidArgumentException( 'Run ID or token hash is invalid.' );
	}
	if ( ! preg_match( '/^[a-f0-9]{64}$/D', (string) $config['hook_fingerprint'] ) ) {
		throw new InvalidArgumentException( 'The reviewed product-hook fingerprint is missing or invalid.' );
	}
	if ( 'Done' !== (string) $config['probe_terminal_status'] || '6b9495a4-0807-4d00-9928-fdf629da61a3' !== (string) $config['probe_event_id'] ) {
		throw new InvalidArgumentException( 'The exact Kosmos probe is not recorded terminal.' );
	}
	$checked = strtotime( (string) $config['probe_checked_at_utc'] );
	if ( ! $checked || abs( time() - $checked ) > HOUR_IN_SECONDS ) {
		throw new InvalidArgumentException( 'Probe terminal evidence must be refreshed within one hour.' );
	}
	if ( ! is_array( $config['targets'] ) || array_keys( $config['targets'] ) !== array( 'approved', 'withdrawal' ) ) {
		throw new InvalidArgumentException( 'Exactly approved and withdrawal targets are required.' );
	}
	$ids = array(); $skus = array();
	foreach ( $config['targets'] as $kind => $target ) {
		if ( ! is_array( $target ) || empty( $target['product_id'] ) || empty( $target['sku'] ) || ! isset( $target['action'] ) || ! is_array( $target['action'] ) ) {
			throw new InvalidArgumentException( 'Each target requires exact product ID, text SKU, and action.' );
		}
		$ids[] = (int) $target['product_id']; $skus[] = (string) $target['sku'];
		$action = $target['action'];
		if ( (string) $action['sku'] !== (string) $target['sku'] || ! isset( $action['record'] ) || ! is_array( $action['record'] ) ) {
			throw new InvalidArgumentException( 'Action identity is not exact.' );
		}
		if ( 'approved' === $kind ) {
			$allowed = array( 'sku', 'approved', 'source', 'sheet_row', 'name', 'description', 'short_description', 'package_size', 'ingredients', 'allergens', 'supplement_facts', 'directions', 'warnings', 'seo_title', 'seo_description' );
			if ( 'approved' !== (string) $action['action'] || true !== ( $action['record']['approved'] ?? null ) ) {
				throw new InvalidArgumentException( 'Approved target is not an approved action.' );
			}
			foreach ( array_keys( $action['record'] ) as $field ) {
				if ( ! in_array( $field, $allowed, true ) ) {
					throw new InvalidArgumentException( 'Approved rehearsal forbids field: ' . $field );
				}
			}
			if ( ! array_key_exists( 'short_description', $action['record'] ) && ! array_key_exists( 'package_size', $action['record'] ) ) {
				throw new InvalidArgumentException( 'Approved rehearsal must make a narrow reversible text/meta change.' );
			}
		} else {
			$allowed = array( 'sku', 'source', 'row' );
			if ( 'withdrawal' !== (string) $action['action'] || array_diff( array_keys( $action['record'] ), $allowed ) ) {
				throw new InvalidArgumentException( 'Withdrawal must be explicit and contain identity/provenance only.' );
			}
		}
	}
	if ( 2 !== count( array_unique( $ids ) ) || 2 !== count( array_unique( $skus ) ) ) {
		throw new InvalidArgumentException( 'Targets must be two distinct products and SKUs.' );
	}
	return true;
}

function rhn_frh_validate_dry_results( $targets, $results ) {
	foreach ( $targets as $target ) {
		$sku = (string) $target['sku'];
		$outcome = (string) ( $results[ $sku ]['outcome'] ?? '' );
		if ( empty( $results[ $sku ]['verified'] ) || ! in_array( $outcome, array( 'planned_update' ), true ) ) {
			throw new RuntimeException( 'Dry plan did not produce the required real update for SKU ' . $sku . ': ' . $outcome );
		}
	}
	return true;
}
