<?php
defined( 'ABSPATH' ) || exit;

function rhn_fr_stage_exact_host() {
	$home = function_exists( 'home_url' ) ? home_url( '/' ) : get_option( 'home', '' );
	return is_ssl() && 'wordpress-1651482-6655800.cloudwaysapps.com' === strtolower( (string) wp_parse_url( $home, PHP_URL_HOST ) );
}

function rhn_fr_stage_require_access() {
	if ( ! rhn_fr_stage_exact_host() ) {
		throw new RuntimeException( 'Exact HTTPS Cloudways staging host is required.' );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		throw new RuntimeException( 'A staging administrator is required.' );
	}
}

function rhn_fr_stage_header_indexes( $header ) {
	$normalized = array();
	foreach ( $header as $index => $value ) {
		$normalized[ strtolower( trim( (string) $value ) ) ] = (int) $index;
	}
	$status = $normalized['status'] ?? $normalized['website status'] ?? null;
	$sku    = $normalized['sku / barcode'] ?? $normalized['sku'] ?? $normalized['barcode'] ?? null;
	if ( null === $status || null === $sku ) {
		throw new RuntimeException( 'Catalog header lacks Status or SKU / Barcode.' );
	}
	return array( 'status' => $status, 'sku' => $sku );
}

function rhn_fr_stage_plan_rows( $header, $rows ) {
	$indexes = rhn_fr_stage_header_indexes( $header );
	$seen    = array();
	$report  = array(
		'total_source_rows'  => count( $rows ),
		'approved'           => 0,
		'withdrawals'        => 0,
		'not_approved'       => 0,
		'blank_identity'     => 0,
		'duplicate_identity' => 0,
	);
	foreach ( $rows as $row ) {
		$row    = is_array( $row ) ? $row : array();
		$status = strtolower( trim( (string) ( $row[ $indexes['status'] ] ?? '' ) ) );
		$sku    = trim( (string) ( $row[ $indexes['sku'] ] ?? '' ) );
		if ( '' === $sku ) {
			$report['blank_identity']++;
			continue;
		}
		if ( isset( $seen[ $sku ] ) ) {
			$report['duplicate_identity']++;
			continue;
		}
		$seen[ $sku ] = true;
		if ( in_array( $status, array( 'approved', 'publish' ), true ) ) {
			$report['approved']++;
		} elseif ( in_array( $status, array( 'remove from website', 'remove' ), true ) ) {
			$report['withdrawals']++;
		} else {
			$report['not_approved']++;
		}
	}
	$report['identity_fingerprint_sha256'] = hash( 'sha256', implode( "\n", array_keys( $seen ) ) );
	return $report;
}

/** Bounded Google read plus source-only planning. No Woo or catalog mutation path exists in this package. */
function rhn_fr_stage_prepare() {
	rhn_fr_stage_require_access();
	$sheet_id = function_exists( 'rhn_catalog_google_sheet_id' ) ? (string) rhn_catalog_google_sheet_id() : '';
	if ( '18wuB-kfgMfKbWGV6qgHClXDjagtPPJP7XdkHlxQhF-s' !== $sheet_id ) {
		throw new RuntimeException( 'Configured Sheet is not the approved inventory catalog.' );
	}
	$token     = rhn_fr_stage_google_access_token();
	$drive_url = 'https://www.googleapis.com/drive/v3/files/' . rawurlencode( $sheet_id ) . '?fields=id%2CmodifiedTime%2Cversion%2CheadRevisionId&supportsAllDrives=true';
	$before    = rhn_fr_stage_google_json_get( $drive_url, $token );
	if ( (string) ( $before['id'] ?? '' ) !== $sheet_id || ! isset( $before['version'], $before['modifiedTime'] ) ) {
		throw new RuntimeException( 'Drive metadata response is incomplete.' );
	}
	$meta_url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode( $sheet_id ) . '?fields=spreadsheetId%2Csheets(properties(title%2CgridProperties(rowCount%2CcolumnCount)))';
	$metadata = rhn_fr_stage_google_json_get( $meta_url, $token );
	$catalog  = null;
	foreach ( (array) ( $metadata['sheets'] ?? array() ) as $sheet ) {
		$properties = (array) ( $sheet['properties'] ?? array() );
		if ( 'Catalog' === (string) ( $properties['title'] ?? '' ) ) {
			$catalog = $properties;
			break;
		}
	}
	$grid_rows = (int) ( $catalog['gridProperties']['rowCount'] ?? 0 );
	$grid_cols = (int) ( $catalog['gridProperties']['columnCount'] ?? 0 );
	if ( $grid_rows < 2 || $grid_rows > 20001 || $grid_cols < 2 || $grid_cols > 64 ) {
		throw new RuntimeException( 'Catalog dimensions are outside the preparation ceiling.' );
	}
	$header_rows = rhn_fr_stage_google_values( $sheet_id, "'Catalog'!A1:W1", $token );
	if ( 1 !== count( $header_rows ) ) {
		throw new RuntimeException( 'Catalog header read was incomplete.' );
	}
	$header = array_values( $header_rows[0] );
	$rows   = array();
	$pages  = 0;
	for ( $start = 2; $start <= $grid_rows; $start += 500 ) {
		$end  = min( $grid_rows, $start + 499 );
		$page = rhn_fr_stage_google_values( $sheet_id, "'Catalog'!A{$start}:W{$end}", $token );
		foreach ( $page as $row ) {
			$rows[] = array_pad( array_slice( array_values( (array) $row ), 0, 23 ), 23, '' );
		}
		$pages++;
	}
	while ( $rows && ! array_filter( end( $rows ), static fn( $value ) => '' !== trim( (string) $value ) ) ) {
		array_pop( $rows );
	}
	$after = rhn_fr_stage_google_json_get( $drive_url, $token );
	$token = '';
	$version       = hash( 'sha256', wp_json_encode( array( (string) $before['version'], (string) $before['modifiedTime'], (string) ( $before['headRevisionId'] ?? '' ) ) ) );
	$after_version = hash( 'sha256', wp_json_encode( array( (string) ( $after['version'] ?? '' ), (string) ( $after['modifiedTime'] ?? '' ), (string) ( $after['headRevisionId'] ?? '' ) ) ) );
	if ( ! hash_equals( $version, $after_version ) ) {
		throw new RuntimeException( 'Drive version changed during the bounded read.' );
	}
	$report = rhn_fr_stage_plan_rows( $header, $rows );
	$report['read_only'] = true;
	$report['pages'] = $pages;
	$report['max_page_rows'] = 500;
	$report['source_version_sha256'] = $version;
	$report['prepared_at_utc'] = gmdate( 'c' );
	$preview_id = bin2hex( random_bytes( 16 ) );
	$manifest = array( 'preview_id' => $preview_id, 'source_version_sha256' => $version, 'report_sha256' => hash( 'sha256', wp_json_encode( $report ) ) );
	if ( ! rhn_fr_stage_store_create( $preview_id, 'manifest', $manifest ) || ! rhn_fr_stage_store_create( $preview_id, 'report', $report ) ) {
		rhn_fr_stage_cleanup_preview( $preview_id );
		throw new RuntimeException( 'Could not atomically capture the preparation report.' );
	}
	$captured = rhn_fr_stage_store_get( $preview_id, 'report' );
	if ( ! is_array( $captured ) || ! hash_equals( $manifest['report_sha256'], hash( 'sha256', wp_json_encode( $captured ) ) ) ) {
		rhn_fr_stage_cleanup_preview( $preview_id );
		throw new RuntimeException( 'Captured preparation report failed verification.' );
	}
	$cleanup = rhn_fr_stage_cleanup_preview( $preview_id );
	if ( in_array( false, $cleanup, true ) ) {
		throw new RuntimeException( 'Verified report was captured, but isolated preview cleanup needs administrator retry.' );
	}
	$captured['preview_id'] = $preview_id;
	$captured['preview_cleanup'] = 'complete';
	return $captured;
}

