<?php
/** Feature-gated integration with the existing four-hour cycle and continuation hooks. */

defined( 'ABSPATH' ) || exit;

function rhn_full_range_scheduler_source() { $source = apply_filters( 'rhn_full_range_scheduler_source', null ); return is_array( $source ) ? $source : rhn_full_range_google_source_adapter(); }
function rhn_full_range_scheduler_store() { $store = apply_filters( 'rhn_full_range_scheduler_store', null ); return is_array( $store ) ? $store : rhn_full_range_wordpress_option_store(); }
function rhn_full_range_scheduler_apply() { $apply = apply_filters( 'rhn_full_range_scheduler_apply', null ); return is_callable( $apply ) ? $apply : rhn_full_range_existing_reconcile_adapter( true ); }
function rhn_full_range_apply_enabled() { return defined( 'RHN_FULL_RANGE_CATALOG_APPLY_ENABLED' ) && true === RHN_FULL_RANGE_CATALOG_APPLY_ENABLED; }
function rhn_full_range_snapshot_id_for_version( $version ) { return 'sheet-' . substr( hash( 'sha256', (string) $version ), 0, 32 ); }
function rhn_full_range_cycle_state_key() { return 'rhn-full-range:active-cycle'; }

function rhn_full_range_new_cycle_state( $version ) {
	$cycle = rhn_live_catalog_new_cycle_state( wp_generate_uuid4(), 10, 2 );
	return array_merge( $cycle, array(
		'mode' => 'full_range', 'engine' => 'full_range_v2', 'generation' => 0, 'phase' => 'preparing',
		'source_version' => (string) $version, 'snapshot_id' => rhn_full_range_snapshot_id_for_version( $version ),
		'outcome_counts' => array(), 'review' => array(), 'permanent_failures' => array(),
		'abandoned_snapshots' => array(), 'action_retry_counts' => array(),
	) );
}

/** Import a pre-CAS full-range state once, then use the atomic store as authority. */
function rhn_full_range_cycle_state_get( $store ) {
	$key = rhn_full_range_cycle_state_key(); $state = rhn_full_range_store_get( $store, $key );
	if ( is_array( $state ) ) { return $state; }
	$legacy = (array) get_option( 'rhn_live_catalog_active_cycle', array() );
	if ( 'full_range' !== (string) ( $legacy['mode'] ?? '' ) ) { return null; }
	$legacy['engine'] = 'full_range_v2'; $legacy['generation'] = max( 0, (int) ( $legacy['generation'] ?? 0 ) );
	if ( rhn_full_range_store_put_if_absent( $store, $key, $legacy ) ) { return $legacy; }
	$state = rhn_full_range_store_get( $store, $key ); return is_array( $state ) ? $state : null;
}

/** Atomic generation transition; the WordPress option is a read-only compatibility mirror. */
function rhn_full_range_cycle_state_commit( $store, $expected, $replacement ) {
	$key = rhn_full_range_cycle_state_key(); $replacement['mode'] = 'full_range'; $replacement['engine'] = 'full_range_v2';
	$replacement['generation'] = 1 + (int) ( is_array( $expected ) ? ( $expected['generation'] ?? 0 ) : 0 );
	$replacement['updated_at_utc'] = gmdate( 'c' );
	$stored = null === $expected ? rhn_full_range_store_put_if_absent( $store, $key, $replacement ) : rhn_full_range_store_cas( $store, $key, $expected, $replacement );
	if ( ! $stored ) { return false; }
	update_option( 'rhn_live_catalog_active_cycle', $replacement, false );
	update_option( 'rhn_live_catalog_last_cycle', $replacement, false );
	return $replacement;
}

function rhn_full_range_schedule_job_event( $cycle_id, $token, $delay ) {
	$at = time() + max( 1, (int) $delay );
	if ( function_exists( 'as_schedule_single_action' ) ) { return as_schedule_single_action( $at, 'rhn_live_catalog_cycle_job', array( $cycle_id, $token ), 'rhn-live-catalog', true ); }
	return wp_schedule_single_event( $at, 'rhn_live_catalog_cycle_job', array( $cycle_id, $token ) );
}

