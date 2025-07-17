<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_search_companies')): 
    function jobster_search_companies() {
        $keywords = isset($_GET['keywords'])
                    ? stripslashes(sanitize_text_field($_GET['keywords']))
                    : '';
        $location = isset($_GET['location'])
                    ? stripslashes(sanitize_text_field($_GET['location']))
                    : '';
        $industry = isset($_GET['industry']) 
                    ? stripslashes(sanitize_text_field($_GET['industry']))
                    : '';

        $settings = get_option('jobster_companies_settings');
        $companies_per_page = isset($settings['jobster_companies_per_page_field'])
                        ? $settings['jobster_companies_per_page_field']
                        : 10;
        $sort = isset($_GET['sort'])
                ? sanitize_text_field($_GET['sort'])
                : 'asc';

        global $paged;

        if (is_front_page()) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }

        $args = array(
            'posts_per_page' => $companies_per_page,
            'paged'          => $paged,
            's'              => $keywords,
            'post_type'      => 'company',
            'post_status'    => 'publish'
        );

        if ($sort == 'asc') {
            $args['orderby'] = array(
                'title' => 'ASC',
                'date' => 'DESC',
                'ID' => 'DESC'
            );
        } elseif ($sort == 'desc') {
            $args['orderby'] = array(
                'title' => 'DESC',
                'date' => 'DESC',
                'ID' => 'DESC'
            );
        } else {
            $args['orderby'] = array(
                'title' => 'ASC',
                'date' => 'DESC',
                'ID' => 'DESC'
            );
        }

        $args['tax_query'] = array('relation' => 'AND');

        if ($location != '' && $location != '0') {
            array_push($args['tax_query'], array(
                'taxonomy' => 'company_location',
                'field'    => 'term_id',
                'terms'    => $location,
            ));
        }

        if ($industry != '' && $industry != '0') {
            array_push($args['tax_query'], array(
                'taxonomy' => 'company_industry',
                'field'    => 'term_id',
                'terms'    => $industry,
            ));
        }

        $query = new WP_Query($args);
        wp_reset_postdata();

        return $query;
    }
endif;

if (!function_exists('jobster_get_jobs_no_by_company_id')): 
    function jobster_get_jobs_no_by_company_id($company_id) {
        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'job',
            'post_status'    => 'publish',
            'meta_key'       => 'job_company',
            'meta_value'     => $company_id
        );

        $query = new WP_Query($args);
        wp_reset_postdata();

        return $query->found_posts;
    }
endif;

if (!function_exists('jobster_get_active_jobs_no_by_company_id')): 
    function jobster_get_active_jobs_no_by_company_id($company_id) {
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'job',
            'suppress_filters' => false,
            'post_status'      => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'job_company',
                    'value' => $company_id
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'job_valid',
                        'compare' => '>=',
                        'value' => date('Y-m-d'),
                        'type' => 'DATE'
                    ),
                    array(
                        'key' => 'job_valid',
                        'compare' => '==',
                        'value' => ''
                    ),
                    array(
                        'key' => 'job_valid',
                        'compare' => 'NOT EXISTS'
                    )
                )
            )
        );

        $query = new WP_Query($args);
        wp_reset_postdata();

        return $query->found_posts;
    }
endif;

if (!function_exists('jobster_get_jobs_by_company_id')): 
    function jobster_get_jobs_by_company_id($company_id) {
        $args = array(
            'posts_per_page' => -1,
            'post_type'      => 'job',
            'post_status'    => 'publish',
            'meta_key'       => 'job_company',
            'meta_value'     => $company_id
        );

        $query = new WP_Query($args);
        $jobs = get_object_vars($query);

        wp_reset_postdata();

        if (is_array($jobs['posts']) && count($jobs['posts']) > 0) {
            return $jobs['posts'];
        }

        return false;
    }
endif;

if (!function_exists('jobster_get_active_jobs_by_company_id')): 
    function jobster_get_active_jobs_by_company_id($company_id) {
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'job',
            'suppress_filters' => false,
            'post_status'      => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'job_company',
                    'value' => $company_id
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'job_valid',
                        'compare' => '>=',
                        'value' => date('Y-m-d'),
                        'type' => 'DATE'
                    ),
                    array(
                        'key' => 'job_valid',
                        'compare' => '==',
                        'value' => ''
                    ),
                    array(
                        'key' => 'job_valid',
                        'compare' => 'NOT EXISTS'
                    )
                )
            )
        );

        $query = new WP_Query($args);
        $jobs = get_object_vars($query);

        wp_reset_postdata();

        if (is_array($jobs['posts']) && count($jobs['posts']) > 0) {
            return $jobs['posts'];
        }

        return false;
    }
endif;
?>