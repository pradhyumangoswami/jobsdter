<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_membership')): 
    function jobster_admin_membership() {
        add_settings_section(
            'jobster_membership_section',
            __('Membership & Payment', 'jobster'),
            'jobster_membership_section_callback',
            'jobster_membership_settings'
        );
        add_settings_field(
            'jobster_payment_type_field',
            __('Payment Type', 'jobster'),
            'jobster_payment_type_field_render',
            'jobster_membership_settings',
            'jobster_membership_section'
        );
        add_settings_field(
            'jobster_paypal_payment_currency_field',
            __('Payment Currency', 'jobster'),
            'jobster_paypal_payment_currency_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-paypal'
            )
        );
        add_settings_field(
            'jobster_stripe_payment_currency_field',
            __('Payment Currency', 'jobster'),
            'jobster_stripe_payment_currency_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-stripe'
            )
        );
        add_settings_field(
            'jobster_submission_price_field',
            __('Job Posting Price', 'jobster'),
            'jobster_submission_price_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-per'
            )
        );
        add_settings_field(
            'jobster_featured_price_field',
            __('Featured Job Posting Price', 'jobster'),
            'jobster_featured_price_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-per'
            )
        );
        add_settings_field(
            'jobster_free_submissions_no_field',
            __('Number of Free Job Postings', 'jobster'),
            'jobster_free_submissions_no_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-per'
            )
        );
        add_settings_field(
            'jobster_free_submissions_unlim_field',
            __('Unlimited Free Job Postings', 'jobster'),
            'jobster_free_submissions_unlim_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-per'
            )
        );
        add_settings_field(
            'jobster_free_featured_submissions_no_field',
            __('Number of Free Featured Job Postings', 'jobster'),
            'jobster_free_featured_submissions_no_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-per'
            )
        );
        add_settings_field(
            'jobster_payment_system_field',
            __('Payment System', 'jobster'),
            'jobster_payment_system_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-system'
            )
        );
        add_settings_field(
            'jobster_paypal_api_version_field',
            __('PayPal API Version', 'jobster'),
            'jobster_paypal_api_version_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-paypal'
            )
        );
        add_settings_field(
            'jobster_paypal_client_id_field',
            __('PayPal Client ID', 'jobster'),
            'jobster_paypal_client_id_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-paypal'
            )
        );
        add_settings_field(
            'jobster_paypal_client_key_field',
            __('PayPal Client Secret Key', 'jobster'),
            'jobster_paypal_client_key_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-paypal'
            )
        );
        add_settings_field(
            'jobster_paypal_api_username_field',
            __('PayPal API Username', 'jobster'),
            'jobster_paypal_api_username_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-paypal'
            )
        );
        add_settings_field(
            'jobster_paypal_api_password_field',
            __('PayPal API Password', 'jobster'),
            'jobster_paypal_api_password_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-paypal'
            )
        );
        add_settings_field(
            'jobster_paypal_api_signature_field',
            __('PayPal API Signature', 'jobster'),
            'jobster_paypal_api_signature_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-paypal'
            )
        );
        add_settings_field(
            'jobster_paypal_email_field',
            __('PayPal Receiving E-mail', 'jobster'),
            'jobster_paypal_email_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-paypal'
            )
        );
        add_settings_field(
            'jobster_stripe_pub_key_field',
            __('Stripe Publishable Key', 'jobster'),
            'jobster_stripe_pub_key_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-stripe'
            )
        );
        add_settings_field(
            'jobster_stripe_secret_key_field',
            __('Stripe Secret Key', 'jobster'),
            'jobster_stripe_secret_key_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-stripe'
            )
        );
        add_settings_field(
            'jobster_stripe_endpoint_key_field',
            __('Stripe Endpoint Signing Secret', 'jobster'),
            'jobster_stripe_endpoint_key_field_render',
            'jobster_membership_settings',
            'jobster_membership_section',
            array(
                'class' => 'pxp-membership-settings-field-all pxp-membership-settings-field-stripe'
            )
        );
    }
endif;

if (!function_exists('jobster_membership_section_callback')): 
    function jobster_membership_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('jobster_payment_type_field_render')): 
    function jobster_payment_type_field_render() { 
        $options = get_option('jobster_membership_settings');

        $types = array(
            'disabled' => __('Disabled', 'jobster'),
            'listing'  => __('Pay per job posting', 'jobster'),
            'plan'     => __('Membership plan', 'jobster')
        ); ?>

        <select
            name="jobster_membership_settings[jobster_payment_type_field]" 
            id="jobster_membership_settings[jobster_payment_type_field]"
        >
            <?php foreach ($types as $type_key => $type_value) { ?>
                <option 
                    value="<?php echo esc_attr($type_key); ?>" 
                    <?php selected(
                        isset($options['jobster_payment_type_field'])
                        && $options['jobster_payment_type_field'] == $type_key
                    ) ?>
                >
                    <?php echo esc_html($type_value); ?>
                </option>
            <?php } ?>
        </select>
    <?php }
