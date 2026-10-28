<?php
defined( 'ABSPATH' ) || exit;

function rhn_fr_stage_google_json_get( $url, $token ) {
	$response = wp_safe_remote_get(
		$url,
		array(
			'timeout' => 30,
			'headers' => array( 'Authorization' => 'Bearer ' . $token, 'Cache-Control' => 'no-cache' ),
		)
	);
	if ( is_wp_error( $response ) ) {
		throw new RuntimeException( 'Google read failed: ' . $response->get_error_message() );
	}
	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( 200 !== wp_remote_retrieve_response_code( $response ) || ! is_array( $body ) ) {
		throw new RuntimeException( 'Google returned an invalid read response.' );
	}
	return $body;
}

function rhn_fr_stage_google_access_token() {
	if ( ! function_exists( 'rhn_catalog_google_credentials' ) ) {
		throw new RuntimeException( 'The existing staging credential provider is unavailable.' );
	}
	$credentials = rhn_catalog_google_credentials();
	if ( is_wp_error( $credentials ) ) {
		throw new RuntimeException( $credentials->get_error_message() );
	}
	if ( ! is_array( $credentials )
		|| 'service_account' !== (string) ( $credentials['type'] ?? '' )
		|| 'rhn-catalog-reader@psychic-hangout-510319-c1.iam.gserviceaccount.com' !== (string) ( $credentials['client_email'] ?? '' )
		|| empty( $credentials['private_key'] )
	) {
		throw new RuntimeException( 'The configured staging service account is incomplete.' );
	}
	$encode = static function ( $value ) {
		return rtrim( strtr( base64_encode( $value ), '+/', '-_' ), '=' );
	};
	$now      = time();
	$header   = $encode( wp_json_encode( array( 'alg' => 'RS256', 'typ' => 'JWT' ) ) );
	$claims   = $encode( wp_json_encode( array( 'iss' => (string) $credentials['client_email'], 'scope' => 'https://www.googleapis.com/auth/spreadsheets.readonly https://www.googleapis.com/auth/drive.metadata.readonly', 'aud' => 'https://oauth2.googleapis.com/token', 'iat' => $now, 'exp' => $now + 900 ) ) );
	$unsigned = $header . '.' . $claims;
	$signature = '';
	if ( ! openssl_sign( $unsigned, $signature, $credentials['private_key'], OPENSSL_ALGO_SHA256 ) ) {
		throw new RuntimeException( 'Could not sign the read-only Google request.' );
	}
	$assertion = $unsigned . '.' . $encode( $signature );
	$credentials['private_key'] = '';
	$signature = '';
	$response = wp_safe_remote_post( 'https://oauth2.googleapis.com/token', array( 'timeout' => 30, 'body' => array( 'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer', 'assertion' => $assertion ) ) );
	$assertion = '';
	if ( is_wp_error( $response ) ) {
		throw new RuntimeException( 'Google token request failed: ' . $response->get_error_message() );
	}
	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( 200 !== wp_remote_retrieve_response_code( $response ) || ! is_array( $body ) || empty( $body['access_token'] ) ) {
		throw new RuntimeException( 'Google did not issue a read-only token.' );
	}
	return (string) $body['access_token'];
}

function rhn_fr_stage_google_values( $sheet_id, $range, $token ) {
	$url    = 'https://sheets.googleapis.com/v4/spreadsheets/' . rawurlencode( $sheet_id ) . '/values/' . rawurlencode( $range ) . '?majorDimension=ROWS';
	$body   = rhn_fr_stage_google_json_get( $url, $token );
	$values = $body['values'] ?? array();
	if ( ! is_array( $values ) || ! array_is_list( $values ) ) {
		throw new RuntimeException( 'Sheets values response is not a row list.' );
	}
	return $values;
}
