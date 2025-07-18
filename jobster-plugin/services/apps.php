<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_job_apply')): 
    function jobster_job_apply() {
        check_ajax_referer('apps_ajax_nonce', 'security');

        $job_id =   isset($_POST['job_id']) 
                    ? sanitize_text_field($_POST['job_id'])
                    : '';

        if (is_user_logged_in() && !empty($job_id)) {
            global $current_user;
            $current_user = wp_get_current_user();

            $is_candidate = function_exists('jobster_user_is_candidate')
                            ? jobster_user_is_candidate($current_user->ID)
                            : false;

            if ($is_candidate) {
                $candidate_id = jobster_get_candidate_by_userid($current_user->ID);

                $apps = get_post_meta(
                    $job_id,
                    'job_applications',
                    true
                );

                if (!is_array($apps)) {
                    $apps = array();
                }

                if (!array_key_exists($candidate_id, $apps)) {
                    $apps[$candidate_id] = array(
                        'date' => current_time('mysql'),
                        'status' => 'na'
                    );

                    update_post_meta($job_id, 'job_applications', $apps);

                    jobster_job_apply_notify_company(
                        $job_id,
                        $candidate_id
                    );

                    jobster_job_apply_save_notification(
                        $job_id,
                        $candidate_id
                    );
                }

                echo json_encode(array('saved' => true, 'apps' => $apps));
                exit;
            } else {
                echo json_encode(array('saved' => false));
                exit;
            }
        } else {
            echo json_encode(array('saved' => false));
            exit;
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_job_apply',
    'jobster_job_apply'
);
add_action(
    'wp_ajax_jobster_job_apply',
    'jobster_job_apply'
);

if (!function_exists('jobster_job_apply_anonymous')): 
    function jobster_job_apply_anonymous() {
        check_ajax_referer('candidate_apply_ajax_nonce', 'security');

        $job_id =   isset($_POST['job_id']) 
                    ? sanitize_text_field($_POST['job_id'])
                    : '';
        $name = isset($_POST['name']) 
                ? sanitize_text_field($_POST['name'])
                : '';
        $email =    isset($_POST['email'])
                    ? sanitize_email($_POST['email'])
                    : '';
        $phone =    isset($_POST['phone'])
                    ? sanitize_text_field($_POST['phone'])
                    : '';
        $message =  isset($_POST['message'])
                    ? sanitize_text_field($_POST['message'])
                    : '';
        $cv =   isset($_POST['cv'])
                ? sanitize_text_field($_POST['cv'])
                : '';

        if ($name == '') {
            echo json_encode(
                array(
                    'saved' => false,
                    'message' => __('Name field is mandatory.', 'jobster'),
                    'field' => 'pxp-candidate-apply-name'
                )
            );
            exit();
        }
        if (!is_email($email)) {
            echo json_encode(
                array(
                    'saved' => false,
                    'message' => __('Invalid email address.', 'jobster'),
                    'field' => 'pxp-candidate-apply-email'
                )
            );
            exit();
        }
        if ($phone == '') {
            echo json_encode(
                array(
                    'saved' => false,
                    'message' => __('Name field is mandatory.', 'jobster'),
                    'field' => 'pxp-candidate-apply-phone'
                )
            );
            exit();
        }

        $candidates_settings = get_option('jobster_candidates_settings');
        $resume_field = isset($candidates_settings['jobster_candidate_resume_field'])
                        ? $candidates_settings['jobster_candidate_resume_field'] 
                        : 'required';
        if ($resume_field == 'required' && $cv == '') {
            echo json_encode(
                array(
                    'saved' => false,
                    'message' => __('Resume field is mandatory.', 'jobster'),
                    'field' => 'pxp-upload-container-cv'
                )
            );
            exit();
        }

        if (!empty($job_id)) {
            $files_data_encoded = '';

            if (isset($_POST['files'])) {
                $files_list = array();
                $files_data_raw = urldecode($_POST['files']);
                $files_data = json_decode($files_data_raw);

                if (isset($files_data)) {
                    $new_data_files = new stdClass();
                    $new_files = array();
    
                    $files_list = $files_data->files;
    
                    foreach ($files_list as $files_item) {
                        $new_file = new stdClass();
    
                        $new_file->name = sanitize_text_field($files_item->name);
                        $new_file->id   = sanitize_text_field($files_item->id);
                        $new_file->url  = sanitize_text_field($files_item->url);
    
                        array_push($new_files, $new_file);
                    }
    
                    $new_data_files->files = $new_files;
    
                    $files_data_before = json_encode($new_data_files);
                    $files_data_encoded = urlencode($files_data_before);
                }
            }

            $candidate_id = jobster_register_candidate(
                array(
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'cv' => $cv,
                    'files' => $files_data_encoded
                )
            );

            if ($candidate_id) {
                $apps = get_post_meta(
                    $job_id,
                    'job_applications',
                    true
                );

                if (!is_array($apps)) {
                    $apps = array();
                }

                if (!array_key_exists($candidate_id, $apps)) {
                    $apps[$candidate_id] = array(
                        'date' => current_time('mysql'),
                        'status' => 'na'
                    );

                    update_post_meta($job_id, 'job_applications', $apps);

                    jobster_job_apply_notify_company(
                        $job_id,
                        $candidate_id,
                        $message
                    );

                    jobster_job_apply_save_notification(
                        $job_id,
                        $candidate_id
                    );
                }

                echo json_encode(
                    array(
                        'saved' => true
                    )
                );
                exit;
            }
        } else {
            echo json_encode(
                array(
                    'saved' => false,
                    'message' => __('Job offer is missing.', 'jobster')
                )
            );
            exit();
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_job_apply_anonymous',
    'jobster_job_apply_anonymous'
);
add_action(
    'wp_ajax_jobster_job_apply_anonymous',
    'jobster_job_apply_anonymous'
);

/**
 * Notify the company when a new candidate applies to a job
 */
if (!function_exists('jobster_job_apply_notify_company')): 
    function jobster_job_apply_notify_company($job_id, $candidate_id, $anonymous_message = '') {
        $company_id = get_post_meta($job_id, 'job_company', true);
        $candidate_name = get_the_title($candidate_id);
        $candidate_email = get_post_meta($candidate_id, 'candidate_email', true);
        $candidate_phone = get_post_meta($candidate_id, 'candidate_phone', true);
        $job_title = get_the_title($job_id);

        if (!empty($company_id)) {
            $company_email = get_post_meta(
                $company_id,
                'company_email',
                true
            );
            $company_notify = get_post_meta(
                $company_id,
                'company_app_notify',
                true
            );

            $attachments = array();
            $cv_val = get_post_meta($candidate_id, 'candidate_cv', true);
            $cv = wp_get_attachment_url($cv_val);
            if (!empty($cv)) {
                $cv_filename = basename($cv);
                $cv_dir = wp_get_upload_dir($cv);
                $attachments[] = $cv_dir['path'] . '/' . $cv_filename;
            }

            if (!empty($company_email) && !empty($company_notify)) {
                $headers = array(
                    'Content-Type: text/html; charset=UTF-8',
                    'From: ' . $candidate_name . '<' . $candidate_email . '>',
                    'Reply-To: ' . $candidate_name . '<' . $candidate_email . '>',
                );

                $email_settings = get_option('jobster_email_settings');
                $template = isset($email_settings['jobster_email_app_notify_field']) 
                            ? $email_settings['jobster_email_app_notify_field'] 
                            : '';

                if ($template != '') {
                    $template = str_replace("{JOB_TITLE}", $job_title, $template);
                    $template = str_replace("{CANDIDATE_NAME}", $candidate_name, $template);
                    $template = str_replace("{CANDIDATE_EMAIL}", $candidate_email, $template);
                    $template = str_replace("{CANDIDATE_PHONE}", $candidate_phone, $template);
                    $template = str_replace("{CANDIDATE_MESSAGE}", $anonymous_message, $template);

                    $send = wp_mail(
                        $company_email,
                        sprintf(__('[%s] New Job Candidate', 'jobster'), get_option('blogname')),
                        $template,
                        $headers,
                        $attachments
                    );
                } else {
                    $message = __('A new candidate has applied for your job', 'jobster')  . "\r\n\r\n";
                    $message .= sprintf(__('Job title: %s', 'jobster'), esc_html($job_title)) . "\r\n";
                    $message .= sprintf(__('Candidate name: %s', 'jobster'), esc_html($candidate_name)) . "\r\n";
                    $message .= sprintf(__('Candidate email: %s', 'jobster'), esc_html($candidate_email)) . "\r\n\r\n";
                    $message .= sprintf(__('Candidate phone: %s', 'jobster'), esc_html($candidate_phone)) . "\r\n\r\n";
                    $message .= esc_html($anonymous_message);

                    $send = wp_mail(
                        $company_email,
                        sprintf(__('[%s] New Job Candidate', 'jobster'), get_option('blogname')),
                        $message,
                        $headers,
                        $attachments
                    );
                }
            }
        }
    }
endif;

/**
 * Save the new job application in company's notifications system
 */
if (!function_exists('jobster_job_apply_save_notification')): 
    function jobster_job_apply_save_notification($job_id, $candidate_id) {
        $company_id = get_post_meta($job_id, 'job_company', true);

        if (!empty($company_id)) {
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
                    'type'         => 'application',
                    'candidate_id' => $candidate_id,
                    'job_id'       => $job_id,
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
    }
endif;

/**
 * Get total job applications number for a company
 */
if (!function_exists('jobster_get_apps_no_by_company_id')): 
    function jobster_get_apps_no_by_company_id($company_id) {
        $jobs_args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'job',
            'suppress_filters' => false,
            'post_status'      => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'job_company',
                    'value' => $company_id
                )
            )
        );

        $jobs     = new WP_Query($jobs_args);
        $jobs_arr = get_object_vars($jobs);

        $jobs_apps = 0;
        foreach ($jobs_arr['posts'] as $job) : 
            $job_apps = get_post_meta($job->ID, 'job_applications', true);

            if (is_array($job_apps)) {
                $jobs_apps += count($job_apps);
            }
        endforeach;

        wp_reset_query();

        return $jobs_apps;
    }
