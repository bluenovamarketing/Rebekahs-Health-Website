<?php
/** Isolated photo-intake parsing/matching tests; no Google, email or WordPress writes. */
define( 'ABSPATH', __DIR__ );
$photo_test_options = array();
$photo_test_rows = array();
$photo_test_files = array();
function get_option( $key, $default = false ) {
    global $photo_test_options;
    return $photo_test_options[ $key ] ?? $default;
}
function current_time( $type ) { return '2026-10-07 12:00:00'; }
function rhn_catalog_google_private_credentials_configured() { return true; }
function rhn_catalog_google_sheet_id() { return 'test-sheet'; }
function wp_safe_remote_get( $url, $args ) {
    global $photo_test_rows, $photo_test_files;
    $body = str_contains( $url, 'sheets.googleapis.com' )
        ? array( 'values' => $photo_test_rows )
        : array( 'files' => $photo_test_files );
    return array( 'response' => array( 'code' => 200 ), 'body' => json_encode( $body ) );
}
function wp_remote_retrieve_body( $response ) { return $response['body']; }
function wp_remote_retrieve_response_code( $response ) { return $response['response']['code']; }
function add_query_arg( $parameters, $url ) { return $url; }
function rhn_catalog_google_access_token() { return 'test-token'; }
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
    'Information Status *',
    'Website Action *',
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

$photo_test_rows = array( $headers, $checked );
$photo_test_files = array(
    array( 'id' => 'front-file', 'name' => '733739401854-front.jpg', 'mimeType' => 'image/jpeg', 'modifiedTime' => '2026-10-07T12:00:00Z' ),
    array( 'id' => 'gallery-file', 'name' => '733739401854-ingredients.jpg', 'mimeType' => 'image/jpeg', 'modifiedTime' => '2026-10-07T12:01:00Z' ),
);
$intake = rhn_catalog_photo_intake(
    array(
        'registry' => array(
            '733739401854' => array( 'featured_image_url' => '', 'gallery_image_urls' => array() ),
        ),
    ),
    false
);
photo_check( ! empty( $intake['registry']['733739401854']['featured_image_url'] ), 'Matched featured-image URL persists in the returned registry.' );
photo_check( 1 === count( $intake['registry']['733739401854']['gallery_image_urls'] ), 'Matched gallery URL persists in the returned registry.' );

echo "PASS: $checks isolated photo-intake checks.\n";
