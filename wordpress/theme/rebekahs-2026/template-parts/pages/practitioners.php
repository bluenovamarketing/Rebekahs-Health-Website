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

// Keep the source records intact while matching the approved 25-listing directory.
$hidden_practitioner_name_prefixes = array(
	'ivlounge',
	'kimberlycabe',
	'troyfarwell',
);

$practitioners = array_values( array_filter( $practitioners, static function( $practitioner ) use ( $hidden_practitioner_name_prefixes ) {
	$full_name       = get_post_meta( $practitioner->ID, 'medical_practitioner_full_name', true );
	$display_name    = $full_name ? $full_name : get_the_title( $practitioner->ID );
	$normalized_name = preg_replace( '/[^a-z0-9]+/', '', strtolower( remove_accents( wp_strip_all_tags( $display_name ) ) ) );

	foreach ( $hidden_practitioner_name_prefixes as $hidden_name_prefix ) {
		if ( 0 === strpos( $normalized_name, $hidden_name_prefix ) ) {
			return false;
		}
	}

	return true;
} ) );

$service_terms = get_terms( array(
	'taxonomy'   => 'medical-service',
	'hide_empty' => true,
	'object_ids' => wp_list_pluck( $practitioners, 'ID' ),
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
			$about       = $description ? $description : $practitioner->post_content;
			$summary     = wp_trim_words( wp_strip_all_tags( $about ), 25 );
			$location_source = clone $practitioner;
			$location_source->post_content = $description . ' ' . $contact . ' ' . $practitioner->post_content;
			$location   = $find_locations( $location_source );
			$searchable = strtolower( $display_name . ' ' . $center . ' ' . $summary . ' ' . wp_strip_all_tags( $contact ) . ' ' . $location . ' ' . implode( ' ', $term_names ) );
		?>
			<article class="card" data-search="<?php echo esc_attr( $searchable ); ?>" data-categories="<?php echo esc_attr( implode( '|', $term_slugs ) ); ?>">
				<span class="category"><?php echo esc_html( $term_names ? implode( ' · ', $term_names ) : __( 'Practitioner listing', 'rebekahs-2026' ) ); ?></span>
				<h3><?php echo esc_html( $display_name ); ?></h3>
				<?php if ( $center ) : ?><p class="practice"><?php echo esc_html( $center ); ?></p><?php endif; ?>
				<p class="summary"><?php echo esc_html( $summary ? $summary : __( 'Full listing details are available below.', 'rebekahs-2026' ) ); ?></p>
				<?php if ( $location ) : ?><div class="details"><span aria-label="Location">⌖ <?php echo esc_html( $location ); ?></span></div><?php endif; ?>
				<button class="expand" type="button" aria-expanded="false" aria-controls="practitioner-details-<?php echo esc_attr( $practitioner->ID ); ?>">View full listing <span aria-hidden="true">+</span></button>
				<div class="expanded" id="practitioner-details-<?php echo esc_attr( $practitioner->ID ); ?>" hidden>
					<?php if ( trim( wp_strip_all_tags( $about ) ) ) : ?>
						<div class="expanded-about"><strong>About</strong><?php echo wp_kses_post( wpautop( $about ) ); ?></div>
					<?php endif; ?>
					<?php if ( trim( wp_strip_all_tags( $contact ) ) ) : ?>
						<div class="expanded-contact"><strong>Contact details</strong><?php echo wp_kses_post( wpautop( $contact ) ); ?></div>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div><div class="empty" id="empty" hidden><h3>No exact matches</h3><p>Try a broader name, city or area of practice.</p></div></div></section>
	<section class="trust"><div class="wrap trust-grid"><div><h2>Finding the Right Practitioner</h2><p>We believe having access to trusted healthcare resources can be an important part of your wellness journey.</p></div><div class="trust-copy"><p>The practitioners listed here include professionals we have developed relationships with, worked alongside, or who have come highly recommended by members of our community.</p><p>Rebekah’s Health & Nutrition does not independently verify or monitor each practitioner’s licensing, credentials, services, availability, pricing, insurance participation, or scope of practice. Information may change over time.</p><p>We encourage you to do your own research, contact the practitioner directly, ask questions, and determine whether their services are appropriate for your individual needs.</p><p>The practitioners listed on this page are provided as a community resource and their inclusion should not be considered a guarantee or endorsement of specific medical treatment or outcomes.</p></div></div></section>
</main>
