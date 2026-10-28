<?php

define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );

if ( ! function_exists( 'wp_json_encode' ) ) {
	function wp_json_encode( $value, $flags = 0 ) { return json_encode( $value, $flags | JSON_THROW_ON_ERROR ); }
}
if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $value ) { return trim( strip_tags( (string) $value ) ); }
}

$production_root = $argv[1] ?? 'C:\\Users\\todda\\Documents\\Codex\\2026-10-27\\task\\rhn-live-catalog-processor';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'registry-import.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog.php';

$tests = 0;
function check( $condition, $message ) {
	global $tests;
	$tests++;
	if ( ! $condition ) { throw new RuntimeException( 'FAIL: ' . $message ); }
}
function expect_exception( $callback, $contains ) {
	global $tests;
	$tests++;
	try { $callback(); } catch ( Throwable $error ) {
		if ( false !== strpos( $error->getMessage(), $contains ) ) { return; }
		throw new RuntimeException( 'FAIL: expected exception containing "' . $contains . '", got "' . $error->getMessage() . '".' );
	}
	throw new RuntimeException( 'FAIL: expected exception containing "' . $contains . '".' );
}
function approved_record( $index ) {
	return array(
		'sku'       => str_pad( (string) $index, 12, '0', STR_PAD_LEFT ),
		'approved'  => true,
		'source'    => 'Synthetic full-range fixture row ' . ( $index + 2 ),
		'name'      => 'Synthetic product ' . $index,
		'sheet_row' => $index + 2,
	);
}
function build_fixture( $total = 14032, $approved_count = 1205, $withdrawal_count = 37 ) {
	$rows = array();
	for ( $i = 0; $i < $total; $i++ ) {
		$sku = str_pad( (string) $i, 12, '0', STR_PAD_LEFT );
		if ( $i < $approved_count ) {
			$rows[] = array( 'kind' => 'approved', 'sku' => $sku, 'record' => approved_record( $i ) );
		} elseif ( $i < $approved_count + $withdrawal_count ) {
			$rows[] = array( 'kind' => 'withdrawal', 'sku' => $sku, 'record' => array( 'sku' => $sku, 'status' => 'Remove from Website', 'source' => 'Explicit fixture status', 'row' => $i + 2 ) );
		} else {
			$rows[] = array( 'kind' => 'skipped', 'sku' => $sku, 'record' => array( 'row' => $i + 2, 'reason' => 'Working / Keep Off Website or lifecycle review only.' ) );
		}
	}
	return $rows;
}
function run_fixture( $fixture, $version = 'sheet-version-42', $page_size = 500, $stop_after_pages = null ) {
	$manifest = rhn_full_range_new_manifest( $version, count( $fixture ), $page_size );
	$pages = array_chunk( $fixture, $page_size );
	foreach ( $pages as $page_index => $rows ) {
		if ( null !== $stop_after_pages && $page_index >= $stop_after_pages ) { break; }
		$registry = array(); $withdrawals = array(); $skipped = array();
		foreach ( $rows as $row ) {
			if ( 'approved' === $row['kind'] ) { $registry[ $row['sku'] ] = $row['record']; }
			elseif ( 'withdrawal' === $row['kind'] ) { $withdrawals[ $row['sku'] ] = $row['record']; }
			else { $skipped[] = $row['record']; }
		}
		$page = rhn_full_range_prepare_page( $version, $page_index * $page_size, count( $rows ), $registry, $withdrawals, $skipped, $page_index === count( $pages ) - 1 );
		$manifest = rhn_full_range_advance_manifest( $manifest, $page );
		// Exercise serialization between every page, matching resumable option storage.
		$manifest = json_decode( json_encode( $manifest, JSON_THROW_ON_ERROR ), true, 32, JSON_THROW_ON_ERROR );
	}
	return $manifest;
}

$fixture = build_fixture();
$approved_records = array_map( static fn( $row ) => $row['record'], array_slice( $fixture, 0, 1205 ) );

expect_exception(
	static fn() => rhn_catalog_parse_registry( wp_json_encode( $approved_records ) ),
	'1000 records'
);
$chunked = rhn_full_range_parse_registry_records( $approved_records, 500 );
check( 1205 === count( $chunked ), '1,205 approved records parse in guarded chunks.' );
check( isset( $chunked['000000000000'] ), 'Leading-zero SKU survives chunk parsing exactly.' );

