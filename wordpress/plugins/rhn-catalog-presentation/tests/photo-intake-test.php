<?php
/** Isolated photo-intake parsing/matching tests; no Google, email or WordPress writes. */
define( 'ABSPATH', __DIR__ );
function get_option( $key, $default = false ) { return $default; }
function remove_accents( $value ) { return $value; }
class WP_Error {
    private $message;
    function __construct( $code, $message ) { $this->message = $message; }
    function get_error_message() { return $this->message; }
}
function is_wp_error( $value ) { return $value instanceof WP_Error; }
require __DIR__ . '/../photo-intake.php';

$checks = 0;
function photo_check( $condition, $message ) {
    global $checks;
    if ( ! $condition ) {
        throw new RuntimeException( $message );
    }
    $checks++;
}

$headers = array(
    'Review Status *',
    'Online Decision *',
    'SKU / Barcode — READ ONLY',
    'Revel Product Name — READ ONLY',
    'Website Product Title (Optional)',
    'Brand *',
    'Primary Website Category *',
    'Additional Website Category 1 (Optional)',
    'Additional Website Category 2 (Optional)',
    'Additional Website Category 3 (Optional)',
    'Package Size / Net Contents *',
    'Packaged Shipping Weight *',
    'Weight Unit *',
    'Photos Uploaded?',
    'Photo Status',
    'Photo / Source Notes',
);
$checked = array( 'Working', 'Publish', '733739401854', "REBEKAH'S NAC 1000MG", '', "Rebekah's", 'Immune Support', '', '', '', '120 tablets', '', '', true, 'Provided', 'Uploaded to Ecom media' );
$unchecked = $checked;
$unchecked[2] = '788332054013';
$unchecked[3] = 'EAR CLEAR';
$unchecked[13] = false;
$requests = rhn_catalog_photo_requests_from_rows( array( $headers, $checked, $unchecked ) );
photo_check( 1 === count( $requests ), 'Only checked photo rows become intake requests.' );
photo_check( isset( $requests['733739401854'] ), 'SKU remains the exact photo-intake identity.' );

$files = array(
    array( 'id' => 'sku-file', 'name' => '733739401854-front.jpg', 'mimeType' => 'image/jpeg', 'modifiedTime' => '2026-10-02T12:00:00Z' ),
    array( 'id' => 'unrelated', 'name' => 'supplement-photo.jpg', 'mimeType' => 'image/jpeg', 'modifiedTime' => '2026-10-02T12:00:00Z' ),
);
$matches = rhn_catalog_photo_match_files( $requests['733739401854'], $files );
photo_check( 1 === count( $matches ), 'Only a high-confidence filename match is accepted.' );
photo_check( 'sku-file' === $matches[0]['id'] && 100 === $matches[0]['score'], 'Exact SKU filename receives the highest confidence.' );
photo_check( str_contains( $matches[0]['url'], '/sku-file/view' ), 'Matched Drive file receives a stable authenticated file URL.' );

$first = rhn_catalog_photo_folder_fingerprint( $files );
$second = rhn_catalog_photo_folder_fingerprint( array_reverse( $files ) );
photo_check( $first === $second, 'Folder fingerprint is independent of API result order.' );
$files[0]['modifiedTime'] = '2026-10-02T13:00:00Z';
photo_check( $first !== rhn_catalog_photo_folder_fingerprint( $files ), 'A changed file produces a new one-time intake fingerprint.' );
photo_check( ! rhn_catalog_photo_should_process( array( 'status' => 'matched', 'folder_fingerprint' => $first ), 'different-folder-fingerprint' ), 'A completed match stays one-time when unrelated folder files change.' );
photo_check( ! rhn_catalog_photo_should_process( array( 'status' => 'needs-review', 'folder_fingerprint' => $first ), $first ), 'An unchanged no-match is not repeated every four hours.' );
photo_check( rhn_catalog_photo_should_process( array( 'status' => 'needs-review', 'folder_fingerprint' => $first ), 'different-folder-fingerprint' ), 'A prior no-match retries after new media arrives.' );

echo "PASS: $checks isolated photo-intake checks.\n";
