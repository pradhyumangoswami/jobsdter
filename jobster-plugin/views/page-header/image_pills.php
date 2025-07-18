<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_image_pills_header')):
    function jobster_get_image_pills_header($post_id) {
        $caption_title = get_post_meta(
            $post_id, 'ph_image_pills_caption_title', true
        );
        $caption_subtitle = get_post_meta(
            $post_id, 'ph_image_pills_caption_subtitle', true
        );
        $show_search = get_post_meta(
            $post_id, 'ph_image_pills_show_search', true
        );
        $search_system = get_post_meta(
            $post_id, 'ph_image_pills_search_system', true
        );
        $image_left = get_post_meta($post_id, 'ph_image_pills_left', true);
        $image_top = get_post_meta($post_id, 'ph_image_pills_top', true);
        $image_bottom = get_post_meta($post_id, 'ph_image_pills_bottom', true);

        $key_features_val = get_post_meta(
                $post_id,
                'ph_image_pills_caption_key_features',
                true
        );
        $key_features_list = array();
        if ($key_features_val != '') {
            $key_features_data = json_decode(urldecode($key_features_val));

            if (isset($key_features_data)) {
                $key_features_list = $key_features_data->features;
            }
        } ?>

        <section 
            class="pxp-hero pxp-hero-half-image vh-100" 
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

                            if (count($key_features_list) > 0) { ?>
                                <div class="pxp-hero-caption-features-list">
                                    <?php foreach ($key_features_list as $key_feature) { ?>
                                        <div class="pxp-hero-caption-features-list-item">
                                            <img 
                                                src="<?php echo  esc_url(JOBSTER_PLUGIN_PATH . 'images/check.svg'); ?>" 
                                                alt="-"
                                            >
                                            <span><?php echo esc_html($key_feature->text); ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="d-none d-xl-block col-xl-5">
                            <div class="row">
                                <?php $image_left_src = wp_get_attachment_image_src($image_left, 'full');
                                $image_top_src = wp_get_attachment_image_src($image_top, 'full');
                                $image_bottom_src = wp_get_attachment_image_src($image_bottom, 'full');

                                if ($image_left_src !== false) { ?>
                                    <div class="col-6">
                                        <div 
                                            class="pxp-hero-left-pill-img pxp-cover" 
                                            style="background-image:url(<?php echo esc_url($image_left_src[0]); ?>);"
                                            >
                                        </div>
                                    </div>
                                <?php }
                                if ($image_top_src !== false && $image_bottom_src !== false) { ?>
                                    <div class="col-6">
                                        <div 
                                            class="pxp-hero-top-pill-img pxp-cover" 
                                            style="background-image:url(<?php echo esc_url($image_top_src[0]); ?>);"
                                            >
                                        </div>
                                        <div 
                                            class="pxp-hero-bottom-pill-img pxp-cover mt-4" 
                                            style="background-image:url(<?php echo esc_url($image_bottom_src[0]); ?>);"
                                            >
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
endif;
?>