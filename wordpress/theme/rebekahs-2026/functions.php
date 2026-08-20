<?php
/**
 * Theme setup and asset loading.
 *
 * @package Rebekahs_2026
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RHN_THEME_VERSION', '1.0.32' );

/** Google Tag Manager container used for production analytics and marketing tags. */
define( 'RHN_GTM_CONTAINER_ID', 'GTM-MQHBBWCG' );

/** Output the Google Tag Manager loader as high in the document head as possible. */
function rhn_google_tag_manager_head() {
	?>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo esc_js( RHN_GTM_CONTAINER_ID ); ?>');</script>
	<!-- End Google Tag Manager -->
	<?php
}
add_action( 'wp_head', 'rhn_google_tag_manager_head', 0 );

/** Output the no-JavaScript Google Tag Manager fallback after the opening body tag. */
function rhn_google_tag_manager_body() {
	?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( RHN_GTM_CONTAINER_ID ); ?>" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php
}
add_action( 'wp_body_open', 'rhn_google_tag_manager_body', 0 );

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

/** Provide branded favicon assets until a WordPress Site Icon is configured. */
function rhn_site_icons() {
	if ( has_site_icon() ) {
		return;
	}

	$favicon_url = add_query_arg( 'ver', RHN_THEME_VERSION, rhn_theme_asset( 'img/favicon-32.png' ) );
	$touch_url   = add_query_arg( 'ver', RHN_THEME_VERSION, rhn_theme_asset( 'img/apple-touch-icon-180.png' ) );
	$site_url    = add_query_arg( 'ver', RHN_THEME_VERSION, rhn_theme_asset( 'img/rebekahs-site-icon-512.png' ) );

	echo '<link rel="icon" href="' . esc_url( $favicon_url ) . '" sizes="32x32" type="image/png">' . "\n";
	echo '<link rel="icon" href="' . esc_url( $site_url ) . '" sizes="512x512" type="image/png">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $touch_url ) . '" sizes="180x180">' . "\n";
}
add_action( 'wp_head', 'rhn_site_icons', 1 );

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

	// Load the approved global component contract last so legacy page-mockup
	// styles can never restyle the sitewide header or footer.
	$global_dependencies = file_exists( $page_css ) ? array( 'rhn-page-' . $key ) : array( 'rhn-chrome' );
	wp_enqueue_style( 'rhn-global-chrome', rhn_theme_asset( 'css/global-chrome.css' ), $global_dependencies, RHN_THEME_VERSION );

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

		$social_css = get_template_directory() . '/assets/css/components/social-feeds.css';
		if ( file_exists( $social_css ) ) {
			wp_enqueue_style(
				'rhn-social-feeds',
				rhn_theme_asset( 'css/components/social-feeds.css' ),
				array( 'rhn-page-home' ),
				filemtime( $social_css )
			);
		}

		$social_js = get_template_directory() . '/assets/js/components/social-feeds.js';
		if ( file_exists( $social_js ) && filesize( $social_js ) > 3 ) {
			wp_enqueue_script(
				'rhn-social-feeds',
				rhn_theme_asset( 'js/components/social-feeds.js' ),
				array( 'rhn-page-home' ),
				filemtime( $social_js ),
				true
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'rhn_enqueue_assets', 20 );

function rhn_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'rhn_resource_hints', 10, 2 );

function rhn_home_critical_hints() {
	if ( is_front_page() ) {
		echo '<link rel="preload" as="image" href="' . esc_url( rhn_theme_asset( 'output/rebekahs-hero-background.webp' ) ) . '" fetchpriority="high">' . "\n";
	}
}
add_action( 'wp_head', 'rhn_home_critical_hints', 2 );

/** Keep the logo eager, while deferring below-the-fold homepage images. */
function rhn_lazy_home_images( $html ) {
	$seen = 0;
	return preg_replace_callback(
		'/<img\b(?![^>]*\bloading\s*=)[^>]*>/i',
		function( $matches ) use ( &$seen ) {
			$seen++;
			if ( 1 === $seen ) {
				return $matches[0];
			}
			return preg_replace( '/<img\b/i', '<img loading="lazy" decoding="async"', $matches[0], 1 );
		},
		$html
	);
}

function rhn_start_home_image_buffer() {
	if ( is_front_page() ) {
		ob_start( 'rhn_lazy_home_images' );
	}
}
add_action( 'template_redirect', 'rhn_start_home_image_buffer', 20 );

// Keep The Events Calendar data and URL while rendering the approved theme archive.
add_filter( 'tribe_events_views_v2_use_wp_template_hierarchy', '__return_true' );

