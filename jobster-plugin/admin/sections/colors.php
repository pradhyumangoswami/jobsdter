<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_colors')): 
    function jobster_admin_colors() {
        add_settings_section(
            'jobster_colors_section', 
            __('Colors', 'jobster'), 
            'jobster_colors_section_callback', 
            'jobster_colors_settings'
        );
        add_settings_field(
            'jobster_text_color_field',
            __('Text', 'jobster'),
            'jobster_text_color_field_render',
            'jobster_colors_settings',
            'jobster_colors_section'
        );
        add_settings_field(
            'jobster_main_color_field',
            __('Primary', 'jobster'),
            'jobster_main_color_field_render',
            'jobster_colors_settings',
            'jobster_colors_section'
        );
        add_settings_field(
            'jobster_main_color_dark_field',
            __('Primary Dark', 'jobster'),
            'jobster_main_color_dark_field_render',
            'jobster_colors_settings',
            'jobster_colors_section'
        );
        add_settings_field(
            'jobster_main_color_light_field',
            __('Primary Light', 'jobster'),
            'jobster_main_color_light_field_render',
            'jobster_colors_settings',
            'jobster_colors_section'
        );
        add_settings_field(
            'jobster_secondary_color_field',
            __('Secondary', 'jobster'),
            'jobster_secondary_color_field_render',
            'jobster_colors_settings',
            'jobster_colors_section'
        );
        add_settings_field(
            'jobster_secondary_color_light_field',
            __('Secondary Light', 'jobster'),
            'jobster_secondary_color_light_field_render',
            'jobster_colors_settings',
            'jobster_colors_section'
        );
        add_settings_field(
            'jobster_feat_job_label_bg_field',
            __('Featured Job Label Background', 'jobster'),
            'jobster_feat_job_label_bg_field_render',
            'jobster_colors_settings',
            'jobster_colors_section'
        );
        add_settings_field(
            'jobster_feat_job_label_text_field',
            __('Featured Job Label Text', 'jobster'),
            'jobster_feat_job_label_text_field_render',
            'jobster_colors_settings',
            'jobster_colors_section'
        );
    }
endif;

if (!function_exists('jobster_colors_section_callback')): 
    function jobster_colors_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('jobster_text_color_field_render')): 
    function jobster_text_color_field_render() { 
        $options = get_option('jobster_colors_settings'); ?>

        <input 
            type="text" 
            name="jobster_colors_settings[jobster_text_color_field]" 
            id="jobster_colors_settings[jobster_text_color_field]" 
            class="pxp-color-field" 
            value="<?php if (isset($options['jobster_text_color_field'])) {
                    echo esc_attr($options['jobster_text_color_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_main_color_field_render')): 
    function jobster_main_color_field_render() { 
        $options = get_option('jobster_colors_settings'); ?>

        <input 
            type="text" 
            name="jobster_colors_settings[jobster_main_color_field]" 
            id="jobster_colors_settings[jobster_main_color_field]" 
            class="pxp-color-field pxp-hex-color" 
            value="<?php if (isset($options['jobster_main_color_field'])) {
                    echo esc_attr($options['jobster_main_color_field']);
                } ?>" 
        />
        <input 
            type="hidden" 
            name="jobster_colors_settings[jobster_main_tranparent_color_field]" 
            id="jobster_colors_settings[jobster_main_tranparent_color_field]" 
            class="pxp-rgba-color"
            value="<?php if (isset($options['jobster_main_tranparent_color_field'])) {
                    echo esc_attr($options['jobster_main_tranparent_color_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_main_color_dark_field_render')): 
    function jobster_main_color_dark_field_render() { 
        $options = get_option('jobster_colors_settings'); ?>

        <input 
            type="text" 
            name="jobster_colors_settings[jobster_main_color_dark_field]" 
            id="jobster_colors_settings[jobster_main_color_dark_field]" 
            class="pxp-color-field" 
            value="<?php if (isset($options['jobster_main_color_dark_field'])) {
                    echo esc_attr($options['jobster_main_color_dark_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_main_color_light_field_render')): 
    function jobster_main_color_light_field_render() { 
        $options = get_option('jobster_colors_settings'); ?>

        <input 
            type="text" 
            name="jobster_colors_settings[jobster_main_color_light_field]" 
            id="jobster_colors_settings[jobster_main_color_light_field]" 
            class="pxp-color-field" 
            value="<?php if (isset($options['jobster_main_color_light_field'])) {
                    echo esc_attr($options['jobster_main_color_light_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_secondary_color_field_render')): 
    function jobster_secondary_color_field_render() { 
        $options = get_option('jobster_colors_settings'); ?>

        <input 
            type="text" 
            name="jobster_colors_settings[jobster_secondary_color_field]" 
            id="jobster_colors_settings[jobster_secondary_color_field]" 
            class="pxp-color-field" 
            value="<?php if (isset($options['jobster_secondary_color_field'])) {
                    echo esc_attr($options['jobster_secondary_color_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_secondary_color_light_field_render')): 
    function jobster_secondary_color_light_field_render() { 
        $options = get_option('jobster_colors_settings'); ?>

        <input 
            type="text" 
            name="jobster_colors_settings[jobster_secondary_color_light_field]" 
            id="jobster_colors_settings[jobster_secondary_color_light_field]" 
            class="pxp-color-field" 
            value="<?php if (isset($options['jobster_secondary_color_light_field'])) {
                    echo esc_attr($options['jobster_secondary_color_light_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_feat_job_label_bg_field_render')): 
    function jobster_feat_job_label_bg_field_render() { 
        $options = get_option('jobster_colors_settings'); ?>

        <input 
            type="text" 
            name="jobster_colors_settings[jobster_feat_job_label_bg_field]" 
            id="jobster_colors_settings[jobster_feat_job_label_bg_field]" 
            class="pxp-color-field" 
            value="<?php if (isset($options['jobster_feat_job_label_bg_field'])) {
                    echo esc_attr($options['jobster_feat_job_label_bg_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_feat_job_label_text_field_render')): 
    function jobster_feat_job_label_text_field_render() { 
        $options = get_option('jobster_colors_settings'); ?>

        <input 
            type="text" 
            name="jobster_colors_settings[jobster_feat_job_label_text_field]" 
            id="jobster_colors_settings[jobster_feat_job_label_text_field]" 
            class="pxp-color-field" 
            value="<?php if (isset($options['jobster_feat_job_label_text_field'])) {
                    echo esc_attr($options['jobster_feat_job_label_text_field']);
                } ?>" 
        />
    <?php }
endif;
?>