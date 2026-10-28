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
            'directions', 'warnings', 'seo_title', 'seo_description', 'sheet_row',
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
        if ( isset( $row['sheet_row'] ) && ( ! is_int( $row['sheet_row'] ) || $row['sheet_row'] < 2 ) ) {
            throw new InvalidArgumentException( 'Sheet row provenance must be a positive data-row number.' );
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

