# 🔧 Subscription Pages Troubleshooting Guide

## 🚨 Issue: Subscription Pages Showing Blank

If your subscription pages are showing blank, follow this comprehensive troubleshooting guide to identify and fix the issue.

## 📋 Quick Diagnosis

### Step 1: Run the Debug Script
1. Upload the `subscription-debug.php` file to your `jobster-plugin` directory
2. Access it via: `yoursite.com/wp-content/plugins/jobster-plugin/subscription-debug.php`
3. Review the diagnostic report to identify specific issues

### Step 2: Run the Auto-Fix Script
1. Upload the `subscription-fix.php` file to your `jobster-plugin` directory
2. Access it via: `yoursite.com/wp-content/plugins/jobster-plugin/subscription-fix.php`
3. Use the auto-fix options to resolve common issues

## 🔍 Common Causes & Solutions

### 1. Missing WordPress Pages

**Problem**: Subscription page templates exist but WordPress pages don't exist.

**Symptoms**:
- Page shows 404 error
- URL returns blank page
- `jobster_get_page_link()` returns empty

**Solution**:
```php
// Manual Fix
1. Go to WordPress Admin → Pages → Add New
2. Create page titled "Candidate Subscriptions"
3. Set page template to "Candidate Dashboard - Subscriptions"
4. Publish the page
5. Repeat for "Company Subscriptions"
```

**Auto-Fix**: Use the subscription-fix.php script

### 2. Missing Subscription Functions

**Problem**: Required PHP functions are not loaded.

**Symptoms**:
- PHP errors in error log
- Functions like `jobster_get_candidate_subscription_status()` don't exist

**Solution**:
```php
// Check if these files are included in jobster-plugin.php:
require_once 'services/candidate-subscription.php';
require_once 'admin/subscription-admin.php';
require_once 'admin/subscription-admin-fix.php';
```

### 3. Missing Subscription Data

**Problem**: Candidates/companies don't have subscription metadata.

**Symptoms**:
- Page loads but shows no subscription information
- Functions return empty/default values

**Solution**:
```php
// Set subscription data for a candidate:
update_post_meta($candidate_id, 'candidate_subscription_status', 'active');
update_post_meta($candidate_id, 'candidate_subscription_plan', 'premium');
update_post_meta($candidate_id, 'candidate_subscription_expiry', '2024-12-31');
```

### 4. Missing Membership Settings

**Problem**: Jobster membership settings are not configured.

**Symptoms**:
- Company subscriptions don't show
- Payment options are missing

**Solution**:
```php
// Go to WordPress Admin → Jobster Settings → Membership
// Configure:
// - Payment Type: "plan"
// - Payment System: "stripe" or "paypal"
// - Currency settings
```

### 5. Plugin Activation Issues

**Problem**: Plugin files are not properly loaded.

**Symptoms**:
- Functions don't exist
- Page templates not available

**Solution**:
```php
1. Deactivate Jobster plugin
2. Reactivate Jobster plugin
3. Clear any caching
4. Check file permissions (755 for directories, 644 for files)
```

## 🛠️ Manual Fixes

### Create Subscription Pages Manually

```php
// Add this code to functions.php or a custom plugin
function create_subscription_pages() {
    // Create Candidate Subscriptions page
    $candidate_page = get_page_by_title('Candidate Subscriptions');
    if (!$candidate_page) {
        $candidate_page_id = wp_insert_post(array(
            'post_title' => 'Candidate Subscriptions',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page'
        ));
        update_post_meta($candidate_page_id, '_wp_page_template', 'candidate-dashboard-subscriptions.php');
    }
    
    // Create Company Subscriptions page
    $company_page = get_page_by_title('Company Subscriptions');
    if (!$company_page) {
        $company_page_id = wp_insert_post(array(
            'post_title' => 'Company Subscriptions',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page'
        ));
        update_post_meta($company_page_id, '_wp_page_template', 'company-dashboard-subscriptions.php');
    }
}
add_action('init', 'create_subscription_pages');
```

### Set Default Membership Settings

```php
// Add this code to functions.php or a custom plugin
function set_default_membership_settings() {
    $membership_settings = get_option('jobster_membership_settings');
    if (empty($membership_settings)) {
        $default_settings = array(
            'jobster_payment_type_field' => 'plan',
            'jobster_payment_system_field' => 'stripe',
            'jobster_stripe_payment_currency_field' => 'USD'
        );
        update_option('jobster_membership_settings', $default_settings);
    }
}
add_action('init', 'set_default_membership_settings');
```

## 🔧 Advanced Troubleshooting

### Check PHP Error Log

```bash
# Check WordPress debug log
tail -f /path/to/wp-content/debug.log

# Check server error log
tail -f /var/log/apache2/error.log
# or
tail -f /var/log/nginx/error.log
```

### Enable WordPress Debug

Add to `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Test Functions Manually

```php
// Add this to a test page or functions.php
function test_subscription_functions() {
    if (function_exists('jobster_get_candidate_subscription_status')) {
        echo "✓ Function exists<br>";
        
        // Test with a candidate ID
        $candidate_id = 1; // Replace with actual candidate ID
        $subscription = jobster_get_candidate_subscription_status($candidate_id);
        echo "Subscription data: " . print_r($subscription, true) . "<br>";
    } else {
        echo "✗ Function missing<br>";
    }
}
```

## 📊 Verification Checklist

After applying fixes, verify:

- [ ] Subscription pages exist in WordPress admin
- [ ] Pages are accessible via direct URL
- [ ] All required functions exist (`function_exists()` returns true)
- [ ] Subscription data exists for test candidates/companies
- [ ] Membership settings are configured
- [ ] No PHP errors in error log
- [ ] Page templates are properly registered

## 🆘 Still Having Issues?

If the subscription pages are still showing blank after following this guide:

1. **Check the debug script output** for specific error messages
2. **Review the error logs** for PHP errors
3. **Verify file permissions** on plugin directories
4. **Test with a default theme** to rule out theme conflicts
5. **Disable other plugins** to check for conflicts
6. **Contact support** with the debug script output

## 📁 File Structure

Ensure these files exist and are properly loaded:

```
jobster-plugin/
├── page-templates/
│   ├── candidate-dashboard-subscriptions.php
│   └── company-dashboard-subscriptions.php
├── services/
│   ├── candidate-subscription.php
│   └── subscription.php
├── admin/
│   ├── subscription-admin.php
│   └── subscription-admin-fix.php
├── subscription-debug.php
└── subscription-fix.php
```

## 🎯 Quick Fix Summary

1. **Run debug script** to identify issues
2. **Run fix script** to auto-resolve common problems
3. **Create missing pages** if needed
4. **Set up subscription data** for test users
5. **Configure membership settings**
6. **Test the pages** and verify functionality

---

*This troubleshooting guide covers the most common causes of blank subscription pages. For additional support, refer to the debug script output and error logs.*