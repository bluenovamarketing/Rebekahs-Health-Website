<?php
/**
 * Plugin Name: Blue Nova Events Traffic Guard
 * Description: Rejects abusive legacy Events Calendar archive and export requests before the calendar plugin performs expensive queries.
 * Version: 1.1.0
 * Author: Blue Nova Marketing
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	return;
}

$blue_nova_request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
$blue_nova_event_path  = (string) wp_parse_url( $blue_nova_request_uri, PHP_URL_PATH );

$blue_nova_event_query = array();
$blue_nova_query_text  = (string) wp_parse_url( $blue_nova_request_uri, PHP_URL_QUERY );
if ( '' !== $blue_nova_query_text ) {
	parse_str( $blue_nova_query_text, $blue_nova_event_query );
}

$blue_nova_event_path_normalized = trailingslashit( strtolower( $blue_nova_event_path ) );
$blue_nova_is_events_path        = 0 === strpos( $blue_nova_event_path_normalized, '/events/' );
$blue_nova_is_index_path         = '/' === $blue_nova_event_path || '/index.php' === strtolower( $blue_nova_event_path );
$blue_nova_post_type_raw         = $blue_nova_event_query['post_type'] ?? '';
$blue_nova_post_type_value       = is_scalar( $blue_nova_post_type_raw ) ? strtolower( (string) $blue_nova_post_type_raw ) : '';
$blue_nova_has_calendar_query    = 'tribe_events' === $blue_nova_post_type_value;
$blue_nova_has_calendar_query    = $blue_nova_has_calendar_query
	|| isset( $blue_nova_event_query['tribe-bar-date'] )
	|| isset( $blue_nova_event_query['tribe_bar_date'] )
	|| isset( $blue_nova_event_query['tribe_events_cat'] )
	|| isset( $blue_nova_event_query['tribe_event_category'] )
	|| isset( $blue_nova_event_query['ical'] )
	|| isset( $blue_nova_event_query['outlook-ical'] );

// Limit protection to the public Events surface and direct legacy calendar query routes.
if ( ! $blue_nova_is_events_path && ! ( $blue_nova_is_index_path && $blue_nova_has_calendar_query ) ) {
	return;
}

$blue_nova_block_reason = '';

// Crawlers can bypass pretty event URLs by requesting legacy query-string routes directly.
if ( $blue_nova_is_index_path && $blue_nova_has_calendar_query ) {
	$blue_nova_block_reason = 'legacy-query-route';
}

// Deep, obsolete pagination was the primary production worker-exhaustion route.
if ( preg_match( '#^/events/(?:list/)?page/([0-9]+)/?#i', $blue_nova_event_path, $blue_nova_page_match ) && (int) $blue_nova_page_match[1] >= 3 ) {
	$blue_nova_block_reason = 'deep-pagination';
}

// Calendar export permutations are not part of the approved public experience.
$blue_nova_display_raw   = $blue_nova_event_query['eventDisplay'] ?? $blue_nova_event_query['tribe_event_display'] ?? '';
$blue_nova_display_value = is_scalar( $blue_nova_display_raw ) ? strtolower( (string) $blue_nova_display_raw ) : '';
if (
	isset( $blue_nova_event_query['ical'] )
	|| isset( $blue_nova_event_query['outlook-ical'] )
	|| 'ical' === $blue_nova_display_value
) {
	$blue_nova_block_reason = 'calendar-export';
}

// Reject automated archive queries for dates far outside the useful schedule.
$blue_nova_date_raw   = $blue_nova_event_query['tribe-bar-date'] ?? $blue_nova_event_query['tribe_bar_date'] ?? $blue_nova_event_query['eventDate'] ?? '';
$blue_nova_date_value = is_scalar( $blue_nova_date_raw ) ? (string) $blue_nova_date_raw : '';
if ( '' !== $blue_nova_date_value ) {
	$blue_nova_date = DateTimeImmutable::createFromFormat( '!Y-m-d', $blue_nova_date_value, wp_timezone() );
	if ( $blue_nova_date instanceof DateTimeImmutable ) {
		$blue_nova_today       = new DateTimeImmutable( 'today', wp_timezone() );
		$blue_nova_oldest_date = $blue_nova_today->modify( '-366 days' );
		$blue_nova_latest_date = $blue_nova_today->modify( '+730 days' );
		if ( $blue_nova_date < $blue_nova_oldest_date || $blue_nova_date > $blue_nova_latest_date ) {
			$blue_nova_block_reason = 'out-of-range-date';
		}
	}
}

if ( '' === $blue_nova_block_reason ) {
	return;
}

status_header( 410 );
nocache_headers();
header( 'Content-Type: text/plain; charset=utf-8' );
header( 'X-Robots-Tag: noindex, nofollow, noarchive', true );
header( 'X-Blue-Nova-Events-Guard: ' . $blue_nova_block_reason, true );

if ( 'HEAD' !== strtoupper( isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : 'GET' ) ) {
	echo "This legacy events-calendar request is no longer available.\n";
}
exit;
