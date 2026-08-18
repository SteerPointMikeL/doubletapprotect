<?php
/**
 * WooCommerce: Archive Product (Shop Page)
 *
 * Overrides woocommerce/templates/archive-product.php
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Get current category if filtering
$current_cat = get_queried_object();
$page_title  = is_product_category() && $current_cat ? $current_cat->name : __( 'Shop', 'doubletap' );
$page_desc   = is_product_category() && $current_cat ? $current_cat->description : __( 'Aerospace-grade firearms protection products — made in America.', 'doubletap' );
?>

<main id="main" class="site-main">

	<!-- Page Header -->
	<section class="page-header">
		<div class="container">
			<?php woocommerce_breadcrumb( [ 'wrap_before' => '<nav class="breadcrumb">', 'wrap_after' => '</nav>', 'delimiter' => '<span aria-hidden="true">/</span>', 'home' => __( 'Home', 'doubletap' ) ] ); ?>
			<h1 class="page-header__title"><?php echo esc_html( $page_title ); ?></h1>
			<?php if ( $page_desc ) : ?>
				<p class="page-header__subtitle"><?php echo esc_html( strip_tags( $page_desc ) ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- Shop Content -->
	<section class="shop-content">
		<div class="container">

			<?php
			/**
			 * Hook: woocommerce_before_shop_loop
			 * Adds: result count, ordering dropdown
			 */
			do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php woocommerce_product_loop_start(); ?>

			<?php if ( woocommerce_product_loop() ) : ?>

				<?php
				wc_setup_loop( [
					'loop'         => 0,
					'columns'      => apply_filters( 'loop_shop_columns', 3 ),
					'name'         => 'product',
					'is_shortcode' => false,
					'is_search'    => false,
					'is_paginated' => wc_string_to_bool( wc_get_loop_prop( 'is_paginated', true ) ),
					'total'        => $GLOBALS['wp_query']->found_posts ?? 0,
					'total_pages'  => $GLOBALS['wp_query']->max_num_pages ?? 1,
					'per_page'     => get_option( 'posts_per_page' ),
					'current_page' => max( 1, get_query_var( 'paged', 1 ) ),
				] );

				while ( have_posts() ) :
					the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				?>

			<?php else : ?>

				<?php do_action( 'woocommerce_no_products_found' ); ?>

			<?php endif; ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
			/**
			 * Hook: woocommerce_after_shop_loop
			 * Adds: pagination
			 */
			do_action( 'woocommerce_after_shop_loop' );
			?>

		</div>
	</section>

</main>

<!-- CTA Banner -->
<section class="cta-banner">
	<div class="container">
		<div class="cta-banner__inner">
			<h2 class="cta-banner__title"><?php esc_html_e( 'Questions About Our Products?', 'doubletap' ); ?></h2>
			<p class="cta-banner__text"><?php esc_html_e( 'Reach out and we\'ll help you find the right protection solution for your arsenal.', 'doubletap' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--outline btn--lg">
				<?php esc_html_e( 'Contact Us', 'doubletap' ); ?>
			</a>
		</div>
	</div>
</section>

<?php
get_footer();
