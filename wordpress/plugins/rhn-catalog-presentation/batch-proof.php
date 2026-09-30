<?php
/** Reversible 25-product staging proof, processed one product per request. */
defined( 'ABSPATH' ) || exit;

function rhn_catalog_batch_skus() {
    return array(
        '733739433268', '733739401854', '733739430700', '733739423900', '788332231308',
        '788332174704', '788332198601', '788332226502', '788332128905', '788332129209',
        '788332083815', '788332083921', '788332037313', '788332230226', '788332167812',
        '788332166921', '788332227806', '788332217302', '788332155000', '788332227509',
        '788332155703', '788332051418', '788332230110', '788332048517', '788332054013',
    );
}

function rhn_catalog_batch_guarded() {
    return rhn_catalog_presentation_enabled()
        && function_exists( 'rhn_is_cloudways_staging' )
        && rhn_is_cloudways_staging()
        && false !== has_filter( 'woocommerce_webhook_should_deliver', '__return_false' );
}

function rhn_catalog_batch_find_product( $sku ) {
    $ids = get_posts(
        array(
            'post_type'      => 'product',
            'post_status'    => 'any',
            'fields'         => 'ids',
            'posts_per_page' => 3,
            'meta_key'       => '_sku',
            'meta_value'     => $sku,
            'orderby'        => 'ID',
            'order'          => 'ASC',
        )
    );
    if ( 1 !== count( $ids ) ) {
        throw new RuntimeException( 'Expected exactly one staging product for SKU ' . $sku . '; found ' . count( $ids ) . '.' );
    }
    $product = wc_get_product( (int) $ids[0] );
    if ( ! $product || ! $product->is_type( 'simple' ) || $sku !== $product->get_sku( 'edit' ) ) {
        throw new RuntimeException( 'The staging product for SKU ' . $sku . ' is not an exact simple-product match.' );
    }
    return $product;
}

function rhn_catalog_batch_snapshot( $product ) {
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

function rhn_catalog_batch_restore( $baseline ) {
    $allowed = rhn_catalog_batch_skus();
    $id = (int) ( $baseline['id'] ?? 0 );
    $sku = (string) ( $baseline['sku'] ?? '' );
    if ( 1 > $id || ! in_array( $sku, $allowed, true ) ) {
        throw new RuntimeException( 'Batch recovery identity is invalid.' );
    }
    $product = wc_get_product( $id );
    if ( ! $product || $sku !== $product->get_sku( 'edit' ) ) {
        throw new RuntimeException( 'Batch recovery identity changed for SKU ' . $sku . '; recovery stopped.' );
    }
    foreach ( (array) $baseline['fields'] as $key => $value ) {
        $setter = 'set_' . $key;
        $product->$setter( $value );
    }
    foreach ( (array) $baseline['meta'] as $key => $state ) {
        if ( ! empty( $state['exists'] ) ) {
            $product->update_meta_data( $key, $state['value'] );
        } else {
            $product->delete_meta_data( $key );
        }
    }
    $product->save();
    if ( taxonomy_exists( 'product_brand' ) ) {
        wp_set_object_terms( $id, array_map( 'intval', (array) $baseline['brand_ids'] ), 'product_brand', false );
    }
    clean_post_cache( $id );
    $fresh = wc_get_product( $id );
    if ( ! $fresh || $sku !== $fresh->get_sku( 'edit' ) ) {
        throw new RuntimeException( 'Batch recovery verification failed for SKU ' . $sku . '.' );
    }
    foreach ( (array) $baseline['fields'] as $key => $expected ) {
        $getter = 'get_' . $key;
        if ( $expected !== $fresh->$getter( 'edit' ) ) {
            throw new RuntimeException( 'Batch recovery did not restore ' . $key . ' for SKU ' . $sku . '.' );
        }
    }
    return true;
}

function rhn_catalog_batch_verify_entry( $actual, $entry, $baseline ) {
    $checks = array();
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
        $expected = rhn_catalog_resolve_term_ids( $entry['categories'], 'product_cat' );
        $checks['approved categories assigned'] = ! is_wp_error( $expected ) && $expected === array_map( 'intval', $actual->get_category_ids( 'edit' ) );
    }
    if ( isset( $entry['brand'] ) ) {
        $expected = rhn_catalog_resolve_term_ids( array( $entry['brand'] ), 'product_brand' );
        $actual_ids = wp_get_object_terms( $actual->get_id(), 'product_brand', array( 'fields' => 'ids' ) );
        $checks['approved brand assigned'] = ! is_wp_error( $expected ) && ! is_wp_error( $actual_ids ) && $expected === array_map( 'intval', $actual_ids );
    }
    if ( isset( $entry['featured_image_url'] ) ) {
        $checks['featured image imported'] = 0 < (int) $actual->get_image_id( 'edit' ) && $entry['featured_image_url'] === $actual->get_meta( '_rhn_featured_image_source_url', true, 'edit' );
    }
    if ( isset( $entry['gallery_image_urls'] ) ) {
        $checks['gallery images imported'] = count( $entry['gallery_image_urls'] ) === count( $actual->get_gallery_image_ids( 'edit' ) );
    }
    foreach ( array( 'package_size' => '_rhn_package_size', 'ingredients' => '_rhn_ingredients', 'allergens' => '_rhn_allergens', 'supplement_facts' => '_rhn_supplement_facts', 'directions' => '_rhn_directions', 'warnings' => '_rhn_warnings', 'seo_title' => '_seopress_titles_title', 'seo_description' => '_seopress_titles_desc' ) as $field => $meta_key ) {
        if ( isset( $entry[ $field ] ) ) {
            $checks[ $field . ' stored'] = '' !== (string) $actual->get_meta( $meta_key, true, 'edit' );
        }
    }
    foreach ( array( 'sku', 'regular_price', 'sale_price', 'price', 'stock_quantity', 'stock_status', 'manage_stock', 'backorders' ) as $protected ) {
        $getter = 'get_' . $protected;
        $checks[ $protected . ' unchanged' ] = $baseline['fields'][ $protected ] === $actual->$getter( 'edit' );
    }
    $checks['forced Draft during proof'] = 'draft' === $actual->get_status( 'edit' );
    $checks['forced hidden during proof'] = 'hidden' === $actual->get_catalog_visibility( 'edit' );
    $errors = (array) $actual->get_meta( '_rhn_catalog_sync_errors', true, 'edit' );
    $checks['no catalog sync errors'] = array() === array_values( array_filter( $errors ) );
    return $checks;
}

