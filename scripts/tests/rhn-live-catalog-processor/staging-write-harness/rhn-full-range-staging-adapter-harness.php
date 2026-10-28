<?php
/**
 * Plugin Name: RHN Full-range Staging Adapter Harness (temporary)
 * Description: One-request, rollback-only validation of the production adapter on the exact Cloudways staging host.
 */
defined( 'ABSPATH' ) || exit;

const RHN_FRH_ACTION = 'rhn_full_range_staging_adapter_test';
const RHN_FRH_HOST = 'wordpress-1651482-6655800.cloudwaysapps.com';
const RHN_FRH_STAGING_PLUGIN = 'rhn-catalog-presentation/rhn-catalog-presentation.php';

require_once __DIR__ . '/staging-adapter-harness-lib.php';

function rhn_frh_config() {
	$file = __DIR__ . '/run-config.php';
	if ( ! is_readable( $file ) ) {
		return array();
	}
	$config = require $file;
	return is_array( $config ) ? $config : array();
}

function rhn_frh_exact_host() {
	$host = strtolower( preg_replace( '/:\d+$/', '', (string) ( $_SERVER['HTTP_HOST'] ?? '' ) ) );
	$https = ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== strtolower( (string) $_SERVER['HTTPS'] ) ) || 'https' === strtolower( (string) ( $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '' ) );
	return $https && RHN_FRH_HOST === $host;
}

function rhn_frh_candidate_request() {
	$config = rhn_frh_config();
	$token = (string) ( $_POST['rhn_frh_token'] ?? '' );
	return rhn_frh_exact_host()
		&& 'POST' === strtoupper( (string) ( $_SERVER['REQUEST_METHOD'] ?? '' ) )
		&& RHN_FRH_ACTION === (string) ( $_POST['action'] ?? '' )
		&& isset( $config['token_hash'] )
		&& preg_match( '/^[a-f0-9]{64}$/D', (string) $config['token_hash'] )
		&& hash_equals( strtolower( (string) $config['token_hash'] ), hash( 'sha256', $token ) );
}

/** Skip only the colliding staging presentation plugin, and only for the exact signed POST. */
function rhn_frh_filter_active_plugins( $plugins ) {
	if ( ! rhn_frh_candidate_request() || ! is_array( $plugins ) ) {
		return $plugins;
	}
	return array_values( array_filter( $plugins, static fn( $plugin ) => RHN_FRH_STAGING_PLUGIN !== (string) $plugin ) );
}

add_filter( 'option_active_plugins', 'rhn_frh_filter_active_plugins', PHP_INT_MIN );
add_action( 'admin_post_' . RHN_FRH_ACTION, 'rhn_frh_handle', 0 );
add_action( 'admin_post_nopriv_' . RHN_FRH_ACTION, 'rhn_frh_reject_nopriv', 0 );
add_action( 'admin_menu', 'rhn_frh_register_page' );

function rhn_frh_register_page() {
	if ( rhn_frh_exact_host() ) {
		add_management_page( 'Full-range adapter rehearsal', 'Full-range adapter rehearsal', 'manage_options', 'rhn-full-range-staging-adapter-harness', 'rhn_frh_page' );
	}
}

function rhn_frh_page() {
	if ( ! rhn_frh_exact_host() || ! current_user_can( 'manage_options' ) ) { wp_die( 'Forbidden.' ); }
	$config = rhn_frh_config();
	echo '<div class="wrap"><h1>Rollback-only full-range adapter rehearsal</h1>';
	echo '<p>This temporary staging-only tool performs one adapter-only request inside a database transaction and always rolls it back. It is not an end-to-end scheduler test.</p>';
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
	echo '<input type="hidden" name="action" value="' . esc_attr( RHN_FRH_ACTION ) . '">';
	wp_nonce_field( 'rhn_frh_' . (string) ( $config['run_id'] ?? '' ), 'rhn_frh_nonce' );
	echo '<p><label>One-use token <input type="password" autocomplete="off" name="rhn_frh_token" required></label></p>';
	echo '<p><button type="submit" class="button button-primary">Run one rollback-only adapter rehearsal</button></p>';
	echo '</form></div>';
}

