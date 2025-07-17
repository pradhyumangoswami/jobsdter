<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_search_jobs')): 
    function jobster_search_jobs() {
        $keywords = isset($_GET['keywords'])
                    ? stripslashes(sanitize_text_field($_GET['keywords']))
                    : '';
        $location = isset($_GET['location'])
                    ? stripslashes(sanitize_text_field($_GET['location']))
                    : '';
        $category = isset($_GET['category']) 
                    ? stripslashes(sanitize_text_field($_GET['category']))
                    : '';
        $type     = isset($_GET['type'])
                    ? stripslashes(sanitize_text_field($_GET['type']))
                    : '';
        $level    = isset($_GET['level'])
                    ? stripslashes(sanitize_text_field($_GET['level']))
                    : '';

        $settings = get_option('jobster_jobs_settings');
        $jobs_per_page = isset($settings['jobster_jobs_per_page_field'])
                        ? $settings['jobster_jobs_per_page_field']
                        : 10;
        $sort = isset($_GET['sort'])
                ? sanitize_text_field($_GET['sort'])
                : 'newest';

        global $paged;

        if (is_front_page()) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }

        $args = array(
            'posts_per_page' => $jobs_per_page,
            'paged'          => $paged,
            's'              => $keywords,
            'post_type'      => 'job',
            'post_status'    => 'publish',
            'meta_query' => array(
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
        );

        if ($sort == 'newest') {
            $args['meta_key'] = 'job_featured';
            $args['orderby'] = array('meta_value_num' => 'DESC', 'date' => 'DESC', 'ID' => 'DESC');
        } elseif ($sort == 'oldest') {
            $args['meta_key'] = 'job_featured';
            $args['orderby'] = array('meta_value_num' => 'DESC', 'date' => 'ASC', 'ID' => 'ASC');
        } else {
            $args['meta_key'] = 'job_featured';
            $args['orderby'] = array('meta_value_num' => 'DESC', 'date' => 'DESC', 'ID' => 'DESC');
        }

        $args['tax_query'] = array('relation' => 'AND');

        if ($location != '' && $location != '0') {
            array_push($args['tax_query'], array(
                'taxonomy' => 'job_location',
                'field'    => 'term_id',
                'terms'    => $location,
            ));
        }

        if ($category != '' && $category != '0') {
            array_push($args['tax_query'], array(
                'taxonomy' => 'job_category',
                'field'    => 'term_id',
                'terms'    => $category,
            ));
        }

        if ($type != '') {
            $types = explode(',', $type);

            array_push($args['tax_query'], array(
                'taxonomy' => 'job_type',
                'field'    => 'term_id',
                'terms'    => $types,
            ));
        }

        if ($level != '') {
            $levels = explode(',', $level);

            array_push($args['tax_query'], array(
                'taxonomy' => 'job_level',
                'field'    => 'term_id',
                'terms'    => $levels,
            ));
        }

        $query = new WP_Query($args);
        wp_reset_postdata();

        if ($keywords != '') {
            jobster_save_jobs_search_data($query, $keywords);
        }

        return $query;
    }
endif;

if (!function_exists('jobster_save_jobs_search_data')):
    function jobster_save_jobs_search_data($query, $keywords) {
        $total_jobs = $query->found_posts;

        if ($total_jobs > 0 && $keywords != '') {
            $popular_searches = get_option('jobster_popular_searches');

            if (is_array($popular_searches)) {
                if (array_key_exists($keywords, $popular_searches)) {
                    $popular_searches[$keywords]++;
                } else {
                    $popular_searches[$keywords] = 1;
                }
            } else {
                $popular_searches = array();
                $popular_searches[$keywords] = 1;
            }

            update_option('jobster_popular_searches', $popular_searches);
        }
    }
endif;

if (!function_exists('jobster_get_popular_searches')):
    function jobster_get_popular_searches() {
        $popular_searches = get_option('jobster_popular_searches');

        if (is_array($popular_searches)) {
            arsort($popular_searches);
        } else{
            $popular_searches = array();
        }

        $last_searches = array_slice($popular_searches, 0, 10, true);

        return $last_searches;
    }
endif;
?>