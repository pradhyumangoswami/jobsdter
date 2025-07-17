<?php
/*
Template Name: Candidate Dashboard - Subscriptions
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $current_user;
global $candidate_id;
global $post;

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

$current_user = wp_get_current_user();

$is_candidate = jobster_user_is_candidate($current_user->ID);
if ($is_candidate) {
    $candidate_id = jobster_get_candidate_by_userid($current_user->ID);
} else {
    wp_redirect(home_url());
}

get_header('dashboard', array('bg_color' => 'pxpSecondaryColorLight'));

jobster_get_candidate_dashboard_side($candidate_id, 'subscriptions'); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_candidate_dashboard_top($candidate_id); ?>

    <div class="pxp-dashboard-content-details">
        <h1><?php esc_html_e('Subscriptions', 'jobster'); ?></h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Manage your subscription plans and features.', 'jobster'); ?>
        </p>

        <?php 
        // Display current subscription status
        $subscription = jobster_get_candidate_subscription_status($candidate_id);
        $features = jobster_get_candidate_subscription_features($candidate_id);
        ?>

        <div class="row mt-4 mt-lg-5">
            <div class="col-md-8">
                <div class="pxp-dashboard-content-box">
                    <h2><?php esc_html_e('Current Subscription', 'jobster'); ?></h2>
                    
                    <?php if ($subscription['status'] === 'active' && $subscription['plan'] === 'premium') { ?>
                        <div class="alert alert-success">
                            <span class="fa fa-check-circle"></span>
                            <?php esc_html_e('You have an active Premium subscription!', 'jobster'); ?>
                            <?php if (!empty($subscription['expiry'])) { ?>
                                <br><small>
                                    <?php esc_html_e('Expires:', 'jobster'); ?> 
                                    <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($subscription['expiry']))); ?>
                                </small>
                            <?php } ?>
                        </div>
                    <?php } elseif ($subscription['status'] === 'expired') { ?>
                        <div class="alert alert-warning">
                            <span class="fa fa-exclamation-triangle"></span>
                            <?php esc_html_e('Your subscription has expired. Please renew to continue enjoying premium features.', 'jobster'); ?>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-info">
                            <span class="fa fa-info-circle"></span>
                            <?php esc_html_e('You are currently on the Free plan. Upgrade to Premium for enhanced features!', 'jobster'); ?>
                        </div>
                    <?php } ?>

                    <div class="pxp-subscription-current-features mt-4">
                        <h3><?php esc_html_e('Your Current Features', 'jobster'); ?></h3>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="fa fa-eye text-primary"></span>
                                        <strong><?php echo esc_html($features['profile_views']); ?></strong> <?php esc_html_e('profile views/month', 'jobster'); ?>
                                    </li>
                                    <li class="mb-2">
                                        <span class="fa fa-<?php echo $features['job_alerts'] ? 'check text-success' : 'times text-danger'; ?>"></span>
                                        <?php esc_html_e('Job alerts', 'jobster'); ?>
                                    </li>
                                    <li class="mb-2">
                                        <span class="fa fa-<?php echo $features['priority_support'] ? 'check text-success' : 'times text-danger'; ?>"></span>
                                        <?php esc_html_e('Priority support', 'jobster'); ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="fa fa-<?php echo $features['featured_profile'] ? 'check text-success' : 'times text-danger'; ?>"></span>
                                        <?php esc_html_e('Featured profile', 'jobster'); ?>
                                    </li>
                                    <li class="mb-2">
                                        <span class="fa fa-<?php echo $features['unlimited_applications'] ? 'check text-success' : 'times text-danger'; ?>"></span>
                                        <?php esc_html_e('Unlimited applications', 'jobster'); ?>
                                    </li>
                                    <li class="mb-2">
                                        <span class="fa fa-<?php echo $features['cv_download'] ? 'check text-success' : 'times text-danger'; ?>"></span>
                                        <?php esc_html_e('CV download', 'jobster'); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription Plans -->
                <div class="pxp-dashboard-content-box mt-4">
                    <h2><?php esc_html_e('Available Plans', 'jobster'); ?></h2>
                    
                    <div class="row">
                        <!-- Free Plan -->
                        <div class="col-md-6 mb-4">
                            <div class="pxp-subscription-plan-card <?php echo $subscription['status'] !== 'active' || $subscription['plan'] === 'free' ? 'pxp-subscription-plan-current' : ''; ?>">
                                <div class="pxp-subscription-plan-header">
                                    <h3><?php esc_html_e('Free Plan', 'jobster'); ?></h3>
                                    <div class="pxp-subscription-plan-price">
                                        <span class="pxp-subscription-plan-currency">$</span>
                                        <span class="pxp-subscription-plan-amount">0</span>
                                        <span class="pxp-subscription-plan-period">/month</span>
                                    </div>
                                </div>
                                <div class="pxp-subscription-plan-features">
                                    <ul class="list-unstyled">
                                        <li><span class="fa fa-check text-success"></span> 10 profile views/month</li>
                                        <li><span class="fa fa-times text-danger"></span> Job alerts</li>
                                        <li><span class="fa fa-times text-danger"></span> Priority support</li>
                                        <li><span class="fa fa-times text-danger"></span> Featured profile</li>
                                        <li><span class="fa fa-times text-danger"></span> Unlimited applications</li>
                                        <li><span class="fa fa-times text-danger"></span> CV download</li>
                                    </ul>
                                </div>
                                <div class="pxp-subscription-plan-action">
                                    <?php if ($subscription['status'] !== 'active' || $subscription['plan'] === 'free') { ?>
                                        <button class="btn btn-outline-primary" disabled>
                                            <?php esc_html_e('Current Plan', 'jobster'); ?>
                                        </button>
                                    <?php } else { ?>
                                        <button class="btn btn-outline-secondary" onclick="jobster_downgrade_subscription(<?php echo esc_attr($candidate_id); ?>)">
                                            <?php esc_html_e('Downgrade', 'jobster'); ?>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Premium Plan -->
                        <div class="col-md-6 mb-4">
                            <div class="pxp-subscription-plan-card pxp-subscription-plan-premium <?php echo $subscription['status'] === 'active' && $subscription['plan'] === 'premium' ? 'pxp-subscription-plan-current' : ''; ?>">
                                <div class="pxp-subscription-plan-header">
                                    <div class="pxp-subscription-plan-badge">
                                        <?php esc_html_e('Most Popular', 'jobster'); ?>
                                    </div>
                                    <h3><?php esc_html_e('Premium Plan', 'jobster'); ?></h3>
                                    <div class="pxp-subscription-plan-price">
                                        <span class="pxp-subscription-plan-currency">$</span>
                                        <span class="pxp-subscription-plan-amount">29</span>
                                        <span class="pxp-subscription-plan-period">/month</span>
                                    </div>
                                </div>
                                <div class="pxp-subscription-plan-features">
                                    <ul class="list-unstyled">
                                        <li><span class="fa fa-check text-success"></span> 1000 profile views/month</li>
                                        <li><span class="fa fa-check text-success"></span> Job alerts</li>
                                        <li><span class="fa fa-check text-success"></span> Priority support</li>
                                        <li><span class="fa fa-check text-success"></span> Featured profile</li>
                                        <li><span class="fa fa-check text-success"></span> Unlimited applications</li>
                                        <li><span class="fa fa-check text-success"></span> CV download</li>
                                    </ul>
                                </div>
                                <div class="pxp-subscription-plan-action">
                                    <?php if ($subscription['status'] === 'active' && $subscription['plan'] === 'premium') { ?>
                                        <button class="btn btn-primary" disabled>
                                            <?php esc_html_e('Current Plan', 'jobster'); ?>
                                        </button>
                                    <?php } else { ?>
                                        <button class="btn btn-primary" onclick="jobster_upgrade_subscription(<?php echo esc_attr($candidate_id); ?>, 'premium')">
                                            <?php if ($subscription['status'] === 'expired') {
                                                esc_html_e('Renew Now', 'jobster');
                                            } else {
                                                esc_html_e('Upgrade Now', 'jobster');
                                            } ?>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Subscription Status Widget -->
                <?php jobster_display_candidate_subscription_widget($candidate_id); ?>

                <!-- Quick Stats -->
                <div class="pxp-dashboard-content-box">
                    <h3><?php esc_html_e('Quick Stats', 'jobster'); ?></h3>
                    <div class="pxp-subscription-stats">
                        <div class="pxp-subscription-stat">
                            <div class="pxp-subscription-stat-number">
                                <?php echo esc_html(get_post_meta($candidate_id, 'candidate_profile_views', true) ?: 0); ?>
                            </div>
                            <div class="pxp-subscription-stat-label">
                                <?php esc_html_e('Profile Views This Month', 'jobster'); ?>
                            </div>
                        </div>
                        <div class="pxp-subscription-stat">
                            <div class="pxp-subscription-stat-number">
                                <?php echo esc_html(jobster_get_apps_no_by_candidate_id($candidate_id)); ?>
                            </div>
                            <div class="pxp-subscription-stat-label">
                                <?php esc_html_e('Total Applications', 'jobster'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function jobster_upgrade_subscription(candidate_id, plan) {
    // Redirect to payment processing or show payment modal
    if (confirm('<?php esc_html_e('Proceed with subscription upgrade?', 'jobster'); ?>')) {
        window.location.href = '<?php echo esc_url(admin_url('admin-post.php')); ?>?action=jobster_process_subscription_upgrade&candidate_id=' + candidate_id + '&plan=' + plan;
    }
}

function jobster_downgrade_subscription(candidate_id) {
    if (confirm('<?php esc_html_e('Are you sure you want to downgrade to the free plan?', 'jobster'); ?>')) {
        window.location.href = '<?php echo esc_url(admin_url('admin-post.php')); ?>?action=jobster_process_subscription_downgrade&candidate_id=' + candidate_id;
    }
}
</script>

<?php get_footer('dashboard'); ?>