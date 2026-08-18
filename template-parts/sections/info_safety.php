<?php
/**
 * Info Section: Safety Information
 * Layout: info_safety
 *
 * Fields:
 *   section_background (select: bg | surface)
 *   section_label      (text)
 *   section_heading    (text)
 *   body               (wysiwyg)
 *   sds_files          (repeater)
 *     ↳ sds_product_name (text)
 *     ↳ sds_file         (file, return_format: array)
 *   cta_label          (text)
 *   cta_url            (url)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$bg        = get_sub_field( 'section_background' ) ?: 'bg';
$label     = get_sub_field( 'section_label' );
$heading   = get_sub_field( 'section_heading' );
$body      = get_sub_field( 'body' );
$sds_files = get_sub_field( 'sds_files' );
$cta_label = get_sub_field( 'cta_label' );
$cta_url   = get_sub_field( 'cta_url' );
$bg_var    = ( $bg === 'surface' ) ? 'var(--color-surface)' : 'var(--color-bg)';
?>

<section class="info-section" style="background:<?php echo esc_attr( $bg_var ); ?>;">
	<div class="container">
		<div class="info-content fade-in" style="max-width:var(--content-default);margin-inline:auto;">

			<?php if ( $label ) : ?>
				<p class="section-label"><?php echo esc_html( $label ); ?></p>
			<?php endif; ?>

			<?php if ( $heading ) : ?>
				<h2><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php if ( $body ) : ?>
				<?php echo wp_kses_post( $body ); ?>
			<?php endif; ?>

			<?php if ( $sds_files ) : ?>
				<ul class="sds-list" role="list">
					<?php foreach ( $sds_files as $entry ) :
						$file = ! empty( $entry['sds_file'] ) ? $entry['sds_file'] : null;
						$name = ! empty( $entry['sds_product_name'] ) ? $entry['sds_product_name'] : '';
						if ( ! $file ) { continue; }
						$file_url = is_array( $file ) ? $file['url'] : $file;
					?>
						<li>
							<a href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noopener noreferrer" download>
								<?php echo doubletap_get_icon( 'pdf' ); ?>
								<?php echo esc_html( $name ); ?> — <?php esc_html_e( 'Safety Data Sheet (PDF)', 'doubletap' ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php elseif ( $cta_label && $cta_url ) : ?>
				<p style="margin-top:var(--space-6);">
					<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary">
						<?php echo esc_html( $cta_label ); ?>
					</a>
				</p>
			<?php endif; ?>

		</div>
	</div>
</section>
