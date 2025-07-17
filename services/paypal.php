<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_paypal_access_token')):
    function jobster_get_paypal_access_token($url, $postdata) {
        $membership_settings = get_option('jobster_membership_settings');
        $client_id =    isset($membership_settings['jobster_paypal_client_id_field'])
                        ? $membership_settings['jobster_paypal_client_id_field']
                        : '';
        $client_secret =    isset($membership_settings['jobster_paypal_client_key_field'])
                            ? $membership_settings['jobster_paypal_client_key_field']
                            : '';

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERPWD, $client_id . ":" . $client_secret);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

        $response = curl_exec($curl);

        if (empty($response)) {
            die(curl_error($curl));
            curl_close($curl);
        } else {
            $info = curl_getinfo($curl);

            curl_close($curl);

            if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                echo "Received error: " . $info['http_code'] . "\n";
                echo "Raw response: " . $response . "\n";

                die();
            }
        }

        $jsonResponse = json_decode($response);

        return $jsonResponse->access_token;
    }
endif;

if (!function_exists('jobster_make_paypal_post_call')):
    function jobster_make_paypal_post_call($url, $postdata, $token) {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
            'Content-Type: application/json'
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

        $response = curl_exec($curl);

        if (empty($response)) {
            die(curl_error($curl));
            curl_close($curl);
        } else {
            $info = curl_getinfo($curl);

            curl_close($curl);

            if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                echo "Received error: " . $info['http_code'] . "\n";
                echo "Raw response: " . $response . "\n";

                die();
            }
        }

        $jsonResponse = json_decode($response, TRUE);

        return $jsonResponse;
    }
endif;

