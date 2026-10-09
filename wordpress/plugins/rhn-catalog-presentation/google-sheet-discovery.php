<?php
/** Append newly mirrored Clarkston Revel products to the existing client Sheet. */
defined( 'ABSPATH' ) || exit;

/** Automatic writes stay disabled until the service identity has Sheet edit access. */
function rhn_catalog_discovery_write_enabled() {
    if ( defined( 'RHN_CATALOG_DISCOVERY_WRITE_ENABLED' ) ) {
        return true === RHN_CATALOG_DISCOVERY_WRITE_ENABLED;
    }
    return 'yes' === (string) get_option( 'rhn_catalog_discovery_write_enabled', 'no' );
}

/** Validate current Sheet rows and build an idempotent no-write discovery plan. */
function rhn_catalog_discovery_plan( $products, $rows ) {
    $plan = array(
        'new'          => array(),
        'name_updates' => array(),
        'unchanged'    => array(),
        'errors'       => array(),
    );
    if ( ! is_array( $rows ) || count( $rows ) < 2 || ! is_array( $rows[0] ) ) {
        $plan['errors'][] = 'Product Review must contain its header and at least one existing product row.';
        return $plan;
    }

    $required = array(
        'Information Status *'              => 'status',
        'Website Action *'                  => 'action',
        'SKU / Barcode — READ ONLY'         => 'sku',
        'Revel Product Name — READ ONLY'    => 'name',
        'Photos Uploaded?'                  => 'photos_uploaded',
        'Photo Status'                      => 'photo_status',
    );
    $indexes = array();
    foreach ( $rows[0] as $index => $heading ) {
        $heading = trim( (string) $heading );
        if ( isset( $required[ $heading ] ) ) {
            $indexes[ $required[ $heading ] ] = (int) $index;
        }
    }
    foreach ( $required as $heading => $key ) {
        if ( ! isset( $indexes[ $key ] ) ) {
            $plan['errors'][] = 'Missing required Product Review heading: ' . $heading;
        }
    }
    if ( $plan['errors'] ) {
        return $plan;
    }

    $sheet_by_sku = array();
    foreach ( array_slice( $rows, 1, null, true ) as $index => $cells ) {
        $sku = trim( (string) ( $cells[ $indexes['sku'] ] ?? '' ) );
        if ( '' === $sku ) {
            continue;
        }
        $row_number = 5 + (int) $index;
        if ( isset( $sheet_by_sku[ $sku ] ) ) {
            $plan['errors'][] = 'Duplicate Sheet SKU ' . $sku . ' in rows ' . $sheet_by_sku[ $sku ]['row'] . ' and ' . $row_number . '.';
            continue;
        }
        $sheet_by_sku[ $sku ] = array(
            'row'  => $row_number,
            'name' => trim( (string) ( $cells[ $indexes['name'] ] ?? '' ) ),
        );
    }

    $source_by_sku = array();
    foreach ( (array) $products as $product ) {
        if ( ! is_array( $product ) ) {
            $plan['errors'][] = 'Invalid staging product record.';
            continue;
        }
        $sku = trim( (string) ( $product['sku'] ?? '' ) );
        $name = trim( (string) ( $product['name'] ?? '' ) );
        if ( '' === $sku || '' === $name ) {
            $plan['errors'][] = 'Every Revel mirror product requires an exact nonempty SKU and source name.';
            continue;
        }
        if ( strlen( $sku ) > 100 || strlen( $name ) > 500 ) {
            $plan['errors'][] = 'Revel mirror product identity exceeds the supported length.';
            continue;
        }
        if ( isset( $source_by_sku[ $sku ] ) ) {
            $plan['errors'][] = 'Duplicate Revel mirror SKU ' . $sku . ' on staging products ' . $source_by_sku[ $sku ]['id'] . ' and ' . (int) ( $product['id'] ?? 0 ) . '.';
            continue;
        }
        $source_by_sku[ $sku ] = array(
            'id'   => (int) ( $product['id'] ?? 0 ),
            'sku'  => $sku,
            'name' => $name,
        );
    }

    if ( $plan['errors'] ) {
        return $plan;
    }

    foreach ( $source_by_sku as $sku => $product ) {
        if ( ! isset( $sheet_by_sku[ $sku ] ) ) {
            $plan['new'][ $sku ] = $product;
        } elseif ( $sheet_by_sku[ $sku ]['name'] !== $product['name'] ) {
            $plan['name_updates'][ $sku ] = array(
                'id'       => $product['id'],
                'sku'      => $sku,
                'row'      => $sheet_by_sku[ $sku ]['row'],
                'previous' => $sheet_by_sku[ $sku ]['name'],
                'name'     => $product['name'],
            );
        } else {
            $plan['unchanged'][ $sku ] = $product;
        }
    }

    uasort(
        $plan['new'],
        static function ( $left, $right ) {
            $by_name = strcasecmp( $left['name'], $right['name'] );
            return 0 !== $by_name ? $by_name : strcmp( $left['sku'], $right['sku'] );
        }
    );
    ksort( $plan['name_updates'], SORT_STRING );
    ksort( $plan['unchanged'], SORT_STRING );
    return $plan;
}

