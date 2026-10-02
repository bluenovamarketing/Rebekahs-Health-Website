<?php
/** One-time, read-only Google Drive photo intake for client-marked products. */
defined( 'ABSPATH' ) || exit;

function rhn_catalog_photo_folder_id() {
    if ( defined( 'RHN_CATALOG_GOOGLE_MEDIA_FOLDER_ID' ) && is_string( RHN_CATALOG_GOOGLE_MEDIA_FOLDER_ID ) ) {
        return RHN_CATALOG_GOOGLE_MEDIA_FOLDER_ID;
    }
    return (string) get_option( 'rhn_catalog_google_media_folder_id', '1AxIgYQFeo5ZUyFjU-CMpl8nAQ8NVpnxc' );
}

function rhn_catalog_google_sheet_values( $range ) {
    $sheet_id = rhn_catalog_google_sheet_id();
    $token = rhn_catalog_google_access_token();
    if ( '' === $sheet_id || is_wp_error( $token ) ) {
        return is_wp_error( $token ) ? $token : new WP_Error( 'rhn_photo_sheet', 'Google Sheet ID is not configured.' );
    }
    $url = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode( $sheet_id ) . '/values/' . rawurlencode( $range ) . '?majorDimension=ROWS';
    $response = wp_safe_remote_get( $url, array( 'timeout' => 20, 'headers' => array( 'Authorization' => 'Bearer ' . $token ) ) );
    if ( is_wp_error( $response ) ) {
        return $response;
    }
    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( 200 !== wp_remote_retrieve_response_code( $response ) || ! is_array( $body ) || ! isset( $body['values'] ) ) {
        return new WP_Error( 'rhn_photo_sheet', 'The client photo columns could not be read.' );
    }
    return $body['values'];
}

function rhn_catalog_photo_requests_from_rows( $rows ) {
    if ( ! is_array( $rows ) || count( $rows ) < 2 ) {
        return array();
    }
    $required = array(
        'SKU / Barcode — READ ONLY'       => 'sku',
        'Revel Product Name — READ ONLY'  => 'name',
        'Brand *'                         => 'brand',
        'Package Size / Net Contents *'   => 'package_size',
        'Photos Uploaded?'                => 'photos_uploaded',
        'Photo Status'                    => 'photo_status',
        'Photo / Source Notes'             => 'notes',
    );
    $indexes = array();
    foreach ( $rows[0] as $index => $heading ) {
        if ( isset( $required[ $heading ] ) ) {
            $indexes[ $required[ $heading ] ] = (int) $index;
        }
    }
    foreach ( array( 'sku', 'name', 'photos_uploaded' ) as $key ) {
        if ( ! isset( $indexes[ $key ] ) ) {
            return new WP_Error( 'rhn_photo_headers', 'Missing required Product Review photo heading: ' . $key );
        }
    }
    $requests = array();
    foreach ( array_slice( $rows, 1 ) as $cells ) {
        $get = static function ( $key ) use ( $cells, $indexes ) {
            return isset( $indexes[ $key ] ) ? trim( (string) ( $cells[ $indexes[ $key ] ] ?? '' ) ) : '';
        };
        $checked = strtolower( $get( 'photos_uploaded' ) );
        if ( ! in_array( $checked, array( 'true', 'yes', '1', 'checked' ), true ) ) {
            continue;
        }
        $sku = $get( 'sku' );
        if ( '' === $sku || isset( $requests[ $sku ] ) ) {
            continue;
        }
        $requests[ $sku ] = array(
            'sku'          => $sku,
            'name'         => $get( 'name' ),
            'brand'        => $get( 'brand' ),
            'package_size' => $get( 'package_size' ),
            'photo_status' => $get( 'photo_status' ),
            'notes'        => $get( 'notes' ),
        );
    }
    return $requests;
}

