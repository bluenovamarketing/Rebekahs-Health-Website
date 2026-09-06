<?php
/**
 * Approved System 03 shop homepage and product catalog.
 *
 * @package Rebekahs_2026
 */

defined( 'ABSPATH' ) || exit;

get_header();

$shop_url   = function_exists( 'rhn_phase_two_shop_url' ) ? rhn_phase_two_shop_url() : home_url( '/shop/' );
$goal_cards = array(
	'energy-fatigue'     => 'Energy & Fatigue',
	'immune-support'     => 'Immune Support',
	'stress-sleep'       => 'Stress & Sleep',
	'brain-focus-memory' => 'Brain, Focus & Memory',
);
?>
<main id="main" class="rhn-store-page">
	<div class="rhn-store-wrap">
		<nav class="rhn-store-crumbs" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span><span>Shop</span></nav>
		<header class="rhn-catalog-head">
			<div>
				<h1>Shop Rebekah&rsquo;s Online</h1>
				<p>Browse wellness favorites by goal, search the catalog, or ask our team for help choosing where to begin.</p>
			</div>
			<form class="rhn-catalog-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">
				<label class="visually-hidden" for="rhn-catalog-query">Search this catalog</label>
				<input id="rhn-catalog-query" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Search this catalog">
				<input type="hidden" name="post_type" value="product">
				<button type="submit" aria-label="Search">&rarr;</button>
			</form>
		</header>

		<section class="rhn-shop-goals" aria-label="Shop by wellness goal">
			<?php foreach ( $goal_cards as $slug => $label ) : ?>
				<a href="<?php echo esc_url( add_query_arg( 'filter_wellness[]', $slug, $shop_url ) ); ?>">
					<span aria-hidden="true"></span><small>Shop by goal</small><strong><?php echo esc_html( $label ); ?></strong>
				</a>
			<?php endforeach; ?>
		</section>

		<section class="rhn-shop-trust" aria-label="Shopping benefits and help">
			<div><strong>Carefully selected</strong><span>A broad mix of supplements and wellness essentials.</span></div>
			<div><strong>Packed with care</strong><span>Every order is prepared by Rebekah&rsquo;s team.</span></div>
			<div><strong>Easy online ordering</strong><span>Review your selections before checkout.</span></div>
			<div><strong>Need product help?</strong><span>Call <a href="tel:2488432011">(248) 843-2011</a> for friendly guidance.</span></div>
		</section>

		<section class="rhn-catalog-toolbar" aria-label="Catalog controls">
			<strong>Find support for your everyday wellness.</strong>
			<div>
				<button class="rhn-button rhn-button--secondary rhn-filter-open" type="button" data-rhn-filter-open aria-expanded="false" aria-controls="rhn-catalog-filter-panel">Filters</button>
				<?php woocommerce_catalog_ordering(); ?>
			</div>
		</section>

		<div class="rhn-catalog-layout">
			<aside class="rhn-catalog-filter-panel" id="rhn-catalog-filter-panel" aria-label="Product filters" data-rhn-filter-drawer hidden>
				<button class="rhn-filter-drawer__backdrop" type="button" data-rhn-filter-close aria-label="Close filters"></button>
				<div class="rhn-filter-drawer__panel" role="dialog" aria-modal="true" aria-labelledby="rhn-filter-title" tabindex="-1">
					<div class="rhn-filter-mobile-head"><h2 id="rhn-filter-title">Filter Products</h2><button type="button" data-rhn-filter-close aria-label="Close filters">&times;</button></div>
					<?php rhn_phase_two_catalog_filters(); ?>
				</div>
			</aside>

			<section class="rhn-catalog-results" aria-label="Catalog results">
				<?php if ( woocommerce_product_loop() ) : ?>
					<p class="visually-hidden" aria-live="polite"><?php woocommerce_result_count(); ?></p>
					<ul class="products columns-3">
						<?php while ( have_posts() ) : the_post(); wc_get_template_part( 'content', 'product' ); endwhile; ?>
					</ul>
					<?php woocommerce_pagination(); ?>
				<?php else : ?>
					<div class="rhn-no-results" role="status">
						<div aria-hidden="true">&#8981;</div>
						<h2>No products found</h2>
						<p>Try a broader search or clear the current filters.</p>
						<a class="rhn-button" href="<?php echo esc_url( $shop_url ); ?>">Clear Search &amp; Filters</a>
					</div>
				<?php endif; ?>
			</section>
		</div>
	</div>
</main>
<?php
get_footer();
