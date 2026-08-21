<?php
/**
 * Universal Section: Bullet List
 *
 * ACF flexible content layout: bullet_list (field: content_sections)
 *
 * A lightweight, icon-free alternative to compatibility_grid for pages
 * with a large number of plain items where sourcing/maintaining an icon
 * per item isn't worthwhile. Renders as a clean multi-column bulleted
 * list.
 *
 * Fields:
 *   heading             (text, optional)
 *   columns             (select: 2 | 3 | 4)
 *   section_background  (select: bg | surface)
 *   items (repeater: text)
 *   spacing_top          (select: normal | tight | none, optional)
 *   spacing_bottom       (select: normal | tight | none, optional)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$heading   = get_sub_field( 'heading' );
$cols      = (int) ( get_sub_field( 'columns' ) ?: 4 );
$bg        = get_sub_field( 'section_background' ) ?: 'bg';
$items     = get_sub_field( 'items' );
$bg_var    = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';
$space_cls = doubletap_section_spacing_class( get_sub_field( 'spacing_top' ), get_sub_field( 'spacing_bottom' ) );

if ( ! $items ) {
	return;
}

$cols = in_array( $cols, array( 2, 3, 4 ), true ) ? $cols : 4;
?>
<section class="bullet-list<?php echo esc_attr( $space_cls ); ?>" style="background:<?php echo esc_attr( $bg_var ); ?>;" aria-label="<?php echo $heading ? esc_attr( $heading ) : esc_attr__( 'List', 'doubletap' ); ?>">
	<div class="container">

		<?php if ( $heading ) : ?>
			<h2 class="section-heading section-heading--center fade-in"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<ul class="bullet-list__list bullet-list__list--<?php echo esc_attr( $cols ); ?>col fade-in">
			<?php foreach ( $items as $item ) :
				$text = $item['text'] ?? '';
				if ( ! $text ) {
					continue;
				}
			?>
				<li class="bullet-list__item"><?php echo esc_html( $text ); ?></li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