function rhn_fr_stage_cleanup_requested_preview( $preview_id ) {
	rhn_fr_stage_require_access();
	if ( ! preg_match( '/^[a-f0-9]{32}$/', (string) $preview_id ) ) {
		throw new RuntimeException( 'A valid isolated preview ID is required.' );
	}
	return rhn_fr_stage_cleanup_preview( $preview_id );
}

function rhn_fr_stage_render_page() {
	try {
		rhn_fr_stage_require_access();
	} catch ( Throwable $error ) {
		wp_die( esc_html( $error->getMessage() ), 403 );
	}
	echo '<div class="wrap"><h1>RHN staging preparation</h1><p>This validator performs bounded Google reads and source-only planning. It contains no processor, scheduler, Woo write, or catalog apply functions.</p>';
	if ( 'POST' === strtoupper( (string) ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) && isset( $_POST['rhn_fr_stage_prepare'] ) ) {
		check_admin_referer( 'rhn_fr_stage_prepare' );
		try {
			$result = rhn_fr_stage_prepare();
			echo '<div class="notice notice-success"><p>Preparation completed and isolated preview state was cleaned up.</p></div><pre>' . esc_html( wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) ) . '</pre>';
		} catch ( Throwable $error ) {
			echo '<div class="notice notice-error"><p><strong>Stopped safely:</strong> ' . esc_html( $error->getMessage() ) . '</p></div>';
		}
	}
	if ( 'POST' === strtoupper( (string) ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) && isset( $_POST['rhn_fr_stage_cleanup'] ) ) {
		check_admin_referer( 'rhn_fr_stage_cleanup' );
		try {
			$preview_id = sanitize_text_field( wp_unslash( $_POST['rhn_fr_stage_preview_id'] ?? '' ) );
			$cleanup = rhn_fr_stage_cleanup_requested_preview( $preview_id );
			echo '<div class="notice notice-success"><p>Exact isolated preview cleanup checked.</p></div><pre>' . esc_html( wp_json_encode( $cleanup, JSON_PRETTY_PRINT ) ) . '</pre>';
		} catch ( Throwable $error ) {
			echo '<div class="notice notice-error"><p><strong>Cleanup stopped safely:</strong> ' . esc_html( $error->getMessage() ) . '</p></div>';
		}
	}
	echo '<form method="post">';
	wp_nonce_field( 'rhn_fr_stage_prepare' );
	submit_button( 'Run read-only staging preparation', 'primary', 'rhn_fr_stage_prepare' );
	echo '</form><hr><h2>Cleanup an isolated preview</h2><form method="post">';
	wp_nonce_field( 'rhn_fr_stage_cleanup' );
	echo '<label>Preview ID <input name="rhn_fr_stage_preview_id" pattern="[a-f0-9]{32}" maxlength="32" required></label> ';
	submit_button( 'Clean exact preview state', 'secondary', 'rhn_fr_stage_cleanup', false );
	echo '</form></div>';
}
