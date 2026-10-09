<?php
/** Isolated Google Sheet parsing tests; no Google, WordPress or product writes. */
define( 'ABSPATH', __DIR__ );
function add_action( ...$args ) {}
function sanitize_text_field( $value ) { return trim( strip_tags( $value ) ); }
function wp_json_encode( $value, $flags = 0 ) { return json_encode( $value, $flags ); }
require __DIR__ . '/../registry-import.php';
require __DIR__ . '/../google-sheet-sync.php';

$checks = 0;
function sheet_check( $condition, $message ) {
    global $checks;
    if ( ! $condition ) {
        throw new RuntimeException( $message );
    }
    $checks++;
}

$headers = array_keys( rhn_catalog_sheet_headers() );
$approved = array(
    'Approved for Test',
    '733739401854',
    'TEST NAC Product',
    'Rebekah’s Private Label',
    "Immune Support|Vitamins & Supplements",
    'TEST long description',
    'TEST short description',
    '0.73',
    'lb',
    '60 capsules',
    'https://drive.google.com/file/d/test-image/view',
    "https://drive.google.com/file/d/gallery-a/view\nhttps://drive.google.com/file/d/gallery-b/view",
    'TEST ingredients',
    'TEST allergens',
    'TEST supplement facts',
    'TEST directions',
    'TEST warnings',
    'TEST SEO title',
    'TEST SEO description',
    'Controlled staging fixture',
    'Todd Bailey',
    '2026-09-26',
    'Remove after proof',
);
$working = $approved;
$working[0] = 'Working';
$working[1] = 'SKIPPED-ROW';
$withdrawn = $approved;
$withdrawn[0] = 'Remove from Website';
$withdrawn[1] = '788332054013';

$parsed = rhn_catalog_sheet_rows_to_registry( array( $headers, $approved, $working, $withdrawn ) );
sheet_check( 1 === count( $parsed['registry'] ), 'Only approved rows are accepted.' );
sheet_check( isset( $parsed['registry']['733739401854'] ), 'Barcode remains exact text.' );
sheet_check( array( 'Immune Support', 'Vitamins & Supplements' ) === $parsed['registry']['733739401854']['categories'], 'Category list parsed.' );
sheet_check( 2 === count( $parsed['registry']['733739401854']['gallery_image_urls'] ), 'Gallery list parsed.' );
sheet_check( '0.73' === $parsed['registry']['733739401854']['weight'], 'Weight remains numeric text.' );
sheet_check( '60 capsules' === $parsed['registry']['733739401854']['package_size'], 'Package size / net contents parsed separately from shipping weight.' );
sheet_check( 1 === count( $parsed['skipped'] ) && 3 === $parsed['skipped'][0]['row'], 'Working row skipped with correct sheet row.' );
sheet_check( isset( $parsed['withdrawals']['788332054013'] ), 'Exact Remove from Website row is accepted separately.' );
sheet_check( 4 === $parsed['withdrawals']['788332054013']['row'], 'Withdrawal keeps its exact source row.' );
sheet_check( 'Controlled staging fixture' === $parsed['registry']['733739401854']['source'], 'Client-entered source evidence is preserved.' );

$blank_source = $approved;
$blank_source[1] = 'BLANK-SOURCE-APPROVED';
$blank_source[19] = '';
$blank_source_parsed = rhn_catalog_sheet_rows_to_registry( array( $headers, $blank_source ) );
sheet_check(
    'Client-owned Inventory Sheet — Catalog row 2' === $blank_source_parsed['registry']['BLANK-SOURCE-APPROVED']['source'],
    'An approved row with no separate evidence uses its exact client-owned Sheet row as derived provenance.'
);

$combined_weight = $approved;
$combined_weight[1] = 'COMBINED-WEIGHT';
$combined_weight[7] = '4.5oz';
$combined_weight[8] = '';
$combined_weight[9] = '1 fl oz (30 mL)';
$combined_parsed = rhn_catalog_sheet_rows_to_registry( array( $headers, $combined_weight ) );
sheet_check( '4.5' === $combined_parsed['registry']['COMBINED-WEIGHT']['weight'], 'Attached ounces are separated without changing the Sheet.' );
sheet_check( 'oz' === $combined_parsed['registry']['COMBINED-WEIGHT']['weight_unit'], 'Attached ounce unit is normalized for import.' );

