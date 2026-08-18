<?php
/**
 * Template Name: Instructions
 * Template Post Type: page
 *
 * ACF flexible-content driven page template for the Instructions page.
 * Each section is a layout inside the `instructions_sections` field group.
 *
 * Current layouts:
 *   section_video_grid — multi-column embedded video grid with captions
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="page-instructions">

	<?php if ( function_exists( 'have_rows' ) && have_rows( 'instructions_sections' ) ) : ?>

		<?php while ( have_rows( 'instructions_sections' ) ) : the_row(); ?>
			<?php
			$layout   = get_row_layout();
			$template = get_template_directory() . '/template-parts/sections/' . $layout . '.php';
			if ( file_exists( $template ) ) {
				include $template;
			}
			?>
		<?php endwhile; ?>

	<?php endif; ?>

</main>

<?php get_footer(); ?>
