<?php
/**
 * Admin Subscription Fix - Ensures admin functionality loads properly
 */

// Ensure this runs on admin_init to guarantee proper loading
add_action('admin_init', 'jobster_subscription_admin_init');

if (!function_exists('jobster_subscription_admin_init')):
    function jobster_subscription_admin_init() {
        // Only run on candidate edit/list pages
        global $pagenow, $typenow;
        
        if ($typenow === 'candidate' || (isset($_GET['post_type']) && $_GET['post_type'] === 'candidate')) {
            // Force load subscription functions if not already loaded
            if (!function_exists('jobster_get_candidate_subscription_status')) {
                require_once plugin_dir_path(__FILE__) . '../services/candidate-subscription.php';
            }
            
            // Add meta box if not already added
            add_action('add_meta_boxes', 'jobster_ensure_subscription_metabox');
            
            // Ensure columns are added
            add_filter('manage_candidate_posts_columns', 'jobster_add_subscription_column_fix');
            add_action('manage_candidate_posts_custom_column', 'jobster_display_subscription_column_fix', 10, 2);
            
            // Add filter dropdown
            add_action('restrict_manage_posts', 'jobster_add_subscription_filter_fix');
            add_action('pre_get_posts', 'jobster_filter_candidates_by_subscription_fix');
        }
    }
endif;

// Ensure subscription meta box is added
if (!function_exists('jobster_ensure_subscription_metabox')):
    function jobster_ensure_subscription_metabox() {
        add_meta_box(
            'candidate-subscription-section',
            __('Subscription Management', 'jobster'),
            'jobster_subscription_metabox_render_fix',
            'candidate',
            'side',
            'high'
        );
    }
endif;

// Fixed meta box render function
if (!function_exists('jobster_subscription_metabox_render_fix')):
    function jobster_subscription_metabox_render_fix($post) {
        $candidate_id = $post->ID;
        
        // Get current subscription data
        $subscription_status = get_post_meta($candidate_id, 'candidate_subscription_status', true) ?: 'free';
        $subscription_plan = get_post_meta($candidate_id, 'candidate_subscription_plan', true) ?: 'free';
        $subscription_expiry = get_post_meta($candidate_id, 'candidate_subscription_expiry', true);
        $profile_views = get_post_meta($candidate_id, 'candidate_profile_views', true) ?: 0;
        
        // Add nonce for security
        wp_nonce_field('jobster_subscription_meta', 'subscription_nonce');
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
                        - <?php echo esc_html(ucfirst($subscription_plan)); ?>
                    <?php } ?>
                </p>
                <?php if (!empty($subscription_expiry)) { ?>
                    <p><strong><?php esc_html_e('Expires:', 'jobster'); ?></strong> <?php echo esc_html(date_i18n('M j, Y', strtotime($subscription_expiry))); ?></p>
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
                    <option value="free" <?php selected($subscription_plan, 'free'); ?>><?php esc_html_e('Free', 'jobster'); ?></option>
                    <option value="basic" <?php selected($subscription_plan, 'basic'); ?>><?php esc_html_e('Basic', 'jobster'); ?></option>
                    <option value="premium" <?php selected($subscription_plan, 'premium'); ?>><?php esc_html_e('Premium', 'jobster'); ?></option>
                </select>
            </div>
            
            <!-- Expiry Date -->
            <div class="subscription-field">
                <label for="candidate_subscription_expiry"><?php esc_html_e('Expiry Date', 'jobster'); ?></label>
                <input type="date" name="candidate_subscription_expiry" id="candidate_subscription_expiry" 
                       value="<?php echo esc_attr(!empty($subscription_expiry) ? date('Y-m-d', strtotime($subscription_expiry)) : ''); ?>">
                <small><?php esc_html_e('Leave empty for no expiry', 'jobster'); ?></small>
            </div>
            
            <!-- Profile Views -->
            <div class="subscription-field">
                <label for="candidate_profile_views"><?php esc_html_e('Profile Views', 'jobster'); ?></label>
                <input type="number" name="candidate_profile_views" id="candidate_profile_views" 
                       value="<?php echo esc_attr($profile_views); ?>" min="0">
            </div>
            
            <!-- Quick Actions -->
            <div class="subscription-actions">
                <button type="button" class="button button-secondary" onclick="setSubscription('premium', '<?php echo date('Y-m-d', strtotime('+1 month')); ?>')">
                    <?php esc_html_e('Set Premium (1 Month)', 'jobster'); ?>
                </button>
                <button type="button" class="button button-secondary" onclick="setSubscription('free', '')">
                    <?php esc_html_e('Set Free Plan', 'jobster'); ?>
                </button>
            </div>
            
            <script>
                function setSubscription(plan, expiry) {
                    document.getElementById('candidate_subscription_plan').value = plan;
                    document.getElementById('candidate_subscription_expiry').value = expiry;
                    
                    if (plan === 'premium') {
                        document.getElementById('candidate_subscription_status').value = 'active';
                    } else {
                        document.getElementById('candidate_subscription_status').value = 'free';
                    }
                }
            </script>
        </div>
        <?php
    }
endif;

// Fixed column addition
if (!function_exists('jobster_add_subscription_column_fix')):
    function jobster_add_subscription_column_fix($columns) {
        $new_columns = array();
        
        // Add subscription column after title
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'title') {
                $new_columns['subscription'] = __('Subscription', 'jobster');
            }
        }
        
        return $new_columns;
    }
endif;

