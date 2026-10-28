<?php
define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );
define( 'RHN_FULL_RANGE_CATALOG_TESTING', true );
define( 'HOUR_IN_SECONDS', 3600 );
class WP_Error { private $message; private $data; public function __construct( $code, $message, $data = null ) { $this->message = $message; $this->data = $data; } public function get_error_message() { return $this->message; } public function get_error_data() { return $this->data; } }
function is_wp_error( $v ) { return $v instanceof WP_Error; }
function wp_json_encode( $v, $flags = 0 ) { return json_encode( $v, $flags | JSON_THROW_ON_ERROR ); }
function sanitize_text_field( $v ) { return trim( strip_tags( (string) $v ) ); }
function sanitize_key( $v ) { return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( (string) $v ) ); }
function wp_generate_uuid4() { static $i = 0; return 'adversarial-' . ++$i; }
$options = array(); $filters = array(); $queue = array(); $fetch_calls = 0;
function get_option( $k, $d = false ) { global $options; return array_key_exists( $k, $options ) ? $options[ $k ] : $d; }
function update_option( $k, $v ) { global $options; $options[ $k ] = $v; return true; }
function add_option( $k, $v ) { global $options; if ( array_key_exists( $k, $options ) ) { return false; } $options[ $k ] = $v; return true; }
function delete_option( $k ) { global $options; unset( $options[ $k ] ); return true; }
function add_action() { return true; } function remove_action() { return true; } function add_filter( $h, $cb, $p = 10 ) { global $filters; $filters[ $h ][ $p ][] = $cb; return true; }
function apply_filters( $h, $v ) { global $filters; $all = (array) ( $filters[ $h ] ?? array() ); ksort( $all ); foreach ( $all as $cbs ) { foreach ( $cbs as $cb ) { $v = $cb( $v ); } } return $v; }
function wp_next_scheduled() { return false; } function wp_schedule_event() { return true; } function wp_unschedule_event() { return true; }
function wp_schedule_single_event( $at, $hook, $args = array() ) { global $queue; $queue[] = array( $hook, $args ); return count( $queue ); }
function rhn_catalog_batch_guarded() { return true; }
function adversarial_store( &$data ) { return array(
	'get' => static function ( $k ) use ( &$data ) { return $data[ $k ] ?? null; },
	'put_if_absent' => static function ( $k, $v ) use ( &$data ) { if ( array_key_exists( $k, $data ) ) { return false; } $data[ $k ] = $v; return true; },
	'compare_and_swap' => static function ( $k, $e, $r ) use ( &$data ) { if ( ! array_key_exists( $k, $data ) || json_encode( $data[ $k ] ) !== json_encode( $e ) ) { return false; } $data[ $k ] = $r; return true; },
	'delete' => static function ( $k, $e = null ) use ( &$data ) { if ( ! array_key_exists( $k, $data ) ) { return null === $e; } if ( null !== $e && json_encode( $data[ $k ] ) !== json_encode( $e ) ) { return false; } unset( $data[ $k ] ); return true; },
); }
function check_adv( $condition, $message ) { static $n = 0; $GLOBALS['adv_assertions'] = ++$n; if ( ! $condition ) { throw new RuntimeException( 'FAIL: ' . $message ); } }

$plugin = $argv[1] ?? dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'isolated-plugin';
require_once $plugin . DIRECTORY_SEPARATOR . 'registry-import.php';
require_once $plugin . DIRECTORY_SEPARATOR . 'google-sheet-sync.php';
require_once $plugin . DIRECTORY_SEPARATOR . 'production-scheduler.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog-runtime.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog-integration.php';

$data = array(); $store = adversarial_store( $data ); add_filter( 'rhn_full_range_scheduler_store', static fn() => $store );

