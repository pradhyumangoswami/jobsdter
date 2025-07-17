<?php
/**
 * Search jobs block
 */
if (!function_exists('jobster_search_jobs_block')): 
    function jobster_search_jobs_block() {
        wp_register_script(
            'jobster-search-jobs-block',
            plugins_url('js/search-jobs.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-search-jobs-block-editor',
            plugins_url('css/search-jobs.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/search-jobs', array(
            'editor_script' => 'jobster-search-jobs-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_search_jobs_block_render'
        ));
    }
endif;
add_action('init', 'jobster_search_jobs_block');

if (!function_exists('jobster_search_jobs_block_render')): 
    function jobster_search_jobs_block_render($attrs, $content = null) {
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
        $system = isset($data['system']) && $data['system'] == 'c' ? 'c' : 'd';

        $section_padding = '';
        $form_css = 'pxp-has-border';
        if (isset($data['image']) && $data['image'] != '') {
            $section_padding = 'pt-100 pb-100';
            $form_css = '';
        }

        $margin = 'mt-100';
        if (isset($data['margin']) && $data['margin'] == 'n') {
            $margin = 'mt-5 mt-xl-0';
        }

        $return_string = 
            '<section class="' . esc_attr($margin) . '">
                <div class="pxp-container">';
        if (isset($data['image']) && $data['image'] != '') {
            $image = wp_get_attachment_image_src($data['image'], 'full');

            if (is_array($image)) {
                $return_string .=
                    '<div 
                        class="pxp-search-jobs-img pxp-cover ' . esc_attr($section_padding) . ' ' . esc_attr($animation) . ' ' . esc_attr($type) . '" 
                        style="background-image: url(' . esc_url($image[0]) . ');"
                    >';
            } else {
                $return_string .=
                    '<div class="pxp-search-jobs-img pxp-no-img ' . esc_attr($animation) . ' ' . esc_attr($type) . '">';
            }
        } else {
            $return_string .=
                    '<div class="pxp-search-jobs-img pxp-no-img ' . esc_attr($animation) . ' ' . esc_attr($type) . '">';
        }
        $return_string .=
                        '<h2 class="pxp-section-h2 text-center">
                            ' . esc_html($data['title']) . '
                        </h2>
                        <p class="pxp-text-light text-center">
                            ' . esc_html($data['text']) . '
                        </p>';
        if ($system) {
            if ($system == 'c') {
                if (function_exists('jobster_get_careerjet_section_search_jobs_form')) {
                    $return_string .= jobster_get_careerjet_section_search_jobs_form($form_css);
                }
            } else {
                if (function_exists('jobster_get_section_search_jobs_form')) {
                    $return_string .= jobster_get_section_search_jobs_form($form_css);
                }
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