function rhn_catalog_drive_folder_files( $folder_id, $token, $depth = 0 ) {
    if ( $depth > 4 ) {
        return new WP_Error( 'rhn_photo_depth', 'The media folder is nested more than four levels deep.' );
    }
    $files = array();
    $page_token = '';
    do {
        $query = "'" . str_replace( "'", "\\'", $folder_id ) . "' in parents and trashed=false";
        $parameters = array(
            'q'        => $query,
            'fields'   => 'nextPageToken,files(id,name,mimeType,modifiedTime,md5Checksum,size)',
            'pageSize' => 1000,
        );
        if ( $page_token ) {
            $parameters['pageToken'] = $page_token;
        }
        $url = add_query_arg( $parameters, 'https://www.googleapis.com/drive/v3/files' );
        $response = wp_safe_remote_get( $url, array( 'timeout' => 30, 'headers' => array( 'Authorization' => 'Bearer ' . $token ) ) );
        if ( is_wp_error( $response ) ) {
            return $response;
        }
        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( 200 !== wp_remote_retrieve_response_code( $response ) || ! is_array( $body ) || ! isset( $body['files'] ) ) {
            return new WP_Error( 'rhn_photo_drive', 'The client media folder could not be listed.' );
        }
        foreach ( $body['files'] as $file ) {
            if ( 'application/vnd.google-apps.folder' === ( $file['mimeType'] ?? '' ) ) {
                $nested = rhn_catalog_drive_folder_files( (string) $file['id'], $token, $depth + 1 );
                if ( is_wp_error( $nested ) ) {
                    return $nested;
                }
                $files = array_merge( $files, $nested );
            } elseif ( preg_match( '#^image/(?:jpeg|png|webp)$#', (string) ( $file['mimeType'] ?? '' ) ) ) {
                $files[] = $file;
            }
        }
        $page_token = (string) ( $body['nextPageToken'] ?? '' );
    } while ( $page_token );
    return $files;
}

function rhn_catalog_photo_normalize( $value ) {
    $value = strtolower( remove_accents( (string) $value ) );
    return trim( preg_replace( '/[^a-z0-9]+/', ' ', $value ) );
}

function rhn_catalog_photo_match_files( $request, $files ) {
    $sku_digits = preg_replace( '/\D+/', '', (string) $request['sku'] );
    $name = rhn_catalog_photo_normalize( $request['name'] );
    $matches = array();
    foreach ( $files as $file ) {
        $file_name = rhn_catalog_photo_normalize( pathinfo( (string) ( $file['name'] ?? '' ), PATHINFO_FILENAME ) );
        $file_digits = preg_replace( '/\D+/', '', $file_name );
        $score = 0;
        $reason = '';
        if ( strlen( $sku_digits ) >= 6 && str_contains( $file_digits, $sku_digits ) ) {
            $score = 100;
            $reason = 'exact SKU/barcode in filename';
        } elseif ( strlen( $name ) >= 10 && str_contains( $file_name, $name ) ) {
            $score = 95;
            $reason = 'exact normalized product name in filename';
        }
        if ( $score >= 95 ) {
            $file['score'] = $score;
            $file['reason'] = $reason;
            $file['url'] = 'https://drive.google.com/file/d/' . rawurlencode( (string) $file['id'] ) . '/view';
            $matches[] = $file;
        }
    }
    usort(
        $matches,
        static function ( $left, $right ) {
            return ( $right['score'] <=> $left['score'] ) ?: strcmp( (string) $left['name'], (string) $right['name'] );
        }
    );
    return $matches;
}

function rhn_catalog_photo_folder_fingerprint( $files ) {
    $parts = array();
    foreach ( $files as $file ) {
        $parts[] = implode( '|', array( $file['id'] ?? '', $file['modifiedTime'] ?? '', $file['md5Checksum'] ?? '', $file['size'] ?? '' ) );
    }
    sort( $parts, SORT_STRING );
    return hash( 'sha256', implode( "\n", $parts ) );
}

