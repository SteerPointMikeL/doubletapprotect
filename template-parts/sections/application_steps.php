<?php
/**
 * Universal Section: Application Steps
 *
 * ACF flexible content layout: application_steps (field: content_sections)
 * Formerly a hardcoded fixed section on template-landing.php.
 *
 * Fields:
 *   heading (text, optional)
 *   steps (repeater: number, title, body)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$heading = get_sub_field( 'heading' );
$steps   = get_sub_field( 'steps' );

if ( ! $steps ) {
	return;
}
?>
<section class="application-steps">
	<div class="container">
		<?php if ( $heading ) : ?>
			<h2 class="section-heading section-heading--center"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<div class="application-steps__grid">
			<?php foreach ( $steps as $step ) : ?>
				<div class="application-step fade-in">
					<div class="application-step__num"><?php echo esc_html( $step['number'] ?? '' ); ?></div>
					<h3 class="application-step__title"><?php echo esc_html( $step['title'] ?? '' ); ?></h3>
					<p class="application-step__body"><?php echo esc_html( $step['body'] ?? '' ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