endif;

if (!function_exists('jobster_paypal_payment_currency_field_render')): 
    function jobster_paypal_payment_currency_field_render() { 
        $options = get_option('jobster_membership_settings');

        $currencies = array('USD','EUR','AUD','BRL','CAD','CNY','CZK','DKK','HKD','HUF','ILS','JPY','MYR','MXN','TWD','NZD','NOK','PHP','PLN','GBP','RUB','SGD','SEK','CHF','THB'); ?>

        <select
            name="jobster_membership_settings[jobster_paypal_payment_currency_field]" 
            id="jobster_membership_settings[jobster_paypal_payment_currency_field]"
        >
            <?php foreach ($currencies as $currency) { ?>
                <option 
                    value="<?php echo esc_attr($currency); ?>" 
                    <?php selected(
                        isset($options['jobster_paypal_payment_currency_field'])
                        && $options['jobster_paypal_payment_currency_field'] == $currency
                    ) ?>
                >
                    <?php echo esc_html($currency); ?>
                </option>
            <?php } ?>
        </select>
    <?php }
endif;

if (!function_exists('jobster_stripe_payment_currency_field_render')): 
    function jobster_stripe_payment_currency_field_render() { 
        $options = get_option('jobster_membership_settings');

        $currencies = array('USD','EUR','AED','AFN','ALL','AMD','ANG','AOA','ARS','AUD','AWG','AZN','BAM','BBD','BDT','BGN','BIF','BMD','BND','BOB','BRL','BSD','BWP','BYN','BZD','CAD','CDF','CHF','CLP','CNY','COP','CRC','CVE','CZK','DJF','DKK','DOP','DZD','EGP','ETB','FJD','FKP','GBP','GEL','GIP','GMD','GNF','GTQ','GYD','HKD','HNL','HRK','HTG','HUF','IDR','ILS','INR','ISK','JMD','JPY','KES','KGS','KHR','KMF','KRW','KYD','KZT','LAK','LBP','LKR','LRD','LSL','MAD','MDL','MGA','MKD','MMK','MNT','MOP','MRO','MUR','MVR','MWK','MXN','MYR','MZN','NAD','NGN','NIO','NOK','NPR','NZD','PAB','PEN','PGK','PHP','PKR','PLN','PYG','QAR','RON','RSD','RUB','RWF','SAR','SBD','SCR','SEK','SGD','SHP','SLE','SLL','SOS','SRD','STD','SZL','THB','TJS','TOP','TRY','TTD','TWD','TZS','UAH','UGX','UYU','UZS','VND','VUV','WST','XAF','XCD','XOF','XPF','YER','ZAR','ZMW'); ?>

        <select
            name="jobster_membership_settings[jobster_stripe_payment_currency_field]" 
            id="jobster_membership_settings[jobster_stripe_payment_currency_field]"
        >
            <?php foreach ($currencies as $currency) { ?>
                <option 
                    value="<?php echo esc_attr($currency); ?>" 
                    <?php selected(
                        isset($options['jobster_stripe_payment_currency_field'])
                        && $options['jobster_stripe_payment_currency_field'] == $currency
                    ) ?>
                >
                    <?php echo esc_html($currency); ?>
                </option>
            <?php } ?>
        </select>
    <?php }
endif;

if (!function_exists('jobster_submission_price_field_render')): 
    function jobster_submission_price_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_submission_price_field]" 
            id="jobster_membership_settings[jobster_submission_price_field]" 
            type="text" 
            size="20" 
            value="<?php if (isset($options['jobster_submission_price_field'])) {
                echo esc_attr($options['jobster_submission_price_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_featured_price_field_render')): 
    function jobster_featured_price_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_featured_price_field]" 
            id="jobster_membership_settings[jobster_featured_price_field]" 
            type="text" 
            size="20" 
            value="<?php if (isset($options['jobster_featured_price_field'])) {
                echo esc_attr($options['jobster_featured_price_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_free_submissions_no_field_render')): 
    function jobster_free_submissions_no_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_free_submissions_no_field]" 
            id="jobster_membership_settings[jobster_free_submissions_no_field]" 
            type="text" 
            size="5" 
            value="<?php if (isset($options['jobster_free_submissions_no_field'])) {
                    echo esc_attr($options['jobster_free_submissions_no_field']); 
            } ?>"
        >
        <?php wp_nonce_field(
            'set_free_submissions_ajax_nonce',
            'pxp-set-free-submissions-security',
            true
        ); ?>
        <button 
            type="button" 
            class="button button-secondary pxp-set-free-submissions-btn"
        >
            <span class="pxp-set-free-submissions-btn-text">
                <?php esc_html_e('Set for all existing companies', 'jobster'); ?>
            </span>
            <span class="pxp-set-free-submissions-btn-loading">
                <span class="fa fa-spin fa-spinner"></span>
                <?php esc_html_e('Setting for all companies...', 'jobster'); ?>
            </span>
        </button>
        <span class="pxp-free-submissions-response"></span>
    <?php }
