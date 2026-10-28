<?php

define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );
define( 'RHN_FULL_RANGE_CATALOG_TESTING', true );
if ( ! function_exists( 'wp_json_encode' ) ) {
	function wp_json_encode( $value, $flags = 0 ) { return json_encode( $value, $flags | JSON_THROW_ON_ERROR ); }
}
if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $value ) { return trim( strip_tags( (string) $value ) ); }
}

$production_root = $argv[1] ?? 'C:\\Users\\todda\\Documents\\Codex\\2026-10-27\\task\\rhn-live-catalog-processor';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'registry-import.php';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'google-sheet-sync.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog-runtime.php';

$assertions = 0;
function runtime_check( $condition, $message ) {
	global $assertions;
	$assertions++;
	if ( ! $condition ) { throw new RuntimeException( 'FAIL: ' . $message ); }
}
function runtime_exception( $callback, $contains ) {
	global $assertions;
	$assertions++;
	try { $callback(); } catch ( Throwable $error ) {
		if ( false !== strpos( $error->getMessage(), $contains ) ) { return; }
		throw new RuntimeException( 'FAIL: expected "' . $contains . '", got "' . $error->getMessage() . '".' );
	}
	throw new RuntimeException( 'FAIL: expected exception containing "' . $contains . '".' );
}

function runtime_file_store( $directory ) {
	if ( ! is_dir( $directory ) && ! mkdir( $directory, 0700, true ) && ! is_dir( $directory ) ) {
		throw new RuntimeException( 'Could not create test store.' );
	}
	$path = static fn( $key ) => $directory . DIRECTORY_SEPARATOR . hash( 'sha256', (string) $key ) . '.json';
	$lock_path = static fn( $key ) => $directory . DIRECTORY_SEPARATOR . hash( 'sha256', (string) $key ) . '.lock';
	$get = static function ( $key ) use ( $path ) {
		$file = $path( $key );
		if ( ! is_file( $file ) ) { return null; }
		return json_decode( file_get_contents( $file ), true, 64, JSON_THROW_ON_ERROR );
	};
	return array(
		'get' => $get,
		'put_if_absent' => static function ( $key, $value ) use ( $path ) {
			$handle = @fopen( $path( $key ), 'x' );
			if ( false === $handle ) { return false; }
			try { fwrite( $handle, rhn_full_range_json( $value ) ); fflush( $handle ); } finally { fclose( $handle ); }
			return true;
		},
		'compare_and_swap' => static function ( $key, $expected, $replacement ) use ( $path, $lock_path, $get ) {
			$lock = fopen( $lock_path( $key ), 'c+' );
			if ( false === $lock || ! flock( $lock, LOCK_EX ) ) { return false; }
			try {
				$current = $get( $key );
				if ( rhn_full_range_json( $current ) !== rhn_full_range_json( $expected ) ) { return false; }
				file_put_contents( $path( $key ), rhn_full_range_json( $replacement ), LOCK_EX );
				return rhn_full_range_json( $get( $key ) ) === rhn_full_range_json( $replacement );
			} finally { flock( $lock, LOCK_UN ); fclose( $lock ); }
		},
		'delete' => static function ( $key, $expected = null ) use ( $path, $lock_path, $get ) {
			$lock = fopen( $lock_path( $key ), 'c+' );
			if ( false === $lock || ! flock( $lock, LOCK_EX ) ) { return false; }
			try {
				if ( null !== $expected && rhn_full_range_json( $get( $key ) ) !== rhn_full_range_json( $expected ) ) { return false; }
				$file = $path( $key );
				return ! is_file( $file ) || unlink( $file );
			} finally { flock( $lock, LOCK_UN ); fclose( $lock ); }
		},
	);
}

function runtime_remove_tree( $directory ) {
	if ( ! is_dir( $directory ) ) { return; }
	foreach ( new FilesystemIterator( $directory ) as $item ) {
		if ( $item->isDir() ) { runtime_remove_tree( $item->getPathname() ); } else { unlink( $item->getPathname() ); }
	}
	rmdir( $directory );
}

