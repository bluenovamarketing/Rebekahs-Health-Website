<?php
/** Account-specific read-only Google adapter and existing-reconcile bridge. */

defined( 'ABSPATH' ) || exit;

function rhn_full_range_google_default_transport( $method, $url, $args = array() ) {
	if ( 'GET' !== strtoupper( (string) $method ) ) {
		throw new InvalidArgumentException( 'Full-range Google transport is read-only.' );
	}
	$token = rhn_catalog_google_access_token();
	if ( is_wp_error( $token ) ) {
		throw new RuntimeException( $token->get_error_message() );
	}
	$response = wp_safe_remote_get(
		(string) $url,
		array(
			'timeout' => (int) ( $args['timeout'] ?? 20 ),
			'headers' => array( 'Authorization' => 'Bearer ' . $token ),
		)
	);
	if ( is_wp_error( $response ) ) {
		throw new RuntimeException( $response->get_error_message() );
	}
	return array(
		'status' => (int) wp_remote_retrieve_response_code( $response ),
		'body'   => json_decode( wp_remote_retrieve_body( $response ), true ),
	);
}

function rhn_full_range_google_request_json( $transport, $url ) {
	$response = call_user_func( $transport, 'GET', (string) $url, array( 'timeout' => 20 ) );
	if ( ! is_array( $response ) || 200 !== (int) ( $response['status'] ?? 0 ) || ! is_array( $response['body'] ?? null ) ) {
		throw new RuntimeException( 'Bounded Google read failed.' );
	}
	return $response['body'];
}

function rhn_full_range_google_values_url( $sheet_id, $range ) {
	return 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode( (string) $sheet_id ) . '/values/' . rawurlencode( (string) $range ) . '?majorDimension=ROWS';
}

