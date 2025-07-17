<?php
/*
Template Name: Candidate Dashboard - Applications
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $current_user;
global $candidate_id;
global $post;

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

$current_user = wp_get_current_user();

$is_candidate = jobster_user_is_candidate($current_user->ID);
if ($is_candidate) {
    $candidate_id = jobster_get_candidate_by_userid($current_user->ID);
} else {
    wp_redirect(home_url());
}

get_header('dashboard', array('bg_color' => 'pxpSecondaryColorLight'));

jobster_get_candidate_dashboard_side($candidate_id, 'apps');

$keywords = isset($_GET['keywords'])
            ? stripslashes(sanitize_text_field($_GET['keywords']))
            : '';

global $paged;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$apps_per_page = 20;

$jobs_args = array(
    'posts_per_page'   => -1,
    's'                => $keywords,
    'post_type'        => 'job',
    'suppress_filters' => false,
    'post_status'      => 'publish',
    'meta_query'       => array(
        array(
            'key'     => 'job_applications',
            'value'   => 'i:' . $candidate_id,
            'compare' => 'REGEXP',
        )
    )
);

$jobs = new WP_Query($jobs_args);
$jobs_arr = get_object_vars($jobs);

$apps = array();

foreach ($jobs_arr['posts'] as $job) : 
    $job_apps = get_post_meta($job->ID, 'job_applications', true);

    if (is_array($job_apps)) {
        foreach ($job_apps as $job_app_k => $job_app_v) :
            if ($job_app_k == $candidate_id) {
                $job_app_date = $job_app_v['date'];
                $apps[$job_app_date] = array(
                    'job_id' => $job->ID
                );
            }
        endforeach;
    }
endforeach;

$total_apps = count($apps);

if ($total_apps > 0) {
    krsort($apps);
}

$new_apps = array();
foreach ($apps as $app_k => $app_v) {
    $new_apps[] = array(
        'job_id' => $app_v['job_id'],
        'date'   => $app_k
    );
}
$total_new_apps = count($new_apps);

$this_url = jobster_get_page_link('candidate-dashboard-apps.php'); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_candidate_dashboard_top($candidate_id); ?>

    <div class="pxp-dashboard-content-details">
        <h1><?php esc_html_e('Applications', 'jobster'); ?></h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Detailed list of your job applications.', 'jobster'); ?>
        </p>

        <div class="mt-4 mt-lg-5">
            <div class="row justify-content-between align-content-center">
                <div class="col-auto order-2 order-sm-1">
                    <div class="pxp-candidate-dashboard-apps-bulk-actions mb-3">
                        <select 
                            class="form-select" 
                            id="pxp-candidate-dashboard-apps-bulk-actions"
                        >
                            <option value="">
                                <?php esc_html_e('Bulk actions', 'jobster'); ?>
                            </option>
                            <option value="delete">
                                <?php esc_html_e('Delete', 'jobster'); ?>
                            </option>
                        </select>
                        <a 
                            href="javascript:void(0);" 
                            class="btn ms-2 disabled pxp-candidate-dashboard-apps-bulk-actions-apply" 
                        >
                            <span class="pxp-candidate-dashboard-apps-bulk-actions-apply-text">
                                <?php esc_html_e('Apply Action', 'jobster'); ?>
                            </span>
                            <span class="pxp-candidate-dashboard-apps-bulk-actions-apply-loading pxp-btn-loading">
                                <img 
                                    src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                    class="pxp-btn-loader" 
                                    alt="..."
                                >
                            </span>
                        </a>
                        <?php wp_nonce_field(
                            'candidate_bulk_apps_ajax_nonce',
                            'pxp-candidate-bulk-apps-security',
                            true
                        );
                        wp_nonce_field(
                            'company_candidates_ajax_nonce',
                            'pxp-candidate-apps-security',
                            true
                        ); ?>
                    </div>
                </div>
                <div class="col-auto order-1 order-sm-2">
                    <div class="pxp-candidate-dashboard-apps-search mb-3">
                        <div class="pxp-candidate-dashboard-apps-search-results me-3">
                            <?php echo esc_attr($total_apps); ?>
                            <?php esc_html_e('job applications', 'jobster'); ?>
                        </div>
                        <div class="pxp-candidate-dashboard-apps-search-form">
                            <form 
                                role="search" 
                                method="get" 
                                action="<?php echo esc_url($this_url); ?>"
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
                                        placeholder="<?php esc_attr_e('Search applications...', 'jobster'); ?>"
                                    >
                                </div>
                            </form>
                        </div>
                    </div>
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
                                class="form-check-input pxp-candidate-dashboard-check-all-apps"
                            >
                        </th>
                        <th style="width: 25%;">
                            <?php esc_html_e('Job', 'jobster'); ?>
                        </th>
                        <th style="width: 15%;">
                            <?php esc_html_e('Company', 'jobster'); ?>
                        </th>
                        <th style="width: 20%;">
                            <?php esc_html_e('Category', 'jobster'); ?>
                        </th>
                        <th style="width: 12%;">
                            <?php esc_html_e('Type', 'jobster'); ?>
                        </th>
                        <th>
                            <?php esc_html_e('Date', 'jobster'); ?>
                        </th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total_new_apps > 0) {
                        for ($i = (intval($paged) - 1) * $apps_per_page;
                            $i < intval($paged) * $apps_per_page && $i < $total_new_apps;
                            $i++) {
                            if (isset($new_apps[$i])) {
                                $job_id = $new_apps[$i]['job_id'];
                                $app_date = $new_apps[$i]['date'];

                                $job_link = get_permalink($job_id);
                                $job_title = get_the_title($job_id);

                                $location = wp_get_post_terms(
                                    $job_id, 'job_location'
                                );

                                $company_id = get_post_meta(
                                    $job_id, 'job_company', true
                                );
                                $company = ($company_id != '')
                                            ? get_post($company_id) :
                                            '';

                                $category = wp_get_post_terms(
                                    $job_id, 'job_category'
                                );

                                $type = wp_get_post_terms(
                                    $job_id, 'job_type'
                                ); ?>

                                <tr data-id="<?php echo esc_attr($job_id); ?>">
                                    <td>
                                        <input 
                                            type="checkbox" 
                                            class="form-check-input pxp-candidate-dashboard-check" 
                                            data-job-id="<?php echo esc_attr($job_id); ?>" 
                                            data-candidate-id="<?php echo esc_attr($candidate_id); ?>"
                                        >
                                    </td>
                                    <td>
                                        <a href="<?php echo esc_url($job_link); ?>">
                                            <div class="pxp-candidate-dashboard-job-title">
                                                <?php echo esc_html($job_title); ?>
                                            </div>
                                            <?php if ($location) { ?>
                                                <div class="pxp-candidate-dashboard-job-location">
                                                    <span class="fa fa-globe me-1"></span>
                                                    <?php echo esc_html($location[0]->name); ?>
                                                </div>
                                            <?php } ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($company != '') { ?>
                                            <a 
                                                href="<?php echo esc_url(get_permalink($company_id)); ?>" 
                                                class="pxp-candidate-dashboard-job-company"
                                            >
                                                <?php echo esc_html($company->post_title); ?>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($category) { ?>
                                            <div class="pxp-candidate-dashboard-job-category">
                                                <?php echo esc_html($category[0]->name); ?>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($type) { ?>
                                            <div class="pxp-candidate-dashboard-job-type">
                                                <?php echo esc_html($type[0]->name); ?>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="pxp-candidate-dashboard-job-date mt-1">
                                            <?php printf(
                                                __('%1$s at %2$s', 'jobster'),
                                                date(__('Y/m/d', 'jobster'), strtotime($app_date)),
                                                date(__('g:i a', 'jobster'), strtotime($app_date))
                                            ); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pxp-dashboard-table-options">
                                            <ul class="list-unstyled">
                                                <li>
                                                    <a 
                                                        href="<?php echo esc_url($job_link); ?>" 
                                                        title="<?php esc_html_e('View job', 'jobster'); ?>"
                                                    >
                                                        <span class="fa fa-eye"></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a 
                                                        href="javascript:void(0);" 
                                                        title="<?php esc_html_e('Delete', 'jobster'); ?>" 
                                                        class="btn pxp-candidate-dashboard-delete-app-btn" 
                                                        data-job-id="<?php echo esc_attr($job_id); ?>" 
                                                        data-candidate-id="<?php echo esc_attr($candidate_id); ?>"
                                                    >
                                                        <span class="fa fa-trash-o"></span>
                                                        <span class="pxp-btn-loading">
                                                            <img 
                                                                src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                                                class="pxp-btn-loader" 
                                                                alt="..."
                                                            >
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        }
                    } ?>
                </tbody>
            </table>

            <?php if ($total_new_apps > 0) {
                $total_pages = ceil($total_new_apps / $apps_per_page);
                jobster_pagination($total_pages);
            } ?>
        </div>
    </div>

    <?php get_footer('dashboard'); ?>
</div>
