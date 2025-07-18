<?php
/**
 * Company Subscription Enabler - Automatically enables company subscriptions for testing
 */

// Enable company subscriptions automatically
add_action('admin_init', 'jobster_enable_company_subscriptions');

if (!function_exists('jobster_enable_company_subscriptions')):
    function jobster_enable_company_subscriptions() {
        // Only run once
        if (get_option('jobster_company_subscriptions_enabled')) {
            return;
        }
        
        // Get current membership settings
        $membership_settings = get_option('jobster_membership_settings', array());
        
        // Enable plan-based payment system
        $membership_settings['jobster_payment_type_field'] = 'plan';
        
        // Set default payment system to PayPal for testing
        if (!isset($membership_settings['jobster_payment_system_field'])) {
            $membership_settings['jobster_payment_system_field'] = 'paypal';
        }
        
        // Set default currency
        if (!isset($membership_settings['jobster_paypal_payment_currency_field'])) {
            $membership_settings['jobster_paypal_payment_currency_field'] = 'USD';
        }
        
        // Update settings
        update_option('jobster_membership_settings', $membership_settings);
        
        // Mark as enabled
        update_option('jobster_company_subscriptions_enabled', true);
        
        // Create default membership plans if none exist
        jobster_create_default_membership_plans();
    }
endif;

// Create default membership plans for testing
if (!function_exists('jobster_create_default_membership_plans')):
    function jobster_create_default_membership_plans() {
        // Check if any membership plans exist
        $existing_plans = get_posts(array(
            'post_type' => 'membership',
            'posts_per_page' => 1,
            'post_status' => 'publish'
        ));
        
        if (!empty($existing_plans)) {
            return; // Plans already exist
        }
        
        // Create Free Plan
        $free_plan_id = wp_insert_post(array(
            'post_title' => 'Free Plan',
            'post_content' => 'Basic job posting plan for small companies.',
            'post_status' => 'publish',
            'post_type' => 'membership'
        ));
        
        if ($free_plan_id) {
            update_post_meta($free_plan_id, 'membership_price', '0');
            update_post_meta($free_plan_id, 'membership_listings', '3');
            update_post_meta($free_plan_id, 'membership_featured_listings', '0');
            update_post_meta($free_plan_id, 'membership_unlimited_listings', '');
            update_post_meta($free_plan_id, 'membership_cv_access', '');
            update_post_meta($free_plan_id, 'membership_period', '30');
            update_post_meta($free_plan_id, 'membership_billing_time_unit', 'Days');
            update_post_meta($free_plan_id, 'membership_free', '1');
        }
        
        // Create Basic Plan
        $basic_plan_id = wp_insert_post(array(
            'post_title' => 'Basic Plan',
            'post_content' => 'Standard job posting plan with more listings.',
            'post_status' => 'publish',
            'post_type' => 'membership'
        ));
        
        if ($basic_plan_id) {
            update_post_meta($basic_plan_id, 'membership_price', '29');
            update_post_meta($basic_plan_id, 'membership_listings', '10');
            update_post_meta($basic_plan_id, 'membership_featured_listings', '2');
            update_post_meta($basic_plan_id, 'membership_unlimited_listings', '');
            update_post_meta($basic_plan_id, 'membership_cv_access', '1');
            update_post_meta($basic_plan_id, 'membership_period', '30');
            update_post_meta($basic_plan_id, 'membership_billing_time_unit', 'Days');
            update_post_meta($basic_plan_id, 'membership_free', '');
        }
        
        // Create Premium Plan
        $premium_plan_id = wp_insert_post(array(
            'post_title' => 'Premium Plan',
            'post_content' => 'Unlimited job postings with all features.',
            'post_status' => 'publish',
            'post_type' => 'membership'
        ));
        
        if ($premium_plan_id) {
            update_post_meta($premium_plan_id, 'membership_price', '99');
            update_post_meta($premium_plan_id, 'membership_listings', '100');
            update_post_meta($premium_plan_id, 'membership_featured_listings', '10');
            update_post_meta($premium_plan_id, 'membership_unlimited_listings', '1');
            update_post_meta($premium_plan_id, 'membership_cv_access', '1');
            update_post_meta($premium_plan_id, 'membership_period', '30');
            update_post_meta($premium_plan_id, 'membership_billing_time_unit', 'Days');
            update_post_meta($premium_plan_id, 'membership_free', '');
        }
    }
