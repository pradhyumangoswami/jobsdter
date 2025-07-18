<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

require_once 'page-meta-header-animated-cards.php';
require_once 'page-meta-header-image-rotator.php';
require_once 'page-meta-header-illustration.php';
require_once 'page-meta-header-boxed.php';
require_once 'page-meta-header-image-bg.php';
require_once 'page-meta-header-top-search.php';
require_once 'page-meta-header-image-card.php';
require_once 'page-meta-header-half-image.php';
require_once 'page-meta-header-center-image.php';
require_once 'page-meta-header-image-pills.php';
require_once 'page-meta-header-right-image.php';

if (!function_exists('jobster_add_page_metaboxes')): 
    function jobster_add_page_metaboxes() {
        add_meta_box(
            'pxp-page-header-section',
            __('Header', 'jobster'),
            'jobster_page_header_render',
            'page',
            'normal',
            'default'
        );
        add_meta_box(
            'pxp-page-settings-section',
            __('Page Settings', 'jobster'),
            'jobster_page_settings_render',
            'page',
            'normal',
            'default'
        );
        add_meta_box(
            'pxp-jobs-page-settings-section',
            __('Jobs Page Settings', 'jobster'),
            'jobster_jobs_page_settings_render',
            'page',
            'normal',
            'default'
        );
        add_meta_box(
            'pxp-job-categories-page-settings-section',
            __('Job Categories Page Settings', 'jobster'),
            'jobster_job_categories_page_settings_render',
            'page',
            'normal',
            'default'
        );
        add_meta_box(
            'pxp-companies-page-settings-section',
            __('Companies Page Settings', 'jobster'),
            'jobster_companies_page_settings_render',
            'page',
            'normal',
            'default'
        );
        add_meta_box(
            'pxp-candidates-page-settings-section',
            __('Candidates Page Settings', 'jobster'),
            'jobster_candidates_page_settings_render',
            'page',
            'normal',
            'default'
        );
    }
endif;
add_action('add_meta_boxes', 'jobster_add_page_metaboxes');

if (!function_exists('jobster_page_header_render')): 
    function jobster_page_header_render($post) {
        wp_nonce_field('jobster_page', 'page_noncename');

        if (isset($_GET['post'])) {
            $post_id = sanitize_text_field($_GET['post']);
        } else if(isset($_POST['post_ID'])) {
            $post_id = sanitize_text_field($_POST['post_ID']);
        }

        /* ---------------------------------------------------
            Header types select box
        --------------------------------------------------- */
        $header_types_value = '';

        if (isset($post_id)) {
            $header_types_value = get_post_meta(
                $post_id,
                'page_header_type',
                true
            );
        }

        $header_types = array(
            'none'           => __('None', 'jobster'),
            'animated_cards' => __('Animated Cards', 'jobster'),
            'image_rotator'  => __('Image Rotator', 'jobster'),
            'illustration'   => __('Illustration', 'jobster'),
            'boxed'          => __('Boxed Animation', 'jobster'),
            'image_bg'       => __('Image Background', 'jobster'),
            'top_search'     => __('Top Search', 'jobster'),
            'image_card'     => __('Image Card', 'jobster'),
            'half_image'     => __('Half Image', 'jobster'),
            'center_image'   => __('Center Image', 'jobster'),
            'image_pills'    => __('Image Pills', 'jobster'),
            'right_image'    => __('Right Image', 'jobster')
        ); ?>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100%" valign="top" align="left">
                    <div class="form-field pxp-is-custom">
                        <label>
                            <?php esc_html_e('Header Type', 'jobster'); ?>
                        </label>
                        <div class="pxp-layout-radio-container">
                            <?php foreach ($header_types as $key => $value) { ?>
                                <div 
                                    class="pxp-layout-radio pxp-layout-radio-<?php echo esc_attr($key); ?>"
                                >
                                    <label>
                                        <input 
                                            type="radio" 
                                            name="page_header_type" 
                                            value="<?php echo esc_attr($key); ?>" 
                                            <?php checked(
                                                isset($header_types_value)
                                                && $header_types_value == $key
                                            ); ?>
                                        >
                                        <span>
                                            <span class="fa fa-check"></span>
                                        </span>
                                    </label>
                                    <div class="pxp-layout-radio-title">
                                        <?php echo esc_html($value); ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <?php 
        jobster_get_animated_cards_header_meta($post->ID, $header_types_value);
        jobster_get_image_rotator_header_meta($post->ID, $header_types_value);
        jobster_get_illustration_header_meta($post->ID, $header_types_value);
        jobster_get_boxed_header_meta($post->ID, $header_types_value);
        jobster_get_image_bg_header_meta($post->ID, $header_types_value);
        jobster_get_top_search_header_meta($post->ID, $header_types_value);
        jobster_get_image_card_header_meta($post->ID, $header_types_value);
        jobster_get_half_image_header_meta($post->ID, $header_types_value);
        jobster_get_center_image_header_meta($post->ID, $header_types_value);
        jobster_get_image_pills_header_meta($post->ID, $header_types_value);
        jobster_get_right_image_header_meta($post->ID, $header_types_value);
    }
endif;

if (!function_exists('jobster_page_settings_render')): 
    function jobster_page_settings_render($post) {
        wp_nonce_field('jobster_page', 'page_noncename'); ?>

        <div class="pxp-d-flex">
            <div style="width: 50%;">
                <div class="form-field pxp-is-custom">
                    <label for="page_settings_hide_title_field">
                        <input 
                            type="hidden" 
                            name="page_settings_hide_title" 
                            value="0"
                        >
                        <input 
                            type="checkbox" 
                            name="page_settings_hide_title" 
                            id="page_settings_hide_title_field" 
                            value="1" 
                            <?php checked(
                                get_post_meta(
                                    $post->ID, 
                                    'page_settings_hide_title', 
                                    true
                                ),
                                true,
                                true
                            ); ?>
                        >
                        <?php esc_html_e('Hide page title', 'jobster'); ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="pxp-d-flex">
            <div style="width: 50%;">
                <div class="form-field pxp-is-custom">
                    <label for="page_settings_subtitle">
                        <?php esc_html_e('Page subtitle', 'jobster'); ?>
                    </label>
                    <input 
                        name="page_settings_subtitle" 
                        id="page_settings_subtitle" 
                        type="text" 
                        value="<?php echo esc_attr(
                            get_post_meta($post->ID, 'page_settings_subtitle', true)
                        ); ?>"
                    >
                </div>
            </div>
            <div style="width: 25%;">
                <div class="form-field pxp-is-custom">
                    <label for="page_settings_title_align">
                        <?php esc_html_e('Title/Subtitle align', 'jobster'); ?>
                    </label>
                    <select 
                        name="page_settings_title_align" 
                        id="page_settings_title_align"
                    >
                        <option 
                            value="left" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'page_settings_title_align', true
                                ),
                                'left'
                            ) ?>
                        >
                            <?php esc_html_e('Left', 'jobster') ?>
                        </option>
                        <option 
                            value="center" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'page_settings_title_align', true
                                ),
                                'center'
                            ) ?>
                        >
                            <?php esc_html_e('Center', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="pxp-d-flex">
            <div style="width: 50%;">
                <div class="form-field pxp-is-custom">
                    <label for="page_settings_bg_color">
                        <?php esc_html_e('Title background color', 'jobster'); ?>
                    </label><br>
                    <input 
                        name="page_settings_bg_color" 
                        id="page_settings_bg_color" 
                        type="text" 
                        class="pxp-color-field"
                        value="<?php echo esc_attr(
                            get_post_meta($post->ID, 'page_settings_bg_color', true)
                        ); ?>"
                    >
                </div>
            </div>
        </div>

        <br><hr><br>

        <div class="pxp-d-flex">
            <div style="width: 50%;">
                <div class="form-field pxp-is-custom">
                    <label for="page_settings_margin_field">
                        <input 
                            type="hidden" 
                            name="page_settings_margin" 
                            value="0"
                        >
                        <input 
                            type="checkbox" 
                            name="page_settings_margin" 
                            id="page_settings_margin_field" 
                            value="1" 
                            <?php checked(
                                get_post_meta(
                                    $post->ID, 
                                    'page_settings_margin', 
                                    true
                                ),
                                true,
                                true
                            ); ?>
                        >
                        <?php esc_html_e('No margin bottom', 'jobster'); ?>
                    </label>
                </div>
            </div>
        </div>
    <?php }
