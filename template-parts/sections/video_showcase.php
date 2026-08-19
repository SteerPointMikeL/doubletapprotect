<?php
/**
 * Universal Section: Video Showcase
 *
 * ACF flexible content layout: video_showcase (field: content_sections)
 * Single click-to-play video with a poster frame. Formerly hardcoded on
 * template-landing.php. For a multi-video grid, use the "video_grid" layout
 * instead.
 *
 * Fields:
 *   heading  (text, optional)
 *   provider (select: youtube | vimeo)
 *   video_id (text)
 *   poster   (image, optional)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$heading  = get_sub_field( 'heading' );
$provider = get_sub_field( 'provider' ) ?: 'youtube';
$video_id = get_sub_field( 'video_id' );
$poster   = get_sub_field( 'poster' );

if ( ! $video_id ) {
	return;
}

$vid   = rawurlencode( $video_id );
$embed = '';
if ( $provider === 'youtube' ) {
	$embed = 'https://www.youtube-nocookie.com/embed/' . $vid . '?rel=0&modestbranding=1&playsinline=1';
} elseif ( $provider === 'vimeo' ) {
	$embed = 'https://player.vimeo.com/video/' . $vid . '?title=0&byline=0&portrait=0';
}
$poster_src = ! empty( $poster['url'] ) ? $poster['url'] : '';

// Unique id so multiple video_showcase sections on one page don't collide,
// and so the click-to-play script is only printed once per page.
if ( ! isset( $GLOBALS['dt_video_showcase_count'] ) ) {
	$GLOBALS['dt_video_showcase_count'] = 0;
}
$GLOBALS['dt_video_showcase_count']++;
$dt_video_showcase_i = $GLOBALS['dt_video_showcase_count'];
$uid                 = 'video-showcase-' . $dt_video_showcase_i;
?>
<section class="video-showcase">
	<div class="container">
		<?php if ( $heading ) : ?>
			<h2 class="section-heading section-heading--center"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<div
			id="<?php echo esc_attr( $uid ); ?>"
			class="video-showcase__frame"
			<?php if ( $poster_src ) : ?> style="background-image:url('<?php echo esc_url( $poster_src ); ?>');"<?php endif; ?>
			data-embed="<?php echo esc_url( $embed ); ?>"
		>
			<button type="button" class="video-showcase__play" aria-label="<?php esc_attr_e( 'Play video', 'doubletap' ); ?>">
				<svg viewBox="0 0 64 64" aria-hidden="true"><polygon points="24,16 52,32 24,48" fill="currentColor"/></svg>
			</button>
		</div>
	</div>
</section>
<?php if ( $dt_video_showcase_i === 1 ) : ?>
<script>
(function(){
	document.addEventListener('click', function(e){
		var btn = e.target.closest('.video-showcase__play');
		if(!btn) return;
		var frame = btn.closest('.video-showcase__frame');
		var src = frame && frame.getAttribute('data-embed');
		if(!src) return;
		var iframe = document.createElement('iframe');
		iframe.src = src + (src.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1';
		iframe.setAttribute('allow','autoplay; encrypted-media; picture-in-picture');
		iframe.setAttribute('allowfullscreen','');
		iframe.setAttribute('frameborder','0');
		iframe.style.width = '100%';
		iframe.style.height = '100%';
		iframe.style.border = '0';
		frame.innerHTML = '';
		frame.appendChild(iframe);
	});
})();
</script>
<?php endif; ?>
