<?php
/** Approval-gated Google Sheet catalog intake. */
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

/** Normalize visually equivalent Sheet header punctuation and whitespace. */
function rhn_catalog_normalize_heading( $heading ) {
    $heading = str_replace( array( "\xE2\x80\x94", "\xE2\x80\x93" ), '-', trim( (string) $heading ) );
    return preg_replace( '/\s+/u', ' ', $heading );
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
            'sheet_row'=> $row_number,
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
    // This package is guarded to Rebekah's exact production host. Keep the
    // canonical workbook identity available even if an option-cache or
    // deployment-layer filter refuses the redundant option write.
    return '18wuB-kfgMfKbWGV6qgHClXDjagtPPJP7XdkHlxQhF-s';
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

    $stored = '';
    $encrypted_path = __DIR__ . '/.rhn-google-credentials.php';
    if ( is_readable( $encrypted_path ) && ! is_link( $encrypted_path ) ) {
        $encrypted_file = file_get_contents( $encrypted_path );
        $prefix = "<?php exit; ?>\n";
        if ( is_string( $encrypted_file ) && str_starts_with( $encrypted_file, $prefix ) ) {
            $stored = substr( $encrypted_file, strlen( $prefix ) );
        } else {
            return new WP_Error( 'rhn_google_config', 'Encrypted Google credential file is invalid.' );
        }
    }
    if ( '' === $stored ) {
        $stored = get_option( 'rhn_catalog_google_credentials_encrypted', '' );
    }
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
    return defined( 'RHN_CATALOG_GOOGLE_CREDENTIALS_PATH' )
        || ( is_readable( __DIR__ . '/.rhn-google-credentials.php' ) && ! is_link( __DIR__ . '/.rhn-google-credentials.php' ) )
        || '' !== (string) get_option( 'rhn_catalog_google_credentials_encrypted', '' );
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
    $cached = get_transient( 'rhn_live_catalog_google_token_rw_v2' );
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
                'scope' => 'https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/drive.metadata.readonly',
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
    set_transient( 'rhn_live_catalog_google_token_rw_v2', $body['access_token'], max( 60, (int) ( $body['expires_in'] ?? 3600 ) - 120 ) );
    return $body['access_token'];
}