function runtime_row( $header, $values ) {
	$row = array();
	foreach ( $header as $heading ) { $row[] = (string) ( $values[ $heading ] ?? '' ); }
	return $row;
}

$header = array_keys( rhn_catalog_sheet_headers() );
$rows = array();
for ( $i = 0; $i < 14032; $i++ ) {
	$sku = str_pad( (string) $i, 12, '0', STR_PAD_LEFT );
	if ( $i < 1205 ) {
		$values = array(
			'Status' => 'Approved', 'SKU / Barcode' => $sku, 'Product Name' => 'Product ' . $i,
			'Source / Evidence' => 'Fixture row ' . ( $i + 2 ),
		);
		if ( 0 === $i ) {
			$values += array(
				'Brand' => 'Full Field Brand', 'Website Categories' => "Supplements|Wellness",
				'Long Description' => 'Long client description', 'Short Description' => 'Short client description',
				'Packaged Weight' => '4.5', 'Weight Unit' => 'oz', 'Package Size / Net Contents' => '1 fl oz',
				'Featured Image URL' => 'https://example.com/featured.jpg',
				'Gallery Image URLs' => "https://example.com/a.jpg|https://example.com/b.jpg",
				'Ingredients' => 'Ingredient A', 'Allergens' => 'None', 'Supplement Facts' => 'Facts',
				'Directions' => 'Use as directed', 'Warnings' => 'Keep away from children',
				'SEO Title' => 'SEO title', 'SEO Description' => 'SEO description',
			);
		}
		$rows[] = runtime_row( $header, $values );
	} elseif ( $i < 1242 ) {
		$rows[] = runtime_row( $header, array( 'Status' => 'Remove from Website', 'SKU / Barcode' => $sku, 'Source / Evidence' => 'Explicit withdrawal ' . ( $i + 2 ) ) );
	} else {
		$rows[] = runtime_row( $header, array( 'Status' => 'Working / Keep Off Website', 'SKU / Barcode' => $sku, 'Product Name' => 'Review only ' . $i ) );
	}
}

$version = 'sheet-v1';
$source = array(
	'get_version' => static function () use ( &$version ) { return $version; },
	'get_total_rows' => static fn() => 14032,
	'get_header' => static fn() => $header,
	'read_page' => static fn( $start, $limit ) => array_slice( $rows, $start, $limit ),
);

