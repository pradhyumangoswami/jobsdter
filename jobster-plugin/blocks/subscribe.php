<?php
/**
 * Subscribe block
 */
if (!function_exists('jobster_subscribe_block')): 
    function jobster_subscribe_block() {
        wp_register_script(
            'jobster-subscribe-block',
            plugins_url('js/subscribe.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-subscribe-block-editor',
            plugins_url('css/subscribe.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/subscribe', array(
            'editor_script' => 'jobster-subscribe-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_subscribe_block_render'
        ));
    }
endif;
add_action('init', 'jobster_subscribe_block');

if (!function_exists('jobster_subscribe_block_render')): 
    function jobster_subscribe_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';

        $nonce_field = wp_nonce_field(
            'subscribe_ajax_nonce',
            'pxp-subscribe-block-security',
            true,
            false
        );

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
                    '<div class="row mt-4 mt-md-5 justify-content-center">
                        <div class="col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                            <div class="pxp-subscribe-1-container ' . esc_attr($animation) . '">';
        if (isset($data['image']) && $data['image'] != '') {
            $image = wp_get_attachment_image_src($data['image'], 'pxp-full');

            if (is_array($image)) {
                $return_string .=
                                '<div class="pxp-subscribe-1-image">
                                    <img 
                                        src="' . esc_url($image[0]) . '" 
                                        alt="' . esc_attr($data['title']) . '"
                                    >
                                </div>';
            }
        }
        $return_string .=
                                '<div class="pxp-subscribe-1-form">
                                    <form>
                                        ' . $nonce_field . '
                                        <div class="pxp-subscribe-1-form-response"></div>
                                        <div class="input-group">
                                            <input 
                                                type="email" 
                                                class="form-control" 
                                                id="pxp-subscribe-1-form-email" 
                                                placeholder="' . esc_attr__('Enter your email...', 'jobster') . '"
                                            >
                                            <button 
                                                class="btn btn-primary pxp-subscribe-1-form-btn" 
                                                type="button"
                                            >
                                                <span class="pxp-subscribe-1-form-btn-text">
                                                    ' . esc_html__('Subscribe', 'jobster') . '
                                                </span>
                                                <span class="pxp-subscribe-1-form-btn-loading pxp-btn-loading">
                                                    <img 
                                                        src="' . esc_url(JOBSTER_LOCATION . '/images/loader-light.svg') . '" 
                                                        class="pxp-btn-loader" 
                                                        alt="..."
                                                    >
                                                </span>
                                                <span class="fa fa-angle-right"></span>
                                            </button>
                                        </div>
                                    </form>
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