function rhn_full_range_enqueue_cycle_job( $store, $state, $delay = 0 ) {
	if ( ! is_array( $state ) || '' !== (string) ( $state['pending_token'] ?? '' ) ) { return $state; }
	$replacement = $state; $token = wp_generate_uuid4(); $replacement['pending_token'] = $token; $replacement['pending_scheduled_at'] = time();
	$replacement['queue_backend'] = function_exists( 'as_schedule_single_action' ) ? 'action_scheduler' : 'wp_cron_continuation';
	$replacement = rhn_full_range_cycle_state_commit( $store, $state, $replacement );
	if ( false === $replacement ) { return false; }
	if ( rhn_full_range_schedule_job_event( (string) $replacement['cycle_id'], $token, $delay ) ) { return $replacement; }
	$failed = $replacement; $failed['pending_token'] = ''; $failed['pending_scheduled_at'] = 0; $failed['status'] = 'queue_failed';
	rhn_full_range_cycle_state_commit( $store, $replacement, $failed ); return false;
}

/** A consumed callback blocked on the cycle lease must always get one later retry. */
function rhn_full_range_schedule_contention_retry( $cycle_id, $token, $delay = 60 ) { return rhn_full_range_schedule_job_event( $cycle_id, $token, $delay ); }

/** Legacy hooks consult authoritative state so a stale compatibility mirror cannot cause fallback processing. */
function rhn_full_range_legacy_dispatch_state( $mirror ) {
	try { $authoritative = rhn_full_range_cycle_state_get( rhn_full_range_scheduler_store() ); }
	catch ( Throwable $error ) { return array( 'mode' => 'full_range', 'engine' => 'ownership_unavailable', 'status' => 'ownership_unavailable', 'pending_token' => '' ); }
	return is_array( $authoritative ) && 'full_range' === (string) ( $authoritative['mode'] ?? '' ) ? $authoritative : (array) $mirror;
}

/** Called by legacy dispatch after a mid-cycle flag rollback; never enters the legacy Sheet path. */
function rhn_full_range_pause_disabled_cycle_callback( $cycle_id = '', $token = '' ) {
	try { $store = rhn_full_range_scheduler_store(); $state = rhn_full_range_cycle_state_get( $store ); }
	catch ( Throwable $error ) { return (array) get_option( 'rhn_live_catalog_active_cycle', array() ); }
	if ( ! is_array( $state ) || ( '' !== $cycle_id && (string) ( $state['cycle_id'] ?? '' ) !== (string) $cycle_id ) ) { return $state; }
	if ( '' !== $token && ! hash_equals( (string) ( $state['pending_token'] ?? '' ), (string) $token ) ) { return $state; }
	$replacement = $state; $replacement['status'] = 'paused_feature_disabled'; $replacement['pending_token'] = ''; $replacement['pending_scheduled_at'] = 0; $replacement['job_started_at'] = 0;
	$committed = rhn_full_range_cycle_state_commit( $store, $state, $replacement ); return false === $committed ? rhn_full_range_cycle_state_get( $store ) : $committed;
}

function rhn_full_range_scheduler_record_terminal( &$state, $result ) {
	$outcome = (string) ( $result['receipt']['outcome'] ?? 'unknown_terminal' ); $sku = (string) ( $result['action']['sku'] ?? '' );
	$state['outcome_counts'][ $outcome ] = 1 + (int) ( $state['outcome_counts'][ $outcome ] ?? 0 );
	$state['total_processed_jobs'] = 1 + (int) ( $state['total_processed_jobs'] ?? 0 ); $state['processed_jobs'] = 1 + (int) ( $state['processed_jobs'] ?? 0 );
	if ( in_array( $outcome, array( 'review_unmanaged', 'review_unsupported_variation', 'failed_validation' ), true ) ) { $state['review'][ $sku ] = array( 'outcome' => $outcome, 'detail' => (string) ( $result['receipt']['detail'] ?? '' ) ); }
	if ( 'failed_permanent' === $outcome ) { $state['permanent_failures'][ $sku ] = (string) ( $result['receipt']['detail'] ?? 'Transient action failed after bounded retries.' ); }
	unset( $state['action_retry_counts'][ (string) ( $result['action_id'] ?? '' ) ] );
}

