<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Register candidate custom post type
 */
if (!function_exists('jobster_register_candidate_type')): 
    function jobster_register_candidate_type() {
        register_post_type('candidate', array(
            'labels' => array(
                'name'               => __('Candidates', 'jobster'),
                'singular_name'      => __('Candidate', 'jobster'),
                'add_new'            => __('Add New Candidate', 'jobster'),
                'add_new_item'       => __('Add Candidate', 'jobster'),
                'edit'               => __('Edit', 'jobster'),
                'edit_item'          => __('Edit Candidate', 'jobster'),
                'new_item'           => __('New Candidate', 'jobster'),
                'view'               => __('View', 'jobster'),
                'view_item'          => __('View Candidate', 'jobster'),
                'search_items'       => __('Search Candidates', 'jobster'),
                'not_found'          => __('No Candidates found', 'jobster'),
                'not_found_in_trash' => __('No Candidates found in Trash', 'jobster'),
                'parent'             => __('Parent Candidate', 'jobster'),
            ),
            'public'                => true,
            'exclude_from_search '  => false,
            'has_archive'           => true,
            'rewrite'               => array('slug' => _x('candidates', 'URL SLUG', 'jobster')),
            'supports'              => array('title', 'editor', 'comments'),
            'show_in_rest'          => true,
            'can_export'            => true,
            'register_meta_box_cb'  => 'jobster_add_candidate_metaboxes',
            'menu_icon'             => 'dashicons-businessman',
        ));

        // add candidate industry taxonomy
        register_taxonomy('candidate_industry', 'candidate', array(
            'labels' => array(
                'name'                       => __('Candidate Industries', 'jobster'),
                'singular_name'              => __('Candidate Industry', 'jobster'),
                'search_items'               => __('Search Candidate Industries', 'jobster'),
                'popular_items'              => __('Popular Candidate Industries', 'jobster'),
                'all_items'                  => __('All Candidate Industries', 'jobster'),
                'edit_item'                  => __('Edit Candidate Industry', 'jobster'),
                'update_item'                => __('Update Candidate Industry', 'jobster'),
                'add_new_item'               => __('Add New Candidate Industry', 'jobster'),
                'new_item_name'              => __('New Candidate Industry Name', 'jobster'),
                'separate_items_with_commas' => __('Separate candidate industries with commas', 'jobster'),
                'add_or_remove_items'        => __('Add or remove candidate industries', 'jobster'),
                'choose_from_most_used'      => __('Choose from the most used candidate industries', 'jobster'),
                'not_found'                  => __('No candidate industry found.', 'jobster'),
                'menu_name'                  => __('Candidate Industries', 'jobster'),
            ),
            'hierarchical'      => true,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'candidate-industry'),
            'show_in_rest'      => true,
        ));

        // add candidate location taxonomy
        register_taxonomy('candidate_location', 'candidate', array(
            'labels' => array(
                'name'                       => __('Candidate Locations', 'jobster'),
                'singular_name'              => __('Candidate Location', 'jobster'),
                'search_items'               => __('Search Candidate Locations', 'jobster'),
                'popular_items'              => __('Popular Candidate Locations', 'jobster'),
                'all_items'                  => __('All Candidate Locations', 'jobster'),
                'edit_item'                  => __('Edit Candidate Location', 'jobster'),
                'update_item'                => __('Update Candidate Location', 'jobster'),
                'add_new_item'               => __('Add New Candidate Location', 'jobster'),
                'new_item_name'              => __('New Candidate Location Name', 'jobster'),
                'separate_items_with_commas' => __('Separate candidate locations with commas', 'jobster'),
                'add_or_remove_items'        => __('Add or remove candidate locations', 'jobster'),
                'choose_from_most_used'      => __('Choose from the most used candidate locations', 'jobster'),
                'not_found'                  => __('No candidate location found.', 'jobster'),
                'menu_name'                  => __('Candidate Locations', 'jobster'),
            ),
            'hierarchical'      => true,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'candidate-location'),
            'show_in_rest'      => true,
        ));

        // add candidate skills taxonomy
        register_taxonomy('candidate_skill', 'candidate', array(
            'labels' => array(
                'name'                       => __('Candidate Skills', 'jobster'),
                'singular_name'              => __('Candidate Skill', 'jobster'),
                'search_items'               => __('Search Candidate Skills', 'jobster'),
                'popular_items'              => __('Popular Candidate Skills', 'jobster'),
                'all_items'                  => __('All Candidate Skills', 'jobster'),
                'edit_item'                  => __('Edit Candidate Skill', 'jobster'),
                'update_item'                => __('Update Candidate Skill', 'jobster'),
                'add_new_item'               => __('Add New Candidate Skill', 'jobster'),
                'new_item_name'              => __('New Candidate Skill Name', 'jobster'),
                'separate_items_with_commas' => __('Separate candidate skills with commas', 'jobster'),
                'add_or_remove_items'        => __('Add or remove candidate skills', 'jobster'),
                'choose_from_most_used'      => __('Choose from the most used candidate skills', 'jobster'),
                'not_found'                  => __('No candidate skill found.', 'jobster'),
                'menu_name'                  => __('Candidate Skills', 'jobster'),
            ),
            'hierarchical'      => false,
            'query_var'         => true,
            'show_admin_column' => false,
            'rewrite'           => array('slug' => 'candidate-skill'),
            'show_in_rest'      => true,
        ));
    }
endif;
add_action('init', 'jobster_register_candidate_type');

if (!function_exists('jobster_change_candidate_default_title')): 
    function jobster_change_candidate_default_title($title) {
        $screen = get_current_screen();

        if ('candidate' == $screen->post_type) {
            $title = __('Add candidate name', 'jobster');
        }

        return $title;
    }
