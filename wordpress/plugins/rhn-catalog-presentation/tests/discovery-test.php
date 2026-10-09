<?php
/** Isolated Clarkston Revel discovery tests; no Google, WordPress or product writes. */
define( 'ABSPATH', __DIR__ );
function add_action( ...$args ) {}
require __DIR__ . '/../google-sheet-discovery.php';

$checks = 0;
function discovery_check( $condition, $message ) {
    global $checks;
    if ( ! $condition ) {
        throw new RuntimeException( $message );
    }
    $checks++;
}

$headers = array(
    'Information Status *', 'Website Action *', 'SKU / Barcode — READ ONLY', 'Revel Product Name — READ ONLY',
    'Website Product Title (Optional)', 'Brand *', 'Primary Category *', 'Secondary Category', 'Product Type / Form',
    'Package Size / Net Contents *', 'Packaged Shipping Weight *', 'Weight Unit *', 'Reserved', 'Photos Uploaded?',
    'Photo Status', 'Photo / Source Notes', 'Short Description *', 'Long Description', 'Ingredients', 'Allergens',
    'Supplement Facts', 'Directions', 'Warnings', 'Client Notes',
);
$existing = array_fill( 0, 24, '' );
$existing[0] = 'Working';
$existing[2] = '00123';
$existing[3] = 'Existing Revel Name';
$existing[13] = false;
$existing[14] = 'Not Reviewed';
$rows = array( $headers, $existing );

$products = array(
    array( 'id' => 101, 'sku' => '00123', 'name' => 'Existing Revel Name' ),
    array( 'id' => 102, 'sku' => '00999', 'name' => 'New Clarkston Product' ),
);
$plan = rhn_catalog_discovery_plan( $products, $rows );
discovery_check( ! $plan['errors'], 'Valid exact identities produce no errors.' );
discovery_check( isset( $plan['unchanged']['00123'] ), 'An exact existing barcode and name is unchanged.' );
discovery_check( isset( $plan['new']['00999'] ), 'A missing Revel barcode is planned as a new row.' );
discovery_check( '00999' === $plan['new']['00999']['sku'], 'Leading zeroes are preserved.' );

$renamed_products = $products;
$renamed_products[0]['name'] = 'Updated Revel Source Name';
$rename_plan = rhn_catalog_discovery_plan( $renamed_products, $rows );
discovery_check( 6 === $rename_plan['name_updates']['00123']['row'], 'The first client product remains row 6.' );
discovery_check( 'Existing Revel Name' === $rename_plan['name_updates']['00123']['previous'], 'Name reconciliation retains the previous read-only value for audit.' );

$metadata = array(
    'Product Review' => array( 'sheet_id' => 10, 'row_count' => 6 ),
    'Catalog'        => array( 'sheet_id' => 20, 'row_count' => 2 ),
);
$catalog_rows = array( array_fill( 0, 23, 'header' ), array_fill( 0, 23, '=formula') );
$requests = rhn_catalog_discovery_batch_requests( $rename_plan, $rows, $catalog_rows, $metadata );
discovery_check( 9 === count( $requests ), 'One new row plus one source-name update produces the complete atomic request set.' );
discovery_check( 1 === $requests[0]['appendDimension']['length'], 'Product Review grows only by the missing row count.' );
discovery_check( 1 === $requests[1]['appendDimension']['length'], 'Catalog grows one-for-one with Product Review.' );
$update_rows = $requests[4]['updateCells']['rows'];
discovery_check( 'Working' === $update_rows[0]['values'][0]['userEnteredValue']['stringValue'], 'New rows start in Working status.' );
discovery_check( 'Keep Off Website' === $update_rows[0]['values'][1]['userEnteredValue']['stringValue'], 'New rows start with the agreed Keep Off Website action.' );
discovery_check( '00999' === $update_rows[0]['values'][2]['userEnteredValue']['stringValue'], 'The exact Revel barcode is written to the read-only identity column.' );
discovery_check( false === $update_rows[0]['values'][13]['userEnteredValue']['boolValue'], 'The photo checkbox starts unchecked.' );
discovery_check( 'Not Reviewed' === $update_rows[0]['values'][14]['userEnteredValue']['stringValue'], 'Photo status starts unreviewed.' );
discovery_check( 'PASTE_FORMULA' === $requests[7]['copyPaste']['pasteType'], 'Hidden Catalog formulas extend one-for-one.' );
discovery_check( 3 === $requests[8]['updateCells']['range']['startColumnIndex'], 'Existing reconciliation can update only read-only Revel name column D.' );

$legacy_requests = rhn_catalog_discovery_batch_requests( $rename_plan, $rows, $catalog_rows, $metadata, 'Remove from Website' );
$legacy_update_rows = $legacy_requests[4]['updateCells']['rows'];
discovery_check( 'Remove from Website' === $legacy_update_rows[0]['values'][1]['userEnteredValue']['stringValue'], 'Verified existing legacy products begin with the agreed Remove from Website action.' );

$legacy_path = __DIR__ . '/../data/clarkston-revel-legacy-backfill-2026-10-05.json';
$legacy_records = json_decode( (string) file_get_contents( $legacy_path ), true );
discovery_check( is_array( $legacy_records ) && 343 === count( $legacy_records ), 'The legacy source map contains exactly 343 verified identities.' );
$legacy_skus = array_column( $legacy_records, 'sku' );
discovery_check( 343 === count( array_unique( $legacy_skus ) ), 'Every legacy source-map SKU is unique.' );
discovery_check( ! in_array( '', array_map( 'trim', array_column( $legacy_records, 'name' ) ), true ), 'Every legacy source-map identity has a nonempty Revel name.' );

$duplicate_products = $products;
$duplicate_products[] = array( 'id' => 103, 'sku' => '00999', 'name' => 'Duplicate' );
$duplicate_plan = rhn_catalog_discovery_plan( $duplicate_products, $rows );
discovery_check( 1 === count( $duplicate_plan['errors'] ), 'Duplicate source SKU fails closed.' );
discovery_check( ! $duplicate_plan['new'], 'A duplicate source plan cannot append rows.' );

$duplicate_rows = $rows;
$duplicate_rows[] = $existing;
$duplicate_sheet_plan = rhn_catalog_discovery_plan( $products, $duplicate_rows );
discovery_check( 1 === count( $duplicate_sheet_plan['errors'] ), 'Duplicate Sheet SKU fails closed.' );

echo "PASS: $checks isolated Clarkston Revel discovery checks.\n";
