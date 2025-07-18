<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_candidates')):
    function jobster_admin_candidates() {
        add_settings_section(
            'jobster_candidates_section',
            __('Candidates', 'jobster'),
            'jobster_candidates_section_callback',
            'jobster_candidates_settings'
        );
        add_settings_field(
            'jobster_candidates_per_page_field',
            __('Candidates per Page', 'jobster'),
            'jobster_candidates_per_page_field_render',
            'jobster_candidates_settings',
            'jobster_candidates_section'
        );
        add_settings_field(
            'jobster_candidate_page_layout_field',
            __('Candidate Page Layout', 'jobster'),
            'jobster_candidate_page_layout_field_render',
            'jobster_candidates_settings',
            'jobster_candidates_section'
        );
        add_settings_field(
            'jobster_candidate_restrictions_field',
            __('Candidate Page Access Restrictions', 'jobster'),
            'jobster_candidate_restrictions_field_render',
            'jobster_candidates_settings',
            'jobster_candidates_section'
        );
        add_settings_field(
            'jobster_candidates_gallery_max_field',
            __('Gallery/Portfolio Max Number of Files', 'jobster'),
            'jobster_candidates_gallery_max_field_render',
            'jobster_candidates_settings',
            'jobster_candidates_section'
        );
        add_settings_field(
            'jobster_candidates_new_location_field',
            __('Allow Candidate to Add New Profile Locations', 'jobster'),
            'jobster_candidates_new_location_field_render',
            'jobster_candidates_settings',
            'jobster_candidates_section'
        );
        add_settings_field(
            'jobster_candidate_resume_field',
            __('Resume', 'jobster'),
            'jobster_candidate_resume_field_render',
            'jobster_candidates_settings',
            'jobster_candidates_section'
        );
        add_settings_field(
            'jobster_candidates_hide_email_field',
            __('Hide Email Address', 'jobster'),
            'jobster_candidates_hide_email_field_render',
            'jobster_candidates_settings',
            'jobster_candidates_section'
        );
    }
endif;

