<?php

define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );
define( 'RHN_FULL_RANGE_CATALOG_TESTING', true );
define( 'RHN_FULL_RANGE_CATALOG_ENABLED', true );
define( 'RHN_FULL_RANGE_CATALOG_APPLY_ENABLED', true );
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
function sanitize_key( $value ) { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $value ) ); }
function wp_generate_uuid4() { static $id = 0; return 'test-uuid-' . ++$id; }

$test_options = array(); $test_hooks = array(); $test_filters = array(); $test_queue = array();
function get_option( $key, $default = false ) { global $test_options; return array_key_exists( $key, $test_options ) ? $test_options[ $key ] : $default; }
function update_option( $key, $value ) { global $test_options; $test_options[ $key ] = $value; return true; }
function add_option( $key, $value ) { global $test_options; if ( array_key_exists( $key, $test_options ) ) { return false; } $test_options[ $key ] = $value; return true; }
function delete_option( $key ) { global $test_options; unset( $test_options[ $key ] ); return true; }
function add_action( $hook, $callback, $priority = 10, $args = 1 ) { global $test_hooks; $test_hooks[ $hook ][ $priority ][] = array( $callback, $args ); return true; }
function remove_action( $hook, $callback, $priority = 10 ) { global $test_hooks; foreach ( (array) ( $test_hooks[ $hook ][ $priority ] ?? array() ) as $index => $item ) { if ( $item[0] === $callback ) { unset( $test_hooks[ $hook ][ $priority ][ $index ] ); } } return true; }
function do_action( $hook, ...$args ) { global $test_hooks; $priorities = (array) ( $test_hooks[ $hook ] ?? array() ); ksort( $priorities ); foreach ( $priorities as $callbacks ) { foreach ( $callbacks as $item ) { call_user_func_array( $item[0], array_slice( $args, 0, $item[1] ) ); } } }
function add_filter( $hook, $callback, $priority = 10 ) { global $test_filters; $test_filters[ $hook ][ $priority ][] = $callback; return true; }
function apply_filters( $hook, $value ) { global $test_filters; $priorities = (array) ( $test_filters[ $hook ] ?? array() ); ksort( $priorities ); foreach ( $priorities as $callbacks ) { foreach ( $callbacks as $callback ) { $value = call_user_func( $callback, $value ); } } return $value; }
function wp_schedule_single_event( $time, $hook, $args = array() ) { global $test_queue; $test_queue[] = array( $hook, $args ); return count( $test_queue ); }
function wp_schedule_event() { return true; }
function wp_next_scheduled() { return false; }
function wp_unschedule_event() { return true; }
function rhn_catalog_batch_guarded() { return true; }

function scheduler_check( $condition, $message ) { static $count = 0; $count++; $GLOBALS['scheduler_assertions'] = $count; if ( ! $condition ) { throw new RuntimeException( 'FAIL: ' . $message ); } }
function scheduler_row( $header, $values ) { $row = array(); foreach ( $header as $heading ) { $row[] = (string) ( $values[ $heading ] ?? '' ); } return $row; }
function scheduler_memory_store( &$data ) {
	$get = static function ( $key ) use ( &$data ) { return $data[ $key ] ?? null; };
	return array(
		'get' => $get,
		'put_if_absent' => static function ( $key, $value ) use ( &$data ) { if ( array_key_exists( $key, $data ) ) { return false; } $data[ $key ] = $value; return true; },
		'compare_and_swap' => static function ( $key, $expected, $replacement ) use ( &$data ) { if ( ! array_key_exists( $key, $data ) || rhn_full_range_json( $data[ $key ] ) !== rhn_full_range_json( $expected ) ) { return false; } $data[ $key ] = $replacement; return true; },
		'delete' => static function ( $key, $expected = null ) use ( &$data ) { if ( ! array_key_exists( $key, $data ) ) { return true; } if ( null !== $expected && rhn_full_range_json( $data[ $key ] ) !== rhn_full_range_json( $expected ) ) { return false; } unset( $data[ $key ] ); return true; },
	);
}

$production_root = $argv[1] ?? dirname( __DIR__, 2 ) . DIRECTORY_SEPARATOR . 'deployed-production-readonly';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'registry-import.php';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'google-sheet-sync.php';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'production-scheduler.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog-runtime.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog-integration.php';