/** Collect only products positively identified by a Revel/Kosmos product write. */
function rhn_catalog_collect_revel_products() {
    $ids = get_posts(
        array(
            'post_type'      => array( 'product', 'product_variation' ),
            'post_status'    => array( 'publish', 'private', 'pending', 'future', 'draft' ),
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'orderby'        => 'ID',
            'order'          => 'ASC',
            'meta_key'       => '_rhn_catalog_revel_mirror',
            'meta_value'     => 'yes',
        )
    );
    $products = array();
    foreach ( $ids as $id ) {
        $product = wc_get_product( (int) $id );
        if ( ! $product ) {
            continue;
        }
        $sku = trim( (string) $product->get_sku( 'edit' ) );
        $name = trim( (string) $product->get_meta( '_rhn_revel_product_name', true, 'edit' ) );
        if ( '' === $sku || '' === $name ) {
            continue;
        }
        $products[] = array( 'id' => (int) $id, 'sku' => $sku, 'name' => $name );
    }
    return $products;
}

/**
 * Load the one-time Clarkston legacy catalog reconciliation.
 *
 * Every entry was matched by exact barcode across the current staging
 * WooCommerce export and a direct Clarkston Revel export. The live product is
 * rechecked by ID and SKU before any Sheet request can be constructed.
 */
function rhn_catalog_collect_legacy_revel_products() {
    $path = __DIR__ . '/data/clarkston-revel-legacy-backfill-2026-10-05.json';
    if ( ! is_readable( $path ) ) {
        return new WP_Error( 'rhn_legacy_source', 'The verified Clarkston legacy source map is unavailable.' );
    }
    $records = json_decode( (string) file_get_contents( $path ), true );
    if ( ! is_array( $records ) || 343 !== count( $records ) ) {
        return new WP_Error( 'rhn_legacy_source', 'The Clarkston legacy source map does not contain the expected 343 exact identities.' );
    }
    $products = array();
    $seen = array();
    foreach ( $records as $record ) {
        $id = (int) ( $record['id'] ?? 0 );
        $sku = trim( (string) ( $record['sku'] ?? '' ) );
        $name = trim( (string) ( $record['name'] ?? '' ) );
        if ( $id < 1 || '' === $sku || '' === $name || isset( $seen[ $sku ] ) ) {
            return new WP_Error( 'rhn_legacy_source', 'The Clarkston legacy source map contains an invalid or duplicate identity.' );
        }
        $product = wc_get_product( $id );
        if ( ! $product || $sku !== trim( (string) $product->get_sku( 'edit' ) ) ) {
            return new WP_Error( 'rhn_legacy_source', 'A verified Clarkston legacy product no longer matches its staging WooCommerce ID and SKU: ' . $sku );
        }
        $seen[ $sku ] = true;
        $products[] = array( 'id' => $id, 'sku' => $sku, 'name' => $name );
    }
    return $products;
}

/** Read Sheet IDs and row capacities without changing the workbook. */
function rhn_catalog_discovery_sheet_metadata() {
    $token = rhn_catalog_google_access_token();
    if ( is_wp_error( $token ) ) {
        return $token;
    }
    $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode( rhn_catalog_google_sheet_id() ) . '?fields=sheets.properties(sheetId,title,gridProperties.rowCount)';
    $response = wp_safe_remote_get( $url, array( 'timeout' => 20, 'headers' => array( 'Authorization' => 'Bearer ' . $token ) ) );
    if ( is_wp_error( $response ) ) {
        return $response;
    }
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( 200 !== wp_remote_retrieve_response_code( $response ) || ! is_array( $body ) || empty( $body['sheets'] ) ) {
        return new WP_Error( 'rhn_discovery_metadata', 'Google Sheet metadata could not be read.' );
    }
    $metadata = array();
    foreach ( $body['sheets'] as $sheet ) {
        $properties = $sheet['properties'] ?? array();
        $title = (string) ( $properties['title'] ?? '' );
        if ( '' !== $title ) {
            $metadata[ $title ] = array(
                'sheet_id'  => (int) ( $properties['sheetId'] ?? 0 ),
                'row_count' => (int) ( $properties['gridProperties']['rowCount'] ?? 0 ),
            );
        }
    }
    foreach ( array( 'Product Review', 'Catalog' ) as $title ) {
        if ( ! isset( $metadata[ $title ] ) ) {
            return new WP_Error( 'rhn_discovery_metadata', 'Required Sheet tab is missing: ' . $title );
        }
    }
    return $metadata;
}