function rhn_catalog_run_batch_product( $sku ) {
    if ( ! rhn_catalog_batch_guarded() ) {
        throw new RuntimeException( 'Exact staging and webhook-block guards are required.' );
    }
    if ( ! in_array( $sku, rhn_catalog_batch_skus(), true ) ) {
        throw new RuntimeException( 'SKU is not in the fixed 25-product pilot.' );
    }
    if ( ! add_option( 'rhn_catalog_batch_proof_running', array( 'sku' => $sku, 'started' => time() ), '', false ) ) {
        throw new RuntimeException( 'A batch proof is already running or needs recovery.' );
    }
    $checks = array();
    $baseline = null;
    try {
        $product = rhn_catalog_batch_find_product( $sku );
        $entry = rhn_catalog_entry_for_product( $product );
        if ( ! $entry ) {
            throw new RuntimeException( 'Apply the approved 25-row Sheet registry first; no entry exists for SKU ' . $sku . '.' );
        }
        $baseline = rhn_catalog_batch_snapshot( $product );
        update_option( 'rhn_catalog_batch_proof_baseline', $baseline, false );
        $attachments_before = get_posts( array( 'post_type' => 'attachment', 'post_parent' => $product->get_id(), 'fields' => 'ids', 'posts_per_page' => -1 ) );

        $product->set_status( 'draft' );
        $product->set_catalog_visibility( 'hidden' );
        $product->save();
        $request = new WP_REST_Request( 'PUT', '/wc/v1/products/' . $product->get_id() );
        $request->set_body_params(
            array(
                'name'              => 'INCOMING 25-PRODUCT TEST TITLE',
                'description'       => 'INCOMING 25-PRODUCT TEST DESCRIPTION',
                'short_description' => 'INCOMING 25-PRODUCT TEST SHORT DESCRIPTION',
            )
        );
        $response = rest_do_request( $request );
        $checks['REST HTTP 200'] = 200 === $response->get_status();
        clean_post_cache( $product->get_id() );
        $actual = wc_get_product( $product->get_id() );
        $checks = array_merge( $checks, rhn_catalog_batch_verify_entry( $actual, $entry, $baseline ) );
        $attachments_after = get_posts( array( 'post_type' => 'attachment', 'post_parent' => $product->get_id(), 'fields' => 'ids', 'posts_per_page' => -1 ) );
        $checks['new attachment ids retained for review'] = array_values( array_diff( array_map( 'intval', $attachments_after ), array_map( 'intval', $attachments_before ) ) );
        foreach ( $checks as $label => $passed ) {
            if ( 'new attachment ids retained for review' !== $label && true !== $passed ) {
                throw new RuntimeException( 'Verification failed: ' . $label . '.' );
            }
        }
    } finally {
        if ( is_array( $baseline ) ) {
            $checks['baseline restored'] = rhn_catalog_batch_restore( $baseline );
        }
        $reports = (array) get_option( 'rhn_catalog_batch_proof_report', array() );
        $reports[ $sku ] = array( 'completed' => current_time( 'mysql' ), 'checks' => $checks );
        update_option( 'rhn_catalog_batch_proof_report', $reports, false );
        delete_option( 'rhn_catalog_batch_proof_running' );
    }
    return $checks;
}

function rhn_catalog_batch_ajax_run() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_batch_guarded() ) {
        wp_send_json_error( array( 'message' => 'Staging administrators only.' ), 403 );
    }
    check_ajax_referer( 'rhn_catalog_batch_proof', 'nonce' );
    $sku = sanitize_text_field( wp_unslash( $_POST['sku'] ?? '' ) );
    try {
        $checks = rhn_catalog_run_batch_product( $sku );
        wp_send_json_success( array( 'sku' => $sku, 'checks' => $checks ) );
    } catch ( Throwable $error ) {
        wp_send_json_error( array( 'sku' => $sku, 'message' => $error->getMessage() ), 500 );
    }
}

