<?php
/**
 * Universal Section: Contact Info & Form
 *
 * ACF flexible content layout: contact_info (field: content_sections)
 * Migrated from the fixed-field "Contact Page Settings" group
 * (acf-json/group_contact_page.json) — the Contact page's one section that
 * hadn't yet been rolled into the universal content_sections builder.
 *
 * Fields:
 *   phone / email / response_time (text/email/text, optional)
 *   instagram_handle / instagram_url (optional, shown together)
 *   dealer_heading / dealer_text (optional callout box)
 *   form_heading (text)
 *   gravity_form_id (number)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$phone          = get_sub_field( 'phone' );
$email          = get_sub_field( 'email' );
$response_time  = get_sub_field( 'response_time' );
$ig_handle      = get_sub_field( 'instagram_handle' );
$ig_url         = get_sub_field( 'instagram_url' );
$dealer_heading = get_sub_field( 'dealer_heading' );
$dealer_text    = get_sub_field( 'dealer_text' );
$form_heading   = get_sub_field( 'form_heading' );
$form_id        = get_sub_field( 'gravity_form_id' );
?>
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
					<?php if ( $form_heading ) : ?>
						<h2 class="contact-form__heading"><?php echo esc_html( $form_heading ); ?></h2>
					<?php endif; ?>
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
