<?php
define( 'ABSPATH', __DIR__ );
function add_action( $hook, $callback, $priority ) { $GLOBALS['fit_callback'] = $callback; }
function get_stylesheet() { return $GLOBALS['fit_theme']; }
require __DIR__ . '/../wordpress/plugins/rhn-staging-responsive-fit/rhn-staging-responsive-fit.php';
foreach ( array(
    array( 'wordpress-1651482-6655800.cloudwaysapps.com', 'rebekahs-2026', true ),
    array( 'rebekahspureliving.com', 'rebekahs-2026', false ),
    array( 'wordpress-1651482-6655800.cloudwaysapps.com', 'other-theme', false ),
) as $case ) {
    list( $_SERVER['HTTP_HOST'], $fit_theme, $expected ) = $case;
    ob_start();
    $fit_callback();
    $css = ob_get_clean();
    if ( str_contains( $css, 'rhn-staging-responsive-fit' ) !== $expected ) {
        throw new Exception( 'Responsive staging scope failed' );
    }
}
echo "PASS: 3 responsive-fit staging scope cases\n";