function rhn_catalog_batch_ajax_reset() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_batch_guarded() ) {
        wp_send_json_error( array( 'message' => 'Staging administrators only.' ), 403 );
    }
    check_ajax_referer( 'rhn_catalog_batch_proof', 'nonce' );
    delete_option( 'rhn_catalog_batch_proof_report' );
    wp_send_json_success( array( 'message' => 'Previous batch report cleared.' ) );
}

function rhn_catalog_batch_ajax_restore() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_batch_guarded() ) {
        wp_send_json_error( array( 'message' => 'Staging administrators only.' ), 403 );
    }
    check_ajax_referer( 'rhn_catalog_batch_proof', 'nonce' );
    try {
        rhn_catalog_batch_restore( get_option( 'rhn_catalog_batch_proof_baseline' ) );
        delete_option( 'rhn_catalog_batch_proof_running' );
        wp_send_json_success( array( 'message' => 'Latest saved product baseline restored.' ) );
    } catch ( Throwable $error ) {
        wp_send_json_error( array( 'message' => $error->getMessage() ), 500 );
    }
}

function rhn_catalog_batch_ajax_report() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_batch_guarded() ) {
        wp_send_json_error( array( 'message' => 'Staging administrators only.' ), 403 );
    }
    wp_send_json_success( array( 'report' => (array) get_option( 'rhn_catalog_batch_proof_report', array() ) ) );
}

function rhn_catalog_batch_proof_page() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_batch_guarded() ) {
        wp_die( 'Staging administrators only.' );
    }
    $skus = rhn_catalog_batch_skus();
    $report = (array) get_option( 'rhn_catalog_batch_proof_report', array() );
    $nonce = wp_create_nonce( 'rhn_catalog_batch_proof' );
    echo '<div class="wrap"><h1>25-product reversible staging proof</h1><p>Runs the fixed pilot one product at a time. Every product is forced to Draft/hidden, tested through WooCommerce wc/v1, verified, and restored before the next product starts. It does not call Revel or Kosmos. Imported test attachments are retained but detached after restoration.</p>';
    echo '<p><button type="button" class="button button-primary" id="rhn-batch-run">Run all 25 and restore each</button> <button type="button" class="button" id="rhn-batch-recover">Restore latest saved baseline</button></p><pre id="rhn-batch-log">' . esc_html( wp_json_encode( $report, JSON_PRETTY_PRINT ) ) . '</pre></div>';
    ?>
    <script>
    (() => {
        const skus = <?php echo wp_json_encode( $skus ); ?>;
        const nonce = <?php echo wp_json_encode( $nonce ); ?>;
        const log = document.getElementById('rhn-batch-log');
        const button = document.getElementById('rhn-batch-run');
        const post = async (action, data = {}) => {
            const body = new URLSearchParams({action, nonce, ...data});
            const response = await fetch(ajaxurl, {method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body});
            const json = await response.json();
            if (!response.ok || !json.success) throw new Error(json?.data?.message || `HTTP ${response.status}`);
            return json.data;
        };
        button.addEventListener('click', async () => {
            button.disabled = true;
            log.textContent = 'Clearing old report...';
            try {
                await post('rhn_catalog_batch_reset');
                const results = {};
                for (let i = 0; i < skus.length; i++) {
                    log.textContent = `Running ${i + 1}/25: ${skus[i]}\n\n` + JSON.stringify(results, null, 2);
                    results[skus[i]] = await post('rhn_catalog_batch_run', {sku: skus[i]});
                }
                log.textContent = 'COMPLETE: 25/25 products tested and restored.\n\n' + JSON.stringify(results, null, 2);
            } catch (error) {
                log.textContent = 'STOPPED FOR RECOVERY: ' + error.message + '\n\n' + log.textContent;
            } finally {
                button.disabled = false;
            }
        });
        document.getElementById('rhn-batch-recover').addEventListener('click', async () => {
            try { log.textContent = JSON.stringify(await post('rhn_catalog_batch_restore'), null, 2); }
            catch (error) { log.textContent = 'RECOVERY FAILED: ' + error.message; }
        });
    })();
    </script>
    <?php
}

if ( function_exists( 'add_action' ) ) {
    add_action( 'wp_ajax_rhn_catalog_batch_run', 'rhn_catalog_batch_ajax_run' );
    add_action( 'wp_ajax_rhn_catalog_batch_reset', 'rhn_catalog_batch_ajax_reset' );
    add_action( 'wp_ajax_rhn_catalog_batch_restore', 'rhn_catalog_batch_ajax_restore' );
    add_action( 'wp_ajax_rhn_catalog_batch_report', 'rhn_catalog_batch_ajax_report' );
    add_action(
        'admin_menu',
        function () {
            if ( rhn_catalog_presentation_enabled() ) {
                add_management_page( '25-product staging proof', '25-product staging proof', 'manage_options', 'rhn-catalog-batch-proof', 'rhn_catalog_batch_proof_page' );
            }
        }
    );
}
