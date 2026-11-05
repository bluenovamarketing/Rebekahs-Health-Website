<?php
/**
 * Plugin Name: RHN Approved Legacy Visibility Hotfix
 * Description: Lets an explicit client-Sheet approval override the staging legacy-quarantine display filter without weakening withdrawals.
 * Version: 1.0.0
 * Author: Blue Nova Marketing
 */

defined( 'ABSPATH' ) || exit;

function rhn_approved_legacy_visibility_hotfix_applies( $product_id ) {
	if ( ! function_exists( 'rhn_phase_two_is_staging_host' ) || ! rhn_phase_two_is_staging_host() ) {
		return false;
	}

	$product_id = (int) $product_id;
	return 'yes' === get_post_meta( $product_id, '_rhn_phase_two_legacy_quarantined', true )
		&& '' !== (string) get_post_meta( $product_id, '_rhn_catalog_published_by_sheet', true )
		&& '' === (string) get_post_meta( $product_id, '_rhn_catalog_withdrawn_by_sheet', true );
}

add_filter(
	'woocommerce_product_is_visible',
	function ( $visible, $product_id ) {
		return rhn_approved_legacy_visibility_hotfix_applies( $product_id ) ? true : $visible;
	},
	30,
	2
);

add_filter(
	'woocommerce_is_purchasable',
	function ( $purchasable, $product ) {
		return $product && rhn_approved_legacy_visibility_hotfix_applies( $product->get_id() ) ? true : $purchasable;
	},
	30,
	2
);
