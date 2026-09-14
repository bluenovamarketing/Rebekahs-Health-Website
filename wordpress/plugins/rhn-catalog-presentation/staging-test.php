<?php
/** Fixed staging-only proof. No outbound HTTP, credentials, or Revel access. */
defined( 'ABSPATH' ) || exit;
function rhn_catalog_restore_test() {
    $baseline = get_option( 'rhn_catalog_test_baseline' );
    if ( ! is_array( $baseline ) || 1479193 !== ( $baseline['id'] ?? null ) ) { throw new RuntimeException( 'No valid baseline.' ); }
    $p = wc_get_product( 1479193 );
    if ( ! $p || '090900000019' !== $p->get_sku() ) { throw new RuntimeException( 'Recovery identity mismatch.' ); }
    foreach ( $baseline['fields'] as $key => $value ) { $setter = 'set_' . $key; $p->$setter( $value ); }
    if ( $baseline['meta_exists'] ) { $p->update_meta_data( '_rhn_source_presentation', $baseline['meta'] ); }
    else { $p->delete_meta_data( '_rhn_source_presentation' ); }
    $p->save();
    clean_post_cache( 1479193 );
    $fresh = wc_get_product( 1479193 );
    foreach ( $baseline['fields'] as $key => $value ) {
        $getter = 'get_' . $key;
        if ( $fresh->$getter( 'edit' ) !== $value ) { throw new RuntimeException( 'Recovery verification failed: ' . $key ); }
    }
    if ( $fresh->meta_exists( '_rhn_source_presentation' ) !== $baseline['meta_exists'] || ( $baseline['meta_exists'] && $fresh->get_meta( '_rhn_source_presentation', true, 'edit' ) !== $baseline['meta'] ) ) { throw new RuntimeException( 'Metadata recovery verification failed.' ); }
    update_option( 'rhn_catalog_test_restored', true, false );
    return 'Original fields and metadata restored and read back successfully.';
}
function rhn_catalog_run_test() {
    if ( ! function_exists( 'rhn_is_cloudways_staging' ) || ! rhn_is_cloudways_staging() || false === has_filter( 'woocommerce_webhook_should_deliver', '__return_false' ) ) { throw new RuntimeException( 'Staging guard required.' ); }
    $p = wc_get_product( 1479193 );
    if ( ! $p || '090900000019' !== $p->get_sku() || ! $p->is_type( 'simple' ) ) { throw new RuntimeException( 'Test identity mismatch.' ); }
    $baseline = array( 'id'=>1479193, 'fields'=>array(), 'meta_exists'=>$p->meta_exists( '_rhn_source_presentation' ), 'meta'=>$p->get_meta( '_rhn_source_presentation', true, 'edit' ) );
    foreach ( array( 'name','description','short_description','regular_price','sale_price','price','stock_quantity','stock_status','manage_stock','backorders','sku' ) as $key ) { $getter='get_'.$key; $baseline['fields'][$key]=$p->$getter('edit'); }
    // One-shot lock and durable baseline. Repeated submit cannot start a second run.
    if ( ! add_option( 'rhn_catalog_test_baseline', $baseline, '', false ) ) { throw new RuntimeException( 'Test already started. Read the saved report or use recovery; do not rerun.' ); }
    $approved = array( '090900000019'=>array( 'approved'=>true, 'source'=>'Todd-approved staging-only synthetic proof; not customer copy', 'name'=>'RHN protected test title', 'description'=>'<p>Protected test description</p><script>window.invalid=true;</script>', 'short_description'=>'<p>Protected short test</p>' ) );
    $override = function () use ( $approved ) { return $approved; };
    $checks = array();
    try {
        add_filter( 'pre_option_rhn_catalog_presentation_overrides', $override );
        $request = new WP_REST_Request( 'PUT', '/wc/v1/products/1479193' );
        $request->set_body_params( array( 'name'=>'Incoming test title', 'description'=>'Incoming test description', 'short_description'=>'Incoming short test', 'regular_price'=>'20.00', 'manage_stock'=>true, 'stock_quantity'=>1, 'backorders'=>'no' ) );
        $response = rest_do_request( $request );
        $checks['REST HTTP 200'] = 200 === $response->get_status();
        clean_post_cache(1479193);
        $actual = wc_get_product(1479193);
        $checks['protected title persisted'] = 'RHN protected test title' === $actual->get_name('edit');
        $checks['protected long description sanitized and persisted'] = wp_kses_post($approved['090900000019']['description']) === $actual->get_description('edit') && false === strpos($actual->get_description('edit'),'<script');
        $checks['protected short description persisted'] = $approved['090900000019']['short_description'] === $actual->get_short_description('edit');
        $checks['exact barcode unchanged'] = '090900000019' === $actual->get_sku();
        $checks['incoming price persisted'] = 20.0 === (float)$actual->get_regular_price();
        $checks['incoming stock persisted'] = 1 === $actual->get_stock_quantity() && $actual->get_manage_stock() && 'no' === $actual->get_backorders();
        $source = $actual->get_meta('_rhn_source_presentation',true,'edit');
        $checks['incoming copy retained separately'] = is_array($source) && 'Incoming test description' === ($source['description']??null);
    } finally {
        remove_filter( 'pre_option_rhn_catalog_presentation_overrides', $override );
        $checks['baseline restoration'] = rhn_catalog_restore_test();
        update_option( 'rhn_catalog_test_report', $checks, false );
    }
    return $checks;
}
function rhn_catalog_test_page() {
    if ( ! current_user_can('manage_options') || ! current_user_can('edit_post',1479193) || ! rhn_catalog_presentation_enabled() ) { wp_die('Staging administrators only.'); }
    $message='';
    if ('POST' === ($_SERVER['REQUEST_METHOD']??'')) {
        check_admin_referer('rhn_catalog_test');
        try { $message = 'restore' === ($_POST['test_action']??'') ? rhn_catalog_restore_test() : wp_json_encode(rhn_catalog_run_test()); }
        catch(Throwable $error) { $message = 'Check/recovery required: '.$error->getMessage(); }
    }
    echo '<div class="wrap"><h1>Controlled staging API proof</h1><p>Fixed product 1479193 / barcode 090900000019. Tests synthetic copy, price 20 and quantity 1 through wc/v1, then restores original values. No Revel calls. Run only once; baseline retained for recovery.</p><p role="status">'.esc_html($message).'</p><pre>'.esc_html(wp_json_encode(get_option('rhn_catalog_test_report',array()),JSON_PRETTY_PRINT)).'</pre><form method="post">';
    wp_nonce_field('rhn_catalog_test');
    echo '<button class="button" name="test_action" value="run">Run combined staging proof once</button> <button class="button" name="test_action" value="restore">Restore saved baseline</button></form></div>';
}
if(function_exists('add_action')) {
    add_action('admin_menu',function(){ if(rhn_catalog_presentation_enabled()) { add_management_page('Controlled staging API proof','Controlled staging API proof','manage_options','rhn-catalog-test','rhn_catalog_test_page'); } });
}
