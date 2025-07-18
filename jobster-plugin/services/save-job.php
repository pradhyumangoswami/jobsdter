<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Save new job offer
 */
if (!function_exists('jobster_save_new_job')): 
    function jobster_save_new_job() {
        check_ajax_referer('company_new_job_ajax_nonce', 'security');

        $jobs_settings = get_option('jobster_jobs_settings');

        $company_id =   isset($_POST['company_id'])
                        ? sanitize_text_field($_POST['company_id'])
                        : '';
        $title =    isset($_POST['title'])
                    ? sanitize_text_field($_POST['title'])
                    : '';
        $category = isset($_POST['category'])
                    ? sanitize_text_field($_POST['category'])
                    : '';
        $location = isset($_POST['location'])
                    ? sanitize_text_field($_POST['location'])
                    : '';
        $cover =    isset($_POST['cover'])
                    ? sanitize_text_field($_POST['cover'])
                    : '';
        $description =  isset($_POST['description'])
                        ? $_POST['description']
                        : '';
        $type = isset($_POST['type'])
                ? sanitize_text_field($_POST['type'])
                : '';
        $level =    isset($_POST['level'])
                    ? sanitize_text_field($_POST['level'])
                    : '';
        $experience =   isset($_POST['experience'])
                        ? sanitize_text_field($_POST['experience'])
                        : '';
        $salary =   isset($_POST['salary'])
                    ? sanitize_text_field($_POST['salary'])
                    : '';
        $valid =    isset($_POST['valid'])
                    ? sanitize_text_field($_POST['valid'])
                    : '';
        $validity_period =  isset($jobs_settings['jobster_job_validity_period_field'])
                            ? $jobs_settings['jobster_job_validity_period_field']
                            : '';
        if ($validity_period != '' 
            && is_numeric($validity_period) 
            && intval($validity_period) > 0) 
        {
            $valid = date('Y-m-d', strtotime("+$validity_period days"));
        }
        $action =   isset($_POST['btn_action'])
                    ? sanitize_text_field($_POST['btn_action'])
                    : '';
        $draft =    isset($_POST['draft'])
                    ? sanitize_text_field($_POST['draft'])
                    : '';

        $job_type_optional =    isset($jobs_settings['jobster_job_type_optional_field'])
                                && $jobs_settings['jobster_job_type_optional_field'] == '1';
        $job_experience_optional =  isset($jobs_settings['jobster_job_experience_optional_field'])
                                    && $jobs_settings['jobster_job_experience_optional_field'] == '1';

        if (isset($_POST['cfields']) && is_array($_POST['cfields'])) {
            array_walk_recursive($_POST['cfields'], 'jobster_sanitize_multi_array');
            $custom_fields = $_POST['cfields'];
        } else {
            $custom_fields = '';
        }

        if ($title == '') {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('Job title field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-new-job-title'
                )
            );
            exit();
        }
        if ($category == '0') {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('Category field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-new-job-category'
                )
            );
            exit();
        }
        if ($location == '0') {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('Location field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-new-job-location'
                )
            );
            exit();
        }
        if ($type == '0' && !$job_type_optional) {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('Type of employment field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-new-job-type'
                )
            );
            exit();
        }
        if ($level == '0' && !$job_experience_optional) {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('Experience level field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-new-job-level'
                )
            );
            exit();
        }
        if ($custom_fields != '') {
            foreach ($custom_fields as $cf_key => $cf_value) {
                if ($cf_value['field_mandatory'] == 'yes' && $cf_value['field_value'] == '') {
                    echo json_encode(array(
                        'save' => false, 
                        'message' => sprintf (__('%s field is mandatory.', 'jobster'), $cf_value['field_label']),
                        'field' => $cf_value['field_name']
                    ));
                    exit();
                }
            }
        }

        $job_status = 'publish';
        if ($draft == 'true') {
            $job_status = 'draft';
        }

        $is_admin_pending = false;
        if (isset($jobs_settings['jobster_job_approval_field']) 
            && $jobs_settings['jobster_job_approval_field'] == '1'
            && $job_status != 'draft') {
                $job_status = 'pending';
                $is_admin_pending = true;
        }

        $job = array(
            'post_title'   => $title,
            'post_content' => $description,
            'post_type'    => 'job',
            'post_status'  => $job_status,
        );

        $job_id = wp_insert_post($job);

        wp_set_object_terms($job_id, array(intval($category)), 'job_category');
        wp_set_object_terms($job_id, array(intval($location)), 'job_location');
        wp_set_object_terms($job_id, array(intval($type)), 'job_type');
        wp_set_object_terms($job_id, array(intval($level)), 'job_level');

        update_post_meta($job_id, 'job_cover', $cover);
        update_post_meta($job_id, 'job_experience', $experience);
        update_post_meta($job_id, 'job_salary', $salary);
        update_post_meta($job_id, 'job_valid', $valid);
        update_post_meta($job_id, 'job_company', $company_id);
        update_post_meta($job_id, 'job_featured', '');
        update_post_meta($job_id, 'job_action', $action);

        if (isset($_POST['benefits'])) {
            $benefits_list = array();
            $benefits_data_raw = urldecode($_POST['benefits']);
            $benefits_data = json_decode($benefits_data_raw);

            $benefits_data_encoded = '';

            if (isset($benefits_data)) {
                $new_data_benefits = new stdClass();
                $new_benefits = array();

                $benefits_list = $benefits_data->benefits;

                foreach ($benefits_list as $benefits_item) {
                    $new_benefit = new stdClass();

                    $new_benefit->title    = sanitize_text_field($benefits_item->title);
                    $new_benefit->icon     = sanitize_text_field($benefits_item->icon);
                    $new_benefit->icon_src = sanitize_text_field($benefits_item->icon_src);

                    array_push($new_benefits, $new_benefit);
                }

                $new_data_benefits->benefits = $new_benefits;

                $benefits_data_before = json_encode($new_data_benefits);
                $benefits_data_encoded = urlencode($benefits_data_before);
            }

            update_post_meta(
                $job_id,
                'job_benefits',
                $benefits_data_encoded
            );
        }

        if ($custom_fields != '') {
            foreach ($custom_fields as $ucf_key => $ucf_value) {
                update_post_meta(
                    $job_id,
                    $ucf_value['field_name'],
                    $ucf_value['field_value']
                );
            }
        }

        $membership_settings = get_option('jobster_membership_settings');
        $payment_type = isset($membership_settings['jobster_payment_type_field'])
                        ? $membership_settings['jobster_payment_type_field']
                        : '';
        $standard_unlim =   isset($membership_settings['jobster_free_submissions_unlim_field'])
                            ? $membership_settings['jobster_free_submissions_unlim_field']
                            : '';
        $company_payment = get_post_meta($company_id, 'company_payment', true);

        if ($is_admin_pending && $payment_type != 'listing' && $payment_type != 'plan') {
            update_post_meta($job_id, 'job_admin_pending', '1');

            $job_approval_data = array(
                'job_title' => $title,
                'job_url' => get_permalink($job_id),
                'company_name' => get_the_title($company_id),
                'company_url' => get_permalink($company_id)
            );
            jobster_notify_admin_job_approval($job_approval_data);
        }

        if ($company_payment == '1') {
            update_post_meta($job_id, 'job_payment_status', 'paid');
        } else {
            // update the free standard submissions number on company
            if ($payment_type == 'listing') {
                $company_free_listings = get_post_meta(
                    $company_id,
                    'company_free_listings',
                    true
                );
                $cfl_int = intval($company_free_listings);

                if ($cfl_int > 0 || $standard_unlim == '1') {
                    update_post_meta(
                        $company_id, 'company_free_listings', $cfl_int - 1
                    );
                    update_post_meta($job_id, 'job_payment_status', 'paid');
                } else {
                    if ($draft == 'true') {
                        $updated_job = array(
                            'ID' => $job_id,
                            'post_status' => 'draft'
                        );
                    } else {
                        $updated_job = array(
                            'ID' => $job_id,
                            'post_status' => 'pending'
                        );
                    }
                    wp_update_post($updated_job);
                }
            }

            // update the membership submissions number for company
            if ($payment_type == 'plan') {
                $company_plan_listings = get_post_meta(
                    $company_id,
                    'company_plan_listings',
                    true
                );
                $cpl_int = intval($company_plan_listings);

                update_post_meta(
                    $company_id, 'company_plan_listings', $cpl_int - 1
                );
                update_post_meta($job_id, 'job_payment_status', 'paid');
            }
        }

        /* Send job alerts if active */
        $saved_status = get_post_status($job_id);

        if ($saved_status === 'publish') {
            $jobs_settings = get_option('jobster_jobs_settings');
            if (isset($jobs_settings['jobster_job_alert_field']) 
                && $jobs_settings['jobster_job_alert_field'] == '1'
            ) {
                $location = wp_get_post_terms($job_id, 'job_location');
                $location_id = $location ? $location[0]->term_id : '';
                $category = wp_get_post_terms($job_id, 'job_category');
                $category_id = $category ? $category[0]->term_id : '';
                $type = wp_get_post_terms($job_id, 'job_type');
                $type_id = $type ? $type[0]->term_id : '';
                $level = wp_get_post_terms($job_id, 'job_level');
                $level_id = $level ? $level[0]->term_id : '';

                $job_data = array(
                    'id' => $job_id,
                    'location' => $location_id,
                    'category' => $category_id,
                    'type' => $type_id,
                    'level' => $level_id,
                );

                $candidates = jobster_get_job_alerts_candidates($job_data);

                foreach ($candidates->posts as $candidate) {
                    jobster_send_jobs_alerts_email($job_data, $candidate);
                }
            }
        }

        echo json_encode(
            array(
                'save' => true,
                'message' => __('Your profile data was successfully updated. Redirecting...', 'jobster')
            )
        );
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_save_new_job', 'jobster_save_new_job');
add_action('wp_ajax_jobster_save_new_job', 'jobster_save_new_job');