$header = array_keys( rhn_catalog_sheet_headers() ); $rows = array();
for ( $i = 0; $i < 14032; $i++ ) {
	$sku = str_pad( (string) $i, 12, '0', STR_PAD_LEFT );
	if ( $i < 1205 ) { $values = array( 'Status' => 'Approved', 'SKU / Barcode' => $sku, 'Product Name' => 'Product ' . $i, 'Source / Evidence' => 'Fixture' ); }
	elseif ( $i < 1242 ) { $values = array( 'Status' => 'Remove from Website', 'SKU / Barcode' => $sku, 'Source / Evidence' => 'Explicit withdrawal' ); }
	else { $values = array( 'Status' => 'Working / Keep Off Website', 'SKU / Barcode' => $sku, 'Product Name' => 'Not approved ' . $i ); }
	$rows[] = scheduler_row( $header, $values );
}
$version = 'sheet-v1'; $read_pages = 0;
$source = array(
	'get_version' => static function () use ( &$version ) { return $version; },
	'get_total_rows' => static fn() => 14032,
	'get_header' => static fn() => $header,
	'read_page' => static function ( $start, $limit ) use ( &$rows, &$read_pages ) { $read_pages++; return array_slice( $rows, $start, $limit ); },
	'verify_action' => static function ( $action ) use ( &$rows, $header ) {
		$index = (int) $action['source_row'] - 2; if ( ! isset( $rows[ $index ] ) ) { return false; }
		$parsed = rhn_full_range_parse_sheet_page( $header, array( $rows[ $index ] ), $index ); $sku = (string) $action['sku'];
		return 'approved' === $action['action'] ? isset( $parsed['registry'][ $sku ] ) : isset( $parsed['withdrawals'][ $sku ] );
	},
);
$store_data = array(); $store = scheduler_memory_store( $store_data );
$mutations = array(); $apply_calls = array(); $drifted = false;
$apply = static function ( $action, $action_id ) use ( &$mutations, &$apply_calls, &$version, &$drifted ) {
	$sku = (string) $action['sku']; $apply_calls[ $sku ] = 1 + (int) ( $apply_calls[ $sku ] ?? 0 );
	$outcome = 'applied_and_verified'; $detail = '';
	if ( '000000000000' === $sku ) { $outcome = 'review_unmanaged'; $detail = 'Not enrolled for automated presentation.'; }
	elseif ( '000000000001' === $sku ) { $outcome = 'review_unsupported_variation'; $detail = 'Variation requires review.'; }
	elseif ( '000000000002' === $sku ) { $outcome = 'failed_validation'; $detail = 'Required presentation field missing.'; }
	elseif ( '000000000003' === $sku && $apply_calls[ $sku ] <= 2 ) { return array( 'verified' => false, 'terminal' => false, 'retryable' => true, 'action_id' => $action_id, 'outcome' => 'failed_runtime', 'detail' => 'Synthetic transient.' ); }
	elseif ( '000000000004' === $sku ) { return array( 'verified' => false, 'terminal' => false, 'retryable' => true, 'action_id' => $action_id, 'outcome' => 'failed_runtime', 'detail' => 'Synthetic persistent runtime failure.' ); }
	elseif ( isset( $mutations[ $sku ] ) ) { $outcome = 'already_correct'; }
	else { $mutations[ $sku ] = 1; }
	if ( ! $drifted && count( $mutations ) >= 50 ) { $version = 'sheet-v2'; $drifted = true; }
	return array( 'verified' => true, 'terminal' => true, 'retryable' => false, 'action_id' => $action_id, 'outcome' => $outcome, 'detail' => $detail );
};
add_filter( 'rhn_full_range_scheduler_source', static fn() => $source );
add_filter( 'rhn_full_range_scheduler_store', static fn() => $store );
add_filter( 'rhn_full_range_scheduler_apply', static fn() => $apply );

