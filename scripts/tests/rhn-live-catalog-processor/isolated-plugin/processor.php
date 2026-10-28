<?php
/**
 * Plugin Name: RHN Live Catalog Sheet Processor
 * Description: Approval-gated production synchronization of website-owned WooCommerce catalog fields from Rebekah's canonical Inventory Sheet.
 * Version: 2.1.1
 */
defined( 'ABSPATH' ) || exit;

function rhn_catalog_presentation_enabled() {
	return 'rebekahspureliving.com' === strtolower( (string) wp_parse_url( get_option( 'home' ), PHP_URL_HOST ) );
}

function rhn_catalog_batch_guarded() {
	return rhn_catalog_presentation_enabled()
		&& 'yes' === (string) get_option( 'rhn_live_catalog_processor_enabled', 'no' );
}

require_once __DIR__ . '/core.php';
require_once __DIR__ . '/registry-import.php';
require_once __DIR__ . '/full-range-catalog.php';
require_once __DIR__ . '/full-range-catalog-runtime.php';
require_once __DIR__ . '/full-range-catalog-adapters.php';
require_once __DIR__ . '/catalog-fields.php';
require_once __DIR__ . '/google-sheet-sync.php';
require_once __DIR__ . '/photo-intake.php';
require_once __DIR__ . '/google-sheet-discovery.php';
require_once __DIR__ . '/production-scheduler.php';
require_once __DIR__ . '/full-range-catalog-integration.php';

/**
 * Keep API-created products quarantined until the Sheet scheduler proves the
 * content, POS price and safe inventory state. Positive stock on a new
 * quarantine also requires a verified post-quarantine inventory REST update.
 * Existing products keep their POS-owned fields while approved website-owned
 * fields are reasserted.
 */
function rhn_live_catalog_before_rest_save( $product, $request, $creating = false ) {
	if ( ! rhn_catalog_batch_guarded() || ! ( $product instanceof WC_Product ) || ! ( $request instanceof WP_REST_Request ) ) {
		return $product;
	}
	if ( ! preg_match( '#^/wc/v[123]/products(?:/\d+)?$#', $request->get_route() ) ) {
		return $product;
	}
	$is_new = (bool) $creating || ! $product->get_id();
	if ( $request->has_param( 'name' ) && is_string( $request->get_param( 'name' ) ) && '' !== trim( $request->get_param( 'name' ) ) ) {
		$product->update_meta_data( '_rhn_revel_product_name', sanitize_text_field( $request->get_param( 'name' ) ) );
	}
	if ( $is_new ) {
		$product->set_status( 'draft' );
		$product->set_catalog_visibility( 'hidden' );
		$product->set_manage_stock( true );
		$product->set_stock_quantity( 0 );
		$product->set_stock_status( 'outofstock' );
		$product->set_backorders( 'no' );
		$product->update_meta_data( '_rhn_catalog_pending_sheet_review', 'yes' );
		$product->update_meta_data( '_rhn_live_catalog_managed', 'yes' );
		$product->update_meta_data( '_rhn_catalog_revel_mirror', 'yes' );
		$product->update_meta_data( '_rhn_catalog_quarantine_created_gmt', gmdate( 'c' ) );
	} elseif ( 'yes' === (string) $product->get_meta( '_rhn_catalog_pending_sheet_review', true, 'edit' ) ) {
		$product->set_status( 'draft' );
		$product->set_catalog_visibility( 'hidden' );
	} elseif ( '' !== (string) $product->get_meta( '_rhn_catalog_withdrawn_by_sheet', true, 'edit' ) ) {
		$product->set_status( 'draft' );
		$product->set_catalog_visibility( 'hidden' );
	} elseif ( rhn_live_catalog_product_is_managed( $product ) && rhn_catalog_entry_for_product( $product ) ) {
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
	}
	return rhn_catalog_apply_entry_to_product( $product, $request );
}

/**
 * Record successful production REST price/inventory observations without
 * changing any POS-owned field. A receipt is accepted only for an existing
 * exact-ID product update; creation POSTs never satisfy the inventory gate.
 */
function rhn_live_catalog_record_pos_observation( $product, $request, $creating = false ) {
	if ( ! rhn_catalog_batch_guarded() || ! ( $product instanceof WC_Product ) || ! ( $request instanceof WP_REST_Request ) ) {
		return;
	}
	$route = (string) $request->get_route();
	if ( ! preg_match( '#^/wc/v[123]/products/(\d+)$#', $route, $match ) || (int) $match[1] !== (int) $product->get_id() ) {
		return;
	}
	if ( ! in_array( strtoupper( (string) $request->get_method() ), array( 'PUT', 'PATCH' ), true ) || $creating ) {
		return;
	}
	if ( ! rhn_live_catalog_product_is_managed( $product ) ) {
		return;
	}
	$request_sku = $request->has_param( 'sku' ) ? trim( (string) $request->get_param( 'sku' ) ) : '';
	if ( '' !== $request_sku && $request_sku !== (string) $product->get_sku( 'edit' ) ) {
		return;
	}
	$observed = gmdate( 'c' );
	if ( $request->has_param( 'stock_quantity' ) && is_numeric( $request->get_param( 'stock_quantity' ) ) ) {
		update_post_meta( $product->get_id(), '_rhn_live_catalog_inventory_receipt', array(
			'observed_at_gmt' => $observed,
			'route' => $route,
			'method' => strtoupper( (string) $request->get_method() ),
			'sku' => (string) $product->get_sku( 'edit' ),
			'quantity' => $product->get_stock_quantity( 'edit' ),
			'stock_status' => (string) $product->get_stock_status( 'edit' ),
			'manage_stock' => (bool) $product->get_manage_stock( 'edit' ),
			'backorders' => (string) $product->get_backorders( 'edit' ),
		) );
	}
	if ( $request->has_param( 'regular_price' ) || $request->has_param( 'price' ) ) {
		update_post_meta( $product->get_id(), '_rhn_live_catalog_price_receipt', array(
			'observed_at_gmt' => $observed,
			'route' => $route,
			'method' => strtoupper( (string) $request->get_method() ),
			'sku' => (string) $product->get_sku( 'edit' ),
			'regular_price' => (string) $product->get_regular_price( 'edit' ),
		) );
	}
}

add_filter( 'woocommerce_rest_pre_insert_product', 'rhn_live_catalog_before_rest_save', 100, 2 );
add_filter( 'woocommerce_rest_pre_insert_product_object', 'rhn_live_catalog_before_rest_save', 100, 3 );
add_action( 'woocommerce_rest_insert_product', 'rhn_catalog_after_save', 100, 2 );
add_action( 'woocommerce_rest_insert_product_object', 'rhn_catalog_after_save', 100, 3 );
add_action( 'woocommerce_rest_insert_product', 'rhn_live_catalog_record_pos_observation', 110, 2 );
add_action( 'woocommerce_rest_insert_product_object', 'rhn_live_catalog_record_pos_observation', 110, 3 );