$future_blank = $approved;
$future_blank[1] = 'FUTURE-90';
$future_blank[7] = '';
$future_blank[8] = '';
$future_blank[9] = '90 veggie capsules';
$future_parsed = rhn_catalog_sheet_rows_to_registry( array( $headers, $future_blank ) );
sheet_check( '7' === $future_parsed['registry']['FUTURE-90']['weight'], 'Blank 90-count product receives the approved future default.' );
sheet_check( 'oz' === $future_parsed['registry']['FUTURE-90']['weight_unit'], 'Future default uses ounces.' );

$unmatched_120 = $approved;
$unmatched_120[1] = 'UNMATCHED-120';
$unmatched_120[7] = '';
$unmatched_120[8] = '';
$unmatched_120[9] = '120 capsules';
$unmatched_parsed = rhn_catalog_sheet_rows_to_registry( array( $headers, $unmatched_120 ) );
sheet_check( ! isset( $unmatched_parsed['registry']['UNMATCHED-120']['weight'] ), 'Unmatched 120-count product is not guessed.' );
sheet_check( ! isset( $unmatched_parsed['registry']['UNMATCHED-120']['weight_unit'] ), 'Unmatched 120-count product receives no invented unit.' );

$quercetin_120 = $approved;
$quercetin_120[1] = '733739430700';
$quercetin_120[2] = "Rebekah's Quercetin with Bromelain";
$quercetin_120[7] = '';
$quercetin_120[8] = '';
$quercetin_120[9] = '120 capsules';
$quercetin_parsed = rhn_catalog_sheet_rows_to_registry( array( $headers, $quercetin_120 ) );
sheet_check( '6' === $quercetin_parsed['registry']['733739430700']['weight'], 'Exact Quercetin 120-count SKU receives its client-approved weight.' );
sheet_check( 'oz' === $quercetin_parsed['registry']['733739430700']['weight_unit'], 'Exact Quercetin 120-count fallback uses ounces.' );

$quercetin_existing = $quercetin_120;
$quercetin_existing[7] = '6.5oz';
$quercetin_existing_parsed = rhn_catalog_sheet_rows_to_registry( array( $headers, $quercetin_existing ) );
sheet_check( '6.5' === $quercetin_existing_parsed['registry']['733739430700']['weight'], 'A later client-entered Quercetin weight still overrides the fallback.' );
sheet_check( 'oz' === $quercetin_existing_parsed['registry']['733739430700']['weight_unit'], 'A later client-entered Quercetin unit still overrides the fallback.' );

$csv_stream = fopen( 'php://temp', 'w+' );
foreach ( array( $headers, $approved, $working ) as $row ) {
    fputcsv( $csv_stream, $row, ',', '"', '' );
}
rewind( $csv_stream );
$csv = stream_get_contents( $csv_stream );
fclose( $csv_stream );
$csv_parsed = rhn_catalog_sheet_rows_to_registry( rhn_catalog_csv_rows( $csv ) );
sheet_check( isset( $csv_parsed['registry']['733739401854'] ), 'CSV fallback preserves the approved NAC barcode.' );

$current_catalog_stream = fopen( 'php://temp', 'w+' );
fputcsv( $current_catalog_stream, $headers, ',', '"', '' );
for ( $index = 0; $index < 1169; $index++ ) {
    $current_row = $working;
    $current_row[1] = str_pad( (string) $index, 12, '0', STR_PAD_LEFT );
    $current_row[2] = 'Current catalog product ' . $index;
    fputcsv( $current_catalog_stream, $current_row, ',', '"', '' );
}
rewind( $current_catalog_stream );
$current_catalog_csv = stream_get_contents( $current_catalog_stream );
fclose( $current_catalog_stream );
sheet_check( 1170 === count( rhn_catalog_csv_rows( $current_catalog_csv ) ), 'CSV intake accepts the current 1,169-product catalog plus its header.' );

$bad_headers = $headers;
array_shift( $bad_headers );
try {
    rhn_catalog_sheet_rows_to_registry( array( $bad_headers, array_slice( $approved, 1 ) ) );
    throw new RuntimeException( 'Missing Status heading was accepted.' );
} catch ( InvalidArgumentException $error ) {
    sheet_check( str_contains( $error->getMessage(), 'status' ), 'Missing required heading rejected.' );
}

$unapproved = $approved;
$unapproved[0] = 'Hold';
$no_action = rhn_catalog_sheet_rows_to_registry( array( $headers, $unapproved ) );
sheet_check( array() === $no_action['registry'] && array() === $no_action['withdrawals'], 'Sheet with no actionable rows is a safe no-op.' );
sheet_check( 1 === count( $no_action['skipped'] ), 'No-op Sheet still reports its skipped row.' );

echo "PASS: $checks isolated Google Sheet parsing and approval checks.\n";
