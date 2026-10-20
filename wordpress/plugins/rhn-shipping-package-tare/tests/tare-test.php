<?php

define( 'ABSPATH', __DIR__ );

$filters = array();

function add_filter( $hook, $callback, $priority = 10 ) {
	global $filters;
	$filters[ $hook ] = array( $callback, $priority );
}

function apply_filters( $hook, $value ) {
	return $value;
}

function get_option( $name, $default = false ) {
	return 'woocommerce_weight_unit' === $name ? 'oz' : $default;
}

function wc_get_weight( $weight, $to, $from ) {
	if ( 'oz' === $to && 'oz' === $from ) {
		return $weight;
	}
	throw new RuntimeException( 'Unexpected unit conversion.' );
}

class RHN_Test_Product {
	private $weight;
	private $ships;

	public function __construct( $weight, $ships = true ) {
		$this->weight = $weight;
		$this->ships  = $ships;
	}

	public function get_weight( $context = 'view' ) {
		return $this->weight;
	}

	public function set_weight( $weight ) {
		$this->weight = $weight;
	}

	public function needs_shipping() {
		return $this->ships;
	}
}

function tare_check( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	echo "PASS: {$message}\n";
}

require dirname( __DIR__ ) . '/rhn-shipping-package-tare.php';

tare_check( isset( $filters['woocommerce_cart_shipping_packages'] ), 'Package filter is registered.' );

$original = new RHN_Test_Product( 6.0 );
$one_item = rhn_shipping_add_package_tare(
	array(
		array(
			'contents' => array(
				'one' => array( 'data' => $original, 'quantity' => 1 ),
			),
		),
	)
);
tare_check( 18.0 === $one_item[0]['contents']['one']['data']->get_weight(), 'One 6 oz product rates as 18 oz with one box.' );
tare_check( 6.0 === $original->get_weight(), 'The saved product object is not changed.' );

$three_items = rhn_shipping_add_package_tare(
	array(
		array(
			'contents' => array(
				'three' => array( 'data' => new RHN_Test_Product( 6.0 ), 'quantity' => 3 ),
			),
		),
	)
);
$rated_unit = $three_items[0]['contents']['three']['data']->get_weight();
tare_check( 30.0 === ( $rated_unit * 3 ), 'Three 6 oz products rate as 30 oz with one box, not three boxes.' );

$twice = rhn_shipping_add_package_tare( $three_items );
tare_check( $rated_unit === $twice[0]['contents']['three']['data']->get_weight(), 'A repeated filter pass does not add tare twice.' );

$two_packages = rhn_shipping_add_package_tare(
	array(
		array( 'contents' => array( 'a' => array( 'data' => new RHN_Test_Product( 4.5 ), 'quantity' => 1 ) ) ),
		array( 'contents' => array( 'b' => array( 'data' => new RHN_Test_Product( 5.5 ), 'quantity' => 1 ) ) ),
	)
);
tare_check( 16.5 === $two_packages[0]['contents']['a']['data']->get_weight(), 'The first physical package receives one tare allowance.' );
tare_check( 17.5 === $two_packages[1]['contents']['b']['data']->get_weight(), 'A second physical package receives its own tare allowance.' );

$virtual = new RHN_Test_Product( 0.0, false );
$physical = new RHN_Test_Product( 7.0, true );
$mixed = rhn_shipping_add_package_tare(
	array(
		array(
			'contents' => array(
				'virtual'  => array( 'data' => $virtual, 'quantity' => 1 ),
				'physical' => array( 'data' => $physical, 'quantity' => 1 ),
			),
		),
	)
);
tare_check( 0.0 === $mixed[0]['contents']['virtual']['data']->get_weight(), 'Virtual items do not receive package weight.' );
tare_check( 19.0 === $mixed[0]['contents']['physical']['data']->get_weight(), 'The first shippable item carries the one-time box allowance.' );

echo "All RHN shipping tare checks passed.\n";

