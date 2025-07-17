<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_candidate_add_fav')): 
    function jobster_candidate_add_fav() {
        check_ajax_referer('favs_ajax_nonce', 'security');

        $job_id =   isset($_POST['job_id']) 
                    ? sanitize_text_field($_POST['job_id'])
                    : '';

        if (is_user_logged_in()) {
            global $current_user;
            $current_user = wp_get_current_user();

            $is_candidate = function_exists('jobster_user_is_candidate')
                            ? jobster_user_is_candidate($current_user->ID)
                            : false;
            if ($is_candidate) {
                $candidate_id = jobster_get_candidate_by_userid($current_user->ID);
                $favs = get_post_meta(
                    $candidate_id,
                    'candidate_favs',
                    true
                );

                if (empty($favs)) {
                    $favs = array();
                }
                if (!in_array($job_id, $favs)) {
                    array_push($favs, sanitize_text_field($job_id));
                    update_post_meta($candidate_id, 'candidate_favs', $favs);
                }

                echo json_encode(array('saved' => true, 'favs' => $favs));
                exit;
            } else {
                echo json_encode(array('saved' => false));
                exit;
            }
        } else {
            echo json_encode(array('saved' => false));
            exit;
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_candidate_add_fav',
    'jobster_candidate_add_fav'
);
add_action(
    'wp_ajax_jobster_candidate_add_fav',
    'jobster_candidate_add_fav'
);

if (!function_exists('jobster_candidate_remove_fav')): 
    function jobster_candidate_remove_fav() {
        check_ajax_referer('favs_ajax_nonce', 'security');

        $job_ids = array();
        if (isset($_POST['job_ids']) && is_array($_POST['job_ids'])) {
            $job_ids = $_POST['job_ids'];
        }

        if (is_user_logged_in()) {
            global $current_user;
            $current_user = wp_get_current_user();

            $is_candidate = function_exists('jobster_user_is_candidate')
                            ? jobster_user_is_candidate($current_user->ID)
                            : false;
            if ($is_candidate) {
                $candidate_id = jobster_get_candidate_by_userid($current_user->ID);
                $favs = get_post_meta(
                    $candidate_id,
                    'candidate_favs',
                    true
                );

                $for_remove = array();
                foreach ($job_ids as $job_id) {
                    if (is_array($favs) 
                    && !empty($favs)
                    && in_array($job_id, $favs)) {
                        array_push($for_remove, $job_id);
                    }
                }

                if (count($for_remove) > 0) {
                    $favs = array_diff($favs, $for_remove);
                    update_post_meta($candidate_id, 'candidate_favs', $favs);

                    echo json_encode(array('removed' => true, 'favs' => $favs));
                    exit;
                }
            } else {
                echo json_encode(array('removed' => false));
                exit;
            }
        } else {
            echo json_encode(array('removed' => false));
            exit;
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_candidate_remove_fav',
    'jobster_candidate_remove_fav'
);
add_action(
    'wp_ajax_jobster_candidate_remove_fav',
    'jobster_candidate_remove_fav'
);
?>