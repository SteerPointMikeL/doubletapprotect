<?php
/**
 * Index — Blog fallback (required by WordPress)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="site-main">

	<section class="page-header">
		<div class="container">
			<?php if ( is_home() && ! is_front_page() ) : ?>
				<h1 class="page-header__title"><?php single_post_title(); ?></h1>
			<?php elseif ( is_search() ) : ?>
				<h1 class="page-header__title">
					<?php printf( esc_html__( 'Search Results: %s', 'doubletap' ), '<span>' . get_search_query() . '</span>' ); ?>
				</h1>
			<?php else : ?>
				<h1 class="page-header__title"><?php esc_html_e( 'Latest Posts', 'doubletap' ); ?></h1>
			<?php endif; ?>
		</div>
	</section>

	<div class="container" style="padding-top: var(--space-10); padding-bottom: var(--space-16);">
		<?php if ( have_posts() ) : ?>

			<div class="products-grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'product-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="product-card__image">
								<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
									<?php the_post_thumbnail( 'product-card' ); ?>
								</a>
							</div>
						<?php endif; ?>
						<div class="product-card__body">
							<h2 class="product-card__name">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<p class="product-card__desc"><?php the_excerpt(); ?></p>
							<a href="<?php the_permalink(); ?>" class="btn btn--outline" style="margin-top: var(--space-4);">
								<?php esc_html_e( 'Read More', 'doubletap' ); ?>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div style="margin-top: var(--space-12);">
				<?php the_posts_navigation(); ?>
			</div>

		<?php else : ?>
			<p style="color: var(--color-text-muted);"><?php esc_html_e( 'No posts found.', 'doubletap' ); ?></p>
		<?php endif; ?>
	</div>

</main>

<?php get_footer(); ?>
