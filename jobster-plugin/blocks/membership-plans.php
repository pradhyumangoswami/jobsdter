<?php
/**
 * Membership plans block
 */
if (!function_exists('jobster_membership_plans_block')): 
    function jobster_membership_plans_block() {
        wp_register_script(
            'jobster-membership-plans-block',
            plugins_url('js/membership-plans.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-membership-plans-block-editor',
            plugins_url('css/membership-plans.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/membership-plans', array(
            'editor_script' => 'jobster-membership-plans-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_membership_plans_block_render'
        ));
    }
endif;
add_action('init', 'jobster_membership_plans_block');

if (!function_exists('jobster_membership_plans_block_render')): 
    function jobster_membership_plans_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';

        $align_text = '';
        $align_cards = '';
        if (isset($data['align']) && $data['align'] == 'c') {
            $align_text = 'text-center';
            $align_cards = 'justify-content-center';
        }

        $args = array(
            'numberposts'      => -1,
            'post_type'        => 'membership',
            'order'            => 'ASC',
            'suppress_filters' => false,
            'post_status'      => 'publish,',
            'meta_key'         => 'membership_plan_price',
            'orderby'          => 'meta_value_num'
        );

        $posts = get_posts($args);

        $plans_total = count($posts);
        $column_class = 'col-md-6 col-xl-4 col-xxl-3';
        if ($plans_total == 3) {
            $column_class = 'col-md-6 col-xl-4';
        }

        $membership_settings = get_option('jobster_membership_settings');
        $currency = '';
        $payment_system =   isset($membership_settings['jobster_payment_system_field'])
                            ? $membership_settings['jobster_payment_system_field']
                            : '';
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

        $logged_in = false;
        $subscriptions_url = '';
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $is_company = jobster_user_is_company($current_user->ID);

            if ($is_company) {
                $subscriptions_url = jobster_get_page_link('company-dashboard-subscriptions.php');
                $logged_in = true;
            }
        }

        $return_string = 
            '<section class="mt-100">
                <div class="pxp-container">';
        if (isset($data['title']) && $data['title'] != '') {
            $return_string .=
                    '<h2 class="pxp-section-h2 ' . esc_attr($align_text) . '">
                        ' . esc_html($data['title']) . '
                    </h2>';
        }
        if (isset($data['subtitle']) && $data['subtitle'] != '') {
            $return_string .=
                    '<p class="pxp-text-light ' . esc_attr($align_text) . '">
                        ' . esc_html($data['subtitle']) . '
                    </p>';
        }
        $return_string .=
                    '<div class="row mt-3 mt-md-4 pxp-animate-in ' . esc_attr($animation) . ' ' . esc_attr($align_cards) . '">';

        foreach($posts as $post) : 
            $membership_billing_time_unit       = get_post_meta($post->ID, 'membership_billing_time_unit', true);
            $membership_period                  = get_post_meta($post->ID, 'membership_period', true);
            $membership_submissions_no          = get_post_meta($post->ID, 'membership_submissions_no', true);
            $membership_unlim_submissions       = get_post_meta($post->ID, 'membership_unlim_submissions', true);
            $membership_featured_submissions_no = get_post_meta($post->ID, 'membership_featured_submissions_no', true);
            $membership_cv_access               = get_post_meta($post->ID, 'membership_cv_access', true);
            $membership_plan_price              = get_post_meta($post->ID, 'membership_plan_price', true);
            $membership_free_plan               = get_post_meta($post->ID, 'membership_free_plan', true);
            $membership_plan_popular            = get_post_meta($post->ID, 'membership_plan_popular', true);
            $membership_plan_popular_label      = get_post_meta($post->ID, 'membership_plan_popular_label', true);

            if ($membership_billing_time_unit == 'day') {
                if ($membership_period == '1') {
                    $time_unit = __('day', 'jobster');
                } else {
                    $time_unit = __('days', 'jobster');
                }
            } else if ($membership_billing_time_unit == 'week') {
                if ($membership_period == '1') {
                    $time_unit = __('week', 'jobster');
                } else {
                    $time_unit = __('weeks', 'jobster');
                }
            } else if ($membership_billing_time_unit == 'month') {
                if ($membership_period == '1') {
                    $time_unit = __('month', 'jobster');
                } else {
                    $time_unit = __('months', 'jobster');
                }
            } else {
                if ($membership_period == '1') {
                    $time_unit = __('year', 'jobster');
                } else {
                    $time_unit = __('years', 'jobster');
                }
            }

            $popular_class = '';
            $check_icon = '';
            if ($membership_plan_popular == '1') {
                $popular_class = 'pxp-is-featured';
                $check_icon = '-light';
            }

            $return_string .=
                        '<div class="' . esc_attr($column_class) . ' pxp-plans-card-1-container">
                            <div class="pxp-plans-card-1 ' . esc_attr($popular_class) . '">
                                <div class="pxp-plans-card-1-top">';
            if ($membership_plan_popular_label != '') {
                $return_string .=
                                    '<div class="pxp-plans-card-1-featured-label">
                                        ' . esc_html($membership_plan_popular_label) . '
                                    </div>';
            }
            $return_string .=
                                    '<div class="pxp-plans-card-1-title">
                                        ' . esc_html($post->post_title) . '
                                    </div>
                                    <div class="pxp-plans-card-1-price">';
            if ($membership_free_plan == 1) {
                $return_string .=
                                        __('Free', 'jobster') . '<span class="pxp-period">/' . esc_html($membership_period) . ' ' . esc_html($time_unit) . '</span>';
            } else {
                $return_string .=
                                        esc_html($membership_plan_price) . '<span class="pxp-plans-card-1-currency">' . esc_html($currency). '</span><span class="pxp-period">/' . esc_html($membership_period). ' ' . esc_html($time_unit) . '</span>';
            }
            $return_string .=
                                    '</div>
                                    <div class="pxp-plans-card-1-list">
                                        <ul class="list-unstyled">';
            if ($membership_unlim_submissions == 1) {
                $return_string .=
                                            '<li>
                                                <img 
                                                    src="' . esc_url(JOBSTER_LOCATION . '/images/check' . esc_attr($check_icon) . '.svg') . '" 
                                                    alt="-"
                                                >
                                                ' . __('Unlimited job postings', 'jobster') . '
                                            </li>';
            } else {
                $return_string .=
                                            '<li>
                                                <img 
                                                    src="' . esc_url(JOBSTER_LOCATION . '/images/check' . esc_attr($check_icon) . '.svg') . '" 
                                                    alt="-"
                                                >
                                                ' . esc_html($membership_submissions_no) . ' ' . __('job postings', 'jobster') . '
                                            </li>';
            }
            $return_string .=
                                            '<li>
                                                <img 
                                                    src="' . esc_url(JOBSTER_LOCATION . '/images/check' . esc_attr($check_icon) . '.svg') . '" 
                                                    alt="-"
                                                >
                                                ' . esc_html($membership_featured_submissions_no) . ' ' . __('featured job postings', 'jobster') . '
                                            </li>';
            if ($membership_cv_access == 1) {
                $return_string .= 
                                            '<li>
                                                <img 
                                                    src="' . esc_url(JOBSTER_LOCATION . '/images/check' . esc_attr($check_icon) . '.svg') . '" 
                                                    alt="-"
                                                >
                                                ' . __('Resume access', 'jobster') . '
                                            </li>';
            } else {
                $return_string .= 
                                            '<li class="opacity-50">
                                                <img 
                                                    src="' . esc_url(JOBSTER_LOCATION . '/images/x-circle' . esc_attr($check_icon) . '.svg') . '" 
                                                    alt="-"
                                                >
                                                ' . __('Resume access', 'jobster') . '
                                            </li>';
            }
            $return_string .=
                                        '</ul>
                                    </div>
                                </div>
                                <div class="pxp-plans-card-1-bottom">
                                    <div class="pxp-plans-card-1-cta">';
            if ($logged_in) {
                $return_string .=
                                        '<a 
                                            href="' . esc_url($subscriptions_url) . '" 
                                            class="btn rounded-pill pxp-card-btn"
                                        >
                                            ' . __('Choose Plan', 'jobster') . '
                                        </a>';
            } else {
                $return_string .=
                                        '<a 
                                            class="btn rounded-pill pxp-card-btn" 
                                            href="#pxp-signin-modal" 
                                            data-bs-toggle="modal" 
                                            role="button"
                                        >
                                            ' . __('Choose Plan', 'jobster') . '
                                        </a>';
            }
            $return_string .=
                                    '</div>
                                </div>
                            </div>
                        </div>';
        endforeach;

        $return_string .=
                    '</div>
                </div>
            </section>';

        wp_reset_postdata();
        wp_reset_query();

        return $return_string;
    }
endif;
?>