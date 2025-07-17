<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_update_company_profile')): 
    function jobster_update_company_profile() {
        check_ajax_referer('company_profile_ajax_nonce', 'security');

        $company_id =   isset($_POST['company_id'])
                        ? sanitize_text_field($_POST['company_id'])
                        : '';
        $name =     isset($_POST['name'])
                    ? sanitize_text_field($_POST['name'])
                    : '';
        $email =    isset($_POST['email'])
                    ? sanitize_email($_POST['email'])
                    : '';
        $phone =    isset($_POST['phone'])
                    ? sanitize_text_field($_POST['phone'])
                    : '';
        $website =  isset($_POST['website'])
                    ? sanitize_text_field($_POST['website'])
                    : '';
        $redirect = isset($_POST['redirect'])
                    ? sanitize_text_field($_POST['redirect'])
                    : '';
        $cover =    isset($_POST['cover'])
                    ? sanitize_text_field($_POST['cover'])
                    : '';
        $cover_type =   isset($_POST['cover_type'])
                        ? sanitize_text_field($_POST['cover_type'])
                        : '';
        $cover_color =  isset($_POST['cover_color'])
                        ? sanitize_text_field($_POST['cover_color'])
                        : '';
        $logo = isset($_POST['logo'])
                ? sanitize_text_field($_POST['logo'])
                : '';
        $about = isset($_POST['about']) ? $_POST['about'] : '';
        $industry = isset($_POST['industry'])
                    ? sanitize_text_field($_POST['industry'])
                    : '0';
        $location = isset($_POST['location'])
                    ? sanitize_text_field($_POST['location'])
                    : '0';
        $founded =  isset($_POST['founded'])
                    ? sanitize_text_field($_POST['founded'])
                    : '';
        $size = isset($_POST['size'])
                ? sanitize_text_field($_POST['size'])
                : '';
        $doc =  isset($_POST['doc'])
                ? sanitize_text_field($_POST['doc'])
                : '';
        $doc_title =    isset($_POST['doc_title'])
                        ? sanitize_text_field($_POST['doc_title'])
                        : '';
        $facebook = isset($_POST['facebook'])
                    ? sanitize_text_field($_POST['facebook'])
                    : '';
        $twitter =  isset($_POST['twitter'])
                    ? sanitize_text_field($_POST['twitter'])
                    : '';
        $instagram =    isset($_POST['instagram'])
                        ? sanitize_text_field($_POST['instagram'])
                        : '';
        $linkedin = isset($_POST['linkedin'])
                    ? sanitize_text_field($_POST['linkedin'])
                    : '';
        $app_notify = isset($_POST['app_notify'])
                    ? sanitize_text_field($_POST['app_notify'])
                    : '';
        $gallery =  isset($_POST['gallery'])
                    ? sanitize_text_field($_POST['gallery'])
                    : '';
        $gallery_title =    isset($_POST['gallery_title'])
                            ? sanitize_text_field($_POST['gallery_title'])
                            : '';
        $video =    isset($_POST['video'])
                    ? sanitize_text_field($_POST['video'])
                    : '';
        $video_title =  isset($_POST['video_title'])
                        ? sanitize_text_field($_POST['video_title'])
                        : '';

        if (isset($_POST['cfields']) && is_array($_POST['cfields'])) {
            array_walk_recursive($_POST['cfields'], 'jobster_sanitize_multi_array');
            $custom_fields = $_POST['cfields'];
        } else {
            $custom_fields = '';
        }

        if ($name == '') {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Name field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-profile-name'
                )
            );
            exit();
        }
        if (!is_email($email)) {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Invalid email address.', 'jobster'),
                    'field' => 'pxp-company-profile-email'
                )
            );
            exit();
        }
        if ($industry == '0') {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Industry field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-profile-industry'
                )
            );
            exit();
        }
        if ($location == '0') {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Location field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-profile-location'
                )
            );
            exit();
        }
        if ($custom_fields != '') {
            foreach ($custom_fields as $cf_key => $cf_value) {
                if ($cf_value['field_mandatory'] == 'yes' && $cf_value['field_value'] == '') {
                    echo json_encode(array(
                        'update' => false, 
                        'message' => sprintf (__('%s field is mandatory.', 'jobster'), $cf_value['field_label']),
                        'field' => $cf_value['field_name']
                    ));
                    exit();
                }
            }
        }

        wp_update_post(
            array(
                'ID' => $company_id,
                'post_title' => $name,
                'post_content' => $about,
                'post_status' => 'publish'
            )
        );

        wp_set_object_terms(
            $company_id,
            array(intval($industry)),
            'company_industry'
        );
        wp_set_object_terms(
            $company_id,
            array(intval($location)),
            'company_location'
        );

        update_post_meta($company_id, 'company_email', $email);
        update_post_meta($company_id, 'company_phone', $phone);
        update_post_meta($company_id, 'company_website', $website);
        update_post_meta($company_id, 'company_redirect', $redirect);
        update_post_meta($company_id, 'company_cover', $cover);
        update_post_meta($company_id, 'company_cover_type', $cover_type);
        update_post_meta($company_id, 'company_cover_color', $cover_color);
        update_post_meta($company_id, 'company_logo', $logo);
        update_post_meta($company_id, 'company_founded', $founded);
        update_post_meta($company_id, 'company_size', $size);
        update_post_meta($company_id, 'company_doc', $doc);
        update_post_meta($company_id, 'company_doc_title', $doc_title);
        update_post_meta($company_id, 'company_facebook', $facebook);
        update_post_meta($company_id, 'company_twitter', $twitter);
        update_post_meta($company_id, 'company_instagram', $instagram);
        update_post_meta($company_id, 'company_linkedin', $linkedin);
        update_post_meta($company_id, 'company_gallery', $gallery);
        update_post_meta($company_id, 'company_gallery_title', $gallery_title);
        update_post_meta($company_id, 'company_video', $video);
        update_post_meta($company_id, 'company_video_title', $video_title);
        update_post_meta($company_id, 'company_app_notify', $app_notify);

        if ($custom_fields != '') {
            foreach ($custom_fields as $ucf_key => $ucf_value) {
                update_post_meta(
                    $company_id,
                    $ucf_value['field_name'],
                    $ucf_value['field_value']
                );
            }
        }

        echo json_encode(
            array(
                'update' => true,
                'message' => __('Your profile data was successfully updated. Redirecting...', 'jobster')
            )
        );
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_update_company_profile', 'jobster_update_company_profile');
add_action('wp_ajax_jobster_update_company_profile', 'jobster_update_company_profile');