// Mid-cycle flag-off: the legacy callback must pause and never fetch/reconcile.
$legacy = array( 'mode' => 'full_range', 'engine' => 'full_range_v2', 'generation' => 0, 'cycle_id' => 'flag-cycle', 'status' => 'queued', 'pending_token' => 'flag-token', 'pending_scheduled_at' => time(), 'job_started_at' => 0 );
$options['rhn_live_catalog_active_cycle'] = $legacy; rhn_live_catalog_run_cycle_job( 'flag-cycle', 'flag-token' );
$paused = rhn_full_range_cycle_state_get( $store );
check_adv( 0 === $fetch_calls, 'mid-cycle flag-off did not enter the legacy Sheet path' );
check_adv( 'paused_feature_disabled' === $paused['status'] && '' === $paused['pending_token'], 'mid-cycle flag-off atomically paused and consumed its token' );

// Cleanup cannot delete another worker's live lease; after expiry it owns cleanup and releases its own lease.
$lock = rhn_full_range_acquire_lock( $store, 'rhn-full-range:lease-snapshot', 'worker-a', 900 );
$blocked = false === rhn_full_range_delete_snapshot( $store, 'lease-snapshot' );
check_adv( $blocked && null !== rhn_full_range_store_get( $store, 'rhn-full-range:lease-snapshot:lock' ), 'cleanup refused and preserved another worker live lease' );
$expired = $lock; $expired['expires'] = time() - 1; check_adv( rhn_full_range_store_cas( $store, 'rhn-full-range:lease-snapshot:lock', $lock, $expired ), 'test expired the held lease atomically' );
rhn_full_range_delete_snapshot( $store, 'lease-snapshot' ); check_adv( null === rhn_full_range_store_get( $store, 'rhn-full-range:lease-snapshot:lock' ), 'cleanup acquired expired lease and released only its own lease' );

// Promotion retains cleanup ownership failures and retries safely after lease release.
$retained_lock = rhn_full_range_acquire_lock( $store, 'rhn-full-range:retained-snapshot', 'source-worker', 900 );
$promotion = array( 'snapshot_id' => 'new-current', 'abandoned_snapshots' => array( 'retained-snapshot' ) );
rhn_full_range_scheduler_promote_snapshot( $store, $promotion );
check_adv( array( 'retained-snapshot' ) === $promotion['abandoned_snapshots'], 'promotion retained cleanup target while another worker owned its lease' );
rhn_full_range_release_lock( $store, 'rhn-full-range:retained-snapshot', $retained_lock ); rhn_full_range_scheduler_promote_snapshot( $store, $promotion );
check_adv( array() === $promotion['abandoned_snapshots'], 'later promotion retried and cleared retained cleanup target after lease release' );

// Contention queues a retry with the same token; after lease expiry that retry claims and progresses.
$header = array_keys( rhn_catalog_sheet_headers() ); $row = array_fill( 0, count( $header ), '' ); $row[ array_search( 'Status', $header, true ) ] = 'Working / Keep Off Website'; $row[ array_search( 'SKU / Barcode', $header, true ) ] = '0001';
$version = 'contention-v1'; $throw_next = false;
$source = array( 'get_version' => static function () use ( &$version, &$throw_next ) { if ( $throw_next ) { $throw_next = false; throw new RuntimeException( 'Separated transient source error.' ); } return $version; }, 'get_total_rows' => static fn() => 1, 'get_header' => static fn() => $header, 'read_page' => static fn() => array( $row ), 'verify_action' => static fn() => true );
add_filter( 'rhn_full_range_scheduler_source', static fn() => $source );
$state = rhn_full_range_new_cycle_state( $version ); $state['cycle_id'] = 'contention-cycle'; $state = rhn_full_range_cycle_state_commit( $store, $paused, $state ); $state = rhn_full_range_enqueue_cycle_job( $store, $state, 1 ); $token = $state['pending_token']; array_pop( $queue );
$held = rhn_full_range_acquire_lock( $store, 'rhn-full-range:cycle', 'other-worker', 900 ); $before = count( $queue ); rhn_full_range_run_cycle_job( 'contention-cycle', $token );
check_adv( count( $queue ) === $before + 1 && $queue[ array_key_last( $queue ) ][1] === array( 'contention-cycle', $token ), 'cycle-lock contention scheduled same-token retry' );
$held_expired = $held; $held_expired['expires'] = time() - 1; rhn_full_range_store_cas( $store, 'rhn-full-range:cycle:lock', $held, $held_expired );
$retry = array_pop( $queue ); rhn_full_range_run_cycle_job( ...$retry[1] ); $after_retry = rhn_full_range_cycle_state_get( $store );
check_adv( (int) ( $after_retry['prepared_rows'] ?? 0 ) === 1 && '' !== (string) ( $after_retry['pending_token'] ?? '' ), 'same-token retry progressed after lease expiry and queued continuation' );

