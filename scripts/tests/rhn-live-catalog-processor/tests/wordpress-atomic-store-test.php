<?php
define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );
define( 'RHN_FULL_RANGE_CATALOG_TESTING', true );
function wp_json_encode( $value, $flags = 0 ) { return json_encode( $value, $flags | JSON_THROW_ON_ERROR ); }
function maybe_serialize( $value ) { return serialize( $value ); }
$cache_deletes = array();
function wp_cache_delete( $key, $group = '' ) { global $cache_deletes; $cache_deletes[] = $group . ':' . $key; return true; }

class AtomicWpdb {
	public $options = 'wp_options'; public $rows = array();
	public function prepare( $query, ...$args ) { return array( 'query' => $query, 'args' => $args ); }
	public function query( $prepared ) {
		$query = $prepared['query']; $args = $prepared['args'];
		if ( str_starts_with( $query, 'INSERT IGNORE' ) ) {
			if ( isset( $this->rows[ $args[0] ] ) ) { return 0; }
			$this->rows[ $args[0] ] = $args[1]; return 1;
		}
		if ( str_starts_with( $query, 'UPDATE' ) ) {
			if ( ! isset( $this->rows[ $args[1] ] ) || $this->rows[ $args[1] ] !== $args[2] ) { return 0; }
			$this->rows[ $args[1] ] = $args[0]; return 1;
		}
		if ( str_starts_with( $query, 'DELETE' ) ) {
			$name = $args[0]; if ( ! isset( $this->rows[ $name ] ) ) { return 0; }
			if ( isset( $args[1] ) && $this->rows[ $name ] !== $args[1] ) { return 0; }
			unset( $this->rows[ $name ] ); return 1;
		}
		throw new RuntimeException( 'Unexpected SQL in atomic store test.' );
	}
}
$wpdb = new AtomicWpdb();
function get_option( $name, $default = false ) { global $wpdb; return isset( $wpdb->rows[ $name ] ) ? unserialize( $wpdb->rows[ $name ] ) : $default; }
class WC_Product { private $sku; public function __construct( $sku ) { $this->sku = $sku; } public function get_sku() { return $this->sku; } }
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'full-range-catalog-runtime.php';
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'isolated-plugin' . DIRECTORY_SEPARATOR . 'catalog-fields.php';

$assertions = 0;
function atomic_check( $condition, $message ) { global $assertions; $assertions++; if ( ! $condition ) { throw new RuntimeException( 'FAIL: ' . $message ); } }
$store = rhn_full_range_wordpress_option_store(); $v1 = array( 'revision' => 1 ); $v2 = array( 'revision' => 2 ); $v3 = array( 'revision' => 3 );
atomic_check( rhn_full_range_store_put_if_absent( $store, 'state', $v1 ), 'first insert succeeds' );
atomic_check( ! rhn_full_range_store_put_if_absent( $store, 'state', $v2 ), 'duplicate insert is rejected atomically' );
atomic_check( rhn_full_range_store_cas( $store, 'state', $v1, $v2 ), 'first compare-and-swap succeeds' );
atomic_check( ! rhn_full_range_store_cas( $store, 'state', $v1, $v3 ), 'competing stale compare-and-swap is rejected' );
atomic_check( $v2 === rhn_full_range_store_get( $store, 'state' ), 'winning value is durable' );
atomic_check( ! rhn_full_range_store_delete( $store, 'state', $v1 ), 'stale conditional delete is rejected' );
atomic_check( rhn_full_range_store_delete( $store, 'state', $v2 ), 'current conditional delete succeeds' );
atomic_check( null === rhn_full_range_store_get( $store, 'state' ), 'delete is durable' );
atomic_check( count( $cache_deletes ) >= 10, 'persistent option and notoptions cache entries were invalidated' );
for ( $i = 0; $i < 1205; $i++ ) {
	$sku = str_pad( (string) $i, 12, '0', STR_PAD_LEFT );
	atomic_check( rhn_full_range_registry_put( $sku, array( 'approved' => true, 'source' => 'Fixture', 'name' => 'Product ' . $i ), $store ), 'sharded registry write succeeds for ' . $sku );
}
$sample = rhn_full_range_registry_lookup( '000000001204', $store );
atomic_check( ! empty( $sample['found'] ) && 'Product 1204' === $sample['entry']['name'], '>1,000th approved entry is retrievable without the old registry parser' );
atomic_check( rhn_full_range_registry_put( '000000001204', null, $store ), 'explicit withdrawal writes a shard tombstone' );
$withdrawn = rhn_full_range_registry_lookup( '000000001204', $store );
atomic_check( ! empty( $withdrawn['found'] ) && null === $withdrawn['entry'], 'withdrawal tombstone suppresses legacy fallback' );
$wpdb->rows['rhn_catalog_presentation_overrides'] = serialize( array( '000000001204' => array( 'approved' => true, 'source' => 'Legacy entry that must stay withdrawn', 'name' => 'Old Name' ) ) );
atomic_check( null === rhn_catalog_entry_for_product( new WC_Product( '000000001204' ) ), 'feature rollback still honors withdrawal tombstone instead of reviving legacy presentation' );
$registry_rows = array_filter( $wpdb->rows, static function ( $value ) { $decoded = unserialize( $value ); return is_array( $decoded ) && str_starts_with( (string) ( $decoded['_logical_key'] ?? '' ), 'rhn-full-range:registry:' ); } );
atomic_check( count( $registry_rows ) <= 256 && max( array_map( 'strlen', $registry_rows ) ) < 2097152, '1,205 entries stay in bounded shards below the old 2 MiB ceiling' );
echo "PASS WordPress atomic option store: {$assertions} assertions.\n";
