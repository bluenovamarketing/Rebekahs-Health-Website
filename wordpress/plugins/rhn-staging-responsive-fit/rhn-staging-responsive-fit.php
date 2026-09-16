<?php
/**
 * Plugin Name: RHN Staging Responsive Fit
 * Description: Keeps the approved storefront within narrow viewports including scrollbar space; staging only.
 * Version: 1.0.0
 * Author: Blue Nova Marketing
 */
defined( 'ABSPATH' ) || exit;
add_action( 'wp_head', function () {
    $host = strtolower( $_SERVER['HTTP_HOST'] ?? '' );
    if ( 'wordpress-1651482-6655800.cloudwaysapps.com' !== $host || 'rebekahs-2026' !== get_stylesheet() ) {
        return;
    }
    // No design, data or navigation change: let the root fit the available width.
    echo '<style id="rhn-staging-responsive-fit">@media(max-width:340px){html{min-width:0}.site-header .brand{min-width:0;flex-shrink:1}.site-header .menu{white-space:nowrap;flex-shrink:0}}</style>';
}, 100 );
