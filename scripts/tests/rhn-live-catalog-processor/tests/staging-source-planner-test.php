<?php
define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );
function source_check( $condition, $message ) { static $count = 0; $GLOBALS['source_assertions'] = ++$count; if ( ! $condition ) { throw new RuntimeException( 'FAIL: ' . $message ); } }
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'staging-validation-loader' . DIRECTORY_SEPARATOR . 'staging-preparation.php';
$header = array( 'Status', 'SKU / Barcode', 'Product Name' );
$rows = array(
	array( 'Approved', '000123', 'Leading zero' ),
	array( 'Working / Keep Off Website', '99', 'No action' ),
	array( 'Remove from Website', '88', 'Explicit removal' ),
	array( 'Approved', '000123', 'Duplicate' ),
	array( 'Approved', '', 'Blank' ),
);
$result = rhn_fr_stage_plan_rows( $header, $rows );
source_check( 5 === $result['total_source_rows'], 'all source rows counted' );
source_check( 1 === $result['approved'] && 1 === $result['withdrawals'] && 1 === $result['not_approved'], 'only explicit statuses planned' );
source_check( 1 === $result['duplicate_identity'] && 1 === $result['blank_identity'], 'duplicate and blank identities isolated' );
source_check( 64 === strlen( $result['identity_fingerprint_sha256'] ), 'text identities produce stable non-secret fingerprint' );
source_check( $result['identity_fingerprint_sha256'] !== hash( 'sha256', "123\n99\n88" ), 'leading-zero barcode is preserved as text' );
echo 'PASS staging source planner: ' . $GLOBALS['source_assertions'] . " assertions.\n";
