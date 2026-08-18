<?php
/**
 * Info Section: Brand Story (Aerospace-Grade Sealant Technology)
 * Layout: info_brand_story
 *
 * Fields:
 *   section_background (select: bg | surface)
 *   heading            (text)
 *   body               (wysiwyg)
 *   image              (image, return_format: array)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$bg      = get_sub_field( 'section_background' ) ?: 'bg';
$heading = get_sub_field( 'heading' );
$body    = get_sub_field( 'body' );
$image   = get_sub_field( 'image' );
$bg_var  = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';
?>

<section class="info-section" style="background:<?php echo esc_attr( $bg_var ); ?>;">
	<div class="container">
		<div class="brand-story__inner">
			<div class="info-content fade-in">
				<?php if ( $heading ) : ?>
					<h2><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $body ) : ?>
					<?php echo wp_kses_post( $body ); ?>
				<?php endif; ?>
			</div>
			<?php if ( $image ) : ?>
				<div class="brand-story__image-wrap fade-in">
					<img
						src="<?php echo esc_url( $image['url'] ); ?>"
						alt="<?php echo esc_attr( $image['alt'] ); ?>"
						width="<?php echo esc_attr( $image['width'] ); ?>"
						height="<?php echo esc_attr( $image['height'] ); ?>"
						loading="lazy"
					>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
