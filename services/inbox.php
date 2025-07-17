<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_insert_comment')): 
    function jobster_insert_comment($field, $post_id) {
        if (comments_open($post_id)) {
            $data = array(
                'comment_post_ID' => $post_id,
                'comment_content' => $field['message'],
                'user_id'         => $field['user_id'],
                'comment_meta'    => array(
                    'candidate_id' => $field['candidate_id'],
                    'company_id'   => $field['company_id'],
                    'read'         => false
                )
            );

            $comment_id = wp_insert_comment($data);

            if (!is_wp_error($comment_id)) {
                jobster_job_inbox_save_notification(
                    $post_id,
                    $field['candidate_id'],
                    $field['company_id']
                );

                return $comment_id;
            }
        }

        return false;
    }
endif;

/**
 * Save the notifications in notifications system for post ID (candidate/company)
 */
if (!function_exists('jobster_job_inbox_save_notification')): 
    function jobster_job_inbox_save_notification(
        $post_id,
        $candidate_id,
        $company_id
    ) {
        $post_type = get_post_type($post_id);

        if ($post_type == 'company') {
            $notifications = get_post_meta(
                $post_id,
                'company_notifications',
                true
            );

            if (empty($notifications)) {
                $notifications = array();
            }

            array_push(
                $notifications,
                array(
                    'type'         => 'inbox',
                    'candidate_id' => $candidate_id,
                    'read'         => false,
                    'date'         => current_time('mysql')
                )
            );

            update_post_meta(
                $company_id,
                'company_notifications',
                $notifications
            );
        }

        if ($post_type == 'candidate') {
            $notifications = get_post_meta(
                $post_id,
                'candidate_notifications',
                true
            );

            if (empty($notifications)) {
                $notifications = array();
            }

            array_push(
                $notifications,
                array(
                    'type'       => 'inbox',
                    'company_id' => $company_id,
                    'read'       => false,
                    'date'       => current_time('mysql')
                )
            );

            update_post_meta(
                $candidate_id,
                'candidate_notifications',
                $notifications
            );
        }
    }
endif;

/**
 * Update 'read' status if open an inbox conversation
 */
if (!function_exists('jobster_update_inbox_message_status')): 
    function jobster_update_inbox_message_status($post_id, $id_get) {
        $post_type = get_post_type($post_id);

        $inbox_args = array(
            'post_id' => $post_id
        ); 
        $inbox_messages = get_comments($inbox_args);

        if (is_array($inbox_messages)) {
            foreach ($inbox_messages as $message) {

                if ($post_type == 'company') {
                    $candidate_id = get_comment_meta(
                        $message->comment_ID,
                        'candidate_id',
                        true
                    );

                    if ($candidate_id == $id_get) {
                        update_comment_meta(
                            $message->comment_ID,
                            'read',
                            true
                        );
                    }
                }

                if ($post_type == 'candidate') {
                    $company_id = get_comment_meta(
                        $message->comment_ID,
                        'company_id',
                        true
                    );

                    if ($company_id == $id_get) {
                        update_comment_meta(
                            $message->comment_ID,
                            'read',
                            true
                        );
                    }
                }
            }
        }
    }
endif;
?>