$store_directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'rhn-full-range-' . bin2hex( random_bytes( 8 ) );
try {
	$store = runtime_file_store( $store_directory );
	runtime_exception( static fn() => rhn_full_range_prepare_snapshot_if_enabled( $source, $store, 'fixture' ), 'disabled' );
	$partial = rhn_full_range_prepare_snapshot_step( $source, $store, 'fixture', 500, 7, 'worker-a' );
	runtime_check( 'partial' === $partial['status'] && 3500 === $partial['manifest']['next_row'], 'Bounded preparation stops durably after seven pages.' );
	runtime_check( null === rhn_full_range_store_get( $store, 'rhn-full-range:fixture:final' ), 'Partial scan never publishes a final snapshot.' );
	runtime_exception( static fn() => rhn_full_range_finalize_manifest( $partial['manifest'] ), 'Incomplete source scan refused' );

	// Recreate every callback to simulate a fresh PHP process resuming disk state.
	$store = runtime_file_store( $store_directory );
	do { $result = rhn_full_range_prepare_snapshot_step( $source, $store, 'fixture', 500, 5, 'worker-b' ); } while ( 'partial' === $result['status'] );
	$final = $result['final'];
	runtime_check( 14032 === $final['total_source_rows'] && 29 === $final['pages'], 'All 14,032 rows finish in 29 bounded pages.' );
	runtime_check( 1205 === $final['approved'] && 37 === $final['withdrawals'] && 12790 === $final['skipped'], 'Approved, explicit withdrawal, and review-only totals are exact.' );

	$chunk0 = rhn_full_range_store_get( $store, 'rhn-full-range:fixture:chunk:0' );
	runtime_check( isset( $chunk0['payload']['registry']['000000000000'] ), 'Leading-zero SKU remains an exact text key.' );
	$full = $chunk0['payload']['registry']['000000000000'];
	foreach ( array( 'name', 'brand', 'description', 'short_description', 'categories', 'weight', 'weight_unit', 'package_size', 'featured_image_url', 'gallery_image_urls', 'ingredients', 'allergens', 'supplement_facts', 'directions', 'warnings', 'seo_title', 'seo_description', 'sheet_row' ) as $field ) {
		runtime_check( array_key_exists( $field, $full ), 'Real Sheet parser preserves approved field: ' . $field );
	}
	runtime_check( 2 === $full['sheet_row'], 'Global Sheet row provenance is preserved.' );

	$tampered = $chunk0['payload'];
	$tampered['registry']['000000000000']['description'] = 'Changed after preparation';
	runtime_exception( static fn() => rhn_full_range_validate_page_payload( $chunk0['page'], $tampered ), 'payload hash mismatch' );
	$changed_bundle = rhn_full_range_prepare_page_bundle( 'sheet-v1', 0, 1, array( '000000000000' => array( 'name' => 'Changed', 'sheet_row' => 2 ) ), array(), array(), true );
	$original_bundle = rhn_full_range_prepare_page_bundle( 'sheet-v1', 0, 1, array( '000000000000' => array( 'name' => 'Original', 'sheet_row' => 2 ) ), array(), array(), true );
	runtime_check( $changed_bundle['page']['payload_hash'] !== $original_bundle['page']['payload_hash'], 'Client field changes alter the canonical payload hash.' );
	$skipped_a = rhn_full_range_prepare_page_bundle( 'sheet-v1', 0, 1, array(), array(), array( array( 'row' => 2, 'reason' => 'review only' ) ), true, array( array( 'Working', 'Original skipped content' ) ) );
	$skipped_b = rhn_full_range_prepare_page_bundle( 'sheet-v1', 0, 1, array(), array(), array( array( 'row' => 2, 'reason' => 'review only' ) ), true, array( array( 'Working', 'Changed skipped content' ) ) );
	runtime_check( $skipped_a['page']['payload_hash'] !== $skipped_b['page']['payload_hash'], 'Complete source-row hashes bind skipped-row content too.' );

	runtime_exception(
		static fn() => rhn_full_range_prepare_page( 'v', 0, 2, array(), array(), array( array( 'row' => 2, 'reason' => 'one only' ) ), true ),
		'accounted for exactly once'
	);
	runtime_exception(
		static fn() => rhn_full_range_prepare_page( 'v', 0, 2, array(), array(), array( array( 'row' => 2, 'reason' => 'first' ), array( 'row' => 2, 'reason' => 'duplicate' ) ), true ),
		'more than once'
	);
	$order_manifest = rhn_full_range_new_manifest( 'order-v1', 2, 1 );
	runtime_exception(
		static fn() => rhn_full_range_advance_manifest( $order_manifest, rhn_full_range_prepare_page( 'order-v1', 1, 1, array(), array(), array( array( 'row' => 3, 'reason' => 'skip' ) ), false ) ),
		'not contiguous'
	);
	runtime_exception(
		static fn() => rhn_full_range_advance_manifest( $order_manifest, rhn_full_range_prepare_page( 'order-v1', 0, 1, array(), array(), array( array( 'row' => 2, 'reason' => 'skip' ) ), true ) ),
		'does not exhaust'
	);
	$one_row_manifest = rhn_full_range_new_manifest( 'order-v1', 1, 1 );
	runtime_exception(
		static fn() => rhn_full_range_advance_manifest( $one_row_manifest, rhn_full_range_prepare_page( 'order-v1', 0, 1, array(), array(), array( array( 'row' => 2, 'reason' => 'skip' ) ), false ) ),
		'must be marked terminal'
	);

	$replay = rhn_full_range_prepare_snapshot_step( $source, runtime_file_store( $store_directory ), 'fixture', 500, 2, 'worker-c' );
	runtime_check( 'prepared' === $replay['status'] && $replay['final']['snapshot_hash'] === $final['snapshot_hash'], 'Replaying an identical finalized source is idempotent.' );

	$action_source = array(
		'get_version' => static function () use ( &$version ) { return $version; },
		'verify_action' => static function ( $action ) use ( &$version ) { return isset( $action['record'] ) && 'sheet-v1' === $version; },
	);
	$applied = array();
	$apply = static function ( $action, $action_id ) use ( &$applied ) {
		$applied[] = array( $action['sku'], $action_id, $action['record'] );
		return array( 'verified' => true, 'terminal' => true, 'retryable' => false, 'outcome' => 'fixture no-write' );
	};
	$first_action = rhn_full_range_process_next_action( $action_source, $store, 'fixture', $apply, 'action-a' );
	runtime_check( '000000000000' === $first_action['action']['sku'] && 1 === count( $applied ), 'Action cursor supplies one exact verified SKU per invocation.' );
	$second_action = rhn_full_range_process_next_action( $action_source, runtime_file_store( $store_directory ), 'fixture', $apply, 'action-b' );
	runtime_check( '000000000001' === $second_action['action']['sku'] && 2 === count( $applied ), 'Action cursor resumes durably without replaying the prior action.' );
	$cursor_before = rhn_full_range_store_get( $store, 'rhn-full-range:fixture:cursor' );
	runtime_exception(
		static fn() => rhn_full_range_process_next_action( $action_source, $store, 'fixture', static fn() => array( 'verified' => false ), 'action-c' ),
		'not verified'
	);
	$cursor_after = rhn_full_range_store_get( $store, 'rhn-full-range:fixture:cursor' );
	runtime_check( rhn_full_range_json( $cursor_before ) === rhn_full_range_json( $cursor_after ), 'Unverified action never advances the cursor.' );

	$version = 'sheet-v2';
	runtime_exception( static fn() => rhn_full_range_process_next_action( $action_source, $store, 'fixture', $apply, 'action-d' ), 'differs from the prepared snapshot' );
	$version = 'sheet-v1';

	$drift_version = 'drift-v1';
	$drift_rows = array_slice( $rows, 0, 2 );
	$drift_source = array(
		'get_version' => static function () use ( &$drift_version ) { return $drift_version; },
		'get_total_rows' => static fn() => 2,
		'get_header' => static fn() => $header,
		'read_page' => static function ( $start, $limit ) use ( $drift_rows, &$drift_version ) { $drift_version = 'drift-v2'; return array_slice( $drift_rows, $start, $limit ); },
	);
	runtime_exception( static fn() => rhn_full_range_prepare_snapshot_step( $drift_source, $store, 'drift', 2, 1, 'drift-worker' ), 'changed while reading' );
	$drift_manifest = rhn_full_range_store_get( $store, 'rhn-full-range:drift:manifest' );
	runtime_check( 0 === $drift_manifest['next_row'], 'Version drift leaves the durable manifest unadvanced.' );

	$short_source = array(
		'get_version' => static fn() => 'short-v1', 'get_total_rows' => static fn() => 2,
		'get_header' => static fn() => $header, 'read_page' => static fn() => array( $rows[0] ),
	);
	runtime_exception( static fn() => rhn_full_range_prepare_snapshot_step( $short_source, $store, 'short', 2, 1, 'short-worker' ), 'did not return every declared row' );

	$lock = rhn_full_range_acquire_lock( $store, 'rhn-full-range:locked', 'first-worker' );
	runtime_exception( static fn() => rhn_full_range_prepare_snapshot_step( $short_source, $store, 'locked', 2, 1, 'second-worker' ), 'Another full-range worker' );
	rhn_full_range_release_lock( $store, 'rhn-full-range:locked', $lock );

	echo 'PASS: ' . $assertions . " runtime assertions; durable 14,032-row real-parser snapshot, field hashes, resume, drift, lock, and cursor guards.\n";
} finally {
	runtime_remove_tree( $store_directory );
}