endif;

if (!function_exists('jobster_set_free_submisstions_for_all_companies')): 
    function jobster_set_free_submisstions_for_all_companies() {
        check_ajax_referer('set_free_submissions_ajax_nonce', 'security');

        $options = get_option('jobster_membership_settings');

        $args = array(
            'post_type'   => 'company',
            'post_status' => 'publish'
        );

        $companies = get_posts($args);

        foreach ($companies as $company) : setup_postdata($company);
            update_post_meta(
                $company->ID, 
                'company_free_listings', 
                $options['jobster_free_submissions_no_field']
            );
        endforeach;

        wp_reset_postdata();
        wp_reset_query();

        echo json_encode(
            array(
                'set' => true,
                'message' => __('All set.', 'jobster')
            )
        );
        exit();

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_set_free_submisstions_for_all_companies',
    'jobster_set_free_submisstions_for_all_companies'
);
add_action(
    'wp_ajax_jobster_set_free_submisstions_for_all_companies',
    'jobster_set_free_submisstions_for_all_companies'
);

if (!function_exists('jobster_free_submissions_unlim_field_render')): 
    function jobster_free_submissions_unlim_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_free_submissions_unlim_field]" 
            type="checkbox" 
            <?php if (isset($options['jobster_free_submissions_unlim_field'])) {
                checked($options['jobster_free_submissions_unlim_field'], 1);
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_free_featured_submissions_no_field_render')): 
    function jobster_free_featured_submissions_no_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_free_featured_submissions_no_field]" 
            id="jobster_membership_settings[jobster_free_featured_submissions_no_field]" 
            type="text" 
            size="5" 
            value="<?php if (isset($options['jobster_free_featured_submissions_no_field'])) {
                echo esc_attr($options['jobster_free_featured_submissions_no_field']); 
            } ?>"
        >
        <?php wp_nonce_field(
            'set_free_featured_submissions_ajax_nonce',
            'pxp-set-free-featured-submissions-security',
            true
        ); ?>
        <button 
            type="button" 
            class="button button-secondary pxp-set-free-featured-submissions-btn"
        >
            <span class="pxp-set-free-featured-submissions-btn-text">
                <?php esc_html_e('Set for all existing companies', 'jobster'); ?>
            </span>
            <span class="pxp-set-free-featured-submissions-btn-loading">
                <span class="fa fa-spin fa-spinner"></span>
                <?php esc_html_e('Setting for all companies...', 'jobster'); ?>
            </span>
        </button>
        <span class="pxp-free-featured-submissions-response"></span>
    <?php }
endif;

if (!function_exists('jobster_set_free_featured_submisstions_for_all_companies')): 
    function jobster_set_free_featured_submisstions_for_all_companies() {
        check_ajax_referer('set_free_featured_submissions_ajax_nonce', 'security');

        $options = get_option('jobster_membership_settings');

        $args = array(
            'post_type'   => 'company',
            'post_status' => 'publish'
        );

        $companies = get_posts($args);

        foreach ($companies as $company) : setup_postdata($company);
            update_post_meta(
                $company->ID, 
                'company_free_featured_listings', 
                $options['jobster_free_featured_submissions_no_field']
            );
        endforeach;

        wp_reset_postdata();
        wp_reset_query();

        echo json_encode(
            array(
                'set' => true,
                'message' => __('All set.', 'jobster')
            )
        );
        exit();

        die();
    }
endif;
add_action(
    'wp_ajax_nopriv_jobster_set_free_featured_submisstions_for_all_companies',
    'jobster_set_free_featured_submisstions_for_all_companies'
);
add_action(
    'wp_ajax_jobster_set_free_featured_submisstions_for_all_companies',
    'jobster_set_free_featured_submisstions_for_all_companies'
);