/** Convert a scalar to a Google Sheets ExtendedValue. */
function rhn_catalog_discovery_extended_value( $value ) {
    if ( is_bool( $value ) ) {
        return array( 'boolValue' => $value );
    }
    return array( 'stringValue' => (string) $value );
}

/** Build one atomic Google batchUpdate while preserving all existing client cells. */
function rhn_catalog_discovery_batch_requests( $plan, $product_rows, $catalog_rows, $metadata, $new_action = 'Keep Off Website' ) {
    if ( ! empty( $plan['errors'] ) ) {
        throw new InvalidArgumentException( 'A discovery plan with errors cannot be applied.' );
    }
    if ( ! is_array( $product_rows ) || count( $product_rows ) < 2 || ! is_array( $catalog_rows ) || count( $catalog_rows ) < 2 ) {
        throw new InvalidArgumentException( 'Both existing Sheet tabs require a header and template row.' );
    }
    if ( count( $product_rows ) !== count( $catalog_rows ) ) {
        throw new InvalidArgumentException( 'Product Review and Catalog row counts do not match; no rows were added.' );
    }
    foreach ( array( 'Product Review', 'Catalog' ) as $title ) {
        if ( ! isset( $metadata[ $title ]['sheet_id'], $metadata[ $title ]['row_count'] ) ) {
            throw new InvalidArgumentException( 'Missing metadata for ' . $title . '.' );
        }
    }

    $new = array_values( $plan['new'] ?? array() );
    $new_count = count( $new );
    $product_last_row = 4 + count( $product_rows );
    $product_start_row = $product_last_row + 1;
    $catalog_last_row = count( $catalog_rows );
    $catalog_start_row = $catalog_last_row + 1;
    $requests = array();

    if ( $new_count ) {
        $product_needed = $product_last_row + $new_count;
        $catalog_needed = $catalog_last_row + $new_count;
        if ( $product_needed > (int) $metadata['Product Review']['row_count'] ) {
            $requests[] = array( 'appendDimension' => array( 'sheetId' => (int) $metadata['Product Review']['sheet_id'], 'dimension' => 'ROWS', 'length' => $product_needed - (int) $metadata['Product Review']['row_count'] ) );
        }
        if ( $catalog_needed > (int) $metadata['Catalog']['row_count'] ) {
            $requests[] = array( 'appendDimension' => array( 'sheetId' => (int) $metadata['Catalog']['sheet_id'], 'dimension' => 'ROWS', 'length' => $catalog_needed - (int) $metadata['Catalog']['row_count'] ) );
        }

        $product_source = array( 'sheetId' => (int) $metadata['Product Review']['sheet_id'], 'startRowIndex' => $product_last_row - 1, 'endRowIndex' => $product_last_row, 'startColumnIndex' => 0, 'endColumnIndex' => 24 );
        $product_destination = array( 'sheetId' => (int) $metadata['Product Review']['sheet_id'], 'startRowIndex' => $product_start_row - 1, 'endRowIndex' => $product_start_row - 1 + $new_count, 'startColumnIndex' => 0, 'endColumnIndex' => 24 );
        foreach ( array( 'PASTE_FORMAT', 'PASTE_DATA_VALIDATION' ) as $paste_type ) {
            $requests[] = array( 'copyPaste' => array( 'source' => $product_source, 'destination' => $product_destination, 'pasteType' => $paste_type, 'pasteOrientation' => 'NORMAL' ) );
        }

        $row_data = array();
        foreach ( $new as $product ) {
            $values = array_fill( 0, 24, array( 'userEnteredValue' => array( 'stringValue' => '' ) ) );
            $values[0]['userEnteredValue'] = rhn_catalog_discovery_extended_value( 'Working' );
            $values[1]['userEnteredValue'] = rhn_catalog_discovery_extended_value( $new_action );
            $values[2]['userEnteredValue'] = rhn_catalog_discovery_extended_value( $product['sku'] );
            $values[3]['userEnteredValue'] = rhn_catalog_discovery_extended_value( $product['name'] );
            $values[13]['userEnteredValue'] = rhn_catalog_discovery_extended_value( false );
            $values[14]['userEnteredValue'] = rhn_catalog_discovery_extended_value( 'Not Reviewed' );
            $row_data[] = array( 'values' => $values );
        }
        $requests[] = array(
            'updateCells' => array(
                'range'  => $product_destination,
                'rows'   => $row_data,
                'fields' => 'userEnteredValue',
            ),
        );

        $catalog_source = array( 'sheetId' => (int) $metadata['Catalog']['sheet_id'], 'startRowIndex' => $catalog_last_row - 1, 'endRowIndex' => $catalog_last_row, 'startColumnIndex' => 0, 'endColumnIndex' => 23 );
        $catalog_destination = array( 'sheetId' => (int) $metadata['Catalog']['sheet_id'], 'startRowIndex' => $catalog_start_row - 1, 'endRowIndex' => $catalog_start_row - 1 + $new_count, 'startColumnIndex' => 0, 'endColumnIndex' => 23 );
        foreach ( array( 'PASTE_FORMAT', 'PASTE_DATA_VALIDATION', 'PASTE_FORMULA' ) as $paste_type ) {
            $requests[] = array( 'copyPaste' => array( 'source' => $catalog_source, 'destination' => $catalog_destination, 'pasteType' => $paste_type, 'pasteOrientation' => 'NORMAL' ) );
        }
    }

    foreach ( $plan['name_updates'] ?? array() as $update ) {
        $requests[] = array(
            'updateCells' => array(
                'range' => array(
                    'sheetId'          => (int) $metadata['Product Review']['sheet_id'],
                    'startRowIndex'     => (int) $update['row'] - 1,
                    'endRowIndex'       => (int) $update['row'],
                    'startColumnIndex'  => 3,
                    'endColumnIndex'    => 4,
                ),
                'rows'   => array( array( 'values' => array( array( 'userEnteredValue' => rhn_catalog_discovery_extended_value( $update['name'] ) ) ) ) ),
                'fields' => 'userEnteredValue',
            ),
        );
    }
    return $requests;
}

