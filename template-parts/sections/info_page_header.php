<?php
/**
 * Info Section: Page Header
 * Layout: info_page_header
 *
 * Fields:
 *   page_title    (text)
 *   page_subtitle (text)
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$title    = get_sub_field( 'page_title' );
$subtitle = get_sub_field( 'page_subtitle' );
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
