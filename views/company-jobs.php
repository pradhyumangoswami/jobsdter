<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_company_jobs')):
    function jobster_get_company_jobs($is_center = false) {
        global $post;

        $orig_post = $post;

        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'job',
            'suppress_filters' => false,
            'post_status'      => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'job_company',
                    'value' => $orig_post->ID
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

        $jobs     = new WP_Query($args);
        $jobs_arr = get_object_vars($jobs);

        $card_column_class = $is_center ? '' : 'col-xl-6';

        $jobs_settings = get_option('jobster_jobs_settings');
        $show_salary =  isset($jobs_settings['jobster_job_card_salary_field']) 
                        && $jobs_settings['jobster_job_card_salary_field'] == '1';
        $show_valid =   isset($jobs_settings['jobster_job_expiration_field']) 
                        && $jobs_settings['jobster_job_expiration_field'] == '1';

        if (is_array($jobs_arr['posts']) 
            && count($jobs_arr['posts']) > 0) { ?>
            <div class="row mt-3 mt-md-4 pxp-animate-in pxp-animate-in-top pxp-in">
                <?php foreach ($jobs_arr['posts'] as $job) : 
                    $job_title = $job->post_title;
                    $job_link  = get_permalink($job->ID);
                    $job_date = get_the_date('', $job->ID);

                    $company_id = $orig_post->ID;
                    $company = ($company_id != '')
                                ? get_post($company_id) :
                                '';

                    $location = wp_get_post_terms(
                        $job->ID, 'job_location'
                    );
                    $location_id = $location ? $location[0]->term_id : '';

                    $type = wp_get_post_terms(
                        $job->ID, 'job_type'
                    );

                    $category = wp_get_post_terms(
                        $job->ID, 'job_category'
                    );
                    $category_id = $category ? $category[0]->term_id : '';

                    $salary = get_post_meta(
                        $job->ID, 'job_salary', true
                    );
                    $valid = get_post_meta(
                        $job->ID, 'job_valid', true
                    );

                    $search_submit = jobster_get_page_link('job-search.php'); ?>

                    <div class="<?php echo esc_attr($card_column_class); ?> pxp-jobs-card-2-container">
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
                                    <div>
                                        <span class="pxp-jobs-card-2-date pxp-text-light">
                                            <?php echo esc_html($job_date); ?>
                                        </span> 
                                    </div>
                                    <?php if (!empty($valid) && $show_valid) { ?>
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
        <?php } else { ?>
            <p><i><?php esc_html_e('No available jobs.', 'jobster'); ?></i></p>
        <?php }
    }
endif;
?>