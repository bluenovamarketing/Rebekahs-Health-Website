<?php
/**
 * Theme setup and asset loading.
 *
 * @package Rebekahs_2026
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RHN_THEME_VERSION', '1.0.1' );

function rhn_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 180, 'width' => 480, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'align-wide' );
	register_nav_menus( array( 'primary' => __( 'Primary Menu', 'rebekahs-2026' ) ) );
}
add_action( 'after_setup_theme', 'rhn_theme_setup' );

function rhn_theme_asset( $path ) {
	return trailingslashit( get_template_directory_uri() ) . 'assets/' . ltrim( $path, '/' );
}

function rhn_template_key() {
	if ( is_front_page() ) {
		return 'home';
	}
	if ( is_home() ) {
		return 'blog';
	}
	if ( is_post_type_archive( 'tribe_events' ) ) {
		return 'events';
	}
	if ( is_singular( 'post' ) ) {
		return 'single-post';
	}
	if ( is_singular( 'tribe_events' ) ) {
		return 'event-detail';
	}
	if ( is_singular( 'medical-practicioner' ) ) {
		return 'practitioner-profile';
	}
	if ( is_404() ) {
		return '404';
	}
	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( in_array( $slug, array( 'lapeer', 'grand-blanc', 'clarkston', 'lake-orion' ), true ) ) {
			return $slug;
		}
		return $slug;
	}
	return 'default';
}

function rhn_enqueue_assets() {
	$key = sanitize_key( rhn_template_key() );
	wp_enqueue_style( 'rhn-fonts', 'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap', array(), null );
	wp_enqueue_style( 'rhn-theme', get_stylesheet_uri(), array(), RHN_THEME_VERSION );
	wp_enqueue_style( 'rhn-chrome', rhn_theme_asset( 'css/chrome.css' ), array( 'rhn-theme' ), RHN_THEME_VERSION );

	$page_css = get_template_directory() . '/assets/css/pages/' . $key . '.css';
	if ( file_exists( $page_css ) ) {
		wp_enqueue_style( 'rhn-page-' . $key, rhn_theme_asset( 'css/pages/' . $key . '.css' ), array( 'rhn-chrome' ), filemtime( $page_css ) );
	}

	wp_enqueue_script( 'rhn-chrome', rhn_theme_asset( 'js/chrome.js' ), array(), RHN_THEME_VERSION, true );
	$page_js = get_template_directory() . '/assets/js/pages/' . $key . '.js';
	if ( file_exists( $page_js ) && filesize( $page_js ) > 3 ) {
		wp_enqueue_script( 'rhn-page-' . $key, rhn_theme_asset( 'js/pages/' . $key . '.js' ), array(), filemtime( $page_js ), true );
	}

	// The homepage's approved original sections are hydrated from the bundled
	// source after the main homepage script exposes its encoded markup.
	if ( 'home' === $key ) {
		$home_sections_js = get_template_directory() . '/assets/js/pages/home-sections.js';
		if ( file_exists( $home_sections_js ) && filesize( $home_sections_js ) > 3 ) {
			wp_enqueue_script(
				'rhn-home-sections',
				rhn_theme_asset( 'js/pages/home-sections.js' ),
				array( 'rhn-page-home' ),
				filemtime( $home_sections_js ),
				true
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'rhn_enqueue_assets', 20 );

// Keep The Events Calendar data and URL while rendering the approved theme archive.
add_filter( 'tribe_events_views_v2_use_wp_template_hierarchy', '__return_true' );

function rhn_events_archive_seo_title( $title ) {
	if ( is_post_type_archive( 'tribe_events' ) ) {
		return "Classes & Events | Rebekah's Health & Nutrition";
	}
	return $title;
}
add_filter( 'seopress_titles_title', 'rhn_events_archive_seo_title', 99 );
add_filter( 'seopress_social_fb_title', 'rhn_events_archive_seo_title', 99 );
add_filter( 'seopress_social_twitter_title', 'rhn_events_archive_seo_title', 99 );

function rhn_events_archive_seo_description( $description ) {
	if ( is_post_type_archive( 'tribe_events' ) ) {
		return "Explore upcoming wellness classes, community events, local makers and in-store pop-ups at Rebekah's four Michigan locations.";
	}
	return $description;
}
add_filter( 'seopress_titles_desc', 'rhn_events_archive_seo_description', 99 );
add_filter( 'seopress_social_fb_desc', 'rhn_events_archive_seo_description', 99 );
add_filter( 'seopress_social_twitter_desc', 'rhn_events_archive_seo_description', 99 );

/** Approved page-level SEO copy for the assembled Phase One pages. */
function rhn_page_seo_copy() {
	return array(
		'home'                    => array( "Michigan Health & Nutrition Stores | Rebekah's", "Visit Rebekah's four Michigan health and nutrition stores for trusted supplements, natural products, practical education, local events and personal guidance." ),
		'our-story'               => array( "Our Story | Rebekah's Health & Nutrition", "Learn how Rebekah Spencer's personal wellness journey grew into four Michigan health and nutrition stores focused on education, trusted products and genuine care." ),
		'our-team'                => array( "Our Team | Rebekah's Health & Nutrition", "Meet the people who help Michigan families navigate supplements, natural products, store questions and everyday wellness choices at Rebekah's." ),
		'locations'               => array( "Michigan Store Locations | Rebekah's Health & Nutrition", "Compare Rebekah's health and nutrition store locations in Lapeer, Grand Blanc, Clarkston and Lake Orion, including hours, phone numbers and directions." ),
		'lapeer'                  => array( "Health & Nutrition Store in Lapeer, MI | Rebekah's", "Visit Rebekah's in Lapeer for trusted supplements, natural products, practical wellness guidance and local classes at 588 S. Main Street." ),
		'grand-blanc'             => array( "Health & Nutrition Store in Grand Blanc, MI | Rebekah's", "Visit Rebekah's in Grand Blanc for trusted supplements, natural products, practical wellness guidance and local events at 252 Perry Road." ),
		'clarkston'               => array( "Health & Nutrition Store in Clarkston, MI | Rebekah's", "Visit Rebekah's in Clarkston for trusted supplements, natural products, practical wellness guidance and local events on Dixie Highway." ),
		'lake-orion'              => array( "Health & Nutrition Store in Lake Orion, MI | Rebekah's", "Visit Rebekah's in Lake Orion for trusted supplements, natural products, practical wellness guidance and local events on S. Lapeer Road." ),
		'blog'                    => array( "Wellness Source Blog | Rebekah's Health & Nutrition", "Explore practical wellness education, product guidance, seasonal tips and community updates from Rebekah's Health & Nutrition." ),
		'in-store-products'       => array( "Natural Products & Supplements in Michigan | Rebekah's", "Explore supplements, natural foods, skincare, household products and Rebekah's private-label formulas available at four Michigan stores." ),
		'practitioners'           => array( "Wellness Practitioners in Michigan | Rebekah's", "Browse the current directory of independent wellness practitioners connected with Rebekah's and view each practitioner's existing profile and contact details." ),
		'contact-us'              => array( "Contact Rebekah's Health & Nutrition", "Call your nearest Rebekah's store for product availability and local questions, or send the team a general, non-urgent message." ),
		'privacy-policy'          => array( "Privacy Policy | Rebekah's Health & Nutrition", "Read how Rebekah's Health & Nutrition handles website information, contact-form details, cookies and third-party services." ),
		'refund_returns'          => array( "Refund & Returns Policy | Rebekah's Health & Nutrition", "Read Rebekah's current refund and returns policy for eligible in-store purchases, exclusions and return requirements." ),
		'terms-conditions'        => array( "Terms & Conditions | Rebekah's Health & Nutrition", "Review the terms that apply when using Rebekah's website, educational information, external links and referral resources." ),
		'disclaimer'              => array( "Wellness & Website Disclaimer | Rebekah's", "Review important information about educational content, medical advice limitations, product claims, individual results and external resources." ),
		'shop-fullscript'         => array( "Shop Fullscript Through Rebekah's", "Learn about Fullscript and continue to the external Fullscript platform to browse professional-grade supplements through Rebekah's referral relationship." ),
		'shop-designs-for-health' => array( "Shop Designs for Health Through Rebekah's", "Learn about Designs for Health products and continue to the external partner website to browse and purchase outside Rebekah's website." ),
		'shop-lifewave'           => array( "Explore LifeWave Through Rebekah's", "Learn what to consider before exploring LifeWave products, then continue to the clearly identified external partner website." ),
		'peptides-injectables'    => array( "Peptides & Injectables Referral Information | Rebekah's", "Read cautious eligibility and medical-review information before continuing to the independent external provider through Rebekah's referral link." ),
	);
}

