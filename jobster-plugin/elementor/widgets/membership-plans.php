<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Memberhip_Plans extends \Elementor\Widget_Base {
    public function get_name() {
        return 'membership_plans';
    }

    public function get_title() {
        return __('Membership Plans', 'jobster');
    }

    public function get_icon() {
        return 'eicon-price-table';
    }

    public function get_categories() {
        return ['jobster'];
    }


    protected function _register_controls() {
        $this->start_controls_section(
            'title_section',
            [
                'label' => __('Title', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter title', 'jobster'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'input_type' => 'string',
                'placeholder' => __('Enter subtitle', 'jobster'),
            ]
        );

        $this->add_control(
            'align',
            [
                'label' => __('Align', 'jobster'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    's' => [
                        'title' => __('Start', 'jobster'),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'c' => [
                        'title' => __('Center', 'jobster'),
                        'icon' => 'eicon-align-center-h',
                    ]
                ],
                'default' => 's',
                'toggle' => false
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'animation',
            [
                'label' => __('Reveal Animation', 'jobster'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'e',
                'options' => array(
                    'e' => __('Enabled', 'jobster'),
                    'd' => __('Disabled', 'jobster')
                )
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $data = $this->get_settings_for_display();

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
        } ?>

        <section class="mt-100">
            <div class="pxp-container">
                <?php if (isset($data['title']) && $data['title'] != '') { ?>
                    <h2 class="pxp-section-h2 <?php echo esc_attr($align_text); ?>">
                        <?php echo esc_html($data['title']); ?>
                    </h2>
                <?php }
                if (isset($data['subtitle']) && $data['subtitle'] != '') { ?>
                    <p class="pxp-text-light <?php echo esc_attr($align_text); ?>">
                        <?php echo esc_html($data['subtitle']); ?>
                    </p>
                <?php } ?>

                <div class="row mt-3 mt-md-4 pxp-animate-in <?php echo esc_attr($animation); ?> <?php echo esc_attr($align_cards); ?>">
                    <?php foreach($posts as $post) : 
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
                        } ?>

                        <div class="<?php echo esc_attr($column_class); ?> pxp-plans-card-1-container">
                            <div class="pxp-plans-card-1 <?php echo esc_attr($popular_class); ?>">
                                <div class="pxp-plans-card-1-top">
                                    <?php if ($membership_plan_popular_label != '') { ?>
                                        <div class="pxp-plans-card-1-featured-label">
                                            <?php echo esc_html($membership_plan_popular_label); ?>
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
                                            <?php if ($membership_unlim_submissions == 1) { ?>
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
                                        </ul>
                                    </div>
                                </div>
                                <div class="pxp-plans-card-1-bottom">
                                    <div class="pxp-plans-card-1-cta">
                                        <?php if ($logged_in) { ?>
                                            <a 
                                                href="<?php echo esc_url($subscriptions_url); ?>"
                                                class="btn rounded-pill pxp-card-btn"
                                            >
                                                <?php esc_html_e('Choose Plan', 'jobster'); ?>
                                            </a>
                                        <?php } else { ?>
                                            <a 
                                                class="btn rounded-pill pxp-card-btn" 
                                                href="#pxp-signin-modal" 
                                                data-bs-toggle="modal" 
                                                role="button"
                                            >
                                                <?php esc_html_e('Choose Plan', 'jobster'); ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php wp_reset_postdata();
        wp_reset_query();
    }
}
?>