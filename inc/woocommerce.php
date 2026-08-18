<?php
/**
 * WooCommerce setup, hooks, and template customizations.
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// ─── Merchant Kit: Not Purchasable ────────────────────────────────────────
add_filter( 'woocommerce_is_purchasable', function ( $purchasable, $product ) {
	if ( $product->get_sku() === 'DT-MK-001' ) {
		return false;
	}
	return $purchasable;
}, 10, 2 );

// ─── Merchant Kit: Replace Add-to-Cart on Single Product ─────────────────
add_action( 'woocommerce_single_product_summary', function () {
	global $product;

	if ( ! $product || $product->get_sku() !== 'DT-MK-001' ) {
		return;
	}

	echo '<a href="' . esc_url( home_url( '/contact/?subject=Merchant+Kit+Inquiry' ) ) . '" class="btn btn--primary btn--lg">';
	esc_html_e( 'Contact for Pricing', 'doubletap' );
	echo '</a>';
}, 30 );

// Remove default add-to-cart for non-purchasable products on single page
add_filter( 'woocommerce_get_default_attributes', function ( $attributes, $product ) {
	return $attributes;
}, 10, 2 );


// ─── Shop Columns ─────────────────────────────────────────────────────────
add_filter( 'loop_shop_columns', function () {
	return 3;
} );

add_filter( 'loop_shop_per_page', function () {
	return 12;
}, 20 );


// ─── Remove Default WC Breadcrumb Styles ──────────────────────────────────
add_filter( 'woocommerce_breadcrumb_defaults', function ( $args ) {
	$args['wrap_before'] = '<nav class="breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'doubletap' ) . '">';
	$args['wrap_after']  = '</nav>';
	$args['delimiter']   = '<span aria-hidden="true"> / </span>';
	$args['home']        = __( 'Home', 'doubletap' );
	return $args;
} );


// ─── Disable WC Default Styles ────────────────────────────────────────────
// Already done in functions.php via filter, but keep here as well for clarity
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


// ─── Product Badge Meta Box ───────────────────────────────────────────────
add_action( 'woocommerce_product_options_general_product_data', function () {
	woocommerce_wp_text_input( [
		'id'          => '_product_badge',
		'label'       => __( 'Product Badge', 'doubletap' ),
		'placeholder' => __( 'e.g. Best Seller, New, Value', 'doubletap' ),
		'desc_tip'    => true,
		'description' => __( 'Short label displayed on the product card. Leave empty for no badge.', 'doubletap' ),
	] );
} );

add_action( 'woocommerce_process_product_meta', function ( $post_id ) {
	$badge = isset( $_POST['_product_badge'] ) ? sanitize_text_field( wp_unslash( $_POST['_product_badge'] ) ) : '';
	update_post_meta( $post_id, '_product_badge', $badge );
} );


// ─── Cart Fragment Update (header cart count) ─────────────────────────────
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
	$count = WC()->cart->get_cart_contents_count();

	ob_start();
	if ( $count > 0 ) :
		?>
		<span class="nav__cart-count" aria-label="<?php echo esc_attr( sprintf( _n( '%d item', '%d items', $count, 'doubletap' ), $count ) ); ?>">
			<?php echo esc_html( $count ); ?>
		</span>
		<?php
	endif;
	$fragment = ob_get_clean();

	$fragments['.nav__cart-count'] = $fragment;
	return $fragments;
} );


// ─── WooCommerce Checkout Fields ──────────────────────────────────────────
add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
	// unset( $fields['billing']['billing_company'] );
	return $fields;
} );


// ─── Single Product Summary: Title, Price & Size ──────────────────────────
add_action( 'after_setup_theme', function () {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

	add_action( 'woocommerce_single_product_summary', function () {
		?><h1 class="single-product__title"><?php the_title(); ?></h1><?php
	}, 5 );

	add_action( 'woocommerce_single_product_summary', function () {
		global $product;

		if ( ! $product ) {
			return;
		}
		?>
		<div class="single-product__price">
			<?php
			if ( get_post_meta( get_the_ID(), '_dt_contact_for_price', true ) === 'yes' ) {
				echo '<span class="single-product__price--contact">' . esc_html__( 'Contact for Pricing', 'doubletap' ) . '</span>';
			} else {
				echo wp_kses_post( $product->get_price_html() );
			}
			?>
		</div>
		<?php
	}, 10 );

	add_action( 'woocommerce_single_product_summary', function () {
		global $product;

		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return;
		}

		$size = $product->get_attribute( 'size' );

		if ( empty( $size ) ) {
			$size = $product->get_attribute( 'pa_size' );
		}

		if ( empty( $size ) ) {
			return;
		}

		echo '<div class="single-product__size">';
		echo '<span class="single-product__size-label">' . esc_html__( 'Size:', 'doubletap' ) . '</span> ';
		echo '<span class="single-product__size-value">' . esc_html( $size ) . '</span>';
		echo '</div>';
	}, 11 );
} );


// ─── Product Gallery: Click-to-Lightbox ──────────────────────────────────
// wc-product-gallery-zoom is NOT declared in theme support (see functions.php).
// Without zoom, the gallery <a> click falls through to PhotoSwipe directly.


// ─── No sidebar on WooCommerce pages ──────────────────────────────────────
add_filter( 'woocommerce_product_thumbnails_columns', function () {
	return 4;
} );


// ─── Custom "Continue Shopping" URL ───────────────────────────────────────
add_filter( 'woocommerce_continue_shopping_redirect', function () {
	return wc_get_page_permalink( 'shop' );
} );