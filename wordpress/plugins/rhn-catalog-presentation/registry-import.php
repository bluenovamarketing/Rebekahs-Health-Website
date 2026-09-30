<?php
/** Strict bulk intake; does not fetch content or write product records. */
defined( 'ABSPATH' ) || exit;

function rhn_catalog_allowed_weight_units() {
    return array( 'oz', 'lb', 'g', 'kg' );
}

function rhn_catalog_validate_https_url( $url ) {
    if ( ! is_string( $url ) || strlen( $url ) > 2048 || 0 !== stripos( $url, 'https://' ) || false === filter_var( $url, FILTER_VALIDATE_URL ) ) {
        throw new InvalidArgumentException( 'Image URLs must be valid HTTPS URLs.' );
    }
}

function rhn_catalog_parse_registry( $json ) {
    if ( ! is_string( $json ) || strlen( $json ) > 2097152 ) {
        throw new InvalidArgumentException( 'Input must be JSON no larger than 2 MB.' );
    }
    $rows = json_decode( $json, true, 32, JSON_THROW_ON_ERROR );
    if ( ! is_array( $rows ) || ! array_is_list( $rows ) || count( $rows ) < 1 || count( $rows ) > 1000 ) {
        throw new InvalidArgumentException( 'Provide a list of 1–1000 records.' );
    }
    $result = array();
    foreach ( $rows as $row ) {
        $allowed = array(
            'sku', 'approved', 'source', 'name', 'description', 'short_description',
            'brand', 'categories', 'weight', 'weight_unit', 'package_size', 'featured_image_url',
            'gallery_image_urls', 'ingredients', 'allergens', 'supplement_facts',
            'directions', 'warnings', 'seo_title', 'seo_description',
        );
        if ( ! is_array( $row ) || array_diff( array_keys( $row ), $allowed ) ) {
            throw new InvalidArgumentException( 'Unsupported record fields.' );
        }
        $sku = $row['sku'] ?? null;
        if ( ! is_string( $sku ) || '' === trim( $sku ) || $sku !== trim( $sku ) || strlen( $sku ) > 100 ) {
            throw new InvalidArgumentException( 'Each SKU must be exact nonempty text; preserve leading zeroes.' );
        }
        if ( isset( $result[ $sku ] ) ) {
            throw new InvalidArgumentException( 'Duplicate SKU in this input.' );
        }
        if ( true !== ( $row['approved'] ?? null ) || ! is_string( $row['source'] ?? null ) || '' === trim( $row['source'] ) ) {
            throw new InvalidArgumentException( 'Each record requires explicit approval and a source reference.' );
        }
        $fields = array_intersect_key(
            $row,
            array_flip(
                array(
                    'name', 'description', 'short_description', 'brand', 'categories',
                    'weight', 'package_size', 'featured_image_url', 'gallery_image_urls', 'ingredients',
                    'allergens', 'supplement_facts', 'directions', 'warnings', 'seo_title',
                    'seo_description',
                )
            )
        );
        if ( ! $fields ) {
            throw new InvalidArgumentException( 'Each record requires at least one approved catalog field.' );
        }

        foreach ( array( 'name', 'description', 'short_description', 'brand', 'package_size', 'ingredients', 'allergens', 'supplement_facts', 'directions', 'warnings', 'seo_title', 'seo_description' ) as $key ) {
            if ( ! array_key_exists( $key, $row ) ) {
                continue;
            }
            $value = $row[ $key ];
            $limit = in_array( $key, array( 'name', 'brand', 'package_size' ), true ) ? 200 : ( in_array( $key, array( 'seo_title', 'seo_description' ), true ) ? 500 : 100000 );
            if ( ! is_string( $value ) || strlen( $value ) > $limit || ( in_array( $key, array( 'name', 'brand', 'package_size' ), true ) && '' === trim( sanitize_text_field( $value ) ) ) ) {
                throw new InvalidArgumentException( 'Invalid catalog text value.' );
            }
        }

        if ( array_key_exists( 'categories', $row ) ) {
            if ( ! is_array( $row['categories'] ) || ! array_is_list( $row['categories'] ) || count( $row['categories'] ) < 1 || count( $row['categories'] ) > 10 ) {
                throw new InvalidArgumentException( 'Categories must be a list of 1–10 approved names.' );
            }
            foreach ( $row['categories'] as $category ) {
                if ( ! is_string( $category ) || '' === trim( $category ) || strlen( $category ) > 200 ) {
                    throw new InvalidArgumentException( 'Invalid category name.' );
                }
            }
        }

        if ( array_key_exists( 'weight', $row ) ) {
            if ( ! is_string( $row['weight'] ) || ! is_numeric( $row['weight'] ) || (float) $row['weight'] <= 0 || (float) $row['weight'] > 100000 ) {
                throw new InvalidArgumentException( 'Weight must be positive numeric text.' );
            }
            if ( ! isset( $row['weight_unit'] ) || ! in_array( $row['weight_unit'], rhn_catalog_allowed_weight_units(), true ) ) {
                throw new InvalidArgumentException( 'Weight requires an approved unit.' );
            }
        } elseif ( array_key_exists( 'weight_unit', $row ) ) {
            throw new InvalidArgumentException( 'Weight unit cannot be supplied without weight.' );
        }

        if ( array_key_exists( 'featured_image_url', $row ) ) {
            rhn_catalog_validate_https_url( $row['featured_image_url'] );
        }
        if ( array_key_exists( 'gallery_image_urls', $row ) ) {
            if ( ! is_array( $row['gallery_image_urls'] ) || ! array_is_list( $row['gallery_image_urls'] ) || count( $row['gallery_image_urls'] ) > 10 ) {
                throw new InvalidArgumentException( 'Gallery images must be a list of no more than 10 URLs.' );
            }
            foreach ( $row['gallery_image_urls'] as $url ) {
                rhn_catalog_validate_https_url( $url );
            }
        }
        unset( $row['sku'] );
        $result[ $sku ] = $row;
    }
    return $result;
}

