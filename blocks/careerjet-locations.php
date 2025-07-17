<?php
/**
 * Careerjet job locations block
 */
if (!function_exists('jobster_careerjet_locations_block')): 
    function jobster_careerjet_locations_block() {
        wp_register_script(
            'jobster-careerjet-locations-block',
            plugins_url('js/careerjet-locations.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-careerjet-locations-block-editor',
            plugins_url('css/careerjet-locations.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/careerjet-locations', array(
            'editor_script' => 'jobster-careerjet-locations-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_careerjet_locations_block_render'
        ));
    }
endif;
add_action('init', 'jobster_careerjet_locations_block');

if (!function_exists('jobster_careerjet_locations_block_render')): 
    function jobster_careerjet_locations_block_render($attrs, $content = null) {
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

        $search_jobs_url = jobster_get_page_link('job-search-apis.php');

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

        if (isset($data['locations']) && is_array($data['locations'])) {
            switch ($data['type']) {
                case 'b':
                    foreach ($data['locations'] as $location) {
                        $location_link = add_query_arg(
                            'location',
                            $location['location'],
                            $search_jobs_url
                        );

                        $location_img = wp_get_attachment_image_src(
                            $location['id'],
                            'pxp-gallery'
                        );

                        $return_string .=
                            '<div class="col-md-6 col-xl-4 col-xxl-3">
                                <a 
                                    href="' . esc_url($location_link) . '" 
                                    class="pxp-cities-card-2" ';
                        if (isset($data['bg']) && $data['bg'] != '') {
                            $return_string .=
                                    'style="background-color: ' . esc_attr($data['bg']) . '"';
                        }
                        $return_string .=
                                '>';
                        if (is_array($location_img)) {
                            $return_string .=
                                    '<div class="pxp-cities-card-2-image-container">
                                        <div 
                                            class="pxp-cities-card-2-image pxp-cover" 
                                            style="background-image: url(' . esc_url($location_img[0]) . ');"
                                        ></div>
                                    </div>';
                        }
                        $return_string .=
                                    '<div class="pxp-cities-card-2-info">
                                        <div class="pxp-cities-card-2-name">
                                            ' . esc_html($location['location']) . '
                                        </div>
                                    </div>
                                </a>
                            </div>';
                    }
                    break;
                case 's':
                    foreach ($data['locations'] as $location) {
                        $location_link = add_query_arg(
                            'location',
                            $location['location'],
                            $search_jobs_url
                        );

                        $location_img = wp_get_attachment_image_src(
                            $location['id'],
                            'pxp-gallery'
                        );
                        $card_container_class = '';
                        if (!is_array($location_img)) {
                            $card_container_class = 'pxp-no-img';
                        }

                        $return_string .=
                            '<div class="col-12 col-md-4 col-lg-3 col-xxl-2 pxp-cities-card-1-container ' . esc_attr($card_container_class) . '">
                                <a 
                                    href="' . esc_url($location_link) . '" 
                                    class="pxp-cities-card-1 text-center" ';
                        if (isset($data['bg']) && $data['bg'] != '') {
                            $return_string .=
                                    'style="background-color: ' . esc_attr($data['bg']) . '"';
                        }
                        $return_string .=
                                '>
                                    <div class="pxp-cities-card-1-top">';
                        if (is_array($location_img)) {
                            $return_string .=
                                        '<div 
                                            class="pxp-cities-card-1-image pxp-cover" 
                                            style="background-image: url(' . esc_url($location_img[0]) . ');"
                                        ></div>';
                        }
                        $return_string .=
                                        '<div class="pxp-cities-card-1-name">
                                            ' . esc_html($location['location']) . '
                                        </div>
                                    </div>
                                </a>
                            </div>';
                    }
                    break;
                default:
                    foreach ($data['locations'] as $location) {
                        $location_link = add_query_arg(
                            'location',
                            $location['location'],
                            $search_jobs_url
                        );

                        $location_img = wp_get_attachment_image_src(
                            $location['id'],
                            'pxp-gallery'
                        );

                        $return_string .=
                            '<div class="col-md-6 col-xl-4 col-xxl-3">
                                <a 
                                    href="' . esc_url($location_link) . '" 
                                    class="pxp-cities-card-2" ';
                        if (isset($data['bg']) && $data['bg'] != '') {
                            $return_string .=
                                    'style="background-color: ' . esc_attr($data['bg']) . '"';
                        }
                        $return_string .=
                                '>';
                        if (is_array($location_img)) {
                            $return_string .=
                                    '<div class="pxp-cities-card-2-image-container">
                                        <div 
                                            class="pxp-cities-card-2-image pxp-cover" 
                                            style="background-image: url(' . esc_url($location_img[0]) . ');"
                                        ></div>
                                    </div>';
                        }
                        $return_string .=
                                    '<div class="pxp-cities-card-2-info">
                                        <div class="pxp-cities-card-2-name">
                                            ' . esc_html($location['location']) . '
                                        </div>
                                    </div>
                                </a>
                            </div>';
                    }
                    break;
            }
        }
 
        $return_string .=
                    '</div>
                </div>
            </section>';
 
        return $return_string;
    }
endif;
?>