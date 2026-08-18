<?php
/**
 * WooCommerce: Product Loop Item (content-product.php)
 *
 * Overrides woocommerce/templates/content-product.php
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$is_merchant_kit = ( $product->get_sku() === 'DT-MK-001' );
$badge           = get_post_meta( $product->get_id(), '_product_badge', true );
$image_id        = $product->get_image_id();
$image_src       = $image_id
	? wp_get_attachment_image_url( $image_id, 'product-card' )
	: wc_placeholder_img_src( 'product-card' );
$image_alt       = $image_id
	? (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true )
	: $product->get_name();
?>
<article <?php wc_product_class( 'product-card fade-in', $product ); ?>>

	<div class="product-card__image">
		<?php if ( $badge ) : ?>
			<span class="product-card__badge"><?php echo esc_html( $badge ); ?></span>
		<?php elseif ( $product->is_on_sale() ) : ?>
			<span class="product-card__badge"><?php esc_html_e( 'Sale', 'doubletap' ); ?></span>
		<?php endif; ?>

		<a href="<?php echo esc_url( get_permalink() ); ?>" tabindex="-1" aria-hidden="true">
			<img
				src="<?php echo esc_url( $image_src ); ?>"
				alt="<?php echo esc_attr( $image_alt ); ?>"
				width="600"
				height="600"
				loading="lazy"
			>
		</a>
	</div>

	<div class="product-card__body">
		<h2 class="product-card__name">
			<a href="<?php echo esc_url( get_permalink() ); ?>">
				<?php the_title(); ?>
			</a>
		</h2>

		<?php if ( $product->get_short_description() ) : ?>
			<p class="product-card__desc">
				<?php echo esc_html( wp_trim_words( $product->get_short_description(), 18 ) ); ?>
			</p>
		<?php endif; ?>

		<div class="product-card__price">
			<?php if ( $is_merchant_kit ) : ?>
				<span class="product-card__price--contact">
					<?php esc_html_e( 'Contact for Pricing', 'doubletap' ); ?>
				</span>
			<?php else : ?>
				<?php echo wp_kses_post( $product->get_price_html() ); ?>
			<?php endif; ?>
		</div>

		<div style="margin-top: var(--space-4);">
			<?php if ( $is_merchant_kit ) : ?>
				<a href="<?php echo esc_url( home_url( '/contact/?subject=Merchant+Kit+Inquiry' ) ); ?>" class="btn btn--outline">
					<?php esc_html_e( 'Inquire', 'doubletap' ); ?>
				</a>
			<?php else : ?>
				<?php
				woocommerce_template_loop_add_to_cart( [
					'quantity'   => 1,
					'class'      => 'btn btn--primary',
					'attributes' => [
						'data-product_id'  => $product->get_id(),
						'data-product_sku' => $product->get_sku(),
					],
				] );
				?>
			<?php endif; ?>
		</div>
	</div>

</article>
