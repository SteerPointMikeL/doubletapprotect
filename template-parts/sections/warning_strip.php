<?php
/**
 * Universal Section: Warning Strip
 *
 * ACF flexible content layout: warning_strip (field: content_sections)
 * Formerly a hardcoded fixed section on template-landing.php.
 *
 * Fields:
 *   title (text)
 *   text  (text)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$title = get_sub_field( 'title' );
$text  = get_sub_field( 'text' );

if ( ! $title && ! $text ) {
	return;
}
?>
<section class="warning-strip">
	<div class="container warning-strip__inner">
		<?php if ( $title ) : ?>
			<strong class="warning-strip__title"><?php echo esc_html( $title ); ?></strong>
		<?php endif; ?>
		<?php if ( $text ) : ?>
			<span class="warning-strip__text"><?php echo esc_html( $text ); ?></span>
		<?php endif; ?>
	</div>
</section>