function rhn_page_seo_title( $title ) {
	$copy = rhn_page_seo_copy();
	$key  = rhn_template_key();
	return isset( $copy[ $key ] ) ? $copy[ $key ][0] : $title;
}
add_filter( 'seopress_titles_title', 'rhn_page_seo_title', 98 );
add_filter( 'seopress_social_fb_title', 'rhn_page_seo_title', 98 );
add_filter( 'seopress_social_twitter_title', 'rhn_page_seo_title', 98 );

function rhn_page_seo_description( $description ) {
	$copy = rhn_page_seo_copy();
	$key  = rhn_template_key();
	return isset( $copy[ $key ] ) ? $copy[ $key ][1] : $description;
}
add_filter( 'seopress_titles_desc', 'rhn_page_seo_description', 98 );
add_filter( 'seopress_social_fb_desc', 'rhn_page_seo_description', 98 );
add_filter( 'seopress_social_twitter_desc', 'rhn_page_seo_description', 98 );

/** Add truthful Organization and store-location structured data. */
function rhn_output_business_schema() {
	$key     = rhn_template_key();
	$base_id = home_url( '/#organization' );
	$schema  = null;

	if ( 'home' === $key ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Organization',
			'@id'      => $base_id,
			'name'     => "Rebekah's Health & Nutrition",
			'url'      => home_url( '/' ),
			'logo'     => rhn_theme_asset( 'tmp/source/current-site-logo-live.png' ),
			'sameAs'   => array(
				'https://www.instagram.com/rebekahs_health_and_nutrition/',
				'https://www.tiktok.com/@rebekahspureliving',
			),
		);
	}

	$stores = array(
		'lapeer'      => array( 'Lapeer', '588 S. Main Street', 'Lapeer', '48446', '+18106608585', array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ) ),
		'grand-blanc' => array( 'Grand Blanc', '252 Perry Road, Suite 4', 'Grand Blanc', '48439', '+18108664642', array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ) ),
		'clarkston'   => array( 'Clarkston', '7093 Dixie Highway, Suite B', 'Clarkston', '48346', '+12488432011', array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ) ),
		'lake-orion'  => array( 'Lake Orion', '1095 S. Lapeer Road', 'Lake Orion', '48360', '+12489298990', array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ) ),
	);

	if ( isset( $stores[ $key ] ) ) {
		$store       = $stores[ $key ];
		$weekend_days = 'lake-orion' === $key ? array( 'Saturday', 'Sunday' ) : array( 'Sunday' );
		$schema      = array(
			'@context'            => 'https://schema.org',
			'@type'               => 'HealthAndBeautyBusiness',
			'@id'                 => home_url( '/locations/' . $key . '/#store' ),
			'name'                => "Rebekah's Health & Nutrition - " . $store[0],
			'url'                 => home_url( '/locations/' . $key . '/' ),
			'telephone'           => $store[4],
			'parentOrganization'  => array( '@id' => $base_id ),
			'address'             => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => $store[1],
				'addressLocality' => $store[2],
				'addressRegion'   => 'MI',
				'postalCode'      => $store[3],
				'addressCountry'  => 'US',
			),
			'openingHoursSpecification' => array(
				array( '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $store[5], 'opens' => '09:00', 'closes' => '19:00' ),
				array( '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $weekend_days, 'opens' => '10:00', 'closes' => '17:00' ),
			),
		);
	}

	if ( $schema ) {
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_head', 'rhn_output_business_schema', 40 );

function rhn_body_classes( $classes ) {
	$classes[] = 'rhn-site';
	$classes[] = 'rhn-template-' . sanitize_html_class( rhn_template_key() );
	return $classes;
}
add_filter( 'body_class', 'rhn_body_classes' );

/** Consolidate the legacy Meet the Owner URL into the approved Our Story page. */
function rhn_redirect_meet_the_owner() {
	if ( is_page( 'meet-the-owner' ) ) {
		wp_safe_redirect( home_url( '/our-story/' ), 301, 'Rebekahs 2026 Theme' );
		exit;
	}
}
add_action( 'template_redirect', 'rhn_redirect_meet_the_owner', 5 );

function rhn_post_topic_key( $post_id = 0 ) {
	$names = strtolower( implode( ' ', wp_get_post_categories( $post_id ?: get_the_ID(), array( 'fields' => 'names' ) ) ) );
	$keys = array();
	if ( false !== strpos( $names, 'product' ) ) {
		$keys[] = 'products';
	}
	if ( false !== strpos( $names, 'expert' ) || false !== strpos( $names, 'q&a' ) ) {
		$keys[] = 'experts';
	}
	if ( false !== strpos( $names, 'health' ) || empty( $keys ) ) {
		$keys[] = 'healthy';
	}
	return implode( ' ', array_unique( $keys ) );
}

function rhn_reading_time( $post_id = 0 ) {
	$content = get_post_field( 'post_content', $post_id ?: get_the_ID() );
	return max( 1, (int) ceil( str_word_count( wp_strip_all_tags( $content ) ) / 220 ) );
}

function rhn_post_image( $post_id = 0, $size = 'large' ) {
	$post_id = $post_id ?: get_the_ID();
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail( $post_id, $size, array( 'loading' => 'lazy' ) );
	}
	return '<span class="rhn-image-placeholder" aria-hidden="true">Wellness Source</span>';
}

function rhn_release_legacy_lapeer_slug() {
	$attachments = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'name'           => 'lapeer',
		'posts_per_page' => 10,
		'fields'         => 'ids',
	) );
	foreach ( $attachments as $attachment_id ) {
		wp_update_post( array(
			'ID'        => $attachment_id,
			'post_name' => 'lapeer-location-media-' . $attachment_id,
		) );
	}
}

