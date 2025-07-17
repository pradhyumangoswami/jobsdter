<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_general')):
    function jobster_admin_general() {
        add_settings_section(
            'jobster_general_section',
            __('General', 'jobster'),
            'jobster_general_section_callback',
            'jobster_general_settings'
        );
        add_settings_field(
            'jobster_disable_page_preloader_field',
            __('Disable Page Preloader', 'jobster'),
            'jobster_disable_page_preloader_field_render',
            'jobster_general_settings',
            'jobster_general_section'
        );
        add_settings_field(
            'jobster_location_type_field',
            __('Location Field Type', 'jobster'),
            'jobster_location_type_field_render',
            'jobster_general_settings',
            'jobster_general_section'
        );
    }
endif;

if (!function_exists('jobster_general_section_callback')): 
    function jobster_general_section_callback() {
        echo '';
    }
endif;

if (!function_exists('jobster_disable_page_preloader_field_render')): 
    function jobster_disable_page_preloader_field_render() { 
        $options = get_option('jobster_general_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_general_settings[jobster_disable_page_preloader_field]" 
            <?php if (isset($options['jobster_disable_page_preloader_field'])) { 
                checked($options['jobster_disable_page_preloader_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_location_type_field_render')): 
    function jobster_location_type_field_render() {
        $options = get_option('jobster_general_settings'); ?>

        <select 
            name="jobster_general_settings[jobster_location_type_field]" 
            id="jobster_general_settings[jobster_location_type_field]"
        >
            <option 
                value="s" 
                <?php selected(
                    isset($options['jobster_location_type_field'])
                    && $options['jobster_location_type_field'] == 's'
                ) ?>
            >
                <?php esc_html_e('Select', 'jobster'); ?>
            </option>
            <option 
                value="a" 
                <?php selected(
                    isset($options['jobster_location_type_field'])
                    && $options['jobster_location_type_field'] == 'a'
                ) ?>
            >
                <?php esc_html_e('Autocomplete', 'jobster'); ?>
            </option>
        </select>
    <?php }
endif;
?>