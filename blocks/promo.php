<?php
/**
 * Promo block
 */
if (!function_exists('jobster_promo_block')): 
    function jobster_promo_block() {
        wp_register_script(
            'jobster-promo-block',
            plugins_url('js/promo.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-promo-block-editor',
            plugins_url('css/promo.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/promo', array(
            'editor_script' => 'jobster-promo-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_promo_block_render'
        ));
    }
endif;
add_action('init', 'jobster_promo_block');

if (!function_exists('jobster_promo_block_render')): 
    function jobster_promo_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';
        $type = isset($data['type']) && $data['type'] == 'd'
                ? 'pxp-dark'
                : '';

        $section_position = '';
        $row_position = '';
        if (isset($data['position']) && $data['position'] == 'e') {
            $section_position = 'pxp-end';
            $row_position = 'justify-content-end';
        }

        $return_string = 
            '<section class="mt-100">
                <div class="pxp-container">';
        if (isset($data['image']) && $data['image'] != '') {
            $image = wp_get_attachment_image_src($data['image'], 'full');

            if (is_array($image)) {
                $return_string .=
                    '<div 
                        class="pxp-promo-img pxp-cover pt-100 pb-100 ' . esc_attr($animation) . ' ' . esc_attr($type) . ' ' . esc_attr($section_position) . '" 
                        style="background-image: url(' . esc_url($image[0]) . ');"
                    >';
            } else {
                $return_string .=
                    '<div class="pxp-promo-img pt-100 pb-100 ' . esc_attr($animation) . ' ' . esc_attr($type) . ' ' . esc_attr($section_position) . '">';
            }
        } else {
            $return_string .=
                    '<div class="pxp-promo-img pt-100 pb-100 ' . esc_attr($animation) . ' ' . esc_attr($type) . ' ' . esc_attr($section_position) . '">';
        }
        $return_string .=
                        '<div class="row ' . esc_attr($row_position) . '">
                            <div class="col-sm-7 col-lg-5">
                                <h2 class="pxp-section-h2">
                                    ' . esc_html($data['title']) . '
                                </h2>
                                <p class="pxp-text-light">
                                    ' . esc_html($data['text']) . '
                                </p>';
        if (isset($data['cta']) && $data['cta'] != '') {
            $return_string .=
                                '<div class="mt-4 mt-md-5">
                                    <a 
                                        href="' . esc_url($data['link']) . '" 
                                        class="btn rounded-pill pxp-section-cta"
                                    >
                                        ' . esc_html($data['cta']) . '
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>';
        }
        $return_string .=
                            '</div>
                        </div>
                    </div>
                </div>
            </section>';

        return $return_string;
    }
endif;
?>