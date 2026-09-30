<?php
/** Isolated strict-registry tests; no WordPress, network or product writes. */
define('ABSPATH', __DIR__);
function sanitize_text_field($value) { return trim(strip_tags($value)); }
require dirname(__DIR__) . '/registry-import.php';
$checks=0;
function expect_registry($rows,$valid) {
    global $checks;
    try { $result=rhn_catalog_parse_registry(json_encode($rows)); }
    catch (Throwable $error) { if($valid) { throw $error; } $checks++; return; }
    if(!$valid) { throw new RuntimeException('Invalid input accepted'); }
    $checks++;
}
$row=array(
    'sku'=>'00123',
    'approved'=>true,
    'source'=>'test fixture only',
    'description'=>'',
    'brand'=>'Rebekah’s Private Label',
    'categories'=>array('Immune Support'),
    'weight'=>'0.73',
    'weight_unit'=>'lb',
    'package_size'=>'60 capsules',
    'featured_image_url'=>'https://drive.google.com/file/d/test/view',
    'gallery_image_urls'=>array('https://drive.google.com/file/d/gallery/view'),
    'ingredients'=>'Verified ingredients',
);
expect_registry(array($row),true);
if(!isset(rhn_catalog_parse_registry(json_encode(array($row)))['00123'])) { throw new RuntimeException('Leading zero lost'); } $checks++;
expect_registry(array($row,$row),false);
foreach(array('sku'=>123,'approved'=>'true','source'=>'   ','price'=>'19','name'=>'<b></b>','description'=>array()) as $key=>$value) {
    expect_registry(array(array_replace($row,array($key=>$value))),false);
}
expect_registry(array(array_replace($row,array('weight'=>'0'))),false);
expect_registry(array(array_replace($row,array('weight_unit'=>'stone'))),false);
expect_registry(array(array_replace($row,array('categories'=>'Immune Support'))),false);
expect_registry(array(array_replace($row,array('featured_image_url'=>'http://example.com/test.png'))),false);
expect_registry(array(array_replace($row,array('gallery_image_urls'=>'https://example.com/test.png'))),false);
expect_registry(array(),false);
expect_registry(array('record'=>$row),false);
expect_registry(array(array('sku'=>'00123','approved'=>true,'source'=>'fixture only')),false);
echo "PASS: $checks isolated registry checks; admin nonce, permissions and persistence require staging verification.\n";
