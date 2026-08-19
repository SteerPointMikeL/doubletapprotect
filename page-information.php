<?php
/**
 * Template Name: Information
 *
 * Fully ACF flexible-content driven version of the Information page.
 * Each section is a layout in the `info_sections` flexible content field.
 * Content is provided via WXR import — no hardcoded defaults.
 *
 * Layouts:
 *   info_page_header    — page title + subtitle
 *   info_brand_story    — heading, body paragraphs (wysiwyg), image
 *   info_methods        — section label/heading + methods repeater (cards)
 *   info_product_line   — section heading + products repeater
 *   info_what_it_protects — section label/heading/desc + items repeater (4-col)
 *   info_safety         — section label/heading, body wysiwyg, SDS files, CTA
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

get_header();

$doubletap_has_universal_sections = function_exists( 'have_rows' ) && have_rows( 'content_sections' );
$doubletap_has_legacy_sections    = ! $doubletap_has_universal_sections && function_exists( 'have_rows' ) && have_rows( 'info_sections' );
?>

<main id="main" class="page-information">

<?php if ( $doubletap_has_universal_sections ) : ?>

	<?php
	// New universal, reusable ACF flexible content — preferred going forward.
	doubletap_render_flexible_sections( get_the_ID(), 'content_sections' );
	?>

<?php elseif ( $doubletap_has_legacy_sections ) : ?>

	<?php
	// Legacy field, kept for backward compatibility with pages not yet migrated.
	while ( have_rows( 'info_sections' ) ) : the_row(); ?>
		<?php
		$layout = get_row_layout();
		get_template_part( 'template-parts/sections/info_' . $layout );
		?>
	<?php endwhile; ?>

<?php endif; ?>

</main>

<?php get_footer(); ?>
