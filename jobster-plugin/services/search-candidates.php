<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_search_candidates')): 
    function jobster_search_candidates() {
        $keywords = isset($_GET['keywords'])
                    ? stripslashes(sanitize_text_field($_GET['keywords']))
                    : '';
        $location = isset($_GET['location'])
                    ? stripslashes(sanitize_text_field($_GET['location']))
                    : '';
        $industry = isset($_GET['industry']) 
                    ? stripslashes(sanitize_text_field($_GET['industry']))
                    : '';

        $settings = get_option('jobster_candidates_settings');
        $candidates_per_page = isset($settings['jobster_candidates_per_page_field'])
                        ? $settings['jobster_candidates_per_page_field']
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
            'posts_per_page' => $candidates_per_page,
            'paged'          => $paged,
            's'              => $keywords,
            'post_type'      => 'candidate',
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
                'taxonomy' => 'candidate_location',
                'field'    => 'term_id',
                'terms'    => $location,
            ));
        }

        if ($industry != '' && $industry != '0') {
            array_push($args['tax_query'], array(
                'taxonomy' => 'candidate_industry',
                'field'    => 'term_id',
                'terms'    => $industry,
            ));
        }

        $query = new WP_Query($args);
        wp_reset_postdata();

        return $query;
    }
endif;
?>