endif;

/**
 * Get total job applications number for a candidate
 */
if (!function_exists('jobster_get_apps_no_by_candidate_id')): 
    function jobster_get_apps_no_by_candidate_id($candidate_id) {
        $jobs_args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'job',
            'post_status'      => 'publish',
            'suppress_filters' => false,
            'meta_query'       => array(
                array(
                    'key'     => 'job_applications',
                    'value'   => 'i:' . $candidate_id,
                    'compare' => 'REGEXP',
                )
            )
        );

        $jobs     = new WP_Query($jobs_args);
        $jobs_arr = get_object_vars($jobs);

        wp_reset_query();

        if (is_array($jobs_arr['posts'])) {
            return count($jobs_arr['posts']);
        }

        return 0;
    }
endif;

/**
 * Get latest jobs application candidates for a company
 */
if (!function_exists('jobster_get_recent_apps_by_company_id')): 
    function jobster_get_recent_apps_by_company_id($company_id, $limit = 10) {
        $jobs_args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'job',
            'suppress_filters' => false,
            'post_status'      => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'job_company',
                    'value' => $company_id
                )
            )
        );

        $jobs     = new WP_Query($jobs_args);
        $jobs_arr = get_object_vars($jobs);

        $apps = array();
        $apps_count = 0;

        foreach ($jobs_arr['posts'] as $job) : 
            $job_apps = get_post_meta($job->ID, 'job_applications', true);

            if (is_array($job_apps)) {
                foreach ($job_apps as $job_app_k => $job_app_v) : 
                    $job_app_date = $job_app_v['date'];
                    if ($apps_count < $limit) {
                        $apps[$job_app_date] = array(
                            'candidate_id' => $job_app_k,
                            'job_id' => $job->ID
                        );
                    }
                    $apps_count++;
                endforeach;
            }
        endforeach;

        if ($apps_count > 0) {
            krsort($apps);
        }

        wp_reset_query();

        return $apps;
    }