if (!function_exists('jobster_paypal_pay_listing')):
    function jobster_paypal_pay_listing() {
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

        $membership_settings = get_option('jobster_membership_settings');
        $paypal_version =   isset($membership_settings['jobster_paypal_api_version_field'])
                            ? $membership_settings['jobster_paypal_api_version_field']
                            : '';
        $host = 'https://api.sandbox.paypal.com';
        $submission_price = isset($membership_settings['jobster_submission_price_field'])
                            ? floatval($membership_settings['jobster_submission_price_field'])
                            : 0;
        $featured_submission_price =    isset($membership_settings['jobster_featured_price_field'])
                                        ? floatval($membership_settings['jobster_featured_price_field'])
                                        : 0;
        $payment_currency  = isset($membership_settings['jobster_paypal_payment_currency_field'])
                            ? $membership_settings['jobster_paypal_payment_currency_field']
                            : '';
        $payment_description = __('Listing payment on ', 'jobster') . home_url();

        if ($is_featured == 0) {
            $total_price = number_format($submission_price, 2, '.', '');
        } else {
            $total_price = $submission_price + $featured_submission_price;
            $total_price = number_format($total_price, 2, '.', '');
        }

        if ($is_upgrade == 1) {
            $total_price         = number_format($featured_submission_price, 2, '.', '');
            $payment_description = __('Upgrade to Featured Job on ', 'jobster') . home_url();
        }

        if ($paypal_version == 'live') {
            $host = 'https://api.paypal.com';
        }

        $url            = $host . '/v1/oauth2/token';
        $postArgs       = 'grant_type=client_credentials';
        $token          = jobster_get_paypal_access_token($url, $postArgs);
        $url            = $host . '/v1/payments/payment';
        $my_jobs_link   = jobster_get_page_link('company-dashboard-jobs.php');
        $processor_link = jobster_get_page_link('paypal-processor.php');

        $payment = array(
            'intent' => 'sale',
            'redirect_urls' => array(
                'return_url' => $processor_link,
                'cancel_url' => $my_jobs_link
            ),
            'payer' => array('payment_method' => 'paypal'),
        );

        $payment['transactions'][0] = array(
            'amount' => array(
                'total' => $total_price,
                'currency' => $payment_currency,
                'details' => array(
                    'subtotal' => $total_price,
                    'tax' => '0.00',
                    'shipping' => '0.00'
                )
            ),
            'description' => $payment_description
        );

        if ($is_upgrade == 1) {
            $payment['transactions'][0]['item_list']['items'][] = array(
                'quantity' => '1',
                'name' => __('Upgrade to Featured Job', 'jobster'),
                'price' => $total_price,
                'currency' => $payment_currency,
                'sku' => 'Upgrade Featured Job',
            );
        } else {
            if ($is_featured == 0) {
                $payment['transactions'][0]['item_list']['items'][] = array(
                    'quantity' => '1',
                    'name' => __('Job Posting Payment', 'jobster'),
                    'price' => $total_price,
                    'currency' => $payment_currency,
                    'sku' => 'Paid Job Posting',
                );
            } else {
                $payment['transactions'][0]['item_list']['items'][] = array(
                    'quantity' => '1',
                    'name' => __('Job Posting Payment with Featured option', 'jobster'),
                    'price' => $total_price,
                    'currency' => $payment_currency,
                    'sku' => 'Featured Paid Job Posting',
                );
            }
        }

        $json      = json_encode($payment);
        $json_resp = jobster_make_paypal_post_call($url, $json, $token);

        foreach ($json_resp['links'] as $link) {
            if ($link['rel'] == 'execute') {
                $payment_execute_url    = $link['href'];
                $payment_execute_method = $link['method'];
            } else if($link['rel'] == 'approval_url') {
                $payment_approval_url    = $link['href'];
                $payment_approval_method = $link['method'];
            }
        }

        $executor['paypal_execute']   = $payment_execute_url;
        $executor['paypal_token']     = $token;
        $executor['job_id']           = $job_id;
        $executor['is_featured']      = $is_featured;
        $executor['is_upgrade']       = $is_upgrade;
        $save_data[$current_user->ID] = $executor;

        update_option('paypal_transfer', $save_data);

        echo json_encode(array('url' => $payment_approval_url));

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_paypal_pay_listing', 'jobster_paypal_pay_listing');
add_action('wp_ajax_jobster_paypal_pay_listing', 'jobster_paypal_pay_listing');

if (!function_exists('jobster_paypal_pay_membership_plan')):
    function jobster_paypal_pay_membership_plan() {
        $plan_id = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : '';
        $plan    = get_post($plan_id);

        if (!empty($plan)) {
            global $current_user;
            $current_user = wp_get_current_user();

            $plan_price = get_post_meta($plan_id, 'membership_plan_price', true);

            $membership_settings = get_option('jobster_membership_settings');
            $paypal_version =   isset($membership_settings['jobster_paypal_api_version_field']) 
                                ? $membership_settings['jobster_paypal_api_version_field']
                                : '';
            $payment_currency = isset($membership_settings['jobster_paypal_payment_currency_field'])
                                ? $membership_settings['jobster_paypal_payment_currency_field']
                                : '';
            $host = 'https://api.sandbox.paypal.com';

            if ($paypal_version == 'live') {
                $host = 'https://api.paypal.com';
            }

            $url      = $host . '/v1/oauth2/token';
            $postArgs = 'grant_type=client_credentials';
            $token    = jobster_get_paypal_access_token($url, $postArgs);
            $url      = $host . '/v1/payments/payment';

            $subscriptions_url = jobster_get_page_link('company-dashboard-subscriptions.php');

            $payment = array(
                'intent' => 'sale',
                'redirect_urls' => array(
                    'return_url' => $subscriptions_url,
                    'cancel_url' => $subscriptions_url
                ),
                'payer' => array('payment_method' => 'paypal'),
            );

            $payment['transactions'][0] = array(
                'amount' => array(
                    'total' => $plan_price,
                    'currency' => $payment_currency,
                    'details' => array(
                        'subtotal' => $plan_price,
                        'tax' => '0.00',
                        'shipping' => '0.00'
                    )
                ),
                'description' => $plan->post_title . ' ' . __('membership plan payment on', 'jobster') . ' ' . home_url()
            );

            $payment['transactions'][0]['item_list']['items'][] = array(
                'quantity' => '1',
                'name' => __('Membership Plan Payment', 'jobster'),
                'price' => $plan_price,
                'currency' => $payment_currency,
                'sku' => $plan->post_title . ' ' . __('Membership Payment', 'jobster'),
            );

            $json      = json_encode($payment);
            $json_resp = jobster_make_paypal_post_call($url, $json, $token);

            foreach ($json_resp['links'] as $link) {
                if ($link['rel'] == 'execute') {
                    $payment_execute_url    = $link['href'];
                    $payment_execute_method = $link['method'];
                } else if($link['rel'] == 'approval_url') {
                    $payment_approval_url    = $link['href'];
                    $payment_approval_method = $link['method'];
                }
            }

            $executor['paypal_execute']    = $payment_execute_url;
            $executor['paypal_token']      = $token;
            $executor['plan_id']           = $plan_id;
            $save_data[$current_user->ID ] = $executor;

            update_option('paypal_plan_transfer', $save_data);

            echo json_encode(array('url' => $payment_approval_url));
        }
        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_paypal_pay_membership_plan',
    'jobster_paypal_pay_membership_plan'
);
add_action(
    'wp_ajax_jobster_paypal_pay_membership_plan',
    'jobster_paypal_pay_membership_plan'
);

if (!function_exists('jobster_activate_membership_plan')):
    function jobster_activate_membership_plan() {
        global $current_user;

        $plan_id  = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : '';

        $current_user = wp_get_current_user();
        $is_company = jobster_user_is_company($current_user->ID);
        if ($is_company) {
            $company_id = jobster_get_company_by_userid($current_user->ID);

            $plan = get_post($plan_id);

            if (!empty($plan)) {
                jobster_update_company_membership($company_id, $plan_id, true);
    
                $subscriptions_url = jobster_get_page_link('company-dashboard-subscriptions.php');

                echo json_encode(array('url' => $subscriptions_url));
            }
        }

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_activate_membership_plan',
    'jobster_activate_membership_plan'
);
add_action(
    'wp_ajax_jobster_activate_membership_plan',
    'jobster_activate_membership_plan'
);

if (!function_exists('jobster_email_payment_to_admin')):
    function jobster_email_payment_to_admin($job_id, $company_id, $is_upgrade) {
        if ($is_upgrade == 1) {
            $subject = sprintf(__('[%s] Job Upgraded to Featured', 'jobster'), get_option('blogname'));
            $message = sprintf(__('You have a new featured job on %s.', 'jobster'), get_option('blogname')) . "\r\n\r\n";
        } else {
            $subject = sprintf(__('[%s] New Paid Job Posting', 'jobster'), get_option('blogname'));
            $message = sprintf(__('You have a new paid job posting on %s.', 'jobster'), get_option('blogname')) . "\r\n\r\n";
        }

        $message .= sprintf(__('Job: %s', 'jobster'), get_the_title($job_id)) . "\r\n";
        $message .= sprintf(__('Company: %s', 'jobster'), get_the_title($company_id));

        wp_mail(get_option('admin_email'), $subject, $message);
    }
endif;

if (!function_exists('jobster_update_company_membership')):
    function jobster_update_company_membership($company_id, $plan_id, $is_free = false) {
        $plan_listings = get_post_meta(
            $plan_id,
            'membership_submissions_no',
            true
        );
        $plan_unlimited  = get_post_meta(
            $plan_id,
            'membership_unlim_submissions',
            true
        );
        $plan_featured_listings = get_post_meta(
            $plan_id,
            'membership_featured_submissions_no',
            true
        );
        $plan_cv_access = get_post_meta(
            $plan_id,
            'membership_cv_access',
            true
        );
        $company_email = get_post_meta($company_id, 'company_email', true);

        update_post_meta($company_id, 'company_plan', $plan_id);
        update_post_meta($company_id, 'company_plan_listings', $plan_listings);
        update_post_meta($company_id, 'company_plan_unlimited', $plan_unlimited);
        update_post_meta($company_id, 'company_plan_featured', $plan_featured_listings);
        update_post_meta($company_id, 'company_plan_cv_access', $plan_cv_access);

        if ($is_free === true) {
            update_post_meta($company_id, 'company_plan_free', 1);
        } else {
            update_post_meta($company_id, 'company_plan_free', '');
        }

        $time = time(); 
        $date = date('Y-m-d H:i:s', $time);

        update_post_meta($company_id, 'company_plan_activation', $date);

        if ($is_free === false) {
            jobster_insert_invoice('membership_plan', $plan_id, $company_id, 0, 0, 0);
        }

        $subject = sprintf(__('[%s] Membership Plan Activated', 'jobster'), get_option('blogname'));
        $message = sprintf(__('You have a new membership plan on %s is activated.', 'jobster'), get_option('blogname')) . "\r\n\r\n";
        $message .= sprintf(__('Membership Type: %s', 'jobster'), get_the_title($plan_id));

        wp_mail($company_email, $subject, $message);
    }
endif;

if (!function_exists('jobster_update_candidate_membership')):
    function jobster_update_candidate_membership($candidate_id, $plan_id, $is_free = false) {
        $plan_listings = get_post_meta(
            $plan_id,
            'membership_submissions_no',
            true
        );
        $plan_unlimited  = get_post_meta(
            $plan_id,
            'membership_unlim_submissions',
            true
        );
        $plan_featured_listings = get_post_meta(
            $plan_id,
            'membership_featured_submissions_no',
            true
        );
        $plan_cv_access = get_post_meta(
            $plan_id,
            'membership_cv_access',
            true
        );
        $candidate_email = get_post_meta($candidate_id, 'candidate_email', true);

        update_post_meta($candidate_id, 'candidate_plan', $plan_id);
        update_post_meta($candidate_id, 'candidate_plan_listings', $plan_listings);
        update_post_meta($candidate_id, 'candidate_plan_unlimited', $plan_unlimited);
        update_post_meta($candidate_id, 'candidate_plan_featured', $plan_featured_listings);
        update_post_meta($candidate_id, 'candidate_plan_cv_access', $plan_cv_access);

        if ($is_free === true) {
            update_post_meta($candidate_id, 'candidate_plan_free', 1);
        } else {
            update_post_meta($candidate_id, 'candidate_plan_free', '');
        }

        $time = time(); 
        $date = date('Y-m-d H:i:s', $time);

        update_post_meta($candidate_id, 'candidate_plan_activation', $date);

        if ($is_free === false) {
            jobster_insert_invoice('membership_plan', $plan_id, 0, $candidate_id, 0);
        }

        $subject = sprintf(__('[%s] Membership Plan Activated', 'jobster'), get_option('blogname'));
        $message = sprintf(__('You have a new membership plan on %s is activated.', 'jobster'), get_option('blogname')) . "\r\n\r\n";
        $message .= sprintf(__('Membership Type: %s', 'jobster'), get_the_title($plan_id));

        if ($candidate_email) {
            wp_mail($candidate_email, $subject, $message);
        }
    }
endif;
?>