/**
 * Update existing job offer
 */
if (!function_exists('jobster_update_job')): 
    function jobster_update_job() {
        check_ajax_referer('company_edit_job_ajax_nonce', 'security');

        $jobs_settings = get_option('jobster_jobs_settings');

        $company_id =   isset($_POST['company_id'])
                        ? sanitize_text_field($_POST['company_id'])
                        : '';
        $job_id =   isset($_POST['job_id'])
                    ? sanitize_text_field($_POST['job_id'])
                    : '';
        $title =    isset($_POST['title'])
                    ? sanitize_text_field($_POST['title'])
                    : '';
        $category = isset($_POST['category'])
                    ? sanitize_text_field($_POST['category'])
                    : '';
        $location = isset($_POST['location'])
                    ? sanitize_text_field($_POST['location'])
                    : '';
        $cover =    isset($_POST['cover'])
                    ? sanitize_text_field($_POST['cover'])
                    : '';
        $description =  isset($_POST['description'])
                        ? $_POST['description']
                        : '';
        $type = isset($_POST['type'])
                ? sanitize_text_field($_POST['type'])
                : '';
        $level =    isset($_POST['level'])
                    ? sanitize_text_field($_POST['level'])
                    : '';
        $experience =   isset($_POST['experience'])
                        ? sanitize_text_field($_POST['experience'])
                        : '';
        $salary =   isset($_POST['salary'])
                    ? sanitize_text_field($_POST['salary'])
                    : '';
        $valid =    isset($_POST['valid'])
                    ? sanitize_text_field($_POST['valid'])
                    : '';
        $validity_period =  isset($jobs_settings['jobster_job_validity_period_field'])
                            ? $jobs_settings['jobster_job_validity_period_field']
                            : '';
        $save_valid = true;
        if ($validity_period != '' 
            && is_numeric($validity_period) 
            && intval($validity_period) > 0) 
        {
            $save_valid = false;
        }
        $action =   isset($_POST['btn_action'])
                    ? sanitize_text_field($_POST['btn_action'])
                    : '';
        $draft =    isset($_POST['draft'])
                    ? sanitize_text_field($_POST['draft'])
                    : '';

        $job_type_optional =    isset($jobs_settings['jobster_job_type_optional_field'])
                                && $jobs_settings['jobster_job_type_optional_field'] == '1';
        $job_experience_optional =  isset($jobs_settings['jobster_job_experience_optional_field'])
                                && $jobs_settings['jobster_job_experience_optional_field'] == '1';

        if (isset($_POST['cfields']) && is_array($_POST['cfields'])) {
            array_walk_recursive($_POST['cfields'], 'jobster_sanitize_multi_array');
            $custom_fields = $_POST['cfields'];
        } else {
            $custom_fields = '';
        }

        if ($title == '') {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Job title field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-new-job-title'
                )
            );
            exit();
        }
        if ($category == '0') {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Category field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-new-job-category'
                )
            );
            exit();
        }
        if ($location == '0') {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Location field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-new-job-location'
                )
            );
            exit();
        }
        if ($type == '0' && !$job_type_optional) {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Type of employment field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-new-job-type'
                )
            );
            exit();
        }
        if ($level == '0' && !$job_experience_optional) {
            echo json_encode(
                array(
                    'update' => false,
                    'message' => __('Experience level field is mandatory.', 'jobster'),
                    'field' => 'pxp-company-new-job-level'
                )
            );
            exit();
        }
        if ($custom_fields != '') {
            foreach ($custom_fields as $cf_key => $cf_value) {
                if ($cf_value['field_mandatory'] == 'yes' && $cf_value['field_value'] == '') {
                    echo json_encode(array(
                        'update' => false, 
                        'message' => sprintf (__('%s field is mandatory.', 'jobster'), $cf_value['field_label']),
                        'field' => $cf_value['field_id']
                    ));
                    exit();
                }
            }
        }

        $job_status = get_post_status($job_id);

        $membership_settings = get_option('jobster_membership_settings');
        $payment_type = isset($membership_settings['jobster_payment_type_field'])
                        ? $membership_settings['jobster_payment_type_field']
                        : '';
        $standard_unlim =   isset($membership_settings['jobster_free_submissions_unlim_field'])
                            ? $membership_settings['jobster_free_submissions_unlim_field']
                            : '';
        $company_payment = get_post_meta($company_id, 'company_payment', true);

        if ($payment_type == 'listing') {
            $payment_status = get_post_meta(
                $job_id,
                'job_payment_status',
                true
            );

            if ($payment_status == 'paid') {
                if ($draft == 'true') {
                    $new_job_status = 'draft';
                } else {
                    $new_job_status = 'publish';
                }
            } else {
                if ($job_status == 'publish') {
                    if ($draft == 'true') { 
                        $new_job_status = 'draft';
                    } else {
                        $new_job_status = 'publish';
                    }
                } else if ($job_status == 'pending') {
                    if ($draft == 'true') { 
                        $new_job_status = 'draft';
                    } else {
                        $new_job_status = 'pending';
                    }
                } else {
                    $new_job_status = 'draft';
                }
            }
        } else {
            if ($payment_type != 'plan') {
                if ($draft == 'true') {
                    $new_job_status = 'draft';
                } else {
                    if (isset($jobs_settings['jobster_job_approval_field']) 
                        && $jobs_settings['jobster_job_approval_field'] == '1'
                        && $job_status != 'publish') {
                            $new_job_status = 'pending';
                            update_post_meta($job_id, 'job_admin_pending', '1');

                            $job_approval_data = array(
                                'job_title' => $title,
                                'job_url' => get_permalink($job_id),
                                'company_name' => get_the_title($company_id),
                                'company_url' => get_permalink($company_id)
                            );
                            jobster_notify_admin_job_approval($job_approval_data);
                    } else {
                        $new_job_status = 'publish';
                    }
                }
            } else {
                if ($draft == 'true') {
                    $new_job_status = 'draft';
                } else {
                    $new_job_status = 'publish';
                }
            }
        }

        $job = array(
            'ID'           => $job_id,
            'post_title'   => $title,
            'post_content' => $description,
            'post_type'    => 'job',
            'post_status'  => $new_job_status
        );

        $job_id = wp_update_post($job);

        wp_set_object_terms($job_id, array(intval($category)), 'job_category');
        wp_set_object_terms($job_id, array(intval($location)), 'job_location');
        wp_set_object_terms($job_id, array(intval($type)), 'job_type');
        wp_set_object_terms($job_id, array(intval($level)), 'job_level');

        update_post_meta($job_id, 'job_cover', $cover);
        update_post_meta($job_id, 'job_experience', $experience);
        update_post_meta($job_id, 'job_salary', $salary);
        if ($save_valid) {
            update_post_meta($job_id, 'job_valid', $valid);
        }
        update_post_meta($job_id, 'job_action', $action);
        update_post_meta($job_id, 'job_company', $company_id);

        if (isset($_POST['benefits'])) {
            $benefits_list = array();
            $benefits_data_raw = urldecode($_POST['benefits']);
            $benefits_data = json_decode($benefits_data_raw);

            $benefits_data_encoded = '';

            if (isset($benefits_data)) {
                $new_data_benefits = new stdClass();
                $new_benefits = array();

                $benefits_list = $benefits_data->benefits;

                foreach ($benefits_list as $benefits_item) {
                    $new_benefit = new stdClass();

                    $new_benefit->title    = sanitize_text_field($benefits_item->title);
                    $new_benefit->icon     = sanitize_text_field($benefits_item->icon);
                    $new_benefit->icon_src = sanitize_text_field($benefits_item->icon_src);

                    array_push($new_benefits, $new_benefit);
                }

                $new_data_benefits->benefits = $new_benefits;

                $benefits_data_before = json_encode($new_data_benefits);
                $benefits_data_encoded = urlencode($benefits_data_before);
            }

            update_post_meta(
                $job_id,
                'job_benefits',
                $benefits_data_encoded
            );
        }

        if ($custom_fields != '') {
            foreach ($custom_fields as $ucf_key => $ucf_value) {
                update_post_meta(
                    $job_id,
                    $ucf_value['field_name'],
                    $ucf_value['field_value']
                );
            }
        }

        echo json_encode(
            array(
                'update' => true,
                'message' => __('Your profile data was successfully updated. Redirecting...', 'jobster')
            )
        );
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_update_job', 'jobster_update_job');
add_action('wp_ajax_jobster_update_job', 'jobster_update_job');

