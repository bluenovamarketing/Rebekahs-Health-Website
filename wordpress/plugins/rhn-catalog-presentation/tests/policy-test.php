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
function add_action( ...$args ) {}
function taxonomy_exists( $taxonomy ) { return in_array( $taxonomy, array( 'product_cat', 'product_brand' ), true ); }
function get_term_by( $field, $value, $taxonomy ) {
    $ids = array( 'Immune Support' => 11, 'Rebekah’s Private Label' => 21 );
    return isset( $ids[$value] ) ? (object) array( 'term_id' => $ids[$value] ) : false;
}
class WP_Error {
    public $message;
    function __construct( $code, $message ) { $this->message=$message; }
    function get_error_message() { return $this->message; }
}
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function wc_get_weight( $weight, $to, $from ) {
    $oz=array('oz'=>1,'lbs'=>16,'g'=>0.0352739619,'kg'=>35.2739619);
    return ($weight*$oz[$from])/$oz[$to];
}
function wc_get_product( $id ) { return $GLOBALS['test_wc_product'] ?? null; }
function wp_set_object_terms( $id, $terms, $taxonomy, $append ) { $GLOBALS['test_terms'][$taxonomy]=(array)$terms; return $terms; }
function get_post_meta( $id, $key, $single ) { return $GLOBALS['test_post_meta'][$id][$key] ?? ''; }
function update_post_meta( $id, $key, $value ) { $GLOBALS['test_post_meta'][$id][$key]=$value; return true; }
function delete_post_meta( $id, $key ) { unset($GLOBALS['test_post_meta'][$id][$key]); return true; }
function clean_post_cache( $id ) { $GLOBALS['cleaned_post_ids'][]=$id; }
class WP_REST_Request {
    public $params;
    public $route;
    function __construct( $params, $route = '/wc/v1/products/12' ) { $this->params=$params; $this->route=$route; }
    function get_route() { return $this->route; }
    function has_param( $key ) { return array_key_exists($key,$this->params); }
    function get_param( $key ) { return $this->params[$key] ?? null; }
}
class WC_Product {
    public $values = array('name'=>'POS name','description'=>'POS description','short_description'=>'POS short','price'=>'19.00','stock'=>2,'sku'=>'00123','weight'=>'','category_ids'=>array());
    public $meta = array();
    public $save_calls = 0;
    function get_sku( $context ) { return $this->values['sku']; }
    function get_meta( $key, $single, $context ) { return $this->meta[$key] ?? ''; }
    function update_meta_data( $key, $value ) { $this->meta[$key]=$value; }
    function delete_meta_data( $key ) { unset($this->meta[$key]); }
    function set_name($value) { $this->values['name']=$value; }
    function set_description($value) { $this->values['description']=$value; }
    function set_short_description($value) { $this->values['short_description']=$value; }
    function set_weight($value) { $this->values['weight']=$value; }
    function set_category_ids($value) { $this->values['category_ids']=$value; }
    function get_id() { return 12; }
    function save() { $this->save_calls++; return 12; }
}
require dirname(__DIR__) . '/rhn-catalog-presentation.php';
$checks=0;
function check($ok,$message) { global $checks; if(!$ok) { throw new RuntimeException($message); } $checks++; }
$options['home']='https://wordpress-1651482-6655800.cloudwaysapps.com';
$approved=array('approved'=>true,'source'=>'fixture-approved-source','name'=>'Website name','description'=>'Website description','short_description'=>'Website short','brand'=>'Rebekah’s Private Label','categories'=>array('Immune Support'),'weight'=>'0.5','weight_unit'=>'lb','package_size'=>'60 capsules','ingredients'=>'Verified ingredient copy','directions'=>'Verified directions','warnings'=>'Verified warnings','seo_title'=>'Verified SEO title','seo_description'=>'Verified SEO description');
$options['rhn_catalog_presentation_overrides']=array('00123'=>$approved);
$request=new WP_REST_Request(array('name'=>'POS name','description'=>'POS description','short_description'=>'POS short'));
$p=rhn_catalog_presentation_before_save(new WC_Product(),$request);
check($p->values['name']==='Website name','title override');
check($p->values['description']==='Website description','description override');
check($p->values['short_description']==='Website short','short description override');
check($p->values['sku']==='00123','leading-zero barcode identity');
check($p->values['price']==='19.00' && $p->values['stock']===2,'operational fields unchanged');
check((float)$p->values['weight']===8.0,'weight converted to store ounces');
check($p->meta['_rhn_package_size']==='60 capsules','package size stored separately from shipping weight');
check($p->values['category_ids']===array(11),'approved category assigned');
check($p->meta['_rhn_ingredients']==='Verified ingredient copy','approved ingredients stored');
check($p->meta['_rhn_directions_warnings']==="Verified directions\n\nVerified warnings",'directions and warnings combined');
check($p->meta['_seopress_titles_title']==='Verified SEO title' && $p->meta['_seopress_titles_desc']==='Verified SEO description','SEOPress metadata stored');
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
$test_wc_product=new WC_Product();
$test_terms=array();
$test_post_meta=array();
$cleaned_post_ids=array();
rhn_catalog_after_save((object)array('ID'=>12),$request,false);
check($test_terms['product_brand']===array(21),'wc/v1 WP_Post after-hook resolves product and assigns approved brand');
check($test_wc_product->save_calls===0,'after-hook does not invoke a second WooCommerce product save');
check($cleaned_post_ids===array(12),'after-hook refreshes the product cache once');
$batch_skus = rhn_catalog_batch_skus();
check(25===count($batch_skus),'batch proof has exactly 25 fixed pilot SKUs');
check(25===count(array_unique($batch_skus)),'batch proof SKU allowlist contains no duplicates');
check($batch_skus[1]==='733739401854' && $batch_skus[24]==='788332054013','batch proof retains exact string SKU identities');
echo "PASS: $checks isolated policy checks; actual WP/Woo integration and HTML sanitization still require staging tests.\n";
