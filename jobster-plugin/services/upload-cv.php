<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_upload_cv')): 
    function jobster_upload_cv() {
        $file_name = sanitize_text_field($_FILES['pxp_upload_file_cv']['name']);
        $file_info = pathinfo($file_name);
        $file_str = $file_info['filename'] . '-private.' . $file_info['extension'];

        $file = array(
            'name'     => $file_str,
            'type'     => sanitize_text_field($_FILES['pxp_upload_file_cv']['type']),
            'tmp_name' => sanitize_text_field($_FILES['pxp_upload_file_cv']['tmp_name']),
            'error'    => sanitize_text_field($_FILES['pxp_upload_file_cv']['error']),
            'size'     => sanitize_text_field($_FILES['pxp_upload_file_cv']['size'])
        );

        $file = jobster_fileupload_process_cv($file);
    }
endif;
add_action('wp_ajax_jobster_upload_cv', 'jobster_upload_cv');
add_action('wp_ajax_nopriv_jobster_upload_cv', 'jobster_upload_cv');

if (!function_exists('jobster_delete_file_cv')): 
    function jobster_delete_file_cv() {
        $attach_id = intval(sanitize_text_field($_POST['attach_id']));

        wp_delete_attachment($attach_id, true);
        exit;
    }
endif;
add_action('wp_ajax_jobster_delete_file_cv', 'jobster_delete_file_cv');
add_action('wp_ajax_nopriv_jobster_delete_file_cv', 'jobster_delete_file_cv');

if (!function_exists('jobster_fileupload_process_cv')): 
    function jobster_fileupload_process_cv($file) {
        $attachment = jobster_handle_file_cv($file);

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

if (!function_exists('jobster_handle_file_cv')): 
    function jobster_handle_file_cv($upload_data) {
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

            $cv_dir = wp_get_upload_dir();
            $cv_dir_file = $cv_dir['subdir'] . '/' . $file_name;

            $return = array(
                'data' => array('file' => $cv_dir_file),
                'id' => $attach_id
            );

            return $return;
        }

        return $return;
    }
endif;
?>