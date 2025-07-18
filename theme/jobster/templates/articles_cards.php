<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

$blog_settings = get_option('jobster_blog_settings', '');

$blog_page_title =  isset($blog_settings['jobster_blog_title_field'])
                    ? $blog_settings['jobster_blog_title_field']
                    : '';
$blog_page_subtitle =   isset($blog_settings['jobster_blog_subtitle_field'])
                        ? $blog_settings['jobster_blog_subtitle_field']
                        : ''; 

$is_sidebar = is_active_sidebar('pxp-main-widget-area');

$articles_column_class = 'col-12';
$grid_class = 'col-md-6 col-lg-12 col-xl-6 col-xxl-3';
if ($is_sidebar === true) {
    $articles_column_class = 'col-lg-7 col-xl-8 col-xxl-9';
    $grid_class = 'col-md-6 col-lg-12 col-xl-6 col-xxl-4';
} ?>

<section>
    <div class="pxp-container">
        <div class="pxp-blog-hero">
            <h1>
                <?php if ($blog_page_title != '') {
                    echo esc_html($blog_page_title);
                } else {
                    esc_html_e('Latest Articles', 'jobster');
                } ?>
            </h1>

            <?php if ($blog_page_subtitle != '') { ?>
                <p class="pxp-hero-subtitle pxp-text-light">
                    <?php echo esc_html($blog_page_subtitle); ?>
                </p>
            <?php } ?>
        </div>

        <?php get_template_part('templates/featured_posts_carousel'); ?>

        <div class="mt-4 mt-lg-5">
            <div class="row">
                <div class="<?php echo esc_attr($articles_column_class); ?>">
                    <div class="row pxp-masonry">
                        <?php $temp = isset($postslist) ? $postslist : null;
                        $postslist = null; 

                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                        $args = array( 
                            'posts_per_page' => get_option('posts_per_page'),
                            'paged' => $paged,
                            'post_type' => 'post'
                        );

                        $postslist = new WP_Query($args);

                        if ($postslist->have_posts()) {
                            while($postslist->have_posts()) {
                                $postslist->the_post();

                                $post_id = get_the_ID();

                                $post_image = wp_get_attachment_image_src(
                                    get_post_thumbnail_id($post_id), 'pxp-gallery'
                                );
                                $post_excerpt = get_the_excerpt($post_id);
                                $post_link = get_permalink($post_id);

                                $categories = get_the_category();
                                $separator  = '&nbsp;&nbsp;&bull;&nbsp;&nbsp;';

                                if (!is_sticky($post_id)) { ?>
                                    <div class="<?php echo esc_attr($grid_class); ?> pxp-posts-card-1-container pxp-grid-item">
                                        <div class="pxp-posts-card-1 pxp-has-border">
                                            <div class="pxp-posts-card-1-top">
                                                <div class="pxp-posts-card-1-top-bg">
                                                    <?php if ($post_image !== false) { ?>
                                                        <div 
                                                            class="pxp-posts-card-1-image pxp-cover" 
                                                            style="background-image: url(<?php echo esc_url($post_image[0]); ?>);"
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
                                                        href="<?php echo esc_url($post_link); ?>" 
                                                        class="pxp-posts-card-1-title"
                                                    >
                                                        <?php the_title(); ?>
                                                    </a>
                                                    <div class="pxp-posts-card-1-summary pxp-text-light">
                                                        <?php echo esc_html($post_excerpt); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pxp-posts-card-1-bottom">
                                                <div class="pxp-posts-card-1-cta">
                                                    <a href="<?php echo esc_url($post_link); ?>">
                                                        <?php esc_html_e('Read more', 'jobster'); ?>
                                                        <span class="fa fa-angle-right"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            }
                        } 
                        wp_reset_postdata(); ?>
                    </div>

                    <?php jobster_pagination($postslist->max_num_pages);

                    $postslist = null;
                    $postslist = $temp; ?>
                </div>

                <?php if ($is_sidebar === true) { ?>
                    <div class="col-lg-5 col-xl-4 col-xxl-3">
                        <div class="pxp-side-panel pxp-has-margin">
                            <?php get_sidebar(); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>