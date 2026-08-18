<?php
/**
 * Front Page Template
 *
 * Renders ACF flexible content sections in the order they are configured
 * in the WordPress admin. Each section type maps to a template part in
 * template-parts/sections/.
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<?php if ( function_exists( 'have_rows' ) && have_rows( 'page_sections' ) ) : ?>

	<?php
	while ( have_rows( 'page_sections' ) ) :
		the_row();
		$layout = get_row_layout();

		$section_template = get_template_directory() . '/template-parts/sections/' . $layout . '.php';

		if ( file_exists( $section_template ) ) {
			include $section_template;
		}
	endwhile;
	?>

<?php else : ?>

	<?php
	/*
	 * FALLBACK: If no flexible content has been configured yet (fresh install),
	 * render all default sections in the handoff-specified order so the page
	 * is never blank. Once ACF fields are populated and sections are added,
	 * the flexible content loop above takes over.
	 */
	get_template_part( 'template-parts/sections/section_hero' );
	get_template_part( 'template-parts/sections/section_category_tiles' );
	get_template_part( 'template-parts/sections/section_brand_story' );
	get_template_part( 'template-parts/sections/section_featured_products' );
	get_template_part( 'template-parts/sections/section_features' );
	get_template_part( 'template-parts/sections/section_testimonials' );
	get_template_part( 'template-parts/sections/section_cta_banner' );
	?>

<?php endif; ?>

<?php
get_footer();
