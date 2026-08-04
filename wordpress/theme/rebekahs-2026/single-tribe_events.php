<?php
/**
 * Dynamic event detail template using the approved event-detail presentation.
 *
 * @package Rebekahs_2026
 */

get_header();
while ( have_posts() ) :
	the_post();
	$event_id    = get_the_ID();
	$all_day     = function_exists( 'tribe_event_is_all_day' ) && tribe_event_is_all_day( $event_id );
	$date_label  = function_exists( 'tribe_get_start_date' ) ? tribe_get_start_date( $event_id, false, 'F j, Y' ) : get_the_date( 'F j, Y' );
	$start_time  = function_exists( 'tribe_get_start_date' ) ? tribe_get_start_date( $event_id, false, 'g:i A' ) : '';
	$end_time    = function_exists( 'tribe_get_end_date' ) ? tribe_get_end_date( $event_id, false, 'g:i A' ) : '';
	$time_label  = $all_day ? __( 'All day', 'rebekahs-2026' ) : trim( $start_time . ( $end_time && $end_time !== $start_time ? '–' . $end_time : '' ) );
	$venue       = function_exists( 'tribe_get_venue' ) ? tribe_get_venue( $event_id ) : '';
	$venue_id    = function_exists( 'tribe_get_venue_id' ) ? tribe_get_venue_id( $event_id ) : 0;
	$address     = function_exists( 'tribe_get_full_address' ) ? tribe_get_full_address( $event_id ) : '';
	$city        = function_exists( 'tribe_get_city' ) ? tribe_get_city( $event_id ) : '';
	$phone       = function_exists( 'tribe_get_phone' ) ? tribe_get_phone( $venue_id ) : '';
	$phone_digits = preg_replace( '/\D+/', '', $phone );
	$phone_display = 10 === strlen( $phone_digits ) ? sprintf( '(%s) %s-%s', substr( $phone_digits, 0, 3 ), substr( $phone_digits, 3, 3 ), substr( $phone_digits, 6 ) ) : $phone;
	$cost        = function_exists( 'tribe_get_cost' ) ? tribe_get_cost( $event_id, true ) : '';
	$organizer   = function_exists( 'tribe_get_organizer' ) ? tribe_get_organizer( $event_id ) : '';
	$content     = apply_filters( 'the_content', get_the_content() );
	$content     = preg_replace( '/<(\/?)h1(\s|>)/i', '<$1h2$2', $content );
	$lead        = get_the_excerpt();
	$lead        = $lead ? $lead : wp_trim_words( wp_strip_all_tags( $content ), 38 );
	$image_id    = get_post_thumbnail_id();
	$image_alt   = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
	$image_alt   = $image_alt ? $image_alt : get_the_title();
	$event_terms = get_the_terms( $event_id, 'tribe_events_cat' );
	$event_label = is_array( $event_terms ) && $event_terms ? $event_terms[0]->name : __( 'Community event', 'rebekahs-2026' );
	$related     = function_exists( 'tribe_get_events' ) ? tribe_get_events( array(
		'posts_per_page' => 3,
		'start_date'     => current_time( 'Y-m-d H:i:s' ),
		'post__not_in'   => array( $event_id ),
		'orderby'        => 'event_date',
		'order'          => 'ASC',
	) ) : array();
	?>
	<main id="main">
		<section class="detail-hero"><div class="wrap"><nav class="crumbs" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">Classes &amp; Events</a> / <?php the_title(); ?></nav><div class="detail-grid"><div><span class="eyebrow"><?php echo esc_html( $event_label ); ?></span><h1><?php the_title(); ?></h1><p class="event-lead"><?php echo esc_html( $lead ); ?></p><div class="meta-row"><span><?php echo esc_html( $date_label ); ?></span><span><?php echo esc_html( $time_label ); ?></span><?php if ( $city ) : ?><span><?php echo esc_html( $city ); ?></span><?php endif; ?><?php if ( $all_day ) : ?><span>All-day event</span><?php endif; ?></div></div><?php if ( $image_id ) : ?><?php echo wp_kses_post( wp_get_attachment_image( $image_id, 'large', false, array( 'alt' => $image_alt ) ) ); ?><?php else : ?><div class="event-detail-placeholder" aria-hidden="true">Rebekah's Health &amp; Nutrition</div><?php endif; ?></div></div></section>
		<section class="detail-body"><div class="wrap detail-layout"><article class="prose"><?php echo wp_kses_post( $content ); ?><p class="notice"><strong>Please note:</strong> Event details can change. General wellness education is not individualized medical advice; contact the store if you need to confirm attendance details or accommodations.</p></article><aside class="event-facts" aria-label="Event details"><h2>Event details</h2><div class="fact"><small>Date &amp; time</small><strong><?php echo esc_html( $date_label ); ?><br><?php echo esc_html( $time_label ); ?></strong></div><?php if ( $venue || $address ) : ?><div class="fact"><small>Location</small><strong><?php echo esc_html( $venue ); ?><?php echo $address ? '<br>' . wp_kses_post( $address ) : ''; ?></strong></div><?php endif; ?><?php if ( $organizer ) : ?><div class="fact"><small>Organizer</small><strong><?php echo esc_html( $organizer ); ?></strong></div><?php endif; ?><?php if ( $cost ) : ?><div class="fact"><small>Cost</small><strong><?php echo wp_kses_post( $cost ); ?></strong></div><?php endif; ?><?php if ( $phone ) : ?><a class="pill honey" href="tel:<?php echo esc_attr( $phone_digits ); ?>">Call <?php echo esc_html( $city ? $city : 'the store' ); ?> &middot; <?php echo esc_html( $phone_display ); ?></a><?php else : ?><a class="pill honey" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Ask a question</a><?php endif; ?><p class="share">Please confirm details before traveling a long distance.</p></aside></div></section>
		<?php if ( $related ) : ?><section class="related"><div class="wrap"><span class="eyebrow">Keep exploring</span><h2>More happening at Rebekah's.</h2><div class="related-grid"><?php foreach ( $related as $related_event ) : ?><article><small><?php echo esc_html( tribe_get_start_date( $related_event->ID, false, 'F j · g:i A' ) ); ?></small><h3><a href="<?php echo esc_url( get_permalink( $related_event->ID ) ); ?>"><?php echo esc_html( get_the_title( $related_event->ID ) ); ?></a></h3><p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $related_event->post_content ), 18 ) ); ?></p></article><?php endforeach; ?></div></div></section><?php endif; ?>
		<section class="cta"><div><span class="eyebrow" style="color:#f1d4dc">Stay in the loop</span><h2>Find another reason to stop by.</h2></div><div><p>Browse classes, pop-ups, health fairs and local makers across all four stores.</p><a class="pill honey" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">View all events</a></div></section>
	</main>
	<?php
endwhile;
get_footer();
