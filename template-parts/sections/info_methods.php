<?php
/**
 * Info Section: Application Methods (Two Ways to Protect)
 * Layout: info_methods
 *
 * Fields:
 *   section_background (select: bg | surface)
 *   section_label      (text)
 *   section_heading    (text)
 *   methods            (repeater)
 *     ↳ method_title   (text)
 *     ↳ method_icon_svg (textarea — raw SVG markup)
 *     ↳ method_text    (textarea)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$bg      = get_sub_field( 'section_background' ) ?: 'surface';
$label   = get_sub_field( 'section_label' );
$heading = get_sub_field( 'section_heading' );
$methods = get_sub_field( 'methods' );
$bg_var  = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';
?>

<section class="info-section" style="background:<?php echo esc_attr( $bg_var ); ?>;">
	<div class="container">
		<?php if ( $label || $heading ) : ?>
			<div class="section-header fade-in" style="margin-bottom:var(--space-8);">
				<?php if ( $label ) : ?>
					<p class="section-label"><?php echo esc_html( $label ); ?></p>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="section-heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $methods ) : ?>
			<div class="info-methods-grid fade-in">
				<?php foreach ( $methods as $method ) :
					$icon = ! empty( $method['method_icon_svg'] ) ? $method['method_icon_svg'] : '';
					$title = ! empty( $method['method_title'] ) ? $method['method_title'] : '';
					$text  = ! empty( $method['method_text'] ) ? $method['method_text'] : '';
				?>
					<div class="info-method-card">
						<?php if ( $icon ) : ?>
							<div class="feature__icon" style="margin-bottom:var(--space-4);">
								<?php echo $icon; // Raw SVG — output as-is, editor-controlled ?>
							</div>
						<?php endif; ?>
						<?php if ( $title ) : ?>
							<h3 class="info-method-card__title"><?php echo esc_html( $title ); ?></h3>
						<?php endif; ?>
						<?php if ( $text ) : ?>
							<p class="info-method-card__text"><?php echo esc_html( $text ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
