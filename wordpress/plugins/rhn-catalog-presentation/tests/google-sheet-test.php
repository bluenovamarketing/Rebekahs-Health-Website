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

$csv_stream = fopen( 'php://temp', 'w+' );
foreach ( array( $headers, $approved, $working ) as $row ) {
    fputcsv( $csv_stream, $row, ',', '"', '' );
}
rewind( $csv_stream );
$csv = stream_get_contents( $csv_stream );
fclose( $csv_stream );
$csv_parsed = rhn_catalog_sheet_rows_to_registry( rhn_catalog_csv_rows( $csv ) );
sheet_check( isset( $csv_parsed['registry']['733739401854'] ), 'CSV fallback preserves the approved NAC barcode.' );

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
