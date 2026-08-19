<?php
/**
 * Universal Section: Features / Benefits Grid
 *
 * ACF flexible content layout: features_grid (field: content_sections)
 * Merges section_features (3-col, home) and info_what_it_protects
 * (4-col, information page) into one column-configurable layout.
 *
 * Fields:
 *   label, heading, description (text)
 *   columns             (select: 3 | 4)
 *   section_background  (select: bg | surface)
 *   items (repeater: icon (svg textarea), title, description)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$label   = get_sub_field( 'label' );
$heading = get_sub_field( 'heading' );
$desc    = get_sub_field( 'description' );
$columns = (int) ( get_sub_field( 'columns' ) ?: 3 );
$bg      = get_sub_field( 'section_background' ) ?: 'bg';
$items   = get_sub_field( 'items' );
$bg_var  = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';

if ( ! $items ) {
	return;
}
?>
<section class="features" style="background:<?php echo esc_attr( $bg_var ); ?>;" aria-label="<?php esc_attr_e( 'Features and benefits', 'doubletap' ); ?>">
	<div class="container">

		<?php if ( $label || $heading || $desc ) : ?>
			<div class="section-header fade-in">
				<?php if ( $label ) : ?>
					<p class="section-label"><?php echo esc_html( $label ); ?></p>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="section-heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $desc ) : ?>
					<p class="section-desc"><?php echo esc_html( $desc ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="features__grid<?php echo ( $columns === 4 ) ? ' features__grid--4col' : ''; ?>">
			<?php foreach ( $items as $item ) : ?>
				<div class="feature fade-in">
					<?php if ( ! empty( $item['icon'] ) ) : ?>
						<div class="feature__icon" aria-hidden="true">
							<?php
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo $item['icon'];
							?>
						</div>
					<?php endif; ?>
					<h3 class="feature__title"><?php echo esc_html( $item['title'] ); ?></h3>
					<?php if ( ! empty( $item['description'] ) ) : ?>
						<p class="feature__text"><?php echo esc_html( $item['description'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
