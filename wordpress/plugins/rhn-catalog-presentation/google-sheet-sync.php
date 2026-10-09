<?php
/** Read-only, approval-gated Google Sheet catalog intake. */
defined( 'ABSPATH' ) || exit;

function rhn_catalog_sheet_headers() {
    return array(
        'Status'                    => 'status',
        'SKU / Barcode'             => 'sku',
        'Product Name'              => 'name',
        'Brand'                     => 'brand',
        'Website Categories'        => 'categories',
        'Long Description'          => 'description',
        'Short Description'         => 'short_description',
        'Packaged Weight'           => 'weight',
        'Weight Unit'               => 'weight_unit',
        'Package Size / Net Contents' => 'package_size',
        'Featured Image URL'        => 'featured_image_url',
        'Gallery Image URLs'        => 'gallery_image_urls',
        'Ingredients'               => 'ingredients',
        'Allergens'                 => 'allergens',
        'Supplement Facts'          => 'supplement_facts',
        'Directions'                => 'directions',
        'Warnings'                  => 'warnings',
        'SEO Title'                 => 'seo_title',
        'SEO Description'           => 'seo_description',
        'Source / Evidence'         => 'source',
        'Approved By'               => 'approved_by',
        'Last Reviewed'             => 'last_reviewed',
        'Internal Notes'            => 'internal_notes',
    );
}

function rhn_catalog_split_list_cell( $value ) {
    if ( ! is_string( $value ) || '' === trim( $value ) ) {
        return array();
    }
    return array_values( array_unique( array_filter( array_map( 'trim', preg_split( '/[|\r\n]+/', $value ) ) ) ) );
}

/**
 * Normalize a client-entered shipping weight for the derived import registry.
 *
 * The client Sheet remains untouched. Existing values always win; this helper
 * only separates an attached unit (for example, `4.5oz`) or supplies a default
 * for a blank weight when the package size is one of the four client-approved
 * launch rules or the exact SKU has a client-approved product-specific value.
 */
function rhn_catalog_normalize_sheet_weight( $weight, $unit, $package_size, $sku = '' ) {
    $weight       = trim( (string) $weight );
    $unit         = strtolower( trim( (string) $unit ) );
    $package_size = trim( (string) $package_size );
    $sku          = trim( (string) $sku );

    if ( '' !== $weight ) {
        if ( preg_match( '/^([0-9]+(?:\.[0-9]+)?)\s*(oz|lb|g|kg)$/i', $weight, $matches ) ) {
            $attached_unit = strtolower( $matches[2] );
            if ( '' === $unit || $attached_unit === $unit ) {
                return array( $matches[1], $attached_unit );
            }
        }

        // Preserve every client-entered numeric value. When its separate unit
        // is blank, use ounces only for a package size covered by an approved rule.
        if ( is_numeric( $weight ) && '' === $unit && rhn_catalog_default_ounces( $sku, $package_size ) ) {
            $unit = 'oz';
        }
        return array( $weight, $unit );
    }

    if ( '' !== $unit ) {
        return array( $weight, $unit );
    }

    $default_weight = rhn_catalog_default_ounces( $sku, $package_size );
    return null === $default_weight ? array( '', '' ) : array( $default_weight, 'oz' );
}

/** Return an exact client-approved product weight before considering size rules. */
function rhn_catalog_default_ounces( $sku, $package_size ) {
    $product_rules = array(
        // Rebekah's Quercetin with Bromelain — 120 capsules.
        '733739430700' => '6',
    );
    $sku = trim( (string) $sku );
    if ( isset( $product_rules[ $sku ] ) ) {
        return $product_rules[ $sku ];
    }
    return rhn_catalog_default_ounces_for_package_size( $package_size );
}

/** Return the client-approved maximum ounces for an unambiguous package size. */
function rhn_catalog_default_ounces_for_package_size( $package_size ) {
    $package_size = trim( (string) $package_size );
    $rules = array(
        '/^1(?:\.0+)?\s*(?:fl(?:uid)?\.?\s*)?oz\b/i' => '4.5',
        '/^2(?:\.0+)?\s*(?:fl(?:uid)?\.?\s*)?oz\b/i' => '5.5',
        '/^60\s*(?:count|ct|(?:liquid\s+)?(?:veggie\s+)?(?:caps?|capsules?|vcaps?)|tablets?)\b/i' => '6',
        '/^90\s*(?:count|ct|(?:liquid\s+)?(?:veggie\s+)?(?:caps?|capsules?|vcaps?)|tablets?)\b/i' => '7',
    );
    foreach ( $rules as $pattern => $weight ) {
        if ( preg_match( $pattern, $package_size ) ) {
            return $weight;
        }
    }
    return null;
}

