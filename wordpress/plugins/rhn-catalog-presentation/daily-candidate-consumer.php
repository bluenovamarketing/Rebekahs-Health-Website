<?php
defined( 'ABSPATH' ) || exit;

function rhn_catalog_daily_intake_enabled() {
	if ( defined( 'RHN_CATALOG_DAILY_INTAKE_ENABLED' ) ) {
		return true === RHN_CATALOG_DAILY_INTAKE_ENABLED;
	}
	return 'yes' === (string) get_option( 'rhn_catalog_daily_intake_enabled', 'no' );
}

function rhn_catalog_daily_candidate_range() {
	return "'Intake Candidates'!A1:J";
}

function rhn_catalog_daily_normalize_name( $value ) {
	$value = strtolower( trim( (string) $value ) );
	return preg_replace( '/\s+/', ' ', $value );
}

/** Parse the append-only queue and classify only obvious, deterministic duplicates. */
function rhn_catalog_daily_candidate_plan( $candidate_rows, $product_rows ) {
	$plan = array( 'new' => array(), 'statuses' => array(), 'errors' => array() );
	$expected = array( 'Event Key', 'Message ID', 'Attachment SHA256', 'Report SHA256', 'Barcode', 'Name', 'Disposition', 'Prior Barcodes JSON', 'Status', 'Receipt UTC' );
	if ( ! is_array( $candidate_rows ) || ! isset( $candidate_rows[0] ) || array_slice( array_map( 'strval', $candidate_rows[0] ), 0, 10 ) !== $expected ) {
		$plan['errors'][] = 'Intake Candidates A:J headers do not match the reviewed queue contract.';
		return $plan;
	}
	if ( ! is_array( $product_rows ) || count( $product_rows ) < 2 ) {
		$plan['errors'][] = 'Product Review is unavailable.';
		return $plan;
	}

	$sheet_by_barcode = array();
	$sheet_by_name    = array();
	foreach ( array_slice( $product_rows, 1 ) as $cells ) {
		$barcode = trim( (string) ( $cells[2] ?? '' ) );
		$name    = rhn_catalog_daily_normalize_name( $cells[3] ?? '' );
		if ( '' !== $barcode ) {
			$sheet_by_barcode[ $barcode ] = true;
		}
		if ( '' !== $name ) {
			$sheet_by_name[ $name ] = true;
		}
	}

	$seen_events   = array();
	$claimed_skus  = array();
	$claimed_names = array();
	foreach ( array_slice( $candidate_rows, 1, null, true ) as $index => $cells ) {
		$row         = (int) $index;
		$row_number  = $row + 1;
		$event_key   = strtoupper( trim( (string) ( $cells[0] ?? '' ) ) );
		$attachment  = strtoupper( trim( (string) ( $cells[2] ?? '' ) ) );
		$report      = strtoupper( trim( (string) ( $cells[3] ?? '' ) ) );
		$barcode     = trim( (string) ( $cells[4] ?? '' ) );
		$name        = trim( (string) ( $cells[5] ?? '' ) );
		$disposition = sanitize_key( (string) ( $cells[6] ?? '' ) );
		$status      = trim( (string) ( $cells[8] ?? '' ) );
		if ( '' !== $status ) {
			continue;
		}
		if ( ! preg_match( '/^[A-F0-9]{64}$/', $event_key ) || ! preg_match( '/^[A-F0-9]{64}$/', $attachment ) || ! preg_match( '/^[A-F0-9]{64}$/', $report ) || '' === $barcode || '' === $name ) {
			$plan['errors'][] = 'Candidate row ' . $row_number . ' has an incomplete immutable receipt or identity.';
			continue;
		}
		if ( isset( $seen_events[ $event_key ] ) ) {
			$plan['statuses'][ $row ] = 'Replay no-op';
			continue;
		}
		$seen_events[ $event_key ] = true;
		$prior = json_decode( (string) ( $cells[7] ?? '[]' ), true );
		if ( ! is_array( $prior ) ) {
			$plan['errors'][] = 'Candidate row ' . $row_number . ' has invalid prior-barcode JSON.';
			continue;
		}
		$known_prior = false;
		foreach ( $prior as $prior_barcode ) {
			if ( isset( $sheet_by_barcode[ trim( (string) $prior_barcode ) ] ) ) {
				$known_prior = true;
				break;
			}
		}
		$normalized_name = rhn_catalog_daily_normalize_name( $name );
		if ( isset( $sheet_by_barcode[ $barcode ] ) ) {
			$plan['statuses'][ $row ] = 'Already present';
		} elseif ( $known_prior || 'hold_possible_barcode_transition' === $disposition ) {
			$plan['statuses'][ $row ] = 'Known alias no-op';
		} elseif ( isset( $sheet_by_name[ $normalized_name ] ) ) {
			$plan['statuses'][ $row ] = 'Existing name no-op';
		} elseif ( isset( $claimed_skus[ $barcode ] ) || isset( $claimed_names[ $normalized_name ] ) ) {
			$plan['statuses'][ $row ] = 'Queue duplicate no-op';
		} else {
			$claimed_skus[ $barcode ]      = true;
			$claimed_names[ $normalized_name ] = true;
			$plan['new'][ $barcode ] = array( 'id' => 0, 'sku' => $barcode, 'name' => $name );
			$plan['statuses'][ $row ] = 'Appended private';
		}
	}
	return $plan;
}

