<?php
/**
 * Universal Section: Basic Content
 *
 * ACF flexible content layout: basic_content (field: content_sections)
 *
 * A plain, single-column, centered heading + WYSIWYG body block. Use this
 * instead of Text + Image (text_image / brand-story) when a section has no
 * image or video: Text + Image always renders a two-column grid, so a
 * heading-only (or heading+body-only) row collapses to a lopsided,
 * left-aligned single column when the media side is empty. text_image
 * itself is left completely untouched for its existing two-column usages.
 *
 * Fields:
 *   label             (text, optional eyebrow)
 *   heading           (text, optional)
 *   body              (wysiwyg, optional)
 *   cta_text / cta_url (optional)
 *   section_background (select: bg | surface)
 *   spacing_top         (select: normal | tight | none, optional)
 *   spacing_bottom      (select: normal | tight | none, optional)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$label     = get_sub_field( 'label' );
$heading   = get_sub_field( 'heading' );
$body      = get_sub_field( 'body' );
$cta_text  = get_sub_field( 'cta_text' );
$cta_url   = get_sub_field( 'cta_url' );
$bg        = get_sub_field( 'section_background' ) ?: 'bg';
$bg_var    = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';
$space_cls = doubletap_section_spacing_class( get_sub_field( 'spacing_top' ), get_sub_field( 'spacing_bottom' ) );

if ( ! $label && ! $heading && ! $body ) {
	return;
}
?>
<section class="basic-content<?php echo esc_attr( $space_cls ); ?>" style="background:<?php echo esc_attr( $bg_var ); ?>;" aria-label="<?php echo $heading ? esc_attr( $heading ) : esc_attr__( 'Content', 'doubletap' ); ?>">
	<div class="container">
		<div class="basic-content__inner fade-in">

			<?php if ( $label ) : ?>
				<p class="basic-content__label section-label"><?php echo esc_html( $label ); ?></p>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h2 class="basic-content__title"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php if ( $body ) : ?>
				<div class="basic-content__body"><?php echo wp_kses_post( $body ); ?></div>
			<?php endif; ?>

			<?php if ( $cta_text && $cta_url ) : ?>
				<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary">
					<?php echo esc_html( $cta_text ); ?>
				</a>
			<?php endif; ?>

		</div>
	</div>
</section>
