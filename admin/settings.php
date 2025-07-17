<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!defined('JOBSTER_VERSION')) {
    $jobster_theme = wp_get_theme('jobster');

    if ($jobster_theme->Version) {
        define('JOBSTER_VERSION', $jobster_theme->Version);
    }
}

add_action('admin_menu', 'jobster_add_admin_menu');
add_action('admin_init', 'jobster_settings_init');

if (!function_exists('jobster_add_admin_menu')): 
    function jobster_add_admin_menu() {
        add_menu_page(
            'Jobster',
            'Jobster',
            'administrator',
            'admin/settings.php',
            'jobster_settings_page',
            'data:image/svg+xml;base64,' . 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNi42NjUiIGhlaWdodD0iMTUuODMyIiB2aWV3Qm94PSIwIDAgMTYuNjY1IDE1LjgzMiI+CiAgPHBhdGggaWQ9IlBhdGhfMyIgZGF0YS1uYW1lPSJQYXRoIDMiIGQ9Ik0xNyw1LjMzM0gxNC41VjMuNjY3QTEuNjY4LDEuNjY4LDAsMCwwLDEyLjgzMiwyaC01QTEuNjY4LDEuNjY4LDAsMCwwLDYuMTY2LDMuNjY3VjUuMzMzaC0yLjVBMS42NjgsMS42NjgsMCwwLDAsMiw3djkuMTY2YTEuNjY4LDEuNjY4LDAsMCwwLDEuNjY3LDEuNjY3SDE3YTEuNjY4LDEuNjY4LDAsMCwwLDEuNjY3LTEuNjY3VjdBMS42NjgsMS42NjgsMCwwLDAsMTcsNS4zMzNaTTEyLjgzMiwzLjY2N1Y1LjMzM2gtNVYzLjY2N1pNNyw3SDE3VjkuNUgzLjY2N1Y3Wk0zLjY2NywxNi4xNjZ2LTVoNXYxLjY2N0gxMlYxMS4xNjZoNXY1WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTIgLTIpIiBmaWxsPSIjZmZmIi8+Cjwvc3ZnPgo='
        );
    }
endif;

if (!function_exists('jobster_settings_init')): 
    function jobster_settings_init() {
        wp_enqueue_style(
            'font-awesome',
            JOBSTER_PLUGIN_PATH . 'css/font-awesome.min.css',
            array(),
            '4.7.0',
            'all'
        );
        wp_enqueue_style(
            'jobster-settings-style',
            JOBSTER_PLUGIN_PATH . 'admin/css/admin.css',
            false,
            '1.0',
            'all'
        );
        
        wp_enqueue_script('media-upload');
        wp_enqueue_style('thickbox');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('my-upload');

        wp_enqueue_code_editor(array('type' => 'text/html'));

        wp_enqueue_script(
            'jobster-settings',
            JOBSTER_PLUGIN_PATH . 'admin/js/admin.js',
            array('wp-color-picker'),
            '1.0',
            true
        );
        wp_localize_script('jobster-settings', 'settings_vars',
            array(
                'plugin_url'  => JOBSTER_PLUGIN_PATH,
                'admin_url'   => get_admin_url(),
                'ajaxurl'     => admin_url('admin-ajax.php'),
                'image_title' => __('Image', 'jobster'),
                'image_btn'   => __('Insert Image', 'jobster'),
            )
        );

        register_setting(
            'jobster_welcome_settings','jobster_welcome_settings'
        );
        register_setting(
            'jobster_setup_settings','jobster_setup_settings'
        );
        register_setting(
            'jobster_general_settings','jobster_general_settings'
        );
        register_setting(
            'jobster_jobs_settings', 'jobster_jobs_settings'
        );
        register_setting(
            'jobster_jobs_fields_settings', 'jobster_jobs_fields_settings'
        );
        register_setting(
            'jobster_jobs_fields_settings', 'jobster_jobs_pending_settings'
        );
        register_setting(
            'jobster_companies_settings', 'jobster_companies_settings'
        );
        register_setting(
            'jobster_companies_fields_settings', 'jobster_companies_fields_settings'
        );
        register_setting(
            'jobster_candidates_settings', 'jobster_candidates_settings'
        );
        register_setting(
            'jobster_candidates_fields_settings', 'jobster_candidates_fields_settings'
        );
        register_setting(
            'jobster_blog_settings', 'jobster_blog_settings'
        );
        register_setting(
            'jobster_authentication_settings', 'jobster_authentication_settings'
        );
        register_setting(
            'jobster_users_settings', 'jobster_users_settings'
        );
        register_setting(
            'jobster_footer_settings', 'jobster_footer_settings'
        );
        register_setting(
            'jobster_colors_settings', 'jobster_colors_settings'
        );
        register_setting(
            'jobster_membership_settings', 'jobster_membership_settings'
        );
        register_setting(
            'jobster_apis_settings', 'jobster_apis_settings'
        );
        register_setting(
            'jobster_email_settings', 'jobster_email_settings'
        );
    }
endif;