function rhn_catalog_sheet_rows_to_registry( $rows ) {
    if ( ! is_array( $rows ) || count( $rows ) < 2 || ! is_array( $rows[0] ) ) {
        throw new InvalidArgumentException( 'The Catalog tab must contain a header and at least one data row.' );
    }
    $expected = rhn_catalog_sheet_headers();
    $indexes = array();
    foreach ( $rows[0] as $index => $heading ) {
        if ( isset( $expected[ $heading ] ) ) {
            $indexes[ $expected[ $heading ] ] = (int) $index;
        }
    }
    foreach ( array( 'status', 'sku', 'source' ) as $required ) {
        if ( ! isset( $indexes[ $required ] ) ) {
            throw new InvalidArgumentException( 'Missing required Catalog heading: ' . $required );
        }
    }

    $records = array();
    $withdrawals = array();
    $skipped = array();
    foreach ( array_slice( $rows, 1, null, true ) as $offset => $cells ) {
        if ( ! is_array( $cells ) || ! array_filter( $cells, static fn( $value ) => '' !== trim( (string) $value ) ) ) {
            continue;
        }
        $row_number = $offset + 1;
        $get = static function ( $key ) use ( $cells, $indexes ) {
            return isset( $indexes[ $key ] ) ? trim( (string) ( $cells[ $indexes[ $key ] ] ?? '' ) ) : '';
        };
        $status = $get( 'status' );
        if ( 'Remove from Website' === $status ) {
            $sku = $get( 'sku' );
            if ( '' === $sku || $sku !== trim( $sku ) || strlen( $sku ) > 100 ) {
                throw new InvalidArgumentException( 'Remove from Website requires an exact SKU / Barcode.' );
            }
            if ( isset( $withdrawals[ $sku ] ) ) {
                throw new InvalidArgumentException( 'Duplicate Remove from Website SKU in this input.' );
            }
            $withdrawals[ $sku ] = array(
                'sku'    => $sku,
                'status' => $status,
                'source' => $get( 'source' ),
                'row'    => $row_number,
            );
            continue;
        }
        if ( ! in_array( $status, array( 'Approved for Test', 'Approved' ), true ) ) {
            $skipped[] = array( 'row' => $row_number, 'reason' => 'Status is not approved.' );
            continue;
        }
        $source = $get( 'source' );
        if ( '' === $source ) {
            // The approved record itself came from this client-owned Sheet row.
            // Use that exact row as derived provenance without editing the Sheet
            // or weakening the client-controlled approval gate.
            $source = 'Client-owned Inventory Sheet — Catalog row ' . $row_number;
        }
        $record = array(
            'sku'      => $get( 'sku' ),
            'approved' => true,
            'source'   => $source,
        );
        foreach ( array( 'name', 'brand', 'description', 'short_description', 'package_size', 'featured_image_url', 'ingredients', 'allergens', 'supplement_facts', 'directions', 'warnings', 'seo_title', 'seo_description' ) as $field ) {
            $value = $get( $field );
            if ( '' !== $value ) {
                $record[ $field ] = $value;
            }
        }
        list( $weight, $weight_unit ) = rhn_catalog_normalize_sheet_weight(
            $get( 'weight' ),
            $get( 'weight_unit' ),
            $get( 'package_size' ),
            $get( 'sku' )
        );
        if ( '' !== $weight ) {
            $record['weight'] = $weight;
        }
        if ( '' !== $weight_unit ) {
            $record['weight_unit'] = $weight_unit;
        }
        $categories = rhn_catalog_split_list_cell( $get( 'categories' ) );
        if ( $categories ) {
            $record['categories'] = $categories;
        }
        $gallery = rhn_catalog_split_list_cell( $get( 'gallery_image_urls' ) );
        if ( $gallery ) {
            $record['gallery_image_urls'] = $gallery;
        }
        $records[] = $record;
    }
    $registry = $records ? rhn_catalog_parse_registry( wp_json_encode( $records ) ) : array();
    return array( 'registry' => $registry, 'withdrawals' => $withdrawals, 'skipped' => $skipped );
}

function rhn_catalog_base64url( $value ) {
    return rtrim( strtr( base64_encode( $value ), '+/', '-_' ), '=' );
}

