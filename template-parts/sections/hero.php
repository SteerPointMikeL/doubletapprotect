<?php
/**
 * Universal Section: Hero
 *
 * ACF flexible content layout: hero (field: content_sections)
 * Reusable on any page. Supersedes section_hero.php (home) and the fixed
 * hero markup formerly hardcoded in template-landing.php.
 *
 * Fields:
 *   hero_style         (select: full | compact) — "full" is the tall,
 *     darker-overlay treatment used on Home; "compact" is the shorter,
 *     lighter-overlay treatment matching the legacy .dt-fk__hero markup
 *     used on inside/landing pages (Firearms, Rifles, Scopes and Optics,
 *     Tactical Gear, Vehicles & Marine). Defaults to "full" so any row
 *     saved before this field existed keeps its current appearance.
 *   eyebrow            (text, optional)
 *   heading            (text) — wrap a word in {curly braces} for accent color
 *   heading_accent     (text, optional) — rendered as a second accent line
 *   subheading         (textarea)
 *   background_image   (image)
 *   show_logo          (true_false)
 *   cta_primary_text / cta_primary_url
 *   cta_secondary_text / cta_secondary_url
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$hero_style     = get_sub_field( 'hero_style' ) ?: 'full';
$eyebrow        = get_sub_field( 'eyebrow' );
$heading        = get_sub_field( 'heading' );
$heading_accent = get_sub_field( 'heading_accent' );
$subheading     = get_sub_field( 'subheading' );
$bg_image       = get_sub_field( 'background_image' );
$show_logo      = get_sub_field( 'show_logo' );
$cta1_text      = get_sub_field( 'cta_primary_text' );
$cta1_url       = get_sub_field( 'cta_primary_url' );
$cta2_text      = get_sub_field( 'cta_secondary_text' );
$cta2_url       = get_sub_field( 'cta_secondary_url' );

if ( ! $heading && ! $bg_image ) {
	return;
}

$bg_src = ! empty( $bg_image['url'] ) ? $bg_image['url'] : '';
$hero_class = 'hero' . ( $hero_style === 'compact' ? ' hero--compact' : '' );
?>
<section class="<?php echo esc_attr( $hero_class ); ?>" aria-label="<?php esc_attr_e( 'Hero', 'doubletap' ); ?>">

	<?php if ( $bg_src ) : ?>
		<div class="hero__bg" aria-hidden="true">
			<img
				src="<?php echo esc_url( $bg_src ); ?>"
				alt=""
				width="<?php echo esc_attr( $bg_image['width'] ?? 1920 ); ?>"
				height="<?php echo esc_attr( $bg_image['height'] ?? 1080 ); ?>"
				loading="eager"
				fetchpriority="high"
			>
		</div>
		<div class="hero__overlay" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="hero__content fade-in">

		<?php if ( $show_logo ) : ?>
			<div class="hero__logo">
				<img
					src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo.svg' ) ); ?>"
					alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					width="180"
					height="90"
					loading="eager"
				>
			</div>
		<?php endif; ?>

		<?php if ( $eyebrow ) : ?>
			<div class="hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
		<?php endif; ?>

		<?php if ( $heading || $heading_accent ) : ?>
			<h1 class="hero__title">
				<?php if ( $heading ) : ?>
					<?php echo doubletap_render_accent_heading( $heading ); ?><br>
				<?php endif; ?>
				<?php if ( $heading_accent ) : ?>
					<span class="hero__title-accent"><?php echo esc_html( $heading_accent ); ?></span>
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
