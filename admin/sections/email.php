<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_email')): 
    function jobster_admin_email() {
        add_settings_section(
            'jobster_email_section',
            __('Email Templates', 'jobster'),
            'jobster_email_section_callback',
            'jobster_email_settings'
        );
        add_settings_field(
            'jobster_email_app_notify_field',
            __('Job Application Notification', 'jobster'),
            'jobster_email_app_notify_field_render',
            'jobster_email_settings',
            'jobster_email_section'
        );
        add_settings_field(
            'jobster_email_contact_form_section_field',
            __('Contact Form Section', 'jobster'),
            'jobster_email_contact_form_section_field_render',
            'jobster_email_settings',
            'jobster_email_section'
        );
        add_settings_field(
            'jobster_email_contact_candidate_field',
            __('Contact Candidate', 'jobster'),
            'jobster_email_contact_candidate_field_render',
            'jobster_email_settings',
            'jobster_email_section'
        );
        add_settings_field(
            'jobster_email_contact_company_field',
            __('Contact Company', 'jobster'),
            'jobster_email_contact_company_field_render',
            'jobster_email_settings',
            'jobster_email_section'
        );
        add_settings_field(
            'jobster_email_signup_notify_admin_field',
            __('Sign Up Notify Admin', 'jobster'),
            'jobster_email_signup_notify_admin_field_render',
            'jobster_email_settings',
            'jobster_email_section'
        );
        add_settings_field(
            'jobster_email_signup_notify_user_field',
            __('Sign Up Notify User', 'jobster'),
            'jobster_email_signup_notify_user_field_render',
            'jobster_email_settings',
            'jobster_email_section'
        );
        add_settings_field(
            'jobster_email_activation_notify_user_field',
            __('User Account Activation Notification', 'jobster'),
            'jobster_email_activation_notify_user_field_render',
            'jobster_email_settings',
            'jobster_email_section'
        );
        add_settings_field(
            'jobster_email_job_alerts_field',
            __('Job Alerts', 'jobster'),
            'jobster_email_job_alerts_field_render',
            'jobster_email_settings',
            'jobster_email_section'
        );
    }
endif;

if (!function_exists('jobster_email_section_callback')): 
    function jobster_email_section_callback() {
        echo '';
    }
endif;

if (!function_exists('jobster_email_app_notify_field_render')): 
    function jobster_email_app_notify_field_render() { 
        $options = get_option('jobster_email_settings'); ?>

        <p style="font-size: 12px;">
            <i>
                <?php esc_html_e('Variables to use: ','jobster'); ?>
                {JOB_TITLE}, {CANDIDATE_NAME}, {CANDIDATE_EMAIL}, {CANDIDATE_PHONE}, {CANDIDATE_MESSAGE}
            </i>
        </p>
        <br>

        <textarea 
            rows="5" 
            name="jobster_email_settings[jobster_email_app_notify_field]" 
            id="jobster_email_settings[jobster_email_app_notify_field]" 
            class="widefat textarea pxp-email-app-notify-field"
        ><?php if (isset($options['jobster_email_app_notify_field'])) {
            echo wp_unslash($options['jobster_email_app_notify_field']);
        } ?></textarea>
    <?php }
endif;

if (!function_exists('jobster_email_contact_form_section_field_render')): 
    function jobster_email_contact_form_section_field_render() { 
        $options = get_option('jobster_email_settings'); ?>

        <p style="font-size: 12px;">
            <i>
                <?php esc_html_e('Variables to use: ','jobster'); ?>
                {CLIENT_NAME}, {CLIENT_EMAIL}, {CLIENT_MESSAGE}
            </i>
        </p>
        <br>

        <textarea 
            rows="5" 
            name="jobster_email_settings[jobster_email_contact_form_section_field]" 
            id="jobster_email_settings[jobster_email_contact_form_section_field]" 
            class="widefat textarea pxp-email-contact-form-section-field"
        ><?php if (isset($options['jobster_email_contact_form_section_field'])) {
            echo wp_unslash($options['jobster_email_contact_form_section_field']);
        } ?></textarea>
    <?php }
endif;

if (!function_exists('jobster_email_contact_candidate_field_render')): 
    function jobster_email_contact_candidate_field_render() { 
        $options = get_option('jobster_email_settings'); ?>

        <p style="font-size: 12px;">
            <i>
                <?php esc_html_e('Variables to use: ','jobster'); ?>
                {CLIENT_NAME}, {CLIENT_EMAIL}, {CLIENT_MESSAGE}
            </i>
        </p>
        <br>

        <textarea 
            rows="5" 
            name="jobster_email_settings[jobster_email_contact_candidate_field]" 
            id="jobster_email_settings[jobster_email_contact_candidate_field]" 
            class="widefat textarea pxp-email-contact-candidate-field"
        ><?php if (isset($options['jobster_email_contact_candidate_field'])) {
            echo wp_unslash($options['jobster_email_contact_candidate_field']);
        } ?></textarea>
    <?php }