if (!function_exists('jobster_candidates_section_callback')): 
    function jobster_candidates_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('jobster_candidates_per_page_field_render')): 
    function jobster_candidates_per_page_field_render() { 
        $options = get_option('jobster_candidates_settings'); ?>

        <input 
            type="number" 
            step="1" 
            min="1" 
            name="jobster_candidates_settings[jobster_candidates_per_page_field]" 
            id="jobster_candidates_settings[jobster_candidates_per_page_field]" 
            style="width: 65px;" 
            value="<?php if (isset($options['jobster_candidates_per_page_field'])) { 
                    echo esc_attr($options['jobster_candidates_per_page_field']); 
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_candidate_page_layout_field_render')): 
    function jobster_candidate_page_layout_field_render() {
        $options = get_option('jobster_candidates_settings'); ?>

        <select 
            name="jobster_candidates_settings[jobster_candidate_page_layout_field]" 
            id="jobster_candidates_settings[jobster_candidate_page_layout_field]"
        >
            <option 
                value="wide" 
                <?php selected(
                    isset($options['jobster_candidate_page_layout_field'])
                    && $options['jobster_candidate_page_layout_field'] == 'wide'
                ) ?>
            >
                <?php esc_html_e('Wide', 'jobster'); ?>
            </option>
            <option 
                value="side" 
                <?php selected(
                    isset($options['jobster_candidate_page_layout_field'])
                    && $options['jobster_candidate_page_layout_field'] == 'side'
                ) ?>
            >
                <?php esc_html_e('Side', 'jobster'); ?>
            </option>
            <option 
                value="center" 
                <?php selected(
                    isset($options['jobster_candidate_page_layout_field'])
                    && $options['jobster_candidate_page_layout_field'] == 'center'
                ) ?>
            >
                <?php esc_html_e('Center', 'jobster'); ?>
            </option>
        </select>
    <?php }
endif;

if (!function_exists('jobster_candidate_restrictions_field_render')): 
    function jobster_candidate_restrictions_field_render() {
        $options = get_option('jobster_candidates_settings'); ?>

        <p>
            <i>
                <?php esc_html_e(
                    'Visibility restrictions for public access (visible only for company role)',
                    'jobster'
                ); ?>
            </i>
        </p>
        <br>
        <label 
            for="jobster_candidates_settings[jobster_candidate_restrict_list_field]"
        >
            <input 
                type="checkbox" 
                name="jobster_candidates_settings[jobster_candidate_restrict_list_field]" 
                id="jobster_candidates_settings[jobster_candidate_restrict_list_field]" 
                <?php if (isset($options['jobster_candidate_restrict_list_field'])) { 
                    checked($options['jobster_candidate_restrict_list_field'], 1); 
                } ?> 
                value="1"
            >
            <?php esc_html_e('Candidates List', 'jobster'); ?>
        </label>
        <br>
        <label 
            for="jobster_candidates_settings[jobster_candidate_restrict_profile_field]"
        >
            <input 
                type="checkbox" 
                name="jobster_candidates_settings[jobster_candidate_restrict_profile_field]" 
                id="jobster_candidates_settings[jobster_candidate_restrict_profile_field]" 
                <?php if (isset($options['jobster_candidate_restrict_profile_field'])) { 
                    checked($options['jobster_candidate_restrict_profile_field'], 1); 
                } ?> 
                value="1"
            >
            <?php esc_html_e('Candidate Profile Info', 'jobster'); ?>
        </label>
        <br>
        <label 
            for="jobster_candidates_settings[jobster_candidate_restrict_contact_field]"
        >
            <input 
                type="checkbox" 
                name="jobster_candidates_settings[jobster_candidate_restrict_contact_field]" 
                id="jobster_candidates_settings[jobster_candidate_restrict_contact_field]" 
                <?php if (isset($options['jobster_candidate_restrict_contact_field'])) { 
                    checked($options['jobster_candidate_restrict_contact_field'], 1); 
                } ?> 
                value="1"
            >
            <?php esc_html_e('Candidate Contact Info', 'jobster'); ?>
        </label>
        <br>
        <label 
            for="jobster_candidates_settings[jobster_candidate_restrict_resume_field]"
        >
            <input 
                type="checkbox" 
                name="jobster_candidates_settings[jobster_candidate_restrict_resume_field]" 
                id="jobster_candidates_settings[jobster_candidate_restrict_resume_field]" 
                <?php if (isset($options['jobster_candidate_restrict_resume_field'])) { 
                    checked($options['jobster_candidate_restrict_resume_field'], 1); 
                } ?> 
                value="1"
            >
            <?php esc_html_e('Candidate Resume', 'jobster'); ?>
        </label>
    <?php }
endif;

if (!function_exists('jobster_candidates_gallery_max_field_render')): 
    function jobster_candidates_gallery_max_field_render() { 
        $options = get_option('jobster_candidates_settings'); ?>

        <input 
            type="number" 
            step="1" 
            min="1" 
            name="jobster_candidates_settings[jobster_candidates_gallery_max_field]" 
            id="jobster_candidates_settings[jobster_candidates_gallery_max_field]" 
            style="width: 65px;" 
            value="<?php if (isset($options['jobster_candidates_gallery_max_field'])) { 
                    echo esc_attr($options['jobster_candidates_gallery_max_field']); 
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_candidates_new_location_field_render')): 
    function jobster_candidates_new_location_field_render() { 
        $options = get_option('jobster_candidates_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_candidates_settings[jobster_candidates_new_location_field]" 
            <?php if (isset($options['jobster_candidates_new_location_field'])) { 
                checked($options['jobster_candidates_new_location_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_candidate_resume_field_render')): 
    function jobster_candidate_resume_field_render() {
        $options = get_option('jobster_candidates_settings'); ?>

        <select 
            name="jobster_candidates_settings[jobster_candidate_resume_field]" 
            id="jobster_candidates_settings[jobster_candidate_resume_field]"
        >
            <option 
                value="required" 
                <?php selected(
                    isset($options['jobster_candidate_resume_field'])
                    && $options['jobster_candidate_resume_field'] == 'required'
                ) ?>
            >
                <?php esc_html_e('Required', 'jobster'); ?>
            </option>
            <option 
                value="optional" 
                <?php selected(
                    isset($options['jobster_candidate_resume_field'])
                    && $options['jobster_candidate_resume_field'] == 'optional'
                ) ?>
            >
                <?php esc_html_e('Optional', 'jobster'); ?>
            </option>
            <option 
                value="disabled" 
                <?php selected(
                    isset($options['jobster_candidate_resume_field'])
                    && $options['jobster_candidate_resume_field'] == 'disabled'
                ) ?>
            >
                <?php esc_html_e('Disabled', 'jobster'); ?>
            </option>
        </select>
    <?php }
endif;

if (!function_exists('jobster_candidates_hide_email_field_render')): 
    function jobster_candidates_hide_email_field_render() { 
        $options = get_option('jobster_candidates_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_candidates_settings[jobster_candidates_hide_email_field]" 
            <?php if (isset($options['jobster_candidates_hide_email_field'])) { 
                checked($options['jobster_candidates_hide_email_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_candidates_private_resume_write_rules')): 
    function jobster_candidates_private_resume_write_rules() {
        $home_path = get_home_path();
        $htaccess_file  = $home_path . '.htaccess';

        $rules = "<IfModule mod_rewrite.c>\n";
        $rules .= "RewriteEngine On\n";
        $rules .= "RewriteCond %{HTTP_COOKIE} !.*wordpress_logged_in.*$ [NC]\n";
        $rules .= "RewriteCond %{REQUEST_URI} ^.*?/wp-content/uploads/.*\-private.pdf$ [NC]\n";
        $rules .= "RewriteRule . http://%{HTTP_HOST}%1/wp-login.php?redirect_to=%{REQUEST_URI} [L,QSA]\n";
        $rules .= "</IfModule>";

        insert_with_markers($htaccess_file, 'Jobster', $rules);
    }
endif;

if (!function_exists('jobster_candidates_private_resume_remove_rules')): 
    function jobster_candidates_private_resume_remove_rules() {
        $home_path = get_home_path();
        $htaccess_file = $home_path . '.htaccess';

        insert_with_markers($htaccess_file, 'Jobster', array());
    }
endif;

if (!function_exists('jobster_candidates_check_private_resume_option')): 
    function jobster_candidates_check_private_resume_option($new_value, $old_value) {
        if ($new_value !== $old_value) {
            if (isset($new_value['jobster_candidate_restrict_resume_field']) && 
                    $new_value['jobster_candidate_restrict_resume_field'] == '1') {
                jobster_candidates_private_resume_write_rules();
            } else {
                jobster_candidates_private_resume_remove_rules();
            }
        }

        return $new_value;
    }
endif;
add_filter('pre_update_option_jobster_candidates_settings',
    'jobster_candidates_check_private_resume_option', 10, 2);
?>