/**
 * Register a dedicated, single-choice event type field for The Events Calendar.
 * Store location remains in Event Categories; this taxonomy is only for type.
 */
function rhn_register_event_type_taxonomy() {
	register_taxonomy(
		'rhn_event_type',
		array( 'tribe_events' ),
		array(
			'labels'            => array(
				'name'          => __( 'Event Types', 'rebekahs-2026' ),
				'singular_name' => __( 'Event Type', 'rebekahs-2026' ),
				'menu_name'     => __( 'Event Types', 'rebekahs-2026' ),
				'all_items'     => __( 'All Event Types', 'rebekahs-2026' ),
				'edit_item'     => __( 'Edit Event Type', 'rebekahs-2026' ),
				'add_new_item'  => __( 'Add New Event Type', 'rebekahs-2026' ),
			),
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'show_in_quick_edit'=> false,
			'query_var'         => false,
			'rewrite'           => false,
			'meta_box_cb'       => 'rhn_event_type_radio_metabox',
		)
	);

	if ( ! get_option( 'rhn_event_type_terms_seeded_20260819' ) ) {
		$types = array(
			'class'       => 'Classes & education',
			'community'   => 'Community & pop-ups',
			'health-fair' => 'Health fairs',
			'market'      => 'Markets & local makers',
		);
		foreach ( $types as $slug => $name ) {
			if ( ! term_exists( $slug, 'rhn_event_type' ) ) {
				wp_insert_term( $name, 'rhn_event_type', array( 'slug' => $slug ) );
			}
		}
		update_option( 'rhn_event_type_terms_seeded_20260819', 1, false );
	}
}
add_action( 'init', 'rhn_register_event_type_taxonomy', 20 );

/** Render Event Type as radio buttons so each event can have only one type. */
function rhn_event_type_radio_metabox( $post, $box ) {
	$taxonomy = $box['args']['taxonomy'];
	$terms    = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
	$current  = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
	$current  = is_wp_error( $current ) || ! $current ? 0 : (int) reset( $current );

	wp_nonce_field( 'rhn_save_event_type', 'rhn_event_type_nonce' );
	echo '<div id="rhn-event-type-options">';
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			printf(
				'<p><label><input type="radio" name="tax_input[%1$s][]" value="%2$d" %3$s> %4$s</label></p>',
				esc_attr( $taxonomy ),
				(int) $term->term_id,
				checked( $current, (int) $term->term_id, false ),
				esc_html( $term->name )
			);
		}
	}
	echo '<p class="description">Choose one type. Set the store separately under Event Categories.</p></div>';
}

/** Enforce a single saved type even if another editor submits multiple values. */
function rhn_save_single_event_type( $post_id ) {
	if ( ! isset( $_POST['rhn_event_type_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rhn_event_type_nonce'] ) ), 'rhn_save_event_type' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) || wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return;
	}
	$submitted = isset( $_POST['tax_input']['rhn_event_type'] ) ? (array) wp_unslash( $_POST['tax_input']['rhn_event_type'] ) : array();
	$term_id   = $submitted ? absint( reset( $submitted ) ) : 0;
	wp_set_object_terms( $post_id, $term_id ? array( $term_id ) : array(), 'rhn_event_type', false );
}
add_action( 'save_post_tribe_events', 'rhn_save_single_event_type', 20 );