endif;
add_filter('enter_title_here', 'jobster_change_candidate_default_title');

if (!function_exists('jobster_add_candidate_metaboxes')): 
    function jobster_add_candidate_metaboxes() {
        add_meta_box('candidate-details-section', __('Candidate Details', 'jobster'), 'jobster_candidate_details_render', 'candidate', 'normal', 'default');
        add_meta_box('candidate-additional-info-section', __('Additional Info', 'jobster'), 'jobster_candidate_additional_info_render', 'candidate', 'normal', 'default');
        add_meta_box('candidate-photo-section', __('Candidate Photo', 'jobster'), 'jobster_candidate_photo_render', 'candidate', 'side', 'default');
        add_meta_box('candidate-cover-section', __('Candidate Cover', 'jobster'), 'jobster_candidate_cover_render', 'candidate', 'side', 'default');
        add_meta_box('candidate-featured-section', __('Featured', 'jobster'), 'jobster_candidate_featured_render', 'candidate', 'side', 'default');
        add_meta_box('candidate-work-section', __('Work Experience', 'jobster'), 'jobster_candidate_work_render', 'candidate', 'normal', 'default');
        add_meta_box('candidate-edu-section', __('Education and Training', 'jobster'), 'jobster_candidate_edu_render', 'candidate', 'normal', 'default');
        add_meta_box('candidate-cv-section', __('Resume', 'jobster'), 'jobster_candidate_cv_render', 'candidate', 'normal', 'default');
        add_meta_box('candidate-files-section', __('Additional Files', 'jobster'), 'jobster_candidate_files_render', 'candidate', 'normal', 'default');
        add_meta_box('candidate-gallery-section', __('Gallery/Portfolio', 'jobster'), 'jobster_candidate_gallery_render', 'candidate', 'normal', 'default');
        add_meta_box('candidate-video-section', __('Video', 'jobster'), 'jobster_candidate_video_render', 'candidate', 'normal', 'default');
        add_meta_box('candidate-social-section', __('Social Media', 'jobster'), 'jobster_candidate_social_media_render', 'candidate', 'normal', 'default');
        add_meta_box('candidate-job-alerts-section', __('Job Alerts', 'jobster'), 'jobster_candidate_job_alerts_render', 'candidate', 'normal', 'default');
        add_meta_box('candidate-user-section', __('User', 'jobster'), 'jobster_candidate_user_render', 'candidate', 'normal', 'default');
    }
endif;

