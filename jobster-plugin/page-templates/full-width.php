<?php
/*
Template Name: Full Width
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;
get_header();

while (have_posts()) : the_post();
    $post_id = get_the_ID(); ?>

    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <?php if (function_exists('jobster_get_page_header')) {
            jobster_get_page_header(
                array(
                    'post_id' => $post_id,
                    'header_type' => get_post_meta(
                        $post_id,
                        'page_header_type',
                        true
                    )
                )
            );
        }

        $header_type = get_post_meta(
            $post_id,
            'page_header_type',
            true
        );

        $hide_title = get_post_meta(
            $post_id,
            'page_settings_hide_title',
            true
        );

        $content_class =    (
                                ($header_type == '' || $header_type == 'none') 
                                && $hide_title == '1'
                            )
                            ? 'pxp-has-margin'
                            : '';

        if ($hide_title != '1') {
            $subtitle = get_post_meta(
                $post_id,
                'page_settings_subtitle',
                true
            );
            $title_bg_color = get_post_meta(
                $post_id,
                'page_settings_bg_color',
                true
            );
            $title_align = get_post_meta(
                $post_id,
                'page_settings_title_align',
                true
            );

            $align_class = $title_align == 'center' ? 'text-center' : '';

            if ($title_bg_color != '') { ?>
                <section class="pxp-page-header-simple" style="background-color: <?php echo esc_attr($title_bg_color); ?>;">
            <?php } else { ?>
                <section class="mt-100 pxp-no-hero">
            <?php } ?>
                <div class="pxp-container">
                    <h2 class="pxp-section-h2 <?php echo esc_attr($align_class); ?>">
                        <?php the_title(); ?>
                    </h2>

                    <?php if ($subtitle != '') { ?>
                        <div class="pxp-hero-subtitle pxp-text-light <?php echo esc_attr($align_class); ?>">
                            <?php echo esc_html($subtitle); ?>
                        </div>
                    <?php } ?>
                </div>
            </section>
        <?php } ?>


        <div class="entry-content <?php echo esc_attr($content_class); ?>">
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
            );

            if (comments_open() || get_comments_number()) { ?>
                <div class="row justify-content-center">
                    <div class="col-xl-9">
                        <?php comments_template(); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php endwhile;

get_footer(); ?>