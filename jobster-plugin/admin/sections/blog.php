<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_blog')):
    function jobster_admin_blog() {
        add_settings_section(
            'jobster_blog_section',
            __('Blog', 'jobster'),
            'jobster_blog_section_callback',
            'jobster_blog_settings'
        );
        add_settings_field(
            'jobster_blog_title_field',
            __('Blog Page Title', 'jobster'),
            'jobster_blog_title_field_render',
            'jobster_blog_settings',
            'jobster_blog_section'
        );
        add_settings_field(
            'jobster_blog_subtitle_field',
            __('Blog Page Subtitle', 'jobster'),
            'jobster_blog_subtitle_field_render',
            'jobster_blog_settings',
            'jobster_blog_section'
        );
        add_settings_field(
            'jobster_blog_list_field',
            __('Articles Page Design', 'jobster'),
            'jobster_blog_list_field_render',
            'jobster_blog_settings',
            'jobster_blog_section'
        );
        add_settings_field(
            'jobster_blog_related_posts_field',
            __('Show Related Articles on Blog Post', 'jobster'),
            'jobster_blog_related_posts_field_render',
            'jobster_blog_settings',
            'jobster_blog_section'
        );
        add_settings_field(
            'jobster_blog_related_posts_title_field',
            __('Related Articles Title on Blog Post', 'jobster'),
            'jobster_blog_related_posts_title_field_render',
            'jobster_blog_settings',
            'jobster_blog_section'
        );
        add_settings_field(
            'jobster_blog_related_posts_subtitle_field',
            __('Related Articles Subtitle on Blog Post', 'jobster'),
            'jobster_blog_related_posts_subtitle_field_render',
            'jobster_blog_settings',
            'jobster_blog_section'
        );
    }
endif;

if (!function_exists('jobster_blog_section_callback')): 
    function jobster_blog_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('jobster_blog_title_field_render')): 
    function jobster_blog_title_field_render() { 
        $options = get_option('jobster_blog_settings'); ?>

        <input 
            type="text" 
            id="jobster_blog_settings[jobster_blog_title_field]" 
            name="jobster_blog_settings[jobster_blog_title_field]" 
            size="60" 
            value="<?php if (isset($options['jobster_blog_title_field'])) {
                echo esc_attr($options['jobster_blog_title_field']); 
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_blog_subtitle_field_render')): 
    function jobster_blog_subtitle_field_render() { 
        $options = get_option('jobster_blog_settings'); ?>

        <input 
            type="text" 
            id="jobster_blog_settings[jobster_blog_subtitle_field]" 
            name="jobster_blog_settings[jobster_blog_subtitle_field]" 
            size="60" 
            value="<?php if (isset($options['jobster_blog_subtitle_field'])) {
                echo esc_attr($options['jobster_blog_subtitle_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_blog_list_field_render')): 
    function jobster_blog_list_field_render() {
        $options = get_option('jobster_blog_settings'); ?>

        <select 
            name="jobster_blog_settings[jobster_blog_list_field]" 
            id="jobster_blog_settings[jobster_blog_list_field]"
        >
            <option 
                value="cards" 
                <?php selected(
                    isset($options['jobster_blog_list_field'])
                    && $options['jobster_blog_list_field'] == 'cards'
                ) ?>
            >
                <?php esc_html_e('Cards', 'jobster'); ?>
            </option>
            <option 
                value="list" 
                <?php selected(
                    isset($options['jobster_blog_list_field'])
                    && $options['jobster_blog_list_field'] == 'list'
                ) ?>
            >
                <?php esc_html_e('List', 'jobster'); ?>
            </option>
            <option 
                value="boxed" 
                <?php selected(
                    isset($options['jobster_blog_list_field'])
                    && $options['jobster_blog_list_field'] == 'boxed'
                ) ?>
            >
                <?php esc_html_e('Boxed', 'jobster'); ?>
            </option>
        </select>
    <?php }
endif;

if (!function_exists('jobster_blog_related_posts_field_render')): 
    function jobster_blog_related_posts_field_render() { 
        $options = get_option('jobster_blog_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_blog_settings[jobster_blog_related_posts_field]" 
            <?php if (isset($options['jobster_blog_related_posts_field'])) { 
                checked($options['jobster_blog_related_posts_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_blog_related_posts_title_field_render')): 
    function jobster_blog_related_posts_title_field_render() { 
        $options = get_option('jobster_blog_settings'); ?>

        <input 
            type="text" 
            id="jobster_blog_settings[jobster_blog_related_posts_title_field]" 
            name="jobster_blog_settings[jobster_blog_related_posts_title_field]" 
            size="60" 
            value="<?php if (isset($options['jobster_blog_related_posts_title_field'])) {
                echo esc_attr($options['jobster_blog_related_posts_title_field']); 
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_blog_related_posts_subtitle_field_render')): 
    function jobster_blog_related_posts_subtitle_field_render() { 
        $options = get_option('jobster_blog_settings'); ?>

        <input 
            type="text" 
            id="jobster_blog_settings[jobster_blog_related_posts_subtitle_field]" 
            name="jobster_blog_settings[jobster_blog_related_posts_subtitle_field]" 
            size="60" 
            value="<?php if (isset($options['jobster_blog_related_posts_subtitle_field'])) {
                echo esc_attr($options['jobster_blog_related_posts_subtitle_field']); 
            } ?>"
        >
    <?php }
endif;
?>