function rhn_seed_required_pages() {
	rhn_release_legacy_lapeer_slug();
	$pages = array(
		'Home'                    => 'home',
		'Our Story'               => 'our-story',
		'Our Team'                => 'our-team',
		'Locations'               => 'locations',
		'Lapeer'                  => 'lapeer',
		'Grand Blanc'             => 'grand-blanc',
		'Clarkston'               => 'clarkston',
		'Lake Orion'              => 'lake-orion',
		'Classes & Events'        => 'events',
		'Blog'                    => 'blog',
		'In-Store Products'       => 'in-store-products',
		'Practitioners'           => 'practitioners',
		'Contact Us'              => 'contact-us',
		'Privacy Policy'          => 'privacy-policy',
		'Refund & Returns'        => 'refund_returns',
		'Terms & Conditions'      => 'terms-conditions',
		'Disclaimer'              => 'disclaimer',
		'Shop Fullscript'         => 'shop-fullscript',
		'Shop Designs for Health' => 'shop-designs-for-health',
		'Shop LifeWave'           => 'shop-lifewave',
		'Peptides & Injectables'  => 'peptides-injectables',
	);
	$ids = array();
	foreach ( $pages as $title => $slug ) {
		$parent_id = 0;
		if ( in_array( $slug, array( 'lapeer', 'grand-blanc', 'clarkston', 'lake-orion' ), true ) ) {
			$locations = get_page_by_path( 'locations' );
			$parent_id = $locations ? $locations->ID : 0;
		}
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			if ( 'publish' !== $existing->post_status || (int) $existing->post_parent !== (int) $parent_id ) {
				wp_update_post( array(
					'ID'          => $existing->ID,
					'post_status' => 'publish',
					'post_parent' => $parent_id,
				) );
			}
			$ids[ $slug ] = $existing->ID;
			continue;
		}
		$ids[ $slug ] = wp_insert_post( array(
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_parent' => $parent_id,
		) );
	}
	if ( ! empty( $ids['home'] ) && ! is_wp_error( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', (int) $ids['home'] );
	}
	if ( ! empty( $ids['blog'] ) && ! is_wp_error( $ids['blog'] ) ) {
		update_option( 'page_for_posts', (int) $ids['blog'] );
	}
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'rhn_seed_required_pages' );

