<?php
/**
 * Universal Section: Page Header
 *
 * ACF flexible content layout: page_header (field: content_sections)
 *
 * Fields:
 *   title    (text)
 *   subtitle (text)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$title    = get_sub_field( 'title' );
$subtitle = get_sub_field( 'subtitle' );

if ( ! $title && ! $subtitle ) {
	return;
}
?>
<section class="page-header">
	<div class="container">
		<?php if ( $title ) : ?>
			<h1 class="page-header__title"><?php echo esc_html( $title ); ?></h1>
		<?php endif; ?>
		<?php if ( $subtitle ) : ?>
			<p class="page-header__subtitle"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>
	</div>
</section>
