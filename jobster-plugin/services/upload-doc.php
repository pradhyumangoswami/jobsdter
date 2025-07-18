<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_upload_doc')): 
    function jobster_upload_doc() {
        $file = array(
            'name'     => sanitize_text_field($_FILES['pxp_upload_file_doc']['name']),
            'type'     => sanitize_text_field($_FILES['pxp_upload_file_doc']['type']),
            'tmp_name' => sanitize_text_field($_FILES['pxp_upload_file_doc']['tmp_name']),
            'error'    => sanitize_text_field($_FILES['pxp_upload_file_doc']['error']),
            'size'     => sanitize_text_field($_FILES['pxp_upload_file_doc']['size'])
        );

        $file = jobster_fileupload_process_doc($file);
    }
endif;
add_action('wp_ajax_jobster_upload_doc', 'jobster_upload_doc');
add_action('wp_ajax_nopriv_jobster_upload_doc', 'jobster_upload_doc');

if (!function_exists('jobster_delete_file_doc')): 
    function jobster_delete_file_doc() {
        $attach_id = intval(sanitize_text_field($_POST['attach_id']));

        wp_delete_attachment($attach_id, true);
        exit;
    }
endif;
add_action('wp_ajax_jobster_delete_file_doc', 'jobster_delete_file_doc');
add_action('wp_ajax_nopriv_jobster_delete_file_doc', 'jobster_delete_file_doc');

if (!function_exists('jobster_fileupload_process_doc')): 
    function jobster_fileupload_process_doc($file) {
        $attachment = jobster_handle_file_doc($file);

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

if (!function_exists('jobster_handle_file_doc')): 
    function jobster_handle_file_doc($upload_data) {
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

            $doc_dir = wp_get_upload_dir();
            $doc_dir_file = $doc_dir['subdir'] . '/' . $file_name;

            $return = array(
                'data' => array('file' => $doc_dir_file),
                'id' => $attach_id
            );

            return $return;
        }

        return $return;
    }
endif;
?>