// Fixed column display
if (!function_exists('jobster_display_subscription_column_fix')):
    function jobster_display_subscription_column_fix($column, $post_id) {
        if ($column === 'subscription') {
            $subscription_status = get_post_meta($post_id, 'candidate_subscription_status', true) ?: 'free';
            $subscription_plan = get_post_meta($post_id, 'candidate_subscription_plan', true) ?: 'free';
            $subscription_expiry = get_post_meta($post_id, 'candidate_subscription_expiry', true);
            
            $status_colors = array(
                'free' => '#28a745',
                'active' => '#ffc107',
                'expired' => '#dc3545'
            );
            
            $color = isset($status_colors[$subscription_status]) ? $status_colors[$subscription_status] : '#6c757d';
            
            echo '<span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: ' . esc_attr($color) . '; margin-right: 5px;"></span>';
            echo '<strong>' . esc_html(ucfirst($subscription_status)) . '</strong>';
            
            if ($subscription_status === 'active') {
                echo '<br><small>' . esc_html(ucfirst($subscription_plan)) . '</small>';
            }
            
            if (!empty($subscription_expiry)) {
                echo '<br><small>' . esc_html(date_i18n('M j, Y', strtotime($subscription_expiry))) . '</small>';
            }
        }
    }
endif;

// Fixed filter dropdown
if (!function_exists('jobster_add_subscription_filter_fix')):
    function jobster_add_subscription_filter_fix() {
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
    }
endif;

// Fixed filter functionality
if (!function_exists('jobster_filter_candidates_by_subscription_fix')):
    function jobster_filter_candidates_by_subscription_fix($query) {
        global $pagenow, $typenow;
        
        if ($pagenow === 'edit.php' && $typenow === 'candidate' && isset($_GET['subscription_status']) && !empty($_GET['subscription_status'])) {
            $subscription_status = sanitize_text_field($_GET['subscription_status']);
            
            $meta_query = $query->get('meta_query') ?: array();
            $meta_query[] = array(
                'key' => 'candidate_subscription_status',
                'value' => $subscription_status,
                'compare' => '='
            );
            
            $query->set('meta_query', $meta_query);
        }
    }
endif;

// Ensure subscription meta is saved
add_action('save_post', 'jobster_save_subscription_meta_fix');

if (!function_exists('jobster_save_subscription_meta_fix')):
    function jobster_save_subscription_meta_fix($post_id) {
        // Check if this is a candidate post
        if (get_post_type($post_id) !== 'candidate') {
            return;
        }
        
        // Check nonce
        if (!isset($_POST['subscription_nonce']) || !wp_verify_nonce($_POST['subscription_nonce'], 'jobster_subscription_meta')) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
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
    }
endif;

// Initialize demo data if needed
add_action('admin_init', 'jobster_maybe_init_subscription_demo');

if (!function_exists('jobster_maybe_init_subscription_demo')):
    function jobster_maybe_init_subscription_demo() {
        if (isset($_GET['init_subscription_demo']) && current_user_can('manage_options')) {
            // Initialize demo data
            $candidates = get_posts(array(
                'post_type' => 'candidate',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            ));
            
            foreach ($candidates as $candidate) {
                $rand = rand(1, 100);
                
                if ($rand <= 30) {
                    // 30% Premium
                    update_post_meta($candidate->ID, 'candidate_subscription_status', 'active');
                    update_post_meta($candidate->ID, 'candidate_subscription_plan', 'premium');
                    update_post_meta($candidate->ID, 'candidate_subscription_expiry', date('Y-m-d H:i:s', strtotime('+1 month')));
                    update_post_meta($candidate->ID, 'candidate_profile_views', rand(100, 500));
                } elseif ($rand <= 50) {
                    // 20% Expired
                    update_post_meta($candidate->ID, 'candidate_subscription_status', 'expired');
                    update_post_meta($candidate->ID, 'candidate_subscription_plan', 'premium');
                    update_post_meta($candidate->ID, 'candidate_subscription_expiry', date('Y-m-d H:i:s', strtotime('-1 week')));
                    update_post_meta($candidate->ID, 'candidate_profile_views', rand(50, 200));
                } else {
                    // 50% Free
                    update_post_meta($candidate->ID, 'candidate_subscription_status', 'free');
                    update_post_meta($candidate->ID, 'candidate_subscription_plan', 'free');
                    delete_post_meta($candidate->ID, 'candidate_subscription_expiry');
                    update_post_meta($candidate->ID, 'candidate_profile_views', rand(0, 50));
                }
            }
            
            wp_redirect(admin_url('edit.php?post_type=candidate&demo_initialized=1'));
            exit;
        }
    }
endif;

// Add admin notice for demo initialization
add_action('admin_notices', 'jobster_subscription_admin_notices');

if (!function_exists('jobster_subscription_admin_notices')):
    function jobster_subscription_admin_notices() {
        global $typenow;
        
        if ($typenow === 'candidate') {
            if (isset($_GET['demo_initialized'])) {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php esc_html_e('Subscription demo data has been initialized successfully!', 'jobster'); ?></p>
                </div>
                <?php
            }
            
            // Check if we have any candidates without subscription data
            $candidates_without_data = get_posts(array(
                'post_type' => 'candidate',
                'posts_per_page' => 1,
                'meta_query' => array(
                    array(
                        'key' => 'candidate_subscription_status',
                        'compare' => 'NOT EXISTS'
                    )
                )
            ));
            
            if (!empty($candidates_without_data)) {
                ?>
                <div class="notice notice-info">
                    <p>
                        <?php esc_html_e('Some candidates don\'t have subscription data.', 'jobster'); ?>
                        <a href="<?php echo esc_url(admin_url('edit.php?post_type=candidate&init_subscription_demo=1')); ?>" class="button button-secondary">
                            <?php esc_html_e('Initialize Demo Data', 'jobster'); ?>
                        </a>
                    </p>
                </div>
                <?php
            }
        }
    }
endif;
?>