<?php
/** Isolated publication/withdrawal tests; no WordPress, WooCommerce or network writes. */
define( 'ABSPATH', __DIR__ );
define( 'HOUR_IN_SECONDS', 3600 );
define( 'MINUTE_IN_SECONDS', 60 );
$options = array();
function get_option( $key, $default = false ) { global $options; return $options[ $key ] ?? $default; }
function update_option( $key, $value, $autoload = null ) { global $options; $options[ $key ] = $value; return true; }
function current_time( $type ) { return '2026-10-02 12:00:00'; }
function get_posts( $args ) { $GLOBALS['scheduler_get_posts_args'] = $args; return array( 55 ); }
function wc_get_product( $id ) { return $GLOBALS['scheduler_product']; }
function clean_post_cache( $id ) {}
function taxonomy_exists( $taxonomy ) { return false; }
function wp_get_object_terms( $id, $taxonomy, $args ) { return array(); }
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function rhn_catalog_batch_guarded() { return true; }
function rhn_catalog_convert_weight( $weight, $unit ) { return 'oz' === $unit ? (string) $weight : new WP_Error(); }
function rhn_catalog_nac_meta_keys() { return array( '_rhn_catalog_source_weight', '_rhn_package_size' ); }
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
    private $save_count = 0;
    private $type = 'simple';
    function __construct( $type = 'simple' ) {
        $this->type = $type;
        if ( 'variation' === $type ) {
            $this->values['catalog_visibility'] = 'visible';
        }
    }
    function get_id() { return 55; }
    function is_type( $type ) { return $this->type === $type; }
    function save() { $this->save_count++; return 55; }
    function get_save_count() { return $this->save_count; }
    function meta_exists( $key ) { return array_key_exists( $key, $this->meta ); }
    function get_meta( $key, $single = true, $context = 'view' ) { return $this->meta[ $key ] ?? ''; }
    function update_meta_data( $key, $value ) { $this->meta[ $key ] = $value; }
    function delete_meta_data( $key ) { unset( $this->meta[ $key ] ); }
    function set_name( $value ) { $this->values['name'] = $value; }
    function set_status( $value ) { $this->values['status'] = $value; }
    function set_catalog_visibility( $value ) { $this->values['catalog_visibility'] = $value; }
    function set_weight( $value ) { $this->values['weight'] = (string) $value; }
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

scheduler_check( rhn_catalog_sync_collides_with_kosmos( strtotime( '2026-10-08 00:01:30 UTC' ) ), 'A 00:01 UTC catalog run is recognized as overlapping Kosmos.' );
scheduler_check( rhn_catalog_sync_collides_with_kosmos( strtotime( '2026-10-08 11:55:00 UTC' ) ), 'A catalog run just before the 12:00 UTC Kosmos window is recognized as overlapping.' );
scheduler_check( ! rhn_catalog_sync_collides_with_kosmos( strtotime( '2026-10-08 02:30:00 UTC' ) ), 'The 02:30 UTC catalog anchor is outside the Kosmos windows.' );
scheduler_check( strtotime( '2026-10-07 22:30:00 UTC' ) === rhn_catalog_next_staggered_run( strtotime( '2026-10-07 21:40:00 UTC' ) ), 'The scheduler selects the next fixed four-hour UTC anchor.' );

$scheduler_product = new Scheduler_Product();
$published = rhn_catalog_apply_registry_entry_to_staging( '733739401854' );
scheduler_check( 'publish' === $published['status'] && 'visible' === $published['visibility'], 'Approved row publishes and shows the exact staging product.' );
scheduler_check( array( 'product', 'product_variation' ) === $GLOBALS['scheduler_get_posts_args']['post_type'], 'Exact-SKU lookup covers both WooCommerce products and variations.' );
scheduler_check( '27.00' === $scheduler_product->get_regular_price( 'edit' ) && 7 === $scheduler_product->get_stock_quantity( 'edit' ), 'Publishing preserves price and inventory.' );
scheduler_check( '' !== $scheduler_product->get_meta( '_rhn_catalog_published_by_sheet', true, 'edit' ), 'Publishing records a staging audit timestamp.' );

