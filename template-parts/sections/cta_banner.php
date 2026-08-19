<?php
/**
 * Universal Section: CTA Banner
 *
 * ACF flexible content layout: cta_banner (field: content_sections)
 * Merges section_cta_banner (home) and the dt-fk CTA block (landing pages)
 * into one layout.
 *
 * Fields:
 *   heading (text)
 *   text (textarea, optional)
 *   button_text / button_url (optional)
 *   button2_text / button2_url (optional)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$heading    = get_sub_field( 'heading' );
$text       = get_sub_field( 'text' );
$btn_text   = get_sub_field( 'button_text' );
$btn_url    = get_sub_field( 'button_url' );
$btn2_text  = get_sub_field( 'button2_text' );
$btn2_url   = get_sub_field( 'button2_url' );

if ( ! $heading ) {
	return;
}
?>
<section class="cta-banner" aria-label="<?php esc_attr_e( 'Call to action', 'doubletap' ); ?>">
	<div class="container">
		<div class="cta-banner__inner fade-in">
			<h2 class="cta-banner__title"><?php echo esc_html( $heading ); ?></h2>
			<?php if ( $text ) : ?>
				<p class="cta-banner__text"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
			<?php if ( $btn_text || $btn2_text ) : ?>
				<div class="hero__ctas">
					<?php if ( $btn_text && $btn_url ) : ?>
						<a href="<?php echo esc_url( $btn_url ); ?>" class="btn btn--primary btn--lg">
							<?php echo esc_html( $btn_text ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $btn2_text && $btn2_url ) : ?>
						<a href="<?php echo esc_url( $btn2_url ); ?>" class="btn btn--outline btn--lg">
							<?php echo esc_html( $btn2_text ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
