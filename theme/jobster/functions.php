<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!defined('JOBSTER_LOCATION')) {
    define('JOBSTER_LOCATION', get_template_directory_uri());
}

/**
 * Register required plugins
 */
require_once 'libs/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'jobster_register_required_plugins');

if(!function_exists('jobster_register_required_plugins')): 
    function jobster_register_required_plugins() {
        $plugins = array(
            array(
                'name'         => 'Jobster Plugin',
                'slug'         => 'jobster-plugin',
                'source'       => 'https://pixelprime.co/themes/jobster-wp/plugins/jobster-plugin-2-1/jobster-plugin.zip',
                'required'     => true,
                'version'      => '2.1',
                'external_url' => ''
            ),
        );

        $config = array(
            'id'           => 'jobster',
            'default_path' => '',
            'menu'         => 'tgmpa-install-plugins',
            'has_notices'  => true,
            'dismissable'  => false,
            'dismiss_msg'  => '',
            'is_automatic' => false,
            'message'      => '',

            'strings'      => array(
                'page_title'                      => esc_html__('Install Required Plugins', 'jobster'),
                'menu_title'                      => esc_html__('Install Plugins', 'jobster'),
                'installing'                      => esc_html__('Installing Plugin: %s', 'jobster'),
                'updating'                        => esc_html__('Updating Plugin: %s', 'jobster'),
                'oops'                            => esc_html__('Something went wrong with the plugin API.', 'jobster'),
                'notice_can_install_required'     => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'jobster'),
                'notice_can_install_recommended'  => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'jobster'),
                'notice_ask_to_update'            => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'jobster'),
                'notice_ask_to_update_maybe'      => _n_noop('There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'jobster'),
                'notice_can_activate_required'    => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'jobster'),
                'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'jobster'),
                'install_link'                    => _n_noop('Begin installing plugin', 'Begin installing plugins', 'jobster'),
                'update_link'                     => _n_noop('Begin updating plugin', 'Begin updating plugins', 'jobster'),
                'activate_link'                   => _n_noop('Begin activating plugin', 'Begin activating plugins', 'jobster'),
                'return'                          => esc_html__('Return to Required Plugins Installer', 'jobster'),
                'plugin_activated'                => esc_html__('Plugin activated successfully.', 'jobster'),
                'activated_successfully'          => esc_html__('The following plugin was activated successfully:', 'jobster'),
                'plugin_already_active'           => esc_html__('No action taken. Plugin %1$s was already active.', 'jobster'),
                'plugin_needs_higher_version'     => esc_html__('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'jobster'),
                'complete'                        => esc_html__('All plugins installed and activated successfully. %1$s', 'jobster'),
                'dismiss'                         => esc_html__('Dismiss this notice', 'jobster'),
                'notice_cannot_install_activate'  => esc_html__('There are one or more required or recommended plugins to install, update or activate.', 'jobster'),
                'contact_admin'                   => esc_html__('Please contact the administrator of this site for help.', 'jobster'),
                'nag_type'                        => 'updated',
            ),
        );

        tgmpa($plugins, $config);
    }
endif;

/**
 * Theme setup
 */
if (!function_exists('jobster_setup')):
    function jobster_setup() {
        if (function_exists('add_theme_support')) {
            add_theme_support('automatic-feed-links');
            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');
            add_theme_support('custom-logo');
            add_theme_support('html5', array('style', 'script'));
            add_theme_support('responsive-embeds');
        }

        set_post_thumbnail_size(800, 600, true);
        add_image_size('pxp-thmb', 160, 160, true);
        add_image_size('pxp-icon', 200, 200, true);
        add_image_size('pxp-gallery', 800, 600, true);
        add_image_size('pxp-full', 1920, 1280, true);

        if (!isset($content_width)) {
            $content_width = 1140;
        }

        load_theme_textdomain('jobster', JOBSTER_LOCATION . '/languages/');

        register_nav_menus(array(
            'primary' => esc_html__('Top primary menu', 'jobster'),
        ));
    }
endif;
add_action('after_setup_theme', 'jobster_setup');

/**
 * Load scripts
 */
