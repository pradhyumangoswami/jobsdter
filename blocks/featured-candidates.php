<?php
/**
 * Featured candidates block
 */
if (!function_exists('jobster_featured_candidates_block')): 
    function jobster_featured_candidates_block() {
        wp_register_script(
            'jobster-featured-candidates-block',
            plugins_url('js/featured-candidates.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-featured-candidates-block-editor',
            plugins_url('css/featured-candidates.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/featured-candidates', array(
            'editor_script' => 'jobster-featured-candidates-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_featured_candidates_block_render'
        ));
    }
endif;
add_action('init', 'jobster_featured_candidates_block');

if (!function_exists('jobster_featured_candidates_block_render')): 
    function jobster_featured_candidates_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $number =   isset($data['number']) && is_numeric($data['number'])
                    ? $data['number']
                    : '6';
        $location = isset($data['location']) && is_numeric($data['location'])
                    ? $data['location']
                    : '0';
        $industry = isset($data['industry']) && is_numeric($data['industry'])
                    ? $data['industry']
                    : '0';
        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';
        $card_design =  isset($data['design']) && $data['design'] == 'b'
                        ? 'pxp-has-border'
                        : 'pxp-has-shadow';

        $section_padding = '';
        $bg_color = 'transparent';
        $margin = 'mt-100';
        if (isset($data['bg']) && $data['bg'] != '') {
            $section_padding = 'pt-100 pb-100';
            $bg_color = $data['bg'];

            if (isset($data['margin']) && $data['margin'] == 'n') {
                $margin = '';
            }
        }

        $align_text = '';
        $align_cards = '';
        if (isset($data['align']) && $data['align'] == 'c') {
            $align_text = 'text-center';
            $align_cards = 'justify-content-center';
        }

        $search_candidates_url = jobster_get_page_link('candidate-search.php');

        $args = array(
            'numberposts'      => $number,
            'post_type'        => 'candidate',
            'order'            => 'DESC',
            'meta_key'         => 'candidate_featured',
            'meta_value'       => '1',
            'suppress_filters' => false,
            'post_status'      => 'publish'
        );

        if ($location != '0' && $industry != '0') {
            $args['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'candidate_location',
                    'field'    => 'term_id',
                    'terms'    => $location,
                ),
                array(
                    'taxonomy' => 'candidate_industry',
                    'field'    => 'term_id',
                    'terms'    => $industry,
                ),
            );
        } else if ($location != '0') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'candidate_location',
                    'field'    => 'term_id',
                    'terms'    => $location,
                ),
            );
        } else if ($industry != '0') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'candidate_industry',
                    'field'    => 'term_id',
                    'terms'    => $industry,
                ),
            );
        }

        $posts = wp_get_recent_posts($args);

        $return_string = 
            '<section 
                class="' . esc_attr($margin) . ' ' . esc_attr($section_padding) . '" 
                style="background-color: ' . esc_attr($bg_color) . '"
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
        foreach($posts as $post) : 
            $candidate_name = get_the_title($post['ID']);
            $candidate_link = get_permalink($post['ID']);
            $candidate_title = get_post_meta(
                $post['ID'],
                'candidate_title',
                true
            );

            $location = wp_get_post_terms(
                $post['ID'], 'candidate_location'
            );

            $candidate_photo_val = get_post_meta(
                $post['ID'],
                'candidate_photo',
                true
            );
            $candidate_photo = wp_get_attachment_image_src(
                $candidate_photo_val,
                'pxp-thmb'
            );

            $return_string .=
                        '<div class="col-md-6 col-xl-4 col-xxl-3 pxp-candidates-card-1-container">
                            <div class="pxp-candidates-card-1 text-center ' . esc_attr($card_design) . '">
                                <div class="pxp-candidates-card-1-top">';
            if (is_array($candidate_photo)) {
                $return_string .=
                                    '<div 
                                        class="pxp-candidates-card-1-avatar pxp-cover" 
                                        style="background-image: url(' . esc_url($candidate_photo[0]) . ');"
                                    ></div>';
            } else {
                $return_string .=
                                    '<div class="pxp-candidates-card-1-avatar pxp-no-img">
                                        ' . esc_html($candidate_name[0]) . '
                                    </div>';
            }
            $return_string .=
                                    '<div class="pxp-candidates-card-1-name">
                                        ' . esc_html($candidate_name) . '
                                    </div>
                                    <div class="pxp-candidates-card-1-title">
                                        ' . esc_html($candidate_title) . '
                                    </div>';
            if ($location) {
                $return_string .=
                                    '<div class="pxp-candidates-card-1-location">
                                        <span class="fa fa-globe"></span>
                                        ' . esc_html($location[0]->name) . '
                                    </div>';
            }
            $return_string .=
                                '</div>
                                <div class="pxp-candidates-card-1-bottom">
                                    <div class="pxp-candidates-card-1-cta">
                                        <a href="' . esc_url($candidate_link) . '">
                                            ' . esc_html__('View profile', 'jobster') . '
                                            <span class="fa fa-angle-right"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>';
        endforeach;
        $return_string .=
                    '</div>
                    <div class="mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_text) . '">
                        <a 
                            href="' . esc_url($search_candidates_url) . '" 
                            class="btn rounded-pill pxp-section-cta"
                        >
                            ' . esc_html__('All Candidates', 'jobster') . '
                            <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
                </div>
            </section>';


        return $return_string;
    }
endif;
?>