$withdrawn = rhn_catalog_withdraw_staging_product( '733739401854' );
scheduler_check( 'draft' === $withdrawn['status'] && 'hidden' === $withdrawn['visibility'], 'Remove from Website drafts and hides the exact staging product.' );
scheduler_check( '' !== $scheduler_product->get_meta( '_rhn_catalog_withdrawn_by_sheet', true, 'edit' ), 'Withdrawal records a staging audit timestamp.' );
$withdrawn_save_count = $scheduler_product->get_save_count();
$withdrawn_repeat = rhn_catalog_withdraw_staging_product( '733739401854' );
scheduler_check( ! empty( $withdrawn_repeat['unchanged'] ) && $withdrawn_save_count === $scheduler_product->get_save_count(), 'Repeating an already verified withdrawal performs no additional product save.' );

$scheduler_product = new Scheduler_Product( 'variation' );
$variation_withdrawn = rhn_catalog_withdraw_staging_product( '733739401854' );
scheduler_check( 'draft' === $variation_withdrawn['status'] && 'inherited' === $variation_withdrawn['visibility'], 'A variation is withdrawn by Draft status without requiring unsupported standalone hidden visibility.' );
scheduler_check( 'visible' === $scheduler_product->get_catalog_visibility( 'edit' ), 'Variation withdrawal preserves its inherited catalog-visibility field.' );

$republished = rhn_catalog_apply_registry_entry_to_staging( '733739401854' );
scheduler_check( 'publish' === $republished['status'] && 'visible' === $republished['visibility'], 'Re-approval restores Published/visible.' );
scheduler_check( '' === $scheduler_product->get_meta( '_rhn_catalog_withdrawn_by_sheet', true, 'edit' ), 'Re-approval clears the withdrawal marker.' );

$scheduler_product = new Scheduler_Product();
$registry = array(
    '733739401854' => array(
        'weight' => '7', 'weight_unit' => 'oz', 'source' => 'Client-owned Inventory Sheet — Catalog row 10',
    ),
);
$weight_preview = rhn_catalog_apply_shipping_weights( $registry, false );
scheduler_check( isset( $weight_preview['eligible']['733739401854'] ) && '' === $scheduler_product->get_weight( 'edit' ), 'Weight preview identifies the exact SKU without writing.' );
$weight_before = rhn_catalog_sheet_snapshot( $scheduler_product );
$weight_result = rhn_catalog_apply_shipping_weights( $registry, true );
scheduler_check( isset( $weight_result['updated']['733739401854'] ) && '7' === $scheduler_product->get_weight( 'edit' ), 'Weight-only apply updates and reads back the approved shipping weight.' );
scheduler_check( $weight_before['fields']['name'] === $scheduler_product->get_name( 'edit' ) && $weight_before['fields']['status'] === $scheduler_product->get_status( 'edit' ) && $weight_before['fields']['catalog_visibility'] === $scheduler_product->get_catalog_visibility( 'edit' ), 'Weight-only apply preserves copy, status and visibility.' );
scheduler_check( $weight_before['fields']['regular_price'] === $scheduler_product->get_regular_price( 'edit' ) && $weight_before['fields']['stock_quantity'] === $scheduler_product->get_stock_quantity( 'edit' ), 'Weight-only apply preserves price and inventory.' );
$weight_repeat = rhn_catalog_apply_shipping_weights( $registry, true );
scheduler_check( isset( $weight_repeat['unchanged']['733739401854'] ) && ! $weight_repeat['updated'], 'Repeating a correct weight is idempotent.' );
scheduler_check( true === $weight_repeat['unchanged']['733739401854']['baseline_verified'], 'A saved weight is reverified against its durable pre-change baseline.' );

echo "PASS: $checks isolated publication, withdrawal and weight-only checks.\n";
