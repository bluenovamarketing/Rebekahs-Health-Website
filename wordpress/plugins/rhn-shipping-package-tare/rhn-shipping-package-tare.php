<?php
/**
 * Plugin Name: RHN Shipping Package Tare
 * Description: Adds Rebekah's confirmed large-box tare weight exactly once to each WooCommerce shipping package.
 * Version: 1.0.0
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the prepared large-box allowance in ounces.
 *
 * The value includes the box, normal tape, and packing material confirmed by
 * Rebekah on October 2, 2026. It remains filterable for a future approved
 * packaging change without altering product records.
 *
 * @return float
 */
function rhn_shipping_package_tare_ounces() {
	return (float) apply_filters( 'rhn_shipping_package_tare_ounces', 12.0 );
}

/**
 * Add the prepared-box weight once to every physical shipping package.
 *
 * Octolize's free USPS method totals each product object's weight once per
 * quantity. Adding tare / quantity to the first shippable line therefore adds
 * one box allowance to the package total without changing the real product,
 * its saved WooCommerce weight, or the client-owned inventory Sheet.
 *
 * @param array $packages WooCommerce shipping packages.
 * @return array
 */
function rhn_shipping_add_package_tare( $packages ) {
	if ( ! is_array( $packages ) ) {
		return $packages;
	}

	$tare_ounces = rhn_shipping_package_tare_ounces();
	if ( $tare_ounces <= 0 ) {
		return $packages;
	}

	$store_unit = (string) get_option( 'woocommerce_weight_unit', 'oz' );
	$tare_weight = function_exists( 'wc_get_weight' )
		? (float) wc_get_weight( $tare_ounces, $store_unit, 'oz' )
		: ( 'oz' === $store_unit ? $tare_ounces : 0.0 );

	if ( $tare_weight <= 0 ) {
		return $packages;
	}

	foreach ( $packages as $package_index => $package ) {
		if ( ! empty( $package['_rhn_shipping_tare_added'] ) || empty( $package['contents'] ) || ! is_array( $package['contents'] ) ) {
			continue;
		}

		foreach ( $package['contents'] as $item_key => $item ) {
			if ( empty( $item['data'] ) || ! is_object( $item['data'] ) || ! method_exists( $item['data'], 'get_weight' ) || ! method_exists( $item['data'], 'set_weight' ) ) {
				continue;
			}

			if ( method_exists( $item['data'], 'needs_shipping' ) && ! $item['data']->needs_shipping() ) {
				continue;
			}

			$quantity = max( 1, (int) ( $item['quantity'] ?? 1 ) );
			$product  = clone $item['data'];
			$weight   = (float) $product->get_weight( 'edit' );

			$product->set_weight( $weight + ( $tare_weight / $quantity ) );
			$packages[ $package_index ]['contents'][ $item_key ]['data'] = $product;
			$packages[ $package_index ]['_rhn_shipping_tare_added'] = true;
			break;
		}
	}

	return $packages;
}
add_filter( 'woocommerce_cart_shipping_packages', 'rhn_shipping_add_package_tare', 20 );

