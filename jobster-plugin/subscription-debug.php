<?php
/**
 * Subscription Debug Script
 * 
 * This script helps diagnose issues with subscription pages showing blank.
 * Place this file in the jobster-plugin directory and access it via:
 * yoursite.com/wp-content/plugins/jobster-plugin/subscription-debug.php
 */

// Prevent direct access if not logged in
if (!is_user_logged_in()) {
    wp_die('Please log in to access this debug script.');
}

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('You need administrator privileges to access this debug script.');
}

echo '<h1>Subscription System Debug Report</h1>';
echo '<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .warning { color: orange; }
    .info { color: blue; }
    .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
</style>';

// 1. Check if subscription pages exist
echo '<div class="debug-section">';
echo '<h2>1. Subscription Pages Check</h2>';

$subscription_pages = array(
    'candidate-dashboard-subscriptions.php' => 'Candidate Subscriptions',
    'company-dashboard-subscriptions.php' => 'Company Subscriptions'
);

foreach ($subscription_pages as $template => $name) {
    $page_link = jobster_get_page_link($template);
    if ($page_link) {
        echo "<p class='success'>✓ {$name} page exists: <a href='{$page_link}' target='_blank'>{$page_link}</a></p>";
    } else {
        echo "<p class='error'>✗ {$name} page does not exist. Template: {$template}</p>";
    }
}
echo '</div>';

// 2. Check subscription functions
echo '<div class="debug-section">';
echo '<h2>2. Subscription Functions Check</h2>';

$required_functions = array(
    'jobster_get_candidate_subscription_status',
    'jobster_get_candidate_subscription_features',
    'jobster_display_candidate_subscription_widget',
    'jobster_get_candidate_subscription_badge',
    'jobster_user_is_candidate',
    'jobster_get_candidate_by_userid'
);

foreach ($required_functions as $function) {
    if (function_exists($function)) {
        echo "<p class='success'>✓ Function exists: {$function}</p>";
    } else {
        echo "<p class='error'>✗ Function missing: {$function}</p>";
    }
}
echo '</div>';

// 3. Check current user and candidate data
echo '<div class="debug-section">';
echo '<h2>3. Current User & Candidate Data</h2>';

$current_user = wp_get_current_user();
echo "<p class='info'>Current User ID: {$current_user->ID}</p>";
echo "<p class='info'>Current User Email: {$current_user->user_email}</p>";

if (function_exists('jobster_user_is_candidate')) {
    $is_candidate = jobster_user_is_candidate($current_user->ID);
    echo "<p class='info'>Is Candidate: " . ($is_candidate ? 'Yes' : 'No') . "</p>";
    
    if ($is_candidate && function_exists('jobster_get_candidate_by_userid')) {
        $candidate_id = jobster_get_candidate_by_userid($current_user->ID);
        echo "<p class='info'>Candidate ID: " . ($candidate_id ? $candidate_id : 'Not found') . "</p>";
        
        if ($candidate_id) {
            $subscription_status = get_post_meta($candidate_id, 'candidate_subscription_status', true);
            $subscription_plan = get_post_meta($candidate_id, 'candidate_subscription_plan', true);
            $subscription_expiry = get_post_meta($candidate_id, 'candidate_subscription_expiry', true);
            
            echo "<p class='info'>Subscription Status: " . ($subscription_status ? $subscription_status : 'Not set') . "</p>";
            echo "<p class='info'>Subscription Plan: " . ($subscription_plan ? $subscription_plan : 'Not set') . "</p>";
            echo "<p class='info'>Subscription Expiry: " . ($subscription_expiry ? $subscription_expiry : 'Not set') . "</p>";
        }
    }
}
echo '</div>';

// 4. Check membership settings
echo '<div class="debug-section">';
echo '<h2>4. Membership Settings</h2>';

