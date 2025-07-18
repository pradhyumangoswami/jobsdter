<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Register company custom post type
 */
if (!function_exists('jobster_register_company_type')): 
    function jobster_register_company_type() {
        register_post_type('company', array(
            'labels' => array(
                'name'               => __('Companies', 'jobster'),
                'singular_name'      => __('Company', 'jobster'),
                'add_new'            => __('Add New Company', 'jobster'),
                'add_new_item'       => __('Add Company', 'jobster'),
                'edit'               => __('Edit', 'jobster'),
                'edit_item'          => __('Edit Company', 'jobster'),
                'new_item'           => __('New Company', 'jobster'),
                'view'               => __('View', 'jobster'),
                'view_item'          => __('View Company', 'jobster'),
                'search_items'       => __('Search Companies', 'jobster'),
                'not_found'          => __('No Companies found', 'jobster'),
                'not_found_in_trash' => __('No Companies found in Trash', 'jobster'),
                'parent'             => __('Parent Company', 'jobster'),
            ),
            'public'                => true,
            'exclude_from_search '  => false,
            'has_archive'           => true,
            'rewrite'               => array('slug' => _x('companies', 'URL SLUG', 'jobster')),
            'supports'              => array('title', 'editor', 'comments'),
            'show_in_rest'          => true,
            'can_export'            => true,
            'register_meta_box_cb'  => 'jobster_add_company_metaboxes',
            'menu_icon'             => 'dashicons-building',
        ));

        // add company industry taxonomy
        register_taxonomy('company_industry', 'company', array(
            'labels' => array(
                'name'                       => __('Company Industries', 'jobster'),
                'singular_name'              => __('Company Industry', 'jobster'),
                'search_items'               => __('Search Company Industries', 'jobster'),
                'popular_items'              => __('Popular Company Industries', 'jobster'),
                'all_items'                  => __('All Company Industries', 'jobster'),
                'edit_item'                  => __('Edit Company Industry', 'jobster'),
                'update_item'                => __('Update Company Industry', 'jobster'),
                'add_new_item'               => __('Add New Company Industry', 'jobster'),
                'new_item_name'              => __('New Company Industry Name', 'jobster'),
                'separate_items_with_commas' => __('Separate company industries with commas', 'jobster'),
                'add_or_remove_items'        => __('Add or remove company industries', 'jobster'),
                'choose_from_most_used'      => __('Choose from the most used company industries', 'jobster'),
                'not_found'                  => __('No company industry found.', 'jobster'),
                'menu_name'                  => __('Company Industries', 'jobster'),
            ),
            'hierarchical'      => true,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'company-industry'),
            'show_in_rest'      => true,
        ));

        // add company location taxonomy
        register_taxonomy('company_location', 'company', array(
            'labels' => array(
                'name'                       => __('Company Locations', 'jobster'),
                'singular_name'              => __('Company Location', 'jobster'),
                'search_items'               => __('Search Company Locations', 'jobster'),
                'popular_items'              => __('Popular Company Locations', 'jobster'),
                'all_items'                  => __('All Company Locations', 'jobster'),
                'edit_item'                  => __('Edit Company Location', 'jobster'),
                'update_item'                => __('Update Company Location', 'jobster'),
                'add_new_item'               => __('Add New Company Location', 'jobster'),
                'new_item_name'              => __('New Company Location Name', 'jobster'),
                'separate_items_with_commas' => __('Separate company locations with commas', 'jobster'),
                'add_or_remove_items'        => __('Add or remove company locations', 'jobster'),
                'choose_from_most_used'      => __('Choose from the most used company locations', 'jobster'),
                'not_found'                  => __('No company location found.', 'jobster'),
                'menu_name'                  => __('Company Locations', 'jobster'),
            ),
            'hierarchical'      => true,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'company-location'),
            'show_in_rest'      => true,
        ));
    }
endif;
add_action('init', 'jobster_register_company_type');

