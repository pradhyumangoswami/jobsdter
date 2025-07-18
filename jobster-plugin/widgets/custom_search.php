<?php 
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_custom_search_form')): 
    function jobster_custom_search_form($form) {
        $form = '
            <form role="search" method="get" id="searchform" class="searchform pxp-custom" action="' . home_url('/') . '" >
                <div>
                    <label class="screen-reader-text" for="s">' . __('Search for:', 'jobster') . '</label>
                    <input type="text" class="form-control pxp-is-custom" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __('Search by keyword', 'jobster') . '">
                    <button type="submit" aria-label="' . __('Search', 'jobster') . '">
                        <span aria-hidden="true" class="fa fa-search"></span>
                    </button>
                </div>
            </form>';

        return $form;
    }
endif;
add_filter('get_search_form', 'jobster_custom_search_form', 100);
?>