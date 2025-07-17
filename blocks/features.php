<?php
/**
 * Features block
 */
if (!function_exists('jobster_features_block')): 
    function jobster_features_block() {
        wp_register_script(
            'jobster-features-block',
            plugins_url('js/features.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-features-block-editor',
            plugins_url('css/features.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/features', array(
            'editor_script' => 'jobster-features-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_features_block_render'
        ));
    }
endif;
add_action('init', 'jobster_features_block');

if (!function_exists('jobster_features_block_render')): 
    function jobster_features_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $animation_right = '';
        $animation_top = '';
        $animation_bounce = '';
        if (isset($data['animation']) && $data['animation'] == 'e') {
            $animation_right = 'pxp-animate-in pxp-animate-in-right';
            $animation_top = 'pxp-animate-in pxp-animate-in-top';
            $animation_bounce = 'pxp-animate-in pxp-animate-bounce';
        }

        $return_string = 
            '<section class="mt-100">
                <div class="pxp-container">
                    <div class="row justify-content-between align-items-center mt-4 mt-md-5">
                        <div class="col-lg-6 col-xxl-5">
                            <div class="pxp-info-fig ' . esc_attr($animation_right) . '">';
        if (isset($data['image']) && $data['image'] != '') {
            $image = wp_get_attachment_image_src($data['image'], 'full');

            if (is_array($image)) {
                $return_string .=
                                '<div 
                                    class="pxp-info-fig-image pxp-cover" 
                                    style="background-image: url(' . esc_url($image[0]) . ');"
                                ></div>';
            } else {
                $return_string .=
                                '<div class="pxp-info-fig-image"></div>';
            }
        } else {
            $return_string .=
                                '<div class="pxp-info-fig-image"></div>';
        }
        $return_string .=
                                '<div class="pxp-info-stats">';
        if (isset($data['icard_text_1']) && $data['icard_text_1'] != '') {
            $return_string .=
                                    '<div class="pxp-info-stats-item ' . esc_attr($animation_bounce) . '">';
            if (isset($data['icard_label_1']) && $data['icard_label_1'] != '') {
                $return_string .=
                                        '<div class="pxp-info-stats-item-number">';
                if (isset($data['icard_no_1']) && $data['icard_no_1'] != '') {
                    $return_string .=       esc_html($data['icard_no_1']);
                }
                $return_string .=
                                            '<span>' . esc_html($data['icard_label_1']) . '</span>
                                        </div>';
            }
            $return_string .=
                                        '<div class="pxp-info-stats-item-description">
                                            ' . esc_html($data['icard_text_1']) . '
                                        </div>
                                    </div>';
        }
        if (isset($data['icard_text_2']) && $data['icard_text_2'] != '') {
            $return_string .=
                                    '<div class="pxp-info-stats-item ' . esc_attr($animation_bounce) . '">';
            if (isset($data['icard_label_2']) && $data['icard_label_2'] != '') {
                $return_string .=
                                        '<div class="pxp-info-stats-item-number">';
                if (isset($data['icard_no_2']) && $data['icard_no_2'] != '') {
                    $return_string .=       esc_html($data['icard_no_2']);
                }
                $return_string .=
                                            '<span>' . esc_html($data['icard_label_2']) . '</span>
                                        </div>';
            }
            $return_string .=
                                        '<div class="pxp-info-stats-item-description">
                                            ' . esc_html($data['icard_text_2']) . '
                                        </div>
                                    </div>';
        }
        if (isset($data['icard_text_3']) && $data['icard_text_3'] != '') {
            $return_string .=
                                    '<div class="pxp-info-stats-item ' . esc_attr($animation_bounce) . '">';
            if (isset($data['icard_label_3']) && $data['icard_label_3'] != '') {
                $return_string .=
                                        '<div class="pxp-info-stats-item-number">';
                if (isset($data['icard_no_3']) && $data['icard_no_3'] != '') {
                    $return_string .=       esc_html($data['icard_no_3']);
                }
                $return_string .=
                                            '<span>' . esc_html($data['icard_label_3']) . '</span>
                                        </div>';
            }
            $return_string .=
                                        '<div class="pxp-info-stats-item-description">
                                            ' . esc_html($data['icard_text_3']) . '
                                        </div>
                                    </div>';
        }
        $return_string .=
                                '</div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-xxl-6">
                            <div class="pxp-info-caption mt-4 mt-sm-5 mt-lg-0 ' . esc_attr($animation_top) . '">';
        if (isset($data['title']) && $data['title'] != '') {
            $return_string .=
                                '<h2 class="pxp-section-h2">
                                    ' . esc_html($data['title']) . '
                                </h2>';
        }
        if (isset($data['text']) && $data['text'] != '') {
            $return_string .=
                                '<p class="pxp-text-light">
                                    ' . esc_html($data['text']) . '
                                </p>';
        }
        if (isset($data['features']) && is_array($data['features'])) {
            $return_string .=
                                '<div class="pxp-info-caption-list">';
            foreach ($data['features'] as $feature) {
                $return_string .=
                                    '<div class="pxp-info-caption-list-item">
                                        <img 
                                            src="' . esc_url(JOBSTER_PLUGIN_PATH . 'images/check.svg') . '" 
                                            alt="-"
                                        >
                                        <span>' . esc_html($feature['text']) . '</span>
                                    </div>';
            }
            $return_string .=
                                '</div>';
        }
        if (isset($data['cta']) && $data['cta'] != '') {
            $return_string .=
                                '<div class="pxp-info-caption-cta">
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