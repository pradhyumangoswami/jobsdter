<?php
/**
 * Testimonials block
 */
if (!function_exists('jobster_testimonials_block')): 
    function jobster_testimonials_block() {
        wp_register_script(
            'jobster-testimonials-block',
            plugins_url('js/testimonials.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-testimonials-block-editor',
            plugins_url('css/testimonials.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/testimonials', array(
            'editor_script' => 'jobster-testimonials-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_testimonials_block_render'
        ));
    }
endif;
add_action('init', 'jobster_testimonials_block');

if (!function_exists('jobster_testimonials_block_render')): 
    function jobster_testimonials_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';

        $args = array(
            'numberposts'      => -1,
            'post_type'        => 'testimonial',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'suppress_filters' => false,
            'post_status'      => 'publish'
        );

        $posts = wp_get_recent_posts($args);

        $return_string = 
            '<section class="mt-100">
                <div class="pxp-container">';
        if (isset($data['title']) && $data['title'] != '') {
            $return_string .=
                    '<h2 class="pxp-section-h2 text-center">
                        ' . esc_html($data['title']) . '
                    </h2>';
        }
        if (isset($data['subtitle']) && $data['subtitle'] != '') {
            $return_string .=
                    '<p class="pxp-text-light text-center">
                        ' . esc_html($data['subtitle']) . '
                    </p>';
        }
        $return_string .= 
                    '<div class="pxp-testimonials-1">
                        <div class="pxp-testimonials-1-circles d-none d-md-block">
                            <div 
                                class="pxp-testimonials-1-circles-item pxp-item-1 pxp-cover pxp-animate-in pxp-animate-bounce" 
                                style="background-image: url(' . esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-1.png') . ');"
                            ></div>
                            <div class="pxp-testimonials-1-circles-item pxp-item-2 pxp-animate-in pxp-animate-bounce"></div>
                            <div class="pxp-testimonials-1-circles-item pxp-item-3 pxp-animate-in pxp-animate-bounce"></div>
                            <div 
                                class="pxp-testimonials-1-circles-item pxp-item-4 pxp-cover pxp-animate-in pxp-animate-bounce" 
                                style="background-image: url(' . esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-2.png') . ');"
                            ></div>
                            <div 
                                class="pxp-testimonials-1-circles-item pxp-item-5 pxp-cover pxp-animate-in pxp-animate-bounce" 
                                style="background-image: url(' . esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-3.png') . ');"
                            ></div>
                            <div class="pxp-testimonials-1-circles-item pxp-item-6 pxp-animate-in pxp-animate-bounce"></div>
                            <div 
                                class="pxp-testimonials-1-circles-item pxp-item-7 pxp-cover pxp-animate-in pxp-animate-bounce" 
                                style="background-image: url(' . esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-4.png') . ');"
                            ></div>
                            <div class="pxp-testimonials-1-circles-item pxp-item-8 pxp-animate-in pxp-animate-bounce"></div>
                            <div 
                                class="pxp-testimonials-1-circles-item pxp-item-9 pxp-cover pxp-animate-in pxp-animate-bounce" 
                                style="background-image: url(' . esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-5.png') . ');"
                            ></div>
                            <div 
                                class="pxp-testimonials-1-circles-item pxp-item-10 pxp-cover pxp-animate-in pxp-animate-bounce" 
                                style="background-image: url(' . esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-6.png') . ');"
                            ></div>
                        </div>

                        <div class="pxp-testimonials-1-carousel-container">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-10 col-md-6 col-lg-6 col-xl-5 col-xxl-4">
                                    <div class="pxp-testimonials-1-carousel ' . esc_attr($animation) . '">
                                        <div 
                                            id="pxpTestimonials1Carousel" 
                                            class="carousel slide" data-bs-ride="carousel"
                                        >
                                            <div class="carousel-inner">';
        $count = 0;
        foreach ($posts as $post) {
            $text = get_post_meta($post['ID'], 'testimonial_text', true);
            $location = get_post_meta($post['ID'], 'testimonial_location', true);

            $active_class = $count == 0 ? 'active' : '';

            $return_string .=
                                                '<div class="carousel-item text-center ' . esc_attr($active_class) . '">
                                                    <div class="pxp-testimonials-1-carousel-item-text">
                                                        ' . esc_html($text) . '
                                                    </div>
                                                    <div class="pxp-testimonials-1-carousel-item-name">
                                                        ' . esc_html($post['post_title']) . '
                                                    </div>
                                                    <div class="pxp-testimonials-1-carousel-item-company">
                                                        ' . esc_html($location) . '
                                                    </div>
                                                </div>';
            $count++;
        }
        $return_string .= 
                                            '</div>
                                            <button 
                                                class="carousel-control-prev" 
                                                type="button" 
                                                data-bs-target="#pxpTestimonials1Carousel" 
                                                data-bs-slide="prev"
                                            >
                                                <span class="fa fa-angle-left" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button 
                                                class="carousel-control-next" 
                                                type="button" 
                                                data-bs-target="#pxpTestimonials1Carousel" 
                                                data-bs-slide="next"
                                            >
                                                <span class="fa fa-angle-right" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>';

        return $return_string;
    }
endif;
?>