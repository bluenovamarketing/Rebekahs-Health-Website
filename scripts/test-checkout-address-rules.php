<?php
/** Offline regression tests of the exact v1.10 staging deployment payload. No network or orders. */
function wp_strip_all_tags( $text ) { return strip_tags( $text ); }
function get_bloginfo( $key ) { return 'UTF-8'; }
function __( $text, $domain = '' ) { return $text; }
function esc_html__( $text, $domain = '' ) { return $text; }
class WC_Order {
    public $physical = true;
    public $ship1 = '';
    public $ship2 = '';
    public $bill1 = '';
    public $bill2 = '';
    function needs_shipping_address() { return $this->physical; }
    function get_shipping_address_1() { return $this->ship1; }
    function get_shipping_address_2() { return $this->ship2; }
    function get_billing_address_1() { return $this->bill1; }
    function get_billing_address_2() { return $this->bill2; }
}
class WP_Error {
    public $errors = array();
    function add( $code, $message ) { $this->errors[ $code ] = $message; }
}
class TestShippingCart {
    public $physical = true;
    function needs_shipping() { return $this->physical; }
}
$test_wc = (object) array( 'cart' => new TestShippingCart() );
function WC() { return $GLOBALS['test_wc']; }
$source = file_get_contents( __DIR__ . '/../wordpress/plugins/blue-nova-phase-two-installer-v1-10/payload/theme/inc/phase-two-ecommerce.php' );
foreach ( array( 'rhn_phase_two_address_is_po_box', 'rhn_phase_two_validate_delivery_address', 'rhn_phase_two_validate_store_api_delivery_address' ) as $name ) {
    $start = strpos( $source, 'function ' . $name . '(' );
    if ( false === $start ) { throw new Exception( 'Function not found: ' . $name ); }
    $open = strpos( $source, '{', $start );
    $depth = 1;
    $end = $open + 1;
    while ( $depth && $end < strlen( $source ) ) {
        if ( '{' === $source[$end] ) { ++$depth; }
        if ( '}' === $source[$end] ) { --$depth; }
        ++$end;
    }
    if ( false === $start || $depth ) { throw new Exception( 'Function extraction failed' ); }
    eval( substr( $source, $start, $end - $start ) );
}
$count = 0;
foreach ( array(
    'PO Box 123' => true, 'P.O. Box 123' => true, 'P O Box 123' => true,
    'Post Office Box 123' => true, 'po box 1' => true, 'Suite 2 PO Box 9' => true,
    '7093 Dixie Highway' => false, '123 Boxwood Lane' => false,
    '12 Post Road' => false, '123 Poppy Lane' => false, '' => false,
) as $address => $expected ) {
    if ( rhn_phase_two_address_is_po_box( $address ) !== $expected ) { throw new Exception( 'Address classifier failed' ); }
    ++$count;
}
foreach ( array(
    array( false, 'PO Box 1', '', '123 Main St', '', true, true ),
    array( false, '123 Main St', 'PO Box 1', '', '', true, true ),
    array( true, 'PO Box 1', '', '123 Main St', '', true, false ),
    array( true, '123 Main St', '', 'PO Box 1', '', true, true ),
    array( true, '123 Main St', '', '123 Main St', 'P.O. Box 1', true, true ),
    array( false, '123 Main St', '', '', '', true, false ),
    array( false, 'PO Box 1', '', '', '', false, false ),
) as $case ) {
    list( $different, $bill1, $bill2, $ship1, $ship2, $physical, $blocked ) = $case;
    $test_wc->cart->physical = $physical;
    $errors = new WP_Error();
    rhn_phase_two_validate_delivery_address( array(
        'ship_to_different_address' => $different,
        'billing_address_1' => $bill1, 'billing_address_2' => $bill2,
        'shipping_address_1' => $ship1, 'shipping_address_2' => $ship2,
    ), $errors );
    if ( isset( $errors->errors['rhn_po_box_delivery'] ) !== $blocked ) { throw new Exception( 'Delivery selection failed' ); }
    ++$count;
}
foreach ( array(
    array( 'PO Box 1', '', '123 Main St', '', true, true ),
    array( '123 Main St', 'P.O. Box 1', '', '', true, true ),
    array( '123 Main St', '', 'PO Box 1', '', true, false ),
    array( '', '', 'PO Box 1', '', true, true ),
    array( '', '', '123 Main St', 'Post Office Box 2', true, true ),
    array( '', '', '123 Main St', '', true, false ),
    array( 'PO Box 1', '', '', '', false, false ),
    array( '', '', '', '', true, false ),
) as $case ) {
    $order = new WC_Order();
    list( $order->ship1, $order->ship2, $order->bill1, $order->bill2, $order->physical, $blocked ) = $case;
    $thrown = false;
    try { rhn_phase_two_validate_store_api_delivery_address( $order ); }
    catch ( Exception $error ) {
        if ( $error->getMessage() !== 'We cannot ship to PO boxes. Please enter a street delivery address.' ) { throw $error; }
        $thrown = true;
    }
    if ( $thrown !== $blocked ) { throw new Exception( 'Store API delivery selection failed' ); }
    ++$count;
}
rhn_phase_two_validate_store_api_delivery_address( new stdClass() );
++ $count;
echo "PASS: $count address/classic checkout/Store API selection cases (offline payload tests)\n";
