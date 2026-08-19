<?php
/**
 * Template Name: DT Landing (Firearms, Knives, Pistols, Rifles, Scopes, Tactical)
 *
 * Content is managed via the "DT Landing Page" Advanced Custom Fields group.
 * Styles live in the theme's style.css under the `.dt-fk` namespace.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

// Hero title accent rendering ({word} => accented span) now lives in
// functions.php as doubletap_render_accent_heading(), shared with the
// universal hero.php section partial.

// ACF helpers — use get_field with fallbacks so the template still works if ACF is disabled
$hero_eyebrow      = function_exists( 'get_field' ) ? get_field( 'hero_eyebrow' )      : '';
$hero_title        = function_exists( 'get_field' ) ? get_field( 'hero_title' )        : get_the_title();
$hero_subtitle     = function_exists( 'get_field' ) ? get_field( 'hero_subtitle' )     : '';
$hero_image        = function_exists( 'get_field' ) ? get_field( 'hero_image' )        : null;
$cta1_label        = function_exists( 'get_field' ) ? get_field( 'hero_cta_primary_label' )   : 'Shop Now';
$cta1_url          = function_exists( 'get_field' ) ? get_field( 'hero_cta_primary_url' )     : '/shop/';
$cta2_label        = function_exists( 'get_field' ) ? get_field( 'hero_cta_secondary_label' ) : 'Learn More';
$cta2_url          = function_exists( 'get_field' ) ? get_field( 'hero_cta_secondary_url' )   : '/information/';

$warning_show      = function_exists( 'get_field' ) ? get_field( 'warning_show' )      : true;
$warning_title     = function_exists( 'get_field' ) ? get_field( 'warning_title' )     : 'Safety First';
$warning_text      = function_exists( 'get_field' ) ? get_field( 'warning_text' )      : '';

$intro_heading     = function_exists( 'get_field' ) ? get_field( 'intro_heading' )     : '';
$intro_body        = function_exists( 'get_field' ) ? get_field( 'intro_body' )        : '';
$intro_image       = function_exists( 'get_field' ) ? get_field( 'intro_image' )       : null;

$pairing_heading   = function_exists( 'get_field' ) ? get_field( 'pairing_heading' )   : '';
$steps_heading     = function_exists( 'get_field' ) ? get_field( 'steps_heading' )     : '';

$video_show        = function_exists( 'get_field' ) ? get_field( 'video_show' )        : false;
$video_heading     = function_exists( 'get_field' ) ? get_field( 'video_heading' )     : '';
$video_provider    = function_exists( 'get_field' ) ? get_field( 'video_provider' )    : 'youtube';
$video_id          = function_exists( 'get_field' ) ? get_field( 'video_id' )          : '';
$video_poster      = function_exists( 'get_field' ) ? get_field( 'video_poster' )      : null;

$compat_heading    = function_exists( 'get_field' ) ? get_field( 'compat_heading' )    : '';
$testi_heading     = function_exists( 'get_field' ) ? get_field( 'testimonials_heading' ) : '';

$cta_show          = function_exists( 'get_field' ) ? get_field( 'cta_show' )          : true;
$cta_title         = function_exists( 'get_field' ) ? get_field( 'cta_title' )         : '';
$cta_subtitle      = function_exists( 'get_field' ) ? get_field( 'cta_subtitle' )      : '';
$cta_button_label  = function_exists( 'get_field' ) ? get_field( 'cta_button_label' )  : 'Shop Now';
$cta_button_url    = function_exists( 'get_field' ) ? get_field( 'cta_button_url' )    : '/shop/';

$hero_src = is_array( $hero_image ) && ! empty( $hero_image['url'] ) ? $hero_image['url'] : '';
$hero_alt = is_array( $hero_image ) && ! empty( $hero_image['alt'] ) ? $hero_image['alt'] : get_the_title();

$intro_src = is_array( $intro_image ) && ! empty( $intro_image['url'] ) ? $intro_image['url'] : '';
$intro_alt = is_array( $intro_image ) && ! empty( $intro_image['alt'] ) ? $intro_image['alt'] : '';
?>
<?php
$doubletap_has_universal_sections = function_exists( 'have_rows' ) && have_rows( 'content_sections' );
?>
<?php if ( $doubletap_has_universal_sections ) : ?>

	<main id="primary">
		<?php
		// New universal, reusable ACF flexible content - preferred going
		// forward. Lets any section on this landing page (hero, warning strip,
		// text+image, pairing cards, etc.) be added, removed, or reordered
		// instead of being locked into this template's fixed field layout.
		doubletap_render_flexible_sections( get_the_ID(), 'content_sections' );
		?>
	</main>

<?php else : ?>

<main class="dt-fk" id="primary">

    <!-- HERO -->
    <section class="dt-fk__hero"
        <?php if ( $hero_src ) : ?> style="background-image: linear-gradient(180deg, rgba(13,13,13,0.35) 0%, rgba(13,13,13,0.85) 100%), url('<?php echo esc_url( $hero_src ); ?>');"<?php endif; ?>>
        <div class="dt-fk__wrap">
            <?php if ( $hero_eyebrow ) : ?>
                <div class="dt-fk__eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></div>
            <?php endif; ?>
            <h1 class="dt-fk__hero-title">
                <?php echo doubletap_render_accent_heading( $hero_title, 'dt-fk__accent' ); ?>
            </h1>
            <?php if ( $hero_subtitle ) : ?>
                <p class="dt-fk__hero-sub"><?php echo esc_html( $hero_subtitle ); ?></p>
            <?php endif; ?>
            <?php if ( $cta1_label || $cta2_label ) : ?>
            <div class="dt-fk__hero-ctas">
                <?php if ( $cta1_label ) : ?>
                    <a class="dt-fk__btn dt-fk__btn--primary" href="<?php echo esc_url( $cta1_url ); ?>">
                        <?php echo esc_html( $cta1_label ); ?>
                    </a>
                <?php endif; ?>
                <?php if ( $cta2_label ) : ?>
                    <a class="dt-fk__btn dt-fk__btn--ghost" href="<?php echo esc_url( $cta2_url ); ?>">
                        <?php echo esc_html( $cta2_label ); ?>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- WARNING STRIP -->
    <?php if ( $warning_show && ( $warning_title || $warning_text ) ) : ?>
    <section class="dt-fk__warning">
        <div class="dt-fk__wrap dt-fk__warning-inner">
            <?php if ( $warning_title ) : ?>
                <strong class="dt-fk__warning-title"><?php echo esc_html( $warning_title ); ?></strong>
            <?php endif; ?>
            <?php if ( $warning_text ) : ?>
                <span class="dt-fk__warning-text"><?php echo esc_html( $warning_text ); ?></span>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- INTRO -->
    <?php if ( $intro_heading || $intro_body || $intro_src ) : ?>
    <section class="dt-fk__intro">
        <div class="dt-fk__wrap dt-fk__intro-grid">
            <div class="dt-fk__intro-copy">
                <?php if ( $intro_heading ) : ?>
                    <h2 class="dt-fk__h2"><?php echo esc_html( $intro_heading ); ?></h2>
                <?php endif; ?>
                <?php if ( $intro_body ) : ?>
                    <div class="dt-fk__rich"><?php echo wp_kses_post( $intro_body ); ?></div>
                <?php endif; ?>
            </div>
            <?php if ( $intro_src ) : ?>
                <div class="dt-fk__intro-media">
                    <img src="<?php echo esc_url( $intro_src ); ?>" alt="<?php echo esc_attr( $intro_alt ); ?>" loading="lazy" />
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- PAIRING CARDS -->
    <?php if ( function_exists('have_rows') && have_rows('pairing_cards') ) : ?>
    <section class="dt-fk__pairing">
        <div class="dt-fk__wrap">
            <?php if ( $pairing_heading ) : ?>
                <h2 class="dt-fk__h2 dt-fk__h2--center"><?php echo esc_html( $pairing_heading ); ?></h2>
            <?php endif; ?>
            <div class="dt-fk__pairing-grid">
                <?php while ( have_rows('pairing_cards') ) : the_row();
                    $badge = get_sub_field('badge');
                    $title = get_sub_field('title');
                    $tag   = get_sub_field('tag');
                    $body  = get_sub_field('body');
                ?>
                <article class="dt-fk__card">
                    <?php if ( $badge ) : ?>
                        <div class="dt-fk__badge"><?php echo esc_html( $badge ); ?></div>
                    <?php endif; ?>
                    <?php if ( $tag ) : ?>
                        <div class="dt-fk__card-tag"><?php echo esc_html( $tag ); ?></div>
                    <?php endif; ?>
                    <?php if ( $title ) : ?>
                        <h3 class="dt-fk__card-title"><?php echo esc_html( $title ); ?></h3>
                    <?php endif; ?>
                    <?php if ( $body ) : ?>
                        <p class="dt-fk__card-body"><?php echo esc_html( $body ); ?></p>
                    <?php endif; ?>
                    <?php if ( have_rows('bullets') ) : ?>
                        <ul class="dt-fk__card-bullets">
                            <?php while ( have_rows('bullets') ) : the_row(); ?>
                                <li><?php echo esc_html( get_sub_field('text') ); ?></li>
                            <?php endwhile; ?>
                        </ul>
                    <?php endif; ?>
                </article>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- APPLICATION STEPS -->
    <?php if ( function_exists('have_rows') && have_rows('steps') ) : ?>
    <section class="dt-fk__steps">
        <div class="dt-fk__wrap">
            <?php if ( $steps_heading ) : ?>
                <h2 class="dt-fk__h2 dt-fk__h2--center"><?php echo esc_html( $steps_heading ); ?></h2>
            <?php endif; ?>
            <div class="dt-fk__steps-grid">
                <?php while ( have_rows('steps') ) : the_row(); ?>
                <div class="dt-fk__step">
                    <div class="dt-fk__step-num"><?php echo esc_html( get_sub_field('number') ); ?></div>
                    <h3 class="dt-fk__step-title"><?php echo esc_html( get_sub_field('title') ); ?></h3>
                    <p class="dt-fk__step-body"><?php echo esc_html( get_sub_field('body') ); ?></p>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- VIDEO SHOWCASE -->
    <?php if ( $video_show && $video_id ) :
        $vid = rawurlencode( $video_id );
        $embed = '';
        if ( $video_provider === 'youtube' ) {
            $embed = 'https://www.youtube-nocookie.com/embed/' . $vid . '?rel=0&modestbranding=1&playsinline=1';
        } elseif ( $video_provider === 'vimeo' ) {
            $embed = 'https://player.vimeo.com/video/' . $vid . '?title=0&byline=0&portrait=0';
        }
        $poster_src = is_array( $video_poster ) && ! empty( $video_poster['url'] ) ? $video_poster['url'] : '';
    ?>
    <section class="dt-fk__video">
        <div class="dt-fk__wrap">
            <?php if ( $video_heading ) : ?>
                <h2 class="dt-fk__h2 dt-fk__h2--center"><?php echo esc_html( $video_heading ); ?></h2>
            <?php endif; ?>
            <div class="dt-fk__video-frame"
                <?php if ( $poster_src ) : ?> style="background-image:url('<?php echo esc_url( $poster_src ); ?>');"<?php endif; ?>
                data-embed="<?php echo esc_url( $embed ); ?>">
                <button type="button" class="dt-fk__video-play" aria-label="Play video">
                    <svg viewBox="0 0 64 64" aria-hidden="true"><polygon points="24,16 52,32 24,48" fill="currentColor"/></svg>
                </button>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- COMPATIBILITY -->
    <?php if ( function_exists('have_rows') && have_rows('compat_items') ) : ?>
    <section class="dt-fk__compat">
        <div class="dt-fk__wrap">
            <?php if ( $compat_heading ) : ?>
                <h2 class="dt-fk__h2 dt-fk__h2--center"><?php echo esc_html( $compat_heading ); ?></h2>
            <?php endif; ?>
            <div class="dt-fk__compat-grid">
                <?php while ( have_rows('compat_items') ) : the_row();
                    $label = get_sub_field('label');
                    $icon  = get_sub_field('icon');
                    $icon_src = is_array( $icon ) && ! empty( $icon['url'] ) ? $icon['url'] : '';
                ?>
                <div class="dt-fk__compat-item">
                    <?php if ( $icon_src ) : ?>
                        <img class="dt-fk__compat-icon" src="<?php echo esc_url( $icon_src ); ?>" alt="<?php echo esc_attr( $label ); ?>" loading="lazy" />
                    <?php endif; ?>
                    <span class="dt-fk__compat-label"><?php echo esc_html( $label ); ?></span>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
	
    <!-- TESTIMONIALS -->
    <?php if ( function_exists('have_rows') && have_rows('testimonials') ) : ?>
    <section class="dt-fk__testimonials">
        <div class="dt-fk__wrap">
            <?php if ( $testi_heading ) : ?>
                <h2 class="dt-fk__h2 dt-fk__h2--center"><?php echo esc_html( $testi_heading ); ?></h2>
            <?php endif; ?>
            <div class="dt-fk__testimonials-grid">
                <?php while ( have_rows('testimonials') ) : the_row(); ?>
                <figure class="dt-fk__testimonial">
                    <blockquote class="dt-fk__testimonial-quote">
                        &ldquo;<?php echo esc_html( get_sub_field('quote') ); ?>&rdquo;
                    </blockquote>
                    <figcaption class="dt-fk__testimonial-meta">
                        <span class="dt-fk__testimonial-name"><?php echo esc_html( get_sub_field('name') ); ?></span>
                        <span class="dt-fk__testimonial-role"><?php echo esc_html( get_sub_field('role') ); ?></span>
                    </figcaption>
                </figure>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA BANNER -->
    <?php if ( $cta_show && ( $cta_title || $cta_subtitle ) ) : ?>
    <section class="dt-fk__cta">
        <div class="dt-fk__wrap dt-fk__cta-inner">
            <?php if ( $cta_title ) : ?>
                <h2 class="dt-fk__cta-title"><?php echo esc_html( $cta_title ); ?></h2>
            <?php endif; ?>
            <?php if ( $cta_subtitle ) : ?>
                <p class="dt-fk__cta-sub"><?php echo esc_html( $cta_subtitle ); ?></p>
            <?php endif; ?>
            <?php if ( $cta_button_label ) : ?>
                <a class="dt-fk__btn dt-fk__btn--primary dt-fk__btn--lg" href="<?php echo esc_url( $cta_button_url ); ?>">
                    <?php echo esc_html( $cta_button_label ); ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<script>
// Click-to-play for video poster
(function(){
    var frames = document.querySelectorAll('.dt-fk__video-frame');
    frames.forEach(function(frame){
        var btn = frame.querySelector('.dt-fk__video-play');
        if(!btn) return;
        btn.addEventListener('click', function(){
            var src = frame.getAttribute('data-embed');
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
    });
})();
</script>

<?php endif; ?>

<?php get_footer();
