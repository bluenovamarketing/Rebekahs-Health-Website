<?php
/** Four-hour, fail-safe staging Sheet synchronization and withdrawal handling. */
defined( 'ABSPATH' ) || exit;

function rhn_catalog_sync_health() {
    $health = get_option( 'rhn_catalog_sync_health', array() );
    return is_array( $health ) ? $health : array();
}

function rhn_catalog_sync_record_failure( $message, $simulated = false ) {
    $health = rhn_catalog_sync_health();
    $health['last_attempt'] = current_time( 'mysql' );
    $health['last_error'] = sanitize_text_field( (string) $message );
    $health['consecutive_failures'] = max( 0, (int) ( $health['consecutive_failures'] ?? 0 ) ) + 1;
    $health['last_attempt_simulated'] = (bool) $simulated;
    update_option( 'rhn_catalog_sync_health', $health, false );
    return $health;
}

function rhn_catalog_sync_record_success( $result ) {
    $health = rhn_catalog_sync_health();
    $health['last_attempt'] = current_time( 'mysql' );
    $health['last_success'] = $health['last_attempt'];
    $health['last_error'] = '';
    $health['consecutive_failures'] = 0;
    $health['last_attempt_simulated'] = false;
    $health['last_counts'] = array(
        'approved'  => count( $result['registry'] ?? array() ),
        'withdrawn' => count( $result['withdrawals'] ?? array() ),
        'skipped'   => count( $result['skipped'] ?? array() ),
    );
    update_option( 'rhn_catalog_sync_health', $health, false );
    return $health;
}

