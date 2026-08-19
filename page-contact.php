<?php
/**
 * Template Name: Contact
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Determine whether the new universal "Contact Info & Form" section
// (content_sections layout: contact_info) has been configured for this
// page. When present, doubletap_render_flexible_sections() below already
// renders it in place, so the legacy fixed-field block is skipped to avoid
// a duplicate. When absent (page not yet migrated), the legacy fixed
// fields still render — non-destructive fallback, matching the pattern
// used across the rest of the site.
$doubletap_cs_rows                  = function_exists( 'get_field' ) ? get_field( 'content_sections' ) : false;
$doubletap_has_contact_info_section = false;
if ( is_array( $doubletap_cs_rows ) ) {
	foreach ( $doubletap_cs_rows as $doubletap_cs_row ) {
		if ( isset( $doubletap_cs_row['acf_fc_layout'] ) && 'contact_info' === $doubletap_cs_row['acf_fc_layout'] ) {
			$doubletap_has_contact_info_section = true;
			break;
		}
	}
}

$phone          = get_field( 'contact_phone' );
$email          = get_field( 'contact_email' );
$response_time  = get_field( 'contact_response_time' );
$ig_handle      = get_field( 'contact_instagram_handle' );
$ig_url         = get_field( 'contact_instagram_url' );
$dealer_heading = get_field( 'dealer_inquiry_heading' );
$dealer_text    = get_field( 'dealer_inquiry_text' );
$form_id        = get_field( 'gravity_form_id' );
?>

<main id="main" class="page-contact">

	<?php if ( function_exists( 'have_rows' ) && have_rows( 'content_sections' ) ) : ?>
		<?php
		// Universal flexible content — includes the new "Contact Info & Form"
		// (contact_info) layout when configured, plus any additional sections
		// (e.g. an extra text+image or CTA banner) placed above/below it.
		doubletap_render_flexible_sections( get_the_ID(), 'content_sections' );
		?>
	<?php endif; ?>

	<section class="page-header">
		<div class="container">
			<h1 class="page-header__title"><?php esc_html_e( 'Contact Us', 'doubletap' ); ?></h1>
			<p class="page-header__subtitle"><?php esc_html_e( 'Questions, orders, dealer inquiries — we\'re here to help.', 'doubletap' ); ?></p>
		</div>
	</section>

	<?php if ( ! $doubletap_has_contact_info_section ) : ?>
	<section class="contact-section">
		<div class="container">
			<div class="contact-grid">

				<div class="contact-info fade-in">

					<?php if ( $phone ) : ?>
					<a href="tel:<?php echo esc_attr( preg_replace( '/\D/', '', $phone ) ); ?>" class="contact-card">
						<div class="contact-card__icon" aria-hidden="true"><?php echo doubletap_get_icon( 'phone' ); ?></div>
						<div>
							<span class="contact-card__label"><?php esc_html_e( 'Phone', 'doubletap' ); ?></span>
							<div class="contact-card__value"><?php echo esc_html( $phone ); ?></div>
						</div>
					</a>
					<?php endif; ?>

					<?php if ( $email ) : ?>
					<a href="mailto:<?php echo esc_attr( $email ); ?>" class="contact-card">
						<div class="contact-card__icon" aria-hidden="true"><?php echo doubletap_get_icon( 'email' ); ?></div>
						<div>
							<span class="contact-card__label"><?php esc_html_e( 'Email', 'doubletap' ); ?></span>
							<div class="contact-card__value"><?php echo esc_html( $email ); ?></div>
						</div>
					</a>
					<?php endif; ?>

					<?php if ( $response_time ) : ?>
					<div class="contact-card">
						<div class="contact-card__icon" aria-hidden="true"><?php echo doubletap_get_icon( 'clock' ); ?></div>
						<div>
							<span class="contact-card__label"><?php esc_html_e( 'Response Time', 'doubletap' ); ?></span>
							<div class="contact-card__value"><?php echo esc_html( $response_time ); ?></div>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $ig_handle && $ig_url ) : ?>
					<a href="<?php echo esc_url( $ig_url ); ?>" class="contact-card" target="_blank" rel="noopener noreferrer">
						<div class="contact-card__icon" aria-hidden="true"><?php echo doubletap_get_icon( 'instagram' ); ?></div>
						<div>
							<span class="contact-card__label"><?php esc_html_e( 'Instagram', 'doubletap' ); ?></span>
							<div class="contact-card__value"><?php echo esc_html( $ig_handle ); ?></div>
						</div>
					</a>
					<?php endif; ?>

					<?php if ( $dealer_heading ) : ?>
					<div class="dealer-inquiry-box fade-in">
						<h2><?php echo esc_html( $dealer_heading ); ?></h2>
						<?php if ( $dealer_text ) : ?>
							<p><?php echo esc_html( $dealer_text ); ?></p>
						<?php endif; ?>
					</div>
					<?php endif; ?>

				</div>

				<div class="contact-form fade-in">
					<div class="contact-form__card">
						<h2 class="contact-form__heading"><?php esc_html_e( 'Send A Message', 'doubletap' ); ?></h2>
						<?php if ( $form_id && function_exists( 'gravity_form' ) ) : ?>
							<?php gravity_form( (int) $form_id, false, false, false, null, true ); ?>
						<?php elseif ( $form_id ) : ?>
							<?php echo do_shortcode( '[gravityforms id="' . intval( $form_id ) . '" title="false" description="false"]' ); ?>
						<?php endif; ?>
					</div>
				</div>

			</div>
		</div>
	</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
