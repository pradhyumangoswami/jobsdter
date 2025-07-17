<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_boxed_header')):
    function jobster_get_boxed_header($post_id) {
        $caption_title = get_post_meta(
            $post_id, 'ph_boxed_caption_title', true
        );
        $caption_subtitle = get_post_meta(
            $post_id, 'ph_boxed_caption_subtitle', true
        );
        $show_search = get_post_meta($post_id, 'ph_boxed_show_search', true);
        $search_system = get_post_meta(
            $post_id, 'ph_boxed_search_system', true
        );
        $show_popular_searches = get_post_meta(
            $post_id, 'ph_boxed_show_popular', true
        );
        $sfc_label = get_post_meta($post_id, 'ph_boxed_sfc_card_label', true);
        $sfc_illustration = get_post_meta(
            $post_id, 'ph_boxed_sfc_illustration', true
        );
        $sfc_icon = get_post_meta($post_id, 'ph_boxed_sfc_icon', true);
        $bfc_label = get_post_meta($post_id, 'ph_boxed_bfc_card_label', true);
        $bfc_text = get_post_meta($post_id, 'ph_boxed_bfc_card_text', true);
        $bfc_illustration = get_post_meta(
            $post_id, 'ph_boxed_bfc_illustration', true
        );
        $bfc_icon = get_post_meta($post_id, 'ph_boxed_bfc_icon', true);
        $info = get_post_meta($post_id, 'ph_boxed_info', true); ?>

        <section class="pxp-hero pxp-hero-boxed">
            <div class="pxp-container">
                <div 
                    class="pxp-hero-boxed-content" 
                    style="background-color: var(--pxpMainColorLight);"
                >
                    <div class="row align-items-center justify-content-between">
                        <div class="col-12 col-xl-6 col-xxl-5">
                            <h1><?php echo esc_html($caption_title); ?></h1>
                            <div class="pxp-hero-subtitle mt-3 mt-lg-4">
                                <?php echo esc_html($caption_subtitle); ?>
                            </div>

                            <?php if ($show_search == '1') { 
                                if ($search_system == 'careerjet') {
                                    if (function_exists('jobster_get_careerjet_hero_search_jobs_form')) {
                                        jobster_get_careerjet_hero_search_jobs_form('boxed');
                                    }
                                } else {
                                    if (function_exists('jobster_get_hero_search_jobs_form')) {
                                        jobster_get_hero_search_jobs_form('boxed');
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
                        <div class="d-none d-xl-block col-xl-4 col-xxl-5 position-relative">
                            <div class="pxp-hero-boxed-circulars pxp-animate-circles-bounce">
                                <div class="pxp-hero-boxed-circular-outer">
                                    <div class="pxp-hero-boxed-circular-outer-1"></div>
                                    <div class="pxp-hero-boxed-circular-outer-2"></div>
                                    <div class="pxp-hero-boxed-circular-outer-3"></div>
                                </div>
                                <div class="pxp-hero-boxed-circular-middle">
                                    <div class="pxp-hero-boxed-circular-middle-1"></div>
                                    <div class="pxp-hero-boxed-circular-middle-2"></div>
                                    <div class="pxp-hero-boxed-circular-middle-3"></div>
                                </div>
                                <div class="pxp-hero-boxed-circular-center"></div>
                            </div>

                            <div class="pxp-hero-boxed-icon-circles">
                                <?php $sfc_icon_src = wp_get_attachment_image_src(
                                    $sfc_icon, 'pxp-ful'
                                );
                                $sfc_icon_info = jobster_get_attachment($sfc_icon);
                                if ($sfc_icon_src !== false) { ?>
                                    <div class="pxp-hero-boxed-icon-circle-1 pxp-animate-icon-circle-bounce">
                                        <img 
                                            src="<?php echo esc_url($sfc_icon_src[0]); ?>" 
                                            alt="<?php echo esc_attr($sfc_icon_info['alt']); ?>"
                                        >
                                    </div>
                                <?php } ?>

                                <?php $bfc_icon_src = wp_get_attachment_image_src(
                                    $bfc_icon, 'pxp-ful'
                                );
                                $bfc_icon_info = jobster_get_attachment($bfc_icon);
                                if ($bfc_icon_src !== false) { ?>
                                    <div class="pxp-hero-boxed-icon-circle-2 pxp-animate-icon-circle-bounce">
                                        <img 
                                            src="<?php echo esc_url($bfc_icon_src[0]); ?>" 
                                            alt="<?php echo esc_attr($bfc_icon_info['alt']); ?>"
                                        >
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="pxp-hero-boxed-info-cards">
                                <?php if ($bfc_label != '' 
                                            && $bfc_text != '' 
                                            && $bfc_illustration != '') { ?>
                                    <div class="pxp-hero-boxed-info-card-big pxp-animate-info-card">
                                        <div class="pxp-hero-boxed-info-card-big-content">
                                            <div class="pxp-hero-boxed-info-card-big-icon">
                                                <?php $bfc_illustration_src = wp_get_attachment_image_src(
                                                    $bfc_illustration, 'pxp-ful'
                                                );
                                                $bfc_illustration_info = jobster_get_attachment($bfc_illustration);
                                                if ($bfc_illustration_src !== false) { ?>
                                                    <img 
                                                        src="<?php echo esc_url($bfc_illustration_src[0]); ?>" 
                                                        alt="<?php echo esc_attr($bfc_illustration_info['alt']); ?>"
                                                    >
                                                <?php } ?>
                                            </div>
                                            <div class="pxp-hero-boxed-info-card-big-title">
                                                <?php echo esc_html($bfc_label); ?>
                                            </div>
                                            <div class="pxp-hero-boxed-info-card-big-text pxp-text-light">
                                                <?php echo esc_html($bfc_text); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php }

                                if ($sfc_label != '' 
                                    && $sfc_illustration != '') { ?>
                                    <div class="pxp-hero-boxed-info-card-small pxp-animate-info-card">
                                        <div class="pxp-hero-boxed-info-card-small-content">
                                            <div class="pxp-hero-boxed-info-card-small-icon">
                                                <?php $sfc_illustration_src = wp_get_attachment_image_src(
                                                    $sfc_illustration, 'pxp-ful'
                                                );
                                                $sfc_illustration_info = jobster_get_attachment($sfc_illustration);
                                                if ($sfc_illustration_src !== false) { ?>
                                                    <img 
                                                        src="<?php echo esc_url($sfc_illustration_src[0]); ?>" 
                                                        alt="<?php echo esc_attr($sfc_illustration_info['alt']); ?>"
                                                    >
                                                <?php } ?>
                                            </div>
                                            <div class="pxp-hero-boxed-info-card-small-title">
                                                <?php echo esc_html($sfc_label); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php $info_list = array();

                                if ($info != '') {
                                    $info_data = json_decode(urldecode($info));

                                    if (isset($info_data)) {
                                        $info_list = $info_data->info;
                                    }

                                    if (count($info_list) > 0) { ?>
                                        <div class="pxp-hero-boxed-info-list-container pxp-animate-info-card">
                                            <div class="pxp-hero-boxed-info-list">
                                                <?php foreach ($info_list as $info_item) { ?>
                                                    <div class="pxp-hero-boxed-info-list-item">
                                                        <div class="pxp-hero-boxed-info-list-item-number">
                                                            <?php echo esc_html($info_item->number); ?>
                                                            <span>
                                                                <?php echo esc_html($info_item->label); ?>
                                                            </span>
                                                        </div>
                                                        <div class="pxp-hero-boxed-info-list-item-description">
                                                            <?php echo esc_html($info_item->text); ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
endif;
?>