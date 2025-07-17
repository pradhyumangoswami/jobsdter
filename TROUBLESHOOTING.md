# 🔧 Troubleshooting Guide: Candidate Subscription Features

## 🚨 Common Issues and Solutions

### 1. **Admin Interface Not Showing**

**Problem**: The subscription meta box, columns, or filters don't appear in WordPress admin.

**Solution**:
1. **Check if fix file is loaded**:
   - Verify `admin/subscription-admin-fix.php` is included in `jobster-plugin.php`
   - Should see: `require_once 'admin/subscription-admin-fix.php';`

2. **Initialize demo data**:
   - Go to `WordPress Admin → Candidates → All Candidates`
   - Look for blue notice: "Some candidates don't have subscription data"
   - Click "Initialize Demo Data" button
   - This will set up test subscription data for all candidates

3. **Check candidate post type**:
   - Ensure you're viewing the correct post type: `/wp-admin/edit.php?post_type=candidate`
   - Meta box should appear in sidebar when editing any candidate
   - Subscription column should appear in candidates list

4. **Clear cache and refresh**:
   - Deactivate and reactivate the plugin
   - Clear any caching plugins
   - Hard refresh browser (Ctrl+F5)

### 2. **Subscription Page Not Accessible**

**Problem**: The subscription page shows 404 or doesn't load.

**Solution**:
1. **Create the subscription page in WordPress admin**:
   - Go to `Pages → Add New`
   - Title: "Candidate Dashboard - Subscriptions"
   - Template: Select "Candidate Dashboard - Subscriptions"
   - Publish the page

2. **Check page template registration**:
   - Verify `page-templates/init.php` includes the subscription template
   - Template should be: `candidate-dashboard-subscriptions.php`

3. **Flush permalinks**:
   - Go to `Settings → Permalinks`
   - Click "Save Changes" to refresh URL structure

### 2. **Subscription Widget Not Showing**

**Problem**: The subscription widget doesn't appear on the candidate dashboard.

**Solution**:
1. **Check if candidate is logged in**:
   - Ensure user is logged in as a candidate
   - Verify candidate profile exists

2. **Verify function calls**:
   - Check if `jobster_display_candidate_subscription_widget($candidate_id)` is called in `candidate-dashboard.php`
   - Ensure `services/candidate-subscription.php` is included