endif;

if (!function_exists('jobster_save_page_header_logos_meta')): 
    function jobster_save_page_header_logos_meta($post_id, $field) {
        $ph_logos_list = array();
        $ph_logos_data_raw = urldecode($_POST[$field]);
        $ph_logos_data = json_decode($ph_logos_data_raw);

        $ph_logos_data_encoded = '';

        if (isset($ph_logos_data)) {
            $ph_logos_new_data = new stdClass();
            $ph_new_logos = array();

            $ph_logos_list = $ph_logos_data->logos;

            foreach ($ph_logos_list as $ph_logo) {
                $ph_new_logo = new stdClass();

                $ph_new_logo->image = sanitize_text_field($ph_logo->image);

                array_push($ph_new_logos, $ph_new_logo);
            }

            $ph_logos_new_data->logos = $ph_new_logos;

            $ph_logos_data_before = json_encode($ph_logos_new_data);
            $ph_logos_data_encoded = urlencode($ph_logos_data_before);
        }

        update_post_meta($post_id, $field, $ph_logos_data_encoded);
    }
endif;

if (!function_exists('jobster_save_page_header_photos_meta')): 
    function jobster_save_page_header_photos_meta($post_id, $field) {
        $ph_photos_list = array();
        $ph_photos_data_raw = urldecode($_POST[$field]);
        $ph_photos_data = json_decode($ph_photos_data_raw);

        $ph_photos_data_encoded = '';

        if (isset($ph_photos_data)) {
            $ph_photos_new_data = new stdClass();
            $ph_new_photos = array();

            $ph_photos_list = $ph_photos_data->photos;

            foreach ($ph_photos_list as $ph_photo) {
                $ph_new_photo = new stdClass();

                $ph_new_photo->image = sanitize_text_field($ph_photo->image);

                array_push($ph_new_photos, $ph_new_photo);
            }

            $ph_photos_new_data->photos = $ph_new_photos;

            $ph_photos_data_before = json_encode($ph_photos_new_data);
            $ph_photos_data_encoded = urlencode($ph_photos_data_before);
        }

        update_post_meta($post_id, $field, $ph_photos_data_encoded);
    }
endif;

if (!function_exists('jobster_save_page_header_info_meta')): 
    function jobster_save_page_header_info_meta($post_id, $field) {
        $ph_infos_list = array();
        $ph_infos_data_raw = urldecode($_POST[$field]);
        $ph_infos_data = json_decode($ph_infos_data_raw);

        $ph_infos_data_encoded = '';

        if (isset($ph_infos_data)) {
            $ph_infos_new_data = new stdClass();
            $ph_new_infos = array();

            $ph_infos_list = $ph_infos_data->info;

            foreach ($ph_infos_list as $ph_info) {
                $ph_new_info = new stdClass();

                $ph_new_info->number = sanitize_text_field($ph_info->number);
                $ph_new_info->label  = sanitize_text_field($ph_info->label);
                $ph_new_info->text   = sanitize_text_field($ph_info->text);

                array_push($ph_new_infos, $ph_new_info);
            }

            $ph_infos_new_data->info = $ph_new_infos;

            $ph_infos_data_before = json_encode($ph_infos_new_data);
            $ph_infos_data_encoded = urlencode($ph_infos_data_before);
        }

        update_post_meta($post_id, $field, $ph_infos_data_encoded);
    }
endif;

if (!function_exists('jobster_save_page_header_key_features_meta')): 
    function jobster_save_page_header_key_features_meta($post_id, $field) {
        $ph_features_list = array();
        $ph_features_data_raw = urldecode($_POST[$field]);
        $ph_features_data = json_decode($ph_features_data_raw);

        $ph_features_data_encoded = '';

        if (isset($ph_features_data)) {
            $ph_features_new_data = new stdClass();
            $ph_new_features = array();

            $ph_features_list = $ph_features_data->features;

            foreach ($ph_features_list as $ph_feature) {
                $ph_new_feature = new stdClass();

                $ph_new_feature->text   = sanitize_text_field($ph_feature->text);

                array_push($ph_new_features, $ph_new_feature);
            }

            $ph_features_new_data->features = $ph_new_features;

            $ph_features_data_before = json_encode($ph_features_new_data);
            $ph_features_data_encoded = urlencode($ph_features_data_before);
        }

        update_post_meta($post_id, $field, $ph_features_data_encoded);
    }
