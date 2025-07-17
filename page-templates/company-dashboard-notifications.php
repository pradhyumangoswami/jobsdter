<?php
/*
Template Name: Company Dashboard - Notifications
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

global $paged;

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$notifications_per_page = 20;

$notifications = get_post_meta(
    $company_id,
    'company_notifications',
    true
);

$new_notifs = array();
if (is_array($notifications)) {
    foreach ($notifications as &$notification) {
        $notification['read'] = true;
    }

    unset($notification);

    update_post_meta(
        $company_id,
        'company_notifications',
        $notifications
    );

    $new_notifs = array_reverse($notifications);
}

$total_notifs = count($new_notifs);

$this_url = jobster_get_page_link('company-dashboard-notifications.php');

jobster_get_company_dashboard_side($company_id, 'notifications'); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_company_dashboard_top($company_id); ?>

    <div class="pxp-dashboard-content-details">
        <h1>
            <?php  esc_html_e('Notifications', 'jobster'); ?>
        </h1>
        <p class="pxp-text-light">
            <?php esc_html_e('History of all your received notifications.', 'jobster'); ?>
        </p>

        <?php wp_nonce_field(
            'company_notifications_ajax_nonce',
            'pxp-company-notifications-security',
            true
        ); ?>

        <div class="mt-4 mt-lg-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <tbody>
                        <?php if ($total_notifs > 0) {
                            for ($i = (intval($paged) - 1) * $notifications_per_page;
                                $i < intval($paged) * $notifications_per_page && $i < $total_notifs;
                                $i++) {
                                if (isset($new_notifs[$i])) {
                                    switch ($new_notifs[$i]['type']) {
                                        case 'application':
                                            $candidate_name = get_the_title($new_notifs[$i]['candidate_id']);
                                            $candidate_url = get_permalink($new_notifs[$i]['candidate_id']);
                                            $job_title = get_the_title($new_notifs[$i]['job_id']);
                                            $job_url = get_permalink($new_notifs[$i]['job_id']);

                                            $candidate_post_status = get_post_status($new_notifs[$i]['candidate_id']);

                                            if ($candidate_post_status == 'draft') {
                                                $candidate_url = jobster_get_page_link('company-dashboard-candidates.php');
                                            } ?>

                                            <tr>
                                                <td style="width: 75%;">
                                                    <div class="pxp-dashboard-notifications-item-left">
                                                        <div class="pxp-dashboard-notifications-item-type">
                                                            <span class="fa fa-briefcase"></span>
                                                        </div>
                                                        <div class="pxp-dashboard-notifications-item-message">
                                                            <a href="<?php echo esc_url($candidate_url); ?>">
                                                                <?php echo esc_html($candidate_name); ?>
                                                            </a>
                                                            <?php esc_html_e('applied for', 'jobster'); ?>
                                                            <a href="<?php echo esc_url($job_url); ?>">
                                                                <?php echo esc_html($job_title); ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="width: 20%;">
                                                    <div class="pxp-dashboard-notifications-item-right">
                                                        <?php $time_ago = jobster_get_time_ago(
                                                            strtotime($new_notifs[$i]['date'])
                                                        );
                                                        echo esc_html($time_ago); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="pxp-dashboard-table-options">
                                                        <ul class="list-unstyled">
                                                            <li>
                                                                <a 
                                                                    href="javascript:void(0);" 
                                                                    title="<?php esc_html_e('Delete', 'jobster'); ?>" 
                                                                    class="btn pxp-company-dashboard-delete-notify-btn" 
                                                                    data-offset="<?php echo esc_attr($i); ?>" 
                                                                    data-company-id="<?php echo esc_attr($company_id); ?>"
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
                                            <?php break;
                                        case 'job_approve':
                                            $job_title = get_the_title($new_notifs[$i]['job_id']);
                                            $job_url = get_permalink($new_notifs[$i]['job_id']); ?>

                                            <tr>
                                                <td style="width: 75%;">
                                                    <div class="pxp-dashboard-notifications-item-left">
                                                        <div class="pxp-dashboard-notifications-item-type">
                                                            <span class="fa fa-briefcase"></span>
                                                        </div>
                                                        <div class="pxp-dashboard-notifications-item-message">
                                                            <a href="<?php echo esc_url($job_url); ?>">
                                                                <?php echo esc_html($job_title); ?>
                                                            </a>
                                                            <?php esc_html_e('was approved', 'jobster'); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="width: 20%;">
                                                    <div class="pxp-dashboard-notifications-item-right">
                                                        <?php $time_ago = jobster_get_time_ago(
                                                            strtotime($new_notifs[$i]['date'])
                                                        );
                                                        echo esc_html($time_ago); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="pxp-dashboard-table-options">
                                                        <ul class="list-unstyled">
                                                            <li>
                                                                <a 
                                                                    href="javascript:void(0);" 
                                                                    title="<?php esc_html_e('Delete', 'jobster'); ?>" 
                                                                    class="btn pxp-company-dashboard-delete-notify-btn" 
                                                                    data-offset="<?php echo esc_attr($i); ?>" 
                                                                    data-company-id="<?php echo esc_attr($company_id); ?>"
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
                                            <?php break;
                                        case 'job_reject':
                                            $job_title = get_the_title($new_notifs[$i]['job_id']);
                                            $edit_job_url = jobster_get_page_link('company-dashboard-edit-job.php');
                                            $edit_job_link = add_query_arg(
                                                'id',
                                                $new_notifs[$i]['job_id'],
                                                $edit_job_url
                                            ); ?>

                                            <tr>
                                                <td style="width: 75%;">
                                                    <div class="pxp-dashboard-notifications-item-left">
                                                        <div class="pxp-dashboard-notifications-item-type">
                                                            <span class="fa fa-briefcase"></span>
                                                        </div>
                                                        <div class="pxp-dashboard-notifications-item-message">
                                                            <a href="<?php echo esc_url($edit_job_link); ?>">
                                                                <?php echo esc_html($job_title); ?>
                                                            </a>
                                                            <?php esc_html_e('was rejected', 'jobster'); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="width: 20%;">
                                                    <div class="pxp-dashboard-notifications-item-right">
                                                        <?php $time_ago = jobster_get_time_ago(
                                                            strtotime($new_notifs[$i]['date'])
                                                        );
                                                        echo esc_html($time_ago); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="pxp-dashboard-table-options">
                                                        <ul class="list-unstyled">
                                                            <li>
                                                                <a 
                                                                    href="javascript:void(0);" 
                                                                    title="<?php esc_html_e('Delete', 'jobster'); ?>" 
                                                                    class="btn pxp-company-dashboard-delete-notify-btn" 
                                                                    data-offset="<?php echo esc_attr($i); ?>" 
                                                                    data-company-id="<?php echo esc_attr($company_id); ?>"
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
                                            <?php break;
                                        case 'inbox':
                                            $candidate_name = get_the_title($new_notifs[$i]['candidate_id']);
                                            $candidate_url = get_permalink($new_notifs[$i]['candidate_id']); ?>

                                            <tr>
                                                <td style="width: 75%;">
                                                    <div class="pxp-dashboard-notifications-item-left">
                                                        <div class="pxp-dashboard-notifications-item-type">
                                                            <span class="fa fa-envelope-o"></span>
                                                        </div>
                                                        <div class="pxp-dashboard-notifications-item-message">
                                                            <a href="<?php echo esc_url($candidate_url); ?>">
                                                                <?php echo esc_html($candidate_name); ?>
                                                            </a>
                                                            <?php esc_html_e('sent you a message', 'jobster'); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="width: 20%;">
                                                    <div class="pxp-dashboard-notifications-item-right">
                                                        <?php $time_ago = jobster_get_time_ago(
                                                            strtotime($new_notifs[$i]['date'])
                                                        );
                                                        echo esc_html($time_ago); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="pxp-dashboard-table-options">
                                                        <ul class="list-unstyled">
                                                            <li>
                                                                <a 
                                                                    href="javascript:void(0);" 
                                                                    title="<?php esc_html_e('Delete', 'jobster'); ?>" 
                                                                    class="btn pxp-company-dashboard-delete-notify-btn" 
                                                                    data-offset="<?php echo esc_attr($i); ?>" 
                                                                    data-company-id="<?php echo esc_attr($company_id); ?>"
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
                                            <?php break;
                                    }
                                }
                            }
                        } ?>
                    </tbody>
                </table>

                <?php if ($total_notifs > 0) {
                    $total_pages = ceil($total_notifs / $notifications_per_page);
                    jobster_pagination($total_pages);
                } ?>
            </div>
        </div>
    </div>

    <?php get_footer('dashboard'); ?>
</div>