function rhn_catalog_photo_intake( $result, $write_state = true ) {
    if ( ! rhn_catalog_google_private_credentials_configured() ) {
        $result['photo_intake'] = array( 'status' => 'disabled', 'message' => 'Private Google access is required.' );
        return $result;
    }
    $rows = rhn_catalog_google_sheet_values( "'Product Review'!A5:X" );
    if ( is_wp_error( $rows ) ) {
        $result['photo_intake'] = array( 'status' => 'error', 'message' => $rows->get_error_message() );
        return $result;
    }
    $requests = rhn_catalog_photo_requests_from_rows( $rows );
    if ( is_wp_error( $requests ) ) {
        $result['photo_intake'] = array( 'status' => 'error', 'message' => $requests->get_error_message() );
        return $result;
    }
    if ( ! $requests ) {
        $result['photo_intake'] = array( 'status' => 'idle', 'requested' => 0 );
        return $result;
    }
    $token = rhn_catalog_google_access_token();
    if ( is_wp_error( $token ) ) {
        $result['photo_intake'] = array( 'status' => 'error', 'message' => $token->get_error_message() );
        return $result;
    }
    $files = rhn_catalog_drive_folder_files( rhn_catalog_photo_folder_id(), $token );
    if ( is_wp_error( $files ) ) {
        $result['photo_intake'] = array( 'status' => 'error', 'message' => $files->get_error_message() );
        return $result;
    }
    $fingerprint = rhn_catalog_photo_folder_fingerprint( $files );
    $state = get_option( 'rhn_catalog_photo_intake_state', array() );
    $state = is_array( $state ) ? $state : array();
    $changed = array();
    foreach ( $requests as $sku => $request ) {
        if ( isset( $state[ $sku ] ) && hash_equals( (string) ( $state[ $sku ]['folder_fingerprint'] ?? '' ), $fingerprint ) ) {
            continue;
        }
        $matches = rhn_catalog_photo_match_files( $request, $files );
        $state[ $sku ] = array(
            'folder_fingerprint' => $fingerprint,
            'completed_at'       => current_time( 'mysql' ),
            'matched_files'      => array_map(
                static fn( $file ) => array_intersect_key( $file, array_flip( array( 'id', 'name', 'mimeType', 'modifiedTime', 'md5Checksum', 'size', 'url', 'reason' ) ) ),
                $matches
            ),
            'status'             => $matches ? 'matched' : 'needs-review',
            'exception'          => $matches ? '' : 'No exact SKU or product-name filename match; manual visual identification is required.',
        );
        $changed[ $sku ] = $state[ $sku ];
    }
    if ( $write_state ) {
        update_option( 'rhn_catalog_photo_intake_state', $state, false );
    }

    foreach ( (array) ( $result['registry'] ?? array() ) as $sku => &$entry ) {
        $matched = (array) ( $state[ $sku ]['matched_files'] ?? array() );
        if ( ! $matched ) {
            continue;
        }
        $urls = array_values( array_filter( array_column( $matched, 'url' ) ) );
        if ( $urls && empty( $entry['featured_image_url'] ) ) {
            $entry['featured_image_url'] = array_shift( $urls );
        }
        if ( $urls && empty( $entry['gallery_image_urls'] ) ) {
            $entry['gallery_image_urls'] = $urls;
        }
    }
    unset( $entry );

    if ( $write_state && $changed ) {
        $digest = array(
            'created_at' => current_time( 'mysql' ),
            'matched'    => array_keys( array_filter( $changed, static fn( $item ) => 'matched' === $item['status'] ) ),
            'review'     => array_keys( array_filter( $changed, static fn( $item ) => 'needs-review' === $item['status'] ) ),
        );
        update_option( 'rhn_catalog_photo_last_digest', $digest, false );
        if ( (bool) get_option( 'rhn_catalog_photo_email_enabled', false ) && function_exists( 'wp_mail' ) ) {
            $recipient = sanitize_email( (string) get_option( 'rhn_catalog_photo_notification_recipient', get_option( 'admin_email', '' ) ) );
            if ( $recipient ) {
                $body = 'Catalog photo intake completed.' . "\n\nMatched: " . implode( ', ', $digest['matched'] ) . "\nNeeds review: " . implode( ', ', $digest['review'] );
                wp_mail( $recipient, 'Rebekah catalog photo intake', $body );
            }
        }
    }
    $result['photo_intake'] = array(
        'status'          => $changed ? 'processed' : 'unchanged',
        'requested'       => count( $requests ),
        'folder_images'   => count( $files ),
        'newly_processed' => count( $changed ),
        'matched'         => count( array_filter( $state, static fn( $item, $sku ) => isset( $requests[ $sku ] ) && 'matched' === ( $item['status'] ?? '' ), ARRAY_FILTER_USE_BOTH ) ),
        'needs_review'    => count( array_filter( $state, static fn( $item, $sku ) => isset( $requests[ $sku ] ) && 'needs-review' === ( $item['status'] ?? '' ), ARRAY_FILTER_USE_BOTH ) ),
    );
    return $result;
}

function rhn_catalog_photo_admin_notice() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_presentation_enabled() ) {
        return;
    }
    $digest = get_option( 'rhn_catalog_photo_last_digest', array() );
    if ( ! is_array( $digest ) || empty( $digest['created_at'] ) ) {
        return;
    }
    $matched = count( (array) ( $digest['matched'] ?? array() ) );
    $review = count( (array) ( $digest['review'] ?? array() ) );
    echo '<div class="notice notice-info"><p><strong>Catalog photo intake:</strong> ' . esc_html( $matched ) . ' product(s) matched automatically; ' . esc_html( $review ) . ' need visual review. Completed ' . esc_html( (string) $digest['created_at'] ) . '.</p></div>';
}

if ( function_exists( 'add_action' ) ) {
    add_action( 'admin_notices', 'rhn_catalog_photo_admin_notice' );
}
