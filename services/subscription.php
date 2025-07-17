<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Save subscription
 */
if (!function_exists('jobster_save_subscription')): 
    function jobster_save_subscription() {
        check_ajax_referer('subscribe_ajax_nonce', 'security');

        $email = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : '';

        if (!is_email($email)) {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('Please provide a valid email address.', 'jobster')
                )
            );
            exit();
        }

        $args = array(
            'post_type' => 'subscriber',
            'posts_per_page' => -1,
            's' => $email
        );

        $subscriber_selection     = new WP_Query($args);
        $subscriber_selection_arr = get_object_vars($subscriber_selection);

        if (is_array($subscriber_selection_arr['posts']) 
            && count($subscriber_selection_arr['posts']) > 0) {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('You have already subscribed.', 'jobster')
                )
            );
            exit();
        }

        $subscriber = array(
            'post_title'  => $email,
            'post_type'   => 'subscriber',
            'post_status' => 'draft'
        );

        $subscriber_id = wp_insert_post($subscriber);

        if ($subscriber_id) {
            echo json_encode(
                array(
                    'save' => true,
                    'message' => __('You have successfully subscribed.', 'jobster')
                )
            );
            exit();
        } else {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('Something went wrong, please try again.', 'jobster')
                )
            );
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_save_subscription', 'jobster_save_subscription');
add_action('wp_ajax_jobster_save_subscription', 'jobster_save_subscription');
?>