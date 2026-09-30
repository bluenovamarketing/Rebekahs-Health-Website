<?php
/** Apply approved website-owned fields while leaving POS-owned fields untouched. */
defined( 'ABSPATH' ) || exit;

function rhn_catalog_registry() {
    $registry = get_option( 'rhn_catalog_presentation_overrides', array() );
    return is_array( $registry ) ? $registry : array();
}

function rhn_catalog_entry_for_product( $product ) {
    if ( ! $product instanceof WC_Product ) {
        return null;
    }
    $sku = (string) $product->get_sku( 'edit' );
    $registry = rhn_catalog_registry();
    $entry = isset( $registry[ $sku ] ) ? $registry[ $sku ] : null;
    if ( ! is_array( $entry ) || true !== ( $entry['approved'] ?? false ) || ! is_string( $entry['source'] ?? null ) || '' === trim( $entry['source'] ) ) {
        return null;
    }
    return $entry;
}

function rhn_catalog_resolve_term_ids( $names, $taxonomy ) {
    if ( ! taxonomy_exists( $taxonomy ) ) {
        return new WP_Error( 'rhn_missing_taxonomy', 'Required taxonomy is unavailable: ' . $taxonomy );
    }
    $ids = array();
    foreach ( $names as $name ) {
        $term = get_term_by( 'name', $name, $taxonomy );
        if ( ! $term || is_wp_error( $term ) ) {
            return new WP_Error( 'rhn_missing_term', 'Approved term is not installed: ' . $name );
        }
        $ids[] = (int) $term->term_id;
    }
    return array_values( array_unique( $ids ) );
}

function rhn_catalog_convert_weight( $weight, $from_unit ) {
    $store_unit = (string) get_option( 'woocommerce_weight_unit', 'oz' );
    if ( function_exists( 'wc_get_weight' ) ) {
        // The Sheet uses the familiar singular `lb`; WooCommerce's converter
        // requires its internal `lbs` token and otherwise treats it as kg.
        $wc_from_unit = 'lb' === $from_unit ? 'lbs' : $from_unit;
        $wc_store_unit = 'lb' === $store_unit ? 'lbs' : $store_unit;
        return (string) round( (float) wc_get_weight( (float) $weight, $wc_store_unit, $wc_from_unit ), 4 );
    }
    $ounces = array( 'oz' => 1.0, 'lb' => 16.0, 'g' => 0.0352739619, 'kg' => 35.2739619 );
    if ( ! isset( $ounces[ $from_unit ], $ounces[ $store_unit ] ) ) {
        return new WP_Error( 'rhn_weight_unit', 'Unsupported weight conversion.' );
    }
    return (string) round( ( (float) $weight * $ounces[ $from_unit ] ) / $ounces[ $store_unit ], 6 );
}

function rhn_catalog_apply_entry_to_product( $product, $request = null ) {
    $entry = rhn_catalog_entry_for_product( $product );
    if ( ! $entry ) {
        return $product;
    }

    $errors = array();
    $source = $product->get_meta( '_rhn_source_presentation', true, 'edit' );
    $source = is_array( $source ) ? $source : array();
    foreach ( array( 'name', 'description', 'short_description' ) as $field ) {
        if ( $request instanceof WP_REST_Request && $request->has_param( $field ) && is_string( $request->get_param( $field ) ) ) {
            $source[ $field ] = 'name' === $field ? sanitize_text_field( $request->get_param( $field ) ) : wp_kses_post( $request->get_param( $field ) );
        }
        if ( ! array_key_exists( $field, $entry ) || ! is_string( $entry[ $field ] ) ) {
            continue;
        }
        $value = 'name' === $field ? sanitize_text_field( $entry[ $field ] ) : wp_kses_post( $entry[ $field ] );
        if ( 'name' === $field && '' === trim( $value ) ) {
            continue;
        }
        $setter = 'set_' . $field;
        $product->$setter( $value );
    }

    if ( isset( $entry['weight'], $entry['weight_unit'] ) ) {
        $weight = rhn_catalog_convert_weight( $entry['weight'], $entry['weight_unit'] );
        if ( is_wp_error( $weight ) ) {
            $errors[] = $weight->get_error_message();
        } else {
            $product->set_weight( $weight );
            $product->update_meta_data( '_rhn_catalog_source_weight', array( 'value' => $entry['weight'], 'unit' => $entry['weight_unit'] ) );
        }
    }

    if ( isset( $entry['categories'] ) ) {
        $category_ids = rhn_catalog_resolve_term_ids( $entry['categories'], 'product_cat' );
        if ( is_wp_error( $category_ids ) ) {
            $errors[] = $category_ids->get_error_message();
        } else {
            $product->set_category_ids( $category_ids );
        }
    }

    $meta_fields = array(
        'package_size'     => '_rhn_package_size',
        'ingredients'      => '_rhn_ingredients',
        'allergens'        => '_rhn_allergens',
        'supplement_facts' => '_rhn_supplement_facts',
        'directions'       => '_rhn_directions',
        'warnings'         => '_rhn_warnings',
        'seo_title'        => '_rhn_seo_title',
        'seo_description'  => '_rhn_seo_description',
    );
    foreach ( $meta_fields as $field => $meta_key ) {
        if ( array_key_exists( $field, $entry ) && is_string( $entry[ $field ] ) ) {
            $value = in_array( $field, array( 'seo_title', 'seo_description' ), true ) ? sanitize_text_field( $entry[ $field ] ) : wp_kses_post( $entry[ $field ] );
            $product->update_meta_data( $meta_key, $value );
            if ( 'seo_title' === $field ) {
                $product->update_meta_data( '_seopress_titles_title', $value );
            } elseif ( 'seo_description' === $field ) {
                $product->update_meta_data( '_seopress_titles_desc', $value );
            }
        }
    }
    if ( array_key_exists( 'directions', $entry ) || array_key_exists( 'warnings', $entry ) ) {
        $combined = trim( (string) ( $entry['directions'] ?? '' ) . "\n\n" . (string) ( $entry['warnings'] ?? '' ) );
        $product->update_meta_data( '_rhn_directions_warnings', wp_kses_post( $combined ) );
    }

    $product->update_meta_data( '_rhn_source_presentation', $source );
    $product->update_meta_data( '_rhn_catalog_approved_source', sanitize_text_field( $entry['source'] ) );
    if ( $errors ) {
        $product->update_meta_data( '_rhn_catalog_sync_errors', array_values( array_unique( $errors ) ) );
    } else {
        $product->delete_meta_data( '_rhn_catalog_sync_errors' );
    }
    return $product;
}

