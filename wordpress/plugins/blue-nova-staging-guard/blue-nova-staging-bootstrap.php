<?php
/**
 * Plugin Name: Blue Nova Staging Bootstrap
 * Description: Provides additional PHP memory headroom for the staging build.
 * Version: 1.0.0
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blue_nova_staging_host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( (string) $_SERVER['HTTP_HOST'] ) : '';
if ( false === strpos( $blue_nova_staging_host, 'cloudwaysapps.com' ) ) {
	return;
}

@ini_set( 'memory_limit', '512M' );

