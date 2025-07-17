<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_candidate_dashboard_top')):
    function jobster_get_candidate_dashboard_top($candidate_id) {
        $username = get_the_title($candidate_id);

        $candidate_photo_val = get_post_meta(
            $candidate_id, 'candidate_photo', true
        );
        $candidate_photo = wp_get_attachment_image_src(
            $candidate_photo_val, 'pxp-icon'
        );

        $inbox_args = array(
            'post_id' => $candidate_id
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
            $candidate_id,
            'candidate_notifications',
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

        $dashboard_link = jobster_get_page_link('candidate-dashboard.php');
        $profile_link = jobster_get_page_link('candidate-dashboard-profile.php');
        $apps_link = jobster_get_page_link('candidate-dashboard-apps.php');
        $favs_link = jobster_get_page_link('candidate-dashboard-favs.php');
        $password_link = jobster_get_page_link('candidate-dashboard-password.php');
        $inbox_link = jobster_get_page_link('candidate-dashboard-inbox.php');
        $notifications_link = jobster_get_page_link('candidate-dashboard-notifications.php');
        $job_search_link = jobster_get_page_link('job-search.php'); ?>

        <div 
            class="pxp-dashboard-content-header" 
            style="background-color: var(--pxpSecondaryColorLight);"
        >
            <div class="pxp-nav-trigger navbar pxp-is-dashboard d-lg-none">
                <a 
                    role="button" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#pxpMobileNav" 
                    aria-controls="pxpMobileNav"
                >
                    <div class="pxp-line-1"></div>
                    <div class="pxp-line-2"></div>
                    <div class="pxp-line-3"></div>
                </a>
                <div 
                    class="offcanvas offcanvas-start pxp-nav-mobile-container pxp-is-dashboard" 
                    style="background-color: var(--pxpSecondaryColorLight);" 
                    tabindex="-1" 
                    id="pxpMobileNav"
                >
                    <div class="offcanvas-header">
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
                        <button 
                            type="button" 
                            class="btn-close text-reset" 
                            data-bs-dismiss="offcanvas" 
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="offcanvas-body">
                        <nav class="pxp-nav-mobile">
                            <ul class="navbar-nav justify-content-end flex-grow-1">
                                <li class="pxp-dropdown-header">
                                    <?php esc_html_e('Admin tools', 'jobster'); ?>
                                </li>
                                <?php if ($dashboard_link != '') { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo esc_url($dashboard_link); ?>">
                                            <span class="fa fa-home"></span>
                                            <?php esc_html_e('Dashboard', 'jobster'); ?>
                                        </a>
                                    </li>
                                <?php }
                                if ($profile_link != '') { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo esc_url($profile_link); ?>">
                                            <span class="fa fa-pencil"></span>
                                            <?php esc_html_e('Edit Profile', 'jobster'); ?>
                                        </a>
                                    </li>
                                <?php }
                                if ($apps_link != '') { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo esc_url($apps_link); ?>">
                                            <span class="fa fa-file-text-o"></span>
                                            <?php esc_html_e('Applications', 'jobster'); ?>
                                        </a>
                                    </li>
                                <?php }
                                if ($favs_link != '') { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo esc_url($favs_link); ?>">
                                            <span class="fa fa fa-heart-o"></span>
                                            <?php esc_html_e('Favourite Jobs', 'jobster'); ?>
                                        </a>
                                    </li>
                                <?php }
                                if ($password_link != '') { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo esc_url($password_link); ?>">
                                            <span class="fa fa-lock"></span>
                                            <?php esc_html_e('Change Password', 'jobster'); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li class="pxp-dropdown-header mt-4">
                                    <?php esc_html_e('Insights', 'jobster'); ?>
                                </li>
                                <?php if ($inbox_link != '') { ?>
                                    <li class="nav-item">
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
                                    <li class="nav-item">
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
                    </div>
                </div>
            </div>
            <nav class="pxp-user-nav pxp-on-light">
                <?php if ($job_search_link != '') { ?>
                    <a 
                        href="<?php echo esc_url($job_search_link); ?>" 
                        class="btn rounded-pill pxp-nav-btn"
                    >
                        <?php esc_html_e('Search Jobs', 'jobster'); ?>
                    </a>
                <?php } ?>
                <div class="dropdown pxp-user-nav-dropdown pxp-user-notifications pxp-candidate-notifications">
                    <a 
                        role="button" 
                        class="dropdown-toggle" 
                        data-bs-toggle="dropdown" 
                        data-id="<?php echo esc_attr($candidate_id); ?>"
                    >
                        <span class="fa fa-bell-o"></span>
                        <?php if ($unread_notifications > 0) { ?>
                            <div class="pxp-user-notifications-counter">
                                <?php echo esc_html($unread_notifications); ?>
                            </div>
                        <?php } ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php if (is_array($notifications)) {
                            $total_n = count($notifications);

                            if ($total_n > 0) {
                                $i = 1;
                                while ($total_n - $i >= 0 && $i <= 10): 
                                    $n_item = $notifications[$total_n - $i];

                                    $unread_class = '';
                                    if (isset($n_item['read']) 
                                        && $n_item['read'] === false) {
                                        $unread_class = 'pxp-unread';
                                    }

                                    switch ($n_item['type']) {
                                        case 'visit':
                                            $company_name = get_the_title($n_item['company_id']);
                                            $company_url = get_permalink($n_item['company_id']); ?>

                                            <li class="<?php echo esc_attr($unread_class); ?>">
                                                <a href="<?php echo esc_url($company_url); ?>">
                                                    <?php echo esc_html($company_name); ?>
                                                </a>
                                                <?php esc_html_e('viewed your profile', 'jobster'); ?>
                                                <?php if (isset($n_item['date'])) { ?>
                                                    <span class="pxp-is-time">
                                                        <?php $time_ago = jobster_get_time_ago(
                                                            strtotime($n_item['date'])
                                                        );
                                                        echo esc_html($time_ago); ?>
                                                    </span>
                                                <?php } ?>
                                            </li>

                                            <?php break;
                                        case 'inbox':
                                            $company_name = get_the_title($n_item['company_id']);
                                            $company_url = get_permalink($n_item['company_id']); ?>

                                            <li class="<?php echo esc_attr($unread_class); ?>">
                                                <a href="<?php echo esc_url($company_url); ?>">
                                                    <?php echo esc_html($company_name); ?>
                                                </a>
                                                <?php esc_html_e('sent you a message', 'jobster'); ?>
                                                <?php if (isset($n_item['date'])) { ?>
                                                    <span class="pxp-is-time">
                                                        <?php $time_ago = jobster_get_time_ago(
                                                            strtotime($n_item['date'])
                                                        );
                                                        echo esc_html($time_ago); ?>
                                                    </span>
                                                <?php } ?>
                                            </li>

                                            <?php break;
                                    }
                                    $i++;
                                endwhile; ?>
                                <li class="pxp-has-divider">
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a 
                                        class="dropdown-item pxp-link" 
                                        href="<?php echo esc_url($notifications_link); ?>"
                                    >
                                        <?php esc_html_e('Read All', 'jobster'); ?>
                                    </a>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <?php esc_html_e('No recent notifications', 'jobster'); ?>
                                </li>
                            <?php }
                        } else { ?>
                            <li>
                                <?php esc_html_e('No recent notifications', 'jobster'); ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php wp_nonce_field(
                    'notifications_ajax_nonce',
                    'pxp-notifications-security',
                    true
                ); ?>
                <div class="dropdown pxp-user-nav-dropdown">
                    <a 
                        role="button" 
                        class="dropdown-toggle" 
                        data-bs-toggle="dropdown"
                    >
                        <?php if (is_array($candidate_photo)) { ?>
                            <div 
                                class="pxp-user-nav-avatar pxp-cover" 
                                style="background-image: url(<?php echo esc_url($candidate_photo[0]); ?>);"
                            ></div>
                        <?php } else { ?>
                            <div class="pxp-user-nav-avatar pxp-no-img">
                                <?php echo esc_html($username[0]); ?>
                            </div>
                        <?php } ?>
                        <div class="pxp-user-nav-name d-none d-md-block">
                            <?php echo esc_html($username); ?>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
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
            </nav>
        </div>
    <?php }
endif;

if (!function_exists('jobster_get_candidate_dashboard_top_apply')):
    function jobster_get_candidate_dashboard_top_apply() {
        $job_search_link = jobster_get_page_link('job-search.php'); ?>

        <div 
            class="pxp-dashboard-content-header" 
            style="background-color: var(--pxpSecondaryColorLight);"
        >
            <div class="pxp-nav-trigger navbar pxp-is-dashboard d-lg-none">
                <a 
                    role="button" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#pxpMobileNav" 
                    aria-controls="pxpMobileNav"
                >
                    <div class="pxp-line-1"></div>
                    <div class="pxp-line-2"></div>
                    <div class="pxp-line-3"></div>
                </a>
                <div 
                    class="offcanvas offcanvas-start pxp-nav-mobile-container pxp-is-dashboard" 
                    style="background-color: var(--pxpSecondaryColorLight);" 
                    tabindex="-1" 
                    id="pxpMobileNav"
                >
                    <div class="offcanvas-header">
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
                        <button 
                            type="button" 
                            class="btn-close text-reset" 
                            data-bs-dismiss="offcanvas" 
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="offcanvas-body">
                        <nav class="pxp-nav-mobile">
                            <ul class="navbar-nav justify-content-end flex-grow-1">
                                <li class="nav-item">
                                    <a href="<?php echo esc_url(home_url('/')); ?>">
                                        <span class="fa fa-home"></span>
                                        <?php esc_html_e('Home', 'jobster'); ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#">
                                        <span class="fa fa-file-text-o"></span>
                                        <?php esc_html_e('Job Application', 'jobster'); ?>
                                    </a>
                                </li>
                                <?php if ($job_search_link != '') { ?>
                                    <li class="nav-item">
                                        <a href="<?php echo esc_url($job_search_link); ?>">
                                            <span class="fa fa-search"></span>
                                            <?php esc_html_e('Search Jobs', 'jobster'); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <nav class="pxp-user-nav pxp-on-light">
                <a 
                    href="<?php echo esc_url($job_search_link); ?>" 
                    class="btn rounded-pill pxp-nav-btn"
                >
                    <?php esc_html_e('Search Jobs', 'jobster'); ?>
                </a>
            </nav>
        </div>
    <?php }
endif;
?>