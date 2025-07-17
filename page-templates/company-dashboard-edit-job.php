<?php
/*
Template Name: Company Dashboard - Edit Job
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
    $jobs_url = jobster_get_page_link('company-dashboard-jobs.php');

    $job_id =   isset($_GET['id']) 
                ? sanitize_text_field($_GET['id'])
                : '';
    $job = get_post($job_id);

    if ($job) {
        $job_company = get_post_meta($job_id, 'job_company', true);
        $job_status = get_post_status($job_id);

        if ($company_id != $job_company) {
            wp_redirect($jobs_url);
        }
    } else {
        wp_redirect($jobs_url);
    }
} else {
    wp_redirect(home_url());
}

get_header('dashboard', array('bg_color' => 'pxpMainColorLight'));

jobster_get_company_dashboard_side($company_id, 'jobs');

$membership_settings = get_option('jobster_membership_settings', '');
$payment_type  =    isset($membership_settings['jobster_payment_type_field'])
                    ? $membership_settings['jobster_payment_type_field']
                    : '';
$featured = get_post_meta($job_id, 'job_featured', true);

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
        <div class="pxp-edit-job-statuses">
            <?php if ($job_status == 'publish') { ?>
                <span class="badge rounded-pill bg-success">
                    <?php esc_html_e('Published', 'jobster'); ?>
                </span>
            <?php } else if ($job_status == 'pending') { ?>
                <span class="badge rounded-pill bg-warning">
                    <?php esc_html_e('Pending', 'jobster'); ?>
                </span>
            <?php } else { ?>
                <span class="badge rounded-pill bg-secondary">
                    <?php esc_html_e('Draft', 'jobster'); ?>
                </span>
            <?php }

            if ($payment_type == 'listing') {
                $payment_status = get_post_meta(
                    $job_id,
                    'job_payment_status',
                    true
                );

                if ($payment_status == 'paid') { ?>
                    <span class="badge rounded-pill bg-success">
                        <?php esc_html_e('Paid', 'jobster'); ?>
                    </span>
                <?php } else if (get_post_status($job_id) != 'publish') { ?>
                    <span class="badge rounded-pill bg-danger">
                        <?php esc_html_e('Payment required', 'jobster'); ?>
                    </span>
                <?php }
            } ?>
        </div>
        <h1 class="mt-3">
            <?php esc_html_e('Edit', 'jobster'); ?> <i><?php echo get_the_title($job_id); ?></i>
            <?php if ($featured == '1') { ?>
                <span class="badge rounded-pill pxp-company-dashboard-job-feat-label">
                    <span class="fa fa-star"></span>
            </span>
            <?php } ?>
        </h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Edit the job offer details.', 'jobster'); ?>
        </p>

        <form class="pxp-dashboard-form">
            <input 
                type="hidden" 
                id="pxp-company-edit-job-company-id" 
                value="<?php echo esc_attr($company_id); ?>"
            >
            <input 
                type="hidden" 
                id="pxp-company-edit-job-id" 
                value="<?php echo esc_attr($job_id); ?>"
            >
            <div class="row mt-4 mt-lg-5">
                <div class="col-xxl-8">
                    <div class="mb-3">
                        <?php $title = get_the_title($job_id); ?>
                        <label 
                            for="pxp-company-edit-job-title" 
                            class="form-label"
                        >
                            <?php esc_html_e('Job title', 'jobster'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-company-edit-job-title" 
                            class="form-control pxp-is-required" 
                            placeholder="<?php esc_html_e('Add job title', 'jobster'); ?>" 
                            value="<?php echo esc_attr($title); ?>"
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
                                );
                                $category = wp_get_post_terms(
                                    $job_id, 'job_category'
                                );
                                $category_id =  $category 
                                                ? $category[0]->term_id
                                                : ''; ?>

                                <label 
                                    for="pxp-company-edit-job-category" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Category', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <select 
                                    id="pxp-company-edit-job-category" 
                                    class="form-select pxp-is-required" 
                                >
                                    <option value="0">
                                        <?php esc_html_e('Select category', 'jobster'); ?>
                                    </option>
                                    <?php foreach ($category_terms as $category_term) { ?>
                                        <option 
                                            value="<?php echo esc_attr($category_term->term_id);?>" 
                                            <?php selected($category_id, $category_term->term_id) ?>
                                        >
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
                                );
                                $type = wp_get_post_terms(
                                    $job_id, 'job_type'
                                );
                                $type_id =  $type
                                            ? $type[0]->term_id
                                            : ''; ?>

                                <label 
                                    for="pxp-company-edit-job-type" 
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
                                    id="pxp-company-edit-job-type" 
                                    class="form-select <?php echo esc_attr($type_required_class); ?>"
                                >
                                    <option value="0">
                                        <?php esc_html_e('Select type', 'jobster'); ?>
                                    </option>
                                    <?php foreach ($type_terms as $type_term) { ?>
                                        <option 
                                            value="<?php echo esc_attr($type_term->term_id);?>" 
                                            <?php selected($type_id, $type_term->term_id) ?>
                                        >
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
                        <?php $cover_val = get_post_meta($job_id, 'job_cover', true);
                        $cover = wp_get_attachment_image_src($cover_val,'pxp-gallery');
                        
                        $has_cover_class = '';
                        if (is_array($cover)) {
                            $has_cover_class = 'pxp-has-image';
                        } ?>
                        <div id="pxp-upload-container-cover" class="<?php echo esc_attr($has_cover_class); ?>">
                            <div class="pxp-dashboard-cover">
                                <?php if (is_array($cover)) { ?>
                                    <div 
                                        class="pxp-dashboard-cover-photo pxp-cover has-animation" 
                                        style="background-image:url(<?php echo esc_url($cover[0]); ?>)" 
                                        data-id="<?php echo esc_attr($cover_val); ?>"
                                    ></div>
                                <?php } else { ?>
                                    <div 
                                        class="pxp-dashboard-cover-photo pxp-cover has-animation" 
                                        data-id=""
                                    ></div>
                                <?php } ?>
                            </div>
                            <div class="pxp-dashboard-upload-cover-status"></div>
                            <a 
                                role="button" 
                                id="pxp-uploader-cover" 
                                class="pxp-dashboard-upload-cover-btn"
                            >
                                <?php if (!is_array($cover)) {
                                    esc_html_e('Upload Cover Image', 'jobster'); 
                                } ?>
                            </a>
                            <input 
                                type="hidden" 
                                name="pxp-dashboard-cover" 
                                id="pxp-dashboard-cover" 
                                value="<?php echo esc_attr($cover_val); ?>"
                            >
                            <a 
                                href="javascript:void(0);" 
                                role="button" 
                                class="pxp-delete-photo-btn pxp-company-edit-job-cover-delete-btn"
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
                <?php $description = apply_filters('the_content', $job->post_content);
                $description_settings = array(
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
                wp_editor($description, 'pxp-company-edit-job-description', $description_settings); ?>
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
                        );
                        $level = wp_get_post_terms(
                            $job_id, 'job_level'
                        );
                        $level_id =  $level
                                    ? $level[0]->term_id
                                    : ''; ?>

                        <label 
                            for="pxp-company-edit-job-level" 
                            class="form-label"
                        >
                            <?php esc_html_e('Experience level', 'jobster');
                            $experience_required_class = '';
                            if (!$job_experience_optional) { ?>
                                <span class="text-danger">*</span>
                            <?php } ?>
                        </label>
                        <select 
                            id="pxp-company-edit-job-level" 
                            class="form-select <?php echo esc_attr($experience_required_class); ?>"
                        >
                            <option value="0">
                                <?php esc_html_e('Select type', 'jobster'); ?>
                            </option>
                            <?php foreach ($level_terms as $level_term) { ?>
                                <option 
                                    value="<?php echo esc_attr($level_term->term_id);?>" 
                                    <?php selected($level_id, $level_term->term_id) ?>
                                >
                                    <?php echo esc_html($level_term->name); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="mb-3">
                        <?php $experience = get_post_meta(
                            $job_id,
                            'job_experience',
                            true
                        ); ?>
                        <label 
                            for="pxp-company-edit-job-experience" 
                            class="form-label"
                        >
                            <?php esc_html_e('Required experience', 'jobster'); ?>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-company-edit-job-experience" 
                            class="form-control" 
                            placeholder="<?php esc_html_e('E.g. Minimum 1 year', 'jobster'); ?>" 
                            value="<?php echo esc_attr($experience); ?>"
                        >
                    </div>
                </div>
                <?php if (!$hide_salary_field) { ?>
                    <div class="col-md-6 col-xxl-3">
                        <div class="mb-3">
                            <?php $salary = get_post_meta(
                                $job_id,
                                'job_salary',
                                true
                            ); ?>
                            <label 
                                for="pxp-company-edit-job-salary" 
                                class="form-label"
                            >
                                <?php esc_html_e('Salary', 'jobster'); ?>
                            </label>
                            <input 
                                type="text" 
                                id="pxp-company-edit-job-salary" 
                                class="form-control" 
                                placeholder="<?php esc_html_e('E.g. $100k / year', 'jobster'); ?>" 
                                value="<?php echo esc_attr($salary); ?>"
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
                            <?php $valid = get_post_meta(
                                $job_id,
                                'job_valid',
                                true
                            ); ?>
                            <label 
                                for="pxp-company-edit-job-valid" 
                                class="form-label"
                            >
                                <?php esc_html_e('Valid until', 'jobster'); ?>
                            </label>
                            <input 
                                type="text" 
                                id="pxp-company-edit-job-valid" 
                                class="form-control pxp-datepicker" 
                                placeholder="<?php esc_html_e('YYYY-MM-DD', 'jobster'); ?>" 
                                value="<?php echo esc_attr($valid); ?>"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                <?php } ?>
                <div class="col-md-6">
                    <div class="mb-3">
                        <?php $action = get_post_meta(
                            $job_id,
                            'job_action',
                            true
                        ); ?>
                        <label 
                            for="pxp-company-edit-job-action" 
                            class="form-label"
                        >
                            <?php esc_html_e('Apply Job External URL', 'jobster'); ?>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-company-edit-job-action" 
                            class="form-control" 
                            placeholder="https://" 
                            value="<?php echo esc_attr($action); ?>"
                        >
                    </div>
                </div>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Location', 'jobster'); ?></h2>

                <?php $location = wp_get_post_terms(
                    $job_id, 'job_location'
                );
                $location_id =  $location 
                                ? $location[0]->term_id
                                : ''; ?>

                <div class="pxp-company-new-job-location mb-3">
                    <div class="pxp-company-new-job-location-select">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label 
                                        for="pxp-company-edit-job-location" 
                                        class="form-label"
                                    >
                                        <?php esc_html_e('Location', 'jobster'); ?>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <?php wp_dropdown_categories(array(
                                        'taxonomy' => 'job_location',
                                        'class' => 'form-select pxp-is-required',
                                        'hide_empty' => false,
                                        'id' => 'pxp-company-edit-job-location',
                                        'selected' => $location_id,
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
                            'company_edit_job_location_ajax_nonce',
                            'pxp-company-edit-job-location-security',
                            true
                        ); ?>
                        <div class="pxp-company-edit-job-location-new d-none">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label 
                                            for="pxp-company-edit-job-location-new" 
                                            class="form-label"
                                        >
                                            <?php esc_html_e('New location', 'jobster'); ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            id="pxp-company-edit-job-location-new" 
                                            class="form-control pxp-is-required" 
                                            placeholder="<?php esc_html_e('Add your location', 'jobster'); ?>" 
                                            required
                                        >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label 
                                            for="pxp-company-edit-job-location-parent" 
                                            class="form-label"
                                        >
                                            <?php esc_html_e('Parent location (optional)', 'jobster'); ?>
                                        </label>
                                        <?php wp_dropdown_categories(array(
                                            'taxonomy' => 'job_location',
                                            'class' => 'form-select',
                                            'hide_empty' => false,
                                            'id' => 'pxp-company-edit-job-location-parent',
                                            'orderby' => 'name',
                                            'hierarchical' => true,
                                            'show_option_all' => __('Select parent location', 'jobster')
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                            <a 
                                href="javascript:void(0);" 
                                class="btn rounded-pill pxp-subsection-cta pxp-company-edit-job-location-ok-btn"
                            >
                                <span class="pxp-company-edit-job-location-ok-btn-text">
                                    <?php esc_html_e('Add', 'jobster'); ?>
                                </span>
                                <span class="pxp-company-edit-job-location-ok-btn-loading pxp-btn-loading">
                                    <img 
                                        src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                        class="pxp-btn-loader" 
                                        alt="..."
                                    >
                                </span>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-company-edit-job-location-cancel-btn"
                            >
                                <?php esc_html_e('Cancel', 'jobster'); ?>
                            </a>
                        </div>
                        <a 
                            href="javascript:void(0);" 
                            class="btn rounded-pill pxp-subsection-cta pxp-company-edit-job-add-location-btn"
                        >
                            <?php esc_html_e('Add New Location', 'jobster'); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Benefits', 'jobster'); ?></h2>

                <?php $benefits = get_post_meta($job_id, 'job_benefits', true);
                $benefits_list = array();

                if ($benefits != '') {
                    $benefits_data = json_decode(urldecode($benefits));

                    if (isset($benefits_data)) {
                        $benefits_list = $benefits_data->benefits;
                    }
                } ?>

                <div class="table-responsive">
                    <table class="table align-middle pxp-company-edit-job-benefits-list">
                        <tbody>
                            <?php if (count($benefits_list) > 0) {
                                foreach ($benefits_list as $benefits_item) { 
                                    $icon = wp_get_attachment_image_src($benefits_item->icon, 'pxp-thmb'); ?>

                                    <tr>
                                        <td style="width: 5%;">
                                            <div class="pxp-company-new-job-benefits-cell-icon">
                                                <?php if (is_array($icon)) { ?>
                                                    <span>
                                                        <img 
                                                            src="<?php echo esc_url($icon[0]); ?>" 
                                                            alt="<?php echo esc_attr($benefits_item->title); ?>"
                                                        >
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="fa fa-star-o"></span>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td style="width: 70%;">
                                            <div class="pxp-company-new-job-benefits-cell-title">
                                                <?php echo esc_html($benefits_item->title); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pxp-dashboard-table-options">
                                                <ul 
                                                    class="list-unstyled" 
                                                    data-title="<?php echo esc_attr($benefits_item->title); ?>" 
                                                    data-icon="<?php echo esc_attr($benefits_item->icon); ?>" 
                                                    <?php if (is_array($icon)) { ?>
                                                        data-src="<?php echo esc_url($icon[0]); ?>"
                                                    <?php } else { ?>
                                                        data-src=""
                                                    <?php } ?>
                                                >
                                                    <li>
                                                        <button 
                                                            type="button" 
                                                            class="pxp-company-dashboard-delete-job-benefit-btn" 
                                                            title="<?php esc_attr_e('Delete', 'jobster'); ?>"
                                                        >
                                                            <span class="fa fa-trash-o"></span>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>

                <input 
                    type="hidden" 
                    id="pxp-company-edit-job-benefits" 
                    name="pxp-company-edit-job-benefits" 
                    value="<?php echo esc_attr($benefits); ?>"
                >
                <div class="pxp-company-edit-job-benefit-form mt-3 mt-lg-4 d-none">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label 
                                    for="pxp-company-edit-job-benefit-title" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Title', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-company-edit-job-benefit-title" 
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
                                        class="pxp-delete-photo-btn pxp-company-edit-job-benefit-icon-delete-btn"
                                    >
                                        <span class="fa fa-trash-o"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-subsection-cta pxp-company-edit-job-ok-benefit-btn"
                    >
                        <?php esc_html_e('Add', 'jobster'); ?>
                    </a>
                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-company-edit-job-cancel-benefit-btn"
                    >
                        <?php esc_html_e('Cancel', 'jobster'); ?>
                    </a>
                </div>

                <a 
                    href="javascript:void(0);" 
                    class="btn mt-3 mt-lg-4 rounded-pill pxp-subsection-cta pxp-company-edit-job-add-benefit-btn"
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
                        <?php foreach ($jobs_fields_settings as $jfs_key => $jfs_value) {
                            $cf_edit_value  = ($job_id != '') ? get_post_meta($job_id, $jfs_key, true) : ''; ?>

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
                                            class="form-control pxp-datepicker pxp-company-edit-job-custom-field <?php if ($jfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
                                            placeholder="<?php esc_html_e('YYYY-MM-DD', 'jobster'); ?>" 
                                            autocomplete="off" 
                                            data-mandatory="<?php echo esc_attr($jfs_value['mandatory']); ?>" 
                                            data-label="<?php echo esc_attr($jfs_value['label']); ?>" 
                                            value="<?php echo esc_attr($cf_edit_value); ?>"
                                        >
                                    <?php } else if ($jfs_value['type'] == 'list_field') {
                                        $jf_list = explode(',', $jfs_value['list']); ?>
                                        <select 
                                            id="<?php echo esc_attr($jfs_key); ?>" 
                                            class="form-select pxp-company-edit-job-custom-field <?php if ($jfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
                                            data-mandatory="<?php echo esc_attr($jfs_value['mandatory']); ?>" 
                                            data-label="<?php echo esc_attr($jfs_value['label']); ?>"
                                        >
                                            <option value="">
                                                <?php esc_html_e('Select', 'jobster'); ?>
                                            </option>
                                            <?php for ($jf_i = 0; $jf_i < count($jf_list); $jf_i++) { ?>
                                                <option 
                                                    value="<?php echo esc_attr($jf_i); ?>" 
                                                    <?php selected($cf_edit_value, $jf_i); ?>
                                                >
                                                    <?php echo esc_html($jf_list[$jf_i]); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>
                                        <input 
                                            type="text" 
                                            id="<?php echo esc_attr($jfs_key); ?>" 
                                            class="form-control pxp-company-edit-job-custom-field <?php if ($jfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
                                            data-mandatory="<?php echo esc_attr($jfs_value['mandatory']); ?>" 
                                            data-label="<?php echo esc_attr($jfs_value['label']); ?>" 
                                            value="<?php echo esc_attr($cf_edit_value); ?>"
                                        >
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <div class="mt-4 mt-lg-5">
                <div class="pxp-company-edit-job-response"></div>
                <?php wp_nonce_field(
                    'company_edit_job_ajax_nonce',
                    'pxp-company-edit-job-security',
                    true
                );

                $show_publish_btn = false;
                $show_update_btn = false;
                $show_save_btn = false;

                if ($payment_type == 'listing') {
                    $payment_status = get_post_meta(
                        $job_id,
                        'job_payment_status',
                        true
                    );

                    if ($job_status == 'publish') {
                        $show_update_btn = true;
                        $show_save_btn = true;
                    } else if ($job_status == 'pending') {
                        $show_update_btn = true;
                        $show_save_btn = true;
                    } else {
                        if ($payment_status == 'paid') {
                            $show_publish_btn = true;
                            $show_save_btn = true;
                        } else {
                            $show_update_btn = true;
                        }
                    }
                } else {
                    if ($job_status == 'publish' || $job_status == 'pending') {
                        $show_update_btn = true;
                        $show_save_btn = true;
                    } else {
                        $show_publish_btn = true;
                        $show_save_btn = true;
                    }
                }

                if ($show_publish_btn === true) { ?>
                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-submit-btn pxp-company-edit-job-save-btn"
                    >
                        <span class="pxp-company-edit-job-save-btn-text">
                            <?php esc_html_e('Publish', 'jobster'); ?>
                        </span>
                        <span class="pxp-company-edit-job-save-btn-loading pxp-btn-loading">
                            <img 
                                src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                class="pxp-btn-loader" 
                                alt="..."
                            >
                        </span>
                    </a>
                <?php }

                if ($show_update_btn === true) { ?>
                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-submit-btn pxp-company-edit-job-save-btn"
                    >
                        <span class="pxp-company-edit-job-save-btn-text">
                            <?php esc_html_e('Update', 'jobster'); ?>
                        </span>
                        <span class="pxp-company-edit-job-save-btn-loading pxp-btn-loading">
                            <img 
                                src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                class="pxp-btn-loader" 
                                alt="..."
                            >
                        </span>
                    </a>
                <?php }

                if ($show_save_btn === true) { ?>
                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-submit-btn-o pxp-company-edit-job-save-btn pxp-is-draft ms-3"
                    >
                        <span class="pxp-company-edit-job-save-btn-text">
                            <?php esc_html_e('Save Draft', 'jobster'); ?>
                        </span>
                        <span class="pxp-company-edit-job-save-btn-loading pxp-btn-loading">
                            <img 
                                src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-blue.svg'); ?>" 
                                class="pxp-btn-loader" 
                                alt="..."
                            >
                        </span>
                    </a>
                <?php } ?>
            </div>
        </form>
    </div>

    <?php get_footer('dashboard'); ?>
</div>