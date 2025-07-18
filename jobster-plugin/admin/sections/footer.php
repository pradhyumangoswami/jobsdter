<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_footer')): 
    function jobster_admin_footer() {
        add_settings_section(
            'jobster_footer_section', 
            __('Footer', 'jobster'), 
            'jobster_footer_section_callback', 
            'jobster_footer_settings'
        );
        add_settings_field(
            'jobster_copyright_field',
            __('Copyright Text', 'jobster'),
            'jobster_copyright_field_render',
            'jobster_footer_settings',
            'jobster_footer_section'
        );
        add_settings_field(
            'jobster_facebook_field',
            __('Facebook Link', 'jobster'),
            'jobster_facebook_field_render',
            'jobster_footer_settings',
            'jobster_footer_section'
        );
        add_settings_field(
            'jobster_twitter_field',
            __('Twitter Link', 'jobster'),
            'jobster_twitter_field_render',
            'jobster_footer_settings',
            'jobster_footer_section'
        );
        add_settings_field(
            'jobster_instagram_field',
            __('Instagram Link', 'jobster'),
            'jobster_instagram_field_render',
            'jobster_footer_settings',
            'jobster_footer_section'
        );
        add_settings_field(
            'jobster_linkedin_field',
            __('Linkedin Link', 'jobster'),
            'jobster_linkedin_field_render',
            'jobster_footer_settings',
            'jobster_footer_section'
        );
    }
endif;

if (!function_exists('jobster_footer_section_callback')): 
    function jobster_footer_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('jobster_copyright_field_render')): 
    function jobster_copyright_field_render() { 
        $options = get_option('jobster_footer_settings'); ?>

        <input 
            type="text" 
            name="jobster_footer_settings[jobster_copyright_field]" 
            id="jobster_footer_settings[jobster_copyright_field]" 
            style="width: 50%;" 
            value="<?php if (isset($options['jobster_copyright_field'])) {
                    echo esc_attr($options['jobster_copyright_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_facebook_field_render')): 
    function jobster_facebook_field_render() { 
        $options = get_option('jobster_footer_settings'); ?>

        <input 
            type="text" 
            name="jobster_footer_settings[jobster_facebook_field]" 
            id="jobster_footer_settings[jobster_facebook_field]" 
            style="width: 50%;" 
            value="<?php if (isset($options['jobster_facebook_field'])) {
                    echo esc_attr($options['jobster_facebook_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_twitter_field_render')): 
    function jobster_twitter_field_render() { 
        $options = get_option('jobster_footer_settings'); ?>

        <input 
            type="text" 
            name="jobster_footer_settings[jobster_twitter_field]" 
            id="jobster_footer_settings[jobster_twitter_field]" 
            style="width: 50%;" 
            value="<?php if (isset($options['jobster_twitter_field'])) {
                    echo esc_attr($options['jobster_twitter_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_instagram_field_render')): 
    function jobster_instagram_field_render() { 
        $options = get_option('jobster_footer_settings'); ?>

        <input 
            type="text" 
            name="jobster_footer_settings[jobster_instagram_field]" 
            id="jobster_footer_settings[jobster_instagram_field]" 
            style="width: 50%;" 
            value="<?php if (isset($options['jobster_instagram_field'])) {
                    echo esc_attr($options['jobster_instagram_field']);
                } ?>" 
        />
    <?php }
endif;

if (!function_exists('jobster_linkedin_field_render')): 
    function jobster_linkedin_field_render() { 
        $options = get_option('jobster_footer_settings'); ?>

        <input 
            type="text" 
            name="jobster_footer_settings[jobster_linkedin_field]" 
            id="jobster_footer_settings[jobster_linkedin_field]" 
            style="width: 50%;" 
            value="<?php if (isset($options['jobster_linkedin_field'])) {
                    echo esc_attr($options['jobster_linkedin_field']);
                } ?>" 
        />
    <?php }
endif;
?>