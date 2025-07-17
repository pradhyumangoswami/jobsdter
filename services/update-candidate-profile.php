<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_update_candidate_profile')): 
    function jobster_update_candidate_profile() {
        check_ajax_referer('candidate_profile_ajax_nonce', 'security');

        $candidate_id = isset($_POST['candidate_id'])
                        ? sanitize_text_field($_POST['candidate_id'])
                        : '';
        $name = isset($_POST['name'])
                ? sanitize_text_field($_POST['name'])
                : '';
        $email =    isset($_POST['email'])
                    ? sanitize_email($_POST['email'])
                    : '';
        $phone =    isset($_POST['phone'])
                    ? sanitize_text_field($_POST['phone'])
                    : '';
        $title =    isset($_POST['title'])
                    ? sanitize_text_field($_POST['title'])
                    : '';
        $website =  isset($_POST['website'])
                    ? sanitize_text_field($_POST['website'])
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
        $logo =     isset($_POST['logo'])
                    ? sanitize_text_field($_POST['logo'])
                    : '';
        $about =  isset($_POST['about']) ? $_POST['about'] : '';
        $industry = isset($_POST['industry'])
                    ? sanitize_text_field($_POST['industry'])
                    : '0';
        $location = isset($_POST['location'])
                    ? sanitize_text_field($_POST['location'])
                    : '0';
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
        $cv =   isset($_POST['cv'])
                ? sanitize_text_field($_POST['cv'])
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
        $alerts =   isset($_POST['alerts'])
                    ? sanitize_text_field($_POST['alerts'])
                    : '';
        $alerts_location =  isset($_POST['alerts_location'])
                            ? jobster_sanitize_array($_POST['alerts_location'])
                            : [];
        $alerts_category =  isset($_POST['alerts_category'])
                            ? jobster_sanitize_array($_POST['alerts_category'])
                            : [];
        $alerts_type =  isset($_POST['alerts_type'])
                        ? jobster_sanitize_array($_POST['alerts_type'])
                        : [];
        $alerts_level = isset($_POST['alerts_level'])
                        ? jobster_sanitize_array($_POST['alerts_level'])
                        : [];
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
                    'field' => 'pxp-candidate-profile-name'
                )
            );
            exit();
        }
        if (!is_email($email)) {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Invalid email address.', 'jobster'),
                    'field' => 'pxp-candidate-profile-email'
                )
            );
            exit();
        }
        if ($industry == '0') {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Industry field is mandatory.', 'jobster'),
                    'field' => 'pxp-candidate-profile-industry'
                )
            );
            exit();
        }
        if ($location == '0') {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Location field is mandatory.', 'jobster'),
                    'field' => 'pxp-candidate-profile-location'
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
                'ID' => $candidate_id,
                'post_title' => $name,
                'post_content' => $about,
                'post_status' => 'publish'
            )
        );

        update_post_meta($candidate_id, 'candidate_email', $email);
        update_post_meta($candidate_id, 'candidate_phone', $phone);
        update_post_meta($candidate_id, 'candidate_title', $title);
        update_post_meta($candidate_id, 'candidate_website', $website);
        update_post_meta($candidate_id, 'candidate_cover', $cover);
        update_post_meta($candidate_id, 'candidate_cover_type', $cover_type);
        update_post_meta($candidate_id, 'candidate_cover_color', $cover_color);
        update_post_meta($candidate_id, 'candidate_photo', $logo);
        update_post_meta($candidate_id, 'candidate_facebook', $facebook);
        update_post_meta($candidate_id, 'candidate_twitter', $twitter);
        update_post_meta($candidate_id, 'candidate_instagram', $instagram);
        update_post_meta($candidate_id, 'candidate_linkedin', $linkedin);
        update_post_meta($candidate_id, 'candidate_gallery', $gallery);
        update_post_meta($candidate_id, 'candidate_gallery_title', $gallery_title);
        update_post_meta($candidate_id, 'candidate_video', $video);
        update_post_meta($candidate_id, 'candidate_video_title', $video_title);
        update_post_meta($candidate_id, 'candidate_job_alerts', $alerts);
        update_post_meta($candidate_id, 'candidate_job_alerts_location', $alerts_location);
        update_post_meta($candidate_id, 'candidate_job_alerts_category', $alerts_category);
        update_post_meta($candidate_id, 'candidate_job_alerts_type', $alerts_type);
        update_post_meta($candidate_id, 'candidate_job_alerts_level', $alerts_level);

        wp_set_object_terms(
            $candidate_id,
            array(intval($industry)),
            'candidate_industry'
        );
        wp_set_object_terms(
            $candidate_id,
            array(intval($location)),
            'candidate_location'
        );

        if (isset($_POST['skills'])) {
            $skills_data_raw = urldecode($_POST['skills']);
            $skills_data = json_decode($skills_data_raw);

            $new_skills = array();

            if (isset($skills_data)) {
                if (is_array($skills_data) && count($skills_data) > 0) {
                    foreach ($skills_data as $skill_data) {
                        if ($skill_data->id == '') {
                            $skill_exist = term_exists(
                                $skill_data->name,
                                'candidate_skill'
                            );

                            if ($skill_exist) {
                                array_push(
                                    $new_skills,
                                    intval($skill_exist['term_id'])
                                );
                            } else {
                                $new_skill = wp_insert_term(
                                    sanitize_text_field($skill_data->name),
                                    'candidate_skill'
                                );
                                array_push(
                                    $new_skills,
                                    intval($new_skill['term_id'])
                                );
                            }
                        } else {
                            array_push(
                                $new_skills,
                                intval(intval($skill_data->id))
                            );
                        }
                    }
                }
            }
            wp_set_object_terms(
                $candidate_id,
                $new_skills,
                'candidate_skill'
            );
        }

        if (isset($_POST['work'])) {
            $work_list = array();
            $work_data_raw = urldecode($_POST['work']);
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

            update_post_meta(
                $candidate_id,
                'candidate_work',
                $work_data_encoded
            );
        }

        if (isset($_POST['education'])) {
            $edu_list = array();
            $edu_data_raw = urldecode($_POST['education']);
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

            update_post_meta(
                $candidate_id,
                'candidate_edu',
                $edu_data_encoded
            );
        }

        update_post_meta($candidate_id, 'candidate_cv', $cv);

        if (isset($_POST['files'])) {
            $files_list = array();
            $files_data_raw = urldecode($_POST['files']);
            $files_data = json_decode($files_data_raw);

            $files_data_encoded = '';

            if (isset($files_data)) {
                $new_data_files = new stdClass();
                $new_files = array();

                $files_list = $files_data->files;

                foreach ($files_list as $files_item) {
                    $new_file = new stdClass();

                    $new_file->name = sanitize_text_field($files_item->name);
                    $new_file->id   = sanitize_text_field($files_item->id);
                    $new_file->url  = sanitize_text_field($files_item->url);

                    array_push($new_files, $new_file);
                }

                $new_data_files->files = $new_files;

                $files_data_before = json_encode($new_data_files);
                $files_data_encoded = urlencode($files_data_before);
            }

            update_post_meta(
                $candidate_id,
                'candidate_files',
                $files_data_encoded
            );
        }

        if ($custom_fields != '') {
            foreach ($custom_fields as $ucf_key => $ucf_value) {
                update_post_meta(
                    $candidate_id,
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
add_action(
    'wp_ajax_nopriv_jobster_update_candidate_profile',
    'jobster_update_candidate_profile'
);
add_action(
    'wp_ajax_jobster_update_candidate_profile',
    'jobster_update_candidate_profile'
);

if (!function_exists('jobster_add_candidate_location')): 
    function jobster_add_candidate_location() {
        check_ajax_referer('candidate_profile_ajax_nonce', 'security');

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
                    'field' => 'pxp-candidate-profile-location-new'
                )
            );
            exit();
        }

        $location_new = wp_insert_term(
            $name,
            'candidate_location',
            array(
                'parent' => $parent
            )
        );

        if (!is_wp_error($location_new)) {
            $loc_terms = get_terms(
                array(
                    'taxonomy' => 'candidate_location',
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
                    'field' => 'pxp-candidate-profile-location-new'
                )
            );
            exit();
        }
        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_add_candidate_location',
    'jobster_add_candidate_location'
);
add_action(
    'wp_ajax_jobster_add_candidate_location',
    'jobster_add_candidate_location'
);