function rhn_catalog_allowed_image_host( $host ) {
    $host = strtolower( (string) $host );
    return in_array( $host, array( 'drive.google.com', 'drive.usercontent.google.com', 'lh3.googleusercontent.com', 'dropbox.com', 'www.dropbox.com', 'dl.dropboxusercontent.com' ), true );
}

function rhn_catalog_download_url( $url ) {
    $host = wp_parse_url( $url, PHP_URL_HOST );
    if ( ! rhn_catalog_allowed_image_host( $host ) ) {
        return new WP_Error( 'rhn_image_host', 'Image host is not approved.' );
    }
    if ( 'drive.google.com' === strtolower( (string) $host ) && preg_match( '#/file/d/([A-Za-z0-9_-]+)#', $url, $match ) ) {
        return 'https://drive.google.com/uc?export=download&id=' . rawurlencode( $match[1] );
    }
    if ( in_array( strtolower( (string) $host ), array( 'dropbox.com', 'www.dropbox.com' ), true ) ) {
        return add_query_arg( 'dl', '1', remove_query_arg( 'dl', $url ) );
    }
    return $url;
}

function rhn_catalog_google_drive_file_id( $url ) {
    if ( preg_match( '#/file/d/([A-Za-z0-9_-]+)#', $url, $match ) ) {
        return $match[1];
    }
    $query = wp_parse_url( $url, PHP_URL_QUERY );
    if ( is_string( $query ) ) {
        parse_str( $query, $parts );
        if ( isset( $parts['id'] ) && preg_match( '/^[A-Za-z0-9_-]+$/', $parts['id'] ) ) {
            return $parts['id'];
        }
    }
    return '';
}

function rhn_catalog_download_image_file( $url ) {
    $host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
    $file_id = 'drive.google.com' === $host ? rhn_catalog_google_drive_file_id( $url ) : '';
    if ( $file_id && function_exists( 'rhn_catalog_google_access_token' ) && defined( 'RHN_CATALOG_GOOGLE_CREDENTIALS_PATH' ) ) {
        $token = rhn_catalog_google_access_token();
        if ( ! is_wp_error( $token ) ) {
            $temporary = wp_tempnam( $url );
            if ( ! $temporary ) {
                return new WP_Error( 'rhn_image_temp', 'Could not create a temporary image file.' );
            }
            $response = wp_safe_remote_get(
                'https://www.googleapis.com/drive/v3/files/' . rawurlencode( $file_id ) . '?alt=media',
                array(
                    'timeout'             => 30,
                    'headers'             => array( 'Authorization' => 'Bearer ' . $token ),
                    'stream'              => true,
                    'filename'            => $temporary,
                    'limit_response_size' => 10485761,
                )
            );
            if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
                @unlink( $temporary );
                return is_wp_error( $response ) ? $response : new WP_Error( 'rhn_image_download', 'Authenticated Google Drive image download failed.' );
            }
            return $temporary;
        }
    }
    $download = rhn_catalog_download_url( $url );
    return is_wp_error( $download ) ? $download : download_url( $download, 30 );
}

