<?php

define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );
define( 'RHN_FULL_RANGE_CATALOG_TESTING', true );
define( 'HOUR_IN_SECONDS', 3600 );

class WP_Error {
	private $code; private $message; private $data;
	public function __construct( $code, $message, $data = null ) { $this->code = $code; $this->message = $message; $this->data = $data; }
	public function get_error_message() { return $this->message; }
	public function get_error_data() { return $this->data; }
}
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function wp_json_encode( $value, $flags = 0 ) { return json_encode( $value, $flags | JSON_THROW_ON_ERROR ); }
function sanitize_text_field( $value ) { return trim( strip_tags( (string) $value ) ); }
function wp_kses_post( $value ) { return (string) $value; }
function wp_strip_all_tags( $value ) { return strip_tags( (string) $value ); }
function sanitize_key( $value ) { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $value ) ); }
function add_filter() { return true; }
function add_action() { return true; }
function get_option( $key, $default = false ) { global $adapter_options; return $adapter_options[ $key ] ?? $default; }
function wp_generate_uuid4() { static $id = 0; $id++; return 'mock-run-' . $id; }
function taxonomy_exists() { return true; }
function rhn_catalog_convert_weight( $weight, $unit ) { return (string) $weight; }
function rhn_catalog_resolve_term_ids( $names, $taxonomy ) { return 'product_brand' === $taxonomy ? array( 21 ) : array( 11 ); }
function get_posts( $args ) { global $adapter_products_by_sku; $sku = (string) ( $args['meta_value'] ?? '' ); return isset( $adapter_products_by_sku[ $sku ] ) ? array( $adapter_products_by_sku[ $sku ]->get_id() ) : array(); }
function wc_get_product( $id ) { global $adapter_products_by_id; return $adapter_products_by_id[ (int) $id ] ?? null; }
function wp_get_object_terms( $id ) { global $adapter_products_by_id; return $adapter_products_by_id[ (int) $id ]->brand_ids ?? array(); }

class WC_Product {
	public $brand_ids = array( 21 );
	private $id; private $sku; private $name; private $status; private $visibility; private $meta;
	public function __construct( $id, $sku, $name, $status, $visibility ) {
		$this->id = $id; $this->sku = $sku; $this->name = $name; $this->status = $status; $this->visibility = $visibility;
		$this->meta = array( '_rhn_live_catalog_managed' => 'yes', '_rhn_package_size' => '1 fl oz' );
	}
	public function get_id() { return $this->id; }
	public function get_sku() { return $this->sku; }
	public function get_name() { return $this->name; }
	public function get_description() { return ''; }
	public function get_short_description() { return 'Short'; }
	public function get_weight() { return '4.5'; }
	public function get_category_ids() { return array( 11 ); }
	public function get_image_id() { return 0; }
	public function get_gallery_image_ids() { return array(); }
	public function get_status() { return $this->status; }
	public function get_catalog_visibility() { return $this->visibility; }
	public function get_type() { return 'simple'; }
	public function is_type( $type ) { return 'simple' === $type; }
	public function get_meta( $key ) { return $this->meta[ $key ] ?? ''; }
}

$production_root = $argv[1] ?? 'C:\\Users\\todda\\Documents\\Codex\\2026-10-27\\task\\rhn-live-catalog-processor';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'core.php';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'registry-import.php';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'google-sheet-sync.php';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'production-scheduler.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog-runtime.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog-adapters.php';

$adapter_assertions = 0;
function adapter_check( $condition, $message ) {
	global $adapter_assertions; $adapter_assertions++;
	if ( ! $condition ) { throw new RuntimeException( 'FAIL: ' . $message ); }
}
function adapter_exception( $callback, $contains ) {
	global $adapter_assertions; $adapter_assertions++;
	try { $callback(); } catch ( Throwable $error ) {
		if ( false !== strpos( $error->getMessage(), $contains ) ) { return; }
		throw new RuntimeException( 'FAIL: expected "' . $contains . '", got "' . $error->getMessage() . '".' );
	}
	throw new RuntimeException( 'FAIL: expected exception containing "' . $contains . '".' );
}

