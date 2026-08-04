<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
$result = array(
    'memory_limit' => ini_get('memory_limit'),
    'php_version'  => PHP_VERSION,
);
@unlink(__FILE__);
echo json_encode($result);