if (!function_exists('jobster_change_company_default_title')): 
    function jobster_change_company_default_title($title) {
        $screen = get_current_screen();

        if ('company' == $screen->post_type) {
            $title = __('Add company name', 'jobster');
        }

        return $title;
    }
endif;
add_filter('enter_title_here', 'jobster_change_company_default_title');

if (!function_exists('jobster_add_company_metaboxes')): 
    function jobster_add_company_metaboxes() {
        add_meta_box('company-details-section', __('Company Details', 'jobster'), 'jobster_company_details_render', 'company', 'normal', 'default');
        add_meta_box('company-additional-info-section', __('Additional Info', 'jobster'), 'jobster_company_additional_info_render', 'company', 'normal', 'default');
        add_meta_box('company-doc-section', __('Document', 'jobster'), 'jobster_company_doc_render', 'company', 'normal', 'default');
        add_meta_box('company-gallery-section', __('Photo Gallery', 'jobster'), 'jobster_company_gallery_render', 'company', 'normal', 'default');
        add_meta_box('company-video-section', __('Video', 'jobster'), 'jobster_company_video_render', 'company', 'normal', 'default');
        add_meta_box('company-social-section', __('Social Media', 'jobster'), 'jobster_company_social_media_render', 'company', 'normal', 'default');
        add_meta_box('company-logo-section', __('Company Logo', 'jobster'), 'jobster_company_logo_render', 'company', 'side', 'default');
        add_meta_box('company-cover-section', __('Company Cover', 'jobster'), 'jobster_company_cover_render', 'company', 'side', 'default');
        add_meta_box('company-featured-section', __('Featured', 'jobster'), 'jobster_company_featured_render', 'company', 'side', 'default');
        add_meta_box('company-notificaitons-section', __('Notifications', 'jobster'), 'jobster_company_notifications_render', 'company', 'normal', 'default');
        add_meta_box('company-payment-section', __('Membership & Payment', 'jobster'), 'jobster_company_payment_render', 'company', 'normal', 'default');
        add_meta_box('company-user-section', __('User', 'jobster'), 'jobster_company_user_render', 'company', 'normal', 'default');
    }
endif;

