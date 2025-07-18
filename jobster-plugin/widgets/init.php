<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_register_widgets')): 
    function jobster_register_widgets() {
        require 'custom_search.php';
    }
endif;
add_action('widgets_init', 'jobster_register_widgets');
?>