function rhn_full_range_google_source_adapter( $transport = null, $config = array() ) {
	$transport = is_callable( $transport ) ? $transport : 'rhn_full_range_google_default_transport';
	$sheet_id = (string) ( $config['sheet_id'] ?? rhn_catalog_google_sheet_id() );
	$tab = (string) ( $config['tab'] ?? 'Catalog' );
	$columns = (string) ( $config['columns'] ?? 'A:W' );
	$max_rows = (int) ( $config['max_rows'] ?? 20000 );
	$discovery_page_size = (int) ( $config['discovery_page_size'] ?? 500 );
	if ( '' === $sheet_id || '' === $tab || ! preg_match( '/^[A-Z]+:[A-Z]+$/D', $columns ) || $max_rows < 1 || $max_rows > 20000 || $discovery_page_size < 1 || $discovery_page_size > 1000 ) {
		throw new InvalidArgumentException( 'Google source adapter configuration is invalid.' );
	}
	$quoted_tab = "'" . str_replace( "'", "''", $tab ) . "'";
	$column_parts = explode( ':', $columns, 2 );
	$first_column = $column_parts[0];
	$last_column = $column_parts[1];
	$cache = array( 'header' => null, 'total' => null, 'discovery_version' => null, 'grid_rows' => null );

	$get_version = static function () use ( $transport, $sheet_id ) {
		$url = 'https://www.googleapis.com/drive/v3/files/' . rawurlencode( $sheet_id ) . '?fields=id%2CmodifiedTime%2Cversion%2CheadRevisionId&supportsAllDrives=true';
		$body = rhn_full_range_google_request_json( $transport, $url );
		if ( (string) ( $body['id'] ?? '' ) !== $sheet_id || empty( $body['modifiedTime'] ) || ! isset( $body['version'] ) ) {
			throw new RuntimeException( 'Drive metadata did not provide an authoritative Sheet version.' );
		}
		return hash( 'sha256', rhn_full_range_json( array( 'id' => $body['id'], 'modifiedTime' => $body['modifiedTime'], 'version' => (string) $body['version'], 'headRevisionId' => (string) ( $body['headRevisionId'] ?? '' ) ) ) );
	};

	$get_metadata = static function () use ( &$cache, $transport, $sheet_id, $tab, $max_rows ) {
		if ( null !== $cache['grid_rows'] ) { return $cache['grid_rows']; }
		$url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode( $sheet_id ) . '?fields=spreadsheetId%2Csheets(properties(sheetId%2Ctitle%2CgridProperties(rowCount%2CcolumnCount)))';
		$body = rhn_full_range_google_request_json( $transport, $url );
		if ( (string) ( $body['spreadsheetId'] ?? '' ) !== $sheet_id ) {
			throw new RuntimeException( 'Sheets metadata identity mismatch.' );
		}
		foreach ( (array) ( $body['sheets'] ?? array() ) as $sheet ) {
			$properties = (array) ( $sheet['properties'] ?? array() );
			if ( $tab === (string) ( $properties['title'] ?? '' ) ) {
				$grid_rows = (int) ( $properties['gridProperties']['rowCount'] ?? 0 );
				if ( $grid_rows < 2 || $grid_rows - 1 > $max_rows ) {
					throw new RuntimeException( 'Catalog grid row count is outside the full-range safety ceiling.' );
				}
				$cache['grid_rows'] = $grid_rows;
				return $grid_rows;
			}
		}
		throw new RuntimeException( 'Catalog tab metadata was not found.' );
	};

	$read_range = static function ( $start_sheet_row, $end_sheet_row ) use ( $transport, $sheet_id, $quoted_tab, $first_column, $last_column ) {
		$range = $quoted_tab . '!' . $first_column . (int) $start_sheet_row . ':' . $last_column . (int) $end_sheet_row;
		$body = rhn_full_range_google_request_json( $transport, rhn_full_range_google_values_url( $sheet_id, $range ) );
		$values = $body['values'] ?? array();
		if ( ! is_array( $values ) || ! array_is_list( $values ) ) {
			throw new RuntimeException( 'Google values response is not a row list.' );
		}
		return $values;
	};

	$get_header = static function () use ( &$cache, $read_range ) {
		if ( null === $cache['header'] ) {
			$rows = $read_range( 1, 1 );
			if ( 1 !== count( $rows ) || ! is_array( $rows[0] ) || ! array_is_list( $rows[0] ) ) {
				throw new RuntimeException( 'Catalog header row is unavailable.' );
			}
			$cache['header'] = $rows[0];
		}
		return $cache['header'];
	};

	$normalize_rows = static function ( $values, $count ) use ( $get_header ) {
		$width = count( $get_header() );
		$rows = array();
		for ( $index = 0; $index < $count; $index++ ) {
			$row = isset( $values[ $index ] ) && is_array( $values[ $index ] ) ? array_values( $values[ $index ] ) : array();
			if ( count( $row ) > $width ) {
				throw new RuntimeException( 'Google returned more cells than the declared Catalog header.' );
			}
			$rows[] = array_pad( $row, $width, '' );
		}
		return $rows;
	};

	$get_total_rows = static function () use ( &$cache, $get_version, $get_metadata, $read_range, $normalize_rows, $discovery_page_size ) {
		if ( null !== $cache['total'] ) { return $cache['total']; }
		$version = $get_version();
		$data_capacity = $get_metadata() - 1;
		$last_nonempty = 0;
		for ( $start = 0; $start < $data_capacity; $start += $discovery_page_size ) {
			$count = min( $discovery_page_size, $data_capacity - $start );
			$values = $read_range( $start + 2, $start + $count + 1 );
			$rows = $normalize_rows( $values, $count );
			foreach ( $rows as $offset => $row ) {
				if ( array_filter( $row, static fn( $value ) => '' !== trim( (string) $value ) ) ) {
					$last_nonempty = $start + $offset + 1;
				}
			}
		}
		if ( $last_nonempty < 1 ) {
			throw new RuntimeException( 'Catalog has no nonempty data rows.' );
		}
		if ( ! hash_equals( $version, $get_version() ) ) {
			throw new RuntimeException( 'Source version changed during row-count discovery.' );
		}
		$cache['total'] = $last_nonempty;
		$cache['discovery_version'] = $version;
		return $last_nonempty;
	};

	$read_page = static function ( $start, $limit ) use ( $get_total_rows, $read_range, $normalize_rows ) {
		$start = (int) $start; $limit = (int) $limit;
		if ( $start < 0 || $limit < 1 || $start + $limit > $get_total_rows() ) {
			throw new InvalidArgumentException( 'Bounded Catalog page coordinates are invalid.' );
		}
		return $normalize_rows( $read_range( $start + 2, $start + $limit + 1 ), $limit );
	};

	$verify_action = static function ( $action ) use ( $get_header, $read_range, $normalize_rows ) {
		$row_number = (int) ( $action['source_row'] ?? 0 );
		if ( $row_number < 2 ) { return false; }
		$rows = $normalize_rows( $read_range( $row_number, $row_number ), 1 );
		$parsed = rhn_full_range_parse_sheet_page( $get_header(), $rows, $row_number - 2 );
		$sku = (string) ( $action['sku'] ?? '' );
		if ( 'approved' === (string) ( $action['action'] ?? '' ) ) {
			return isset( $parsed['registry'][ $sku ] ) && rhn_full_range_json( $parsed['registry'][ $sku ] ) === rhn_full_range_json( $action['record'] ?? null );
		}
		if ( 'withdrawal' === (string) ( $action['action'] ?? '' ) ) {
			return isset( $parsed['withdrawals'][ $sku ] ) && rhn_full_range_json( $parsed['withdrawals'][ $sku ] ) === rhn_full_range_json( $action['record'] ?? null );
		}
		return false;
	};

	return array(
		'get_version' => $get_version,
		'get_total_rows' => $get_total_rows,
		'get_header' => $get_header,
		'read_page' => $read_page,
		'verify_action' => $verify_action,
	);
}

