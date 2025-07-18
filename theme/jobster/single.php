<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;
get_header();

$is_sidebar = is_active_sidebar('pxp-main-widget-area');
$blog_settings = get_option('jobster_blog_settings', '');

while (have_posts()) : the_post();
    $post_id    = get_the_ID();
    $post_date  = get_the_date();
    $categories = get_the_category();
    $author     = get_the_author();

    $post_hero = wp_get_attachment_image_src(
        get_post_thumbnail_id($post_id),
        'pxp-full'
    );

    $post_excerpt = get_the_excerpt($post_id);

    $categories = get_the_category();
    $separator  = '&nbsp;&nbsp;&bull;&nbsp;&nbsp;'; ?>

    <section>
        <div class="pxp-container">
            <div class="pxp-blog-hero">
                <div class="row justify-content-between align-items-end">
                    <div class="col-lg-8 col-xxl-6">
                        <h1><?php the_title(); ?></h1>
                        <div class="pxp-hero-subtitle pxp-text-light">
                            <?php echo esc_html($post_excerpt); ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xxl-6">
                        <div class="text-start text-lg-end mt-4 mt-lg-0">
                            <?php if ($categories) { ?>
                                <div class="pxp-single-blog-top-category">
                                    <?php esc_html_e('Posted in', 'jobster'); 
                                    $cat_count = 0;
                                    foreach ($categories as $category) { ?>
                                        <a 
                                            href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                            title="<?php echo esc_attr(sprintf(__('View all posts in %s','jobster'), $category->name)); ?>"
                                        >
                                            <?php echo esc_html($category->cat_name); ?>
                                        </a><?php if ((count($categories) - 1) != $cat_count) echo esc_html($separator); ?>
                                        <?php $cat_count++;
                                    } ?>
                                </div>
                            <?php } ?>
                            <div class="pxp-single-blog-top-date">
                                <?php echo get_the_date(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($post_hero !== false) { ?>
                <img 
                    class="pxp-single-blog-featured-img" 
                    src="<?php echo esc_url($post_hero[0]); ?>" 
                    alt="<?php the_title(); ?>"
                >
            <?php } ?>
        </div>
    </section>

    <section class="mt-100">
        <div class="pxp-container">
            <div class="row justify-content-center">
                <div class="col-xl-7">
                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-content pxp-single-blog-content">
                            <?php the_content(); ?>
                            <div class="clearfix"></div>

                            <?php wp_link_pages(
                                array(
                                    'before'      => '<div class="pagination pxp-pagination mt-2 mt-md-4">',
                                    'after'       => '</div>',
                                    'link_before' => '<span>',
                                    'link_after'  => '</span>',
                                    'pagelink'    => '%',
                                    'separator'   => '',
                                )
                            ); ?>

                            <div class="mt-4 mt-md-5">
                                <?php the_tags(
                                    '<div class="pxp-single-post-tags"><span class="fa fa-tags"></span>',
                                    '',
                                    '</div>'
                                ); ?>
                            </div>
                        </div>
                    </div>

                    <?php if (function_exists('jobster_share_post')) {
                        jobster_share_post($post_id);
                    }

                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    } ?>
                </div>

                <?php if ($is_sidebar === true) { ?>
                    <div class="col-lg-5 col-xl-4 col-xxl-3">
                        <div class="pxp-side-panel">
                            <?php get_sidebar(); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <?php $related_posts =  isset($blog_settings['jobster_blog_related_posts_field'])
                            ? $blog_settings['jobster_blog_related_posts_field']
                            : '';

    if ($related_posts == '1') {
        get_template_part('templates/related_posts');
    }
endwhile;

get_footer(); ?>