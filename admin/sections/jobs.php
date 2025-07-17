<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_jobs')):
    function jobster_admin_jobs() {
        add_settings_section(
            'jobster_jobs_section',
            __('Jobs', 'jobster'),
            'jobster_jobs_section_callback',
            'jobster_jobs_settings'
        );
        add_settings_field(
            'jobster_jobs_per_page_field',
            __('Jobs per Page', 'jobster'),
            'jobster_jobs_per_page_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_jobs_featured_label_field',
            __('Featured Label', 'jobster'),
            'jobster_jobs_featured_label_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_page_layout_field',
            __('Job Page Layout', 'jobster'),
            'jobster_job_page_layout_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_page_similar_field',
            __('Show Similar Jobs on Job Page', 'jobster'),
            'jobster_job_page_similar_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_card_salary_field',
            __('Show Salary on Job Cards', 'jobster'),
            'jobster_job_card_salary_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_form_hide_salary_field',
            __('Hide Salary Field From Submit Job Form', 'jobster'),
            'jobster_job_form_hide_salary_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_page_similar_title_field',
            __('Similar Jobs Title on Job Page', 'jobster'),
            'jobster_job_page_similar_title_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_page_similar_subtitle_field',
            __('Similar Jobs Subtitle on Job Page', 'jobster'),
            'jobster_job_page_similar_subtitle_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_anonymous_apply_field',
            __('Allow Candidates to Apply to Jobs Without Being Registered', 'jobster'),
            'jobster_job_anonymous_apply_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_apply_external_login_field',
            __('Candidate has to Signin Before Applying to External Job URL', 'jobster'),
            'jobster_job_apply_external_login_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_jobs_new_location_field',
            __('Allow Companies to Add New Job Locations', 'jobster'),
            'jobster_jobs_new_location_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_type_optional_field',
            __('Set Type of Employment Field Optional', 'jobster'),
            'jobster_job_type_optional_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_experience_optional_field',
            __('Set Experience Level Field Optional', 'jobster'),
            'jobster_job_experience_optional_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_expiration_field',
            __('Display Job Expiration Date', 'jobster'),
            'jobster_job_expiration_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_validity_period_field',
            __('Job Validity Period', 'jobster'),
            'jobster_job_validity_period_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_date_format_field',
            __('Job Date Format', 'jobster'),
            'jobster_job_date_format_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_approval_field',
            __('Enable Job Posting Approval Process', 'jobster'),
            'jobster_job_approval_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
        add_settings_field(
            'jobster_job_alert_field',
            __('Enable Job Alerts', 'jobster'),
            'jobster_job_alert_field_render',
            'jobster_jobs_settings',
            'jobster_jobs_section'
        );
    }
endif;

if (!function_exists('jobster_jobs_section_callback')): 
    function jobster_jobs_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('jobster_jobs_per_page_field_render')): 
    function jobster_jobs_per_page_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="number" 
            step="1" 
            min="1" 
            name="jobster_jobs_settings[jobster_jobs_per_page_field]" 
            id="jobster_jobs_settings[jobster_jobs_per_page_field]" 
            style="width: 65px;" 
            value="<?php if (isset($options['jobster_jobs_per_page_field'])) { 
                    echo esc_attr($options['jobster_jobs_per_page_field']); 
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_jobs_featured_label_field_render')): 
    function jobster_jobs_featured_label_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="text" 
            name="jobster_jobs_settings[jobster_jobs_featured_label_field]" 
            id="jobster_jobs_settings[jobster_jobs_featured_label_field]" 
            style="width: 25%;" 
            value="<?php if (isset($options['jobster_jobs_featured_label_field'])) {
                    echo esc_attr($options['jobster_jobs_featured_label_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_job_page_layout_field_render')): 
    function jobster_job_page_layout_field_render() {
        $options = get_option('jobster_jobs_settings'); ?>

        <select 
            name="jobster_jobs_settings[jobster_job_page_layout_field]" 
            id="jobster_jobs_settings[jobster_job_page_layout_field]"
        >
            <option 
                value="wide" 
                <?php selected(
                    isset($options['jobster_job_page_layout_field'])
                    && $options['jobster_job_page_layout_field'] == 'wide'
                ) ?>
            >
                <?php esc_html_e('Wide', 'jobster'); ?>
            </option>
            <option 
                value="side" 
                <?php selected(
                    isset($options['jobster_job_page_layout_field'])
                    && $options['jobster_job_page_layout_field'] == 'side'
                ) ?>
            >
                <?php esc_html_e('Side', 'jobster'); ?>
            </option>
            <option 
                value="center" 
                <?php selected(
                    isset($options['jobster_job_page_layout_field'])
                    && $options['jobster_job_page_layout_field'] == 'center'
                ) ?>
            >
                <?php esc_html_e('Center', 'jobster'); ?>
            </option>
        </select>
    <?php }