function rhn_catalog_google_sheet_id() {
    if ( defined( 'RHN_CATALOG_GOOGLE_SHEET_ID' ) && is_string( RHN_CATALOG_GOOGLE_SHEET_ID ) && '' !== RHN_CATALOG_GOOGLE_SHEET_ID ) {
        return RHN_CATALOG_GOOGLE_SHEET_ID;
    }
    $private_sheet_id = get_option( 'rhn_catalog_google_sheet_id', '' );
    if ( is_string( $private_sheet_id ) && '' !== $private_sheet_id ) {
        return $private_sheet_id;
    }
    // Exact Blue Nova-owned staging fixture; remove this fallback when the private client Sheet is configured.
    return rhn_catalog_presentation_enabled() ? '1gz6YDVXjQR76TP51V9BXgNPk3Tffqx48n_ohKhljYjY' : '';
}

function rhn_catalog_google_credentials() {
    if ( defined( 'RHN_CATALOG_GOOGLE_CREDENTIALS_PATH' ) ) {
        $path = RHN_CATALOG_GOOGLE_CREDENTIALS_PATH;
        if ( ! is_string( $path ) || ! is_readable( $path ) ) {
            return new WP_Error( 'rhn_google_config', 'Google credentials file is unavailable.' );
        }
        $credentials = json_decode( file_get_contents( $path ), true );
        return is_array( $credentials ) ? $credentials : new WP_Error( 'rhn_google_config', 'Google credentials file is invalid.' );
    }

    $stored = get_option( 'rhn_catalog_google_credentials_encrypted', '' );
    if ( ! is_string( $stored ) || '' === $stored ) {
        return new WP_Error( 'rhn_google_config', 'Google credentials are not configured.' );
    }
    $envelope = json_decode( base64_decode( $stored, true ), true );
    if ( ! is_array( $envelope ) || 1 !== (int) ( $envelope['v'] ?? 0 ) ) {
        return new WP_Error( 'rhn_google_config', 'Stored Google credentials are invalid.' );
    }
    $iv = base64_decode( (string) ( $envelope['iv'] ?? '' ), true );
    $tag = base64_decode( (string) ( $envelope['tag'] ?? '' ), true );
    $ciphertext = base64_decode( (string) ( $envelope['data'] ?? '' ), true );
    if ( false === $iv || false === $tag || false === $ciphertext ) {
        return new WP_Error( 'rhn_google_config', 'Stored Google credentials are invalid.' );
    }
    $plaintext = openssl_decrypt( $ciphertext, 'aes-256-gcm', hash( 'sha256', wp_salt( 'auth' ), true ), OPENSSL_RAW_DATA, $iv, $tag );
    if ( false === $plaintext ) {
        return new WP_Error( 'rhn_google_config', 'Stored Google credentials could not be decrypted.' );
    }
    $credentials = json_decode( $plaintext, true );
    return is_array( $credentials ) ? $credentials : new WP_Error( 'rhn_google_config', 'Stored Google credentials are invalid.' );
}

function rhn_catalog_google_private_credentials_configured() {
    return defined( 'RHN_CATALOG_GOOGLE_CREDENTIALS_PATH' ) || '' !== (string) get_option( 'rhn_catalog_google_credentials_encrypted', '' );
}

function rhn_catalog_csv_rows( $csv ) {
    if ( ! is_string( $csv ) || '' === trim( $csv ) || strlen( $csv ) > 8388608 ) {
        throw new InvalidArgumentException( 'Google returned an empty or oversized CSV response.' );
    }
    $stream = fopen( 'php://temp', 'w+' );
    if ( false === $stream ) {
        throw new RuntimeException( 'Could not open a temporary CSV parser.' );
    }
    try {
        fwrite( $stream, $csv );
        rewind( $stream );
        $rows = array();
        while ( false !== ( $row = fgetcsv( $stream, null, ',', '"', '' ) ) ) {
            $rows[] = $row;
            if ( count( $rows ) > 5001 ) {
                throw new InvalidArgumentException( 'The Catalog tab may contain no more than 5000 data rows.' );
            }
        }
        return $rows;
    } finally {
        fclose( $stream );
    }
}

