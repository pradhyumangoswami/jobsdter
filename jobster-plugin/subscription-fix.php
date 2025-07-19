<?php
/**
 * Subscription Fix Script
 * 
 * This script automatically fixes common subscription page issues.
 * Place this file in the jobster-plugin directory and access it via:
 * yoursite.com/wp-content/plugins/jobster-plugin/subscription-fix.php
 */

// Prevent direct access if not logged in
if (!is_user_logged_in()) {
    wp_die('Please log in to access this fix script.');
}

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('You need administrator privileges to access this fix script.');
}

echo '<h1>Subscription System Auto-Fix</h1>';
echo '<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .warning { color: orange; }
    .info { color: blue; }
    .fix-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
</style>';

// Process form submission
if (isset($_POST['fix_subscription_pages'])) {
    echo '<div class="fix-section">';
    echo '<h2>Creating Subscription Pages...</h2>';
    
    // Create Candidate Subscriptions page
    $candidate_page_exists = get_page_by_title('Candidate Subscriptions');
    if (!$candidate_page_exists) {
        $candidate_page_id = wp_insert_post(array(
            'post_title' => 'Candidate Subscriptions',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => 'candidate-dashboard-subscriptions.php'
        ));
        
        if ($candidate_page_id) {
            update_post_meta($candidate_page_id, '_wp_page_template', 'candidate-dashboard-subscriptions.php');
            echo "<p class='success'>✓ Created Candidate Subscriptions page (ID: {$candidate_page_id})</p>";
        } else {
            echo "<p class='error'>✗ Failed to create Candidate Subscriptions page</p>";
        }
    } else {
        echo "<p class='info'>ℹ Candidate Subscriptions page already exists (ID: {$candidate_page_exists->ID})</p>";
    }
    
    // Create Company Subscriptions page
    $company_page_exists = get_page_by_title('Company Subscriptions');
    if (!$company_page_exists) {
        $company_page_id = wp_insert_post(array(
            'post_title' => 'Company Subscriptions',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => 'company-dashboard-subscriptions.php'
        ));
        
        if ($company_page_id) {
            update_post_meta($company_page_id, '_wp_page_template', 'company-dashboard-subscriptions.php');
            echo "<p class='success'>✓ Created Company Subscriptions page (ID: {$company_page_id})</p>";
        } else {
            echo "<p class='error'>✗ Failed to create Company Subscriptions page</p>";
        }
    } else {
        echo "<p class='info'>ℹ Company Subscriptions page already exists (ID: {$company_page_exists->ID})</p>";
    }
    
    echo '</div>';
}

if (isset($_POST['fix_membership_settings'])) {
    echo '<div class="fix-section">';
    echo '<h2>Setting up Membership Settings...</h2>';
    
    $membership_settings = get_option('jobster_membership_settings', array());
    
    // Set default membership settings if not already set
    if (empty($membership_settings)) {
        $membership_settings = array(
            'jobster_payment_type_field' => 'plan',
            'jobster_payment_system_field' => 'stripe',
            'jobster_stripe_payment_currency_field' => 'USD',
            'jobster_stripe_secret_key_field' => '',
            'jobster_stripe_publishable_key_field' => ''
        );
        
        update_option('jobster_membership_settings', $membership_settings);
        echo "<p class='success'>✓ Created default membership settings</p>";
    } else {
        echo "<p class='info'>ℹ Membership settings already exist</p>";
    }
    
    echo '</div>';
}

if (isset($_POST['create_sample_subscription'])) {
    echo '<div class="fix-section">';
    echo '<h2>Creating Sample Subscription Data...</h2>';
    
    // Get first candidate
    $candidates = get_posts(array(
        'post_type' => 'candidate',
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    
    if (!empty($candidates)) {
        $candidate_id = $candidates[0]->ID;
        
        // Set sample subscription data
        update_post_meta($candidate_id, 'candidate_subscription_status', 'active');
        update_post_meta($candidate_id, 'candidate_subscription_plan', 'premium');
        update_post_meta($candidate_id, 'candidate_subscription_expiry', date('Y-m-d', strtotime('+1 year')));
        
        echo "<p class='success'>✓ Created sample subscription data for candidate ID: {$candidate_id}</p>";
        echo "<p class='info'>Status: Active Premium (expires in 1 year)</p>";
    } else {
        echo "<p class='warning'>⚠ No candidates found to create sample data</p>";
    }
    
    echo '</div>';
}

// Display current status
echo '<div class="fix-section">';
echo '<h2>Current Status</h2>';

// Check subscription pages
$candidate_page = get_page_by_title('Candidate Subscriptions');
$company_page = get_page_by_title('Company Subscriptions');

if ($candidate_page) {
    echo "<p class='success'>✓ Candidate Subscriptions page exists</p>";
} else {
    echo "<p class='error'>✗ Candidate Subscriptions page missing</p>";
}

if ($company_page) {
    echo "<p class='success'>✓ Company Subscriptions page exists</p>";
} else {
    echo "<p class='error'>✗ Company Subscriptions page missing</p>";
}

// Check membership settings
$membership_settings = get_option('jobster_membership_settings');
if ($membership_settings) {
    echo "<p class='success'>✓ Membership settings exist</p>";
} else {
    echo "<p class='error'>✗ Membership settings missing</p>";
}

echo '</div>';

// Display fix options
echo '<div class="fix-section">';
echo '<h2>Auto-Fix Options</h2>';

echo '<form method="post">';
echo '<p><input type="submit" name="fix_subscription_pages" value="Create Missing Subscription Pages" style="background: #0073aa; color: white; padding: 10px 20px; border: none; cursor: pointer;"></p>';
echo '</form>';

echo '<form method="post">';
echo '<p><input type="submit" name="fix_membership_settings" value="Setup Membership Settings" style="background: #0073aa; color: white; padding: 10px 20px; border: none; cursor: pointer;"></p>';
echo '</form>';

echo '<form method="post">';
echo '<p><input type="submit" name="create_sample_subscription" value="Create Sample Subscription Data" style="background: #0073aa; color: white; padding: 10px 20px; border: none; cursor: pointer;"></p>';
echo '</form>';

echo '</div>';

// Display links
echo '<div class="fix-section">';
echo '<h2>Quick Links</h2>';
echo '<p><a href="' . admin_url('edit.php?post_type=page') . '" target="_blank">Manage Pages</a></p>';
echo '<p><a href="' . admin_url('edit.php?post_type=candidate') . '" target="_blank">Manage Candidates</a></p>';
echo '<p><a href="' . admin_url('edit.php?post_type=company') . '" target="_blank">Manage Companies</a></p>';
echo '<p><a href="' . admin_url('admin.php?page=jobster-settings') . '" target="_blank">Jobster Settings</a></p>';
echo '<p><a href="' . plugin_dir_url(__FILE__) . 'subscription-debug.php" target="_blank">Run Debug Report</a></p>';
echo '</div>';

echo '<div class="fix-section">';
echo '<h2>Next Steps</h2>';
echo '<ol>';
echo '<li>Run the debug script to check for any remaining issues</li>';
echo '<li>Configure payment settings in Jobster Settings</li>';
echo '<li>Create membership plans if using company subscriptions</li>';
echo '<li>Set up subscription data for candidates/companies</li>';
echo '<li>Test the subscription pages</li>';
echo '</ol>';
echo '</div>';
?>