<?php
/**
 * 404 Error Page
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="site-main">
	<section class="not-found-section">
		<div class="container">
			<div class="fade-in" style="text-align: center;">
				<p class="not-found__code" aria-hidden="true">404</p>
				<h1 class="not-found__title"><?php esc_html_e( 'Mission: Page Not Found', 'doubletap' ); ?></h1>
				<p class="not-found__text">
					<?php esc_html_e( "That page couldn't be located. It may have been moved or removed. Head back to base and try again.", 'doubletap' ); ?>
				</p>
				<div class="hero__ctas" style="justify-content: center;">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary btn--lg">
						<?php esc_html_e( 'Return Home', 'doubletap' ); ?>
					</a>
					<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn btn--outline btn--lg">
						<?php esc_html_e( 'Shop Products', 'doubletap' ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
