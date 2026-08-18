<?php
/**
 * Double Tap Protect — functions.php
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

// ─── Theme Setup ──────────────────────────────────────────────────────────
add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'doubletap', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
	add_theme_support( 'custom-logo', [
		'height'      => 80,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	] );

	// WooCommerce
	add_theme_support( 'woocommerce', [
		'thumbnail_image_width'         => 600,
		'gallery_thumbnail_image_width' => 100,
		'single_image_width'            => 800,
	] );
	// Zoom intentionally omitted — its JS intercepts clicks and blocks PhotoSwipe.
	// Lightbox (PhotoSwipe) is triggered by clicking the image <a> directly.
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Navigation menus — managed at Appearance → Menus
	register_nav_menus( [
		'primary' => __( 'Primary Navigation', 'doubletap' ),
		'footer'  => __( 'Footer Navigation', 'doubletap' ),
	] );

	// Image sizes
	add_image_size( 'product-card',    600, 600, true );
	add_image_size( 'hero-bg',        1920, 1080, true );
	add_image_size( 'category-tile',   600, 600, true );
	add_image_size( 'brand-story',     800, 600, true );
} );


// ─── Widget Areas (Sidebars) ──────────────────────────────────────────────
// Manage widgets at Appearance → Widgets → Blog Sidebar.
add_action( 'widgets_init', function () {
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'doubletap' ),
		'id'            => 'blog-sidebar',
		'description'   => __( 'Right-hand sidebar shown on single blog posts. Drop any widgets here (Search, Recent Posts, Categories, custom HTML, CTA blocks, etc.).', 'doubletap' ),
		'before_widget' => '<section id="%1$s" class="dt-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="dt-widget__title">',
		'after_title'   => '</h3>',
	) );
} );


// ─── Enqueue Scripts & Styles ─────────────────────────────────────────────
add_action( 'wp_enqueue_scripts', function () {
	// Google Fonts preconnect happens in header.php

	// Google Fonts stylesheet
	wp_enqueue_style(
		'doubletap-fonts',
		'https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap',
		[],
		null
	);

	// Main stylesheet (theme header + design tokens + all component styles)
	wp_enqueue_style(
		'doubletap-style',
		get_stylesheet_uri(),
		[ 'doubletap-fonts' ],
		//wp_get_theme()->get( 'Version' )
		uniqid() // Web server cache busting while site is under development. Remove when finished.
	);

	// Navigation JS (mobile hamburger + submenu toggle)
	wp_enqueue_script(
		'doubletap-nav',
		get_theme_file_uri( 'assets/js/navigation.js' ),
		[],
		wp_get_theme()->get( 'Version' ),
		true
	);

	// Animation JS
	wp_enqueue_script(
		'doubletap-animations',
		get_theme_file_uri( 'assets/js/animations.js' ),
		[],
		wp_get_theme()->get( 'Version' ),
		true
	);

	// WooCommerce cart fragment refresh
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
} );


// ─── WooCommerce: Remove Default Styles ───────────────────────────────────
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


// ─── ACF Pro: Local JSON Sync ──────────────────────────────────────────────
add_filter( 'acf/settings/save_json', function () {
	return get_template_directory() . '/acf-json';
} );
add_filter( 'acf/settings/load_json', function ( $paths ) {
	$paths[] = get_template_directory() . '/acf-json';
	return $paths;
} );


// ─── Include Modules ──────────────────────────────────────────────────────
require_once get_template_directory() . '/inc/woocommerce.php';
require_once get_template_directory() . '/inc/gravity-forms.php';


// ─── Helper: Cart Count ────────────────────────────────────────────────────
function doubletap_cart_count() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return 0;
	}
	return WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}


// ─── Helper: SVG icons ────────────────────────────────────────────────────
function doubletap_get_icon( string $name ): string {
	$icons = [
		'cart'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>',
		'phone'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.64 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.55 1.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 9a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.73 16z"/></svg>',
		'email'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
		'clock'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
		'instagram'=> '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
		'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
		'shield'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
		'star'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
		'pdf'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
		'check'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>',
		'award'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>',
		'zap'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
	];

	return $icons[ $name ] ?? '';
}


// ─── Flexible Content Section Renderer ────────────────────────────────────
/**
 * Renders flexible content layout sections for a given post/page.
 * Used by front-page.php and can be used by any page template.
 *
 * @param int|null $post_id  Post ID (null = current post)
 * @param string   $field    ACF flexible content field name
 */
function doubletap_render_flexible_sections( $post_id = null, string $field = 'page_sections' ): void {
	if ( ! function_exists( 'have_rows' ) ) {
		return;
	}

	if ( have_rows( $field, $post_id ) ) {
		while ( have_rows( $field, $post_id ) ) {
			the_row();
			$layout = get_row_layout();

			$template = get_template_directory() . '/template-parts/sections/' . $layout . '.php';
			if ( file_exists( $template ) ) {
				include $template;
			}
		}
	}
}


// ─── Disable Block Editor on Custom Template Pages ──────────────────────
// Keep front-end templates in PHP / ACF, not Gutenberg
add_filter( 'use_block_editor_for_post', function ( $use, $post ) {
	$acf_templates = [ 'front-page.php', 'page-information.php', 'page-contact.php', 'template-landing.php', 'page-galleries.php' ];
	$template      = get_page_template_slug( $post->ID );

	if ( in_array( $template, $acf_templates, true ) ) {
		return false;
	}

	// Also disable for front page
	if ( (int) get_option( 'page_on_front' ) === $post->ID ) {
		return false;
	}

	return $use;
}, 10, 2 );