/** Send a Google Sheets batchUpdate request. */
function rhn_catalog_discovery_apply_requests( $requests ) {
    if ( ! $requests ) {
        return array( 'replies' => array() );
    }
    $token = rhn_catalog_google_access_token();
    if ( is_wp_error( $token ) ) {
        return $token;
    }
    $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode( rhn_catalog_google_sheet_id() ) . ':batchUpdate';
    $response = wp_safe_remote_post(
        $url,
        array(
            'timeout' => 30,
            'headers' => array( 'Authorization' => 'Bearer ' . $token, 'Content-Type' => 'application/json' ),
            'body'    => wp_json_encode( array( 'requests' => array_values( $requests ), 'includeSpreadsheetInResponse' => false ) ),
        )
    );
    if ( is_wp_error( $response ) ) {
        return $response;
    }
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    $code = (int) wp_remote_retrieve_response_code( $response );
    if ( 200 !== $code || ! is_array( $body ) ) {
        $google_message = is_array( $body ) ? sanitize_text_field( (string) ( $body['error']['message'] ?? '' ) ) : '';
        $google_status  = is_array( $body ) ? sanitize_key( (string) ( $body['error']['status'] ?? '' ) ) : '';
        $detail = 'Google rejected the product-discovery update (HTTP ' . $code;
        if ( '' !== $google_status ) {
            $detail .= ', ' . $google_status;
        }
        $detail .= '). No existing client rows were intentionally changed.';
        if ( '' !== $google_message ) {
            $detail .= ' Google: ' . $google_message;
        }
        return new WP_Error( 'rhn_discovery_write', $detail );
    }
    return $body;
}

