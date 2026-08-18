<?php
/**
 * Section: Brand Story
 *
 * ACF flexible content layout: section_brand_story
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$get      = function_exists( 'get_sub_field' ) && get_sub_field( 'brand_story_heading' ) !== null ? 'get_sub_field' : 'get_field';
$label    = $get( 'brand_story_label' );
$heading  = $get( 'brand_story_heading' );
$text     = $get( 'brand_story_text' );
$image    = $get( 'brand_story_image' );
$cta_text = $get( 'brand_story_cta_text' );
$cta_url  = $get( 'brand_story_cta_url' );

if ( ! $heading && ! $text ) {
	return;
}
?>
<section class="brand-story" aria-label="<?php esc_attr_e( 'About Double Tap Protect', 'doubletap' ); ?>">
	<div class="container">
		<div class="brand-story__inner">

			<div class="brand-story__copy fade-in">
				<?php if ( $label ) : ?>
					<p class="brand-story__label section-label"><?php echo esc_html( $label ); ?></p>
				<?php endif; ?>

				<?php if ( $heading ) : ?>
					<h2 class="brand-story__title"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<div class="brand-story__text">
						<?php echo wp_kses_post( $text ); ?>
					</div>
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
