<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_upload_file')): 
    function jobster_upload_file() {
        $file = array(
            'name'     => sanitize_text_field($_FILES['pxp_upload_file']['name']),
            'type'     => sanitize_text_field($_FILES['pxp_upload_file']['type']),
            'tmp_name' => sanitize_text_field($_FILES['pxp_upload_file']['tmp_name']),
            'error'    => sanitize_text_field($_FILES['pxp_upload_file']['error']),
            'size'     => sanitize_text_field($_FILES['pxp_upload_file']['size'])
        );

        $file = jobster_fileupload_process_file($file);
    }
endif;
add_action('wp_ajax_jobster_upload_file', 'jobster_upload_file');
add_action('wp_ajax_nopriv_jobster_upload_file', 'jobster_upload_file');

if (!function_exists('jobster_delete_file')): 
    function jobster_delete_file() {
        $attach_id = intval(sanitize_text_field($_POST['attach_id']));

        wp_delete_attachment($attach_id, true);
        exit;
    }
endif;
add_action('wp_ajax_jobster_delete_file', 'jobster_delete_file');
add_action('wp_ajax_nopriv_jobster_delete_file', 'jobster_delete_file');

if (!function_exists('jobster_fileupload_process_file')): 
    function jobster_fileupload_process_file($file) {
        $attachment = jobster_handle_file($file);

        if (is_array($attachment)) {
            $response = array(
                'success' => true,
                'html'    => wp_get_attachment_url($attachment['id']),
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

if (!function_exists('jobster_handle_file')): 
    function jobster_handle_file($upload_data) {
        $return = false;
        $uploaded_file = wp_handle_upload(
            $upload_data, 
            array('test_form' => false)
        );

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

            $attach_id = wp_insert_attachment($attachment, $file_loc);

            $file_dir = wp_get_upload_dir();
            $file_dir_file = $file_dir['subdir'] . '/' . $file_name;

            $return = array(
                'data' => array('file' => $file_dir_file),
                'id' => $attach_id
            );

            return $return;
        }

        return $return;
    }
endif;
?>