function rhn_frh_reject_nopriv() {
	status_header( 403 );
	exit;
}

function rhn_frh_consume_once( $run_id ) {
	$marker = __DIR__ . '/.consumed-' . preg_replace( '/[^a-zA-Z0-9_.-]/', '', (string) $run_id );
	$handle = @fopen( $marker, 'x' );
	if ( false === $handle ) {
		throw new RuntimeException( 'This harness token has already been consumed.' );
	}
	fwrite( $handle, gmdate( 'c' ) . "\n" );
	fclose( $handle );
	return $marker;
}

function rhn_frh_release_manifest() {
	return array(
		'catalog-fields.php'                  => 'F8B991227278D679C54BFA5E52F5D0B5CB6587C724A3FD0FB194D790E569AD31',
		'core.php'                            => '0CC69BA638CFB5F5067AF38E7498B6B9FB26BA2846CD1301D121EDFFF56DCE47',
		'full-range-catalog-adapters.php'      => '100C326E0260ABFC613F05590559BA907E867665DC579CAAE3FC21D5C45F9AD6',
		'full-range-catalog-integration.php'   => '72B731E66E99707E7631499793F30B32D73CF6F988E84B1769F31457450D527E',
		'full-range-catalog-runtime.php'       => '83D3BDE5CEA6C0BAB96D13D1F8CFB1EDAB7F7A7E18A28666314CAAF83702E9A5',
		'full-range-catalog.php'               => '3C69DDF6F3188F69EE8BE80D125DA8942EED47BC3FEF56E04E820296E9EDCF47',
		'google-sheet-discovery.php'           => '6788A669001335CC10897B73F1930DB654F472070E485A9EF5FE1D3EEDF48B5B',
		'google-sheet-sync.php'                => '856AE2B7B16367DF3E5157C4DA759532EF48E511E9B651147C5CA4849A8ABA20',
		'photo-intake.php'                     => '7DCC15156E47020471E7EE399F6F63684C70DA2D9A0997BEBEAB21C86D545CC8',
		'production-scheduler.php'             => 'A0C112BA22FB7CF53A1C03D1F933D5E62235F654CFE630ED57ABECFBBB8F7B6A',
		'registry-import.php'                  => '5B68AF9D7D130608D689A41185CC080DECB8D2BBA3AD5A12C8EECBA6A935F11C',
		'rhn-live-catalog-processor.php'       => '4B764C612614B40C3D0C6D2C945440FAF9B7CED4EFF598040D2E0F5AEF5D5406',
	);
}

function rhn_frh_verify_release( $release_dir ) {
	$manifest = rhn_frh_release_manifest();
	$actual = array();
	foreach ( glob( rtrim( $release_dir, '/\\' ) . '/*.php' ) ?: array() as $file ) {
		$actual[ basename( $file ) ] = strtoupper( hash_file( 'sha256', $file ) );
	}
	ksort( $actual, SORT_STRING );
	$expected = $manifest;
	ksort( $expected, SORT_STRING );
	if ( $actual !== $expected ) {
		throw new RuntimeException( 'Release file set or SHA-256 manifest does not match the audited package.' );
	}
	$symbols = rhn_frh_extract_global_functions( array_keys( $actual ), $release_dir );
	$collisions = array_values( array_filter( $symbols, 'function_exists' ) );
	if ( $collisions ) {
		throw new RuntimeException( 'Release global symbol collision: ' . implode( ', ', $collisions ) );
	}
	return array( 'files' => count( $actual ), 'symbols' => count( $symbols ) );
}

function rhn_frh_load_release( $release_dir ) {
	require_once $release_dir . '/rhn-live-catalog-processor.php';
	if ( ! function_exists( 'rhn_full_range_existing_reconcile_adapter' ) || ! function_exists( 'rhn_live_catalog_reconcile' ) ) {
		throw new RuntimeException( 'The exact production adapter did not load.' );
	}
}

function rhn_frh_transaction_active() {
	global $wpdb;
	return 1 === (int) $wpdb->get_var( 'SELECT @@in_transaction' );
}

function rhn_frh_table_exists( $table ) {
	global $wpdb;
	return (string) $table === (string) $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
}