endif;

if (!function_exists('jobster_jobs_page_settings_render')): 
    function jobster_jobs_page_settings_render($post) {
        wp_nonce_field('jobster_page', 'page_noncename'); ?>

        <div class="pxp-d-flex">
            <div style="width: 50%;">
                <div class="form-field pxp-is-custom">
                    <label for="jobs_page_subtitle">
                        <?php esc_html_e('Page subtitle', 'jobster'); ?>
                    </label>
                    <input 
                        name="jobs_page_subtitle" 
                        id="jobs_page_subtitle" 
                        type="text" 
                        value="<?php echo esc_attr(
                            get_post_meta($post->ID, 'jobs_page_subtitle', true)
                        ); ?>"
                    >
                </div>
            </div>
        </div>
        <div class="pxp-d-flex">
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="jobs_page_header_align">
                        <?php esc_html_e('Header align', 'jobster'); ?>
                    </label>
                    <select 
                        name="jobs_page_header_align" 
                        id="jobs_page_header_align"
                    >
                        <option 
                            value="left" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_header_align', true
                                ),
                                'left'
                            ) ?>
                        >
                            <?php esc_html_e('Left', 'jobster') ?>
                        </option>
                        <option 
                            value="center" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_header_align', true
                                ),
                                'center'
                            ) ?>
                        >
                            <?php esc_html_e('Center', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="jobs_page_search_position">
                        <?php esc_html_e('Search form position', 'jobster'); ?>
                    </label>
                    <select 
                        name="jobs_page_search_position" 
                        id="jobs_page_search_position"
                    >
                        <option 
                            value="top" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_search_position', true
                                ),
                                'top'
                            ) ?>
                        >
                            <?php esc_html_e('Top', 'jobster') ?>
                        </option>
                        <option 
                            value="side" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_search_position', true
                                ),
                                'side'
                            ) ?>
                        >
                            <?php esc_html_e('Side', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="jobs_page_filter_position">
                        <?php esc_html_e('Filter form position', 'jobster'); ?>
                    </label>
                    <select 
                        name="jobs_page_filter_position" 
                        id="jobs_page_filter_position"
                    >
                        <option 
                            value="top" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_filter_position', true
                                ),
                                'top'
                            ) ?>
                        >
                            <?php esc_html_e('Top', 'jobster') ?>
                        </option>
                        <option 
                            value="side" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_filter_position', true
                                ),
                                'side'
                            ) ?>
                        >
                            <?php esc_html_e('Side', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="jobs_page_side_position">
                        <?php esc_html_e('Sidebar position', 'jobster'); ?>
                    </label>
                    <select 
                        name="jobs_page_side_position" 
                        id="jobs_page_side_position"
                    >
                        <option 
                            value="left" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_side_position', true
                                ),
                                'left'
                            ) ?>
                        >
                            <?php esc_html_e('Left', 'jobster') ?>
                        </option>
                        <option 
                            value="right" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_side_position', true
                                ),
                                'right'
                            ) ?>
                        >
                            <?php esc_html_e('Right', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="pxp-d-flex">
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="jobs_page_card_design">
                        <?php esc_html_e('Card design', 'jobster'); ?>
                    </label>
                    <select 
                        name="jobs_page_card_design" 
                        id="jobs_page_card_design"
                    >
                        <option 
                            value="big" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_card_design', true
                                ),
                                'big'
                            ) ?>
                        >
                            <?php esc_html_e('Big', 'jobster') ?>
                        </option>
                        <option 
                            value="small" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_card_design', true
                                ),
                                'small'
                            ) ?>
                        >
                            <?php esc_html_e('Small', 'jobster') ?>
                        </option>
                        <option 
                            value="list" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_card_design', true
                                ),
                                'list'
                            ) ?>
                        >
                            <?php esc_html_e('List', 'jobster') ?>
                        </option>
                        <option 
                            value="cover" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'jobs_page_card_design', true
                                ),
                                'cover'
                            ) ?>
                        >
                            <?php esc_html_e('Cover', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
    <?php }
endif;