/** Add queue status receipts to the same atomic Google batch as private Sheet rows. */
function rhn_catalog_daily_status_requests( $statuses, $metadata ) {
	if ( ! isset( $metadata['Intake Candidates']['sheet_id'] ) ) {
		throw new InvalidArgumentException( 'Intake Candidates tab metadata is unavailable.' );
	}
	$requests = array();
	$receipt  = current_time( 'mysql', true );
	foreach ( $statuses as $row => $status ) {
		$requests[] = array(
			'updateCells' => array(
				'range'  => array(
					'sheetId'          => (int) $metadata['Intake Candidates']['sheet_id'],
					'startRowIndex'     => (int) $row,
					'endRowIndex'       => (int) $row + 1,
					'startColumnIndex'  => 8,
					'endColumnIndex'    => 10,
				),
				'rows'   => array( array( 'values' => array(
					array( 'userEnteredValue' => array( 'stringValue' => (string) $status ) ),
					array( 'userEnteredValue' => array( 'stringValue' => $receipt ) ),
				) ) ),
				'fields' => 'userEnteredValue',
			),
		);
	}
	return $requests;
}

/** Consume the daily queue as the only Product Review/Catalog writer. */
function rhn_catalog_run_daily_candidate_consumer() {
	if ( ! rhn_catalog_daily_intake_enabled() || ! rhn_catalog_discovery_write_enabled() ) {
		return array( 'write' => false, 'reason' => 'disabled' );
	}
	$writer_token = rhn_catalog_writer_lock_acquire( 'daily_candidate_consumer', 1800 );
	if ( is_wp_error( $writer_token ) ) {
		return $writer_token;
	}
	try {
		$candidate_rows = rhn_catalog_google_sheet_values( rhn_catalog_daily_candidate_range() );
		$product_rows   = rhn_catalog_google_sheet_values( "'Product Review'!A5:X" );
		if ( is_wp_error( $candidate_rows ) || is_wp_error( $product_rows ) ) {
			return is_wp_error( $candidate_rows ) ? $candidate_rows : $product_rows;
		}
		$daily_plan = rhn_catalog_daily_candidate_plan( $candidate_rows, $product_rows );
		if ( $daily_plan['errors'] ) {
			return new WP_Error( 'rhn_daily_candidate_plan', 'Daily intake validation failed; no Sheet cells were changed.', $daily_plan );
		}
		if ( ! $daily_plan['statuses'] ) {
			return array( 'write' => false, 'reason' => 'no_queued_candidates' );
		}

		$discovery_plan = rhn_catalog_discovery_plan( array_values( $daily_plan['new'] ), $product_rows );
		if ( $discovery_plan['errors'] || $discovery_plan['name_updates'] ) {
			return new WP_Error( 'rhn_daily_candidate_discovery', 'Daily intake could not build an append-only private plan.', $discovery_plan );
		}
		$catalog_rows = rhn_catalog_google_sheet_values( 'Catalog!A:W' );
		$metadata     = rhn_catalog_discovery_sheet_metadata();
		if ( is_wp_error( $catalog_rows ) || is_wp_error( $metadata ) ) {
			return is_wp_error( $catalog_rows ) ? $catalog_rows : $metadata;
		}
		try {
			$requests = rhn_catalog_discovery_batch_requests( $discovery_plan, $product_rows, $catalog_rows, $metadata, 'Keep Off Website' );
			$requests = array_merge( $requests, rhn_catalog_daily_status_requests( $daily_plan['statuses'], $metadata ) );
		} catch ( Throwable $error ) {
			return new WP_Error( 'rhn_daily_candidate_batch', $error->getMessage() );
		}
		$applied = rhn_catalog_discovery_apply_requests( $requests );
		if ( is_wp_error( $applied ) ) {
			return $applied;
		}

		$verified_products   = rhn_catalog_google_sheet_values( "'Product Review'!A5:X" );
		$verified_candidates = rhn_catalog_google_sheet_values( rhn_catalog_daily_candidate_range() );
		if ( is_wp_error( $verified_products ) || is_wp_error( $verified_candidates ) ) {
			return new WP_Error( 'rhn_daily_candidate_verify', 'Google accepted the atomic batch, but read-back verification failed.' );
		}
		$verified_plan = rhn_catalog_discovery_plan( array_values( $daily_plan['new'] ), $verified_products );
		if ( $verified_plan['errors'] || $verified_plan['new'] ) {
			return new WP_Error( 'rhn_daily_candidate_verify', 'Private Product Review read-back does not contain every planned barcode.', $verified_plan );
		}
		foreach ( $daily_plan['statuses'] as $row => $status ) {
			if ( (string) ( $verified_candidates[ $row ][8] ?? '' ) !== (string) $status ) {
				return new WP_Error( 'rhn_daily_candidate_verify', 'Candidate receipt read-back failed at row ' . ( (int) $row + 1 ) . '.' );
			}
		}
		update_option( 'rhn_catalog_daily_intake_health', array(
			'last_success' => current_time( 'mysql' ),
			'appended'     => count( $daily_plan['new'] ),
			'processed'    => count( $daily_plan['statuses'] ),
		), false );
		return array( 'write' => true, 'appended' => count( $daily_plan['new'] ), 'processed' => count( $daily_plan['statuses'] ) );
	} finally {
		rhn_catalog_writer_lock_release( $writer_token );
	}
}

if ( function_exists( 'add_action' ) ) {
	// Reuse the existing four-hour event after the catalog sync callback returns
	// and releases the same shared lease. No new schedule or staff step is added.
	add_action( 'rhn_catalog_sheet_sync_event', 'rhn_catalog_run_daily_candidate_consumer', 20 );
}
