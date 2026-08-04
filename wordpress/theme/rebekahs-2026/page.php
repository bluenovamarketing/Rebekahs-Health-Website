<?php
get_header();
$slug = get_post_field( 'post_name', get_queried_object_id() );
$part = get_template_directory() . '/template-parts/pages/' . sanitize_file_name( $slug ) . '.php';
if ( file_exists( $part ) ) {
	get_template_part( 'template-parts/pages/' . $slug );
} else {
	while ( have_posts() ) {
		the_post();
		echo '<main id="main" class="rhn-content"><article>';
		the_title( '<h1>', '</h1>' );
		the_content();
		echo '</article></main>';
	}
}
get_footer();