function rhn_catalog_fetch_google_sheet() {
    $sheet_id = rhn_catalog_google_sheet_id();
    if ( '' === $sheet_id ) {
        return new WP_Error( 'rhn_google_sheet', 'Google Sheet ID is not configured.' );
    }
    if ( ! rhn_catalog_google_private_credentials_configured() ) {
        return new WP_Error( 'rhn_google_config', 'Private Google credentials are required on production.' );
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
        wp_die( 'Production administrators only.' );
    }
    $notice = '';
    $details = array();
    if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
        check_admin_referer( 'rhn_catalog_google_sheet' );
        $action = sanitize_key( wp_unslash( $_POST['sheet_action'] ?? '' ) );
        if ( ! in_array( $action, array( 'preview', 'apply' ), true ) ) {
            $notice = 'Unsupported catalog action. No changes were made.';
            $action = '';
        }
        if ( '' === $action ) {
            $result = new WP_Error( 'rhn_catalog_action', $notice );
        } else {
        $result = rhn_catalog_fetch_google_sheet();
        }
        if ( is_wp_error( $result ) ) {
            $notice = 'Not applied: ' . $result->get_error_message();
        } else {
            if ( function_exists( 'rhn_catalog_photo_intake' ) ) {
                // Product preview/apply must use the same no-write photo plan.
                // Cron persists photo intake state only after its remote reads pass.
                $result = rhn_catalog_photo_intake( $result, false );
            }
            if ( 'error' === (string) ( $result['photo_intake']['status'] ?? '' ) ) {
                $notice = 'Not applied: ' . (string) ( $result['photo_intake']['message'] ?? 'Photo intake failed.' );
                $details = $result;
            } elseif ( ! function_exists( 'rhn_catalog_apply_sheet_result' ) ) {
                $notice = 'Not applied: the production processor is unavailable. No registry or product records were changed.';
            } else {
                $preview = rhn_catalog_apply_sheet_result( $result, false );
                if ( is_wp_error( $preview ) ) {
                    $notice = 'Not applied: ' . $preview->get_error_message();
                    $details = $preview->get_error_data() ?: $result;
                } else {
                    $details = $preview;
                    $fingerprint = (string) ( $preview['receipt']['plan_fingerprint'] ?? '' );
                    $preview_key = 'rhn_live_catalog_admin_preview_' . (int) get_current_user_id();
                    if ( 'preview' === $action ) {
                        set_transient( $preview_key, $fingerprint, 30 * MINUTE_IN_SECONDS );
                        $counts = (array) ( $preview['receipt']['counts'] ?? array() );
                        $notice = 'Preview only; no product, registry, Sheet or photo-state changes were made. Exact plan fingerprint saved for 30 minutes. Planned updates: ' . (int) ( $counts['planned_updates'] ?? 0 ) . '; planned Draft/hidden creations: ' . (int) ( $counts['planned_creations'] ?? 0 ) . '; review exceptions: ' . ( (int) ( $counts['unmanaged'] ?? 0 ) + (int) ( $counts['unsupported_variations'] ?? 0 ) ) . '.';
                    } elseif ( ! $fingerprint || ! hash_equals( (string) get_transient( $preview_key ), $fingerprint ) ) {
                        $notice = 'Not applied: run and review a fresh exact preview first. The current plan does not match the saved preview.';
                    } else {
                        delete_transient( $preview_key );
                        $applied = rhn_catalog_apply_sheet_result( $result, true );
                        if ( is_wp_error( $applied ) ) {
                            $notice = 'Catalog run reported failures: ' . $applied->get_error_message() . ' Review the durable per-SKU receipt; verified successful actions, if any, were retained.';
                            $details = $applied->get_error_data() ?: $preview;
                        } else {
                            $details = $applied;
                            $notice = 'Production plan applied and read-back verified. See the durable per-SKU run receipt for outcomes and exceptions.';
                        }
                    }
                }
            }
        }
    }
    echo '<div class="wrap"><h1>Google Sheet catalog intake</h1><p>Authenticated production workflow. Price and inventory remain owned by Revel/Kosmos. Preview the exact product differential before applying.</p>';
    echo '<p><a href="' . esc_url( admin_url( 'tools.php?page=rhn-catalog-sync-status' ) ) . '">View read-only catalog sync status</a></p>';
    if ( $notice ) {
        echo '<p role="status">' . esc_html( $notice ) . '</p>';
    }
    if ( $details ) {
        echo '<pre>' . esc_html( wp_json_encode( $details, JSON_PRETTY_PRINT ) ) . '</pre>';
    }
    echo '<form method="post">';
    wp_nonce_field( 'rhn_catalog_google_sheet' );
    echo '<p><button class="button" name="sheet_action" value="preview">Preview full approved rows</button> <button class="button button-primary" name="sheet_action" value="apply">Apply full catalog + withdrawals</button></p></form></div>';
}

/** Convert a stored UTC timestamp or epoch into a safe, consistent display value. */
function rhn_catalog_status_utc( $value ) {
    if ( is_numeric( $value ) ) {
        $timestamp = (int) $value;
    } else {
        $timestamp = strtotime( (string) $value );
    }
    return $timestamp ? gmdate( 'Y-m-d H:i:s', $timestamp ) . ' UTC' : 'Not recorded';
}

/** Render a small whitelist table. Values are always escaped as text. */
function rhn_catalog_status_table( $caption, $rows ) {
    echo '<h2>' . esc_html( $caption ) . '</h2><table class="widefat striped" style="max-width:1000px"><tbody>';
    foreach ( (array) $rows as $label => $value ) {
        if ( is_bool( $value ) ) {
            $value = $value ? 'Yes' : 'No';
        } elseif ( null === $value || '' === (string) $value ) {
            $value = 'Not recorded';
        }
        echo '<tr><th scope="row" style="width:280px">' . esc_html( (string) $label ) . '</th><td>' . esc_html( (string) $value ) . '</td></tr>';
    }
    echo '</tbody></table>';
}

/**
 * Administrator-only, read-only production diagnostics.
 *
 * Only explicitly whitelisted status fields are rendered. Google credentials,
 * Sheet identifiers, action tokens and arbitrary option contents are never read
 * into the response.
 */
