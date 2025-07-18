<?php
/*
Template Name: Job Search
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;
get_header();

$subtitle        = get_post_meta($post->ID, 'jobs_page_subtitle', true);
$header_align    = get_post_meta($post->ID, 'jobs_page_header_align', true);
$search_position = get_post_meta($post->ID, 'jobs_page_search_position', true);
$filter_position = get_post_meta($post->ID, 'jobs_page_filter_position', true);
$side_position   = get_post_meta($post->ID, 'jobs_page_side_position', true);
$card_design     = get_post_meta($post->ID, 'jobs_page_card_design', true);

$search_submit = jobster_get_page_link('job-search.php');

$header_align_class = ($header_align == 'center') ? 'text-center' : '';

$filter_margin = false;
if ($search_position != 'top' && $filter_position != 'top') {
    $filter_margin = true;
}

$side_order_class = '';
$content_order_class = '';
if ($side_position == 'right') {
    $side_order_class = 'order-lg-last';
    $content_order_class = 'order-lg-first';
} 

$big_card_column_class = 'col-md-6 col-lg-12 col-xl-6 col-xxl-4 pxp-jobs-card-1-container';
$small_card_column_class = 'col-xl-6 pxp-jobs-card-2-container';

$jobs_settings = get_option('jobster_jobs_settings');
$featured_label =  isset($jobs_settings['jobster_jobs_featured_label_field']) 
                    ? $jobs_settings['jobster_jobs_featured_label_field'] 
                    : __('Featured', 'jobster');
$show_salary =  isset($jobs_settings['jobster_job_card_salary_field']) 
                && $jobs_settings['jobster_job_card_salary_field'] == '1';
$show_valid =   isset($jobs_settings['jobster_job_expiration_field']) 
                && $jobs_settings['jobster_job_expiration_field'] == '1';
$date_format =  isset($jobs_settings['jobster_job_date_format_field']) 
                ? $jobs_settings['jobster_job_date_format_field'] 
                : 'date';

$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'newest';
$searched_jobs = jobster_search_jobs();
$total_jobs = $searched_jobs->found_posts; ?>

<section class="pxp-page-header-simple" style="background-color: var(--pxpMainColorLight);">
    <div class="pxp-container">
        <h1 class="<?php echo esc_attr($header_align_class); ?>">
            <?php echo get_the_title(); ?>
        </h1>
        <div class="pxp-hero-subtitle pxp-text-light <?php echo esc_attr($header_align_class); ?>">
            <?php echo esc_html($subtitle); ?>
        </div>
        <?php if (function_exists('jobster_get_search_jobs_form')
                    && $search_position == 'top') {
            jobster_get_search_jobs_form('top');
        } ?>
    </div>
</section>

<section class="mt-100">
    <div class="pxp-container">
        <?php if (  $search_position == 'top' 
                    && $filter_position == 'top') {
            jobster_get_filter_jobs_form('top');
            $big_card_column_class = 'col-md-6 col-xl-4 col-xxl-3 pxp-jobs-card-1-container';
            $small_card_column_class = 'col-xxl-6 pxp-jobs-card-2-container';
        } ?>
        <div class="row">
            <?php $content_column_class = 'col';
            if ( $search_position != 'top'
                    || $filter_position != 'top') {
                $content_column_class = 'col-lg-7 col-xl-8 col-xxl-9'; ?>
                <div class="col-lg-5 col-xl-4 col-xxl-3 <?php echo esc_attr($side_order_class); ?>">
                    <?php if ($search_position != 'top') {
                        jobster_get_search_jobs_form('side');
                    }
                    if ($filter_position != 'top') {
                        jobster_get_filter_jobs_form('side', $filter_margin);
                    } ?>
                </div>
            <?php } ?>
            <div class="<?php echo esc_attr($content_column_class); ?> <?php echo esc_attr($content_order_class); ?>">
                <?php if (  $search_position == 'side' 
                            && $filter_position == 'top') {
                    jobster_get_filter_jobs_form('top', true);
                }

                $list_top_class = ($filter_position == 'top')
                                    ? 'mt-4' : 'mt-4 mt-lg-0'; ?>

                <div class="pxp-jobs-list-top <?php echo esc_attr($list_top_class); ?>">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto">
                            <h2>
                                <span class="pxp-text-light"><?php esc_html_e('Showing', 'jobster'); ?></span> 
                                <?php echo esc_html($total_jobs); ?> 
                                <span class="pxp-text-light"><?php esc_html_e('jobs', 'jobster'); ?></span>
                            </h2>
                        </div>
                        <div class="col-auto">
                            <select class="form-select" id="pxp-sort-jobs">
                                <option 
                                    value="newest" 
                                    <?php selected($sort, 'newest'); ?>
                                >
                                    <?php esc_html_e('Newest', 'jobster'); ?>
                                </option>
                                <option 
                                    value="oldest" 
                                    <?php selected($sort, 'oldest'); ?>
                                >
                                    <?php esc_html_e('Oldest', 'jobster'); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php while ($searched_jobs->have_posts()) {
                        $searched_jobs->the_post();

                        $job_id = get_the_ID();
                        $job_link = get_permalink($job_id);
                        $job_date = get_the_date();

                        $category = wp_get_post_terms(
                            $job_id, 'job_category'
                        );
                        $category_id = $category ? $category[0]->term_id : '';
                        $category_icon = 'fa fa-folder-o';
                        if ($category_id != '') {
                            $category_icon_type = get_term_meta(
                                $category_id, 'job_category_icon_type', true
                            );
                            $category_icon = get_term_meta(
                                $category_id, 'job_category_icon', true
                            );
                        }

                        $location = wp_get_post_terms(
                            $job_id, 'job_location'
                        );
                        $location_id = $location ? $location[0]->term_id : '';

                        $type = wp_get_post_terms(
                            $job_id, 'job_type'
                        );

                        $job_cover_val = get_post_meta(
                            $job_id,
                            'job_cover',
                            true
                        );
                        $job_cover = wp_get_attachment_image_src(
                            $job_cover_val,
                            'pxp-gallery'
                        );

                        $company_id = get_post_meta(
                            $job_id, 'job_company', true
                        );
                        $company = ($company_id != '')
                                    ? get_post($company_id) :
                                    '';

                        $featured = get_post_meta(
                            $job_id, 'job_featured', true
                        );
                        $featured_class = ($featured == '1') ? 'pxp-is-featured' : '';

                        $salary = get_post_meta(
                            $job_id, 'job_salary', true
                        );
                        $valid = get_post_meta(
                            $job_id, 'job_valid', true
                        );
                        $jc1_mt_class = !empty($valid) && $show_valid ? '' : 'mt-1';
                        $jc3_mt_class = !empty($valid) && $show_valid ? '' : 'mt-2';

                        switch ($card_design) {
                            case 'big': ?>
                                <div class="<?php echo esc_attr($big_card_column_class); ?>">
                                    <div class="pxp-jobs-card-1 pxp-has-border <?php echo esc_attr($featured_class); ?>">
                                        <div class="pxp-jobs-card-1-top">
                                            <?php $title_margin_class = 'mt-0';
                                            if ($category_id != '') { 
                                                $title_margin_class = '';
                                                $category_link = add_query_arg(
                                                    'category',
                                                    $category_id,
                                                    $search_submit
                                                ); ?>
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="pxp-jobs-card-1-category"
                                                >
                                                <?php if ($category_icon_type == 'image') {
                                                        $icon_image = wp_get_attachment_image_src($category_icon, 'pxp-icon');
                                                        if (is_array($icon_image)) { ?>
                                                            <div class="pxp-jobs-card-1-category-icon-image">
                                                                <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="pxp-jobs-card-1-category-icon">
                                                                <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                            </div>
                                                        <?php }
                                                    } else { ?>
                                                        <div class="pxp-jobs-card-1-category-icon">
                                                            <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="pxp-jobs-card-1-category-label">
                                                        <?php echo esc_html($category[0]->name); ?>
                                                    </div>
                                                </a>
                                            <?php }
                                            if ($featured == '1') { ?>
                                                <div class="pxp-jobs-card-1-feat-label">
                                                    <?php echo esc_html($featured_label); ?>
                                                </div>
                                            <?php } ?>
                                            <a 
                                                href="<?php echo esc_url($job_link); ?>" 
                                                class="pxp-jobs-card-1-title <?php echo esc_attr($title_margin_class); ?>"
                                            >
                                                <?php the_title(); ?>
                                            </a>
                                            <div class="pxp-jobs-card-1-details">
                                                <?php if ($location_id != '') { 
                                                    $location_link = add_query_arg(
                                                        'location',
                                                        $location_id,
                                                        $search_submit
                                                    ); ?>
                                                    <a 
                                                        href="<?php echo esc_url($location_link); ?>" 
                                                        class="pxp-jobs-card-1-location"
                                                    >
                                                        <span class="fa fa-globe"></span>
                                                        <?php echo esc_html($location[0]->name); ?>
                                                    </a>
                                                <?php }
                                                if ($type) { ?>
                                                    <div class="pxp-jobs-card-1-type">
                                                        <?php echo esc_html($type[0]->name); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php if ($show_salary && $salary != '') { ?>
                                                <div class="pxp-jobs-card-1-salary">
                                                    <span class="fa fa-money"></span>
                                                    <?php echo esc_html($salary); ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="pxp-jobs-card-1-bottom">
                                            <div class="pxp-jobs-card-1-bottom-left">
                                                <div class="pxp-jobs-card-1-date pxp-text-light">
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
                                                </div>
                                                <?php if ($company != '') { ?>
                                                    <a 
                                                        href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                                        class="pxp-jobs-card-1-company <?php echo esc_attr($jc1_mt_class); ?>"
                                                    >
                                                        <?php echo esc_html($company->post_title); ?>
                                                    </a>
                                                <?php }
                                                if (!empty($valid) && $show_valid) { ?>
                                                    <div class="pxp-jobs-card-1-date-small pxp-text-light mt-1">
                                                        <?php esc_html_e('Valid until:', 'jobster'); ?> <?php esc_html_e(date_i18n(__('F j, Y', 'jobster'), strtotime($valid))); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
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
                                                        class="pxp-jobs-card-1-company-logo" 
                                                        style="background-image: url(<?php echo esc_url($company_logo[0]); ?>);"
                                                    ></a>
                                                <?php } else { ?>
                                                    <a 
                                                        href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                                        class="pxp-jobs-card-1-company-logo pxp-no-img" 
                                                    >
                                                        <?php $company_name = $company->post_title;
                                                        echo esc_html($company_name[0]); ?>
                                                    </a>
                                                <?php }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php break;
                            case 'small': ?>
                                <div class="<?php echo esc_attr($small_card_column_class); ?>">
                                    <div class="pxp-jobs-card-2 pxp-has-border <?php echo esc_attr($featured_class); ?>">
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
                                                        <?php $company_name = $company->post_title;
                                                        echo esc_html($company_name[0]); ?>
                                                    </a>
                                                <?php }
                                            } ?>
                                            <div class="pxp-jobs-card-2-info">
                                                <?php if ($featured == '1') { ?>
                                                    <div class="pxp-jobs-card-2-feat-label">
                                                        <?php echo esc_html($featured_label); ?>
                                                    </div>
                                                <?php } ?>
                                                <a 
                                                    href="<?php echo esc_url($job_link); ?>" 
                                                    class="pxp-jobs-card-2-title"
                                                >
                                                    <?php the_title(); ?>
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
                                </div>
                                <?php break;
                            case 'list': ?>
                                <div class="col-12">
                                    <div class="pxp-jobs-card-3 pxp-has-border <?php echo esc_attr($featured_class); ?>">
                                        <div class="row align-items-center justify-content-between">
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
                                                    <div class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-xxl-auto">
                                                        <a 
                                                            href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                                            class="pxp-jobs-card-3-company-logo" 
                                                            style="background-image: url(<?php echo esc_url($company_logo[0]); ?>);"
                                                        ></a>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-xxl-auto">
                                                        <a 
                                                            href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                                            class="pxp-jobs-card-3-company-logo pxp-no-img" 
                                                        >
                                                            <?php $company_name = $company->post_title;
                                                            echo esc_html($company_name[0]); ?>
                                                        </a>
                                                    </div>
                                                <?php }
                                            } ?>
                                            <div class="col-sm-9 col-md-10 col-lg-9 col-xl-10 col-xxl-4">
                                                <?php if ($featured == '1') { ?>
                                                    <div class="pxp-jobs-card-3-feat-label">
                                                        <?php echo esc_html($featured_label); ?>
                                                    </div>
                                                <?php } ?>
                                                <a 
                                                    href="<?php echo esc_url($job_link); ?>" 
                                                    class="pxp-jobs-card-3-title mt-3 mt-sm-0"
                                                >
                                                    <?php the_title(); ?>
                                                </a>
                                                <div class="pxp-jobs-card-3-details">
                                                    <?php if ($location_id != '') { 
                                                        $location_link = add_query_arg(
                                                            'location',
                                                            $location_id,
                                                            $search_submit
                                                        ); ?>
                                                        <a 
                                                            href="<?php echo esc_url($location_link); ?>" 
                                                            class="pxp-jobs-card-3-location"
                                                        >
                                                            <span class="fa fa-globe"></span>
                                                            <?php echo esc_html($location[0]->name); ?>
                                                        </a>
                                                    <?php }
                                                    if ($type) { ?>
                                                        <div class="pxp-jobs-card-3-type">
                                                            <?php echo esc_html($type[0]->name); ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <?php if ($show_salary && $salary != '') { ?>
                                                    <div class="pxp-jobs-card-3-salary">
                                                        <span class="fa fa-money"></span>
                                                        <?php echo esc_html($salary); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="col-sm-8 col-xl-6 col-xxl-4 mt-3 mt-xxl-0">
                                                <?php if ($category_id != '') { 
                                                    $category_link = add_query_arg(
                                                        'category',
                                                        $category_id,
                                                        $search_submit
                                                    ); ?>
                                                    <a 
                                                        href="<?php echo esc_url($category_link); ?>" 
                                                        class="pxp-jobs-card-3-category"
                                                    >
                                                        <div class="pxp-jobs-card-3-category-label">
                                                            <?php echo esc_html($category[0]->name); ?>
                                                        </div>
                                                    </a>
                                                <?php } ?>
                                                <div class="pxp-jobs-card-3-date-company <?php echo esc_attr($jc3_mt_class); ?>">
                                                    <span class="pxp-jobs-card-3-date pxp-text-light">
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
                                                            class="pxp-jobs-card-3-company"
                                                        >
                                                            <?php echo esc_html($company->post_title); ?>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                                <?php if (!empty($valid) && $show_valid) { ?>
                                                    <div class="pxp-jobs-card-3-date-small pxp-text-light mt-2">
                                                        <?php esc_html_e('Valid until:', 'jobster'); ?>  <?php esc_html_e(date_i18n(__('F j, Y', 'jobster'), strtotime($valid))); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="col-sm-4 col-xl-2 col-xxl-auto mt-3 mt-xxl-0 pxp-text-right">
                                                <a 
                                                    href="<?php echo esc_url($job_link); ?>" 
                                                    class="btn rounded-pill pxp-card-btn"
                                                >
                                                    <?php esc_html_e('Apply', 'jobster'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php break;
                            case 'cover': ?>
                                <div class="<?php echo esc_attr($big_card_column_class); ?>">
                                    <div class="pxp-jobs-card-5 pxp-has-border <?php echo esc_attr($featured_class); ?>">
                                        <div class="pxp-jobs-card-5-top">
                                            <div 
                                                <?php if (is_array($job_cover)) { ?>
                                                    class="pxp-jobs-card-5-cover pxp-cover" 
                                                    style="background-image: url(<?php echo esc_url($job_cover[0]); ?>);"
                                                <?php } else { ?>
                                                    class="pxp-jobs-card-5-cover pxp-no-cover"
                                                <?php } ?>
                                            >
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
                                                            class="pxp-jobs-card-5-company-logo" 
                                                            style="background-image: url(<?php echo esc_url($company_logo[0]); ?>);"
                                                        ></a>
                                                    <?php } else { ?>
                                                        <a 
                                                            href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                                            class="pxp-jobs-card-5-company-logo pxp-no-img" 
                                                        >
                                                            <?php $company_name = $company->post_title;
                                                            echo esc_html($company_name[0]); ?>
                                                        </a>
                                                    <?php }
                                                } ?>
                                            </div>
                                            <?php $title_margin_class = 'mt-0';
                                            if ($category_id != '') { 
                                                $title_margin_class = '';
                                                $category_link = add_query_arg(
                                                    'category',
                                                    $category_id,
                                                    $search_submit
                                                ); ?>
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="pxp-jobs-card-5-category"
                                                >
                                                    <?php if ($category_icon_type == 'image') {
                                                        $icon_image = wp_get_attachment_image_src($category_icon, 'pxp-icon');
                                                        if (is_array($icon_image)) { ?>
                                                            <div class="pxp-jobs-card-5-category-icon-image">
                                                                <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="pxp-jobs-card-5-category-icon">
                                                                <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                            </div>
                                                        <?php }
                                                    } else { ?>
                                                        <div class="pxp-jobs-card-5-category-icon">
                                                            <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="pxp-jobs-card-5-category-label">
                                                        <?php echo esc_html($category[0]->name); ?>
                                                    </div>
                                                </a>
                                            <?php }
                                            if ($featured == '1') { ?>
                                                <div class="pxp-jobs-card-5-feat-label">
                                                    <?php echo esc_html($featured_label); ?>
                                                </div>
                                            <?php } ?>
                                            <a 
                                                href="<?php echo esc_url($job_link); ?>" 
                                                class="pxp-jobs-card-5-title <?php echo esc_attr($title_margin_class); ?>"
                                            >
                                                <?php the_title(); ?>
                                            </a>
                                            <div class="pxp-jobs-card-5-details">
                                                <?php if ($location_id != '') { 
                                                    $location_link = add_query_arg(
                                                        'location',
                                                        $location_id,
                                                        $search_submit
                                                    ); ?>
                                                    <a 
                                                        href="<?php echo esc_url($location_link); ?>" 
                                                        class="pxp-jobs-card-5-location"
                                                    >
                                                        <span class="fa fa-globe"></span>
                                                        <?php echo esc_html($location[0]->name); ?>
                                                    </a>
                                                <?php }
                                                if ($type) { ?>
                                                    <div class="pxp-jobs-card-5-type">
                                                        <?php echo esc_html($type[0]->name); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php if ($show_salary && $salary != '') { ?>
                                                <div class="pxp-jobs-card-5-salary">
                                                    <span class="fa fa-money"></span>
                                                    <?php echo esc_html($salary); ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="pxp-jobs-card-5-bottom">
                                            <div class="pxp-jobs-card-5-bottom-left">
                                                <div class="pxp-jobs-card-5-date pxp-text-light">
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
                                                </div>
                                                <?php if ($company != '') { ?>
                                                    <a 
                                                        href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                                        class="pxp-jobs-card-5-company <?php echo esc_attr($jc1_mt_class); ?>"
                                                    >
                                                        <?php echo esc_html($company->post_title); ?>
                                                    </a>
                                                <?php }
                                                if (!empty($valid) && $show_valid) { ?>
                                                    <div class="pxp-jobs-card-5-date-small pxp-text-light mt-1">
                                                        <?php esc_html_e('Valid until:', 'jobster'); ?> <?php esc_html_e(date_i18n(__('F j, Y', 'jobster'), strtotime($valid))); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php break;
                            default: ?>
                                <div class="<?php echo esc_attr($big_card_column_class); ?>">
                                    <div class="pxp-jobs-card-1 pxp-has-border <?php echo esc_attr($featured_class); ?>">
                                        <div class="pxp-jobs-card-1-top">
                                            <?php $title_margin_class = 'mt-0';
                                            if ($category_id != '') { 
                                                $title_margin_class = '';
                                                $category_link = add_query_arg(
                                                    'category',
                                                    $category_id,
                                                    $search_submit
                                                ); ?>
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="pxp-jobs-card-1-category"
                                                >
                                                    <?php if ($category_icon_type == 'image') {
                                                        $icon_image = wp_get_attachment_image_src($category_icon, 'pxp-icon');
                                                        if (is_array($icon_image)) { ?>
                                                            <div class="pxp-jobs-card-1-category-icon-image">
                                                                <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="pxp-jobs-card-1-category-icon">
                                                                <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                            </div>
                                                        <?php }
                                                    } else { ?>
                                                        <div class="pxp-jobs-card-1-category-icon">
                                                            <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="pxp-jobs-card-1-category-label">
                                                        <?php echo esc_html($category[0]->name); ?>
                                                    </div>
                                                </a>
                                            <?php }
                                            if ($featured == '1') { ?>
                                                <div class="pxp-jobs-card-1-feat-label">
                                                    <?php echo esc_html($featured_label); ?>
                                                </div>
                                            <?php } ?>
                                            <a 
                                                href="<?php echo esc_url($job_link); ?>" 
                                                class="pxp-jobs-card-1-title <?php echo esc_attr($title_margin_class); ?>"
                                            >
                                                <?php the_title(); ?>
                                            </a>
                                            <div class="pxp-jobs-card-1-details">
                                                <?php if ($location_id != '') { 
                                                    $location_link = add_query_arg(
                                                        'location',
                                                        $location_id,
                                                        $search_submit
                                                    ); ?>
                                                    <a 
                                                        href="<?php echo esc_url($location_link); ?>" 
                                                        class="pxp-jobs-card-1-location"
                                                    >
                                                        <span class="fa fa-globe"></span>
                                                        <?php echo esc_html($location[0]->name); ?>
                                                    </a>
                                                <?php }
                                                if ($type) { ?>
                                                    <div class="pxp-jobs-card-1-type">
                                                        <?php echo esc_html($type[0]->name); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php if ($show_salary && $salary != '') { ?>
                                                <div class="pxp-jobs-card-1-salary">
                                                    <span class="fa fa-money"></span>
                                                    <?php echo esc_html($salary); ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="pxp-jobs-card-1-bottom">
                                            <div class="pxp-jobs-card-1-bottom-left">
                                                <div class="pxp-jobs-card-1-date pxp-text-light">
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
                                                </div>
                                                <?php if ($company != '') { ?>
                                                    <a 
                                                        href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                                        class="pxp-jobs-card-1-company <?php echo esc_attr($jc1_mt_class); ?>"
                                                    >
                                                        <?php echo esc_html($company->post_title); ?>
                                                    </a>
                                                <?php }
                                                if (!empty($valid) && $show_valid) { ?>
                                                    <div class="pxp-jobs-card-1-date-small pxp-text-light mt-1">
                                                        <?php esc_html_e('Valid until:', 'jobster'); ?> <?php esc_html_e(date_i18n(__('F j, Y', 'jobster'), strtotime($valid))); ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
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
                                                        class="pxp-jobs-card-1-company-logo" 
                                                        style="background-image: url(<?php echo esc_url($company_logo[0]); ?>);"
                                                    ></a>
                                                <?php } else { ?>
                                                    <a 
                                                        href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                                        class="pxp-jobs-card-1-company-logo pxp-no-img" 
                                                    >
                                                        <?php $company_name = $company->post_title;
                                                        echo esc_html($company_name[0]); ?>
                                                    </a>
                                                <?php }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php break;
                        }
                    } ?>
                </div>

                <?php jobster_pagination($searched_jobs->max_num_pages); ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>