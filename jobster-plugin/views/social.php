<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_job_share_menu')):
    function jobster_get_job_share_menu($post_id) { ?>
        <div class="dropdown ms-2">
            <button 
                class="btn pxp-single-job-share-btn dropdown-toggle" 
                type="button" 
                id="socialShareJobBtn" 
                data-bs-toggle="dropdown" 
                aria-expanded="false"
            >
                <span class="fa fa-share-alt">
            </span></button>
            <ul 
                class="dropdown-menu pxp-single-job-share-dropdown" 
                aria-labelledby="socialShareJobBtn"
            >
                <li>
                    <a 
                        class="dropdown-item" 
                        href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink($post_id)); ?>" 
                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
                        target="_blank"
                    >
                        <span class="fa fa-facebook"></span>
                        <?php esc_html_e('Facebook', 'jobster'); ?>
                    </a>
                </li>
                <li>
                    <a 
                        class="dropdown-item" 
                        href="https://twitter.com/share?url=<?php echo esc_url(get_permalink($post_id)); ?>&amp;text=<?php echo urlencode(get_the_title($post_id)); ?>" 
                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
                        target="_blank"
                    >
                        <span class="fa fa-twitter"></span>
                        <?php esc_html_e('Twitter', 'jobster'); ?>
                    </a>
                </li>
                <li>
                    <script async defer src="//assets.pinterest.com/js/pinit.js"></script>
                    <a 
                        class="dropdown-item" 
                        href="https://www.pinterest.com/pin/create/button/" 
                        data-pin-do="buttonBookmark" 
                        data-pin-custom="true"
                    >
                        <span class="fa fa-pinterest"></span>
                        <?php esc_html_e('Pinterest', 'jobster'); ?>
                    </a>
                </li>
                <li>
                    <a 
                        class="dropdown-item" 
                        href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url(get_permalink($post_id)); ?>&title=<?php echo urlencode(get_the_title($post_id)); ?>"
                    >
                        <span class="fa fa-linkedin"></span>
                        <?php esc_html_e('LinkedIn', 'jobster'); ?>
                    </a>
                </li>
            </ul>
        </div>
    <?php }
endif;

if (!function_exists('jobster_get_social_meta')):
    function jobster_get_social_meta() {
        if (is_single() && !is_singular('job') && have_posts()) { 
            $fb_post_id      = get_the_ID();
            $fb_post_image   = wp_get_attachment_image_src(
                get_post_thumbnail_id($fb_post_id), 'single-post-thumbnail'
            );
            $fb_post_excerpt = jobster_get_excerpt_by_id($fb_post_id);
            $fb_post_title   = get_the_title(); ?>

            <meta property="og:url" content="<?php the_permalink(); ?>" />
            <meta property="og:title" content="<?php echo esc_attr($fb_post_title); ?>" />
            <meta property="og:description" content="<?php echo esc_attr($fb_post_excerpt); ?>" />
            <?php if (is_array($fb_post_image)) { ?>
                <meta property="og:image" content="<?php echo esc_url($fb_post_image[0]); ?>" />
            <?php } ?>
        <?php } else if (is_singular('job') && have_posts()) {
            $fb_post_id    = get_the_ID();
            $fb_post_title = get_the_title();

            $fb_job_cover_val = get_post_meta($fb_post_id, 'job_cover', true);
            $fb_job_cover = wp_get_attachment_image_src($fb_job_cover_val,'pxp-full');

            $fb_p_photo = '';
            if (is_array($fb_job_cover)) {
                $fb_p_photo = $fb_job_cover[0];
            } ?>

            <meta property="og:url" content="<?php the_permalink(); ?>" />
            <meta property="og:title" content="<?php echo esc_attr($fb_post_title); ?>" />
            <meta property="og:description" content="<?php echo esc_attr($fb_post_title); ?>" />
            <meta property="og:image" content="<?php echo esc_url($fb_p_photo); ?>" />
        <?php } else { 
            $bloginfo = get_bloginfo('description'); ?>

            <meta property="og:description" content="<?php echo esc_attr($bloginfo); ?>" />
        <?php }
    }
endif;
?>