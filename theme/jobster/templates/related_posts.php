<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

$blog_settings = get_option('jobster_blog_settings', '');
$related_title =    isset($blog_settings['jobster_blog_related_posts_title_field'])
                    ? $blog_settings['jobster_blog_related_posts_title_field']
                    : __('Related Articles', 'jobster');
$related_subtitle =     isset($blog_settings['jobster_blog_related_posts_subtitle_field'])
                        ? $blog_settings['jobster_blog_related_posts_subtitle_field']
                        : '' ?>

<section class="mt-100">
    <div class="pxp-container">
        <h2 class="pxp-subsection-h2">
            <?php echo esc_html($related_title); ?>
        </h2>
        <?php if ($related_subtitle != '') { ?>
            <p class="pxp-text-light">
                <?php echo esc_html($related_subtitle); ?>
            </p>
        <?php } ?>

        <div class="row mt-3 mt-md-4">
            <?php $orig_post = $post;
            $tags = wp_get_post_tags($post->ID);

            if ($tags) {
                $tag_ids = array();

                foreach ($tags as $individual_tag) {
                    $tag_ids[] = $individual_tag->term_id;
                }

                $args = array(
                    'tag__in'             => $tag_ids,
                    'post__not_in'        => array($post->ID),
                    'posts_per_page'      => 4,
                    'ignore_sticky_posts' => false,
                );

                $my_query = new wp_query($args);

                if ($my_query->have_posts()) {
                    while ($my_query->have_posts()) {
                        $my_query->the_post();

                        $r_id        = get_the_ID();
                        $r_link      = get_permalink($r_id);
                        $r_title     = get_the_title($r_id);
                        $r_excerpt   = get_the_excerpt($r_id);
                        $r_image     =  wp_get_attachment_image_src(
                                            get_post_thumbnail_id($r_id), 'pxp-gallery'
                                        );
                        $r_date      = get_the_date();

                        $categories = get_the_category();
                        $separator  = '&nbsp;&nbsp;&bull;&nbsp;&nbsp;'; ?>

                        <div class="col-md-6 col-xl-4 col-xxl-3 pxp-posts-card-1-container">
                            <div class="pxp-posts-card-1 pxp-has-border">
                                <div class="pxp-posts-card-1-top">
                                    <div class="pxp-posts-card-1-top-bg">
                                        <?php if ($r_image !== false) { ?>
                                            <div 
                                                class="pxp-posts-card-1-image pxp-cover" 
                                                style="background-image: url(<?php echo esc_url($r_image[0]); ?>);"
                                            ></div>
                                        <?php } ?>
                                        <div class="pxp-posts-card-1-info">
                                            <div class="pxp-posts-card-1-date">
                                                <?php echo get_the_date(); ?>
                                            </div>
                                            <?php if ($categories) { ?>
                                                <div class="pxp-posts-card-1-categories">
                                                    <?php $cat_count = 0;
                                                    foreach ($categories as $category) { ?>
                                                        <a 
                                                            class="pxp-posts-card-1-category" 
                                                            href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                                            title="<?php echo esc_attr(sprintf(__('View all posts in %s','jobster'), $category->name)); ?>"
                                                        >
                                                            <?php echo esc_html($category->cat_name); ?>
                                                        </a><?php if ((count($categories) - 1) != $cat_count) echo esc_html($separator); ?>
                                                        <?php $cat_count++;
                                                    } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="pxp-posts-card-1-content">
                                        <a 
                                            href="<?php echo esc_url($r_link); ?>" 
                                            class="pxp-posts-card-1-title"
                                        >
                                            <?php echo esc_html($r_title); ?>
                                        </a>
                                        <div class="pxp-posts-card-1-summary pxp-text-light">
                                            <?php echo esc_html($r_excerpt); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="pxp-posts-card-1-bottom">
                                    <div class="pxp-posts-card-1-cta">
                                        <a href="<?php echo esc_url($r_link); ?>">
                                            <?php esc_html_e('Read more', 'jobster'); ?>
                                            <span class="fa fa-angle-right"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <div class="col">
                        <i><?php esc_html_e('No related articles', 'jobster'); ?></i>
                    </div>
                <?php }
            } else { ?>
                <div class="col">
                    <i><?php esc_html_e('No related articles', 'jobster'); ?></i>
                </div>
            <?php }
            $post = $orig_post;
            wp_reset_postdata(); ?>
        </div>
    </div>
</section>