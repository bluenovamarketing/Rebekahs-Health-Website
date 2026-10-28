<?php
define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );
define( 'RHN_FULL_RANGE_CATALOG_ENABLED', true );
define( 'RHN_FULL_RANGE_CATALOG_APPLY_ENABLED', true );
$loader_hooks = array();

function add_action( $hook, $callback, $priority = 10, $args = 1 ) { global $loader_hooks; $loader_hooks[] = array( $hook, $callback, $priority, $args ); return true; }
function get_option( $key, $default = false ) { return $default; }
function home_url() { return 'https://wordpress-1651482-6655800.cloudwaysapps.com/'; }
function is_ssl() { return true; }
function wp_parse_url( $url, $part = -1 ) { return parse_url( $url, $part ); }
function current_user_can() { return true; }
function maybe_serialize( $value ) { return serialize( $value ); }
function maybe_unserialize( $value ) { return unserialize( $value ); }
function wp_cache_delete() { return true; }
function loader_functions( $directory ) { $out = array(); foreach ( glob( rtrim( $directory, '\\/' ) . DIRECTORY_SEPARATOR . '*.php' ) as $file ) { preg_match_all( '/^function\s+([A-Za-z0-9_]+)\s*\(/m', file_get_contents( $file ), $matches ); foreach ( $matches[1] as $name ) { $out[ $name ] = basename( $file ); } } return $out; }
function stage_check( $condition, $message ) { static $count = 0; $GLOBALS['stage_assertions'] = ++$count; if ( ! $condition ) { throw new RuntimeException( 'FAIL: ' . $message ); } }

$existing = $argv[1] ?? 'C:\\Users\\todda\\Blue Nova Projects\\Rebekahs Health Website\\wordpress\\plugins\\rhn-catalog-presentation';
$loader = $argv[2] ?? dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'staging-validation-loader';
$collisions = array_intersect_key( loader_functions( $existing ), loader_functions( $loader ) );
stage_check( array() === $collisions, 'preparation loader has no global-function collision with installed plugin' );
require_once $loader . DIRECTORY_SEPARATOR . 'rhn-full-range-staging-validation.php';
stage_check( array( 'admin_menu' ) === array_values( array_unique( array_column( $loader_hooks, 0 ) ) ), 'only administrator menu hook is registered' );
foreach ( array( 'rhn_full_range_start_cycle', 'rhn_full_range_run_cycle_job', 'rhn_full_range_process_next_action', 'rhn_full_range_scheduler_apply', 'rhn_full_range_existing_reconcile_adapter' ) as $forbidden ) {
	stage_check( ! function_exists( $forbidden ), 'write/processor function absent: ' . $forbidden );
}
stage_check( ! in_array( 'rhn_catalog_sheet_sync_event', array_column( $loader_hooks, 0 ), true ), 'existing sheet callback untouched' );
stage_check( ! in_array( 'rhn_live_catalog_cycle_job', array_column( $loader_hooks, 0 ), true ), 'existing cycle callback untouched' );
stage_check( function_exists( 'rhn_fr_stage_prepare' ) && function_exists( 'rhn_fr_stage_cleanup_preview' ), 'narrow preparation and cleanup entry points exist' );
echo 'PASS intrinsic staging-loader safety: ' . $GLOBALS['stage_assertions'] . " assertions.\n";
