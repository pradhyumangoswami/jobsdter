<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_set_company_read_notifications')): 
    function jobster_set_company_read_notifications() {
        check_ajax_referer('notifications_ajax_nonce', 'security');

        $company_id =   isset($_POST['company_id']) 
                        ? sanitize_text_field($_POST['company_id'])
                        : '';

        $notifications = get_post_meta(
            $company_id,
            'company_notifications',
            true
        );

        if (is_array($notifications)) {
            foreach ($notifications as &$notification) {
                $notification['read'] = true;
            }

            unset($notification);

            update_post_meta(
                $company_id,
                'company_notifications',
                $notifications
            );

            echo json_encode(array('set' => true));
            exit;
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_set_company_read_notifications',
    'jobster_set_company_read_notifications'
);
add_action(
    'wp_ajax_jobster_set_company_read_notifications',
    'jobster_set_company_read_notifications'
);

if (!function_exists('jobster_set_candidate_read_notifications')): 
    function jobster_set_candidate_read_notifications() {
        check_ajax_referer('notifications_ajax_nonce', 'security');

        $candidate_id =   isset($_POST['candidate_id']) 
                        ? sanitize_text_field($_POST['candidate_id'])
                        : '';

        $notifications = get_post_meta(
            $candidate_id,
            'candidate_notifications',
            true
        );

        if (is_array($notifications)) {
            foreach ($notifications as &$notification) {
                $notification['read'] = true;
            }

            unset($notification);

            update_post_meta(
                $candidate_id,
                'candidate_notifications',
                $notifications
            );

            echo json_encode(array('set' => true));
            exit;
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_set_candidate_read_notifications',
    'jobster_set_candidate_read_notifications'
);
add_action(
    'wp_ajax_jobster_set_candidate_read_notifications',
    'jobster_set_candidate_read_notifications'
);

/**
 * Delete company notification
 */
if (!function_exists('jobster_delete_company_notify')): 
    function jobster_delete_company_notify() {
        check_ajax_referer('company_notifications_ajax_nonce', 'security');

        $company_id =   isset($_POST['company_id']) 
                        ? sanitize_text_field($_POST['company_id'])
                        : '';
        $offset =   isset($_POST['offset']) 
                    ? sanitize_text_field($_POST['offset'])
                    : '';

        $notifications = get_post_meta(
            $company_id,
            'company_notifications',
            true
        );

        if (is_array($notifications) && $offset != '') {
            $new_notifs = array_reverse($notifications);

            array_splice($new_notifs, intval($offset), 1);

            $back_notifs = array_reverse($new_notifs);

            update_post_meta(
                $company_id,
                'company_notifications',
                $back_notifs
            );

            echo json_encode(array('deleted' => true)); 
            exit;
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_delete_company_notify',
    'jobster_delete_company_notify'
);
add_action(
    'wp_ajax_jobster_delete_company_notify',
    'jobster_delete_company_notify'
);

/**
 * Delete candidate notification
 */
if (!function_exists('jobster_delete_candidate_notify')): 
    function jobster_delete_candidate_notify() {
        check_ajax_referer('candidate_notifications_ajax_nonce', 'security');

        $candidate_id = isset($_POST['candidate_id']) 
                        ? sanitize_text_field($_POST['candidate_id'])
                        : '';
        $offset =   isset($_POST['offset']) 
                    ? sanitize_text_field($_POST['offset'])
                    : '';

        $notifications = get_post_meta(
            $candidate_id,
            'candidate_notifications',
            true
        );

        if (is_array($notifications) && $offset != '') {
            $new_notifs = array_reverse($notifications);

            array_splice($new_notifs, intval($offset), 1);

            $back_notifs = array_reverse($new_notifs);

            update_post_meta(
                $candidate_id,
                'candidate_notifications',
                $back_notifs
            );

            echo json_encode(array('deleted' => true)); 
            exit;
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_delete_candidate_notify',
    'jobster_delete_candidate_notify'
);
add_action(
    'wp_ajax_jobster_delete_candidate_notify',
    'jobster_delete_candidate_notify'
);
?>