function rhn_full_range_scheduler_promote_snapshot( $store, &$state ) {
	$key = 'rhn-full-range:snapshot-index'; $index = rhn_full_range_store_get( $store, $key ); if ( ! is_array( $index ) ) { $index = array( 'revision' => 0, 'current' => '', 'previous' => '' ); }
	$stored = false;
	for ( $attempt = 0; $attempt < 8 && ! $stored; $attempt++ ) {
		$expected = $index; $same = (string) ( $index['current'] ?? '' ) === (string) $state['snapshot_id'];
		$replacement = array( 'revision' => 1 + (int) ( $index['revision'] ?? 0 ), 'current' => (string) $state['snapshot_id'], 'previous' => (string) ( $same ? ( $index['previous'] ?? '' ) : ( $index['current'] ?? '' ) ) );
		$stored = null === rhn_full_range_store_get( $store, $key ) ? rhn_full_range_store_put_if_absent( $store, $key, $replacement ) : rhn_full_range_store_cas( $store, $key, $expected, $replacement );
		if ( ! $stored ) { $index = rhn_full_range_store_get( $store, $key ); }
	}
	if ( ! $stored ) { throw new RuntimeException( 'Snapshot retention index could not be atomically updated.' ); }
	$keep = array_filter( array( $replacement['current'], $replacement['previous'] ) );
	$cleanup_retry = array();
	foreach ( array_unique( array_merge( (array) ( $state['abandoned_snapshots'] ?? array() ), array( (string) ( $expected['previous'] ?? '' ) ) ) ) as $snapshot_id ) {
		if ( '' !== $snapshot_id && ! in_array( $snapshot_id, $keep, true ) && false === rhn_full_range_delete_snapshot( $store, $snapshot_id ) ) { $cleanup_retry[] = $snapshot_id; }
	}
	$state['abandoned_snapshots'] = array_values( array_unique( $cleanup_retry ) );
}

function rhn_full_range_start_cycle() {
	if ( ! rhn_catalog_batch_guarded() ) { return new WP_Error( 'rhn_live_catalog_disabled', 'Production catalog processor is not enabled.' ); }
	$store = rhn_full_range_scheduler_store(); $state = rhn_full_range_cycle_state_get( $store );
	$active = array( 'queued', 'running', 'capped_with_continuation', 'prepared_apply_disabled', 'paused_feature_disabled' );
	if ( is_array( $state ) && in_array( (string) ( $state['status'] ?? '' ), $active, true ) ) {
		if ( 'prepared_apply_disabled' === $state['status'] && ! rhn_full_range_apply_enabled() ) { return $state; }
		if ( 'capped_with_continuation' === $state['status'] ) { rhn_live_catalog_schedule_follow_on_cycle( 30 ); return $state; }
		$replacement = $state;
		if ( 'paused_feature_disabled' === $replacement['status'] ) { $replacement['status'] = 'queued'; }
		$pending_age = time() - (int) ( $replacement['pending_scheduled_at'] ?? 0 ); $running_age = time() - (int) ( $replacement['job_started_at'] ?? 0 );
		if ( 'queued' === $replacement['status'] && '' !== (string) ( $replacement['pending_token'] ?? '' ) && $pending_age > 900 ) { $replacement['pending_token'] = ''; $replacement['pending_scheduled_at'] = 0; }
		if ( 'running' === $replacement['status'] && $running_age > 900 ) { $replacement['status'] = 'queued'; $replacement['pending_token'] = ''; $replacement['pending_scheduled_at'] = 0; $replacement['job_started_at'] = 0; }
		if ( rhn_full_range_json( $replacement ) !== rhn_full_range_json( $state ) ) { $replacement = rhn_full_range_cycle_state_commit( $store, $state, $replacement ); if ( false === $replacement ) { return rhn_full_range_cycle_state_get( $store ); } $state = $replacement; }
		if ( 'queued' === $state['status'] && '' === (string) ( $state['pending_token'] ?? '' ) ) { $queued = rhn_full_range_enqueue_cycle_job( $store, $state, 1 ); return false === $queued ? rhn_full_range_cycle_state_get( $store ) : $queued; }
		return $state;
	}
	$source = rhn_full_range_scheduler_source(); $new = rhn_full_range_new_cycle_state( (string) call_user_func( $source['get_version'] ) );
	$new = rhn_full_range_cycle_state_commit( $store, $state, $new ); if ( false === $new ) { return rhn_full_range_cycle_state_get( $store ); }
	$queued = rhn_full_range_enqueue_cycle_job( $store, $new, 1 ); return false === $queued ? rhn_full_range_cycle_state_get( $store ) : $queued;
}