/**
 * Bulk publish jobs
 */
if (!function_exists('jobster_publish_jobs')): 
    function jobster_publish_jobs() {
        check_ajax_referer('company_bulk_jobs_ajax_nonce', 'security');

        $jobs = isset($_POST['jobs'])
                ? sanitize_text_field($_POST['jobs'])
                : '';

        if ($jobs != '') {
            $jobs_arr = explode(',', $jobs);

            $membership_settings = get_option('jobster_membership_settings');
            $payment_type = isset($membership_settings['jobster_payment_type_field'])
                            ? $membership_settings['jobster_payment_type_field']
                            : '';

            $pending_jobs = array();
            foreach ($jobs_arr as $job) {
                if ($payment_type == 'listing') {
                    $payment_status = get_post_meta(
                        $job,
                        'job_payment_status',
                        true
                    );

                    if ($payment_status == 'paid') {
                        wp_publish_post($job);
                    }
                } else {
                    $jobs_settings = get_option('jobster_jobs_settings');
                    $job_status = get_post_status($job);
                    if (isset($jobs_settings['jobster_job_approval_field']) 
                        && $jobs_settings['jobster_job_approval_field'] == '1'
                        && $job_status != 'publish') {
                            update_post_meta($job, 'job_admin_pending', '1');

                            $current_job = get_post($job, 'ARRAY_A');
                            $current_job['post_status'] = 'pending';
                            wp_update_post($current_job);

                            $company_id = get_post_meta($job, 'job_company', true);

                            array_push(
                                $pending_jobs,
                                array(
                                   'job_title' => get_the_title($job),
                                   'job_url' => get_permalink($job),
                                   'company_name' => get_the_title($company_id),
                                   'company_url' => get_permalink($company_id)
                               )
                            );

                            jobster_notify_admin_jobs_bulk_approval($pending_jobs);
                    } else {
                        wp_publish_post($job);
                    }
                }
            }

            echo json_encode(
                array(
                    'published' => true,
                    'message' => __('Jobs were successfully published. Redirecting...', 'jobster')
                )
            );
            exit();
        } else {
            echo json_encode(
                array(
                    'published' => false,
                    'message' => __('Jobs were not published.', 'jobster')
                )
            );
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_publish_jobs', 'jobster_publish_jobs');
add_action('wp_ajax_jobster_publish_jobs', 'jobster_publish_jobs');

/**
 * Delete job offer(s)
 */
if (!function_exists('jobster_delete_jobs')): 
    function jobster_delete_jobs() {
        check_ajax_referer('company_bulk_jobs_ajax_nonce', 'security');

        $jobs = isset($_POST['jobs'])
                ? sanitize_text_field($_POST['jobs'])
                : '';

        if ($jobs != '') {
            $jobs_arr = explode(',', $jobs);

            if (!is_user_logged_in()) {
                echo json_encode(
                    array(
                        'deleted' => false,
                        'message' => __('Permission denied.', 'jobster')
                    )
                );
                exit();
            }

            $current_user = wp_get_current_user();
            $company_id = jobster_get_company_by_userid($current_user->ID);

            $args = array(
                'posts_per_page' => -1,
                'post_type'      => 'job',
                'meta_key'       => 'job_company',
                'meta_value'     => $company_id,
            );

            $company_jobs = get_posts($args);
            $company_jobs_ids = array();
            if (is_array($company_jobs) && count($company_jobs) > 0) {
                foreach ($company_jobs as $company_job) {
                    $company_jobs_ids[] = $company_job->ID;
                }
            }

            foreach ($jobs_arr as $job) {
                if (in_array($job, $company_jobs_ids)) {
                    wp_delete_post($job);
                } else {
                    echo json_encode(
                        array(
                            'deleted' => false,
                            'message' => __('Permission denied.', 'jobster')
                        )
                    );
                    exit();
                }
            }

            echo json_encode(
                array(
                    'deleted' => true,
                    'message' => __('Jobs were successfully deleted. Redirecting...', 'jobster')
                )
            );
            exit();

        } else {
            echo json_encode(
                array(
                    'deleted' => false,
                    'message' => __('Jobs were not deleted.', 'jobster')
                )
            );
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_delete_jobs', 'jobster_delete_jobs');
add_action('wp_ajax_jobster_delete_jobs', 'jobster_delete_jobs');

/**
 * Upgrade job offer to featured
 */
if (!function_exists('jobster_upgrade_job_featured')): 
    function jobster_upgrade_job_featured() {
        check_ajax_referer('upgradejob_ajax_nonce', 'security');

        $job_id =   isset($_POST['job_id'])
                    ? sanitize_text_field($_POST['job_id'])
                    : '';
        $company_id =   isset($_POST['company_id'])
                        ? sanitize_text_field($_POST['company_id'])
                        : '';
        $company_payment = get_post_meta($company_id, 'company_payment', true);

        $feat_job = update_post_meta($job_id, 'job_featured', 1);

        if ($feat_job) {
            $company_free_featured_listings = get_post_meta(
                $company_id,
                'company_free_featured_listings',
                true
            );
            $cffl_int = intval($company_free_featured_listings);
    
            if ($company_payment != '1') {
                update_post_meta(
                    $company_id,
                    'company_free_featured_listings',
                    $cffl_int - 1
                );
            }

            echo json_encode(array('upgrade' => true));
            exit();
        } else {
            echo json_encode(array('upgrade' => false));
            exit();
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_upgrade_job_featured',
    'jobster_upgrade_job_featured');
add_action(
    'wp_ajax_jobster_upgrade_job_featured',
    'jobster_upgrade_job_featured'
);

/**
 * Set job as featured from company plan
 */
if (!function_exists('jobster_set_job_featured')): 
    function jobster_set_job_featured() {
        check_ajax_referer('featuredjob_ajax_nonce', 'security');

        $job_id =   isset($_POST['job_id']) 
                    ? sanitize_text_field($_POST['job_id'])
                    : '';
        $company_id =   isset($_POST['company_id'])
                        ? sanitize_text_field($_POST['company_id'])
                        : '';
        $company_payment = get_post_meta($company_id, 'company_payment', true);

        $feat_prop = update_post_meta($job_id, 'job_featured', 1);

        $company_plan_featured_listings = get_post_meta($company_id, 'company_plan_featured', true);
        $cpfl_int = intval($company_plan_featured_listings);

        if ($company_payment != '1') {
            update_post_meta($company_id, 'company_plan_featured', $cpfl_int - 1);
        }

        if ($feat_prop) {
            echo json_encode(array(
                'upgrade' => true,
                'message' => __('The job was successfully set as featured. Redirecting...', 'jobster')
            ));
            exit();
        } else {
            echo json_encode(array(
                'upgrade' => false,
                'message' => __('Something went wrong. The job was not set as featured.', 'jobster'))
            );
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_set_job_featured', 'jobster_set_job_featured');
add_action('wp_ajax_jobster_set_job_featured', 'jobster_set_job_featured');

/**
 * Add job new location - New Job
 */
if (!function_exists('jobster_add_job_location_new')): 
    function jobster_add_job_location_new() {
        check_ajax_referer('company_new_job_location_ajax_nonce', 'security');

        $name = isset($_POST['name'])
                ? sanitize_text_field($_POST['name'])
                : '';
        $parent =   isset($_POST['parent'])
                    ? intval(sanitize_text_field($_POST['parent']))
                    : 0;

        if ($name == '') {
            echo json_encode(
                array(
                    'add' => false,
                    'field' => 'pxp-company-new-job-location-new'
                )
            );
            exit();
        }

        $location_new = wp_insert_term(
            $name,
            'job_location',
            array(
                'parent' => $parent
            )
        );

        if (!is_wp_error($location_new)) {
            $loc_terms = get_terms(
                array(
                    'taxonomy' => 'job_location',
                    'orderby' => 'name',
                    'hierarchical' => true,
                    'hide_empty' => false,
                )
            );
    
            $top_level_locations = array();
            $children_locations  = array();
            foreach ($loc_terms as $location) {
                if (empty($location->parent)) {
                    $top_level_locations[] = $location;
                } else {
                    $children_locations[$location->parent][] = $location;
                }
            }
            $locations = array(
                array(
                    'id' => 0,
                    'label' => __('Select location', 'jobster')
                )
            );
            foreach ($top_level_locations as $top_location) {
                $locations[] = array(
                    'id' => $top_location->term_id,
                    'label' => $top_location->name
                );
                if (array_key_exists($top_location->term_id, $children_locations)) {
                    foreach ($children_locations[$top_location->term_id] as $child_location) {
                        $locations[] = array(
                            'id' => $child_location->term_id,
                            'label' => '&nbsp;&nbsp;&nbsp;' . $child_location->name
                        );
                    }
                }
            }

            echo json_encode(array(
                'add' => true,
                'locations' => $locations,
                'location_id' => $location_new['term_id']
            ));
            exit();
        } else {
            echo json_encode(
                array(
                    'add' => false,
                    'field' => 'pxp-company-new-job-location-new'
                )
            );
            exit();
        }
        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_add_job_location_new',
    'jobster_add_job_location_new'
);
add_action(
    'wp_ajax_jobster_add_job_location_new',
    'jobster_add_job_location_new'
);

/**
 * Add job new location - Edit Job
 */
if (!function_exists('jobster_add_job_location_edit')): 
    function jobster_add_job_location_edit() {
        check_ajax_referer('company_edit_job_location_ajax_nonce', 'security');

        $name = isset($_POST['name'])
                ? sanitize_text_field($_POST['name'])
                : '';
        $parent =   isset($_POST['parent'])
                    ? intval(sanitize_text_field($_POST['parent']))
                    : 0;

        if ($name == '') {
            echo json_encode(
                array(
                    'add' => false,
                    'field' => 'pxp-company-edit-job-location-new'
                )
            );
            exit();
        }

        $location_new = wp_insert_term(
            $name,
            'job_location',
            array(
                'parent' => $parent
            )
        );

        if (!is_wp_error($location_new)) {
            $loc_terms = get_terms(
                array(
                    'taxonomy' => 'job_location',
                    'orderby' => 'name',
                    'hierarchical' => true,
                    'hide_empty' => false,
                )
            );
    
            $top_level_locations = array();
            $children_locations  = array();
            foreach ($loc_terms as $location) {
                if (empty($location->parent)) {
                    $top_level_locations[] = $location;
                } else {
                    $children_locations[$location->parent][] = $location;
                }
            }
            $locations = array(
                array(
                    'id' => 0,
                    'label' => __('Select location', 'jobster')
                )
            );
            foreach ($top_level_locations as $top_location) {
                $locations[] = array(
                    'id' => $top_location->term_id,
                    'label' => $top_location->name
                );
                if (array_key_exists($top_location->term_id, $children_locations)) {
                    foreach ($children_locations[$top_location->term_id] as $child_location) {
                        $locations[] = array(
                            'id' => $child_location->term_id,
                            'label' => '&nbsp;&nbsp;&nbsp;' . $child_location->name
                        );
                    }
                }
            }

            echo json_encode(array(
                'add' => true,
                'locations' => $locations,
                'location_id' => $location_new['term_id']
            ));
            exit();
        } else {
            echo json_encode(
                array(
                    'add' => false,
                    'field' => 'pxp-company-edit-job-location-new'
                )
            );
            exit();
        }
        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_add_job_location_edit',
    'jobster_add_job_location_edit'
);
add_action(
    'wp_ajax_jobster_add_job_location_edit',
    'jobster_add_job_location_edit'
);

/**
 * New job - Delete cover photo
 */
if (!function_exists('jobster_new_job_delete_cover')): 
    function jobster_new_job_delete_cover() {
        check_ajax_referer('company_new_job_ajax_nonce', 'security');

        $cover_id = isset($_POST['cover_id'])
                    ? sanitize_text_field($_POST['cover_id'])
                    : '';

        $delete_cover = wp_delete_attachment($cover_id, true);

        if (!is_wp_error($delete_cover)) {
            echo json_encode(array('delete' => true));
            exit();
        } else {
            echo json_encode(array('delete' => false, 'info' => $delete_cover));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_new_job_delete_cover', 'jobster_new_job_delete_cover');
add_action('wp_ajax_jobster_new_job_delete_cover', 'jobster_new_job_delete_cover');

/**
 * Edit job - Delete cover photo
 */
if (!function_exists('jobster_edit_job_delete_cover')): 
    function jobster_edit_job_delete_cover() {
        check_ajax_referer('company_edit_job_ajax_nonce', 'security');

        $job_id =   isset($_POST['job_id'])
                    ? sanitize_text_field($_POST['job_id'])
                    : '';
        $cover_id = isset($_POST['cover_id'])
                    ? sanitize_text_field($_POST['cover_id'])
                    : '';

        $delete_cover = wp_delete_attachment($cover_id, true);

        if (!is_wp_error($delete_cover)) {
            update_post_meta($job_id, 'job_cover', $cover_id);
            echo json_encode(array('delete' => true));
            exit();
        } else {
            echo json_encode(array('delete' => false, 'info' => $delete_cover));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_edit_job_delete_cover', 'jobster_edit_job_delete_cover');
add_action('wp_ajax_jobster_edit_job_delete_cover', 'jobster_edit_job_delete_cover');

/**
 * Admin job pending notification
 */
if (!function_exists('jobster_notify_admin_job_approval')): 
    function jobster_notify_admin_job_approval($job) {
        $message = sprintf(__('New job pending approval on %s:', 'jobster'), get_option('blogname')) . "\r\n\r\n";
        $message .= '<a href="' . esc_url($job['job_url']) . '">
                        ' . $job['job_title'] . '
                    </a> by <a href="' . esc_url($job['company_url']) . '">
                        ' . $job['company_name'] . '
                    </a>';

        $admin_email = get_option('admin_email');

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option('blogname') . '<' . get_option('admin_email') . '>',
            'Reply-To: ' . get_option('blogname') . '<' . get_option('admin_email') . '>'
        );

        $send_admin = wp_mail(
            $admin_email,
            sprintf(__('[%s] New Job Pending Approval', 'jobster'), get_option('blogname')),
            $message,
            $headers
        );

        if (!$send_admin) {
            wp_mail(
                $admin_email,
                sprintf(__('[%s] New Job Pending Approval', 'jobster'), get_option('blogname')),
                $message
            );
        }
    }
endif;

/**
 * Admin bulk jobs pending notification
 */
if (!function_exists('jobster_notify_admin_jobs_bulk_approval')): 
    function jobster_notify_admin_jobs_bulk_approval($jobs) {
        $message = sprintf(__('New jobs pending approval on %s:', 'jobster'), get_option('blogname')) . "\r\n\r\n";


        foreach ($jobs as $job) {
            $message .= '<a href="' . esc_url($job['job_url']) . '">
                            ' . $job['job_title'] . '
                        </a> by <a href="' . esc_url($job['company_url']) . '">
                            ' . $job['company_name'] . '
                        </a>' . "\r\n";
        }

        $admin_email = get_option('admin_email');

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option('blogname') . '<' . get_option('admin_email') . '>',
            'Reply-To: ' . get_option('blogname') . '<' . get_option('admin_email') . '>'
        );

        $send_admin = wp_mail(
            $admin_email,
            sprintf(__('[%s] New Job Pending Approval', 'jobster'), get_option('blogname')),
            $message,
            $headers
        );

        if (!$send_admin) {
            wp_mail(
                $admin_email,
                sprintf(__('[%s] New Job Pending Approval', 'jobster'), get_option('blogname')),
                $message
            );
        }
    }
endif;

if (!function_exists('jobster_delete_new_job_benefit_icon')): 
    function jobster_delete_new_job_benefit_icon() {
        check_ajax_referer('company_new_job_ajax_nonce', 'security');

        $icon_id =  isset($_POST['icon_id'])
                    ? sanitize_text_field($_POST['icon_id'])
                    : '';

        $delete_icon = wp_delete_attachment($icon_id, true);

        if (!is_wp_error($delete_icon)) {
            echo json_encode(array('delete' => true));
            exit();
        } else {
            echo json_encode(array('delete' => false, 'info' => $delete_icon));
            exit();
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_delete_new_job_benefit_icon',
    'jobster_delete_new_job_benefit_icon'
);
add_action(
    'wp_ajax_jobster_delete_new_job_benefit_icon',
    'jobster_delete_new_job_benefit_icon'
);

if (!function_exists('jobster_delete_edit_job_benefit_icon')): 
    function jobster_delete_edit_job_benefit_icon() {
        check_ajax_referer('company_edit_job_ajax_nonce', 'security');

        $icon_id =  isset($_POST['icon_id'])
                    ? sanitize_text_field($_POST['icon_id'])
                    : '';

        $delete_icon = wp_delete_attachment($icon_id, true);

        if (!is_wp_error($delete_icon)) {
            echo json_encode(array('delete' => true));
            exit();
        } else {
            echo json_encode(array('delete' => false, 'info' => $delete_icon));
            exit();
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_delete_edit_job_benefit_icon',
    'jobster_delete_edit_job_benefit_icon'
);
add_action(
    'wp_ajax_jobster_delete_edit_job_benefit_icon',
    'jobster_delete_edit_job_benefit_icon'
);
?>