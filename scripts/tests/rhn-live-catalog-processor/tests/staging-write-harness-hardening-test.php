<?php
define( 'ABSPATH', __DIR__ );
define( 'HOUR_IN_SECONDS', 3600 );
require_once __DIR__ . '/../staging-write-harness/staging-adapter-harness-lib.php';

$assertions = 0;
function frh_check( $condition, $message ) {
	global $assertions;
	$assertions++;
	if ( ! $condition ) { throw new RuntimeException( $message ); }
}
function frh_throws( $callable, $needle ) {
	try { $callable(); } catch ( Throwable $error ) { return false !== strpos( $error->getMessage(), $needle ); }
	return false;
}

$release = realpath( __DIR__ . '/../release-package/rhn-live-catalog-processor' );
$files = array_map( 'basename', glob( $release . '/*.php' ) );
$symbols = rhn_frh_extract_global_functions( $files, $release );
frh_check( in_array( 'rhn_full_range_existing_reconcile_adapter', $symbols, true ), 'Release symbol scan must include the real adapter.' );
frh_check( in_array( 'rhn_live_catalog_reconcile', $symbols, true ), 'Release symbol scan must include the production reconcile function.' );
frh_check( count( $symbols ) > 100, 'Whole-package collision preflight must cover the complete global function surface.' );

$config = array(
	'run_id' => 'test-run-2026-10-28',
	'token_hash' => str_repeat( 'a', 64 ),
	'release_dir' => $release,
	'probe_event_id' => '6b9495a4-0807-4d00-9928-fdf629da61a3',
	'probe_terminal_status' => 'Done',
	'probe_checked_at_utc' => gmdate( 'c' ),
	'hook_fingerprint' => str_repeat( 'b', 64 ),
	'targets' => array(
		'approved' => array(
			'product_id' => 101, 'sku' => '000101',
			'action' => array( 'action' => 'approved', 'sku' => '000101', 'source_row' => 2, 'record' => array( 'sku' => '000101', 'approved' => true, 'source' => 'Harness', 'sheet_row' => 2, 'short_description' => 'Temporary rollback-only text' ) ),
		),
		'withdrawal' => array(
			'product_id' => 102, 'sku' => '000102',
			'action' => array( 'action' => 'withdrawal', 'sku' => '000102', 'source_row' => 3, 'record' => array( 'sku' => '000102', 'source' => 'Explicit harness withdrawal', 'row' => 3 ) ),
		),
	),
);
frh_check( true === rhn_frh_validate_config( $config ), 'Exact approved and explicit withdrawal config must validate.' );

$bad = $config; $bad['probe_terminal_status'] = 'In progress';
frh_check( frh_throws( static fn() => rhn_frh_validate_config( $bad ), 'not recorded terminal' ), 'Nonterminal Kosmos evidence must fail closed.' );
$bad = $config; $bad['targets']['approved']['action']['record']['featured_image_url'] = 'https://example.invalid/image.jpg';
frh_check( frh_throws( static fn() => rhn_frh_validate_config( $bad ), 'forbids field' ), 'Media changes must be rejected.' );
$bad = $config; $bad['targets']['approved']['action']['record']['brand'] = 'Changed Brand';
frh_check( frh_throws( static fn() => rhn_frh_validate_config( $bad ), 'forbids field' ), 'Brand changes must be rejected.' );
$bad = $config; $bad['targets']['approved']['action']['record']['categories'] = array( 'Changed' );
frh_check( frh_throws( static fn() => rhn_frh_validate_config( $bad ), 'forbids field' ), 'Category changes must be rejected.' );
$bad = $config; $bad['targets']['withdrawal']['action']['record']['description'] = 'Not identity only';
frh_check( frh_throws( static fn() => rhn_frh_validate_config( $bad ), 'identity/provenance only' ), 'Withdrawal payload must remain exact and explicit.' );
$bad = $config; $bad['targets']['withdrawal']['product_id'] = 101;
frh_check( frh_throws( static fn() => rhn_frh_validate_config( $bad ), 'distinct products' ), 'Two targets must be distinct.' );