function rhn_frh_require_innodb( $tables ) {
	global $wpdb;
	foreach ( $tables as $table ) {
		if ( ! rhn_frh_table_exists( $table ) ) {
			continue;
		}
		$engine = $wpdb->get_var( $wpdb->prepare( 'SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = %s', $table ) );
		if ( 'innodb' !== strtolower( (string) $engine ) ) {
			throw new RuntimeException( 'Non-transactional table blocks the rehearsal: ' . $table );
		}
	}
}

function rhn_frh_related_hooks() {
	return array( 'rhn_catalog_sheet_sync_event', 'rhn_live_catalog_cycle_job', 'rhn_live_catalog_drain_continue_event', 'rhn_catalog_sheet_discovery_event' );
}

function rhn_frh_require_idle() {
	global $wpdb;
	if ( get_option( 'rhn_live_catalog_lock', false ) ) {
		throw new RuntimeException( 'The catalog lock is active.' );
	}
	$full_range_lock = 'rhn_fr_' . substr( hash( 'sha256', 'rhn-full-range:cycle:lock' ), 0, 40 );
	if ( null !== get_option( $full_range_lock, null ) ) {
		throw new RuntimeException( 'The full-range cycle lock is active.' );
	}
	$legacy_cycle = (array) get_option( 'rhn_live_catalog_active_cycle', array() );
	if ( in_array( (string) ( $legacy_cycle['status'] ?? '' ), array( 'queued', 'running', 'capped_with_continuation' ), true ) ) {
		throw new RuntimeException( 'The existing catalog cycle is active.' );
	}
	$full_range_cycle_name = 'rhn_fr_' . substr( hash( 'sha256', 'rhn-full-range:active-cycle' ), 0, 40 );
	$full_range_cycle = get_option( $full_range_cycle_name, null );
	$full_range_state = is_array( $full_range_cycle ) ? (array) ( $full_range_cycle['value'] ?? array() ) : array();
	if ( in_array( (string) ( $full_range_state['status'] ?? '' ), array( 'queued', 'running', 'capped_with_continuation' ), true ) ) {
		throw new RuntimeException( 'The full-range catalog cycle is active.' );
	}
	$cron = function_exists( '_get_cron_array' ) ? (array) _get_cron_array() : array();
	foreach ( $cron as $timestamp => $events ) {
		if ( (int) $timestamp > time() + 600 ) { continue; }
		foreach ( rhn_frh_related_hooks() as $hook ) {
			if ( ! empty( $events[ $hook ] ) ) {
				throw new RuntimeException( 'Related WordPress cron work is pending: ' . $hook );
			}
		}
	}
	$actions = $wpdb->prefix . 'actionscheduler_actions';
	if ( rhn_frh_table_exists( $actions ) ) {
		$placeholders = implode( ',', array_fill( 0, count( rhn_frh_related_hooks() ), '%s' ) );
		$args = array_merge( rhn_frh_related_hooks(), array( gmdate( 'Y-m-d H:i:s', time() + 600 ) ) );
		$sql = $wpdb->prepare( "SELECT COUNT(*) FROM {$actions} WHERE hook IN ({$placeholders}) AND (status = 'in-progress' OR (status = 'pending' AND scheduled_date_gmt <= %s))", $args );
		if ( 0 !== (int) $wpdb->get_var( $sql ) ) {
			throw new RuntimeException( 'Related Action Scheduler work is pending or running.' );
		}
	}
}

