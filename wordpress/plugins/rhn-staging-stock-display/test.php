<?php
define( 'ABSPATH', __DIR__ );
function add_filter( ...$args ) {}
function get_stylesheet() { return $GLOBALS['test_theme']; }
function doing_action( $action ) { return $GLOBALS['test_action'] && 'woocommerce_simple_add_to_cart' === $action; }
class StockDisplayProduct {
	public $type = 'simple';
	function is_type( $type ) { return $type === $this->type; }
}
require __DIR__ . '/rhn-staging-stock-display.php';
$product = new StockDisplayProduct();
$cases = array(
	array( 'wordpress-1651482-6655800.cloudwaysapps.com', 'rebekahs-2026', true, 'simple', '' ),
	array( 'wordpress-1651482-6655800.cloudwaysapps.com', 'rebekahs-2026', false, 'simple', 'stock' ),
	array( 'rebekahspureliving.com', 'rebekahs-2026', true, 'simple', 'stock' ),
	array( 'wordpress-1651482-6655800.cloudwaysapps.com', 'other-theme', true, 'simple', 'stock' ),
	array( 'wordpress-1651482-6655800.cloudwaysapps.com', 'rebekahs-2026', true, 'variable', 'stock' ),
);
foreach ( $cases as $index => $case ) {
	list( $_SERVER['HTTP_HOST'], $GLOBALS['test_theme'], $GLOBALS['test_action'], $product->type, $expected ) = $case;
	if ( rhn_staging_single_stock_label( 'stock', $product ) !== $expected ) {
		throw new Exception( 'Failed case ' . $index );
	}
}
echo "PASS: 5 stock-display scope cases\n";
