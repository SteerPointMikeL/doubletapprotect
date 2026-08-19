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

$doubletap_has_universal_sections = function_exists( 'have_rows' ) && have_rows( 'content_sections' );
$doubletap_has_legacy_sections    = ! $doubletap_has_universal_sections && function_exists( 'have_rows' ) && have_rows( 'instructions_sections' );
?>

<main id="main" class="page-instructions">

	<?php if ( $doubletap_has_universal_sections ) : ?>

		<?php
		// New universal, reusable ACF flexible content — preferred going forward.
		doubletap_render_flexible_sections( get_the_ID(), 'content_sections' );
		?>

	<?php elseif ( $doubletap_has_legacy_sections ) : ?>

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
