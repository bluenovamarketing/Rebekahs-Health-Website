<?php
/** Fixed, reversible staging proof for the approved NAC catalog row. */
defined( 'ABSPATH' ) || exit;

function rhn_catalog_nac_meta_keys() {
    return array(
        '_rhn_source_presentation', '_rhn_catalog_source_weight', '_rhn_package_size', '_rhn_ingredients',
        '_rhn_allergens', '_rhn_supplement_facts', '_rhn_directions', '_rhn_warnings',
        '_rhn_directions_warnings', '_rhn_seo_title', '_rhn_seo_description',
        '_seopress_titles_title', '_seopress_titles_desc', '_rhn_catalog_approved_source',
        '_rhn_catalog_sync_errors', '_rhn_featured_image_source_url',
        '_rhn_gallery_image_source_urls',
    );
}

function rhn_catalog_nac_snapshot( $product ) {
    $fields = array();
    foreach ( array( 'name', 'description', 'short_description', 'weight', 'category_ids', 'image_id', 'gallery_image_ids', 'regular_price', 'sale_price', 'price', 'stock_quantity', 'stock_status', 'manage_stock', 'backorders', 'sku', 'status', 'catalog_visibility' ) as $key ) {
        $getter = 'get_' . $key;
        $fields[ $key ] = $product->$getter( 'edit' );
    }
    $meta = array();
    foreach ( rhn_catalog_nac_meta_keys() as $key ) {
        $meta[ $key ] = array(
            'exists' => $product->meta_exists( $key ),
            'value'  => $product->get_meta( $key, true, 'edit' ),
        );
    }
    $brand_ids = taxonomy_exists( 'product_brand' ) ? wp_get_object_terms( $product->get_id(), 'product_brand', array( 'fields' => 'ids' ) ) : array();
    return array(
        'id'        => $product->get_id(),
        'sku'       => $product->get_sku( 'edit' ),
        'fields'    => $fields,
        'meta'      => $meta,
        'brand_ids' => is_wp_error( $brand_ids ) ? array() : array_map( 'intval', $brand_ids ),
    );
}

function rhn_catalog_nac_restore( $baseline ) {
    if ( ! is_array( $baseline ) || 1479647 !== ( $baseline['id'] ?? 0 ) || '733739401854' !== ( $baseline['sku'] ?? '' ) ) {
        throw new RuntimeException( 'NAC recovery identity is invalid.' );
    }
    $product = wc_get_product( 1479647 );
    if ( ! $product || '733739401854' !== $product->get_sku( 'edit' ) ) {
        throw new RuntimeException( 'NAC product identity changed; recovery stopped.' );
    }
    foreach ( $baseline['fields'] as $key => $value ) {
        $setter = 'set_' . $key;
        $product->$setter( $value );
    }
    foreach ( $baseline['meta'] as $key => $state ) {
        if ( ! empty( $state['exists'] ) ) {
            $product->update_meta_data( $key, $state['value'] );
        } else {
            $product->delete_meta_data( $key );
        }
    }
    $product->save();
    if ( taxonomy_exists( 'product_brand' ) ) {
        wp_set_object_terms( 1479647, array_map( 'intval', $baseline['brand_ids'] ), 'product_brand', false );
    }
    clean_post_cache( 1479647 );
    $fresh = wc_get_product( 1479647 );
    if ( ! $fresh || '733739401854' !== $fresh->get_sku( 'edit' ) ) {
        throw new RuntimeException( 'NAC recovery verification failed.' );
    }
    return true;
}

