<?php
/*
Template Name: Company Dashboard - Change Password
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $current_user;
global $company_id;
global $post;

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

$current_user = wp_get_current_user();

$is_company = jobster_user_is_company($current_user->ID);
if ($is_company) {
    $company_id = jobster_get_company_by_userid($current_user->ID);
} else {
    wp_redirect(home_url());
}

get_header('dashboard', array('bg_color' => 'pxpMainColorLight'));

jobster_get_company_dashboard_side($company_id, 'password'); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_company_dashboard_top($company_id); ?>

    <div class="pxp-dashboard-content-details">
        <h1><?php esc_html_e('Change Password', 'jobster'); ?></h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Choose a new account password.', 'jobster'); ?>
        </p>

        <form class="pxp-dashboard-form">
            <input 
                type="hidden" 
                id="pxp-company-edit-job-company-id" 
                value="<?php echo esc_attr($company_id); ?>"
            >

            <div class="row mt-4 mt-lg-5">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label 
                            for="pxp-company-password-old" 
                            class="form-label"
                        >
                            <?php esc_html_e('Password', 'jobster'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <input 
                                type="password" 
                                id="pxp-company-password-old" 
                                class="form-control pxp-password-control pxp-is-required" 
                                placeholder="<?php esc_html_e('Enter old password', 'jobster'); ?>" 
                                required
                            >
                            <span class="fa fa-eye pxp-password-toggle"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label 
                            for="pxp-company-password-new" 
                            class="form-label"
                        >
                            <?php esc_html_e('New password', 'jobster'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <input 
                                type="password" 
                                id="pxp-company-password-new" 
                                class="form-control pxp-password-control pxp-is-required" 
                                placeholder="<?php esc_html_e('Enter new password', 'jobster'); ?>" 
                                required
                            >
                            <span class="fa fa-eye pxp-password-toggle"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label 
                            for="pxp-company-password-new-repeat" 
                            class="form-label"
                        >
                            <?php esc_html_e('New password repeat', 'jobster'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <input 
                                type="password" 
                                id="pxp-company-password-new-repeat" 
                                class="form-control pxp-password-control pxp-is-required" 
                                placeholder="<?php esc_html_e('Repeat new password', 'jobster'); ?>" 
                                required
                            >
                            <span class="fa fa-eye pxp-password-toggle"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 mt-lg-5">
                <div class="pxp-company-password-response"></div>
                <?php wp_nonce_field(
                    'password_ajax_nonce',
                    'pxp-password-security',
                    true
                ); ?>
                <a 
                    href="javascript:void(0);" 
                    class="btn rounded-pill pxp-submit-btn pxp-company-save-password-btn"
                >
                    <span class="pxp-company-save-password-btn-text">
                        <?php esc_html_e('Save New Password', 'jobster'); ?>
                    </span>
                    <span class="pxp-company-save-password-btn-loading pxp-btn-loading">
                        <img 
                            src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                            class="pxp-btn-loader" 
                            alt="..."
                        >
                    </span>
                </a>
            </div>
        </form>
    </div>

    <?php get_footer('dashboard'); ?>
</div>