<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Get jobs visitors number per period of time
 */
if (!function_exists('jobster_get_jobs_visitors')): 
    function jobster_get_jobs_visitors() {
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

        $visitors = array();
        $visitors_prev = array();

        if ($jobs) {
            foreach ($jobs as $job) {
                $job_visitors = get_post_meta($job->ID, 'job_visitors', true);

                if (is_array($job_visitors)) {
                    $visitors_step = array();
                    $visitors_step_prev = array();

                    // Get visitors for period and prev period and group by day
                    foreach ($job_visitors as $jv_key => $jv_value) {
                        if ($jv_value > $start_date && $jv_value < $today) {
                            $visitors_step[$jv_key] = date_format(
                                                        date_create($jv_value),
                                                        'M d'
                                                    );
                        }
                        if ($jv_value > $start_date_prev && $jv_value < $start_date) {
                            $visitors_step_prev[$jv_key] = date_format(
                                                            date_create($jv_value),
                                                            'M d'
                                                        );
                        }
                    }

                    $visitors = jobster_merge_count_visitors(
                                    $visitors,
                                    array_count_values($visitors_step)
                                );
                    $visitors_prev = jobster_merge_count_visitors(
                                        $visitors_prev,
                                        array_count_values($visitors_step_prev)
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

        $visitors_result = array_merge($dates, $visitors);

        if (count($visitors_result) > 0) {
            echo json_encode(
                array(
                    'getvisitors'         => true,
                    'visitors'            => $visitors_result,
                    'total_visitors'      => array_sum($visitors),
                    'total_visitors_prev' => array_sum($visitors_prev)
                )
            );
            exit();
        } else {
            echo json_encode(array('getvisitors' => false));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_get_jobs_visitors', 'jobster_get_jobs_visitors');
add_action('wp_ajax_jobster_get_jobs_visitors', 'jobster_get_jobs_visitors');

/**
 * Get candidate profile visitors number
 */
if (!function_exists('jobster_get_profile_visitors_no')): 
    function jobster_get_profile_visitors_no($candidate_id) {
        $visitors = get_post_meta($candidate_id, 'candidate_visitors', true);

        if (is_array($visitors)) {
            return count($visitors);
        }

        return 0;
    }
endif;

/**
 * Get candidate profile visitors number per period of time
 */
if (!function_exists('jobster_get_candidate_visitors')): 
    function jobster_get_candidate_visitors() {
        check_ajax_referer('charts_ajax_nonce', 'security');

        $period         = isset($_POST['period'])
                        ? sanitize_text_field($_POST['period'])
                        : '-7 days';
        $candidate_id = isset($_POST['candidate_id'])
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

        $start_date      = date('Y-m-d', strtotime($period));
        $start_date_prev = date('Y-m-d', strtotime($period_prev));
        $today           = date('Y-m-d');

        $visitors = array();
        $visitors_prev = array();

        $candidate_visitors = get_post_meta(
            $candidate_id,
            'candidate_visitors',
            true
        );

        if (is_array($candidate_visitors)) {
            $visitors_step = array();
            $visitors_step_prev = array();

            // Get visitors for period and prev period and group by day
            foreach ($candidate_visitors as $cv_key => $cv_value) {
                if ($cv_value > $start_date && $cv_value < $today) {
                    $visitors_step[$cv_key] = date_format(
                                                date_create($cv_value),
                                                'M d'
                                            );
                }
                if ($cv_value > $start_date_prev && $cv_value < $start_date) {
                    $visitors_step_prev[$cv_key] = date_format(
                                                    date_create($cv_value),
                                                    'M d'
                                                );
                }
            }

            $visitors = jobster_merge_count_visitors(
                            $visitors,
                            array_count_values($visitors_step)
                        );
            $visitors_prev = jobster_merge_count_visitors(
                                $visitors_prev,
                                array_count_values($visitors_step_prev)
                            );
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

        $visitors_result = array_merge($dates, $visitors);

        if (count($visitors_result) > 0) {
            echo json_encode(
                array(
                    'getvisitors'         => true,
                    'visitors'            => $visitors_result,
                    'total_visitors'      => array_sum($visitors),
                    'total_visitors_prev' => array_sum($visitors_prev)
                )
            );
            exit();
        } else {
            echo json_encode(array('getvisitors' => false));
            exit();
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_get_candidate_visitors',
    'jobster_get_candidate_visitors'
);
add_action(
    'wp_ajax_jobster_get_candidate_visitors',
    'jobster_get_candidate_visitors'
);

if (!function_exists('jobster_merge_count_visitors')): 
    function jobster_merge_count_visitors($a1, $a2) {
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
?>