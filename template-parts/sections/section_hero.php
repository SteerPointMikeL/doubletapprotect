<?php
/**
 * Section: Hero
 *
 * ACF flexible content layout: section_hero
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$get        = function_exists( 'get_sub_field' ) && get_sub_field( 'hero_heading' ) !== null
			  ? 'get_sub_field'
			  : 'get_field';

$bg_image   = $get( 'hero_background_image' );
$heading    = $get( 'hero_heading' );
$heading_2  = $get( 'hero_heading_accent' );
$subheading = $get( 'hero_subheading' );
$cta1_text  = $get( 'hero_cta_primary_text' );
$cta1_url   = $get( 'hero_cta_primary_url' );
$cta2_text  = $get( 'hero_cta_secondary_text' );
$cta2_url   = $get( 'hero_cta_secondary_url' );

// Render nothing if neither heading nor image is set
if ( ! $bg_image && ! $heading ) {
	return;
}
?>
<section class="hero" aria-label="<?php esc_attr_e( 'Homepage hero', 'doubletap' ); ?>">

	<div class="hero__bg" aria-hidden="true">
		<?php if ( ! empty( $bg_image ) ) : ?>
			<img
				src="<?php echo esc_url( $bg_image['url'] ); ?>"
				alt=""
				width="<?php echo esc_attr( $bg_image['width'] ?? 1920 ); ?>"
				height="<?php echo esc_attr( $bg_image['height'] ?? 1080 ); ?>"
				loading="eager"
				fetchpriority="high"
			>
		<?php endif; ?>
	</div>

	<div class="hero__overlay" aria-hidden="true"></div>

	<div class="hero__content fade-in">
		<div class="hero__logo">
			<img
				src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo.svg' ) ); ?>"
				alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
				width="180"
				height="90"
				loading="eager"
			>
		</div>

		<?php if ( $heading || $heading_2 ) : ?>
			<h1 class="hero__title">
				<?php if ( $heading ) : ?>
					<?php echo esc_html( $heading ); ?><br>
				<?php endif; ?>
				<?php if ( $heading_2 ) : ?>
					<span class="hero__title-accent"><?php echo esc_html( $heading_2 ); ?></span>
				<?php endif; ?>
			</h1>
		<?php endif; ?>

		<?php if ( $subheading ) : ?>
			<p class="hero__subtitle"><?php echo esc_html( $subheading ); ?></p>
		<?php endif; ?>

		<?php if ( $cta1_text || $cta2_text ) : ?>
			<div class="hero__ctas">
				<?php if ( $cta1_text && $cta1_url ) : ?>
					<a href="<?php echo esc_url( $cta1_url ); ?>" class="btn btn--primary btn--lg">
						<?php echo esc_html( $cta1_text ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $cta2_text && $cta2_url ) : ?>
					<a href="<?php echo esc_url( $cta2_url ); ?>" class="btn btn--outline btn--lg">
						<?php echo esc_html( $cta2_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

</section>