function adapter_memory_store( &$data ) {
	return array(
		'get' => static function ( $key ) use ( &$data ) { return $data[ $key ] ?? null; },
		'put_if_absent' => static function ( $key, $value ) use ( &$data ) { if ( array_key_exists( $key, $data ) ) { return false; } $data[ $key ] = $value; return true; },
		'compare_and_swap' => static function ( $key, $expected, $replacement ) use ( &$data ) { if ( ! array_key_exists( $key, $data ) || rhn_full_range_json( $data[ $key ] ) !== rhn_full_range_json( $expected ) ) { return false; } $data[ $key ] = $replacement; return true; },
		'delete' => static function ( $key, $expected = null ) use ( &$data ) { if ( ! array_key_exists( $key, $data ) ) { return true; } if ( null !== $expected && rhn_full_range_json( $data[ $key ] ) !== rhn_full_range_json( $expected ) ) { return false; } unset( $data[ $key ] ); return true; },
	);
}

function adapter_row( $header, $values ) {
	$row = array(); foreach ( $header as $heading ) { $row[] = (string) ( $values[ $heading ] ?? '' ); } return $row;
}
function adapter_approved_values( $sku, $name ) {
	return array(
		'Status' => 'Approved', 'SKU / Barcode' => $sku, 'Product Name' => $name, 'Brand' => 'Brand',
		'Website Categories' => 'Supplements', 'Short Description' => 'Short', 'Packaged Weight' => '4.5',
		'Weight Unit' => 'oz', 'Package Size / Net Contents' => '1 fl oz', 'Source / Evidence' => 'Mock Sheet',
	);
}

function adapter_transport( &$grid, &$version, &$calls, $sheet_id = 'mock-sheet' ) {
	return static function ( $method, $url ) use ( &$grid, &$version, &$calls, $sheet_id ) {
		if ( 'GET' !== $method ) { throw new RuntimeException( 'Non-read transport call.' ); }
		if ( false !== strpos( $url, 'drive/v3/files/' ) ) {
			$calls[] = array( 'kind' => 'version' );
			return array( 'status' => 200, 'body' => array( 'id' => $sheet_id, 'modifiedTime' => '2026-10-28T00:00:00Z', 'version' => (string) $version, 'headRevisionId' => 'head-' . $version ) );
		}
		if ( false !== strpos( $url, 'sheets.googleapis.com/v4/spreadsheets/' ) && false === strpos( $url, '/values/' ) ) {
			$calls[] = array( 'kind' => 'metadata' );
			return array( 'status' => 200, 'body' => array( 'spreadsheetId' => $sheet_id, 'sheets' => array( array( 'properties' => array( 'sheetId' => 1, 'title' => 'Catalog', 'gridProperties' => array( 'rowCount' => count( $grid ), 'columnCount' => count( $grid[0] ) ) ) ) ) ) );
		}
		$path = rawurldecode( (string) parse_url( $url, PHP_URL_PATH ) );
		$range = substr( $path, strpos( $path, '/values/' ) + 8 );
		if ( ! preg_match( '/!([A-Z]+)(\d+):([A-Z]+)(\d+)$/D', $range, $matches ) ) { throw new RuntimeException( 'Unrecognized mock range: ' . $range ); }
		$start = (int) $matches[2]; $end = (int) $matches[4];
		$calls[] = array( 'kind' => 'values', 'start' => $start, 'end' => $end, 'count' => $end - $start + 1 );
		$values = array_slice( $grid, $start - 1, $end - $start + 1 );
		foreach ( $values as &$row ) { while ( $row && '' === (string) end( $row ) ) { array_pop( $row ); } } unset( $row );
		while ( $values && ! array_filter( end( $values ), static fn( $value ) => '' !== trim( (string) $value ) ) ) { array_pop( $values ); }
		return array( 'status' => 200, 'body' => array( 'range' => $range, 'majorDimension' => 'ROWS', 'values' => $values ) );
	};
}

function adapter_plan_outcomes( $plan ) {
	$out = array();
	foreach ( array( 'update' => 'planned_update', 'create_quarantine' => 'planned_create_quarantine', 'unchanged' => 'already_correct', 'missing_remove_satisfied' => 'missing_remove_already_satisfied' ) as $bucket => $outcome ) {
		foreach ( (array) $plan[ $bucket ] as $sku => $unused ) { $out[ $sku ] = $outcome; }
	}
	ksort( $out, SORT_STRING ); return $out;
}

