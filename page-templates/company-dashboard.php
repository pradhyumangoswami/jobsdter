<?php
/*
Template Name: Company Dashboard
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

jobster_get_company_dashboard_side($company_id, 'dashboard'); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_company_dashboard_top($company_id); ?>

    <div class="pxp-dashboard-content-details">
        <?php $inbox_args = array(
            'post_id' => $company_id
        ); 
        $inbox_messages = get_comments($inbox_args);

        $count_unread_messages = 0;
        if (is_array($inbox_messages)) {
            foreach ($inbox_messages as $message) {
                $read = get_comment_meta($message->comment_ID, 'read', true);

                if (empty($read)) {
                    $count_unread_messages++;
                }
            }
        }

        $notifications = get_post_meta(
            $company_id,
            'company_notifications',
            true
        );

        $unread_notifications = 0;
        if (is_array($notifications)) {
            foreach ($notifications as $notification) {
                if (isset($notification['read']) 
                    && $notification['read'] === false) {
                    $unread_notifications++;
                }
            }
        }

        $notifications_link = jobster_get_page_link('company-dashboard-notifications.php');
        $inbox_link = jobster_get_page_link('company-dashboard-inbox.php');
        $candidates_link = jobster_get_page_link('company-dashboard-candidates.php');

        $company_name = get_the_title($company_id); ?>

        <h1><?php esc_html_e('Dashboard', 'jobster'); ?></h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Welcome', 'jobster') ?>, 
            <?php echo esc_html($company_name); ?>!
        </p>

        <div class="row mt-4 mt-lg-5 align-items-center">
            <div class="col-sm-6 col-xxl-3">
                <div class="pxp-dashboard-stats-card bg-primary bg-opacity-10 mb-3 mb-xxl-0">
                    <div class="pxp-dashboard-stats-card-icon text-primary">
                        <span class="fa fa-file-text-o"></span>
                    </div>
                    <div class="pxp-dashboard-stats-card-info">
                        <div class="pxp-dashboard-stats-card-info-number">
                            <?php echo esc_html(
                                jobster_get_jobs_no_by_company_id($company_id)
                            ); ?>
                        </div>
                        <div class="pxp-dashboard-stats-card-info-text pxp-text-light">
                            <?php esc_html_e('Jobs posted', 'jobster') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
                <div class="pxp-dashboard-stats-card bg-success bg-opacity-10 mb-3 mb-xxl-0">
                    <div class="pxp-dashboard-stats-card-icon text-success">
                        <span class="fa fa-user-circle-o"></span>
                    </div>
                    <div class="pxp-dashboard-stats-card-info">
                        <div class="pxp-dashboard-stats-card-info-number">
                            <?php echo esc_html(
                                jobster_get_apps_no_by_company_id($company_id)
                            ); ?>
                        </div>
                        <div class="pxp-dashboard-stats-card-info-text pxp-text-light">
                            <?php esc_html_e('Applications', 'jobster') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
                <div class="pxp-dashboard-stats-card bg-warning bg-opacity-10 mb-3 mb-xxl-0">
                    <div class="pxp-dashboard-stats-card-icon text-warning">
                        <span class="fa fa-envelope-o"></span>
                    </div>
                    <div class="pxp-dashboard-stats-card-info">
                        <div class="pxp-dashboard-stats-card-info-number">
                            <?php echo esc_html($count_unread_messages); ?>
                        </div>
                        <div class="pxp-dashboard-stats-card-info-text pxp-text-light">
                            <?php esc_html_e('Unread messages', 'jobster') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xxl-3">
                <div class="pxp-dashboard-stats-card bg-danger bg-opacity-10 mb-3 mb-xxl-0">
                    <div class="pxp-dashboard-stats-card-icon text-danger">
                        <span class="fa fa-bell-o"></span>
                    </div>
                    <div class="pxp-dashboard-stats-card-info">
                        <div class="pxp-dashboard-stats-card-info-number pxp-is-notify">
                            <?php echo esc_html($unread_notifications); ?>
                        </div>
                        <div class="pxp-dashboard-stats-card-info-text pxp-text-light">
                            <?php esc_html_e('Notifications', 'jobster') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input 
            type="hidden" 
            id="pxp-company-id" 
            value="<?php echo esc_attr($company_id); ?>"
        >
        <?php wp_nonce_field('charts_ajax_nonce', 'pxp-charts-security', true); ?>

        <div class="row mt-4 mt-lg-5">
            <div class="col-xl-6">
                <h2><?php esc_html_e('Company\'s Jobs Visitors', 'jobster'); ?></h2>
                <div class="mt-3 mt-lg-4 pxp-dashboard-chart-container">
                    <div class="row justify-content-between align-items-center mb-4">
                        <div class="col-auto d-flex align-items-center">
                            <span class="pxp-dashboard-chart-value pxp-company-dashboard-visitors-number-total">0</span>
                            <span class="pxp-dashboard-chart-percent pxp-company-dashboard-visitors-chart-percent"></span>
                            <span class="pxp-dashboard-chart-vs pxp-company-dashboard-visitors-vs"></span>
                        </div>
                        <div class="col-auto">
                            <select 
                                class="form-select" 
                                id="pxp-company-visitors-period"
                            >
                                <option value="-7 days">
                                    <?php esc_html_e('Last 7 days', 'jobster'); ?>
                                </option>
                                <option value="-30 days">
                                    <?php esc_html_e('Last 30 days', 'jobster'); ?>
                                </option>
                                <option value="-60 days">
                                    <?php esc_html_e('Last 60 days', 'jobster'); ?>
                                </option>
                                <option value="-90 days">
                                    <?php esc_html_e('Last 90 days', 'jobster'); ?>
                                </option>
                                <option value="-12 months">
                                    <?php esc_html_e('Last 12 months', 'jobster'); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <canvas id="pxp-company-dashboard-visitors-chart"></canvas>
                </div>
            </div>

            <div class="col-xl-6">
                <h2 class="mt-4 mt-lg-5 mt-xl-0">
                    <?php esc_html_e('Applications', 'jobster'); ?>
                </h2>
                <div class="mt-3 mt-lg-4 pxp-dashboard-chart-container">
                    <div class="row justify-content-between align-items-center mb-4">
                        <div class="col-auto d-flex align-items-center">
                            <span class="pxp-dashboard-chart-value pxp-company-dashboard-apps-number-total">0</span>
                            <span class="pxp-dashboard-chart-percent pxp-company-dashboard-apps-chart-percent"></span>
                            <span class="pxp-dashboard-chart-vs pxp-company-dashboard-apps-vs"></span>
                        </div>
                        <div class="col-auto">
                            <select 
                                class="form-select" 
                                id="pxp-company-apps-period"
                            >
                                <option value="-7 days">
                                    <?php esc_html_e('Last 7 days', 'jobster'); ?>
                                </option>
                                <option value="-30 days">
                                    <?php esc_html_e('Last 30 days', 'jobster'); ?>
                                </option>
                                <option value="-60 days">
                                    <?php esc_html_e('Last 60 days', 'jobster'); ?>
                                </option>
                                <option value="-90 days">
                                    <?php esc_html_e('Last 90 days', 'jobster'); ?>
                                </option>
                                <option value="-12 months">
                                    <?php esc_html_e('Last 12 months', 'jobster'); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <canvas id="pxp-company-dashboard-apps-chart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mt-4 mt-lg-5">
            <div class="col-xxl-6">
                <h2><?php esc_html_e('Recent Notifications', 'jobster'); ?></h2>
                <div class="pxp-dashboard-notifications">
                    <?php if (is_array($notifications)) {
                        $total_n = count($notifications);

                        if ($total_n > 0) {
                            $i = 1;
                            while ($total_n - $i >= 0 && $i <= 10): 
                                $n_item = $notifications[$total_n - $i];

                                switch ($n_item['type']) {
                                    case 'application':
                                        $candidate_name = get_the_title($n_item['candidate_id']);
                                        $candidate_url = get_permalink($n_item['candidate_id']);
                                        $job_title = get_the_title($n_item['job_id']);
                                        $job_url = get_permalink($n_item['job_id']);

                                        $candidate_post_status = get_post_status($n_item['candidate_id']);

                                        if ($candidate_post_status == 'draft') {
                                            $candidate_url = $candidates_link;
                                        } ?>

                                        <div class="pxp-dashboard-notifications-item mb-3">
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
                                            <?php if (isset($n_item['date'])) { ?>
                                                <div class="pxp-dashboard-notifications-item-right">
                                                    <?php $time_ago = jobster_get_time_ago(
                                                        strtotime($n_item['date'])
                                                    );
                                                    echo esc_html($time_ago); ?>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <?php break;
                                    case 'job_approve':
                                        $job_title = get_the_title($n_item['job_id']);
                                        $job_url = get_permalink($n_item['job_id']); ?>

                                        <div class="pxp-dashboard-notifications-item mb-3">
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
                                            <?php if (isset($n_item['date'])) { ?>
                                                <div class="pxp-dashboard-notifications-item-right">
                                                    <?php $time_ago = jobster_get_time_ago(
                                                        strtotime($n_item['date'])
                                                    );
                                                    echo esc_html($time_ago); ?>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <?php break;
                                    case 'job_reject':
                                        $job_title = get_the_title($n_item['job_id']);
                                        $edit_job_url = jobster_get_page_link('company-dashboard-edit-job.php');
                                        $edit_job_link = add_query_arg(
                                            'id',
                                            $n_item['job_id'],
                                            $edit_job_url
                                        ); ?>

                                        <div class="pxp-dashboard-notifications-item mb-3">
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
                                            <?php if (isset($n_item['date'])) { ?>
                                                <div class="pxp-dashboard-notifications-item-right">
                                                    <?php $time_ago = jobster_get_time_ago(
                                                        strtotime($n_item['date'])
                                                    );
                                                    echo esc_html($time_ago); ?>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <?php break;
                                    case 'inbox':
                                        $candidate_name = get_the_title($n_item['candidate_id']);
                                        $candidate_url = get_permalink($n_item['candidate_id']); ?>

                                        <div class="pxp-dashboard-notifications-item mb-3">
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
                                            <?php if (isset($n_item['date'])) { ?>
                                                <div class="pxp-dashboard-notifications-item-right">
                                                    <?php $time_ago = jobster_get_time_ago(
                                                        strtotime($n_item['date'])
                                                    );
                                                    echo esc_html($time_ago); ?>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <?php break;
                                }
                                $i++;
                            endwhile; 

                            if ($notifications_link != '') { ?>
                                <div class="mt-4">
                                    <a 
                                        class="pxp-dashboard-more-cta" 
                                        href="<?php echo esc_url($notifications_link); ?>"
                                    >
                                        <?php esc_html_e('Read all', 'jobster'); ?> 
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>
                            <?php }
                        } else { ?>
                            <div class="pxp-dashboard-notifications-item mb-3">
                                <div class="pxp-dashboard-notifications-item-left">
                                    <?php esc_html_e('No recent notifications', 'jobster'); ?>
                                </div>
                            </div>
                        <?php }
                    }  else  { ?>
                        <div class="pxp-dashboard-notifications-item mb-3">
                            <div class="pxp-dashboard-notifications-item-left">
                                <?php esc_html_e('No recent notifications', 'jobster'); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="col-xxl-6">
                <h2 class="mt-4 mt-lg-5 mt-xxl-0">
                    <?php esc_html_e('Recent Messages', 'jobster'); ?>
                </h2>
                <div class="pxp-company-dashboard-messages">
                    <?php $messages_args = array(
                        'post_id' => $company_id,
                        'number' => 4
                    );
                    $recent_messages = get_comments($messages_args);

                    if (is_array($recent_messages) 
                        && count($recent_messages) > 0) {
                        foreach($recent_messages as $r_message) {
                            $r_message_candidate_id = get_comment_meta(
                                $r_message->comment_ID,
                                'candidate_id',
                                true
                            );

                            $r_message_candidate = get_post($r_message_candidate_id);

                            if (isset($r_message_candidate)) { 
                                $r_photo_val = get_post_meta(
                                                    $r_message_candidate->ID,
                                                    'candidate_photo',
                                                    true
                                                );
                                $r_photo =  wp_get_attachment_image_src(
                                                $r_photo_val, 'pxp-thmb'
                                            );
                                $r_title =  get_the_title($r_message_candidate->ID); ?>

                                <div class="pxp-company-dashboard-messages-item mb-3">
                                    <?php if (is_array($r_photo)) { ?>
                                        <div 
                                            class="pxp-company-dashboard-messages-item-avatar me-3 pxp-cover" 
                                            style="background-image: url(<?php echo esc_url($r_photo[0]); ?>);"
                                        ></div>
                                    <?php } else { ?>
                                        <div class="pxp-company-dashboard-messages-item-avatar me-3 pxp-no-img">
                                            <?php echo esc_html($r_title[0]); ?>
                                        </div>
                                    <?php } ?>
                                    <div class="pxp-company-dashboard-messages-item-details">
                                        <div class="pxp-company-dashboard-messages-item-name">
                                            <?php echo esc_html($r_title); ?>
                                        </div>
                                        <div class="pxp-company-dashboard-messages-item-date pxp-text-light">
                                            <?php printf(
                                                esc_html__('%1$s at %2$s', 'jobster'),
                                                get_comment_date('', $r_message->comment_ID),
                                                date("H:i", strtotime($r_message->comment_date))
                                            ); ?>
                                        </div>
                                        <p class="mt-1 mb-0">
                                            <?php echo esc_html($r_message->comment_content); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php }

                        if ($inbox_link != '') { ?>
                            <div class="mt-4">
                                <a 
                                    class="pxp-dashboard-more-cta" 
                                    href="<?php echo esc_url($inbox_link); ?>"
                                >
                                    <?php esc_html_e('Read all', 'jobster'); ?> 
                                    <span class="fa fa-angle-right"></span>
                                </a>
                            </div>
                        <?php }
                    } else { ?>
                        <div class="pxp-company-dashboard-messages-item mb-3">
                            <?php esc_html_e('No recent messages', 'jobster'); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="mt-4 mt-lg-5">
            <h2><?php esc_html_e('Recent Candidates', 'jobster') ?></h2>

            <?php $recent_apps = jobster_get_recent_apps_by_company_id($company_id);

            if (count($recent_apps)) { ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <tbody>
                            <?php foreach ($recent_apps as $r_app) {
                                $r_app_candidate = get_post($r_app['candidate_id']);

                                if (isset($r_app_candidate)) { 
                                    $r_app_photo_val = get_post_meta(
                                                            $r_app['candidate_id'],
                                                            'candidate_photo',
                                                            true
                                                        );
                                    $r_app_photo =  wp_get_attachment_image_src(
                                                        $r_app_photo_val,
                                                        'pxp-thmb'
                                                    );
                                    $r_app_name = get_the_title($r_app['candidate_id']);
                                    $r_app_link = get_the_permalink($r_app['candidate_id']);
                                    $r_app_title = get_post_meta(
                                                        $r_app['candidate_id'],
                                                        'candidate_title',
                                                        true
                                                    );
                                    $location = wp_get_post_terms(
                                                    $r_app['candidate_id'],
                                                    'candidate_location'
                                                );
                                    $location_string =  $location
                                                        ? $location[0]->name
                                                        : '';
                                    $r_app_job_name = get_the_title($r_app['job_id']);
                                    $r_app_job_link = get_the_permalink($r_app['job_id']);

                                    $candidate_post_status = get_post_status($r_app['candidate_id']);

                                    if ($candidate_post_status == 'draft') {
                                        $r_app_link = $candidates_link;
                                    } ?>

                                    <tr>
                                        <td style="width: 3%;">
                                            <a 
                                                href="<?php echo esc_url($r_app_link); ?>" 
                                                class="pxp-dashboard-table-link"
                                            >
                                                <?php if (is_array($r_app_photo)) { ?>
                                                    <div 
                                                        class="pxp-company-dashboard-candidate-avatar pxp-cover" 
                                                        style="background-image: url(<?php echo esc_url($r_app_photo[0]); ?>);"
                                                    ></div>
                                                <?php } else { ?>
                                                    <div class="pxp-company-dashboard-candidate-avatar pxp-no-img">
                                                        <?php echo esc_html($r_app_name[0]); ?>
                                                    </div>
                                                <?php } ?>
                                            </a>
                                        </td>
                                        <td style="width: 25%;">
                                            <div class="pxp-company-dashboard-candidate-name">
                                                <a 
                                                    href="<?php echo esc_url($r_app_link); ?>" 
                                                    class="pxp-dashboard-table-link"
                                                >
                                                    <?php echo esc_html($r_app_name); ?>
                                                </a>
                                            </div>
                                        </td>
                                        <td style="width: 25%;">
                                            <div class="pxp-company-dashboard-candidate-title">
                                                <?php echo esc_html($r_app_title); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="pxp-company-dashboard-candidate-location">
                                                <?php if ($location_string != '') { ?>
                                                    <span class="fa fa-globe"></span>
                                                    <?php echo esc_html($location_string);
                                                } ?>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="pxp-company-dashboard-candidate-job">
                                                <?php esc_html_e('Applied for', 'jobster'); ?>
                                                <a 
                                                    href="<?php echo esc_url($r_app_job_link); ?>" 
                                                    class="pxp-dashboard-table-link"
                                                >
                                                    <span>
                                                        <?php echo esc_html($r_app_job_name); ?>
                                                    </span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($candidates_link != '') { ?>
                    <div class="mt-4">
                        <a 
                            class="pxp-dashboard-more-cta" 
                            href="<?php echo esc_url($candidates_link); ?>"
                        >
                            <?php esc_html_e('View all', 'jobster'); ?> 
                            <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
                <?php }
            } else { ?>
                <div class="mb-3">
                    <?php esc_html_e('No recent candidates', 'jobster'); ?>
                </div>
            <?php }

            ?>
        </div>
    </div>

    <?php get_footer('dashboard'); ?>
</div>