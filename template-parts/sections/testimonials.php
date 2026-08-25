<?php
/**
 * Universal Section: Testimonials
 *
 * ACF flexible content layout: testimonials (field: content_sections)
 * Merges section_testimonials (home, star rating) and the dt-fk
 * testimonials block (landing pages, no rating) into one layout.
 *
 * Fields:
 *   label, heading (text, optional)
 *   testimonials (repeater: rating (optional 1-5), text, author, role)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$label        = get_sub_field( 'label' );
$heading      = get_sub_field( 'heading' );
$testimonials = get_sub_field( 'testimonials' );

if ( ! $testimonials ) {
	return;
}
?>
<section class="testimonials" aria-label="<?php esc_attr_e( 'Customer testimonials', 'doubletap' ); ?>">
	<div class="container">

		<?php if ( $label || $heading ) : ?>
			<div class="section-header fade-in">
				<?php if ( $label ) : ?>
					<p class="section-label"><?php echo esc_html( $label ); ?></p>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="section-heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="testimonials__grid">
			<?php foreach ( $testimonials as $t ) :
				$rating = isset( $t['rating'] ) && $t['rating'] !== '' ? min( 5, max( 1, (int) $t['rating'] ) ) : 0;
			?>
				<blockquote class="testimonial fade-in">
					<?php if ( $rating > 0 ) : ?>
						<div class="testimonial__stars" aria-label="<?php echo esc_attr( sprintf( _n( '%d star', '%d stars', $rating, 'doubletap' ), $rating ) ); ?>">
							<?php for ( $i = 0; $i < $rating; $i++ ) { echo '&#9733;'; } ?>
						</div>
					<?php endif; ?>
					<p class="testimonial__text">"<?php echo esc_html( $t['text'] ?? '' ); ?>"</p>
					<footer>
						<cite>
							<p class="testimonial__author"><?php echo esc_html( $t['author'] ?? '' ); ?></p>
							<?php if ( ! empty( $t['role'] ) ) : ?>
								<p class="testimonial__role"><?php echo esc_html( $t['role'] ); ?></p>
							<?php endif; ?>
						</cite>
					</footer>
				</blockquote>
			<?php endforeach; ?>
		</div>

	</div>
</section>
