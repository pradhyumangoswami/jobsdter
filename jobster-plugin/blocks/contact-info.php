<?php
/**
 * Contact info block
 */
if (!function_exists('jobster_contact_info_block')): 
    function jobster_contact_info_block() {
        wp_register_script(
            'jobster-contact-info-block',
            plugins_url('js/contact-info.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-contact-info-block-editor',
            plugins_url('css/contact-info.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/contact-info', array(
            'editor_script' => 'jobster-contact-info-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_contact_info_block_render'
        ));
    }
endif;
add_action('init', 'jobster_contact_info_block');

if (!function_exists('jobster_contact_info_block_render')): 
    function jobster_contact_info_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';

        $return_string = 
            '<section class="pt-100">
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
                    '<div class="row mt-4 mt-md-5 justify-content-center ' . esc_attr($animation) . '">';
        if (isset($data['location']) && $data['location'] != '') {
            $return_string .=
                        '<div class="col-lg-4 col-xxl-3 pxp-contact-card-1-container">
                            <div class="pxp-contact-card-1">
                                <div class="pxp-contact-card-1-icon-container">
                                    <div class="pxp-contact-card-1-icon">
                                        <span class="fa fa-globe"></span>
                                    </div>
                                </div>
                                <div class="pxp-contact-card-1-title">
                                    ' . esc_html($data['location']) . '
                                </div>
                            </div>
                        </div>';
        }
        if (isset($data['phone']) && $data['phone'] != '') {
            $return_string .=
                        '<div class="col-lg-4 col-xxl-3 pxp-contact-card-1-container">
                            <a 
                                href="tel:' . esc_attr($data['phone']) . '" 
                                class="pxp-contact-card-1"
                            >
                                <div class="pxp-contact-card-1-icon-container">
                                    <div class="pxp-contact-card-1-icon">
                                        <span class="fa fa-phone"></span>
                                    </div>
                                </div>
                                <div class="pxp-contact-card-1-title">
                                    ' . esc_html($data['phone']) . '
                                </div>
                            </a>
                        </div>';
        }
        if (isset($data['email']) && $data['email'] != '') {
            $return_string .=
                        '<div class="col-lg-4 col-xxl-3 pxp-contact-card-1-container">
                            <a 
                                href="mailto:' . esc_attr($data['email']) . '" 
                                class="pxp-contact-card-1"
                            >
                                <div class="pxp-contact-card-1-icon-container">
                                    <div class="pxp-contact-card-1-icon">
                                        <span class="fa fa-envelope-o"></span>
                                    </div>
                                </div>
                                <div class="pxp-contact-card-1-title">
                                    ' . esc_html($data['email']) . '
                                </div>
                            </a>
                        </div>';
        }
        $return_string .=
                    '</div>
                </div>
            </section>';

        return $return_string;
    }
endif;
?>