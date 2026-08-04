<?php
get_header();
$part = get_template_directory() . '/template-parts/pages/404.php';
if ( file_exists( $part ) ) {
	get_template_part( 'template-parts/pages/404' );
} else {
	echo '<main id="main"><h1>We could not find that page.</h1><p><a href="' . esc_url( home_url( '/' ) ) . '">Return home</a></p></main>';
}
get_footer();
