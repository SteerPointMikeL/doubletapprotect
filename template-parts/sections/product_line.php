<?php
/**
 * Universal Section: Product Line
 *
 * ACF flexible content layout: product_line (field: content_sections)
 * (Formerly the page-locked "info_product_line" layout on the Information page.)
 *
 * Fields:
 *   heading (text)
 *   section_background (select: bg | surface)
 *   products (repeater: name, description (wysiwyg), instructions (wysiwyg))
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$bg       = get_sub_field( 'section_background' ) ?: 'bg';
$heading  = get_sub_field( 'heading' );
$products = get_sub_field( 'products' );
$bg_var   = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';

if ( ! $products ) {
	return;
}
?>
<section class="info-section" style="background:<?php echo esc_attr( $bg_var ); ?>;">
	<div class="container">
		<div class="info-content" style="max-width:var(--content-default);margin-inline:auto;">
			<?php if ( $heading ) : ?>
				<h2 class="fade-in"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php foreach ( $products as $product ) :
				$name         = $product['name'] ?? '';
				$description  = $product['description'] ?? '';
				$instructions = $product['instructions'] ?? '';
			?>
				<div class="product-info-block fade-in">
					<?php if ( $name ) : ?>
						<h3><?php echo esc_html( $name ); ?></h3>
					<?php endif; ?>
					<?php if ( $description ) : ?>
						<?php echo wp_kses_post( $description ); ?>
					<?php endif; ?>
					<?php if ( $instructions ) : ?>
						<h4 class="product-info-block__instructions-label">
							<?php esc_html_e( 'Application Instructions', 'doubletap' ); ?>
						</h4>
						<?php echo wp_kses_post( $instructions ); ?>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
