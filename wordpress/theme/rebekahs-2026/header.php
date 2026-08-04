<?php
/** Site header. */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main"><?php esc_html_e( 'Skip to content', 'rebekahs-2026' ); ?></a>
<header class="site-header">
	<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Rebekah's home">
		<img src="<?php echo esc_url( rhn_theme_asset( 'tmp/source/current-site-logo-live.png' ) ); ?>" alt="Rebekah's Health & Nutrition">
	</a>
	<button class="menu" type="button" aria-expanded="false" aria-controls="nav">Menu <span aria-hidden="true">&#9776;</span></button>
	<nav id="nav" aria-label="Main navigation">
		<div class="nav-dropdown"><button class="nav-trigger" type="button" aria-expanded="false">About <span class="chevron" aria-hidden="true">&#9662;</span></button><div class="dropdown-panel"><a href="<?php echo esc_url( home_url( '/our-story/' ) ); ?>">Our Story</a><a href="<?php echo esc_url( home_url( '/our-team/' ) ); ?>">Our Team</a><a href="<?php echo esc_url( home_url( '/practitioners/' ) ); ?>">Practitioners</a><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Us</a></div></div>
		<div class="nav-dropdown"><button class="nav-trigger" type="button" aria-expanded="false">Visit Our Stores <span class="chevron" aria-hidden="true">&#9662;</span></button><div class="dropdown-panel"><a href="<?php echo esc_url( home_url( '/locations/' ) ); ?>">View All Locations</a><a href="<?php echo esc_url( home_url( '/locations/lapeer/' ) ); ?>">Lapeer</a><a href="<?php echo esc_url( home_url( '/locations/grand-blanc/' ) ); ?>">Grand Blanc</a><a href="<?php echo esc_url( home_url( '/locations/clarkston/' ) ); ?>">Clarkston</a><a href="<?php echo esc_url( home_url( '/locations/lake-orion/' ) ); ?>">Lake Orion</a></div></div>
		<a href="<?php echo esc_url( home_url( '/in-store-products/' ) ); ?>">In-Store Products</a><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">Classes &amp; Events</a><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Wellness Source Blog</a>
		<div class="mobile-actions" aria-label="Header actions"><a class="pill call-order" href="tel:2488432011">Call To Order</a><a class="pill" href="#footer-connect">Stay Connected</a></div>
	</nav>
	<div class="header-actions" aria-label="Header actions"><a class="pill call-order" href="tel:2488432011">Call To Order</a><a class="pill" href="#footer-connect">Stay Connected</a></div>
</header>
