<?php
/**
 * Info Section: What It Protects (4-column feature grid)
 * Layout: info_what_it_protects
 *
 * Fields:
 *   section_background (select: bg | surface)
 *   section_label      (text)
 *   section_heading    (text)
 *   section_desc       (text)
 *   items              (repeater)
 *     ↳ item_title       (text)
 *     ↳ item_icon_svg    (textarea — raw SVG markup)
 *     ↳ item_description (text)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$bg      = get_sub_field( 'section_background' ) ?: 'surface';
$label   = get_sub_field( 'section_label' );
$heading = get_sub_field( 'section_heading' );
$desc    = get_sub_field( 'section_desc' );
$items   = get_sub_field( 'items' );
$bg_var  = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';
?>

<section class="info-section" style="background:<?php echo esc_attr( $bg_var ); ?>;">
	<div class="container">
		<?php if ( $label || $heading || $desc ) : ?>
			<div class="section-header fade-in" style="margin-bottom:var(--space-8);">
				<?php if ( $label ) : ?>
					<p class="section-label"><?php echo esc_html( $label ); ?></p>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="section-heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $desc ) : ?>
					<p class="section-desc"><?php echo esc_html( $desc ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $items ) : ?>
			<div class="features__grid features__grid--4col fade-in">
				<?php foreach ( $items as $item ) :
					$title = ! empty( $item['item_title'] ) ? $item['item_title'] : '';
					$icon  = ! empty( $item['item_icon_svg'] ) ? $item['item_icon_svg'] : '';
					$text  = ! empty( $item['item_description'] ) ? $item['item_description'] : '';
				?>
					<div class="feature">
						<?php if ( $icon ) : ?>
							<div class="feature__icon">
								<?php echo $icon; // Raw SVG — output as-is, editor-controlled ?>
							</div>
						<?php endif; ?>
						<?php if ( $title ) : ?>
							<h3 class="feature__title" style="font-size:var(--text-sm);"><?php echo esc_html( $title ); ?></h3>
						<?php endif; ?>
						<?php if ( $text ) : ?>
							<p class="feature__text"><?php echo esc_html( $text ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
