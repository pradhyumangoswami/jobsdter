# 🏢 Company Subscription System Analysis

## 🔍 **Issue Identified**

The company subscription features are not showing because **they work differently from candidate subscriptions**. Here's the complete breakdown:

## 🔧 **How Company Subscriptions Work vs. Candidate Subscriptions**

### **Candidate Subscriptions** (What We Implemented)
- ✅ **Individual subscription status** per candidate
- ✅ **Direct subscription management** (Free/Premium/Expired)
- ✅ **Simple badge system** with status indicators
- ✅ **Profile-based features** (profile views, job alerts, etc.)
- ✅ **Always visible** in admin and frontend

### **Company Subscriptions** (Existing System)
- 🏢 **Membership plan-based system**
- 🏢 **Requires admin configuration** to enable
- 🏢 **Job posting quota system** (listings, featured posts)
- 🏢 **Payment processing integration** (PayPal/Stripe)
- 🏢 **Only shows when `payment_type = 'plan'`**

## 🚨 **Why Company Subscriptions Don't Show**

### **Primary Issue: Payment Type Configuration**

The company subscription interface is controlled by this condition:
```php
if ($subscriptions_link != '' && $payment_type == 'plan') {
    // Show subscription link
}
```

**Current Status**: `payment_type` is likely set to `'disabled'` or `''` (empty)

**Required Setting**: `payment_type` must be set to `'plan'`

### **Where This Setting is Controlled**

**Location**: `WordPress Admin → Jobster → Membership`
**Setting**: "Payment Type" dropdown
**Options**:
- `disabled` - No payment system (default)
- `listing` - Pay per job posting
- `plan` - Membership plan ✅ **(Required for company subscriptions)**

## 🛠️ **How to Enable Company Subscriptions**

### **Step 1: Configure Payment Type**
1. Go to `WordPress Admin → Jobster → Membership`
2. Set "Payment Type" to `Membership plan`
3. Configure payment system (PayPal or Stripe)
4. Save settings

### **Step 2: Create Membership Plans**
1. Go to `WordPress Admin → Membership Plans`
2. Create plans with:
   - Job posting quotas
   - Featured job limits
   - CV access permissions
   - Pricing information

### **Step 3: Test Company Dashboard**
1. Company subscription link should now appear
2. Companies can select and purchase plans
3. Job posting limits are enforced

## 📊 **Company vs. Candidate Subscription Features**

| Feature | Candidates | Companies |
|---------|------------|-----------|
| **System Type** | Status-based | Plan-based |
| **Admin Control** | Always visible | Requires payment_type='plan' |
| **Features** | Profile views, alerts | Job postings, featured jobs |
| **Payment** | Direct subscription | Membership plans |
| **Configuration** | Auto-enabled | Requires admin setup |

## 🔧 **Technical Differences**

### **Candidate System Uses:**
- `candidate_subscription_status` (free/active/expired)
- `candidate_subscription_plan` (free/basic/premium)
- `candidate_subscription_expiry` (date)
- Simple status management

### **Company System Uses:**
- `company_plan` (membership plan ID)
- `company_plan_listings` (job posting quota)
- `company_plan_featured` (featured job quota)
- `company_plan_unlimited` (unlimited posting flag)
- `company_plan_activation` (activation date)
- Complex quota and billing management

## 🎯 **Solutions for Company Subscriptions**

### **Option 1: Enable Existing System** (Recommended)
1. **Configure payment type** to "Membership plan"
2. **Set up PayPal/Stripe** credentials
3. **Create membership plans** with quotas
4. **Test company dashboard** functionality

### **Option 2: Create Candidate-Style System for Companies**
1. **Create company subscription service** files
2. **Add company subscription meta boxes** to admin
3. **Implement simple status management** like candidates
4. **Add company subscription widgets** to dashboard

### **Option 3: Hybrid Approach**
1. **Keep existing plan system** for job quotas
2. **Add simple status indicators** for company profiles
3. **Enhance admin interface** with subscription management
4. **Integrate both systems** seamlessly

## 🔍 **Files That Control Company Subscriptions**

### **Configuration Files:**
- `admin/sections/membership.php` - Payment type settings
- `admin/settings.php` - Main settings registration

### **Template Files:**
- `page-templates/company-dashboard-subscriptions.php` - Subscription page
- `views/company-dashboard-side.php` - Navigation (conditional)
- `views/company-dashboard-top.php` - Mobile navigation (conditional)

### **Service Files:**
- `services/paypal.php` - PayPal payment processing
- `services/stripe.php` - Stripe payment processing
- `post-types/membership.php` - Membership plan post type

### **Logic Files:**
- `post-types/company.php` - Company meta boxes (includes plan assignment)
- `services/save-job.php` - Job posting quota enforcement

## ✅ **Quick Fix: Enable Company Subscriptions**

### **Method 1: Admin Interface**
1. Navigate to `WordPress Admin → Jobster → Membership`
2. Change "Payment Type" from "Disabled" to "Membership plan"
3. Save settings
4. Company subscription links will appear

### **Method 2: Database Direct**
```php
// Add to functions.php temporarily
add_action('init', function() {
    $membership_settings = get_option('jobster_membership_settings', array());
    $membership_settings['jobster_payment_type_field'] = 'plan';
    update_option('jobster_membership_settings', $membership_settings);
});
```

### **Method 3: Code Override**
```php
// Add to functions.php for testing
add_filter('option_jobster_membership_settings', function($settings) {
    $settings['jobster_payment_type_field'] = 'plan';
    return $settings;
});
```

## 🚀 **Expected Results After Enabling**

### **In Company Dashboard:**
- ✅ "Subscriptions" link appears in navigation
- ✅ Subscription page shows membership plans
- ✅ Companies can select and purchase plans
- ✅ Job posting quotas are enforced

### **In Admin:**
- ✅ Company edit page shows plan assignment
- ✅ Membership plans post type is available
- ✅ Payment processing is enabled

## 📝 **Next Steps**

1. **Configure payment type** in Jobster settings
2. **Set up payment system** (PayPal or Stripe)
3. **Create membership plans** with appropriate quotas
4. **Test the complete flow** from plan selection to job posting
5. **Consider implementing candidate-style features** for companies if needed

---

*The company subscription system is fully implemented but requires proper configuration to be visible. The main difference from candidate subscriptions is that it's plan-based rather than status-based and requires admin setup to enable.*