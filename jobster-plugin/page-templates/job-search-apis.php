<?php
/*
Template Name: Job Search - External APIs
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

$apis_settings = get_option('jobster_apis_settings', '');
$api  = isset($apis_settings['jobster_api_field'])
        ? $apis_settings['jobster_api_field']
        : '';
if ($api == 'careerjet') {
    $search_submit = jobster_get_page_link('job-search-apis.php');

    $api_locale  =  isset($apis_settings['jobster_api_careerjet_locale_field'])
                    ? $apis_settings['jobster_api_careerjet_locale_field']
                    : '';
    $api_aff_id =   isset($apis_settings['jobster_api_careerjet_affid_field'])
                    ? $apis_settings['jobster_api_careerjet_affid_field']
                    : '';
    if ($api_locale == '') {
        $api_locale = 'en_GB';
    }
    if ($api_aff_id != '') {
        $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'relevance';
        $searched_jobs = jobster_search_careerjet_jobs($api_locale, $api_aff_id, $sort);
        if ($searched_jobs) {
            $jobs = property_exists($searched_jobs, 'jobs') ? $searched_jobs->jobs : array();
        } else {
            $jobs = array();
        }
    }
}

$jobs_settings = get_option('jobster_jobs_settings');
$date_format =  isset($jobs_settings['jobster_job_date_format_field']) 
                ? $jobs_settings['jobster_job_date_format_field'] 
                : 'date';

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
$small_card_column_class = 'col-xl-6 pxp-jobs-card-2-container'; ?>

<section class="pxp-page-header-simple" style="background-color: var(--pxpMainColorLight);">
    <div class="pxp-container">
        <h1 class="<?php echo esc_attr($header_align_class); ?>">
            <?php echo get_the_title(); ?>
        </h1>
        <div class="pxp-hero-subtitle pxp-text-light <?php echo esc_attr($header_align_class); ?>">
            <?php echo esc_html($subtitle); ?>
        </div>
        <?php if (function_exists('jobster_get_careerjet_search_jobs_form')
                    && $search_position == 'top') {
            jobster_get_careerjet_search_jobs_form('top');
        } ?>
    </div>
</section>

<section class="mt-100">
    <div class="pxp-container">
        <?php if (  $search_position == 'top' 
                    && $filter_position == 'top') {
            jobster_get_careerjet_filter_jobs_form('top');
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
                        jobster_get_careerjet_search_jobs_form('side');
                    }
                    if ($filter_position != 'top') {
                        jobster_get_careerjet_filter_jobs_form('side', $filter_margin);
                    } ?>
                </div>
            <?php } ?>
            <div class="<?php echo esc_attr($content_column_class); ?> <?php echo esc_attr($content_order_class); ?>">
                <?php if (  $search_position == 'side' 
                            && $filter_position == 'top') {
                    jobster_get_careerjet_filter_jobs_form('top', true);
                }

                $list_top_class = ($filter_position == 'top')
                                    ? 'mt-4' : 'mt-4 mt-lg-0';

                if ($searched_jobs) { ?>
                    <div class="pxp-jobs-list-top <?php echo esc_attr($list_top_class); ?>">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <h2>
                                    <span class="pxp-text-light"><?php esc_html_e('Showing', 'jobster'); ?></span> 
                                    <?php echo esc_html($searched_jobs->hits); ?> 
                                    <span class="pxp-text-light"><?php esc_html_e('jobs', 'jobster'); ?></span>
                                </h2>
                            </div>
                            <div class="col-auto">
                                <select class="form-select" id="pxp-sort-jobs">
                                    <option 
                                        value="relevance" 
                                        <?php selected($sort, 'relevance'); ?>
                                    >
                                        <?php esc_html_e('Relevance', 'jobster'); ?>
                                    </option>
                                    <option 
                                        value="date" 
                                        <?php selected($sort, 'date'); ?>
                                    >
                                        <?php esc_html_e('Date', 'jobster'); ?>
                                    </option>
                                    <option 
                                        value="salary" 
                                        <?php selected($sort, 'salary'); ?>
                                    >
                                        <?php esc_html_e('Salary', 'jobster'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php foreach ($jobs as $job) {
                            switch ($card_design) {
                                case 'big': ?>
                                    <div class="<?php echo esc_attr($big_card_column_class); ?>">
                                        <div class="pxp-jobs-card-1 pxp-has-border">
                                            <div class="pxp-jobs-card-1-top">
                                                <a 
                                                    href="<?php echo esc_url($job->url); ?>" 
                                                    class="pxp-jobs-card-1-title mt-0"
                                                >
                                                    <?php echo esc_html($job->title); ?>
                                                </a>
                                                <div class="pxp-jobs-card-1-details d-block">
                                                    <?php $location_link = add_query_arg(
                                                        'location',
                                                        $job->locations,
                                                        $search_submit
                                                    ); ?>
                                                    <a 
                                                        href="<?php echo esc_url($location_link); ?>" 
                                                        class="pxp-jobs-card-1-location"
                                                    >
                                                        <span class="fa fa-globe"></span>
                                                        <?php echo esc_html($job->locations); ?>
                                                    </a>
                                                    <?php if ($job->salary) { ?>
                                                        <div class="pxp-jobs-card-1-type ps-0 mt-1">
                                                            <?php echo esc_html($job->salary); ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="pxp-jobs-card-1-bottom">
                                                <div class="pxp-jobs-card-1-bottom-left">
                                                    <div class="pxp-jobs-card-1-date pxp-text-light">
                                                        <?php $date = strtotime($job->date);
                                                        if ($date_format == 'time') {
                                                            $date_formated = date('F j, Y', $date);
                                                            $time_ago = jobster_get_time_ago(
                                                                strtotime($date_formated)
                                                            );
                                                            echo esc_html($time_ago) . ' ' . esc_html__('ago', 'jobster');
                                                        } else {
                                                            echo esc_html(date('F j, Y', $date));
                                                        }
                                                        if ($job->company != '') { ?>
                                                            <span class="d-inline">
                                                                <?php esc_html_e('by', 'jobster'); ?>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($job->company != '') { ?>
                                                        <div class="pxp-jobs-card-1-company">
                                                            <?php echo esc_html($job->company); ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="pxp-jobs-card-1-company-logo pxp-no-img">
                                                    <?php $company_name = $job->company != '' ? $job->company : 'C';
                                                    echo esc_html($company_name[0]); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php break;
                                case 'small': ?>
                                    <div class="<?php echo esc_attr($small_card_column_class); ?>">
                                        <div class="pxp-jobs-card-2 pxp-has-border">
                                            <div class="pxp-jobs-card-2-top">
                                                <div class="pxp-jobs-card-2-company-logo pxp-no-img">
                                                    <?php $company_name = $job->company != '' ? $job->company : 'C';
                                                    echo esc_html($company_name[0]); ?>
                                                </div>
                                                <div class="pxp-jobs-card-2-info">
                                                    <a 
                                                        href="<?php echo esc_url($job->url); ?>" 
                                                        class="pxp-jobs-card-2-title"
                                                    >
                                                        <?php echo esc_html($job->title); ?>
                                                    </a>
                                                    <div class="pxp-jobs-card-2-details">
                                                        <?php $location_link = add_query_arg(
                                                            'location',
                                                            $job->locations,
                                                            $search_submit
                                                        ); ?>
                                                        <a 
                                                            href="<?php echo esc_url($location_link); ?>" 
                                                            class="pxp-jobs-card-2-location"
                                                        >
                                                            <span class="fa fa-globe"></span>
                                                            <?php echo esc_html($job->locations); ?>
                                                        </a>
                                                        <?php if ($job->salary) { ?>
                                                            <div class="pxp-jobs-card-2-type">
                                                                <?php echo esc_html($job->salary); ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pxp-jobs-card-2-bottom">
                                                <?php if ($job->company != '') { ?>
                                                    <div class="pxp-jobs-card-2-company mt-0">
                                                        <?php echo esc_html($job->company); ?>
                                                    </div>
                                                <?php } ?>
                                                <div class="pxp-jobs-card-2-bottom-right">
                                                    <div class="pxp-jobs-card-2-date pxp-text-light">
                                                        <?php $date = strtotime($job->date);
                                                        if ($date_format == 'time') {
                                                            $date_formated = date('F j, Y', $date);
                                                            $time_ago = jobster_get_time_ago(
                                                                strtotime($date_formated)
                                                            );
                                                            echo esc_html($time_ago) . ' ' . esc_html__('ago', 'jobster');
                                                        } else {
                                                            echo esc_html(date('F j, Y', $date));
                                                        } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php break;
                                case 'list': ?>
                                    <div class="col-12">
                                        <div class="pxp-jobs-card-3 pxp-has-border">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-3 col-md-2 col-lg-3 col-xl-2 col-xxl-auto">
                                                    <div class="pxp-jobs-card-3-company-logo pxp-no-img">
                                                        <?php $company_name = $job->company != '' ? $job->company : 'C';
                                                        echo esc_html($company_name[0]); ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-9 col-md-10 col-lg-9 col-xl-10 col-xxl-4">
                                                    <a 
                                                        href="<?php echo esc_url($job->url); ?>" 
                                                        class="pxp-jobs-card-3-title"
                                                    >
                                                        <?php echo esc_html($job->title); ?>
                                                    </a>
                                                    <div class="pxp-jobs-card-3-details">
                                                        <?php $location_link = add_query_arg(
                                                            'location',
                                                            $job->locations,
                                                            $search_submit
                                                        ); ?>
                                                        <a 
                                                            href="<?php echo esc_url($location_link); ?>" 
                                                            class="pxp-jobs-card-3-location"
                                                        >
                                                            <span class="fa fa-globe"></span>
                                                            <?php echo esc_html($job->locations); ?>
                                                        </a>
                                                        <?php if ($job->salary) { ?>
                                                            <div class="pxp-jobs-card-3-type">
                                                                <?php echo esc_html($job->salary); ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8 col-xl-6 col-xxl-4 mt-3 mt-xxl-0">
                                                    <div class="pxp-jobs-card-3-date pxp-text-light">
                                                        <?php $date = strtotime($job->date);
                                                        if ($date_format == 'time') {
                                                            $date_formated = date('F j, Y', $date);
                                                            $time_ago = jobster_get_time_ago(
                                                                strtotime($date_formated)
                                                            );
                                                            echo esc_html($time_ago) . ' ' . esc_html__('ago', 'jobster');
                                                        } else {
                                                            echo esc_html(date('F j, Y', $date));
                                                        } ?>
                                                    </div>
                                                    <?php if ($job->company != '') { ?>
                                                        <div class="pxp-jobs-card-3-date-company">
                                                            <div class="pxp-jobs-card-3-company">
                                                                <?php echo esc_html($job->company); ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-4 col-xl-2 col-xxl-auto mt-3 mt-xxl-0 pxp-text-right">
                                                    <a 
                                                        href="<?php echo esc_url($job->url); ?>" 
                                                        class="btn rounded-pill pxp-card-btn"
                                                    >
                                                        <?php esc_html_e('Apply', 'jobster'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php break;
                                default: ?>
                                    <div class="<?php echo esc_attr($big_card_column_class); ?>">
                                        <div class="pxp-jobs-card-1 pxp-has-border">
                                            <div class="pxp-jobs-card-1-top">
                                                <a 
                                                    href="<?php echo esc_url($job->url); ?>" 
                                                    class="pxp-jobs-card-1-title mt-0"
                                                >
                                                    <?php echo esc_html($job->title); ?>
                                                </a>
                                                <div class="pxp-jobs-card-1-details d-block">
                                                    <?php $location_link = add_query_arg(
                                                        'location',
                                                        $job->locations,
                                                        $search_submit
                                                    ); ?>
                                                    <a 
                                                        href="<?php echo esc_url($location_link); ?>" 
                                                        class="pxp-jobs-card-1-location"
                                                    >
                                                        <span class="fa fa-globe"></span>
                                                        <?php echo esc_html($job->locations); ?>
                                                    </a>
                                                    <?php if ($job->salary) { ?>
                                                        <div class="pxp-jobs-card-1-type ps-0 mt-1">
                                                            <?php echo esc_html($job->salary); ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="pxp-jobs-card-1-bottom">
                                                <div class="pxp-jobs-card-1-bottom-left">
                                                    <div class="pxp-jobs-card-1-date pxp-text-light">
                                                        <?php $date = strtotime($job->date);
                                                        if ($date_format == 'time') {
                                                            $date_formated = date('F j, Y', $date);
                                                            $time_ago = jobster_get_time_ago(
                                                                strtotime($date_formated)
                                                            );
                                                            echo esc_html($time_ago) . ' ' . esc_html__('ago', 'jobster');
                                                        } else {
                                                            echo esc_html(date('F j, Y', $date));
                                                        }
                                                        if ($job->company != '') { ?>
                                                            <span class="d-inline">
                                                                <?php esc_html_e('by', 'jobster'); ?>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($job->company != '') { ?>
                                                        <div class="pxp-jobs-card-1-company">
                                                            <?php echo esc_html($job->company); ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="pxp-jobs-card-1-company-logo pxp-no-img">
                                                    <?php $company_name = $job->company != '' ? $job->company : 'C';
                                                    echo esc_html($company_name[0]); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php break;
                            }
                        } ?>
                    </div>

                    <?php jobster_pagination($searched_jobs->pages);
                } else {
                    esc_html_e('No jobs found', 'jobster');
                } ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>