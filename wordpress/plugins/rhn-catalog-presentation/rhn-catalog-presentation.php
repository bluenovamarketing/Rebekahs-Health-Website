<?php
/**
 * Plugin Name: RHN Catalog Presentation (Staging)
 * Description: Applies approved barcode-keyed website copy without blocking retail price or stock updates.
 * Version: 0.8.4
 */
defined( 'ABSPATH' ) || exit;

/** Deliberately inert outside this exact staging installation. */
function rhn_catalog_presentation_enabled() {
    return 'wordpress-1651482-6655800.cloudwaysapps.com' === wp_parse_url( get_option( 'home' ), PHP_URL_HOST );
}

/** Unknown or unapproved products pass through without invented content. */
function rhn_catalog_presentation_before_save( $product, $request, $creating = false ) {
    if ( ! rhn_catalog_presentation_enabled() || ! ( $product instanceof WC_Product ) || ! ( $request instanceof WP_REST_Request ) ) {
        return $product;
    }
    if ( ! preg_match( '#^/wc/v[123]/products(?:/\d+)?$#', $request->get_route() ) ) {
        return $product;
    }

    // Preserve the exact Revel/Kosmos identity before approved website copy can
    // replace the storefront title. This never changes SKU, price or inventory.
    if ( $request->has_param( 'name' ) && is_string( $request->get_param( 'name' ) ) && '' !== trim( $request->get_param( 'name' ) ) ) {
        $product->update_meta_data( '_rhn_revel_product_name', sanitize_text_field( $request->get_param( 'name' ) ) );
        $product->update_meta_data( '_rhn_catalog_revel_mirror', 'yes' );
    }

    // A newly discovered product cannot become customer-facing until the Sheet
    // explicitly approves it. Updates to existing products retain their status.
    $is_new = (bool) $creating || ! $product->get_id();
    if ( $is_new && ! rhn_catalog_entry_for_product( $product ) ) {
        $product->set_status( 'draft' );
        $product->set_catalog_visibility( 'hidden' );
        $product->update_meta_data( '_rhn_catalog_pending_sheet_review', 'yes' );
    }
    return rhn_catalog_apply_entry_to_product( $product, $request );
}

require_once __DIR__ . '/registry-import.php';
require_once __DIR__ . '/catalog-fields.php';
require_once __DIR__ . '/google-sheet-sync.php';
require_once __DIR__ . '/google-sheet-discovery.php';
require_once __DIR__ . '/photo-intake.php';
require_once __DIR__ . '/nac-proof.php';
require_once __DIR__ . '/batch-proof.php';
require_once __DIR__ . '/catalog-scheduler.php';
require_once __DIR__ . '/preview-samples.php';

// Kosmos was observed using wc/v1, which has a different hook from v2/v3.
add_filter( 'woocommerce_rest_pre_insert_product', 'rhn_catalog_presentation_before_save', 100, 2 );
add_filter( 'woocommerce_rest_pre_insert_product_object', 'rhn_catalog_presentation_before_save', 100, 3 );
add_action( 'woocommerce_rest_insert_product', 'rhn_catalog_after_save', 100, 2 );
add_action( 'woocommerce_rest_insert_product_object', 'rhn_catalog_after_save', 100, 3 );
require_once __DIR__ . '/staging-test.php';
