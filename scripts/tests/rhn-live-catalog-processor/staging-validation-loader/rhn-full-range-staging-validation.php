<?php
/**
 * Plugin Name: RHN Full-range Staging Preparation Validator
 * Description: Exact-host, administrator-only, source-read and planning validator. Contains no catalog apply or scheduler code.
 * Version: 0.2.0-local-review
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/staging-preparation-store.php';
require_once __DIR__ . '/staging-google-readonly.php';
require_once __DIR__ . '/staging-preparation.php';

add_action(
	'admin_menu',
	static function () {
		if ( rhn_fr_stage_exact_host() && current_user_can( 'manage_options' ) ) {
			add_management_page(
				'RHN staging preparation',
				'RHN staging preparation',
				'manage_options',
				'rhn-full-range-staging-preparation',
				'rhn_fr_stage_render_page'
			);
		}
	}
);
