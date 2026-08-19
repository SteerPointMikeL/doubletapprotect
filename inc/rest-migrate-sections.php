<?php
/**
 * REST endpoint for running the content_sections migration on environments
 * without SSH/WP-CLI access.
 *
 * POST /wp-json/doubletap/v1/migrate-sections
 *   Body (JSON, all optional):
 *     { "post_id": 123, "dry_run": true }
 *
 * Auth: an authenticated administrator, via WordPress core Application
 * Passwords (Users -> Profile -> Application Passwords) sent as HTTP Basic
 * auth. No plugin required — this has been a core WordPress feature since
 * 5.6. The endpoint itself only checks the manage_options capability; it
 * does not implement authentication. HTTPS is strongly recommended in
 * production (Basic Auth headers are base64, not encrypted) but is not
 * enforced by this endpoint since dev/staging hosts often lack TLS — see
 * doubletap_migrate_sections_permission_check() below.
 *
 * This calls the exact same doubletap_run_sections_migration() function
 * used by `wp doubletap migrate-sections` (see inc/cli-migrate-sections.php)
 * — same non-destructive, idempotent behavior, just reachable over HTTP(S)
 * instead of a terminal.
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

add_action( 'rest_api_init', 'doubletap_register_migrate_sections_route' );

function doubletap_register_migrate_sections_route() {
	register_rest_route(
		'doubletap/v1',
		'/migrate-sections',
		[
			'methods'             => 'POST',
			'callback'            => 'doubletap_handle_migrate_sections_request',
			'permission_callback' => 'doubletap_migrate_sections_permission_check',
			'args'                => [
				'post_id' => [
					'type'              => 'integer',
					'required'          => false,
					'default'           => 0,
					'sanitize_callback' => 'absint',
				],
				'dry_run' => [
					'type'              => 'boolean',
					'required'          => false,
					'default'           => false,
				],
			],
		]
	);
}

/**
 * Only site administrators may run the migration.
 *
 * HTTPS is strongly recommended (WordPress Application Password Basic Auth
 * headers are base64, not encrypted), but is not enforced here: WordPress
 * core itself does not require HTTPS for Application Passwords to
 * function, and many dev/staging hosts (including this project's) don't
 * have TLS configured. If you deploy this to a production HTTPS site, you
 * can re-add a hard `is_ssl()` check; the real security boundary is the
 * `manage_options` capability check below plus the Application Password
 * itself, which is scoped to one admin account and independently
 * revocable from your login password.
 */
function doubletap_migrate_sections_permission_check( WP_REST_Request $request ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return new WP_Error(
			'doubletap_forbidden',
			'You do not have permission to run this migration.',
			[ 'status' => 403 ]
		);
	}

	return true;
}

function doubletap_handle_migrate_sections_request( WP_REST_Request $request ) {
	if ( ! function_exists( 'doubletap_run_sections_migration' ) ) {
		return new WP_Error(
			'doubletap_migration_unavailable',
			'The migration logic is not loaded on this site.',
			[ 'status' => 500 ]
		);
	}

	$post_id = (int) $request->get_param( 'post_id' );
	$dry_run = (bool) $request->get_param( 'dry_run' );

	$result = doubletap_run_sections_migration( $post_id, $dry_run );

	if ( ! $result['ok'] ) {
		return new WP_Error(
			'doubletap_migration_failed',
			$result['error'] ?: 'The migration could not run.',
			[ 'status' => 500 ]
		);
	}

	return new WP_REST_Response( $result, 200 );
}
