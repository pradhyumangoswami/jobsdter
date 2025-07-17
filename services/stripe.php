<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_stripe_pay_listing')):
    function jobster_stripe_pay_listing() {
        global $current_user;
        $current_user = wp_get_current_user();

        $job_id     = isset($_POST['job_id']) ? intval($_POST['job_id']) : '';
        $is_featured = isset($_POST['is_featured']) ? intval($_POST['is_featured']) : '';
        $is_upgrade  = isset($_POST['is_upgrade']) ? intval($_POST['is_upgrade']) : '';

        $userID = $current_user->ID;
        $post   = get_post($job_id);

        if ($post->post_author != $userID) {
            exit();
        }

        $company_id = jobster_get_company_by_userid($current_user->ID);

        $membership_settings = get_option('jobster_membership_settings');
        $submission_price = isset($membership_settings['jobster_submission_price_field'])
                            ? floatval($membership_settings['jobster_submission_price_field'])
                            : 0;
        $featured_submission_price =    isset($membership_settings['jobster_featured_price_field'])
                                        ? floatval($membership_settings['jobster_featured_price_field'])
                                        : 0;
        $payment_currency  =    isset($membership_settings['jobster_stripe_payment_currency_field'])
                                ? $membership_settings['jobster_stripe_payment_currency_field']
                                : '';
        $payment_description = __('Job posting payment on ', 'jobster') . home_url();

        if ($is_featured == 0) {
            $total_price = $submission_price * 100;
        } else {
            $total_price = $submission_price + $featured_submission_price;
            $total_price = $total_price * 100;
            $payment_description = __('Featured job posting payment on ', 'jobster') . home_url();
        }

        if ($is_upgrade == 1) {
            $total_price         = $featured_submission_price * 100;
            $payment_description = __('Upgrade to featured job on ', 'jobster') . home_url();
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => $payment_currency,
                    'product_data' => [
                        'name' => $payment_description,
                    ],
                    'unit_amount' => $total_price,
                ],
                'quantity' => 1,
            ]],
            'payment_intent_data' => [
                'metadata' => [
                    'job_id'      => $job_id,
                    'is_featured' => $is_featured,
                    'is_upgrade'  => $is_upgrade,
                    'company_id'  => $company_id
                ]
            ],
            'mode' => 'payment',
            'success_url' => jobster_get_page_link('company-dashboard-jobs.php'),
            'cancel_url' => jobster_get_page_link('company-dashboard-jobs.php'),
        ]);


        echo json_encode(array('success' => true, 'sessionId' => $session->id));
        exit;

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_stripe_pay_listing', 'jobster_stripe_pay_listing');
add_action('wp_ajax_jobster_stripe_pay_listing', 'jobster_stripe_pay_listing');

if (!function_exists('jobster_stripe_pay_membership_plan')):
    function jobster_stripe_pay_membership_plan() {
        $plan_id = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : '';
        $plan    = get_post($plan_id);

        if (!empty($plan)) {
            global $current_user;
            $current_user = wp_get_current_user();

            $is_candidate = jobster_user_is_candidate($current_user->ID);
            $company_id = 0;
            $candidate_id = 0;
            $success_url = '';
            $cancel_url = '';
            
            if ($is_candidate) {
                $candidate_id = jobster_get_candidate_by_userid($current_user->ID);
                $success_url = jobster_get_page_link('candidate-dashboard-subscriptions.php');
                $cancel_url = jobster_get_page_link('candidate-dashboard-subscriptions.php');
            } else {
                $company_id = jobster_get_company_by_userid($current_user->ID);
                $success_url = jobster_get_page_link('company-dashboard-subscriptions.php');
                $cancel_url = jobster_get_page_link('company-dashboard-subscriptions.php');
            }

            $plan_price = get_post_meta($plan_id, 'membership_plan_price', true);
            $plan_price = floatval($plan_price) * 100;

            $membership_settings = get_option('jobster_membership_settings');
            $payment_currency = isset($membership_settings['jobster_stripe_payment_currency_field'])
                                ? $membership_settings['jobster_stripe_payment_currency_field']
                                : '';

            $payment_description = $plan->post_title . ' ' . __('membership plan payment on', 'jobster') . ' ' . home_url();

            $metadata = array('plan_id' => $plan_id);
            if ($is_candidate) {
                $metadata['candidate_id'] = $candidate_id;
            } else {
                $metadata['company_id'] = $company_id;
            }

            $session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => $payment_currency,
                        'product_data' => [
                            'name' => $payment_description,
                        ],
                        'unit_amount' => $plan_price,
                    ],
                    'quantity' => 1,
                ]],
                'payment_intent_data' => [
                    'metadata' => $metadata
                ],
                'mode' => 'payment',
                'success_url' => $success_url,
                'cancel_url' => $cancel_url,
            ]);
    
    
            echo json_encode(array('success' => true, 'sessionId' => $session->id));
            exit;
    
            die();
        }
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_stripe_pay_membership_plan',
    'jobster_stripe_pay_membership_plan'
);
add_action(
    'wp_ajax_jobster_stripe_pay_membership_plan',
    'jobster_stripe_pay_membership_plan'
);