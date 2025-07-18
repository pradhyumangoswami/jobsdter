<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

require_once 'animated_cards.php';
require_once 'image_rotator.php';
require_once 'illustration.php';
require_once 'boxed.php';
require_once 'image_bg.php';
require_once 'top_search.php';
require_once 'image_card.php';
require_once 'half_image.php';
require_once 'center_image.php';
require_once 'image_pills.php';
require_once 'right_image.php';

if (!function_exists('jobster_get_page_header')):
    function jobster_get_page_header($header_data) {
        switch ($header_data['header_type']) {
            case 'animated_cards':
                jobster_get_animated_cards_header($header_data['post_id']);
            break;
            case 'image_rotator':
                jobster_get_image_rotator_header($header_data['post_id']);
            break;
            case 'illustration':
                jobster_get_illustration_header($header_data['post_id']);
            break;
            case 'boxed':
                jobster_get_boxed_header($header_data['post_id']);
            break;
            case 'image_bg':
                jobster_get_image_bg_header($header_data['post_id']);
            break;
            case 'top_search':
                jobster_get_top_search_header($header_data['post_id']);
            break;
            case 'image_card':
                jobster_get_image_card_header($header_data['post_id']);
            break;
            case 'half_image':
                jobster_get_half_image_header($header_data['post_id']);
            break;
            case 'center_image':
                jobster_get_center_image_header($header_data['post_id']);
            break;
            case 'image_pills':
                jobster_get_image_pills_header($header_data['post_id']);
            break;
            case 'right_image':
                jobster_get_right_image_header($header_data['post_id']);
            break;
        }
    }
endif;
?>