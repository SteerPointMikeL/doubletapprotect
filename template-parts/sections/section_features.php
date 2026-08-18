<?php
/**
 * Section: Features / Benefits
 *
 * ACF flexible content layout: section_features
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$get      = function_exists( 'get_sub_field' ) && get_sub_field( 'features_section_heading' ) !== null ? 'get_sub_field' : 'get_field';
$label    = $get( 'features_section_label' );
$heading  = $get( 'features_section_heading' );
$features = $get( 'features' );

if ( ! $features ) {
	return;
}
?>
<section class="features" aria-label="<?php esc_attr_e( 'Product features and benefits', 'doubletap' ); ?>">
	<div class="container">

		<?php if ( $label || $heading ) : ?>
			<div class="section-header fade-in">
				<?php if ( $label ) : ?>
					<p class="section-label"><?php echo esc_html( $label ); ?></p>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="section-heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="features__grid">
			<?php foreach ( $features as $feature ) : ?>
				<div class="feature fade-in">
					<?php if ( ! empty( $feature['feature_icon'] ) ) : ?>
						<div class="feature__icon" aria-hidden="true">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo $feature['feature_icon'];
							?>
						</div>
					<?php endif; ?>
					<h3 class="feature__title"><?php echo esc_html( $feature['feature_title'] ); ?></h3>
					<?php if ( ! empty( $feature['feature_description'] ) ) : ?>
						<p class="feature__text"><?php echo esc_html( $feature['feature_description'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
