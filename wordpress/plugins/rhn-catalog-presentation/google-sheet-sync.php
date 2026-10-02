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
        $record = array(
            'sku'      => $get( 'sku' ),
            'approved' => true,
            'source'   => $get( 'source' ),
        );
        foreach ( array( 'name', 'brand', 'description', 'short_description', 'weight', 'weight_unit', 'package_size', 'featured_image_url', 'ingredients', 'allergens', 'supplement_facts', 'directions', 'warnings', 'seo_title', 'seo_description' ) as $field ) {
            $value = $get( $field );
            if ( '' !== $value ) {
                $record[ $field ] = $value;
            }
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
    if ( ! is_string( $csv ) || '' === trim( $csv ) || strlen( $csv ) > 2097152 ) {
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
            if ( count( $rows ) > 1001 ) {
                throw new InvalidArgumentException( 'The Catalog tab may contain no more than 1000 data rows.' );
            }
        }
        return $rows;
    } finally {
        fclose( $stream );
    }
}

function rhn_catalog_google_access_token() {
    $cached = get_transient( 'rhn_catalog_google_token' );
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
                'scope' => 'https://www.googleapis.com/auth/spreadsheets.readonly https://www.googleapis.com/auth/drive.readonly',
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
    set_transient( 'rhn_catalog_google_token', $body['access_token'], max( 60, (int) ( $body['expires_in'] ?? 3600 ) - 120 ) );
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
        $result = rhn_catalog_fetch_google_sheet();
        if ( is_wp_error( $result ) ) {
            $notice = 'Not applied: ' . $result->get_error_message();
        } else {
            if ( function_exists( 'rhn_catalog_photo_intake' ) ) {
                $result = rhn_catalog_photo_intake( $result, 'apply' === ( $_POST['sheet_action'] ?? '' ) );
            }
            $details = $result;
            $notice = count( $result['registry'] ) . ' approved rows validated; ' . count( $result['withdrawals'] ?? array() ) . ' explicit withdrawals validated; ' . count( $result['skipped'] ) . ' rows skipped.';
            if ( 'apply' === ( $_POST['sheet_action'] ?? '' ) ) {
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
    echo '<div class="wrap"><h1>Google Sheet catalog intake</h1><p>Read-only Google access. Preview first. Approved and Approved for Test rows update website-owned catalog fields and change the matching staging product to Published/visible. The exact status Remove from Website changes it to Draft/hidden; it never deletes a product. Price, inventory, SKU, orders and Revel remain untouched.</p>';
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
    echo '<button class="button" name="sheet_action" value="preview">Preview approved Sheet rows</button> <button class="button button-primary" name="sheet_action" value="apply">Apply approved registry</button></form></div>';
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
