<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_company_dashboard_side')):
    function jobster_get_company_dashboard_side($company_id, $active_tab = 'dashboard') {
        $username = get_the_title($company_id);

        $company_logo_val = get_post_meta(
            $company_id, 'company_logo', true
        );
        $company_logo = wp_get_attachment_image_src(
            $company_logo_val, 'pxp-icon'
        );

        $inbox_args = array(
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

        $membership_settings = get_option('jobster_membership_settings', '');
        $payment_type  =    isset($membership_settings['jobster_payment_type_field'])
                            ? $membership_settings['jobster_payment_type_field']
                            : '';

        $dashboard_link = jobster_get_page_link('company-dashboard.php');
        $profile_link = jobster_get_page_link('company-dashboard-profile.php');
        $new_job_link = jobster_get_page_link('company-dashboard-new-job.php');
        $jobs_link = jobster_get_page_link('company-dashboard-jobs.php');
        $candidates_link = jobster_get_page_link('company-dashboard-candidates.php');
        $subscriptions_link = jobster_get_page_link('company-dashboard-subscriptions.php');
        $password_link = jobster_get_page_link('company-dashboard-password.php');
        $inbox_link = jobster_get_page_link('company-dashboard-inbox.php');
        $notifications_link = jobster_get_page_link('company-dashboard-notifications.php'); ?>

        <div class="pxp-dashboard-side-panel d-none d-lg-block">
            <div class="pxp-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src(
                        $custom_logo_id , 'pxp-full'
                    );

                    if ($logo !== false) {
                        print 
                            '<img 
                                src="' . esc_url($logo[0]) . '" 
                                alt="' . esc_attr(get_bloginfo('name')) . '"
                            >';
                    } else {
                        print esc_html(get_bloginfo('name'));
                    } ?>
                </a>
            </div>

            <nav class="mt-3 mt-lg-4 d-flex justify-content-between flex-column pb-100">
                <div class="pxp-dashboard-side-label">
                    <?php esc_html_e('Admin tools', 'jobster'); ?>
                </div>
                <ul class="list-unstyled">
                    <?php if ($dashboard_link != '') { ?>
                        <li class="<?php if ($active_tab == 'dashboard') {
                                    echo esc_attr('pxp-active');
                                } ?>"
                        >
                            <a href="<?php echo esc_url($dashboard_link); ?>">
                                <span class="fa fa-home"></span>
                                <?php esc_html_e('Dashboard', 'jobster'); ?>
                            </a>
                        </li>
                    <?php }
                    if ($profile_link != '') { ?>
                        <li class="<?php if ($active_tab == 'profile') {
                                    echo esc_attr('pxp-active');
                                } ?>"
                        >
                            <a href="<?php echo esc_url($profile_link); ?>">
                                <span class="fa fa-pencil"></span>
                                <?php esc_html_e('Edit Profile', 'jobster'); ?>
                            </a>
                        </li>
                    <?php }
                    if ($new_job_link != '') { ?>
                        <li class="<?php if ($active_tab == 'new_job') {
                                    echo esc_attr('pxp-active');
                                } ?>"
                        >
                            <a href="<?php echo esc_url($new_job_link); ?>">
                                <span class="fa fa-file-text-o"></span>
                                <?php esc_html_e('New Job Offer', 'jobster'); ?>
                            </a>
                        </li>
                    <?php }
                    if ($jobs_link != '') { ?>
                        <li class="<?php if ($active_tab == 'jobs') {
                                    echo esc_attr('pxp-active');
                                } ?>"
                        >
                            <a href="<?php echo esc_url($jobs_link); ?>">
                                <span class="fa fa-briefcase"></span>
                                <?php esc_html_e('Manage Jobs', 'jobster'); ?>
                            </a>
                        </li>
                    <?php }
                    if ($candidates_link != '') { ?>
                        <li class="<?php if ($active_tab == 'candidates') {
                                    echo esc_attr('pxp-active');
                                } ?>"
                        >
                            <a href="<?php echo esc_url($candidates_link); ?>">
                                <span class="fa fa-user-circle-o"></span>
                                <?php esc_html_e('Candidates', 'jobster'); ?>
                            </a>
                        </li>
                    <?php }
                    if ($subscriptions_link != '' && $payment_type == 'plan') { ?>
                        <li class="<?php if ($active_tab == 'subscriptions') {
                                    echo esc_attr('pxp-active');
                                } ?>"
                        >
                            <a href="<?php echo esc_url($subscriptions_link); ?>">
                                <span class="fa fa fa-credit-card"></span>
                                <?php esc_html_e('Subscriptions', 'jobster'); ?>
                            </a>
                        </li>
                    <?php }
                    if ($password_link != '') { ?>
                        <li class="<?php if ($active_tab == 'password') {
                                    echo esc_attr('pxp-active');
                                } ?>"
                        >
                            <a href="<?php echo esc_url($password_link); ?>">
                                <span class="fa fa-lock"></span>
                                <?php esc_html_e('Change Password', 'jobster'); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <div class="pxp-dashboard-side-label mt-3 mt-lg-4">
                    <?php esc_html_e('Insights', 'jobster'); ?>
                </div>
                <ul class="list-unstyled">
                    <?php if ($inbox_link != '') { ?>
                        <li class="<?php if ($active_tab == 'inbox') {
                                    echo esc_attr('pxp-active');
                                } ?>"
                        >
                            <a 
                                href="<?php echo esc_url($inbox_link); ?>" 
                                class="d-flex justify-content-between align-items-center"
                            >
                                <div>
                                    <span class="fa fa-envelope-o"></span>
                                    <?php esc_html_e('Inbox', 'jobster'); ?>
                                </div>
                                <?php if ($count_unread_messages > 0) { ?>
                                    <span class="badge rounded-pill">
                                        <?php echo esc_html($count_unread_messages); ?>
                                    </span>
                                <?php } ?>
                            </a>
                        </li>
                    <?php }
                    if ($notifications_link != '') { ?>
                        <li class="<?php if ($active_tab == 'notifications') {
                                    echo esc_attr('pxp-active');
                                } ?>"
                        >
                            <a 
                                href="<?php echo esc_url($notifications_link); ?>" 
                                class="d-flex justify-content-between align-items-center"
                            >
                                <div>
                                    <span class="fa fa-bell-o"></span>
                                    <?php esc_html_e('Notifications', 'jobster'); ?>
                                </div>
                                <?php if ($unread_notifications > 0) { ?>
                                    <span class="badge rounded-pill pxp-unread-notificatons">
                                        <?php echo esc_html($unread_notifications); ?>
                                    </span>
                                <?php } ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>

            <nav class="pxp-dashboard-side-user-nav-container">
                <div class="pxp-dashboard-side-user-nav">
                    <div class="dropdown pxp-dashboard-side-user-nav-dropdown dropup">
                        <a 
                            role="button" 
                            class="dropdown-toggle" 
                            data-bs-toggle="dropdown"
                        >
                            <?php if (is_array($company_logo)) { ?>
                                <div 
                                    class="pxp-dashboard-side-user-nav-avatar pxp-cover" 
                                    style="background-image: url(<?php echo esc_url($company_logo[0]); ?>);"
                                ></div>
                            <?php } else { ?>
                                <div class="pxp-dashboard-side-user-nav-avatar pxp-no-img">
                                    <?php echo esc_html($username[0]); ?>
                                </div>
                            <?php } ?>
                            <div class="pxp-dashboard-side-user-nav-name d-none d-md-block">
                                <?php echo esc_html($username); ?>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if ($dashboard_link != '') { ?>
                                <li>
                                    <a 
                                        class="dropdown-item" 
                                        href="<?php echo esc_url($dashboard_link); ?>"
                                    >
                                        <?php esc_html_e('Dashboard', 'jobster'); ?>
                                    </a>
                                </li>
                            <?php }
                            if ($new_job_link != '') { ?>
                                <li>
                                    <a 
                                        class="dropdown-item" 
                                        href="<?php echo esc_url($new_job_link); ?>"
                                    >
                                        <?php esc_html_e('Pos a Job', 'jobster'); ?>
                                    </a>
                                </li>
                            <?php }
                            if ($profile_link != '') { ?>
                                <li>
                                    <a 
                                        class="dropdown-item" 
                                        href="<?php echo esc_url($profile_link); ?>"
                                    >
                                        <?php esc_html_e('Edit profile', 'jobster'); ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <li>
                                <a 
                                    class="dropdown-item" 
                                    href="<?php echo wp_logout_url(home_url()); ?>"
                                >
                                    <?php esc_html_e('Logout', 'jobster'); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    <?php }
endif;
?>