<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_jobs_pending')): 
    function jobster_admin_jobs_pending() {
        add_settings_section('jobster_jobs_pending_section', __('Pending Jobs', 'jobster'), 'jobster_jobs_pending_section_callback', 'jobster_jobs_pending_settings');
    }
endif;

if (!function_exists('jobster_jobs_pending_section_callback')): 
    function jobster_jobs_pending_section_callback() { 
        wp_nonce_field('add_jobs_pending_ajax_nonce', 'pxp-jobs-pending-security', true);

        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'job',
            'order' => 'DESC',
            'post_status' => 'pending',
            'meta_query' => array(
                array(
                    'key' => 'job_admin_pending',
                    'value' => '1'
                )
            )
        );

        $jobs = get_posts($args);
        

        if (is_array($jobs) && count($jobs) > 0) { ?>
            <table class="wp-list-table widefat fixed striped" style="margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Job', 'jobster'); ?></th>
                        <th><?php esc_html_e('Company', 'jobster'); ?></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job) : setup_postdata($job);
                        $company_id = get_post_meta(
                            $job->ID,
                            'job_company',
                            true
                        );
                        $company_url = get_permalink($company_id);
                        $company_name = get_the_title($company_id);
                        $company_email = get_post_meta(
                            $company_id,
                            'company_email',
                            true
                        ); ?>

                        <tr 
                            data-id="<?php echo esc_attr($job->ID); ?>" 
                            data-email= "<?php echo esc_attr($company_email); ?>"
                        >
                            <td>
                                <a 
                                    href="<?php echo esc_url($job->guid); ?>" 
                                    target="_blank" 
                                    style="font-weight: bold;"
                                >
                                    <?php echo esc_html($job->post_title); ?>
                                </a>
                            </td>
                            <td>
                                <a 
                                    href="<?php echo esc_url($company_url); ?>" 
                                    target="_blank" 
                                    style="font-weight: bold;"
                                >
                                    <?php echo esc_html($company_name); ?>
                                </a>
                            </td>
                            <td style="text-align:right;">
                                <a 
                                    href="javascript:void(0);" 
                                    class="button pxp-approve-job-btn"
                                >
                                    <span class="fa fa-check"></span>
                                    <span class="fa fa-spin fa-spinner" style="display: none;"></span>
                                    <?php esc_html_e('Approve', 'jobster'); ?>
                                </a>
                                <a 
                                    href="javascript:void(0);" 
                                    class="button pxp-reject-job-btn"
                                >
                                    <span class="fa fa-close"></span>
                                    <span class="fa fa-spin fa-spinner" style="display: none;"></span>
                                    <?php esc_html_e('Reject', 'jobster'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
        <?php } else { ?>
            <p><i><?php esc_html_e('No pending jobs.', 'jobster'); ?></i></p>
        <?php }

        wp_reset_postdata();
        wp_reset_query();
    }
endif;

/**
 * Approve pending job
 */
