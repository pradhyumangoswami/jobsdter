<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Register membership custom post type
 */
if (!function_exists('jobster_register_membership_type')): 
    function jobster_register_membership_type() {
        register_post_type('membership', array(
            'labels' => array(
                'name'               => __('Membership Plans', 'jobster'),
                'singular_name'      => __('Membership Plan', 'jobster'),
                'add_new'            => __('Add New Membership Plan', 'jobster'),
                'add_new_item'       => __('Add Membership Plan', 'jobster'),
                'edit'               => __('Edit', 'jobster'),
                'edit_item'          => __('Edit Membership Plan', 'jobster'),
                'new_item'           => __('New Membership Plan', 'jobster'),
                'view'               => __('View', 'jobster'),
                'view_item'          => __('View Membership Plan', 'jobster'),
                'search_items'       => __('Search Membership Plans', 'jobster'),
                'not_found'          => __('No Membership Plans found', 'jobster'),
                'not_found_in_trash' => __('No Membership Plans found in Trash', 'jobster'),
                'parent'             => __('Parent Membership Plan', 'jobster'),
            ),
            'public'               => true,
            'exclude_from_search ' => true,
            'has_archive'          => true,
            'rewrite'              => array('slug' => _x('membership_plans', 'URL SLUG', 'jobster')),
            'supports'             => array('title'),
            'can_export'           => true,
            'register_meta_box_cb' => 'jobster_add_membership_metaboxes',
            'menu_icon'            => 'dashicons-id'
        ));
    }
endif;
add_action('init', 'jobster_register_membership_type');

/**
 * Add membership post type metaboxes
 */
if (!function_exists('jobster_add_membership_metaboxes')): 
    function jobster_add_membership_metaboxes() {
        add_meta_box(
            'membership-plan-features-section',
            __('Membership Plan Features', 'jobster'),
            'jobster_membership_plan_features_render',
            'membership',
            'normal',
            'default'
        );
    }
endif;

