<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_companies')):
    function jobster_admin_companies() {
        add_settings_section(
            'jobster_companies_section',
            __('Companies', 'jobster'),
            'jobster_companies_section_callback',
            'jobster_companies_settings'
        );
        add_settings_field(
            'jobster_companies_per_page_field',
            __('Companies per Page', 'jobster'),
            'jobster_companies_per_page_field_render',
            'jobster_companies_settings',
            'jobster_companies_section'
        );
        add_settings_field(
            'jobster_company_page_layout_field',
            __('Company Page Layout', 'jobster'),
            'jobster_company_page_layout_field_render',
            'jobster_companies_settings',
            'jobster_companies_section'
        );
        add_settings_field(
            'jobster_companies_new_location_field',
            __('Allow Company to Add New Profile Locations', 'jobster'),
            'jobster_companies_new_location_field_render',
            'jobster_companies_settings',
            'jobster_companies_section'
        );
        add_settings_field(
            'jobster_companies_gallery_max_field',
            __('Photo Gallery Max Number of Files', 'jobster'),
            'jobster_companies_gallery_max_field_render',
            'jobster_companies_settings',
            'jobster_companies_section'
        );
        add_settings_field(
            'jobster_companies_hide_email_field',
            __('Hide Email Address', 'jobster'),
            'jobster_companies_hide_email_field_render',
            'jobster_companies_settings',
            'jobster_companies_section'
        );
    }
endif;

if (!function_exists('jobster_companies_section_callback')): 
    function jobster_companies_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('jobster_companies_per_page_field_render')): 
    function jobster_companies_per_page_field_render() { 
        $options = get_option('jobster_companies_settings'); ?>

        <input 
            type="number" 
            step="1" 
            min="1" 
            name="jobster_companies_settings[jobster_companies_per_page_field]" 
            id="jobster_companies_settings[jobster_companies_per_page_field]" 
            style="width: 65px;" 
            value="<?php if (isset($options['jobster_companies_per_page_field'])) { 
                    echo esc_attr($options['jobster_companies_per_page_field']); 
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_company_page_layout_field_render')): 
    function jobster_company_page_layout_field_render() {
        $options = get_option('jobster_companies_settings'); ?>

        <select 
            name="jobster_companies_settings[jobster_company_page_layout_field]" 
            id="jobster_companies_settings[jobster_company_page_layout_field]"
        >
            <option 
                value="wide" 
                <?php selected(
                    isset($options['jobster_company_page_layout_field'])
                    && $options['jobster_company_page_layout_field'] == 'wide'
                ) ?>
            >
                <?php esc_html_e('Wide', 'jobster'); ?>
            </option>
            <option 
                value="side" 
                <?php selected(
                    isset($options['jobster_company_page_layout_field'])
                    && $options['jobster_company_page_layout_field'] == 'side'
                ) ?>
            >
                <?php esc_html_e('Side', 'jobster'); ?>
            </option>
            <option 
                value="center" 
                <?php selected(
                    isset($options['jobster_company_page_layout_field'])
                    && $options['jobster_company_page_layout_field'] == 'center'
                ) ?>
            >
                <?php esc_html_e('Center', 'jobster'); ?>
            </option>
        </select>
    <?php }
endif;

if (!function_exists('jobster_companies_new_location_field_render')): 
    function jobster_companies_new_location_field_render() { 
        $options = get_option('jobster_companies_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_companies_settings[jobster_companies_new_location_field]" 
            <?php if (isset($options['jobster_companies_new_location_field'])) { 
                checked($options['jobster_companies_new_location_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_companies_gallery_max_field_render')): 
    function jobster_companies_gallery_max_field_render() { 
        $options = get_option('jobster_companies_settings'); ?>

        <input 
            type="number" 
            step="1" 
            min="1" 
            name="jobster_companies_settings[jobster_companies_gallery_max_field]" 
            id="jobster_companies_settings[jobster_companies_gallery_max_field]" 
            style="width: 65px;" 
            value="<?php if (isset($options['jobster_companies_gallery_max_field'])) { 
                    echo esc_attr($options['jobster_companies_gallery_max_field']); 
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_companies_hide_email_field_render')): 
    function jobster_companies_hide_email_field_render() { 
        $options = get_option('jobster_companies_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_companies_settings[jobster_companies_hide_email_field]" 
            <?php if (isset($options['jobster_companies_hide_email_field'])) { 
                checked($options['jobster_companies_hide_email_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;
?>