# 🧪 Testing Guide: Candidate Subscription Features

## 🎯 How to Test if Everything is Working

### 1. **Check Admin Interface**

#### Test the Subscription Meta Box:
1. Go to `WordPress Admin → Candidates → Edit any candidate`
2. Look for **"Subscription Management"** meta box in the right sidebar
3. You should see:
   - Current Status display with color indicator
   - Subscription Status dropdown
   - Subscription Plan dropdown  
   - Expiry Date field
   - Profile Views counter
   - Quick Action buttons

#### Test the Candidates List:
1. Go to `WordPress Admin → Candidates → All Candidates`
2. Look for **"Subscription"** column
3. You should see:
   - Color-coded status indicators
   - Status text (Free/Active/Expired)
   - Plan type (if active)
   - Expiry date (if set)

#### Test the Filter:
1. On the candidates list page
2. Look for **"All Subscriptions"** dropdown filter
3. Try filtering by Free/Active/Expired

### 2. **Test Manual Plan Changes**

#### Set a Candidate to Premium:
1. Edit any candidate
2. In "Subscription Management" meta box:
   - Click **"Set Premium (1 Month)"** button
   - Click **"Update"** to save
3. Check if:
   - Status changed to "Active"
   - Plan changed to "Premium"
   - Expiry date was set to 1 month from now

#### Test Downgrade:
1. Edit a premium candidate
2. Click **"Set Free Plan"** button
3. Click **"Update"**
4. Verify status changed to "Free"

### 3. **Test Frontend Display**

#### Check Candidate Dashboard:
1. Login as a candidate
2. Go to candidate dashboard
3. Look for **subscription widget** showing current plan

#### Check Candidate Search:
1. Go to candidate search page
2. Look for **subscription badges** next to candidate names
3. Should see green "Free Plan" or yellow "Premium" badges

#### Check Subscription Page:
1. As a candidate, go to dashboard
2. Click **"Subscriptions"** link in navigation
3. Should see full subscription management page

### 4. **Test Database Storage**

#### Check Meta Fields:
You can verify data is being saved by checking these meta fields for any candidate:
- `candidate_subscription_status` (free/active/expired)
- `candidate_subscription_plan` (free/basic/premium)
- `candidate_subscription_expiry` (datetime)
- `candidate_profile_views` (number)

## 🔧 Troubleshooting

### If Meta Box Doesn't Appear:
1. Check if candidate post type exists
2. Verify plugin is activated
3. Check for PHP errors in WordPress debug log
4. Try deactivating/reactivating plugin

### If Saving Doesn't Work:
1. Check WordPress permissions
2. Verify nonce is working
3. Look for JavaScript errors in browser console
4. Check if candidate_meta_save function is running

### If Functions Don't Exist:
1. Verify all files are uploaded correctly
2. Check if services are included in jobster-plugin.php
3. Try clearing any caching
4. Check for PHP syntax errors

## 📋 Quick Test Checklist

- [ ] Subscription meta box appears on candidate edit page
- [ ] Status dropdown has Free/Active/Expired options
- [ ] Plan dropdown has Free/Basic/Premium options
- [ ] Quick action buttons work
- [ ] Changes save when clicking "Update"
- [ ] Subscription column appears in candidates list
- [ ] Filter dropdown works
- [ ] Frontend subscription widget displays
- [ ] Subscription badges appear in search results
- [ ] Subscription page is accessible from dashboard

## 🎯 Expected Behavior

### Admin Interface:
- ✅ Meta box in right sidebar of candidate edit page
- ✅ Color-coded status indicators (Green/Yellow/Red)
- ✅ Functional dropdown filters
- ✅ Working quick action buttons
- ✅ Data persistence after saving

### Frontend:
- ✅ Subscription widget on candidate dashboard
- ✅ Subscription badges in search results
- ✅ Subscription management page
- ✅ Navigation links to subscription features

### Database:
- ✅ Meta fields properly stored
- ✅ Expiry dates in correct format
- ✅ Status updates reflected immediately

---

*If any of these tests fail, there may be an issue with the implementation that needs to be addressed.*