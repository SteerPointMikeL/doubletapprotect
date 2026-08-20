<?php
/**
 * Universal Section: Feature Columns
 *
 * ACF flexible content layout: feature_columns (field: content_sections)
 *
 * A plain (non-card) column layout: image on top, then title, then body
 * text, then an optional CTA button — stacked vertically, no card
 * background or absolute-positioned overlay text. Intended for content
 * that needs a simple "picture explains itself, heading, copy, button"
 * column rather than the tile/card treatment used by category_tiles.
 *
 * Fields:
 *   heading             (text, optional)
 *   section_background  (select: bg | surface)
 *   grid_columns        (select: 2 | 3 | 4) - columns per row on desktop,
 *     always a single column on mobile
 *   columns (repeater: image, title, body, cta_text, cta_url, full_link)
 *     full_link (true_false, optional) — when enabled, the entire column
 *     is wrapped in a link to cta_url (in addition to the visible
 *     button); when disabled, only the button itself is clickable.
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$heading      = get_sub_field( 'heading' );
$bg           = get_sub_field( 'section_background' ) ?: 'bg';
$grid_columns = (int) ( get_sub_field( 'grid_columns' ) ?: 3 );
$columns      = get_sub_field( 'columns' );
$bg_var       = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';

if ( ! $columns ) {
	return;
}

$grid_columns = in_array( $grid_columns, array( 2, 3, 4 ), true ) ? $grid_columns : 3;
?>
<section class="feature-columns" style="background:<?php echo esc_attr( $bg_var ); ?>;" aria-label="<?php echo $heading ? esc_attr( $heading ) : esc_attr__( 'Feature columns', 'doubletap' ); ?>">
	<div class="container">

		<?php if ( $heading ) : ?>
			<h2 class="section-heading section-heading--center fade-in"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<div class="feature-columns__grid feature-columns__grid--<?php echo esc_attr( $grid_columns ); ?>col">
			<?php foreach ( $columns as $col ) :
				$img       = $col['image'] ?? null;
				$title     = $col['title'] ?? '';
				$body      = $col['body'] ?? '';
				$cta_text  = $col['cta_text'] ?? '';
				$cta_url   = $col['cta_url'] ?? '';
				$full_link = ! empty( $col['full_link'] ) && $cta_url;
				$Tag       = $full_link ? 'a' : 'div';
			?>
				<<?php echo $Tag; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
					class="feature-column fade-in"
					<?php if ( $full_link ) : ?>href="<?php echo esc_url( $cta_url ); ?>"<?php endif; ?>
				>
					<?php if ( $img ) : ?>
						<div class="feature-column__image-wrap">
							<img
								src="<?php echo esc_url( $img['url'] ); ?>"
								alt="<?php echo esc_attr( $img['alt'] ?? $title ); ?>"
								width="<?php echo esc_attr( $img['width'] ?? 540 ); ?>"
								height="<?php echo esc_attr( $img['height'] ?? 360 ); ?>"
								loading="lazy"
							>
						</div>
					<?php endif; ?>
					<?php if ( $title ) : ?>
						<h3 class="feature-column__title"><?php echo esc_html( $title ); ?></h3>
					<?php endif; ?>
					<?php if ( $body ) : ?>
						<p class="feature-column__body"><?php echo esc_html( $body ); ?></p>
					<?php endif; ?>
					<?php if ( $cta_text && ! $full_link ) : ?>
						<a class="feature-column__cta btn btn--primary" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_text ); ?></a>
					<?php elseif ( $cta_text && $full_link ) : ?>
						<span class="feature-column__cta btn btn--primary" aria-hidden="true"><?php echo esc_html( $cta_text ); ?></span>
					<?php endif; ?>
				</<?php echo $Tag; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>>
			<?php endforeach; ?>
		</div>

	</div>
</section>