endif;

if (!function_exists('jobster_job_page_similar_field_render')): 
    function jobster_job_page_similar_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_job_page_similar_field]" 
            <?php if (isset($options['jobster_job_page_similar_field'])) { 
                checked($options['jobster_job_page_similar_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_job_card_salary_field_render')): 
    function jobster_job_card_salary_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_job_card_salary_field]" 
            <?php if (isset($options['jobster_job_card_salary_field'])) { 
                checked($options['jobster_job_card_salary_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_job_form_hide_salary_field_render')): 
    function jobster_job_form_hide_salary_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_job_form_hide_salary_field]" 
            <?php if (isset($options['jobster_job_form_hide_salary_field'])) { 
                checked($options['jobster_job_form_hide_salary_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_job_page_similar_title_field_render')): 
    function jobster_job_page_similar_title_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="text" 
            name="jobster_jobs_settings[jobster_job_page_similar_title_field]" 
            id="jobster_jobs_settings[jobster_job_page_similar_title_field]" 
            style="width: 50%;" 
            value="<?php if (isset($options['jobster_job_page_similar_title_field'])) {
                    echo esc_attr($options['jobster_job_page_similar_title_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_job_page_similar_subtitle_field_render')): 
    function jobster_job_page_similar_subtitle_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="text" 
            name="jobster_jobs_settings[jobster_job_page_similar_subtitle_field]" 
            id="jobster_jobs_settings[jobster_job_page_similar_subtitle_field]" 
            style="width: 50%;" 
            value="<?php if (isset($options['jobster_job_page_similar_subtitle_field'])) {
                    echo esc_attr($options['jobster_job_page_similar_subtitle_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_job_anonymous_apply_field_render')): 
    function jobster_job_anonymous_apply_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_job_anonymous_apply_field]" 
            <?php if (isset($options['jobster_job_anonymous_apply_field'])) { 
                checked($options['jobster_job_anonymous_apply_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_job_apply_external_login_field_render')): 
    function jobster_job_apply_external_login_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_job_apply_external_login_field]" 
            <?php if (isset($options['jobster_job_apply_external_login_field'])) { 
                checked($options['jobster_job_apply_external_login_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_jobs_new_location_field_render')): 
    function jobster_jobs_new_location_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_jobs_new_location_field]" 
            <?php if (isset($options['jobster_jobs_new_location_field'])) { 
                checked($options['jobster_jobs_new_location_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_job_type_optional_field_render')): 
    function jobster_job_type_optional_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_job_type_optional_field]" 
            <?php if (isset($options['jobster_job_type_optional_field'])) { 
                checked($options['jobster_job_type_optional_field'], 1); 
            } ?> 
            value="1"
        >
        <p style="font-size: 12px;">
            <i><?php esc_html_e('This option is used on submit new job form.', 'jobster'); ?></i>
        </p>
    <?php }
endif;

if (!function_exists('jobster_job_experience_optional_field_render')): 
    function jobster_job_experience_optional_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_job_experience_optional_field]" 
            <?php if (isset($options['jobster_job_experience_optional_field'])) { 
                checked($options['jobster_job_experience_optional_field'], 1); 
            } ?> 
            value="1"
        >
        <p style="font-size: 12px;">
            <i><?php esc_html_e('This option is used on submit new job form.', 'jobster'); ?></i>
        </p>
    <?php }
endif;

