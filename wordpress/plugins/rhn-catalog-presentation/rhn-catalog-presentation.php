<?php
/**
 * Plugin Name: RHN Catalog Presentation (Staging)
 * Description: Applies approved barcode-keyed website copy without blocking retail price or stock updates.
 * Version: 0.2.1
 */
defined( 'ABSPATH' ) || exit;

/** Deliberately inert outside this exact staging installation. */
function rhn_catalog_presentation_enabled() {
    return 'wordpress-1651482-6655800.cloudwaysapps.com' === wp_parse_url( get_option( 'home' ), PHP_URL_HOST );
}

/**
 * Registry is populated in bulk by a reviewed catalog importer, not from a REST
 * request. Unknown/unapproved products pass through without invented content.
 * Only these three presentation fields are owned by this module.
 */
function rhn_catalog_presentation_before_save( $product, $request, $creating = false ) {
    if ( ! rhn_catalog_presentation_enabled() || ! ( $product instanceof WC_Product ) || ! ( $request instanceof WP_REST_Request ) ) {
        return $product;
    }
    if ( ! preg_match( '#^/wc/v[123]/products(?:/\d+)?$#', $request->get_route() ) ) {
        return $product;
    }
    $sku = (string) $product->get_sku( 'edit' );
    $registry = get_option( 'rhn_catalog_presentation_overrides', array() );
    $entry = is_array( $registry ) && isset( $registry[ $sku ] ) ? $registry[ $sku ] : null;
    if ( ! is_array( $entry ) || true !== ( $entry['approved'] ?? false ) || ! is_string( $entry['source'] ?? null ) || '' === trim( $entry['source'] ) ) {
        return $product;
    }
    $fields = array( 'name', 'description', 'short_description' );
    $source = $product->get_meta( '_rhn_source_presentation', true, 'edit' );
    $source = is_array( $source ) ? $source : array();
    foreach ( $fields as $field ) {
        // Preserve the raw imported copy separately for comparison; no stock,
        // prices, secrets, purchasing data or request headers are recorded.
        if ( $request->has_param( $field ) && is_string( $request->get_param( $field ) ) ) {
            $source[ $field ] = 'name' === $field
                ? sanitize_text_field( $request->get_param( $field ) )
                : wp_kses_post( $request->get_param( $field ) );
        }
        if ( ! array_key_exists( $field, $entry ) || ! is_string( $entry[ $field ] ) ) {
            continue;
        }
        // Empty descriptions can intentionally suppress unwanted POS copy;
        // an empty approved title must never erase a valid incoming title.
        $value = 'name' === $field ? sanitize_text_field( $entry[ $field ] ) : wp_kses_post( $entry[ $field ] );
        if ( 'name' === $field && '' === trim( $value ) ) {
            continue;
        }
        $setter = 'set_' . $field;
        $product->$setter( $value );
    }
    $product->update_meta_data( '_rhn_source_presentation', $source );
    return $product;
}

// Kosmos was observed using wc/v1, which has a different hook from v2/v3.
add_filter( 'woocommerce_rest_pre_insert_product', 'rhn_catalog_presentation_before_save', 100, 2 );
add_filter( 'woocommerce_rest_pre_insert_product_object', 'rhn_catalog_presentation_before_save', 100, 3 );
require_once __DIR__ . '/registry-import.php';
require_once __DIR__ . '/staging-test.php';
