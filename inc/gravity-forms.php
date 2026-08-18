<?php
/**
 * Gravity Forms styling hooks and custom behaviors.
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'GFForms' ) ) {
	return;
}

// ─── Disable GF Default CSS ───────────────────────────────────────────────
// Our dark theme styles are in style.css targeting .gform_wrapper
add_filter( 'pre_option_rg_gforms_disable_css', '__return_true' );
add_filter( 'gform_disable_print_styles', '__return_true' );


// ─── Pre-populate Subject from URL Query Param ────────────────────────────
// Used when linking from the Merchant Kit page: /contact/?subject=Merchant+Kit+Inquiry
add_filter( 'gform_field_value_subject', function () {
	if ( ! empty( $_GET['subject'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return sanitize_text_field( wp_unslash( $_GET['subject'] ) );
	}
	return '';
} );


// ─── Notification: Send confirmation email to submitter ───────────────────
// Standard Gravity Forms notifications are configured in GF admin UI.
// This hook adds extra server-side processing if needed in the future.
add_action( 'gform_after_submission', function ( $entry, $form ) {
	// Placeholder for any custom post-submission logic
	// e.g., CRM sync, Slack notification, etc.
}, 10, 2 );


// ─── Custom Submit Button Text ────────────────────────────────────────────
add_filter( 'gform_submit_button', function ( $button, $form ) {
	return '<button type="submit" class="btn btn--primary btn--lg btn--block gform_button" id="gform_submit_button_' . esc_attr( $form['id'] ) . '">' .
		   esc_html__( 'Send Message', 'doubletap' ) .
		   '</button>';
}, 10, 2 );


// ─── Spinner / Loading State ──────────────────────────────────────────────
add_filter( 'gform_ajax_spinner_url', function () {
	return 'none'; // Suppress default spinner; CSS handles it
} );


// ─── Wrapper CSS Class ────────────────────────────────────────────────────
add_filter( 'gform_wrapper_classes', function ( $classes ) {
	$classes[] = 'doubletap-form';
	return $classes;
} );