if (!function_exists('jobster_job_categories_page_settings_render')):
    function jobster_job_categories_page_settings_render($post) {
        wp_nonce_field('jobster_page', 'page_noncename'); ?>

        <div class="pxp-d-flex">
            <div style="width: 50%;">
                <div class="form-field pxp-is-custom">
                    <label for="job_categories_page_subtitle">
                        <?php esc_html_e('Page subtitle', 'jobster'); ?>
                    </label>
                    <input 
                        name="job_categories_page_subtitle" 
                        id="job_categories_page_subtitle" 
                        type="text" 
                        value="<?php echo esc_attr(
                            get_post_meta($post->ID, 'job_categories_page_subtitle', true)
                        ); ?>"
                    >
                </div>
            </div>
        </div>
        <div class="pxp-d-flex">
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="job_categories_page_header_align">
                        <?php esc_html_e('Header align', 'jobster'); ?>
                    </label>
                    <select 
                        name="job_categories_page_header_align" 
                        id="job_categories_page_header_align"
                    >
                        <option 
                            value="left" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'job_categories_page_header_align', true
                                ),
                                'left'
                            ) ?>
                        >
                            <?php esc_html_e('Left', 'jobster') ?>
                        </option>
                        <option 
                            value="center" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'job_categories_page_header_align', true
                                ),
                                'center'
                            ) ?>
                        >
                            <?php esc_html_e('Center', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="job_categories_page_side_position">
                        <?php esc_html_e('Sidebar position', 'jobster'); ?>
                    </label>
                    <select 
                        name="job_categories_page_side_position" 
                        id="job_categories_page_side_position"
                    >
                        <option 
                            value="left" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'job_categories_page_side_position', true
                                ),
                                'left'
                            ) ?>
                        >
                            <?php esc_html_e('Left', 'jobster') ?>
                        </option>
                        <option 
                            value="right" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'job_categories_page_side_position', true
                                ),
                                'right'
                            ) ?>
                        >
                            <?php esc_html_e('Right', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="job_categories_page_sort">
                        <?php esc_html_e('Sort by', 'jobster'); ?>
                    </label>
                    <select 
                        name="job_categories_page_sort" 
                        id="job_categories_page_sort"
                    >
                        <option 
                            value="n" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'job_categories_page_sort', true
                                ),
                                'n'
                            ) ?>
                        >
                            <?php esc_html_e('Name', 'jobster') ?>
                        </option>
                        <option 
                            value="j" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'job_categories_page_sort', true
                                ),
                                'j'
                            ) ?>
                        >
                            <?php esc_html_e('Number of jobs', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="job_categories_page_design">
                        <?php esc_html_e('Card design', 'jobster'); ?>
                    </label>
                    <select 
                        name="job_categories_page_design" 
                        id="job_categories_page_design"
                    >
                        <option 
                            value="v" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'job_categories_page_design', true
                                ),
                                'v'
                            ) ?>
                        >
                            <?php esc_html_e('Vertical', 'jobster') ?>
                        </option>
                        <option 
                            value="h" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'job_categories_page_design', true
                                ),
                                'h'
                            ) ?>
                        >
                            <?php esc_html_e('Horizontal', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="pxp-d-flex">
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="job_categories_page_icon">
                        <?php esc_html_e('Icon Background', 'jobster'); ?>
                    </label>
                    <select 
                        name="job_categories_page_icon" 
                        id="job_categories_page_icon"
                    >
                        <option 
                            value="t" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'job_categories_page_icon', true
                                ),
                                't'
                            ) ?>
                        >
                            <?php esc_html_e('Transparent', 'jobster') ?>
                        </option>
                        <option 
                            value="o" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID, 'job_categories_page_icon', true
                                ),
                                'o'
                            ) ?>
                        >
                            <?php esc_html_e('Opaque', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <?php 
    }
endif;

if (!function_exists('jobster_companies_page_settings_render')): 
    function jobster_companies_page_settings_render($post) {
        wp_nonce_field('jobster_page', 'page_noncename'); ?>

        <div class="pxp-d-flex">
            <div style="width: 50%;">
                <div class="form-field pxp-is-custom">
                    <label for="jobs_page_subtitle">
                        <?php esc_html_e('Page subtitle', 'jobster'); ?>
                    </label>
                    <input 
                        name="companies_page_subtitle" 
                        id="companies_page_subtitle" 
                        type="text" 
                        value="<?php echo esc_attr(
                            get_post_meta(
                                $post->ID, 'companies_page_subtitle', true
                            )
                        ); ?>"
                    >
                </div>
            </div>
        </div>
        <div class="pxp-d-flex">
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="companies_page_header_align">
                        <?php esc_html_e('Header align', 'jobster'); ?>
                    </label>
                    <select 
                        name="companies_page_header_align" 
                        id="companies_page_header_align"
                    >
                        <option 
                            value="left" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'companies_page_header_align',
                                    true
                                ),
                                'left'
                            ) ?>
                        >
                            <?php esc_html_e('Left', 'jobster') ?>
                        </option>
                        <option 
                            value="center" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'companies_page_header_align',
                                    true
                                ),
                                'center'
                            ) ?>
                        >
                            <?php esc_html_e('Center', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="companies_page_search_position">
                        <?php esc_html_e('Search form position', 'jobster'); ?>
                    </label>
                    <select 
                        name="companies_page_search_position" 
                        id="companies_page_search_position"
                    >
                        <option 
                            value="top" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'companies_page_search_position',
                                    true
                                ),
                                'top'
                            ) ?>
                        >
                            <?php esc_html_e('Top', 'jobster') ?>
                        </option>
                        <option 
                            value="side" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'companies_page_search_position',
                                    true
                                ),
                                'side'
                            ) ?>
                        >
                            <?php esc_html_e('Side', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="companies_page_side_position">
                        <?php esc_html_e('Sidebar position', 'jobster'); ?>
                    </label>
                    <select 
                        name="companies_page_side_position" 
                        id="companies_page_side_position"
                    >
                        <option 
                            value="left" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'companies_page_side_position',
                                    true
                                ),
                                'left'
                            ) ?>
                        >
                            <?php esc_html_e('Left', 'jobster') ?>
                        </option>
                        <option 
                            value="right" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'companies_page_side_position',
                                    true
                                ),
                                'right'
                            ) ?>
                        >
                            <?php esc_html_e('Right', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
    <?php }
endif;

if (!function_exists('jobster_candidates_page_settings_render')): 
    function jobster_candidates_page_settings_render($post) {
        wp_nonce_field('jobster_page', 'page_noncename'); ?>

        <div class="pxp-d-flex">
            <div style="width: 50%;">
                <div class="form-field pxp-is-custom">
                    <label for="candidates_page_subtitle">
                        <?php esc_html_e('Page subtitle', 'jobster'); ?>
                    </label>
                    <input 
                        name="candidates_page_subtitle" 
                        id="candidates_page_subtitle" 
                        type="text" 
                        value="<?php echo esc_attr(
                            get_post_meta(
                                $post->ID, 'candidates_page_subtitle', true
                            )
                        ); ?>"
                    >
                </div>
            </div>
        </div>
        <div class="pxp-d-flex">
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="candidates_page_header_align">
                        <?php esc_html_e('Header align', 'jobster'); ?>
                    </label>
                    <select 
                        name="candidates_page_header_align" 
                        id="candidates_page_header_align"
                    >
                        <option 
                            value="left" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'candidates_page_header_align',
                                    true
                                ),
                                'left'
                            ) ?>
                        >
                            <?php esc_html_e('Left', 'jobster') ?>
                        </option>
                        <option 
                            value="center" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'candidates_page_header_align',
                                    true
                                ),
                                'center'
                            ) ?>
                        >
                            <?php esc_html_e('Center', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="candidates_page_search_position">
                        <?php esc_html_e('Search form position', 'jobster'); ?>
                    </label>
                    <select 
                        name="candidates_page_search_position" 
                        id="candidates_page_search_position"
                    >
                        <option 
                            value="top" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'candidates_page_search_position',
                                    true
                                ),
                                'top'
                            ) ?>
                        >
                            <?php esc_html_e('Top', 'jobster') ?>
                        </option>
                        <option 
                            value="side" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'candidates_page_search_position',
                                    true
                                ),
                                'side'
                            ) ?>
                        >
                            <?php esc_html_e('Side', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
            <div style="width: 25%">
                <div class="form-field pxp-is-custom">
                    <label for="candidates_page_side_position">
                        <?php esc_html_e('Sidebar position', 'jobster'); ?>
                    </label>
                    <select 
                        name="candidates_page_side_position" 
                        id="candidates_page_side_position"
                    >
                        <option 
                            value="left" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'candidates_page_side_position',
                                    true
                                ),
                                'left'
                            ) ?>
                        >
                            <?php esc_html_e('Left', 'jobster') ?>
                        </option>
                        <option 
                            value="right" 
                            <?php selected(
                                get_post_meta(
                                    $post->ID,
                                    'candidates_page_side_position',
                                    true
                                ),
                                'right'
                            ) ?>
                        >
                            <?php esc_html_e('Right', 'jobster') ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>
    <?php }