function rhn_full_range_run_cycle_job( $cycle_id, $token ) {
	$store = rhn_full_range_scheduler_store();
	try { $cycle_lock = rhn_full_range_acquire_lock( $store, 'rhn-full-range:cycle', (string) $cycle_id . ':' . (string) $token, 900 ); }
	catch ( Throwable $error ) { rhn_full_range_schedule_contention_retry( $cycle_id, $token, 60 ); return; }
	try {
		$state = rhn_full_range_cycle_state_get( $store );
		if ( ! is_array( $state ) || (string) ( $state['cycle_id'] ?? '' ) !== (string) $cycle_id || ! hash_equals( (string) ( $state['pending_token'] ?? '' ), (string) $token ) ) { return; }
		$running = $state; $running['pending_token'] = ''; $running['pending_scheduled_at'] = 0; $running['job_started_at'] = time(); $running['status'] = 'running'; $running['attempts'] = 1 + (int) ( $running['attempts'] ?? 0 );
		$running = rhn_full_range_cycle_state_commit( $store, $state, $running ); if ( false === $running ) { return; } $state = $running;
		$claimed_generation = (int) $state['generation'];
		$source = rhn_full_range_scheduler_source(); $version = (string) call_user_func( $source['get_version'] );
		$renewed = rhn_full_range_renew_lock( $store, 'rhn-full-range:cycle', $cycle_lock, 900 ); if ( false === $renewed ) { throw new RuntimeException( 'Full-range cycle lease could not be renewed.' ); } $cycle_lock = $renewed;
		if ( ! hash_equals( (string) $state['source_version'], $version ) ) {
			$state['abandoned_snapshots'][] = (string) $state['snapshot_id']; $state['rollover_history'][] = array( 'source_version' => (string) $state['source_version'], 'snapshot_id' => (string) $state['snapshot_id'], 'outcome_counts' => (array) ( $state['outcome_counts'] ?? array() ), 'rolled_at_utc' => gmdate( 'c' ) );
			$state['source_version'] = $version; $state['snapshot_id'] = rhn_full_range_snapshot_id_for_version( $version ); $state['phase'] = 'preparing'; $state['action_retry_counts'] = array(); $state['outcome_counts'] = array(); $state['review'] = array(); $state['permanent_failures'] = array(); $state['processed_jobs'] = 0;
		}
		if ( 'preparing' === (string) $state['phase'] ) {
			$result = rhn_full_range_prepare_snapshot_step( $source, $store, (string) $state['snapshot_id'], 500, 2, (string) $cycle_id );
			$state['prepared_pages'] = (int) ( $result['manifest']['pages'] ?? 0 ); $state['prepared_rows'] = (int) ( $result['manifest']['next_row'] ?? 0 ); if ( 'prepared' === (string) $result['status'] ) { $state['phase'] = 'applying'; } $state['status'] = 'queued';
		} else {
			if ( ! rhn_full_range_apply_enabled() ) { $state['status'] = 'prepared_apply_disabled'; }
			else {
				$result = rhn_full_range_process_next_action( $source, $store, (string) $state['snapshot_id'], rhn_full_range_scheduler_apply(), (string) $cycle_id );
				if ( 'retryable_error' === (string) $result['status'] ) {
					$id = (string) $result['action_id']; $attempts = 1 + (int) ( $state['action_retry_counts'][ $id ] ?? 0 ); $state['action_retry_counts'][ $id ] = $attempts;
					if ( $attempts > (int) $state['max_retries'] ) { $detail = (string) ( $result['receipt']['detail'] ?? 'Transient action failed after bounded retries.' ); $result = rhn_full_range_process_next_action( $source, $store, (string) $state['snapshot_id'], static fn( $action, $rid ) => array( 'verified' => true, 'terminal' => true, 'retryable' => false, 'action_id' => $rid, 'outcome' => 'failed_permanent', 'detail' => $detail ), (string) $cycle_id ); rhn_full_range_scheduler_record_terminal( $state, $result ); }
					$state['status'] = 'queued';
				} elseif ( 'applied' === (string) $result['status'] ) { rhn_full_range_scheduler_record_terminal( $state, $result ); $state['status'] = (int) $state['processed_jobs'] >= (int) $state['max_jobs'] ? 'capped_with_continuation' : 'queued'; }
				else { $state['status'] = $state['permanent_failures'] ? 'completed_with_failures' : ( $state['review'] ? 'completed_with_review' : 'completed' ); $state['completed_at_utc'] = gmdate( 'c' ); rhn_full_range_scheduler_promote_snapshot( $store, $state ); }
			}
		}
		$renewed = rhn_full_range_renew_lock( $store, 'rhn-full-range:cycle', $cycle_lock, 900 ); if ( false === $renewed ) { throw new RuntimeException( 'Full-range cycle lease was lost before state commit.' ); } $cycle_lock = $renewed;
		$state['job_started_at'] = 0; $state['cycle_error_retries'] = 0; unset( $state['failures']['_cycle'] );
		$expected = rhn_full_range_cycle_state_get( $store );
		if ( ! is_array( $expected ) || (int) ( $expected['generation'] ?? -1 ) !== $claimed_generation ) { return; }
		$committed = rhn_full_range_cycle_state_commit( $store, $expected, $state ); if ( false === $committed ) { return; } $state = $committed;
		if ( 'queued' === $state['status'] ) { rhn_full_range_enqueue_cycle_job( $store, $state, 5 ); } elseif ( 'capped_with_continuation' === $state['status'] ) { rhn_live_catalog_schedule_follow_on_cycle( 30 ); }
	} catch ( Throwable $error ) {
		$current = rhn_full_range_cycle_state_get( $store );
		if ( is_array( $current ) && (string) ( $current['cycle_id'] ?? '' ) === (string) $cycle_id && ( ! isset( $claimed_generation ) || (int) ( $current['generation'] ?? -1 ) === (int) $claimed_generation ) ) {
			$failed = $current; $failed['job_started_at'] = 0; $failed['cycle_error_retries'] = 1 + (int) ( $failed['cycle_error_retries'] ?? 0 ); $failed['failures']['_cycle'] = $error->getMessage(); $failed['status'] = $failed['cycle_error_retries'] <= 2 ? 'queued' : 'cycle_failed';
			$failed = rhn_full_range_cycle_state_commit( $store, $current, $failed ); if ( is_array( $failed ) && 'queued' === $failed['status'] ) { rhn_full_range_enqueue_cycle_job( $store, $failed, 5 ); }
		}
	} finally { rhn_full_range_release_lock( $store, 'rhn-full-range:cycle', $cycle_lock ); }
}

