<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;
get_header();

$blog_settings = get_option('jobster_blog_settings', '');

$list_design =  isset($blog_settings['jobster_blog_list_field'])
                    ? $blog_settings['jobster_blog_list_field']
                    : '';

switch ($list_design) {
    case 'cards':
        get_template_part('templates/author_cards');
        break;
    case 'list':
        get_template_part('templates/author_list');
        break;
    case 'boxed':
        get_template_part('templates/author_boxed');
        break;
    default:
        get_template_part('templates/author_cards');
        break;
}

get_footer(); ?>