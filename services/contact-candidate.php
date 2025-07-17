<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_contact_candidate')): 
    function jobster_contact_candidate() {
        check_ajax_referer('contact_candidate_ajax_nonce', 'security');

        if (is_user_logged_in()) {
            global $current_user;
    
            $current_user = wp_get_current_user();
            $is_company = function_exists('jobster_user_is_company')
                            ? jobster_user_is_company($current_user->ID)
                            : false;
            if ($is_company) {
                $company_id = jobster_get_company_by_userid($current_user->ID);
                $candidate_id  =    isset($_POST['candidate_id'])
                                    ? sanitize_text_field($_POST['candidate_id'])
                                    : '';
                $message =  isset($_POST['message'])
                            ? sanitize_text_field($_POST['message'])
                            : '';
                $company_name = get_the_title($company_id);
                $company_email = get_post_meta($company_id, 'company_email', true);
                $candidate_email = get_post_meta($candidate_id, 'candidate_email', true);

                if (empty($message)) {
                    echo json_encode(
                        array(
                            'sent' => false,
                            'message' => __('Please type a message.', 'jobster')
                        )
                    );
                    exit();
                }

                $inbox_fields = array(
                    'candidate_id' => $candidate_id,
                    'company_id'   => $company_id,
                    'user_id'      => $current_user->ID,
                    'message'      => $message
                );
                $comment_id = jobster_insert_comment($inbox_fields, $candidate_id);
                $comment = get_comment($comment_id);
                $time = date("H:i", strtotime($comment->comment_date));
        
                $headers = array(
                    'Content-Type: text/html; charset=UTF-8',
                    'From: ' . $company_name . '<' . $company_email . '>',
                    'Reply-To: ' . $company_name . '<' . $company_email . '>'
                );

                $email_settings = get_option('jobster_email_settings');
                $template = isset($email_settings['jobster_email_contact_candidate_field']) 
                            ? $email_settings['jobster_email_contact_candidate_field'] 
                            : '';

                if ($template != '') {
                    $template = str_replace("{CLIENT_NAME}", $company_name, $template);
                    $template = str_replace("{CLIENT_EMAIL}", $company_email, $template);
                    $template = str_replace("{CLIENT_MESSAGE}", $message, $template);

                    $send = wp_mail(
                        $candidate_email,
                        sprintf( __('[%s] Message from company', 'jobster'), get_option('blogname') ),
                        $template,
                        $headers
                    );
                } else {
                    $body = __('You received the following message from ', 'jobster') . 
                            $company_name . ' [' . __('Email', 'jobster') . ': ' . $company_email . ']' . "\r\n\r\n" . 
                            '<i>' . $message . '</i>';

                    $send = wp_mail(
                        $candidate_email,
                        sprintf( __('[%s] Message from company', 'jobster'), get_option('blogname') ),
                        $body,
                        $headers
                    );
                }

                if ($send) {
                    echo json_encode(
                        array(
                            'sent' => true,
                            'message' => __('Your message was successfully sent.', 'jobster'),
                            'time' => $time
                        )
                    );
                    exit();
                } else {
                    echo json_encode(
                        array(
                            'sent' => false,
                            'message' => __('Your message failed to be sent.', 'jobster')
                        )
                    );
                    exit();
                }
            }
        }

        exit();
        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_contact_candidate', 'jobster_contact_candidate');
add_action('wp_ajax_jobster_contact_candidate', 'jobster_contact_candidate');
?>