/** Assign existing events once so the new field preserves the current archive result. */
function rhn_migrate_existing_event_types() {
	if ( get_option( 'rhn_event_types_migrated_20260819' ) || ! taxonomy_exists( 'rhn_event_type' ) ) {
		return;
	}
	$event_ids = get_posts(
		array(
			'post_type'      => 'tribe_events',
			'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page' => 200,
			'fields'         => 'ids',
		)
	);
	foreach ( $event_ids as $event_id ) {
		if ( wp_get_object_terms( $event_id, 'rhn_event_type', array( 'fields' => 'ids' ) ) ) {
			continue;
		}
		$text = strtolower( get_the_title( $event_id ) . ' ' . wp_strip_all_tags( get_post_field( 'post_content', $event_id ) ) );
		if ( preg_match( '/health fair|wellness fair/', $text ) ) {
			$type = 'health-fair';
		} elseif ( preg_match( '/market|microgreen|maker|sale/', $text ) ) {
			$type = 'market';
		} elseif ( preg_match( '/class|workshop|education|seminar/', $text ) ) {
			$type = 'class';
		} else {
			$type = 'community';
		}
		wp_set_object_terms( $event_id, $type, 'rhn_event_type', false );
	}
	update_option( 'rhn_event_types_migrated_20260819', 1, false );
}
add_action( 'init', 'rhn_migrate_existing_event_types', 30 );

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
		'rebekahs-signature-line' => array( "Rebekah's Signature Line | Private-Label Supplements", "Learn about the sourcing, testing, manufacturing standards and amber-glass packaging behind Rebekah's Signature Line of herbal and specialty wellness products." ),
		'practitioners'           => array( "Wellness Practitioners in Michigan | Rebekah's", "Browse the current directory of independent wellness practitioners connected with Rebekah's and view each practitioner's existing profile and contact details." ),
		'contact-us'              => array( "Contact Rebekah's Health & Nutrition", "Call your nearest Rebekah's store for product availability and local questions, or send the team a general, non-urgent message." ),
		'privacy-policy'          => array( "Privacy Policy | Rebekah's Health & Nutrition", "Read how Rebekah's Health & Nutrition handles website information, contact-form details, cookies and third-party services." ),
		'refund_returns'          => array( "Refund & Returns Policy | Rebekah's Health & Nutrition", "Read Rebekah's current refund and returns policy for eligible in-store purchases, exclusions and return requirements." ),
		'terms-conditions'        => array( "Terms & Conditions | Rebekah's Health & Nutrition", "Review the terms that apply when using Rebekah's website, educational information, external links and referral resources." ),
		'disclaimer'              => array( "Wellness & Website Disclaimer | Rebekah's", "Review important information about educational content, medical advice limitations, product claims, individual results and external resources." ),
		'shop-fullscript'         => array( "Shop Fullscript Through Rebekah's", "Learn about Fullscript and continue to the external Fullscript platform to browse professional-grade supplements through Rebekah's referral relationship." ),
		'shop-designs-for-health' => array( "Shop Designs for Health Through Rebekah's", "Learn about Designs for Health products and continue to the external partner website to browse and purchase outside Rebekah's website." ),
		'shop-lifewave'           => array( "LifeWave X39 & Phototherapy Patches | Rebekah's Health & Nutrition", "Discover LifeWave X39 and non-transdermal phototherapy patches through Rebekah's Health & Nutrition. Learn how LifeWave technology works and explore wellness options." ),
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

/** Keep dynamic practitioner profiles concise and useful in search previews. */
function rhn_practitioner_seo_title( $title ) {
	if ( ! is_singular( 'medical-practicioner' ) ) {
		return $title;
	}
	$name = get_post_meta( get_queried_object_id(), 'medical_practitioner_full_name', true );
	$name = $name ? $name : get_the_title( get_queried_object_id() );
	$name = wp_html_excerpt( wp_strip_all_tags( $name ), 45, '…' );
	return sprintf( "%s | Rebekah's", $name );
}
add_filter( 'seopress_titles_title', 'rhn_practitioner_seo_title', 100 );
add_filter( 'seopress_social_fb_title', 'rhn_practitioner_seo_title', 100 );
add_filter( 'seopress_social_twitter_title', 'rhn_practitioner_seo_title', 100 );

function rhn_practitioner_seo_description( $description ) {
	if ( ! is_singular( 'medical-practicioner' ) ) {
		return $description;
	}
	$name = get_post_meta( get_queried_object_id(), 'medical_practitioner_full_name', true );
	$name = $name ? $name : get_the_title( get_queried_object_id() );
	$copy = sprintf( "View the current profile and contact information for %s, an independent practitioner listed in Rebekah's Michigan wellness directory.", wp_strip_all_tags( $name ) );
	return wp_html_excerpt( $copy, 160, '…' );
}
add_filter( 'seopress_titles_desc', 'rhn_practitioner_seo_description', 100 );
add_filter( 'seopress_social_fb_desc', 'rhn_practitioner_seo_description', 100 );
add_filter( 'seopress_social_twitter_desc', 'rhn_practitioner_seo_description', 100 );

/** Give every individual event a compact, consistent search preview. */
function rhn_event_detail_seo_title( $title ) {
	if ( ! is_singular( 'tribe_events' ) ) {
		return $title;
	}
	$event_title = wp_html_excerpt( wp_strip_all_tags( get_the_title( get_queried_object_id() ) ), 40, '…' );
	return sprintf( "%s | Rebekah's Events", $event_title );
}
add_filter( 'seopress_titles_title', 'rhn_event_detail_seo_title', 101 );
add_filter( 'seopress_social_fb_title', 'rhn_event_detail_seo_title', 101 );
add_filter( 'seopress_social_twitter_title', 'rhn_event_detail_seo_title', 101 );

