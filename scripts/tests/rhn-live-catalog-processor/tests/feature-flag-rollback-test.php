<?php
define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );
define( 'HOUR_IN_SECONDS', 3600 );
function add_filter() { return true; }
$hooks = array();
function add_action( $hook, $callback, $priority = 10, $args = 1 ) { global $hooks; $hooks[ $hook ][ $priority ][] = array( $callback, $args ); return true; }
function remove_action( $hook, $callback, $priority = 10 ) { global $hooks; foreach ( (array) ( $hooks[ $hook ][ $priority ] ?? array() ) as $index => $item ) { if ( $item[0] === $callback ) { unset( $hooks[ $hook ][ $priority ][ $index ] ); } } }
function wp_json_encode( $value, $flags = 0 ) { return json_encode( $value, $flags | JSON_THROW_ON_ERROR ); }
$production_root = $argv[1] ?? dirname( __DIR__, 2 ) . DIRECTORY_SEPARATOR . 'deployed-production-readonly';
require_once rtrim( $production_root, '\\/' ) . DIRECTORY_SEPARATOR . 'production-scheduler.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog-runtime.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog-integration.php';
rhn_full_range_scheduler_bootstrap();
$sheet = array_map( static fn( $item ) => $item[0], array_values( $hooks['rhn_catalog_sheet_sync_event'][10] ?? array() ) );
$job = array_map( static fn( $item ) => $item[0], array_values( $hooks['rhn_live_catalog_cycle_job'][10] ?? array() ) );
if ( ! in_array( 'rhn_live_catalog_start_cycle', $sheet, true ) || in_array( 'rhn_full_range_start_cycle', $sheet, true ) || ! in_array( 'rhn_live_catalog_run_cycle_job', $job, true ) || in_array( 'rhn_full_range_run_cycle_job', $job, true ) ) {
	throw new RuntimeException( 'FAIL: disabled feature did not preserve the exact existing scheduler callbacks.' );
}
echo "PASS feature-disabled rollback path preserves existing scheduler callbacks.\n";