function rhn_full_range_continue_drain() {
	$store = rhn_full_range_scheduler_store(); $state = rhn_full_range_cycle_state_get( $store );
	if ( ! is_array( $state ) || 'capped_with_continuation' !== (string) ( $state['status'] ?? '' ) ) { return $state; }
	$next = $state; $next['status'] = 'queued'; $next['processed_jobs'] = 0; $next['tranches'] = 1 + (int) ( $next['tranches'] ?? 1 );
	$next = rhn_full_range_cycle_state_commit( $store, $state, $next ); if ( false === $next ) { return rhn_full_range_cycle_state_get( $store ); }
	return rhn_full_range_enqueue_cycle_job( $store, $next, 5 );
}

function rhn_full_range_scheduler_bootstrap() {
	if ( ! rhn_full_range_feature_enabled() ) { return; }
	remove_action( 'rhn_catalog_sheet_sync_event', 'rhn_live_catalog_start_cycle' ); remove_action( 'rhn_live_catalog_cycle_job', 'rhn_live_catalog_run_cycle_job', 10 ); remove_action( 'rhn_live_catalog_drain_continue_event', 'rhn_live_catalog_continue_drain' );
	add_action( 'rhn_catalog_sheet_sync_event', 'rhn_full_range_start_cycle' ); add_action( 'rhn_live_catalog_cycle_job', 'rhn_full_range_run_cycle_job', 10, 2 ); add_action( 'rhn_live_catalog_drain_continue_event', 'rhn_full_range_continue_drain' );
}

add_action( 'plugins_loaded', 'rhn_full_range_scheduler_bootstrap', 100 );