/** Preview or apply the Clarkston Revel mirror reconciliation. */
function rhn_catalog_run_discovery( $write = false, $source = 'mirror' ) {
    if ( ! rhn_catalog_batch_guarded() || ! rhn_catalog_google_private_credentials_configured() ) {
        return new WP_Error( 'rhn_discovery_guard', 'Exact staging and private Google access are required.' );
    }
    if ( $write && ! rhn_catalog_discovery_write_enabled() ) {
        return new WP_Error( 'rhn_discovery_disabled', 'Automatic Sheet writes are disabled until edit access is explicitly enabled.' );
    }
    if ( ! add_option( 'rhn_catalog_discovery_lock', time(), '', false ) ) {
        $started = (int) get_option( 'rhn_catalog_discovery_lock', 0 );
        if ( $started && time() - $started < 1800 ) {
            return new WP_Error( 'rhn_discovery_locked', 'A product discovery run is already active.' );
        }
        delete_option( 'rhn_catalog_discovery_lock' );
        if ( ! add_option( 'rhn_catalog_discovery_lock', time(), '', false ) ) {
            return new WP_Error( 'rhn_discovery_locked', 'Could not acquire the product-discovery lock.' );
        }
    }
    try {
        $product_rows = rhn_catalog_google_sheet_values( "'Product Review'!A5:X" );
        if ( is_wp_error( $product_rows ) ) {
            return $product_rows;
        }
        if ( 'legacy' === $source ) {
            $products = rhn_catalog_collect_legacy_revel_products();
            $new_action = 'Remove from Website';
        } else {
            $products = rhn_catalog_collect_revel_products();
            $new_action = 'Keep Off Website';
        }
        if ( is_wp_error( $products ) ) {
            return $products;
        }
        $plan = rhn_catalog_discovery_plan( $products, $product_rows );
        if ( $plan['errors'] ) {
            return new WP_Error( 'rhn_discovery_plan', 'Product discovery found duplicate or invalid identities; the Sheet was not changed.', $plan );
        }
        $result = array( 'write' => false, 'plan' => $plan );
        if ( ! $write || ( ! $plan['new'] && ! $plan['name_updates'] ) ) {
            return $result;
        }

        $catalog_rows = rhn_catalog_google_sheet_values( 'Catalog!A:W' );
        $metadata = rhn_catalog_discovery_sheet_metadata();
        if ( is_wp_error( $catalog_rows ) || is_wp_error( $metadata ) ) {
            return is_wp_error( $catalog_rows ) ? $catalog_rows : $metadata;
        }
        try {
            $requests = rhn_catalog_discovery_batch_requests( $plan, $product_rows, $catalog_rows, $metadata, $new_action );
        } catch ( Throwable $error ) {
            return new WP_Error( 'rhn_discovery_batch', $error->getMessage() );
        }
        $applied = rhn_catalog_discovery_apply_requests( $requests );
        if ( is_wp_error( $applied ) ) {
            return $applied;
        }

        $verified_rows = rhn_catalog_google_sheet_values( "'Product Review'!A5:X" );
        if ( is_wp_error( $verified_rows ) ) {
            return new WP_Error( 'rhn_discovery_verify', 'Google accepted the update, but the required read-back verification failed.' );
        }
        $verified = rhn_catalog_discovery_plan( $products, $verified_rows );
        if ( $verified['errors'] || $verified['new'] || $verified['name_updates'] ) {
            return new WP_Error( 'rhn_discovery_verify', 'Sheet read-back did not match the Revel mirror; review is required.', $verified );
        }
        $result['write'] = true;
        $result['verified'] = true;
        $result['request_count'] = count( $requests );
        update_option(
            'rhn_catalog_discovery_health',
            array(
                'last_success' => current_time( 'mysql' ),
                'added'        => count( $plan['new'] ),
                'renamed'      => count( $plan['name_updates'] ),
                'source_count' => count( $products ),
                'source'       => $source,
            ),
            false
        );
        return $result;
    } finally {
        delete_option( 'rhn_catalog_discovery_lock' );
    }
}

/** Queue one consolidated Sheet intake after a Kosmos product batch. */
function rhn_catalog_queue_discovery_after_rest_save( $product, $request = null, $creating = false ) {
    if ( ! rhn_catalog_discovery_write_enabled() || ! $request instanceof WP_REST_Request || ! preg_match( '#^/wc/v[123]/products(?:/\d+)?$#', $request->get_route() ) || ! $request->has_param( 'name' ) ) {
        return;
    }
    if ( ! wp_next_scheduled( 'rhn_catalog_sheet_discovery_event' ) ) {
        wp_schedule_single_event( time() + 300, 'rhn_catalog_sheet_discovery_event' );
    }
}

function rhn_catalog_run_scheduled_discovery() {
    if ( rhn_catalog_discovery_write_enabled() ) {
        rhn_catalog_run_discovery( true );
    }
}

