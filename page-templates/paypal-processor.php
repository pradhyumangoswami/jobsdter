<?php
/*
Template Name: Paypal Processor
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

$membership_settings = get_option('jobster_membership_settings');
$payment_type = isset($membership_settings['jobster_payment_type_field'])
                ? $membership_settings['jobster_payment_type_field']
                : '';

if ($payment_type != 'listing') {
    exit();
}

$paypal_version =   isset($membership_settings['jobster_paypal_api_version_field'])
                    ? $membership_settings['jobster_paypal_api_version_field']
                    : '';
$host = 'https://api.sandbox.paypal.com';

if ($paypal_version == 'live') {
    $host = 'https://api.paypal.com';
}

global $current_user;
$current_user = wp_get_current_user();

$headers = 'From: My Name <myname@example.com>' . "\r\n";
$attachments = '';

if (isset($_GET['token']) && isset($_GET['PayerID'])) {
    $token   = sanitize_text_field($_GET['token']);
    $payerId = sanitize_text_field($_GET['PayerID']);

    // get transfer data
    $save_data           = get_option('paypal_transfer');
    $payment_execute_url = $save_data[$current_user->ID]['paypal_execute'];
    $token               = $save_data[$current_user->ID]['paypal_token'];
    $job_id              = $save_data[$current_user->ID]['job_id'];
    $is_featured         = $save_data[$current_user->ID]['is_featured'];
    $is_upgrade          = $save_data[$current_user->ID]['is_upgrade'];

    $payment_execute = array(
        'payer_id' => $payerId
    );

    $json      = json_encode($payment_execute);
    $json_resp = jobster_make_paypal_post_call($payment_execute_url, $json, $token);

    $save_data[$current_user->ID ] = array();

    update_option('paypal_transfer', $save_data);

    // update prop listing status
    if ($json_resp['state'] == 'approved') {
        $time       = time();
        $date       = date('Y-m-d H:i:s', $time);
        $company_id = jobster_get_company_by_userid($current_user->ID);

        if ($is_upgrade == 1) {
            update_post_meta($job_id, 'job_featured', 1);
            jobster_insert_invoice('job_upgraded_featured', $job_id, $company_id, 0, 1);

            jobster_email_payment_to_admin($job_id, $company_id, 1);
        } else {
            update_post_meta($job_id, 'job_payment_status', 'paid');

            if (get_post_status($job_id) != 'draft') {
                $post = array(
                    'ID' => $job_id,
                    'post_status' => 'publish'
                );
            }

            $post_id = wp_update_post($post);

            if ($is_featured == 1) {
                update_post_meta($job_id, 'job_featured', 1);
                jobster_insert_invoice('featured_job', $job_id, $company_id, 1, 0);
            } else {
                jobster_insert_invoice('standard_job_posting', $job_id, $company_id, 0, 0);
            }

            jobster_email_payment_to_admin($job_id, $company_id, 0);
        }
    }

    $redirect = jobster_get_page_link('company-dashboard-jobs.php');

    wp_redirect($redirect);
}

$token = '';

/**
 * Process messages from PayPal IPN
 */
define('DEBUG', 0);

$raw_post_data  = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost         = array();

foreach ($raw_post_array as $keyval) {
    $keyval = explode ('=', $keyval);

    if (count($keyval) == 2) {
        $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
}

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

if (function_exists('get_magic_quotes_gpc')) {
    $get_magic_quotes_exists = true;
}

foreach ($myPost as $key => $value) {
    if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        $value = urlencode(stripslashes($value)); 
    } else {
        $value = urlencode($value);
    }

    $req .= "&$key=$value";
}

// Step 2: POST IPN data back to PayPal to validate
if ($paypal_version == 'live') {
    $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
} else {
    $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}

$ch = curl_init($paypal_url);

if ($ch == FALSE) {
    return FALSE;
}

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

if (DEBUG == true) {
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}

// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

$res = curl_exec($ch);

if (curl_errno($ch) != 0) {
    if (DEBUG == true) {
        // error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
    }

    curl_close($ch);

    exit;
} else {
    if (DEBUG == true) {
        // Split response headers and payload
        list($headers, $res) = explode("\r\n\r\n", $res, 2);
    }

    curl_close($ch);
}

// Inspect IPN validation result
if (strcmp($res, "VERIFIED") == 0) {
    $allowed_html   = array();
    $payment_status = sanitize_text_field($_POST['payment_status']);
    $txn_id         = sanitize_text_field($_POST['txn_id']);
    $txn_type       = sanitize_text_field($_POST['txn_type']);

    $paypal_email   =   isset($membership_settings['jobster_paypal_email_field']) 
                        ? $membership_settings['jobster_paypal_email_field'] : '';
    $receiver_email = sanitize_email($_POST['receiver_email']);
    $payer_id       = sanitize_text_field($_POST['payer_id']);

    $payer_email = sanitize_email($_POST['payer_email']);
    $amount      = sanitize_text_field($_POST['amount']);

    // $recurring_payment_id = sanitize_text_field($_POST['recurring_payment_id']);
    // $agent_id = reales_get_agent_by_profile($recurring_payment_id);
    // $plan_id = get_post_meta($agent_id, 'plan_id', true);
    // $price = get_post_meta($plan_id, 'membership_plan_price', true);

    $mailm = '';

    foreach ($_POST as $key => $value) {
        $key   = sanitize_key($key);
        $value = wp_kses($value, $allowed_html);
        $mailm .= '[' . $key . ']=' . $value . '</br>';
    }

    if ($payment_status == 'Completed') {
        if ($receiver_email != $paypal_email) {
            exit();
        }
        // payment already processed
        /*if(reales_get_invoice_by_taxid($txn_id)) {
            exit();
        }*/
        // no user with such profile id
        /*if($agent_id == 0) {
            exit();
        }*/
        // received payment different than pack value
        /*if($amount != $price) {
            exit();
        }*/

        // reales_upgrade_agent_membership($agent_id, $plan_id, 2, $txn_id);
    } else { 
        // payment not completed
        /*if($txn_type != 'recurring_payment_profile_created') {
            reales_remove_plan($agent_id);
        }*/
    }
} else if (strcmp($res, "INVALID") == 0) {
    exit('invalid');
}
?>
