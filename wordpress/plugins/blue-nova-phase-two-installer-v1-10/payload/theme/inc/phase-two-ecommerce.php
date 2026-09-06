<?php
/**
 * Phase Two ecommerce component scaffold.
 *
 * This file is intentionally dormant until the approved staging build defines
 * RHN_PHASE_TWO_ECOMMERCE_ENABLED as true and loads it from functions.php.
 *
 * @package Rebekahs_2026
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'RHN_PHASE_TWO_ECOMMERCE_ENABLED' ) || true !== RHN_PHASE_TWO_ECOMMERCE_ENABLED ) {
	return;
}

/** Determine whether the current request uses a WooCommerce customer surface. */
function rhn_phase_two_is_commerce_context() {
	return ( function_exists( 'is_woocommerce' ) && is_woocommerce() )
		|| ( function_exists( 'is_cart' ) && is_cart() )
		|| ( function_exists( 'is_checkout' ) && is_checkout() )
		|| ( function_exists( 'is_account_page' ) && is_account_page() );
}

/** Exact client-approved storefront terms from Systems 03 and 07. */
function rhn_phase_two_store_terms() {
	return array(
		'brands'    => array(
			'Rebekah’s Private Label',
			'Terry Naturally',
			'Ancient Nutrition',
			'NOW Foods',
			'BioOptimizers',
			'Spencer’s Honey',
			'Just Thrive',
			'Emerald Labs',
			'MegaFood',
		),
		'wellness' => array(
			'Immune Support',
			'Stress & Sleep',
			'Brain, Focus & Memory',
			'Digestive Health',
			'Energy & Fatigue',
			'Heart & Cholesterol Support',
			'Blood Sugar Support',
			'Healthy Weight',
			'Women’s Health',
			'Men’s Health',
			'Thyroid & Adrenal Support',
			'Joint, Bone & Muscle Support',
			'Inflammation Support',
			'Liver & Detox Support',
			'Kidney & Urinary Support',
			'Skin, Hair & Nails',
			'Respiratory & Sinus Support',
			'Healthy Aging',
			'Children’s Wellness',
			'Cellular & Mitochondrial Support',
			'Cleanse Support',
		),
	);
}

/** Seed only the approved storefront taxonomy terms; never seed sample products. */
function rhn_phase_two_seed_store_terms() {
	if ( ! class_exists( 'WooCommerce' ) || get_option( 'rhn_phase_two_store_terms_v1_9' ) ) {
		return;
	}

	$terms = rhn_phase_two_store_terms();
	foreach ( $terms['wellness'] as $name ) {
		if ( ! term_exists( $name, 'product_cat' ) ) {
			wp_insert_term( $name, 'product_cat' );
		}
	}

	if ( taxonomy_exists( 'product_brand' ) ) {
		foreach ( $terms['brands'] as $name ) {
			if ( ! term_exists( $name, 'product_brand' ) ) {
				wp_insert_term( $name, 'product_brand' );
			}
		}
	}

	update_option( 'rhn_phase_two_store_terms_v1_9', gmdate( 'c' ), false );
}
add_action( 'init', 'rhn_phase_two_seed_store_terms', 99 );

