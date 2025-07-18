<?php
/*
Template Name: Candidate Dashboard - Profile
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $current_user;
global $candidate_id;
global $post;

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

$current_user = wp_get_current_user();
$is_candidate = jobster_user_is_candidate($current_user->ID);

if ($is_candidate) {
    $candidate_id = jobster_get_candidate_by_userid($current_user->ID);

    $candidate_name = get_the_title($candidate_id);
    $candidate_title = get_post_meta($candidate_id, 'candidate_title', true);
    $candidate_email = get_post_meta($candidate_id, 'candidate_email', true);
    $candidate_phone = get_post_meta($candidate_id, 'candidate_phone', true);
    $candidate_website = get_post_meta($candidate_id, 'candidate_website', true);

    $cover_val = get_post_meta($candidate_id, 'candidate_cover', true);
    $cover = wp_get_attachment_image_src($cover_val, 'pxp-gallery');
    $cover_color = get_post_meta($candidate_id, 'candidate_cover_color', true);
    $cover_type = get_post_meta($candidate_id, 'candidate_cover_type', true);
    $cover_types = array(
        'n' => __('None', 'jobster'),
        'i' => __('Image', 'jobster'),
        'c' => __('Color', 'jobster')
    );

    $photo_val = get_post_meta($candidate_id, 'candidate_photo', true);
    $photo = wp_get_attachment_image_src($photo_val, 'pxp-thmb');

    $candidate = get_post($candidate_id);
    $about = apply_filters('the_content', $candidate->post_content);

    $industry = wp_get_post_terms($candidate_id, 'candidate_industry', true);
    $industry_id = $industry ? $industry[0]->term_id : '';

    $location = wp_get_post_terms($candidate_id, 'candidate_location', true);
    $location_id = $location ? $location[0]->term_id : '';

    $skills = wp_get_post_terms($candidate_id, 'candidate_skill', true);

    $work = get_post_meta($candidate_id, 'candidate_work', true);
    $education = get_post_meta($candidate_id, 'candidate_edu', true);

    $candidate_facebook = get_post_meta($candidate_id, 'candidate_facebook', true);
    $candidate_twitter = get_post_meta($candidate_id, 'candidate_twitter', true);
    $candidate_instagram = get_post_meta($candidate_id, 'candidate_instagram', true);
    $candidate_linkedin = get_post_meta($candidate_id, 'candidate_linkedin', true);

    $cv_val = get_post_meta($candidate_id, 'candidate_cv', true);
    $cv = wp_get_attachment_url($cv_val);
    $cv_filename = '';
    $cv_class = '';
    if (!empty($cv)) {
        $cv_filename = basename($cv);
        $cv_class = 'pxp-has-file';
    }

    $files = get_post_meta($candidate_id, 'candidate_files', true);
    $files_list = array();
    if ($files != '') {
        $files_data = json_decode(urldecode($files));
        if (isset($files_data)) {
            $files_list = $files_data->files;
        }
    }

    $gallery = get_post_meta($candidate_id, 'candidate_gallery', true);
    $gallery_title = get_post_meta($candidate_id, 'candidate_gallery_title', true);
    $candidates_settings = get_option('jobster_candidates_settings');
    $max_files =    isset($candidates_settings['jobster_candidates_gallery_max_field'])
                        && $candidates_settings['jobster_candidates_gallery_max_field'] != ''
                    ? $candidates_settings['jobster_candidates_gallery_max_field'] 
                    : 10;
    $new_location_option = isset($candidates_settings['jobster_candidates_new_location_field'])
                            && $candidates_settings['jobster_candidates_new_location_field'] == '1';

    $video = get_post_meta($candidate_id, 'candidate_video', true);
    $video_title = get_post_meta($candidate_id, 'candidate_video_title', true);

    $editor_settings = array(
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
} else {
    wp_redirect(home_url());
}

get_header('dashboard', array('bg_color' => 'pxpSecondaryColorLight'));

jobster_get_candidate_dashboard_side($candidate_id, 'profile'); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_candidate_dashboard_top($candidate_id); ?>

    <div class="pxp-dashboard-content-details">
        <h1><?php esc_html_e('Edit Profile', 'jobster'); ?></h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Edit your candidate profile page info.', 'jobster'); ?>
        </p>

        <form class="pxp-dashboard-form">
            <input 
                type="hidden" 
                id="pxp-candidate-profile-id" 
                value="<?php echo esc_attr($candidate_id); ?>"
            >
            <?php wp_nonce_field(
                'candidate_profile_ajax_nonce',
                'pxp-candidate-profile-security',
                true
            ); ?>

            <div class="row mt-4 mt-lg-5">
                <div class="col-xxl-8">
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-profile-name" 
                            class="form-label"
                        >
                            <?php esc_html_e('Name', 'jobster'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-candidate-profile-name" 
                            class="form-control pxp-is-required" 
                            placeholder="<?php esc_html_e('Add your name', 'jobster'); ?>" 
                            value="<?php echo esc_attr($candidate_name); ?>" 
                            required
                        >
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-profile-email" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Email', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="pxp-candidate-profile-email" 
                                    class="form-control pxp-is-required" 
                                    placeholder="candidate@email.com" 
                                    value="<?php echo esc_attr($candidate_email); ?>"
                                >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-profile-phone" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Phone', 'jobster'); ?>
                                </label>
                                <input 
                                    type="tel" 
                                    id="pxp-candidate-profile-phone" 
                                    class="form-control" 
                                    placeholder="<?php esc_html_e('(+12) 345 6789', 'jobster'); ?>" 
                                    value="<?php echo esc_attr($candidate_phone); ?>"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-profile-title" 
                            class="form-label"
                        >
                            <?php esc_html_e('Title', 'jobster'); ?>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-candidate-profile-title" 
                            class="form-control" 
                            placeholder="<?php esc_attr_e('Add your title', 'jobster'); ?>" 
                            value="<?php echo esc_attr($candidate_title); ?>"
                        >
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-profile-website" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Website', 'jobster'); ?>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-candidate-profile-website" 
                                    class="form-control" 
                                    placeholder="<?php esc_attr_e('Add your website URL', 'jobster'); ?>" 
                                    value="<?php echo esc_attr($candidate_website); ?>"
                                >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <?php $industry_tax = array( 
                                    'candidate_industry'
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
                                    for="pxp-candidate-profile-industry" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Industry', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <select 
                                    id="pxp-candidate-profile-industry" 
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
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-profile-cover-type" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Cover Type', 'jobster'); ?>
                                </label>
                                <select 
                                    id="pxp-candidate-profile-cover-type" 
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
                                    for="pxp-candidate-profile-cover-color" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Cover Color', 'jobster'); ?>
                                </label>
                                <input 
                                    type="color" 
                                    id="pxp-candidate-profile-cover-color" 
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
                                class="pxp-delete-photo-btn pxp-candidate-profile-cover-delete-btn"
                            >
                                <span class="fa fa-trash-o"></span>
                            </a>
                        </div>
                    </div>
                    <div class="position-relative mb-3">
                        <?php $has_logo_class = '';
                        if (is_array($photo)) {
                            $has_logo_class = 'pxp-has-image';
                        } ?>
                        <div 
                            id="pxp-upload-container-logo" 
                            class="<?php echo esc_attr($has_logo_class); ?>"
                        >
                            <div class="pxp-dashboard-logo">
                                <?php if (is_array($photo)) { ?>
                                    <div 
                                        class="pxp-dashboard-logo-photo pxp-cover has-animation pxp-no-border" 
                                        style="background-image: url(<?php echo esc_url($photo[0]); ?>);" 
                                        data-id="<?php echo esc_attr($photo_val); ?>"
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
                                <?php if (!is_array($photo)) {
                                    esc_html_e('Upload Photo', 'jobster');
                                } ?>
                            </a>
                            <input 
                                type="hidden" 
                                name="pxp-dashboard-logo" 
                                id="pxp-dashboard-logo" 
                                value="<?php echo esc_attr($photo_val); ?>"
                            >
                            <a 
                                href="javascript:void(0);" 
                                role="button" 
                                class="pxp-delete-photo-btn pxp-candidate-profile-logo-delete-btn"
                            >
                                <span class="fa fa-trash-o"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pxp-is-tinymce">
                <label class="form-label">
                    <?php esc_html_e('About', 'jobster'); ?>
                </label>
                <?php wp_editor($about, 'pxp-candidate-profile-about', $editor_settings); ?>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Location', 'jobster'); ?></h2>
                <div class="pxp-candidate-dashboard-location mb-3">
                    <div class="pxp-candidate-dashboard-location-select">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label 
                                        for="pxp-candidate-profile-location" 
                                        class="form-label"
                                    >
                                        <?php esc_html_e('Location', 'jobster'); ?>
                                        <span class="text-danger">*</span>
                                    </label>
                                    <?php wp_dropdown_categories(array(
                                        'taxonomy' => 'candidate_location',
                                        'class' => 'form-select pxp-is-required',
                                        'hide_empty' => false,
                                        'id' => 'pxp-candidate-profile-location',
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
                        <div class="pxp-candidate-dashboard-location-new d-none">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label 
                                            for="pxp-candidate-profile-location-new" 
                                            class="form-label"
                                        >
                                            <?php esc_html_e('New location', 'jobster'); ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            id="pxp-candidate-profile-location-new" 
                                            class="form-control pxp-is-required" 
                                            placeholder="<?php esc_html_e('Add your location', 'jobster'); ?>" 
                                            required
                                        >
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label 
                                            for="pxp-candidate-profile-location-parent" 
                                            class="form-label"
                                        >
                                            <?php esc_html_e('Parent location (optional)', 'jobster'); ?>
                                        </label>
                                        <?php wp_dropdown_categories(array(
                                            'taxonomy' => 'candidate_location',
                                            'class' => 'form-select',
                                            'hide_empty' => false,
                                            'id' => 'pxp-candidate-profile-location-parent',
                                            'orderby' => 'name',
                                            'hierarchical' => true,
                                            'show_option_all' => __('Select parent location', 'jobster')
                                        )); ?>
                                    </div>
                                </div>
                            </div>
                            <a 
                                href="javascript:void(0);" 
                                class="btn rounded-pill pxp-subsection-cta pxp-candidate-location-ok-btn"
                            >
                                <span class="pxp-candidate-location-ok-btn-text">
                                    <?php esc_html_e('Add', 'jobster'); ?>
                                </span>
                                <span class="pxp-candidate-location-ok-btn-loading pxp-btn-loading">
                                    <img 
                                        src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                        class="pxp-btn-loader" 
                                        alt="..."
                                    >
                                </span>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-candidate-location-cancel-btn"
                            >
                                <?php esc_html_e('Cancel', 'jobster'); ?>
                            </a>
                        </div>
                        <a 
                            href="javascript:void(0);" 
                            class="btn rounded-pill pxp-subsection-cta pxp-candidate-dashboard-add-location-btn"
                        >
                            <?php esc_html_e('Add New Location', 'jobster'); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <?php $candidates_fields_settings = get_option('jobster_candidates_fields_settings');

            if (is_array($candidates_fields_settings)) {
                uasort($candidates_fields_settings, 'jobster_compare_position'); ?>

                <div class="mt-4 mt-lg-5">
                    <h2><?php esc_html_e('Additional Info', 'jobster'); ?></h2>

                    <div class="row">
                        <?php foreach ($candidates_fields_settings as $cfs_key => $cfs_value) {
                            $cf_edit_value  = ($candidate_id != '') ? get_post_meta($candidate_id, $cfs_key, true) : ''; ?>

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
                                            class="form-control pxp-datepicker pxp-candidate-profile-custom-field <?php if ($cfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
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
                                            class="form-select pxp-candidate-profile-custom-field <?php if ($cfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
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
                                            class="form-control pxp-candidate-profile-custom-field <?php if ($cfs_value['mandatory'] == 'yes') echo esc_attr('pxp-is-required'); ?>" 
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
                <h2><?php esc_html_e('Skills', 'jobster'); ?></h2>
                <div class="pxp-candidate-dashboard-skills mb-3">
                    <ul class="list-unstyled">
                        <?php if ($skills) { 
                            foreach ($skills as $skill) { ?>
                                <li data-id="<?php echo esc_attr($skill->term_id); ?>">
                                    <?php echo esc_html($skill->name); ?>
                                    <span class="fa fa-trash-o"></span>
                                </li>
                            <?php }
                        } ?>
                    </ul>
                </div>
                <div class="input-group mb-3">
                    <input 
                        type="text" 
                        class="form-control" 
                        id="pxp-candidate-profile-skills" 
                        placeholder="<?php esc_html_e('Skill', 'jobster'); ?>"
                    >
                    <a 
                        href="javascript:void(0);" 
                        role="button" 
                        class="btn pxp-candidate-dashboard-add-skill-btn"
                    >
                        <?php esc_html_e('Add Skill', 'jobster'); ?>
                    </a>
                </div>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Work Experience', 'jobster'); ?></h2>

                <div class="table-responsive">
                    <table class="table align-middle pxp-candidate-dashboard-work-list">
                        <tbody>
                            <?php 
                            $work_list = array();

                            if ($work != '') {
                                $work_data = json_decode(urldecode($work));

                                if (isset($work_data)) {
                                    $work_list = $work_data->works;
                                }
                            }
                            if (count($work_list) > 0) {
                                foreach ($work_list as $work_item) { ?>
                                    <tr>
                                        <td style="width: 30%;">
                                            <div class="pxp-candidate-dashboard-work-cell-title">
                                                <?php echo esc_html($work_item->title); ?>
                                            </div>
                                        </td>
                                        <td style="width: 25%;">
                                            <div class="pxp-candidate-dashboard-work-cell-company">
                                                <?php echo esc_html($work_item->company); ?>
                                            </div>
                                        </td>
                                        <td style="width: 25%;">
                                            <div class="pxp-candidate-dashboard-work-cell-time">
                                                <?php echo esc_html($work_item->period); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pxp-dashboard-table-options">
                                                <ul 
                                                    class="list-unstyled" 
                                                    data-title="<?php echo esc_attr(urlencode($work_item->title)); ?>" 
                                                    data-company="<?php echo esc_attr(urlencode($work_item->company)); ?>" 
                                                    data-period="<?php echo esc_attr(urlencode($work_item->period)); ?>" 
                                                    data-description="<?php echo esc_attr(urlencode($work_item->description)); ?>"
                                                >
                                                    <li>
                                                        <button 
                                                            class="pxp-candidate-dashboard-edit-work-btn" 
                                                            title="<?php esc_attr_e('Edit', 'jobster'); ?>"
                                                        >
                                                            <span class="fa fa-pencil"></span>
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button 
                                                            class="pxp-candidate-dashboard-delete-work-btn" 
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
                    id="pxp-candidate-dashboard-work" 
                    name="pxp-candidate-dashboard-work" 
                    value="<?php echo esc_attr($work); ?>"
                >
                <div class="pxp-candidate-dashboard-work-form mt-3 mt-lg-4 d-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-dashboard-work-title" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Job title', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-candidate-dashboard-work-title" 
                                    class="form-control pxp-is-required" 
                                    placeholder="<?php esc_attr_e('E.g. Web Designer', 'jobster'); ?>"
                                >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-dashboard-work-company" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Company', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-candidate-dashboard-work-company" 
                                    class="form-control pxp-is-required" 
                                    placeholder="<?php esc_attr_e('Company name', 'jobster'); ?>"
                                >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-dashboard-work-time" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Time period', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-candidate-dashboard-work-time" 
                                    class="form-control pxp-is-required" 
                                    placeholder="E.g. 2005 - 2013"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-dashboard-work-about" 
                            class="form-label"
                        >
                            <?php esc_html_e('Description', 'jobster'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <?php wp_editor('', 'pxp-candidate-dashboard-work-about', $editor_settings); ?>
                    </div>

                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-subsection-cta pxp-candidate-dashboard-ok-work-btn"
                    >
                        <?php esc_html_e('Add', 'jobster'); ?>
                    </a>
                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-candidate-dashboard-cancel-work-btn"
                    >
                        <?php esc_html_e('Cancel', 'jobster'); ?>
                    </a>
                </div>

                <div class="pxp-candidate-dashboard-edit-work-form">
                </div>

                <a 
                    href="javascript:void(0);" 
                    class="btn mt-3 mt-lg-4 rounded-pill pxp-subsection-cta pxp-candidate-dashboard-add-work-btn"
                >
                    <?php esc_html_e('Add Experience', 'jobster'); ?>
                </a>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Education & Training', 'jobster'); ?></h2>

                <div class="table-responsive">
                    <table class="table align-middle pxp-candidate-dashboard-edu-list">
                        <tbody>
                            <?php 
                            $edu_list = array();

                            if ($education != '') {
                                $edu_data = json_decode(urldecode($education));

                                if (isset($edu_data)) {
                                    $edu_list = $edu_data->edus;
                                }
                            }
                            if (count($edu_list) > 0) {
                                foreach ($edu_list as $edu_item) { ?>
                                    <tr>
                                        <td style="width: 30%;">
                                            <div class="pxp-candidate-dashboard-edu-cell-title">
                                                <?php echo esc_html($edu_item->title); ?>
                                            </div>
                                        </td>
                                        <td style="width: 25%;">
                                            <div class="pxp-candidate-dashboard-edu-cell-school">
                                                <?php echo esc_html($edu_item->school); ?>
                                            </div>
                                        </td>
                                        <td style="width: 25%;">
                                            <div class="pxp-candidate-dashboard-edu-cell-time">
                                                <?php echo esc_html($edu_item->period); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pxp-dashboard-table-options">
                                                <ul 
                                                    class="list-unstyled" 
                                                    data-title="<?php echo esc_attr($edu_item->title); ?>" 
                                                    data-school="<?php echo esc_attr($edu_item->school); ?>" 
                                                    data-period="<?php echo esc_attr($edu_item->period); ?>" 
                                                    data-description="<?php echo esc_attr($edu_item->description); ?>"
                                                >
                                                    <li>
                                                        <button 
                                                            class="pxp-candidate-dashboard-edit-edu-btn" 
                                                            title="<?php esc_attr_e('Edit', 'jobster'); ?>"
                                                        >
                                                            <span class="fa fa-pencil"></span>
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button 
                                                            class="pxp-candidate-dashboard-delete-edu-btn" 
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
                    id="pxp-candidate-dashboard-edu" 
                    name="pxp-candidate-dashboard-edu" 
                    value="<?php echo esc_attr($education); ?>"
                >
                <div class="pxp-candidate-dashboard-edu-form mt-3 mt-lg-4 d-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-dashboard-edu-title" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Specialization/Course of study', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-candidate-dashboard-edu-title" 
                                    class="form-control pxp-is-required" 
                                    placeholder="<?php esc_attr_e('E.g. Architecure', 'jobster'); ?>"
                                >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-dashboard-edu-school" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Institution', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-candidate-dashboard-edu-school" 
                                    class="form-control pxp-is-required" 
                                    placeholder="<?php esc_attr_e('Institution name', 'jobster'); ?>"
                                >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-dashboard-edu-time" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Time period', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-candidate-dashboard-edu-time" 
                                    class="form-control pxp-is-required" 
                                    placeholder="E.g. 2005 - 2013"
                                >
                            </div>
                        </div>
                    </div>
    
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-dashboard-edu-about" 
                            class="form-label"
                        >
                            <?php esc_html_e('Description', 'jobster'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <textarea 
                            class="form-control pxp-smaller pxp-is-required" 
                            id="pxp-candidate-dashboard-edu-about" 
                            placeholder="<?php esc_attr_e('Type a short description...', 'jobster'); ?>"
                        ></textarea>
                    </div>

                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-subsection-cta pxp-candidate-dashboard-ok-edu-btn"
                    >
                        <?php esc_html_e('Add', 'jobster'); ?>
                    </a>
                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-candidate-dashboard-cancel-edu-btn"
                    >
                        <?php esc_html_e('Cancel', 'jobster'); ?>
                    </a>
                </div>

                <div class="pxp-candidate-dashboard-edit-edu-form">
                </div>

                <a 
                    href="javascript:void(0);" 
                    class="btn mt-3 mt-lg-4 rounded-pill pxp-subsection-cta pxp-candidate-dashboard-add-edu-btn"
                >
                    <?php esc_html_e('Add Education', 'jobster'); ?>
                </a>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Social Media', 'jobster'); ?></h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-candidate-profile-facebook" 
                                class="form-label"
                            >
                                <?php esc_html_e('Facebook', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-candidate-profile-facebook" 
                                class="form-control" 
                                placeholder="https://" 
                                value="<?php echo esc_attr($candidate_facebook); ?>"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-candidate-profile-twitter" 
                                class="form-label"
                            >
                                <?php esc_html_e('Twitter', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-candidate-profile-twitter" 
                                class="form-control" 
                                placeholder="https://" 
                                value="<?php echo esc_attr($candidate_twitter); ?>"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-candidate-profile-instagram" 
                                class="form-label"
                            >
                                <?php esc_html_e('Instagram', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-candidate-profile-instagram" 
                                class="form-control" 
                                placeholder="https://" 
                                value="<?php echo esc_attr($candidate_instagram); ?>"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-candidate-profile-linkedin" 
                                class="form-label"
                            >
                                <?php esc_html_e('Linkedin', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-candidate-profile-linkedin" 
                                class="form-control" 
                                placeholder="https://" 
                                value="<?php echo esc_attr($candidate_linkedin); ?>"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <?php $resume_field =   isset($candidates_settings['jobster_candidate_resume_field'])
                                    ? $candidates_settings['jobster_candidate_resume_field'] 
                                    : 'required';
            if ($resume_field != 'disabled') { ?>
                <div class="mt-4 mt-lg-5">
                    <h2><?php esc_html_e('Resume', 'jobster'); ?></h2>

                    <div 
                        id="pxp-upload-container-cv" 
                        class="<?php echo esc_attr($cv_class); ?>"
                    >
                        <div class="pxp-candidate-dashboard-cv-icon">
                            <span class="fa fa-file-pdf-o"></span>
                        </div>
                        <div class="pxp-dashboard-cv w-100">
                            <?php if (!empty($cv)) { ?>
                                <div 
                                    class="pxp-dashboard-cv-file" 
                                    data-id="<?php echo esc_attr($cv_val); ?>"
                                >
                                    <?php echo esc_html($cv_filename); ?>
                                </div>
                            <?php } else { ?>
                                <div 
                                    class="pxp-dashboard-cv-file" 
                                    data-id=""
                                >
                                    <?php esc_html_e('No resume uploaded.', 'jobster'); ?>
                                </div>
                            <?php } ?>
                            <div class="pxp-dashboard-upload-cv-status"></div>
                        </div>
                        <a 
                            role="button" 
                            id="pxp-uploader-cv" 
                            class="btn rounded-pill pxp-subsection-cta pxp-dashboard-upload-cv-btn"
                        >
                            <?php esc_html_e('Upload PDF', 'jobster'); ?>
                        </a>
                        <input 
                            type="hidden" 
                            name="pxp-dashboard-cv" 
                            id="pxp-dashboard-cv" 
                            value="<?php echo esc_attr($cv_val); ?>"
                        >
                        <div class="pxp-candidate-dashboard-cv-options">
                            <ul class="list-unstyled">
                                <li>
                                    <a 
                                        href="<?php echo esc_url($cv); ?>" 
                                        target="_blank" 
                                        class="pxp-candidate-dashboard-download-cv-btn" 
                                        title="<?php esc_html_e('Download', 'jobster'); ?>"
                                    >
                                        <span class="fa fa-download"></span>
                                    </a>
                                </li>
                                <li>
                                    <button 
                                        class="pxp-candidate-dashboard-delete-cv-btn" 
                                        title="<?php esc_html_e('Delete', 'jobster'); ?>"
                                    >
                                        <span class="fa fa-trash-o"></span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Additional Files', 'jobster'); ?></h2>

                <?php if (count($files_list) > 0) { ?>
                    <div class="table-responsive">
                        <table class="table align-middle pxp-candidate-dashboard-files-list">
                            <tbody>
                                <?php foreach ($files_list as $files_item) { ?>
                                    <tr>
                                        <td style="width: 80%;">
                                            <div class="pxp-candidate-dashboard-files-cell-name">
                                                <?php echo esc_html($files_item->name); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pxp-dashboard-table-options">
                                                <ul 
                                                    class="list-unstyled" 
                                                    data-name="<?php echo esc_attr($files_item->name); ?>" 
                                                    data-id="<?php echo esc_attr($files_item->id); ?>" 
                                                    data-url="<?php echo esc_attr($files_item->url); ?>"
                                                >
                                                    <li>
                                                        <a
                                                            href="<?php echo esc_url($files_item->url); ?>"  
                                                            class="pxp-candidate-dashboard-download-file-btn" 
                                                            title="<?php esc_attr_e('Download', 'jobster'); ?>"
                                                        >
                                                            <span class="fa fa-download"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button 
                                                            class="pxp-candidate-dashboard-delete-file-btn" 
                                                            title="<?php esc_attr_e('Delete', 'jobster'); ?>"
                                                        >
                                                            <span class="fa fa-trash-o"></span>
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <input 
                    type="hidden" 
                    id="pxp-candidate-dashboard-files" 
                    name="pxp-candidate-dashboard-files" 
                    value="<?php echo esc_attr($files); ?>"
                >
                <div class="pxp-candidate-dashboard-file-form mt-3 mt-lg-4 d-none">
                    <div class="row">
                        <div class="col col-md-6">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-dashboard-file-name" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Name', 'jobster'); ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="pxp-candidate-dashboard-file-name" 
                                    class="form-control pxp-is-required" 
                                    placeholder="<?php esc_attr_e('Add file name', 'jobster'); ?>"
                                >
                            </div>
                        </div>
                        <div class="col col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <?php esc_html_e('File upload', 'jobster'); ?>(PDF)
                                    <span class="text-danger">*</span>
                                </label>
                                <div id="pxp-upload-container-file">
                                    <div class="pxp-dashboard-upload-file w-100">
                                        <div class="pxp-dashboard-upload-file-status"></div>
                                    </div>
                                    <a 
                                        role="button" 
                                        id="pxp-uploader-file" 
                                        class="btn rounded-pill pxp-subsection-cta pxp-dashboard-upload-file-btn"
                                    >
                                        <?php esc_html_e('Upload File', 'jobster'); ?>
                                    </a>
                                    <div class="pxp-dashboard-upload-file-placeholder d-inline-block ms-3"></div>
                                    <input 
                                        type="hidden" 
                                        id="pxp-candidate-dashboard-file-id"
                                    >
                                    <input 
                                        type="hidden" 
                                        id="pxp-candidate-dashboard-file-url"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-subsection-cta pxp-candidate-dashboard-ok-file-btn"
                    >
                        <?php esc_html_e('Add', 'jobster'); ?>
                    </a>
                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-candidate-dashboard-cancel-file-btn"
                    >
                        <?php esc_html_e('Cancel', 'jobster'); ?>
                    </a>
                </div>

                <a 
                    href="javascript:void(0);" 
                    class="btn mt-3 mt-lg-4 rounded-pill pxp-subsection-cta pxp-candidate-dashboard-add-file-btn"
                >
                    <?php esc_html_e('Add File', 'jobster'); ?>
                </a>
            </div>

            <div class="mt-4 mt-lg-5">
                <h2><?php esc_html_e('Gallery/Portfolio', 'jobster'); ?></h2>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-candidate-profile-gallery-title" 
                                class="form-label"
                            >
                                <?php esc_html_e('Title', 'jobster'); ?>
                            </label>
                            <input 
                                type="text" 
                                id="pxp-candidate-profile-gallery-title" 
                                class="form-control" 
                                placeholder="<?php esc_attr_e('E.g. My Work', 'jobster'); ?>" 
                                value="<?php echo esc_attr($gallery_title); ?>"
                            >
                        </div>
                    </div>
                </div>

                <div class="mt-3 mt-md-4">
                    <div class="position-relative">
                        <div id="aaiu-upload-container-gallery">
                            <div class="pxp-profile-gallery" id="pxp-candidate-profile-gallery">
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
                                                data-source="candidate"
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
                                for="pxp-candidate-profile-video-title" 
                                class="form-label"
                            >
                                <?php esc_html_e('Title', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-candidate-profile-video-title" 
                                class="form-control" 
                                placeholder="<?php esc_attr_e('E.g. About Me', 'jobster'); ?>" 
                                value="<?php echo esc_attr($video_title); ?>"
                            >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label 
                                for="pxp-candidate-profile-video" 
                                class="form-label"
                            >
                                <?php esc_html_e('YouTube Video ID', 'jobster'); ?>
                            </label>
                            <input 
                                type="url" 
                                id="pxp-candidate-profile-video" 
                                class="form-control" 
                                placeholder="<?php esc_attr_e('E.g. Ur1Nrz23sSI', 'jobster'); ?>" 
                                value="<?php echo esc_attr($video); ?>"
                            >
                            <small class="form-text text-muted">E.g. https://www.youtube.com/watch?v=<b>Ur1Nrz23sSI</b></small>
                        </div>
                    </div>
                </div>
            </div>

            <?php $jobs_settings = get_option('jobster_jobs_settings');
            $alerts =   isset($jobs_settings['jobster_job_alert_field'])
                            && $jobs_settings['jobster_job_alert_field'] == '1'; 
            
            if ($alerts) {
                $candidate_alerts = get_post_meta($candidate_id, 'candidate_job_alerts', true); ?>

                <div class="mt-4 mt-lg-5">
                    <h2><?php esc_html_e('Job Alerts', 'jobster'); ?></h2>

                    <div class="mb-3">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="pxp-candidate-profile-alerts" 
                                value="1" 
                                <?php checked($candidate_alerts, '1'); ?>
                            >
                            <label class="form-check-label" for="pxp-candidate-profile-alerts">
                                <?php esc_html_e('Notify me when New Jobs are posted', 'jobster'); ?>
                            </label>
                        </div>
                    </div>
                    <strong class="mb-3">
                        <?php esc_html_e('Receive job alerts from:', 'jobster'); ?>
                    </strong>
                    <div class="row">
                        <div class="col-md-6 col-xxl-3">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-profile-alerts-location" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Location', 'jobster'); ?>
                                </label>
                                <select 
                                    class="form-select" 
                                    id="pxp-candidate-profile-alerts-location" 
                                    multiple
                                >
                                    <?php $location_data = get_post_meta(
                                        $candidate_id,
                                        'candidate_job_alerts_location',
                                        true
                                    );
                                    var_dump($location_data);
                                    $loc_terms = get_terms(
                                        array(
                                            'taxonomy' => 'job_location',
                                            'orderby' => 'name',
                                            'hierarchical' => true,
                                            'hide_empty' => false,
                                        )
                                    );

                                    $top_level_locations = array();
                                    $children_locations  = array();
                                    foreach ($loc_terms as $location) {
                                        if (empty($location->parent)) {
                                            $top_level_locations[] = $location;
                                        } else {
                                            $children_locations[$location->parent][] = $location;
                                        }
                                    }
                                    $locations = array();
                                    foreach ($top_level_locations as $top_location) {
                                        $locations[$top_location->term_id . '*'] = $top_location->name;
                                        if (array_key_exists($top_location->term_id, $children_locations)) {
                                            foreach ($children_locations[$top_location->term_id] as $child_location) {
                                                $locations[$child_location->term_id . '*'] = '&nbsp;&nbsp;&nbsp;' . $child_location->name;
                                            }
                                        }
                                    }
                                    foreach ($locations as $loc_key => $loc_value) { ?>
                                        <option 
                                            value="<?php echo esc_attr($loc_key); ?>" 
                                            <?php selected(
                                                is_array($location_data) 
                                                    && in_array($loc_key, $location_data),
                                                true
                                            ); ?> 
                                        >
                                            <?php echo esc_attr($loc_value); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xxl-3">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-profile-alerts-category" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Category', 'jobster'); ?>
                                </label>
                                <select 
                                    class="form-select" 
                                    id="pxp-candidate-profile-alerts-category" 
                                    multiple
                                >
                                    <?php $category_data = get_post_meta(
                                        $candidate_id,
                                        'candidate_job_alerts_category',
                                        true
                                    );
                                    $category_terms = get_terms(
                                        array('job_category'),
                                        array(
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                            'hide_empty' => false
                                        )
                                    );
                                    foreach ($category_terms as $category_term) { ?>
                                        <option 
                                            value="<?php echo esc_attr($category_term->term_id); ?>" 
                                            <?php selected(
                                                is_array($category_data) 
                                                    && in_array($category_term->term_id, $category_data),
                                                true
                                            ); ?>
                                        >
                                            <?php echo esc_html($category_term->name); ?>
                                        </option>';
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xxl-3">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-profile-alerts-type" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Type of Employment', 'jobster'); ?>
                                </label>
                                <select 
                                    class="form-select" 
                                    id="pxp-candidate-profile-alerts-type" 
                                    multiple
                                >
                                    <?php $type_data = get_post_meta(
                                        $candidate_id,
                                        'candidate_job_alerts_type',
                                        true
                                    );
                                    $type_terms = get_terms(
                                        array('job_type'),
                                        array(
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                            'hide_empty' => false
                                        )
                                    );
                                    foreach ($type_terms as $type_term) { ?>
                                        <option 
                                            value="<?php echo esc_attr($type_term->term_id); ?>" 
                                            <?php selected(
                                                is_array($type_data) 
                                                    && in_array($type_term->term_id, $type_data),
                                                true
                                            ); ?>
                                        >
                                            <?php echo esc_attr($type_term->name); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xxl-3">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-profile-alerts-level" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Experience Level', 'jobster'); ?>
                                </label>
                                <select 
                                    class="form-select" 
                                    id="pxp-candidate-profile-alerts-level" 
                                    multiple
                                >
                                    <?php $level_data = get_post_meta(
                                        $candidate_id,
                                        'candidate_job_alerts_level',
                                        true
                                    );
                                    $level_terms = get_terms(
                                        array('job_level'),
                                        array(
                                            'orderby'    => 'name',
                                            'order'      => 'ASC',
                                            'hide_empty' => false
                                        )
                                    );
                                    foreach ($level_terms as $level_term) { ?>
                                        <option 
                                            value="<?php echo esc_attr($level_term->term_id); ?>" 
                                            <?php selected(
                                                is_array($level_data) 
                                                    && in_array($level_term->term_id, $level_data),
                                                true
                                            ); ?>
                                        >
                                            <?php echo esc_attr($level_term->name); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="mt-4 mt-lg-5">
                <div class="pxp-candidate-profile-response"></div>
                <a 
                    href="javascript:void(0);" 
                    class="btn rounded-pill pxp-submit-btn pxp-candidate-profile-update-btn"
                >
                    <span class="pxp-candidate-profile-update-btn-text">
                        <?php esc_html_e('Update Profile', 'jobster'); ?>
                    </span>
                    <span class="pxp-candidate-profile-update-btn-loading pxp-btn-loading">
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