function rhn_frh_lock_and_snapshot( $product_ids, $option_names ) {
	global $wpdb;
	$product_ids = array_values( array_unique( array_map( 'intval', $product_ids ) ) );
	sort( $product_ids, SORT_NUMERIC );
	$option_names = array_values( array_unique( array_map( 'strval', $option_names ) ) );
	sort( $option_names, SORT_STRING );
	$ids = implode( ',', $product_ids );
	$option_placeholders = implode( ',', array_fill( 0, count( $option_names ), '%s' ) );
	$lookup = $wpdb->wc_product_meta_lookup;
	$term_ids = $wpdb->get_col( "SELECT DISTINCT term_taxonomy_id FROM {$wpdb->term_relationships} WHERE object_id IN ({$ids}) ORDER BY term_taxonomy_id FOR UPDATE" );
	$wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE ID IN ({$ids}) ORDER BY ID FOR UPDATE", ARRAY_A );
	$wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE post_id IN ({$ids}) ORDER BY post_id, meta_id FOR UPDATE", ARRAY_A );
	$wpdb->get_results( "SELECT * FROM {$lookup} WHERE product_id IN ({$ids}) ORDER BY product_id FOR UPDATE", ARRAY_A );
	if ( $term_ids ) {
		$terms = implode( ',', array_map( 'intval', $term_ids ) );
		$wpdb->get_results( "SELECT * FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id IN ({$terms}) ORDER BY term_taxonomy_id FOR UPDATE", ARRAY_A );
	}
	if ( $option_names ) {
		$wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->options} WHERE option_name IN ({$option_placeholders}) ORDER BY option_name FOR UPDATE", $option_names ), ARRAY_A );
	}
	return rhn_frh_snapshot_storage( $product_ids, $option_names );
}

function rhn_frh_snapshot_storage( $product_ids, $option_names ) {
	global $wpdb;
	$product_ids = array_values( array_unique( array_map( 'intval', $product_ids ) ) );
	sort( $product_ids, SORT_NUMERIC );
	$ids = implode( ',', $product_ids );
	$lookup = $wpdb->wc_product_meta_lookup;
	$option_names = array_values( array_unique( array_map( 'strval', $option_names ) ) );
	sort( $option_names, SORT_STRING );
	$option_placeholders = implode( ',', array_fill( 0, count( $option_names ), '%s' ) );
	$tables = array(
		'posts'              => $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE ID IN ({$ids}) ORDER BY ID", ARRAY_A ),
		'postmeta'           => $wpdb->get_results( "SELECT * FROM {$wpdb->postmeta} WHERE post_id IN ({$ids}) ORDER BY post_id, meta_id", ARRAY_A ),
		'term_relationships' => $wpdb->get_results( "SELECT * FROM {$wpdb->term_relationships} WHERE object_id IN ({$ids}) ORDER BY object_id, term_taxonomy_id", ARRAY_A ),
		'lookup'             => $wpdb->get_results( "SELECT * FROM {$lookup} WHERE product_id IN ({$ids}) ORDER BY product_id", ARRAY_A ),
		'options'            => $option_names ? $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->options} WHERE option_name IN ({$option_placeholders}) ORDER BY option_name", $option_names ), ARRAY_A ) : array(),
	);
	$term_ids = array_values( array_unique( array_map( 'intval', array_column( $tables['term_relationships'], 'term_taxonomy_id' ) ) ) );
	$tables['term_taxonomy'] = $term_ids ? $wpdb->get_results( "SELECT * FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id IN (" . implode( ',', $term_ids ) . ") ORDER BY term_taxonomy_id", ARRAY_A ) : array();
	$actions = $wpdb->prefix . 'actionscheduler_actions';
	$logs = $wpdb->prefix . 'actionscheduler_logs';
	$claims = $wpdb->prefix . 'actionscheduler_claims';
	$groups = $wpdb->prefix . 'actionscheduler_groups';
	if ( rhn_frh_table_exists( $actions ) ) {
		$tables['async_actions'] = rhn_frh_async_action_markers( $actions );
	}
	if ( rhn_frh_table_exists( $logs ) ) {
		$tables['async_logs'] = rhn_frh_async_log_markers( $logs, $actions );
	}
	if ( rhn_frh_table_exists( $claims ) ) {
		$tables['async_claims'] = rhn_frh_bounded_table_summary( $claims, 'claim_id' );
	}
	if ( rhn_frh_table_exists( $groups ) ) {
		$tables['async_groups'] = rhn_frh_bounded_table_summary( $groups, 'group_id' );
	}
	return array( 'hash' => hash( 'sha256', rhn_frh_canonical_json( $tables ) ), 'tables' => $tables );
}

function rhn_frh_bounded_table_summary( $table, $id_column ) {
	global $wpdb;
	$allowed = array( 'claim_id', 'group_id' );
	if ( ! in_array( $id_column, $allowed, true ) ) {
		throw new InvalidArgumentException( 'Unsupported Action Scheduler summary column.' );
	}
	return (array) $wpdb->get_row( "SELECT COUNT(*) AS row_count, COALESCE(MAX({$id_column}), 0) AS max_id FROM {$table}", ARRAY_A );
}