if (!function_exists('jobster_job_expiration_field_render')): 
    function jobster_job_expiration_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_job_expiration_field]" 
            <?php if (isset($options['jobster_job_expiration_field'])) { 
                checked($options['jobster_job_expiration_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_job_validity_period_field_render')): 
    function jobster_job_validity_period_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="number" 
            step="1" 
            min="1" 
            name="jobster_jobs_settings[jobster_job_validity_period_field]" 
            id="jobster_jobs_settings[jobster_job_validity_period_field]" 
            style="width: 65px;" 
            value="<?php if (isset($options['jobster_job_validity_period_field'])) { 
                    echo esc_attr($options['jobster_job_validity_period_field']); 
                } ?>" 
        />
        <p style="font-size: 12px;">
            <i><?php esc_html_e('Number of days a job post will be available.', 'jobster'); ?></i>
        </p>
    <?php }
endif;

if (!function_exists('jobster_job_date_format_field_render')): 
    function jobster_job_date_format_field_render() {
        $options = get_option('jobster_jobs_settings'); ?>

        <select 
            name="jobster_jobs_settings[jobster_job_date_format_field]" 
            id="jobster_jobs_settings[jobster_job_date_format_field]"
        >
            <option 
                value="date" 
                <?php selected(
                    isset($options['jobster_job_date_format_field'])
                    && $options['jobster_job_date_format_field'] == 'date'
                ) ?>
            >
                <?php esc_html_e('Date', 'jobster'); ?>
            </option>
            <option 
                value="time" 
                <?php selected(
                    isset($options['jobster_job_date_format_field'])
                    && $options['jobster_job_date_format_field'] == 'time'
                ) ?>
            >
                <?php esc_html_e('Time ago', 'jobster'); ?>
            </option>
        </select>
    <?php }
endif;

if (!function_exists('jobster_job_approval_field_render')): 
    function jobster_job_approval_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_job_approval_field]" 
            <?php if (isset($options['jobster_job_approval_field'])) { 
                checked($options['jobster_job_approval_field'], 1); 
            } ?> 
            value="1"
        >
        <p style="font-size: 12px;">
            <i><?php esc_html_e('This option is applied only when Payment Type is Disabled.', 'jobster'); ?></i>
        </p>
    <?php }
endif;

if (!function_exists('jobster_job_alert_field_render')): 
    function jobster_job_alert_field_render() { 
        $options = get_option('jobster_jobs_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_jobs_settings[jobster_job_alert_field]" 
            <?php if (isset($options['jobster_job_alert_field'])) { 
                checked($options['jobster_job_alert_field'], 1); 
            } ?> 
            value="1"
        >
        <p style="font-size: 12px;">
            <i><?php esc_html_e('Notify candidates when new jobs are posted, according to their preferences.', 'jobster'); ?></i>
        </p>
    <?php }
endif;

if (!function_exists('jobster_candidates_update_alerts')): 
    function jobster_candidates_update_alerts($option_name, $old_value, $value) {
        if ($option_name == 'jobster_jobs_settings') {
            $old_val = isset($old_value['jobster_job_alert_field']) ? $old_value['jobster_job_alert_field'] : '';
            $val = isset($value['jobster_job_alert_field']) ? $value['jobster_job_alert_field'] : '';

            if ($val == '1' && empty($old_val)) {
                // Enable job alerts for all candidates
                $candidates = get_posts(array(
                    'posts_per_page' => -1,
                    'post_type' => 'candidate',
                ));
                foreach($candidates as $candidate) {
                    update_post_meta($candidate->ID, 'candidate', true);
                    update_post_meta(
                        $candidate->ID,
                        'candidate_job_alerts',
                        '1'
                    );
                }
            }
            if (empty($val) && $old_val == '1') {
                // Disable job alerts for all candidates
                $candidates = get_posts(array(
                    'posts_per_page' => -1,
                    'post_type' => 'candidate',
                ));
                foreach($candidates as $candidate) {
                    update_post_meta($candidate->ID, 'candidate', true);
                    update_post_meta(
                        $candidate->ID,
                        'candidate_job_alerts',
                        ''
                    );
                }
            }
        }
    }
endif;
add_action('updated_option', 'jobster_candidates_update_alerts', 10, 3);
?>