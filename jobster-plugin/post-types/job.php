<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Register job custom post type
 */
if (!function_exists('jobster_register_job_type')): 
    function jobster_register_job_type() {
        register_post_type('job', array(
            'labels' => array(
                'name'               => __('Jobs', 'jobster'),
                'singular_name'      => __('Job', 'jobster'),
                'add_new'            => __('Add New Job', 'jobster'),
                'add_new_item'       => __('Add Job', 'jobster'),
                'edit'               => __('Edit', 'jobster'),
                'edit_item'          => __('Edit Job', 'jobster'),
                'new_item'           => __('New Job', 'jobster'),
                'view'               => __('View', 'jobster'),
                'view_item'          => __('View Job', 'jobster'),
                'search_items'       => __('Search Jobs', 'jobster'),
                'not_found'          => __('No Jobs found', 'jobster'),
                'not_found_in_trash' => __('No Jobs found in Trash', 'jobster'),
                'parent'             => __('Parent Job', 'jobster'),
            ),
            'public'                => true,
            'exclude_from_search '  => false,
            'has_archive'           => true,
            'rewrite'               => array('slug' => _x('jobs', 'URL SLUG', 'jobster')),
            'supports'              => array('title', 'editor'),
            'show_in_rest'          => true,
            'can_export'            => true,
            'register_meta_box_cb'  => 'jobster_add_job_metaboxes',
            'menu_icon'             => 'dashicons-portfolio',
        ));

        // add job location taxonomy
        register_taxonomy('job_location', 'job', array(
            'labels' => array(
                'name'                       => __('Job Locations', 'jobster'),
                'singular_name'              => __('Job Location', 'jobster'),
                'search_items'               => __('Search Job Locations', 'jobster'),
                'popular_items'              => __('Popular Job Locations', 'jobster'),
                'all_items'                  => __('All Job Locations', 'jobster'),
                'edit_item'                  => __('Edit Job Location', 'jobster'),
                'update_item'                => __('Update Job Location', 'jobster'),
                'add_new_item'               => __('Add New Job Location', 'jobster'),
                'new_item_name'              => __('New Job Location Name', 'jobster'),
                'separate_items_with_commas' => __('Separate job locations with commas', 'jobster'),
                'add_or_remove_items'        => __('Add or remove job locations', 'jobster'),
                'choose_from_most_used'      => __('Choose from the most used job locations', 'jobster'),
                'not_found'                  => __('No job location found.', 'jobster'),
                'menu_name'                  => __('Job Locations', 'jobster'),
            ),
            'hierarchical'      => true,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'job-location'),
            'show_in_rest'      => true,
        ));

        // add job category taxonomy
        register_taxonomy('job_category', 'job', array(
            'labels' => array(
                'name'                       => __('Job Categories', 'jobster'),
                'singular_name'              => __('Job Category', 'jobster'),
                'search_items'               => __('Search Job Categories', 'jobster'),
                'popular_items'              => __('Popular Job Categories', 'jobster'),
                'all_items'                  => __('All Job Categories', 'jobster'),
                'edit_item'                  => __('Edit Job Category', 'jobster'),
                'update_item'                => __('Update Job Category', 'jobster'),
                'add_new_item'               => __('Add New Job Category', 'jobster'),
                'new_item_name'              => __('New Job Category Name', 'jobster'),
                'separate_items_with_commas' => __('Separate job categories with commas', 'jobster'),
                'add_or_remove_items'        => __('Add or remove job categories', 'jobster'),
                'choose_from_most_used'      => __('Choose from the most used job categories', 'jobster'),
                'not_found'                  => __('No job category found.', 'jobster'),
                'menu_name'                  => __('Job Categories', 'jobster'),
            ),
            'hierarchical'      => true,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'job-category'),
            'show_in_rest'      => true,
        ));

        // add job employment type taxonomy
        register_taxonomy('job_type', 'job', array(
            'labels' => array(
                'name'                       => __('Job Types', 'jobster'),
                'singular_name'              => __('Job Type', 'jobster'),
                'search_items'               => __('Search Job Types', 'jobster'),
                'popular_items'              => __('Popular Job Types', 'jobster'),
                'all_items'                  => __('All Job Types', 'jobster'),
                'edit_item'                  => __('Edit Job Type', 'jobster'),
                'update_item'                => __('Update Job Type', 'jobster'),
                'add_new_item'               => __('Add New Job Type', 'jobster'),
                'new_item_name'              => __('New Job Type Name', 'jobster'),
                'separate_items_with_commas' => __('Separate job types with commas', 'jobster'),
                'add_or_remove_items'        => __('Add or remove job types', 'jobster'),
                'choose_from_most_used'      => __('Choose from the most used job types', 'jobster'),
                'not_found'                  => __('No job type found.', 'jobster'),
                'menu_name'                  => __('Job Types', 'jobster'),
            ),
            'hierarchical'      => false,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'job-type'),
            'show_in_rest'      => true,
        ));

        // add job career level taxonomy
        register_taxonomy('job_level', 'job', array(
            'labels' => array(
                'name'                       => __('Job Levels', 'jobster'),
                'singular_name'              => __('Job Level', 'jobster'),
                'search_items'               => __('Search Job Levels', 'jobster'),
                'popular_items'              => __('Popular Job Levels', 'jobster'),
                'all_items'                  => __('All Job Levels', 'jobster'),
                'edit_item'                  => __('Edit Job Level', 'jobster'),
                'update_item'                => __('Update Job Level', 'jobster'),
                'add_new_item'               => __('Add New Job Level', 'jobster'),
                'new_item_name'              => __('New Job Level Name', 'jobster'),
                'separate_items_with_commas' => __('Separate job levels with commas', 'jobster'),
                'add_or_remove_items'        => __('Add or remove job levels', 'jobster'),
                'choose_from_most_used'      => __('Choose from the most used job levels', 'jobster'),
                'not_found'                  => __('No job level found.', 'jobster'),
                'menu_name'                  => __('Job Levels', 'jobster'),
            ),
            'hierarchical'      => false,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'job-level'),
            'show_in_rest'      => true,
        ));

        register_meta(
            'job_category', 
            'job_category_icon', 
            'jobster_sanitize_term_meta'
        );
    }
