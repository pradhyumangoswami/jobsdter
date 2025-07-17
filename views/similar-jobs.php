<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_similar_jobs')):
    function jobster_get_similar_jobs() {
        global $post;

        $orig_location = wp_get_post_terms(
            $post->ID,
            'job_location', 
            array('fields' => 'ids')
        );
        $orig_category = wp_get_post_terms(
            $post->ID,
            'job_category', 
            array('fields' => 'ids')
        );
        $orig_type = wp_get_post_terms(
            $post->ID,
            'job_type', 
            array('fields' => 'ids')
        );
        $orig_level = wp_get_post_terms(
            $post->ID,
            'job_level', 
            array('fields' => 'ids')
        );

        $exclude_ids = array($post->ID);

        $args = array(
            'posts_per_page'   => 10,
            'post_type'        => 'job',
            'suppress_filters' => false,
            'post_status'      => 'publish',
            'post__not_in'     => $exclude_ids,
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

        if ($orig_location && $orig_category && $orig_type && $orig_level) {
            $args['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'job_location',
                    'field'    => 'term_id',
                    'terms'    => $orig_location[0],
                ),
                array(
                    'taxonomy' => 'job_category',
                    'field'    => 'term_id',
                    'terms'    => $orig_category[0],
                ),
                array(
                    'taxonomy' => 'job_type',
                    'field'    => 'term_id',
                    'terms'    => $orig_type[0],
                ),
                array(
                    'taxonomy' => 'job_level',
                    'field'    => 'term_id',
                    'terms'    => $orig_level[0],
                ),
            );
        }

        $similars     = new WP_Query($args);
        $similars_arr = get_object_vars($similars);

        $jobs_settings = get_option('jobster_jobs_settings');
        $show_salary =  isset($jobs_settings['jobster_job_card_salary_field']) 
                        && $jobs_settings['jobster_job_card_salary_field'] == '1';
        $show_valid =   isset($jobs_settings['jobster_job_expiration_field']) 
                        && $jobs_settings['jobster_job_expiration_field'] == '1';
        $date_format =  isset($jobs_settings['jobster_job_date_format_field']) 
                        ? $jobs_settings['jobster_job_date_format_field'] 
                        : 'date';

        if (is_array($similars_arr['posts']) 
            && count($similars_arr['posts']) > 0) {
            $jobs_settings = get_option('jobster_jobs_settings');
            $similar_title = isset($jobs_settings['jobster_job_page_similar_title_field']) 
                                ? $jobs_settings['jobster_job_page_similar_title_field'] 
                                : false;
            $similar_subtitle = isset($jobs_settings['jobster_job_page_similar_subtitle_field']) 
                                ? $jobs_settings['jobster_job_page_similar_subtitle_field'] 
                                : false;

            if (!empty($similar_title)) { ?>
                <h2 class="pxp-subsection-h2">
                    <?php echo esc_html($similar_title); ?>
                </h2>
            <?php }
            if (!empty($similar_title)) { ?>
                <p class="pxp-text-light">
                    <?php echo esc_html($similar_subtitle); ?>
                </p>
            <?php } ?>

            <div class="row mt-3 mt-md-4 pxp-animate-in pxp-animate-in-top pxp-in">
                <?php foreach ($similars_arr['posts'] as $similar) : 
                    $job_title = $similar->post_title;
                    $job_link  = get_permalink($similar->ID);
                    $job_date = get_the_date('', $similar->ID);

                    $company_id = get_post_meta(
                        $similar->ID, 'job_company', true
                    );
                    $company = ($company_id != '')
                                ? get_post($company_id) :
                                '';

                    $location = wp_get_post_terms(
                        $similar->ID, 'job_location'
                    );
                    $location_id = $location ? $location[0]->term_id : '';

                    $type = wp_get_post_terms(
                        $similar->ID, 'job_type'
                    );

                    $category = wp_get_post_terms(
                        $similar->ID, 'job_category'
                    );
                    $category_id = $category ? $category[0]->term_id : '';

                    $search_submit = jobster_get_page_link('job-search.php');

                    $salary = get_post_meta(
                        $similar->ID, 'job_salary', true
                    );
                    $valid = get_post_meta(
                        $similar->ID, 'job_valid', true
                    ); ?>

                    <div class="col-xl-6 pxp-jobs-card-2-container">
                        <div class="pxp-jobs-card-2 pxp-has-border">
                            <div class="pxp-jobs-card-2-top">
                                <?php if ($company != '') { 
                                    $company_logo_val = get_post_meta(
                                        $company_id,
                                        'company_logo',
                                        true
                                    );
                                    $company_logo = wp_get_attachment_image_src(
                                        $company_logo_val,
                                        'pxp-thmb'
                                    );
                                    if (is_array($company_logo)) { ?>
                                        <a 
                                            href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                            class="pxp-jobs-card-2-company-logo" 
                                            style="background-image: url(<?php echo esc_url($company_logo[0]); ?>);"
                                        ></a>
                                    <?php } else { ?>
                                        <a 
                                            href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                            class="pxp-jobs-card-2-company-logo pxp-no-img" 
                                        >
                                            <?php $company_name = get_the_title($company_id);
                                            echo esc_html($company_name[0]); ?>
                                        </a>
                                    <?php }
                                } ?>
                                <div class="pxp-jobs-card-2-info">
                                    <a 
                                        href="<?php echo esc_url($job_link); ?>" 
                                        class="pxp-jobs-card-2-title"
                                    >
                                        <?php echo esc_html($job_title); ?>
                                    </a>
                                    <div class="pxp-jobs-card-2-details">
                                        <?php if ($location_id != '') { 
                                            $location_link = add_query_arg(
                                                'location',
                                                $location_id,
                                                $search_submit
                                            ); ?>
                                            <a 
                                                href="<?php echo esc_url($location_link); ?>" 
                                                class="pxp-jobs-card-2-location"
                                            >
                                                <span class="fa fa-globe"></span>
                                                <?php echo esc_html($location[0]->name); ?>
                                            </a>
                                        <?php }
                                        if ($type) { ?>
                                            <div class="pxp-jobs-card-2-type">
                                                <?php echo esc_html($type[0]->name); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php if ($show_salary && $salary != '') { ?>
                                        <div class="pxp-jobs-card-2-salary">
                                            <span class="fa fa-money"></span>
                                            <?php echo esc_html($salary); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="pxp-jobs-card-2-bottom">
                                <?php if ($category_id != '') { 
                                    $category_link = add_query_arg(
                                        'category',
                                        $category_id,
                                        $search_submit
                                    ); ?>
                                    <a 
                                        href="<?php echo esc_url($category_link); ?>" 
                                        class="pxp-jobs-card-2-category"
                                    >
                                        <div class="pxp-jobs-card-2-category-label">
                                            <?php echo esc_html($category[0]->name); ?>
                                        </div>
                                    </a>
                                <?php } ?>
                                <div class="pxp-jobs-card-2-bottom-right">
                                    <span class="pxp-jobs-card-2-date pxp-text-light">
                                        <?php if ($date_format == 'time') {
                                            $time_ago = jobster_get_time_ago(
                                                strtotime($job_date)
                                            );
                                            echo esc_html($time_ago) . ' ' . esc_html__('ago', 'jobster');
                                        } else {
                                            echo esc_html($job_date);
                                        }
                                        if ($company != '') { ?>
                                            <span class="d-inline">
                                                <?php esc_html_e('by', 'jobster'); ?>
                                            </span>
                                        <?php } ?>
                                    </span> 
                                    <?php if ($company != '') { ?>
                                        <a 
                                            href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                            class="pxp-jobs-card-2-company"
                                        >
                                            <?php echo esc_html($company->post_title); ?>
                                        </a>
                                    <?php }
                                    if (!empty($valid) && $show_valid) { ?>
                                        <div class="pxp-jobs-card-2-date-small pxp-text-light mt-1">
                                            <?php esc_html_e('Valid until:', 'jobster'); ?>  <?php esc_html_e(date_i18n(__('F j, Y', 'jobster'), strtotime($valid))); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php }
    }
endif;
?>