require_once 'sections/welcome.php';
require_once 'sections/setup.php';
require_once 'sections/general.php';
require_once 'sections/jobs.php';
require_once 'sections/jobs_fields.php';
require_once 'sections/jobs_pending.php';
require_once 'sections/candidates.php';
require_once 'sections/candidates_fields.php';
require_once 'sections/companies.php';
require_once 'sections/companies_fields.php';
require_once 'sections/blog.php';
require_once 'sections/authentication.php';
require_once 'sections/users.php';
require_once 'sections/footer.php';
require_once 'sections/colors.php';
require_once 'sections/membership.php';
require_once 'sections/apis.php';
require_once 'sections/email.php';

if (!function_exists('jobster_settings_page')): 
    function jobster_settings_page() {
        $allowed_html = array();
        $active_tab =   isset($_GET['tab'])
                        ? wp_kses($_GET['tab'], $allowed_html)
                        : 'welcome';
        $tab = 'jobster_welcome_settings';

        switch ($active_tab) {
            case "welcome":
                jobster_admin_welcome();
                $tab = 'jobster_welcome_settings';
            break;
            case "setup":
                jobster_admin_setup();
                $tab = 'jobster_setup_settings';
            break;
            case "general":
                jobster_admin_general();
                $tab = 'jobster_general_settings';
            break;
            case "jobs":
                jobster_admin_jobs();
                $tab = 'jobster_jobs_settings';
            break;
            case "jobs_fields":
                jobster_admin_jobs_fields();
                $tab = 'jobster_jobs_fields_settings';
            break;
            case "jobs_pending":
                jobster_admin_jobs_pending();
                $tab = 'jobster_jobs_pending_settings';
            break;
            case "companies":
                jobster_admin_companies();
                $tab = 'jobster_companies_settings';
            break;
            case "companies_fields":
                jobster_admin_companies_fields();
                $tab = 'jobster_companies_fields_settings';
            break;
            case "candidates":
                jobster_admin_candidates();
                $tab = 'jobster_candidates_settings';
            break;
            case "candidates_fields":
                jobster_admin_candidates_fields();
                $tab = 'jobster_candidates_fields_settings';
            break;
            case "blog":
                jobster_admin_blog();
                $tab = 'jobster_blog_settings';
            break;
            case "auth":
                jobster_admin_authentication();
                $tab = 'jobster_authentication_settings';
            break;
            case "users":
                jobster_admin_users();
                $tab = 'jobster_users_settings';
            break;
            case "footer":
                jobster_admin_footer();
                $tab = 'jobster_footer_settings';
            break;
            case "colors":
                jobster_admin_colors();
                $tab = 'jobster_colors_settings';
            break;
            case "membership":
                jobster_admin_membership();
                $tab = 'jobster_membership_settings';
            break;
            case "apis":
                jobster_admin_apis();
                $tab = 'jobster_apis_settings';
            break;
            case "email":
                jobster_admin_email();
                $tab = 'jobster_email_settings';
            break;
        } ?>

        <div class="jobster-wrapper">
            <div class="jobster-leftSide">
                <div class="jobster-logo">
                    <img src="<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'admin/images/logo.png'); ?>">
                </div>
                <ul class="jobster-tabs">
                    <li class="<?php echo ($active_tab == 'welcome') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=welcome">
                            <span class="fa fa-info-circle jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Welcome', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'setup') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=setup">
                            <span class="fa fa-magic jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Theme Setup', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'general') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=general">
                            <span class="fa fa-globe jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('General', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'jobs') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=jobs">
                            <span class="fa fa-folder-open-o jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Jobs', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'jobs_fields') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=jobs_fields">
                            <span class="fa fa-sliders jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Jobs Custom Fields', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'jobs_pending') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=jobs_pending">
                            <span class="fa fa-check-square-o jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Pending Jobs', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'companies') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=companies">
                            <span class="fa fa-building-o jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Companies', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'companies_fields') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=companies_fields">
                            <span class="fa fa-sliders jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Companies Custom Fields', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'candidates') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=candidates">
                            <span class="fa fa-address-card-o jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Candidates', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'candidates_fields') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=candidates_fields">
                            <span class="fa fa-sliders jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Candidates Custom Fields', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'blog') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=blog">
                            <span class="fa fa-newspaper-o jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Blog', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'auth') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=auth">
                            <span class="fa fa-user jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Authentication', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'users') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=users">
                            <span class="fa fa-user-plus jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Pending Users', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'footer') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=footer">
                            <span class="fa fa-hand-o-down jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Footer', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'colors') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=colors">
                            <span class="fa fa-tint jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Colors', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'membership') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=membership">
                            <span class="fa fa-credit-card jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Membership & Payment', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'apis') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=apis">
                            <span class="fa fa-cloud jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Job Board External APIs', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                    <li class="<?php echo ($active_tab == 'email') 
                                            ? 'jobster-tab-active' : '' ?>"
                    >
                        <a href="admin.php?page=admin/settings.php&tab=email">
                            <span class="fa fa-envelope jobster-tab-icon"></span>
                            <span class="jobster-tab-link">
                                <?php esc_html_e('Email Templates', 'jobster'); ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="jobster-content">
                <div class="jobster-content-body">
                    <form action='options.php' method='post'>
                        <?php wp_nonce_field('update-options');
                        settings_fields($tab);
                        do_settings_sections($tab);

                        if ($active_tab != 'welcome'
                            && $active_tab != 'setup'
                            && $active_tab != 'users'
                            && $active_tab != 'jobs_pending') {
                            submit_button();
                        } ?>
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    <?php }
endif;
?>