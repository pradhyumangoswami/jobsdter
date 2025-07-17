<?php
/**
 * Services block
 */
if (!function_exists('jobster_services_block')): 
    function jobster_services_block() {
        wp_register_script(
            'jobster-services-block',
            plugins_url('js/services.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-services-block-editor',
            plugins_url('css/services.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/services', array(
            'editor_script' => 'jobster-services-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_services_block_render'
        ));
    }
endif;
add_action('init', 'jobster_services_block');

if (!function_exists('jobster_services_block_render')): 
    function jobster_services_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';
        $hanimation =    isset($data['hanimation']) && $data['hanimation'] == 'e'
                        ? 'pxp-animate-icon'
                        : '';

        $align_text = '';
        $align_cards = 'justify-content-between';
        if (isset($data['align']) && $data['align'] == 'c') {
            $align_text = 'text-center';
            $align_cards = 'justify-content-evenly';
        }

        $section_id = uniqid();

        $return_string = 
            '<section 
                class="mt-100" 
                id="pxp-services-1-' . esc_attr($section_id) . '"
            >
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

        if (isset($data['services']) && is_array($data['services'])) {
            foreach ($data['services'] as $service) {
                $service_img = wp_get_attachment_image_src(
                    $service['value'],
                    'pxp-full'
                );

                $return_string .=
                        '<div class="col-lg-4 col-xl-3 pxp-services-1-item-container">
                            <div class="pxp-services-1-item ' . esc_attr($align_text) . ' ' . esc_attr($hanimation) . '">';
                if (is_array($service_img)) {
                    $return_string .=
                                '<div class="pxp-services-1-item-icon">
                                    <img 
                                        src="' . esc_url($service_img[0]) . '" 
                                        alt="' . esc_attr($service['title']) . '"
                                    >
                                </div>';
                }
                $return_string .=
                                '<div class="pxp-services-1-item-title">
                                    ' . esc_html($service['title']) . '
                                </div>
                                <div class="pxp-services-1-item-text pxp-text-light">
                                    ' . esc_html($service['text']) . '
                                </div>';
                if ($service['cta'] != '') {
                    $return_string .=
                                '<div class="pxp-services-1-item-cta">
                                    <a href="' . esc_url($service['link']) . '">
                                        ' . esc_html($service['cta']) . '
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>';
                }
                $return_string .=
                            '</div>
                        </div>';
            }
        }

        $return_string .=
                    '</div>
                </div>
            </section>';

        if (isset($data['color']) && $data['color'] != '') {
            $return_string .= 
                '<style>
                    #pxp-services-1-' . esc_html($section_id) . ' .pxp-services-1-item-icon::before {
                        background-color: ' . esc_html($data['color']) . ';
                    }
                </style>';
        }

        return $return_string;
    }
endif;
?>