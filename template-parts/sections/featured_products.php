<?php
/**
 * Universal Section: Featured Products
 *
 * ACF flexible content layout: featured_products (field: content_sections)
 *
 * Fields:
 *   label, heading, description (text)
 *   product_ids (relationship — product post type)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$heading     = get_sub_field( 'heading' ) ?: 'Featured Products';
$label       = get_sub_field( 'label' ) ?: 'Best Sellers';
$desc        = get_sub_field( 'description' ) ?: '';
$product_ids = get_sub_field( 'product_ids' );

$ids = [];
foreach ( (array) $product_ids as $item ) {
	if ( is_object( $item ) ) {
		$ids[] = $item->ID;
	} elseif ( is_int( $item ) || is_string( $item ) ) {
		$ids[] = (int) $item;
	}
}

if ( empty( $ids ) ) {
	$ids = get_posts( [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'fields'         => 'ids',
	] );
}

if ( empty( $ids ) ) {
	return;
}
?>
<section class="products-section" aria-label="<?php esc_attr_e( 'Featured Products', 'doubletap' ); ?>">
	<div class="container">

		<div class="section-header fade-in">
			<?php if ( $label ) : ?>
				<p class="section-label"><?php echo esc_html( $label ); ?></p>
			<?php endif; ?>
			<h2 class="section-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php if ( $desc ) : ?>
				<p class="section-desc"><?php echo esc_html( $desc ); ?></p>
			<?php endif; ?>
		</div>

		<div class="products-grid">
			<?php
			foreach ( $ids as $product_id ) :
				$product = wc_get_product( $product_id );
				if ( ! $product || ! $product->is_visible() ) {
					continue;
				}

				$is_merchant_kit = ( $product->get_sku() === 'DT-MK-001' );
				$badge           = get_post_meta( $product_id, '_product_badge', true );
				$image_id        = $product->get_image_id();
				$image_src       = $image_id ? wp_get_attachment_image_url( $image_id, 'product-card' ) : wc_placeholder_img_src( 'product-card' );
				$image_alt       = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : $product->get_name();
			?>
				<article class="product-card fade-in">

					<div class="product-card__image">
						<?php if ( $badge ) : ?>
							<span class="product-card__badge"><?php echo esc_html( $badge ); ?></span>
						<?php endif; ?>
						<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" tabindex="-1" aria-hidden="true">
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
						<h3 class="product-card__name">
							<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
								<?php echo esc_html( $product->get_name() ); ?>
							</a>
						</h3>
						<p class="product-card__desc">
							<?php echo esc_html( wp_trim_words( $product->get_short_description(), 20 ) ); ?>
						</p>
						<div class="product-card__price">
							<?php if ( $is_merchant_kit ) : ?>
								<span class="product-card__price--contact"><?php esc_html_e( 'Contact for Pricing', 'doubletap' ); ?></span>
							<?php else : ?>
								<?php echo wp_kses_post( $product->get_price_html() ); ?>
							<?php endif; ?>
						</div>
					</div>

				</article>
			<?php endforeach; ?>
		</div>

		<div class="products-section__footer fade-in">
			<?php if ( function_exists( 'wc_get_page_id' ) ) : ?>
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn btn--outline btn--lg">
					<?php esc_html_e( 'View All Products', 'doubletap' ); ?>
				</a>
			<?php endif; ?>
		</div>

	</div>
</section>
