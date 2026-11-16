<?php
define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );

class WP_Error {
    private $code;
    private $message;
    private $data;
    public function __construct( $code, $message, $data = null ) {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }
    public function get_error_code() { return $this->code; }
    public function get_error_message() { return $this->message; }
    public function get_error_data( $code = '' ) { return $this->data; }
}
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function sanitize_text_field( $value ) { return trim( strip_tags( (string) $value ) ); }
function sanitize_title( $value ) {
    $value = strtolower( sanitize_text_field( $value ) );
    $value = preg_replace( '/[^a-z0-9]+/', '-', $value );
    return trim( $value, '-' );
}
function taxonomy_exists( $taxonomy ) { return in_array( $taxonomy, array( 'product_brand', 'product_cat' ), true ); }

$GLOBALS['brand_test_terms'] = array(
    'product_brand' => array( 21 => 'Rebekah’s Private Label' ),
    'product_cat'   => array( 11 => 'Immune Support' ),
);
$GLOBALS['brand_test_insertions'] = array();

function get_term_by( $field, $value, $taxonomy ) {
    foreach ( $GLOBALS['brand_test_terms'][ $taxonomy ] ?? array() as $id => $name ) {
        if ( ( 'name' === $field && $name === $value ) || ( 'slug' === $field && sanitize_title( $name ) === $value ) ) {
            return (object) array( 'term_id' => $id, 'name' => $name );
        }
    }
    return false;
}
function get_terms( $args ) {
    $taxonomy = (string) ( $args['taxonomy'] ?? '' );
    $terms = array();
    foreach ( $GLOBALS['brand_test_terms'][ $taxonomy ] ?? array() as $id => $name ) {
        $terms[] = (object) array( 'term_id' => $id, 'name' => $name );
    }
    return $terms;
}
function wp_insert_term( $name, $taxonomy, $args = array() ) {
    $existing = get_term_by( 'slug', sanitize_title( $name ), $taxonomy );
    if ( $existing ) {
        return new WP_Error( 'term_exists', 'Term already exists.', $existing->term_id );
    }
    $ids = array_keys( $GLOBALS['brand_test_terms'][ $taxonomy ] ?? array() );
    $id = $ids ? max( $ids ) + 1 : 1;
    $GLOBALS['brand_test_terms'][ $taxonomy ][ $id ] = $name;
    $GLOBALS['brand_test_insertions'][] = array( 'id' => $id, 'name' => $name, 'taxonomy' => $taxonomy );
    return array( 'term_id' => $id, 'term_taxonomy_id' => $id );
}

require dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'isolated-plugin' . DIRECTORY_SEPARATOR . 'catalog-fields.php';

$checks = 0;
function brand_check( $condition, $message ) {
    global $checks;
    if ( ! $condition ) {
        throw new RuntimeException( 'FAIL: ' . $message );
    }
    $checks++;
}

$created = rhn_catalog_resolve_term_ids( array( 'Herb Pharm' ), 'product_brand' );
brand_check( array( 22 ) === $created, 'a genuinely missing approved brand is created' );
brand_check( 1 === count( $GLOBALS['brand_test_insertions'] ), 'the new brand is inserted once' );

$reused = rhn_catalog_resolve_term_ids( array( 'HERB PHARM' ), 'product_brand' );
brand_check( array( 22 ) === $reused, 'case-only variation reuses the existing brand slug' );
brand_check( 1 === count( $GLOBALS['brand_test_insertions'] ), 'case-only variation does not create a duplicate' );

$near = rhn_catalog_resolve_term_ids( array( 'Herb Pharn' ), 'product_brand' );
brand_check( is_wp_error( $near ) && 'rhn_ambiguous_brand' === $near->get_error_code(), 'one-character typo fails closed instead of creating a duplicate brand' );
brand_check( 1 === count( $GLOBALS['brand_test_insertions'] ), 'typo created no term' );

$missing_category = rhn_catalog_resolve_term_ids( array( 'Invented Category' ), 'product_cat' );
brand_check( is_wp_error( $missing_category ) && 'rhn_missing_term' === $missing_category->get_error_code(), 'categories remain strict and are never auto-created' );
brand_check( 1 === count( $GLOBALS['brand_test_insertions'] ), 'category failure created no term' );

echo 'PASS brand term resolution: ' . $checks . " assertions.\n";
