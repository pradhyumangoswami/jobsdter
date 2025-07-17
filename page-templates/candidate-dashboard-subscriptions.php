<?php
/**
 * @package WordPress
 * @subpackage Jobster
 * Template Name: Candidate Dashboard - Subscriptions
 */

get_header();
jobster_get_page_header(get_the_ID());

$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$is_candidate = jobster_user_is_candidate($user_id);

if ($is_candidate) {
    $candidate_id = jobster_get_candidate_by_userid($user_id);
    $candidate_plan_id = get_post_meta($candidate_id, 'candidate_plan', true);
    $candidate_plan_activation = get_post_meta($candidate_id, 'candidate_plan_activation', true);
    $candidate_plan_free = get_post_meta($candidate_id, 'candidate_plan_free', true);
    $candidate_plan_unlimited = get_post_meta($candidate_id, 'candidate_plan_unlimited', true);
    $candidate_plan_listings = get_post_meta($candidate_id, 'candidate_plan_listings', true);
    $candidate_plan_featured = get_post_meta($candidate_id, 'candidate_plan_featured', true);
    $candidate_plan_cv_access = get_post_meta($candidate_id, 'candidate_plan_cv_access', true);

    $membership_settings = get_option('jobster_membership_settings');
    $payment_type = isset($membership_settings['jobster_payment_type_field'])
                    ? $membership_settings['jobster_payment_type_field']
                    : '';
    $payment_system =   isset($membership_settings['jobster_payment_system_field'])
                        ? $membership_settings['jobster_payment_system_field']
                        : '';

    $currency = '';
    switch ($payment_system) {
        case 'paypal':
            $currency = isset($membership_settings['jobster_paypal_payment_currency_field'])
                        ? $membership_settings['jobster_paypal_payment_currency_field']
                        : '';
            break;
        case 'stripe':
            $currency = isset($membership_settings['jobster_stripe_payment_currency_field'])
                        ? $membership_settings['jobster_stripe_payment_currency_field']
                        : '';
            break;
        default:
            $currency = '';
            break;
    }
}

if (isset($_POST['candidate_plan_submit'])) {
    $plan_id = sanitize_text_field($_POST['candidate_plan_id']);
    $payment_type = sanitize_text_field($_POST['payment_type']);
    $payment_system = sanitize_text_field($_POST['payment_system']);

    if ($payment_type == 'plan') {
        $plan_price = get_post_meta($plan_id, 'membership_plan_price', true);
        $plan_free = get_post_meta($plan_id, 'membership_free_plan', true);

        if ($plan_free == 1) {
            jobster_update_candidate_membership($candidate_id, $plan_id, true);
        } else {
            if ($payment_system == 'paypal') {
                wp_redirect(home_url('paypal-processor') . '?candidate_id=' . $candidate_id . '&plan_id=' . $plan_id . '&payment_type=' . $payment_type);
            } else if ($payment_system == 'stripe') {
                wp_redirect(home_url('stripe-processor') . '?candidate_id=' . $candidate_id . '&plan_id=' . $plan_id . '&payment_type=' . $payment_type);
            }
        }
    }
}

