<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

while (have_posts()) : the_post();
    $candidate_id = get_the_ID();

    $cover_type = get_post_meta($candidate_id, 'candidate_cover_type', true);
    $cover_val = get_post_meta($candidate_id, 'candidate_cover', true);
    $cover = wp_get_attachment_image_src($cover_val, 'pxp-full');
    $cover_color = get_post_meta($candidate_id, 'candidate_cover_color', true);

    $photo_val = get_post_meta($candidate_id, 'candidate_photo', true);
    $photo = wp_get_attachment_image_src($photo_val, 'pxp-thmb');

    $name = get_the_title($candidate_id);
    $title = get_post_meta($candidate_id, 'candidate_title', true);

    $gallery = get_post_meta($candidate_id, 'candidate_gallery', true);
    $photos  = explode(',', $gallery);
    $gallery_title = get_post_meta($candidate_id, 'candidate_gallery_title', true);

    $video = get_post_meta($candidate_id, 'candidate_video', true);
    $video_title = get_post_meta($candidate_id, 'candidate_video_title', true);

    $candidate_user_id = get_post_meta($candidate_id, 'candidate_user', true);

    $is_company = false;
    if (is_user_logged_in()) {
        global $current_user;

        $current_user = wp_get_current_user();
        $is_company = function_exists('jobster_user_is_company')
                        ? jobster_user_is_company($current_user->ID)
                        : false;

        if ($is_company) {
            $company_id = jobster_get_company_by_userid($current_user->ID);
            $visitors = get_post_meta($candidate_id, 'candidate_visitors', true);

            if (!is_array($visitors)) {
                $visitors = array();
            }

            if (!array_key_exists($company_id, $visitors)) {
                $visitors[$company_id] = current_time('mysql');

                update_post_meta($candidate_id, 'candidate_visitors', $visitors);

                $notifications = get_post_meta(
                    $candidate_id,
                    'candidate_notifications',
                    true
                );
    
                if (empty($notifications)) {
                    $notifications = array();
                }

                array_push(
                    $notifications,
                    array(
                        'type'       => 'visit',
                        'company_id' => $company_id,
                        'read'       => false,
                        'date'       => current_time('mysql')
                    )
                );
    
                update_post_meta(
                    $candidate_id,
                    'candidate_notifications',
                    $notifications
                );
            }
        }
    }

    $candidates_settings = get_option('jobster_candidates_settings');
    $restrict_profile = isset($candidates_settings['jobster_candidate_restrict_profile_field']) 
                        ? $candidates_settings['jobster_candidate_restrict_profile_field'] 
                        : '';
    $restrict_contact = isset($candidates_settings['jobster_candidate_restrict_contact_field']) 
                        ? $candidates_settings['jobster_candidate_restrict_contact_field'] 
                        : ''; 
    $restrict_resume =  isset($candidates_settings['jobster_candidate_restrict_resume_field']) 
                        ? $candidates_settings['jobster_candidate_restrict_resume_field'] 
                        : '';
    $hide_email =   isset($candidates_settings['jobster_candidates_hide_email_field'])
                        && $candidates_settings['jobster_candidates_hide_email_field'] == '1';

    $show_profile = true;
    $show_contact = true;
    $show_resume = true;
    if ($restrict_profile == '1' && !$is_company) {
        $show_profile = false;
    }
    if ($restrict_contact == '1' && !$is_company) {
        $show_contact = false;
    }
    if ($restrict_resume == '1' && !$is_company) {
        $show_resume = false;
    }
    if (is_user_logged_in()) {
        if ($candidate_user_id == $current_user->ID) {
            $show_profile = true;
            $show_contact = true;
            $show_resume = true;
        }
    }

    $has_cover = false;
    if ($cover_type === 'i' && is_array($cover)) {
        $has_cover = true;
    }
    if ($cover_type === 'c' && !empty($cover_color)) {
        $has_cover = true;
    }

    $side_margin_class = is_array($cover) ? 'mt-5 mt-lg-0' : 'mt-5 mt-lg-4';
    $profile_column_class = ($show_profile === true) ? 'col-lg-7 col-xl-8 col-xxl-9' : 'col-12'; ?>

    <section>
        <div class="pxp-container">
            <div class="pxp-single-candidate-container pxp-has-columns">
                <div class="row">
                    <div class="<?php echo esc_attr($profile_column_class); ?>">
                        <?php if ($has_cover) {
                            if ($cover_type === 'i') { ?>
                                <div 
                                    class="pxp-single-candidate-hero pxp-cover pxp-boxed" 
                                    style="background-image: url(<?php echo esc_url($cover[0]); ?>);"
                                >
                                    <div class="pxp-hero-opacity"></div>
                            <?php }
                            if ($cover_type === 'c') { ?>
                                <div 
                                    class="pxp-single-candidate-hero pxp-boxed" 
                                    style="background-color: <?php echo esc_attr($cover_color); ?>;"
                                >
                            <?php }
                        } else { ?>
                            <div class="pxp-single-candidate-hero pxp-no-cover pxp-boxed mt-4">
                        <?php } ?>
                            <div class="pxp-single-candidate-hero-caption">
                                <div class="pxp-single-candidate-hero-content">
                                    <?php if (is_array($photo)) { ?>
                                        <div 
                                            class="pxp-single-candidate-hero-avatar" 
                                            style="background-image: url(<?php echo esc_url($photo[0]); ?>);"
                                        ></div>
                                    <?php } else { ?>
                                        <div class="pxp-single-candidate-hero-avatar pxp-no-img">
                                            <?php echo esc_html($name[0]); ?>
                                        </div>
                                    <?php } ?>
                                    <div class="pxp-single-candidate-hero-name">
                                        <h1><?php echo esc_html($name); ?></h1>
                                        <?php if (!empty($title)) { ?>
                                            <div class="pxp-single-candidate-hero-title">
                                                <?php echo esc_html($title); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($show_profile === true) { ?>
                            <div class="mt-4 mt-lg-5">
                                <div class="pxp-single-candidate-content">
                                    <?php $candidate_firstname = trim(
                                        strstr(
                                            get_the_title($candidate_id), ' ', true
                                        )
                                    ); ?>
                                    <h2>
                                        <?php printf(
                                            __('About %s', 'jobster'),
                                            esc_html($candidate_firstname)
                                        ); ?>
                                    </h2>
                                    <div>
                                        <?php the_content(); ?>
                                    </div>
    
                                    <?php $skills = wp_get_post_terms(
                                        $candidate_id,
                                        'candidate_skill'
                                    );
    
                                    if ($skills) { ?>
                                        <div class="mt-4 mt-lg-5">
                                            <h2><?php esc_attr_e('Skills', 'jobster'); ?></h2>
                                            <div class="pxp-single-candidate-skills">
                                                <ul class="list-unstyled">
                                                    <?php foreach ($skills as $skill) { ?>
                                                        <li>
                                                            <?php echo esc_html($skill->name); ?>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php }
    
                                    $work = get_post_meta(
                                        $candidate_id,
                                        'candidate_work',
                                        true
                                    );
                                    $work_list = array();
    
                                    if (!empty($work)) {
                                        $work_data = json_decode(urldecode($work));
    
                                        if (isset($work_data)) {
                                            $work_list = $work_data->works;
                                        }
                                    }
    
                                    if (count($work_list) > 0) { ?>
                                        <div class="mt-4 mt-lg-5">
                                            <h2>
                                                <?php esc_attr_e('Work Experience', 'jobster'); ?>
                                            </h2>
                                            <div class="pxp-single-candidate-timeline">
                                                <?php foreach ($work_list as $work_item) { ?>
                                                    <div class="pxp-single-candidate-timeline-item">
                                                        <div class="pxp-single-candidate-timeline-dot"></div>
                                                        <div class="pxp-single-candidate-timeline-info ms-3">
                                                            <div class="pxp-single-candidate-timeline-time">
                                                                <span class="me-3">
                                                                    <?php echo esc_html($work_item->period); ?>
                                                                </span>
                                                            </div>
                                                            <div class="pxp-single-candidate-timeline-position mt-2">
                                                                <?php echo esc_html($work_item->title); ?>
                                                            </div>
                                                            <div class="pxp-single-candidate-timeline-company pxp-text-light">
                                                                <?php echo esc_html($work_item->company); ?>
                                                            </div>
                                                            <div class="pxp-single-candidate-timeline-about mt-2 pb-4">
                                                                <?php $allow_tags = array(
                                                                    'br' => array(),
                                                                    'p' => array(
                                                                        'style' => array()
                                                                    ),
                                                                    'strong' => array(),
                                                                    'em' => array(),
                                                                    'span' => array(
                                                                        'style' => array()
                                                                    ),
                                                                    'del' => array(),
                                                                    'ul' => array(),
                                                                    'ol' => array(),
                                                                    'li' => array(
                                                                        'style' => array()
                                                                    ),
                                                                    'a' => array(
                                                                        'href' => array()
                                                                    ),
                                                                    'blockquote' => array(
                                                                        'style' => array()
                                                                    )
                                                                );

                                                                echo wp_kses($work_item->description, $allow_tags); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php }
    
                                    $edu = get_post_meta(
                                        $candidate_id,
                                        'candidate_edu',
                                        true
                                    );
                                    $edu_list = array();
    
                                    if (!empty($edu)) {
                                        $edu_data = json_decode(urldecode($edu));
    
                                        if (isset($edu_data)) {
                                            $edu_list = $edu_data->edus;
                                        }
                                    }
    
                                    if (count($edu_list) > 0) { ?>
                                        <div class="mt-4 mt-lg-5">
                                            <h2>
                                                <?php esc_attr_e('Education & Training', 'jobster'); ?>
                                            </h2>
                                            <div class="pxp-single-candidate-timeline">
                                                <?php foreach ($edu_list as $edu_item) { ?>
                                                    <div class="pxp-single-candidate-timeline-item">
                                                        <div class="pxp-single-candidate-timeline-dot"></div>
                                                        <div class="pxp-single-candidate-timeline-info ms-3">
                                                            <div class="pxp-single-candidate-timeline-time">
                                                                <span class="me-3">
                                                                    <?php echo esc_html($edu_item->period); ?>
                                                                </span>
                                                            </div>
                                                            <div class="pxp-single-candidate-timeline-position mt-2">
                                                                <?php echo esc_html($edu_item->title); ?>
                                                            </div>
                                                            <div class="pxp-single-candidate-timeline-company pxp-text-light">
                                                                <?php echo esc_html($edu_item->school); ?>
                                                            </div>
                                                            <div class="pxp-single-candidate-timeline-about mt-2 pb-4">
                                                                <?php echo esc_html($edu_item->description); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php }

                                    if ($photos[0] != '') { ?>
                                        <div class="mt-4 mt-lg-5">
                                            <h2>
                                                <?php if ($gallery_title != '') {
                                                    echo esc_html($gallery_title);
                                                } else {
                                                    esc_attr_e('Gallery/Portfolio', 'jobster');
                                                } ?>
                                            </h2>

                                            <div class="pxp-single-candidate-gallery-container">
                                                <div 
                                                    class="pxp-single-candidate-gallery row" 
                                                    itemscope 
                                                    itemtype="http://schema.org/ImageGallery"
                                                >
                                                    <?php for ($i = 0; $i < count($photos); $i++) {
                                                        $photo = wp_get_attachment_image_src(
                                                            $photos[$i], 'pxp-gallery'
                                                        );
                                                        $photo_full = wp_get_attachment_image_src(
                                                            $photos[$i], 'full'
                                                        );
                                                        $photo_info = jobster_get_attachment($photos[$i]);
                                                        $d_none = $i > 3 ? 'd-none' : ''; ?>

                                                        <figure 
                                                            itemprop="associatedMedia" 
                                                            itemscope 
                                                            itemtype="http://schema.org/ImageObject" 
                                                            class="<?php echo esc_attr($d_none); ?> col-6 col-xxl-3"
                                                        >
                                                            <a 
                                                                href="<?php echo esc_url($photo_full[0]); ?>" 
                                                                itemprop="contentUrl" 
                                                                data-size="<?php echo esc_attr($photo_full[1]); ?>x<?php echo esc_attr($photo_full[2]); ?>" 
                                                            >
                                                                <div
                                                                    class="pxp-cover" 
                                                                    style="background-image: url(<?php echo esc_url($photo[0]); ?>);"
                                                                >
                                                                    <?php if ($i == 3 && count($photos) > 4) { ?>
                                                                        <span class="pxp-single-candidate-gallery-more">
                                                                            <span>+<?php $more = count($photos) - ($i + 1);
                                                                            echo esc_html($more); ?></span>
                                                                        </span>
                                                                    <?php } ?>
                                                                </div>
                                                            </a>
                                                            <figcaption itemprop="caption description">
                                                                <?php echo esc_html($photo_info['caption']); ?>
                                                            </figcaption>
                                                        </figure>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }

                                    if ($video != '' && function_exists('jobster_get_youtube_video')) { ?>
                                        <div class="mt-4 mt-lg-5">
                                            <h2>
                                                <?php if ($video_title != '') {
                                                    echo esc_html($video_title);
                                                } else {
                                                    esc_attr_e('Video', 'jobster');
                                                } ?>
                                            </h2>
                                            <div class="mt-3 mt-md-4">
                                                <?php jobster_get_youtube_video($video); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="mt-4 mt-lg-5">
                                <p><i><?php esc_html_e('Restricted content. You need company account to have access.', 'jobster') ?></i></p>
                                <?php if (!is_user_logged_in()) { ?>
                                    <button 
                                        class="btn pxp-single-job-apply-btn pxp-section-cta rounded-pill" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#pxp-signin-modal"
                                    >
                                        <?php esc_html_e('Sign In Now', 'jobster'); ?>
                                    </button>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if ($show_profile === true) { ?>
                        <div class="col-lg-5 col-xl-4 col-xxl-3">
                            <div class="pxp-single-candidate-side-panel <?php echo esc_attr($side_margin_class); ?>">
                                <?php if ($show_contact === true) {
                                    if (!$hide_email) {
                                        $email = get_post_meta(
                                            $candidate_id, 'candidate_email', true
                                        );
                                        if (!empty($email)) { ?>
                                            <div>
                                                <div class="pxp-single-candidate-side-info-label pxp-text-light">
                                                    <?php esc_html_e('Email', 'jobster'); ?>
                                                </div>
                                                <div class="pxp-single-candidate-side-info-data">
                                                    <a href="mailto:<?php echo esc_attr($email); ?>">
                                                        <?php echo esc_html($email); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php }
                                    }

                                    $location = wp_get_post_terms(
                                        $candidate_id, 'candidate_location'
                                    );
                                    if ($location) { ?>
                                        <div class="mt-4">
                                            <div class="pxp-single-candidate-side-info-label pxp-text-light">
                                                <?php esc_html_e('Location', 'jobster'); ?>
                                            </div>
                                            <div class="pxp-single-candidate-side-info-data">
                                                <?php echo esc_html($location[0]->name); ?>
                                            </div>
                                        </div>
                                    <?php }

                                    $phone = get_post_meta(
                                        $candidate_id, 'candidate_phone', true
                                    );
                                    if (!empty($phone)) {
                                        $phone_short = substr_replace(
                                            $phone, '****', -4
                                        ); ?>
                                        <div class="mt-4">
                                            <div class="pxp-single-candidate-side-info-label pxp-text-light">
                                                <?php esc_html_e('Phone', 'jobster'); ?>
                                            </div>
                                            <div class="pxp-single-candidate-side-info-data">
                                                <div class="pxp-single-candidate-side-info-phone">
                                                    <a class="d-none"
                                                        href="tel:<?php echo esc_attr($phone); ?>"
                                                    >
                                                        <?php echo esc_html($phone); ?>
                                                    </a>
                                                    <span 
                                                        class="d-flex align-items-center" 
                                                        onclick="this.parentNode.classList.add('pxp-show');"
                                                    >
                                                        <?php echo esc_html($phone_short); ?>
                                                        <span class="btn btn-sm rounded-pill">
                                                            <?php esc_html_e('Show', 'jobster'); ?>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }

                                    $website = get_post_meta(
                                        $candidate_id, 'candidate_website', true
                                    );
                                    if (!empty($website)) { ?>
                                        <div class="mt-4">
                                            <div class="pxp-single-candidate-side-info-label pxp-text-light">
                                                <?php esc_html_e('Website', 'jobster'); ?>
                                            </div>
                                            <div class="pxp-single-candidate-side-info-data">
                                                <a href="<?php echo esc_url($website); ?>">
                                                    <?php echo esc_url($website); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <p><i><?php esc_html_e('Restricted contact info. You need company account to have access.', 'jobster') ?></i></p>
                                    <?php if (!is_user_logged_in()) { ?>
                                        <button 
                                            class="btn pxp-single-job-apply-btn pxp-section-cta rounded-pill" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#pxp-signin-modal"
                                        >
                                            <?php esc_html_e('Sign In Now', 'jobster'); ?>
                                        </button>
                                    <?php }
                                }

                                $candidates_fields_settings = get_option('jobster_candidates_fields_settings');
                                if (is_array($candidates_fields_settings)) {
                                    uasort($candidates_fields_settings, "jobster_compare_position");

                                    foreach ($candidates_fields_settings as $cfs_key => $cfs_value) {
                                        $cf_label = $cfs_value['label'];

                                        $candidate_field_value = get_post_meta($candidate_id, $cfs_key, true);

                                        if ($candidate_field_value != '') { ?>
                                            <div class="mt-4">
                                                <div class="pxp-single-candidate-side-info-label pxp-text-light">
                                                    <?php echo esc_html($cf_label); ?>
                                                </div>
                                                <div class="pxp-single-candidate-side-info-data">
                                                    <?php if ($cfs_value['type'] == 'list_field') {
                                                        $list = explode(',', $cfs_value['list']);
                                                        echo esc_html($list[$candidate_field_value]);
                                                    } else if ($cfs_value['type'] == 'date_field') {
                                                        esc_html_e(date(__('F j, Y', 'jobster'), strtotime($candidate_field_value)));
                                                    } else {
                                                        echo esc_html($candidate_field_value);
                                                    } ?>
                                                </div>
                                            </div>
                                        <?php }
                                    }
                                }

                                $facebook = get_post_meta(
                                    $candidate_id, 'candidate_facebook', true
                                );
                                $twitter = get_post_meta(
                                    $candidate_id, 'candidate_twitter', true
                                );
                                $instagram = get_post_meta(
                                    $candidate_id, 'candidate_instagram', true
                                );
                                $linkedin = get_post_meta(
                                    $candidate_id, 'candidate_linkedin', true
                                );

                                if (!empty($facebook)
                                    || !empty($twitter)
                                    || !empty($instagram)
                                    || !empty($linkedin)) { ?>
                                    <div class="mt-4">
                                        <div class="pxp-single-candidate-side-info-data">
                                            <ul class="list-unstyled pxp-single-candidate-side-info-social">
                                                <?php if (!empty($facebook)) { ?>
                                                    <li>
                                                        <a href="<?php echo esc_url($facebook); ?>">
                                                            <span class="fa fa-facebook"></span>
                                                        </a>
                                                    </li>
                                                <?php }
                                                if (!empty($twitter)) { ?>
                                                    <li>
                                                        <a href="<?php echo esc_url($twitter); ?>">
                                                            <span class="fa fa-twitter"></span>
                                                        </a>
                                                    </li>
                                                <?php }
                                                if (!empty($instagram)) { ?>
                                                    <li>
                                                        <a href="<?php echo esc_url($instagram); ?>">
                                                            <span class="fa fa-instagram"></span>
                                                        </a>
                                                    </li>
                                                <?php }
                                                if (!empty($linkedin)) { ?>
                                                    <li>
                                                        <a href="<?php echo esc_url($linkedin); ?>">
                                                            <span class="fa fa-linkedin"></span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php }

                                $show_download_btn = true;
                                $cv = get_post_meta(
                                    $candidate_id, 'candidate_cv', true
                                );
                                $membership_settings = get_option('jobster_membership_settings');
                                $payment_type = isset($membership_settings['jobster_payment_type_field'])
                                                ? $membership_settings['jobster_payment_type_field']
                                                : '';
                                if ($payment_type == 'plan') {
                                    if ($is_company) {
                                        $company_id = jobster_get_company_by_userid($current_user->ID);
                                        $plan_cv_access = get_post_meta(
                                            $company_id, 'company_plan_cv_access', true
                                        );
                                        if ($plan_cv_access != 1) {
                                            $show_download_btn = false;
                                        }
                                    } else {
                                        $show_download_btn = false;
                                    }
                                } else {
                                    if (!$show_resume) {
                                        $show_download_btn = false;
                                    }
                                }

                                if (!empty($cv) && $show_download_btn) {
                                    $cv_url = wp_get_attachment_url($cv); ?>

                                    <div class="mt-4">
                                        <form>
                                            <a 
                                                href="<?php echo esc_url($cv_url); ?>" 
                                                class="btn rounded-pill d-block"
                                            >
                                                <?php esc_html_e('Download Resume', 'jobster'); ?>
                                            </a>
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>

                            <?php if ($is_company) {
                                if (function_exists('jobster_get_candidate_contact_form')) { ?>
                                    <div class="pxp-single-candidate-side-panel mt-4 mt-lg-5">
                                        <?php jobster_get_candidate_contact_form($candidate_id); ?>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

<?php endwhile; ?>