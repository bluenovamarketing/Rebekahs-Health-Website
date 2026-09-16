<?php
/** Isolated access/nonce/write-boundary tests; no WordPress or network writes. */
define( 'ABSPATH', __DIR__ );
function add_action( ...$args ) {}
function current_user_can( $cap ) { return $GLOBALS['test_admin']; }
function rhn_catalog_presentation_enabled() { return $GLOBALS['test_staging']; }
function wp_die( $message ) { throw new RuntimeException( 'DENIED' ); }
function check_admin_referer( $action ) {
    if ( ! $GLOBALS['test_nonce'] ) { throw new RuntimeException( 'NONCE' ); }
}
function wp_unslash( $value ) { return $value; }
function sanitize_text_field( $value ) { return strip_tags( $value ); }
function esc_html( $value ) { return htmlspecialchars( $value ); }
function esc_textarea( $value ) { return htmlspecialchars( $value ); }
function wp_nonce_field( $action ) {}
function get_option( $key, $default = false ) { return $GLOBALS['test_options'][$key] ?? $default; }
function update_option( $key, $value, $autoload = null ) {
    $GLOBALS['test_writes'][] = $key;
    $GLOBALS['test_options'][$key] = $value;
    return true;
}
require __DIR__ . '/../registry-import.php';
$valid = json_encode( array( array( 'sku' => '000TEST', 'approved' => true, 'source' => 'synthetic fixture', 'name' => 'Synthetic test' ) ) );
$cases = array(
    array( false, true, true, 'GET', 'preview', $valid, 'DENIED' ),
    array( true, false, true, 'GET', 'preview', $valid, 'DENIED' ),
    array( true, true, false, 'POST', 'apply', $valid, 'NONCE' ),
    array( true, true, false, 'POST', 'restore', '', 'NONCE' ),
    array( true, true, true, 'POST', 'preview', $valid, 'valid records' ),
    array( true, true, true, 'POST', 'apply', '[{"sku":"000TEST","approved":true,"source":"fixture","stock_quantity":99}]', 'Not applied:' ),
    array( true, true, true, 'POST', 'apply', '{broken', 'Not applied:' ),
    array( true, true, true, 'POST', 'restore', '', 'No previous registry snapshot' ),
);
foreach ( $cases as $index => $case ) {
    list( $test_admin, $test_staging, $test_nonce, $method, $action, $json, $expected ) = $case;
    $test_options = array();
    $test_writes = array();
    $_SERVER['REQUEST_METHOD'] = $method;
    $_POST = array( 'registry_action' => $action, 'registry_json' => $json );
    ob_start();
    try { rhn_catalog_registry_page(); } catch ( RuntimeException $error ) { echo $error->getMessage(); }
    $output = ob_get_clean();
    if ( ! str_contains( $output, $expected ) || $test_writes ) {
        throw new RuntimeException( 'Access/write boundary failed case ' . $index );
    }
}
echo "PASS: 8 registry access, nonce and no-write cases (isolated stubs)\n";
