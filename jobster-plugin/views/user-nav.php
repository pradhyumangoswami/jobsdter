<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_user_nav')):
    function jobster_get_user_nav($pos = 'top', $is_light = false) {
        $is_light_class = $is_light ? 'pxp-is-light' : '';
        $is_on_light_class = $is_light === false ? 'pxp-on-light' : '';

        if (is_user_logged_in()) {
            global $current_user;

            $current_user = wp_get_current_user();
            $username = get_the_author_meta(
                'display_name' , $current_user->ID
            );

            $is_company = function_exists('jobster_user_is_company')
                        ? jobster_user_is_company($current_user->ID)
                        : false;
            $is_candidate = function_exists('jobster_user_is_candidate')
                            ? jobster_user_is_candidate($current_user->ID)
                            : false;

            if ($is_company) {
                $company_id = jobster_get_company_by_userid($current_user->ID);
                $username = get_the_title($company_id);

                $logo_val = get_post_meta(
                    $company_id, 'company_logo', true
                );
                $logo = wp_get_attachment_image_src(
                    $logo_val, 'pxp-icon'
                );

                $dashboard_link = jobster_get_page_link('company-dashboard.php');
                $new_job_link = jobster_get_page_link('company-dashboard-new-job.php');
                $profile_link = jobster_get_page_link('company-dashboard-profile.php');

                if ($pos == 'top') { ?>
                    <div class="dropdown pxp-user-nav-dropdown <?php echo esc_attr($is_light_class); ?>">
                        <a 
                            role="button" 
                            class="dropdown-toggle" 
                            data-bs-toggle="dropdown"
                        >
                            <?php if (is_array($logo)) { ?>
                                <div 
                                    class="pxp-user-nav-avatar pxp-cover" 
                                    style="background-image: url(<?php echo esc_url($logo[0]); ?>);"
                                ></div>
                            <?php } else if ($username != '') { ?>
                                <div class="pxp-user-nav-avatar pxp-no-img">
                                    <?php echo esc_html($username[0]); ?>
                                </div>
                            <?php } else { ?>
                                <div class="pxp-user-nav-avatar pxp-no-img"> </div>
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
                            if ($new_job_link != '') { ?>
                                <li>
                                    <a 
                                        class="dropdown-item" 
                                        href="<?php echo esc_url($new_job_link); ?>"
                                    >
                                        <?php esc_html_e('Post a Job', 'jobster'); ?>
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
                <?php } else { ?>
                    <ul class="navbar-nav justify-content-end flex-grow-1">
                        <li class="menu-item menu-item-has-children dropdown">
                            <a 
                                role="button" 
                                class="dropdown-toggle" 
                                data-bs-toggle="dropdown"
                            >
                                <div class="d-flex align-items-center">
                                    <?php if (is_array($logo)) { ?>
                                        <div 
                                            class="pxp-user-nav-avatar pxp-cover" 
                                            style="background-image: url(<?php echo esc_url($logo[0]); ?>);"
                                        ></div>
                                    <?php } else if ($username != '') { ?>
                                        <div class="pxp-user-nav-avatar pxp-no-img">
                                            <?php echo esc_html($username[0]); ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="pxp-user-nav-avatar pxp-no-img"> </div>
                                    <?php } ?>
                                    <div class="pxp-user-nav-name">
                                        <?php echo esc_html($username); ?>
                                    </div>
                                </div>
                            </a>
                            <ul class="sub-menu dropdown-menu">
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
                                            <?php esc_html_e('Post a Job', 'jobster'); ?>
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
                        </li>
                    </ul>
                <?php }
            } else if ($is_candidate) { 
                $candidate_id = jobster_get_candidate_by_userid($current_user->ID);
                $username = get_the_title($candidate_id);

                $photo_val = get_post_meta(
                    $candidate_id, 'candidate_photo', true
                );
                $photo = wp_get_attachment_image_src(
                    $photo_val, 'pxp-icon'
                );

                $dashboard_link = jobster_get_page_link('candidate-dashboard.php');
                $profile_link = jobster_get_page_link('candidate-dashboard-profile.php');

                if ($pos == 'top') { ?>
                    <div class="dropdown pxp-user-nav-dropdown <?php echo esc_attr($is_light_class); ?>">
                        <a 
                            role="button" 
                            class="dropdown-toggle" 
                            data-bs-toggle="dropdown"
                        >
                            <?php if (is_array($photo)) { ?>
                                <div 
                                    class="pxp-user-nav-avatar pxp-cover" 
                                    style="background-image: url(<?php echo esc_url($photo[0]); ?>);"
                                ></div>
                            <?php } else if ($username != '') { ?>
                                <div class="pxp-user-nav-avatar pxp-no-img">
                                    <?php echo esc_html($username[0]); ?>
                                </div>
                            <?php } else { ?>
                                <div class="pxp-user-nav-avatar pxp-no-img"> </div>
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
                <?php } else { ?>
                    <ul class="navbar-nav justify-content-end flex-grow-1">
                        <li class="menu-item menu-item-has-children dropdown">
                            <a 
                                role="button" 
                                class="dropdown-toggle" 
                                data-bs-toggle="dropdown"
                            >
                                <div class="d-flex align-items-center">
                                    <?php if (is_array($photo)) { ?>
                                        <div 
                                            class="pxp-user-nav-avatar pxp-cover" 
                                            style="background-image: url(<?php echo esc_url($photo[0]); ?>);"
                                        ></div>
                                    <?php } else { ?>
                                        <div class="pxp-user-nav-avatar pxp-no-img">
                                            <?php echo esc_html($username[0]); ?>
                                        </div>
                                    <?php } ?>
                                    <div class="pxp-user-nav-name">
                                        <?php echo esc_html($username); ?>
                                    </div>
                                </div>
                            </a>
                            <ul class="sub-menu dropdown-menu">
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
                        </li>
                    </ul>
                <?php }
            } else {
                if ($pos == 'top') { ?>
                    <div class="dropdown pxp-user-nav-dropdown <?php echo esc_attr($is_light_class); ?>">
                        <a 
                            role="button" 
                            class="dropdown-toggle" 
                            data-bs-toggle="dropdown"
                        >
                            <div class="pxp-user-nav-avatar pxp-no-img">
                                <?php echo esc_html($username[0]); ?>
                            </div>
                            <div class="pxp-user-nav-name d-none d-md-block">
                                <?php echo esc_html($username); ?>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
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
                <?php } else { ?>
                    <ul class="navbar-nav justify-content-end flex-grow-1">
                        <li class="menu-item menu-item-has-children dropdown">
                            <a 
                                role="button" 
                                class="dropdown-toggle" 
                                data-bs-toggle="dropdown"
                            >
                                <div class="d-flex align-items-center">
                                    <div class="pxp-user-nav-avatar pxp-no-img">
                                        <?php echo esc_html($username[0]); ?>
                                    </div>
                                    <div class="pxp-user-nav-name">
                                        <?php echo esc_html($username); ?>
                                    </div>
                                </div>
                            </a>
                            <ul class="sub-menu dropdown-menu">
                                <li>
                                    <a 
                                        class="dropdown-item" 
                                        href="<?php echo wp_logout_url(home_url()); ?>"
                                    >
                                        <?php esc_html_e('Logout', 'jobster'); ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <?php }
            }
        } else { ?>
            <a 
                class="btn rounded-pill pxp-user-nav-trigger <?php echo esc_attr($is_on_light_class); ?>" 
                data-bs-toggle="modal" 
                href="#pxp-signin-modal" 
                role="button"
            >
                <?php esc_html_e('Sign in', 'jobster'); ?>
            </a>
        <?php }
    }
endif;
?>