if ($is_candidate) : ?>
    <div class="pxp-content">
        <div class="pxp-content-wrapper">
            <div class="pxp-content-side">
                <?php get_template_part('template-parts/dashboard', 'candidate-side'); ?>
            </div>
            <div class="pxp-content-main">
                <div class="pxp-dashboard-content-details">
                    <h1><?php esc_html_e('Membership Plans', 'jobster'); ?></h1>
                    <p class="pxp-text-light"><?php esc_html_e('Membership subscription plans.', 'jobster'); ?></p>

                    <?php if ($payment_type == 'plan') {
                        if ($candidate_plan_id != '') {
                            $plan_time_unit  = get_post_meta($candidate_plan_id, 'membership_billing_time_unit', true);
                            $plan_period     = get_post_meta($candidate_plan_id, 'membership_period', true);

                            switch ($plan_time_unit) {
                                case 'day':
                                    if (intval($plan_period) == 1) {
                                        $time_unit = __('day', 'jobster');
                                    } else {
                                        $time_unit = __('days', 'jobster');
                                    }
                                    break;
                                case 'week':
                                    if (intval($plan_period) == 1) {
                                        $time_unit = __('week', 'jobster');
                                    } else {
                                        $time_unit = __('weeks', 'jobster');
                                    }
                                    break;
                                case 'month':
                                    if (intval($plan_period) == 1) {
                                        $time_unit = __('month', 'jobster');
                                    } else {
                                        $time_unit = __('months', 'jobster');
                                    }
                                    break;
                                case 'year':
                                    if (intval($plan_period) == 1) {
                                        $time_unit = __('year', 'jobster');
                                    } else {
                                        $time_unit = __('years', 'jobster');
                                    }
                                    break;
                            } ?>

                            <div class="pxp-dashboard-content-details-form-section">
                                <h2><?php esc_html_e('Current Plan', 'jobster'); ?></h2>
                                <div class="pxp-plans-card-1">
                                    <div class="pxp-plans-card-1-top">
                                        <div class="pxp-plans-card-1-title"><?php echo esc_html(get_the_title($candidate_plan_id)); ?></div>
                                        <div class="pxp-plans-card-1-price">
                                            <?php if ($candidate_plan_free == 1) {
                                                esc_html_e('Free', 'jobster'); ?><span class="pxp-period">/<?php echo esc_html($plan_period); ?> <?php echo esc_html($time_unit); ?></span>
                                            <?php } else {
                                                $plan_price = get_post_meta($candidate_plan_id, 'membership_plan_price', true);
                                                echo esc_html($plan_price); ?><span class="pxp-plans-card-1-currency"><?php echo esc_html($currency); ?></span><span class="pxp-period">/<?php echo esc_html($plan_period); ?> <?php echo esc_html($time_unit); ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="pxp-plans-card-1-bottom">
                                        <div class="pxp-plans-card-1-cta">
                                            <div class="pxp-plans-card-1-cta-props">
                                                <div class="pxp-plans-card-1-cta-props-item">
                                                    <?php if ($candidate_plan_unlimited == 1) {
                                                        esc_html_e('Unlimited applications', 'jobster');
                                                    } else {
                                                        echo esc_html($candidate_plan_listings) . ' ' . esc_html__('applications', 'jobster');
                                                    } ?>
                                                </div>
                                                <div class="pxp-plans-card-1-cta-props-item">
                                                    <?php esc_html_e('Featured applications', 'jobster'); ?>: <?php echo esc_html($candidate_plan_featured); ?>
                                                </div>
                                                <div class="pxp-plans-card-1-cta-props-item">
                                                    <?php esc_html_e('Resume access', 'jobster'); ?>:&nbsp;<strong><?php if ($candidate_plan_cv_access == 1) {
                                                        esc_html_e('Yes', 'jobster');
                                                    } else {
                                                        esc_html_e('No', 'jobster');
                                                    } ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }

                        // Get membership plans list
                        $args = array(
                            'post_type'        => 'membership',
                            'post_status'      => 'publish',
                            'posts_per_page'   => -1,
                            'meta_key'         => 'membership_plan_price',
                            'orderby'          => 'meta_value_num',
                            'order'            => 'ASC'
                        );

                        $plans_query = new WP_Query($args);

                        if ($plans_query->have_posts()) : ?>
                            <div class="pxp-dashboard-content-details-form-section">
                                <h2><?php esc_html_e('Available Plans', 'jobster'); ?></h2>
                                <div class="row">
                                    <?php while ($plans_query->have_posts()) : $plans_query->the_post();
                                        $membership_billing_time_unit       = get_post_meta(get_the_ID(), 'membership_billing_time_unit', true);
                                        $membership_period                  = get_post_meta(get_the_ID(), 'membership_period', true);
                                        $membership_submissions_no          = get_post_meta(get_the_ID(), 'membership_submissions_no', true);
                                        $membership_unlim_submissions       = get_post_meta(get_the_ID(), 'membership_unlim_submissions', true);
                                        $membership_featured_submissions_no = get_post_meta(get_the_ID(), 'membership_featured_submissions_no', true);
                                        $membership_cv_access               = get_post_meta(get_the_ID(), 'membership_cv_access', true);
                                        $membership_plan_price              = get_post_meta(get_the_ID(), 'membership_plan_price', true);
                                        $membership_free_plan               = get_post_meta(get_the_ID(), 'membership_free_plan', true);
                                        $membership_plan_popular            = get_post_meta(get_the_ID(), 'membership_plan_popular', true);

                                        switch ($membership_billing_time_unit) {
                                            case 'day':
                                                if (intval($membership_period) == 1) {
                                                    $time_unit = __('day', 'jobster');
                                                } else {
                                                    $time_unit = __('days', 'jobster');
                                                }
                                                break;
                                            case 'week':
                                                if (intval($membership_period) == 1) {
                                                    $time_unit = __('week', 'jobster');
                                                } else {
                                                    $time_unit = __('weeks', 'jobster');
                                                }
                                                break;
                                            case 'month':
                                                if (intval($membership_period) == 1) {
                                                    $time_unit = __('month', 'jobster');
                                                } else {
                                                    $time_unit = __('months', 'jobster');
                                                }
                                                break;
                                            case 'year':
                                                if (intval($membership_period) == 1) {
                                                    $time_unit = __('year', 'jobster');
                                                } else {
                                                    $time_unit = __('years', 'jobster');
                                                }
                                                break;
                                        } ?>

                                        <div class="col-md-4 col-sm-6">
                                            <div class="pxp-plans-card-1 <?php if ($membership_plan_popular == 1) { ?>pxp-plans-card-1-popular<?php } ?>">
                                                <?php if ($membership_plan_popular == 1) {
                                                    $membership_plan_popular_label = get_post_meta(get_the_ID(), 'membership_plan_popular_label', true); ?>
                                                    <div class="pxp-plans-card-1-popular-label">
                                                        <?php if ($membership_plan_popular_label != '') {
                                                            echo esc_html($membership_plan_popular_label);
                                                        } else {
                                                            esc_html_e('Popular', 'jobster');
                                                        } ?>
                                                    </div>
                                                <?php } ?>
                                                <div class="pxp-plans-card-1-top">
                                                    <div class="pxp-plans-card-1-title"><?php the_title(); ?></div>
                                                    <div class="pxp-plans-card-1-price">
                                                        <?php if ($membership_free_plan == 1) {
                                                            esc_html_e('Free', 'jobster'); ?><span class="pxp-period">/<?php echo esc_html($membership_period); ?> <?php echo esc_html($time_unit); ?></span>
                                                        <?php } else {
                                                            echo esc_html($membership_plan_price); ?><span class="pxp-plans-card-1-currency"><?php echo esc_html($currency); ?></span><span class="pxp-period">/<?php echo esc_html($membership_period); ?> <?php echo esc_html($time_unit); ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="pxp-plans-card-1-bottom">
                                                    <div class="pxp-plans-card-1-cta">
                                                        <div class="pxp-plans-card-1-cta-props">
                                                            <div class="pxp-plans-card-1-cta-props-item">
                                                                <?php if ($membership_unlim_submissions == 1) {
                                                                    esc_html_e('Unlimited applications', 'jobster');
                                                                } else {
                                                                    echo esc_html($membership_submissions_no) . ' ' . esc_html__('applications', 'jobster');
                                                                } ?>
                                                            </div>
                                                            <div class="pxp-plans-card-1-cta-props-item">
                                                                <?php esc_html_e('Featured applications', 'jobster'); ?>: <?php echo esc_html($membership_featured_submissions_no); ?>
                                                            </div>
                                                            <div class="pxp-plans-card-1-cta-props-item">
                                                                <?php esc_html_e('Resume access', 'jobster'); ?>:&nbsp;<strong><?php if ($membership_cv_access == 1) {
                                                                    esc_html_e('Yes', 'jobster');
                                                                } else {
                                                                    esc_html_e('No', 'jobster');
                                                                } ?></strong>
                                                            </div>
                                                        </div>
                                                        <form method="post">
                                                            <input type="hidden" name="candidate_plan_id" value="<?php echo esc_attr(get_the_ID()); ?>">
                                                            <input type="hidden" name="payment_type" value="<?php echo esc_attr($payment_type); ?>">
                                                            <input type="hidden" name="payment_system" value="<?php echo esc_attr($payment_system); ?>">
                                                            <button class="pxp-plans-card-1-cta-btn" name="candidate_plan_submit" type="submit">
                                                                <?php if ($candidate_plan_id == get_the_ID()) {
                                                                    esc_html_e('Current Plan', 'jobster');
                                                                } else {
                                                                    esc_html_e('Choose Plan', 'jobster');
                                                                } ?>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        <?php endif;

                        wp_reset_postdata();
                    } else { ?>
                        <div class="pxp-dashboard-content-details-form-section">
                            <p><?php esc_html_e('Membership plans are currently disabled.', 'jobster'); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="pxp-content">
        <div class="pxp-content-wrapper">
            <div class="pxp-content-main">
                <div class="pxp-dashboard-content-details">
                    <h1><?php esc_html_e('Access Denied', 'jobster'); ?></h1>
                    <p><?php esc_html_e('You need to be logged in as a candidate to access this page.', 'jobster'); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif;

get_footer(); ?>