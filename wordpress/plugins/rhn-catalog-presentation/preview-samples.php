<?php
/** Two persistent, reversible staging examples for visual client review. */
defined( 'ABSPATH' ) || exit;

function rhn_catalog_preview_skus() {
    return array( '733739401854', '733739430700' );
}

function rhn_catalog_apply_preview_sample( $sku ) {
    if ( ! rhn_catalog_batch_guarded() || ! in_array( $sku, rhn_catalog_preview_skus(), true ) ) {
        throw new RuntimeException( 'The requested preview is not an approved staging sample.' );
    }
    $product = rhn_catalog_batch_find_product( $sku );
    $entry = rhn_catalog_entry_for_product( $product );
    if ( ! $entry ) {
        throw new RuntimeException( 'Apply the two approved Sheet rows to the staging registry first.' );
    }
    $baselines = (array) get_option( 'rhn_catalog_preview_baselines', array() );
    if ( ! isset( $baselines[ $sku ] ) ) {
        $baselines[ $sku ] = rhn_catalog_batch_snapshot( $product );
        update_option( 'rhn_catalog_preview_baselines', $baselines, false );
    }
    $baseline = $baselines[ $sku ];
    $product->set_status( 'draft' );
    $product->set_catalog_visibility( 'hidden' );
    $product->save();
    $request = new WP_REST_Request( 'PUT', '/wc/v1/products/' . $product->get_id() );
    $request->set_body_params(
        array(
            'name'              => 'INCOMING VISUAL PREVIEW TITLE',
            'description'       => 'INCOMING VISUAL PREVIEW DESCRIPTION',
            'short_description' => 'INCOMING VISUAL PREVIEW SHORT DESCRIPTION',
        )
    );
    $response = rest_do_request( $request );
    if ( 200 !== $response->get_status() ) {
        throw new RuntimeException( 'WooCommerce preview update did not return HTTP 200.' );
    }
    clean_post_cache( $product->get_id() );
    $actual = wc_get_product( $product->get_id() );
    $checks = rhn_catalog_batch_verify_entry( $actual, $entry, $baseline );
    foreach ( $checks as $label => $passed ) {
        if ( true !== $passed ) {
            throw new RuntimeException( 'Preview verification failed: ' . $label . '.' );
        }
    }
    $applied = (array) get_option( 'rhn_catalog_preview_applied', array() );
    $applied[ $sku ] = array( 'id' => $actual->get_id(), 'applied' => current_time( 'mysql' ), 'checks' => $checks );
    update_option( 'rhn_catalog_preview_applied', $applied, false );
    return array(
        'id'          => $actual->get_id(),
        'sku'         => $sku,
        'preview_url' => get_preview_post_link( $actual->get_id() ),
        'checks'      => $checks,
    );
}

function rhn_catalog_restore_preview_samples() {
    $baselines = (array) get_option( 'rhn_catalog_preview_baselines', array() );
    $restored = array();
    foreach ( rhn_catalog_preview_skus() as $sku ) {
        if ( isset( $baselines[ $sku ] ) ) {
            $restored[ $sku ] = rhn_catalog_batch_restore( $baselines[ $sku ] );
        }
    }
    delete_option( 'rhn_catalog_preview_applied' );
    return $restored;
}

