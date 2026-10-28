<?php
define( 'ABSPATH', __DIR__ . DIRECTORY_SEPARATOR );
function maybe_serialize( $value ) { return serialize( $value ); }
function maybe_unserialize( $value ) { return unserialize( $value ); }
function wp_cache_delete() { return true; }
function prep_store_check( $condition, $message ) { static $count = 0; $GLOBALS['prep_store_assertions'] = ++$count; if ( ! $condition ) { throw new RuntimeException( 'FAIL: ' . $message ); } }

class RhnFrStageFakeWpdb {
	public $options = 'wp_options';
	public $data = array();
	public function prepare( $query, ...$args ) { return json_encode( array( 'query' => $query, 'args' => $args ) ); }
	public function query( $prepared ) {
		$item = json_decode( $prepared, true ); $query = $item['query']; $args = $item['args'];
		if ( str_starts_with( $query, 'INSERT IGNORE' ) ) { if ( array_key_exists( $args[0], $this->data ) ) { return 0; } $this->data[ $args[0] ] = $args[1]; return 1; }
		if ( str_starts_with( $query, 'UPDATE' ) ) { if ( ! isset( $this->data[ $args[1] ] ) || $this->data[ $args[1] ] !== $args[2] ) { return 0; } $this->data[ $args[1] ] = $args[0]; return 1; }
		if ( str_starts_with( $query, 'DELETE' ) ) { if ( ! isset( $this->data[ $args[0] ] ) || $this->data[ $args[0] ] !== $args[1] ) { return 0; } unset( $this->data[ $args[0] ] ); return 1; }
		throw new RuntimeException( 'Unexpected query.' );
	}
	public function get_var( $prepared ) { $item = json_decode( $prepared, true ); return $this->data[ $item['args'][0] ] ?? null; }
}

$wpdb = new RhnFrStageFakeWpdb();
require_once dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'staging-validation-loader' . DIRECTORY_SEPARATOR . 'staging-preparation-store.php';
$id = str_repeat( 'a', 32 );
$manifest = array( 'status' => 'preparing', 'generation' => 1 );
prep_store_check( rhn_fr_stage_store_create( $id, 'manifest', $manifest ), 'atomic create succeeds once' );
prep_store_check( ! rhn_fr_stage_store_create( $id, 'manifest', $manifest ), 'atomic create refuses overwrite' );
prep_store_check( ! rhn_fr_stage_store_cas( $id, 'manifest', array( 'wrong' => true ), array( 'status' => 'ready' ) ), 'CAS rejects stale expected value' );
$ready = array( 'status' => 'ready', 'generation' => 2 );
prep_store_check( rhn_fr_stage_store_cas( $id, 'manifest', $manifest, $ready ), 'CAS advances matching preparation state' );
prep_store_check( $ready === rhn_fr_stage_store_get( $id, 'manifest' ), 'read returns captured state' );
prep_store_check( ! rhn_fr_stage_store_delete_exact( $id, 'manifest', $manifest ), 'cleanup refuses mismatched state' );
$cleanup = rhn_fr_stage_cleanup_preview( $id );
prep_store_check( true === $cleanup['manifest'] && array() === $wpdb->data, 'known preview cleanup removes exact matching isolated state' );
$threw = false; try { rhn_fr_stage_store_key( '../wp_', 'anything' ); } catch ( Throwable $error ) { $threw = true; }
prep_store_check( $threw, 'arbitrary option identity is rejected' );
echo 'PASS staging preparation store: ' . $GLOBALS['prep_store_assertions'] . " assertions.\n";