if (!function_exists('jobster_approve_pending_job')): 
    function jobster_approve_pending_job() {
        check_ajax_referer('add_jobs_pending_ajax_nonce', 'security');

        $job_id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
        $company_email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

        if ($job_id != '') {
            update_post_meta($job_id, '(job_admin_pending', '0');
            $company_id = get_post_meta($job_id, 'job_company', true);

            $job = array(
                'ID' => $job_id,
                'post_status' => 'publish'
            );
            $update_job = wp_update_post($job);

            if (is_wp_error($update_job)) {
                echo json_encode(array('approve' => false));
                exit();
            } else {
                jobster_notify_job_approve($company_email, $job_id);
                jobster_job_approve_push_notification($job_id, $company_id);

                echo json_encode(array('approve' => true));
                exit();
            }
        } else {
            echo json_encode(array('approve' => false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_approve_pending_job', 'jobster_approve_pending_job');
add_action('wp_ajax_jobster_approve_pending_job', 'jobster_approve_pending_job');

/**
 * Reject pending job
 */
if (!function_exists('jobster_reject_pending_job')): 
    function jobster_reject_pending_job() {
        check_ajax_referer('add_jobs_pending_ajax_nonce', 'security');

        $job_id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
        $company_email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

        if ($job_id != '') {
            $company_id = get_post_meta($job_id, 'job_company', true);
            $job = array(
                'ID' => $job_id,
                'post_status' => 'draft'
            );
            $update_job = wp_update_post($job);

            if (is_wp_error($update_job)) {
                echo json_encode(array('reject' => false));
                exit();
            } else {
                jobster_notify_job_reject($company_email, $job_id);
                jobster_job_reject_push_notification($job_id, $company_id);

                echo json_encode(array('reject' => true));
                exit();
            }
        } else {
            echo json_encode(array('reject' => false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_reject_pending_job', 'jobster_reject_pending_job');
add_action('wp_ajax_jobster_reject_pending_job', 'jobster_reject_pending_job');

/**
 * Job approval company notification
 */
if (!function_exists('jobster_notify_job_approve')): 
    function jobster_notify_job_approve($email, $job_id) {
        $message = sprintf(__('Your job posting on %s was approved.', 'jobster'), get_option('blogname')) . "\r\n\r\n";
        $message .= '<a href="' . get_permalink($job_id) . '">
                        ' . get_the_title($job_id) . '
                    </a>';

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option('blogname') . '<' . get_option('admin_email') . '>',
            'Reply-To: ' . get_option('blogname') . '<' . get_option('admin_email') . '>'
        );

        $send = wp_mail(
            $email,
            sprintf(__('[%s] Job Posting Approved', 'jobster'), get_option('blogname')),
            $message,
            $headers
        );

        if (!$send) {
            wp_mail(
                $email,
                sprintf(__('[%s] Job Posting Approved', 'jobster'), get_option('blogname')),
                $message
            );
        }
    }
endif;

/**
 * Job reject company notification
 */
if (!function_exists('jobster_notify_job_reject')): 
    function jobster_notify_job_reject($email, $job_id) {
        $message = sprintf(__('Your job posting on %s was rejected.', 'jobster'), get_option('blogname')) . "\r\n\r\n";
        $message .= '<b>' . get_the_title($job_id) . '</b>';

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option('blogname') . '<' . get_option('admin_email') . '>',
            'Reply-To: ' . get_option('blogname') . '<' . get_option('admin_email') . '>'
        );

        $send = wp_mail(
            $email,
            sprintf(__('[%s] Job Posting Rejected', 'jobster'), get_option('blogname')),
            $message,
            $headers
        );

        if (!$send) {
            wp_mail(
                $email,
                sprintf(__('[%s] Job Posting Rejected', 'jobster'), get_option('blogname')),
                $message
            );
        }
    }
endif;

/**
 * Job approval push notification
 */
if (!function_exists('jobster_job_approve_push_notification')): 
    function jobster_job_approve_push_notification($job_id, $company_id) {
        $notifications = get_post_meta(
            $company_id,
            'company_notifications',
            true
        );

        if (empty($notifications)) {
            $notifications = array();
        }

        array_push(
            $notifications,
            array(
                'type'   => 'job_approve',
                'job_id' => $job_id,
                'read'   => false,
                'date'   => current_time('mysql')
            )
        );
        update_post_meta(
            $company_id,
            'company_notifications',
            $notifications
        );
    }
endif;

/**
 * Job reject push notification
 */
if (!function_exists('jobster_job_reject_push_notification')): 
    function jobster_job_reject_push_notification($job_id, $company_id) {
        $notifications = get_post_meta(
            $company_id,
            'company_notifications',
            true
        );

        if (empty($notifications)) {
            $notifications = array();
        }

        array_push(
            $notifications,
            array(
                'type'   => 'job_reject',
                'job_id' => $job_id,
                'read'   => false,
                'date'   => current_time('mysql')
            )
        );
        update_post_meta(
            $company_id,
            'company_notifications',
            $notifications
        );
    }
endif;
?>