endif;
add_action('init', 'jobster_register_job_type');

if (!function_exists('jobster_change_job_default_title')): 
    function jobster_change_job_default_title($title) {
        $screen = get_current_screen();

        if ('job' == $screen->post_type) {
            $title = __('Add job title', 'jobster');
        }

        return $title;
    }
endif;
add_filter('enter_title_here', 'jobster_change_job_default_title');

if (!function_exists('jobster_add_job_metaboxes')): 
    function jobster_add_job_metaboxes() {
        add_meta_box('job-details-section', __('Job Details', 'jobster'), 'jobster_job_details_render', 'job', 'normal', 'default');
        add_meta_box('job-benefits-section', __('Benefits', 'jobster'), 'jobster_job_benefits_render', 'job', 'normal', 'default');
        add_meta_box('job-additional-info-section', __('Additional Info', 'jobster'), 'jobster_job_additional_info_render', 'job', 'normal', 'default');
        add_meta_box('job-action-section', __('Job Apply Action', 'jobster'), 'jobster_job_action_render', 'job', 'normal', 'default');
        add_meta_box('job-cover-section', __('Job Cover', 'jobster'), 'jobster_job_cover_render', 'job', 'side', 'default');
        add_meta_box('job-featured-section', __('Featured', 'jobster'), 'jobster_job_featured_render', 'job', 'side', 'default');
        add_meta_box('job-company-section', __('Company', 'jobster'), 'jobster_job_company_render', 'job', 'normal', 'default');
    }
endif;