if (!function_exists('jobster_membership_plan_features_render')): 
    function jobster_membership_plan_features_render($post) {
        wp_nonce_field('jobster_membership', 'membership_noncename');

        $selected_unit = get_post_meta(
            $post->ID,
            'membership_billing_time_unit',
            true
        );
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

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="33%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="membership_period">' . __('Membership Plan Period', 'jobster') . '</label><br />
                            <input type="text" class="formInput" id="membership_period" name="membership_period" placeholder="' . __('Enter number of...', 'jobster') . '" value="' . esc_attr(get_post_meta($post->ID, 'membership_period', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="membership_billing_time_unit">&nbsp;</label><br />
                            <select id="membership_billing_time_unit" name="membership_billing_time_unit" style="width: auto;">
                                <option value="day" ';
                                selected( $selected_unit, 'day' );
                                print '>' . __('Days', 'jobster') . '</option>
                                <option value="week" ';
                                selected( $selected_unit, 'week' );
                                print '>' . __('Weeks', 'jobster') . '</option>
                                <option value="month" ';
                                selected( $selected_unit, 'month' );
                                print '>' . __('Months', 'jobster') . '</option>
                                <option value="year" ';
                                selected( $selected_unit, 'year' );
                                print '>' . __('Years', 'jobster') . '</option>
                            </select>
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="membership_submissions_no">' . __('Number of Job Postings', 'jobster') . '</label><br />
                            <input type="text" class="formInput" id="membership_submissions_no" name="membership_submissions_no" placeholder="' . __('Enter the number of job postings', 'jobster') . '" value="' . esc_attr(get_post_meta($post->ID, 'membership_submissions_no', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">
                        <p class="meta-options" style="padding-top: 15px;"> 
                            <input type="hidden" name="membership_unlim_submissions" value="">
                            <input type="checkbox" name="membership_unlim_submissions" id="membership_unlim_submissions" value="1" ';
                            if (get_post_meta($post->ID, 'membership_unlim_submissions', true) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="membership_unlim_submissions">' . __('Unlimited Job Postings', 'jobster') . '</label>
                        </p>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="membership_featured_submissions_no">' . __('Number of Featured Jobs', 'jobster') . '</label><br />
                            <input type="text" class="formInput" id="membership_featured_submissions_no" name="membership_featured_submissions_no" placeholder="' . __('Enter the number of featured jobs', 'jobster') . '" value="' . esc_attr(get_post_meta($post->ID, 'membership_featured_submissions_no', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top" align="left">
                        <p class="meta-options"> 
                            <input type="hidden" name="membership_cv_access" value="">
                            <input type="checkbox" name="membership_cv_access" id="membership_cv_access" value="1" ';
                            if (get_post_meta($post->ID, 'membership_cv_access', true) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="membership_cv_access">' . __('Candidate Resume Access', 'jobster') . '</label>
                        </p>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top" align="left" style="padding-top: 15px;">
                        <div class="form-field pxp-is-custom">
                            <label for="membership_plan_price">' . __('Plan Price', 'jobster') . ' (' . esc_html($currency) . ')</label><br />
                            <input type="text" class="formInput" id="membership_plan_price" name="membership_plan_price" placeholder="' . __('Enter the plan price', 'jobster') . '" value="' . esc_attr(get_post_meta($post->ID, 'membership_plan_price', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">
                        <p class="meta-options" style="padding-top: 30px;"> 
                            <input type="hidden" name="membership_free_plan" value="">
                            <input type="checkbox" name="membership_free_plan" id="membership_free_plan" value="1" ';
                            if (get_post_meta($post->ID, 'membership_free_plan', true) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="membership_free_plan">' . __('Free Plan', 'jobster') . '</label>
                        </p>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" valign="top" align="left">
                        <p class="meta-options" style="padding-top: 15px;"> 
                            <input type="hidden" name="membership_plan_popular" value="">
                            <input type="checkbox" name="membership_plan_popular" id="membership_plan_popular" value="1" ';
                            if (get_post_meta($post->ID, 'membership_plan_popular', true) == 1) {
                                print ' checked ';
                            }
                            print ' />
                            <label for="membership_free_plan">' . __('Popular/Recommended Plan', 'jobster') . '</label>
                        </p>
                    </td>
                    <td width="33%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="membership_plan_popular_label">' . __('Popular/Recommended Plan Label', 'jobster') . '</label><br />
                            <input type="text" class="formInput" id="membership_plan_popular_label" name="membership_plan_popular_label" placeholder="' . __('Enter the label text', 'jobster') . '" value="' . esc_attr(get_post_meta($post->ID, 'membership_plan_popular_label', true)) . '" />
                        </div>
                    </td>
                    <td width="33%" valign="top" align="left">&nbsp;</td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_membership_meta_save')): 
    function jobster_membership_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['membership_noncename']) && wp_verify_nonce($_POST['membership_noncename'], 'jobster_membership')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['membership_billing_time_unit'])) {
            update_post_meta($post_id, 'membership_billing_time_unit', sanitize_text_field($_POST['membership_billing_time_unit']));
        }
        if (isset($_POST['membership_period'])) {
            update_post_meta($post_id, 'membership_period', sanitize_text_field($_POST['membership_period']));
        }
        if (isset($_POST['membership_submissions_no'])) {
            update_post_meta($post_id, 'membership_submissions_no', sanitize_text_field($_POST['membership_submissions_no']));
        }
        if (isset($_POST['membership_unlim_submissions'])) {
            update_post_meta($post_id, 'membership_unlim_submissions', sanitize_text_field($_POST['membership_unlim_submissions']));
        }
        if (isset($_POST['membership_featured_submissions_no'])) {
            update_post_meta($post_id, 'membership_featured_submissions_no', sanitize_text_field($_POST['membership_featured_submissions_no']));
        }
        if (isset($_POST['membership_cv_access'])) {
            update_post_meta($post_id, 'membership_cv_access', sanitize_text_field($_POST['membership_cv_access']));
        }
        if (isset($_POST['membership_plan_price'])) {
            update_post_meta($post_id, 'membership_plan_price', sanitize_text_field($_POST['membership_plan_price']));
        }
        if (isset($_POST['membership_free_plan'])) {
            update_post_meta($post_id, 'membership_free_plan', sanitize_text_field($_POST['membership_free_plan']));
        }
        if (isset($_POST['membership_plan_popular'])) {
            update_post_meta($post_id, 'membership_plan_popular', sanitize_text_field($_POST['membership_plan_popular']));
        }
        if (isset($_POST['membership_plan_popular_label'])) {
            update_post_meta($post_id, 'membership_plan_popular_label', sanitize_text_field($_POST['membership_plan_popular_label']));
        }
        if (isset($_POST['membership_icon'])) {
            update_post_meta($post_id, 'membership_icon', sanitize_text_field($_POST['membership_icon']));
        }
    }
endif;
add_action('save_post', 'jobster_membership_meta_save');

if (!function_exists('jobster_change_membership_default_title')): 
    function jobster_change_membership_default_title($title) {
        $screen = get_current_screen();

        if ('membership' == $screen->post_type) {
            $title = __('Enter membership plan title here', 'jobster');
        }
        return $title;
    }
endif;
add_filter('enter_title_here', 'jobster_change_membership_default_title');
?>