/**
 * Snapshot Action Scheduler without loading the site's complete history.
 *
 * Global count/max markers deliberately make unrelated concurrent scheduler
 * activity fail rollback verification closed. Only catalog-related rows are
 * materialized, and those are bounded so this diagnostic cannot exhaust PHP
 * memory on a mature Action Scheduler installation.
 */
function rhn_frh_async_action_markers( $actions ) {
	global $wpdb;
	$markers = array(
		'summary' => (array) $wpdb->get_row( "SELECT COUNT(*) AS row_count, COALESCE(MAX(action_id), 0) AS max_id FROM {$actions}", ARRAY_A ),
		'related' => array(),
	);
	$hooks = rhn_frh_related_hooks();
	$placeholders = implode( ',', array_fill( 0, count( $hooks ), '%s' ) );
	$sql = $wpdb->prepare(
		"SELECT action_id, hook, status, scheduled_date_gmt, args FROM {$actions} WHERE hook IN ({$placeholders}) ORDER BY action_id DESC LIMIT 100",
		$hooks
	);
	$markers['related'] = $wpdb->get_results( $sql, ARRAY_A );
	return $markers;
}

function rhn_frh_async_log_markers( $logs, $actions ) {
	global $wpdb;
	$markers = array(
		'summary' => (array) $wpdb->get_row( "SELECT COUNT(*) AS row_count, COALESCE(MAX(log_id), 0) AS max_id FROM {$logs}", ARRAY_A ),
		'related' => array(),
	);
	if ( ! rhn_frh_table_exists( $actions ) ) {
		return $markers;
	}
	$hooks = rhn_frh_related_hooks();
	$placeholders = implode( ',', array_fill( 0, count( $hooks ), '%s' ) );
	$sql = $wpdb->prepare(
		"SELECT l.log_id, l.action_id, l.message, l.log_date_gmt FROM {$logs} l INNER JOIN {$actions} a ON a.action_id = l.action_id WHERE a.hook IN ({$placeholders}) ORDER BY l.log_id DESC LIMIT 200",
		$hooks
	);
	$markers['related'] = $wpdb->get_results( $sql, ARRAY_A );
	return $markers;
}