if (!function_exists('jobster_payment_system_field_render')): 
    function jobster_payment_system_field_render() { 
        $options = get_option('jobster_membership_settings');

        $systems = array(
            'paypal' => __('PayPal', 'jobster'),
            'stripe' => __('Stripe', 'jobster')
        ); ?>

        <select
            name="jobster_membership_settings[jobster_payment_system_field]" 
            id="jobster_membership_settings[jobster_payment_system_field]" 
        >
            <?php foreach ($systems as $system_key => $system_value) { ?>
                <option 
                    value="<?php echo esc_attr($system_key); ?>" 
                    <?php selected(
                        isset($options['jobster_payment_system_field'])
                        && $options['jobster_payment_system_field'] == $system_key
                    ) ?>
                >
                    <?php echo esc_html($system_value); ?>
                </option>
            <?php } ?>
        </select>
    <?php }
endif;

if (!function_exists('jobster_paypal_api_version_field_render')): 
    function jobster_paypal_api_version_field_render() { 
        $options = get_option('jobster_membership_settings');

        $versions = array(
            'test' => __('Test', 'jobster'),
            'live' => __('Live', 'jobster')
        ); ?>

        <select
            name="jobster_membership_settings[jobster_paypal_api_version_field]" 
            id="jobster_membership_settings[jobster_paypal_api_version_field]" 
        >
            <?php foreach ($versions as $version_key => $version_value) { ?>
                <option 
                    value="<?php echo esc_attr($version_key); ?>" 
                    <?php selected(
                        isset($options['jobster_paypal_api_version_field'])
                        && $options['jobster_paypal_api_version_field'] == $version_key
                    ) ?>
                >
                    <?php echo esc_html($version_value); ?>
                </option>
            <?php } ?>
        </select>
    <?php }
endif;

if (!function_exists('jobster_paypal_client_id_field_render')): 
    function jobster_paypal_client_id_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_paypal_client_id_field]" 
            id="jobster_membership_settings[jobster_paypal_client_id_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_paypal_client_id_field'])) {
                echo esc_attr($options['jobster_paypal_client_id_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_paypal_client_key_field_render')): 
    function jobster_paypal_client_key_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_paypal_client_key_field]" 
            id="jobster_membership_settings[jobster_paypal_client_key_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_paypal_client_key_field'])) {
                echo esc_attr($options['jobster_paypal_client_key_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_paypal_api_username_field_render')): 
    function jobster_paypal_api_username_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_paypal_api_username_field]" 
            id="jobster_membership_settings[jobster_paypal_api_username_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_paypal_api_username_field'])) {
                echo esc_attr($options['jobster_paypal_api_username_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_paypal_api_password_field_render')): 
    function jobster_paypal_api_password_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_paypal_api_password_field]" 
            id="jobster_membership_settings[jobster_paypal_api_password_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_paypal_api_password_field'])) {
                echo esc_attr($options['jobster_paypal_api_password_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_paypal_api_signature_field_render')): 
    function jobster_paypal_api_signature_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_paypal_api_signature_field]" 
            id="jobster_membership_settings[jobster_paypal_api_signature_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_paypal_api_signature_field'])) {
                echo esc_attr($options['jobster_paypal_api_signature_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_paypal_email_field_render')): 
    function jobster_paypal_email_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_paypal_email_field]" 
            id="jobster_membership_settings[jobster_paypal_email_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_paypal_email_field'])) {
                echo esc_attr($options['jobster_paypal_email_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_stripe_pub_key_field_render')): 
    function jobster_stripe_pub_key_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_stripe_pub_key_field]" 
            id="jobster_membership_settings[jobster_stripe_pub_key_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_stripe_pub_key_field'])) {
                echo esc_attr($options['jobster_stripe_pub_key_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_stripe_secret_key_field_render')): 
    function jobster_stripe_secret_key_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_stripe_secret_key_field]" 
            id="jobster_membership_settings[jobster_stripe_secret_key_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_stripe_secret_key_field'])) {
                echo esc_attr($options['jobster_stripe_secret_key_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_stripe_endpoint_key_field_render')): 
    function jobster_stripe_endpoint_key_field_render() {
        $options = get_option('jobster_membership_settings'); ?>

        <input 
            name="jobster_membership_settings[jobster_stripe_endpoint_key_field]" 
            id="jobster_membership_settings[jobster_stripe_endpoint_key_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_stripe_endpoint_key_field'])) {
                echo esc_attr($options['jobster_stripe_endpoint_key_field']);
            } ?>"
        >
    <?php }
endif;
?>