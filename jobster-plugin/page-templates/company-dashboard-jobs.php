<?php
/*
Template Name: Company Dashboard - Manage Jobs
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $current_user;
global $company_id;
global $post;

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

$current_user = wp_get_current_user();

$is_company = jobster_user_is_company($current_user->ID);
if ($is_company) {
    $company_id = jobster_get_company_by_userid($current_user->ID);
} else {
    wp_redirect(home_url());
}

get_header('dashboard', array('bg_color' => 'pxpMainColorLight'));

jobster_get_company_dashboard_side($company_id, 'jobs');

$keywords = isset($_GET['keywords'])
            ? stripslashes(sanitize_text_field($_GET['keywords']))
            : '';
$category_get = isset($_GET['category'])
                ? stripslashes(sanitize_text_field($_GET['category']))
                : '0';
$type_get = isset($_GET['type'])
            ? stripslashes(sanitize_text_field($_GET['type']))
            : '0';

global $paged;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
    'posts_per_page' => 20,
    'paged'          => $paged,
    's'              => $keywords,
    'post_type'      => 'job',
    'post_status'    => array('publish', 'draft', 'pending'),
    'meta_key'       => 'job_company',
    'meta_value'     => $company_id,
);

$args['tax_query'] = array('relation' => 'AND');

if ($category_get != '0') {
    array_push($args['tax_query'], array(
        'taxonomy' => 'job_category',
        'field'    => 'term_id',
        'terms'    => $category_get,
    ));
}

if ($type_get != '0') {
    $types = explode(',', $type_get);

    array_push($args['tax_query'], array(
        'taxonomy' => 'job_type',
        'field'    => 'term_id',
        'terms'    => $types,
    ));
}

$jobs = new WP_Query($args);
$total_jobs  = $jobs ? $jobs->found_posts : 0;

$search_submit = jobster_get_page_link('company-dashboard-jobs.php');
$edit_job_url = jobster_get_page_link('company-dashboard-edit-job.php');

$candidates_url = jobster_get_page_link('company-dashboard-candidates.php'); 

$membership_settings = get_option('jobster_membership_settings', '');
$payment_type  =    isset($membership_settings['jobster_payment_type_field'])
                    ? $membership_settings['jobster_payment_type_field']
                    : '';
$payment_currency = '';
$payment_system =   isset($membership_settings['jobster_payment_system_field'])
                    ? $membership_settings['jobster_payment_system_field']
                    : '';
switch ($payment_system) {
    case 'paypal':
        $payment_currency = isset($membership_settings['jobster_paypal_payment_currency_field'])
                            ? $membership_settings['jobster_paypal_payment_currency_field']
                            : '';
        break;
    case 'stripe':
        $payment_currency = isset($membership_settings['jobster_stripe_payment_currency_field'])
                            ? $membership_settings['jobster_stripe_payment_currency_field']
                            : '';
        break;
    default:
        $payment_currency = '';
        break;
}
$standard_price  =  isset($membership_settings['jobster_submission_price_field'])
                    ? $membership_settings['jobster_submission_price_field']
                    : __('Free', 'jobster');
$featured_price =   isset($membership_settings['jobster_featured_price_field'])
                    ? $membership_settings['jobster_featured_price_field']
                    : __('Free', 'jobster');
$company_payment = get_post_meta($company_id, 'company_payment', true); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_company_dashboard_top($company_id); ?>

    <div class="pxp-dashboard-content-details">
        <h1><?php esc_html_e('Manage Jobs', 'jobster'); ?></h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Detailed list with all your job offers.', 'jobster'); ?>
        </p>

        <?php if ($payment_type == 'listing' && $company_payment != '1') { ?>
            <input 
                type="hidden" 
                id="pxp-company-dashboard-jobs-standard-price" 
                value="<?php echo esc_attr($standard_price); ?>"
            >
            <input 
                type="hidden" 
                id="pxp-company-dashboard-jobs-featured-price" 
                value="<?php echo esc_attr($featured_price); ?>"
            >
        <?php } ?>

        <div class="mt-4 mt-lg-5">
            <div class="row justify-content-between align-content-center">
                <div class="col-auto order-2 order-sm-1">
                    <div class="pxp-company-dashboard-jobs-bulk-actions mb-3">
                        <select 
                            class="form-select" 
                            id="pxp-company-dashboard-jobs-bulk-actions"
                        >
                            <option value="">
                                <?php esc_html_e('Bulk actions', 'jobster'); ?>
                            </option>
                            <?php if ($payment_type != 'plan') { ?>
                                <option value="publish">
                                    <?php esc_html_e('Publish', 'jobster'); ?>
                                </option>
                            <?php } ?>
                            <option value="delete">
                                <?php esc_html_e('Delete', 'jobster'); ?>
                            </option>
                        </select>
                        <a 
                            href="javascript:void(0);" 
                            class="btn ms-2 disabled pxp-company-dashboard-jobs-bulk-actions-apply" 
                        >
                            <span class="pxp-company-dashboard-jobs-bulk-actions-apply-text">
                                <?php esc_html_e('Apply Action', 'jobster'); ?>
                            </span>
                            <span class="pxp-company-dashboard-jobs-bulk-actions-apply-loading pxp-btn-loading">
                                <img 
                                    src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                    class="pxp-btn-loader" 
                                    alt="..."
                                >
                            </span>
                        </a>
                        <?php wp_nonce_field(
                            'company_bulk_jobs_ajax_nonce',
                            'pxp-company-bulk-jobs-security',
                            true
                        ); ?>
                    </div>
                </div>
                <div class="col-auto order-1 order-sm-1">
                    <div class="pxp-company-dashboard-jobs-search mb-3">
                        <div class="pxp-company-dashboard-jobs-search-results me-3">
                            <?php echo esc_attr($total_jobs); ?>
                            <?php esc_html_e('jobs', 'jobster'); ?>
                        </div>
                        <div class="pxp-company-dashboard-jobs-search-form">
                            <form 
                                role="search" 
                                method="get" 
                                action="<?php echo esc_url($search_submit); ?>"
                            >
                                <input 
                                    type="hidden" 
                                    name="category"
                                    id="category" 
                                    value="<?php echo esc_attr($category_get); ?>"
                                >
                                <input 
                                    type="hidden" 
                                    name="type"
                                    id="type" 
                                    value="<?php echo esc_attr($type_get); ?>"
                                >
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
                                        placeholder="<?php esc_attr_e('Search jobs...', 'jobster'); ?>"
                                    >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pxp-company-dashboard-jobs-filter mb-3">
                <div class="row justify-content-end align-items-center gx-2">
                    <div class="col-auto">
                        <?php esc_html_e('Filter by', 'jobster'); ?>
                    </div>
                    <div class="col-auto">
                        <?php $category_tax = array( 
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
                        ); ?>
                        <select 
                            class="form-select" 
                            id="pxp-company-dashboard-jobs-filter-category"
                        >
                            <option value="0">
                                <?php esc_html_e('Category', 'jobster'); ?>
                            </option>
                            <?php foreach ($category_terms as $category_term) { ?>
                                <option 
                                    value="<?php echo esc_attr($category_term->term_id);?>" 
                                    <?php selected($category_get == $category_term->term_id); ?>
                                >
                                    <?php echo esc_html($category_term->name); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <?php $type_tax = array( 
                            'job_type'
                        );
                        $type_args = array(
                            'orderby'    => 'name',
                            'order'      => 'ASC',
                            'hide_empty' => false
                        );
                        $type_terms = get_terms(
                            $type_tax,
                            $type_args
                        ); ?>
                        <select 
                            class="form-select" 
                            id="pxp-company-dashboard-jobs-filter-type"
                        >
                            <option value="0">
                                <?php esc_html_e('Type', 'jobster'); ?>
                            </option>
                            <?php foreach ($type_terms as $type_term) { ?>
                                <option 
                                    value="<?php echo esc_attr($type_term->term_id); ?>"
                                    <?php selected(
                                        $type_term->term_id,
                                        $type_get
                                    ); ?>
                                >
                                    <?php echo esc_html($type_term->name); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="pxp-is-checkbox" style="width: 1%;">
                                <input 
                                    type="checkbox" 
                                    class="form-check-input pxp-company-dashboard-check-all-jobs"
                                >
                            </th>
                            <th style="width: 25%;">
                                <?php esc_html_e('Title', 'jobster'); ?>
                            </th>
                            <th style="width: 20%;">
                                <?php esc_html_e('Category/Type', 'jobster'); ?>
                            </th>
                            <th style="width: 15%;">
                                <?php esc_html_e('Applications', 'jobster'); ?>
                            </th>
                            <th>
                                <?php esc_html_e('Date', 'jobster'); ?>
                            </th>
                            <th style="width: 12%;">
                                <?php esc_html_e('Status', 'jobster'); ?>
                            </th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($jobs->have_posts()) {
                            $jobs->the_post(); 

                            $job_id = get_the_ID();
                            $job_link = get_permalink($job_id);

                            $edit_job_link = add_query_arg(
                                'id',
                                $job_id,
                                $edit_job_url
                            );

                            $location = wp_get_post_terms(
                                $job_id, 'job_location'
                            );
                            $location_id = $location ? $location[0]->term_id : '';

                            $category = wp_get_post_terms(
                                $job_id, 'job_category'
                            );
                            $category_id = $category ? $category[0]->term_id : '';

                            $type = wp_get_post_terms(
                                $job_id, 'job_type'
                            );

                            $apps = get_post_meta(
                                $job_id,
                                'job_applications',
                                true
                            );
                            $apps_no = is_array($apps) ? count($apps) : '0';
                            $candidates_link = add_query_arg(
                                'job_id',
                                $job_id,
                                $candidates_url
                            );

                            $featured = get_post_meta(
                                $job_id, 'job_featured', true
                            );

                            $payment_status = get_post_meta(
                                $job_id,
                                'job_payment_status',
                                true
                            );

                            $valid = get_post_meta(
                                $job_id, 'job_valid', true
                            ); ?>

                            <tr data-id="<?php echo esc_html($job_id); ?>">
                                <td>
                                    <input 
                                        type="checkbox" 
                                        class="form-check-input pxp-company-dashboard-check" 
                                        data-id="<?php echo esc_html($job_id); ?>"
                                    >
                                </td>
                                <td>
                                    <a 
                                        href="<?php echo esc_url($edit_job_link); ?>" 
                                        <?php if ($featured == '1') { ?>
                                            title="<?php esc_html_e('Featured', 'jobster'); ?>"
                                        <?php } ?>
                                    >
                                        <div class="pxp-company-dashboard-job-title">
                                            <?php the_title();
                                            if ($featured == '1') { ?>
                                                <div class="d-inline-block badge rounded-pill pxp-company-dashboard-job-feat-label">
                                                    <span class="fa fa-star"></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if ($location_id != '') { ?>
                                            <div class="pxp-company-dashboard-job-location">
                                                <span class="fa fa-globe me-1"></span>
                                                <?php echo esc_html($location[0]->name); ?>
                                            </div>
                                        <?php } ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($category_id != '') { ?>
                                        <div class="pxp-company-dashboard-job-category">
                                            <?php echo esc_html($category[0]->name); ?>
                                        </div>
                                    <?php }
                                    if ($type) { ?>
                                        <div class="pxp-company-dashboard-job-type">
                                            <?php echo esc_html($type[0]->name); ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (intval($apps_no > 0)) { ?>
                                        <a 
                                            href="<?php echo esc_url($candidates_link); ?>" 
                                            class="pxp-company-dashboard-job-applications"
                                        >
                                            <?php echo esc_html($apps_no); ?>
                                            <?php esc_html_e('candidates', 'jobster'); ?>
                                        </a>
                                    <?php } else { ?>
                                        <div class="pxp-company-dashboard-job-applications">
                                            <?php echo esc_html($apps_no); ?>
                                            <?php esc_html_e('candidates', 'jobster'); ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <div class="pxp-company-dashboard-job-date mt-1">
                                        <?php printf(
                                            __('%1$s at %2$s', 'jobster'),
                                            get_the_time(__('Y/m/d', 'jobster')),
                                            get_the_time(__('g:i a', 'jobster'))
                                        );
                                        if ($valid != '') { ?>
                                            <div class="pxp-company-dashboard-job-date-delim">
                                                <?php esc_html_e('Valid until: ', 'jobster');
                                                echo esc_html(date(__('Y/m/d', 'jobster'), strtotime($valid))); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="pxp-company-dashboard-job-status">
                                        <?php $today = date('Y-m-d');
                                        
                                        if (get_post_status($job_id) == 'publish') { ?>
                                            <span class="badge rounded-pill bg-success">
                                                <?php esc_html_e('Published', 'jobster'); ?>
                                            </span>
                                        <?php } else if (get_post_status($job_id) == 'pending') { ?>
                                            <span class="badge rounded-pill bg-warning">
                                                <?php esc_html_e('Pending', 'jobster'); ?>
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge rounded-pill bg-secondary">
                                                <?php esc_html_e('Draft', 'jobster'); ?>
                                            </span>
                                        <?php }

                                        if ($valid != '' 
                                            && strtotime($today) > strtotime($valid)) { ?>
                                            <span class="badge rounded-pill bg-danger">
                                                <?php esc_html_e('Expired', 'jobster'); ?>
                                            </span>
                                        <?php }

                                        if ($payment_type == 'listing') {
                                            if ($payment_status == 'paid') { ?>
                                                <span class="badge rounded-pill bg-success">
                                                    <?php esc_html_e('Paid', 'jobster'); ?>
                                                </span>
                                            <?php } else if (get_post_status($job_id) != 'publish') { ?>
                                                <span class="badge rounded-pill bg-danger">
                                                    <?php esc_html_e('Payment required', 'jobster'); ?>
                                                </span>
                                            <?php }
                                        } ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="pxp-dashboard-table-options">
                                        <ul class="list-unstyled">
                                            <li>
                                                <?php if ($payment_type == 'listing') {
                                                    $featured_free_left = get_post_meta(
                                                        $company_id,
                                                        'company_free_featured_listings',
                                                        true
                                                    );
                                                    $ffl_int = intval($featured_free_left); 
                                                    $show_options = true;

                                                    if ($payment_status == 'paid') {
                                                        if ($ffl_int > 0 || $company_payment == '1') {
                                                            if ($featured == '1') {
                                                                $show_options = false;
                                                            }
                                                        } else {
                                                            if ($featured == '1') {
                                                                $show_options = false;
                                                            }
                                                        }
                                                    }

                                                    if ($show_options == true) {  ?>
                                                        <div class="dropdown">
                                                            <a 
                                                                type="button" 
                                                                data-bs-toggle="dropdown" 
                                                                aria-expanded="false"
                                                            >
                                                                <span class="fa fa-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu pxp-dashboard-table-options-dropdown">
                                                                <?php if ($payment_status == 'paid') {
                                                                    if ($ffl_int > 0 || $company_payment == '1') {
                                                                        if ($featured != 1) { ?>
                                                                            <div class="form-check pxp-company-dashboard-jobs-pay-option">
                                                                                <input 
                                                                                    type="checkbox" 
                                                                                    class="form-check-input pxp-company-dashboard-jobs-featured-free" 
                                                                                    id="pxp-company-dashboard-jobs-featured-free<?php echo esc_attr($job_id); ?>" 
                                                                                    value="1"
                                                                                >
                                                                                <label 
                                                                                    class="form-check-label" 
                                                                                    for="pxp-company-dashboard-jobs-featured-free<?php echo esc_attr($job_id); ?>"
                                                                                >
                                                                                    <?php esc_html_e('Set as Featured', 'jobster'); ?> <?php if ($company_payment != '1') {
                                                                                         ?>(<strong><?php echo esc_html($ffl_int); ?> <?php esc_html_e('free left', 'jobster'); ?></strong>)<?php 
                                                                                    } ?>
                                                                                </label>
                                                                            </div>
                                                                        <?php }
                                                                        wp_nonce_field('upgradejob_ajax_nonce', 'pxp-upgrade-job-security', true); ?>
                                                                        <a 
                                                                            href="javascript:void(0);" 
                                                                            class="btn pxp-company-dashboard-jobs-payment-upgrade-btn pxp-is-payment mt-3" 
                                                                            style="display: none;" 
                                                                            data-id="<?php echo esc_html($job_id); ?>" 
                                                                            data-company-id="<?php echo esc_html($company_id); ?>"
                                                                        >
                                                                            <span class="pxp-company-dashboard-jobs-payment-upgrade-btn-text">
                                                                                <?php esc_html_e('Upgrade to Featured', 'jobster'); ?>
                                                                            </span>
                                                                            <span class="pxp-company-dashboard-jobs-payment-upgrade-btn-loading pxp-btn-loading">
                                                                                <img 
                                                                                    src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                                                                    class="pxp-btn-loader" 
                                                                                    alt="..."
                                                                                >
                                                                            </span>
                                                                        </a>
                                                                    <?php } else {
                                                                        if ($featured != 1) { ?>
                                                                            <div class="form-check pxp-company-dashboard-jobs-pay-option">
                                                                                <input 
                                                                                    type="checkbox" 
                                                                                    class="form-check-input pxp-company-dashboard-jobs-featured" 
                                                                                    id="pxp-company-dashboard-jobs-featured<?php echo esc_attr($job_id); ?>" 
                                                                                    value="1"
                                                                                >
                                                                                <label 
                                                                                    class="form-check-label" 
                                                                                    for="pxp-company-dashboard-jobs-featured<?php echo esc_attr($job_id); ?>"
                                                                                >
                                                                                    <?php esc_html_e('Set as Featured', 'jobster'); ?> (<strong>+ <?php echo esc_html($featured_price) . ' ' . esc_html($payment_currency); ?></strong>)
                                                                                </label>
                                                                            </div>
                                                                        <?php } ?>
                                                                        <input 
                                                                            type="hidden" 
                                                                            class="pxp-company-dashboard-jobs-pay-featured" 
                                                                            value="1"
                                                                        >
                                                                        <a 
                                                                            href="javascript:void(0);" 
                                                                            class="btn pxp-company-dashboard-jobs-payment-btn pxp-is-payment mt-3" 
                                                                            style="display: none;" 
                                                                            data-id="<?php echo esc_attr($job_id); ?>" 
                                                                            data-featured="" 
                                                                            data-upgrade="1" 
                                                                            data-system="<?php echo esc_attr($payment_system); ?>"
                                                                        >
                                                                            <span class="pxp-company-dashboard-jobs-payment-btn-text">
                                                                                <?php if ($payment_system == 'paypal') { ?>
                                                                                    <span class="fa fa-paypal"></span> <?php esc_html_e('Pay with PayPal', 'jobster'); ?> <span class="pxp-company-dashboard-jobs-payment-btn-total"><?php echo esc_html($featured_price); ?></span> <?php echo esc_html($payment_currency); ?>
                                                                                <?php }
                                                                                if ($payment_system == 'stripe') { ?>
                                                                                    <span class="fa fa-cc-stripe"></span> <?php esc_html_e('Pay with Stripe', 'jobster'); ?> <span class="pxp-company-dashboard-jobs-payment-btn-total"><?php echo esc_html($featured_price); ?></span> <?php echo esc_html($payment_currency); ?>
                                                                                <?php } ?>
                                                                            </span>
                                                                            <span class="pxp-company-dashboard-jobs-payment-btn-loading pxp-btn-loading">
                                                                                <img 
                                                                                    src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                                                                    class="pxp-btn-loader" 
                                                                                    alt="..."
                                                                                >
                                                                            </span>
                                                                        </a>
                                                                    <?php }
                                                                } else { ?>
                                                                    <div class="pxp-company-dashboard-jobs-payment-price">
                                                                        <?php esc_html_e('Submission Price', 'jobster'); ?>: <b><?php echo esc_html($standard_price) . ' ' . esc_html($payment_currency); ?></b>
                                                                    </div>
                                                                    <?php if ($featured != 1) {
                                                                        if ($ffl_int > 0) { ?>
                                                                            <div class="form-check pxp-company-dashboard-jobs-pay-option">
                                                                                <input 
                                                                                    type="checkbox"
                                                                                    class="form-check-input pxp-company-dashboard-jobs-featured-free" 
                                                                                    id="pxp-company-dashboard-jobs-featured-free<?php echo esc_attr($job_id); ?>" 
                                                                                    value="1"
                                                                                >
                                                                                <label 
                                                                                    class="form-check-label" 
                                                                                    for="pxp-company-dashboard-jobs-featured-free<?php echo esc_attr($job_id); ?>"
                                                                                >
                                                                                    <?php esc_html_e('Set as Featured', 'jobster'); ?> (<strong><?php echo esc_html($ffl_int) . ' ' . esc_html__('free left', 'jobster'); ?></strong>)
                                                                                </label>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <div class="form-check pxp-company-dashboard-jobs-pay-option">
                                                                                <input 
                                                                                    type="checkbox" 
                                                                                    class="form-check-input pxp-company-dashboard-jobs-featured" 
                                                                                    id="pxp-company-dashboard-jobs-featured<?php echo esc_attr($job_id); ?>" 
                                                                                    value="1"
                                                                                >
                                                                                <label 
                                                                                    class="form-check-label" 
                                                                                    for="pxp-company-dashboard-jobs-featured<?php echo esc_attr($job_id); ?>"
                                                                                >
                                                                                    <?php esc_html_e('Set as Featured', 'jobster'); ?> (<strong>+ <?php echo esc_html($featured_price) . ' ' . esc_html($payment_currency); ?></strong>)
                                                                                </label>
                                                                            </div>
                                                                        <?php }
                                                                    } ?>
                                                                    <a 
                                                                        href="javascript:void(0);" 
                                                                        class="btn pxp-company-dashboard-jobs-payment-btn pxp-is-payment mt-3" 
                                                                        data-id="<?php echo esc_attr($job_id); ?>" 
                                                                        data-featured="" 
                                                                        data-upgrade="" 
                                                                        data-system="<?php echo esc_attr($payment_system); ?>" 
                                                                    >
                                                                        <span class="pxp-company-dashboard-jobs-payment-btn-text">
                                                                            <?php if ($payment_system == 'paypal') { ?>
                                                                                <span class="fa fa-paypal"></span> <?php esc_html_e('Pay with PayPal', 'jobster'); ?> <span class="pxp-company-dashboard-jobs-payment-btn-total"><?php echo esc_html($standard_price); ?></span> <?php echo esc_html($payment_currency); ?>
                                                                            <?php }
                                                                            if ($payment_system == 'stripe') { ?>
                                                                                <span class="fa fa-cc-stripe"></span> <?php esc_html_e('Pay with Stripe', 'jobster'); ?> <span class="pxp-company-dashboard-jobs-payment-btn-total"><?php echo esc_html($standard_price); ?></span> <?php echo esc_html($payment_currency); ?>
                                                                            <?php } ?>
                                                                        </span>
                                                                        <span class="pxp-company-dashboard-jobs-payment-btn-loading pxp-btn-loading">
                                                                            <img 
                                                                                src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                                                                class="pxp-btn-loader" 
                                                                                alt="..."
                                                                            >
                                                                        </span>
                                                                    </a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } else if ($payment_type == 'plan') {
                                                    $featured_plan_left = get_post_meta(
                                                        $company_id,
                                                        'company_plan_featured',
                                                        true
                                                    );

                                                    $fpl_int = ($company_payment == '1') ? 1 : intval($featured_plan_left);

                                                    if ($featured != 1 && $fpl_int > 0) {
                                                        wp_nonce_field('featuredjob_ajax_nonce', 'pxp-featured-job-security', true); ?>
                                                        <div class="dropdown">
                                                            <a 
                                                                type="button" 
                                                                data-bs-toggle="dropdown" 
                                                                aria-expanded="false"
                                                            >
                                                                <span class="fa fa-ellipsis-h"></span>
                                                            </a>
                                                            <div class="dropdown-menu pxp-dashboard-table-options-dropdown">
                                                                <a 
                                                                    href="javascript:void(0);" 
                                                                    class="btn pxp-company-dashboard-jobs-payment-featured-btn pxp-is-payment" 
                                                                    data-id="<?php echo esc_attr($job_id); ?>" 
                                                                    data-company-id="<?php echo esc_attr($company_id); ?>" 
                                                                >
                                                                    <span class="pxp-company-dashboard-jobs-payment-featured-btn-text">
                                                                        <?php esc_html_e('Set as Featured', 'jobster'); ?>
                                                                    </span>
                                                                    <span class="pxp-company-dashboard-jobs-payment-featured-btn-loading pxp-btn-loading">
                                                                        <img 
                                                                            src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                                                            class="pxp-btn-loader" 
                                                                            alt="..."
                                                                        >
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    <?php } 
                                                } ?>
                                            </li>
                                            <li>
                                                <a 
                                                    href="<?php echo esc_url($edit_job_link); ?>" 
                                                    title="<?php echo esc_attr('Edit', 'jobster'); ?>"
                                                >
                                                    <span class="fa fa-pencil"></span>
                                                </a>
                                            </li>
                                            <li>
                                                <a 
                                                    href="<?php echo esc_url($job_link); ?>" 
                                                    title="<?php echo esc_attr('Preview', 'jobster'); ?>"
                                                >
                                                    <span class="fa fa-eye"></span>
                                                </a>
                                            </li>
                                            <li>
                                                <a 
                                                    href="javascript:void(0);" 
                                                    class="pxp-company-dashboard-job-delete" 
                                                    title="<?php echo esc_attr('Delete', 'jobster'); ?>" 
                                                    data-id="<?php echo esc_html($job_id); ?>"
                                                >
                                                    <span class="pxp-company-dashboard-job-delete-text">
                                                        <span class="fa fa-trash-o"></span>
                                                    </span>
                                                    <span class="pxp-company-dashboard-job-delete-loading pxp-btn-loading">
                                                        <img 
                                                            src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                                            class="pxp-btn-loader pxp-small" 
                                                            alt="..."
                                                        >
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <?php jobster_pagination($jobs->max_num_pages); ?>
            </div>
        </div>
    </div>

    <?php get_footer('dashboard'); ?>
</div>