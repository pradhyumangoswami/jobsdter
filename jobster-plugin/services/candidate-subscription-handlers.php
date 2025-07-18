<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Process candidate subscription upgrade
 */
if (!function_exists('jobster_process_subscription_upgrade')):
    function jobster_process_subscription_upgrade() {
        // Verify user permissions
        if (!is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        $current_user = wp_get_current_user();
        $is_candidate = jobster_user_is_candidate($current_user->ID);
        
        if (!$is_candidate) {
            wp_redirect(home_url());
            exit;
        }

        $candidate_id = isset($_GET['candidate_id']) ? intval($_GET['candidate_id']) : 0;
        $plan = isset($_GET['plan']) ? sanitize_text_field($_GET['plan']) : '';

        if (!$candidate_id || !$plan) {
            wp_redirect(jobster_get_page_link('candidate-dashboard-subscriptions.php'));
            exit;
        }

        // Verify candidate ownership
        $candidate_user_id = get_post_meta($candidate_id, 'candidate_user_id', true);
        if ($candidate_user_id != $current_user->ID) {
            wp_redirect(home_url());
            exit;
        }

        // For demo purposes, we'll simulate a successful upgrade
        // In a real implementation, this would integrate with payment processing
        
        if ($plan === 'premium') {
            // Set premium subscription
            update_post_meta($candidate_id, 'candidate_subscription_status', 'active');
            update_post_meta($candidate_id, 'candidate_subscription_plan', 'premium');
            update_post_meta($candidate_id, 'candidate_subscription_expiry', date('Y-m-d H:i:s', strtotime('+1 month')));
            
            // Add success message
            add_action('wp_footer', function() {
                echo '<script>alert("' . esc_js(__('Successfully upgraded to Premium plan!', 'jobster')) . '");</script>';
            });
        }

        wp_redirect(jobster_get_page_link('candidate-dashboard-subscriptions.php'));
        exit;
    }
endif;
add_action('admin_post_jobster_process_subscription_upgrade', 'jobster_process_subscription_upgrade');
add_action('admin_post_nopriv_jobster_process_subscription_upgrade', 'jobster_process_subscription_upgrade');

/**
 * Process candidate subscription downgrade
 */
if (!function_exists('jobster_process_subscription_downgrade')):
    function jobster_process_subscription_downgrade() {
        // Verify user permissions
        if (!is_user_logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        $current_user = wp_get_current_user();
        $is_candidate = jobster_user_is_candidate($current_user->ID);
        
        if (!$is_candidate) {
            wp_redirect(home_url());
            exit;
        }

        $candidate_id = isset($_GET['candidate_id']) ? intval($_GET['candidate_id']) : 0;

        if (!$candidate_id) {
            wp_redirect(jobster_get_page_link('candidate-dashboard-subscriptions.php'));
            exit;
        }

        // Verify candidate ownership
        $candidate_user_id = get_post_meta($candidate_id, 'candidate_user_id', true);
        if ($candidate_user_id != $current_user->ID) {
            wp_redirect(home_url());
            exit;
        }

        // Downgrade to free plan
        update_post_meta($candidate_id, 'candidate_subscription_status', 'free');
        update_post_meta($candidate_id, 'candidate_subscription_plan', 'free');
        delete_post_meta($candidate_id, 'candidate_subscription_expiry');

        // Add success message
        add_action('wp_footer', function() {
            echo '<script>alert("' . esc_js(__('Successfully downgraded to Free plan!', 'jobster')) . '");</script>';
        });

        wp_redirect(jobster_get_page_link('candidate-dashboard-subscriptions.php'));
        exit;
    }
endif;
add_action('admin_post_jobster_process_subscription_downgrade', 'jobster_process_subscription_downgrade');
add_action('admin_post_nopriv_jobster_process_subscription_downgrade', 'jobster_process_subscription_downgrade');

/**
 * AJAX handler for updating subscription status
 */
if (!function_exists('jobster_ajax_update_subscription_status')):
    function jobster_ajax_update_subscription_status() {
        check_ajax_referer('jobster_subscription_nonce', 'nonce');

        if (!is_user_logged_in()) {
            wp_send_json_error(__('User not logged in', 'jobster'));
        }

        $current_user = wp_get_current_user();
        $is_candidate = jobster_user_is_candidate($current_user->ID);
        
        if (!$is_candidate) {
            wp_send_json_error(__('Invalid user type', 'jobster'));
        }

        $candidate_id = isset($_POST['candidate_id']) ? intval($_POST['candidate_id']) : 0;
        $action_type = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : '';
        $plan = isset($_POST['plan']) ? sanitize_text_field($_POST['plan']) : '';

        if (!$candidate_id || !$action_type) {
            wp_send_json_error(__('Missing required parameters', 'jobster'));
        }

        // Verify candidate ownership
        $candidate_user_id = get_post_meta($candidate_id, 'candidate_user_id', true);
        if ($candidate_user_id != $current_user->ID) {
            wp_send_json_error(__('Permission denied', 'jobster'));
        }

        switch ($action_type) {
            case 'upgrade':
                if ($plan === 'premium') {
                    update_post_meta($candidate_id, 'candidate_subscription_status', 'active');
                    update_post_meta($candidate_id, 'candidate_subscription_plan', 'premium');
                    update_post_meta($candidate_id, 'candidate_subscription_expiry', date('Y-m-d H:i:s', strtotime('+1 month')));
                    wp_send_json_success(__('Successfully upgraded to Premium plan!', 'jobster'));
                }
                break;

            case 'downgrade':
                update_post_meta($candidate_id, 'candidate_subscription_status', 'free');
                update_post_meta($candidate_id, 'candidate_subscription_plan', 'free');
                delete_post_meta($candidate_id, 'candidate_subscription_expiry');
                wp_send_json_success(__('Successfully downgraded to Free plan!', 'jobster'));
                break;

            default:
                wp_send_json_error(__('Invalid action type', 'jobster'));
        }

        wp_send_json_error(__('Unknown error occurred', 'jobster'));
    }
endif;
add_action('wp_ajax_jobster_update_subscription_status', 'jobster_ajax_update_subscription_status');
add_action('wp_ajax_nopriv_jobster_update_subscription_status', 'jobster_ajax_update_subscription_status');

/**
 * Initialize demo subscription data for testing
 */
if (!function_exists('jobster_init_demo_subscription_data')):
    function jobster_init_demo_subscription_data() {
        // This function can be called to set up demo data for testing
        // Get all candidates
        $candidates = get_posts(array(
            'post_type' => 'candidate',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));

        foreach ($candidates as $candidate) {
            $candidate_id = $candidate->ID;
            
            // Check if subscription data already exists
            $existing_status = get_post_meta($candidate_id, 'candidate_subscription_status', true);
            
            if (empty($existing_status)) {
                // Randomly assign subscription status for demo
                $random = rand(1, 10);
                
                if ($random <= 3) {
                    // 30% premium users
                    update_post_meta($candidate_id, 'candidate_subscription_status', 'active');
                    update_post_meta($candidate_id, 'candidate_subscription_plan', 'premium');
                    update_post_meta($candidate_id, 'candidate_subscription_expiry', date('Y-m-d H:i:s', strtotime('+1 month')));
                } elseif ($random <= 5) {
                    // 20% expired users
                    update_post_meta($candidate_id, 'candidate_subscription_status', 'expired');
                    update_post_meta($candidate_id, 'candidate_subscription_plan', 'premium');
                    update_post_meta($candidate_id, 'candidate_subscription_expiry', date('Y-m-d H:i:s', strtotime('-1 week')));
                } else {
                    // 50% free users
                    update_post_meta($candidate_id, 'candidate_subscription_status', 'free');
                    update_post_meta($candidate_id, 'candidate_subscription_plan', 'free');
                }
                
                // Initialize profile views counter
                update_post_meta($candidate_id, 'candidate_profile_views', rand(0, 50));
            }
        }
    }
endif;

// Uncomment the line below to initialize demo data
add_action('init', 'jobster_init_demo_subscription_data');