if (!function_exists('jobster_load_scripts')): 
    function jobster_load_scripts() {
        wp_enqueue_style('font-awesome', JOBSTER_LOCATION . '/css/font-awesome.min.css', array(), '4.7.0', 'all');
        wp_enqueue_style('pxp-base-font', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap', array(), '1.0', 'all');
        wp_enqueue_style('bootstrap', JOBSTER_LOCATION . '/css/bootstrap.min.css', array(), '5.1.3', 'all');
        wp_enqueue_style('owl-carousel', JOBSTER_LOCATION . '/css/owl.carousel.min.css', array(), '2.3.4', 'all');
        wp_enqueue_style('owl-theme', JOBSTER_LOCATION . '/css/owl.theme.default.min.css', array(), '2.3.4', 'all');
        wp_enqueue_style('animate', JOBSTER_LOCATION . '/css/animate.css', array(), '4.1.1', 'all');
        if (is_singular('candidate') || is_singular('company')) {
            wp_enqueue_style('photoswipe', JOBSTER_LOCATION . '/css/photoswipe.css', array(), '4.1.3', 'all');
            wp_enqueue_style('photoswipe-skin', JOBSTER_LOCATION . '/css/default-skin/default-skin.css', array(), '4.1.3', 'all');
        }
        wp_enqueue_style('pxp-datepicker', JOBSTER_LOCATION . '/css/datepicker.css', array(), '1.0', 'all');
        wp_enqueue_style('jobster-style', get_stylesheet_uri(), array(), '1.0', 'all');

        wp_deregister_style('common');
        wp_deregister_style('forms');
        wp_enqueue_script('jquery-ui-sortable');

        include_once(ABSPATH . 'wp-admin/includes/plugin.php');

        wp_enqueue_script('bootstrap', JOBSTER_LOCATION . '/js/bootstrap.bundle.min.js', array('jquery'), '5.1.3', true);
        wp_enqueue_script('owl-carousel',  JOBSTER_LOCATION . '/js/owl.carousel.min.js', array(), '2.3.4', true);
        wp_enqueue_script('masonry');
        wp_enqueue_script('jquery-masonry');
        wp_enqueue_script('chartjs',  JOBSTER_LOCATION . '/js/Chart.min.js', array(), '2.3.4', true);

        if (is_singular('candidate') || is_singular('company')) {
            wp_enqueue_script('photoswipe', JOBSTER_LOCATION . '/js/photoswipe.min.js', array(), '4.1.3', true);
            wp_enqueue_script('photoswipe-ui', JOBSTER_LOCATION . '/js/photoswipe-ui-default.min.js', array(), '4.1.3', true);
            wp_enqueue_script('pxp-gallery', JOBSTER_LOCATION . '/js/gallery.js', array('jquery'), '1.0', true);
        }
        wp_enqueue_script('pxp-datepicker', JOBSTER_LOCATION . '/js/bootstrap-datepicker.js', array(), '1.0', true);
        wp_enqueue_script('pxp-main', JOBSTER_LOCATION . '/js/main.js', array('jquery'), '1.0', true);
        wp_enqueue_script('pxp-nav', JOBSTER_LOCATION . '/js/nav.js', array('jquery'), '1.0', true);

        $auth_settings = get_option('jobster_authentication_settings');
        $google_auth = isset($auth_settings['jobster_google_auth_field']) && $auth_settings['jobster_google_auth_field'] == '1';
        $google_auth_client_id =    isset($auth_settings['jobster_google_auth_client_id_field'])
                                    ? $auth_settings['jobster_google_auth_client_id_field']
                                    : '';
        if ($google_auth && !empty($google_auth_client_id)) {
            wp_enqueue_script('google-auth-handle', JOBSTER_LOCATION . '/js/google-auth.js', array(), '1.0', true);
            wp_enqueue_script('google-auth', 'https://accounts.google.com/gsi/client', array(), '1.0', true);
        }

        $fb_auth = isset($auth_settings['jobster_fb_auth_field']) && $auth_settings['jobster_fb_auth_field'] == '1';
        $fb_auth_app_id =   isset($auth_settings['jobster_fb_auth_app_id_field'])
                            ? $auth_settings['jobster_fb_auth_app_id_field']
                            : '';
        if ($fb_auth && !empty($fb_auth_app_id)) {
            wp_enqueue_script('facebook-auth-handle', JOBSTER_LOCATION . '/js/facebook-auth.js', array(), '1.0', true);
        }

        $membership_settings = get_option('jobster_membership_settings', '');
        $payment_type  =    isset($membership_settings['jobster_payment_type_field'])
                            ? $membership_settings['jobster_payment_type_field']
                            : '';
        if ($payment_type == 'listing' || $payment_type == 'plan') {
            $payment_system =   isset($membership_settings['jobster_payment_system_field'])
                                ? $membership_settings['jobster_payment_system_field']
                                : '';
            if ($payment_system == 'stripe') {
                wp_enqueue_script('stripe', 'https://js.stripe.com/v3/', array(), '3.0', true);
            }
        }
        $stripe_pk =    isset($membership_settings['jobster_stripe_pub_key_field'])
                        ? $membership_settings['jobster_stripe_pub_key_field']
                        : '';

        $job_location_terms = get_terms(
            array( 
                'job_location'
            ),
            array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'hide_empty' => false
            )
        );
        $job_locations = array();
        foreach ($job_location_terms as $job_location_term) {
            $job_locations[$job_location_term->term_id] = $job_location_term->name;
        }

        $company_location_terms = get_terms(
            array( 
                'company_location'
            ),
            array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'hide_empty' => false
            )
        );
        $company_locations = array();
        foreach ($company_location_terms as $company_location_term) {
            $company_locations[$company_location_term->term_id] = $company_location_term->name;
        }

        $candidate_location_terms = get_terms(
            array( 
                'candidate_location'
            ),
            array(
                'orderby'    => 'name',
                'order'      => 'ASC',
                'hide_empty' => false
            )
        );
        $candidate_locations = array();
        foreach ($candidate_location_terms as $candidate_location_term) {
            $candidate_locations[$candidate_location_term->term_id] = $candidate_location_term->name;
        }

        wp_enqueue_script('pxp-services', JOBSTER_LOCATION . '/js/services.js', array('jquery'), '1.0', true);
        $user_logged_in = is_user_logged_in() ? 1 : 0;
        wp_localize_script('pxp-services', 'services_vars', 
            array(
                'admin_url'                   => get_admin_url(),
                'ajaxurl'                     => admin_url('admin-ajax.php'),
                'theme_url'                   => JOBSTER_LOCATION,
                'base_url'                    => home_url(),
                'user_logged_in'              => $user_logged_in,
                'applied'                     => esc_html__('Applied', 'jobster'),
                'visitors'                    => esc_html__('Visitors', 'jobster'),
                'vs_7_days'                   => esc_html__('vs last 7 days', 'jobster'),
                'vs_30_days'                  => esc_html__('vs last 30 days', 'jobster'),
                'vs_60_days'                  => esc_html__('vs last 60 days', 'jobster'),
                'vs_90_days'                  => esc_html__('vs last 90 days', 'jobster'),
                'vs_12_months'                => esc_html__('vs last 12 months', 'jobster'),
                'applications'                => esc_html__('Applications', 'jobster'),
                'company_profile_url'         => function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('company-dashboard-profile.php')
                                            : '',
                'company_jobs_url'            => function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('company-dashboard-jobs.php')
                                            : '',
                'company_candidates_url'      => function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('company-dashboard-candidates.php')
                                            : '',
                'company_password_url'        => function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('company-dashboard-password.php')
                                            : '',
                'company_inbox_url'           =>  function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('company-dashboard-inbox.php')
                                            : '',
                'company_notifications_url'   =>  function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('company-dashboard-notifications.php')
                                            : '',
                'approved_status'             => esc_html__('Approved', 'jobster'),
                'rejected_status'             => esc_html__('Rejected', 'jobster'),
                'na_status'                   => esc_html__('N/A', 'jobster'),
                'candidate_profile_url'       => function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('candidate-dashboard-profile.php')
                                            : '',
                'edit'                        => esc_html__('Edit', 'jobster'),
                'download'                    => esc_html__('Download', 'jobster'),
                'delete'                      => esc_html__('Delete', 'jobster'),
                'job_title'                   => esc_html__('Job title', 'jobster'),
                'job_title_placeholder'       => esc_html__('Job title', 'jobster'),
                'company'                     => esc_html__('Company', 'jobster'),
                'company_placeholder'         => esc_html__('Company name', 'jobster'),
                'time_period'                 => esc_html__('Time period', 'jobster'),
                'description'                 => esc_html__('Description', 'jobster'),
                'type_description'            => esc_html__('Type a short description...', 'jobster'),
                'add'                         => esc_html__('Add', 'jobster'),
                'cancel'                      => esc_html__('Cancel', 'jobster'),
                'update'                      => esc_html__('Update', 'jobster'),
                'edu_title'                   => esc_html__('Specialization/Course of study', 'jobster'),
                'edu_title_placeholder'       => esc_html__('E.g. Architecure', 'jobster'),
                'edu_school'                  => esc_html__('Institution', 'jobster'),
                'edu_school_placeholder'      => esc_html__('Institution name', 'jobster'),
                'candidate_apps_url'          => function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('candidate-dashboard-apps.php')
                                            : '',
                'candidate_favs_url'          => function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('candidate-dashboard-favs.php')
                                            : '',
                'candidate_password_url'      => function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('candidate-dashboard-password.php')
                                            : '',
                'candidate_inbox_url'         =>  function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('candidate-dashboard-inbox.php')
                                            : '',
                'candidate_notifications_url' =>  function_exists('jobster_get_page_link') 
                                            ? jobster_get_page_link('candidate-dashboard-notifications.php')
                                            : '',
                'stripe_pk'                   => $stripe_pk,
                'job_locations'               => $job_locations,
                'company_locations'           => $company_locations,
                'candidate_locations'         => $candidate_locations,
                'locations_empty'             => esc_html__('No location found.', 'jobster'),
                'upload_icon'                 => esc_html__('Upload Icon', 'jobster'),
                'fb_signin_error'             => esc_html__('Login cancelled or not fully authorized!', 'jobster'),
                'fb_wait_btn_text'            => esc_html__('Signing in...', 'jobster'),
                'fb_signin_btn_text'          => esc_html__('Continue with Facebook', 'jobster'),
            )
        );

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
endif;
add_action('wp_enqueue_scripts', 'jobster_load_scripts');

