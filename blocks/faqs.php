<?php
/**
 * FAQs block
 */
if (!function_exists('jobster_faqs_block')): 
    function jobster_faqs_block() {
        wp_register_script(
            'jobster-faqs-block',
            plugins_url('js/faqs.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-faqs-block-editor',
            plugins_url('css/faqs.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/faqs', array(
            'editor_script' => 'jobster-faqs-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_faqs_block_render'
        ));
    }
endif;
add_action('init', 'jobster_faqs_block');

if (!function_exists('jobster_faqs_block_render')): 
    function jobster_faqs_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';

        $align_text = '';
        $align_items = '';
        if (isset($data['align']) && $data['align'] == 'c') {
            $align_text = 'text-center';
            $align_items = 'justify-content-center';
        }

        $return_string = 
            '<section class="pt-100">
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
                    '<div class="row mt-4 mt-lg-5 ' . esc_attr($align_items) . '">
                        <div class="col-xxl-7">
                            <div 
                                class="accordion pxp-faqs-accordion ' . esc_attr($animation) . '" 
                                id="pxpFAQsAccordion"
                            >';
        if (isset($data['faqs']) && is_array($data['faqs'])) {
            $count_q = 1;
            foreach ($data['faqs'] as $faq) {
                $return_string .=
                                '<div class="accordion-item">
                                    <h3 
                                        class="accordion-header" 
                                        id="pxpFAQsHeader' . esc_attr($count_q) . '"
                                    >
                                        <button 
                                            class="accordion-button collapsed" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#pxpCollapseFAQs' . esc_attr($count_q) . '" 
                                            aria-expanded="false" 
                                            aria-controls="pxpCollapseFAQs' . esc_attr($count_q) . '"
                                        >
                                            ' . esc_html($faq['question']) . '
                                        </button>
                                    </h3>
                                    <div 
                                        id="pxpCollapseFAQs' . esc_attr($count_q) . '" 
                                        class="accordion-collapse collapse" 
                                        aria-labelledby="pxpFAQsHeader' . esc_attr($count_q) . '" 
                                        data-bs-parent="#pxpFAQsAccordion"
                                    >
                                        <div class="accordion-body">
                                            ' . esc_html($faq['answer']) . '
                                        </div>
                                    </div>
                                </div>';
                $count_q++;
            }
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