$membership_settings = get_option('jobster_membership_settings');
if ($membership_settings) {
    echo "<p class='success'>✓ Membership settings exist</p>";
    echo "<pre>" . print_r($membership_settings, true) . "</pre>";
} else {
    echo "<p class='warning'>⚠ Membership settings not found</p>";
}
echo '</div>';

// 5. Check if subscription service files are loaded
echo '<div class="debug-section">';
echo '<h2>5. Service Files Check</h2>';

$service_files = array(
    'services/candidate-subscription.php',
    'services/subscription.php',
    'admin/subscription-admin.php',
    'admin/subscription-admin-fix.php'
);

foreach ($service_files as $file) {
    $file_path = plugin_dir_path(__FILE__) . $file;
    if (file_exists($file_path)) {
        echo "<p class='success'>✓ File exists: {$file}</p>";
    } else {
        echo "<p class='error'>✗ File missing: {$file}</p>";
    }
}
echo '</div>';

// 6. Test subscription functions
echo '<div class="debug-section">';
echo '<h2>6. Function Test Results</h2>';

if (function_exists('jobster_user_is_candidate') && function_exists('jobster_get_candidate_by_userid')) {
    $is_candidate = jobster_user_is_candidate($current_user->ID);
    if ($is_candidate) {
        $candidate_id = jobster_get_candidate_by_userid($current_user->ID);
        if ($candidate_id) {
            if (function_exists('jobster_get_candidate_subscription_status')) {
                try {
                    $subscription = jobster_get_candidate_subscription_status($candidate_id);
                    echo "<p class='success'>✓ Subscription status retrieved: " . print_r($subscription, true) . "</p>";
                } catch (Exception $e) {
                    echo "<p class='error'>✗ Error getting subscription status: " . $e->getMessage() . "</p>";
                }
            }
            
            if (function_exists('jobster_get_candidate_subscription_features')) {
                try {
                    $features = jobster_get_candidate_subscription_features($candidate_id);
                    echo "<p class='success'>✓ Subscription features retrieved: " . print_r($features, true) . "</p>";
                } catch (Exception $e) {
                    echo "<p class='error'>✗ Error getting subscription features: " . $e->getMessage() . "</p>";
                }
            }
        }
    }
}
echo '</div>';

// 7. Recommendations
echo '<div class="debug-section">';
echo '<h2>7. Recommendations</h2>';

echo '<h3>If subscription pages are missing:</h3>';
echo '<ol>';
echo '<li>Go to WordPress Admin → Pages → Add New</li>';
echo '<li>Create a page titled "Candidate Subscriptions"</li>';
echo '<li>Set the page template to "Candidate Dashboard - Subscriptions"</li>';
echo '<li>Publish the page</li>';
echo '<li>Repeat for "Company Subscriptions" with template "Company Dashboard - Subscriptions"</li>';
echo '</ol>';

echo '<h3>If functions are missing:</h3>';
echo '<ol>';
echo '<li>Check if the plugin is properly activated</li>';
echo '<li>Deactivate and reactivate the Jobster plugin</li>';
echo '<li>Check for PHP errors in your error log</li>';
echo '</ol>';

echo '<h3>If subscription data is missing:</h3>';
echo '<ol>';
echo '<li>Go to WordPress Admin → Candidates</li>';
echo '<li>Edit a candidate and check the "Subscription Management" meta box</li>';
echo '<li>Set subscription status, plan, and expiry date</li>';
echo '<li>Save the candidate</li>';
echo '</ol>';
echo '</div>';

echo '<div class="debug-section">';
echo '<h2>8. Quick Fixes</h2>';
echo '<p><a href="' . admin_url('admin.php?page=jobster-settings') . '" target="_blank">Go to Jobster Settings</a></p>';
echo '<p><a href="' . admin_url('edit.php?post_type=candidate') . '" target="_blank">Manage Candidates</a></p>';
echo '<p><a href="' . admin_url('edit.php?post_type=company') . '" target="_blank">Manage Companies</a></p>';
echo '</div>';
?>