function rhn_frh_option_names( $targets ) {
	global $wpdb;
	$names = array( 'rhn_live_catalog_product_baselines', 'rhn_live_catalog_last_receipt', 'rhn_catalog_presentation_overrides', 'rhn_catalog_presentation_previous' );
	foreach ( $targets as $target ) {
		$logical = 'rhn-full-range:registry:' . substr( hash( 'sha256', (string) $target['sku'] ), 0, 2 );
		$names[] = 'rhn_fr_' . substr( hash( 'sha256', $logical ), 0, 40 );
	}
	$patterns = array( '_transient_wc_%', '_transient_timeout_wc_%', '_transient_woocommerce_%', '_transient_timeout_woocommerce_%', '%transient_version%' );
	foreach ( $patterns as $pattern ) {
		$names = array_merge( $names, $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s ORDER BY option_name", $pattern ) ) );
	}
	return array_values( array_unique( $names ) );
}

function rhn_frh_block_side_effects() {
	add_filter( 'pre_http_request', static fn() => new WP_Error( 'rhn_frh_http_blocked', 'Outbound HTTP is blocked during the rollback-only rehearsal.' ), PHP_INT_MIN );
	add_filter( 'pre_wp_mail', static fn() => false, PHP_INT_MIN );
	add_filter( 'woocommerce_webhook_should_deliver', '__return_false', PHP_INT_MIN );
	add_filter( 'pre_schedule_event', static fn() => new WP_Error( 'rhn_frh_schedule_blocked', 'Scheduling is blocked during the rollback-only rehearsal.' ), PHP_INT_MIN );
	foreach ( array( 'pre_as_schedule_single_action', 'pre_as_schedule_recurring_action', 'pre_as_schedule_cron_action', 'pre_as_enqueue_async_action' ) as $filter ) {
		add_filter( $filter, '__return_zero', PHP_INT_MIN );
	}
}

function rhn_frh_callback_id( $callback ) {
	if ( is_string( $callback ) ) { return $callback; }
	if ( is_array( $callback ) && 2 === count( $callback ) ) {
		$owner = is_object( $callback[0] ) ? get_class( $callback[0] ) : (string) $callback[0];
		return $owner . '::' . (string) $callback[1];
	}
	if ( $callback instanceof Closure ) {
		$ref = new ReflectionFunction( $callback );
		return 'Closure@' . basename( (string) $ref->getFileName() ) . ':' . (int) $ref->getStartLine();
	}
	return get_debug_type( $callback );
}

function rhn_frh_callback_file( $callback ) {
	try {
		if ( $callback instanceof Closure || is_string( $callback ) ) {
			$reflection = new ReflectionFunction( $callback );
		} elseif ( is_array( $callback ) && 2 === count( $callback ) ) {
			$reflection = new ReflectionMethod( $callback[0], $callback[1] );
		} elseif ( is_object( $callback ) && method_exists( $callback, '__invoke' ) ) {
			$reflection = new ReflectionMethod( $callback, '__invoke' );
		} else {
			return '';
		}
		return wp_normalize_path( (string) $reflection->getFileName() );
	} catch ( ReflectionException $error ) {
		return '';
	}
}

function rhn_frh_allowed_callback_roots( $release_dir ) {
	$roots = array(
		wp_normalize_path( trailingslashit( ABSPATH ) . 'wp-admin/' ),
		wp_normalize_path( trailingslashit( ABSPATH ) . 'wp-includes/' ),
		wp_normalize_path( trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/' ),
		wp_normalize_path( trailingslashit( __DIR__ ) ),
		wp_normalize_path( trailingslashit( $release_dir ) ),
	);
	return array_values( array_unique( $roots ) );
}

/** Fail closed before any transaction if an unreviewed callback could run. */
function rhn_frh_assert_callback_corridor( $release_dir ) {
	global $wp_filter;
	$hooks = array( 'save_post_product', 'woocommerce_before_product_object_save', 'woocommerce_after_product_object_save', 'woocommerce_update_product', 'updated_post_meta', 'added_post_meta', 'deleted_post_meta', 'set_object_terms' );
	$roots = rhn_frh_allowed_callback_roots( $release_dir );
	$blocked = array();
	foreach ( $hooks as $hook ) {
		$callbacks = isset( $wp_filter[ $hook ] ) && is_object( $wp_filter[ $hook ] ) ? (array) $wp_filter[ $hook ]->callbacks : array();
		foreach ( $callbacks as $priority => $items ) {
			foreach ( (array) $items as $item ) {
				$callback = $item['function'] ?? null;
				$file = rhn_frh_callback_file( $callback );
				$allowed = false;
				foreach ( $roots as $root ) {
					if ( '' !== $file && str_starts_with( $file, $root ) ) {
						$allowed = true;
						break;
					}
				}
				if ( ! $allowed ) {
					$blocked[] = $hook . '@' . (int) $priority . ':' . rhn_frh_callback_id( $callback ) . ':' . ( $file ? basename( $file ) : 'unknown-file' );
				}
			}
		}
	}
	if ( $blocked ) {
		throw new RuntimeException( 'Unreviewed product callback blocks the rehearsal: ' . implode( ', ', array_slice( $blocked, 0, 10 ) ) );
	}
	return true;
}

function rhn_frh_product_hook_fingerprint() {
	global $wp_filter;
	$hooks = array( 'save_post_product', 'woocommerce_before_product_object_save', 'woocommerce_after_product_object_save', 'woocommerce_update_product', 'updated_post_meta', 'added_post_meta', 'deleted_post_meta', 'set_object_terms' );
	$inventory = array();
	foreach ( $hooks as $hook ) {
		$inventory[ $hook ] = array();
		$callbacks = isset( $wp_filter[ $hook ] ) && is_object( $wp_filter[ $hook ] ) ? (array) $wp_filter[ $hook ]->callbacks : array();
		ksort( $callbacks, SORT_NUMERIC );
		foreach ( $callbacks as $priority => $items ) {
			foreach ( (array) $items as $item ) {
				$inventory[ $hook ][] = (int) $priority . ':' . rhn_frh_callback_id( $item['function'] ?? null ) . ':' . (int) ( $item['accepted_args'] ?? 0 );
			}
		}
	}
	return hash( 'sha256', rhn_frh_canonical_json( $inventory ) );
}

function rhn_frh_invalidate_precise_caches( $product_ids, $option_names ) {
	foreach ( $product_ids as $product_id ) {
		wp_cache_delete( (int) $product_id, 'posts' );
		wp_cache_delete( (int) $product_id, 'post_meta' );
		wp_cache_delete( (int) $product_id, 'products' );
		wp_cache_delete( 'product-' . (int) $product_id, 'products' );
	}
	foreach ( $option_names as $name ) {
		wp_cache_delete( (string) $name, 'options' );
	}
	wp_cache_delete( 'alloptions', 'options' );
	wp_cache_delete( 'notoptions', 'options' );
}

function rhn_frh_woo_readback( $targets ) {
	$out = array();
	foreach ( $targets as $target ) {
		$product = wc_get_product( (int) $target['product_id'] );
		if ( ! $product ) {
			throw new RuntimeException( 'Product disappeared after rollback.' );
		}
		$out[ $target['sku'] ] = array(
			'id' => $product->get_id(), 'type' => $product->get_type(), 'sku' => $product->get_sku( 'edit' ),
			'price' => $product->get_regular_price( 'edit' ), 'stock' => $product->get_stock_quantity( 'edit' ),
			'stock_status' => $product->get_stock_status( 'edit' ), 'backorders' => $product->get_backorders( 'edit' ),
			'status' => $product->get_status( 'edit' ), 'visibility' => $product->get_catalog_visibility( 'edit' ),
			'name' => $product->get_name( 'edit' ), 'description' => $product->get_description( 'edit' ),
			'short_description' => $product->get_short_description( 'edit' ),
		);
	}
	return $out;
}

function rhn_frh_handle() {
	global $wpdb;
	$config = rhn_frh_config();
	$run_id = (string) ( $config['run_id'] ?? '' );
	$transaction = false;
	$result = array( 'ok' => false, 'run_id' => $run_id, 'adapter_only' => true, 'rollback_only' => true );
	try {
		rhn_frh_consume_once( $run_id );
		if ( ! rhn_frh_candidate_request() || ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'rhn_frh_' . $run_id, 'rhn_frh_nonce' ) ) {
			throw new RuntimeException( 'Exact host, token, administrator, or nonce gate failed.' );
		}
		rhn_frh_validate_config( $config );
		$release_dir = (string) $config['release_dir'];
		$result['release'] = rhn_frh_verify_release( $release_dir );
		rhn_frh_require_idle();
		$observed_hook_fingerprint = rhn_frh_product_hook_fingerprint();
		$result['hook_fingerprint'] = $observed_hook_fingerprint;
		if ( ! hash_equals( (string) $config['hook_fingerprint'], $observed_hook_fingerprint ) ) {
			throw new RuntimeException( 'Product-hook fingerprint differs from the separately reviewed read-only inventory.' );
		}
		rhn_frh_assert_callback_corridor( $release_dir );
		$targets = array_values( $config['targets'] );
		$product_ids = array_column( $targets, 'product_id' );
		$option_names = rhn_frh_option_names( $targets );
		$tables = array( $wpdb->posts, $wpdb->postmeta, $wpdb->term_relationships, $wpdb->term_taxonomy, $wpdb->options, $wpdb->wc_product_meta_lookup, $wpdb->prefix . 'actionscheduler_actions', $wpdb->prefix . 'actionscheduler_logs', $wpdb->prefix . 'actionscheduler_claims', $wpdb->prefix . 'actionscheduler_groups' );
		rhn_frh_require_innodb( $tables );
		rhn_frh_load_release( $release_dir );
		if ( function_exists( 'rhn_catalog_presentation_version' ) ) {
			throw new RuntimeException( 'The colliding staging presentation plugin remained loaded.' );
		}
		// Re-run after loading the exact release so newly registered callbacks
		// are also confined to the audited code corridor.
		rhn_frh_assert_callback_corridor( $release_dir );
		$wpdb->query( 'START TRANSACTION' );
		$transaction = true;
		if ( ! rhn_frh_transaction_active() ) { throw new RuntimeException( 'Database transaction did not start.' ); }
		$before = rhn_frh_lock_and_snapshot( $product_ids, $option_names );
		$stable = rhn_frh_snapshot_storage( $product_ids, $option_names );
		if ( ! hash_equals( $before['hash'], $stable['hash'] ) ) { throw new RuntimeException( 'Locked target storage was not stable across two reads.' ); }
		foreach ( $targets as $target ) {
			$product = wc_get_product( (int) $target['product_id'] );
			if ( ! $product || ! $product->is_type( 'simple' ) || (string) $target['sku'] !== (string) $product->get_sku( 'edit' ) || 'yes' !== (string) $product->get_meta( '_rhn_live_catalog_managed', true, 'edit' ) ) {
				throw new RuntimeException( 'Every target must be an existing, exact-SKU, managed simple product.' );
			}
		}
		$before_woo = rhn_frh_woo_readback( $targets );
		rhn_frh_invalidate_precise_caches( $product_ids, $option_names );
		if ( $before_woo !== rhn_frh_woo_readback( $targets ) ) { throw new RuntimeException( 'Selected products were not stable across two Woo reads.' ); }
		$dry = rhn_full_range_existing_reconcile_adapter( false );
		$dry_results = array();
		foreach ( $targets as $target ) {
			$dry_results[ $target['sku'] ] = $dry( $target['action'], 'dry-' . $run_id . '-' . $target['sku'] );
		}
		rhn_frh_validate_dry_results( $targets, $dry_results );
		if ( ! rhn_frh_transaction_active() ) { throw new RuntimeException( 'Dry planning ended the database transaction.' ); }
		rhn_frh_block_side_effects();
		if ( ! defined( 'RHN_FULL_RANGE_CATALOG_ENABLED' ) ) { define( 'RHN_FULL_RANGE_CATALOG_ENABLED', true ); }
		if ( ! defined( 'RHN_FULL_RANGE_CATALOG_APPLY_ENABLED' ) ) { define( 'RHN_FULL_RANGE_CATALOG_APPLY_ENABLED', true ); }
		$apply = rhn_full_range_existing_reconcile_adapter( true );
		$applied = array();
		foreach ( $targets as $target ) {
			$applied[ $target['sku'] ] = $apply( $target['action'], 'apply-' . $run_id . '-' . $target['sku'] );
		}
		if ( ! rhn_frh_transaction_active() ) { throw new RuntimeException( 'Adapter ended the database transaction.' ); }
		$result['dry'] = $dry_results;
		$result['applied'] = $applied;
		$result['in_transaction_hash'] = rhn_frh_snapshot_storage( $product_ids, $option_names )['hash'];
		$result['ok'] = true;
	} catch ( Throwable $error ) {
		$result['error'] = $error->getMessage();
	} finally {
		if ( $transaction ) {
			$wpdb->query( 'ROLLBACK' );
			$transaction = false;
		}
		if ( isset( $before, $product_ids, $option_names ) ) {
			rhn_frh_invalidate_precise_caches( $product_ids, $option_names );
			$after = rhn_frh_snapshot_storage( $product_ids, $option_names );
			$after_stable = rhn_frh_snapshot_storage( $product_ids, $option_names );
			$result['rollback_hash_match'] = isset( $before['hash'] ) && hash_equals( $before['hash'], $after['hash'] ) && hash_equals( $after['hash'], $after_stable['hash'] );
			if ( isset( $before_woo ) ) {
				$result['woo_readback_match'] = $before_woo === rhn_frh_woo_readback( $targets );
			}
			if ( empty( $result['rollback_hash_match'] ) || empty( $result['woo_readback_match'] ) ) {
				$result['ok'] = false;
				$result['error'] = 'Rollback verification failed closed.';
			}
		}
	}
	status_header( ! empty( $result['ok'] ) ? 200 : 500 );
	header( 'Content-Type: application/json; charset=utf-8' );
	echo wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	exit;
}