endif;

/**
 * Get company jobs applications number per period of time
 */
if (!function_exists('jobster_get_company_jobs_apps')): 
    function jobster_get_company_jobs_apps() {
        check_ajax_referer('charts_ajax_nonce', 'security');

        $period         = isset($_POST['period'])
                        ? sanitize_text_field($_POST['period'])
                        : '-7 days';
        $company_id     = isset($_POST['company_id'])
                        ? sanitize_text_field($_POST['company_id'])
                        : '';

        switch ($period) {
            case '-7 days':
                $period_prev = '-14 days';
                break;
            case '-30 days':
                $period_prev = '-60 days';
                break;
            case '-60 days':
                $period_prev = '-120 days';
                break;
            case '-90 days':
                $period_prev = '-180 days';
                break;
            case '-12 months':
                $period_prev = '-24 months';
                break;
            default:
                $period_prev = '-14 days';
                break;
        }

        $jobs = jobster_get_jobs_by_company_id($company_id);

        $start_date      = date('Y-m-d', strtotime($period));
        $start_date_prev = date('Y-m-d', strtotime($period_prev));
        $today           = date('Y-m-d');

        $apps = array();
        $apps_prev = array();

        if ($jobs) {
            foreach ($jobs as $job) {
                $job_apps = get_post_meta($job->ID, 'job_applications', true);

                if (is_array($job_apps)) {
                    $apps_step = array();
                    $apps_step_prev = array();

                    // Get applications for period and prev period and group by day
                    foreach ($job_apps as $ja_key => $ja_value) {
                        $ja_date = $ja_value['date'];

                        if ($ja_date > $start_date && $ja_date < $today) {
                            $apps_step[$ja_key] =   date_format(
                                                        date_create($ja_date),
                                                        'M d'
                                                    );
                        }
                        if ($ja_date > $start_date_prev && $ja_date < $start_date) {
                            $apps_step_prev[$ja_key] =  date_format(
                                                            date_create($ja_date),
                                                            'M d'
                                                        );
                        }
                    }

                    $apps = jobster_merge_count_apps(
                                    $apps,
                                    array_count_values($apps_step)
                                );
                    $apps_prev = jobster_merge_count_visitors(
                                    $apps_prev,
                                    array_count_values($apps_step_prev)
                                );
                }
            }
        }

        $interval = new DatePeriod(
            new DateTime($period),
            new DateInterval('P1D'),
            new DateTime()
        );
        $dates = array();
        foreach ($interval as $date) {
            $dates[$date->format('M d')] = 0;
        }

        $apps_result = array_merge($dates, $apps);

        if (count($apps_result) > 0) {
            echo json_encode(
                array(
                    'getapps'         => true,
                    'apps'            => $apps_result,
                    'total_apps'      => array_sum($apps),
                    'total_apps_prev' => array_sum($apps_prev)
                )
            );
            exit();
        } else {
            echo json_encode(array('getapps' => false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_get_company_jobs_apps', 'jobster_get_company_jobs_apps');
add_action('wp_ajax_jobster_get_company_jobs_apps', 'jobster_get_company_jobs_apps');

/**
 * Get candidate jobs where he/she applied
 */
if (!function_exists('jobster_get_jobs_by_candidate_id')): 
    function jobster_get_jobs_by_candidate_id($candidate_id) {
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'job',
            'suppress_filters' => false,
            'post_status'      => 'publish',
            'meta_query'       => array(
                array(
                    'key'     => 'job_applications',
                    'value'   => 'i:' . $candidate_id,
                    'compare' => 'REGEXP',
                )
            )
        );

        $query = new WP_Query($args);
        $jobs = get_object_vars($query);

        wp_reset_postdata();

        if (is_array($jobs['posts']) && count($jobs['posts']) > 0) {
            return $jobs['posts'];
        }

        return false;
    }
endif;

/**
 * Get latest jobs applications for a candidate
 */
if (!function_exists('jobster_get_recent_apps_by_candidate_id')): 
    function jobster_get_recent_apps_by_candidate_id($candidate_id, $limit = 10) {
        $jobs_args = array(
            'posts_per_page'   => $limit,
            'post_type'        => 'job',
            'suppress_filters' => false,
            'post_status'      => 'publish',
            'meta_query'       => array(
                array(
                    'key'     => 'job_applications',
                    'value'   => 'i:' . $candidate_id,
                    'compare' => 'REGEXP',
                )
            )
        );

        $apps = array();

        $jobs     = new WP_Query($jobs_args);
        $jobs_arr = get_object_vars($jobs);

        foreach ($jobs_arr['posts'] as $job) : 
            $job_apps = get_post_meta($job->ID, 'job_applications', true);

            if (is_array($job_apps)) {
                foreach ($job_apps as $job_app_k => $job_app_v) : 
                    $job_app_date = $job_app_v['date'];

                    if ($job_app_k == $candidate_id) {
                        $apps[$job_app_date] = $job->ID;
                    }
                endforeach;
            }
        endforeach;

        if (count($apps) > 0) {
            krsort($apps);
        }

        wp_reset_query();

        return $apps;
    }
endif;

/**
 * Get candidate jobs applications number per period of time
 */
if (!function_exists('jobster_get_candidate_jobs_apps')): 
    function jobster_get_candidate_jobs_apps() {
        check_ajax_referer('charts_ajax_nonce', 'security');

        $period         = isset($_POST['period'])
                        ? sanitize_text_field($_POST['period'])
                        : '-7 days';
        $candidate_id   = isset($_POST['candidate_id'])
                        ? sanitize_text_field($_POST['candidate_id'])
                        : '';

        switch ($period) {
            case '-7 days':
                $period_prev = '-14 days';
                break;
            case '-30 days':
                $period_prev = '-60 days';
                break;
            case '-60 days':
                $period_prev = '-120 days';
                break;
            case '-90 days':
                $period_prev = '-180 days';
                break;
            case '-12 months':
                $period_prev = '-24 months';
                break;
            default:
                $period_prev = '-14 days';
                break;
        }

        $jobs = jobster_get_jobs_by_candidate_id($candidate_id);

        $start_date      = date('Y-m-d', strtotime($period));
        $start_date_prev = date('Y-m-d', strtotime($period_prev));
        $today           = date('Y-m-d');

        $apps = array();
        $apps_prev = array();

        if ($jobs) {
            foreach ($jobs as $job) {
                $job_apps = get_post_meta($job->ID, 'job_applications', true);

                if (is_array($job_apps)) {
                    $apps_step = array();
                    $apps_step_prev = array();

                    // Get applications for period and prev period and group by day
                    foreach ($job_apps as $ja_key => $ja_value) {
                        if ($ja_key == $candidate_id) {
                            $ja_date = $ja_value['date'];

                            if ($ja_date > $start_date && $ja_date < $today) {
                                $apps_step[$ja_key] = date_format(
                                    date_create($ja_date),
                                    'M d'
                                );
                            }
                            if ($ja_date > $start_date_prev && $ja_date < $start_date) {
                                $apps_step_prev[$ja_key] = date_format(
                                    date_create($ja_date),
                                    'M d'
                                );
                            }
                        }
                    }

                    $apps = jobster_merge_count_apps(
                        $apps,
                        array_count_values($apps_step)
                    );
                    $apps_prev = jobster_merge_count_visitors(
                        $apps_prev,
                        array_count_values($apps_step_prev)
                    );
                }
            }
        }

        $interval = new DatePeriod(
            new DateTime($period),
            new DateInterval('P1D'),
            new DateTime()
        );
        $dates = array();
        foreach ($interval as $date) {
            $dates[$date->format('M d')] = 0;
        }

        $apps_result = array_merge($dates, $apps);

        if (count($apps_result) > 0) {
            echo json_encode(
                array(
                    'getapps'         => true,
                    'apps'            => $apps_result,
                    'total_apps'      => array_sum($apps),
                    'total_apps_prev' => array_sum($apps_prev)
                )
            );
            exit();
        } else {
            echo json_encode(array('getapps' => false));
            exit();
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_get_candidate_jobs_apps',
    'jobster_get_candidate_jobs_apps'
);
add_action(
    'wp_ajax_jobster_get_candidate_jobs_apps',
    'jobster_get_candidate_jobs_apps'
);

if (!function_exists('jobster_merge_count_apps')): 
    function jobster_merge_count_apps($a1, $a2) {
        $merged = array();

        foreach ([$a1, $a2] as $a) {
            foreach ($a as $key => $value) {
                $val = isset($merged[$key]) ? $merged[$key] : 0;
                $merged[$key] = $value + $val;
            }
        }

        return $merged;
    }
endif;

/**
 * Set job applications status
 */
if (!function_exists('jobster_set_app_status')): 
    function jobster_set_app_status() {
        check_ajax_referer('company_candidates_ajax_nonce', 'security');

        $apps = array();
        if (isset($_POST['apps']) && is_array($_POST['apps'])) {
            array_walk_recursive($_POST['apps'], 'jobster_sanitize_multi_array');
            $apps = $_POST['apps'];
        }

        $status =   isset($_POST['status'])
                    ? sanitize_text_field($_POST['status'])
                    : '';

        foreach ($apps as $app) {
            $job = get_post($app['job_id']);

            if ($job) {
                $job_apps = get_post_meta($job->ID, 'job_applications', true);
    
                if (is_array($job_apps)) {
                    $modified = false;
                    foreach ($job_apps as $job_app_k =>  &$job_app_v) {
                        if ($job_app_k == $app['candidate_id']) {
                            $job_apps[$job_app_k]['status'] = $status;
                            $modified = true;
                        }
                    }

                    unset($job_app_v);

                    if ($modified) {
                        update_post_meta(
                            $job->ID,
                            'job_applications',
                            $job_apps
                        );
                    }
                }
            }
        }

        echo json_encode(array('set' => true));
        exit;

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_set_app_status', 'jobster_set_app_status');
add_action('wp_ajax_jobster_set_app_status', 'jobster_set_app_status');

/**
 * Delete job applications
 */
if (!function_exists('jobster_delete_app')): 
    function jobster_delete_app() {
        check_ajax_referer('company_candidates_ajax_nonce', 'security');

        $apps = array();
        if (isset($_POST['apps']) && is_array($_POST['apps'])) {
            array_walk_recursive($_POST['apps'], 'jobster_sanitize_multi_array');
            $apps = $_POST['apps'];
        }

        foreach ($apps as $app) {
            $job = get_post($app['job_id']);

            if ($job) {
                $job_apps = get_post_meta($job->ID, 'job_applications', true);

                if (is_array($job_apps)) {
                    $deleted = false;
                    foreach ($job_apps as $job_app_k =>  &$job_app_v) {
                        if ($job_app_k == $app['candidate_id']) {
                            unset($job_apps[$job_app_k]);
                            $deleted = true;
                        }
                    }

                    unset($job_app_v);

                    if ($deleted) {
                        update_post_meta(
                            $job->ID,
                            'job_applications',
                            $job_apps
                        );
                    }
                }
            }
        }

        echo json_encode(array('deleted' => true));
        exit;

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_delete_app', 'jobster_delete_app');
add_action('wp_ajax_jobster_delete_app', 'jobster_delete_app');
?>