if (!function_exists('jobster_delete_candidate_profile_cover')): 
    function jobster_delete_candidate_profile_cover() {
        check_ajax_referer('candidate_profile_ajax_nonce', 'security');

        $candidate_id = isset($_POST['candidate_id'])
                        ? sanitize_text_field($_POST['candidate_id'])
                        : '';
        $cover_id = isset($_POST['cover_id'])
                    ? sanitize_text_field($_POST['cover_id'])
                    : '';

        $delete_cover = wp_delete_attachment($cover_id, true);

        if (!is_wp_error($delete_cover)) {
            update_post_meta($candidate_id, 'candidate_cover', $cover_id);
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
    'wp_ajax_nopriv_jobster_delete_candidate_profile_cover',
    'jobster_delete_candidate_profile_cover'
);
add_action(
    'wp_ajax_jobster_delete_candidate_profile_cover',
    'jobster_delete_candidate_profile_cover'
);

if (!function_exists('jobster_delete_candidate_profile_photo')): 
    function jobster_delete_candidate_profile_photo() {
        check_ajax_referer('candidate_profile_ajax_nonce', 'security');

        $candidate_id = isset($_POST['candidate_id'])
                        ? sanitize_text_field($_POST['candidate_id'])
                        : '';
        $photo_id = isset($_POST['photo_id'])
                    ? sanitize_text_field($_POST['photo_id'])
                    : '';

        $delete_photo = wp_delete_attachment($photo_id, true);

        if (!is_wp_error($delete_photo)) {
            update_post_meta($candidate_id, 'candidate_photo', $photo_id);
            echo json_encode(array('delete' => true));
            exit();
        } else {
            echo json_encode(array('delete' => false, 'info' => $delete_photo));
            exit();
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_delete_candidate_profile_photo',
    'jobster_delete_candidate_profile_photo'
);
add_action(
    'wp_ajax_jobster_delete_candidate_profile_photo',
    'jobster_delete_candidate_profile_photo'
);
?>