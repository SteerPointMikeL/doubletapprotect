<?php
/**
 * Default Page Template
 *
 * Used for all pages that do not have a more specific template
 * (page-{slug}.php, page-{id}.php, or a Template Name: header).
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

get_header();

$doubletap_has_sections = function_exists( 'have_rows' ) && have_rows( 'content_sections' );
?>

<main id="main" class="site-main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( $doubletap_has_sections ) : ?>

			<?php doubletap_render_flexible_sections( get_the_ID(), 'content_sections' ); ?>

		<?php else : ?>

			<section class="page-header">
				<div class="container">
					<h1 class="page-header__title"><?php the_title(); ?></h1>
				</div>
			</section>

			<div class="container page-content" style="padding-top: var(--space-10); padding-bottom: var(--space-16);">
				<?php the_content(); ?>
			</div>

		<?php endif; ?>

	<?php endwhile; ?>

</main>

<?php get_footer(); ?>
