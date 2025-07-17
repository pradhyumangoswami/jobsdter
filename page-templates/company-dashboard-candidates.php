<?php
/*
Template Name: Company Dashboard - Candidates
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

jobster_get_company_dashboard_side($company_id, 'candidates');

$keywords = isset($_GET['keywords'])
            ? stripslashes(sanitize_text_field($_GET['keywords']))
            : '';

global $paged;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$apps_per_page = 20;

$job_id =   isset($_GET['job_id'])
            ? sanitize_text_field($_GET['job_id'])
            : '';

$jobs_args = array(
    'posts_per_page'   => -1,
    'post_type'        => 'job',
    'suppress_filters' => false,
    'meta_query' => array(
        array(
            'key' => 'job_company',
            'value' => $company_id
        )
    )
);

if ($job_id != '') {
    $jobs_args['p'] = $job_id;
}

$jobs     = new WP_Query($jobs_args);
$jobs_arr = get_object_vars($jobs);

$apps = array();

foreach ($jobs_arr['posts'] as $job) : 
    $job_apps = get_post_meta($job->ID, 'job_applications', true);

    if (is_array($job_apps)) {
        foreach ($job_apps as $job_app_k => $job_app_v) :
            $job_app_date = $job_app_v['date'];
            $apps[$job_app_date] = array(
                'candidate_id' => $job_app_k,
                'job_id' => $job->ID,
                'app_status' => $job_app_v['status']
            );
        endforeach;
    }
endforeach;

$total_apps = count($apps);

if ($total_apps > 0) {
    krsort($apps);
}

$new_apps = array();
foreach ($apps as $app_k => $app_v) {
    $candidate_name = get_the_title($app_v['candidate_id']);

    $found = false;

    if (empty($keywords)) {
        $found = true;
    } else {
        if (stripos($candidate_name, $keywords) !== false) {
            $found = true;
        }
    }

    if ($found) {
        $new_apps[] = array(
            'candidate_id' => $app_v['candidate_id'],
            'job_id'       => $app_v['job_id'],
            'app_status'   => $app_v['app_status'],
            'date'         => $app_k
        );
    }
}
$total_new_apps = count($new_apps);

$this_url = jobster_get_page_link('company-dashboard-candidates.php'); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_company_dashboard_top($company_id); ?>

    <div class="pxp-dashboard-content-details">
        <h1>
            <?php if ($job_id != '') {
                $job_title_get = get_the_title($job_id);
                printf(
                    __('Candidates for <i>%1$s</i>', 'jobster'),
                    esc_html($job_title_get)
                );
            } else {
                esc_html_e('Candidates', 'jobster');
            } ?>
        </h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Detailed list of candidates that applied for your job offers.', 'jobster'); ?>
        </p>

        <div class="mt-4 mt-lg-5">
            <div class="row justify-content-between align-content-center">
                <div class="col-auto order-2 order-sm-1">
                    <div class="pxp-company-dashboard-candidates-bulk-actions mb-3">
                        <select 
                            class="form-select" 
                            id="pxp-company-dashboard-candidates-bulk-actions"
                        >
                            <option value="">
                                <?php esc_html_e('Bulk actions', 'jobster'); ?>
                            </option>
                            <option value="approve">
                                <?php esc_html_e('Approve', 'jobster'); ?>
                            </option>
                            <option value="reject">
                                <?php esc_html_e('Reject', 'jobster'); ?>
                            </option>
                            <option value="delete">
                                <?php esc_html_e('Delete', 'jobster'); ?>
                            </option>
                        </select>
                        <a 
                            href="javascript:void(0);" 
                            class="btn ms-2 disabled pxp-company-dashboard-candidates-bulk-actions-apply" 
                        >
                            <span class="pxp-company-dashboard-candidates-bulk-actions-apply-text">
                                <?php esc_html_e('Apply Action', 'jobster'); ?>
                            </span>
                            <span class="pxp-company-dashboard-candidates-bulk-actions-apply-loading pxp-btn-loading">
                                <img 
                                    src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                    class="pxp-btn-loader" 
                                    alt="..."
                                >
                            </span>
                        </a>
                        <?php wp_nonce_field(
                            'company_candidates_ajax_nonce',
                            'pxp-company-candidates-security',
                            true
                        ); ?>
                    </div>
                </div>
                <div class="col-auto order-1 order-sm-2">
                    <div class="pxp-company-dashboard-candidates-search mb-3">
                        <div class="pxp-company-dashboard-candidates-search-results me-3">
                            <?php echo esc_attr($total_new_apps); ?>
                            <?php esc_html_e('candidates', 'jobster'); ?>
                        </div>
                        <div class="pxp-company-dashboard-candidates-search-form">
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
                                        placeholder="<?php esc_attr_e('Search candidates...', 'jobster'); ?>"
                                    >
                                </div>
                            </form>
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
                                    class="form-check-input pxp-company-dashboard-check-all-candidates"
                                >
                            </th>
                            <th colspan="2" style="width: 25%;">
                                <?php esc_html_e('Name', 'jobster'); ?>
                            </th>
                            <th style="width: 20%;">
                                <?php esc_html_e('Applied for', 'jobster'); ?>
                            </th>
                            <th style="width: 15%;">
                                <?php esc_html_e('Status', 'jobster'); ?>
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
                                    $photo_val = get_post_meta(
                                        $new_apps[$i]['candidate_id'],
                                        'candidate_photo',
                                        true
                                    );
                                    $photo = wp_get_attachment_image_src(
                                        $photo_val,
                                        'pxp-thmb'
                                    );
                                    $link = get_permalink($new_apps[$i]['candidate_id']);
                                    $name = get_the_title($new_apps[$i]['candidate_id']);
                                    $location = wp_get_post_terms(
                                        $new_apps[$i]['candidate_id'],
                                        'candidate_location'
                                    );
                                    $job_title = get_the_title($new_apps[$i]['job_id']);
                                    $candidate_post_status = get_post_status($new_apps[$i]['candidate_id']); ?>

                                    <tr 
                                        data-job-id="<?php echo esc_attr($new_apps[$i]['job_id']); ?>" 
                                        data-candidate-id="<?php echo esc_attr($new_apps[$i]['candidate_id']); ?>"
                                    >
                                        <td>
                                            <input 
                                                type="checkbox" 
                                                class="form-check-input pxp-company-dashboard-check" 
                                                data-job-id="<?php echo esc_attr($new_apps[$i]['job_id']); ?>" 
                                                data-candidate-id="<?php echo esc_attr($new_apps[$i]['candidate_id']); ?>"
                                            >
                                        </td>
                                        <td style="width: 3%;">
                                            <?php if (is_array($photo)) { ?>
                                                <div 
                                                    class="pxp-company-dashboard-candidate-avatar pxp-cover" 
                                                    style="background-image: url(<?php echo esc_url($photo[0]); ?>);"
                                                ></div>
                                            <?php } else { ?>
                                                <div class="pxp-company-dashboard-candidate-avatar pxp-no-img">
                                                    <?php if (!empty($name)) {
                                                        echo esc_html($name[0]);
                                                    } else {
                                                        echo esc_html('C');
                                                    } ?>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($candidate_post_status == 'publish') { ?>
                                                <a href="<?php echo esc_url($link); ?>">
                                                    <div class="pxp-company-dashboard-job-title">
                                                        <?php if (!empty($name)) {
                                                            echo esc_html($name);
                                                        } ?>
                                                    </div>
                                                    <?php if ($location) { ?>
                                                        <div class="pxp-company-dashboard-job-location">
                                                            <span class="fa fa-globe me-1"></span>
                                                            <?php echo esc_html($location[0]->name); ?>
                                                        </div>
                                                    <?php } ?>
                                                </a>
                                            <?php } else {
                                                $candidate_email = get_post_meta(
                                                    $new_apps[$i]['candidate_id'],
                                                    'candidate_email',
                                                    true
                                                ); ?>
                                                <div>
                                                    <div class="pxp-company-dashboard-job-title">
                                                        <?php echo esc_html($name); ?>
                                                    </div>
                                                    <?php if ($candidate_email != '') { ?>
                                                        <div class="pxp-company-dashboard-job-location">
                                                            <?php echo esc_html($candidate_email); ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="pxp-company-dashboard-job-category">
                                                <?php echo esc_html($job_title); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pxp-company-dashboard-job-status">
                                                <?php if (isset($new_apps[$i]['app_status'])) {
                                                    switch ($new_apps[$i]['app_status']) {
                                                        case 'approved': ?>
                                                            <span class="badge rounded-pill bg-success">
                                                                <?php esc_html_e('Approved', 'jobster'); ?>
                                                            </span>
                                                            <?php break;
                                                        case 'rejected': ?>
                                                            <span class="badge rounded-pill bg-danger">
                                                                <?php esc_html_e('Rejected', 'jobster'); ?>
                                                            </span>
                                                            <?php break;
                                                        case 'na': ?>
                                                            <span class="badge rounded-pill bg-secondary">
                                                                <?php esc_html_e('N/A', 'jobster'); ?>
                                                            </span>
                                                            <?php break;
                                                        default: ?>
                                                            <span class="badge rounded-pill bg-secondary">
                                                                <?php esc_html_e('N/A', 'jobster'); ?>
                                                            </span>
                                                            <?php break;
                                                    }
                                                } ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pxp-company-dashboard-job-date">
                                                <?php printf(
                                                    __('%1$s at %2$s', 'jobster'),
                                                    date(__('Y/m/d', 'jobster'), strtotime($new_apps[$i]['date'])),
                                                    date(__('g:i a', 'jobster'), strtotime($new_apps[$i]['date']))
                                                ); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pxp-dashboard-table-options">
                                                <?php $candidate_cv = get_post_meta(
                                                    $new_apps[$i]['candidate_id'],
                                                    'candidate_cv',
                                                    true
                                                );
                                                $cv_url = wp_get_attachment_url($candidate_cv);

                                                $files = get_post_meta(
                                                    $new_apps[$i]['candidate_id'],
                                                    'candidate_files',
                                                    true
                                                );
                                                $files_list = array();

                                                if (!empty($files)) {
                                                    $files_data = json_decode(urldecode($files));

                                                    if (isset($files_data)) {
                                                        $files_list = $files_data->files;
                                                    }
                                                } ?>

                                                <ul class="list-unstyled">
                                                    <?php if ($cv_url || count($files_list) > 0) { ?>
                                                        <li>
                                                            <div class="dropdown">
                                                                <a 
                                                                    type="button" 
                                                                    class="dropdown-toggle" 
                                                                    data-bs-toggle="dropdown" 
                                                                    aria-expanded="false"
                                                                >
                                                                    <span class="fa fa-file-text-o"></span>
                                                                </a>
                                                                <ul class="dropdown-menu pxp-dashboard-files-list">
                                                                    <?php if ($cv_url) { ?>
                                                                        <li>
                                                                            <a 
                                                                                class="dropdown-item" 
                                                                                href="<?php echo esc_url($cv_url); ?>" 
                                                                            >
                                                                                <?php esc_html_e('Resume', 'jobster'); ?>
                                                                            </a>
                                                                        </li>
                                                                    <?php }
                                                                    if (count($files_list) > 0) {
                                                                        foreach ($files_list as $files_item) { ?>
                                                                            <li>
                                                                                <a 
                                                                                    class="dropdown-item" 
                                                                                    href="<?php echo esc_url($files_item->url); ?>" 
                                                                                >
                                                                                    <?php echo esc_html($files_item->name); ?>
                                                                                </a>
                                                                            </li>
                                                                        <?php }
                                                                    } ?>
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    <?php }

                                                    if ($candidate_post_status == 'publish') { ?>
                                                        <li>
                                                            <a 
                                                                href="<?php echo esc_url($link); ?>" 
                                                                title="<?php esc_html_e('View profile', 'jobster'); ?>"
                                                            >
                                                                <span class="fa fa-eye"></span>
                                                            </a>
                                                        </li>
                                                    <?php } ?>

                                                    <li>
                                                        <a 
                                                            href="javascript:void(0);" 
                                                            title="<?php esc_html_e('Approve', 'jobster'); ?>" 
                                                            class="btn pxp-company-dashboard-app-set-status-btn" 
                                                            data-job-id="<?php echo esc_attr($new_apps[$i]['job_id']); ?>" 
                                                            data-candidate-id="<?php echo esc_attr($new_apps[$i]['candidate_id']); ?>" 
                                                            data-status="approved"
                                                        >
                                                            <span class="fa fa-check"></span>
                                                            <span class="pxp-btn-loading">
                                                                <img 
                                                                    src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                                                    class="pxp-btn-loader" 
                                                                    alt="..."
                                                                >
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a 
                                                            href="javascript:void(0);" 
                                                            title="<?php esc_html_e('Reject', 'jobster'); ?>" 
                                                            class="btn pxp-company-dashboard-app-set-status-btn" 
                                                            data-job-id="<?php echo esc_attr($new_apps[$i]['job_id']); ?>" 
                                                            data-candidate-id="<?php echo esc_attr($new_apps[$i]['candidate_id']); ?>" 
                                                            data-status="rejected"
                                                        >
                                                            <span class="fa fa-ban"></span>
                                                            <span class="pxp-btn-loading">
                                                                <img 
                                                                    src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-dark.svg'); ?>" 
                                                                    class="pxp-btn-loader" 
                                                                    alt="..."
                                                                >
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a 
                                                            href="javascript:void(0);" 
                                                            title="<?php esc_html_e('Delete', 'jobster'); ?>" 
                                                            class="btn pxp-company-dashboard-delete-app-btn" 
                                                            data-job-id="<?php echo esc_attr($new_apps[$i]['job_id']); ?>" 
                                                            data-candidate-id="<?php echo esc_attr($new_apps[$i]['candidate_id']); ?>"
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
    </div>

    <?php get_footer('dashboard'); ?>
</div>