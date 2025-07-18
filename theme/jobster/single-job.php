<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;

$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
$can_view = array_intersect($allowed_roles, $user->roles) ? true : false;

$valid = get_post_meta($post->ID, 'job_valid', true);
$today = date('Y-m-d');

if ($valid != '' && strtotime($today) > strtotime($valid) && $can_view === false) {
    wp_redirect(home_url());
    exit;
}

get_header();

$jobs_settings = get_option('jobster_jobs_settings');
$layout =   isset($jobs_settings['jobster_job_page_layout_field']) 
            ? $jobs_settings['jobster_job_page_layout_field'] 
            : 'wide';

get_template_part('templates/single_job_' . $layout);

get_footer();
?>