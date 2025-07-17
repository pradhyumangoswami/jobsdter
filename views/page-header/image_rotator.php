<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_image_rotator_header')):
    function jobster_get_image_rotator_header($post_id) {
        $caption_title = get_post_meta(
            $post_id, 'ph_image_rotator_caption_title', true
        );
        $caption_subtitle = get_post_meta(
            $post_id, 'ph_image_rotator_caption_subtitle', true
        );
        $show_search = get_post_meta(
            $post_id, 'ph_image_rotator_show_search', true
        );
        $search_system = get_post_meta(
            $post_id, 'ph_image_rotator_search_system', true
        );
        $show_popular_searches = get_post_meta(
            $post_id, 'ph_image_rotator_show_popular', true
        );
        $logos = get_post_meta($post_id, 'ph_image_rotator_logos', true);
        $photos = get_post_meta($post_id, 'ph_image_rotator_photos', true);
        $interval = get_post_meta($post_id, 'ph_image_rotator_interval', true);
        $info = get_post_meta($post_id, 'ph_image_rotator_info', true); 

        $interval_data = 3000;
        if ($interval != '') {
            $interval_data = intval($interval) * 1000;
        } ?>

        <section class="pxp-hero vh-100">
            <div class="pxp-hero-caption">
                <div class="pxp-container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-12 col-xl-6 col-xxl-5">
                            <h1><?php echo esc_html($caption_title); ?></h1>
                            <div class="pxp-hero-subtitle mt-3 mt-lg-4">
                                <?php echo esc_html($caption_subtitle); ?>
                            </div>

                            <?php if ($show_search == '1') { 
                                if ($search_system == 'careerjet') {
                                    if (function_exists('jobster_get_careerjet_hero_search_jobs_form')) {
                                        jobster_get_careerjet_hero_search_jobs_form('image_rotator');
                                    }
                                } else {
                                    if (function_exists('jobster_get_hero_search_jobs_form')) {
                                        jobster_get_hero_search_jobs_form('image_rotator');
                                    }
                                } ?>
                            <?php }

                            if ($show_popular_searches == '1') { 
                                if ($search_system == 'careerjet') {
                                    $popular_searches = jobster_get_careerjet_popular_searches(); 
                                    $search_jobs_url = jobster_get_page_link('job-search-apis.php');
                                } else {
                                    $popular_searches = jobster_get_popular_searches(); 
                                    $search_jobs_url = jobster_get_page_link('job-search.php');
                                } ?>

                                <?php if (count($popular_searches) > 0) { ?>
                                    <div class="pxp-hero-searches-container pxp-smaller">
                                        <div class="pxp-hero-searches-label">
                                            <?php esc_html_e('Popular Searches', 'jobster'); ?>
                                        </div>
                                        <div class="pxp-hero-searches">
                                            <div class="pxp-hero-searches-items">
                                                <?php foreach ($popular_searches as $p_search_key => $p_search_val) {
                                                    $p_search_url = add_query_arg(
                                                        'keywords',
                                                        $p_search_key,
                                                        $search_jobs_url
                                                    ); ?>
                                                    <a href="<?php echo esc_url($p_search_url); ?>">
                                                        <?php echo esc_html($p_search_key); ?>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php $photos_list = array();

            if ($photos != '') {
                $photos_data = json_decode(urldecode($photos));

                if (isset($photos_data)) {
                    $photos_list = $photos_data->photos;
                }

                if (count($photos_list) > 0) { ?>
                    <div class="pxp-hero-center-carousel d-none d-xl-block pxp-has-animation">
                        <div 
                            class="carousel slide carousel-fade" 
                            data-bs-ride="carousel" 
                            data-bs-pause="false"
                        >
                            <div class="carousel-inner">
                                <?php $photos_counter = 0;
                                foreach ($photos_list as $photos_item) {
                                    $photo_src = wp_get_attachment_image_src(
                                        $photos_item->image, 'pxp-full'
                                    );
                                    if ($photo_src !== false) { ?>
                                        <div class="carousel-item pxp-cover <?php if ($photos_counter == 0) { echo 'active'; } ?>" 
                                            data-bs-interval="<?php echo esc_attr($interval_data); ?>" 
                                            style="background-image: url(<?php echo esc_url($photo_src[0]); ?>);"
                                        ></div>
                                    <?php }
                                    $photos_counter++;
                                } ?>
                            </div>
                        </div>
                    </div>
                <?php }
            }

            $info_list = array();

            if ($info != '') {
                $info_data = json_decode(urldecode($info));

                if (isset($info_data)) {
                    $info_list = $info_data->info;
                }

                if (count($info_list) > 0) { ?>
                    <div class="pxp-hero-stats d-none d-xl-block">
                        <?php foreach ($info_list as $info_item) { ?>
                            <div 
                                class="pxp-hero-stats-item pxp-has-animation pxp-mouse-move" 
                                data-speed="60"
                            >
                                <div class="pxp-hero-stats-item-number">
                                    <?php echo esc_html($info_item->number); ?>
                                    <span>
                                        <?php echo esc_html($info_item->label); ?>
                                    </span>
                                </div>
                                <div class="pxp-hero-stats-item-description">
                                    <?php echo esc_html($info_item->text); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php }
            }

            $logos_list = array();

            if ($logos != '') {
                $logos_data = json_decode(urldecode($logos));

                if (isset($logos_data)) {
                    $logos_list = $logos_data->logos;
                }

                if (count($logos_list) > 0) { ?>
                    <div class="pxp-hero-logos-carousel-container">
                        <div class="pxp-container">
                            <div class="row justify-content-center">
                                <div class="col-xl-8 col-xxl-6">
                                    <div class="pxp-hero-logos-carousel owl-carousel">
                                        <?php foreach ($logos_list as $logos_item) {
                                            $logo_src = wp_get_attachment_image_src(
                                                $logos_item->image, 'pxp-full'
                                            );
                                            $logo_info = jobster_get_attachment($logos_item->image);

                                            if ($logo_src !== false) { ?>
                                                <img 
                                                    src="<?php echo esc_url($logo_src[0]); ?>" 
                                                    alt="<?php echo esc_attr($logo_info['alt']); ?>"
                                                >
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
        </section>
    <?php }
endif;
?>