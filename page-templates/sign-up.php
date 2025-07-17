<?php
/*
Template Name: Sign Up
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;

if (is_user_logged_in()) {
    wp_redirect(home_url());
}

get_header();

$auth_settings = get_option('jobster_authentication_settings');
$terms =    isset($auth_settings['jobster_terms_field']) 
            ? $auth_settings['jobster_terms_field'] : '';
$disable_candidate =    isset($auth_settings['jobster_disable_candidate_field']) 
                        ? $auth_settings['jobster_disable_candidate_field'] : '';
$disable_company   =    isset($auth_settings['jobster_disable_company_field']) 
                        ? $auth_settings['jobster_disable_company_field'] : '';
$signup_img =   isset($auth_settings['jobster_signup_img_field']) 
                ? $auth_settings['jobster_signup_img_field'] 
                : '';

$show_reg_modal = true;
$show_reg_candidate = true;
$show_reg_company = true;

if ($disable_candidate == '1' && $disable_company == '1') {
    $show_reg_modal = false;
    $show_reg_candidate = false;
    $show_reg_company = false;
} else {
    if ($disable_candidate == '1') {
        $show_reg_candidate = false;
    }
    if ($disable_company == '1') {
        $show_reg_company = false;
    }
}

$signin_url = jobster_get_page_link('sign-in.php'); ?>

<section 
    class="pxp-hero vh-100" 
    style="background-color: var(--pxpMainColorLight);"
>
    <div class="row align-items-center pxp-sign-hero-container">
        <div class="col-xl-6 pxp-column">
            <div class="pxp-sign-hero-fig text-center pb-100 pt-100">
                <?php if (!empty($signup_img)) {
                    $signup_fig = wp_get_attachment_image_src($signup_img, 'full');
                    if (is_array($signup_fig)) { ?>
                        <img 
                            src="<?php echo esc_url($signup_fig[0]); ?>" 
                            alt="<?php esc_html_e('Sign up', 'jobster'); ?>"
                        >
                    <?php } else { ?>
                        <img 
                            src="<?php echo esc_url(JOBSTER_LOCATION . '/images/signup-fig.png'); ?>" 
                            alt="<?php esc_html_e('Sign up', 'jobster'); ?>"
                        >
                    <?php }
                } else { ?>
                    <img 
                        src="<?php echo esc_url(JOBSTER_LOCATION . '/images/signup-fig.png'); ?>" 
                        alt="<?php esc_html_e('Sign up', 'jobster'); ?>"
                    >
                <?php }
                if ($show_reg_candidate === true && $show_reg_company === true) { ?>
                    <h1 class="mt-4 mt-lg-5">
                        <?php esc_html_e('Create an account', 'jobster'); ?>
                    </h1>
                <?php } else {
                    if ($show_reg_candidate === true) { ?>
                        <h1 class="mt-4 mt-lg-5">
                            <?php esc_html_e('Create an account', 'jobster'); ?>
                        </h1>
                        <input type="hidden" id="pxp-is-candidate-page-reg">
                    <?php }
                    if ($show_reg_company === true) { ?>
                        <h1 class="mt-4 mt-lg-5">
                            <?php esc_html_e('Create an account', 'jobster'); ?>
                        </h1>
                        <input type="hidden" id="pxp-is-company-page-reg">
                    <?php }
                } ?>
            </div>
        </div>
        <div class="col-xl-6 pxp-column pxp-is-light">
            <div class="pxp-sign-hero-form pb-100 pt-100">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-xl-7 col-xxl-6">
                        <div class="pxp-sign-hero-form-content pxp-auth-page">
                            <h5 class="text-center">
                                <?php esc_html_e('Sign Up', 'jobster'); ?>
                            </h5>
                            <form class="mt-4">
                                <div class="pxp-modal-message pxp-signup-page-message"></div>

                                <?php if ($show_reg_candidate === true && $show_reg_company === true) { ?>
                                    <div class="text-center">
                                        <div 
                                            class="btn-group pxp-option-switcher" 
                                            role="group" 
                                            aria-label="<?php esc_attr_e('Account types switcher', 'jobster'); ?>"
                                        >
                                            <input 
                                                type="radio" 
                                                class="btn-check" 
                                                name="pxp-signup-page-type-switcher" 
                                                id="pxp-signup-page-type-candidate" 
                                                data-type="candidate" 
                                                checked
                                            >
                                            <label 
                                                class="btn btn-outline-primary" 
                                                for="pxp-signup-page-type-candidate"
                                            >
                                                <?php esc_html_e('I am candidate', 'jobster'); ?>
                                            </label>

                                            <input 
                                                type="radio" 
                                                class="btn-check" 
                                                name="pxp-signup-page-type-switcher" 
                                                id="pxp-signup-page-type-company" 
                                                data-type="company"
                                            >
                                            <label 
                                                class="btn btn-outline-primary" 
                                                for="pxp-signup-page-type-company"
                                            >
                                                <?php esc_html_e('I am company', 'jobster'); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="mt-4 pxp-signup-page-candidate-fields">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-floating mb-3">
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    id="pxp-signup-page-firstname" 
                                                    placeholder="<?php esc_html_e('First name', 'jobster'); ?>"
                                                >
                                                <label for="pxp-signup-page-firstname">
                                                    <?php esc_html_e('First name', 'jobster'); ?>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-floating mb-3">
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    id="pxp-signup-page-lastname" 
                                                    placeholder="<?php esc_html_e('Last name', 'jobster'); ?>"
                                                >
                                                <label for="pxp-signup-page-lastname">
                                                    <?php esc_html_e('Last name', 'jobster'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 pxp-signup-page-company-fields">
                                    <div class="form-floating mb-3">
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="pxp-signup-page-company-name" 
                                            placeholder="<?php esc_html_e('Company name', 'jobster'); ?>"
                                        >
                                        <label for="pxp-signup-page-company-name">
                                            <?php esc_html_e('Company name', 'jobster'); ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="pxp-signup-page-email" 
                                        placeholder="<?php esc_html_e('Email address', 'jobster'); ?>"
                                    >
                                    <label for="pxp-signup-page-email">
                                        <?php esc_html_e('Email address', 'jobster'); ?>
                                    </label>
                                    <span class="fa fa-envelope-o"></span>
                                </div>
                                <div class="form-floating mb-3">
                                    <input 
                                        type="password" 
                                        class="form-control pxp-password-control" 
                                        id="pxp-signup-page-password" 
                                        placeholder="<?php esc_html_e('Create password', 'jobster'); ?>"
                                    >
                                    <label for="pxp-signup-page-password">
                                        <?php esc_html_e('Create password', 'jobster'); ?>
                                    </label>
                                    <span class="fa fa-eye pxp-password-toggle"></span>
                                </div>

                                <?php if ($terms != '') { ?>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                value="" 
                                                id="pxp-signup-page-terms"
                                            >
                                            <label 
                                                class="form-check-label" 
                                                for="pxp-signup-page-terms"
                                            >
                                                <?php printf(__('I agree with <a href="%s" class="pxp-modal-link" target="_blank">Terms and Conditions</a>', 'jobster'), get_permalink($terms)); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php } ?>

                                <input 
                                    type="hidden" 
                                    name="pxp-signup-page-redirect" 
                                    id="pxp-signup-page-redirect" 
                                    value="<?php echo esc_url($signin_url); ?>"
                                >
                                <?php wp_nonce_field(
                                    'signin_ajax_nonce', 
                                    'pxp-signup-page-security', 
                                    true); 
                                ?>
                                <a 
                                    href="javascript:void(0);" 
                                    class="btn rounded-pill pxp-modal-cta pxp-signup-page-btn"
                                >
                                    <span class="pxp-signup-page-btn-text">
                                        <?php esc_html_e('Continue', 'jobster'); ?>
                                    </span>
                                    <span class="pxp-signup-page-btn-loading pxp-btn-loading">
                                        <img 
                                            src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                            class="pxp-btn-loader" 
                                            alt="..."
                                        >
                                    </span>
                                </a>

                                <div class="mt-4 text-center pxp-modal-small">
                                    <?php esc_html_e('Already have an account?', 'jobster'); ?> <a href="<?php echo esc_url($signin_url); ?>"><?php esc_html_e('Sign in', 'jobster'); ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>