endif;

if (!function_exists('jobster_email_contact_company_field_render')): 
    function jobster_email_contact_company_field_render() { 
        $options = get_option('jobster_email_settings'); ?>

        <p style="font-size: 12px;">
            <i>
                <?php esc_html_e('Variables to use: ','jobster'); ?>
                {CLIENT_NAME}, {CLIENT_EMAIL}, {CLIENT_MESSAGE}
            </i>
        </p>
        <br>

        <textarea 
            rows="5" 
            name="jobster_email_settings[jobster_email_contact_company_field]" 
            id="jobster_email_settings[jobster_email_contact_company_field]" 
            class="widefat textarea pxp-email-contact-company-field"
        ><?php if (isset($options['jobster_email_contact_company_field'])) {
            echo wp_unslash($options['jobster_email_contact_company_field']);
        } ?></textarea>
    <?php }
endif;

if (!function_exists('jobster_email_signup_notify_admin_field_render')): 
    function jobster_email_signup_notify_admin_field_render() { 
        $options = get_option('jobster_email_settings'); ?>

        <p style="font-size: 12px;">
            <i>
                <?php esc_html_e('Variables to use: ','jobster'); ?>
                {USER_NAME}, {USER_EMAIL}
            </i>
        </p>
        <br>

        <textarea 
            rows="5" 
            name="jobster_email_settings[jobster_email_signup_notify_admin_field]" 
            id="jobster_email_settings[jobster_email_signup_notify_admin_field]" 
            class="widefat textarea pxp-signup-notify-admin-field"
        ><?php if (isset($options['jobster_email_signup_notify_admin_field'])) {
            echo wp_unslash($options['jobster_email_signup_notify_admin_field']);
        } ?></textarea>
    <?php }
endif;

if (!function_exists('jobster_email_signup_notify_user_field_render')): 
    function jobster_email_signup_notify_user_field_render() { 
        $options = get_option('jobster_email_settings'); ?>

        <p style="font-size: 12px;">
            <i>
                <?php esc_html_e('Variables to use: ','jobster'); ?>
                {USER_FIRSTNAME}, {USER_NAME}, {USER_PASSWORD}
            </i>
        </p>
        <br>

        <textarea 
            rows="5" 
            name="jobster_email_settings[jobster_email_signup_notify_user_field]" 
            id="jobster_email_settings[jobster_email_signup_notify_user_field]" 
            class="widefat textarea pxp-signup-notify-user-field"
        ><?php if (isset($options['jobster_email_signup_notify_user_field'])) {
            echo wp_unslash($options['jobster_email_signup_notify_user_field']);
        } ?></textarea>
    <?php }
endif;

if (!function_exists('jobster_email_activation_notify_user_field_render')): 
    function jobster_email_activation_notify_user_field_render() { 
        $options = get_option('jobster_email_settings'); ?>

        <p style="font-size: 12px;">
            <i>
                <?php esc_html_e('Variables to use: ','jobster'); ?>
                {USER_NAME}, {WEBSITE_NAME}, {ACTIVATION_URL}
            </i>
        </p>
        <br>

        <textarea 
            rows="5" 
            name="jobster_email_settings[jobster_email_activation_notify_user_field]" 
            id="jobster_email_settings[jobster_email_activation_notify_user_field]" 
            class="widefat textarea pxp-activation-notify-user-field"
        ><?php if (isset($options['jobster_email_activation_notify_user_field'])) {
            echo wp_unslash($options['jobster_email_activation_notify_user_field']);
        } ?></textarea>
    <?php }
endif;

if (!function_exists('jobster_email_job_alerts_field_render')): 
    function jobster_email_job_alerts_field_render() { 
        $options = get_option('jobster_email_settings'); ?>

        <p style="font-size: 12px;">
            <i>
                <?php esc_html_e('Variables to use: ','jobster'); ?>
                {CANDIDATE_NAME}, {JOB_TITLE}, {JOB_URL}, {COMPANY_NAME}
            </i>
        </p>
        <br>

        <textarea 
            rows="5" 
            name="jobster_email_settings[jobster_email_job_alerts_field]" 
            id="jobster_email_settings[jobster_email_job_alerts_field]" 
            class="widefat textarea pxp-email-job-alerts-field"
        ><?php if (isset($options['jobster_email_job_alerts_field'])) {
            echo wp_unslash($options['jobster_email_job_alerts_field']);
        } ?></textarea>
    <?php }
endif;
?>