<?php
/**
 * Admin interface for managing candidate subscriptions
 */

// Add subscription meta box to candidate edit page
add_action('add_meta_boxes', function() {
    add_meta_box(
        'candidate-subscription-section',
        __('Subscription Management', 'jobster'),
        'jobster_subscription_metabox_render',
        'candidate',
        'side',
        'high'
    );
});

// Render subscription meta box
function jobster_subscription_metabox_render($post) {
    $candidate_id = $post->ID;
    
    // Get current subscription data
    $subscription_status = get_post_meta($candidate_id, 'candidate_subscription_status', true) ?: 'free';
    $subscription_plan = get_post_meta($candidate_id, 'candidate_subscription_plan', true) ?: 'free';
    $subscription_expiry = get_post_meta($candidate_id, 'candidate_subscription_expiry', true);
    $profile_views = get_post_meta($candidate_id, 'candidate_profile_views', true) ?: 0;
    
    wp_nonce_field('candidate_subscription_nonce', 'candidate_subscription_nonce_field');
    ?>
    
    <div class="candidate-subscription-admin">
        <style>
            .candidate-subscription-admin { padding: 10px 0; }
            .subscription-field { margin-bottom: 15px; }
            .subscription-field label { display: block; font-weight: bold; margin-bottom: 5px; }
            .subscription-field select, .subscription-field input { width: 100%; }
            .subscription-status-indicator { 
                display: inline-block; 
                width: 10px; 
                height: 10px; 
                border-radius: 50%; 
                margin-right: 5px; 
            }
            .status-free { background-color: #28a745; }
            .status-active { background-color: #ffc107; }
            .status-expired { background-color: #dc3545; }
            .subscription-actions { margin-top: 15px; }
            .subscription-actions button { margin-right: 10px; margin-bottom: 5px; }
            .subscription-info { background: #f8f9fa; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
            .subscription-info h4 { margin: 0 0 10px 0; font-size: 14px; }
            .subscription-info p { margin: 5px 0; font-size: 12px; }
        </style>
        
        <!-- Current Status Display -->
        <div class="subscription-info">
            <h4><?php esc_html_e('Current Status', 'jobster'); ?></h4>
            <p>
                <span class="subscription-status-indicator status-<?php echo esc_attr($subscription_status); ?>"></span>
                <strong><?php echo esc_html(ucfirst($subscription_status)); ?></strong>
                <?php if ($subscription_status === 'active') { ?>
                    (<?php echo esc_html(ucfirst($subscription_plan)); ?>)
                <?php } ?>
            </p>
            <?php if (!empty($subscription_expiry)) { ?>
                <p><strong><?php esc_html_e('Expires:', 'jobster'); ?></strong> <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($subscription_expiry))); ?></p>
            <?php } ?>
            <p><strong><?php esc_html_e('Profile Views:', 'jobster'); ?></strong> <?php echo esc_html($profile_views); ?></p>
        </div>
        
        <!-- Subscription Status -->
        <div class="subscription-field">
            <label for="candidate_subscription_status"><?php esc_html_e('Subscription Status', 'jobster'); ?></label>
            <select name="candidate_subscription_status" id="candidate_subscription_status">
                <option value="free" <?php selected($subscription_status, 'free'); ?>><?php esc_html_e('Free', 'jobster'); ?></option>
                <option value="active" <?php selected($subscription_status, 'active'); ?>><?php esc_html_e('Active', 'jobster'); ?></option>
                <option value="expired" <?php selected($subscription_status, 'expired'); ?>><?php esc_html_e('Expired', 'jobster'); ?></option>
            </select>
        </div>
        
        <!-- Subscription Plan -->
        <div class="subscription-field">
            <label for="candidate_subscription_plan"><?php esc_html_e('Subscription Plan', 'jobster'); ?></label>
            <select name="candidate_subscription_plan" id="candidate_subscription_plan">
                <option value="free" <?php selected($subscription_plan, 'free'); ?>><?php esc_html_e('Free Plan', 'jobster'); ?></option>
                <option value="basic" <?php selected($subscription_plan, 'basic'); ?>><?php esc_html_e('Basic Plan', 'jobster'); ?></option>
                <option value="premium" <?php selected($subscription_plan, 'premium'); ?>><?php esc_html_e('Premium Plan', 'jobster'); ?></option>
            </select>
        </div>
        
        <!-- Expiry Date -->
        <div class="subscription-field">
            <label for="candidate_subscription_expiry"><?php esc_html_e('Expiry Date', 'jobster'); ?></label>
            <input 
                type="datetime-local" 
                name="candidate_subscription_expiry" 
                id="candidate_subscription_expiry" 
                value="<?php echo esc_attr($subscription_expiry ? date('Y-m-d\TH:i', strtotime($subscription_expiry)) : ''); ?>"
            />
            <small><?php esc_html_e('Leave empty for no expiry', 'jobster'); ?></small>
        </div>
        
        <!-- Profile Views -->
        <div class="subscription-field">
            <label for="candidate_profile_views"><?php esc_html_e('Profile Views Count', 'jobster'); ?></label>
            <input 
                type="number" 
                name="candidate_profile_views" 
                id="candidate_profile_views" 
                value="<?php echo esc_attr($profile_views); ?>"
                min="0"
            />
        </div>
        
        <!-- Quick Actions -->
        <div class="subscription-actions">
            <h4><?php esc_html_e('Quick Actions', 'jobster'); ?></h4>
            <button type="button" class="button button-small" onclick="setPremiumPlan()"><?php esc_html_e('Set Premium (1 Month)', 'jobster'); ?></button>
            <button type="button" class="button button-small" onclick="setFreePlan()"><?php esc_html_e('Set Free Plan', 'jobster'); ?></button>
            <button type="button" class="button button-small" onclick="extendSubscription()"><?php esc_html_e('Extend +1 Month', 'jobster'); ?></button>
            <button type="button" class="button button-small" onclick="resetProfileViews()"><?php esc_html_e('Reset Profile Views', 'jobster'); ?></button>
        </div>
    </div>
    
    <script>
    function setPremiumPlan() {
        document.getElementById('candidate_subscription_status').value = 'active';
        document.getElementById('candidate_subscription_plan').value = 'premium';
        
        // Set expiry to 1 month from now
        var now = new Date();
        now.setMonth(now.getMonth() + 1);
        var expiry = now.toISOString().slice(0, 16);
        document.getElementById('candidate_subscription_expiry').value = expiry;
    }
    
    function setFreePlan() {
        document.getElementById('candidate_subscription_status').value = 'free';
        document.getElementById('candidate_subscription_plan').value = 'free';
        document.getElementById('candidate_subscription_expiry').value = '';
    }
    
    function extendSubscription() {
        var currentExpiry = document.getElementById('candidate_subscription_expiry').value;
        var baseDate = currentExpiry ? new Date(currentExpiry) : new Date();
        baseDate.setMonth(baseDate.getMonth() + 1);
        var newExpiry = baseDate.toISOString().slice(0, 16);
        document.getElementById('candidate_subscription_expiry').value = newExpiry;
    }
    
    function resetProfileViews() {
        document.getElementById('candidate_profile_views').value = '0';
    }
    </script>
    
    <?php
}

// Save subscription meta box data
add_action('save_post', function($post_id) {
    // Security checks
    if (!isset($_POST['candidate_subscription_nonce_field']) || 
        !wp_verify_nonce($_POST['candidate_subscription_nonce_field'], 'candidate_subscription_nonce')) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (get_post_type($post_id) !== 'candidate') return;
    
    // Save subscription data
    if (isset($_POST['candidate_subscription_status'])) {
        update_post_meta($post_id, 'candidate_subscription_status', sanitize_text_field($_POST['candidate_subscription_status']));
    }
    
    if (isset($_POST['candidate_subscription_plan'])) {
        update_post_meta($post_id, 'candidate_subscription_plan', sanitize_text_field($_POST['candidate_subscription_plan']));
    }
    
    if (isset($_POST['candidate_subscription_expiry'])) {
        $expiry = sanitize_text_field($_POST['candidate_subscription_expiry']);
        if (!empty($expiry)) {
            update_post_meta($post_id, 'candidate_subscription_expiry', date('Y-m-d H:i:s', strtotime($expiry)));
        } else {
            delete_post_meta($post_id, 'candidate_subscription_expiry');
        }
    }
    
    if (isset($_POST['candidate_profile_views'])) {
        update_post_meta($post_id, 'candidate_profile_views', intval($_POST['candidate_profile_views']));
    }
});

// Add subscription column to candidates list
add_filter('manage_candidate_posts_columns', function($columns) {
    $columns['subscription'] = __('Subscription', 'jobster');
    return $columns;
});

// Display subscription column content
add_action('manage_candidate_posts_custom_column', function($column, $post_id) {
    if ($column === 'subscription') {
        $subscription = jobster_get_candidate_subscription_status($post_id);
        
        $status_colors = array(
            'free' => '#28a745',
            'active' => '#ffc107',
            'expired' => '#dc3545'
        );
        
        $color = isset($status_colors[$subscription['status']]) ? $status_colors[$subscription['status']] : '#6c757d';
        
        echo '<span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: ' . esc_attr($color) . '; margin-right: 5px;"></span>';
        echo '<strong>' . esc_html(ucfirst($subscription['status'])) . '</strong>';
        
        if ($subscription['status'] === 'active') {
            echo '<br><small>' . esc_html(ucfirst($subscription['plan'])) . '</small>';
        }
        
        if (!empty($subscription['expiry'])) {
            echo '<br><small>' . esc_html(date_i18n('M j, Y', strtotime($subscription['expiry']))) . '</small>';
        }
    }
}, 10, 2);

// Add subscription filter to candidates list
add_action('restrict_manage_posts', function() {
    global $typenow;
    
    if ($typenow === 'candidate') {
        $selected = isset($_GET['subscription_status']) ? $_GET['subscription_status'] : '';
        ?>
        <select name="subscription_status">
            <option value=""><?php esc_html_e('All Subscriptions', 'jobster'); ?></option>
            <option value="free" <?php selected($selected, 'free'); ?>><?php esc_html_e('Free', 'jobster'); ?></option>
            <option value="active" <?php selected($selected, 'active'); ?>><?php esc_html_e('Active', 'jobster'); ?></option>
            <option value="expired" <?php selected($selected, 'expired'); ?>><?php esc_html_e('Expired', 'jobster'); ?></option>
        </select>
        <?php
    }
});

// Filter candidates by subscription status
add_action('pre_get_posts', function($query) {
    global $pagenow, $typenow;
    
    if ($pagenow === 'edit.php' && $typenow === 'candidate' && isset($_GET['subscription_status']) && !empty($_GET['subscription_status'])) {
        $subscription_status = sanitize_text_field($_GET['subscription_status']);
        
        $query->set('meta_query', array(
            array(
                'key' => 'candidate_subscription_status',
                'value' => $subscription_status,
                'compare' => '='
            )
        ));
    }
});
?>