endif;

// Add admin notice about company subscriptions being enabled
add_action('admin_notices', 'jobster_company_subscriptions_notice');

if (!function_exists('jobster_company_subscriptions_notice')):
    function jobster_company_subscriptions_notice() {
        global $typenow, $pagenow;
        
        // Show notice on company pages or dashboard
        if ($typenow === 'company' || $pagenow === 'admin.php') {
            if (get_option('jobster_company_subscriptions_enabled') && !get_option('jobster_company_subscriptions_notice_dismissed')) {
                ?>
                <div class="notice notice-success is-dismissible" data-dismissible="company-subscriptions-enabled">
                    <p>
                        <strong><?php esc_html_e('Company Subscriptions Enabled!', 'jobster'); ?></strong><br>
                        <?php esc_html_e('Company subscription features are now active. Companies can now access subscription plans in their dashboard.', 'jobster'); ?>
                    </p>
                    <p>
                        <a href="<?php echo esc_url(admin_url('edit.php?post_type=membership')); ?>" class="button button-secondary">
                            <?php esc_html_e('View Membership Plans', 'jobster'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=admin/settings.php&tab=jobster_membership_settings')); ?>" class="button button-secondary">
                            <?php esc_html_e('Configure Payment Settings', 'jobster'); ?>
                        </a>
                    </p>
                </div>
                <script>
                jQuery(document).ready(function($) {
                    $(document).on('click', '.notice-dismiss', function() {
                        if ($(this).closest('.notice').data('dismissible') === 'company-subscriptions-enabled') {
                            $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                                action: 'dismiss_company_subscriptions_notice',
                                nonce: '<?php echo wp_create_nonce('dismiss_notice'); ?>'
                            });
                        }
                    });
                });
                </script>
                <?php
            }
        }
    }
endif;

// Handle notice dismissal
add_action('wp_ajax_dismiss_company_subscriptions_notice', 'jobster_dismiss_company_subscriptions_notice');

if (!function_exists('jobster_dismiss_company_subscriptions_notice')):
    function jobster_dismiss_company_subscriptions_notice() {
        if (wp_verify_nonce($_POST['nonce'], 'dismiss_notice')) {
            update_option('jobster_company_subscriptions_notice_dismissed', true);
        }
        wp_die();
    }
endif;

// Add company subscription status column to companies list
add_filter('manage_company_posts_columns', 'jobster_add_company_subscription_column');
add_action('manage_company_posts_custom_column', 'jobster_display_company_subscription_column', 10, 2);

