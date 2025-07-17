<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_center_image_header')):
    function jobster_get_center_image_header($post_id) {
        $caption_title = get_post_meta(
            $post_id, 'ph_center_image_caption_title', true
        );
        $caption_subtitle = get_post_meta(
            $post_id, 'ph_center_image_caption_subtitle', true
        );
        $show_search = get_post_meta(
            $post_id, 'ph_center_image_show_search', true
        );
        $search_system = get_post_meta(
            $post_id, 'ph_center_image_search_system', true
        );
        $photo = get_post_meta($post_id, 'ph_center_image_photo', true);
        $bg = get_post_meta($post_id, 'ph_center_image_bg', true);
        $opacity = get_post_meta($post_id, 'ph_center_image_opacity', true); ?>

        <?php $bg_src = wp_get_attachment_image_src($bg, 'full');
        if ($bg_src !== false) { ?>
            <section 
                class="pxp-hero pxp-hero-bg pxp-cover" 
                style="background-image: url(<?php echo esc_url($bg_src[0]); ?>);"
            >
                <div 
                    class="pxp-hero-opacity" 
                    style="background: rgba(255,255,255,<?php print esc_attr($opacity); ?>);"
                ></div>
        <?php } else { ?>
            <section class="pxp-hero">
        <?php } ?>

            <div class="pxp-hero-caption">
                <div class="pxp-container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-xl-9 col-xxl-8">
                            <h1 class="text-center">
                                <?php echo esc_html($caption_title); ?>
                            </h1>

                            <?php $photo_src = wp_get_attachment_image_src($photo, 'full');
                            if ($photo_src !== false) { ?>
                                <div class="text-center mt-3 mt-lg-4">
                                    <img 
                                        class="pxp-hero-center-image" 
                                        src="<?php echo esc_url($photo_src[0]); ?>" 
                                        alt="<?php echo esc_attr($caption_title); ?>"
                                    >
                                </div>
                            <?php } ?>

                            <div class="pxp-hero-subtitle text-center mt-3 mt-lg-4">
                                <?php echo esc_html($caption_subtitle); ?>
                            </div>

                            <?php if ($show_search == '1') { 
                                if ($search_system == 'careerjet') {
                                    if (function_exists('jobster_get_careerjet_hero_search_jobs_form')) {
                                        jobster_get_careerjet_hero_search_jobs_form('image_bg');
                                    }
                                } else {
                                    if (function_exists('jobster_get_hero_search_jobs_form')) {
                                        jobster_get_hero_search_jobs_form('image_bg');
                                    }
                                } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    <?php }
endif;
?>