$adapter_options = array( 'rhn_live_catalog_creation_enabled' => 'no', 'rhn_catalog_google_sheet_range' => 'Catalog!A:W' );
$header = array_keys( rhn_catalog_sheet_headers() );
$small_grid = array(
	$header,
	adapter_row( $header, adapter_approved_values( '0001', 'New One' ) ),
	adapter_row( $header, adapter_approved_values( '0002', 'Same Two' ) ),
	adapter_row( $header, array( 'Status' => 'Remove from Website', 'SKU / Barcode' => '0003', 'Source / Evidence' => 'Explicit removal' ) ),
	adapter_row( $header, array( 'Status' => 'Working / Keep Off Website', 'SKU / Barcode' => '0004', 'Product Name' => 'Review Only' ) ),
	adapter_row( $header, array( 'Status' => 'Remove from Website', 'SKU / Barcode' => '0005', 'Source / Evidence' => 'Explicit missing removal' ) ),
);
$adapter_products_by_sku = array(
	'0001' => new WC_Product( 1, '0001', 'Old One', 'draft', 'hidden' ),
	'0002' => new WC_Product( 2, '0002', 'Same Two', 'publish', 'visible' ),
	'0003' => new WC_Product( 3, '0003', 'Three', 'publish', 'visible' ),
);
$adapter_products_by_id = array(); foreach ( $adapter_products_by_sku as $product ) { $adapter_products_by_id[ $product->get_id() ] = $product; }

$small_version = 1; $small_calls = array();
$small_source = rhn_full_range_google_source_adapter( adapter_transport( $small_grid, $small_version, $small_calls ), array( 'sheet_id' => 'mock-sheet', 'discovery_page_size' => 2 ) );
$small_data = array(); $small_store = adapter_memory_store( $small_data );
$small_result = rhn_full_range_prepare_snapshot_step( $small_source, $small_store, 'small-adapter', 2, 10, 'small-reader' );
adapter_check( 'prepared' === $small_result['status'] && 5 === $small_result['final']['total_source_rows'], 'Mocked Google metadata and bounded values produce an exact five-row snapshot.' );

$old_sheet = rhn_catalog_sheet_rows_to_registry( $small_grid );
$old_plan = rhn_live_catalog_build_plan( $old_sheet, PHP_INT_MAX );
$old_outcomes = adapter_plan_outcomes( $old_plan );
$old_action_types = array(); foreach ( rhn_live_catalog_actions_from_sheet( $old_sheet ) as $sku => $action ) { $old_action_types[ $sku ] = $action['action']; }
$new_outcomes = array(); $new_action_types = array(); $dry_apply = rhn_full_range_existing_reconcile_adapter( false );
for ( $page = 0; $page < $small_result['final']['pages']; $page++ ) {
	$chunk = rhn_full_range_store_get( $small_store, 'rhn-full-range:small-adapter:chunk:' . $page );
	foreach ( rhn_full_range_page_actions( $chunk['payload'] ) as $action ) {
		$receipt = $dry_apply( $action, 'compare-' . $action['sku'] );
		$new_outcomes[ $action['sku'] ] = $receipt['outcome'];
		$new_action_types[ $action['sku'] ] = 'approved' === $action['action'] ? 'Publish' : 'Remove from Website';
	}
}
ksort( $new_outcomes, SORT_STRING ); ksort( $new_action_types, SORT_STRING ); ksort( $old_action_types, SORT_STRING );
adapter_check( $old_outcomes === $new_outcomes, 'Per-action adapter produces the exact same planned outcomes as the old full-sheet path.' );
adapter_check( $old_action_types === $new_action_types, 'Per-action adapter preserves the exact old-path SKU/action map.' );
adapter_check( ! isset( $new_outcomes['0004'] ) && array( '0003', '0005' ) === array_values( array_intersect( array_keys( $new_outcomes ), array( '0003', '0005' ) ) ), 'Only explicit Remove from Website rows create removal actions.' );

