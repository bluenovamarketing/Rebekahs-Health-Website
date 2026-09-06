<?php
/** Approved System 04 product-page shell. */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="rhn-store-page rhn-single-product-page">
	<div class="rhn-store-wrap">
		<?php while ( have_posts() ) : the_post(); wc_get_template_part( 'content', 'single-product' ); endwhile; ?>
	</div>
</main>
<?php get_footer();