endif;

if (!function_exists('jobster_page_meta_save')): 
    function jobster_page_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce =   (isset($_POST['page_noncename'])
                                && wp_verify_nonce(
                                    $_POST['page_noncename'],
                                    'jobster_page'
                                )
                            )
                            ? 'true'
                            : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        // Save header type settings
        if (isset($_POST['page_header_type'])) {
            update_post_meta($post_id,
                'page_header_type',
                sanitize_text_field($_POST['page_header_type'])
            );
        }

        // Save animated cards header settings
        if (isset($_POST['ph_animated_cards_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_animated_cards_caption_title',
                sanitize_text_field($_POST['ph_animated_cards_caption_title'])
            );
        }
        if (isset($_POST['ph_animated_cards_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_animated_cards_caption_subtitle',
                sanitize_text_field($_POST['ph_animated_cards_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_animated_cards_show_search'])) {
            update_post_meta(
                $post_id,
                'ph_animated_cards_show_search',
                sanitize_text_field($_POST['ph_animated_cards_show_search'])
            );
        }
        if (isset($_POST['ph_animated_cards_show_popular'])) {
            update_post_meta(
                $post_id,
                'ph_animated_cards_show_popular',
                sanitize_text_field($_POST['ph_animated_cards_show_popular'])
            );
        }
        if (isset($_POST['ph_animated_cards_search_system'])) {
            update_post_meta(
                $post_id,
                'ph_animated_cards_search_system',
                sanitize_text_field($_POST['ph_animated_cards_search_system'])
            );
        }
        if (isset($_POST['ph_animated_cards_logos'])) {
            jobster_save_page_header_logos_meta(
                $post_id,
                'ph_animated_cards_logos'
            );
        }
        if (isset($_POST['ph_animated_cards_photo'])) {
            update_post_meta(
                $post_id,
                'ph_animated_cards_photo',
                sanitize_text_field($_POST['ph_animated_cards_photo'])
            );
        }
        if (isset($_POST['ph_animated_cards_info'])) {
            jobster_save_page_header_info_meta(
                $post_id,
                'ph_animated_cards_info'
            );
        }

        // Save image rotator header settings
        if (isset($_POST['ph_image_rotator_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_image_rotator_caption_title',
                sanitize_text_field($_POST['ph_image_rotator_caption_title'])
            );
        }
        if (isset($_POST['ph_image_rotator_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_image_rotator_caption_subtitle',
                sanitize_text_field($_POST['ph_image_rotator_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_image_rotator_show_search'])) {
            update_post_meta(
                $post_id,
                'ph_image_rotator_show_search',
                sanitize_text_field($_POST['ph_image_rotator_show_search'])
            );
        }
        if (isset($_POST['ph_image_rotator_search_system'])) {
            update_post_meta(
                $post_id,
                'ph_image_rotator_search_system',
                sanitize_text_field($_POST['ph_image_rotator_search_system'])
            );
        }
        if (isset($_POST['ph_image_rotator_show_popular'])) {
            update_post_meta(
                $post_id,
                'ph_image_rotator_show_popular',
                sanitize_text_field($_POST['ph_image_rotator_show_popular'])
            );
        }
        if (isset($_POST['ph_image_rotator_logos'])) {
            jobster_save_page_header_logos_meta(
                $post_id,
                'ph_image_rotator_logos'
            );
        }
        if (isset($_POST['ph_image_rotator_photos'])) {
            jobster_save_page_header_photos_meta(
                $post_id,
                'ph_image_rotator_photos'
            );
        }
        if (isset($_POST['ph_image_rotator_interval'])) {
            update_post_meta(
                $post_id,
                'ph_image_rotator_interval',
                sanitize_text_field($_POST['ph_image_rotator_interval'])
            );
        }
        if (isset($_POST['ph_image_rotator_info'])) {
            jobster_save_page_header_info_meta(
                $post_id,
                'ph_image_rotator_info'
            );
        }

        // Save illustration header settings
        if (isset($_POST['ph_illustration_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_illustration_caption_title',
                sanitize_text_field($_POST['ph_illustration_caption_title'])
            );
        }
        if (isset($_POST['ph_illustration_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_illustration_caption_subtitle',
                sanitize_text_field($_POST['ph_illustration_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_illustration_show_search'])) {
            update_post_meta(
                $post_id,
                'ph_illustration_show_search',
                sanitize_text_field($_POST['ph_illustration_show_search'])
            );
        }
        if (isset($_POST['ph_illustration_search_system'])) {
            update_post_meta(
                $post_id,
                'ph_illustration_search_system',
                sanitize_text_field($_POST['ph_illustration_search_system'])
            );
        }
        if (isset($_POST['ph_illustration_show_popular'])) {
            update_post_meta(
                $post_id,
                'ph_illustration_show_popular',
                sanitize_text_field($_POST['ph_illustration_show_popular'])
            );
        }
        if (isset($_POST['ph_illustration_logos'])) {
            jobster_save_page_header_logos_meta(
                $post_id,
                'ph_illustration_logos'
            );
        }
        if (isset($_POST['ph_illustration_photo'])) {
            update_post_meta(
                $post_id,
                'ph_illustration_photo',
                sanitize_text_field($_POST['ph_illustration_photo'])
            );
        }

        // Boxed header settings
        if (isset($_POST['ph_boxed_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_caption_title',
                sanitize_text_field($_POST['ph_boxed_caption_title'])
            );
        }
        if (isset($_POST['ph_boxed_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_caption_subtitle',
                sanitize_text_field($_POST['ph_boxed_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_boxed_show_search'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_show_search',
                sanitize_text_field($_POST['ph_boxed_show_search'])
            );
        }
        if (isset($_POST['ph_boxed_search_system'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_search_system',
                sanitize_text_field($_POST['ph_boxed_search_system'])
            );
        }
        if (isset($_POST['ph_boxed_show_popular'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_show_popular',
                sanitize_text_field($_POST['ph_boxed_show_popular'])
            );
        }
        if (isset($_POST['ph_boxed_sfc_card_label'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_sfc_card_label',
                sanitize_text_field($_POST['ph_boxed_sfc_card_label'])
            );
        }
        if (isset($_POST['ph_boxed_sfc_illustration'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_sfc_illustration',
                sanitize_text_field($_POST['ph_boxed_sfc_illustration'])
            );
        }
        if (isset($_POST['ph_boxed_sfc_icon'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_sfc_icon',
                sanitize_text_field($_POST['ph_boxed_sfc_icon'])
            );
        }
        if (isset($_POST['ph_boxed_bfc_card_label'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_bfc_card_label',
                sanitize_text_field($_POST['ph_boxed_bfc_card_label'])
            );
        }
        if (isset($_POST['ph_boxed_bfc_card_text'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_bfc_card_text',
                sanitize_text_field($_POST['ph_boxed_bfc_card_text'])
            );
        }
        if (isset($_POST['ph_boxed_bfc_illustration'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_bfc_illustration',
                sanitize_text_field($_POST['ph_boxed_bfc_illustration'])
            );
        }
        if (isset($_POST['ph_boxed_bfc_icon'])) {
            update_post_meta(
                $post_id,
                'ph_boxed_bfc_icon',
                sanitize_text_field($_POST['ph_boxed_bfc_icon'])
            );
        }
        if (isset($_POST['ph_boxed_info'])) {
            jobster_save_page_header_info_meta($post_id, 'ph_boxed_info');
        }

        // Image Background header settings
        if (isset($_POST['ph_image_bg_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_image_bg_caption_title',
                sanitize_text_field($_POST['ph_image_bg_caption_title'])
            );
        }
        if (isset($_POST['ph_image_bg_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_image_bg_caption_subtitle',
                sanitize_text_field($_POST['ph_image_bg_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_image_bg_show_search'])) {
            update_post_meta(
                $post_id,
                'ph_image_bg_show_search',
                sanitize_text_field($_POST['ph_image_bg_show_search'])
            );
        }
        if (isset($_POST['ph_image_bg_search_system'])) {
            update_post_meta(
                $post_id,
                'ph_image_bg_search_system',
                sanitize_text_field($_POST['ph_image_bg_search_system'])
            );
        }
        if (isset($_POST['ph_image_bg_height'])) {
            update_post_meta(
                $post_id,
                'ph_image_bg_height',
                sanitize_text_field($_POST['ph_image_bg_height'])
            );
        }
        if (isset($_POST['ph_image_bg_align'])) {
            update_post_meta(
                $post_id,
                'ph_image_bg_align',
                sanitize_text_field($_POST['ph_image_bg_align'])
            );
        }
        if (isset($_POST['ph_image_bg_photo'])) {
            update_post_meta(
                $post_id,
                'ph_image_bg_photo',
                sanitize_text_field($_POST['ph_image_bg_photo'])
            );
        }
        if (isset($_POST['ph_image_bg_opacity'])) {
            update_post_meta(
                $post_id,
                'ph_image_bg_opacity',
                sanitize_text_field($_POST['ph_image_bg_opacity'])
            );
        }

        // Top search header settings
        if (isset($_POST['ph_top_search_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_top_search_caption_title',
                sanitize_text_field($_POST['ph_top_search_caption_title'])
            );
        }
        if (isset($_POST['ph_top_search_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_top_search_caption_subtitle',
                sanitize_text_field($_POST['ph_top_search_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_top_search_show_search'])) {
            update_post_meta(
                $post_id,
                'ph_top_search_show_search',
                sanitize_text_field($_POST['ph_top_search_show_search'])
            );
        }
        if (isset($_POST['ph_top_search_system'])) {
            update_post_meta(
                $post_id,
                'ph_top_search_system',
                sanitize_text_field($_POST['ph_top_search_system'])
            );
        }
        if (isset($_POST['ph_top_search_photo'])) {
            update_post_meta(
                $post_id,
                'ph_top_search_photo',
                sanitize_text_field($_POST['ph_top_search_photo'])
            );
        }
        if (isset($_POST['ph_top_search_opacity'])) {
            update_post_meta(
                $post_id,
                'ph_top_search_opacity',
                sanitize_text_field($_POST['ph_top_search_opacity'])
            );
        }
        if (isset($_POST['ph_top_search_cta_label'])) {
            update_post_meta(
                $post_id,
                'ph_top_search_cta_label',
                sanitize_text_field($_POST['ph_top_search_cta_label'])
            );
        }
        if (isset($_POST['ph_top_search_cta_link'])) {
            update_post_meta(
                $post_id,
                'ph_top_search_cta_link',
                sanitize_text_field($_POST['ph_top_search_cta_link'])
            );
        }

        // Save image card header settings
        if (isset($_POST['ph_image_card_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_image_card_caption_title',
                sanitize_text_field($_POST['ph_image_card_caption_title'])
            );
        }
        if (isset($_POST['ph_image_card_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_image_card_caption_subtitle',
                sanitize_text_field($_POST['ph_image_card_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_image_card_show_search'])) {
            update_post_meta(
                $post_id,
                'ph_image_card_show_search',
                sanitize_text_field($_POST['ph_image_card_show_search'])
            );
        }
        if (isset($_POST['ph_image_card_search_system'])) {
            update_post_meta(
                $post_id,
                'ph_image_card_search_system',
                sanitize_text_field($_POST['ph_image_card_search_system'])
            );
        }
        if (isset($_POST['ph_image_card_show_popular'])) {
            update_post_meta(
                $post_id,
                'ph_image_card_show_popular',
                sanitize_text_field($_POST['ph_image_card_show_popular'])
            );
        }
        if (isset($_POST['ph_image_card_logos'])) {
            jobster_save_page_header_logos_meta(
                $post_id,
                'ph_image_card_logos'
            );
        }
        if (isset($_POST['ph_image_card_photo'])) {
            update_post_meta(
                $post_id,
                'ph_image_card_photo',
                sanitize_text_field($_POST['ph_image_card_photo'])
            );
        }

        // Save half image header settings
        if (isset($_POST['ph_half_image_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_half_image_caption_title',
                sanitize_text_field($_POST['ph_half_image_caption_title'])
            );
        }
        if (isset($_POST['ph_half_image_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_half_image_caption_subtitle',
                sanitize_text_field($_POST['ph_half_image_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_half_image_show_search'])) {
            update_post_meta(
                $post_id,
                'ph_half_image_show_search',
                sanitize_text_field($_POST['ph_half_image_show_search'])
            );
        }
        if (isset($_POST['ph_half_image_search_system'])) {
            update_post_meta(
                $post_id,
                'ph_half_image_search_system',
                sanitize_text_field($_POST['ph_half_image_search_system'])
            );
        }
        if (isset($_POST['ph_half_image_photo'])) {
            update_post_meta(
                $post_id,
                'ph_half_image_photo',
                sanitize_text_field($_POST['ph_half_image_photo'])
            );
        }
        if (isset($_POST['ph_half_image_caption_key_features'])) {
            jobster_save_page_header_key_features_meta(
                $post_id,
                'ph_half_image_caption_key_features'
            );
        }

        // Save center image header settings
        if (isset($_POST['ph_center_image_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_center_image_caption_title',
                sanitize_text_field($_POST['ph_center_image_caption_title'])
            );
        }
        if (isset($_POST['ph_center_image_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_center_image_caption_subtitle',
                sanitize_text_field($_POST['ph_center_image_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_center_image_show_search'])) {
            update_post_meta(
                $post_id,
                'ph_center_image_show_search',
                sanitize_text_field($_POST['ph_center_image_show_search'])
            );
        }
        if (isset($_POST['ph_center_image_search_system'])) {
            update_post_meta(
                $post_id,
                'ph_center_image_search_system',
                sanitize_text_field($_POST['ph_center_image_search_system'])
            );
        }
        if (isset($_POST['ph_center_image_photo'])) {
            update_post_meta(
                $post_id,
                'ph_center_image_photo',
                sanitize_text_field($_POST['ph_center_image_photo'])
            );
        }
        if (isset($_POST['ph_center_image_bg'])) {
            update_post_meta(
                $post_id,
                'ph_center_image_bg',
                sanitize_text_field($_POST['ph_center_image_bg'])
            );
        }
        if (isset($_POST['ph_center_image_opacity'])) {
            update_post_meta(
                $post_id,
                'ph_center_image_opacity',
                sanitize_text_field($_POST['ph_center_image_opacity'])
            );
        }

        // Save image pills header settings
        if (isset($_POST['ph_image_pills_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_image_pills_caption_title',
                sanitize_text_field($_POST['ph_image_pills_caption_title'])
            );
        }
        if (isset($_POST['ph_image_pills_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_image_pills_caption_subtitle',
                sanitize_text_field($_POST['ph_image_pills_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_image_pills_show_search'])) {
            update_post_meta(
                $post_id,
                'ph_image_pills_show_search',
                sanitize_text_field($_POST['ph_image_pills_show_search'])
            );
        }
        if (isset($_POST['ph_image_pills_search_system'])) {
            update_post_meta(
                $post_id,
                'ph_image_pills_search_system',
                sanitize_text_field($_POST['ph_image_pills_search_system'])
            );
        }
        if (isset($_POST['ph_image_pills_left'])) {
            update_post_meta(
                $post_id,
                'ph_image_pills_left',
                sanitize_text_field($_POST['ph_image_pills_left'])
            );
        }
        if (isset($_POST['ph_image_pills_top'])) {
            update_post_meta(
                $post_id,
                'ph_image_pills_top',
                sanitize_text_field($_POST['ph_image_pills_top'])
            );
        }
        if (isset($_POST['ph_image_pills_bottom'])) {
            update_post_meta(
                $post_id,
                'ph_image_pills_bottom',
                sanitize_text_field($_POST['ph_image_pills_bottom'])
            );
        }
        if (isset($_POST['ph_image_pills_caption_key_features'])) {
            jobster_save_page_header_key_features_meta(
                $post_id,
                'ph_image_pills_caption_key_features'
            );
        }

        // Save right image header settings
        if (isset($_POST['ph_right_image_caption_title'])) {
            update_post_meta(
                $post_id,
                'ph_right_image_caption_title',
                sanitize_text_field($_POST['ph_right_image_caption_title'])
            );
        }
        if (isset($_POST['ph_right_image_caption_subtitle'])) {
            update_post_meta(
                $post_id,
                'ph_right_image_caption_subtitle',
                sanitize_text_field($_POST['ph_right_image_caption_subtitle'])
            );
        }
        if (isset($_POST['ph_right_image_photo'])) {
            update_post_meta(
                $post_id,
                'ph_right_image_photo',
                sanitize_text_field($_POST['ph_right_image_photo'])
            );
        }
        if (isset($_POST['ph_right_image_bg'])) {
            update_post_meta(
                $post_id,
                'ph_right_image_bg',
                sanitize_text_field($_POST['ph_right_image_bg'])
            );
        }
        if (isset($_POST['ph_right_image_opacity'])) {
            update_post_meta(
                $post_id,
                'ph_right_image_opacity',
                sanitize_text_field($_POST['ph_right_image_opacity'])
            );
        }
        if (isset($_POST['ph_right_image_caption_key_features'])) {
            jobster_save_page_header_key_features_meta(
                $post_id,
                'ph_right_image_caption_key_features'
            );
        }
        if (isset($_POST['ph_right_image_cta_label'])) {
            update_post_meta(
                $post_id,
                'ph_right_image_cta_label',
                sanitize_text_field($_POST['ph_right_image_cta_label'])
            );
        }
        if (isset($_POST['ph_right_image_cta_link'])) {
            update_post_meta(
                $post_id,
                'ph_right_image_cta_link',
                sanitize_text_field($_POST['ph_right_image_cta_link'])
            );
        }

        // Save jobs page settings
        if (isset($_POST['jobs_page_subtitle'])) {
            update_post_meta(
                $post_id,
                'jobs_page_subtitle',
                sanitize_text_field($_POST['jobs_page_subtitle'])
            );
        }
        if (isset($_POST['jobs_page_header_align'])) {
            update_post_meta(
                $post_id,
                'jobs_page_header_align',
                sanitize_text_field($_POST['jobs_page_header_align'])
            );
        }
        if (isset($_POST['jobs_page_search_position'])) {
            update_post_meta(
                $post_id,
                'jobs_page_search_position',
                sanitize_text_field($_POST['jobs_page_search_position'])
            );
        }
        if (isset($_POST['jobs_page_filter_position'])) {
            update_post_meta(
                $post_id,
                'jobs_page_filter_position',
                sanitize_text_field($_POST['jobs_page_filter_position'])
            );
        }
        if (isset($_POST['jobs_page_side_position'])) {
            update_post_meta(
                $post_id,
                'jobs_page_side_position',
                sanitize_text_field($_POST['jobs_page_side_position'])
            );
        }
        if (isset($_POST['jobs_page_card_design'])) {
            update_post_meta(
                $post_id,
                'jobs_page_card_design',
                sanitize_text_field($_POST['jobs_page_card_design'])
            );
        }
        
        // Save job categories page settings
        if (isset($_POST['job_categories_page_subtitle'])) {
            update_post_meta(
                $post_id,
                'job_categories_page_subtitle',
                sanitize_text_field($_POST['job_categories_page_subtitle'])
            );
        }
        if (isset($_POST['job_categories_page_header_align'])) {
            update_post_meta(
                $post_id,
                'job_categories_page_header_align',
                sanitize_text_field($_POST['job_categories_page_header_align'])
            );
        }
        if (isset($_POST['job_categories_page_side_position'])) {
            update_post_meta(
                $post_id,
                'job_categories_page_side_position',
                sanitize_text_field($_POST['job_categories_page_side_position'])
            );
        }
        if (isset($_POST['job_categories_page_sort'])) {
            update_post_meta(
                $post_id,
                'job_categories_page_sort',
                sanitize_text_field($_POST['job_categories_page_sort'])
            );
        }
        if (isset($_POST['job_categories_page_design'])) {
            update_post_meta(
                $post_id,
                'job_categories_page_design',
                sanitize_text_field($_POST['job_categories_page_design'])
            );
        }
        if (isset($_POST['job_categories_page_icon'])) {
            update_post_meta(
                $post_id,
                'job_categories_page_icon',
                sanitize_text_field($_POST['job_categories_page_icon'])
            );
        }

        // Save companies page settings
        if (isset($_POST['companies_page_subtitle'])) {
            update_post_meta(
                $post_id,
                'companies_page_subtitle',
                sanitize_text_field($_POST['companies_page_subtitle'])
            );
        }
        if (isset($_POST['companies_page_header_align'])) {
            update_post_meta(
                $post_id,
                'companies_page_header_align',
                sanitize_text_field($_POST['companies_page_header_align'])
            );
        }
        if (isset($_POST['companies_page_search_position'])) {
            update_post_meta(
                $post_id,
                'companies_page_search_position',
                sanitize_text_field($_POST['companies_page_search_position'])
            );
        }
        if (isset($_POST['companies_page_side_position'])) {
            update_post_meta(
                $post_id,
                'companies_page_side_position',
                sanitize_text_field($_POST['companies_page_side_position'])
            );
        }

        // Save candidates page settings
        if (isset($_POST['candidates_page_subtitle'])) {
            update_post_meta(
                $post_id,
                'candidates_page_subtitle',
                sanitize_text_field($_POST['candidates_page_subtitle'])
            );
        }
        if (isset($_POST['candidates_page_header_align'])) {
            update_post_meta(
                $post_id,
                'candidates_page_header_align',
                sanitize_text_field($_POST['candidates_page_header_align'])
            );
        }
        if (isset($_POST['candidates_page_search_position'])) {
            update_post_meta(
                $post_id,
                'candidates_page_search_position',
                sanitize_text_field($_POST['candidates_page_search_position'])
            );
        }
        if (isset($_POST['candidates_page_side_position'])) {
            update_post_meta(
                $post_id,
                'candidates_page_side_position',
                sanitize_text_field($_POST['candidates_page_side_position'])
            );
        }

        // Save page settings
        if (isset($_POST['page_settings_hide_title'])) {
            update_post_meta(
                $post_id,
                'page_settings_hide_title',
                sanitize_text_field($_POST['page_settings_hide_title'])
            );
        }
        if (isset($_POST['page_settings_subtitle'])) {
            update_post_meta(
                $post_id,
                'page_settings_subtitle',
                sanitize_text_field($_POST['page_settings_subtitle'])
            );
        }
        if (isset($_POST['page_settings_title_align'])) {
            update_post_meta(
                $post_id,
                'page_settings_title_align',
                sanitize_text_field($_POST['page_settings_title_align'])
            );
        }
        if (isset($_POST['page_settings_bg_color'])) {
            update_post_meta(
                $post_id,
                'page_settings_bg_color',
                sanitize_text_field($_POST['page_settings_bg_color'])
            );
        }
        if (isset($_POST['page_settings_margin'])) {
            update_post_meta(
                $post_id,
                'page_settings_margin',
                sanitize_text_field($_POST['page_settings_margin'])
            );
        }
    }
endif;
add_action('save_post', 'jobster_page_meta_save');
?>