if (!function_exists('jobster_add_company_location')): 
    function jobster_add_company_location() {
        check_ajax_referer('company_profile_ajax_nonce', 'security');

        $name = isset($_POST['name'])
                ? sanitize_text_field($_POST['name'])
                : '';
        $parent =   isset($_POST['parent'])
                    ? intval(sanitize_text_field($_POST['parent']))
                    : 0;

        if ($name == '') {
            echo json_encode(
                array(
                    'add' => false,
                    'field' => 'pxp-company-profile-location-new'
                )
            );
            exit();
        }

        $location_new = wp_insert_term(
            $name,
            'company_location',
            array(
                'parent' => $parent
            )
        );

        if (!is_wp_error($location_new)) {
            $loc_terms = get_terms(
                array(
                    'taxonomy' => 'company_location',
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
            $locations = array(
                array(
                    'id' => 0,
                    'label' => __('Select location', 'jobster')
                )
            );
            foreach ($top_level_locations as $top_location) {
                $locations[] = array(
                    'id' => $top_location->term_id,
                    'label' => $top_location->name
                );
                if (array_key_exists($top_location->term_id, $children_locations)) {
                    foreach ($children_locations[$top_location->term_id] as $child_location) {
                        $locations[] = array(
                            'id' => $child_location->term_id,
                            'label' => '&nbsp;&nbsp;&nbsp;' . $child_location->name
                        );
                    }
                }
            }

            echo json_encode(array(
                'add' => true,
                'locations' => $locations,
                'location_id' => $location_new['term_id']
            ));
            exit();
        } else {
            echo json_encode(
                array(
                    'add' => false,
                    'field' => 'pxp-company-profile-location-new'
                )
            );
            exit();
        }
        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_add_company_location',
    'jobster_add_company_location'
);
add_action(
    'wp_ajax_jobster_add_company_location',
    'jobster_add_company_location'
);

if (!function_exists('jobster_delete_company_profile_cover')): 
    function jobster_delete_company_profile_cover() {
        check_ajax_referer('company_profile_ajax_nonce', 'security');

        $company_id =   isset($_POST['company_id'])
                        ? sanitize_text_field($_POST['company_id'])
                        : '';
        $cover_id = isset($_POST['cover_id'])
                    ? sanitize_text_field($_POST['cover_id'])
                    : '';

        $delete_cover = wp_delete_attachment($cover_id, true);

        if (!is_wp_error($delete_cover)) {
            update_post_meta($company_id, 'company_cover', $cover_id);
            echo json_encode(array('delete' => true));
            exit();
        } else {
            echo json_encode(array('delete' => false, 'info' => $delete_cover));
            exit();
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_delete_company_profile_cover',
    'jobster_delete_company_profile_cover'
);
add_action(
    'wp_ajax_jobster_delete_company_profile_cover',
    'jobster_delete_company_profile_cover'
);

if (!function_exists('jobster_delete_company_profile_logo')): 
    function jobster_delete_company_profile_logo() {
        check_ajax_referer('company_profile_ajax_nonce', 'security');

        $company_id =   isset($_POST['company_id'])
                        ? sanitize_text_field($_POST['company_id'])
                        : '';
        $logo_id = isset($_POST['logo_id'])
                    ? sanitize_text_field($_POST['logo_id'])
                    : '';

        $delete_logo = wp_delete_attachment($logo_id, true);

        if (!is_wp_error($delete_logo)) {
            update_post_meta($company_id, 'company_logo', $logo_id);
            echo json_encode(array('delete' => true));
            exit();
        } else {
            echo json_encode(array('delete' => false, 'info' => $delete_logo));
            exit();
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_delete_company_profile_logo',
    'jobster_delete_company_profile_logo'
);
add_action(
    'wp_ajax_jobster_delete_company_profile_logo',
    'jobster_delete_company_profile_logo'
);
?>