if (!function_exists('jobster_candidate_details_render')):
    function jobster_candidate_details_render($post) {
        wp_nonce_field('jobster_candidate', 'candidate_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="candidate_title">' . __('Title', 'jobster') . '</label><br>
                            <input name="candidate_title" id="candidate_title" type="text" value="' . esc_attr(get_post_meta($post->ID, 'candidate_title', true)) . '">
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="candidate_email">' . __('Email', 'jobster') . '</label><br>
                            <input name="candidate_email" id="candidate_email" type="email" value="' . esc_attr(get_post_meta($post->ID, 'candidate_email', true)) . '">
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="candidate_phone">' . __('Phone', 'jobster') . '</label><br>
                            <input name="candidate_phone" id="candidate_phone" type="tel" value="' . esc_attr(get_post_meta($post->ID, 'candidate_phone', true)) . '">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="candidate_website">' . __('Website', 'jobster') . '</label><br>
                            <input name="candidate_website" id="candidate_website" type="text" value="' . esc_url(get_post_meta($post->ID, 'candidate_website', true)) . '">
                        </div>
                    </td>
                    <td width="25%" valign="top">&nbsp;</td>
                    <td width="25%" valign="top">&nbsp;</td>
            </table>';
    }
endif;

if (!function_exists('jobster_candidate_additional_info_render')):
    function jobster_candidate_additional_info_render($post) {
        $candidates_fields_settings = get_option('jobster_candidates_fields_settings');
        $counter = 0;

        if (is_array($candidates_fields_settings)) {
            uasort($candidates_fields_settings, 'jobster_compare_position');

            print '
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>';
            foreach ($candidates_fields_settings as $key => $value) {
                $counter++;

                if (($counter - 1) % 3 == 0) {
                    print '
                    <tr>';
                }

                print '
                        <td width="33%" valign="top">
                            <div class="form-field pxp-is-custom">
                                <label for="' . $key . '">' . $value['label'] . '</label><br />';
                if ($value['type'] == 'date_field') {
                    print '
                                <input type="text" name="' . $key . '" id="' . $key . '" class="datePicker" value="' . esc_attr(get_post_meta($post->ID, $key, true)) . '" placeholder="' . __('YYYY-MM-DD', 'jobster') . '" />';
                } else if ($value['type'] == 'list_field') {
                    $list = explode(',', $value['list']);

                    print '
                                <select name="' . $key . '" id="' . $key . '" class="formInput">
                                    <option value="">' . __('Select', 'jobster') . '</option>';

                    for ($i = 0; $i < count($list); $i++) {
                        $list_value = get_post_meta($post->ID, $key, true);
                        print '
                                    <option value="' . $i . '" ' . selected($list_value != '' && $list_value == $i, true) . '>' . $list[$i] . '</option>';
                                }

                        print '
                                </select>';
                } else {
                    print '
                                <input type="text" name="' . $key . '" id="' . $key . '" value="' . esc_attr(get_post_meta($post->ID, $key, true)) . '" />';
                }
                print '
                            </div>
                        </td>';

                if ($counter % 3 == 0) {
                    print '
                    </tr>';
                }
            }
            print '
                </table>';
        }
    }
endif;

if (!function_exists('jobster_candidate_photo_render')): 
    function jobster_candidate_photo_render($post) {
        $photo_src = JOBSTER_PLUGIN_PATH . 'post-types/images/photo-placeholder.png';
        $photo_val = get_post_meta($post->ID, 'candidate_photo', true);
        $photo = wp_get_attachment_image_src($photo_val, 'pxp-icon');
        $has_image = '';

        if (is_array($photo)) {
            $has_image = 'pxp-has-image';
            $photo_src = $photo[0];
        }

        print '
            <input name="candidate_photo" id="candidate_photo" type="hidden" value="' . esc_attr($photo_val) . '">
            <div class="pxp-candidate-photo-placeholder-container ' . esc_attr($has_image) . '">
                <div class="pxp-candidate-photo-image-placeholder" style="background-image: url(' . esc_url($photo_src) . ');"></div>
                <div class="pxp-delete-candidate-photo-image"><span class="fa fa-trash-o"></span></div>
            </div>';
    }
endif;

if (!function_exists('jobster_candidate_cover_render')): 
    function jobster_candidate_cover_render($post) {
        $cover_src = JOBSTER_PLUGIN_PATH . 'post-types/images/cover-placeholder.png';
        $cover_val = get_post_meta($post->ID, 'candidate_cover', true);
        $cover = wp_get_attachment_image_src($cover_val, 'pxp-gallery');
        $has_image = '';

        $cover_type = get_post_meta($post->ID, 'candidate_cover_type', true);
        $cover_types = array(
            'n' => __('None', 'jobster'),
            'i' => __('Image', 'jobster'),
            'c' => __('Color', 'jobster')
        );

        $cover_color = get_post_meta($post->ID, 'candidate_cover_color', true);

        if (is_array($cover)) {
            $has_image = 'pxp-has-image';
            $cover_src = $cover[0];
        }

        print '
            <label for="candidate_cover_type" style="font-size: 11px; font-weight: 500; line-height: 1.4; text-transform: uppercase; display: inline-block; margin-bottom: calc(8px); padding: 0;">' . __('Cover Type', 'jobster') . '</label>
            <select id="candidate_cover_type" name="candidate_cover_type" style="width: 100%; box-sizing: border-box;">';
        foreach ($cover_types as $ct_key => $ct_value) {
            print '
                <option value="' . esc_attr($ct_key) . '" ' . selected($cover_type, $ct_key) . '>' . esc_attr($ct_value) . '</option>';
        }
        print '
            </select>

            <br><br>
            <label for="candidate_cover_color" style="font-size: 11px; font-weight: 500; line-height: 1.4; text-transform: uppercase; display: block; margin-bottom: calc(8px); padding: 0;">' . __('Cover Color', 'jobster') . '</label>
            <input type="text" name="candidate_cover_color" id="candidate_cover_color" class="pxp-color-field" value="' . esc_attr($cover_color) . '" />

            <br><br>
            <div>
                <label for="candidate_cover" style="font-size: 11px; font-weight: 500; line-height: 1.4; text-transform: uppercase; display: block; margin-bottom: calc(8px); padding: 0;">' . __('Cover Image', 'jobster') . '</label>
                <input name="candidate_cover" id="candidate_cover" type="hidden" value="' . esc_attr($cover_val) . '">
                <div class="pxp-candidate-cover-placeholder-container ' . esc_attr($has_image) . '">
                    <div class="pxp-candidate-cover-image-placeholder" style="background-image: url(' . esc_url($cover_src) . ');"></div>
                    <div class="pxp-delete-candidate-cover-image"><span class="fa fa-trash-o"></span></div>
                </div>
            </div>';
    }
endif;

if (!function_exists('jobster_candidate_featured_render')): 
    function jobster_candidate_featured_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <p class="meta-options">
                            <input type="hidden" name="candidate_featured" value="">
                            <input type="checkbox" name="candidate_featured" value="1" ';
                            if (esc_html(get_post_meta($post->ID, 'candidate_featured', true)) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="candidate_featured">' . __('Set as Featured', 'jobster') . '</label>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_candidate_work_render')):
    function jobster_candidate_work_render($post) {
        $work = get_post_meta($post->ID, 'candidate_work', true);

        $work_list = array();

        if ($work != '') {
            $work_data = json_decode(urldecode($work));

            if (isset($work_data)) {
                $work_list = $work_data->works;
            }
        }

        $description_settings = array(
            'teeny'         => true,
            'media_buttons' => false,
            'editor_height' => 240,
        );

        print '
            <input type="hidden" id="candidate_work" name="candidate_work" value="' . esc_attr($work) . '" />
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-candidate-work-list">';
        if (count($work_list) > 0) {
            foreach ($work_list as $work_item) {
                print '
                    <li class="list-group-item" 
                        data-title="' . esc_attr(urlencode($work_item->title)) . '" 
                        data-company="' . esc_attr(urlencode($work_item->company)) . '" 
                        data-period="' . esc_attr(urlencode($work_item->period)) . '" 
                        data-description="' . esc_attr(urlencode($work_item->description)) . '"
                    >
                        <div class="pxp-candidate-work-list-item">
                            <div class="pxp-candidate-work-list-item-title"><b>' . esc_html($work_item->title) . '</b></div>
                            <div class="pxp-candidate-work-list-item-company">' . esc_html($work_item->company) . '</div>
                            <div class="pxp-candidate-work-list-item-btns">
                                <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-edit-candidate-work"><span class="fa fa-pencil"></span></a>
                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-del-candidate-work"><span class="fa fa-trash-o"></span></a>
                            </div>
                        </div>
                    </li>';
            }
        }
        print '
                        </ul>
                    </td>
                </tr>
                <tr><td width="100%" valign="top">&nbsp;</td>
                <tr>
                    <td width="100%" valign="top"><input id="pxp-add-candidate-work-btn" type="button" class="button" value="' . esc_html__('Add Experience', 'jobster') . '" /></td>
                </tr>
            </table>
            <div class="pxp-candidate-new-work">
                <div class="pxp-candidate-new-work-container">
                    <div class="pxp-candidate-new-work-header"><b>' . esc_html__('New Work Experience', 'jobster') . '</b></div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="candidate_work_title">' . __('Job title', 'jobster') . '</label><br>
                                    <input name="candidate_work_title" id="candidate_work_title" type="text">
                                </div>
                            </td>
                            <td width="30%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="candidate_work_company">' . __('Company', 'jobster') . '</label><br>
                                    <input name="candidate_work_company" id="candidate_work_company" type="text">
                                </div>
                            </td>
                            <td width="20%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="candidate_work_period">' . __('Time period', 'jobster') . '</label><br>
                                    <input name="candidate_work_period" id="candidate_work_period" type="text">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="100%" colspan="3">
                                <div class="form-field pxp-is-custom">
                                    <label for="candidate_work_description">' . __('Description', 'jobster') . '</label><br>';
        wp_editor('', 'candidate_work_description', $description_settings);
        print '
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="form-field">
                        <button type="button" id="pxp-ok-work" class="button media-button button-primary">' . esc_html__('Add', 'jobster') . '</button>
                        <button type="button" id="pxp-cancel-work" class="button media-button button-default">' . esc_html__('Cancel', 'jobster') . '</button>
                    </div>
                </div>
            </div>';
    }
endif;

if (!function_exists('jobster_candidate_edu_render')):
    function jobster_candidate_edu_render($post) {
        $edu = get_post_meta($post->ID, 'candidate_edu', true);

        $edu_list = array();

        if ($edu != '') {
            $edu_data = json_decode(urldecode($edu));

            if (isset($edu_data)) {
                $edu_list = $edu_data->edus;
            }
        }

        print '
            <input type="hidden" id="candidate_edu" name="candidate_edu" value="' . esc_attr($edu) . '" />
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-candidate-edu-list">';
        if (count($edu_list) > 0) {
            foreach ($edu_list as $edu_item) {
                print '
                            <li class="list-group-item" 
                                data-title="' . esc_attr($edu_item->title) . '" 
                                data-school="' . esc_attr($edu_item->school) . '" 
                                data-period="' . esc_attr($edu_item->period) . '" 
                                data-description="' . esc_attr($edu_item->description) . '"
                            >
                                <div class="pxp-candidate-edu-list-item">
                                    <div class="pxp-candidate-edu-list-item-title"><b>' . esc_html($edu_item->title) . '</b></div>
                                    <div class="pxp-candidate-edu-list-item-school">' . esc_html($edu_item->school) . '</div>
                                    <div class="pxp-candidate-edu-list-item-btns">
                                        <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-edit-candidate-edu"><span class="fa fa-pencil"></span></a>
                                        <a href="javascript:void(0);" class="pxp-list-del-btn pxp-del-candidate-edu"><span class="fa fa-trash-o"></span></a>
                                    </div>
                                </div>
                            </li>';
            }
        }
        print '
                        </ul>
                    </td>
                </tr>
                <tr><td width="100%" valign="top">&nbsp;</td>
                <tr>
                    <td width="100%" valign="top"><input id="pxp-add-candidate-edu-btn" type="button" class="button" value="' . esc_html__('Add Education', 'jobster') . '" /></td>
                </tr>
            </table>
            <div class="pxp-candidate-new-edu">
                <div class="pxp-candidate-new-edu-container">
                    <div class="pxp-candidate-new-edu-header"><b>' . esc_html__('New Education', 'jobster') . '</b></div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="candidate_edu_title">' . __('Specialization/Course of study', 'jobster') . '</label><br>
                                    <input name="candidate_edu_title" id="candidate_edu_title" type="text">
                                </div>
                            </td>
                            <td width="30%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="candidate_edu_school">' . __('Institution', 'jobster') . '</label><br>
                                    <input name="candidate_edu_school" id="candidate_edu_school" type="text">
                                </div>
                            </td>
                            <td width="20%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="candidate_edu_period">' . __('Time period', 'jobster') . '</label><br>
                                    <input name="candidate_edu_period" id="candidate_edu_period" type="text">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="100%" colspan="3">
                                <div class="form-field pxp-is-custom">
                                    <label for="candidate_edu_description">' . __('Description', 'jobster') . '</label><br>
                                    <textarea name="candidate_edu_description" id="candidate_edu_description" style="width: 100%; height: 100px;"></textarea>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="form-field">
                        <button type="button" id="pxp-ok-edu" class="button media-button button-primary">' . esc_html__('Add', 'jobster') . '</button>
                        <button type="button" id="pxp-cancel-edu" class="button media-button button-default">' . esc_html__('Cancel', 'jobster') . '</button>
                    </div>
                </div>
            </div>';
    }
endif;

if (!function_exists('jobster_candidate_cv_render')): 
    function jobster_candidate_cv_render($post) {
        $cv_val = get_post_meta($post->ID, 'candidate_cv', true);
        $cv = wp_get_attachment_url($cv_val);

        $item_class = '';
        $filename = '';
        if (!empty($cv)) {
            $item_class = 'pxp-show';
            $filename = basename($cv);
        }

        print '
            <input name="candidate_cv" id="candidate_cv" type="hidden" value="' . esc_attr($cv_val) . '">
            <div class="list-group pxp-candidate-cv-wrapper">
                <div class="list-group-item pxp-candidate-cv-container ' . esc_attr($item_class) . '">
                    <div class="pxp-candidate-cv-filename">' . esc_html($filename) . '</div>
                    <div class="pxp-candidate-cv-btns">
                        <a href="' . esc_url($cv) . '" class="pxp-list-edit-btn pxp-download-candidate-cv"><span class="fa fa-file-text-o"></span></a>
                        <a href="javascript:void(0);" class="pxp-list-del-btn pxp-del-candidate-cv"><span class="fa fa-trash-o"></span></a>
                    </div>
                </div>
            </div>
            <input id="pxp-add-candidate-cv-btn" type="button" class="button" value="' . esc_html__('Upload Resume', 'jobster') . '" />';
    }
endif;

if (!function_exists('jobster_candidate_files_render')): 
    function jobster_candidate_files_render($post) {
        $files = get_post_meta($post->ID, 'candidate_files', true);

        $files_list = array();

        if ($files != '') {
            $files_data = json_decode(urldecode($files));

            if (isset($files_data)) {
                $files_list = $files_data->files;
            }
        }

        print '
            <input type="hidden" id="candidate_files" name="candidate_files" value="' . esc_attr($files) . '" />
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-candidate-files-list">';
        if (count($files_list) > 0) {
            foreach ($files_list as $files_item) {
                print '
                            <li class="list-group-item" 
                                data-name="' . esc_attr($files_item->name) . '" 
                                data-id="' . esc_attr($files_item->id) . '" 
                                data-url="' . esc_url($files_item->url) . '" 
                            >
                                <div class="pxp-candidate-files-list-item">
                                    <div class="pxp-candidate-files-list-item-name">' . esc_html($files_item->name) . '</div>
                                    <div class="pxp-candidate-files-btns">
                                        <a href="' . esc_url($files_item->url) . '" class="pxp-list-edit-btn"><span class="fa fa-file-text-o"></span></a>
                                        <a href="javascript:void(0);" class="pxp-list-del-btn pxp-del-candidate-file"><span class="fa fa-trash-o"></span></a>
                                    </div>
                                </div>
                            </li>';
            }
        }
        print '
                        </ul>
                    </td>
                </tr>
                <tr><td width="100%" valign="top">&nbsp;</td>
                <tr>
                    <td width="100%" valign="top"><input id="pxp-add-candidate-file-btn" type="button" class="button" value="' . esc_html__('Add File', 'jobster') . '" /></td>
                </tr>
            </table>
            <div class="pxp-candidate-new-file">
                <div class="pxp-candidate-new-file-container">
                    <div class="pxp-candidate-new-file-header"><b>' . esc_html__('New File', 'jobster') . '</b></div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="candidate_file_name">' . __('Name', 'jobster') . '</label><br>
                                    <input name="candidate_file_name" id="candidate_file_name" type="text">
                                </div>
                            </td>
                            <td width="50%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <input type="hidden" id="candidate_file_id" name="candidate_file_id" />
                                    <input type="hidden" id="candidate_file_url" name="candidate_file_url" />
                                    <label for="pxp-candidate-new-file-upload-btn">&nbsp;</label><br>
                                    <input id="pxp-candidate-new-file-upload-btn" type="button" class="button" value="' . esc_html__('Upload File', 'jobster') . '" />
                                    <span id="candidate_filename"></span>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="form-field">
                        <button type="button" id="pxp-ok-file" class="button media-button button-primary">' . esc_html__('Add', 'jobster') . '</button>
                        <button type="button" id="pxp-cancel-file" class="button media-button button-default">' . esc_html__('Cancel', 'jobster') . '</button>
                    </div>
                </div>
            </div>';
    }
endif;

if (!function_exists('jobster_candidate_gallery_render')): 
    function jobster_candidate_gallery_render($post) {
        $photos = get_post_meta($post->ID, 'candidate_gallery', true);
        $ids = explode(',', $photos);

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="candidate_gallery_title">' . __('Title', 'jobster') . '</label><br>
                            <input name="candidate_gallery_title" id="candidate_gallery_title" type="text" value="' . esc_attr(get_post_meta($post->ID, 'candidate_gallery_title', true)) . '" placeholder="' . __('E.g. My Work', 'jobster') . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>
            <input type="hidden" id="candidate_gallery" name="candidate_gallery" value="' . esc_attr($photos) . '" />
            <ul class="list-group" id="candidate-gallery-list">';
        foreach ($ids as $id) {
            if ($id != '') {
                $photo_src = wp_get_attachment_image_src($id, 'pxp-thmb');
                $photo_info = jobster_get_attachment($id);

                print '
                <li class="list-group-item" data-id="' . esc_attr($id) . '">
                    <div class="pxp-candidate-gallery-list-item">
                        <img src="' . esc_url($photo_src[0]) . '" />
                        <div class="list-group-item-info">
                            <div class="list-group-item-info-title">' . $photo_info['title'] . '</div>
                            <div class="list-group-item-info-caption">' . $photo_info['caption'] . '</div>
                        </div>
                        <div class="pxp-list-item-btns">
                            <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-candidate-gallery-edit-photo-btn"><span class="fa fa-pencil"></span></a>
                            <a href="javascript:void(0);" class="pxp-list-del-btn pxp-candidate-gallery-delete-photo-btn"><span class="fa fa-trash-o"></span></a>
                        </div>
                    </div>
                </li>';
            }
        }
        print '
            </ul>
            <input id="pxp-add-candidate-gallery-photo-btn" type="button" class="button" value="' . __('Add Photos', 'jobster') . '" />';
    }
endif;

if (!function_exists('jobster_candidate_video_render')): 
    function jobster_candidate_video_render($post) {
        $video = get_post_meta($post->ID, 'candidate_video', true);

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="candidate_video_title">' . __('Title', 'jobster') . '</label><br>
                            <input name="candidate_video_title" id="candidate_video_title" type="text" value="' . esc_attr(get_post_meta($post->ID, 'candidate_video_title', true)) . '" placeholder="' . __('E.g. About Me', 'jobster') . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="candidate_video">' . __('YouTube Video ID', 'jobster') . '</label><br />
                            <input type="text" id="candidate_video" name="candidate_video" placeholder="' . __('E.g. Ur1Nrz23sSI', 'jobster') . '" value="' . esc_attr($video) . '">
                            <p class="help" style="margin-top: 5px; font-size: 11px !important;">E.g. <span style="color: #999;">https://www.youtube.com/watch?v=</span><strong style="color: green; font-style: normal;">Ur1Nrz23sSI</strong></p>
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_candidate_social_media_render')): 
    function jobster_candidate_social_media_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="candidate_facebook">' . __('Facebook', 'jobster') . '</label>
                            <input name="candidate_facebook" id="candidate_facebook" type="url" value="' . esc_attr(get_post_meta($post->ID, 'candidate_facebook', true)) . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="candidate_twitter">' . __('Twitter', 'jobster') . '</label>
                            <input name="candidate_twitter" id="candidate_twitter" type="url" value="' . esc_attr(get_post_meta($post->ID, 'candidate_twitter', true)) . '">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="candidate_instagram">' . __('Instagram', 'jobster') . '</label>
                            <input name="candidate_instagram" id="candidate_instagram" type="url" value="' . esc_attr(get_post_meta($post->ID, 'candidate_instagram', true)) . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="candidate_linkedin">' . __('Linkedin', 'jobster') . '</label>
                            <input name="candidate_linkedin" id="candidate_linkedin" type="url" value="' . esc_attr(get_post_meta($post->ID, 'candidate_linkedin', true)) . '">
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_candidate_job_alerts_render')): 
    function jobster_candidate_job_alerts_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <p class="meta-options">
                            <input type="hidden" name="candidate_job_alerts" value="">
                            <input type="checkbox" name="candidate_job_alerts" value="1" ';
                            if (esc_html(get_post_meta($post->ID, 'candidate_job_alerts', true)) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="candidate_job_alerts">' . __('Notify me when New Jobs are posted', 'jobster') . '</label>
                        </p>
                    </td>
                </tr>
            </table>
            <br>
            <b>' . esc_html__('Receive job alerts from:', 'jobster') . '</b>
            <br><br>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="candidate_job_alerts_location">' . __('Location', 'jobster') . '</label>
                            <select name="candidate_job_alerts_location[]" id="candidate_job_alerts_location" multiple>';
        $location_data = get_post_meta($post->ID, 'candidate_job_alerts_location', true);
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
        foreach ($locations as $loc_key => $loc_value) {
            print '
                                <option value="' . esc_attr($loc_key) . '" ' . selected(is_array($location_data) && in_array($loc_key, $location_data), true, false) . '>' . esc_attr($loc_value) . '</option>';
        }
        print '
                            </select>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="candidate_job_alerts_category">' . __('Category', 'jobster') . '</label>
                            <select name="candidate_job_alerts_category[]" id="candidate_job_alerts_category" multiple>';
        $category_data = get_post_meta($post->ID, 'candidate_job_alerts_category', true);
        $category_terms = get_terms(
            array('job_category'),
            array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'hide_empty' => false
            )
        );
        foreach ($category_terms as $category_term) {
            print '
                                <option value="' . esc_attr($category_term->term_id) . '" ' . selected(is_array($category_data) && in_array($category_term->term_id, $category_data), true, false) . '>' . esc_html($category_term->name) . '</option>';
        }
        print '
                            </select>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="candidate_job_alerts_type">' . __('Type of Employment', 'jobster') . '</label>
                            <select name="candidate_job_alerts_type[]" id="candidate_job_alerts_type" multiple>';
        $type_data = get_post_meta($post->ID, 'candidate_job_alerts_type', true);
        $type_terms = get_terms(
            array('job_type'),
            array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'hide_empty' => false
            )
        );
        foreach ($type_terms as $type_term) {
            print '
                                <option value="' . esc_attr($type_term->term_id) . '" ' . selected(is_array($type_data) && in_array($type_term->term_id, $type_data), true, false) . '>' . esc_attr($type_term->name) . '</option>';
        }
        print '
                            </select>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="candidate_job_alerts_level">' . __('Experience Level', 'jobster') . '</label>
                            <select name="candidate_job_alerts_level[]" id="candidate_job_alerts_level" multiple>';
        $level_data = get_post_meta($post->ID, 'candidate_job_alerts_level', true);
        $level_terms = get_terms(
            array('job_level'),
            array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'hide_empty' => false
            )
        );
        foreach ($level_terms as $level_term) {
            print '
                                <option value="' . esc_attr($level_term->term_id) . '" ' . selected(is_array($level_data) && in_array($level_term->term_id, $level_data), true, false) . '>' . esc_attr($level_term->name) . '</option>';
        }
        print '
                            </select>
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_candidate_user_render')): 
    function jobster_candidate_user_render($post) {
        wp_nonce_field('jobster_causer', 'causer_noncename');

        $mypost        = $post->ID;
        $originalpost  = $post;
        $selected_user = get_post_meta($mypost, 'candidate_user', true);
        $users_list    = '';
        $args          = array('role' => '');

        $user_query = new WP_User_Query($args);

        foreach ($user_query->results as $user) {
            $is_company = jobster_user_is_company($user->ID);

            if (!$is_company) {
                $users_list .= '<option value="' . $user->ID . '"';
                if ($user->ID == $selected_user) {
                    $users_list .= ' selected';
                }
                $users_list .= '>' . $user->user_login . ' - ' . $user->first_name . ' ' . $user->last_name . '</option>';
            }
        }

        wp_reset_query();

        $post = $originalpost;

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="candidate_user">' . __('Assign a User', 'jobster') . '</label>
                            <select id="candidate_user" name="candidate_user">
                                <option value="">' . __('None', 'jobster') . '</option>
                                ' . $users_list . '
                            </select>
                        </div>
                    </td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_candidate_meta_save')): 
    function jobster_candidate_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['candidate_noncename']) && wp_verify_nonce($_POST['candidate_noncename'], 'jobster_candidate')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['candidate_title'])) {
            update_post_meta($post_id, 'candidate_title', sanitize_text_field($_POST['candidate_title']));
        }
        if (isset($_POST['candidate_email'])) {
            update_post_meta($post_id, 'candidate_email', sanitize_text_field($_POST['candidate_email']));
        }
        if (isset($_POST['candidate_phone'])) {
            update_post_meta($post_id, 'candidate_phone', sanitize_text_field($_POST['candidate_phone']));
        }
        if (isset($_POST['candidate_website'])) {
            update_post_meta($post_id, 'candidate_website', sanitize_text_field($_POST['candidate_website']));
        }
        if (isset($_POST['candidate_photo'])) {
            update_post_meta($post_id, 'candidate_photo', sanitize_text_field($_POST['candidate_photo']));
        }
        if (isset($_POST['candidate_cover_type'])) {
            update_post_meta($post_id, 'candidate_cover_type', sanitize_text_field($_POST['candidate_cover_type']));
        }
        if (isset($_POST['candidate_cover_color'])) {
            update_post_meta($post_id, 'candidate_cover_color', sanitize_text_field($_POST['candidate_cover_color']));
        }
        if (isset($_POST['candidate_cover'])) {
            update_post_meta($post_id, 'candidate_cover', sanitize_text_field($_POST['candidate_cover']));
        }
        if (isset($_POST['candidate_featured'])) {
            update_post_meta($post_id, 'candidate_featured', sanitize_text_field($_POST['candidate_featured']));
        }
        if (isset($_POST['candidate_work'])) {
            $work_list = array();
            $work_data_raw = urldecode($_POST['candidate_work']);
            $work_data = json_decode($work_data_raw);

            $work_data_encoded = '';

            $allow_tags = array(
                'br' => array(),
                'p' => array(
                    'style' => array()
                ),
                'strong' => array(),
                'em' => array(),
                'span' => array(
                    'style' => array()
                ),
                'del' => array(),
                'ul' => array(),
                'ol' => array(),
                'li' => array(
                    'style' => array()
                ),
                'a' => array(
                    'href' => array()
                ),
                'blockquote' => array(
                    'style' => array()
                )
            );

            if (isset($work_data)) {
                $new_data = new stdClass();
                $new_works = array();

                $work_list = $work_data->works;

                foreach ($work_list as $work_item) {
                    $new_work = new stdClass();

                    $new_work->title       = sanitize_text_field($work_item->title);
                    $new_work->company     = sanitize_text_field($work_item->company);
                    $new_work->period      = sanitize_text_field($work_item->period);
                    $new_work->description = wp_kses($work_item->description, $allow_tags);

                    array_push($new_works, $new_work);
                }

                $new_data->works = $new_works;

                $work_data_before = json_encode($new_data);
                $work_data_encoded = urlencode($work_data_before);
            }

            update_post_meta($post_id, 'candidate_work', $work_data_encoded);
        }
        if (isset($_POST['candidate_edu'])) {
            $edu_list = array();
            $edu_data_raw = urldecode($_POST['candidate_edu']);
            $edu_data = json_decode($edu_data_raw);

            $edu_data_encoded = '';

            if (isset($edu_data)) {
                $new_data_edu = new stdClass();
                $new_edus = array();

                $edu_list = $edu_data->edus;

                foreach ($edu_list as $edu_item) {
                    $new_edu = new stdClass();

                    $new_edu->title       = sanitize_text_field($edu_item->title);
                    $new_edu->school      = sanitize_text_field($edu_item->school);
                    $new_edu->period      = sanitize_text_field($edu_item->period);
                    $new_edu->description = sanitize_text_field($edu_item->description);

                    array_push($new_edus, $new_edu);
                }

                $new_data_edu->edus = $new_edus;

                $edu_data_before = json_encode($new_data_edu);
                $edu_data_encoded = urlencode($edu_data_before);
            }

            update_post_meta($post_id, 'candidate_edu', $edu_data_encoded);
        }
        if (isset($_POST['candidate_cv'])) {
            update_post_meta($post_id, 'candidate_cv', sanitize_text_field($_POST['candidate_cv']));
        }
        if (isset($_POST['candidate_files'])) {
            $files_list = array();
            $files_data_raw = urldecode($_POST['candidate_files']);
            $files_data = json_decode($files_data_raw);

            $files_data_encoded = '';

            if (isset($files_data)) {
                $new_data_files = new stdClass();
                $new_files = array();

                $files_list = $files_data->files;

                foreach ($files_list as $files_item) {
                    $new_file = new stdClass();

                    $new_file->name  = sanitize_text_field($files_item->name);
                    $new_file->id  = sanitize_text_field($files_item->id);
                    $new_file->url = sanitize_text_field($files_item->url);
                    

                    array_push($new_files, $new_file);
                }

                $new_data_files->files = $new_files;

                $files_data_before = json_encode($new_data_files);
                $files_data_encoded = urlencode($files_data_before);
            }

            update_post_meta($post_id, 'candidate_files', $files_data_encoded);
        }
        if (isset($_POST['candidate_gallery'])) {
            update_post_meta($post_id, 'candidate_gallery', sanitize_text_field($_POST['candidate_gallery']));
        }
        if (isset($_POST['candidate_gallery_title'])) {
            update_post_meta($post_id, 'candidate_gallery_title', sanitize_text_field($_POST['candidate_gallery_title']));
        }
        if (isset($_POST['candidate_video'])) {
            update_post_meta($post_id, 'candidate_video', sanitize_text_field($_POST['candidate_video']));
        }
        if (isset($_POST['candidate_video_title'])) {
            update_post_meta($post_id, 'candidate_video_title', sanitize_text_field($_POST['candidate_video_title']));
        }
        if (isset($_POST['candidate_facebook'])) {
            update_post_meta($post_id, 'candidate_facebook', sanitize_text_field($_POST['candidate_facebook']));
        }
        if (isset($_POST['candidate_twitter'])) {
            update_post_meta($post_id, 'candidate_twitter', sanitize_text_field($_POST['candidate_twitter']));
        }
        if (isset($_POST['candidate_instagram'])) {
            update_post_meta($post_id, 'candidate_instagram', sanitize_text_field($_POST['candidate_instagram']));
        }
        if (isset($_POST['candidate_linkedin'])) {
            update_post_meta($post_id, 'candidate_linkedin', sanitize_text_field($_POST['candidate_linkedin']));
        }
        if (isset($_POST['candidate_job_alerts'])) {
            update_post_meta($post_id, 'candidate_job_alerts', sanitize_text_field($_POST['candidate_job_alerts']));
        }
        if (isset($_POST['candidate_job_alerts_location']) && count($_POST['candidate_job_alerts_location']) > 0) {
            update_post_meta($post_id, 'candidate_job_alerts_location', jobster_sanitize_array($_POST['candidate_job_alerts_location']));
        } else {
            update_post_meta($post_id, 'candidate_job_alerts_location', []);
        }
        if (isset($_POST['candidate_job_alerts_category']) && count($_POST['candidate_job_alerts_category']) > 0) {
            update_post_meta($post_id, 'candidate_job_alerts_category', jobster_sanitize_array($_POST['candidate_job_alerts_category']));
        } else {
            update_post_meta($post_id, 'candidate_job_alerts_category', []);
        }
        if (isset($_POST['candidate_job_alerts_type']) && count($_POST['candidate_job_alerts_type']) > 0) {
            update_post_meta($post_id, 'candidate_job_alerts_type', jobster_sanitize_array($_POST['candidate_job_alerts_type']));
        } else {
            update_post_meta($post_id, 'candidate_job_alerts_type', []);
        }
        if (isset($_POST['candidate_job_alerts_level']) && count($_POST['candidate_job_alerts_level']) > 0) {
            update_post_meta($post_id, 'candidate_job_alerts_level', jobster_sanitize_array($_POST['candidate_job_alerts_level']));
        } else {
            update_post_meta($post_id, 'candidate_job_alerts_level', []);
        }
        if (isset($_POST['candidate_user'])) {
            update_post_meta($post_id, 'candidate_user', sanitize_text_field($_POST['candidate_user']));
        }

        $candidates_fields_settings = get_option('jobster_candidates_fields_settings');
        if (is_array($candidates_fields_settings)) {
            foreach ($candidates_fields_settings as $jvs_key => $jvs_value) {
                if (isset($_POST[$jvs_key])) {
                    update_post_meta($post_id, $jvs_key, sanitize_text_field($_POST[$jvs_key]));
                }
            }
        }
    }
