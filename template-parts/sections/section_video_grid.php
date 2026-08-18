<?php
/**
 * Section: Video Grid
 * ACF flexible content layout: section_video_grid
 *
 * Displays a multi-column grid of embedded videos (Vimeo iframe or HTML5 <video>)
 * with optional per-video captions. Column count is configurable per section
 * (3 = default, supports 2–5 columns) and collapses responsively on smaller screens.
 *
 * ACF fields:
 *   section_background   (select: bg | surface | surface_2)
 *   section_label        (text)   — eyebrow label
 *   section_heading      (text)   — section h2
 *   section_note         (text)   — optional safety note / subtitle below heading
 *   columns              (select: 2 | 3 | 4 | 5) — number of columns
 *   videos               (repeater)
 *     ↳ video_type       (select: iframe | html5)
 *     ↳ video_embed      (textarea) — full <iframe> embed code from Vimeo/YouTube
 *     ↳ video_file       (file, return array) — for html5 type
 *     ↳ video_poster     (image, return array) — poster frame for html5 type
 *     ↳ video_caption    (text) — label shown below the video
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$bg       = get_sub_field( 'section_background' ) ?: 'bg';
$label    = get_sub_field( 'section_label' );
$heading  = get_sub_field( 'section_heading' );
$note     = get_sub_field( 'section_note' );
$columns  = (int) ( get_sub_field( 'columns' ) ?: 3 );
$videos   = get_sub_field( 'videos' );

if ( ! $videos ) {
	return;
}

// Map background select → CSS value
$bg_map = [
	'bg'        => 'var(--color-bg)',
	'surface'   => 'var(--color-surface)',
	'surface_2' => 'var(--color-surface-2)',
];
$bg_css = $bg_map[ $bg ] ?? 'var(--color-bg)';

// Clamp columns to supported range
$columns = max( 2, min( 5, $columns ) );
?>

<section
	class="video-grid-section"
	style="background:<?php echo esc_attr( $bg_css ); ?>;"
	aria-label="<?php echo $heading ? esc_attr( $heading ) : esc_attr__( 'Video instructions', 'doubletap' ); ?>"
>
	<div class="container">

		<?php if ( $label || $heading || $note ) : ?>
			<div class="section-header video-grid-section__header fade-in">
				<?php if ( $label ) : ?>
					<p class="section-label"><?php echo esc_html( $label ); ?></p>
				<?php endif; ?>
				<?php if ( $heading ) : ?>
					<h2 class="section-heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $note ) : ?>
					<p class="video-grid-section__note"><?php echo esc_html( $note ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div
			class="video-grid video-grid--cols-<?php echo esc_attr( $columns ); ?>"
			style="--vg-columns:<?php echo esc_attr( $columns ); ?>;"
		>
			<?php foreach ( $videos as $index => $video ) :
				$type    = ! empty( $video['video_type'] ) ? $video['video_type'] : 'iframe';
				$embed   = ! empty( $video['video_embed'] ) ? $video['video_embed'] : '';
				$file    = ! empty( $video['video_file'] ) ? $video['video_file'] : null;
				$poster  = ! empty( $video['video_poster'] ) ? $video['video_poster'] : null;
				$caption = ! empty( $video['video_caption'] ) ? $video['video_caption'] : '';
			?>
				<figure class="video-grid__item fade-in">

					<div class="video-grid__embed-wrap">
						<?php if ( $type === 'iframe' && $embed ) : ?>
							<?php
							/*
							 * Sanitize the iframe embed code:
							 * Strip any disallowed attributes but allow src, width, height,
							 * frameborder, allow, allowfullscreen, title, loading on iframes.
							 * Editors paste the raw Vimeo/YouTube embed — wp_kses handles it.
							 */
							$allowed_iframe = [
								'iframe' => [
									'src'             => true,
									'width'           => true,
									'height'          => true,
									'frameborder'     => true,
									'allow'           => true,
									'allowfullscreen' => true,
									'title'           => true,
									'loading'         => true,
									'style'           => true,
									'class'           => true,
								],
							];
							echo wp_kses( $embed, $allowed_iframe );
							?>
						<?php elseif ( $type === 'html5' && $file ) :
							$file_url    = is_array( $file ) ? esc_url( $file['url'] ) : esc_url( $file );
							$poster_attr = '';
							if ( $poster && ! empty( $poster['url'] ) ) {
								$poster_attr = ' poster="' . esc_url( $poster['url'] ) . '"';
							}
							$step_label = $caption ?: sprintf( __( 'Video %d', 'doubletap' ), $index + 1 );
						?>
							<video
								controls
								preload="metadata"
								<?php echo $poster_attr; // Already escaped above ?>
								aria-label="<?php echo esc_attr( $step_label ); ?>"
							>
								<source src="<?php echo $file_url; ?>" type="<?php
									$ext = strtolower( pathinfo( is_array( $file ) ? $file['url'] : $file, PATHINFO_EXTENSION ) );
									$mime_map = [ 'mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogv' => 'video/ogg' ];
									echo esc_attr( $mime_map[ $ext ] ?? 'video/mp4' );
								?>">
								<?php esc_html_e( 'Your browser does not support the video element.', 'doubletap' ); ?>
							</video>
						<?php endif; ?>
					</div>

					<?php if ( $caption ) : ?>
						<figcaption class="video-grid__caption">
							<?php echo esc_html( $caption ); ?>
						</figcaption>
					<?php endif; ?>

				</figure>
			<?php endforeach; ?>
		</div>

	</div>
</section>
