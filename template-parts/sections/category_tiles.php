<?php
/**
 * Universal Section: Category Tiles
 *
 * ACF flexible content layout: category_tiles (field: content_sections)
 *
 * Fields:
 *   heading, subheading (text)
 *   tiles (repeater: tile_image, tile_title, tile_description, tile_link,
 *          tile_cta_text (optional) — renders a visible CTA button with
 *          this label linking to tile_link; when blank, the whole card
 *          remains clickable with no visible button, as before)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$heading = get_sub_field( 'heading' );
$sub     = get_sub_field( 'subheading' );
$tiles   = get_sub_field( 'tiles' );

if ( ! $tiles ) {
	return;
}
?>
<section class="categories" aria-label="<?php esc_attr_e( 'Category tiles', 'doubletap' ); ?>">
	<div class="container">

		<?php if ( $heading || $sub ) : ?>
			<div class="section-header fade-in">
				<?php if ( $heading ) : ?>
					<h2 class="categories__heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $sub ) : ?>
					<p class="categories__sub"><?php echo esc_html( $sub ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="categories__grid">
			<?php foreach ( $tiles as $tile ) :
				$img      = $tile['tile_image'] ?? null;
				$cta_text = $tile['tile_cta_text'] ?? '';
			?>
				<a
					href="<?php echo esc_url( $tile['tile_link'] ?? home_url( '/shop/' ) ); ?>"
					class="category-tile fade-in"
					aria-label="<?php echo esc_attr( sprintf( __( 'Shop %s', 'doubletap' ), $tile['tile_title'] ?? '' ) ); ?>"
				>
					<div class="category-tile__bg" aria-hidden="true">
						<?php if ( $img ) : ?>
							<img
								src="<?php echo esc_url( $img['url'] ); ?>"
								alt=""
								width="<?php echo esc_attr( $img['width'] ?? 600 ); ?>"
								height="<?php echo esc_attr( $img['height'] ?? 600 ); ?>"
								loading="lazy"
							>
						<?php endif; ?>
					</div>
					<div class="category-tile__overlay" aria-hidden="true"></div>
					<div class="category-tile__content">
						<?php if ( ! empty( $tile['tile_title'] ) ) : ?>
							<h3 class="category-tile__title"><?php echo esc_html( $tile['tile_title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $tile['tile_description'] ) ) : ?>
							<p class="category-tile__desc"><?php echo esc_html( $tile['tile_description'] ); ?></p>
						<?php endif; ?>
						<?php if ( $cta_text ) : ?>
							<span class="category-tile__cta btn btn--primary"><?php echo esc_html( $cta_text ); ?></span>
						<?php endif; ?>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