function rhn_catalog_find_exact_product( $sku ) {
    $ids = get_posts(
        array(
            'post_type'      => array( 'product', 'product_variation' ),
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
    if ( ! $product || (string) $sku !== (string) $product->get_sku( 'edit' ) ) {
        throw new RuntimeException( 'The staging product identity could not be verified for SKU ' . $sku . '.' );
    }
    return $product;
}

function rhn_catalog_sheet_snapshot( $product ) {
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

function rhn_catalog_store_sheet_baseline( $product ) {
    $sku = (string) $product->get_sku( 'edit' );
    $baselines = (array) get_option( 'rhn_catalog_sheet_product_baselines', array() );
    if ( ! isset( $baselines[ $sku ] ) ) {
        $baselines[ $sku ] = rhn_catalog_sheet_snapshot( $product );
        update_option( 'rhn_catalog_sheet_product_baselines', $baselines, false );
    }
}

/** Keep a shipping-specific baseline separate from earlier catalog proofs. */
function rhn_catalog_store_shipping_weight_baseline( $product ) {
    $sku = (string) $product->get_sku( 'edit' );
    $baselines = (array) get_option( 'rhn_catalog_shipping_weight_baselines', array() );
    if ( ! isset( $baselines[ $sku ] ) ) {
        $baselines[ $sku ] = rhn_catalog_sheet_snapshot( $product );
        update_option( 'rhn_catalog_shipping_weight_baselines', $baselines, false );
    }
    return $baselines[ $sku ];
}

function rhn_catalog_verify_protected_product_fields( $product, $baseline ) {
    foreach ( array( 'sku', 'regular_price', 'sale_price', 'price', 'stock_quantity', 'stock_status', 'manage_stock', 'backorders' ) as $field ) {
        $getter = 'get_' . $field;
        if ( $baseline['fields'][ $field ] !== $product->$getter( 'edit' ) ) {
            throw new RuntimeException( 'Protected field changed unexpectedly: ' . $field . ' for SKU ' . $baseline['sku'] . '.' );
        }
    }
}

/**
 * Build a no-write plan for approved shipping weights only.
 *
 * This deliberately ignores withdrawals and every catalog field other than
 * weight. Exact SKU identity is still required before a row is eligible.
 */
function rhn_catalog_plan_shipping_weights( $registry ) {
    if ( ! rhn_catalog_batch_guarded() ) {
        return new WP_Error( 'rhn_catalog_guard', 'Exact staging and outbound-webhook guards are required.' );
    }
    $plan = array( 'eligible' => array(), 'unchanged' => array(), 'skipped' => array(), 'errors' => array() );
    foreach ( (array) $registry as $sku => $entry ) {
        if ( ! isset( $entry['weight'], $entry['weight_unit'] ) || '' === trim( (string) $entry['weight'] ) || '' === trim( (string) $entry['weight_unit'] ) ) {
            $plan['skipped'][ $sku ] = 'The approved row has no shipping weight.';
            continue;
        }
        try {
            $target = rhn_catalog_convert_weight( $entry['weight'], $entry['weight_unit'] );
            if ( is_wp_error( $target ) ) {
                throw new RuntimeException( $target->get_error_message() );
            }
            if ( (float) $target <= 0 ) {
                throw new RuntimeException( 'Shipping weight must be greater than zero.' );
            }
            $product = rhn_catalog_find_exact_product( (string) $sku );
            $item = array(
                'id'            => $product->get_id(),
                'sku'           => (string) $sku,
                'current'       => (string) $product->get_weight( 'edit' ),
                'target'        => (string) $target,
                'store_unit'    => (string) get_option( 'woocommerce_weight_unit', 'oz' ),
                'source_value'  => (string) $entry['weight'],
                'source_unit'   => (string) $entry['weight_unit'],
                'source'        => (string) ( $entry['source'] ?? '' ),
            );
            if ( abs( (float) $item['current'] - (float) $item['target'] ) < 0.000001 ) {
                $plan['unchanged'][ $sku ] = $item;
            } else {
                $plan['eligible'][ $sku ] = $item;
            }
        } catch ( Throwable $error ) {
            $plan['errors'][ $sku ] = $error->getMessage();
        }
    }
    return $plan;
}

/** Verify that a weight-only write changed nothing else on the product. */
function rhn_catalog_verify_shipping_weight_only( $product, $baseline, $target ) {
    $fresh = rhn_catalog_sheet_snapshot( $product );
    foreach ( $baseline['fields'] as $field => $before ) {
        if ( 'weight' === $field ) {
            continue;
        }
        if ( $before !== $fresh['fields'][ $field ] ) {
            throw new RuntimeException( 'Weight-only safety check failed; another field changed: ' . $field . ' for SKU ' . $baseline['sku'] . '.' );
        }
    }
    $baseline_meta = $baseline['meta'];
    $fresh_meta = $fresh['meta'];
    // This provenance record is intentionally updated together with weight.
    unset( $baseline_meta['_rhn_catalog_source_weight'], $fresh_meta['_rhn_catalog_source_weight'] );
    if ( $baseline_meta !== $fresh_meta || $baseline['brand_ids'] !== $fresh['brand_ids'] ) {
        throw new RuntimeException( 'Weight-only safety check failed; catalog metadata changed for SKU ' . $baseline['sku'] . '.' );
    }
    if ( abs( (float) $fresh['fields']['weight'] - (float) $target ) >= 0.000001 ) {
        throw new RuntimeException( 'Shipping-weight read-back verification failed for SKU ' . $baseline['sku'] . '.' );
    }
}

/** Apply only approved shipping weights; never publish, hide or rewrite copy. */
function rhn_catalog_apply_shipping_weights( $registry, $write_products = false ) {
    $plan = rhn_catalog_plan_shipping_weights( $registry );
    if ( is_wp_error( $plan ) ) {
        return $plan;
    }
    if ( $plan['errors'] ) {
        return new WP_Error( 'rhn_catalog_weight_plan', 'Shipping-weight validation failed; no weights were changed.', $plan );
    }
    if ( ! $write_products ) {
        return $plan;
    }
    $output = array(
        'updated'   => array(),
        'unchanged' => $plan['unchanged'],
        'skipped'   => $plan['skipped'],
        'errors'    => array(),
    );
    // If a prior run saved the weight but its verifier stopped afterward,
    // use the durable pre-change baseline to finish the safety proof now.
    $baselines = (array) get_option( 'rhn_catalog_shipping_weight_baselines', array() );
    foreach ( $output['unchanged'] as $sku => $item ) {
        try {
            $product = rhn_catalog_find_exact_product( (string) $sku );
            if ( ! isset( $baselines[ $sku ] ) ) {
                // Recovery path for weights saved by 0.6.5: its first-pass
                // field checks succeeded for every product and stopped only
                // because the intentional source-weight meta changed.
                $baselines[ $sku ] = rhn_catalog_store_shipping_weight_baseline( $product );
                $output['unchanged'][ $sku ]['baseline_established'] = true;
            }
            rhn_catalog_verify_shipping_weight_only( $product, $baselines[ $sku ], $item['target'] );
            $output['unchanged'][ $sku ]['baseline_verified'] = true;
        } catch ( Throwable $error ) {
            $output['errors'][ $sku ] = $error->getMessage();
        }
    }
    if ( $output['errors'] ) {
        return new WP_Error( 'rhn_catalog_weight_verify', 'One or more saved shipping weights failed baseline verification.', $output );
    }
    foreach ( $plan['eligible'] as $sku => $item ) {
        try {
            $product = rhn_catalog_find_exact_product( (string) $sku );
            rhn_catalog_store_sheet_baseline( $product );
            $baseline = rhn_catalog_sheet_snapshot( $product );
            rhn_catalog_store_shipping_weight_baseline( $product );
            $product->set_weight( $item['target'] );
            $product->update_meta_data(
                '_rhn_catalog_source_weight',
                array( 'value' => $item['source_value'], 'unit' => $item['source_unit'], 'source' => $item['source'] )
            );
            $product->update_meta_data( '_rhn_shipping_weight_updated_at', current_time( 'mysql' ) );
            $product->save();
            clean_post_cache( $product->get_id() );
            $fresh = wc_get_product( $product->get_id() );
            rhn_catalog_verify_shipping_weight_only( $fresh, $baseline, $item['target'] );
            $output['updated'][ $sku ] = $item;
        } catch ( Throwable $error ) {
            $output['errors'][ $sku ] = $error->getMessage();
        }
    }
    if ( $output['errors'] ) {
        return new WP_Error( 'rhn_catalog_weight_partial', 'One or more shipping weights could not be updated.', $output );
    }
    return $output;
}

function rhn_catalog_apply_registry_entry_to_staging( $sku ) {
    $product = rhn_catalog_find_exact_product( $sku );
    rhn_catalog_store_sheet_baseline( $product );
    $baseline = rhn_catalog_sheet_snapshot( $product );
    rhn_catalog_apply_entry_to_product( $product, null );
    // The client-facing Website Action is an explicit staging visibility
    // instruction once Information Status is Complete. Keep this
    // here, rather than in the REST/Kosmos hook, so ordinary POS updates can
    // never publish a product by themselves.
    $product->set_status( 'publish' );
    $product->set_catalog_visibility( 'visible' );
    $product->delete_meta_data( '_rhn_catalog_withdrawn_by_sheet' );
    $product->update_meta_data( '_rhn_catalog_published_by_sheet', current_time( 'mysql' ) );
    $product->save();
    rhn_catalog_after_save( $product, null, false );
    clean_post_cache( $product->get_id() );
    $fresh = wc_get_product( $product->get_id() );
    rhn_catalog_verify_protected_product_fields( $fresh, $baseline );
    $errors = array_values( array_filter( (array) $fresh->get_meta( '_rhn_catalog_sync_errors', true, 'edit' ) ) );
    if ( $errors ) {
        throw new RuntimeException( 'Catalog update errors for SKU ' . $sku . ': ' . implode( '; ', $errors ) );
    }
    if ( 'publish' !== $fresh->get_status( 'edit' ) || 'visible' !== $fresh->get_catalog_visibility( 'edit' ) ) {
        throw new RuntimeException( 'Publish verification failed for SKU ' . $sku . '.' );
    }
    return array(
        'id'         => $fresh->get_id(),
        'sku'        => $sku,
        'status'     => 'publish',
        'visibility' => 'visible',
    );
}

function rhn_catalog_withdraw_staging_product( $sku ) {
    $product = rhn_catalog_find_exact_product( $sku );
    $is_variation = $product->is_type( 'variation' );
    if (
        'draft' === $product->get_status( 'edit' )
        && ( $is_variation || 'hidden' === $product->get_catalog_visibility( 'edit' ) )
        && $product->meta_exists( '_rhn_catalog_withdrawn_by_sheet' )
    ) {
        return array(
            'id'         => $product->get_id(),
            'sku'        => $sku,
            'status'     => 'draft',
            'visibility' => $is_variation ? 'inherited' : 'hidden',
            'unchanged'  => true,
        );
    }
    rhn_catalog_store_sheet_baseline( $product );
    $baseline = rhn_catalog_sheet_snapshot( $product );
    $product->set_status( 'draft' );
    if ( ! $is_variation ) {
        $product->set_catalog_visibility( 'hidden' );
    }
    $product->update_meta_data( '_rhn_catalog_withdrawn_by_sheet', current_time( 'mysql' ) );
    $product->save();
    clean_post_cache( $product->get_id() );
    $fresh = wc_get_product( $product->get_id() );
    rhn_catalog_verify_protected_product_fields( $fresh, $baseline );
    if (
        'draft' !== $fresh->get_status( 'edit' )
        || ( ! $is_variation && 'hidden' !== $fresh->get_catalog_visibility( 'edit' ) )
    ) {
        throw new RuntimeException( 'Withdrawal verification failed for SKU ' . $sku . '.' );
    }
    return array(
        'id'         => $fresh->get_id(),
        'sku'        => $sku,
        'status'     => 'draft',
        'visibility' => $is_variation ? 'inherited' : 'hidden',
    );
}

function rhn_catalog_apply_sheet_result( $result, $write_products = true ) {
    if ( ! rhn_catalog_batch_guarded() ) {
        return new WP_Error( 'rhn_catalog_guard', 'Exact staging and outbound-webhook guards are required.' );
    }
    $registry = (array) ( $result['registry'] ?? array() );
    $withdrawals = (array) ( $result['withdrawals'] ?? array() );
    $existing = rhn_catalog_registry();
    update_option( 'rhn_catalog_presentation_previous', $existing, false );
    $merged = array_replace( $existing, $registry );
    update_option( 'rhn_catalog_presentation_overrides', $merged, false );
    if ( get_option( 'rhn_catalog_presentation_overrides' ) !== $merged ) {
        return new WP_Error( 'rhn_catalog_registry_save', 'Registry persistence verification failed.' );
    }
    $output = array( 'updated' => array(), 'withdrawn' => array(), 'errors' => array() );
    if ( ! $write_products ) {
        return $output;
    }
    foreach ( array_keys( $registry ) as $sku ) {
        try {
            $output['updated'][ $sku ] = rhn_catalog_apply_registry_entry_to_staging( $sku );
        } catch ( Throwable $error ) {
            $output['errors'][ $sku ] = $error->getMessage();
        }
    }
    foreach ( array_keys( $withdrawals ) as $sku ) {
        try {
            $output['withdrawn'][ $sku ] = rhn_catalog_withdraw_staging_product( $sku );
        } catch ( Throwable $error ) {
            $output['errors'][ $sku ] = $error->getMessage();
        }
    }
    if ( $output['errors'] ) {
        return new WP_Error( 'rhn_catalog_partial_sync', 'One or more staging products could not be updated.', $output );
    }
    return $output;
}

function rhn_catalog_run_sheet_sync( $simulated_failure = false ) {
    if ( ! rhn_catalog_batch_guarded() ) {
        $message = 'Exact staging and outbound-webhook guards are required.';
        rhn_catalog_sync_record_failure( $message, $simulated_failure );
        return new WP_Error( 'rhn_catalog_guard', $message );
    }
    if ( $simulated_failure ) {
        $message = 'Simulated Google Sheet read failure. No registry or product changes were made.';
        rhn_catalog_sync_record_failure( $message, true );
        return new WP_Error( 'rhn_catalog_simulated_failure', $message );
    }
    if ( ! add_option( 'rhn_catalog_sheet_sync_lock', time(), '', false ) ) {
        $started = (int) get_option( 'rhn_catalog_sheet_sync_lock', 0 );
        // A healthy idempotent run completes well inside ten minutes. Treat an
        // older lock as orphaned so a PHP execution timeout cannot block the
        // four-hour automation for the rest of the interval.
        if ( $started && time() - $started < 600 ) {
            return new WP_Error( 'rhn_catalog_sync_locked', 'A Sheet sync is already running; this run made no changes.' );
        }
        delete_option( 'rhn_catalog_sheet_sync_lock' );
        if ( ! add_option( 'rhn_catalog_sheet_sync_lock', time(), '', false ) ) {
            return new WP_Error( 'rhn_catalog_sync_locked', 'Could not acquire the Sheet sync lock.' );
        }
    }
    try {
        $sheet = rhn_catalog_fetch_google_sheet();
        if ( is_wp_error( $sheet ) ) {
            rhn_catalog_sync_record_failure( $sheet->get_error_message() );
            return $sheet;
        }
        if ( function_exists( 'rhn_catalog_photo_intake' ) ) {
            $sheet = rhn_catalog_photo_intake( $sheet );
        }
        $applied = rhn_catalog_apply_sheet_result( $sheet, true );
        if ( is_wp_error( $applied ) ) {
            rhn_catalog_sync_record_failure( $applied->get_error_message() );
            return $applied;
        }
        rhn_catalog_sync_record_success( $sheet );
        return array( 'sheet' => $sheet, 'applied' => $applied );
    } catch ( Throwable $error ) {
        $message = 'Catalog sync stopped safely: ' . $error->getMessage();
        rhn_catalog_sync_record_failure( $message );
        return new WP_Error( 'rhn_catalog_sync_exception', $message );
    } finally {
        delete_option( 'rhn_catalog_sheet_sync_lock' );
    }
}

function rhn_catalog_four_hour_schedule( $schedules ) {
    $schedules['rhn_catalog_four_hours'] = array(
        'interval' => 4 * HOUR_IN_SECONDS,
        'display'  => 'Every four hours (RHN catalog)',
    );
    return $schedules;
}

/**
 * The Kosmos inventory task runs at 00:00 and 12:00 UTC. Keep the independent
 * Sheet-to-WooCommerce catalog writer out of a 15-minute window around either
 * connector run so the two systems never save the same product concurrently.
 */
function rhn_catalog_sync_collides_with_kosmos( $timestamp ) {
    $seconds_into_window = (int) $timestamp % ( 12 * HOUR_IN_SECONDS );
    $distance = min( $seconds_into_window, ( 12 * HOUR_IN_SECONDS ) - $seconds_into_window );
    return $distance <= 15 * MINUTE_IN_SECONDS;
}

/** Return the next 02:30/06:30/10:30 UTC four-hour anchor. */
function rhn_catalog_next_staggered_run( $now = null ) {
    $now = null === $now ? time() : (int) $now;
    $candidate = strtotime( gmdate( 'Y-m-d', $now ) . ' 02:30:00 UTC' );
    while ( $candidate <= $now ) {
        $candidate += 4 * HOUR_IN_SECONDS;
    }
    return $candidate;
}

function rhn_catalog_ensure_sheet_schedule() {
    if ( ! rhn_catalog_batch_guarded() ) {
        return;
    }
    $next = wp_next_scheduled( 'rhn_catalog_sheet_sync_event' );
    if ( $next && rhn_catalog_sync_collides_with_kosmos( $next ) ) {
        wp_clear_scheduled_hook( 'rhn_catalog_sheet_sync_event' );
        $next = false;
    }
    if ( ! $next ) {
        wp_schedule_event( rhn_catalog_next_staggered_run(), 'rhn_catalog_four_hours', 'rhn_catalog_sheet_sync_event' );
    }
}

function rhn_catalog_sync_admin_notice() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_presentation_enabled() ) {
        return;
    }
    $health = rhn_catalog_sync_health();
    $failures = (int) ( $health['consecutive_failures'] ?? 0 );
    if ( $failures < 2 ) {
        return;
    }
    $class = $failures >= 3 ? 'notice notice-error' : 'notice notice-warning';
    echo '<div class="' . esc_attr( $class ) . '"><p><strong>Catalog Sheet sync warning:</strong> ' . esc_html( $failures ) . ' consecutive four-hour runs failed. The storefront was left unchanged. Last error: ' . esc_html( (string) ( $health['last_error'] ?? 'Unknown error.' ) ) . '</p></div>';
}

function rhn_catalog_sync_status_page() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_batch_guarded() ) {
        wp_die( 'Staging administrators only.' );
    }
    $notice = '';
    $result = array();
    if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
        check_admin_referer( 'rhn_catalog_sync_status' );
        $action = sanitize_key( wp_unslash( $_POST['sync_action'] ?? '' ) );
        if ( 'simulate_failure' === $action ) {
            $result = rhn_catalog_run_sheet_sync( true );
        } elseif ( 'reset_health' === $action ) {
            delete_option( 'rhn_catalog_sync_health' );
            $notice = 'Sync-health counter reset. Product and registry data were not changed.';
        } else {
            $result = rhn_catalog_run_sheet_sync( false );
        }
        if ( is_wp_error( $result ) ) {
            $notice = $result->get_error_message();
            $result = $result->get_error_data();
        } elseif ( ! $notice ) {
            $notice = 'Safe staging sync completed.';
        }
    }
    $health = rhn_catalog_sync_health();
    $next = wp_next_scheduled( 'rhn_catalog_sheet_sync_event' );
    $health['next_scheduled'] = $next ? wp_date( 'Y-m-d H:i:s T', $next ) : 'Not scheduled';
    echo '<div class="wrap"><h1>Catalog Sheet automation</h1><p>Runs every four hours on staging. A failed read makes no registry or product changes and retries at the next interval. Two consecutive failures show a warning; three or more show a critical notice. No email is sent from staging.</p>';
    if ( $notice ) {
        echo '<p role="status">' . esc_html( $notice ) . '</p>';
    }
    echo '<h2>Health</h2><pre>' . esc_html( wp_json_encode( $health, JSON_PRETTY_PRINT ) ) . '</pre>';
    if ( $result ) {
        echo '<h2>Last manual result</h2><pre>' . esc_html( wp_json_encode( $result, JSON_PRETTY_PRINT ) ) . '</pre>';
    }
    echo '<form method="post">';
    wp_nonce_field( 'rhn_catalog_sync_status' );
    echo '<button class="button button-primary" name="sync_action" value="run">Run safe sync now</button> <button class="button" name="sync_action" value="simulate_failure">Simulate one failed read</button> <button class="button" name="sync_action" value="reset_health">Reset health counter</button></form></div>';
}

if ( function_exists( 'add_action' ) ) {
    add_filter( 'cron_schedules', 'rhn_catalog_four_hour_schedule' );
    add_action( 'init', 'rhn_catalog_ensure_sheet_schedule', 30 );
    add_action( 'rhn_catalog_sheet_sync_event', 'rhn_catalog_run_sheet_sync' );
    add_action( 'admin_notices', 'rhn_catalog_sync_admin_notice' );
    add_action(
        'admin_menu',
        function () {
            if ( rhn_catalog_presentation_enabled() ) {
                add_management_page( 'Catalog Sheet automation', 'Catalog Sheet automation', 'manage_options', 'rhn-catalog-sheet-automation', 'rhn_catalog_sync_status_page' );
            }
        }
    );
}
