<?php
/**
 * Universal Section: Text + Image
 *
 * ACF flexible content layout: text_image (field: content_sections)
 *
 * This is the canonical layout for text-in-one-column / image-in-the-other
 * sections — e.g. the "Protection That Performs Under Pressure" section on
 * the Firearms page. It merges the three previously separate, page-locked
 * variants (section_brand_story, info_brand_story, and template-landing's
 * hardcoded "intro" section) into one reusable layout available on any page.
 *
 * Fields:
 *   label             (text, optional eyebrow)
 *   heading           (text)
 *   body              (wysiwyg)
 *   image             (image)
 *   image_position    (select: right | left)
 *   cta_text / cta_url (optional)
 *   section_background (select: bg | surface)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$label    = get_sub_field( 'label' );
$heading  = get_sub_field( 'heading' );
$body     = get_sub_field( 'body' );
$image    = get_sub_field( 'image' );
$position = get_sub_field( 'image_position' ) ?: 'right';
$cta_text = get_sub_field( 'cta_text' );
$cta_url  = get_sub_field( 'cta_url' );
$bg       = get_sub_field( 'section_background' ) ?: 'bg';
$bg_var   = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';

if ( ! $heading && ! $body && ! $image ) {
	return;
}
?>
<section class="brand-story" style="background:<?php echo esc_attr( $bg_var ); ?>;" aria-label="<?php echo $heading ? esc_attr( $heading ) : esc_attr__( 'Text and image', 'doubletap' ); ?>">
	<div class="container">
		<div class="brand-story__inner<?php echo ( $position === 'left' ) ? ' brand-story__inner--reverse' : ''; ?>">

			<div class="brand-story__copy fade-in">
				<?php if ( $label ) : ?>
					<p class="brand-story__label section-label"><?php echo esc_html( $label ); ?></p>
				<?php endif; ?>

				<?php if ( $heading ) : ?>
					<h2 class="brand-story__title"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $body ) : ?>
					<div class="brand-story__text"><?php echo wp_kses_post( $body ); ?></div>
				<?php endif; ?>

				<?php if ( $cta_text && $cta_url ) : ?>
					<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary">
						<?php echo esc_html( $cta_text ); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( $image ) : ?>
				<div class="brand-story__media fade-in">
					<div class="brand-story__image-wrap">
						<img
							src="<?php echo esc_url( $image['url'] ); ?>"
							alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>"
							width="<?php echo esc_attr( $image['width'] ?? 800 ); ?>"
							height="<?php echo esc_attr( $image['height'] ?? 600 ); ?>"
							loading="lazy"
						>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
