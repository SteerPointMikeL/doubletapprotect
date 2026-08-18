<?php
/**
 * Section: Testimonials
 *
 * ACF flexible content layout: section_testimonials
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$get          = function_exists( 'get_sub_field' ) && get_sub_field( 'testimonials_heading' ) !== null ? 'get_sub_field' : 'get_field';
$label        = $get( 'testimonials_label' );
$heading      = $get( 'testimonials_heading' );
$testimonials = $get( 'testimonials' );

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
				$rating = min( 5, max( 1, (int) ( $t['testimonial_rating'] ?? 5 ) ) );
			?>
				<blockquote class="testimonial fade-in">
					<div class="testimonial__stars" aria-label="<?php echo esc_attr( sprintf( _n( '%d star', '%d stars', $rating, 'doubletap' ), $rating ) ); ?>">
						<?php for ( $i = 0; $i < $rating; $i++ ) { echo '&#9733;'; } ?>
					</div>
					<p class="testimonial__text">"<?php echo esc_html( $t['testimonial_text'] ?? '' ); ?>"</p>
					<footer>
						<cite>
							<p class="testimonial__author"><?php echo esc_html( $t['testimonial_author'] ?? '' ); ?></p>
							<?php if ( ! empty( $t['testimonial_role'] ) ) : ?>
								<p class="testimonial__role"><?php echo esc_html( $t['testimonial_role'] ); ?></p>
							<?php endif; ?>
						</cite>
					</footer>
				</blockquote>
			<?php endforeach; ?>
		</div>

	</div>
</section>
