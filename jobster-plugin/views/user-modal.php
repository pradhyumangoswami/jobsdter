<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_user_modal')):
    function jobster_get_user_modal() {
        $auth_settings         = get_option('jobster_authentication_settings');
        $terms                 = isset($auth_settings['jobster_terms_field']) ? $auth_settings['jobster_terms_field'] : '';
        $disable_candidate     = isset($auth_settings['jobster_disable_candidate_field']) ? $auth_settings['jobster_disable_candidate_field'] : '';
        $disable_company       = isset($auth_settings['jobster_disable_company_field']) ? $auth_settings['jobster_disable_company_field'] : '';
        $signin_img            = isset($auth_settings['jobster_signin_img_field']) ? $auth_settings['jobster_signin_img_field'] : '';
        $signup_img            = isset($auth_settings['jobster_signup_img_field']) ? $auth_settings['jobster_signup_img_field'] : '';
        $forgot_pass_img       = isset($auth_settings['jobster_forgot_pass_img_field']) ? $auth_settings['jobster_forgot_pass_img_field'] : '';
        $account_type_img      = isset($auth_settings['jobster_account_type_img_field']) ? $auth_settings['jobster_account_type_img_field'] : '';
        $google_auth           = isset($auth_settings['jobster_google_auth_field']) && $auth_settings['jobster_google_auth_field'] == '1';
        $google_auth_client_id = isset($auth_settings['jobster_google_auth_client_id_field']) ? $auth_settings['jobster_google_auth_client_id_field'] : '';
        $fb_auth               = isset($auth_settings['jobster_fb_auth_field']) && $auth_settings['jobster_fb_auth_field'] == '1';
        $fb_auth_app_id        = isset($auth_settings['jobster_fb_auth_app_id_field']) ? $auth_settings['jobster_fb_auth_app_id_field'] : '';

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
        } ?>

        <div>
            <div class="modal fade pxp-user-modal" id="pxp-signin-modal" aria-hidden="true" aria-labelledby="signinModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_html_e('Close', 'jobster'); ?>"></button>
                        </div>
                        <div class="modal-body">
                            <div class="pxp-user-modal-fig text-center">
                                <?php if (!empty($signin_img)) {
                                    $signin_fig = wp_get_attachment_image_src($signin_img, 'full');
                                    if (is_array($signin_fig)) { ?>
                                        <img src="<?php echo esc_url($signin_fig[0]); ?>" alt="<?php esc_html_e('Sign in', 'jobster'); ?>">
                                    <?php } else { ?>
                                        <img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/signin-fig.png'); ?>" alt="<?php esc_html_e('Sign in', 'jobster'); ?>">
                                    <?php }
                                } else { ?>
                                    <img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/signin-fig.png'); ?>" alt="<?php esc_html_e('Sign in', 'jobster'); ?>">
                                <?php } ?>
                            </div>
                            <h5 class="modal-title text-center mt-4" id="signinModal"><?php esc_html_e('Welcome back!', 'jobster'); ?></h5>

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
                                <div class="pxp-modal-message pxp-signin-modal-message"></div>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="pxp-signin-modal-email" placeholder="<?php esc_html_e('Email address', 'jobster'); ?>">
                                    <label for="pxp-signin-modal-email"><?php esc_html_e('Email address', 'jobster'); ?></label>
                                    <span class="fa fa-envelope-o"></span>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control pxp-password-control" id="pxp-signin-modal-password" placeholder="<?php esc_html_e('Password', 'jobster'); ?>">
                                    <label for="pxp-signin-modal-password"><?php esc_html_e('Password', 'jobster'); ?></label>
                                    <span class="fa fa-eye pxp-password-toggle"></span>
                                </div>

                                <input type="hidden" name="pxp-signin-modal-redirect" id="pxp-signin-modal-redirect" value="">
                                <?php wp_nonce_field('signin_ajax_nonce', 'pxp-signin-modal-security', true); ?>

                                <a href="javascript:void(0);" class="btn rounded-pill pxp-modal-cta pxp-signin-modal-btn">
                                    <?php if ($google_auth && !empty($google_auth_client_id)) { ?>
                                        <span class="pxp-signin-modal-btn-text"><?php esc_html_e('Continue with email', 'jobster'); ?></span>
                                    <?php } else { ?>
                                        <span class="pxp-signin-modal-btn-text"><?php esc_html_e('Continue', 'jobster'); ?></span>
                                    <?php } ?>
                                    <span class="pxp-signin-modal-btn-loading pxp-btn-loading"><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-btn-loader" alt="..."></span>
                                </a>
                                <div class="mt-4 text-center pxp-modal-small">
                                    <a role="button" class="pxp-modal-link" data-bs-target="#pxp-forgot-modal" data-bs-toggle="modal" data-bs-dismiss="modal"><?php esc_html_e('Forgot password', 'jobster'); ?></a>
                                </div>
                                <?php if ($show_reg_candidate === true && $show_reg_company === true) { ?>
                                    <div class="mt-4 text-center pxp-modal-small">
                                        <?php esc_html_e('New to', 'jobster'); ?> <?php print esc_html(get_bloginfo('name')); ?>? <a role="button" class="" data-bs-target="#pxp-signup-modal" data-bs-toggle="modal" data-bs-dismiss="modal"><?php esc_html_e('Create an account', 'jobster'); ?></a>
                                    </div>
                                <?php } else {
                                    if ($show_reg_candidate === true) { ?>
                                        <div class="mt-4 text-center pxp-modal-small">
                                            <?php esc_html_e('New to', 'jobster'); ?> <?php print esc_html(get_bloginfo('name')); ?>? <a role="button" class="pxp-candidate-reg-trigger" data-bs-target="#pxp-signup-modal" data-bs-toggle="modal" data-bs-dismiss="modal"><?php esc_html_e('Create candidate account', 'jobster'); ?></a>
                                        </div>
                                    <?php }
                                    if ($show_reg_company === true) { ?>
                                        <div class="mt-4 text-center pxp-modal-small">
                                            <?php esc_html_e('New to', 'jobster'); ?> <?php print esc_html(get_bloginfo('name')); ?>? <a role="button" class="pxp-company-reg-trigger" data-bs-target="#pxp-signup-modal" data-bs-toggle="modal" data-bs-dismiss="modal"><?php esc_html_e('Create company account', 'jobster'); ?></a>
                                        </div>
                                    <?php }
                                } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($show_reg_modal === true) { ?>
                <div class="modal fade pxp-user-modal" id="pxp-signup-modal" aria-hidden="true" aria-labelledby="signupModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_html_e('Close', 'jobster'); ?>"></button>
                            </div>
                            <div class="modal-body">
                                <div class="pxp-user-modal-fig text-center">
                                    <?php if (!empty($signup_img)) {
                                        $signup_fig = wp_get_attachment_image_src($signup_img, 'full');
                                        if (is_array($signup_fig)) { ?>
                                            <img src="<?php echo esc_url($signup_fig[0]); ?>" alt="<?php esc_html_e('Sign up', 'jobster'); ?>">
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/signup-fig.png'); ?>" alt="<?php esc_html_e('Sign up', 'jobster'); ?>">
                                        <?php }
                                    } else { ?>
                                        <img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/signup-fig.png'); ?>" alt="<?php esc_html_e('Sign up', 'jobster'); ?>">
                                    <?php } ?>
                                </div>

                                <?php if ($show_reg_candidate === true && $show_reg_company === true) { ?>
                                    <h5 class="modal-title text-center mt-4" id="signupModal"><?php esc_html_e('Create an account', 'jobster'); ?></h5>
                                <?php } else {
                                    if ($show_reg_candidate === true) { ?>
                                        <h5 class="modal-title text-center mt-4" id="signupModal"><?php esc_html_e('Create candidate account', 'jobster'); ?></h5>
                                        <input type="hidden" id="pxp-is-candidate-reg">
                                    <?php }
                                    if ($show_reg_company === true) { ?>
                                        <h5 class="modal-title text-center mt-4" id="signupModal"><?php esc_html_e('Create company account', 'jobster'); ?></h5>
                                        <input type="hidden" id="pxp-is-company-reg">
                                    <?php }
                                } ?>

                                <form class="mt-4">
                                    <div class="pxp-modal-message pxp-signup-modal-message"></div>

                                    <?php if ($show_reg_candidate === true && $show_reg_company === true) { ?>
                                        <div class="text-center">
                                            <div class="btn-group pxp-option-switcher" role="group" aria-label="<?php esc_attr_e('Account types switcher', 'jobster'); ?>">
                                                <input type="radio" class="btn-check" name="pxp-signup-modal-type-switcher" id="pxp-signup-modal-type-candidate" data-type="candidate" checked>
                                                <label class="btn btn-outline-primary" for="pxp-signup-modal-type-candidate"><?php esc_html_e('I am candidate', 'jobster'); ?></label>
                
                                                <input type="radio" class="btn-check" name="pxp-signup-modal-type-switcher" id="pxp-signup-modal-type-company" data-type="company">
                                                <label class="btn btn-outline-primary" for="pxp-signup-modal-type-company"><?php esc_html_e('I am company', 'jobster'); ?></label>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="mt-4 pxp-signup-modal-candidate-fields">
                                        <div class="row">
                                            <div class="col-12 col-sm-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="pxp-signup-modal-firstname" placeholder="<?php esc_html_e('First name', 'jobster'); ?>">
                                                    <label for="pxp-signup-modal-firstname"><?php esc_html_e('First name', 'jobster'); ?></label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="pxp-signup-modal-lastname" placeholder="<?php esc_html_e('Last name', 'jobster'); ?>">
                                                    <label for="pxp-signup-modal-lastname"><?php esc_html_e('Last name', 'jobster'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pxp-signup-modal-company-fields">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="pxp-signup-modal-company-name" placeholder="<?php esc_html_e('Company name', 'jobster'); ?>">
                                            <label for="pxp-signup-modal-company-name"><?php esc_html_e('Company name', 'jobster'); ?></label>
                                        </div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="pxp-signup-modal-email" placeholder="<?php esc_html_e('Email address', 'jobster'); ?>">
                                        <label for="pxp-signup-modal-email"><?php esc_html_e('Email address', 'jobster'); ?></label>
                                        <span class="fa fa-envelope-o"></span>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control pxp-password-control" id="pxp-signup-modal-password" placeholder="<?php esc_html_e('Create password', 'jobster'); ?>">
                                        <label for="pxp-signup-modal-password"><?php esc_html_e('Create password', 'jobster'); ?></label>
                                        <span class="fa fa-eye pxp-password-toggle"></span>
                                    </div>

                                    <?php if ($terms != '') { ?>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="pxp-signup-modal-terms">
                                                <label class="form-check-label" for="pxp-signup-modal-terms">
                                                    <?php printf(__('I agree with <a href="%s" class="pxp-modal-link" target="_blank">Terms and Conditions</a>', 'jobster'), get_permalink($terms)); ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php }

                                    wp_nonce_field('signin_ajax_nonce', 'pxp-signup-modal-security', true); ?>
                                    <a href="javascript:void(0);" class="btn rounded-pill pxp-modal-cta pxp-signup-modal-btn">
                                        <span class="pxp-signup-modal-btn-text"><?php esc_html_e('Continue', 'jobster'); ?></span>
                                        <span class="pxp-signup-modal-btn-loading pxp-btn-loading"><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-btn-loader" alt="..."></span>
                                    </a>

                                    <div class="mt-4 text-center pxp-modal-small">
                                        <?php esc_html_e('Already have an account?', 'jobster'); ?> <a role="button" class="" data-bs-target="#pxp-signin-modal" data-bs-toggle="modal"><?php esc_html_e('Sign in', 'jobster'); ?></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="modal fade pxp-user-modal" id="pxp-forgot-modal" aria-hidden="true" aria-labelledby="forgotModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_html_e('Close', 'jobster'); ?>"></button>
                        </div>
                        <div class="modal-body">
                            <div class="pxp-user-modal-fig text-center">
                                <?php if (!empty($forgot_pass_img)) {
                                    $forgot_pass_fig = wp_get_attachment_image_src($forgot_pass_img, 'full');
                                    if (is_array($forgot_pass_fig)) { ?>
                                        <img src="<?php echo esc_url($forgot_pass_fig[0]); ?>" alt="<?php esc_html_e('Forgot Password', 'jobster'); ?>">
                                    <?php } else { ?>
                                        <img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/password-fig.png'); ?>" alt="<?php esc_html_e('Forgot Password', 'jobster'); ?>">
                                    <?php }
                                } else { ?>
                                    <img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/password-fig.png'); ?>" alt="<?php esc_html_e('Forgot Password', 'jobster'); ?>">
                                <?php } ?>
                            </div>
                            <h5 class="modal-title text-center mt-4" id="forgotModal"><?php esc_html_e('Forgot Password', 'jobster'); ?></h5>
                            <form class="mt-4">
                                <div class="pxp-modal-message pxp-forgot-modal-message"></div>
            
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="pxp-forgot-modal-email" placeholder="<?php esc_html_e('Email address', 'jobster'); ?>">
                                    <label for="pxp-forgot-modal-email"><?php esc_html_e('Email address', 'jobster'); ?></label>
                                    <span class="fa fa-envelope-o"></span>
                                </div>

                                <?php wp_nonce_field('signin_ajax_nonce', 'pxp-forgot-modal-security', true); ?>
                                <a href="javascript:void(0);" class="btn rounded-pill pxp-modal-cta pxp-forgot-modal-btn">
                                    <span class="pxp-forgot-modal-btn-text"><?php esc_html_e('Get new password', 'jobster'); ?></span>
                                    <span class="pxp-forgot-modal-btn-loading pxp-btn-loading"><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-btn-loader" alt="..."></span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade pxp-user-modal" id="pxp-reset-modal" aria-hidden="true" aria-labelledby="resetModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_html_e('Close', 'jobster'); ?>"></button>
                        </div>
                        <div class="modal-body">
                            <div class="pxp-user-modal-fig text-center">
                                <img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/password-fig.png'); ?>" alt="<?php esc_html_e('Reset Password', 'jobster'); ?>">
                            </div>
                            <h5 class="modal-title text-center mt-4" id="resetModal"><?php esc_html_e('Reset Password', 'jobster'); ?></h5>
                            <form class="mt-4">
                                <div class="pxp-modal-message pxp-reset-modal-message"></div>
            
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control pxp-password-control" id="pxp-reset-modal-password" placeholder="<?php esc_html_e('New password', 'jobster'); ?>">
                                    <label for="pxp-reset-modal-password"><?php esc_html_e('New password', 'jobster'); ?></label>
                                    <span class="fa fa-eye pxp-password-toggle"></span>
                                </div>
                                <div class="mb-3">
                                    <small class="form-text text-muted"><?php esc_html_e('Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ & ).', 'jobster') ?></small>
                                </div>

                                <?php wp_nonce_field('signin_ajax_nonce', 'pxp-reset-modal-security', true); ?>
                                <a href="javascript:void(0);" class="btn rounded-pill pxp-modal-cta pxp-reset-modal-btn">
                                    <span class="pxp-reset-modal-btn-text"><?php esc_html_e('Get new password', 'jobster'); ?></span>
                                    <span class="pxp-reset-modal-btn-loading pxp-btn-loading"><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-btn-loader" alt="..."></span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $is_company = jobster_user_is_company($current_user->ID);
            $is_candidate = jobster_user_is_candidate($current_user->ID);

            if ($is_company === false && $is_candidate === false) { ?>
                <div class="modal fade pxp-user-modal" id="pxp-account-type-modal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="accountTypeModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="pxp-user-modal-fig text-center">
                                    <?php if (!empty($account_type_img)) {
                                        $account_type_fig = wp_get_attachment_image_src($account_type_img, 'full');
                                        if (is_array($account_type_fig)) { ?>
                                            <img src="<?php echo esc_url($account_type_fig[0]); ?>" alt="<?php esc_html_e('Account Type', 'jobster'); ?>">
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/account-type-fig.png'); ?>" alt="<?php esc_html_e('Account Type', 'jobster'); ?>">
                                        <?php }
                                    } else { ?>
                                        <img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/account-type-fig.png'); ?>" alt="<?php esc_html_e('Account Type', 'jobster'); ?>">
                                    <?php } ?>
                                </div>
                                <h5 class="modal-title text-center mt-4" id="accountTypeModal"><?php esc_html_e('Choose Your Account Type', 'jobster'); ?></h5>
                                <form class="mt-4">
                                    <div class="pxp-modal-message pxp-reset-modal-message"></div>
                                    <?php wp_nonce_field('signin_ajax_nonce', 'pxp-account-type-modal-security', true); ?>
                                    <div class="row">
                                        <?php if ($show_reg_candidate === true) { ?>
                                            <div class="col">
                                                <a href="javascript:void(0);" class="btn rounded-pill pxp-modal-cta pxp-account-type-modal-btn" data-type="candidate">
                                                    <span class="pxp-account-type-modal-btn-text"><?php esc_html_e('I am Candidate', 'jobster'); ?></span>
                                                    <span class="pxp-account-type-modal-btn-loading pxp-btn-loading"><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-btn-loader" alt="..."></span>
                                                </a>
                                            </div>
                                        <?php }
                                        if ($show_reg_company === true) { ?>
                                            <div class="col">
                                                <a href="javascript:void(0);" class="btn rounded-pill pxp-modal-cta pxp-account-type-modal-btn" data-type="company">
                                                    <span class="pxp-account-type-modal-btn-text"><?php esc_html_e('I am Company', 'jobster'); ?></span>
                                                    <span class="pxp-account-type-modal-btn-loading pxp-btn-loading"><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" class="pxp-btn-loader" alt="..."></span>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        }
    }
endif;
?>