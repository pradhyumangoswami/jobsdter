<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_top_search_header')):
    function jobster_get_top_search_header($post_id) {
        $caption_title = get_post_meta(
            $post_id, 'ph_top_search_caption_title', true
        );
        $caption_subtitle = get_post_meta(
            $post_id, 'ph_top_search_caption_subtitle', true
        );
        $image = get_post_meta($post_id, 'ph_top_search_photo', true);
        $opacity = get_post_meta($post_id, 'ph_top_search_opacity', true);
        $cta_label = get_post_meta($post_id, 'ph_top_search_cta_label', true);
        $cta_link = get_post_meta($post_id, 'ph_top_search_cta_link', true);

        $image_src = wp_get_attachment_image_src($image, 'pxp-full');
        if ($image_src !== false) { ?>
            <section 
                class="pxp-hero pxp-hero-bg pxp-cover" 
                style="background-image: url(<?php echo esc_url($image_src[0]); ?>);"
            >
        <?php } else { ?>
            <section class="pxp-hero pxp-hero-bg pxp-cover">
        <?php } ?>
            <div 
                class="pxp-hero-opacity" 
                style="background: rgba(0,0,0,<?php print esc_attr($opacity); ?>);"
            ></div>
            <div class="pxp-hero-caption">
                <div class="pxp-container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-xl-9 col-xxl-8">
                            <h1 class="text-white text-center">
                                <?php echo esc_html($caption_title); ?>
                            </h1>
                            <div class="pxp-hero-subtitle text-white text-center mt-3 mt-lg-4">
                                <?php echo esc_html($caption_subtitle); ?>
                            </div>
                            <?php if ($cta_label != '' && $cta_link != '') { ?>
                                <div class="mt-3 mt-lg-4 text-center">
                                    <a 
                                        href="<?php echo esc_url($cta_link); ?>" 
                                        class="btn rounded-pill pxp-section-cta"
                                    >
                                        <?php echo esc_html($cta_label); ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
endif;
?>