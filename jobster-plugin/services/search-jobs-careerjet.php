<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_search_careerjet_jobs')): 
    function jobster_search_careerjet_jobs($locale, $aff_id, $sort) {
        $keywords = isset($_GET['keywords'])
                    ? stripslashes(sanitize_text_field($_GET['keywords']))
                    : '';
        $location = isset($_GET['location'])
                    ? stripslashes(sanitize_text_field($_GET['location']))
                    : '';
        $type     = isset($_GET['type'])
                    ? stripslashes(sanitize_text_field($_GET['type']))
                    : '';
        $period =   isset($_GET['period']) 
                    ? stripslashes(sanitize_text_field($_GET['period']))
                    : '';

        $settings = get_option('jobster_jobs_settings');
        $jobs_per_page = isset($settings['jobster_jobs_per_page_field'])
                        ? $settings['jobster_jobs_per_page_field']
                        : 10;

        global $paged;

        if (is_front_page()) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }

        $args = array(
            'pagesize' => $jobs_per_page,
            'page' => $paged,
            'affid' => $aff_id
        );

        if (!empty($keywords)) $args['keywords'] = $keywords;
        if (!empty($location)) $args['location'] = $location;
        if (!empty($sort)) $args['sort'] = $sort;
        if (!empty($type)) $args['contracttype'] = $type;
        if (!empty($period)) $args['contractperiod'] = $period;

        $api = new Careerjet_API($locale);

        $result = $api->search($args);

        if ($keywords != '') {
            $jobs = property_exists($result, 'jobs') ? $result->jobs : array();
            jobster_save_careerjet_jobs_search_data($jobs, $keywords);
        }

        if ($result->type == 'JOBS') {
            return $result;
        }

        return false;
    }
endif;

if (!function_exists('jobster_save_careerjet_jobs_search_data')):
    function jobster_save_careerjet_jobs_search_data($jobs, $keywords) {
        if ($jobs > 0 && $keywords != '') {
            $popular_searches = get_option('jobster_careerjet_popular_searches');

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

            update_option('jobster_careerjet_popular_searches', $popular_searches);
        }
    }
endif;

if (!function_exists('jobster_get_careerjet_popular_searches')):
    function jobster_get_careerjet_popular_searches() {
        $popular_searches = get_option('jobster_careerjet_popular_searches');

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