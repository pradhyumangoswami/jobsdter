<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_right_image_header')):
    function jobster_get_right_image_header($post_id) {
        $caption_title = get_post_meta(
            $post_id, 'ph_right_image_caption_title', true
        );
        $caption_subtitle = get_post_meta(
            $post_id, 'ph_right_image_caption_subtitle', true
        );
        $photo = get_post_meta($post_id, 'ph_right_image_photo', true);
        $bg = get_post_meta($post_id, 'ph_right_image_bg', true);
        $opacity = get_post_meta($post_id, 'ph_right_image_opacity', true);

        $key_features_val = get_post_meta(
                $post_id,
                'ph_right_image_caption_key_features',
                true
        );
        $key_features_list = array();
        if ($key_features_val != '') {
            $key_features_data = json_decode(urldecode($key_features_val));

            if (isset($key_features_data)) {
                $key_features_list = $key_features_data->features;
            }
        }

        $cta_label = get_post_meta($post_id, 'ph_right_image_cta_label', true);
        $cta_link = get_post_meta($post_id, 'ph_right_image_cta_link', true); ?>

        <?php $bg_src = wp_get_attachment_image_src($bg, 'full');
        if ($bg_src !== false) { ?>
            <section 
                class="pxp-hero pxp-is-smaller pxp-hero-bg pxp-cover" 
                style="background-image: url(<?php echo esc_url($bg_src[0]); ?>);"
            >
                <div 
                    class="pxp-hero-opacity" 
                    style="background: rgba(255,255,255,<?php print esc_attr($opacity); ?>);"
                ></div>
        <?php } else { ?>
            <section class="pxp-hero pxp-is-smaller">
        <?php } ?>

            <div class="pxp-hero-caption">
                <div class="pxp-container">
                    <div class="row pxp-pl-80 align-items-center justify-content-between">
                        <div class="col-12 col-xl-6 col-xxl-5">
                            <h1><?php echo esc_html($caption_title); ?></h1>
                            <div class="pxp-hero-subtitle mt-3 mt-lg-4">
                                <?php echo esc_html($caption_subtitle); ?>
                            </div>

                            <?php if (count($key_features_list) > 0) { ?>
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
                            <?php }

                            if ($cta_label != '' && $cta_link != '') { ?>
                                <div class="mt-4 mt-lg-5">
                                    <a 
                                        href="<?php echo esc_url($cta_link); ?>" 
                                        class="btn rounded-pill pxp-section-cta"
                                    >
                                        <?php echo esc_html($cta_label); ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="d-none d-xl-block col-xl-5 position-relative">
                            <?php $photo_src = wp_get_attachment_image_src(
                                $photo, 'full'
                            );
                            $photo_info = jobster_get_attachment($photo);

                            if ($photo_src !== false) { ?>
                                <div class="pxp-header-side-image pxp-has-animation pxp-pr-80 pxp-is-right">
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

        </section>
    <?php }
endif;
?>