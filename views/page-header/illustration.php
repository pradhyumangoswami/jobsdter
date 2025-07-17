<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_illustration_header')):
    function jobster_get_illustration_header($post_id) {
        $caption_title = get_post_meta(
            $post_id, 'ph_illustration_caption_title', true
        );
        $caption_subtitle = get_post_meta(
            $post_id, 'ph_illustration_caption_subtitle', true
        );
        $show_search = get_post_meta(
            $post_id, 'ph_illustration_show_search', true
        );
        $search_system = get_post_meta(
            $post_id, 'ph_illustration_search_system', true
        );
        $show_popular_searches = get_post_meta(
            $post_id, 'ph_illustration_show_popular', true
        );
        $logos = get_post_meta($post_id, 'ph_illustration_logos', true);
        $photo = get_post_meta($post_id, 'ph_illustration_photo', true); ?>

        <section 
            class="pxp-hero vh-100" 
            style="background-color: var(--pxpMainColorLight);"
        >
            <div class="pxp-hero-caption">
                <div class="pxp-container">
                    <div class="row pxp-pl-80 align-items-center justify-content-between">
                        <div class="col-12 col-xl-6 col-xxl-5">
                            <h1><?php echo esc_html($caption_title); ?></h1>
                            <div class="pxp-hero-subtitle mt-3 mt-lg-4">
                                <?php echo esc_html($caption_subtitle); ?>
                            </div>

                            <?php if ($show_search == '1') { 
                                if ($search_system == 'careerjet') {
                                    if (function_exists('jobster_get_careerjet_hero_search_jobs_form')) {
                                        jobster_get_careerjet_hero_search_jobs_form('illustration');
                                    }
                                } else {
                                    if (function_exists('jobster_get_hero_search_jobs_form')) {
                                        jobster_get_hero_search_jobs_form('illustration');
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
                                    <div class="pxp-hero-searches-container">
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
                        <div class="d-none d-xl-block col-xl-5 position-relative">
                            <?php $photo_src = wp_get_attachment_image_src(
                                $photo, 'full'
                            );
                            $photo_info = jobster_get_attachment($photo);

                            if ($photo_src !== false) { ?>
                                <div class="pxp-header-side-image pxp-has-animation">
                                    <img 
                                        src="<?php echo esc_url($photo_src[0]); ?>" 
                                        alt="<?php echo esc_attr($photo_info['alt']); ?>"
                                    >
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php $logos_list = array();

            if ($logos != '') {
                $logos_data = json_decode(urldecode($logos));

                if (isset($logos_data)) {
                    $logos_list = $logos_data->logos;
                }

                if (count($logos_list) > 0) { ?>
                    <div class="pxp-hero-logos-carousel-container">
                        <div class="pxp-container">
                            <div class="row pxp-pl-80">
                                <div class="col-12 col-xl-6">
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

            <div class="pxp-hero-right-bg-card pxp-has-animation"></div>
        </section>
    <?php }
endif;
?>