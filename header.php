<?php
/**
 * The header for Double Tap Protect.
 * Uses WordPress menu system — manage at Appearance → Menus → "Primary" location.
 *
 * @package DoubleTapProtect
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-360MMW13FG"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-360MMW13FG');
</script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'doubletapprotect' ); ?></a>

<!-- Top bar (matches existing theme markup) -->
<div class="top-bar" role="banner">
	<div class="container">
		<span class="top-bar__tagline">2A Strong. American Made Protection.</span>
		<a class="top-bar__phone" href="tel:3172367701">317-236-7701</a>
	</div>
</div>

<header class="site-header">
	<nav class="nav" aria-label="Primary Navigation">

		<!-- Logo -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav__logo" rel="home" aria-label="<?php bloginfo( 'name' ); ?> &mdash; Home">
			<img
				src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/logo.svg"
				alt="<?php bloginfo( 'name' ); ?>"
				loading="eager"
			>
		</a>

		<!-- Primary menu (managed at Appearance → Menus) -->
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav__links',
				'menu_id'        => 'primary-menu',
				'fallback_cb'    => false,
				'depth'          => 2,
			) );
		} else {
			// Fallback if no menu is assigned yet.
			echo '<ul class="nav__links" role="list">';
			echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>';
			echo '<li><a href="' . esc_url( home_url( '/information/' ) ) . '">Information</a></li>';
			echo '<li><a href="' . esc_url( home_url( '/shop/' ) ) . '">Shop</a></li>';
			echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '">Contact Us</a></li>';
			echo '</ul>';
		}
		?>

		<!-- Right side: Cart + mobile toggle -->
		<div class="nav__right">
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="nav__cart" aria-label="Shopping cart">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
				</a>
			<?php endif; ?>

			<!-- Mobile hamburger -->
			<button
				class="nav__toggle"
				aria-controls="primary-menu"
				aria-expanded="false"
				aria-label="Toggle menu"
			>
				<span aria-hidden="true"></span>
				<span aria-hidden="true"></span>
				<span aria-hidden="true"></span>
			</button>
		</div>

	</nav>
</header>
