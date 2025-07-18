<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

while (have_posts()) : the_post();
    $company_id = get_the_ID();

    $cover_type = get_post_meta($company_id, 'company_cover_type', true);
    $cover_val = get_post_meta($company_id, 'company_cover', true);
    $cover = wp_get_attachment_image_src($cover_val, 'pxp-full');
    $cover_color = get_post_meta($company_id, 'company_cover_color', true);

    $logo_val = get_post_meta($company_id, 'company_logo', true);
    $logo = wp_get_attachment_image_src($logo_val, 'pxp-thmb');

    $name = get_the_title($company_id);
    $location = wp_get_post_terms($company_id, 'company_location');

    $gallery = get_post_meta($company_id, 'company_gallery', true);
    $photos  = explode(',', $gallery);
    $gallery_title = get_post_meta($company_id, 'company_gallery_title', true);

    $video = get_post_meta($company_id, 'company_video', true);
    $video_title = get_post_meta($company_id, 'company_video_title', true);

    $is_candidate = false;
    if (is_user_logged_in()) {
        global $current_user;

        $current_user = wp_get_current_user();
        $is_candidate = function_exists('jobster_user_is_candidate')
                        ? jobster_user_is_candidate($current_user->ID)
                        : false;
    }

    $companies_settings = get_option('jobster_companies_settings');
    $hide_email =   isset($companies_settings['jobster_companies_hide_email_field'])
                    && $companies_settings['jobster_companies_hide_email_field'] == '1';

    $has_cover = false;
    if ($cover_type === 'i' && is_array($cover)) {
        $has_cover = true;
    }
    if ($cover_type === 'c' && !empty($cover_color)) {
        $has_cover = true;
    }

    $side_margin_class = is_array($cover) ? 'mt-5 mt-lg-0' : 'mt-5 mt-lg-4'; ?>

    <section>
        <div class="pxp-container">
            <div class="pxp-single-company-container pxp-has-columns">
                <div class="row">
                    <div class="col-lg-7 col-xl-8 col-xxl-9">
                        <?php if ($has_cover) {
                            if ($cover_type === 'i') { ?>
                                <div 
                                    class="pxp-single-company-hero pxp-cover pxp-boxed" 
                                    style="background-image: url(<?php echo esc_url($cover[0]); ?>);"
                                >
                                    <div class="pxp-hero-opacity"></div>
                            <?php }
                            if ($cover_type === 'c') { ?>
                                <div 
                                    class="pxp-single-company-hero pxp-boxed" 
                                    style="background-color: <?php echo esc_attr($cover_color); ?>;"
                                >
                            <?php }
                        } else { ?>
                            <div class="pxp-single-company-hero pxp-no-cover pxp-boxed mt-4">
                        <?php } ?>
                            <div class="pxp-single-company-hero-caption">
                                <div class="pxp-single-company-hero-content">
                                    <?php if (is_array($logo)) { ?>
                                        <div 
                                            class="pxp-single-company-hero-logo" 
                                            style="background-image: url(<?php echo esc_url($logo[0]); ?>);"
                                        ></div>
                                    <?php } else { ?>
                                        <div class="pxp-single-company-hero-logo pxp-no-img">
                                            <?php echo esc_html($name[0]); ?>
                                        </div>
                                    <?php } ?>
                                    <div class="pxp-single-company-hero-title">
                                        <h1><?php echo esc_html($name); ?></h1>
                                        <?php if ($location) { ?>
                                            <div class="pxp-single-company-hero-location">
                                                <span class="fa fa-globe"></span><?php echo esc_html($location[0]->name); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 mt-lg-5">
                            <div class="pxp-single-company-content">
                                <h2><?php esc_html_e('About', 'jobster'); ?> <?php the_title(); ?></h2>
                                <div>
                                    <?php the_content(); ?>
                                </div>

                                <?php if ($photos[0] != '') { ?>
                                    <div class="mt-4 mt-lg-5">
                                        <h2>
                                            <?php if ($gallery_title != '') {
                                                echo esc_html($gallery_title);
                                            } else {
                                                esc_attr_e('Photo Gallery', 'jobster');
                                            } ?>
                                        </h2>

                                        <div class="pxp-single-company-gallery-container">
                                            <div 
                                                class="pxp-single-company-gallery row" 
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
                                                                    <span class="pxp-single-company-gallery-more">
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

                        <div class="mt-100">
                            <h2 class="pxp-subsection-h2">
                                <?php esc_html_e('Available Jobs', 'jobster'); ?>
                            </h2>
                            <p class="pxp-text-light">
                                <?php printf(
                                    __('Jobs posted by %s', 'jobster'),
                                    get_the_title($company_id)
                                ); ?>
                            </p>

                            <?php if (function_exists('jobster_get_company_jobs')) {
                                jobster_get_company_jobs();
                            } ?>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-4 col-xxl-3">
                        <div class="pxp-single-company-side-panel <?php echo esc_attr($side_margin_class); ?>">
                            <?php $industry = wp_get_post_terms(
                                $company_id, 'company_industry'
                            );
                            if ($industry) { ?>
                                <div>
                                    <div class="pxp-single-company-side-info-label pxp-text-light">
                                        <?php esc_html_e('Industry', 'jobster'); ?>
                                    </div>
                                    <div class="pxp-single-company-side-info-data">
                                        <?php echo esc_html($industry[0]->name); ?>
                                    </div>
                                </div>
                            <?php }

                            $company_size = get_post_meta(
                                $company_id, 'company_size', true
                            );
                            if (!empty($company_size)) { ?>
                                <div class="mt-4">
                                    <div class="pxp-single-company-side-info-label pxp-text-light">
                                        <?php esc_html_e('Company size', 'jobster'); ?>
                                    </div>
                                    <div class="pxp-single-company-side-info-data">
                                        <?php printf(
                                            __('%s employees', 'jobster'),
                                            esc_html($company_size)) 
                                        ?>
                                    </div>
                                </div>
                            <?php }

                            $founded = get_post_meta(
                                $company_id, 'company_founded', true
                            );
                            if (!empty($founded)) { ?>
                                <div class="mt-4">
                                    <div class="pxp-single-company-side-info-label pxp-text-light">
                                        <?php esc_html_e('Founded in', 'jobster'); ?>
                                    </div>
                                    <div class="pxp-single-company-side-info-data">
                                        <?php echo esc_html($founded); ?>
                                    </div>
                                </div>
                            <?php }

                            $phone = get_post_meta(
                                $company_id, 'company_phone', true
                            );
                            if (!empty($phone)) {
                                $phone_short = substr_replace(
                                    $phone, '****', -4
                                ); ?>
                                <div class="mt-4">
                                    <div class="pxp-single-company-side-info-label pxp-text-light">
                                        <?php esc_html_e('Phone', 'jobster'); ?>
                                    </div>
                                    <div class="pxp-single-company-side-info-data">
                                        <div class="pxp-single-company-side-info-phone">
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

                            if (!$hide_email) {
                                $email = get_post_meta(
                                    $company_id, 'company_email', true
                                );
                                if (!empty($email)) { ?>
                                    <div class="mt-4">
                                        <div class="pxp-single-company-side-info-label pxp-text-light">
                                            <?php esc_html_e('Email', 'jobster'); ?>
                                        </div>
                                        <div class="pxp-single-company-side-info-data">
                                            <a href="mailto:<?php echo esc_attr($email); ?>">
                                                <?php echo esc_html($email); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php }
                            }

                            if ($location) { ?>
                                <div class="mt-4">
                                    <div class="pxp-single-company-side-info-label pxp-text-light">
                                        <?php esc_html_e('Location', 'jobster'); ?>
                                    </div>
                                    <div class="pxp-single-company-side-info-data">
                                        <?php echo esc_html($location[0]->name); ?>
                                    </div>
                                </div>
                            <?php }

                            if (!empty($website)) { ?>
                                <div class="mt-4">
                                    <div class="pxp-single-company-side-info-label pxp-text-light">
                                        <?php esc_html_e('Website', 'jobster'); ?>
                                    </div>
                                    <div class="pxp-single-company-side-info-data">
                                        <a href="<?php echo esc_url($website); ?>">
                                            <?php echo esc_url($website); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php }

                            $companies_fields_settings = get_option('jobster_companies_fields_settings');

                            if (is_array($companies_fields_settings)) {
                                uasort($companies_fields_settings, "jobster_compare_position");

                                foreach ($companies_fields_settings as $cfs_key => $cfs_value) {
                                    $cf_label = $cfs_value['label'];

                                    $company_field_value = get_post_meta($company_id, $cfs_key, true);

                                    if ($company_field_value != '') { ?>
                                        <div class="mt-4">
                                            <div class="pxp-single-company-side-info-label pxp-text-light">
                                                <?php echo esc_html($cf_label); ?>
                                            </div>
                                            <div class="pxp-single-company-side-info-data">
                                                <?php if ($cfs_value['type'] == 'list_field') {
                                                    $list = explode(',', $cfs_value['list']);
                                                    echo esc_html($list[$company_field_value]);
                                                } else if ($cfs_value['type'] == 'date_field') {
                                                    esc_html_e(date(__('F j, Y', 'jobster'), strtotime($company_field_value)));
                                                } else {
                                                    echo esc_html($company_field_value);
                                                } ?>
                                            </div>
                                        </div>
                                    <?php }
                                }
                            }

                            $facebook = get_post_meta(
                                $company_id, 'company_facebook', true
                            );
                            $twitter = get_post_meta(
                                $company_id, 'company_twitter', true
                            );
                            $instagram = get_post_meta(
                                $company_id, 'company_instagram', true
                            );
                            $linkedin = get_post_meta(
                                $company_id, 'company_linkedin', true
                            );

                            if (!empty($facebook)
                                || !empty($twitter)
                                || !empty($instagram)
                                || !empty($linkedin)) { ?>
                                <div class="mt-4">
                                    <div class="pxp-single-company-side-info-data">
                                        <ul class="list-unstyled pxp-single-company-side-info-social">
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

                            $doc = get_post_meta($company_id, 'company_doc', true);
                            $doc_title = get_post_meta($company_id, 'company_doc_title', true);
                            $doc_title_text =   empty($doc_title) 
                                                ? __('Document', 'jobster')
                                                : $doc_title;

                            if (!empty($doc)) {
                                $doc_url = wp_get_attachment_url($doc); ?>

                                <div class="mt-4">
                                    <form>
                                        <a 
                                            href="<?php echo esc_url($doc_url); ?>" 
                                            class="btn rounded-pill d-block"
                                        >
                                            <?php echo esc_html_e('Download', 'jobster'); ?> <?php echo esc_html($doc_title_text); ?>
                                        </a>
                                    </form>
                                </div>
                            <?php } ?>
                        </div>

                        <?php if ($is_candidate) {
                            if (function_exists('jobster_get_company_contact_form')) { ?>
                                <div class="pxp-single-company-side-panel mt-4 mt-lg-5">
                                    <?php jobster_get_company_contact_form($company_id); ?>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endwhile;
?>