if (!function_exists('jobster_company_details_render')):
    function jobster_company_details_render($post) {
        wp_nonce_field('jobster_company', 'company_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="company_email">' . __('Email', 'jobster') . '</label>
                            <input name="company_email" id="company_email" type="email" value="' . esc_attr(get_post_meta($post->ID, 'company_email', true)) . '">
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="company_phone">' . __('Phone', 'jobster') . '</label>
                            <input name="company_phone" id="company_phone" type="tel" value="' . esc_attr(get_post_meta($post->ID, 'company_phone', true)) . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="company_website">' . __('Website', 'jobster') . '</label>
                            <input name="company_website" id="company_website" type="url" value="' . esc_attr(get_post_meta($post->ID, 'company_website', true)) . '">
                            <label for="company_redirect" style="margin-top:10px;">
                                <input type="hidden" name="company_redirect" value="">
                                <input type="checkbox" name="company_redirect" id="company_redirect" value="1" ';
                                    if (esc_html(get_post_meta($post->ID, 'company_redirect', true)) == 1) {
                                        print ' checked ';
                                    }
                                print ' /> ' . __('Redirect company page to this URL', 'jobster') . '
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="company_founded">' . __('Founded in', 'jobster') . '</label>
                            <input name="company_founded" id="company_founded" type="number" value="' . esc_attr(get_post_meta($post->ID, 'company_founded', true)) . '">
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="company_size">' . __('Size (number of employees)', 'jobster') . '</label>
                            <input name="company_size" id="company_size" type="text" value="' . esc_attr(get_post_meta($post->ID, 'company_size', true)) . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_company_additional_info_render')):
    function jobster_company_additional_info_render($post) {
        $companies_fields_settings = get_option('jobster_companies_fields_settings');
        $counter = 0;

        if (is_array($companies_fields_settings)) {
            uasort($companies_fields_settings, 'jobster_compare_position');

            print '
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>';
            foreach ($companies_fields_settings as $key => $value) {
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

if (!function_exists('jobster_company_doc_render')): 
    function jobster_company_doc_render($post) {
        $doc_val = get_post_meta($post->ID, 'company_doc', true);
        $doc = wp_get_attachment_url($doc_val);

        $item_class = '';
        $filename = '';
        if (!empty($doc)) {
            $item_class = 'pxp-show';
            $filename = basename($doc);
        }

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="company_doc_title">' . __('Document Title', 'jobster') . '</label>
                            <input name="company_doc_title" id="company_doc_title" type="text" value="' . esc_attr(get_post_meta($post->ID, 'company_doc_title', true)) . '" placeholder="' . __('E.g. Brochure', 'jobster') . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>
            <input name="company_doc" id="company_doc" type="hidden" value="' . esc_attr($doc_val) . '">
            <div class="list-group pxp-company-doc-wrapper">
                <div class="list-group-item pxp-company-doc-container ' . esc_attr($item_class) . '">
                    <div class="pxp-company-doc-filename">' . esc_html($filename) . '</div>
                    <div class="pxp-company-doc-btns">
                        <a href="javascript:void(0);" class="pxp-list-del-btn pxp-del-company-doc"><span class="fa fa-trash-o"></span></a>
                    </div>
                </div>
            </div>
            <input id="pxp-add-company-doc-btn" type="button" class="button" value="' . esc_html__('Upload Document', 'jobster') . '" />';
    }
endif;

if (!function_exists('jobster_company_gallery_render')): 
    function jobster_company_gallery_render($post) {
        $photos = get_post_meta($post->ID, 'company_gallery', true);
        $ids = explode(',', $photos);

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="company_gallery_title">' . __('Title', 'jobster') . '</label><br>
                            <input name="company_gallery_title" id="company_gallery_title" type="text" value="' . esc_attr(get_post_meta($post->ID, 'company_gallery_title', true)) . '" placeholder="' . __('E.g. Photo Gallery', 'jobster') . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>
            <input type="hidden" id="company_gallery" name="company_gallery" value="' . esc_attr($photos) . '" />
            <ul class="list-group" id="company-gallery-list">';
        foreach ($ids as $id) {
            if ($id != '') {
                $photo_src = wp_get_attachment_image_src($id, 'pxp-thmb');
                $photo_info = jobster_get_attachment($id);

                print '
                <li class="list-group-item" data-id="' . esc_attr($id) . '">
                    <div class="pxp-company-gallery-list-item">
                        <img src="' . esc_url($photo_src[0]) . '" />
                        <div class="list-group-item-info">
                            <div class="list-group-item-info-title">' . $photo_info['title'] . '</div>
                            <div class="list-group-item-info-caption">' . $photo_info['caption'] . '</div>
                        </div>
                        <div class="pxp-list-item-btns">
                            <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-company-gallery-edit-photo-btn"><span class="fa fa-pencil"></span></a>
                            <a href="javascript:void(0);" class="pxp-list-del-btn pxp-company-gallery-delete-photo-btn"><span class="fa fa-trash-o"></span></a>
                        </div>
                    </div>
                </li>';
            }
        }
        print '
            </ul>
            <input id="pxp-add-company-gallery-photo-btn" type="button" class="button" value="' . __('Add Photos', 'jobster') . '" />';
    }
endif;

if (!function_exists('jobster_company_video_render')): 
    function jobster_company_video_render($post) {
        $video = get_post_meta($post->ID, 'company_video', true);

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="company_video_title">' . __('Title', 'jobster') . '</label><br>
                            <input name="company_video_title" id="company_video_title" type="text" value="' . esc_attr(get_post_meta($post->ID, 'company_video_title', true)) . '" placeholder="' . __('E.g. About Us', 'jobster') . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="company_video">' . __('YouTube Video ID', 'jobster') . '</label><br />
                            <input type="text" id="company_video" name="company_video" placeholder="' . __('E.g. Ur1Nrz23sSI', 'jobster') . '" value="' . esc_attr($video) . '">
                            <p class="help" style="margin-top: 5px; font-size: 11px !important;">E.g. <span style="color: #999;">https://www.youtube.com/watch?v=</span><strong style="color: green; font-style: normal;">Ur1Nrz23sSI</strong></p>
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_company_social_media_render')): 
    function jobster_company_social_media_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="company_facebook">' . __('Facebook', 'jobster') . '</label>
                            <input name="company_facebook" id="company_facebook" type="url" value="' . esc_attr(get_post_meta($post->ID, 'company_facebook', true)) . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="company_twitter">' . __('Twitter', 'jobster') . '</label>
                            <input name="company_twitter" id="company_twitter" type="url" value="' . esc_attr(get_post_meta($post->ID, 'company_twitter', true)) . '">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="company_instagram">' . __('Instagram', 'jobster') . '</label>
                            <input name="company_instagram" id="company_instagram" type="url" value="' . esc_attr(get_post_meta($post->ID, 'company_instagram', true)) . '">
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="company_linkedin">' . __('Linkedin', 'jobster') . '</label>
                            <input name="company_linkedin" id="company_linkedin" type="url" value="' . esc_attr(get_post_meta($post->ID, 'company_linkedin', true)) . '">
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_company_logo_render')): 
    function jobster_company_logo_render($post) {
        $logo_src = JOBSTER_PLUGIN_PATH . 'post-types/images/logo-placeholder.png';
        $logo_val = get_post_meta($post->ID, 'company_logo', true);
        $logo = wp_get_attachment_image_src($logo_val, 'pxp-icon');
        $has_image = '';

        if (is_array($logo)) {
            $has_image = 'pxp-has-image';
            $logo_src = $logo[0];
        }

        print '
            <input name="company_logo" id="company_logo" type="hidden" value="' . esc_attr($logo_val) . '">
            <div class="pxp-company-logo-placeholder-container ' . esc_attr($has_image) . '">
                <div class="pxp-company-logo-image-placeholder" style="background-image: url(' . esc_url($logo_src) . ');"></div>
                <div class="pxp-delete-company-logo-image"><span class="fa fa-trash-o"></span></div>
            </div>';
    }
endif;

if (!function_exists('jobster_company_cover_render')): 
    function jobster_company_cover_render($post) {
        $cover_src = JOBSTER_PLUGIN_PATH . 'post-types/images/cover-placeholder.png';
        $cover_val = get_post_meta($post->ID, 'company_cover', true);
        $cover = wp_get_attachment_image_src($cover_val, 'pxp-gallery');
        $has_image = '';

        $cover_type = get_post_meta($post->ID, 'company_cover_type', true);
        $cover_types = array(
            'n' => __('None', 'jobster'),
            'i' => __('Image', 'jobster'),
            'c' => __('Color', 'jobster')
        );

        $cover_color = get_post_meta($post->ID, 'company_cover_color', true);

        if (is_array($cover)) {
            $has_image = 'pxp-has-image';
            $cover_src = $cover[0];
        }

        print '
            <label for="company_cover_type" style="font-size: 11px; font-weight: 500; line-height: 1.4; text-transform: uppercase; display: inline-block; margin-bottom: calc(8px); padding: 0;">' . __('Cover Type', 'jobster') . '</label>
            <select id="company_cover_type" name="company_cover_type" style="width: 100%; box-sizing: border-box;">';
        foreach ($cover_types as $ct_key => $ct_value) {
            print '
                <option value="' . esc_attr($ct_key) . '" ' . selected($cover_type, $ct_key) . '>' . esc_attr($ct_value) . '</option>';
        }
        print '
            </select>

            <br><br>
            <label for="company_cover_color" style="font-size: 11px; font-weight: 500; line-height: 1.4; text-transform: uppercase; display: block; margin-bottom: calc(8px); padding: 0;">' . __('Cover Color', 'jobster') . '</label>
            <input type="text" name="company_cover_color" id="company_cover_color" class="pxp-color-field" value="' . esc_attr($cover_color) . '" />

            <br><br>
            <div>
                <input name="company_cover" id="company_cover" type="hidden" value="' . esc_attr($cover_val) . '">
                <div class="pxp-company-cover-placeholder-container ' . esc_attr($has_image) . '">
                    <div class="pxp-company-cover-image-placeholder" style="background-image: url(' . esc_url($cover_src) . ');"></div>
                    <div class="pxp-delete-company-cover-image"><span class="fa fa-trash-o"></span></div>
                </div>
            </div>';
    }
endif;

if (!function_exists('jobster_company_featured_render')): 
    function jobster_company_featured_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <p class="meta-options">
                            <input type="hidden" name="company_featured" value="">
                            <input type="checkbox" name="company_featured" value="1" ';
                            if (esc_html(get_post_meta($post->ID, 'company_featured', true)) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="company_featured">' . __('Set as Featured', 'jobster') . '</label>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_company_payment_render')): 
    function jobster_company_payment_render($post) {
        $membership_settings = get_option('jobster_membership_settings');
        $pay_type            = isset($membership_settings['jobster_payment_type_field']) ? $membership_settings['jobster_payment_type_field'] : '';

        if ($pay_type == 'listing' || $pay_type == 'plan') {

            print '<input type="hidden" name="company_payment" value="">
                   <input type="checkbox" name="company_payment" value="1" ';
            if (esc_html(get_post_meta($post->ID, 'company_payment', true)) == 1) {
                print ' checked ';
            }
            print ' /> <label for="company_payment">' . __('Allow the company to post jobs regardless of payment method', 'jobster') . '</label>';

            if ($pay_type == 'plan') {
                $plans_list = '';
                $selected_plan = esc_html(get_post_meta($post->ID, 'company_plan', true));

                $args = array(
                    'posts_per_page'   => -1,
                    'post_type'        => 'membership',
                    'order'            => 'ASC',
                    'post_status'      => 'publish,',
                    'meta_key'         => 'membership_plan_price',
                    'orderby'          => 'meta_value_num',
                    'suppress_filters' => false,
                );

                $plans_selection = new WP_Query($args);
                $plans_selection_arr  = get_object_vars($plans_selection);

                if (is_array($plans_selection_arr['posts']) && count($plans_selection_arr['posts']) > 0) {
                    foreach ($plans_selection_arr['posts'] as $plan) {
                        $plans_list .= '<option value="' . esc_attr($plan->ID) . '"';
                        if ($plan->ID == $selected_plan) {
                            $plans_list .= ' selected';
                        }
                        $plans_list .= '>' . $plan->post_title . '</option>';
                    }
                }

                print '
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" valign="bottom">
                                <div class="form-field pxp-is-custom pxp-is-last" style="margin-top: 15px;">
                                    <label for="company_plan_manual">' . __('Manually Assign a Membership Plan', 'jobster') . '</label><br />
                                    <select id="company_plan_manual" name="company_plan_manual">
                                        <option value="">' . __('None', 'jobster') . '</option>
                                        ' . $plans_list . '
                                    </select>
                                </div>
                            </td>
                            <td width="50%" valign="bottom">
                                <button id="pxp-set-plan-manually-btn" type="button" class="button" data-id="' . esc_attr($post->ID) . '">
                                    <span class="pxp-set-plan-manually-btn-text">' . __('Set Plan', 'jobster') . '</span>
                                    <span class="pxp-set-plan-manually-btn-loading" style="display:none;">' . __('Setting Plan...', 'jobster') . '</span>
                                </button>
                            </td>
                        </tr>
                    </table>';
            }
        } else {
            print '<i>' . __('Payment type is disabled.', 'jobster') . '</i>';
        }
    }
endif;

if (!function_exists('jobster_company_user_render')): 
    function jobster_company_user_render($post) {
        wp_nonce_field('jobster_cuser', 'cuser_noncename');

        $mypost        = $post->ID;
        $originalpost  = $post;
        $selected_user = get_post_meta($mypost, 'company_user', true);
        $users_list    = '';
        $args          = array('role' => '');

        $user_query = new WP_User_Query($args);

        foreach ($user_query->results as $user) {
            $is_candidate = jobster_user_is_candidate($user->ID);

            if (!$is_candidate) {
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
                            <label for="company_user">' . __('Assign a User', 'jobster') . '</label>
                            <select id="company_user" name="company_user">
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

if (!function_exists('jobster_company_notifications_render')):
    function jobster_company_notifications_render($post) {
        wp_nonce_field('jobster_company', 'company_noncename'); ?>

        <div class="form-field pxp-is-custom pxp-is-last">
            &nbsp;<br>
            <label for="company_app_notify">
                <input 
                    type="hidden" 
                    name="company_app_notify" 
                    value="0"
                >
                <input 
                    type="checkbox" 
                    name="company_app_notify" 
                    id="company_app_notify" 
                    value="1" 
                    <?php checked(
                        get_post_meta($post->ID, 'company_app_notify', true), true, true
                    ); ?>
                >
                <?php esc_html_e('Notify the company when a new candidate applies for a job', 'jobster'); ?>
            </label>
        </div>
    <?php }
endif;

if (!function_exists('jobster_company_meta_save')): 
    function jobster_company_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['company_noncename']) && wp_verify_nonce($_POST['company_noncename'], 'jobster_company')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['company_email'])) {
            update_post_meta($post_id, 'company_email', sanitize_text_field($_POST['company_email']));
        }
        if (isset($_POST['company_phone'])) {
            update_post_meta($post_id, 'company_phone', sanitize_text_field($_POST['company_phone']));
        }
        if (isset($_POST['company_website'])) {
            update_post_meta($post_id, 'company_website', sanitize_text_field($_POST['company_website']));
        }
        if (isset($_POST['company_redirect'])) {
            update_post_meta($post_id, 'company_redirect', sanitize_text_field($_POST['company_redirect']));
        }
        if (isset($_POST['company_founded'])) {
            update_post_meta($post_id, 'company_founded', sanitize_text_field($_POST['company_founded']));
        }
        if (isset($_POST['company_size'])) {
            update_post_meta($post_id, 'company_size', sanitize_text_field($_POST['company_size']));
        }
        if (isset($_POST['company_doc'])) {
            update_post_meta($post_id, 'company_doc', sanitize_text_field($_POST['company_doc']));
        }
        if (isset($_POST['company_doc_title'])) {
            update_post_meta($post_id, 'company_doc_title', sanitize_text_field($_POST['company_doc_title']));
        }
        if (isset($_POST['company_gallery'])) {
            update_post_meta($post_id, 'company_gallery', sanitize_text_field($_POST['company_gallery']));
        }
        if (isset($_POST['company_gallery_title'])) {
            update_post_meta($post_id, 'company_gallery_title', sanitize_text_field($_POST['company_gallery_title']));
        }
        if (isset($_POST['company_video'])) {
            update_post_meta($post_id, 'company_video', sanitize_text_field($_POST['company_video']));
        }
        if (isset($_POST['company_video_title'])) {
            update_post_meta($post_id, 'company_video_title', sanitize_text_field($_POST['company_video_title']));
        }
        if (isset($_POST['company_facebook'])) {
            update_post_meta($post_id, 'company_facebook', sanitize_text_field($_POST['company_facebook']));
        }
        if (isset($_POST['company_twitter'])) {
            update_post_meta($post_id, 'company_twitter', sanitize_text_field($_POST['company_twitter']));
        }
        if (isset($_POST['company_instagram'])) {
            update_post_meta($post_id, 'company_instagram', sanitize_text_field($_POST['company_instagram']));
        }
        if (isset($_POST['company_linkedin'])) {
            update_post_meta($post_id, 'company_linkedin', sanitize_text_field($_POST['company_linkedin']));
        }
        if (isset($_POST['company_logo'])) {
            update_post_meta($post_id, 'company_logo', sanitize_text_field($_POST['company_logo']));
        }
        if (isset($_POST['company_cover'])) {
            update_post_meta($post_id, 'company_cover', sanitize_text_field($_POST['company_cover']));
        }
        if (isset($_POST['company_cover_type'])) {
            update_post_meta($post_id, 'company_cover_type', sanitize_text_field($_POST['company_cover_type']));
        }
        if (isset($_POST['company_cover_color'])) {
            update_post_meta($post_id, 'company_cover_color', sanitize_text_field($_POST['company_cover_color']));
        }
        if (isset($_POST['company_featured'])) {
            update_post_meta($post_id, 'company_featured', sanitize_text_field($_POST['company_featured']));
        }
        if (isset($_POST['company_user'])) {
            update_post_meta($post_id, 'company_user', sanitize_text_field($_POST['company_user']));
        }
        if (isset($_POST['company_app_notify'])) {
            update_post_meta($post_id, 'company_app_notify', sanitize_text_field($_POST['company_app_notify']));
        }
        if (isset($_POST['company_payment'])) {
            update_post_meta($post_id, 'company_payment', sanitize_text_field($_POST['company_payment']));
        }

        $companies_fields_settings = get_option('jobster_companies_fields_settings');
        if (is_array($companies_fields_settings)) {
            foreach ($companies_fields_settings as $jvs_key => $jvs_value) {
                if (isset($_POST[$jvs_key])) {
                    update_post_meta($post_id, $jvs_key, sanitize_text_field($_POST[$jvs_key]));
                }
            }
        }
    }
endif;
add_action('save_post', 'jobster_company_meta_save');

if (!function_exists('jobster_get_company_locations_industries')): 
    function jobster_get_company_locations_industries() {
        $locations = get_terms(
            array(
                'taxonomy' => 'company_location',
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
            'company_industry'
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
    'wp_ajax_nopriv_jobster_get_company_locations_industries',
    'jobster_get_company_locations_industries'
);
add_action(
    'wp_ajax_jobster_get_company_locations_industries',
    'jobster_get_company_locations_industries'
);

if (!function_exists('jobster_set_company_membership_manually')):
    function jobster_set_company_membership_manually() {
        check_ajax_referer('jobster_company', 'security');

        $company_id =   isset($_POST['company_id'])
                        ? sanitize_text_field($_POST['company_id'])
                        : '';
        $plan_id =  isset($_POST['plan_id'])
                    ? sanitize_text_field($_POST['plan_id'])
                    : '';

        $plan_listings = get_post_meta($plan_id, 'membership_submissions_no', true);
        $plan_unlimited  = get_post_meta($plan_id, 'membership_unlim_submissions', true);
        $plan_featured_listings = get_post_meta($plan_id, 'membership_featured_submissions_no', true);
        $plan_cv_access = get_post_meta($plan_id, 'membership_cv_access', true);
        $plan_free = get_post_meta($plan_id, 'membership_free_plan', true);

        update_post_meta($company_id, 'company_plan', $plan_id);
        update_post_meta($company_id, 'company_plan_listings', $plan_listings);
        update_post_meta($company_id, 'company_plan_unlimited', $plan_unlimited);
        update_post_meta($company_id, 'company_plan_featured', $plan_featured_listings);
        update_post_meta($company_id, 'company_plan_cv_access', $plan_cv_access);

        if ($plan_free == 1) {
            update_post_meta($company_id, 'company_plan_free', 1);
        } else {
            update_post_meta($company_id, 'company_plan_free', '');
        }

        $time = time(); 
        $date = date('Y-m-d H:i:s', $time);

        update_post_meta($company_id, 'company_plan_activation', $date);

        echo json_encode(array(
            'set' => true
        ));
        exit();

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_set_company_membership_manually',
    'jobster_set_company_membership_manually'
);
add_action(
    'wp_ajax_jobster_set_company_membership_manually',
    'jobster_set_company_membership_manually'
);
?>