function rhn_event_detail_seo_description( $description ) {
	if ( ! is_singular( 'tribe_events' ) ) {
		return $description;
	}
	$copy = sprintf( "View the current date, time, location and attendance details for %s from Rebekah's Health & Nutrition.", wp_strip_all_tags( get_the_title( get_queried_object_id() ) ) );
	return wp_html_excerpt( $copy, 160, '…' );
}
add_filter( 'seopress_titles_desc', 'rhn_event_detail_seo_description', 101 );
add_filter( 'seopress_social_fb_desc', 'rhn_event_detail_seo_description', 101 );
add_filter( 'seopress_social_twitter_desc', 'rhn_event_detail_seo_description', 101 );

/** Add the expected security and privacy relationship tokens to new-window links. */
function rhn_secure_target_blank_markup( $html ) {
	return preg_replace_callback(
		'/<a\b(?=[^>]*\btarget\s*=\s*(["\'])_blank\1)[^>]*>/i',
		function( $matches ) {
			$tag = $matches[0];
			if ( preg_match( '/\srel\s*=\s*(["\'])([^"\']*)\1/i', $tag, $rel_match ) ) {
				$tokens = preg_split( '/\s+/', strtolower( trim( $rel_match[2] ) ) );
				$tokens = array_values( array_unique( array_filter( array_merge( $tokens, array( 'noopener', 'noreferrer' ) ) ) ) );
				return str_replace( $rel_match[0], ' rel="' . esc_attr( implode( ' ', $tokens ) ) . '"', $tag );
			}
			return preg_replace( '/>$/', ' rel="noopener noreferrer">', $tag );
		},
		(string) $html
	);
}

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

	if ( 'rebekahs-signature-line' === $key ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'CollectionPage',
			'@id'      => home_url( '/rebekahs-signature-line/#page' ),
			'name'     => "Rebekah's Signature Line",
			'url'      => home_url( '/rebekahs-signature-line/' ),
			'description' => "The sourcing, testing, manufacturing standards and packaging behind Rebekah's Signature Line of herbal and specialty wellness products.",
			'isPartOf' => array(
				'@type' => 'WebSite',
				'name'  => "Rebekah's Health & Nutrition",
				'url'   => home_url( '/' ),
			),
			'about'    => array(
				array( '@type' => 'Thing', 'name' => 'Herbal supplements' ),
				array( '@type' => 'Thing', 'name' => 'Botanical ingredient testing' ),
				array( '@type' => 'Thing', 'name' => 'Dietary supplement manufacturing' ),
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
	$post_id = $post_id ?: get_the_ID();
	$slugs   = wp_get_post_categories( $post_id, array( 'fields' => 'slugs' ) );
	return implode( ' ', array_map( 'sanitize_html_class', $slugs ) );
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

/** Correct the nested TikTok feed grid while preserving the plugin lightbox. */
function rhn_tiktok_feed_display_fix() {
	if ( ! is_front_page() ) {
		return;
	}

	$tiktok_css = <<<'CSS'
/* rhn-tiktok-grid-fix */
body.home #tiktok .rhn-social-feed-switch.is-live > .rhn-social-feed-live .sb-feed-posts {
	display: block !important;
	width: 100% !important;
}

body.home #tiktok .rhn-social-feed-switch.is-live > .rhn-social-feed-live .sb-grid-wrapper {
	display: grid !important;
	grid-template-columns: repeat(6, minmax(0, 1fr)) !important;
	gap: 12px !important;
	align-items: end;
	width: 100% !important;
}

@media (max-width: 1050px) {
	body.home #tiktok .rhn-social-feed-switch.is-live > .rhn-social-feed-live .sb-grid-wrapper {
		grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
	}
}

@media (max-width: 720px) {
	body.home #tiktok .rhn-social-feed-switch.is-live > .rhn-social-feed-live .sb-grid-wrapper {
		grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
		gap: 10px !important;
	}
}
CSS;

	wp_add_inline_style( 'rhn-page-home', $tiktok_css );
}
add_action( 'wp_enqueue_scripts', 'rhn_tiktok_feed_display_fix', 30 );

