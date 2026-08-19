<?php
/**
 * Template Name: Galleries
 * Template Post Type: page
 *
 * Reusable page template for NextGEN Gallery shortcodes (or any shortcode).
 * Whatever you type into the page editor is rendered as-is — shortcodes,
 * HTML, or plain text all work. Drop in [ngg src="galleries" ids="1"] etc.
 *
 * Layout: full-width dark hero with page title, then a constrained content
 * area that lets gallery thumbnails breathe. Optional sidebar is OFF here so
 * galleries can use the full width.
 *
 * @package doubletap
 */
defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="dt-galleries">

	<?php if ( function_exists( 'have_rows' ) && have_rows( 'content_sections' ) ) : ?>
		<?php
		// Optional universal sections — additive only; the gallery hero and
		// shortcode content below always render regardless.
		doubletap_render_flexible_sections( get_the_ID(), 'content_sections' );
		?>
	<?php endif; ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<!-- Page hero -->
		<?php if ( has_post_thumbnail() ) : ?>
			<section class="dt-galleries__hero" style="background-image: linear-gradient(rgba(13,13,13,0.65), rgba(13,13,13,0.9)), url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>');">
				<div class="dt-galleries__hero-inner">
					<h1 class="dt-galleries__title"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="dt-galleries__subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</div>
			</section>
		<?php else : ?>
			<section class="dt-galleries__header">
				<div class="dt-galleries__hero-inner">
					<h1 class="dt-galleries__title"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="dt-galleries__subtitle"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<!-- Gallery / shortcode content -->
		<section class="dt-galleries__body">
			<div class="dt-galleries__content">
				<?php the_content(); ?>

				<?php
				wp_link_pages( array(
					'before' => '<div class="dt-galleries__pagination">' . esc_html__( 'Pages:', 'doubletap' ),
					'after'  => '</div>',
				) );
				?>
			</div>
		</section>

	<?php endwhile; ?>

</main>

<?php get_footer();
