<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (function_exists('register_block_type')) {
    require_once 'job-categories.php';
    require_once 'recent-jobs.php';
    require_once 'careerjet-jobs.php';
    require_once 'featured-jobs.php';
    require_once 'featured-companies.php';
    require_once 'featured-candidates.php';
    require_once 'job-locations.php';
    require_once 'careerjet-locations.php';
    require_once 'subscribe.php';
    require_once 'services.php';
    require_once 'testimonials.php';
    require_once 'promo.php';
    require_once 'latest-articles.php';
    require_once 'featured-articles.php';
    require_once 'features.php';
    require_once 'faqs.php';
    require_once 'contact-info.php';
    require_once 'contact-form.php';
    require_once 'membership-plans.php';
    require_once 'search-jobs.php';
}

if (!function_exists('jobster_add_block_wrapper')): 
    function jobster_add_block_wrapper($block_content, $block) {
        global $post;

        if (isset($post->post_type) && $post->post_type == 'page') {
            if (!isset($block['attrs']['data_content'])) {
                $block_content = 
                    '<div class="pxp-container">' . $block_content . '</div>';
            }
        }

        return $block_content;

    }
endif;
add_filter('render_block', 'jobster_add_block_wrapper', 10, 2);
?>