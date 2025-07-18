<?php
/*
Template Name: Company Dashboard - Profile
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

    $company_name = get_the_title($company_id);
    $company_email = get_post_meta($company_id, 'company_email', true);
    $company_phone = get_post_meta($company_id, 'company_phone', true);
    $company_website = get_post_meta($company_id, 'company_website', true);
    $company_redirect = get_post_meta($company_id, 'company_redirect', true);

    $cover_val = get_post_meta($company_id, 'company_cover', true);
    $cover = wp_get_attachment_image_src($cover_val, 'pxp-gallery');
    $cover_color = get_post_meta($company_id, 'company_cover_color', true);
    $cover_type = get_post_meta($company_id, 'company_cover_type', true);
    $cover_types = array(
        'n' => __('None', 'jobster'),
        'i' => __('Image', 'jobster'),
        'c' => __('Color', 'jobster')
    );

    $logo_val = get_post_meta($company_id, 'company_logo', true);
    $logo = wp_get_attachment_image_src($logo_val, 'pxp-thmb');

    $company = get_post($company_id);
    $about = apply_filters('the_content', $company->post_content);

    $industry = wp_get_post_terms($company_id, 'company_industry', true);
    $industry_id = $industry ? $industry[0]->term_id : '';

    $location = wp_get_post_terms($company_id, 'company_location', true);
    $location_id = $location ? $location[0]->term_id : '';

    $company_founded = get_post_meta($company_id, 'company_founded', true);
    $company_size = get_post_meta($company_id, 'company_size', true);

    $doc_val = get_post_meta($company_id, 'company_doc', true);
    $doc = wp_get_attachment_url($doc_val);

    $doc_filename = '';
    $doc_class = '';
    if (!empty($doc)) {
        $doc_filename = basename($doc);
        $doc_class = 'pxp-has-file';
    }

    $doc_title = get_post_meta($company_id, 'company_doc_title', true);

    $company_facebook = get_post_meta($company_id, 'company_facebook', true);
    $company_twitter = get_post_meta($company_id, 'company_twitter', true);
    $company_instagram = get_post_meta($company_id, 'company_instagram', true);
    $company_linkedin = get_post_meta($company_id, 'company_linkedin', true);

    $app_notify = get_post_meta($company_id, 'company_app_notify', true);

    $companies_settings = get_option('jobster_companies_settings');
    $new_location_option = isset($companies_settings['jobster_companies_new_location_field'])
                            && $companies_settings['jobster_companies_new_location_field'] == '1';

    $gallery = get_post_meta($company_id, 'company_gallery', true);
    $gallery_title = get_post_meta($company_id, 'company_gallery_title', true);
    $max_files =    isset($companies_settings['jobster_companies_gallery_max_field'])
                        && $companies_settings['jobster_companies_gallery_max_field'] != ''
                    ? $companies_settings['jobster_companies_gallery_max_field'] 
                    : 10;

    $video = get_post_meta($company_id, 'company_video', true);
    $video_title = get_post_meta($company_id, 'company_video_title', true);
} else {
    wp_redirect(home_url());
}

get_header('dashboard', array('bg_color' => 'pxpMainColorLight'));

jobster_get_company_dashboard_side($company_id, 'profile'); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_company_dashboard_top($company_id); ?>

    <div class="pxp-dashboard-content-details">
        <h1><?php esc_html_e('Edit Profile', 'jobster'); ?></h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Edit your company profile page info.', 'jobster'); ?>
        </p>

        <form class="pxp-dashboard-form">
            <input 
                type="hidden" 
                id="pxp-company-profile-id" 
                value="<?php echo esc_attr($company_id); ?>"
            >

            <div class="row mt-4 mt-lg-5">
                <div class="col-xxl-8">
                    <div class="mb-3">
                        <label 
                            for="pxp-company-profile-name" 
                            class="form-label"
                        >
                            <?php esc_html_e('Company name', 'jobster'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-company-profile-name" 
                            class="form-control pxp-is-required" 
                            placeholder="<?php esc_html_e('Add company name', 'jobster'); ?>" 
                            value="<?php echo esc_attr($company_name); ?>" 
                            required
                        >
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label 
                                    for="pxp-company-profile-email" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Email', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="pxp-company-profile-email" 
                                    class="form-control pxp-is-required" 
                                    placeholder="company@email.com" 
                                    value="<?php echo esc_attr($company_email); ?>"
                                >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label 
                                    for="pxp-company-profile-phone" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Phone', 'jobster'); ?>
                                </label>
                                <input 
                                    type="tel" 
                                    id="pxp-company-profile-phone" 
                                    class="form-control" 
                                    placeholder="<?php esc_html_e('(+12) 345 6789', 'jobster'); ?>" 
                                    value="<?php echo esc_attr($company_phone); ?>"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label 
                            for="pxp-company-profile-website" 
                            class="form-label"
                        >
                            <?php esc_html_e('Website', 'jobster'); ?>
                        </label>
                        <input 
                            type="url" 
                            id="pxp-company-profile-website" 
                            class="form-control" 
                            placeholder="https://" 
                            value="<?php echo esc_url($company_website); ?>"
                        >
                    </div>
                    <div class="mt-1">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="pxp-company-profile-redirect" 
                                value="1" 
                                <?php checked($company_redirect, '1'); ?>
                            >
                            <label 
                                class="form-check-label" 
                                for="pxp-company-profile-redirect"
                            >
                                <?php esc_html_e('Redirect company page to this URL', 'jobster'); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                <div class="row">
                        <div class="col-sm-9">
                            <div class="mb-3">
                                <label 
                                    for="pxp-company-profile-cover-type" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Cover Type', 'jobster'); ?>
                                </label>
                                <select 
                                    id="pxp-company-profile-cover-type" 
                                    class="form-select"
                                >
                                    <?php foreach ($cover_types as $ct_key => $ct_value) { ?>
                                        <option 
                                            value="<?php echo esc_attr($ct_key); ?>" 
                                            <?php selected($cover_type, $ct_key, true); ?>
                                        >
                                            <?php echo esc_attr($ct_value); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label 
                                    for="pxp-company-profile-cover-color" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Cover Color', 'jobster'); ?>
                                </label>
                                <input 
                                    type="color" 
                                    id="pxp-company-profile-cover-color" 
                                    class="form-control pxp-color-field" 
                                    placeholder="#cccccc" 
                                    value="<?php echo esc_attr($cover_color); ?>"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="form-label">&nbsp;</div>
                    <div class="position-relative mb-3">
                        <?php $has_cover_class = '';
                        if (is_array($cover)) {
                            $has_cover_class = 'pxp-has-image';
                        } ?>
                        <div 
                            id="pxp-upload-container-cover" 
                            class="<?php echo esc_attr($has_cover_class); ?>"
                        >
                            <div class="pxp-dashboard-cover">
                                <?php if (is_array($cover)) { ?>
                                    <div 
                                        class="pxp-dashboard-cover-photo pxp-cover has-animation pxp-no-border" 
                                        style="background-image: url(<?php echo esc_url($cover[0]); ?>);" 
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
                                class="pxp-delete-photo-btn pxp-company-profile-cover-delete-btn"
                            >
                                <span class="fa fa-trash-o"></span>
                            </a>
                        </div>
                    </div>

                    <div class="position-relative mb-3">
                        <?php $has_logo_class = '';
                        if (is_array($logo)) {
                            $has_logo_class = 'pxp-has-image';
                        } ?>
                        <div 
                            id="pxp-upload-container-logo" 
                            class="<?php echo esc_attr($has_logo_class); ?>"
                        >
                            <div class="pxp-dashboard-logo">
                                <?php if (is_array($logo)) { ?>
                                    <div 
                                        class="pxp-dashboard-logo-photo pxp-cover has-animation pxp-no-border" 
                                        style="background-image: url(<?php echo esc_url($logo[0]); ?>);" 
                                        data-id="<?php echo esc_attr($logo_val); ?>"
                                    ></div>
                                <?php } else { ?>
                                    <div 
                                        class="pxp-dashboard-logo-photo pxp-cover has-animation" 
                                        data-id=""
                                    ></div>
                                <?php } ?>
                            </div>
                            <div class="pxp-dashboard-upload-logo-status"></div>
                            <a 
                                role="button" 
                                id="pxp-uploader-logo" 
                                class="pxp-dashboard-upload-logo-btn"
                            >
                                <?php if (!is_array($logo)) {
                                    esc_html_e('Upload Logo', 'jobster');
                                } ?>
                            </a>
                            <input 
                                type="hidden" 
                                name="pxp-dashboard-logo" 
                                id="pxp-dashboard-logo" 
                                value="<?php echo esc_attr($logo_val); ?>"
                            >
                            <a 
                                href="javascript:void(0);" 
                                role="button" 
                                class="pxp-delete-photo-btn pxp-company-profile-logo-delete-btn"
                            >
                                <span class="fa fa-trash-o"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pxp-is-tinymce mt-4">
                <label class="form-label">
                    <?php esc_html_e('About the company', 'jobster'); ?>
                </label>
                <?php $about_settings = array(
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
                wp_editor($about, 'pxp-company-profile-about', $about_settings); ?>
            </div>

            <div class="row">
                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3">
                        <?php $industry_tax = array( 
                            'company_industry'
                        );
                        $industry_args = array(
                            'orderby'    => 'name',
                            'order'      => 'ASC',
                            'hide_empty' => false
                        );
                        $industry_terms = get_terms(
                            $industry_tax,
                            $industry_args
                        ); ?>

                        <label 
                            for="pxp-company-profile-industry" 
                            class="form-label"
                        >
                            <?php esc_html_e('Industry', 'jobster'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <select 
                            id="pxp-company-profile-industry" 
                            class="form-select pxp-is-required" 
                        >
                            <option value="0">
                                <?php esc_html_e('Select industry', 'jobster'); ?>
                            </option>
                            <?php foreach ($industry_terms as $industry_term) { ?>
                                <option 
                                    value="<?php echo esc_attr($industry_term->term_id);?>" 
                                    <?php selected($industry_id == $industry_term->term_id); ?>
                                >
                                    <?php echo esc_html($industry_term->name); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3">
                        <label 
                            for="pxp-company-profile-founded" 
                            class="form-label"
                        >
                            <?php esc_html_e('Founded in', 'jobster'); ?>
                        </label>
                        <input 
                            type="number" 
                            id="pxp-company-profile-founded" 
                            class="form-control" 
                            placeholder="E.g. 2001" 
                            value="<?php echo esc_attr($company_founded); ?>"
                        >
                    </div>
                </div>

                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3">
                        <label 
                            for="pxp-company-profile-size" 
                            class="form-label"
                        >
                            <?php esc_html_e('Size (number of employees)', 'jobster'); ?>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-company-profile-size" 
                            class="form-control" 
                            placeholder="<?php esc_attr_e('E.g. 1 - 50', 'jobster'); ?>" 
                            value="<?php echo esc_attr($company_size); ?>"
                        >
                    </div>
                </div>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Location', 'jobster'); ?></h2>
                <div class="pxp-company-dashboard-location mb-3">
                    <div class="pxp-company-dashboard-location-select">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label 
                                        for="pxp-company-profile-location" 
                                        class="form-label"
                                    >
                                        <?php esc_html_e('Location', 'jobster'); ?>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <?php wp_dropdown_categories(array(
                                        'taxonomy' => 'company_location',
                                        'class' => 'form-select pxp-is-required',
                                        'hide_empty' => false,
                                        'id' => 'pxp-company-profile-location',
                                        'selected' => $location_id,
                                        'orderby' => 'name',
                                        'hierarchical' => true,
                                        'show_option_all' => __('Select location', 'jobster')
                                    )); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($new_location_option) { ?>
                        <div class="pxp-company-dashboard-location-new d-none">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label 
                                            for="pxp-company-profile-location-new" 
                                            class="form-label"
                                        >
                                            <?php esc_html_e('New location', 'jobster'); ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            id="pxp-company-profile-location-new" 
                                            class="form-control pxp-is-required" 
                                            placeholder="<?php esc_html_e('Add your location', 'jobster'); ?>" 
                                            required
                                        >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label 
                                            for="pxp-company-profile-location-parent" 
                                            class="form-label"
                                        >
                                            <?php esc_html_e('Parent location (optional)', 'jobster'); ?>
                                        </label>
                                        <?php wp_dropdown_categories(array(
                                            'taxonomy' => 'company_location',
                                            'class' => 'form-select',
                                            'hide_empty' => false,
                                            'id' => 'pxp-company-profile-location-parent',
                                            'orderby' => 'name',
                                            'hierarchical' => true,
                                            'show_option_all' => __('Select parent location', 'jobster')
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                            <a 
                                href="javascript:void(0);" 
                                class="btn rounded-pill pxp-subsection-cta pxp-company-location-ok-btn"
                            >
                                <span class="pxp-company-location-ok-btn-text">
                                    <?php esc_html_e('Add', 'jobster'); ?>
                                </span>
                                <span class="pxp-company-location-ok-btn-loading pxp-btn-loading">
                                    <img 
                                        src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                        class="pxp-btn-loader" 
                                        alt="..."
                                    >
                                </span>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-company-location-cancel-btn"
                            >
                                <?php esc_html_e('Cancel', 'jobster'); ?>
                            </a>
                        </div>
                        <a 
                            href="javascript:void(0);" 
                            class="btn rounded-pill pxp-subsection-cta pxp-company-dashboard-add-location-btn"
                        >
                            <?php esc_html_e('Add New Location', 'jobster'); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <?php $companies_fields_settings = get_option('jobster_companies_fields_settings');

            if (is_array($companies_fields_settings)) {
                uasort($companies_fields_settings, 'jobster_compare_position'); ?>

                <div class="mt-4 mt-lg-5">
                    <h2><?php esc_html_e('Additional Info', 'jobster'); ?></h2>

                    <div class="row">
                        <?php foreach ($companies_fields_settings as $cfs_key => $cfs_value) {
                            $cf_edit_value  = ($company_id != '') ? get_post_meta($company_id, $cfs_key, true) : ''; ?>

                            <div class="col-md-6 col-xxl-3">
                                <div class="mb-3">
                                    <label 
                                        for="<?php echo esc_attr($cfs_key); ?>" 
                                        class="form-label"
                                    >
                                        <?php echo esc_html($cfs_value['label']);
                                        if ($cfs_value['mandatory'] == 'yes') { ?>
                                            <span class="text-danger">*</span>
                                        <?php } ?>
                                    </label>
                                    <?php if ($cfs_value['type'] == 'date_field') { ?>
                                        <input 
                                            type="text" 
                                            id="<?php echo esc_attr($cfs_key); ?>" 
                                            class="form-control pxp-datepicker pxp-company-profile-custom-field <?php if ($cfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
                                            placeholder="<?php esc_html_e('YYYY-MM-DD', 'jobster'); ?>" 
                                            autocomplete="off" 
                                            data-mandatory="<?php echo esc_attr($cfs_value['mandatory']); ?>" 
                                            data-label="<?php echo esc_attr($cfs_value['label']); ?>" 
                                            value="<?php echo esc_attr($cf_edit_value); ?>"
                                        >
                                    <?php } else if ($cfs_value['type'] == 'list_field') {
                                        $cf_list = explode(',', $cfs_value['list']); ?>
                                        <select 
                                            id="<?php echo esc_attr($cfs_key); ?>" 
                                            class="form-select pxp-company-profile-custom-field <?php if ($cfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
                                            data-mandatory="<?php echo esc_attr($cfs_value['mandatory']); ?>" 
                                            data-label="<?php echo esc_attr($cfs_value['label']); ?>"
                                        >
                                            <option value="">
                                                <?php esc_html_e('Select', 'jobster'); ?>
                                            </option>
                                            <?php for ($cf_i = 0; $cf_i < count($cf_list); $cf_i++) { ?>
                                                <option 
                                                    value="<?php echo esc_attr($cf_i); ?>" 
                                                    <?php selected($cf_edit_value, $cf_i); ?>
                                                >
                                                    <?php echo esc_html($cf_list[$cf_i]); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>
                                        <input 
                                            type="text" 
                                            id="<?php echo esc_attr($cfs_key); ?>" 
                                            class="form-control pxp-company-profile-custom-field <?php if ($cfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
                                            data-mandatory="<?php echo esc_attr($cfs_value['mandatory']); ?>" 
                                            data-label="<?php echo esc_attr($cfs_value['label']); ?>" 
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
                <h2><?php esc_html_e('Social Media', 'jobster'); ?></h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-profile-facebook" 
                                class="form-label"
                            >
                                <?php esc_html_e('Facebook', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-company-profile-facebook" 
                                class="form-control" 
                                placeholder="https://" 
                                value="<?php echo esc_attr($company_facebook); ?>"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-profile-twitter" 
                                class="form-label"
                            >
                                <?php esc_html_e('Twitter', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-company-profile-twitter" 
                                class="form-control" 
                                placeholder="https://" 
                                value="<?php echo esc_attr($company_twitter); ?>"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-profile-instagram" 
                                class="form-label"
                            >
                                <?php esc_html_e('Instagram', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-company-profile-instagram" 
                                class="form-control" 
                                placeholder="https://" 
                                value="<?php echo esc_attr($company_instagram); ?>"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-profile-linkedin" 
                                class="form-label"
                            >
                                <?php esc_html_e('Linkedin', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-company-profile-linkedin" 
                                class="form-control" 
                                placeholder="https://" 
                                value="<?php echo esc_attr($company_linkedin); ?>"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Photo Gallery', 'jobster'); ?></h2>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-profile-gallery-title" 
                                class="form-label"
                            >
                                <?php esc_html_e('Title', 'jobster'); ?>
                            </label>
                            <input 
                                type="text" 
                                id="pxp-company-profile-gallery-title" 
                                class="form-control" 
                                placeholder="<?php esc_attr_e('E.g. Photos', 'jobster'); ?>" 
                                value="<?php echo esc_attr($gallery_title); ?>"
                            >
                        </div>
                    </div>
                </div>

                <div class="mt-3 mt-md-4">
                    <div class="position-relative">
                        <div id="aaiu-upload-container-gallery">
                            <div class="pxp-profile-gallery" id="pxp-company-profile-gallery">
                                <?php $gallery_ids = explode(',', $gallery);

                                foreach ($gallery_ids as $photo_id) {
                                    if ($photo_id != '') {
                                        $photo_src = wp_get_attachment_image_src($photo_id, 'pxp-icon'); ?>

                                        <div 
                                            class="pxp-profile-gallery-photo has-animation" 
                                            style="background-image: url(<?php echo esc_url($photo_src[0]); ?>);" 
                                            data-id="<?php echo esc_attr($photo_id); ?>"
                                        >
                                            <button 
                                                class="pxp-profile-gallery-delete-photo" 
                                                data-source="company"
                                            >
                                                <span class="fa fa-trash-o"></span>
                                            </button>
                                        </div>
                                    <?php } 
                                } ?>
                            </div>
                            <div class="pxp-profile-upload-gallery-status"></div>
                            <div class="clearfix"></div>
                            <a 
                                role="button" 
                                id="aaiu-uploader-gallery" 
                                class="btn rounded-pill pxp-subsection-cta pxp-browser-photos-btn"
                            >
                                <?php esc_html_e('Upload Photo', 'jobster'); ?>
                            </a>
                            <input 
                                type="hidden" 
                                name="pxp-profile-gallery-field" 
                                id="pxp-profile-gallery-field" 
                                value="<?php echo esc_attr($gallery); ?>"
                            >
                        </div>
                    </div>
                    <p class="pxp-help-block">
                        <?php esc_html_e('Maximum number of files:', 'jobster'); ?> <strong><?php echo esc_html($max_files); ?></strong>
                    </p>
                </div>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Video', 'jobster'); ?></h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-profile-video-title" 
                                class="form-label"
                            >
                                <?php esc_html_e('Title', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-company-profile-video-title" 
                                class="form-control" 
                                placeholder="<?php esc_attr_e('E.g. About Us', 'jobster'); ?>" 
                                value="<?php echo esc_attr($video_title); ?>"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-profile-video" 
                                class="form-label"
                            >
                                <?php esc_html_e('YouTube Video ID', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-company-profile-video" 
                                class="form-control" 
                                placeholder="<?php esc_attr_e('E.g. Ur1Nrz23sSI', 'jobster'); ?>" 
                                value="<?php echo esc_attr($video); ?>"
                            >
                            <small class="form-text text-muted">E.g. https://www.youtube.com/watch?v=<b>Ur1Nrz23sSI</b></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Document', 'jobster'); ?></h2>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-company-profile-doc-title" 
                                class="form-label"
                            >
                                <?php esc_html_e('Document title', 'jobster'); ?>
                            </label>
                            <input 
                                type="text" 
                                id="pxp-company-profile-doc-title" 
                                class="form-control" 
                                placeholder="<?php esc_attr_e('E.g. Brochure', 'jobster'); ?>" 
                                value="<?php echo esc_attr($doc_title); ?>"
                            >
                        </div>
                    </div>
                </div>

                <div 
                    id="pxp-upload-container-doc" 
                    class="<?php echo esc_attr($doc_class); ?>"
                >
                    <div class="pxp-company-dashboard-doc-icon">
                        <span class="fa fa-file-pdf-o"></span>
                    </div>
                    <div class="pxp-dashboard-doc w-100">
                        <?php if (!empty($doc)) { ?>
                            <div 
                                class="pxp-dashboard-doc-file" 
                                data-id="<?php echo esc_attr($doc_val); ?>"
                            >
                                <?php echo esc_html($doc_filename); ?>
                            </div>
                        <?php } else { ?>
                            <div 
                                class="pxp-dashboard-doc-file" 
                                data-id=""
                            >
                                <?php esc_html_e('No document uploaded.', 'jobster'); ?>
                            </div>
                        <?php } ?>
                        <div class="pxp-dashboard-upload-doc-status"></div>
                    </div>
                    <a 
                        role="button" 
                        id="pxp-uploader-doc" 
                        class="btn rounded-pill pxp-subsection-cta pxp-dashboard-upload-doc-btn"
                    >
                        <?php esc_html_e('Upload PDF', 'jobster'); ?>
                    </a>
                    <input 
                        type="hidden" 
                        name="pxp-dashboard-doc" 
                        id="pxp-dashboard-doc" 
                        value="<?php echo esc_attr($doc_val); ?>"
                    >
                    <div class="pxp-company-dashboard-doc-options">
                        <ul class="list-unstyled">
                            <li>
                                <a 
                                    href="<?php echo esc_url($doc); ?>" 
                                    target="_blank" 
                                    class="pxp-company-dashboard-download-doc-btn" 
                                    title="<?php esc_html_e('Download', 'jobster'); ?>"
                                >
                                    <span class="fa fa-download"></span>
                                </a>
                            </li>
                            <li>
                                <button 
                                    class="pxp-company-dashboard-delete-doc-btn" 
                                    title="<?php esc_html_e('Delete', 'jobster'); ?>"
                                >
                                    <span class="fa fa-trash-o"></span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-4 mtlg-5">
                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="pxp-company-profile-app-notify" 
                        value="1" 
                        <?php checked($app_notify, '1'); ?>
                    >
                    <label class="form-check-label" for="pxp-company-profile-app-notify">
                        <?php esc_html_e('Notify the company when a new candidate applies for a job', 'jobster'); ?>
                    </label>
                </div>
            </div>

            <div class="mt-4 mt-lg-5">
                <div class="pxp-company-profile-response"></div>
                <?php wp_nonce_field(
                    'company_profile_ajax_nonce',
                    'pxp-company-profile-security',
                    true
                ); ?>
                <a 
                    href="javascript:void(0);" 
                    class="btn rounded-pill pxp-submit-btn pxp-company-profile-update-btn"
                >
                    <span class="pxp-company-profile-update-btn-text">
                        <?php esc_html_e('Update Profile', 'jobster'); ?>
                    </span>
                    <span class="pxp-company-profile-update-btn-loading pxp-btn-loading">
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