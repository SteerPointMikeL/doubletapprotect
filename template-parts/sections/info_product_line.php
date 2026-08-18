<?php
/**
 * Info Section: Our Product Line
 * Layout: info_product_line
 *
 * Fields:
 *   section_background   (select: bg | surface)
 *   section_heading      (text)
 *   products             (repeater)
 *     ↳ product_name         (text)
 *     ↳ product_description  (wysiwyg)
 *     ↳ product_instructions (wysiwyg)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$bg       = get_sub_field( 'section_background' ) ?: 'bg';
$heading  = get_sub_field( 'section_heading' );
$products = get_sub_field( 'products' );
$bg_var   = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';
?>

<section class="info-section" style="background:<?php echo esc_attr( $bg_var ); ?>;">
	<div class="container">
		<div class="info-content" style="max-width:var(--content-default);margin-inline:auto;">
			<?php if ( $heading ) : ?>
				<h2 class="fade-in"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php if ( $products ) : ?>
				<?php foreach ( $products as $product ) :
					$name         = ! empty( $product['product_name'] ) ? $product['product_name'] : '';
					$description  = ! empty( $product['product_description'] ) ? $product['product_description'] : '';
					$instructions = ! empty( $product['product_instructions'] ) ? $product['product_instructions'] : '';
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
			<?php endif; ?>
		</div>
	</div>
</section>