/** Keep homepage Instagram posts on-site in an accessible embedded lightbox. */
function rhn_instagram_feed_lightbox() {
	if ( ! is_front_page() ) {
		return;
	}

	$instagram_css = <<<'CSS'
/* rhn-instagram-lightbox */
body.rhn-instagram-modal-open {
	overflow: hidden;
}

.rhn-instagram-modal {
	position: fixed;
	inset: 0;
	z-index: 2147483000;
	display: none;
	align-items: center;
	justify-content: center;
	padding: 20px;
}

.rhn-instagram-modal.is-open {
	display: flex;
}

.rhn-instagram-modal__backdrop {
	position: absolute;
	inset: 0;
	background: rgba(18, 19, 17, 0.82);
	backdrop-filter: blur(5px);
}

.rhn-instagram-modal__dialog {
	position: relative;
	z-index: 1;
	width: min(94vw, 620px);
	height: min(90vh, 800px);
	background: #fff;
	border-radius: 18px;
	overflow: hidden;
	box-shadow: 0 24px 70px rgba(0, 0, 0, 0.42);
}

.rhn-instagram-modal__frame {
	display: block;
	width: 100%;
	height: 100%;
	border: 0;
	background: #fff;
}

.rhn-instagram-modal__close {
	position: absolute;
	top: 10px;
	right: 10px;
	z-index: 2;
	display: grid;
	place-items: center;
	width: 42px;
	height: 42px;
	padding: 0;
	border: 2px solid rgba(255, 255, 255, 0.9);
	border-radius: 999px;
	background: rgba(18, 19, 17, 0.88);
	color: #fff;
	font: 700 28px/1 Arial, sans-serif;
	cursor: pointer;
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.28);
}

.rhn-instagram-modal__close:hover,
.rhn-instagram-modal__close:focus-visible {
	background: #5f6f52;
	outline: 3px solid #fff;
	outline-offset: 2px;
}

@media (max-width: 720px) {
	.rhn-instagram-modal {
		padding: 10px;
	}

	.rhn-instagram-modal__dialog {
		width: 100%;
		height: min(92vh, 760px);
		border-radius: 14px;
	}
}
CSS;

	$instagram_js = <<<'JS'
(function () {
	'use strict';

	var modal;
	var frame;
	var closeButton;
	var lastTrigger;

	function instagramEmbedUrl(href) {
		try {
			var url = new URL(href, window.location.href);
			if (!/(^|\.)instagram\.com$/i.test(url.hostname)) {
				return '';
			}

			var match = url.pathname.match(/^\/(p|reel)\/([^/]+)/i);
			return match ? 'https://www.instagram.com/' + match[1] + '/' + match[2] + '/embed/' : '';
		} catch (error) {
			return '';
		}
	}

	function ensureModal() {
		if (modal) {
			return;
		}

		modal = document.createElement('div');
		modal.className = 'rhn-instagram-modal';
		modal.setAttribute('role', 'dialog');
		modal.setAttribute('aria-modal', 'true');
		modal.setAttribute('aria-label', 'Instagram post');
		modal.setAttribute('aria-hidden', 'true');
		modal.innerHTML = '<div class="rhn-instagram-modal__backdrop" data-rhn-instagram-close></div>' +
			'<div class="rhn-instagram-modal__dialog">' +
			'<button class="rhn-instagram-modal__close" type="button" aria-label="Close Instagram post" data-rhn-instagram-close>&times;</button>' +
			'<iframe class="rhn-instagram-modal__frame" title="Instagram post" loading="eager" allowtransparency="true" allowfullscreen></iframe>' +
			'</div>';
		document.body.appendChild(modal);

		frame = modal.querySelector('.rhn-instagram-modal__frame');
		closeButton = modal.querySelector('.rhn-instagram-modal__close');
		modal.querySelectorAll('[data-rhn-instagram-close]').forEach(function (control) {
			control.addEventListener('click', closeModal);
		});
	}

	function openModal(embedUrl, trigger) {
		ensureModal();
		lastTrigger = trigger;
		frame.src = embedUrl;
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('rhn-instagram-modal-open');
		closeButton.focus();
	}

	function closeModal() {
		if (!modal || !modal.classList.contains('is-open')) {
			return;
		}

		modal.classList.remove('is-open');
		modal.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('rhn-instagram-modal-open');
		frame.src = 'about:blank';
		if (lastTrigger) {
			lastTrigger.focus();
		}
	}

	document.addEventListener('click', function (event) {
		var link = event.target.closest('#instagram .sbi_item a[href]');
		var embedUrl = link ? instagramEmbedUrl(link.href) : '';
		if (!embedUrl) {
			return;
		}

		event.preventDefault();
		event.stopImmediatePropagation();
		openModal(embedUrl, link);
	}, true);

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeModal();
		}
	});
}());
JS;

	wp_add_inline_style( 'rhn-page-home', $instagram_css );
	wp_add_inline_script( 'rhn-home-sections', $instagram_js );
}
add_action( 'wp_enqueue_scripts', 'rhn_instagram_feed_lightbox', 31 );
