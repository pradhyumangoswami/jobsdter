<?php
/**
 * Featured articles block
 */
if (!function_exists('jobster_featured_articles_block')): 
    function jobster_featured_articles_block() {
        wp_register_script(
            'jobster-featured-articles-block',
            plugins_url('js/featured-articles.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-featured-articles-block-editor',
            plugins_url('css/featured-articles.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/featured-articles', array(
            'editor_script' => 'jobster-featured-articles-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_featured_articles_block_render'
        ));
    }
endif;
add_action('init', 'jobster_featured_articles_block');

if (!function_exists('jobster_featured_articles_block_render')): 
    function jobster_featured_articles_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';

        $align_text = '';
        $align_cards = '';
        if (isset($data['align']) && $data['align'] == 'c') {
            $align_text = 'text-center';
            $align_cards = 'justify-content-center';
        }

        $blog_url = get_permalink(get_option('page_for_posts'));

        $args = array(
            'numberposts'      => '4',
            'post_type'        => 'post',
            'order'            => 'DESC',
            'meta_key'         => 'post_featured',
            'meta_value'       => '1',
            'suppress_filters' => false,
            'post_status'      => 'publish'
        );

        $posts = wp_get_recent_posts($args);

        $return_string = 
            '<section class="mt-100">
                <div class="pxp-container">';
        if (isset($data['title']) && $data['title'] != '') {
            $return_string .=
                    '<h2 class="pxp-section-h2 ' . esc_attr($align_text) . '">
                        ' . esc_html($data['title']) . '
                    </h2>';
        }
        if (isset($data['subtitle']) && $data['subtitle'] != '') {
            $return_string .=
                    '<p class="pxp-text-light ' . esc_attr($align_text) . '">
                        ' . esc_html($data['subtitle']) . '
                    </p>';
        }
        $return_string .=
                    '<div class="row mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_cards) . '">';

        foreach($posts as $post) : 
            $post_image = wp_get_attachment_image_src(
                get_post_thumbnail_id($post['ID']), 'pxp-gallery'
            );
            $post_excerpt = get_the_excerpt($post['ID']);
            $post_link = get_permalink($post['ID']);

            $categories = get_the_category($post['ID']);
            $separator  = '&nbsp;&nbsp;&bull;&nbsp;&nbsp;';
            $output     = '';

            if ($categories) {
                foreach ($categories as $category) {
                    $output .= '
                        <a 
                            class="pxp-posts-card-1-category" 
                            href="' . esc_url(get_category_link($category->term_id)) . '" 
                            title="' . esc_attr(sprintf(__('View all posts in %s','jobster'), $category->name)) . '"
                        >
                            ' . esc_html($category->cat_name) . '
                        </a>' . esc_html($separator);
                }
                $post_categories = trim($output, $separator);
            }

            $return_string .=
                        '<div class="col-md-6 col-xl-4 col-xxl-3 pxp-posts-card-1-container">
                            <div class="pxp-posts-card-1 pxp-has-border">
                                <div class="pxp-posts-card-1-top">
                                    <div class="pxp-posts-card-1-top-bg">';
            if ($post_image !== false) {
                $return_string .=
                                        '<div 
                                            class="pxp-posts-card-1-image pxp-cover" 
                                            style="background-image: url(' . esc_url($post_image[0]) . ');"
                                        ></div>';
            }
            $return_string .=
                                        '<div class="pxp-posts-card-1-info">
                                            <div class="pxp-posts-card-1-date">
                                                ' . get_the_date('', $post['ID']) . '
                                            </div>';
            if (isset($post_categories)) {
                $return_string .=
                                            '<div class="pxp-posts-card-1-categories">
                                                ' . $post_categories . '
                                            </div>';
            }
            $return_string .=
                                        '</div>
                                    </div>
                                    <div class="pxp-posts-card-1-content">
                                        <a 
                                            href="' . esc_url($post_link) . '" 
                                            class="pxp-posts-card-1-title"
                                        >
                                            ' . esc_html(get_the_title($post['ID'])) . '
                                        </a>
                                        <div class="pxp-posts-card-1-summary pxp-text-light">
                                            ' . esc_html($post_excerpt) . '
                                        </div>
                                    </div>
                                </div>
                                <div class="pxp-posts-card-1-bottom">
                                    <div class="pxp-posts-card-1-cta">
                                        <a href="' . esc_url($post_link) . '">
                                            ' . esc_html__('Read more', 'jobster') . '
                                            <span class="fa fa-angle-right"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>';
        endforeach;

        $return_string .=
                    '</div>
                    <div class="mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_text) . '">
                        <a 
                            href="' . esc_url($blog_url) . '" 
                            class="btn rounded-pill pxp-section-cta"
                        >
                            ' . esc_html__('Read All Articles', 'jobster') . '
                            <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
                </div>
            </section>';

        wp_reset_postdata();
        wp_reset_query();

        return $return_string;
    }
endif;
?>