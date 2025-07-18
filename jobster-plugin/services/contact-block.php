<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_contact_block_send')): 
    function jobster_contact_block_send() {
        check_ajax_referer('contact_form_block_ajax_nonce', 'security');

        $company_email  = isset($_POST['company_email'])
                        ? sanitize_email($_POST['company_email'])
                        : '';
        $name           = isset($_POST['name'])
                        ? sanitize_text_field($_POST['name'])
                        : '';
        $email          = isset($_POST['email'])
                        ? sanitize_email($_POST['email'])
                        : '';
        $message        = isset($_POST['message']) 
                        ? sanitize_text_field($_POST['message'])
                        : '';

        if (empty($name) || empty($email) || empty($message)) {
            echo json_encode(
                array(
                    'sent' => false,
                    'message' => __('Your message failed to be sent. Please check your fields.', 'jobster')
                )
            );
            exit();
        }

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: '. $email,
            'Reply-To: ' . $email
        );

        $email_settings = get_option('jobster_email_settings');
        $template = isset($email_settings['jobster_email_contact_form_section_field']) 
                    ? $email_settings['jobster_email_contact_form_section_field'] 
                    : '';

        if ($template != '') {
            $template = str_replace("{CLIENT_NAME}", $name, $template);
            $template = str_replace("{CLIENT_EMAIL}", $email, $template);
            $template = str_replace("{CLIENT_MESSAGE}", $message, $template);

            $send = wp_mail(
                $company_email,
                sprintf( __('[%s] Message from contact form', 'jobster'), get_option('blogname') ),
                $template,
                $headers
            );
        } else {
            $body = __('You received the following message from ', 'jobster') . 
                $name . ' [' . __('Email', 'jobster') . ': ' . $email . ']' . "\r\n\r\n" .
                '<i>' . $message . '</i>';

            $send = wp_mail(
                $company_email,
                sprintf( __('[%s] Message from contact form', 'jobster'), get_option('blogname') ),
                $body,
                $headers
            );
        }

        if ($send) {
            echo json_encode(
                array(
                    'sent' => true,
                    'message' => __('Your message was successfully sent.', 'jobster')
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

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_contact_block_send',
    'jobster_contact_block_send'
);
add_action(
    'wp_ajax_jobster_contact_block_send',
    'jobster_contact_block_send'
);
?>