// Two writers with the same generation: exactly one wins and the stale update is rejected.
$base = rhn_full_range_cycle_state_get( $store ); $left = $base; $left['marker'] = 'left'; $right = $base; $right['marker'] = 'right';
$winner = rhn_full_range_cycle_state_commit( $store, $base, $left ); $loser = rhn_full_range_cycle_state_commit( $store, $base, $right );
check_adv( is_array( $winner ) && false === $loser && 'left' === rhn_full_range_cycle_state_get( $store )['marker'], 'atomic generation rejected a stale concurrent cycle update' );

// Separated cycle errors do not accumulate across a successful job.
$queue = array(); $expected = rhn_full_range_cycle_state_get( $store ); $current = $expected; $current['status'] = 'queued'; $current['pending_token'] = ''; $current['pending_scheduled_at'] = 0; $current['phase'] = 'preparing'; $current['snapshot_id'] = rhn_full_range_snapshot_id_for_version( $version ); $current['source_version'] = $version;
$current = rhn_full_range_cycle_state_commit( $store, $expected, $current ); $current = rhn_full_range_enqueue_cycle_job( $store, $current, 1 ); $token1 = $current['pending_token']; array_pop( $queue ); $throw_next = true; rhn_full_range_run_cycle_job( $current['cycle_id'], $token1 );
$error1 = rhn_full_range_cycle_state_get( $store ); check_adv( 1 === (int) $error1['cycle_error_retries'], 'first separated cycle error counted once' );
$queued1 = array_pop( $queue ); rhn_full_range_run_cycle_job( ...$queued1[1] ); $success = rhn_full_range_cycle_state_get( $store ); check_adv( 0 === (int) $success['cycle_error_retries'], 'successful job reset cycle error counter' );
$queued2 = array_pop( $queue ); $throw_next = true; rhn_full_range_run_cycle_job( ...$queued2[1] ); $error2 = rhn_full_range_cycle_state_get( $store ); check_adv( 1 === (int) $error2['cycle_error_retries'] && 'queued' === $error2['status'], 'later separated error restarted at one instead of terminating healthy drain' );

// Authoritative store outage must fail closed even when the compatibility mirror looks legacy.
$throwing = array( 'get' => static function () { throw new RuntimeException( 'synthetic authoritative store outage' ); }, 'put_if_absent' => static fn() => false, 'compare_and_swap' => static fn() => false, 'delete' => static fn() => false );
add_filter( 'rhn_full_range_scheduler_store', static fn() => $throwing, 20 );
$options['rhn_live_catalog_active_cycle'] = array( 'cycle_id' => 'legacy-looking', 'status' => 'queued', 'pending_token' => 'legacy-token', 'attempts' => 0, 'cycle_error_retries' => 0, 'excluded_skus' => array(), 'failures' => array() );
rhn_live_catalog_run_cycle_job( 'legacy-looking', 'legacy-token' );
check_adv( ! isset( $options['rhn_live_catalog_lock'] ) && 'queued' === $options['rhn_live_catalog_active_cycle']['status'], 'authoritative-store outage failed closed without legacy or full-range processing' );

echo 'PASS adversarial cycle regression: ' . $GLOBALS['adv_assertions'] . " assertions.\n";
