<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_appearance')):
    function jobster_admin_appearance() {
        add_settings_section(
            'jobster_appearance_section',
            __('Appearance', 'jobster'),
            'jobster_appearance_section_callback',
            'jobster_appearance_settings'
        );
    }
endif;

if (!function_exists('jobster_appearance_section_callback')): 
    function jobster_appearance_section_callback() { 
        echo '';
    }
endif;
?>