if (!function_exists('jobster_job_details_render')):
    function jobster_job_details_render($post) {
        wp_nonce_field('jobster_job', 'job_noncename');

        $jobs_settings = get_option('jobster_jobs_settings');
        $validity_period =  isset($jobs_settings['jobster_job_validity_period_field'])
                            ? $jobs_settings['jobster_job_validity_period_field']
                            : '';

        $valid_until = get_post_meta($post->ID, 'job_valid', true);

        if ($validity_period != '' 
            && is_numeric($validity_period) 
            && intval($validity_period) > 0
            && empty($valid_until))
        {
            $valid_until = date('Y-m-d', strtotime("+$validity_period days"));
        }

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="30%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="job_experience">' . __('Required Experience', 'jobster') . '</label><br>
                            <input name="job_experience" id="job_experience" type="text" value="' . esc_attr(get_post_meta($post->ID, 'job_experience', true)) . '" placeholder="' . __('E.g. Minimum 1 year', 'jobster') . '">
                        </div>
                    </td>
                    <td width="30%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="job_salary">' . __('Salary', 'jobster') . '</label><br>
                            <input name="job_salary" id="job_salary" type="text" value="' . esc_attr(get_post_meta($post->ID, 'job_salary', true)) . '">
                        </div>
                    </td>
                    <td width="30%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="job_valid">' . __('Valid Until', 'jobster') . '</label><br>
                            <input name="job_valid" id="job_valid" type="text" class="datePicker" value="' . esc_attr($valid_until) . '" placeholder="' . __('YYYY-MM-DD', 'jobster') . '">
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_job_benefits_render')):
    function jobster_job_benefits_render($post) {
        $benefits = get_post_meta($post->ID, 'job_benefits', true);

        $benefits_list = array();

        if ($benefits != '') {
            $benefits_data = json_decode(urldecode($benefits));

            if (isset($benefits_data)) {
                $benefits_list = $benefits_data->benefits;
            }
        }

        print '
            <input type="hidden" id="job_benefits" name="job_benefits" value="' . esc_attr($benefits) . '" />
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-job-benefits-list">';
        if (count($benefits_list) > 0) {
            foreach ($benefits_list as $benefit) {
                $icon = wp_get_attachment_image_src($benefit->icon, 'pxp-thmb');
                $icon_src = JOBSTER_PLUGIN_PATH . 'post-types/images/photo-placeholder.png';

                if (is_array($icon)) {
                    $icon_src = $icon[0];
                }

                print '
                            <li class="list-group-item" 
                                data-title="' . esc_attr($benefit->title) . '" 
                                data-icon="' . esc_attr($benefit->icon) . '" 
                                data-src="' . esc_url($icon_src) . '"
                            >
                                <div class="pxp-job-benefits-list-item">
                                    <img src="' . esc_url($icon_src) . '">
                                    <div class="pxp-job-benefits-list-item-title">
                                        <b>' . esc_html($benefit->title) . '</b>
                                    </div>
                                    <div class="pxp-job-benefits-list-item-btns">
                                        <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-edit-job-benefit">
                                            <span class="fa fa-pencil"></span>
                                        </a>
                                        <a href="javascript:void(0);" class="pxp-list-del-btn pxp-del-job-benefit">
                                            <span class="fa fa-trash-o"></span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                ';
            }
        }
        print '
                        </ul>
                    </td>
                </tr>
                <tr><td width="100%" valign="top">&nbsp;</td>
                <tr>
                    <td width="100%" valign="top">
                        <input id="pxp-add-job-benefit-btn" type="button" class="button" value="' . esc_html__('Add Benefit', 'jobster') . '" />
                    </td>
                </tr>
            </table>
            <div class="pxp-job-new-benefit">
                <div class="pxp-job-new-benefit-container">
                    <div class="pxp-job-new-benefit-header"><b>' . esc_html__('New Benefit', 'jobster') . '</b></div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="job_benefit_title">' . __('Title', 'jobster') . '</label><br>
                                    <input name="job_benefit_title" id="job_benefit_title" type="text">
                                </div>
                            </td>
                            <td width="50%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="50%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="job_benefit_icon">' . esc_html__('Icon', 'jobster') . '</label>
                                    <input type="hidden" id="job_benefit_icon" name="job_benefit_icon">
                                    <div class="pxp-job-benefit-icon-placeholder-container">
                                        <div class="pxp-job-benefit-icon-placeholder" style="background-image: url(' . esc_url(JOBSTER_PLUGIN_PATH . 'post-types/images/photo-placeholder.png') . ');"></div>
                                        <div class="pxp-delete-job-benefit-icon"><span class="fa fa-trash-o"></span></div>
                                    </div>
                                </div>
                            </td>
                            <td width="50%">&nbsp;</td>
                        </tr>
                    </table>
                    <div class="form-field">
                        <button type="button" id="pxp-ok-benefit" class="button media-button button-primary">' . esc_html__('Add', 'jobster') . '</button>
                        <button type="button" id="pxp-cancel-benefit" class="button media-button button-default">' . esc_html__('Cancel', 'jobster') . '</button>
                    </div>
                </div>
            </div>
        ';
    }
endif;

if (!function_exists('jobster_job_additional_info_render')):
    function jobster_job_additional_info_render($post) {
        $jobs_fields_settings = get_option('jobster_jobs_fields_settings');
        $counter = 0;

        if (is_array($jobs_fields_settings)) {
            uasort($jobs_fields_settings, 'jobster_compare_position');

            print '
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>';
            foreach ($jobs_fields_settings as $key => $value) {
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

if (!function_exists('jobster_job_action_render')): 
    function jobster_job_action_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="job_company">' . __('Apply Job External URL', 'jobster') . '</label><br />
                            <input name="job_action" id="job_action" type="text" value="' . esc_attr(get_post_meta($post->ID, 'job_action', true)) . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_job_cover_render')): 
    function jobster_job_cover_render($post) {
        $cover_src = JOBSTER_PLUGIN_PATH . 'post-types/images/cover-placeholder.png';
        $cover_val = get_post_meta($post->ID, 'job_cover', true);
        $cover = wp_get_attachment_image_src($cover_val, 'pxp-gallery');
        $has_image = '';

        if (is_array($cover)) {
            $has_image = 'pxp-has-image';
            $cover_src = $cover[0];
        }

        print '
            <input name="job_cover" id="job_cover" type="hidden" value="' . esc_attr($cover_val) . '">
            <div class="pxp-job-cover-placeholder-container ' . esc_attr($has_image) . '">
                <div class="pxp-job-cover-image-placeholder" style="background-image: url(' . esc_url($cover_src) . ');"></div>
                <div class="pxp-delete-job-cover-image"><span class="fa fa-trash-o"></span></div>
            </div>';
    }
endif;

if (!function_exists('jobster_job_featured_render')): 
    function jobster_job_featured_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <p class="meta-options">
                            <input type="hidden" name="job_featured" value="">
                            <input type="checkbox" name="job_featured" value="1" ';
                            if (esc_html(get_post_meta($post->ID, 'job_featured', true)) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="job_featured">' . __('Set as Featured', 'jobster') . '</label>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_job_company_render')): 
    function jobster_job_company_render($post) {
        $company_list = '';
        $selected_company = esc_html(get_post_meta($post->ID, 'job_company', true));

        $args = array(
            'post_type' => 'company',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );

        $company_selection = new WP_Query($args);
        $company_selection_arr  = get_object_vars($company_selection);

        if (is_array($company_selection_arr['posts']) && count($company_selection_arr['posts']) > 0) {
            foreach ($company_selection_arr['posts'] as $company) {
                $company_list .= '<option value="' . esc_attr($company->ID) . '"';
                if ($company->ID == $selected_company) {
                    $company_list .= ' selected';
                }
                $company_list .= '>' . $company->post_title . '</option>';
            }
        }

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="job_company">' . __('Assign a Company', 'jobster') . '</label><br />
                            <select id="job_company" name="job_company">
                                <option value="">' . __('None', 'jobster') . '</option>
                                ' . $company_list . '
                            </select>
                        </div>
                    </td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>';
    }
endif;

/**
 * Sanitize data
 */
if (!function_exists('jobster_sanitize_term_meta')): 
    function jobster_sanitize_term_meta($value) {
        return sanitize_text_field($value);
    }
endif;

/**
 * Getter for job category icon type
 */
if (!function_exists('jobster_get_job_category_icon_type')): 
    function jobster_get_job_category_icon_type($term_id) {
        $value = get_term_meta($term_id, 'job_category_icon_type', true);
        $value = jobster_sanitize_term_meta($value);

        return $value;
    }
endif;

/**
 * Getter for job category icon
 */
if (!function_exists('jobster_get_job_category_icon')): 
    function jobster_get_job_category_icon($term_id) {
        $value = get_term_meta($term_id, 'job_category_icon', true);
        $value = jobster_sanitize_term_meta($value);

        return $value;
    }
endif;

/**
 * Add job category icon type custom field
 */
if (!function_exists('jobster_add_job_category_icon_type')): 
    function jobster_add_job_category_icon_type() { ?>
        <?php wp_nonce_field(basename(__FILE__), 'term_meta_nonce'); ?>
        <div class="form-field term-meta-text-wrap">
            <label for="job_category_icon_type">
                <?php esc_html_e('Icon Type', 'jobster'); ?>
            </label>
            <select 
                name="job_category_icon_type" 
                id="job_category_icon_type"
            >
                <option value="font">
                    <?php esc_html_e('Font Awesome', 'jobster'); ?>
                </option>
                <option value="image">
                    <?php esc_html_e('Image', 'jobster'); ?>
                </option>
            </select>
        </div>
    <?php }
endif;
add_action('job_category_add_form_fields', 'jobster_add_job_category_icon_type');

/**
 * Add job category icon custom field
 */
if (!function_exists('jobster_add_job_category_icon')): 
    function jobster_add_job_category_icon() { ?>
        <?php wp_nonce_field(basename(__FILE__), 'term_meta_nonce'); ?>
        <div class="form-field term-meta-text-wrap">
            <label for="job_category_icon">
                <?php esc_html_e('Icon', 'jobster'); ?>
            </label>
            <input 
                type="hidden" 
                name="job_category_icon" 
                id="job_category_icon" 
                value="" 
                class="pxp-icons-field"
            >
            <a class="button button-secondary pxp-open-icons">
                <?php echo esc_html('Browse Icons...', 'jobster'); ?>
            </a>
            <div 
                class="pxp-job-category-image-placeholder-container" 
                style="display: none;"
            >
                <div 
                    class="pxp-job-category-image-placeholder" 
                    style="background-image: url(<?php echo JOBSTER_PLUGIN_PATH . 'post-types/images/logo-placeholder.png'; ?>);"
                ></div>
                <div class="pxp-delete-job-category-image"><span class="fa fa-trash-o"></span></div>
            </div>
        </div>
    <?php }
endif;
add_action('job_category_add_form_fields', 'jobster_add_job_category_icon');

/**
 * Edit job category icon type custom field
 */
if (!function_exists('jobster_edit_job_category_icon_type')): 
    function jobster_edit_job_category_icon_type($term) { ?>
        <?php $value = jobster_get_job_category_icon_type($term->term_id);

        if (!$value) {
            $value = '';
        } ?>

        <tr class="form-field term-meta-text-wrap">
            <th scope="row">
                <label for="job_category_icon">
                    <?php esc_html_e('Icon Type', 'jobster'); ?>
                </label>
            </th>
            <td>
                <?php wp_nonce_field(basename(__FILE__), 'term_meta_nonce'); ?>
                <select 
                    name="job_category_icon_type" 
                    id="job_category_icon_type"
                >
                    <option 
                        value="font"
                        <?php selected($value == 'font' || $value == ''); ?>
                    >
                        <?php esc_html_e('Font Awesome', 'jobster'); ?>
                    </option>
                    <option 
                        value="image" 
                        <?php selected($value == 'image'); ?>
                    >
                        <?php esc_html_e('Image', 'jobster'); ?>
                    </option>
                </select>
            </td>
        </tr>
    <?php }
endif;
add_action('job_category_edit_form_fields', 'jobster_edit_job_category_icon_type');

/**
 * Edit job category icon custom field
 */
if (!function_exists('jobster_edit_job_category_icon')): 
    function jobster_edit_job_category_icon($term) {
        $type_value = jobster_get_job_category_icon_type($term->term_id);
        $value = jobster_get_job_category_icon($term->term_id);

        if (!$value) {
            $value = '';
        }
        if (!$type_value) {
            $type_value = '';
        }
        if ($type_value == 'image') {
            $image_src = JOBSTER_PLUGIN_PATH . 'post-types/images/logo-placeholder.png';
            $image = wp_get_attachment_image_src($value, 'pxp-icon');
            $has_image = '';

            if (is_array($image)) {
                $has_image = 'pxp-has-image';
                $image_src = $image[0];
            }
        } ?>

        <tr class="form-field term-meta-text-wrap">
            <th scope="row">
                <label for="job_category_icon">
                    <?php esc_html_e('Icon', 'jobster'); ?>
                </label>
            </th>
            <td>
                <?php wp_nonce_field(basename(__FILE__), 'term_meta_nonce'); ?>
                <input 
                    type="hidden" 
                    name="job_category_icon" 
                    id="job_category_icon" 
                    value="<?php echo esc_attr($value); ?>" 
                    class="pxp-icons-field"
                >
                <a 
                    class="button button-secondary pxp-open-icons" 
                    <?php if ($type_value == 'image') { ?>
                        style="display: none;"
                    <?php } ?>
                >
                    <?php echo esc_html('Browse Icons...', 'jobster'); ?>
                </a>
                <div 
                    class="pxp-job-category-image-placeholder-container <?php echo esc_attr($has_image); ?>" 
                    <?php if ($type_value == 'font' || $type_value == '') { ?>
                        style="display: none;"
                    <?php } ?>
                >
                    <div 
                        class="pxp-job-category-image-placeholder" 
                        style="background-image: url(<?php echo esc_url($image_src); ?>);"
                    ></div>
                    <div class="pxp-delete-job-category-image"><span class="fa fa-trash-o"></span></div>
                </div>
            </td>
        </tr>
    <?php }
endif;
add_action('job_category_edit_form_fields', 'jobster_edit_job_category_icon');

/**
 * Save job category icon type custom field
 */
if (!function_exists('jobster_save_job_category_icon_type')): 
    function jobster_save_job_category_icon_type($term_id) {
        if (!isset($_POST['term_meta_nonce']) 
            || !wp_verify_nonce($_POST['term_meta_nonce'], basename(__FILE__))) {
            return;
        }

        $old_value = jobster_get_job_category_icon_type($term_id);
        $new_value = isset($_POST['job_category_icon_type'])
                    ? jobster_sanitize_term_meta($_POST['job_category_icon_type'])
                    : '';

        if ($old_value && '' === $new_value) {
            delete_term_meta($term_id, 'job_category_icon_type');
        } else if ($old_value !== $new_value) {
            update_term_meta($term_id, 'job_category_icon_type', $new_value);
        }
    }
endif;
add_action('edit_job_category', 'jobster_save_job_category_icon_type');
add_action('create_job_category', 'jobster_save_job_category_icon_type');

/**
 * Save job category icon custom field
 */
if (!function_exists('jobster_save_job_category_icon')): 
    function jobster_save_job_category_icon($term_id) {
        if (!isset($_POST['term_meta_nonce']) 
            || !wp_verify_nonce($_POST['term_meta_nonce'], basename(__FILE__))) {
            return;
        }

        $old_value = jobster_get_job_category_icon($term_id);
        $new_value = isset($_POST['job_category_icon'])
                    ? jobster_sanitize_term_meta($_POST['job_category_icon'])
                    : '';

        if ($old_value && '' === $new_value) {
            delete_term_meta($term_id, 'job_category_icon');
        } else if ($old_value !== $new_value) {
            update_term_meta($term_id, 'job_category_icon', $new_value);
        }
    }
endif;
add_action('edit_job_category', 'jobster_save_job_category_icon');
add_action('create_job_category', 'jobster_save_job_category_icon');

if (!function_exists('jobster_job_meta_save')): 
    function jobster_job_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['job_noncename']) && wp_verify_nonce($_POST['job_noncename'], 'jobster_job')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['job_experience'])) {
            update_post_meta($post_id, 'job_experience', sanitize_text_field($_POST['job_experience']));
        }
        if (isset($_POST['job_salary'])) {
            update_post_meta($post_id, 'job_salary', sanitize_text_field($_POST['job_salary']));
        }
        if (isset($_POST['job_valid'])) {
            update_post_meta($post_id, 'job_valid', sanitize_text_field($_POST['job_valid']));
        }
        if (isset($_POST['job_action'])) {
            update_post_meta($post_id, 'job_action', sanitize_text_field($_POST['job_action']));
        }
        if (isset($_POST['job_cover'])) {
            update_post_meta($post_id, 'job_cover', sanitize_text_field($_POST['job_cover']));
        }
        if (isset($_POST['job_featured'])) {
            update_post_meta($post_id, 'job_featured', sanitize_text_field($_POST['job_featured']));
        }
        if (isset($_POST['job_company'])) {
            update_post_meta($post_id, 'job_company', sanitize_text_field($_POST['job_company']));
        }

        $jobs_fields_settings = get_option('jobster_jobs_fields_settings');
        if (is_array($jobs_fields_settings)) {
            foreach ($jobs_fields_settings as $jvs_key => $jvs_value) {
                if (isset($_POST[$jvs_key])) {
                    update_post_meta($post_id, $jvs_key, sanitize_text_field($_POST[$jvs_key]));
                }
            }
        }

        if (isset($_POST['job_benefits'])) {
            $benefits_list = array();
            $benefits_data_raw = urldecode($_POST['job_benefits']);
            $benefits_data = json_decode($benefits_data_raw);

            $benefits_data_encoded = '';

            if (isset($benefits_data)) {
                $new_data_benefits = new stdClass();
                $new_benefits = array();

                $benefits_list = $benefits_data->benefits;

                foreach ($benefits_list as $benefits_item) {
                    $new_benefit = new stdClass();

                    $new_benefit->title = sanitize_text_field($benefits_item->title);
                    $new_benefit->icon  = sanitize_text_field($benefits_item->icon);

                    array_push($new_benefits, $new_benefit);
                }

                $new_data_benefits->benefits = $new_benefits;

                $benefits_data_before = json_encode($new_data_benefits);
                $benefits_data_encoded = urlencode($benefits_data_before);
            }

            update_post_meta($post_id, 'job_benefits', $benefits_data_encoded);
        }
    }
endif;
add_action('save_post', 'jobster_job_meta_save');

if (!function_exists('jobster_get_job_locations_categories')): 
    function jobster_get_job_locations_categories() {
        $locations = get_terms(
            array(
                'taxonomy' => 'job_location',
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

        $category_tax = array( 
            'job_category'
        );
        $category_args = array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false
        ); 
        $category_terms = get_terms($category_tax, $category_args);

        echo json_encode(array(
            'getlc' => true,
            'locations' => $location_terms,
            'categories' => $category_terms
        ));
        exit();

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_get_job_locations_categories',
    'jobster_get_job_locations_categories'
);
add_action(
    'wp_ajax_jobster_get_job_locations_categories',
    'jobster_get_job_locations_categories'
);

if (!function_exists('jobster_get_job_locations')): 
    function jobster_get_job_locations() {
        $loc_terms = get_terms(
            array(
                'taxonomy' => 'job_location',
                'orderby' => 'name',
                'hierarchical' => true,
                'hide_empty' => false,
            )
        );

        if (is_array($loc_terms) && count($loc_terms)) {
            $locations = array();

            $top_level_locations = array();
            $children_locations  = array();
            foreach ($loc_terms as $location) {
                if (empty($location->parent)) {
                    $top_level_locations[] = $location;
                } else {
                    $children_locations[$location->parent][] = $location;
                }
            }
            foreach ($top_level_locations as $top_location) {
                $location = new stdClass();
                $location->id = $top_location->term_id;
                $location->name = $top_location->name;
                array_push($locations, $location);
                if (array_key_exists($top_location->term_id, $children_locations)) {
                    foreach ($children_locations[$top_location->term_id] as $child_location) {
                        $location = new stdClass();
                        $location->id = $child_location->term_id;
                        $location->name = '---' . $child_location->name;
                        array_push($locations, $location);
                    }
                }
            }

            return urlencode(json_encode($locations, true));
        } else {
            return '';
        }
    }
endif;

add_filter('manage_job_posts_columns', function ($defaults) {
    $date  = $defaults['date'];
    unset($defaults['date']);

    $defaults['company'] = __('Company', 'jobster');
    $defaults['date']     = $date;

    return $defaults;
});

add_action('manage_job_posts_custom_column', function ($column_name, $post_id) {
    if ($column_name == 'company') {
        $company_id = get_post_meta($post_id, 'job_company', true);

        if (!empty($company_id)) {
            $company_name = get_the_title($company_id);
            $company_edit_url = get_edit_post_link($company_id); ?>

            <a href="<?php echo esc_url($company_edit_url); ?>">
                <?php echo esc_html($company_name); ?>
            </a>
        <?php }
    }
}, 10, 2);

if (!function_exists('jobster_send_job_alerts')): 
    function jobster_send_job_alerts($post_id, $post, $update, $post_before) {
        if ($post->post_status === 'publish' 
            && $post->post_type === 'job'
            && isset($post_before) 
            && $post_before->post_status !== 'publish'
        ) {
            $jobs_settings = get_option('jobster_jobs_settings');
            if (isset($jobs_settings['jobster_job_alert_field']) 
                && $jobs_settings['jobster_job_alert_field'] == '1'
            ) {
                $location = wp_get_post_terms($post->ID, 'job_location');
                $location_id = $location ? $location[0]->term_id : '';
                $category = wp_get_post_terms($post->ID, 'job_category');
                $category_id = $category ? $category[0]->term_id : '';
                $type = wp_get_post_terms($post->ID, 'job_type');
                $type_id = $type ? $type[0]->term_id : '';
                $level = wp_get_post_terms($post->ID, 'job_level');
                $level_id = $level ? $level[0]->term_id : '';

                $job_data = array(
                    'id' => $post->ID,
                    'location' => $location_id,
                    'category' => $category_id,
                    'type' => $type_id,
                    'level' => $level_id,
                );

                $candidates = jobster_get_job_alerts_candidates($job_data);

                foreach ($candidates->posts as $candidate) {
                    jobster_send_jobs_alerts_email($job_data, $candidate);
                }
            }
        }
    }
endif;
add_action('wp_after_insert_post', 'jobster_send_job_alerts', 10, 4);

if (!function_exists('jobster_get_job_alerts_candidates')): 
    function jobster_get_job_alerts_candidates($job_data) {
        $criteria = array('relation' => 'AND');

        if (!empty($job_data['location'])) {
            array_push($criteria, array(
                'relation' => 'OR',
                array(
                    'key' => 'candidate_job_alerts_location',
                    'value' => '"' . $job_data['location'] . '*"',
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'candidate_job_alerts_location',
                    'value' => 'a:0:{}',
                    'compare' => '=='
                ),
            ));
        }

        if (!empty($job_data['category'])) {
            array_push($criteria, array(
                'relation' => 'OR',
                array(
                    'key' => 'candidate_job_alerts_category',
                    'value' => '"' . $job_data['category'] . '"',
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'candidate_job_alerts_category',
                    'value' => 'a:0:{}',
                    'compare' => '=='
                ),
            ));
        }

        if (!empty($job_data['level'])) {
            array_push($criteria, array(
                'relation' => 'OR',
                array(
                    'key' => 'candidate_job_alerts_level',
                    'value' => '"' . $job_data['level'] . '"',
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'candidate_job_alerts_level',
                    'value' => 'a:0:{}',
                    'compare' => '=='
                ),
            ));
        }

        if (!empty($job_data['type'])) {
            array_push($criteria, array(
                'relation' => 'OR',
                array(
                    'key' => 'candidate_job_alerts_type',
                    'value' => '"' . $job_data['type'] . '"',
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'candidate_job_alerts_type',
                    'value' => 'a:0:{}',
                    'compare' => '=='
                ),
            ));
        }

        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'candidate',
            'post_status'    => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'candidate_job_alerts',
                    'value' => '1'
                ),
                $criteria
            ),
        );

        $query = new WP_Query($args);
        wp_reset_postdata();

        return $query;
    }
