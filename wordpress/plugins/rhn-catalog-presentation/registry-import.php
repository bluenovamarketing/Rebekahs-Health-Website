<?php
/** Strict bulk intake; does not fetch content or write product records. */
defined( 'ABSPATH' ) || exit;

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
        if ( ! is_array( $row ) || array_diff( array_keys( $row ), array( 'sku', 'approved', 'source', 'name', 'description', 'short_description' ) ) ) {
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
        $fields = array_intersect_key( $row, array_flip( array( 'name', 'description', 'short_description' ) ) );
        if ( ! $fields ) {
            throw new InvalidArgumentException( 'Each record requires at least one presentation field.' );
        }
        foreach ( $fields as $key => $value ) {
            if ( ! is_string( $value ) || strlen( $value ) > 100000 || ( 'name' === $key && '' === trim( sanitize_text_field( $value ) ) ) ) {
                throw new InvalidArgumentException( 'Invalid presentation value.' );
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
