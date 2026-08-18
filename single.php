P
<?php
/**
 * Single blog post template — Double Tap Protect
 * Renders a single post with featured image hero, article body, and full-width layout.
 *
 * @package doubletap
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="dt-blog">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'dt-blog__post' ); ?>>
			<!-- Featured image hero -->
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="dt-blog__hero" style="background-image: linear-gradient(rgba(13,13,13,0.55), rgba(13,13,13,0.85)), url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>');">
					<div class="dt-blog__hero-inner">
						<h1 class="dt-blog__title"><?php the_title(); ?></h1>
					</div>
				</div>
			<?php else : ?>
				<div class="dt-blog__header">
					<h1 class="dt-blog__title"><?php the_title(); ?></h1>
				</div>
			<?php endif; ?>
			<!-- Body: Full-width layout (no sidebar) -->
			<div class="dt-blog__body">
				<div class="dt-blog__layout dt-blog__layout--full-width">
					<div class="dt-blog__content">
						<?php the_content(); ?>
						<?php
						wp_link_pages( array(
							'before' => '<div class="dt-blog__pagination">' . esc_html__( 'Pages:', 'doubletap' ),
							'after'  => '</div>',
						) );
						?>
						<!-- Tags -->
						<?php
						$tags = get_the_tags();
						if ( ! empty( $tags ) ) : ?>
							<div class="dt-blog__tags">
								<?php foreach ( $tags as $tag ) : ?>
									<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="dt-blog__tag">#<?php echo esc_html( $tag->name ); ?></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<!-- Prev / Next navigation -->
						<nav class="dt-blog__nav" aria-label="<?php esc_attr_e( 'Post navigation', 'doubletap' ); ?>">
							<div class="dt-blog__nav-inner">
								<div class="dt-blog__nav-prev">
									<?php previous_post_link( '%link', '<span class="dt-blog__nav-label">&larr; ' . esc_html__( 'Previous Post', 'doubletap' ) . '</span><span class="dt-blog__nav-title">%title</span>' ); ?>
								</div>
								<div class="dt-blog__nav-next">
									<?php next_post_link( '%link', '<span class="dt-blog__nav-label">' . esc_html__( 'Next Post', 'doubletap' ) . ' &rarr;</span><span class="dt-blog__nav-title">%title</span>' ); ?>
								</div>
							</div>
						</nav>
					</div><!-- /.dt-blog__content -->
				</div><!-- /.dt-blog__layout -->
			</div><!-- /.dt-blog__body -->
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer();