<?php
/**
 * Dynamic practitioner profile using only the information in the existing record.
 *
 * @package Rebekahs_2026
 */

get_header();
while ( have_posts() ) :
	the_post();
	$practitioner_id = get_the_ID();
	$services        = get_the_terms( $practitioner_id, 'medical-service' );
	$service_names   = $services && ! is_wp_error( $services ) ? wp_list_pluck( $services, 'name' ) : array();
	$full_name       = get_post_meta( $practitioner_id, 'medical_practitioner_full_name', true );
	$center          = get_post_meta( $practitioner_id, 'medical_center', true );
	$description     = get_post_meta( $practitioner_id, 'medical_practitioner_description', true );
	$contact_info    = get_post_meta( $practitioner_id, 'medical_practitioner_contact_info', true );
	$display_name    = $full_name ? $full_name : get_the_title();
	$content_source  = $description ? $description : get_the_content();
	$content         = apply_filters( 'the_content', $content_source );
	$content         = preg_replace( '/<(\/?)h1(\s|>)/i', '<$1h2$2', $content );
	$plain_content   = wp_strip_all_tags( $content );
	$lead            = get_the_excerpt();
	$lead            = $lead ? $lead : wp_trim_words( $plain_content, 42 );
	?>
	<main id="main">
		<section class="profile-hero"><div class="wrap"><a class="back" href="<?php echo esc_url( home_url( '/practitioners/' ) ); ?>">← All practitioners</a><div class="profile-grid"><div><span class="eyebrow">Independent practitioner profile</span><h1><?php echo esc_html( $display_name ); ?></h1><?php if ( $center || $service_names ) : ?><p class="credential"><?php echo esc_html( implode( ' · ', array_filter( array_merge( $center ? array( $center ) : array(), $service_names ) ) ) ); ?></p><?php endif; ?><p class="profile-lede"><?php echo esc_html( $lead ); ?></p></div><?php if ( $contact_info ) : ?><aside class="contact-card" aria-label="Published contact information"><h2>Current contact information</h2><div class="published-contact"><?php echo wp_kses_post( wpautop( $contact_info ) ); ?></div></aside><?php endif; ?></div></div></section>
		<section class="profile-body"><div class="wrap body-grid"><article class="body-copy"><?php echo wp_kses_post( $content ); ?></article><aside class="facts"><h2>Before you contact</h2><?php if ( $service_names ) : ?><ul><?php foreach ( $service_names as $service_name ) : ?><li><?php echo esc_html( $service_name ); ?></li><?php endforeach; ?></ul><?php endif; ?><p>Services are offered independently. Confirm credentials, availability, pricing, insurance and appointment details directly with the practitioner.</p></aside></div></section>
		<nav class="profile-nav" aria-label="Practitioner navigation"><div class="wrap"><a href="<?php echo esc_url( home_url( '/practitioners/' ) ); ?>">← Back to all practitioners</a><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Contact Rebekah's</a></div></nav>
	</main>
	<?php
endwhile;
get_footer();