endif;
add_action('save_post', 'jobster_candidate_meta_save');

if (!function_exists('jobster_get_candidate_locations_industries')): 
    function jobster_get_candidate_locations_industries() {
        $locations = get_terms(
            array(
                'taxonomy' => 'candidate_location',
                'orderby' => 'name',
                'hierarchical' => true,
                'hide_empty' => false,
            )
        );

        $top_level_locations = array();
        $children_locations  = array();
        foreach ($locations as $location) {
            if (empty($location->parent)) {
                $top_level_locations[] = $location;
            } else {
                $children_locations[$location->parent][] = $location;
            }
        }
        $location_terms = array();
        foreach ($top_level_locations as $top_location) {
            $location_terms[] = $top_location;
            if (array_key_exists($top_location->term_id, $children_locations)) {
                foreach ($children_locations[$top_location->term_id] as $child_location) {
                    $child_location->name = '---' . $child_location->name;
                    $location_terms[] = $child_location;
                }
            }
        }

        $industry_tax = array( 
            'candidate_industry'
        );
        $industry_args = array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false
        ); 
        $industry_terms = get_terms($industry_tax, $industry_args);

        echo json_encode(array(
            'getli' => true,
            'locations' => $location_terms,
            'industries' => $industry_terms
        ));
        exit();

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_get_candidate_locations_industries',
    'jobster_get_candidate_locations_industries'
);
add_action(
    'wp_ajax_jobster_get_candidate_locations_industries',
    'jobster_get_candidate_locations_industries'
);
?>