$main = file_get_contents( __DIR__ . '/../staging-write-harness/rhn-full-range-staging-adapter-harness.php' );
foreach ( array(
	"START TRANSACTION", "ROLLBACK", "SELECT @@in_transaction", "FOR UPDATE", "rhn_frh_require_innodb",
	"rhn_frh_require_idle", "rhn_frh_block_side_effects", "pre_http_request", "pre_wp_mail",
	"woocommerce_webhook_should_deliver", "pre_schedule_event", "pre_as_schedule_single_action",
	"rhn_full_range_existing_reconcile_adapter( false )", "rhn_full_range_existing_reconcile_adapter( true )",
	"rollback_hash_match", "woo_readback_match", "option_active_plugins", "rhn_frh_consume_once",
	"actionscheduler_actions", "actionscheduler_logs", "wc_product_meta_lookup",
	"term_taxonomy", "rhn_frh_product_hook_fingerprint", "rhn_frh_assert_callback_corridor",
	"rhn_frh_async_action_markers", "rhn_frh_async_log_markers", "SELECT COUNT(*) AS row_count",
	"rhn_frh_bounded_table_summary", "actionscheduler_claims", "actionscheduler_groups",
	"Unreviewed product callback blocks the rehearsal", "Selected products were not stable across two Woo reads",
	"add_management_page", "admin-post.php", "rollback-only adapter rehearsal",
) as $needle ) {
	frh_check( false !== strpos( $main, $needle ), 'Harness is missing hardening surface: ' . $needle );
}
frh_check( ! preg_match( '/\bwp_schedule_[a-z_]+\s*\(|\bas_schedule_[a-z_]+\s*\(/', $main ), 'Harness must not create a schedule.' );
frh_check( false === strpos( $main, 'wp_insert_post(' ) && false === strpos( $main, 'new WC_Product' ), 'Harness must not create a product.' );
frh_check( false === strpos( $main, 'media_sideload') && false === strpos( $main, 'download_url(' ), 'Harness must not contain media/download logic.' );
frh_check( ! preg_match( '/SELECT\s+action_id,\s*hook,\s*status,\s*scheduled_date_gmt,\s*args\s+FROM\s+\{\$actions\}\s+ORDER\s+BY\s+action_id(?!\s+DESC\s+LIMIT)/i', $main ), 'Harness must not scan the complete Action Scheduler action history.' );
frh_check( ! preg_match( '/SELECT\s+log_id,\s*action_id,\s*message,\s*log_date_gmt\s+FROM\s+\{\$logs\}\s+ORDER\s+BY\s+log_id/i', $main ), 'Harness must not scan the complete Action Scheduler log history.' );
frh_check( false !== strpos( $main, 'LIMIT 100' ) && false !== strpos( $main, 'LIMIT 200' ), 'Related Action Scheduler materialization must stay bounded.' );
frh_check( false !== strpos( $main, "WP_PLUGIN_DIR ) . 'woocommerce/'" ), 'Callback corridor must limit third-party callbacks to audited WooCommerce code.' );
frh_check( 2 === substr_count( $main, 'rhn_frh_assert_callback_corridor( $release_dir );' ), 'Callback corridor must run both before and after the signed release is loaded.' );

$dry_targets = array_values( $config['targets'] );
$dry = array( '000101' => array( 'verified' => true, 'outcome' => 'planned_update' ), '000102' => array( 'verified' => true, 'outcome' => 'planned_update' ) );
frh_check( true === rhn_frh_validate_dry_results( $dry_targets, $dry ), 'Both target actions require a real dry-planned update.' );
$dry['000102']['outcome'] = 'already_correct';
frh_check( frh_throws( static fn() => rhn_frh_validate_dry_results( $dry_targets, $dry ), 'required real update' ), 'Already-correct withdrawal is insufficient for the write-path rehearsal.' );

echo 'PASS staging write harness hardening: ' . $assertions . " assertions.\n";
