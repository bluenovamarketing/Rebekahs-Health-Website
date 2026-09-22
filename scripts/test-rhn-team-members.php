<?php
define( 'ABSPATH', __DIR__ . '/' );

$profiles = require __DIR__ . '/../wordpress/plugins/rhn-team-members/includes/seed-profiles.php';
$expected = array( 'lapeer' => 7, 'grand-blanc' => 4, 'clarkston' => 3, 'lake-orion' => 4 );
$actual   = array_fill_keys( array_keys( $expected ), 0 );
$keys     = array();

foreach ( $profiles as $profile ) {
	foreach ( array( 'key', 'name', 'store', 'role', 'summary', 'position', 'alt', 'image', 'bio', 'order' ) as $field ) {
		if ( ! isset( $profile[ $field ] ) || '' === trim( (string) $profile[ $field ] ) ) {
			throw new RuntimeException( "Missing {$field} for a team profile." );
		}
	}
	if ( ! isset( $actual[ $profile['store'] ] ) ) {
		throw new RuntimeException( "Unknown store {$profile['store']}." );
	}
	if ( isset( $keys[ $profile['key'] ] ) ) {
		throw new RuntimeException( "Duplicate seed key {$profile['key']}." );
	}
	if ( strlen( $profile['summary'] ) > 240 ) {
		throw new RuntimeException( "Summary exceeds 240 characters for {$profile['name']}." );
	}
	$keys[ $profile['key'] ] = true;
	$actual[ $profile['store'] ]++;
}

if ( 18 !== count( $profiles ) ) {
	throw new RuntimeException( 'Expected 18 total profiles.' );
}
if ( $expected !== $actual ) {
	throw new RuntimeException( 'Store counts do not match the approved migration.' );
}

$plugin   = file_get_contents( __DIR__ . '/../wordpress/plugins/rhn-team-members/rhn-team-members.php' );
$template = file_get_contents( __DIR__ . '/../wordpress/plugins/rhn-team-members/templates/our-team.php' );
foreach ( array( 'register_post_type', 'Team Member Details', 'Publishing checklist', 'template_include', 'media_handle_sideload' ) as $needle ) {
	if ( false === strpos( $plugin, $needle ) ) {
		throw new RuntimeException( "Missing plugin contract: {$needle}." );
	}
}
foreach ( array( 'rhn_team_members_grouped', 'team-card', 'team stories', 'stories worth sharing' ) as $needle ) {
	if ( false === strpos( $template, $needle ) ) {
		throw new RuntimeException( "Missing template contract: {$needle}." );
	}
}

echo "PASS: 18 profiles, 7/4/3/4 store distribution, structured editor and dynamic template contracts.\n";