function rhn_full_range_existing_reconcile_adapter( $write = false ) {
	$write = (bool) $write;
	if ( $write ) {
		rhn_full_range_require_feature();
		if ( ! defined( 'RHN_FULL_RANGE_CATALOG_APPLY_ENABLED' ) || true !== RHN_FULL_RANGE_CATALOG_APPLY_ENABLED ) {
			throw new RuntimeException( 'Full-range product application is separately disabled.' );
		}
	}
	return static function ( $action, $action_id ) use ( $write ) {
		$sku = (string) ( $action['sku'] ?? '' );
		$sheet = array( 'registry' => array(), 'withdrawals' => array(), 'skipped' => array() );
		if ( 'approved' === (string) ( $action['action'] ?? '' ) ) {
			$sheet['registry'][ $sku ] = (array) ( $action['record'] ?? array() );
		} elseif ( 'withdrawal' === (string) ( $action['action'] ?? '' ) ) {
			$sheet['withdrawals'][ $sku ] = (array) ( $action['record'] ?? array() );
		} else {
			throw new InvalidArgumentException( 'Unsupported full-range action.' );
		}
		$result = rhn_live_catalog_reconcile( $sheet, $write, 'full_range', array( 'max_changes' => 1, 'allow_keyed_errors' => true, 'persist_registry' => false ) );
		if ( is_wp_error( $result ) ) {
			$data = (array) $result->get_error_data();
			$receipt = (array) ( $data['receipt'] ?? array() );
			$outcome = (string) ( $receipt['outcomes'][ $sku ]['outcome'] ?? '' );
			if ( 'failed' === $outcome || ! empty( $receipt['runtime_errors'][ $sku ] ) ) {
				return array(
					'verified' => false, 'terminal' => false, 'retryable' => true,
					'action_id' => (string) $action_id, 'write' => $write,
					'outcome' => 'failed_runtime', 'detail' => (string) ( $receipt['runtime_errors'][ $sku ] ?? $result->get_error_message() ),
				);
			}
			if ( ! $receipt ) { throw new RuntimeException( $result->get_error_message() ); }
		} else {
			$receipt = (array) ( $result['receipt'] ?? array() );
		}
		$outcome = (string) ( $receipt['outcomes'][ $sku ]['outcome'] ?? '' );
		$terminal = array( 'applied_and_verified', 'already_correct', 'missing_remove_already_satisfied', 'review_unmanaged', 'review_unsupported_variation', 'failed_validation' );
		if ( ! $write ) { $terminal = array_merge( $terminal, array( 'planned_update', 'planned_create_quarantine' ) ); }
		$is_terminal = in_array( $outcome, $terminal, true );
		if ( $write && $is_terminal ) {
			$registry_ok = true;
			if ( 'approved' === (string) ( $action['action'] ?? '' ) && in_array( $outcome, array( 'applied_and_verified', 'already_correct' ), true ) ) {
				$registry_ok = rhn_full_range_registry_put( $sku, (array) ( $action['record'] ?? array() ) );
			} elseif ( 'withdrawal' === (string) ( $action['action'] ?? '' ) && in_array( $outcome, array( 'applied_and_verified', 'missing_remove_already_satisfied' ), true ) ) {
				$registry_ok = rhn_full_range_registry_put( $sku, null );
			}
			if ( ! $registry_ok ) {
				return array( 'verified' => false, 'terminal' => false, 'retryable' => true, 'action_id' => (string) $action_id, 'write' => true, 'outcome' => 'failed_registry_persistence', 'detail' => 'Atomic presentation-registry shard could not be persisted.' );
			}
		}
		return array(
			'verified' => $is_terminal,
			'terminal' => $is_terminal,
			'retryable' => false,
			'action_id' => (string) $action_id,
			'write' => $write,
			'outcome' => $outcome,
			'plan_fingerprint' => (string) ( $receipt['plan_fingerprint'] ?? '' ),
			'counts' => (array) ( $receipt['counts'] ?? array() ),
		);
	};
}

