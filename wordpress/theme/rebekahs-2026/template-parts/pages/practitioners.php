<?php
/**
 * Approved practitioner directory populated from the existing practitioner CPT.
 *
 * @package Rebekahs_2026
 */

$practitioners = get_posts( array(
	'post_type'      => 'medical-practicioner',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'title',
	'order'          => 'ASC',
) );

$service_terms = get_terms( array(
	'taxonomy'   => 'medical-service',
	'hide_empty' => true,
) );

$find_locations = static function( $practitioner ) {
	$text   = strtolower( $practitioner->post_title . ' ' . wp_strip_all_tags( $practitioner->post_content ) );
	$cities = array( 'Lapeer', 'Grand Blanc', 'Clarkston', 'Lake Orion', 'Burton', 'Davison', 'Metamora', 'Rochester', 'Fort Gratiot', 'Michigan', 'Online' );
	$found  = array();
	foreach ( $cities as $city ) {
		if ( false !== strpos( $text, strtolower( $city ) ) ) {
			$found[] = $city;
		}
	}
	return implode( ' · ', array_slice( array_unique( $found ), 0, 2 ) );
};
?>
<main id="main">
	<section class="directory-hero"><div class="wrap hero-grid"><div class="hero-copy"><span class="eyebrow">Local practitioner directory</span><h1>Find a practitioner for your next step.</h1><p class="lead">Explore independent practitioners and healthcare offices serving communities across mid-Michigan. Compare areas of practice, locations and the contact information currently published in each profile.</p></div><div class="hero-note"><strong>A community resource</strong>This directory helps you discover local providers. Rebekah's does not diagnose, prescribe or guarantee services listed here.</div></div></section>
	<section class="controls" aria-label="Directory filters"><div class="wrap control-row"><div class="field"><label for="search">Search by name, practice or city</label><input id="search" type="search" placeholder="Try “Lapeer” or “chiropractic”"></div><div class="field"><label for="category">Area of practice</label><select id="category"><option value="">All areas of practice</option><?php if ( ! is_wp_error( $service_terms ) ) : ?><?php foreach ( $service_terms as $term ) : ?><option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option><?php endforeach; ?><?php endif; ?></select></div></div></section>
	<section class="directory"><div class="wrap"><div class="directory-top"><div><span class="eyebrow">Browse the directory</span><h2>Practitioners &amp; practices</h2></div><p id="count" aria-live="polite">Showing <?php echo esc_html( count( $practitioners ) ); ?> practitioners &amp; practices</p></div><div class="grid" id="grid">
		<?php foreach ( $practitioners as $practitioner ) :
			$terms      = wp_get_post_terms( $practitioner->ID, 'medical-service' );
			$term_names = is_wp_error( $terms ) ? array() : wp_list_pluck( $terms, 'name' );
			$term_slugs = is_wp_error( $terms ) ? array() : wp_list_pluck( $terms, 'slug' );
			$full_name   = get_post_meta( $practitioner->ID, 'medical_practitioner_full_name', true );
			$center      = get_post_meta( $practitioner->ID, 'medical_center', true );
			$description = get_post_meta( $practitioner->ID, 'medical_practitioner_description', true );
			$contact     = get_post_meta( $practitioner->ID, 'medical_practitioner_contact_info', true );
			$display_name = $full_name ? $full_name : get_the_title( $practitioner->ID );
			$summary     = $description ? wp_trim_words( wp_strip_all_tags( $description ), 25 ) : wp_trim_words( wp_strip_all_tags( $practitioner->post_content ), 25 );
			$location_source = clone $practitioner;
			$location_source->post_content = $description . ' ' . $contact . ' ' . $practitioner->post_content;
			$location   = $find_locations( $location_source );
			$searchable = strtolower( $display_name . ' ' . $center . ' ' . $summary . ' ' . wp_strip_all_tags( $contact ) . ' ' . $location . ' ' . implode( ' ', $term_names ) );
		?>
			<article class="card" data-search="<?php echo esc_attr( $searchable ); ?>" data-categories="<?php echo esc_attr( implode( '|', $term_slugs ) ); ?>"><span class="category"><?php echo esc_html( $term_names ? implode( ' · ', $term_names ) : __( 'Practitioner listing', 'rebekahs-2026' ) ); ?></span><h3><?php echo esc_html( $display_name ); ?></h3><?php if ( $center ) : ?><p class="practice"><?php echo esc_html( $center ); ?></p><?php endif; ?><p class="summary"><?php echo esc_html( $summary ? $summary : __( 'Open this profile for the information currently published by the practitioner.', 'rebekahs-2026' ) ); ?></p><?php if ( $location ) : ?><div class="details"><span aria-label="Location">⌖ <?php echo esc_html( $location ); ?></span></div><?php endif; ?><a class="profile-link" href="<?php echo esc_url( get_permalink( $practitioner->ID ) ); ?>">View full profile <span aria-hidden="true">→</span></a></article>
		<?php endforeach; ?>
	</div><div class="empty" id="empty" hidden><h3>No exact matches</h3><p>Try a broader name, city or area of practice.</p></div></div></section>
	<section class="trust"><div class="wrap trust-grid"><div><span class="eyebrow">Before you contact a provider</span><h2>Choose with confidence.</h2><p>Credentials, services, availability and insurance participation can change. Confirm details directly with the practitioner before making care decisions.</p></div><div class="trust-list"><div><strong>Verify credentials</strong><span>Ask about current licensing, certifications and scope of practice.</span></div><div><strong>Confirm logistics</strong><span>Check current address, hours, cost and insurance details directly.</span></div><div><strong>Ask good questions</strong><span>Discuss the provider's approach and whether it fits your needs.</span></div><div><strong>Use appropriate care</strong><span>Seek qualified medical help for urgent symptoms or emergencies.</span></div></div></div></section>
	<div class="wrap disclaimer">Directory information is provided for general informational purposes and is not medical advice or an endorsement. Contact each provider directly to verify services and current information.</div>
</main>
