<?php
/*
Template Name: Company Dashboard - New Job
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

jobster_get_company_dashboard_side($company_id, 'new_job');

$membership_settings = get_option('jobster_membership_settings');
$payment_type = isset($membership_settings['jobster_payment_type_field'])
            ? $membership_settings['jobster_payment_type_field']
            : '';
$payment_currency = '';
$payment_system =   isset($membership_settings['jobster_payment_system_field'])
                    ? $membership_settings['jobster_payment_system_field']
                    : '';
switch ($payment_system) {
    case 'paypal':
        $payment_currency = isset($membership_settings['jobster_paypal_payment_currency_field'])
                            ? $membership_settings['jobster_paypal_payment_currency_field']
                            : '';
        break;
    case 'stripe':
        $payment_currency = isset($membership_settings['jobster_stripe_payment_currency_field'])
                            ? $membership_settings['jobster_stripe_payment_currency_field']
                            : '';
        break;
    default:
        $payment_currency = '';
        break;
}
$standard_price =   isset($membership_settings['jobster_submission_price_field'])
                    ? $membership_settings['jobster_submission_price_field']
                    : __('Free', 'jobster');
$featured_price =   isset($membership_settings['jobster_featured_price_field'])
                    ? $membership_settings['jobster_featured_price_field']
                    : __('Free', 'jobster');
$standard_unlim =   isset($membership_settings['jobster_free_submissions_unlim_field'])
                    ? $membership_settings['jobster_free_submissions_unlim_field']
                    : '';
$company_payment = get_post_meta($company_id, 'company_payment', true);
$display_form = true;
$expired = false;
$inactive = false;

$jobs_settings = get_option('jobster_jobs_settings');
$new_location_option = isset($jobs_settings['jobster_jobs_new_location_field'])
                        && $jobs_settings['jobster_jobs_new_location_field'] == '1';
$job_type_optional =    isset($jobs_settings['jobster_job_type_optional_field'])
                        && $jobs_settings['jobster_job_type_optional_field'] == '1';
$job_experience_optional =  isset($jobs_settings['jobster_job_experience_optional_field'])
                            && $jobs_settings['jobster_job_experience_optional_field'] == '1';
$hide_salary_field =    isset($jobs_settings['jobster_job_form_hide_salary_field'])
                        && $jobs_settings['jobster_job_form_hide_salary_field'] == '1';
$validity_period =  isset($jobs_settings['jobster_job_validity_period_field'])
                    ? $jobs_settings['jobster_job_validity_period_field']
                    : ''; ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_company_dashboard_top($company_id); ?>

    <div class="pxp-dashboard-content-details">
        <h1><?php esc_html_e('New Job Offer', 'jobster'); ?></h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Add a new job to your company\'s jobs list.', 'jobster'); ?>
        </p>

        <?php if ($payment_type == 'listing' && $company_payment != '1') { ?>
            <div class="row mt-4 mt-md-5">
                <div class="col-md-6">
                    <div class="pxp-company-new-job-price-card">
                        <div class="row justify-content-between align-items-center">
                            <div class="pxp-company-new-job-price-card-details col-auto">
                                <div class="pxp-company-new-job-price-card-title">
                                    <?php esc_html_e('Standard Job', 'jobster'); ?>
                                </div>
                                <?php if ($standard_unlim == '1') { ?>
                                    <div class="pxp-company-new-job-price-card-free">
                                        <b><?php esc_html_e('Unlimited', 'jobster'); ?></b> <?php esc_html_e('free posts included', 'jobster'); ?>
                                    </div>
                                <?php } else { 
                                    $standard_free_left = get_post_meta(
                                        $company_id,
                                        'company_free_listings',
                                        true
                                    ); ?>
    
                                    <div class="pxp-company-new-job-price-card-free">
                                        <b>
                                            <?php if ($standard_free_left == '' || $standard_free_left <= 0) {
                                                echo '0';
                                            } else {
                                                echo esc_html($standard_free_left);
                                            } ?>
                                        </b> <?php esc_html_e('Free posts left', 'jobster'); ?>
                                    </div>
                                    <input 
                                        type="hidden" 
                                        id="pxp-company-new-job-standard-free-left" 
                                        value="<?php echo esc_html($standard_free_left); ?>"
                                    >
                                <?php } ?>
                            </div>
                            <div class="pxp-company-new-job-price-card-price col-auto">
                                <?php if ($standard_unlim != '' && $standard_unlim == 1) {
                                    esc_html_e('Free', 'jobster');
                                } else { 
                                    echo esc_html($standard_price); ?><span><?php echo esc_html($payment_currency); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <div class="pxp-company-new-job-price-card pxp-is-featured">
                        <div class="row justify-content-between align-items-center">
                            <div class="pxp-company-new-job-price-card-details col-auto">
                                <div class="pxp-company-new-job-price-card-title">
                                    <?php esc_html_e('Featured Job', 'jobster'); ?>
                                </div>
                                <?php $featured_free_left = get_post_meta(
                                    $company_id,
                                    'company_free_featured_listings',
                                    true
                                ); ?>
                                <div class="pxp-company-new-job-price-card-free">
                                    <b>
                                        <?php if ($featured_free_left == '' || $featured_free_left <= 0) {
                                            echo '0';
                                        } else {
                                            echo esc_html($featured_free_left);
                                        } ?>
                                    </b> <?php esc_html_e('Free posts left', 'jobster'); ?>
                                </div>
                                <input 
                                    type="hidden" 
                                    id="pxp-company-new-job-featured-free-left" 
                                    value="<?php echo esc_html($featured_free_left); ?>"
                                >
                            </div>
                            <div class="pxp-company-new-job-price-card-price col-auto">
                                + <?php echo esc_html($featured_price); ?><span><?php echo esc_html($payment_currency); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }

        if ($payment_type == 'plan' && $company_payment != '1') {
            $plan_id = get_post_meta(
                $company_id,
                'company_plan',
                true
            );
            $plan_listings = get_post_meta(
                $company_id,
                'company_plan_listings',
                true
            );
            $plan_unlimited = get_post_meta(
                $company_id,
                'company_plan_unlimited',
                true
            );
            $plan_activation = strtotime(
                get_post_meta(
                    $company_id,
                    'company_plan_activation',
                    true
                )
            );
            $plan_time_unit = get_post_meta(
                $plan_id,
                'membership_billing_time_unit',
                true
            );
            $plan_period = get_post_meta(
                $plan_id,
                'membership_period',
                true
            );

            $subscriptions_url = jobster_get_page_link('company-dashboard-subscriptions.php');

            $seconds = 0;

            switch ($plan_time_unit) {
                case 'day':
                    $seconds = 60 * 60 * 24;
                break;
                case 'week':
                    $seconds = 60 * 60 * 24 * 7;
                break;
                case 'month':
                    $seconds = 60 * 60 * 24 * 30;
                break;
                case 'year':
                    $seconds = 60 * 60 * 24 * 365;
                break;
            }

            $time_frame      = $seconds * $plan_period;
            $expiration_date = $plan_activation + $time_frame;
            $expiration_date = date('Y-m-d', $expiration_date);
            $today           = getdate();

            if (intval($plan_listings) <= 0) {
                $display_form = false;
            }
            if ($plan_unlimited == '1') {
                $display_form = true;
            }
            if (!$plan_id || $plan_id == '') {
                $display_form = false;
                $inactive = true;
            } else {
                if ($today[0] > strtotime($expiration_date)) {
                    $display_form = false;
                    $expired = true;
                }
            }
        }

        if ($display_form === true) { ?>
            <form class="pxp-dashboard-form">
                <input 
                    type="hidden" 
                    id="pxp-company-new-job-company-id" 
                    value="<?php echo esc_attr($company_id); ?>"
                >
                <div class="row mt-4 mt-lg-5">
                    <div class="col-xxl-8">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-new-job-title" 
                                class="form-label"
                            >
                                <?php esc_html_e('Job title', 'jobster'); ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="pxp-company-new-job-title" 
                                class="form-control pxp-is-required" 
                                placeholder="<?php esc_html_e('Add job title', 'jobster'); ?>" 
                                required
                            >
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <?php $category_tax = array( 
                                        'job_category'
                                    );
                                    $category_args = array(
                                        'orderby'    => 'name',
                                        'order'      => 'ASC',
                                        'hide_empty' => false
                                    );
                                    $category_terms = get_terms(
                                        $category_tax,
                                        $category_args
                                    ); ?>

                                    <label 
                                        for="pxp-company-new-job-category" 
                                        class="form-label"
                                    >
                                        <?php esc_html_e('Category', 'jobster'); ?>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select 
                                        id="pxp-company-new-job-category" 
                                        class="form-select pxp-is-required" 
                                    >
                                        <option value="0">
                                            <?php esc_html_e('Select category', 'jobster'); ?>
                                        </option>
                                        <?php foreach ($category_terms as $category_term) { ?>
                                            <option value="<?php echo esc_attr($category_term->term_id);?>">
                                                <?php echo esc_html($category_term->name); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <?php $type_tax = array( 
                                        'job_type'
                                    );
                                    $type_args = array(
                                        'orderby'    => 'name',
                                        'order'      => 'ASC',
                                        'hide_empty' => false
                                    );
                                    $type_terms = get_terms(
                                        $type_tax,
                                        $type_args
                                    ); ?>

                                    <label 
                                        for="pxp-company-new-job-type" 
                                        class="form-label"
                                    >
                                        <?php esc_html_e('Type of employment', 'jobster');
                                        $type_required_class = '';
                                        if (!$job_type_optional) {
                                            $type_required_class = 'pxp-is-required'; ?>
                                            <span class="text-danger">*</span>
                                        <?php } ?>
                                    </label>
                                    <select 
                                        id="pxp-company-new-job-type" 
                                        class="form-select <?php echo esc_attr($type_required_class); ?>"
                                    >
                                        <option value="0">
                                            <?php esc_html_e('Select type', 'jobster'); ?>
                                        </option>
                                        <?php foreach ($type_terms as $type_term) { ?>
                                            <option value="<?php echo esc_attr($type_term->term_id);?>">
                                                <?php echo esc_html($type_term->name); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4">
                        <div class="form-label">&nbsp;</div>
                        <div class="position-relative mb-3">
                            <div id="pxp-upload-container-cover">
                                <div class="pxp-dashboard-cover">
                                    <div 
                                        class="pxp-dashboard-cover-photo pxp-cover has-animation" 
                                        data-id=""
                                    ></div>
                                </div>
                                <div class="pxp-dashboard-upload-cover-status"></div>
                                <a 
                                    role="button" 
                                    id="pxp-uploader-cover" 
                                    class="pxp-dashboard-upload-cover-btn"
                                >
                                    <?php esc_html_e('Upload Cover Image', 'jobster'); ?>
                                </a>
                                <input 
                                    type="hidden" 
                                    name="pxp-dashboard-cover" 
                                    id="pxp-dashboard-cover"
                                >
                                <a 
                                    href="javascript:void(0);" 
                                    role="button" 
                                    class="pxp-delete-photo-btn pxp-company-new-job-cover-delete-btn"
                                >
                                    <span class="fa fa-trash-o"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pxp-is-tinymce">
                    <label class="form-label">
                        <?php esc_html_e('Job description', 'jobster'); ?>
                    </label>
                    <?php $description_settings = array(
                        'teeny'         => true,
                        'media_buttons' => false,
                        'editor_height' => 240,
                        'editor_css'    => '
                            <style>
                                .wp-editor-tabs {
                                    float: none;
                                    padding: 1rem 0 .5rem 0;
                                    position: relative;
                                    display: inline-flex;
                                    vertical-align: middle;
                                }
                                .wp-switch-editor {
                                    float: none;
                                    top: 0;
                                    height: auto;
                                    background: transparent;
                                    color: var(--pxpMainColor);
                                    border: 1px solid var(--pxpMainColorLight);
                                    padding: 7px 16px;
                                    border-radius: 20px;
                                    margin: 0;
                                    font-weight: 400;
                                    font-size: .8rem;
                                    text-transform: uppercase;
                                    transition: var(--pxpHoverTransition);
                                    transition-property: color, background-color, border-color;
                                }
                                .wp-switch-editor:hover {
                                    color: #fff;
                                    border-color: var(--pxpMainColor);
                                    background-color: var(--pxpMainColor);
                                }
                                .wp-switch-editor.switch-tmce {
                                    border-top-right-radius: 0;
                                    border-bottom-right-radius: 0;
                                }
                                .wp-switch-editor.switch-html {
                                    border-top-left-radius: 0;
                                    border-bottom-left-radius: 0;
                                    margin-left: -1px;
                                }
                                .tmce-active .switch-tmce,
                                .html-active .switch-html {
                                    color: #fff;
                                    background-color: var(--pxpMainColorDark);
                                    border-color: var(--pxpMainColorDark);
                                }
                                div.mce-panel {
                                    background: #fff;
                                }
                                div.mce-edit-area {
                                    box-shadow: none;
                                    overflow: hidden;
                                    border: 1px solid rgba(0,0,0,.2) !important;
                                    border-radius: 30px;
                                    padding: 1rem;
                                }
                                div.mce-fullscreen div.mce-edit-area {
                                    box-shadow: none;
                                    border-radius: 0;
                                }
                                div.mce-fullscreen div.mce-panel {
                                    background: #fff;
                                }
                                div.mce-toolbar-grp {
                                    background: transparent;
                                    border-bottom: 0 none;
                                }
                                div.mce-fullscreen div.mce-toolbar-grp {
                                    background: #fff;
                                    border-bottom: 1px solid #ddd;
                                }
                                .wp-editor-container {
                                    border: 0 none;
                                }
                                div.mce-toolbar-grp > div {
                                    padding: 8px 0;
                                }
                                div.mce-fullscreen div.mce-toolbar-grp > div {
                                    padding: 3px;
                                }
                                div.mce-statusbar {
                                    border-top: 0 none;
                                    margin-bottom: 1rem;
                                }
                                .quicktags-toolbar {
                                    padding: 10px 0;
                                    border-bottom: 0 none;
                                    background: transparent;
                                }
                                .wp-editor-container textarea.wp-editor-area {
                                    border: 1px solid rgba(0,0,0,.2);
                                    font-weight: 300;
                                    color: var(--pxpTextColor);
                                    background-color: #fff;
                                    border-radius: 30px;
                                    padding: calc(1rem + 10px);
                                }
                                .mce-top-part::before {
                                    content: none;
                                }
                                .mce-ico {
                                    color: var(--pxpTextColor);
                                }
                                .mce-btn button {
                                    color: var(--pxpTextColor);
                                    border-radius: 
                                }
                                .mce-toolbar .mce-btn-group .mce-btn, 
                                .qt-dfw {
                                    border-radius: 5px;
                                    transition: var(--pxpHoverTransition);
                                    transition-property: color, background-color, border-color;
                                }
                                .mce-toolbar .mce-btn-group .mce-btn:focus, 
                                .mce-toolbar .mce-btn-group .mce-btn:hover, 
                                .qt-dfw:focus, 
                                .qt-dfw:hover {
                                    box-shadow: none;
                                    color: var(--pxpTextColor);
                                    background: transparent;
                                    border-color: rgba(0,0,0,.2);
                                }
                                .mce-toolbar .mce-btn-group .mce-btn.mce-active, 
                                .mce-toolbar .mce-btn-group .mce-btn:active, 
                                .qt-dfw.active {
                                    box-shadow: none;
                                    color: #fff;
                                    background-color: var(--pxpMainColorDark);
                                    border-color: var(--pxpMainColorDark);
                                }
                                .wp-core-ui .quicktags-toolbar input.button.button-small {
                                    background-color: var(--pxpMainColorLight);
                                    border: 0 none;
                                    border-radius: 5px;
                                    color: var(--pxpMainColorDark);
                                    transition: var(--pxpHoverTransition);
                                    transition-property: background-color, color;
                                }
                                .wp-core-ui .quicktags-toolbar input.button.button-small:hover {
                                    color: #fff;
                                    background-color: var(--pxpMainColor);
                                }
                            </style>
                        ',
                    );
                    wp_editor('', 'pxp-company-new-job-description', $description_settings); ?>
                </div>

                <div class="row">
                    <div class="col-md-6 col-xxl-3">
                        <div class="mb-3">
                            <?php $level_tax = array( 
                                'job_level'
                            );
                            $level_args = array(
                                'orderby'    => 'name',
                                'order'      => 'ASC',
                                'hide_empty' => false
                            );
                            $level_terms = get_terms(
                                $level_tax,
                                $level_args
                            ); ?>

                            <label 
                                for="pxp-company-new-job-level" 
                                class="form-label"
                            >
                                <?php esc_html_e('Experience level', 'jobster');
                                $experience_required_class = '';
                                if (!$job_experience_optional) { ?>
                                    <span class="text-danger">*</span>
                                <?php } ?>
                            </label>
                            <select 
                                id="pxp-company-new-job-level" 
                                class="form-select <?php echo esc_attr($experience_required_class); ?>"
                            >
                                <option value="0">
                                    <?php esc_html_e('Select type', 'jobster'); ?>
                                </option>
                                <?php foreach ($level_terms as $level_term) { ?>
                                    <option value="<?php echo esc_attr($level_term->term_id);?>">
                                        <?php echo esc_html($level_term->name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-xxl-3">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-new-job-experience" 
                                class="form-label"
                            >
                                <?php esc_html_e('Required experience', 'jobster'); ?>
                            </label>
                            <input 
                                type="text" 
                                id="pxp-company-new-job-experience" 
                                class="form-control" 
                                placeholder="<?php esc_html_e('E.g. Minimum 1 year', 'jobster'); ?>" 
                            >
                        </div>
                    </div>
                    <?php if (!$hide_salary_field) { ?>
                        <div class="col-md-6 col-xxl-3">
                            <div class="mb-3">
                                <label 
                                    for="pxp-company-new-job-salary" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Salary', 'jobster'); ?>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-company-new-job-salary" 
                                    class="form-control" 
                                    placeholder="<?php esc_html_e('E.g. $100k / year', 'jobster'); ?>" 
                                >
                            </div>
                        </div>
                    <?php }
                    $hide_valid_field = false;
                    if ($validity_period != '' 
                        && is_numeric($validity_period) 
                        && intval($validity_period) > 0) 
                    {
                        $hide_valid_field = true;
                    } 
                    if ($hide_valid_field === false) { ?>
                        <div class="col-md-6 col-xxl-3">
                            <div class="mb-3">
                                <label 
                                    for="pxp-company-new-job-valid" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Valid until', 'jobster'); ?>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-company-new-job-valid" 
                                    class="form-control pxp-datepicker" 
                                    placeholder="<?php esc_html_e('YYYY-MM-DD', 'jobster'); ?>" 
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-new-job-action" 
                                class="form-label"
                            >
                                <?php esc_html_e('Apply job external URL', 'jobster'); ?>
                            </label>
                            <input 
                                type="text" 
                                id="pxp-company-new-job-action" 
                                class="form-control" 
                                placeholder="https://" 
                            >
                        </div>
                    </div>
                </div>

                <div class="mt-4 mt-lg-5">
                    <h2><?php esc_html_e('Location', 'jobster'); ?></h2>
                    <div class="pxp-company-new-job-location mb-3">
                        <div class="pxp-company-new-job-location-select">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label 
                                            for="pxp-company-new-job-location" 
                                            class="form-label"
                                        >
                                            <?php esc_html_e('Location', 'jobster'); ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <?php wp_dropdown_categories(array(
                                            'taxonomy' => 'job_location',
                                            'class' => 'form-select pxp-is-required',
                                            'hide_empty' => false,
                                            'id' => 'pxp-company-new-job-location',
                                            'orderby' => 'name',
                                            'hierarchical' => true,
                                            'show_option_all' => __('Select location', 'jobster')
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($new_location_option) {
                            wp_nonce_field(
                                'company_new_job_location_ajax_nonce',
                                'pxp-company-new-job-location-security',
                                true
                            ); ?>
                            <div class="pxp-company-new-job-location-new d-none">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label 
                                                for="pxp-company-new-job-location-new" 
                                                class="form-label"
                                            >
                                                <?php esc_html_e('New location', 'jobster'); ?>
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input 
                                                type="text" 
                                                id="pxp-company-new-job-location-new" 
                                                class="form-control pxp-is-required" 
                                                placeholder="<?php esc_html_e('Add your location', 'jobster'); ?>" 
                                                required
                                            >
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="mb-3">
                                            <label 
                                                for="pxp-company-new-job-location-parent" 
                                                class="form-label"
                                            >
                                                <?php esc_html_e('Parent location (optional)', 'jobster'); ?>
                                            </label>
                                            <?php wp_dropdown_categories(array(
                                                'taxonomy' => 'job_location',
                                                'class' => 'form-select',
                                                'hide_empty' => false,
                                                'id' => 'pxp-company-new-job-location-parent',
                                                'orderby' => 'name',
                                                'hierarchical' => true,
                                                'show_option_all' => __('Select parent location', 'jobster')
                                            )); ?>
                                        </div>
                                    </div>
                                </div>
                                <a 
                                    href="javascript:void(0);" 
                                    class="btn rounded-pill pxp-subsection-cta pxp-company-new-job-location-ok-btn"
                                >
                                    <span class="pxp-company-new-job-location-ok-btn-text">
                                        <?php esc_html_e('Add', 'jobster'); ?>
                                    </span>
                                    <span class="pxp-company-new-job-location-ok-btn-loading pxp-btn-loading">
                                        <img 
                                            src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                            class="pxp-btn-loader" 
                                            alt="..."
                                        >
                                    </span>
                                </a>
                                <a 
                                    href="javascript:void(0);" 
                                    class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-company-new-job-location-cancel-btn"
                                >
                                    <?php esc_html_e('Cancel', 'jobster'); ?>
                                </a>
                            </div>
                            <a 
                                href="javascript:void(0);" 
                                class="btn rounded-pill pxp-subsection-cta pxp-company-new-job-add-location-btn"
                            >
                                <?php esc_html_e('Add New Location', 'jobster'); ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>

                <div class="mt-4 mt-lg-5">
                    <h2><?php esc_html_e('Benefits', 'jobster'); ?></h2>

                    <div class="table-responsive">
                        <table class="table align-middle pxp-company-new-job-benefits-list">
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <input 
                        type="hidden" 
                        id="pxp-company-new-job-benefits" 
                        name="pxp-company-new-job-benefits"
                    >
                    <div class="pxp-company-new-job-benefit-form mt-3 mt-lg-4 d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label 
                                        for="pxp-company-new-job-benefit-title" 
                                        class="form-label"
                                    >
                                        <?php esc_html_e('Title', 'jobster'); ?>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="pxp-company-new-job-benefit-title" 
                                        class="form-control pxp-is-required" 
                                        placeholder="<?php esc_attr_e('E.g. Medical Insurance', 'jobster'); ?>"
                                    >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative mb-3">
                                    <div id="pxp-upload-container-logo">
                                        <div class="pxp-dashboard-logo">
                                            <div 
                                                class="pxp-dashboard-logo-photo pxp-cover has-animation" 
                                                data-id="" 
                                                data-src=""
                                            ></div>
                                        </div>
                                        <div class="pxp-dashboard-upload-logo-status"></div>
                                        <a 
                                            role="button" 
                                            id="pxp-uploader-logo" 
                                            class="pxp-dashboard-upload-logo-btn"
                                        >
                                            <?php esc_html_e('Upload Icon', 'jobster'); ?>
                                        </a>
                                        <input 
                                            type="hidden" 
                                            name="pxp-dashboard-logo" 
                                            id="pxp-dashboard-logo"
                                        >
                                        <a 
                                            href="javascript:void(0);" 
                                            role="button" 
                                            class="pxp-delete-photo-btn pxp-company-new-job-benefit-icon-delete-btn"
                                        >
                                            <span class="fa fa-trash-o"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a 
                            href="javascript:void(0);" 
                            class="btn rounded-pill pxp-subsection-cta pxp-company-new-job-ok-benefit-btn"
                        >
                            <?php esc_html_e('Add', 'jobster'); ?>
                        </a>
                        <a 
                            href="javascript:void(0);" 
                            class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-company-new-job-cancel-benefit-btn"
                        >
                            <?php esc_html_e('Cancel', 'jobster'); ?>
                        </a>
                    </div>

                    <a 
                        href="javascript:void(0);" 
                        class="btn mt-3 mt-lg-4 rounded-pill pxp-subsection-cta pxp-company-new-job-add-benefit-btn"
                    >
                        <?php esc_html_e('Add Benefit', 'jobster'); ?>
                    </a>
                </div>

                <?php $jobs_fields_settings = get_option('jobster_jobs_fields_settings');

                if (is_array($jobs_fields_settings)) {
                    uasort($jobs_fields_settings, 'jobster_compare_position'); ?>

                    <div class="mt-4 mt-lg-5">
                        <h2><?php esc_html_e('Additional Info', 'jobster'); ?></h2>

                        <div class="row">
                            <?php foreach ($jobs_fields_settings as $jfs_key => $jfs_value) { ?>
                                <div class="col-md-6 col-xxl-3">
                                    <div class="mb-3">
                                        <label 
                                            for="<?php echo esc_attr($jfs_key); ?>" 
                                            class="form-label"
                                        >
                                            <?php echo esc_html($jfs_value['label']);
                                            if ($jfs_value['mandatory'] == 'yes') { ?>
                                                <span class="text-danger">*</span>
                                            <?php } ?>
                                        </label>
                                        <?php if ($jfs_value['type'] == 'date_field') { ?>
                                            <input 
                                                type="text" 
                                                id="<?php echo esc_attr($jfs_key); ?>" 
                                                class="form-control pxp-datepicker pxp-company-new-job-custom-field <?php if ($jfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
                                                placeholder="<?php esc_html_e('YYYY-MM-DD', 'jobster'); ?>" 
                                                autocomplete="off" 
                                                data-mandatory="<?php echo esc_attr($jfs_value['mandatory']); ?>" 
                                                data-label="<?php echo esc_attr($jfs_value['label']); ?>"
                                            >
                                        <?php } else if ($jfs_value['type'] == 'list_field') {
                                            $jf_list = explode(',', $jfs_value['list']); ?>
                                            <select 
                                                id="<?php echo esc_attr($jfs_key); ?>" 
                                                class="form-select pxp-company-new-job-custom-field <?php if ($jfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
                                                data-mandatory="<?php echo esc_attr($jfs_value['mandatory']); ?>" 
                                                data-label="<?php echo esc_attr($jfs_value['label']); ?>"
                                            >
                                                <option value="">
                                                    <?php esc_html_e('Select', 'jobster'); ?>
                                                </option>
                                                <?php for ($jf_i = 0; $jf_i < count($jf_list); $jf_i++) { ?>
                                                    <option value="<?php echo esc_attr($jf_i); ?>">
                                                        <?php echo esc_html($jf_list[$jf_i]); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        <?php } else { ?>
                                            <input 
                                                type="text" 
                                                id="<?php echo esc_attr($jfs_key); ?>" 
                                                class="form-control pxp-company-new-job-custom-field <?php if ($jfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
                                                data-mandatory="<?php echo esc_attr($jfs_value['mandatory']); ?>" 
                                                data-label="<?php echo esc_attr($jfs_value['label']); ?>"
                                            >
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="mt-4 mt-lg-5">
                    <div class="pxp-company-new-job-response"></div>
                    <?php wp_nonce_field(
                        'company_new_job_ajax_nonce',
                        'pxp-company-new-job-security',
                        true
                    ); ?>

                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-submit-btn pxp-company-new-job-save-btn"
                    >
                        <span class="pxp-company-new-job-save-btn-text">
                            <?php esc_html_e('Publish', 'jobster'); ?>
                        </span>
                        <span class="pxp-company-new-job-save-btn-loading pxp-btn-loading">
                            <img 
                                src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                class="pxp-btn-loader" 
                                alt="..."
                            >
                        </span>
                    </a>
                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-submit-btn-o pxp-company-new-job-save-btn pxp-is-draft ms-3"
                    >
                        <span class="pxp-company-new-job-save-btn-text">
                            <?php esc_html_e('Save Draft', 'jobster'); ?>
                        </span>
                        <span class="pxp-company-new-job-save-btn-loading pxp-btn-loading">
                            <img 
                                src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-blue.svg'); ?>" 
                                class="pxp-btn-loader" 
                                alt="..."
                            >
                        </span>
                    </a>
                </div>
            </form>
        <?php } else if ($expired === true) { ?>
            <div 
                class="alert alert-warning mt-4 mt-md-5 pxp-dashboard-section-alert" 
                role="alert"
            >
                <h4 class="alert-heading">
                    <?php esc_html_e('Your subscription expired.', 'jobster'); ?>
                </h4>
                <p>
                    <?php esc_html_e('Please renew your membership plan subscription in order to post new job offers.', 'jobster'); ?>
                </p>
                <a 
                    href="<?php echo esc_url($subscriptions_url); ?>" 
                    class="btn pxp-section-cta-o alert-link"
                >
                    <?php esc_html_e('Go to your subscriptions', 'jobster'); ?>
                    <span class="fa fa-angle-right"></span>
                </a>
            </div>
        <?php } else if ($inactive === true) { ?>
            <div 
                class="alert alert-warning mt-4 mt-md-5 pxp-dashboard-section-alert" 
                role="alert"
            >
                <h4 class="alert-heading">
                    <?php esc_html_e('Subscription required.', 'jobster'); ?>
                </h4>
                <p>
                    <?php esc_html_e('You need a membership plan subscription in order to post job offers.', 'jobster'); ?>
                </p>
                <a 
                    href="<?php echo esc_url($subscriptions_url); ?>" 
                    class="btn pxp-section-cta-o alert-link"
                >
                    <?php esc_html_e('Check out the plans', 'jobster'); ?>
                    <span class="fa fa-angle-right"></span>
                </a>
            </div>
        <?php } else { ?>
            <div 
                class="alert alert-warning mt-4 mt-md-5 pxp-dashboard-section-alert" 
                role="alert"
            >
                <h4 class="alert-heading">
                    <?php esc_html_e('You ran out of available postings.', 'jobster'); ?>
                </h4>
                <p>
                    <?php esc_html_e('You need to upgrade your membership plan in order to post new job offers.', 'jobster'); ?>
                </p>
                <a 
                    href="<?php echo esc_url($subscriptions_url); ?>" 
                    class="btn pxp-section-cta-o alert-link"
                >
                    <?php esc_html_e('Check out the plans', 'jobster'); ?>
                    <span class="fa fa-angle-right"></span>
                </a>
            </div>
        <?php } ?>
    </div>

    <?php get_footer('dashboard'); ?>
</div>