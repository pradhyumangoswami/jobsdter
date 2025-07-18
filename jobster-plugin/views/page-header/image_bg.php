<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_image_bg_header')):
    function jobster_get_image_bg_header($post_id) {
        $caption_title = get_post_meta(
            $post_id, 'ph_image_bg_caption_title', true
        );
        $caption_subtitle = get_post_meta(
            $post_id, 'ph_image_bg_caption_subtitle', true
        );
        $show_search = get_post_meta($post_id, 'ph_image_bg_show_search', true);
        $search_system = get_post_meta(
            $post_id, 'ph_image_bg_search_system', true
        );
        $image = get_post_meta($post_id, 'ph_image_bg_photo', true);
        $opacity = get_post_meta($post_id, 'ph_image_bg_opacity', true);

        $height = get_post_meta($post_id, 'ph_image_bg_height', true);
        $height_class = $height == 'half' ? 'pxp-is-half' : '';

        $image_src = wp_get_attachment_image_src($image, 'pxp-full');

        $align_caption = 'justify-content-center';
        $align_text = 'text-center';
        $align = get_post_meta($post_id, 'ph_image_bg_align', true);
        if ($align == 'start') {
            $align_caption = '';
            $align_text = '';
        }


        if ($image_src !== false) { ?>
            <section 
                class="pxp-hero pxp-hero-bg pxp-cover <?php echo esc_attr($height_class); ?>" 
                style="background-image: url(<?php echo esc_url($image_src[0]); ?>);"
            >
        <?php } else { ?>
            <section class="pxp-hero pxp-hero-bg pxp-cover <?php echo esc_attr($height_class); ?>">
        <?php } ?>
            <div 
                class="pxp-hero-opacity" 
                style="background: rgba(0,0,0,<?php print esc_attr($opacity); ?>);"
            ></div>
            <div class="pxp-hero-caption">
                <div class="pxp-container">
                    <div class="row <?php echo esc_attr($align_caption); ?>">
                        <div class="col-12 col-xl-9 col-xxl-8">
                            <h1 class="text-white <?php echo esc_attr($align_text); ?>">
                                <?php echo esc_html($caption_title); ?>
                            </h1>

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

                            <div class="pxp-hero-subtitle text-white <?php echo esc_attr($align_text); ?> mt-3 mt-lg-4">
                                <?php echo esc_html($caption_subtitle); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
endif;
?>