function rhn_catalog_registry_page() {
    if ( ! current_user_can( 'manage_options' ) || ! rhn_catalog_presentation_enabled() ) {
        wp_die( 'Staging administrators only.' );
    }
    $json = '';
    $notice = '';
    if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
        check_admin_referer( 'rhn_catalog_registry' );
        $json = wp_unslash( $_POST['registry_json'] ?? '' );
        try {
            if ( 'restore' === ( $_POST['registry_action'] ?? '' ) ) {
                $previous = get_option( 'rhn_catalog_presentation_previous', null );
                if ( ! is_array( $previous ) ) { throw new RuntimeException( 'No previous registry snapshot.' ); }
                update_option( 'rhn_catalog_presentation_overrides', $previous, false );
                if ( get_option( 'rhn_catalog_presentation_overrides' ) !== $previous ) { throw new RuntimeException( 'Registry restoration verification failed.' ); }
                $notice = 'Previous registry restored and verified. Product records were not changed.';
            } else {
            $incoming = rhn_catalog_parse_registry( $json );
            $existing = get_option( 'rhn_catalog_presentation_overrides', array() );
            if ( ! is_array( $existing ) ) { throw new RuntimeException( 'Existing registry is invalid; no changes made.' ); }
            $notice = count( $incoming ) . ' valid records; ' . count( array_intersect_key( $incoming, $existing ) ) . ' existing entries would be replaced.';
            if ( 'apply' === ( $_POST['registry_action'] ?? '' ) ) {
                // Recoverable registry snapshot; never delete unspecified records.
                update_option( 'rhn_catalog_presentation_previous', $existing, false );
                $merged = array_replace( $existing, $incoming );
                update_option( 'rhn_catalog_presentation_overrides', $merged, false );
                if ( get_option( 'rhn_catalog_presentation_overrides' ) !== $merged ) { throw new RuntimeException( 'Registry verification failed.' ); }
                $notice .= ' Saved and verified. Product records were not changed; rules apply on subsequent supported API writes.';
            }
            }
        } catch ( Throwable $error ) {
            $notice = 'Not applied: ' . $error->getMessage();
        }
    }
    echo '<div class="wrap"><h1>Staging catalog copy intake</h1><p>Approved source-referenced JSON only. Preview first. This does not generate copy, fetch sources, or change product records.</p>';
    if ( $notice ) { echo '<p role="status">' . esc_html( $notice ) . '</p>'; }
    echo '<form method="post">';
    wp_nonce_field( 'rhn_catalog_registry' );
    echo '<label for="registry_json">Approved record list (JSON)</label><br><textarea id="registry_json" name="registry_json" rows="18" cols="100">' . esc_textarea( $json ) . '</textarea><p><button class="button" name="registry_action" value="preview">Validate only</button> <button class="button" name="registry_action" value="apply">Apply approved registry</button> <button class="button" name="registry_action" value="restore">Restore previous registry</button></p></form></div>';
}

if ( function_exists( 'add_action' ) ) {
    add_action( 'admin_menu', function () {
        if ( rhn_catalog_presentation_enabled() ) {
            add_management_page( 'Staging catalog copy intake', 'Staging catalog copy intake', 'manage_options', 'rhn-catalog-registry', 'rhn_catalog_registry_page' );
        }
    } );
}