/** Safely read a multi-select storefront query value. */
function rhn_phase_two_selected_store_filters( $key ) {
	if ( ! isset( $_GET[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return array();
	}

	$raw = wp_unslash( $_GET[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$raw = is_array( $raw ) ? $raw : array( $raw );
	return array_values( array_unique( array_filter( array_map( 'sanitize_title', $raw ) ) ) );
}

/** Apply the approved brand and wellness filters to the real WooCommerce catalog. */
function rhn_phase_two_filter_catalog_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	$post_type = $query->get( 'post_type' );
	$is_product_search = $query->is_search() && ( 'product' === $post_type || ( is_array( $post_type ) && in_array( 'product', $post_type, true ) ) );
	if ( ! $query->is_post_type_archive( 'product' ) && ! $query->is_tax( array( 'product_cat', 'product_brand' ) ) && ! $is_product_search ) {
		return;
	}

	$tax_query = (array) $query->get( 'tax_query' );
	$brands    = rhn_phase_two_selected_store_filters( 'filter_brand' );
	$wellness  = rhn_phase_two_selected_store_filters( 'filter_wellness' );

	if ( $brands && taxonomy_exists( 'product_brand' ) ) {
		$tax_query[] = array(
			'taxonomy' => 'product_brand',
			'field'    => 'slug',
			'terms'    => $brands,
			'operator' => 'IN',
		);
	}

	if ( $wellness ) {
		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $wellness,
			'operator' => 'IN',
		);
	}

	if ( $tax_query ) {
		$tax_query['relation'] = 'AND';
		$query->set( 'tax_query', $tax_query );
	}
}
add_action( 'pre_get_posts', 'rhn_phase_two_filter_catalog_query', 30 );

/** Build a shop URL without carrying review-only or unrelated parameters. */
function rhn_phase_two_shop_url( $args = array() ) {
	$shop_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : 0;
	$base    = $shop_id > 0 ? get_permalink( $shop_id ) : home_url( '/shop/' );
	return $args ? add_query_arg( $args, $base ) : $base;
}

/** Render one copy of the approved catalog filters. */
function rhn_phase_two_catalog_filters() {
	$terms     = rhn_phase_two_store_terms();
	$brands    = rhn_phase_two_selected_store_filters( 'filter_brand' );
	$wellness  = rhn_phase_two_selected_store_filters( 'filter_wellness' );
	$search    = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$orderby   = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
	<form class="rhn-catalog-filters" method="get" action="<?php echo esc_url( rhn_phase_two_shop_url() ); ?>">
		<div class="rhn-filter-title">
			<h2><?php esc_html_e( 'Filter Products', 'rebekahs-2026' ); ?></h2>
			<a href="<?php echo esc_url( rhn_phase_two_shop_url() ); ?>"><?php esc_html_e( 'Clear', 'rebekahs-2026' ); ?></a>
		</div>
		<details class="rhn-filter-group" open>
			<summary><span><?php esc_html_e( 'Brands', 'rebekahs-2026' ); ?></span><span><?php echo esc_html( count( $terms['brands'] ) ); ?></span></summary>
			<div class="rhn-filter-options">
				<?php foreach ( $terms['brands'] as $name ) : $slug = sanitize_title( $name ); ?>
					<label><input type="checkbox" name="filter_brand[]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( in_array( $slug, $brands, true ) ); ?>><?php echo esc_html( $name ); ?></label>
				<?php endforeach; ?>
			</div>
		</details>
		<details class="rhn-filter-group">
			<summary><span><?php esc_html_e( 'Wellness Categories', 'rebekahs-2026' ); ?></span><span><?php echo esc_html( count( $terms['wellness'] ) ); ?></span></summary>
			<div class="rhn-filter-options">
				<?php foreach ( $terms['wellness'] as $name ) : $slug = sanitize_title( $name ); ?>
					<label><input type="checkbox" name="filter_wellness[]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( in_array( $slug, $wellness, true ) ); ?>><?php echo esc_html( $name ); ?></label>
				<?php endforeach; ?>
			</div>
		</details>
		<?php if ( $search ) : ?><input type="hidden" name="s" value="<?php echo esc_attr( $search ); ?>"><input type="hidden" name="post_type" value="product"><?php endif; ?>
		<?php if ( $orderby ) : ?><input type="hidden" name="orderby" value="<?php echo esc_attr( $orderby ); ?>"><?php endif; ?>
		<button class="rhn-button" type="submit"><?php esc_html_e( 'Apply Filters', 'rebekahs-2026' ); ?></button>
	</form>
	<?php
}

/** Load the Phase Two styles and interaction helpers only where needed. */
function rhn_phase_two_enqueue_commerce_assets() {
	if ( ! rhn_phase_two_is_commerce_context() && ! is_front_page() ) {
		return;
	}

	$css_path = get_template_directory() . '/assets/css/components/phase-two-commerce.css';
	$js_path  = get_template_directory() . '/assets/js/components/phase-two-commerce.js';

	if ( file_exists( $css_path ) ) {
		wp_enqueue_style(
			'rhn-phase-two-commerce',
			rhn_theme_asset( 'css/components/phase-two-commerce.css' ),
			array( 'rhn-global-chrome' ),
			filemtime( $css_path )
		);
	}

	if ( file_exists( $js_path ) ) {
		wp_enqueue_script(
			'rhn-phase-two-commerce',
			rhn_theme_asset( 'js/components/phase-two-commerce.js' ),
			array(),
			filemtime( $js_path ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'rhn_phase_two_enqueue_commerce_assets', 30 );

/** Add a stable scope class without changing the approved global chrome. */
function rhn_phase_two_commerce_body_classes( $classes ) {
	$classes[] = 'rhn-phase-two-ecommerce-enabled';
	if ( rhn_phase_two_is_commerce_context() ) {
		$classes[] = 'rhn-commerce';
	}
	return $classes;
}
add_filter( 'body_class', 'rhn_phase_two_commerce_body_classes' );

/** Return the live cart count without assuming a cart session is available. */
function rhn_phase_two_cart_count() {
	if ( function_exists( 'WC' ) && WC()->cart ) {
		return (int) WC()->cart->get_cart_contents_count();
	}
	return 0;
}

/** Render the approved second-row commerce utility navigation. */
function rhn_phase_two_store_utility_row() {
	$count = rhn_phase_two_cart_count();
	?>
	<div class="rhn-store-chrome">
	<nav class="rhn-store-utility" aria-label="Online store navigation">
		<div class="rhn-store-utility__inner">
			<a class="rhn-store-utility__home" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Online Store</a>
			<div class="rhn-store-utility__actions">
				<button class="rhn-store-utility__tool rhn-store-search-toggle" type="button" aria-expanded="false" aria-controls="rhn-store-search">
					<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="6.5"></circle><path d="m16 16 4 4"></path></svg><span>Search</span>
				</button>
				<a class="rhn-store-utility__tool" href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="8" r="3.5"></circle><path d="M5.5 20c.6-4 2.9-6 6.5-6s5.9 2 6.5 6"></path></svg><span>My Account</span>
				</a>
				<a class="rhn-store-utility__tool rhn-store-utility__cart" href="<?php echo esc_url( home_url( '/cart/' ) ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M3 4h2l1.8 10.2h9.8l2-7.2H6"></path><circle cx="9" cy="19" r="1.3"></circle><circle cx="17" cy="19" r="1.3"></circle></svg><span>Cart</span><span class="rhn-store-cart-count" aria-label="<?php echo esc_attr( sprintf( _n( '%d item', '%d items', $count, 'rebekahs-2026' ), $count ) ); ?>"><?php echo esc_html( $count ); ?></span>
				</a>
			</div>
		</div>
	</nav>
	<div class="rhn-store-search" id="rhn-store-search" hidden>
		<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">
			<label class="visually-hidden" for="rhn-product-search"><?php esc_html_e( 'Search online store products', 'rebekahs-2026' ); ?></label>
			<input id="rhn-product-search" name="s" type="search" placeholder="<?php esc_attr_e( 'Search products', 'rebekahs-2026' ); ?>">
			<input name="post_type" type="hidden" value="product">
			<button type="submit"><?php esc_html_e( 'Search Online Store', 'rebekahs-2026' ); ?></button>
		</form>
	</div>
	</div>
	<?php
}

/** Keep the utility cart count current after an AJAX add-to-cart action. */
function rhn_phase_two_cart_count_fragment( $fragments ) {
	$count = rhn_phase_two_cart_count();
	ob_start();
	?>
	<span class="rhn-store-cart-count" aria-label="<?php echo esc_attr( sprintf( _n( '%d item', '%d items', $count, 'rebekahs-2026' ), $count ) ); ?>"><?php echo esc_html( $count ); ?></span>
	<?php
	$fragments['.rhn-store-cart-count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'rhn_phase_two_cart_count_fragment' );

/** Render the fifth approved Online Store footer group. */
function rhn_phase_two_store_footer_group() {
	?>
	<details class="footer-group rhn-store-footer-group" open>
		<summary>Online Store</summary>
		<div class="footer-links">
			<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Online Store</a>
			<a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>">My Account</a>
			<a href="<?php echo esc_url( home_url( '/cart/' ) ); ?>">Cart</a>
			<a href="<?php echo esc_url( home_url( '/checkout/' ) ); ?>">Checkout</a>
			<a href="<?php echo esc_url( home_url( '/refund_returns/' ) ); ?>">Shipping &amp; Returns</a>
		</div>
	</details>
	<?php
}

/** Render the exact approved v1.7 missing-product-photo component. */
function rhn_phase_two_product_image_fallback( $label = '', $include_view = false ) {
	$is_default_label = ! $label;
	$label            = $label ? $label : __( 'Product photo coming soon', 'rebekahs-2026' );
	?>
	<div class="rhn-product-image-fallback" role="img" aria-label="<?php echo esc_attr( $label ); ?>">
		<div class="rhn-product-image-fallback__content">
			<span class="rhn-product-image-fallback__mark" aria-hidden="true">
				<svg viewBox="0 0 110 100" focusable="false" aria-hidden="true">
					<path d="M50 84 C48 61 52 41 65 22" fill="none" stroke="#174C3C" stroke-width="7" stroke-linecap="round"/>
					<path d="M63 26 C69 8 87 8 93 11 C91 28 79 38 63 34Z" fill="#3F7D50"/>
					<path d="M52 48 C42 29 24 31 17 35 C22 52 35 61 53 57Z" fill="#D6A33A"/>
					<path d="M47 76 C57 64 70 60 82 61" fill="none" stroke="#174C3C" stroke-width="5" stroke-linecap="round"/>
				</svg>
			</span>
			<span class="rhn-product-image-fallback__kicker"><?php esc_html_e( 'Finishing touch', 'rebekahs-2026' ); ?></span>
			<strong class="rhn-product-image-fallback__label"><?php if ( $is_default_label ) : ?><?php esc_html_e( 'Product photo', 'rebekahs-2026' ); ?><br><?php esc_html_e( 'coming soon', 'rebekahs-2026' ); ?><?php else : ?><?php echo esc_html( $label ); ?><?php endif; ?></strong>
			<?php if ( $include_view ) : ?><span class="rhn-product-image-fallback__view" data-rhn-placeholder-current-view><?php esc_html_e( 'Front image', 'rebekahs-2026' ); ?></span><?php endif; ?>
		</div>
	</div>
	<?php
}

/** Replace WooCommerce's default placeholder without printing a second image. */
function rhn_phase_two_product_image_markup( $image, $product, $size, $attr, $placeholder ) {
	if ( ! $placeholder || ! is_a( $product, 'WC_Product' ) || $product->get_image_id() ) {
		return $image;
	}

	ob_start();
	rhn_phase_two_product_image_fallback();
	return ob_get_clean();
}
add_filter( 'woocommerce_product_get_image', 'rhn_phase_two_product_image_markup', 10, 5 );

/** Add the approved friendly help line after the single-product purchase controls. */
function rhn_phase_two_product_help_line() {
	?>
	<p class="rhn-product-help">Need product help? Call <a href="tel:2488432011">248-843-2011</a>.</p>
	<?php
}

/**
 * Render directions/warnings only when verified content exists.
 *
 * The staging content process may populate this field after source review.
 */
function rhn_phase_two_product_directions_warnings() {
	if ( ! is_product() ) {
		return;
	}

	$content = get_post_meta( get_the_ID(), '_rhn_directions_warnings', true );
	if ( ! is_string( $content ) || '' === trim( $content ) ) {
		return;
	}
	?>
	<details class="rhn-product-disclosure">
		<summary><?php esc_html_e( 'Directions & Warnings', 'rebekahs-2026' ); ?></summary>
		<div><?php echo wp_kses_post( wpautop( $content ) ); ?></div>
	</details>
	<?php
}
add_action( 'woocommerce_after_single_product_summary', 'rhn_phase_two_product_directions_warnings', 7 );

/** Keep the account system limited to features this physical-product store uses. */
function rhn_phase_two_account_menu_items( $items ) {
	unset( $items['downloads'] );
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'rhn_phase_two_account_menu_items', 20 );

/** A direct legacy Downloads endpoint should return customers to their account dashboard. */
function rhn_phase_two_redirect_unused_downloads_endpoint() {
	if ( function_exists( 'is_account_page' ) && is_account_page()
		&& function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'downloads' ) ) {
		wp_safe_redirect( wc_get_account_endpoint_url( 'dashboard' ) );
		exit;
	}
}
add_action( 'template_redirect', 'rhn_phase_two_redirect_unused_downloads_endpoint', 20 );

/** Identify the private Cloudways staging host without affecting the live website. */
function rhn_phase_two_is_staging_host() {
	$host = (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST );
	return false !== strpos( $host, 'cloudwaysapps.com' );
}

/**
 * Quarantine every product that existed before the Revel/Kosmos connection.
 *
 * This runs once on staging, records the exact IDs, and leaves future imports alone.
 * Existing content remains recoverable but cannot appear in the catalog.
 */
function rhn_phase_two_quarantine_existing_legacy_products() {
	if ( ! rhn_phase_two_is_staging_host() || get_option( 'rhn_phase_two_legacy_quarantine_v1_8' ) ) {
		return;
	}

	$product_ids = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'private', 'pending', 'future', 'draft', 'trash' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	foreach ( $product_ids as $product_id ) {
		update_post_meta( $product_id, '_rhn_phase_two_legacy_quarantined', 'yes' );
		$status = get_post_status( $product_id );
		if ( in_array( $status, array( 'publish', 'private', 'pending', 'future' ), true ) ) {
			wp_update_post( array( 'ID' => $product_id, 'post_status' => 'draft' ) );
		}
		update_post_meta( $product_id, '_backorders', 'no' );
	}

	update_option(
		'rhn_phase_two_legacy_quarantine_v1_8',
		array(
			'created_at_utc' => gmdate( 'c' ),
			'product_ids'    => array_map( 'intval', $product_ids ),
			'count'          => count( $product_ids ),
		),
		false
	);
}
add_action( 'admin_init', 'rhn_phase_two_quarantine_existing_legacy_products', 5 );

/** Return whether a product belongs to the pre-connection legacy quarantine. */
function rhn_phase_two_is_quarantined_legacy_product( $product_id ) {
	return rhn_phase_two_is_staging_host()
		&& 'yes' === get_post_meta( (int) $product_id, '_rhn_phase_two_legacy_quarantined', true );
}

/** Quarantined records stay recoverable but never appear in the store or become purchasable. */
add_filter( 'woocommerce_product_is_visible', function( $visible, $product_id ) {
	return rhn_phase_two_is_quarantined_legacy_product( $product_id ) ? false : $visible;
}, 20, 2 );
add_filter( 'woocommerce_is_purchasable', function( $purchasable, $product ) {
	return $product && rhn_phase_two_is_quarantined_legacy_product( $product->get_id() ) ? false : $purchasable;
}, 20, 2 );

/** Give any quarantined legacy record a neutral front-end title on staging. */
function rhn_phase_two_staging_preview_document_title( $title ) {
	$post = get_queried_object();
	if ( is_singular( 'product' )
		&& $post instanceof WP_Post
		&& rhn_phase_two_is_quarantined_legacy_product( $post->ID ) ) {
		return __( 'Product Preview — Photo Coming Soon', 'rebekahs-2026' );
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'rhn_phase_two_staging_preview_document_title', 20 );

/** Keep account, cart, checkout, and internal search surfaces out of search indexes. */
function rhn_phase_two_private_store_robots( $robots ) {
	if ( ( function_exists( 'is_account_page' ) && is_account_page() )
		|| ( function_exists( 'is_cart' ) && is_cart() )
		|| ( function_exists( 'is_checkout' ) && is_checkout() )
		|| ( is_search() && 'product' === get_query_var( 'post_type' ) ) ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'rhn_phase_two_private_store_robots' );

/** Enforce approved store-scope decisions independently of imported catalog data. */
add_filter( 'woocommerce_product_get_backorders', function() { return 'no'; } );
add_filter( 'woocommerce_product_variation_get_backorders', function() { return 'no'; } );
add_filter( 'woocommerce_product_reviews_enabled', '__return_false' );
add_filter( 'woocommerce_return_to_shop_text', function() { return __( 'Return to Online Store', 'rebekahs-2026' ); } );

/** Return whether a delivery address identifies a post-office box. */
function rhn_phase_two_address_is_po_box( $address ) {
	$address = html_entity_decode( wp_strip_all_tags( (string) $address ), ENT_QUOTES, get_bloginfo( 'charset' ) );
	$address = preg_replace( '/\s+/', ' ', trim( $address ) );

	return 1 === preg_match( '/(?:^|[^a-z0-9])(?:p\s*\.?\s*o\s*\.?|post\s+office)\s*box\b/i', $address );
}

/** Add one clear checkout error when the actual delivery address is a PO box. */
function rhn_phase_two_validate_delivery_address( $data, $errors ) {
	if ( ! $errors instanceof WP_Error || ( function_exists( 'WC' ) && WC()->cart && ! WC()->cart->needs_shipping() ) ) {
		return;
	}

	$prefix  = ! empty( $data['ship_to_different_address'] ) ? 'shipping_' : 'billing_';
	$address = trim( implode( ' ', array( $data[ $prefix . 'address_1' ] ?? '', $data[ $prefix . 'address_2' ] ?? '' ) ) );

	if ( rhn_phase_two_address_is_po_box( $address ) ) {
		$errors->add(
			'rhn_po_box_delivery',
			__( 'We cannot ship to PO boxes. Please enter a street delivery address.', 'rebekahs-2026' )
		);
	}
}
add_action( 'woocommerce_after_checkout_validation', 'rhn_phase_two_validate_delivery_address', 20, 2 );

/** Keep the same PO-box rule effective if checkout later moves to WooCommerce Blocks. */
function rhn_phase_two_validate_store_api_delivery_address( $order ) {
	if ( ! is_a( $order, 'WC_Order' ) || ( method_exists( $order, 'needs_shipping_address' ) && ! $order->needs_shipping_address() ) ) {
		return;
	}

	$address = trim( $order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2() );
	if ( '' === $address ) {
		$address = trim( $order->get_billing_address_1() . ' ' . $order->get_billing_address_2() );
	}

	if ( rhn_phase_two_address_is_po_box( $address ) ) {
		throw new Exception( esc_html__( 'We cannot ship to PO boxes. Please enter a street delivery address.', 'rebekahs-2026' ) );
	}
}
add_action( 'woocommerce_store_api_checkout_update_order_meta', 'rhn_phase_two_validate_store_api_delivery_address', 20 );

/** Keep the approved cart → checkout → confirmation orientation on real purchase pages. */
function rhn_phase_two_purchase_stepper() {
	$step = 1;
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		$step = function_exists( 'is_order_received_page' ) && is_order_received_page() ? 3 : 2;
	}
	$shop_url     = rhn_phase_two_shop_url();
	$cart_url     = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
	$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
	?>
	<nav class="rhn-purchase-stepper" aria-label="Purchase progress">
		<a class="<?php echo 1 === $step ? 'is-current' : ''; ?>" href="<?php echo esc_url( $cart_url ); ?>" <?php echo 1 === $step ? 'aria-current="step"' : ''; ?>><span>1</span><?php esc_html_e( 'Cart', 'rebekahs-2026' ); ?></a>
		<a class="<?php echo 2 === $step ? 'is-current' : ''; ?>" href="<?php echo esc_url( $checkout_url ); ?>" <?php echo 2 === $step ? 'aria-current="step"' : ''; ?>><span>2</span><?php esc_html_e( 'Checkout', 'rebekahs-2026' ); ?></a>
		<span class="<?php echo 3 === $step ? 'is-current' : ''; ?>" <?php echo 3 === $step ? 'aria-current="step"' : ''; ?>><span>3</span><?php esc_html_e( 'Confirmation', 'rebekahs-2026' ); ?></span>
	</nav>
	<?php
}

/** Add the approved empty-cart guidance without inventing products or delivery promises. */
function rhn_phase_two_empty_cart_message( $message ) {
	return '<div class="rhn-empty-cart-mark" aria-hidden="true">&#128722;</div><h2>' . esc_html__( 'Your cart is empty', 'rebekahs-2026' ) . '</h2><p>' . esc_html__( 'Browse the online store to find Rebekah’s products.', 'rebekahs-2026' ) . '</p>';
}
add_filter( 'wc_empty_cart_message', 'rhn_phase_two_empty_cart_message' );

/** Remove the standard review tab; approved product facts remain in the main layout. */
function rhn_phase_two_product_tabs( $tabs ) {
	unset( $tabs['reviews'] );
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'rhn_phase_two_product_tabs' );
add_filter( 'loop_shop_per_page', function() { return 12; }, 20 );
