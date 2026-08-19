<?php
/**
 * Universal Section: Pairing Cards
 *
 * ACF flexible content layout: pairing_cards (field: content_sections)
 * Formerly a hardcoded fixed section on template-landing.php.
 *
 * Fields:
 *   heading (text, optional)
 *   cards (repeater: badge, tag, title, body, bullets(repeater: text))
 *
 * @package doubletap
 */

defined( 'ABSPATH' ) || exit;

$heading = get_sub_field( 'heading' );
$cards   = get_sub_field( 'cards' );

if ( ! $cards ) {
	return;
}
?>
<section class="pairing-cards">
	<div class="container">
		<?php if ( $heading ) : ?>
			<h2 class="section-heading section-heading--center"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<div class="pairing-cards__grid">
			<?php foreach ( $cards as $card ) :
				$badge   = $card['badge'] ?? '';
				$tag     = $card['tag'] ?? '';
				$title   = $card['title'] ?? '';
				$body    = $card['body'] ?? '';
				$bullets = $card['bullets'] ?? [];
			?>
				<article class="pairing-card fade-in">
					<?php if ( $badge ) : ?>
						<div class="pairing-card__badge"><?php echo esc_html( $badge ); ?></div>
					<?php endif; ?>
					<?php if ( $tag ) : ?>
						<div class="pairing-card__tag"><?php echo esc_html( $tag ); ?></div>
					<?php endif; ?>
					<?php if ( $title ) : ?>
						<h3 class="pairing-card__title"><?php echo esc_html( $title ); ?></h3>
					<?php endif; ?>
					<?php if ( $body ) : ?>
						<p class="pairing-card__body"><?php echo esc_html( $body ); ?></p>
					<?php endif; ?>
					<?php if ( $bullets ) : ?>
						<ul class="pairing-card__bullets">
							<?php foreach ( $bullets as $bullet ) : ?>
								<li><?php echo esc_html( $bullet['text'] ?? '' ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