rhn_full_range_scheduler_bootstrap();
$sheet_callbacks = array_map( static fn( $item ) => $item[0], array_values( $test_hooks['rhn_catalog_sheet_sync_event'][10] ?? array() ) );
$job_callbacks = array_map( static fn( $item ) => $item[0], array_values( $test_hooks['rhn_live_catalog_cycle_job'][10] ?? array() ) );
scheduler_check( in_array( 'rhn_full_range_start_cycle', $sheet_callbacks, true ) && ! in_array( 'rhn_live_catalog_start_cycle', $sheet_callbacks, true ), 'existing four-hour hook enters the full-range branch when enabled' );
scheduler_check( in_array( 'rhn_full_range_run_cycle_job', $job_callbacks, true ) && ! in_array( 'rhn_live_catalog_run_cycle_job', $job_callbacks, true ), 'existing immediate continuation hook enters the full-range branch when enabled' );
do_action( 'rhn_catalog_sheet_sync_event' );
$steps = 0; $interrupted_state = null;
while ( $test_queue && $steps < 10000 ) {
	$item = array_shift( $test_queue ); do_action( $item[0], ...(array) $item[1] ); $steps++;
	if ( 60 === $steps ) { $interrupted_state = get_option( 'rhn_live_catalog_active_cycle' ); scheduler_check( 'full_range' === $interrupted_state['mode'], 'interruption retained full-range durable state' ); }
}
$state = get_option( 'rhn_live_catalog_active_cycle' );
if ( ! in_array( $state['status'], array( 'completed', 'completed_with_review', 'completed_with_failures' ), true ) ) { fwrite( STDERR, 'DEBUG STATE ' . json_encode( $state ) . "\nQUEUE " . count( $test_queue ) . " STEPS {$steps}\n" ); }
scheduler_check( $steps < 10000, 'scheduler drain terminated' );
scheduler_check( in_array( $state['status'], array( 'completed', 'completed_with_review', 'completed_with_failures' ), true ), 'scheduler reached terminal state' );
scheduler_check( 'sheet-v2' === $state['source_version'], 'source version drift rolled to a new snapshot' );
scheduler_check( $read_pages >= 58, 'all 14,032 rows were paged for both source versions' );
if ( array_sum( $state['outcome_counts'] ) !== 1242 ) { fwrite( STDERR, 'DEBUG OUTCOMES ' . json_encode( $state['outcome_counts'] ) . ' total=' . array_sum( $state['outcome_counts'] ) . ' steps=' . $steps . "\n" ); }
scheduler_check( array_sum( $state['outcome_counts'] ) === 1242, 'all >1,000 approved/withdrawal actions reached terminal outcomes' );
scheduler_check( 1 === $state['outcome_counts']['review_unmanaged'], 'unmanaged review advanced cursor' );
scheduler_check( 1 === $state['outcome_counts']['review_unsupported_variation'], 'variation review advanced cursor' );
scheduler_check( 1 === $state['outcome_counts']['failed_validation'], 'permanent validation review advanced cursor' );
scheduler_check( 1 === $state['outcome_counts']['failed_permanent'], 'transient error became one terminal failure after bounded retries' );
scheduler_check( 4 === $apply_calls['000000000003'], 'transient action retried twice, succeeded, and was receipt-idempotent after rollover' );
scheduler_check( 6 === $apply_calls['000000000004'], 'persistent transient was bounded to three attempts per versioned snapshot' );
scheduler_check( count( $mutations ) === 1238, 'review/permanent failures did not mutate and source rollover did not duplicate mutations' );
scheduler_check( ! isset( $mutations['000000013000'] ), 'Not Approved row created no action' );
scheduler_check( $state['tranches'] > 100, 'existing continuation hook drained more than 1,000 actions in bounded tranches' );
scheduler_check( ! empty( $interrupted_state['snapshot_id'] ), 'interruption point had a durable snapshot identity' );

// Atomic lock/CAS behavior: a competing worker cannot steal a live lease or replace stale state.
$lock_a = rhn_full_range_acquire_lock( $store, 'rhn-full-range:atomic-test', 'worker-a', 300 );
$blocked = false; try { rhn_full_range_acquire_lock( $store, 'rhn-full-range:atomic-test', 'worker-b', 300 ); } catch ( RuntimeException $e ) { $blocked = true; }
scheduler_check( $blocked, 'concurrent worker was rejected while lease was live' );
$key = 'atomic-state'; scheduler_check( rhn_full_range_store_put_if_absent( $store, $key, array( 'revision' => 1 ) ), 'atomic state inserted' );
scheduler_check( ! rhn_full_range_store_cas( $store, $key, array( 'revision' => 0 ), array( 'revision' => 2 ) ), 'stale compare-and-swap rejected' );
rhn_full_range_release_lock( $store, 'rhn-full-range:atomic-test', $lock_a );

echo 'PASS full-range scheduler integration: ' . $GLOBALS['scheduler_assertions'] . " assertions, {$steps} scheduled callbacks, 14,032 rows, 1,242 terminal actions.\n";
