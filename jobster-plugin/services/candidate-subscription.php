<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Get candidate subscription status
 */
if (!function_exists('jobster_get_candidate_subscription_status')):
    function jobster_get_candidate_subscription_status($candidate_id) {
        $subscription_status = get_post_meta($candidate_id, 'candidate_subscription_status', true);
        $subscription_plan = get_post_meta($candidate_id, 'candidate_subscription_plan', true);
        $subscription_expiry = get_post_meta($candidate_id, 'candidate_subscription_expiry', true);
        
        $current_time = current_time('timestamp');
        
        // Check if subscription is expired
        if (!empty($subscription_expiry) && $current_time > strtotime($subscription_expiry)) {
            update_post_meta($candidate_id, 'candidate_subscription_status', 'expired');
            $subscription_status = 'expired';
        }
        
        return array(
            'status' => !empty($subscription_status) ? $subscription_status : 'free',
            'plan' => !empty($subscription_plan) ? $subscription_plan : 'free',
            'expiry' => $subscription_expiry
        );
    }
endif;

/**
 * Get candidate subscription badge HTML
 */
if (!function_exists('jobster_get_candidate_subscription_badge')):
    function jobster_get_candidate_subscription_badge($candidate_id, $size = 'small') {
        $subscription = jobster_get_candidate_subscription_status($candidate_id);
        
        $badge_class = 'pxp-subscription-badge';
        $badge_class .= $size === 'large' ? ' pxp-subscription-badge-large' : ' pxp-subscription-badge-small';
        
        $badge_text = '';
        $badge_color = '';
        
        switch ($subscription['status']) {
            case 'active':
                if ($subscription['plan'] === 'premium') {
                    $badge_text = __('Premium', 'jobster');
                    $badge_color = 'pxp-subscription-badge-premium';
                } else {
                    $badge_text = __('Free Plan', 'jobster');
                    $badge_color = 'pxp-subscription-badge-free';
                }
                break;
            case 'expired':
                $badge_text = __('Expired', 'jobster');
                $badge_color = 'pxp-subscription-badge-expired';
                break;
            default:
                $badge_text = __('Free Plan', 'jobster');
                $badge_color = 'pxp-subscription-badge-free';
                break;
        }
        
        return '<span class="' . esc_attr($badge_class . ' ' . $badge_color) . '">' . esc_html($badge_text) . '</span>';
    }
endif;

/**
 * Get candidate subscription features
 */
if (!function_exists('jobster_get_candidate_subscription_features')):
    function jobster_get_candidate_subscription_features($candidate_id) {
        $subscription = jobster_get_candidate_subscription_status($candidate_id);
        
        $features = array(
            'profile_views' => 0,
            'job_alerts' => false,
            'priority_support' => false,
            'featured_profile' => false,
            'unlimited_applications' => false,
            'cv_download' => false
        );
        
        if ($subscription['status'] === 'active' && $subscription['plan'] === 'premium') {
            $features = array(
                'profile_views' => 1000,
                'job_alerts' => true,
                'priority_support' => true,
                'featured_profile' => true,
                'unlimited_applications' => true,
                'cv_download' => true
            );
        } elseif ($subscription['status'] === 'active' && $subscription['plan'] === 'basic') {
            $features = array(
                'profile_views' => 100,
                'job_alerts' => true,
                'priority_support' => false,
                'featured_profile' => false,
                'unlimited_applications' => false,
                'cv_download' => true
            );
        } else {
            // Free plan features
            $features = array(
                'profile_views' => 10,
                'job_alerts' => false,
                'priority_support' => false,
                'featured_profile' => false,
                'unlimited_applications' => false,
                'cv_download' => false
            );
        }
        
        return $features;
    }
endif;

/**
 * Display candidate subscription widget
 */