function rhn_catalog_google_access_token() {
    // Separate cache key prevents reuse of an older read-only token after the
    // discovery intake is enabled.
    $cached = get_transient( 'rhn_catalog_google_token_rw_v1' );
    if ( is_string( $cached ) && '' !== $cached ) {
        return $cached;
    }
    $credentials = rhn_catalog_google_credentials();
    if ( is_wp_error( $credentials ) ) {
        return $credentials;
    }
    if ( empty( $credentials['client_email'] ) || empty( $credentials['private_key'] ) ) {
        return new WP_Error( 'rhn_google_config', 'Google credentials file is invalid.' );
    }
    $now = time();
    $header = rhn_catalog_base64url( wp_json_encode( array( 'alg' => 'RS256', 'typ' => 'JWT' ) ) );
    $claims = rhn_catalog_base64url(
        wp_json_encode(
            array(
                'iss'   => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/drive.readonly',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'iat'   => $now,
                'exp'   => $now + 3600,
            )
        )
    );
    $unsigned = $header . '.' . $claims;
    $signature = '';
    if ( ! openssl_sign( $unsigned, $signature, $credentials['private_key'], OPENSSL_ALGO_SHA256 ) ) {
        return new WP_Error( 'rhn_google_sign', 'Could not sign the Google access request.' );
    }
    $assertion = $unsigned . '.' . rhn_catalog_base64url( $signature );
    $response = wp_safe_remote_post(
        'https://oauth2.googleapis.com/token',
        array(
            'timeout' => 20,
            'body'    => array(
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $assertion,
            ),
        )
    );
    if ( is_wp_error( $response ) ) {
        return $response;
    }
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( 200 !== wp_remote_retrieve_response_code( $response ) || ! is_array( $body ) || empty( $body['access_token'] ) ) {
        return new WP_Error( 'rhn_google_token', 'Google did not issue an access token.' );
    }
    set_transient( 'rhn_catalog_google_token_rw_v1', $body['access_token'], max( 60, (int) ( $body['expires_in'] ?? 3600 ) - 120 ) );
    return $body['access_token'];
}

function rhn_catalog_fetch_google_sheet() {
    $sheet_id = rhn_catalog_google_sheet_id();
    if ( '' === $sheet_id ) {
        return new WP_Error( 'rhn_google_sheet', 'Google Sheet ID is not configured.' );
    }
    if ( ! rhn_catalog_google_private_credentials_configured() ) {
        // Force one header row. Without this, Google Visualization may infer the
        // first data row as part of the column labels when most catalog fields
        // are blank, producing headings such as "Status Working".
        $url = 'https://docs.google.com/spreadsheets/d/' . rawurlencode( $sheet_id ) . '/gviz/tq?tqx=out:csv&sheet=Catalog&headers=1';
        $response = wp_safe_remote_get( $url, array( 'timeout' => 20, 'limit_response_size' => 2097153 ) );
        if ( is_wp_error( $response ) ) {
            return $response;
        }
        if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
            return new WP_Error( 'rhn_google_public_read', 'The temporary public test Sheet could not be read.' );
        }
        try {
            return rhn_catalog_sheet_rows_to_registry( rhn_catalog_csv_rows( wp_remote_retrieve_body( $response ) ) );
        } catch ( Throwable $error ) {
            return new WP_Error( 'rhn_google_parse', $error->getMessage() );
        }
    }
    $token = rhn_catalog_google_access_token();
    if ( is_wp_error( $token ) ) {
        return $token;
    }
    $range = defined( 'RHN_CATALOG_GOOGLE_SHEET_RANGE' ) ? RHN_CATALOG_GOOGLE_SHEET_RANGE : (string) get_option( 'rhn_catalog_google_sheet_range', 'Catalog!A:W' );
    $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode( $sheet_id ) . '/values/' . rawurlencode( $range ) . '?majorDimension=ROWS';
    $response = wp_safe_remote_get( $url, array( 'timeout' => 20, 'headers' => array( 'Authorization' => 'Bearer ' . $token ) ) );
    if ( is_wp_error( $response ) ) {
        return $response;
    }
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( 200 !== wp_remote_retrieve_response_code( $response ) || ! is_array( $body ) || ! isset( $body['values'] ) ) {
        return new WP_Error( 'rhn_google_read', 'Google Sheet read failed.' );
    }
    try {
        return rhn_catalog_sheet_rows_to_registry( $body['values'] );
    } catch ( Throwable $error ) {
        return new WP_Error( 'rhn_google_parse', $error->getMessage() );
    }
}

