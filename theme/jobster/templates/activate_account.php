<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;

if (is_user_logged_in()) {
    wp_redirect(home_url());
}

get_header();

$message = '';
if (function_exists('jobster_activate_user_account')) {
    $message = jobster_activate_user_account();
} ?>

<section class="mt-100 pxp-no-hero">
    <div class="pxp-container">
        <h2 class="pxp-section-h2 text-center">
            <?php esc_html_e('Account Activation', 'jobster'); ?>
        </h2>
        <p class="pxp-text-light text-center">
            <?php echo esc_html($message); ?>
        </p>

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