function rhn_catalog_preview_information_panel() {
    if ( ! rhn_catalog_presentation_enabled() || ! function_exists( 'is_product' ) || ! is_product() ) {
        return;
    }
    $product = wc_get_product( get_the_ID() );
    if ( ! $product || ! in_array( $product->get_sku( 'edit' ), rhn_catalog_preview_skus(), true ) ) {
        return;
    }
    $ingredients = (string) $product->get_meta( '_rhn_ingredients', true, 'edit' );
    $allergens = (string) $product->get_meta( '_rhn_allergens', true, 'edit' );
    $facts = (string) $product->get_meta( '_rhn_supplement_facts', true, 'edit' );
    $package_size = (string) $product->get_meta( '_rhn_package_size', true, 'edit' );
    if ( '' === trim( $ingredients . $allergens . $facts ) ) {
        return;
    }
    $brands = taxonomy_exists( 'product_brand' ) ? wp_get_object_terms( $product->get_id(), 'product_brand', array( 'fields' => 'names' ) ) : array();
    $categories = wp_get_object_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) );
    $categories = is_wp_error( $categories ) ? array() : $categories;
    $weight = trim( (string) $product->get_weight( 'edit' ) );
    $weight_label = '' === $weight ? 'Not supplied' : $weight . ' ' . get_option( 'woocommerce_weight_unit', 'oz' );
    ?>
    <section class="rhn-test-product-information" aria-labelledby="rhn-test-product-information-title">
        <div class="rhn-test-product-information__notice"><strong>Staging example — test information only.</strong> This content demonstrates the approved catalog workflow and is not real product guidance.</div>
        <h2 id="rhn-test-product-information-title">Product information</h2>
        <dl class="rhn-test-product-information__summary">
            <div><dt>Brand</dt><dd><?php echo esc_html( is_wp_error( $brands ) ? '' : implode( ', ', $brands ) ); ?></dd></div>
            <div><dt>Package size / net contents</dt><dd><?php echo esc_html( '' === trim( $package_size ) ? 'Not supplied' : $package_size ); ?></dd></div>
            <div><dt>Packaged shipping weight</dt><dd><?php echo esc_html( $weight_label ); ?></dd></div>
            <div class="rhn-test-product-information__wide"><dt>Website categories</dt><dd class="rhn-test-product-information__categories"><?php if ( $categories ) : foreach ( $categories as $category ) : ?><span><?php echo esc_html( $category ); ?></span><?php endforeach; else : ?>Not assigned<?php endif; ?></dd></div>
            <div class="rhn-test-product-information__wide"><dt>SKU / barcode</dt><dd class="rhn-test-product-information__identifier"><?php echo esc_html( $product->get_sku( 'edit' ) ); ?></dd></div>
        </dl>
        <div class="rhn-test-product-information__grid">
            <article><h3>Ingredients</h3><?php echo wp_kses_post( wpautop( $ingredients ) ); ?></article>
            <article><h3>Allergen information</h3><?php echo wp_kses_post( wpautop( $allergens ) ); ?></article>
            <article class="rhn-test-product-information__facts"><h3>Supplement Facts</h3><?php echo wp_kses_post( wpautop( $facts ) ); ?></article>
        </div>
    </section>
    <?php
}

/** The approved staging product template reads the description directly. */
function rhn_catalog_preview_append_information( $description, $product ) {
    if ( ! rhn_catalog_presentation_enabled() || ! function_exists( 'is_product' ) || ! is_product()
        || ! ( $product instanceof WC_Product ) || ! in_array( $product->get_sku( 'edit' ), rhn_catalog_preview_skus(), true ) ) {
        return $description;
    }
    $description = '<section class="rhn-test-product-description" aria-labelledby="rhn-test-product-description-title"><h3 id="rhn-test-product-description-title">Description</h3>' . $description . '</section>';
    ob_start();
    rhn_catalog_preview_information_panel();
    return $description . ob_get_clean();
}