if (!function_exists('jobster_display_candidate_subscription_widget')):
    function jobster_display_candidate_subscription_widget($candidate_id) {
        $subscription = jobster_get_candidate_subscription_status($candidate_id);
        $features = jobster_get_candidate_subscription_features($candidate_id);
        $subscriptions_link = jobster_get_page_link('candidate-dashboard-subscriptions.php');
        
        $widget_class = 'pxp-subscription-widget';
        $widget_class .= $subscription['status'] === 'active' && $subscription['plan'] === 'premium' ? ' pxp-subscription-widget-premium' : ' pxp-subscription-widget-free';
        ?>
        
        <div class="<?php echo esc_attr($widget_class); ?> mb-4">
            <div class="pxp-subscription-widget-header">
                <h3><?php esc_html_e('Subscription Status', 'jobster'); ?></h3>
                <?php echo jobster_get_candidate_subscription_badge($candidate_id, 'large'); ?>
            </div>
            
            <div class="pxp-subscription-widget-content">
                <?php if ($subscription['status'] === 'active' && $subscription['plan'] === 'premium') { ?>
                    <div class="pxp-subscription-widget-plan">
                        <span class="fa fa-star text-warning"></span>
                        <?php esc_html_e('Premium Plan Active', 'jobster'); ?>
                    </div>
                    <?php if (!empty($subscription['expiry'])) { ?>
                        <div class="pxp-subscription-widget-expiry">
                            <?php esc_html_e('Expires:', 'jobster'); ?> 
                            <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($subscription['expiry']))); ?>
                        </div>
                    <?php } ?>
                <?php } elseif ($subscription['status'] === 'expired') { ?>
                    <div class="pxp-subscription-widget-expired">
                        <span class="fa fa-exclamation-triangle text-danger"></span>
                        <?php esc_html_e('Subscription Expired', 'jobster'); ?>
                    </div>
                    <p class="pxp-text-light">
                        <?php esc_html_e('Renew your subscription to continue enjoying premium features.', 'jobster'); ?>
                    </p>
                <?php } else { ?>
                    <div class="pxp-subscription-widget-free">
                        <span class="fa fa-user text-success"></span>
                        <?php esc_html_e('Free Plan', 'jobster'); ?>
                    </div>
                    <p class="pxp-text-light">
                        <?php esc_html_e('Upgrade to premium for enhanced features and better visibility.', 'jobster'); ?>
                    </p>
                <?php } ?>
                
                <div class="pxp-subscription-widget-features mt-3">
                    <h4><?php esc_html_e('Your Features', 'jobster'); ?></h4>
                    <ul class="list-unstyled">
                        <li>
                            <span class="fa fa-eye"></span>
                            <?php echo esc_html($features['profile_views']); ?> <?php esc_html_e('profile views/month', 'jobster'); ?>
                        </li>
                        <li>
                            <span class="fa fa-<?php echo $features['job_alerts'] ? 'check text-success' : 'times text-danger'; ?>"></span>
                            <?php esc_html_e('Job alerts', 'jobster'); ?>
                        </li>
                        <li>
                            <span class="fa fa-<?php echo $features['priority_support'] ? 'check text-success' : 'times text-danger'; ?>"></span>
                            <?php esc_html_e('Priority support', 'jobster'); ?>
                        </li>
                        <li>
                            <span class="fa fa-<?php echo $features['featured_profile'] ? 'check text-success' : 'times text-danger'; ?>"></span>
                            <?php esc_html_e('Featured profile', 'jobster'); ?>
                        </li>
                    </ul>
                </div>
                
                <?php if ($subscriptions_link && ($subscription['status'] !== 'active' || $subscription['plan'] !== 'premium')) { ?>
                    <div class="pxp-subscription-widget-actions mt-3">
                        <a href="<?php echo esc_url($subscriptions_link); ?>" class="btn btn-primary">
                            <?php if ($subscription['status'] === 'expired') {
                                esc_html_e('Renew Subscription', 'jobster');
                            } else {
                                esc_html_e('Upgrade Now', 'jobster');
                            } ?>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
        
        <?php
    }
endif;

/**
 * Check if candidate has active subscription
 */
if (!function_exists('jobster_candidate_has_active_subscription')):
    function jobster_candidate_has_active_subscription($candidate_id) {
        $subscription = jobster_get_candidate_subscription_status($candidate_id);
        return $subscription['status'] === 'active';
    }
endif;

/**
 * Check if candidate has premium subscription
 */
if (!function_exists('jobster_candidate_has_premium_subscription')):
    function jobster_candidate_has_premium_subscription($candidate_id) {
        $subscription = jobster_get_candidate_subscription_status($candidate_id);
        return $subscription['status'] === 'active' && $subscription['plan'] === 'premium';
    }
endif;