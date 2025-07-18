<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;
get_header(); 
?>

<section class="mt-100 pxp-no-hero">
    <div class="pxp-container">
        <h2 class="pxp-section-h2 text-center">
            <?php esc_html_e('This page is off the map', 'jobster'); ?>
        </h2>
        <p class="pxp-text-light text-center">
            <?php esc_html_e("We can't seem to find the page you're looking for.", 'jobster'); ?>
        </p>

        <div class="pxp-404-fig text-center mt-4 mt-lg-5">
            <img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/404.png'); ?>" alt="<?php esc_attr_e('Page not found', 'jobster'); ?>">
        </div>

        <div class="mt-4 mt-lg-5 text-center">
            <a 
                href="<?php echo esc_url(home_url('/')); ?>" 
                class="btn rounded-pill pxp-section-cta"
            >
                <?php esc_html_e('Go Home', 'jobster'); ?>
                <span class="fa fa-angle-right"></span>
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>