function rhn_catalog_discovery_admin_page() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_batch_guarded() ) {
        wp_die( 'Staging administrators only.' );
    }
    $notice = '';
    $result = array();
    if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
        check_admin_referer( 'rhn_catalog_discovery' );
        $action = sanitize_key( wp_unslash( $_POST['discovery_action'] ?? '' ) );
        if ( 'enable' === $action ) {
            update_option( 'rhn_catalog_discovery_write_enabled', 'yes', false );
            $notice = 'Automatic Sheet appends enabled. Existing client rows were not changed.';
        } elseif ( 'disable' === $action ) {
            update_option( 'rhn_catalog_discovery_write_enabled', 'no', false );
            $notice = 'Automatic Sheet appends disabled. Existing client rows were not changed.';
        } else {
            $source = in_array( $action, array( 'legacy_preview', 'legacy_apply' ), true ) ? 'legacy' : 'mirror';
            $write = in_array( $action, array( 'apply', 'legacy_apply' ), true );
            $result = rhn_catalog_run_discovery( $write, $source );
            if ( is_wp_error( $result ) ) {
                $notice = $result->get_error_message();
                $result = $result->get_error_data();
            } else {
                $plan = $result['plan'];
                $notice = count( $plan['new'] ) . ' new Revel products; ' . count( $plan['name_updates'] ) . ' source-name updates; ' . count( $plan['unchanged'] ) . ' already matched. ' . ( $result['write'] ? 'Applied and read-back verified.' : 'Preview only; no Sheet cells changed.' );
            }
        }
    }
    echo '<div class="wrap"><h1>Clarkston Revel → Sheet discovery</h1><p>This preserves all existing rows and client fields. Automatic discovery appends exact barcode/name identities received through the staging Kosmos product feed as Working + Keep Off Website. The one-time verified legacy reconciliation appends existing WooCommerce products that exactly match the Clarkston Revel export as Working + Remove from Website. Revel is never written.</p>';
    echo '<p><strong>Automatic writes:</strong> ' . ( rhn_catalog_discovery_write_enabled() ? 'Enabled' : 'Disabled pending explicit Sheet edit access' ) . '</p>';
    if ( $notice ) {
        echo '<p role="status">' . esc_html( $notice ) . '</p>';
    }
    if ( $result ) {
        echo '<pre>' . esc_html( wp_json_encode( $result, JSON_PRETTY_PRINT ) ) . '</pre>';
    }
    echo '<form method="post">';
    wp_nonce_field( 'rhn_catalog_discovery' );
    echo '<button class="button" name="discovery_action" value="preview">Preview exact differences</button> <button class="button button-primary" name="discovery_action" value="apply">Append missing products + verify</button> ';
    if ( rhn_catalog_discovery_write_enabled() ) {
        echo '<button class="button" name="discovery_action" value="disable">Disable automatic appends</button>';
    } else {
        echo '<button class="button" name="discovery_action" value="enable">Enable automatic appends</button>';
    }
    echo '<hr><h2>One-time verified legacy backfill</h2><p>Expected source: 343 exact staging WooCommerce ID + SKU + Clarkston Revel name identities. These existing products start as Remove from Website so they cannot remain customer-facing unless the client later chooses Publish.</p>';
    echo '<button class="button" name="discovery_action" value="legacy_preview">Preview 343-product legacy reconciliation</button> <button class="button button-primary" name="discovery_action" value="legacy_apply">Append verified legacy products + verify</button>';
    echo '</form></div>';
}

if ( function_exists( 'add_action' ) ) {
    add_action( 'woocommerce_rest_insert_product', 'rhn_catalog_queue_discovery_after_rest_save', 120, 3 );
    add_action( 'woocommerce_rest_insert_product_object', 'rhn_catalog_queue_discovery_after_rest_save', 120, 3 );
    add_action( 'rhn_catalog_sheet_discovery_event', 'rhn_catalog_run_scheduled_discovery' );
    add_action( 'rhn_catalog_sheet_sync_event', 'rhn_catalog_run_scheduled_discovery', 5 );
    add_action(
        'admin_menu',
        function () {
            if ( rhn_catalog_presentation_enabled() ) {
                add_management_page( 'Clarkston Revel discovery', 'Clarkston Revel discovery', 'manage_options', 'rhn-catalog-discovery', 'rhn_catalog_discovery_admin_page' );
            }
        }
    );
}
