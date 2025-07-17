<?php
/*
Template Name: Stripe Processor
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

$membership_settings = get_option('jobster_membership_settings');
$payment_type = isset($membership_settings['jobster_payment_type_field'])
                ? $membership_settings['jobster_payment_type_field']
                : '';
$endpoint_secret =   isset($membership_settings['jobster_stripe_endpoint_key_field'])
                    ? $membership_settings['jobster_stripe_endpoint_key_field']
                    : '';

$redirect = '';
if ($payment_type == 'listing') {
    $redirect = jobster_get_page_link('company-dashboard-jobs.php');
}
if ($payment_type == 'plan') {
    $redirect = jobster_get_page_link('company-dashboard-subscriptions.php');
}

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'payment_intent.succeeded':
        $paymentIntent = $event->data->object;
        // ... handle other event types
    case 'charge.succeeded':
        if ($payment_type == 'listing') {
            $time       = time();
            $date       = date('Y-m-d H:i:s', $time);
            $company_id = $event->data->object->metadata->company_id;
    
            $job_id = $event->data->object->metadata->job_id;
            $is_featured = $event->data->object->metadata->is_featured;
            $is_upgrade = $event->data->object->metadata->is_upgrade;
    
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

        if ($payment_type == 'plan') {
            $company_id = $event->data->object->metadata->company_id;
            $plan_id = $event->data->object->metadata->plan_id;

            jobster_update_company_membership($company_id, $plan_id);
        }

        wp_redirect($redirect);

        break;
    case 'charge.failed':
        wp_redirect($redirect);
        break;
    default:
        echo 'Received unknown event type ' . $event->type;
        wp_redirect($redirect);
}

http_response_code(200);
?>

