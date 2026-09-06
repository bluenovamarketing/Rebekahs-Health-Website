<?php
/** Approved System 03 catalog card. */

defined( 'ABSPATH' ) || exit;
global $product;

if ( ! $product || ! $product->is_visible() ) {
	return;
}

$categories = wc_get_product_category_list( $product->get_id(), ', ' );
?>
<li <?php wc_product_class( 'rhn-product-card', $product ); ?>>
	<a class="rhn-product-card__image" href="<?php the_permalink(); ?>" aria-label="View <?php echo esc_attr( get_the_title() ); ?>">
		<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ) ); ?>
		<?php if ( ! $product->is_in_stock() ) : ?><span class="rhn-stock-badge">Out of stock</span><?php endif; ?>
	</a>
	<div class="rhn-product-card__body">
		<?php if ( $categories ) : ?><p class="rhn-product-card__category"><?php echo wp_kses_post( $categories ); ?></p><?php endif; ?>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="rhn-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
		<a class="rhn-button rhn-button--secondary" href="<?php the_permalink(); ?>">View Product</a>
	</div>
</li>