function rhn_catalog_preview_styles() {
    if ( ! rhn_catalog_presentation_enabled() || ! function_exists( 'is_product' ) || ! is_product() ) {
        return;
    }
    ?>
    <style>
    .rhn-test-product-information{max-width:1180px;margin:2.25rem auto;padding:clamp(1rem,3vw,2rem);background:#f8f5ec;border:1px solid #ded4bd;border-radius:18px;color:#173f34}
    .rhn-test-product-information__notice{margin:-.25rem 0 1.5rem;padding:.8rem 1rem;background:#fff4cc;border-left:4px solid #c18c18;border-radius:6px;color:#5d4510}
    .rhn-test-product-description{margin-top:1.1rem}.rhn-test-product-description h3{margin:0 0 .7rem;color:#174c3c;font-size:1.15rem}.rhn-test-product-description>p:first-of-type{margin-top:0}
    .rhn-test-product-information h2{margin:0 0 1.25rem;font-size:clamp(1.7rem,3vw,2.35rem)}
    .rhn-test-product-information__summary{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:.75rem;margin:0 0 1.5rem}
    .rhn-test-product-information__summary div,.rhn-test-product-information__grid article{padding:1rem;background:#fff;border:1px solid #e5dfd1;border-radius:12px}
    .rhn-test-product-information__summary .rhn-test-product-information__wide{grid-column:1/-1}
    .rhn-test-product-information dt{font-size:.76rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#66786f}
    .rhn-test-product-information dd{margin:.35rem 0 0;font-weight:700}
    .rhn-test-product-information__categories{display:flex;flex-wrap:wrap;gap:.45rem}.rhn-test-product-information__categories span{display:inline-flex;padding:.35rem .62rem;border-radius:999px;background:#eef4ec;color:#174c3c;font-size:.9rem;line-height:1.25}
    .rhn-test-product-information__identifier{max-width:100%;overflow-wrap:anywhere;word-break:break-word;font-variant-numeric:tabular-nums;letter-spacing:.02em}
    .rhn-test-product-information__grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
    .rhn-test-product-information__grid h3{margin:0 0 .65rem;font-size:1.15rem}
    .rhn-test-product-information__facts{grid-column:1/-1}
    .rhn-product-facts{grid-template-columns:minmax(0,1fr);align-items:start}
    @media(max-width:760px){.rhn-test-product-information{margin:1.25rem .75rem}.rhn-test-product-information__summary,.rhn-test-product-information__grid{grid-template-columns:1fr 1fr}}
    @media(max-width:520px){.rhn-test-product-information__summary,.rhn-test-product-information__grid{grid-template-columns:1fr}.rhn-test-product-information__facts{grid-column:auto}}
    </style>
    <?php
}

function rhn_catalog_preview_page() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_batch_guarded() ) {
        wp_die( 'Staging administrators only.' );
    }
    $notice = '';
    $results = array();
    if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
        check_admin_referer( 'rhn_catalog_preview_samples' );
        try {
            if ( 'restore' === ( $_POST['preview_action'] ?? '' ) ) {
                $results = rhn_catalog_restore_preview_samples();
                $notice = 'Both staging examples restored.';
            } else {
                foreach ( rhn_catalog_preview_skus() as $sku ) {
                    $results[ $sku ] = rhn_catalog_apply_preview_sample( $sku );
                }
                $notice = 'Both Draft/hidden staging examples applied and verified.';
            }
        } catch ( Throwable $error ) {
            $notice = 'Preview action stopped: ' . $error->getMessage();
        }
    }
    echo '<div class="wrap"><h1>Two visual catalog examples</h1><p>Applies or restores NAC and Quercetin as persistent Draft/hidden staging examples. No Revel or Kosmos calls occur.</p>';
    if ( $notice ) {
        echo '<p role="status">' . esc_html( $notice ) . '</p>';
    }
    echo '<pre>' . esc_html( wp_json_encode( $results ? $results : get_option( 'rhn_catalog_preview_applied', array() ), JSON_PRETTY_PRINT ) ) . '</pre><form method="post">';
    wp_nonce_field( 'rhn_catalog_preview_samples' );
    echo '<button class="button button-primary" name="preview_action" value="apply">Apply both staging examples</button> <button class="button" name="preview_action" value="restore">Restore both baselines</button></form></div>';
}

if ( function_exists( 'add_action' ) ) {
    add_filter( 'woocommerce_product_get_description', 'rhn_catalog_preview_append_information', 100, 2 );
    add_action( 'wp_head', 'rhn_catalog_preview_styles', 20 );
    add_action(
        'admin_menu',
        function () {
            if ( rhn_catalog_presentation_enabled() ) {
                add_management_page( 'Two visual catalog examples', 'Two visual catalog examples', 'manage_options', 'rhn-catalog-preview-samples', 'rhn_catalog_preview_page' );
            }
        }
    );
}
