<?php
/**
 * Contact form block
 */
if (!function_exists('jobster_contact_form_block')): 
    function jobster_contact_form_block() {
        wp_register_script(
            'jobster-contact-form-block',
            plugins_url('js/contact-form.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-contact-form-block-editor',
            plugins_url('css/contact-form.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/contact-form', array(
            'editor_script' => 'jobster-contact-form-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_contact_form_block_render'
        ));
    }
endif;
add_action('init', 'jobster_contact_form_block');

if (!function_exists('jobster_contact_form_block_render')): 
    function jobster_contact_form_block_render($attrs, $content = null) {
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

        $nonce_field = wp_nonce_field(
            'contact_form_block_ajax_nonce',
            'pxp-contact-form-block-security',
            true,
            false
        );

        $return_string .=
                    '<div class="row justify-content-center ' . esc_attr($animation) . '">
                        <div class="col-lg-6 col-xxl-4">
                            <div class="pxp-contact-form-block-form">
                                <div class="mb-4 pxp-contact-form-block-response"></div>
                                <form class="mt-4">
                                    <input 
                                        type="hidden" 
                                        id="pxp-contact-form-block-company-email" 
                                        value="' . esc_attr($data['email']) . '"
                                    >
                                    <div class="mb-3">
                                        <label 
                                            for="pxp-contact-form-block-name" 
                                            class="form-label"
                                        >
                                            ' . esc_html__('Name', 'jobster') . '
                                        </label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="pxp-contact-form-block-name" 
                                            placeholder="' . esc_attr__('Enter your name', 'jobster') . '"
                                        >
                                    </div>
                                    <div class="mb-3">
                                        <label 
                                            for="pxp-contact-form-block-email" 
                                            class="form-label"
                                        >
                                            ' . esc_html__('Email', 'jobster') . '
                                        </label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="pxp-contact-form-block-email" 
                                            placeholder="' . esc_attr__('Enter your email address', 'jobster') . '"
                                        >
                                    </div>
                                    <div class="mb-3">
                                        <label 
                                            for="pxp-contact-form-block-message" 
                                            class="form-label"
                                        >
                                            ' . esc_html__('Message', 'jobster') . '
                                        </label>
                                        <textarea 
                                            class="form-control" 
                                            id="pxp-contact-form-block-message" 
                                            placeholder="' . esc_attr__('Type your message here...', 'jobster') . '"
                                        ></textarea>
                                    </div>
                                    ' . $nonce_field . '
                                    <a 
                                        href="javascript:void(0);" 
                                        class="btn rounded-pill d-block pxp-contact-form-block-btn"
                                    >
                                        <span class="pxp-contact-form-block-btn-text">
                                            ' . esc_html__('Send Message', 'jobster') . '
                                        </span>
                                        <span class="pxp-contact-form-block-btn-loading pxp-btn-loading">
                                            <img 
                                                src="' . esc_url(JOBSTER_LOCATION . '/images/loader-light.svg') . '" 
                                                class="pxp-btn-loader" 
                                                alt="..."
                                            >
                                        </span>
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>';

        return $return_string;
    }
endif;
?>