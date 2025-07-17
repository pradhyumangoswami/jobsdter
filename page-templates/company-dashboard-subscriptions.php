<?php
/*
Template Name: Company Dashboard - Subscriptions
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $current_user;
global $company_id;
global $post;

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

$current_user = wp_get_current_user();

$is_company = jobster_user_is_company($current_user->ID);
if ($is_company) {
    $company_id = jobster_get_company_by_userid($current_user->ID);
    $company_payment = get_post_meta($company_id, 'company_payment', true);
} else {
    wp_redirect(home_url());
}

$subscriptions_url = jobster_get_page_link('company-dashboard-subscriptions.php');

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

// Check PayPal Memberhip Plans Payment
if (isset($_GET['token']) && $payment_system == 'paypal') {
    $token = sanitize_text_field($_GET['token']);

    $save_data           = get_option('paypal_plan_transfer');
    $payment_execute_url = $save_data[$current_user->ID]['paypal_execute'];
    $token               = $save_data[$current_user->ID]['paypal_token'];
    $plan_id             = $save_data[$current_user->ID]['plan_id'];

    if (isset($_GET['PayerID'])) {
        $payerId = sanitize_text_field($_GET['PayerID']);

        $payment_execute = array(
            'payer_id' => $payerId
        );

        $json      = json_encode($payment_execute);
        $json_resp = jobster_make_paypal_post_call($payment_execute_url, $json, $token);

        $save_data[$current_user->ID] = array();

        update_option('paypal_plan_transfer', $save_data);

        if ($json_resp['state'] == 'approved') {
            jobster_update_company_membership($company_id, $plan_id);
            wp_redirect($subscriptions_url);
        }
    }
}

get_header('dashboard', array('bg_color' => 'pxpMainColorLight'));

jobster_get_company_dashboard_side($company_id, 'subscriptions'); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_company_dashboard_top($company_id); ?>

    <div class="pxp-dashboard-content-details">
        <h1><?php esc_html_e('Subscriptions', 'jobster'); ?></h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Membership subscription plans.', 'jobster'); ?>
        </p>

        <?php if ($payment_type == 'plan' && $company_payment != '1') {
            $company_plan_id = get_post_meta($company_id, 'company_plan', true);
            $has_free = '';

            if ($company_plan_id) {
                $plan_name       = get_the_title($company_plan_id);
                $plan_listings   = get_post_meta($company_id, 'company_plan_listings', true);
                $plan_featured   = get_post_meta($company_id, 'company_plan_featured', true);
                $plan_unlimited  = get_post_meta($company_id, 'company_plan_unlimited', true);
                $has_free        = get_post_meta($company_id, 'company_plan_free', true);
                $plan_activation = strtotime(get_post_meta($company_id, 'company_plan_activation', true));
                $plan_time_unit  = get_post_meta($company_plan_id, 'membership_billing_time_unit', true);
                $plan_period     = get_post_meta($company_plan_id, 'membership_period', true);

                $seconds = 0;
                switch ($plan_time_unit) {
                    case 'day':
                        $seconds = 60 * 60 * 24;
                    break;
                    case 'week':
                        $seconds = 60 * 60 * 24 * 7;
                    break;
                    case 'month':
                        $seconds = 60 * 60 * 24 * 30;
                    break;
                    case 'year':
                        $seconds = 60 * 60 * 24 * 365;
                    break;
                }

                $time_frame      = $seconds * $plan_period;
                $expiration_date = $plan_activation + $time_frame;
                $expiration_date = date('Y-m-d', $expiration_date);
                $today           = getdate();
            }

            // Get membership plans list
            $args = array(
                'posts_per_page'   => -1,
                'post_type'        => 'membership',
                'order'            => 'ASC',
                'post_status'      => 'publish,',
                'meta_key'         => 'membership_plan_price',
                'orderby'          => 'meta_value_num',
                'suppress_filters' => false,
            );

            $posts = get_posts($args); ?>

            <div class="row mt-4 mt-lg-5">
                <?php foreach ($posts as $post) :
                    
                    $membership_billing_time_unit       = get_post_meta($post->ID, 'membership_billing_time_unit', true);
                    $membership_period                  = get_post_meta($post->ID, 'membership_period', true);
                    $membership_submissions_no          = get_post_meta($post->ID, 'membership_submissions_no', true);
                    $membership_unlim_submissions       = get_post_meta($post->ID, 'membership_unlim_submissions', true);
                    $membership_featured_submissions_no = get_post_meta($post->ID, 'membership_featured_submissions_no', true);
                    $membership_cv_access               = get_post_meta($post->ID, 'membership_cv_access', true);
                    $membership_plan_price              = get_post_meta($post->ID, 'membership_plan_price', true);
                    $membership_free_plan               = get_post_meta($post->ID, 'membership_free_plan', true);

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
                    }

                    $featured_class = '';
                    $check_icon = '';
                    if ($post->ID == $company_plan_id) {
                        $featured_class = 'pxp-is-featured';
                        $check_icon = '-light';
                    } ?>

                    <div class="col-md-6 col-xl-4 pxp-plans-card-1-container">
                        <div class="pxp-plans-card-1 <?php echo esc_attr($featured_class); ?>">
                            <div class="pxp-plans-card-1-top">
                                <?php if ($post->ID == $company_plan_id) { ?>
                                    <div class="pxp-plans-card-1-featured-label">
                                        <?php if ($post->ID == $company_plan_id) {
                                            if ($today[0] > strtotime($expiration_date)) {
                                                esc_html_e('Expired', 'jobster');
                                            } else {
                                                esc_html_e('Active', 'jobster');
                                            }
                                        } ?>
                                    </div>
                                <?php } ?>
                                <div class="pxp-plans-card-1-title">
                                    <?php echo esc_html($post->post_title); ?>
                                </div>
                                <div class="pxp-plans-card-1-price">
                                    <?php if ($membership_free_plan == 1) {
                                        esc_html_e('Free', 'jobster'); ?><span class="pxp-period">/<?php echo esc_html($membership_period); ?> <?php echo esc_html($time_unit); ?></span>
                                    <?php } else { ?>
                                        <?php echo esc_html($membership_plan_price); ?><span class="pxp-plans-card-1-currency"><?php echo esc_html($currency); ?></span><span class="pxp-period">/<?php echo esc_html($membership_period); ?> <?php echo esc_html($time_unit); ?></span>
                                    <?php } ?>
                                </div>
                                <div class="pxp-plans-card-1-list">
                                    <ul class="list-unstyled">
                                        <?php if ($post->ID == $company_plan_id) {
                                            if ($plan_unlimited == '1') { ?>
                                                <li><?php esc_html_e('Job postings', 'jobster'); ?>:&nbsp;<strong><?php esc_html_e('Unlimited', 'jobster'); ?></strong></li>
                                            <?php } else { ?>
                                                <li>
                                                    <?php esc_html_e('Job postings', 'jobster'); ?>:&nbsp;<strong><?php if (!empty($plan_listings) && intval($plan_listings) > 0) {
                                                        echo esc_html($plan_listings);
                                                    } else { ?>
                                                        0
                                                    <?php } ?></strong>
                                                </li>
                                            <?php } ?>
                                            <li>
                                                <?php esc_html_e('Featured job postings', 'jobster'); ?>:&nbsp;<strong><?php if (!empty($plan_featured) && intval($plan_featured) > 0) {
                                                    echo esc_html($plan_featured);
                                                } else { ?>
                                                    0
                                                <?php } ?></strong>
                                            </li>
                                            <li>
                                                <?php esc_html_e('Resume access', 'jobster'); ?>:&nbsp;<strong><?php if ($membership_cv_access == 1) {
                                                    esc_html_e('Yes', 'jobster');
                                                } else {
                                                    esc_html_e('No', 'jobster');
                                                } ?></strong>
                                            </li>
                                        <?php } else {
                                            if ($membership_unlim_submissions == 1) { ?>
                                                <li><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/check' . esc_attr($check_icon) . '.svg'); ?>" alt="-"><?php esc_html_e('Unlimited job postings', 'jobster'); ?></li>
                                            <?php } else { ?>
                                                <li><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/check' . esc_attr($check_icon) . '.svg'); ?>" alt="-"><?php echo esc_html($membership_submissions_no); ?> <?php esc_html_e('job postings', 'jobster'); ?></li>
                                            <?php } ?>
                                            <li><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/check' . esc_attr($check_icon). '.svg'); ?>" alt="-"><?php echo esc_html($membership_featured_submissions_no); ?> <?php esc_html_e('featured job postings', 'jobster'); ?></li>
                                            <?php if ($membership_cv_access == 1) { ?>
                                                <li><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/check' . esc_attr($check_icon) . '.svg'); ?>" alt="-"><?php esc_html_e('Resume access', 'jobster'); ?></li>
                                            <?php } else { ?>
                                                <li class="opacity-50"><img src="<?php echo esc_url(JOBSTER_LOCATION . '/images/x-circle' . esc_attr($check_icon) . '.svg'); ?>" alt="-"><?php esc_html_e('Resume access', 'jobster'); ?></li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="pxp-plans-card-1-bottom">
                                <div class="pxp-plans-card-1-cta">
                                    <?php if ($membership_free_plan == 1) {
                                        if ($has_free != 1) { ?>
                                            <a 
                                                href="javascript:void(0);" 
                                                class="btn rounded-pill pxp-card-btn pxp-activate-plan-btn" 
                                                data-company-id="<?php echo esc_attr($company_id); ?>" 
                                                data-id="<?php echo esc_attr($post->ID); ?>"
                                            >
                                                <span class="pxp-activate-plan-btn-text">
                                                    <?php esc_html_e('Activate Plan', 'jobster'); ?>
                                                </span>
                                                <span class="pxp-activate-plan-btn-loading pxp-btn-loading">
                                                    <img 
                                                        src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                                        class="pxp-btn-loader" 
                                                        alt="..."
                                                    >
                                                </span>
                                            </a>
                                        <?php }
                                    } else {
                                        if (
                                            ($post->ID != $company_plan_id) 
                                            || (($post->ID == $company_plan_id) && $today[0] > strtotime($expiration_date))
                                            || (($post->ID == $company_plan_id) && ($plan_unlimited != '1') && intval($plan_listings) <= 0)
                                        ) { ?>
                                            <a 
                                                href="javascript:void(0);" 
                                                class="btn rounded-pill pxp-card-btn pxp-pay-plan-btn" 
                                                data-company-id="<?php echo esc_attr($company_id); ?>" 
                                                data-id="<?php echo esc_attr($post->ID); ?>" 
                                                data-system="<?php echo esc_attr($payment_system); ?>"
                                            >
                                                <span class="pxp-pay-plan-btn-text">
                                                    <?php if ($payment_system == 'paypal') { ?>
                                                        <span class="fa fa-paypal"></span> <?php esc_html_e('Pay with PayPal', 'jobster');
                                                    }
                                                    if ($payment_system == 'stripe') { ?>
                                                        <span class="fa fa-cc-stripe"></span> <?php esc_html_e('Pay with Stripe', 'jobster');
                                                    } ?>
                                                </span>
                                                <span class="pxp-pay-plan-btn-loading pxp-btn-loading">
                                                    <img 
                                                        src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                                        class="pxp-btn-loader" 
                                                        alt="..."
                                                    >
                                                </span>
                                            </a>
                                        <?php }
                                    } ?>
                                </div>
                                <?php if ($post->ID == $company_plan_id) { ?>
                                    <div class="pxp-plans-card-1-expiration mt-3">
                                        <?php if ($today[0] > strtotime($expiration_date)) {
                                            esc_html_e('Expired on', 'jobster'); ?> <b><?php echo esc_html($expiration_date); ?></b>
                                        <?php } else {
                                            esc_html_e('Expires on', 'jobster'); ?> <b><?php echo esc_html($expiration_date); ?></b>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                <?php endforeach;

                wp_reset_postdata();
                wp_reset_query(); ?>
            </div>

        <?php } ?>
    </div>

    <?php get_footer('dashboard'); ?>
</div>