function rhn_catalog_run_nac_proof() {
    if ( ! rhn_catalog_presentation_enabled() || ! function_exists( 'rhn_is_cloudways_staging' ) || ! rhn_is_cloudways_staging() || false === has_filter( 'woocommerce_webhook_should_deliver', '__return_false' ) ) {
        throw new RuntimeException( 'Exact staging and webhook-block guards are required.' );
    }
    if ( ! add_option( 'rhn_catalog_nac_proof_running', time(), '', false ) ) {
        throw new RuntimeException( 'A NAC proof is already running or needs recovery.' );
    }
    $product = wc_get_product( 1479647 );
    if ( ! $product || '733739401854' !== $product->get_sku( 'edit' ) || ! $product->is_type( 'simple' ) ) {
        delete_option( 'rhn_catalog_nac_proof_running' );
        throw new RuntimeException( 'Fixed NAC product identity does not match.' );
    }
    $entry = rhn_catalog_entry_for_product( $product );
    if ( ! $entry ) {
        delete_option( 'rhn_catalog_nac_proof_running' );
        throw new RuntimeException( 'Apply the approved NAC Sheet row to the registry first.' );
    }
    $baseline = rhn_catalog_nac_snapshot( $product );
    update_option( 'rhn_catalog_nac_proof_baseline', $baseline, false );
    $checks = array();
    $attachments_before = get_posts( array( 'post_type' => 'attachment', 'post_parent' => 1479647, 'fields' => 'ids', 'posts_per_page' => -1 ) );
    try {
        $request = new WP_REST_Request( 'PUT', '/wc/v1/products/1479647' );
        $request->set_body_params(
            array(
                'name'              => 'INCOMING NAC TEST TITLE',
                'description'       => 'INCOMING NAC TEST DESCRIPTION',
                'short_description' => 'INCOMING NAC TEST SHORT DESCRIPTION',
            )
        );
        $response = rest_do_request( $request );
        $checks['REST HTTP 200'] = 200 === $response->get_status();
        clean_post_cache( 1479647 );
        $actual = wc_get_product( 1479647 );
        foreach ( array( 'name', 'description', 'short_description' ) as $field ) {
            if ( array_key_exists( $field, $entry ) ) {
                $getter = 'get_' . $field;
                $expected = 'name' === $field ? sanitize_text_field( $entry[ $field ] ) : wp_kses_post( $entry[ $field ] );
                $checks[ $field . ' from registry' ] = $expected === $actual->$getter( 'edit' );
            }
        }
        if ( isset( $entry['weight'], $entry['weight_unit'] ) ) {
            $checks['weight converted and stored'] = (string) rhn_catalog_convert_weight( $entry['weight'], $entry['weight_unit'] ) === (string) $actual->get_weight( 'edit' );
        }
        if ( isset( $entry['categories'] ) ) {
            $expected_categories = rhn_catalog_resolve_term_ids( $entry['categories'], 'product_cat' );
            $checks['approved categories assigned'] = ! is_wp_error( $expected_categories ) && $expected_categories === array_map( 'intval', $actual->get_category_ids( 'edit' ) );
        }
        if ( isset( $entry['brand'] ) ) {
            $expected_brand = rhn_catalog_resolve_term_ids( array( $entry['brand'] ), 'product_brand' );
            $actual_brand = wp_get_object_terms( 1479647, 'product_brand', array( 'fields' => 'ids' ) );
            $checks['approved brand assigned'] = ! is_wp_error( $expected_brand ) && ! is_wp_error( $actual_brand ) && $expected_brand === array_map( 'intval', $actual_brand );
        }
        if ( isset( $entry['featured_image_url'] ) ) {
            $checks['featured image imported'] = 0 < (int) $actual->get_image_id( 'edit' ) && $entry['featured_image_url'] === $actual->get_meta( '_rhn_featured_image_source_url', true, 'edit' );
        }
        foreach ( array( 'package_size' => '_rhn_package_size', 'ingredients' => '_rhn_ingredients', 'allergens' => '_rhn_allergens', 'supplement_facts' => '_rhn_supplement_facts', 'directions' => '_rhn_directions', 'warnings' => '_rhn_warnings', 'seo_title' => '_seopress_titles_title', 'seo_description' => '_seopress_titles_desc' ) as $field => $meta_key ) {
            if ( isset( $entry[ $field ] ) ) {
                $checks[ $field . ' stored' ] = '' !== (string) $actual->get_meta( $meta_key, true, 'edit' );
            }
        }
        foreach ( array( 'sku', 'status', 'catalog_visibility', 'regular_price', 'sale_price', 'price', 'stock_quantity', 'stock_status', 'manage_stock', 'backorders' ) as $protected ) {
            $getter = 'get_' . $protected;
            $checks[ $protected . ' unchanged' ] = $baseline['fields'][ $protected ] === $actual->$getter( 'edit' );
        }
        $errors = (array) $actual->get_meta( '_rhn_catalog_sync_errors', true, 'edit' );
        $checks['no catalog sync errors'] = array() === array_values( array_filter( $errors ) );
    } finally {
        $attachments_after = get_posts( array( 'post_type' => 'attachment', 'post_parent' => 1479647, 'fields' => 'ids', 'posts_per_page' => -1 ) );
        $checks['test attachment ids retained for review'] = array_values( array_diff( array_map( 'intval', $attachments_after ), array_map( 'intval', $attachments_before ) ) );
        $checks['baseline restored'] = rhn_catalog_nac_restore( $baseline );
        update_option( 'rhn_catalog_nac_proof_report', $checks, false );
        delete_option( 'rhn_catalog_nac_proof_running' );
    }
    return $checks;
}

function rhn_catalog_nac_proof_page() {
    if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'edit_post', 1479647 ) || ! rhn_catalog_presentation_enabled() ) {
        wp_die( 'Staging administrators only.' );
    }
    $notice = '';
    if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
        check_admin_referer( 'rhn_catalog_nac_proof' );
        try {
            if ( 'restore' === ( $_POST['proof_action'] ?? '' ) ) {
                $notice = rhn_catalog_nac_restore( get_option( 'rhn_catalog_nac_proof_baseline' ) ) ? 'NAC baseline restored and verified.' : 'NAC restoration failed.';
                delete_option( 'rhn_catalog_nac_proof_running' );
            } else {
                $notice = 'Proof completed: ' . wp_json_encode( rhn_catalog_run_nac_proof() );
            }
        } catch ( Throwable $error ) {
            $notice = 'Check/recovery required: ' . $error->getMessage();
        }
    }
    echo '<div class="wrap"><h1>NAC catalog workflow proof</h1><p>Fixed draft product 1479647 / SKU 733739401854. Applies the approved registry row through WooCommerce wc/v1, verifies website-owned fields, proves price/stock/status/SKU are unchanged, and restores the complete product baseline. It does not call Revel or Kosmos. Imported test attachments are retained but detached for review.</p>';
    if ( $notice ) {
        echo '<p role="status">' . esc_html( $notice ) . '</p>';
    }
    echo '<pre>' . esc_html( wp_json_encode( get_option( 'rhn_catalog_nac_proof_report', array() ), JSON_PRETTY_PRINT ) ) . '</pre><form method="post">';
    wp_nonce_field( 'rhn_catalog_nac_proof' );
    echo '<button class="button button-primary" name="proof_action" value="run">Run NAC proof and restore</button> <button class="button" name="proof_action" value="restore">Restore saved NAC baseline</button></form></div>';
}

if ( function_exists( 'add_action' ) ) {
    add_action(
        'admin_menu',
        function () {
            if ( rhn_catalog_presentation_enabled() ) {
                add_management_page( 'NAC catalog workflow proof', 'NAC catalog workflow proof', 'manage_options', 'rhn-catalog-nac-proof', 'rhn_catalog_nac_proof_page' );
            }
        }
    );
}
