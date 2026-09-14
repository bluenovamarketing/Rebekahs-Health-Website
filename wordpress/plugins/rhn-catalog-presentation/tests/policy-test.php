<?php
// Isolated policy tests; no WordPress/network/server access. Run with PHP CLI.
define( 'ABSPATH', __DIR__ );
$options = array();
function get_option( $key, $default = false ) { global $options; return $options[$key] ?? $default; }
function wp_parse_url( $url, $component ) { return parse_url( $url, $component ); }
function sanitize_text_field( $value ) { return trim( strip_tags( $value ) ); }
// Stub only: actual WordPress HTML sanitization must be tested on staging.
function wp_kses_post( $value ) { return $value; }
function add_filter( ...$args ) {}
class WP_REST_Request {
    public $params;
    public $route;
    function __construct( $params, $route = '/wc/v1/products/12' ) { $this->params=$params; $this->route=$route; }
    function get_route() { return $this->route; }
    function has_param( $key ) { return array_key_exists($key,$this->params); }
    function get_param( $key ) { return $this->params[$key] ?? null; }
}
class WC_Product {
    public $values = array('name'=>'POS name','description'=>'POS description','short_description'=>'POS short','price'=>'19.00','stock'=>2,'sku'=>'00123');
    public $meta = array();
    function get_sku( $context ) { return $this->values['sku']; }
    function get_meta( $key, $single, $context ) { return $this->meta[$key] ?? ''; }
    function update_meta_data( $key, $value ) { $this->meta[$key]=$value; }
    function set_name($value) { $this->values['name']=$value; }
    function set_description($value) { $this->values['description']=$value; }
    function set_short_description($value) { $this->values['short_description']=$value; }
}
require dirname(__DIR__) . '/rhn-catalog-presentation.php';
$checks=0;
function check($ok,$message) { global $checks; if(!$ok) { throw new RuntimeException($message); } $checks++; }
$options['home']='https://wordpress-1651482-6655800.cloudwaysapps.com';
$approved=array('approved'=>true,'source'=>'fixture-approved-source','name'=>'Website name','description'=>'Website description','short_description'=>'Website short');
$options['rhn_catalog_presentation_overrides']=array('00123'=>$approved);
$request=new WP_REST_Request(array('name'=>'POS name','description'=>'POS description','short_description'=>'POS short'));
$p=rhn_catalog_presentation_before_save(new WC_Product(),$request);
check($p->values['name']==='Website name','title override');
check($p->values['description']==='Website description','description override');
check($p->values['short_description']==='Website short','short description override');
check($p->values['sku']==='00123','leading-zero barcode identity');
check($p->values['price']==='19.00' && $p->values['stock']===2,'operational fields unchanged');
check($p->meta['_rhn_source_presentation']['description']==='POS description','raw imported description retained');
$options['rhn_catalog_presentation_overrides']['00123']['description']='';
$options['rhn_catalog_presentation_overrides']['00123']['name']='';
$p=rhn_catalog_presentation_before_save(new WC_Product(),$request);
check($p->values['description']==='','approved intentional blank');
check($p->values['name']==='POS name','empty title cannot erase name');
$options['rhn_catalog_presentation_overrides']['00123']['approved']=false;
$p=rhn_catalog_presentation_before_save(new WC_Product(),$request);
check($p->values['description']==='POS description' && $p->meta===array(),'unapproved passthrough');
$options['rhn_catalog_presentation_overrides']=array('123'=>$approved);
$p=rhn_catalog_presentation_before_save(new WC_Product(),$request);
check($p->values['name']==='POS name','different barcode is not a match');
$options['rhn_catalog_presentation_overrides']=array('00123'=>$approved);
$options['home']='https://rebekahspureliving.com';
$p=rhn_catalog_presentation_before_save(new WC_Product(),$request);
check($p->values['name']==='POS name' && $p->meta===array(),'production no-op');
$options['home']='https://wordpress-1651482-6655800.cloudwaysapps.com';
$p=rhn_catalog_presentation_before_save(new WC_Product(),new WP_REST_Request(array(),'/wc/v1/orders/12'));
check($p->values['name']==='POS name','unrelated route no-op');
$options['rhn_catalog_presentation_overrides']['00123']['source']='';
$p=rhn_catalog_presentation_before_save(new WC_Product(),$request);
check($p->values['name']==='POS name','missing source no-op');
$options['rhn_catalog_presentation_overrides']['00123']['source']='   ';
$p=rhn_catalog_presentation_before_save(new WC_Product(),$request);
check($p->values['name']==='POS name','whitespace-only source no-op');
$options['rhn_catalog_presentation_overrides']=array('00123'=>$approved);
foreach (array('/wc/v1/products','/wc/v2/products/12','/wc/v3/products/12') as $route) {
    $p=rhn_catalog_presentation_before_save(new WC_Product(),new WP_REST_Request(array('name'=>'POS name'),$route));
    check($p->values['name']==='Website name','supported route '.$route);
}
foreach (array('/wc/v3/products/batch','/wc/v3/products/12/variations/4','/wc/v3/customers/12') as $route) {
    $p=rhn_catalog_presentation_before_save(new WC_Product(),new WP_REST_Request(array(),$route));
    check($p->values['name']==='POS name' && $p->meta===array(),'unsupported route unchanged '.$route);
}
$p=new WC_Product();
$p->values['price']='23.00';
$p->values['stock']=0;
$p=rhn_catalog_presentation_before_save($p,new WP_REST_Request(array('regular_price'=>'23.00','stock_quantity'=>0)));
check($p->values['price']==='23.00' && $p->values['stock']===0,'new price and zero stock retained');
check(!isset($p->meta['_rhn_source_presentation']['price']) && !isset($p->meta['_rhn_source_presentation']['stock_quantity']),'operational payload not copied to presentation metadata');
echo "PASS: $checks isolated policy checks; actual WP/Woo integration and HTML sanitization still require staging tests.\n";