3. **Check for PHP errors**:
   - Enable WordPress debug mode
   - Add to `wp-config.php`:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```

### 3. **Subscription Badges Not Showing**

**Problem**: Badges don't appear next to candidate names in search results.

**Solution**:
1. **Check CSS loading**:
   - Verify `css/candidate-subscription.css` is being loaded
   - Check browser developer tools for CSS errors

2. **Verify badge function call**:
   - Ensure `jobster_get_candidate_subscription_badge($candidate_id)` is called in search results
   - Check `page-templates/candidate-search.php`

3. **Initialize demo data**:
   - Call `jobster_init_demo_subscription_data()` to set up test data
   - Or manually set subscription meta for test candidates

### 4. **Navigation Link Missing**

**Problem**: "Subscriptions" link doesn't appear in dashboard navigation.

**Solution**:
1. **Check navigation files**:
   - Verify `views/candidate-dashboard-side.php` includes subscription link
   - Check `views/candidate-dashboard-top.php` for mobile navigation

2. **Verify page exists**:
   - Ensure subscription page is created and published
   - Check if `jobster_get_page_link('candidate-dashboard-subscriptions.php')` returns valid URL

### 5. **Subscription Status Not Updating**

**Problem**: Changes to subscription status don't save or display.

**Solution**:
1. **Check meta fields**:
   - Verify these meta fields exist for candidates:
     - `candidate_subscription_status`
     - `candidate_subscription_plan`
     - `candidate_subscription_expiry`

2. **Test function calls**:
   - Use `jobster_get_candidate_subscription_status($candidate_id)` to check status
   - Verify `update_post_meta()` calls are working

3. **Check permissions**:
   - Ensure current user has permission to edit candidate
   - Verify candidate ownership in handlers

### 6. **CSS Styles Not Applied**

**Problem**: Subscription elements don't have proper styling.

**Solution**:
1. **Check CSS enqueuing**:
   - Verify `scripts.php` includes CSS enqueue
   - Should see: `wp_enqueue_style('candidate-subscription', ...)`

2. **Clear cache**:
   - Clear any caching plugins
   - Hard refresh browser (Ctrl+F5)

3. **Check CSS file**:
   - Verify `css/candidate-subscription.css` exists and is readable
   - Check for CSS syntax errors

## 🔍 Debugging Steps

### Step 1: Test Admin Interface

1. **Go to WordPress Admin → Candidates → All Candidates**
2. **Look for**:
   - "Subscription" column in the candidates list
   - Filter dropdown with "All Subscriptions" option
   - Blue notice about initializing demo data (if no data exists)

3. **Edit any candidate**:
   - Look for "Subscription Management" meta box in right sidebar
   - Should contain status dropdowns, date field, and quick action buttons

4. **If missing**:
   - Check if `admin/subscription-admin-fix.php` is included
   - Try deactivating/reactivating plugin
   - Initialize demo data using the button

### Step 2: Check Plugin Activation
```php
// Add to functions.php temporarily
add_action('wp_footer', function() {
    if (function_exists('jobster_get_candidate_subscription_status')) {
        echo '<!-- Subscription functions loaded -->';
    } else {
        echo '<!-- ERROR: Subscription functions not loaded -->';
    }
});
```

### Step 2: Test Subscription Status
```php
// Add to candidate dashboard template temporarily
$candidate_id = 123; // Replace with actual candidate ID
$status = jobster_get_candidate_subscription_status($candidate_id);
echo '<pre>' . print_r($status, true) . '</pre>';
```

### Step 3: Check Page Template
```php
// Add to subscription page template
global $post;
echo '<p>Template: ' . get_post_meta($post->ID, '_wp_page_template', true) . '</p>';
```

### Step 4: Verify CSS Loading
```javascript
// Check in browser console
console.log('CSS loaded:', !!document.querySelector('link[href*="candidate-subscription.css"]'));
```

## 🛠️ Quick Fixes

### Fix 1: Recreate Subscription Page
```php
// Add to functions.php and visit any page once
add_action('init', function() {
    if (!get_option('subscription_page_created')) {
        $page_id = wp_insert_post(array(
            'post_title' => 'Candidate Dashboard - Subscriptions',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'meta_input' => array(
                '_wp_page_template' => 'candidate-dashboard-subscriptions.php'
            )
        ));
        update_option('subscription_page_created', true);
    }
});
```

### Fix 2: Initialize Demo Data
```php
// Add to functions.php and visit any page once
add_action('init', function() {
    if (!get_option('subscription_demo_initialized')) {
        jobster_init_demo_subscription_data();
        update_option('subscription_demo_initialized', true);
    }
});
```

### Fix 3: Force CSS Reload
```php
// Change version number in scripts.php
wp_enqueue_style('candidate-subscription', JOBSTER_PLUGIN_PATH . 'css/candidate-subscription.css', array(), '1.1', 'all');
```

## 📋 Verification Checklist

After implementing fixes, verify:

- [ ] Subscription page loads without 404 error
- [ ] Navigation shows "Subscriptions" link
- [ ] Subscription widget appears on dashboard
- [ ] Badges show in candidate search results
- [ ] CSS styles are applied correctly
- [ ] Subscription status updates save properly
- [ ] Demo data is initialized
- [ ] No PHP errors in debug log
- [ ] No JavaScript errors in browser console

## 🆘 If Nothing Works

1. **Deactivate and reactivate the plugin**
2. **Check file permissions** (should be 644 for files, 755 for directories)
3. **Verify all files are uploaded** correctly
4. **Check PHP version compatibility** (requires PHP 7.0+)
5. **Test with default WordPress theme** to rule out theme conflicts
6. **Check for plugin conflicts** by deactivating other plugins

## 📞 Support Information

If issues persist:
- Check WordPress debug log: `/wp-content/debug.log`
- Enable query debugging: `define('SAVEQUERIES', true);`
- Use WordPress health check: `Tools → Site Health`
- Test in staging environment first

---

*This guide covers the most common issues with the candidate subscription features. Follow the steps systematically to identify and resolve problems.*