<?php
/**
 * Approved Classes & Events archive, populated from The Events Calendar.
 *
 * @package Rebekahs_2026
 */

$show_past = isset( $_GET['eventDisplay'] ) && 'past' === sanitize_key( wp_unslash( $_GET['eventDisplay'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$now       = current_time( 'Y-m-d H:i:s' );
$args      = array(
	'posts_per_page' => 50,
	'order'          => $show_past ? 'DESC' : 'ASC',
	'orderby'        => 'event_date',
);

if ( $show_past ) {
	$args['end_date'] = $now;
} else {
	$args['start_date'] = $now;
}

if ( function_exists( 'tribe_get_events' ) ) {
	$events = tribe_get_events( $args );
} else {
	$events = get_posts( array(
		'post_type'      => 'tribe_events',
		'post_status'    => 'publish',
		'posts_per_page' => 50,
		'order'          => $args['order'],
	) );
}

$location_slugs = array( 'lapeer', 'grand-blanc', 'clarkston', 'lake-orion' );
$type_labels    = array(
	'class'       => 'Class & education',
	'community'   => 'Community & pop-up',
	'health-fair' => 'Health fair',
	'market'      => 'Market & local maker',
);

$classify_event = static function( $event ) {
	$text = strtolower( $event->post_title . ' ' . wp_strip_all_tags( $event->post_content ) );
	if ( preg_match( '/health fair|wellness fair/', $text ) ) {
		return 'health-fair';
	}
	if ( preg_match( '/market|microgreen|maker|sale/', $text ) ) {
		return 'market';
	}
	if ( preg_match( '/class|workshop|education|seminar/', $text ) ) {
		return 'class';
	}
	return 'community';
};
?>
<main id="main">
	<section class="hero">
		<div class="wrap hero-grid">
			<div><span class="eyebrow">Learn &middot; gather &middot; connect</span><h1>There is always something <em>happening here.</em></h1><p class="hero-copy">Explore wellness classes, local makers, health fairs and in-store pop-ups across Rebekah's four Michigan communities.</p></div>
			<aside class="hero-note"><strong>Come as you are.</strong><p>Most events happen right inside our stores. Open an event for its confirmed date, location, host and attendance details before you go.</p></aside>
		</div>
	</section>

	<section class="filters" aria-label="Event filters">
		<div class="wrap">
			<form class="filter-panel" id="event-filters">
				<label>Search events<input type="search" name="event_search" placeholder="Try “class” or “microgreens”"></label>
				<label>Location<select name="event_location"><option value="">All stores</option><option value="lapeer">Lapeer</option><option value="grand-blanc">Grand Blanc</option><option value="clarkston">Clarkston</option><option value="lake-orion">Lake Orion</option></select></label>
				<label>Event type<select name="event_type"><option value="">All event types</option><option value="class">Classes &amp; education</option><option value="community">Community &amp; pop-ups</option><option value="health-fair">Health fairs</option><option value="market">Markets &amp; local makers</option></select></label>
				<button class="pill honey" type="submit">Find events</button>
			</form>
		</div>
	</section>

	<section class="events">
		<div class="wrap">
			<div class="section-head">
				<div><span class="eyebrow"><?php echo $show_past ? esc_html__( 'Past events', 'rebekahs-2026' ) : esc_html__( 'Upcoming', 'rebekahs-2026' ); ?></span><h2><?php echo $show_past ? esc_html__( 'Explore recent gatherings.', 'rebekahs-2026' ) : esc_html__( 'Plan your next visit.', 'rebekahs-2026' ); ?></h2><p>Dates and availability can change. Each event page is the source for its current details.</p></div>
				<nav class="view-toggle" aria-label="Event timeframe"><a aria-current="<?php echo $show_past ? 'false' : 'page'; ?>" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">Upcoming</a><a aria-current="<?php echo $show_past ? 'page' : 'false'; ?>" href="<?php echo esc_url( add_query_arg( 'eventDisplay', 'past', home_url( '/events/' ) ) ); ?>">Past</a></nav>
			</div>

			<div class="event-list" id="event-list">
				<?php foreach ( $events as $event ) :
					$event_id      = $event->ID;
					$start_month   = function_exists( 'tribe_get_start_date' ) ? tribe_get_start_date( $event_id, false, 'M' ) : get_the_date( 'M', $event_id );
					$start_day     = function_exists( 'tribe_get_start_date' ) ? tribe_get_start_date( $event_id, false, 'j' ) : get_the_date( 'j', $event_id );
					$all_day       = function_exists( 'tribe_event_is_all_day' ) && tribe_event_is_all_day( $event_id );
					$start_time    = function_exists( 'tribe_get_start_date' ) ? tribe_get_start_date( $event_id, false, 'g:ia' ) : '';
					$end_time      = function_exists( 'tribe_get_end_date' ) ? tribe_get_end_date( $event_id, false, 'g:ia' ) : '';
					$time_label    = $all_day ? __( 'All day', 'rebekahs-2026' ) : trim( $start_time . ( $end_time && $end_time !== $start_time ? '–' . $end_time : '' ) );
					$venue         = function_exists( 'tribe_get_venue' ) ? tribe_get_venue( $event_id ) : '';
					$city          = function_exists( 'tribe_get_city' ) ? tribe_get_city( $event_id ) : '';
					$type          = $classify_event( $event );
					$terms         = get_the_terms( $event_id, 'tribe_events_cat' );
					$location_slug = '';
					if ( is_array( $terms ) ) {
						foreach ( $terms as $term ) {
							if ( in_array( $term->slug, $location_slugs, true ) ) {
								$location_slug = $term->slug;
								break;
							}
						}
					}
					$summary    = get_the_excerpt( $event_id );
					$summary    = $summary ? $summary : wp_trim_words( wp_strip_all_tags( $event->post_content ), 28 );
					$is_recurring = function_exists( 'tribe_is_recurring_event' ) && tribe_is_recurring_event( $event_id );
					$image_id   = get_post_thumbnail_id( $event_id );
					$image_alt  = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
					$image_alt  = $image_alt ? $image_alt : get_the_title( $event_id );
				?>
					<article class="event-card" data-event-title="<?php echo esc_attr( strtolower( get_the_title( $event_id ) . ' ' . wp_strip_all_tags( $summary ) ) ); ?>" data-event-location="<?php echo esc_attr( $location_slug ); ?>" data-event-type="<?php echo esc_attr( $type ); ?>">
						<div class="date"><span><?php echo esc_html( $start_month ); ?></span><b><?php echo esc_html( $start_day ); ?></b><small><?php echo esc_html( $time_label ); ?></small></div>
						<?php if ( $image_id ) : ?><?php echo wp_kses_post( wp_get_attachment_image( $image_id, 'medium_large', false, array( 'alt' => $image_alt, 'loading' => 'lazy' ) ) ); ?><?php else : ?><span class="event-image-placeholder" aria-hidden="true">Rebekah's</span><?php endif; ?>
						<div class="event-copy"><span class="tag"><?php echo esc_html( $type_labels[ $type ] ); ?></span><h3><a href="<?php echo esc_url( get_permalink( $event_id ) ); ?>"><?php echo esc_html( get_the_title( $event_id ) ); ?></a></h3><?php if ( $venue || $city ) : ?><p><b><?php echo esc_html( $venue ); ?></b><?php echo $city ? ' · ' . esc_html( $city ) : ''; ?></p><?php endif; ?><p><?php echo esc_html( $summary ); ?></p></div>
						<span class="status"><?php echo $is_recurring ? esc_html__( 'Recurring', 'rebekahs-2026' ) : ( $show_past ? esc_html__( 'Past', 'rebekahs-2026' ) : esc_html__( 'Upcoming', 'rebekahs-2026' ) ); ?></span>
					</article>
				<?php endforeach; ?>
			</div>
			<p class="no-events" id="no-events" hidden>No events match those filters. Try a broader search or another store.</p>
			<?php if ( ! $events ) : ?><p class="no-events">No <?php echo $show_past ? 'past' : 'upcoming'; ?> events are currently published. Please check back soon.</p><?php endif; ?>
		</div>
	</section>

	<section class="editorial"><div class="wrap editorial-grid"><div class="editorial-copy"><span class="eyebrow">More than a store</span><h2>Make room for useful conversations.</h2><p>Rebekah's events are designed to make learning feel personal and community feel close. Some are structured classes; others are easy drop-in opportunities to meet a maker, practitioner or product representative.</p></div><div class="editorial-cards"><article><b>01</b><h3>Classes &amp; education</h3><p>Topic-focused sessions led by Rebekah, visiting educators and wellness professionals.</p></article><article><b>02</b><h3>Community &amp; pop-ups</h3><p>Local foods, makers, demonstrations, store celebrations and seasonal gatherings.</p></article></div></div></section>
	<section class="host-invitation"><div class="wrap host-panel"><div><span class="eyebrow">Share what you know</span><h2>Interested in hosting something at Rebekah's?</h2></div><div class="host-copy"><p>We welcome conversations with educators, practitioners, and local makers, growers, and bakers interested in leading a class or discussion, planning a pop-up, or bringing a small event to one of our stores.</p><p>Tell us a little about your idea. Opportunities are considered with each store's space, schedule, and community fit in mind.</p><a class="pill honey" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Start a conversation</a></div></div></section>
	<section class="cta"><div><span class="eyebrow" style="color:#f1d4dc">Stay in the loop</span><h2>Be the first to hear what is happening near you.</h2></div><div><p>Choose your preferred store for classes, events, new arrivals and practical wellness education.</p><a class="pill honey" href="#footer-connect">Stay Connected</a></div></section>
</main>
