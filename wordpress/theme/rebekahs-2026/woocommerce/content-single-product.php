<?php
/** Approved System 04 real product layout. */

defined( 'ABSPATH' ) || exit;
global $product;

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$image_ids = array_values( array_filter( array_merge( array( $product->get_image_id() ), $product->get_gallery_image_ids() ) ) );
$image_ids = array_slice( $image_ids, 0, 3 );
$shop_url  = rhn_phase_two_shop_url();
$related   = wc_get_related_products( $product->get_id(), 3 );
$is_legacy_staging_preview = function_exists( 'rhn_phase_two_is_quarantined_legacy_product' )
	&& rhn_phase_two_is_quarantined_legacy_product( $product->get_id() );
$related = $is_legacy_staging_preview ? array() : $related;
$display_title = $is_legacy_staging_preview ? __( 'Product Preview — Photo Coming Soon', 'rebekahs-2026' ) : get_the_title();
?>
<nav class="rhn-store-crumbs" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span><a href="<?php echo esc_url( $shop_url ); ?>">Online Store</a><span>/</span><span><?php echo esc_html( $display_title ); ?></span></nav>
<article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'rhn-product-layout', $product ); ?>>
	<section class="rhn-product-gallery" aria-label="Product images">
		<?php if ( $image_ids ) : ?>
			<div class="rhn-product-thumbs" aria-label="Choose product image">
				<?php foreach ( $image_ids as $index => $image_id ) :
					$full  = wp_get_attachment_image_url( $image_id, 'large' );
					$label = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
					$label = $label ? $label : sprintf( 'Product image %d', $index + 1 );
					?>
					<button type="button" class="rhn-product-thumb" data-rhn-product-image="<?php echo esc_url( $full ); ?>" data-rhn-product-alt="<?php echo esc_attr( $label ); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>"><?php echo wp_kses_post( wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'alt' => $label ) ) ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="rhn-product-main-image"><?php echo wp_kses_post( wp_get_attachment_image( $image_ids[0], 'large', false, array( 'data-rhn-product-main-image' => '', 'alt' => get_the_title() ) ) ); ?></div>
		<?php else : ?>
			<div class="rhn-product-thumbs rhn-product-thumbs--fallback" aria-label="Product image views">
				<button class="rhn-product-thumb" type="button" aria-current="true" aria-label="Front image — image coming soon" data-rhn-placeholder-view="Front image"><span class="rhn-product-thumb__title">Front</span><span class="rhn-product-thumb__state">Coming soon</span></button>
				<button class="rhn-product-thumb" type="button" aria-current="false" aria-label="Supplement facts image — image coming soon" data-rhn-placeholder-view="Supplement facts image"><span class="rhn-product-thumb__title">Facts</span><span class="rhn-product-thumb__state">Coming soon</span></button>
				<button class="rhn-product-thumb" type="button" aria-current="false" aria-label="Ingredients image — image coming soon" data-rhn-placeholder-view="Ingredients image"><span class="rhn-product-thumb__title">Ingredients</span><span class="rhn-product-thumb__state">Coming soon</span></button>
			</div>
			<div class="rhn-product-main-image rhn-product-main-image--fallback"><?php rhn_phase_two_product_image_fallback( '', true ); ?></div>
		<?php endif; ?>
	</section>

	<section class="rhn-product-summary">
		<?php if ( $is_legacy_staging_preview ) : ?><p class="rhn-product-meta">Staging preview</p><?php else : echo wp_kses_post( wc_get_product_category_list( $product->get_id(), ' &middot; ', '<p class="rhn-product-meta">', '</p>' ) ); endif; ?>
		<h1><?php echo esc_html( $display_title ); ?></h1>
		<?php if ( $is_legacy_staging_preview ) : ?>
			<div class="rhn-product-description"><p>Private staging example of the approved missing-photo treatment. Real product information will come from the verified catalog.</p></div>
			<div class="rhn-product-stock is-unavailable"><p>Preview only — not available for purchase.</p></div>
		<?php else : ?>
			<div class="rhn-product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			<?php if ( $product->get_short_description() ) : ?><div class="rhn-product-description"><?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?></div><?php endif; ?>
			<div class="rhn-product-stock <?php echo $product->is_in_stock() ? '' : 'is-unavailable'; ?>"><?php echo wp_kses_post( wc_get_stock_html( $product ) ); ?></div>
		<?php endif; ?>
		<?php if ( ! $is_legacy_staging_preview && $product->is_purchasable() && $product->is_in_stock() ) : ?>
			<div class="rhn-product-purchase"><?php woocommerce_template_single_add_to_cart(); ?></div>
		<?php elseif ( ! $is_legacy_staging_preview ) : ?>
			<div class="rhn-product-unavailable"><strong>This product is currently unavailable online.</strong><a class="rhn-button rhn-button--secondary" href="<?php echo esc_url( $shop_url ); ?>">Browse Similar Products</a></div>
		<?php endif; ?>
		<?php rhn_phase_two_product_help_line(); ?>
	</section>
</article>

<section class="rhn-product-facts">
	<article>
		<h2>Product Details</h2>
		<?php if ( $is_legacy_staging_preview ) : ?><p>Brand, form, count, ingredients, and other verified product information will appear here when available.</p><?php elseif ( $product->get_description() ) : echo wp_kses_post( wpautop( $product->get_description() ) ); else : ?><p>Brand, form, count, ingredients, and other approved product information will appear here when available.</p><?php endif; ?>
		<?php if ( ! $is_legacy_staging_preview && $product->has_attributes() ) : wc_display_product_attributes( $product ); endif; ?>
	</article>
	<?php rhn_phase_two_product_directions_warnings(); ?>
</section>

<?php if ( $related ) : ?>
	<section class="rhn-related-products">
		<h2>Related products</h2>
		<ul class="products columns-3">
			<?php
			$original_post = $GLOBALS['post'];
			foreach ( $related as $related_id ) :
				$post_object = get_post( $related_id );
				if ( ! $post_object ) {
					continue;
				}
				$GLOBALS['post'] = $post_object; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				setup_postdata( $post_object );
				wc_setup_product_data( $post_object );
				wc_get_template_part( 'content', 'product' );
			endforeach;
			$GLOBALS['post'] = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			wp_reset_postdata();
			wc_setup_product_data( $original_post );
			?>
		</ul>
	</section>
<?php endif; ?>
