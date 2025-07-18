<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="pxp-root">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php if (function_exists('jobster_get_social_meta')) {
        jobster_get_social_meta();
    }

    wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php if (!function_exists('wp_body_open')) {
        function wp_body_open() {
            do_action('wp_body_open');
        }
    }

    $general_settings = get_option('jobster_general_settings');
    $disable_preloader =    isset($general_settings['jobster_disable_page_preloader_field'])
                            ? $general_settings['jobster_disable_page_preloader_field']
                            : '';
    if ($disable_preloader != '1') { ?>
        <div class="pxp-preloader">
            <span><?php esc_html_e('Loading...', 'jobster'); ?></span>
        </div>
    <?php } else { ?>
        <div class="pxp-preloader d-none"></div>
    <?php } ?>


    <?php $header_type = '';
    $post_type = '';
    $show_search = '';
    $search_system = 'default';
    $hide_title = '';
    $title_bg_color = '';
    if (isset($post)) {
        $header_type = get_post_meta(
            $post->ID,
            'page_header_type',
            true
        );
        $post_type = get_post_type($post);
        $show_search = get_post_meta(
            $post->ID, 'ph_top_search_show_search', true
        );
        $search_system = get_post_meta(
            $post->ID, 'ph_top_search_system', true
        );

        $hide_title = get_post_meta(
            $post->ID,
            'page_settings_hide_title',
            true
        );

        $title_bg_color = get_post_meta(
            $post->ID,
            'page_settings_bg_color',
            true
        );
    }

    $nav_trigger_class = '';
    if ($header_type != 'image_rotator'
        && $header_type != 'top_search') {
        $nav_trigger_class = 'd-xl-none flex-fill';
    }

    $companies_settings = get_option('jobster_companies_settings');
    $company_layout =   isset($companies_settings['jobster_company_page_layout_field'])
                        ? $companies_settings['jobster_company_page_layout_field']
                        : 'wide';

    $candidates_settings = get_option('jobster_candidates_settings');
    $candidate_layout = isset($candidates_settings['jobster_candidate_page_layout_field'])
                        ? $candidates_settings['jobster_candidate_page_layout_field']
                        : 'wide';

    $user_nav_on_light_class = '';
    if (    $header_type == 'image_rotator'
            || $header_type == 'boxed'
            || $header_type == 'none'
            || empty($header_type)
            || $header_type == 'half_image'
            || $header_type == 'center_image'
            || $header_type == 'image_pills'
            || $header_type == 'right_image'
            || (isset($_GET['action']) && $_GET['action'] == 'activate')
    ) {
        $user_nav_on_light_class = 'pxp-on-light';
        if (is_singular('company') && $company_layout == 'wide') {
            $user_nav_on_light_class = '';
        }
        if (is_singular('candidate') && $candidate_layout == 'wide') {
            $user_nav_on_light_class = '';
        }
    }

    $jobs_settings = get_option('jobster_jobs_settings');
    $job_layout =   isset($jobs_settings['jobster_job_page_layout_field']) 
                    ? $jobs_settings['jobster_job_page_layout_field'] 
                    : 'wide';

    $header_boxed_class = '';
    if ($header_type == 'boxed'
        || $header_type == 'top_search'
        || (is_singular('job') && $job_layout == 'center')
        || (is_singular('company') && $company_layout == 'center')
        || (is_singular('candidate') && $candidate_layout == 'center')) {
        $header_boxed_class = 'pxp-bigger';
    }

    $nav_light_class = '';
    $logo_light_class = '';
    if ($header_type == 'image_bg'
        || $header_type == 'top_search'
        || (is_singular('company') && $company_layout == 'wide')
        || (is_singular('candidate') && $candidate_layout == 'wide')
    ) {
        $nav_light_class = 'pxp-light';
        $logo_light_class = 'pxp-light';

        if (isset($_GET['action']) && $_GET['action'] == 'activate') {
            $nav_light_class = '';
            $logo_light_class = '';
        }
    }

    $header_border_class = '';
    if (
        ((is_home() || is_archive() || is_single()) && ($post_type == 'post'))
        || (($header_type == '' || $header_type == 'none') && $hide_title == '1')
        || is_404()
        || (isset($_GET['action']) && $_GET['action'] == 'activate')
    ) {
        $header_border_class = 'pxp-has-border';
    }

    $auth_settings = get_option('jobster_authentication_settings');
    $disable_auth = isset($auth_settings['jobster_disable_auth_field']) 
                    && $auth_settings['jobster_disable_auth_field'] === '1'; ?>

    <div class="pxp-header fixed-top <?php echo esc_attr($header_boxed_class); ?> <?php echo esc_attr($header_border_class); ?>">
        <div class="pxp-container">
            <div class="pxp-header-container">
                <?php if ($header_type == 'image_rotator'
                        || $header_type == 'top_search') { ?>
                    <div class="pxp-logo-nav-container">
                <?php } ?>

                <div class="pxp-logo <?php echo esc_attr($logo_light_class); ?>">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src(
                            $custom_logo_id , 'pxp-full'
                        );

                        $second_logo_id = get_theme_mod('jobster_second_logo');
                        $second_logo = wp_get_attachment_image_src($second_logo_id , 'pxp-full');
                        $first_logo_class = $second_logo !== false ? 'pxp-default-logo' : '';

                        if ($logo !== false) {
                            print 
                                '<img 
                                    src="' . esc_url($logo[0]) . '" 
                                    alt="' . esc_attr(get_bloginfo('name')) . '"
                                    class="' . esc_attr($first_logo_class) . '"
                                >';
                            if ($second_logo !== false) {
                                print 
                                '<img 
                                    src="' . esc_url($second_logo[0]) . '" 
                                    alt="' . esc_attr(get_bloginfo('name')) . '" 
                                    class="pxp-second-logo"
                                >';
                            }
                        } else {
                            print esc_html(get_bloginfo('name'));
                        } ?>
                    </a>
                </div>

                <div class="pxp-nav-trigger navbar <?php echo esc_attr($nav_light_class); ?> <?php echo esc_attr($nav_trigger_class); ?>">
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
                        class="offcanvas offcanvas-start pxp-nav-mobile-container" 
                        tabindex="-1" id="pxpMobileNav"
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
                                <?php wp_nav_menu(
                                    array(
                                        'theme_location' => 'primary',
                                        'menu_class' => 'navbar-nav justify-content-end flex-grow-1'
                                    )
                                ); ?>
                            </nav>

                            <nav class="pxp-user-nav-side pxp-nav-mobile pxp-on-light mt-4 d-flex d-sm-none">
                                <?php if (function_exists('jobster_get_user_nav') 
                                    && !$disable_auth
                                    && !is_page_template('sign-in.php')
                                    && !is_page_template('sign-up.php')) 
                                {
                                    jobster_get_user_nav('side');
                                } ?>
                            </nav>
                        </div>
                    </div>
                </div>

                <?php if ($header_type == 'image_rotator'
                        || $header_type == 'top_search') { ?>
                    </div>
                <?php }

                if ($header_type != 'image_rotator'
                    && $header_type != 'top_search') { ?>
                    <nav class="pxp-nav <?php echo esc_attr($nav_light_class); ?> dropdown-hover-all d-none d-xl-block">
                        <?php wp_nav_menu(
                            array(
                                'theme_location' => 'primary'
                            )
                        ); ?>
                    </nav>
                <?php }

                if ($header_type == 'top_search'
                    && $show_search == '1') {
                    if ($search_system == 'careerjet') {
                        if (function_exists('jobster_get_careerjet_hero_search_jobs_form')) {
                            jobster_get_careerjet_hero_search_jobs_form('top_search');
                        }
                    } else {
                        if (function_exists('jobster_get_hero_search_jobs_form')) {
                            jobster_get_hero_search_jobs_form('top_search');
                        }
                    }
                } ?>

                <?php if (function_exists('jobster_get_user_nav') 
                    && !$disable_auth
                    && !is_page_template('sign-in.php')
                    && !is_page_template('sign-up.php')) 
                { ?>
                    <nav class="pxp-user-nav <?php echo esc_attr($user_nav_on_light_class); ?> d-none d-sm-flex">
                        <?php if ($header_type == 'image_bg'
                        || $header_type == 'top_search'
                        || (is_singular('company') && $company_layout == 'wide')
                        || (is_singular('candidate') && $candidate_layout == 'wide')) {
                            jobster_get_user_nav('top', true);
                        } else {
                            jobster_get_user_nav();
                        } ?>
                    </nav>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php if (function_exists('jobster_get_user_modal') 
        && !$disable_auth
        && !is_page_template('sign-in.php')
        && !is_page_template('sign-up.php')) 
    {
        jobster_get_user_modal();
    } ?>