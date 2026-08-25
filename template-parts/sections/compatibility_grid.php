<?php
/**
 * Universal Section: Compatibility Grid
 *
 * ACF flexible content layout: compatibility_grid (field: content_sections)
 * Formerly a hardcoded fixed section on template-landing.php.
 *
 * Fields:
 *   heading (text, optional)
 *   items (repeater: label, icon)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$heading = get_sub_field( 'heading' );
$items   = get_sub_field( 'items' );

if ( ! $items ) {
	return;
}
?>
<section class="compatibility-grid">
	<div class="container">
		<?php if ( $heading ) : ?>
			<h2 class="section-heading section-heading--center"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<div class="compatibility-grid__grid">
			<?php foreach ( $items as $item ) :
				$label    = $item['label'] ?? '';
				$icon     = $item['icon'] ?? null;
				$icon_src = ! empty( $icon['url'] ) ? $icon['url'] : '';
			?>
				<div class="compatibility-grid__item">
					<?php if ( $icon_src ) : ?>
						<img class="compatibility-grid__icon" src="<?php echo esc_url( $icon_src ); ?>" alt="<?php echo esc_attr( $label ); ?>" loading="lazy" />
					<?php endif; ?>
					<span class="compatibility-grid__label"><?php echo esc_html( $label ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