$apply_calls = 0;
$counted_apply = static function ( $action, $action_id ) use ( &$apply_calls, $dry_apply ) { $apply_calls++; return $dry_apply( $action, $action_id ); };
$first = rhn_full_range_process_next_action( $small_source, $small_store, 'small-adapter', $counted_apply, 'small-action-1' );
$cursor_key = 'rhn-full-range:small-adapter:cursor';
$advanced_cursor = rhn_full_range_store_get( $small_store, $cursor_key );
$initial_cursor = array( 'snapshot_hash' => $small_result['final']['snapshot_hash'], 'page' => 0, 'action' => 0, 'revision' => 0, 'done' => false );
adapter_check( rhn_full_range_store_cas( $small_store, $cursor_key, $advanced_cursor, $initial_cursor ), 'Test simulates a retry after receipt persistence but before durable cursor progress.' );
$retry = rhn_full_range_process_next_action( $small_source, $small_store, 'small-adapter', $counted_apply, 'small-action-2' );
adapter_check( $first['action_id'] === $retry['action_id'] && 1 === $apply_calls, 'Retry reuses the verified deterministic receipt without applying twice.' );

$small_grid[1][ array_search( 'Product Name', $header, true ) ] = 'Staff Changed Name';
adapter_check( false === $small_source['verify_action']( $first['action'] ), 'Action-time exact-row verification detects a staff field edit even before version metadata changes.' );
$small_grid[1][ array_search( 'Product Name', $header, true ) ] = 'New One';
$small_version = 2;
adapter_exception( static fn() => rhn_full_range_process_next_action( $small_source, $small_store, 'small-adapter', $counted_apply, 'small-action-3' ), 'differs from the prepared snapshot' );

$full_grid = array( $header );
for ( $i = 0; $i < 14032; $i++ ) {
	$sku = str_pad( (string) $i, 12, '0', STR_PAD_LEFT );
	if ( $i < 1205 ) { $values = adapter_approved_values( $sku, 'Product ' . $i ); }
	elseif ( $i < 1242 ) { $values = array( 'Status' => 'Remove from Website', 'SKU / Barcode' => $sku, 'Source / Evidence' => 'Explicit removal' ); }
	else { $values = array( 'Status' => 'Working / Keep Off Website', 'SKU / Barcode' => $sku, 'Product Name' => 'Review ' . $i ); }
	$full_grid[] = adapter_row( $header, $values );
}
$full_version = 7; $full_calls = array();
$full_source = rhn_full_range_google_source_adapter( adapter_transport( $full_grid, $full_version, $full_calls ), array( 'sheet_id' => 'mock-sheet', 'discovery_page_size' => 500 ) );
$full_data = array(); $full_store = adapter_memory_store( $full_data );
do { $full_result = rhn_full_range_prepare_snapshot_step( $full_source, $full_store, 'full-adapter', 500, 20, 'full-reader' ); } while ( 'partial' === $full_result['status'] );
adapter_check( 14032 === $full_result['final']['total_source_rows'] && 1205 === $full_result['final']['approved'] && 37 === $full_result['final']['withdrawals'], 'Mocked account adapter prepares the full 14,032 / 1,205 / 37 capacity fixture.' );
$full_action_count = 0; for ( $page = 0; $page < $full_result['final']['pages']; $page++ ) { $chunk = rhn_full_range_store_get( $full_store, 'rhn-full-range:full-adapter:chunk:' . $page ); $full_action_count += count( rhn_full_range_page_actions( $chunk['payload'] ) ); }
adapter_check( 1242 === $full_action_count, 'Full snapshot exposes exactly 1,205 publish and 37 explicit removal actions.' );
adapter_exception( static fn() => rhn_catalog_sheet_rows_to_registry( $full_grid ), 'Provide a list' );
$oversized_reads = array_filter( $full_calls, static fn( $call ) => 'values' === $call['kind'] && $call['count'] > 500 && 1 !== $call['count'] );
adapter_check( ! $oversized_reads, 'All mocked Google data reads are bounded to at most 500 rows.' );

echo 'PASS: ' . $adapter_assertions . " adapter assertions; mocked Google version/ranges and actual parser/reconcile planning with no external writes.\n";

