<?php
/*
Template Name: Sign In
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
$signin_img =   isset($auth_settings['jobster_signin_img_field']) 
                ? $auth_settings['jobster_signin_img_field']
                : '';
$google_auth =  isset($auth_settings['jobster_google_auth_field']) 
                && $auth_settings['jobster_google_auth_field'] == '1';
$google_auth_client_id =    isset($auth_settings['jobster_google_auth_client_id_field']) 
                            ? $auth_settings['jobster_google_auth_client_id_field'] : '';
$fb_auth =  isset($auth_settings['jobster_fb_auth_field']) 
            && $auth_settings['jobster_fb_auth_field'] == '1';
$fb_auth_app_id =   isset($auth_settings['jobster_fb_auth_app_id_field']) 
                    ? $auth_settings['jobster_fb_auth_app_id_field'] : '';

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

$reg_url = jobster_get_page_link('sign-up.php'); ?>

<section 
    class="pxp-hero vh-100 pxp-signin-page" 
    style="background-color: var(--pxpMainColorLight);"
>
    <div class="row align-items-center pxp-sign-hero-container">
        <div class="col-xl-6 pxp-column">
            <div class="pxp-sign-hero-fig text-center pb-100 pt-100">
                <?php if (!empty($signin_img)) {
                    $signin_fig = wp_get_attachment_image_src($signin_img, 'full');
                    if (is_array($signin_fig)) { ?>
                        <img 
                            src="<?php echo esc_url($signin_fig[0]); ?>" 
                            alt="<?php esc_html_e('Sign in', 'jobster'); ?>"
                        >
                    <?php } else { ?>
                        <img 
                            src="<?php echo esc_url(JOBSTER_LOCATION . '/images/signin-fig.png'); ?>" 
                            alt="<?php esc_html_e('Sign in', 'jobster'); ?>"
                        >
                    <?php }
                } else { ?>
                    <img 
                        src="<?php echo esc_url(JOBSTER_LOCATION . '/images/signin-fig.png'); ?>" 
                        alt="<?php esc_html_e('Sign in', 'jobster'); ?>"
                    >
                <?php } ?>
                <h1 class="mt-4 mt-lg-5">
                    <?php esc_html_e('Welcome back!', 'jobster'); ?>
                </h1>
            </div>
        </div>
        <div class="col-xl-6 pxp-column pxp-is-light">
            <div class="pxp-sign-hero-form pb-100 pt-100">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-xl-7 col-xxl-6">
                        <div class="pxp-sign-hero-form-content pxp-auth-page">
                            <h5 class="text-center">
                                <?php esc_html_e('Sign In', 'jobster'); ?>
                            </h5>

                            <?php if ($google_auth && !empty($google_auth_client_id)) { ?>
                                <div class="mt-4">
                                    <div id="g_id_onload"
                                        data-client_id="<?php echo esc_attr($google_auth_client_id); ?>"
                                        data-context="signin"
                                        data-ux_mode="popup"
                                        data-callback="handleGoogleAuthResponse"
                                        data-auto_prompt="false">
                                    </div>
                                    <div class="g_id_signin"
                                        data-type="standard"
                                        data-shape="pill"
                                        data-theme="outline"
                                        data-text="continue_with"
                                        data-size="large"
                                        data-logo_alignment="left">
                                    </div>
                                </div>
                            <?php }

                            if ($fb_auth && !empty($fb_auth_app_id)) { ?>
                                <script>
                                    window.fbAsyncInit = function() {
                                        FB.init({
                                            appId  : '<?php echo esc_html($fb_auth_app_id); ?>',
                                            status : true,
                                            cookie : true,
                                            xfbml  : true,
                                            version: 'v17.0'
                                        });
                                    };
                                    (function(d, s, id) {
                                        var js, fjs = d.getElementsByTagName(s)[0];
                                        if (d.getElementById(id)) return;
                                        js = d.createElement(s); js.id = id;
                                        js.src = "//connect.facebook.net/en_US/sdk.js";
                                        fjs.parentNode.insertBefore(js, fjs);
                                    }(document, 'script', 'facebook-jssdk'));
                                </script>

                                <div class="mt-3">
                                    <a href="javascript:void(0);" class="btn rounded-pill pxp-fb-signin-btn">
                                        <div class="pxp-fb-signin-btn-wrapper">
                                            <span class="fa fa-facebook"></span>
                                            <span class="pxp-fb-signin-btn-text"><?php esc_html_e('Continue with Facebook', 'jobster'); ?></span>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>

                            <form class="mt-4">
                                <div class="pxp-modal-message pxp-signin-page-message"></div>

                                <div class="form-floating mb-3">
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="pxp-signin-page-email" 
                                        placeholder="<?php esc_html_e('Email address', 'jobster'); ?>"
                                    >
                                    <label for="pxp-signin-page-email">
                                        <?php esc_html_e('Email address', 'jobster'); ?>
                                    </label>
                                    <span class="fa fa-envelope-o"></span>
                                </div>
                                <div class="form-floating mb-3">
                                    <input 
                                        type="password" 
                                        class="form-control pxp-password-control" 
                                        id="pxp-signin-page-password" 
                                        placeholder="<?php esc_html_e('Password', 'jobster'); ?>"
                                    >
                                    <label for="pxp-signin-page-password">
                                        <?php esc_html_e('Password', 'jobster'); ?>
                                    </label>
                                    <span class="fa fa-eye pxp-password-toggle"></span>
                                </div>

                                <input 
                                    type="hidden" 
                                    name="pxp-signin-page-redirect" 
                                    id="pxp-signin-page-redirect" 
                                    value="<?php echo esc_url(home_url()); ?>"
                                >
                                <?php wp_nonce_field(
                                    'signin_ajax_nonce',
                                    'pxp-signin-page-security',
                                    true
                                ); ?>

                                <a 
                                    href="javascript:void(0);" 
                                    class="btn rounded-pill pxp-modal-cta pxp-signin-page-btn"
                                >
                                    <span class="pxp-signin-page-btn-text">
                                        <?php esc_html_e('Continue', 'jobster'); ?>
                                    </span>
                                    <span class="pxp-signin-page-btn-loading pxp-btn-loading">
                                        <img 
                                            src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                            class="pxp-btn-loader" 
                                            alt="..."
                                        >
                                    </span>
                                </a>
                                <div class="mt-4 text-center pxp-modal-small">
                                    <a 
                                        role="button" 
                                        class="pxp-modal-link" 
                                        data-bs-target="#pxp-forgot-modal" 
                                        data-bs-toggle="modal" 
                                        data-bs-dismiss="modal"
                                    >
                                        <?php esc_html_e('Forgot password', 'jobster'); ?>
                                    </a>
                                </div>

                                <?php if ($show_reg_candidate === true && $show_reg_company === true) { ?>
                                    <div class="mt-4 text-center pxp-modal-small">
                                        <?php esc_html_e('New to', 'jobster'); ?> <?php print esc_html(get_bloginfo('name')); ?>? <a href="<?php echo esc_url($reg_url); ?>"><?php esc_html_e('Create an account', 'jobster'); ?></a>
                                    </div>
                                <?php } else {
                                    if ($show_reg_candidate === true) { ?>
                                        <div class="mt-4 text-center pxp-modal-small">
                                            <?php esc_html_e('New to', 'jobster'); ?> <?php print esc_html(get_bloginfo('name')); ?>? <a href="<?php echo esc_url($reg_url); ?>"><?php esc_html_e('Create candidate account', 'jobster'); ?></a>
                                        </div>
                                    <?php }
                                    if ($show_reg_company === true) { ?>
                                        <div class="mt-4 text-center pxp-modal-small">
                                            <?php esc_html_e('New to', 'jobster'); ?> <?php print esc_html(get_bloginfo('name')); ?>? <a href="<?php echo esc_url($reg_url); ?>"><?php esc_html_e('Create company account', 'jobster'); ?></a>
                                        </div>
                                    <?php }
                                } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>