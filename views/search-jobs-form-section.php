<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_section_search_jobs_form')):
    function jobster_get_section_search_jobs_form($form_css = '') {
        $search_submit = jobster_get_page_link('job-search.php');

        $category_tax = array( 
            'job_category'
        );
        $category_args = array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false
        );
        $category_terms = get_terms(
            $category_tax,
            $category_args
        );

        $general_settings = get_option('jobster_general_settings');
        $locations_field_type = isset($general_settings['jobster_location_type_field'])
                                ? $general_settings['jobster_location_type_field']
                                : 's';

        $return_string = '
            <div class="pxp-hero-form pxp-hero-form-round pxp-large mt-4 mt-lg-5 ' . esc_attr($form_css) . '">
                <form 
                    class="row gx-3 align-items-center" 
                    role="search" 
                    method="get" 
                    autocomplete="off" 
                    action="' . esc_url($search_submit) . '"
                >
                    <div class="col-12 col-lg">
                        <div class="input-group mb-3 mb-lg-0">
                            <span class="input-group-text">
                                <span class="fa fa-search"></span>
                            </span>
                            <input 
                                type="text" 
                                class="form-control pxp-has-floating-label" 
                                name="keywords" 
                                id="keywords" 
                                placeholder="' . esc_attr__('Job Title or Keyword', 'jobster'). '"
                            >
                        </div>
                    </div>
                    <div class="col-12 col-lg pxp-has-left-border">
                        <div class="input-group mb-3 mb-lg-0">
                            <span class="input-group-text">
                                <span class="fa fa-globe"></span>
                            </span>';
        if ($locations_field_type === 'a') {
            $return_string .= '
                            <input 
                                type="hidden" 
                                class="pxp-autocomplete-value" 
                                name="location" 
                                id="location" 
                            >
                            <div class="pxp-autocomplete-wrapper">
                                <input 
                                    type="text" 
                                    class="form-control pxp-autocomplete-jobs" 
                                    id="pxp-location-auto" 
                                    placeholder="' . esc_attr__('Location', 'jobster') . '"
                                >
                            </div>';

        } else {
            $return_string .= wp_dropdown_categories(array(
                'taxonomy' => 'job_location',
                'class' => 'form-select',
                'hide_empty' => false,
                'name' => 'location',
                'id' => 'location',
                'orderby' => 'name',
                'hierarchical' => true,
                'show_option_all' => __('All Locations', 'jobster'),
                'echo' => false
            ));
        }
        $return_string .= '
                        </div>
                    </div>
                    <div class="col-12 col-lg pxp-has-left-border">
                        <div class="input-group mb-3 mb-lg-0">
                            <span class="input-group-text"><span class="fa fa-folder-o"></span></span>
                            <select 
                                class="form-select" 
                                name="category" 
                                id="category"
                            >
                                <option value="0">
                                    ' . esc_html__('All Categories', 'jobster') . '
                                </option>';
        foreach ($category_terms as $category_term) {
            $return_string .=
                                '<option 
                                        value="' . esc_attr($category_term->term_id) . '"
                                    >
                                        ' . esc_html($category_term->name). '
                                </option>';
        }
        $return_string .=
                            '</select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-auto">
                        <button>
                            ' . esc_html__('Find Jobs', 'jobster') . '
                        </button>
                    </div>
                </form>
            </div>';

        return $return_string;
    }
endif;

if (!function_exists('jobster_get_careerjet_section_search_jobs_form')):
    function jobster_get_careerjet_section_search_jobs_form($form_css = '') {
        $search_submit = jobster_get_page_link('job-search-apis.php');

        $return_string = '
            <div class="pxp-hero-form pxp-hero-form-round pxp-large mt-4 mt-lg-5 ' . esc_attr($form_css) . '">
                <form 
                    class="row gx-3 align-items-center" 
                    role="search" 
                    method="get" 
                    action="' . esc_url($search_submit) . '"
                >
                    <div class="col-12 col-lg">
                        <div class="input-group mb-3 mb-lg-0">
                            <span class="input-group-text">
                                <span class="fa fa-search"></span>
                            </span>
                            <input 
                                type="text" 
                                class="form-control pxp-has-floating-label" 
                                name="keywords" 
                                id="keywords" 
                                placeholder="' . esc_attr__('Job Title or Keyword', 'jobster') . '"
                            >
                        </div>
                    </div>
                    <div class="col-12 col-lg pxp-has-left-border">
                        <div class="input-group mb-3 mb-lg-0">
                            <span class="input-group-text">
                                <span class="fa fa-globe"></span>
                            </span>
                            <input 
                                type="text" 
                                class="form-control pxp-has-floating-label" 
                                name="location" 
                                id="location" 
                                placeholder="' . esc_attr__('Location', 'jobster') . '"
                            >
                        </div>
                    </div>
                    <div class="col-12 col-lg-auto">
                        <button>
                            ' . esc_attr__('Find Jobs', 'jobster') . '
                        </button>
                    </div>
                </form>
            </div>';

        return $return_string;
    }
endif;