if (!function_exists('jobster_add_company_subscription_column')):
    function jobster_add_company_subscription_column($columns) {
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

if (!function_exists('jobster_display_company_subscription_column')):
    function jobster_display_company_subscription_column($column, $post_id) {
        if ($column === 'subscription') {
            $company_plan_id = get_post_meta($post_id, 'company_plan', true);
            $plan_listings = get_post_meta($post_id, 'company_plan_listings', true);
            $plan_unlimited = get_post_meta($post_id, 'company_plan_unlimited', true);
            $plan_activation = get_post_meta($post_id, 'company_plan_activation', true);
            
            if ($company_plan_id) {
                $plan_name = get_the_title($company_plan_id);
                $color = '#ffc107'; // Yellow for active plan
                
                echo '<span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: ' . esc_attr($color) . '; margin-right: 5px;"></span>';
                echo '<strong>' . esc_html($plan_name) . '</strong>';
                
                if ($plan_unlimited === '1') {
                    echo '<br><small>' . esc_html__('Unlimited posts', 'jobster') . '</small>';
                } else {
                    echo '<br><small>' . esc_html(sprintf(__('%d posts remaining', 'jobster'), $plan_listings)) . '</small>';
                }
                
                if ($plan_activation) {
                    echo '<br><small>' . esc_html(date_i18n('M j, Y', strtotime($plan_activation))) . '</small>';
                }
            } else {
                echo '<span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #6c757d; margin-right: 5px;"></span>';
                echo '<em>' . esc_html__('No plan', 'jobster') . '</em>';
            }
        }
    }
endif;

// Add subscription management to company meta boxes
add_action('add_meta_boxes', 'jobster_add_company_subscription_metabox');

if (!function_exists('jobster_add_company_subscription_metabox')):
    function jobster_add_company_subscription_metabox() {
        add_meta_box(
            'company-subscription-overview',
            __('Subscription Overview', 'jobster'),
            'jobster_company_subscription_overview_render',
            'company',
            'side',
            'high'
        );
    }
endif;

if (!function_exists('jobster_company_subscription_overview_render')):
    function jobster_company_subscription_overview_render($post) {
        $company_id = $post->ID;
        $company_plan_id = get_post_meta($company_id, 'company_plan', true);
        $plan_listings = get_post_meta($company_id, 'company_plan_listings', true);
        $plan_featured = get_post_meta($company_id, 'company_plan_featured', true);
        $plan_unlimited = get_post_meta($company_id, 'company_plan_unlimited', true);
        $plan_activation = get_post_meta($company_id, 'company_plan_activation', true);
        ?>
        
        <div class="company-subscription-overview">
            <style>
                .company-subscription-overview { padding: 10px 0; }
                .subscription-info { background: #f8f9fa; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
                .subscription-info h4 { margin: 0 0 10px 0; font-size: 14px; }
                .subscription-info p { margin: 5px 0; font-size: 12px; }
                .plan-indicator { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 5px; }
                .plan-active { background-color: #ffc107; }
                .plan-none { background-color: #6c757d; }
            </style>
            
            <div class="subscription-info">
                <h4><?php esc_html_e('Current Plan', 'jobster'); ?></h4>
                <?php if ($company_plan_id) { 
                    $plan_name = get_the_title($company_plan_id); ?>
                    <p>
                        <span class="plan-indicator plan-active"></span>
                        <strong><?php echo esc_html($plan_name); ?></strong>
                    </p>
                    <?php if ($plan_unlimited === '1') { ?>
                        <p><strong><?php esc_html_e('Job Posts:', 'jobster'); ?></strong> <?php esc_html_e('Unlimited', 'jobster'); ?></p>
                    <?php } else { ?>
                        <p><strong><?php esc_html_e('Job Posts:', 'jobster'); ?></strong> <?php echo esc_html($plan_listings); ?> <?php esc_html_e('remaining', 'jobster'); ?></p>
                    <?php } ?>
                    <p><strong><?php esc_html_e('Featured Posts:', 'jobster'); ?></strong> <?php echo esc_html($plan_featured); ?></p>
                    <?php if ($plan_activation) { ?>
                        <p><strong><?php esc_html_e('Active Since:', 'jobster'); ?></strong> <?php echo esc_html(date_i18n('M j, Y', strtotime($plan_activation))); ?></p>
                    <?php } ?>
                <?php } else { ?>
                    <p>
                        <span class="plan-indicator plan-none"></span>
                        <em><?php esc_html_e('No active plan', 'jobster'); ?></em>
                    </p>
                    <p><?php esc_html_e('Company has not selected a membership plan yet.', 'jobster'); ?></p>
                <?php } ?>
            </div>
            
            <p><small><?php esc_html_e('Plans can be assigned in the "Membership & Payment" section below or companies can select plans from their dashboard.', 'jobster'); ?></small></p>
        </div>
        <?php
    }
endif;

// Reset enabler (for testing)
if (isset($_GET['reset_company_subscriptions']) && current_user_can('manage_options')) {
    delete_option('jobster_company_subscriptions_enabled');
    delete_option('jobster_company_subscriptions_notice_dismissed');
    wp_redirect(admin_url('edit.php?post_type=company'));
    exit;
}
?>