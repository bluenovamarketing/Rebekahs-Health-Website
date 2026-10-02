<?php
/** Isolated publication/withdrawal tests; no WordPress, WooCommerce or network writes. */
define( 'ABSPATH', __DIR__ );
$options = array();
function get_option( $key, $default = false ) { global $options; return $options[ $key ] ?? $default; }
function update_option( $key, $value, $autoload = null ) { global $options; $options[ $key ] = $value; return true; }
function current_time( $type ) { return '2026-10-02 12:00:00'; }
function get_posts( $args ) { return array( 55 ); }
function wc_get_product( $id ) { return $GLOBALS['scheduler_product']; }
function clean_post_cache( $id ) {}
function taxonomy_exists( $taxonomy ) { return false; }
function wp_get_object_terms( $id, $taxonomy, $args ) { return array(); }
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function rhn_catalog_nac_meta_keys() { return array( '_rhn_package_size' ); }
function rhn_catalog_apply_entry_to_product( $product, $request = null ) { $product->set_name( 'Approved website name' ); return $product; }
function rhn_catalog_after_save( $product, $request = null, $creating = false ) {}
class WP_Error {}
class Scheduler_Product {
    private $values = array(
        'name' => 'Original name', 'description' => '', 'short_description' => '', 'weight' => '',
        'category_ids' => array(), 'image_id' => 0, 'gallery_image_ids' => array(),
        'regular_price' => '27.00', 'sale_price' => '', 'price' => '27.00', 'stock_quantity' => 7,
        'stock_status' => 'instock', 'manage_stock' => true, 'backorders' => 'no',
        'sku' => '733739401854', 'status' => 'draft', 'catalog_visibility' => 'hidden',
    );
    private $meta = array();
    function get_id() { return 55; }
    function save() { return 55; }
    function meta_exists( $key ) { return array_key_exists( $key, $this->meta ); }
    function get_meta( $key, $single = true, $context = 'view' ) { return $this->meta[ $key ] ?? ''; }
    function update_meta_data( $key, $value ) { $this->meta[ $key ] = $value; }
    function delete_meta_data( $key ) { unset( $this->meta[ $key ] ); }
    function set_name( $value ) { $this->values['name'] = $value; }
    function set_status( $value ) { $this->values['status'] = $value; }
    function set_catalog_visibility( $value ) { $this->values['catalog_visibility'] = $value; }
    function __call( $name, $arguments ) {
        if ( str_starts_with( $name, 'get_' ) ) {
            return $this->values[ substr( $name, 4 ) ];
        }
        throw new BadMethodCallException( $name );
    }
}
require __DIR__ . '/../catalog-scheduler.php';
$checks = 0;
function scheduler_check( $condition, $message ) {
    global $checks;
    if ( ! $condition ) { throw new RuntimeException( $message ); }
    $checks++;
}

$scheduler_product = new Scheduler_Product();
$published = rhn_catalog_apply_registry_entry_to_staging( '733739401854' );
scheduler_check( 'publish' === $published['status'] && 'visible' === $published['visibility'], 'Approved row publishes and shows the exact staging product.' );
scheduler_check( '27.00' === $scheduler_product->get_regular_price( 'edit' ) && 7 === $scheduler_product->get_stock_quantity( 'edit' ), 'Publishing preserves price and inventory.' );
scheduler_check( '' !== $scheduler_product->get_meta( '_rhn_catalog_published_by_sheet', true, 'edit' ), 'Publishing records a staging audit timestamp.' );

$withdrawn = rhn_catalog_withdraw_staging_product( '733739401854' );
scheduler_check( 'draft' === $withdrawn['status'] && 'hidden' === $withdrawn['visibility'], 'Remove from Website drafts and hides the exact staging product.' );
scheduler_check( '' !== $scheduler_product->get_meta( '_rhn_catalog_withdrawn_by_sheet', true, 'edit' ), 'Withdrawal records a staging audit timestamp.' );

$republished = rhn_catalog_apply_registry_entry_to_staging( '733739401854' );
scheduler_check( 'publish' === $republished['status'] && 'visible' === $republished['visibility'], 'Re-approval restores Published/visible.' );
scheduler_check( '' === $scheduler_product->get_meta( '_rhn_catalog_withdrawn_by_sheet', true, 'edit' ), 'Re-approval clears the withdrawal marker.' );

echo "PASS: $checks isolated publication and withdrawal checks.\n";
