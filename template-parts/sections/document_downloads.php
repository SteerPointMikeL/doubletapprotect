<?php
/**
 * Universal Section: Document Downloads
 *
 * ACF flexible content layout: document_downloads (field: content_sections)
 *
 * Grid of downloadable documents (e.g. Safety Data Sheets): a fixed "file
 * download" icon + title + a link-text label (defaults to "Download PDF")
 * per item, with the whole card clickable through to the file. Matches
 * the "documents" section on
 * https://www.outdoorprotector.com/safety-data-sheet/.
 *
 * Fields:
 *   heading             (text, optional)
 *   columns             (select: 2 | 3 | 4)
 *   section_background  (select: bg | surface)
 *   items (repeater: title, file, link_text)
 *   spacing_top          (select: normal | tight | none, optional)
 *   spacing_bottom       (select: normal | tight | none, optional)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$heading   = get_sub_field( 'heading' );
$cols      = (int) ( get_sub_field( 'columns' ) ?: 3 );
$bg        = get_sub_field( 'section_background' ) ?: 'bg';
$items     = get_sub_field( 'items' );
$bg_var    = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';
$space_cls = doubletap_section_spacing_class( get_sub_field( 'spacing_top' ), get_sub_field( 'spacing_bottom' ) );

if ( ! $items ) {
	return;
}

$cols = in_array( $cols, array( 2, 3, 4 ), true ) ? $cols : 3;
?>
<section class="document-downloads<?php echo esc_attr( $space_cls ); ?>" style="background:<?php echo esc_attr( $bg_var ); ?>;" aria-label="<?php echo $heading ? esc_attr( $heading ) : esc_attr__( 'Document downloads', 'doubletap' ); ?>">
	<div class="container">

		<?php if ( $heading ) : ?>
			<h2 class="section-heading section-heading--center fade-in"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<div class="document-downloads__grid document-downloads__grid--<?php echo esc_attr( $cols ); ?>col">
			<?php foreach ( $items as $item ) :
				$title     = $item['title'] ?? '';
				$file      = $item['file'] ?? null;
				$file_url  = ! empty( $file['url'] ) ? $file['url'] : '';
				$link_text = ! empty( $item['link_text'] ) ? $item['link_text'] : __( 'Download PDF', 'doubletap' );

				if ( ! $title || ! $file_url ) {
					continue;
				}
			?>
				<a
					href="<?php echo esc_url( $file_url ); ?>"
					class="document-downloads__card fade-in"
					target="_blank"
					rel="noopener"
					aria-label="<?php echo esc_attr( $link_text . ': ' . $title ); ?>"
				>
					<div class="document-downloads__icon" aria-hidden="true">
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo doubletap_get_icon( 'pdf' );
						?>
					</div>
					<p class="document-downloads__title"><?php echo esc_html( $title ); ?></p>
					<p class="document-downloads__link-text"><?php echo esc_html( $link_text ); ?></p>
				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