function rhn_run_schema_migrations() {
	$schema_version = '1.0.3';
	if ( get_option( 'rhn_theme_schema_version' ) === $schema_version ) {
		return;
	}
	rhn_seed_required_pages();

	// Remove only staging duplicates created while the legacy media slug was in use.
	$host      = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) : '';
	$locations = get_page_by_path( 'locations' );
	$canonical = get_page_by_path( 'locations/lapeer' );
	if ( false !== strpos( $host, 'cloudwaysapps.com' ) && $locations && $canonical ) {
		$lapeer_pages = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'private', 'pending', 'future' ),
			'post_parent'    => $locations->ID,
			'posts_per_page' => -1,
		) );
		foreach ( $lapeer_pages as $lapeer_page ) {
			if ( $lapeer_page->ID !== $canonical->ID && 'Lapeer' === $lapeer_page->post_title && 0 === strpos( $lapeer_page->post_name, 'lapeer-' ) ) {
				wp_trash_post( $lapeer_page->ID );
			}
		}
	}

	update_option( 'rhn_theme_schema_version', $schema_version, false );
}
add_action( 'init', 'rhn_run_schema_migrations', 20 );

function rhn_disable_commerce_during_phase_one( $items, $args ) {
	return $items;
}
add_filter( 'wp_nav_menu_objects', 'rhn_disable_commerce_during_phase_one', 10, 2 );

add_action( 'elementor/theme/register_locations', function( $manager ) {
	$manager->register_all_core_location();
} );