function rhn_catalog_import_image( $url, $product_id ) {
    $download = rhn_catalog_download_url( $url );
    if ( is_wp_error( $download ) ) {
        return $download;
    }
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $temporary = rhn_catalog_download_image_file( $url );
    if ( is_wp_error( $temporary ) ) {
        return $temporary;
    }
    try {
        $size = filesize( $temporary );
        $image = @getimagesize( $temporary );
        $types = array( IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp' );
        if ( false === $size || $size < 1 || $size > 10485760 || ! is_array( $image ) || ! isset( $types[ $image[2] ] ) ) {
            return new WP_Error( 'rhn_image_invalid', 'Image must be JPEG, PNG or WebP and no larger than 10 MB.' );
        }
        $file = array(
            'name'     => 'rhn-catalog-' . (int) $product_id . '-' . substr( hash( 'sha256', $url ), 0, 12 ) . '.' . $types[ $image[2] ],
            'tmp_name' => $temporary,
        );
        $attachment_id = media_handle_sideload( $file, $product_id, 'Approved product image' );
        if ( is_wp_error( $attachment_id ) ) {
            return $attachment_id;
        }
        update_post_meta( $attachment_id, '_rhn_catalog_source_url', esc_url_raw( $url ) );
        $temporary = '';
        return (int) $attachment_id;
    } finally {
        if ( $temporary && file_exists( $temporary ) ) {
            @unlink( $temporary );
        }
    }
}

function rhn_catalog_after_save( $product, $request = null, $creating = false ) {
    static $running = false;
    if ( ! $product instanceof WC_Product && is_object( $product ) && isset( $product->ID ) ) {
        $product = wc_get_product( (int) $product->ID );
    }
    if ( $running || ! rhn_catalog_presentation_enabled() || ! $product instanceof WC_Product ) {
        return;
    }
    $entry = rhn_catalog_entry_for_product( $product );
    if ( ! $entry ) {
        return;
    }
    $running = true;
    $product_id = $product->get_id();
    $errors = (array) get_post_meta( $product_id, '_rhn_catalog_sync_errors', true );
    try {
        if ( isset( $entry['brand'] ) ) {
            $brand_ids = rhn_catalog_resolve_term_ids( array( $entry['brand'] ), 'product_brand' );
            if ( is_wp_error( $brand_ids ) ) {
                $errors[] = $brand_ids->get_error_message();
            } else {
                wp_set_object_terms( $product_id, $brand_ids, 'product_brand', false );
            }
        }

        if ( isset( $entry['featured_image_url'] ) ) {
            $current_source = (string) $product->get_meta( '_rhn_featured_image_source_url', true, 'edit' );
            if ( $current_source !== $entry['featured_image_url'] || ! $product->get_image_id( 'edit' ) ) {
                $attachment_id = rhn_catalog_import_image( $entry['featured_image_url'], $product_id );
                if ( is_wp_error( $attachment_id ) ) {
                    $errors[] = 'Featured image: ' . $attachment_id->get_error_message();
                } else {
                    set_post_thumbnail( $product_id, $attachment_id );
                    update_post_meta( $product_id, '_rhn_featured_image_source_url', esc_url_raw( $entry['featured_image_url'] ) );
                }
            }
        }

        if ( isset( $entry['gallery_image_urls'] ) ) {
            $current_sources = (array) $product->get_meta( '_rhn_gallery_image_source_urls', true, 'edit' );
            $current_ids = (array) $product->get_gallery_image_ids( 'edit' );
            if ( $current_sources !== $entry['gallery_image_urls'] || count( $current_ids ) !== count( $entry['gallery_image_urls'] ) ) {
                $gallery_ids = array();
                foreach ( $entry['gallery_image_urls'] as $url ) {
                    $attachment_id = rhn_catalog_import_image( $url, $product_id );
                    if ( is_wp_error( $attachment_id ) ) {
                        $errors[] = 'Gallery image: ' . $attachment_id->get_error_message();
                    } else {
                        $gallery_ids[] = $attachment_id;
                    }
                }
                if ( count( $gallery_ids ) === count( $entry['gallery_image_urls'] ) ) {
                    update_post_meta( $product_id, '_product_image_gallery', implode( ',', array_map( 'intval', $gallery_ids ) ) );
                    update_post_meta( $product_id, '_rhn_gallery_image_source_urls', array_map( 'esc_url_raw', $entry['gallery_image_urls'] ) );
                }
            }
        }

        if ( $errors ) {
            update_post_meta( $product_id, '_rhn_catalog_sync_errors', array_values( array_unique( $errors ) ) );
        } else {
            delete_post_meta( $product_id, '_rhn_catalog_sync_errors' );
        }
        // This hook runs after WooCommerce has already saved the product. A
        // second WC_Product::save() here re-enters the entire product-save
        // pipeline and can tie up PHP workers during imports. Persist only the
        // post-save taxonomy/image metadata above, then refresh caches.
        clean_post_cache( $product_id );
        if ( function_exists( 'wc_delete_product_transients' ) ) {
            wc_delete_product_transients( $product_id );
        }
    } finally {
        $running = false;
    }
}
