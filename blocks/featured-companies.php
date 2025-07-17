<?php
/**
 * Featured companies block
 */
if (!function_exists('jobster_featured_companies_block')): 
    function jobster_featured_companies_block() {
        wp_register_script(
            'jobster-featured-companies-block',
            plugins_url('js/featured-companies.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-featured-companies-block-editor',
            plugins_url('css/featured-companies.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/featured-companies', array(
            'editor_script' => 'jobster-featured-companies-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_featured_companies_block_render'
        ));
    }
endif;
add_action('init', 'jobster_featured_companies_block');

if (!function_exists('jobster_featured_companies_block_render')): 
    function jobster_featured_companies_block_render($attrs, $content = null) {
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

        $search_companies_url = jobster_get_page_link('company-search.php');

        $args = array(
            'numberposts'      => $number,
            'post_type'        => 'company',
            'order'            => 'DESC',
            'meta_key'         => 'company_featured',
            'meta_value'       => '1',
            'suppress_filters' => false,
            'post_status'      => 'publish'
        );

        if ($location != '0' && $industry != '0') {
            $args['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'company_location',
                    'field'    => 'term_id',
                    'terms'    => $location,
                ),
                array(
                    'taxonomy' => 'company_industry',
                    'field'    => 'term_id',
                    'terms'    => $industry,
                ),
            );
        } else if ($location != '0') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'company_location',
                    'field'    => 'term_id',
                    'terms'    => $location,
                ),
            );
        } else if ($industry != '0') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'company_industry',
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
            $company_name = get_the_title($post['ID']);
            $company_link = get_permalink($post['ID']);
            $excerpt = get_the_excerpt($post['ID']);

            $company_logo_val = get_post_meta(
                $post['ID'],
                'company_logo',
                true
            );
            $company_logo = wp_get_attachment_image_src(
                $company_logo_val,
                'pxp-thmb'
            );

            $return_string .=
                        '<div class="col-md-6 col-xl-4 col-xxl-3 pxp-companies-card-1-container">
                            <div class="pxp-companies-card-1 ' . esc_attr($card_design) . '">
                                <div class="pxp-companies-card-1-top">';
            if (is_array($company_logo)) {
                $return_string .=
                                    '<a 
                                        href="' . esc_url($company_link) . '" 
                                        class="pxp-companies-card-1-company-logo" 
                                        style="background-image: url(' . esc_url($company_logo[0]) . ');"
                                    ></a>';
            } else {
                $return_string .=
                                    '<a 
                                        href="' . esc_url($company_link) . '" 
                                        class="pxp-companies-card-1-company-logo pxp-no-img"
                                    >
                                        ' . esc_html($company_name[0]) . '
                                    </a>';
            }
            $return_string .=
                                    '<a 
                                        href="' . esc_url($company_link) . '" 
                                        class="pxp-companies-card-1-company-name"
                                    >
                                        ' . esc_html($company_name) . '
                                    </a>
                                    <div class="pxp-companies-card-1-company-description pxp-text-light">
                                        ' . esc_html($excerpt) . '
                                    </div>
                                </div>
                                <div class="pxp-companies-card-1-bottom">
                                    <a 
                                        href="' . esc_url($company_link) . '" 
                                        class="pxp-companies-card-1-company-jobs"
                                    >
                                        ' . esc_html(
                                            jobster_get_active_jobs_no_by_company_id($post['ID'])
                                        ) . ' ' .  esc_html__('jobs', 'jobster') . '
                                    </a>
                                </div>
                            </div>
                        </div>';
        endforeach;
        $return_string .=
                    '</div>
                    <div class="mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_text) . '">
                        <a 
                            href="' . esc_url($search_companies_url) . '" 
                            class="btn rounded-pill pxp-section-cta"
                        >
                            ' . esc_html__('All Companies', 'jobster') . '
                            <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
                </div>
            </section>';


        return $return_string;
    }
endif;
?>