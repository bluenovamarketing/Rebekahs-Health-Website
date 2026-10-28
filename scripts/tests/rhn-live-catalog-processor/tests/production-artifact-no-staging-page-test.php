<?php
$root = dirname( __DIR__ );
$integration = file_get_contents( $root . DIRECTORY_SEPARATOR . 'full-range-catalog-integration.php' );
$runtime = file_get_contents( $root . DIRECTORY_SEPARATOR . 'full-range-catalog-runtime.php' );
$isolated_integration = file_get_contents( $root . DIRECTORY_SEPARATOR . 'isolated-plugin' . DIRECTORY_SEPARATOR . 'full-range-catalog-integration.php' );
$isolated_runtime = file_get_contents( $root . DIRECTORY_SEPARATOR . 'isolated-plugin' . DIRECTORY_SEPARATOR . 'full-range-catalog-runtime.php' );
$checks = array(
	! str_contains( $integration, 'rhn_full_range_staging_' ),
	! str_contains( $isolated_integration, 'rhn_full_range_staging_' ),
	! str_contains( $runtime, 'rhn_full_range_staging_prepare_context' ),
	! str_contains( $isolated_runtime, 'rhn_full_range_staging_prepare_context' ),
	! str_contains( $integration, "add_action( 'admin_menu'" ),
);
foreach ( $checks as $index => $passed ) {
	if ( ! $passed ) { throw new RuntimeException( 'FAIL production artifact still contains staging preparation surface at check ' . ( $index + 1 ) ); }
}
echo 'PASS production artifact excludes staging preparation/admin surface: ' . count( $checks ) . " assertions.\n";
