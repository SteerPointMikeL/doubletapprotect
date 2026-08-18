<?php
/**
 * WooCommerce: Single Product Page
 *
 * Overrides woocommerce/templates/single-product.php
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	global $product;
	if ( empty( $product ) ) {
		$product = wc_get_product( get_the_ID() );
	}
	?>

	<main id="main" class="site-main">

		<div class="single-product-wrapper">
			<div class="container">

				<!-- Breadcrumb -->
				<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'doubletap' ); ?>">
					<?php woocommerce_breadcrumb( [ 'wrap_before' => '', 'wrap_after' => '', 'delimiter' => '<span aria-hidden="true"> / </span>', 'home' => __( 'Home', 'doubletap' ) ] ); ?>
				</nav>

				<?php do_action( 'woocommerce_before_single_product' ); ?>

				<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'single-product-layout', $product ); ?>>

					<!-- Gallery -->
					<div class="single-product__gallery">
						<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
					</div>

					<!-- Summary -->
					<div class="single-product__summary">

						<?php do_action( 'woocommerce_single_product_summary' ); ?>

					</div>

				</div>

				<?php do_action( 'woocommerce_after_single_product' ); ?>

				<!-- Related products -->
				<div style="margin-top: var(--space-16);">
					<?php
					$related_products = wc_get_related_products( $product->get_id(), 3 );
					if ( ! empty( $related_products ) ) :
					?>
						<div class="section-header fade-in">
							<h2 class="section-heading"><?php esc_html_e( 'You Might Also Like', 'doubletap' ); ?></h2>
						</div>
						<div class="products-grid">
							<?php
							foreach ( $related_products as $related_id ) :
								$post_object = get_post( $related_id );
								setup_postdata( $GLOBALS['post'] = $post_object ); // phpcs:ignore
								wc_get_template_part( 'content', 'product' );
							endforeach;
							wp_reset_postdata();
							?>
						</div>
					<?php endif; ?>
				</div>

			</div>
		</div>

	</main>

	<?php
endwhile;

get_footer();
