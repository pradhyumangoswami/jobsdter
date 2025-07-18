<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_search_candidates_form')):
    function jobster_get_search_candidates_form($pos = 'top') {
        $search_submit = jobster_get_page_link('candidate-search.php');

        $keywords = isset($_GET['keywords'])
                    ? stripslashes(sanitize_text_field($_GET['keywords']))
                    : '';
        $location = isset($_GET['location'])
                    ? stripslashes(sanitize_text_field($_GET['location']))
                    : '';
        $industry = isset($_GET['industry']) 
                    ? stripslashes(sanitize_text_field($_GET['industry']))
                    : '';

        $sort = isset($_GET['sort'])
                ? sanitize_text_field($_GET['sort'])
                : 'asc';

        $general_settings = get_option('jobster_general_settings');
        $locations_field_type = isset($general_settings['jobster_location_type_field'])
                                ? $general_settings['jobster_location_type_field']
                                : 's';

        if ($pos == 'top') { ?>
            <div class="pxp-hero-form pxp-hero-form-round pxp-large mt-3 mt-lg-4">
                <form 
                    id="pxp-candidates-page-search-form" 
                    class="row gx-3 align-items-center" 
                    role="search" 
                    method="get" 
                    autocomplete="off" 
                    action="<?php echo esc_url($search_submit); ?>"
                >
                    <input 
                        type="hidden" 
                        name="sort" 
                        id="sort" 
                        value="<?php echo esc_attr($sort); ?>" 
                        autocomplete="off"
                    >
                    <div class="col-12 col-lg">

                        <!-- Keywords field -->

                        <div class="input-group mb-3 mb-lg-0">
                            <span class="input-group-text">
                                <span class="fa fa-search"></span>
                            </span>
                            <input 
                                type="text" 
                                name="keywords" 
                                id="keywords" 
                                class="form-control" 
                                value="<?php echo esc_attr($keywords); ?>"
                                placeholder="<?php esc_attr_e('Candidate Name or Keyword', 'jobster'); ?>"
                            >
                        </div>
                    </div>
                    <div class="col-12 col-lg pxp-has-left-border">

                        <!-- Location field -->

                        <div class="input-group mb-3 mb-lg-0">
                            <span class="input-group-text">
                                <span class="fa fa-globe"></span>
                            </span>
                            <?php if ($locations_field_type === 'a') {
                                $location_term = get_term($location);
                                $location_name = '';
                                if (!empty($location_term) && !is_wp_error($location_term)) {
                                    $location_name = $location_term->name;
                                } ?>
                                <input 
                                    type="hidden" 
                                    class="pxp-autocomplete-value" 
                                    name="location" 
                                    id="location" 
                                    value="<?php echo esc_attr($location); ?>"
                                >
                                <div class="pxp-autocomplete-wrapper">
                                    <input 
                                        type="text" 
                                        class="form-control pxp-autocomplete-candidates" 
                                        id="pxp-location-auto" 
                                        placeholder="<?php esc_attr_e('Location', 'jobster'); ?>" 
                                        value="<?php echo esc_attr($location_name); ?>"
                                    >
                                </div>
                            <?php } else {
                                wp_dropdown_categories(array(
                                    'taxonomy' => 'candidate_location',
                                    'class' => 'form-select',
                                    'hide_empty' => false,
                                    'name' => 'location',
                                    'id' => 'location',
                                    'selected' => $location,
                                    'orderby' => 'name',
                                    'hierarchical' => true,
                                    'show_option_all' => __('All Locations', 'jobster')
                                ));
                            } ?>
                        </div>
                    </div>
                    <div class="col-12 col-lg pxp-has-left-border">

                        <!-- Industry field -->

                        <?php $industry_tax = array( 
                            'candidate_industry'
                        );
                        $industry_args = array(
                            'orderby'    => 'name',
                            'order'      => 'ASC',
                            'hide_empty' => false
                        );
                        $industry_terms = get_terms(
                            $industry_tax,
                            $industry_args
                        ); ?>

                        <div class="input-group mb-3 mb-lg-0">
                            <span class="input-group-text">
                                <span class="fa fa-folder-o"></span>
                            </span>
                            <select 
                                class="form-select" 
                                name="industry" 
                                id="industry"
                            >
                                <option value="0">
                                    <?php esc_html_e('All Industries', 'jobster'); ?>
                                </option>
                                <?php foreach ($industry_terms as $industry_term) { ?>
                                    <option 
                                        value="<?php echo esc_attr($industry_term->term_id);?>" 
                                        <?php selected($industry == $industry_term->term_id); ?>
                                    >
                                        <?php echo esc_html($industry_term->name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-auto">
                        <button>
                            <?php esc_html_e('Find Candidates', 'jobster') ?>
                        </button>
                    </div>
                </form>
            </div>
        <?php } else { ?>
            <div class="pxp-candidates-list-side-filter">
                <div class="pxp-list-side-filter-header d-flex d-lg-none">
                    <div class="pxp-list-side-filter-header-label">
                        <?php esc_html_e('Search Candidates', 'jobster'); ?>
                    </div>
                    <a role="button"><span class="fa fa-sliders"></span></a>
                </div>
                <div class="mt-4 mt-lg-0 d-lg-block pxp-list-side-filter-panel">
                    <form 
                        id="pxp-candidates-page-search-form" 
                        role="search" 
                        method="get" 
                        autocomplete="off" 
                        action="<?php echo esc_url($search_submit); ?>"
                    >
                        <input 
                            type="hidden" 
                            name="sort" 
                            id="sort" 
                            value="<?php echo esc_attr($sort); ?>" 
                            autocomplete="off"
                        >

                        <!-- Keywords field -->

                        <h3>
                            <?php esc_html_e('Search by Keywords', 'jobster'); ?>
                        </h3>
                        <div class="mt-2 mt-lg-3">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <span class="fa fa-search"></span>
                                </span>
                                <input 
                                    type="text" 
                                    name="keywords" 
                                    id="keywords" 
                                    class="form-control" 
                                    value="<?php echo esc_attr($keywords); ?>"
                                    placeholder="<?php esc_attr_e('Candidate Name or Keyword', 'jobster'); ?>"
                                >
                            </div>
                        </div>

                        <!-- Location field -->

                        <h3 class="mt-3 mt-lg-4">
                            <?php esc_html_e('Location', 'jobster'); ?>
                        </h3>
                        <div class="input-group mt-2 mt-lg-3">
                            <span class="input-group-text">
                                <span class="fa fa-globe"></span>
                            </span>
                            <?php if ($locations_field_type === 'a') {
                                $location_term = get_term($location);
                                $location_name = '';
                                if (!empty($location_term) && !is_wp_error($location_term)) {
                                    $location_name = $location_term->name;
                                } ?>
                                <input 
                                    type="hidden" 
                                    class="pxp-autocomplete-value" 
                                    name="location" 
                                    id="location" 
                                    value="<?php echo esc_attr($location); ?>"
                                >
                                <div class="pxp-autocomplete-wrapper">
                                    <input 
                                        type="text" 
                                        class="form-control pxp-autocomplete-candidates" 
                                        id="pxp-location-auto" 
                                        placeholder="<?php esc_attr_e('Location', 'jobster'); ?>" 
                                        value="<?php echo esc_attr($location_name); ?>"
                                    >
                                </div>
                            <?php } else {
                                wp_dropdown_categories(array(
                                    'taxonomy' => 'candidate_location',
                                    'class' => 'form-select',
                                    'hide_empty' => false,
                                    'name' => 'location',
                                    'id' => 'location',
                                    'selected' => $location,
                                    'orderby' => 'name',
                                    'hierarchical' => true,
                                    'show_option_all' => __('All Locations', 'jobster')
                                ));
                            } ?>
                        </div>

                        <!-- Industry field -->

                        <?php $industry_tax = array( 
                            'candidate_industry'
                        );
                        $industry_args = array(
                            'orderby'    => 'name',
                            'order'      => 'ASC',
                            'hide_empty' => false
                        );
                        $industry_terms = get_terms(
                            $industry_tax,
                            $industry_args
                        ); ?>

                        <h3 class="mt-3 mt-lg-4">
                            <?php esc_html_e('Industry', 'jobster'); ?>
                        </h3>
                        <div class="input-group mt-2 mt-lg-3">
                            <span class="input-group-text">
                                <span class="fa fa-folder-o"></span>
                            </span>
                            <select 
                                class="form-select" 
                                name="industry" 
                                id="industry"
                            >
                                <option value="0">
                                    <?php esc_html_e('All Industries', 'jobster'); ?>
                                </option>
                                <?php foreach ($industry_terms as $industry_term) { ?>
                                    <option 
                                        value="<?php echo esc_attr($industry_term->term_id);?>" 
                                        <?php selected($industry == $industry_term->term_id); ?>
                                    >
                                        <?php echo esc_html($industry_term->name); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mt-3 mt-lg-4">
                            <button><?php esc_html_e('Find Candidates', 'jobster') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        <?php }
    }
endif;