endif;

if (!function_exists('jobster_send_jobs_alerts_email')): 
    function jobster_send_jobs_alerts_email($job_data, $candidate) {
        $address = get_post_meta($candidate->ID, 'candidate_email', true);
        if (empty($address)) return;

        $job_title = get_the_title($job_data['id']);
        $job_url = get_the_permalink($job_data['id']);
        $candidate_name = $candidate->post_title;

        $job_company_id = get_post_meta($job_data['id'], 'job_company', true);
        $subject = sprintf( __('New Job Alert: [%s]', 'jobster'), $job_title );
        if (!empty($job_company_id)) {
            $company_name = get_the_title($job_company_id);
            $subject = sprintf(
                __('New Job Alert: [%s] at [%s]', 'jobster'),
                $job_title,
                $company_name
            );
        }

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option('blogname') . '<' . get_option('admin_email') . '>',
            'Reply-To: ' . get_option('blogname') . '<' . get_option('admin_email') . '>'
        );

        $email_settings = get_option('jobster_email_settings');
        $template = isset($email_settings['jobster_email_job_alerts_field']) 
                    ? $email_settings['jobster_email_job_alerts_field'] 
                    : '';

        if ($template != '') {
            $template = str_replace("{CANDIDATE_NAME}", $candidate_name, $template);
            $template = str_replace("{JOB_TITLE}", $job_title, $template);
            $template = str_replace("{JOB_URL}", $job_url, $template);
            $template = str_replace("{COMPANY_NAME}", $company_name, $template);

            $send = wp_mail($address, $subject, $template, $headers);
        } else {
            $message = sprintf( __('Dear %s,', 'jobster'), esc_html($candidate_name) ) . "\r\n\r\n";
            $message .= "<br/><br/>";
            $message .= sprintf(
                __('We are excited to inform you about a new job posting for the position of <b>%s</b> at <b>%s</b>. As a valued member of our job board community, we wanted to notify you about this opportunity.', 'jobster'),
                esc_html($job_title),
                esc_html($company_name),
            ) . "\r\n\r\n";
            $message .= "<br/><br/>";
            $message .= sprintf(
                __('If you meet these qualifications and are interested in applying for this position, please click on the link below to visit the job posting page and submit your application:', 'jobster')
            ) . "\r\n\r\n";
            $message .= "<br/><br/>";
            $message .= '<a href="' . esc_url($job_url) . '" target="_blank">' . esc_url($job_url) . '</a>' . "\r\n\r\n";

            $send = wp_mail($address, $subject, $message, $headers);
        }
    }
endif;

if (!function_exists('jobster_filter_form_count_jobs_by_term')):
    function jobster_filter_form_count_jobs_by_term($taxonomy, $term_id) {
        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'job',
            'post_status'    => 'publish',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'job_valid',
                    'compare' => '>=',
                    'value' => date('Y-m-d'),
                    'type' => 'DATE'
                ),
                array(
                    'key' => 'job_valid',
                    'compare' => '==',
                    'value' => ''
                ),
                array(
                    'key' => 'job_valid',
                    'compare' => 'NOT EXISTS'
                )
            ),
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => array($term_id),
                ),
            )
        );

        $jobs = get_posts($args);

        return count($jobs);
    }
endif;
?>