$large = array();
for ( $i = 0; $i < 24; $i++ ) {
	$record = approved_record( 5000 + $i );
	$record['description'] = str_repeat( 'x', 95000 );
	$large[] = $record;
}
expect_exception(
	static fn() => rhn_catalog_parse_registry( wp_json_encode( $large ) ),
	'2 MB'
);
check( 24 === count( rhn_full_range_parse_registry_records( $large, 8 ) ), 'Per-chunk 2 MB guard remains enforced while aggregate preparation can continue.' );

$manifest_a = run_fixture( $fixture );
$final_a = rhn_full_range_finalize_manifest( $manifest_a );
check( 14032 === $final_a['total_source_rows'], 'Full 14,032-row fixture is exhausted.' );
check( 29 === $final_a['pages'], '14,032 rows use 29 bounded pages of at most 500.' );
check( 1205 === $final_a['approved'], 'Approved total is preserved.' );
check( 37 === $final_a['withdrawals'], 'Only explicit Remove from Website rows become withdrawals.' );
check( 12790 === $final_a['skipped'], 'Unapproved, inactive, or absent review rows do not become removals.' );

$manifest_b = run_fixture( $fixture );
$final_b = rhn_full_range_finalize_manifest( $manifest_b );
check( $final_a['chain_hash'] === $final_b['chain_hash'], 'Identical input is deterministic and idempotent.' );

$partial = run_fixture( $fixture, 'sheet-version-42', 500, 7 );
expect_exception( static fn() => rhn_full_range_finalize_manifest( $partial ), 'Incomplete source scan refused' );
$resumed = $partial;
$pages = array_chunk( $fixture, 500 );
for ( $page_index = 7; $page_index < count( $pages ); $page_index++ ) {
	$registry = array(); $withdrawals = array(); $skipped = array();
	foreach ( $pages[ $page_index ] as $row ) {
		if ( 'approved' === $row['kind'] ) { $registry[ $row['sku'] ] = $row['record']; }
		elseif ( 'withdrawal' === $row['kind'] ) { $withdrawals[ $row['sku'] ] = $row['record']; }
		else { $skipped[] = $row['record']; }
	}
	$page = rhn_full_range_prepare_page( 'sheet-version-42', $page_index * 500, count( $pages[ $page_index ] ), $registry, $withdrawals, $skipped, $page_index === count( $pages ) - 1 );
	$resumed = rhn_full_range_advance_manifest( $resumed, $page );
}
check( $final_a['chain_hash'] === rhn_full_range_finalize_manifest( $resumed )['chain_hash'], 'Interrupted scan resumes to the same verified manifest.' );

$one_page = array_slice( $fixture, 0, 500 );
$registry = array();
foreach ( $one_page as $row ) { $registry[ $row['sku'] ] = $row['record']; }
$m = rhn_full_range_new_manifest( 'v1', 1000, 500 );
$p1 = rhn_full_range_prepare_page( 'v1', 0, 500, $registry, array(), array(), false );
$m = rhn_full_range_advance_manifest( $m, $p1 );
$drift_skipped = array();
for ( $row = 502; $row <= 1001; $row++ ) { $drift_skipped[] = array( 'row' => $row, 'reason' => 'skip' ); }
expect_exception(
	static fn() => rhn_full_range_advance_manifest( $m, rhn_full_range_prepare_page( 'v2', 500, 500, array(), array(), $drift_skipped, true ) ),
	'Source version changed'
);

$duplicate_registry = array( '000000000000' => approved_record( 0 ) );
$duplicate_registry['000000000000']['sheet_row'] = 502;
$duplicate_skipped = array();
for ( $row = 503; $row <= 1001; $row++ ) { $duplicate_skipped[] = array( 'row' => $row, 'reason' => 'skip' ); }
expect_exception(
	static fn() => rhn_full_range_advance_manifest( $m, rhn_full_range_prepare_page( 'v1', 500, 500, $duplicate_registry, array(), $duplicate_skipped, true ) ),
	'Duplicate actionable SKU across pages'
);

expect_exception( static fn() => rhn_full_range_new_manifest( 'v1', 20001, 500 ), 'safety ceiling' );

echo 'PASS: ' . $tests . " assertions; 14,032-row fixture, 1,205 approved rows, 37 explicit withdrawals.\n";