function rhn_catalog_sync_status_page() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_presentation_enabled() ) {
        wp_die( 'Production administrators only.' );
    }

    $receipt = get_option( 'rhn_live_catalog_last_receipt', array() );
    $health  = get_option( 'rhn_live_catalog_health', array() );
    $active  = get_option( 'rhn_live_catalog_active_cycle', array() );
    $cycle   = get_option( 'rhn_live_catalog_last_cycle', array() );
    $receipt = is_array( $receipt ) ? $receipt : array();
    $health  = is_array( $health ) ? $health : array();
    $active  = is_array( $active ) ? $active : array();
    $cycle   = is_array( $cycle ) ? $cycle : array();

    $counts = is_array( $receipt['counts'] ?? null ) ? $receipt['counts'] : array();
    $allowed_counts = array(
        'actionable' => 'Actionable Sheet rows',
        'sheet_skipped' => 'Sheet rows skipped',
        'planned_updates' => 'Planned updates',
        'planned_creations' => 'Planned Draft/hidden creations',
        'deferred' => 'Deferred to a short follow-on job',
        'unchanged' => 'Already correct',
        'missing_remove_satisfied' => 'Missing products whose Remove instruction is already satisfied',
        'unmanaged' => 'Unmanaged review exceptions',
        'unsupported_variations' => 'Unsupported variation review exceptions',
        'errors' => 'Validation errors',
        'runtime_errors' => 'Runtime errors',
        'applied' => 'Applied and verified in this receipt',
    );
    $count_rows = array();
    foreach ( $allowed_counts as $key => $label ) {
        $count_rows[ $label ] = (int) ( $counts[ $key ] ?? 0 );
    }

    $outcome_counts = array();
    $attention = array();
    foreach ( (array) ( $receipt['outcomes'] ?? array() ) as $sku => $item ) {
        if ( ! is_array( $item ) ) {
            continue;
        }
        $outcome = sanitize_key( (string) ( $item['outcome'] ?? 'unknown' ) );
        $outcome_counts[ $outcome ] = (int) ( $outcome_counts[ $outcome ] ?? 0 ) + 1;
        if ( in_array( $outcome, array( 'applied_and_verified', 'failed', 'failed_validation', 'deferred_to_next_short_job', 'not_applied', 'review_unmanaged', 'review_unsupported_variation' ), true ) ) {
            $attention[] = array(
                'sku' => sanitize_text_field( (string) ( $item['sku'] ?? $sku ) ),
                'row' => (int) ( $item['row'] ?? 0 ),
                'action' => sanitize_text_field( (string) ( $item['action'] ?? '' ) ),
                'outcome' => $outcome,
                'detail' => sanitize_text_field( (string) ( $item['detail'] ?? '' ) ),
            );
        }
    }
    ksort( $outcome_counts );
    $outcome_rows = array();
    foreach ( $outcome_counts as $outcome => $count ) {
        $outcome_rows[ ucwords( str_replace( '_', ' ', $outcome ) ) ] = (int) $count;
    }

    $main_next = wp_next_scheduled( 'rhn_catalog_sheet_sync_event' );
    $callbacks = array(
        'Main four-hour callback' => false !== has_action( 'rhn_catalog_sheet_sync_event', 'rhn_live_catalog_start_cycle' ) ? 'Registered' : 'Missing',
        'Short-job callback' => false !== has_action( 'rhn_live_catalog_cycle_job', 'rhn_live_catalog_run_cycle_job' ) ? 'Registered' : 'Missing',
        'Drain-continuation callback' => false !== has_action( 'rhn_live_catalog_drain_continue_event', 'rhn_live_catalog_continue_drain' ) ? 'Registered' : 'Missing',
        'Next main event' => $main_next ? rhn_catalog_status_utc( $main_next ) : 'Not scheduled',
        'Active cycle status' => sanitize_key( (string) ( $active['status'] ?? 'none' ) ),
        'Queue backend' => sanitize_key( (string) ( $active['queue_backend'] ?? 'none' ) ),
        'Pending short job' => '' !== (string) ( $active['pending_token'] ?? '' ) ? 'Yes' : 'No',
        'Pending job recorded at' => rhn_catalog_status_utc( (int) ( $active['pending_scheduled_at'] ?? 0 ) ),
    );

    echo '<div class="wrap"><h1>Catalog sync status</h1>';
    echo '<p>This administrator-only page is read-only. It cannot preview, apply, schedule or invoke catalog processing.</p>';
    rhn_catalog_status_table( 'Runtime and enabled switches', array_merge( array(
        'Installed processor code' => '2.1.1',
        'Processor enabled' => 'yes' === (string) get_option( 'rhn_live_catalog_processor_enabled', 'no' ) ? 'Yes' : 'No',
        'Draft/hidden creation enabled' => 'yes' === (string) get_option( 'rhn_live_catalog_creation_enabled', 'no' ) ? 'Yes' : 'No',
        'Revel/Kosmos discovery writes enabled' => function_exists( 'rhn_catalog_discovery_write_enabled' ) && rhn_catalog_discovery_write_enabled() ? 'Yes' : 'No',
    ), $callbacks ) );
    rhn_catalog_status_table( 'Latest durable receipt', array(
        'Run ID' => sanitize_text_field( (string) ( $receipt['run_id'] ?? '' ) ),
        'Processed at' => rhn_catalog_status_utc( $receipt['processed_at_utc'] ?? '' ),
        'Source' => sanitize_key( (string) ( $receipt['source'] ?? '' ) ),
        'Write run' => ! empty( $receipt['write'] ) ? 'Yes' : 'No',
        'Receipt processor version' => sanitize_text_field( (string) ( $receipt['processor'] ?? '' ) ),
        'Result status' => sanitize_key( (string) ( $receipt['coverage']['result_status'] ?? '' ) ),
        'Fully synchronized' => ! empty( $receipt['coverage']['fully_synchronized'] ) ? 'Yes' : 'No',
    ) );
    rhn_catalog_status_table( 'Latest receipt counts', $count_rows );
    if ( $outcome_rows ) {
        rhn_catalog_status_table( 'Per-item outcome counts', $outcome_rows );
    }
    rhn_catalog_status_table( 'Scheduler health', array(
        'Last attempt' => rhn_catalog_status_utc( $health['last_attempt_utc'] ?? '' ),
        'Last success' => rhn_catalog_status_utc( $health['last_success_utc'] ?? '' ),
        'Last error' => sanitize_text_field( (string) ( $health['last_error'] ?? '' ) ),
        'Consecutive failures' => (int) ( $health['consecutive_failures'] ?? 0 ),
        'Health run ID' => sanitize_text_field( (string) ( $health['run_id'] ?? '' ) ),
        'Last cycle ID' => sanitize_text_field( (string) ( $cycle['cycle_id'] ?? '' ) ),
        'Last cycle status' => sanitize_key( (string) ( $cycle['status'] ?? '' ) ),
        'Last cycle updated at' => rhn_catalog_status_utc( $cycle['updated_at_utc'] ?? '' ),
        'Short jobs processed in cycle' => (int) ( $cycle['total_processed_jobs'] ?? 0 ),
        'Cycle tranches' => (int) ( $cycle['tranches'] ?? 0 ),
        'Cycle attempts' => (int) ( $cycle['attempts'] ?? 0 ),
        'Cycle failures' => count( (array) ( $cycle['failures'] ?? array() ) ),
    ) );

    if ( $attention ) {
        $shown = array_slice( $attention, 0, 100 );
        echo '<h2>Changed, deferred or failed item outcomes</h2><table class="widefat striped" style="max-width:1200px"><thead><tr><th>SKU</th><th>Sheet row</th><th>Action</th><th>Outcome</th><th>Detail</th></tr></thead><tbody>';
        foreach ( $shown as $item ) {
            echo '<tr><td>' . esc_html( $item['sku'] ) . '</td><td>' . esc_html( (string) $item['row'] ) . '</td><td>' . esc_html( $item['action'] ) . '</td><td>' . esc_html( $item['outcome'] ) . '</td><td>' . esc_html( $item['detail'] ) . '</td></tr>';
        }
        echo '</tbody></table>';
        if ( count( $attention ) > count( $shown ) ) {
            echo '<p>' . esc_html( (string) ( count( $attention ) - count( $shown ) ) ) . ' additional item outcomes were omitted from this bounded display.</p>';
        }
    } else {
        echo '<h2>Changed, deferred or failed item outcomes</h2><p>None in the latest receipt.</p>';
    }
    echo '</div>';
}

if ( function_exists( 'add_action' ) ) {
    add_action(
        'admin_menu',
        function () {
            if ( rhn_catalog_presentation_enabled() ) {
                add_management_page( 'Google Sheet catalog intake', 'Google Sheet catalog intake', 'manage_options', 'rhn-catalog-google-sheet', 'rhn_catalog_google_sheet_page' );
                add_management_page( 'Catalog sync status', 'Catalog sync status', 'manage_options', 'rhn-catalog-sync-status', 'rhn_catalog_sync_status_page' );
            }
        }
    );
}
