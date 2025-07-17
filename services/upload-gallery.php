<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_upload_gallery')): 
    function jobster_upload_gallery() {
        $file = array(
            'name'     => sanitize_text_field($_FILES['aaiu_upload_file_gallery']['name']),
            'type'     => sanitize_text_field($_FILES['aaiu_upload_file_gallery']['type']),
            'tmp_name' => sanitize_text_field($_FILES['aaiu_upload_file_gallery']['tmp_name']),
            'error'    => sanitize_text_field($_FILES['aaiu_upload_file_gallery']['error']),
            'size'     => sanitize_text_field($_FILES['aaiu_upload_file_gallery']['size'])
        );

        $file = jobster_fileupload_process_gallery($file);
    }
endif;
add_action('wp_ajax_jobster_upload_gallery', 'jobster_upload_gallery');
add_action('wp_ajax_nopriv_jobster_upload_gallery', 'jobster_upload_gallery');

if (!function_exists('jobster_delete_file_gallery')): 
    function jobster_delete_file_gallery() {
        $attach_id = intval(sanitize_text_field($_POST['attach_id']));

        wp_delete_attachment($attach_id, true);
        exit;
    }
endif;
add_action('wp_ajax_jobster_delete_file_gallery', 'jobster_delete_file_gallery');
add_action('wp_ajax_nopriv_jobster_delete_file_gallery', 'jobster_delete_file_gallery');

if (!function_exists('jobster_fileupload_process_gallery')): 
    function jobster_fileupload_process_gallery($file) {
        $attachment = jobster_handle_file_gallery($file);

        if (is_array($attachment)) {
            $html = jobster_get_html_gallery($attachment);
            $response = array(
                'success' => true,
                'html'    => $html,
                'attach'  => $attachment['id']
            );

            echo json_encode($response);
            exit;
        }

        $response = array('success' => false);

        echo json_encode($response);
        exit;
    }
endif;

if (!function_exists('jobster_handle_file_gallery')): 
    function jobster_handle_file_gallery($upload_data) {
        $return        = false;
        $uploaded_file = wp_handle_upload($upload_data, array('test_form' => false));

        if (isset($uploaded_file['file'])) {
            $file_loc  = $uploaded_file['file'];
            $file_name = basename($upload_data['name']);
            $file_type = wp_check_filetype($file_name);

            $attachment = array(
                'post_mime_type' => $file_type['type'],
                'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            $attach_id   = wp_insert_attachment($attachment, $file_loc);
            $attach_data = wp_generate_attachment_metadata($attach_id, $file_loc);

            wp_update_attachment_metadata($attach_id, $attach_data);

            $return = array('data' => $attach_data, 'id' => $attach_id);

            return $return;
        }

        return $return;
    }
endif;

if (!function_exists('jobster_get_html_gallery')): 
    function jobster_get_html_gallery($attachment) {
        $attach_id = $attachment['id'];
        $post      = get_post($attach_id);
        $dir       = wp_upload_dir();
        $path      = $dir['baseurl'];
        $file      = $attachment['data']['file'];
        $html      = '';
        $html      .= $path . '/' . $file;

        return $html;
    }
endif;

if (!function_exists('jobster_delete_company_profile_gallery_photo')): 
    function jobster_delete_company_profile_gallery_photo() {
        check_ajax_referer('company_profile_ajax_nonce', 'security');

        $photo_id = isset($_POST['photo_id'])
                    ? sanitize_text_field($_POST['photo_id'])
                    : '';

        $delete_photo = wp_delete_attachment($photo_id, true);

        if (!is_wp_error($delete_photo)) {
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
    'wp_ajax_nopriv_jobster_delete_company_profile_gallery_photo',
    'jobster_delete_company_profile_gallery_photo'
);
add_action(
    'wp_ajax_jobster_delete_company_profile_gallery_photo',
    'jobster_delete_company_profile_gallery_photo'
);

if (!function_exists('jobster_delete_candidate_profile_gallery_photo')): 
    function jobster_delete_candidate_profile_gallery_photo() {
        check_ajax_referer('candidate_profile_ajax_nonce', 'security');

        $photo_id = isset($_POST['photo_id'])
                    ? sanitize_text_field($_POST['photo_id'])
                    : '';

        $delete_photo = wp_delete_attachment($photo_id, true);

        if (!is_wp_error($delete_photo)) {
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
    'wp_ajax_nopriv_jobster_delete_candidate_profile_gallery_photo',
    'jobster_delete_candidate_profile_gallery_photo'
);
add_action(
    'wp_ajax_jobster_delete_candidate_profile_gallery_photo',
    'jobster_delete_candidate_profile_gallery_photo'
);
?>