/**
 * Add script async defer
 */
if (!function_exists('jobster_add_script_async_defer')) :
    function jobster_add_script_async_defer($tag, $handle) {
        if ($handle !== 'google-auth') {
            return $tag;
        }

        return str_replace(' src=', ' async defer src=', $tag);
    }
endif;
add_filter('script_loader_tag', 'jobster_add_script_async_defer', 10, 2);

if (!function_exists('jobster_wp_title')) :
    function jobster_wp_title($title, $sep) {
        global $page, $paged;

        $title .= get_bloginfo('name', 'display');
        $site_description = get_bloginfo('description', 'display');

        if ($site_description && (is_home() || is_front_page() || is_archive() || is_search())) {
            $title .= " $sep $site_description";
        }

        return $title;
    }
endif;
add_filter('wp_title', 'jobster_wp_title', 10, 2);

if (!function_exists('jobster_widgets_init')): 
    function jobster_widgets_init() {
        register_sidebar(array(
            'name'          => esc_html__('Main Widget Area', 'jobster'),
            'id'            => 'pxp-main-widget-area',
            'description'   => esc_html__('The main widget area', 'jobster'),
            'before_widget' => '<div id="%1$s" class="pxp-side-panel-section %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));
        register_sidebar(array(
            'name'          => esc_html__('Column #1 Footer Widget Area', 'jobster'),
            'id'            => 'pxp-first-footer-widget-area',
            'description'   => esc_html__('The first column footer widget area', 'jobster'),
            'before_widget' => '<div id="%1$s" class="pxp-footer-section %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));
        register_sidebar(array(
            'name'          => esc_html__('Column #2 Footer Widget Area', 'jobster'),
            'id'            => 'pxp-second-footer-widget-area',
            'description'   => esc_html__('The second column footer widget area', 'jobster'),
            'before_widget' => '<div id="%1$s" class="pxp-footer-section %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));
        register_sidebar(array(
            'name'          => esc_html__('Column #3 Footer Widget Area', 'jobster'),
            'id'            => 'pxp-third-footer-widget-area',
            'description'   => esc_html__('The third column footer widget area', 'jobster'),
            'before_widget' => '<div id="%1$s" class="pxp-footer-section %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));

        register_sidebar(array(
            'name'          => esc_html__('Column #4 Footer Widget Area', 'jobster'),
            'id'            => 'pxp-fourth-footer-widget-area',
            'description'   => esc_html__('The fourth column footer widget area', 'jobster'),
            'before_widget' => '<div id="%1$s" class="pxp-footer-section %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));
        register_sidebar(array(
            'name'          => esc_html__('Column #5 Footer Widget Area', 'jobster'),
            'id'            => 'pxp-fifth-footer-widget-area',
            'description'   => esc_html__('The fourth column footer widget area', 'jobster'),
            'before_widget' => '<div id="%1$s" class="pxp-footer-section %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));
    }
endif;
add_action('widgets_init', 'jobster_widgets_init');
add_filter('use_widgets_block_editor', '__return_false');

if (!function_exists('jobster_compare_position')) :
    function jobster_compare_position($a, $b) {
        return intval($a["position"]) - intval($b["position"]);
    }
endif;

if (!function_exists('jobster_get_attachment')) :
    function jobster_get_attachment($id) {
        $attachment = get_post($id);

        return array(
            'alt'         => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
            'caption'     => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'title'       => $attachment->post_title
        );
    }
endif;

/**
 * Custom excerpt lenght
 */
if (!function_exists('jobster_custom_excerpt_length')): 
    function jobster_custom_excerpt_length($length) {
        return 30;
    }
endif;
add_filter('excerpt_length', 'jobster_custom_excerpt_length', 999);

/**
 * Custom excerpt ending
 */
function jobster_excerpt_more($more) {
    return '&#46;&#46;&#46;';
}
add_filter('excerpt_more', 'jobster_excerpt_more');

if (!function_exists('jobster_get_excerpt_by_id')): 
    function jobster_get_excerpt_by_id($post_id) {
        $the_post       = get_post($post_id);
        $the_excerpt    = $the_post->post_content;
        $excerpt_length = 30;
        $the_excerpt    = strip_tags(strip_shortcodes($the_excerpt));
        $words          = explode(' ', $the_excerpt, $excerpt_length + 1);

        if (count($words) > $excerpt_length) :
            array_pop($words);
            array_push($words, '...');
            $the_excerpt = implode(' ', $words);
        endif;

        wp_reset_postdata();

        return $the_excerpt;
    }
endif;

if (!function_exists('jobster_sanitize_item')) :
    function jobster_sanitize_item($item) {
        return sanitize_text_field($item);
    }
endif;

if (!function_exists('jobster_sanitize_multi_array')) :
    function jobster_sanitize_multi_array(&$item, $key) {
        $item = sanitize_text_field($item);
    }
endif;

/**
 * Pagination
 */
if (!function_exists('jobster_pagination')): 
    function jobster_pagination($pages = '', $range = 2) {
        $showitems = ($range * 2) + 1;

        global $paged;
        if (empty($paged)) {
            $paged = 1;
        }

        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }

        if (1 != $pages) { ?>
            <div class="row mt-4 mt-lg-5 justify-content-between align-items-center">
                <div class="col-auto">
                    <nav 
                        class="mt-3 mt-sm-0" 
                        aria-label="<?php esc_html_e('Pagination', 'jobster'); ?>"
                    >
                        <ul class="pagination pxp-pagination">
                            <?php if ($paged > 2 
                                    && $paged > $range + 1 
                                    && $showitems < $pages) { ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo esc_url(get_pagenum_link(1)); ?>">
                                        <span class="fa fa-angle-double-left"></span>
                                    </a>
                                </li>
                            <?php }
                            if ($paged > 1 && $showitems < $pages) { ?>
                                <li class="page-item">
                                    <a 
                                        class="page-link" 
                                        href="<?php echo esc_url(get_pagenum_link($paged - 1)); ?>"
                                    >
                                        <span class="fa fa-angle-left"></span>
                                    </a>
                                </li>
                            <?php }
                            for ($i = 1; $i <= $pages; $i++) {
                                if (1 != $pages
                                    && (!($i >= $paged + $range + 1
                                            || $i <= $paged - $range - 1)
                                        || $pages <= $showitems)) {
                                    if ($paged == $i) { ?>
                                        <li class="page-item active">
                                            <a class="page-link" href="#">
                                                <?php echo esc_html($i) ?>
                                            </a>
                                        </li>
                                    <?php } else { ?>
                                        <li class="page-item">
                                            <a 
                                                class="page-link" 
                                                href="<?php echo esc_url(get_pagenum_link($i)) ?>"
                                            >
                                                <?php echo esc_html($i); ?>
                                            </a>
                                        </li>
                                    <?php }
                                }
                            }
                            if ($paged < $pages && $showitems < $pages) { ?>
                                <li class="page-item">
                                    <a 
                                        class="page-link" 
                                        href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>"
                                    >
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                </li>
                            <?php }
                            if ($paged < $pages - 1 
                                && $paged + $range - 1 < $pages 
                                && $showitems < $pages) { ?>
                                <li class="page-item">
                                    <a 
                                        class="page-link" 
                                        href="<?php echo esc_url(get_pagenum_link($pages)); ?>"
                                    >
                                        <span class="fa fa-angle-double-right"></span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
                <div class="col-auto pxp-next-page-link mt-3 mt-sm-0">
                    <?php next_posts_link(
                        '<div class="btn rounded-pill pxp-section-cta">' 
                            . esc_html__('Show me more', 'jobster') 
                            . '<span class="fa fa-angle-right"></span>
                        </div>',
                        $pages
                    ); ?>
                </div>
            </div>
        <?php }
    }
endif;

/**
 * Pagination
 */
if (!function_exists('jobster_get_time_ago')): 
    function jobster_get_time_ago($time) {
        $time_difference = time() - $time;

        if ($time_difference < 1) {
            return '1s';
        }

        $condition = array(
            12 * 30 * 24 * 60 * 60 => 'y',
            30 * 24 * 60 * 60      => 'mo',
            24 * 60 * 60           => 'd',
            60 * 60                => 'h',
            60                     => 'm',
            1                      => 's'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return $t . $str;
            }
        }
    }
endif;

/**
 * Comments
 */
if (!function_exists('jobster_comment')): 
    function jobster_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);

        if ('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        } ?>

        <<?php echo esc_html($tag); ?> 
            <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> 
            id="comment-<?php comment_ID() ?>"
        >

        <div class="pxp-comments-list-item mt-3 mt-lg-4">
            <?php if ($args['avatar_size'] != 0) {
                echo get_avatar($comment, $args['avatar_size']);
            }

            if ('div' != $args['style']) : ?>
                <div 
                    id="div-comment-<?php comment_ID() ?>" 
                    class="comment-body media-body pxp-comments-list-item-body"
                >
            <?php endif; ?>

            <h5 class="pxp-comment-author">
                <?php echo get_comment_author_link(); ?>
                <span class="badge rounded-pill bg-secondary">
                    <?php esc_html_e('Author', 'jobster'); ?>
                </span>
            </h5>

            <div class="pxp-comments-list-item-date">
                <div class="comment-meta commentmetadata">
                    <?php printf(
                        esc_html__('%1$s at %2$s', 'jobster'),
                        get_comment_date(),
                        get_comment_time()
                    ); ?>
                </div>
            </div>

            <?php if ($comment->comment_approved == '0') : ?>
                <div class="comment-awaiting-moderation alert alert-warning pxp-comments-list-awaiting-moderation">
                    <?php esc_html_e('Your comment is awaiting moderation.', 'jobster'); ?>
                </div>
            <?php endif; ?>

            <div class="comment-content pxp-comments-list-item-content mt-2">
                <?php comment_text(); ?>
            </div>

            <ul class="pxp-comment-ops list-unstyled">
                <li>
                    <?php comment_reply_link(
                        array_merge($args,
                            array(
                                'add_below' => $add_below,
                                'depth' => $depth,
                                'max_depth' => $args['max_depth']
                            )
                        )
                    ); ?>
                </li>
                <li>
                    <?php edit_comment_link(esc_html__('Edit', 'jobster')); ?>
                </li>
            </ul>

            <?php if ('div' != $args['style']) : ?>
                </div>
            <?php endif; ?>
        </div>
    <?php }
endif;

if (!function_exists('jobster_add_user_account_activation_template')): 
    function jobster_add_user_account_activation_template($template) {
        if (isset($_GET['action']) && $_GET['action'] == 'activate') {
            $custom_template = get_template_directory() . '/templates/activate_account.php';

            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }

        return $template;
    }
endif;
add_filter('template_include', 'jobster_add_user_account_activation_template');
?>