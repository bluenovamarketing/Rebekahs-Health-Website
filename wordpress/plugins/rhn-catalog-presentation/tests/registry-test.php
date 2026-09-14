<?php
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
$row=array('sku'=>'00123','approved'=>true,'source'=>'test fixture only','description'=>'');
expect_registry(array($row),true);
if(!isset(rhn_catalog_parse_registry(json_encode(array($row)))['00123'])) { throw new RuntimeException('Leading zero lost'); } $checks++;
expect_registry(array($row,$row),false);
foreach(array('sku'=>123,'approved'=>'true','source'=>'   ','price'=>'19','name'=>'<b></b>','description'=>array()) as $key=>$value) {
    expect_registry(array(array_replace($row,array($key=>$value))),false);
}
expect_registry(array(),false);
expect_registry(array('record'=>$row),false);
unset($row['description']); expect_registry(array($row),false);
echo "PASS: $checks isolated registry checks; admin nonce, permissions and persistence require staging verification.\n";