function rhn_catalog_google_sheet_page() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_presentation_enabled() ) {
        wp_die( 'Staging administrators only.' );
    }
    $notice = '';
    $details = array();
    if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
        check_admin_referer( 'rhn_catalog_google_sheet' );
        $action = sanitize_key( wp_unslash( $_POST['sheet_action'] ?? '' ) );
        $result = rhn_catalog_fetch_google_sheet();
        if ( is_wp_error( $result ) ) {
            $notice = 'Not applied: ' . $result->get_error_message();
        } else {
            $weight_only = in_array( $action, array( 'preview_weights', 'apply_weights' ), true );
            if ( ! $weight_only && function_exists( 'rhn_catalog_photo_intake' ) ) {
                $result = rhn_catalog_photo_intake( $result, 'apply' === $action );
            }
            $details = $result;
            $notice = count( $result['registry'] ) . ' approved rows validated; ' . count( $result['withdrawals'] ?? array() ) . ' explicit withdrawals validated; ' . count( $result['skipped'] ) . ' rows skipped.';
            if ( $weight_only ) {
                $weights = rhn_catalog_apply_shipping_weights( $result['registry'], 'apply_weights' === $action );
                if ( is_wp_error( $weights ) ) {
                    $notice = 'Shipping weights not applied: ' . $weights->get_error_message();
                    $details = $weights->get_error_data() ?: $details;
                } else {
                    $details = $weights;
                    if ( 'apply_weights' === $action ) {
                        $notice = count( $weights['updated'] ) . ' shipping weights updated and verified; ' . count( $weights['unchanged'] ) . ' already correct; ' . count( $weights['skipped'] ) . ' approved rows had no weight. No copy, image, category, price, inventory, status or visibility fields were changed.';
                    } else {
                        $notice = count( $weights['eligible'] ) . ' shipping weights would change; ' . count( $weights['unchanged'] ) . ' are already correct; ' . count( $weights['skipped'] ) . ' approved rows have no weight. Preview made no changes.';
                    }
                }
            } elseif ( 'apply' === $action ) {
                if ( function_exists( 'rhn_catalog_apply_sheet_result' ) ) {
                    $applied = rhn_catalog_apply_sheet_result( $result, true );
                    if ( is_wp_error( $applied ) ) {
                        $notice = 'Not applied: ' . $applied->get_error_message();
                    } else {
                        $notice .= ' Registry and staging products saved and verified. ' . count( $applied['updated'] ) . ' approved products changed to Published/visible; ' . count( $applied['withdrawn'] ) . ' products changed to Draft/hidden.';
                    }
                } else {
                    $existing = rhn_catalog_registry();
                    update_option( 'rhn_catalog_presentation_previous', $existing, false );
                    $merged = array_replace( $existing, $result['registry'] );
                    update_option( 'rhn_catalog_presentation_overrides', $merged, false );
                    $notice .= ' Registry saved; the staging automation module is unavailable, so product records were not changed.';
                }
            }
        }
    }
    echo '<div class="wrap"><h1>Google Sheet catalog intake</h1><p>Authenticated Google access. For shipping setup, use the weight-only buttons: they match exact SKUs and change only WooCommerce product weight. They do not change Rebekah\'s Sheet, copy, images, categories, prices, inventory, product status or visibility. The separate Clarkston discovery tool is the only workflow allowed to append rows.</p>';
    if ( ! rhn_catalog_google_private_credentials_configured() ) {
        echo '<p><strong>Test mode:</strong> reading the exact Blue Nova staging fixture through its temporary public read-only CSV link. Configure the private service identity before restricting or replacing the Sheet.</p>';
    }
    if ( $notice ) {
        echo '<p role="status">' . esc_html( $notice ) . '</p>';
    }
    if ( $details ) {
        echo '<pre>' . esc_html( wp_json_encode( $details, JSON_PRETTY_PRINT ) ) . '</pre>';
    }
    echo '<form method="post">';
    wp_nonce_field( 'rhn_catalog_google_sheet' );
    echo '<h2>Shipping weights only</h2><p><button class="button" name="sheet_action" value="preview_weights">Preview shipping weights only</button> <button class="button button-primary" name="sheet_action" value="apply_weights">Apply shipping weights only</button></p><h2>Full catalog publishing</h2><p><button class="button" name="sheet_action" value="preview">Preview full approved rows</button> <button class="button" name="sheet_action" value="apply">Apply full catalog + withdrawals</button></p></form></div>';
}

if ( function_exists( 'add_action' ) ) {
    add_action(
        'admin_menu',
        function () {
            if ( rhn_catalog_presentation_enabled() ) {
                add_management_page( 'Google Sheet catalog intake', 'Google Sheet catalog intake', 'manage_options', 'rhn-catalog-google-sheet', 'rhn_catalog_google_sheet_page' );
            }
        }
    );
}
