<?php
/**
 * Job categories block
 */
if (!function_exists('jobster_job_categories_block')): 
    function jobster_job_categories_block() {
        wp_register_script(
            'jobster-job-categories-block',
            plugins_url('js/job-categories.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-job-categories-block-editor',
            plugins_url('css/job-categories.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/job-categories', array(
            'editor_script' => 'jobster-job-categories-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_job_categories_block_render'
        ));
    }
endif;
add_action('init', 'jobster_job_categories_block');

if (!function_exists('jobster_job_categories_block_render')): 
    function jobster_job_categories_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                    ? 'pxp-animate-in pxp-animate-in-top'
                    : '';
        $number = isset($data['number']) ? $data['number'] : '6';
        $exclude = isset($data['exclude']) ? $data['exclude'] : 'n';
        $sort = isset($data['sort']) ? $data['sort'] : 'n';
        $target = isset($data['target']) ? $data['target'] : 'j';
        $v_card =   isset($data['icon']) && $data['icon'] == 'o'
                    ? 'pxp-categories-card-1'
                    : 'pxp-categories-card-2';
        $icon_bg =  isset($data['icon']) && $data['icon'] == 'o'
                    ? 'pxp-has-bg'
                    : '';

        $align_text = '';
        $align_cards = '';
        if (isset($data['align']) && $data['align'] == 'c') {
            $align_text = 'text-center';
            $align_cards = 'justify-content-center';
        }

        $card_align = '';
        if (isset($data['card_align']) && $data['card_align'] == 'c') {
            $card_align = 'text-center';
        }

        $search_jobs_url = jobster_get_page_link('job-search.php');
        $job_categories_url = jobster_get_page_link('job-categories.php');

        $category_tax = array( 
            'job_category'
        );
        $category_args = array(
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC'
        );

        $cta_target = $target == 'c' ? $job_categories_url : $search_jobs_url;

        $category_terms = get_terms(
            $category_tax,
            $category_args
        );

        foreach ($category_terms as $i => $category_term) {
            $category_term->jobs_count = jobster_filter_form_count_jobs_by_term(
                'job_category',
                $category_term->term_id
            );
            if ($exclude == 'y' && $category_term->jobs_count === 0) {
                unset($category_terms[$i]);
            }
        }

        if ($sort === 'j') {
            usort($category_terms, function($a, $b) {
                if ($a->jobs_count == $b->jobs_count) {
                    return 0;
                }
                return ($a->jobs_count < $b->jobs_count) ? 1 : -1;
            });
        }

        $return_string = '';

        switch($data['layout']) {
            case 'g':
                $return_string .=
                    '<section class="mt-100">
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

                $categories_count = 0;
                foreach ($category_terms as $category_term) {
                    if ($categories_count < intval($number)) {
                        $category_link = add_query_arg(
                            'category',
                            $category_term->term_id,
                            $search_jobs_url
                        );
                        $category_icon = get_term_meta(
                            $category_term->term_id,
                            'job_category_icon',
                            true
                        );

                        if (isset($data['card'])) {
                            if ($data['card'] == 'h') {
                                $return_string .=
                                            '<div class="col-lg-6 col-xxl-4 pxp-categories-card-3-container">
                                                <a 
                                                    href="' . esc_url($category_link) . '" 
                                                    class="pxp-categories-card-3"
                                                >';
                                if (!empty($category_icon)) {
                                    $category_icon_type = get_term_meta(
                                        $category_term->term_id,
                                        'job_category_icon_type',
                                        true
                                    );
                                    if ($category_icon_type == 'image') {
                                        $icon_image = wp_get_attachment_image_src(
                                            $category_icon,
                                            'pxp-icon'
                                        );
                                        if (is_array($icon_image)) {
                                            $return_string .= 
                                                    '<div class="pxp-categories-card-3-icon-image">
                                                        <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                                    </div>';
                                        } else {
                                            $return_string .= 
                                                    '<div class="pxp-categories-card-3-icon">
                                                        <span class="fa fa-folder-o"></span>
                                                    </div>';
                                        }
                                    } else {
                                        $return_string .= 
                                                    '<div class="pxp-categories-card-3-icon">
                                                        <span class="' . esc_attr($category_icon) . '"></span>
                                                    </div>';
                                    }
                                } else {
                                    $return_string .= 
                                                    '<div class="pxp-categories-card-3-icon">
                                                        <span class="fa fa-folder-o"></span>
                                                    </div>';
                                }
                                $return_string .= 
                                                    '<div class="pxp-categories-card-3-text">
                                                        <div class="pxp-categories-card-3-title">
                                                            ' . esc_html($category_term->name) . '
                                                        </div>
                                                        <div class="pxp-categories-card-3-subtitle">
                                                            ' . esc_html($category_term->jobs_count) . ' '
                                                            . esc_html__('open positions', 'jobster') . '
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>';
                            } else if ($data['card'] == 'b') {
                                $return_string .= 
                                            '<div class="col-12 col-md-4 col-lg-3 col-xxl-2 pxp-categories-card-4-container">
                                                <a 
                                                    href="' . esc_url($category_link) . '" 
                                                    class="pxp-categories-card-4"
                                                >
                                                    <div class="pxp-categories-card-4-icon-container ' . esc_attr($card_align) . '">
                                                        <div>
                                                            <div class="pxp-categories-card-4-subtitle">
                                                                ' . esc_html($category_term->jobs_count) . ' '
                                                                . esc_html__('open positions', 'jobster') . '
                                                            </div>
                                                        </div>';
                                if (!empty($category_icon)) {
                                    $category_icon_type = get_term_meta(
                                        $category_term->term_id,
                                        'job_category_icon_type',
                                        true
                                    );
                                    if ($category_icon_type == 'image') {
                                        $icon_image = wp_get_attachment_image_src(
                                            $category_icon,
                                            'pxp-icon'
                                        );
                                        if (is_array($icon_image)) {
                                            $return_string .= 
                                                        '<div class="pxp-categories-card-4-icon-image ' . esc_attr($icon_bg) . '">
                                                            <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                                        </div>';
                                        } else {
                                            $return_string .= 
                                                        '<div class="pxp-categories-card-4-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                        }
                                    } else {
                                        $return_string .= 
                                                        '<div class="pxp-categories-card-4-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="' . esc_attr($category_icon) . '"></span>
                                                        </div>';
                                    }
                                } else {
                                    $return_string .= 
                                                        '<div class="pxp-categories-card-4-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                }
                                $return_string .= 
                                                    '</div>
                                                    <div class="pxp-categories-card-4-title ' . esc_attr($card_align) . '">
                                                        ' . esc_html($category_term->name) . '
                                                    </div>
                                                </a>
                                            </div>';
                            } else if ($data['card'] == 't') {
                                $return_string .= 
                                            '<div class="col-12 col-md-4 col-lg-3 col-xxl-2 pxp-categories-card-5-container">
                                                <a 
                                                    href="' . esc_url($category_link) . '" 
                                                    class="pxp-categories-card-5"
                                                >
                                                    <div class="pxp-categories-card-5-icon-container ' . esc_attr($card_align) . '">';
                                if (!empty($category_icon)) {
                                    $category_icon_type = get_term_meta(
                                        $category_term->term_id,
                                        'job_category_icon_type',
                                        true
                                    );
                                    if ($category_icon_type == 'image') {
                                        $icon_image = wp_get_attachment_image_src(
                                            $category_icon,
                                            'pxp-icon'
                                        );
                                        if (is_array($icon_image)) {
                                            $return_string .=
                                                        '<div class="pxp-categories-card-5-icon-image ' . esc_attr($icon_bg) . '">
                                                            <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                                        </div>';
                                        } else {
                                            $return_string .=
                                                        '<div class="pxp-categories-card-5-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                        }
                                    } else {
                                        $return_string .=
                                                        '<div class="pxp-categories-card-5-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="' . esc_attr($category_icon) . '"></span>
                                                        </div>';
                                    }
                                } else {
                                    $return_string .= 
                                                        '<div class="pxp-categories-card-5-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                }
                                $return_string .= 
                                                    '</div>
                                                    <div class="pxp-categories-card-5-title ' . esc_attr($card_align) . '">
                                                        ' . esc_html($category_term->name) . '
                                                    </div>
                                                    <div class="pxp-categories-card-5-subtitle ' . esc_attr($card_align) . '">
                                                        ' . esc_html($category_term->jobs_count) . ' '
                                                        . esc_html__('open positions', 'jobster') . '
                                                    </div>
                                                </a>
                                            </div>';
                            } else {
                                $return_string .= 
                                            '<div class="col-12 col-md-4 col-lg-3 col-xxl-2 ' . esc_attr($v_card) . '-container">
                                                <a 
                                                    href="' . esc_url($category_link) . '" 
                                                    class="' . esc_attr($v_card) . '"
                                                >
                                                    <div class="' . esc_attr($v_card) . '-icon-container">';
                                if (!empty($category_icon)) {
                                    $category_icon_type = get_term_meta(
                                        $category_term->term_id,
                                        'job_category_icon_type',
                                        true
                                    );
                                    if ($category_icon_type == 'image') {
                                        $icon_image = wp_get_attachment_image_src(
                                            $category_icon,
                                            'pxp-icon'
                                        );
                                        if (is_array($icon_image)) {
                                            $return_string .= 
                                                        '<div class="' . esc_attr($v_card) . '-icon-image">
                                                            <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                                        </div>';
                                        } else {
                                            $return_string .= 
                                                        '<div class="' . esc_attr($v_card) . '-icon">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                        }
                                    } else {
                                        $return_string .= 
                                                        '<div class="' . esc_attr($v_card) . '-icon">
                                                            <span class="' . esc_attr($category_icon) . '"></span>
                                                        </div>';
                                    }
                                } else {
                                    $return_string .= 
                                                        '<div class="' . esc_attr($v_card) . '-icon">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                }
                                $return_string .= 
                                                    '</div>
                                                    <div class="' . esc_attr($v_card) . '-title">
                                                        ' . esc_html($category_term->name) . '
                                                    </div>
                                                    <div class="' . esc_attr($v_card) . '-subtitle">
                                                        ' . esc_html($category_term->jobs_count) . ' '
                                                        . esc_html__('open positions', 'jobster') . '
                                                    </div>
                                                </a>
                                            </div>';
                            }
                        } else {
                            $return_string .= 
                                        '<div class="col-12 col-md-4 col-lg-3 col-xxl-2 ' . esc_attr($v_card) . '-container">
                                            <a 
                                                href="' . esc_url($category_link) . '" 
                                                class="' . esc_attr($v_card) . '"
                                            >
                                                <div class="' . esc_attr($v_card) . '-icon-container">';
                            if (!empty($category_icon)) {
                                $category_icon_type = get_term_meta(
                                    $category_term->term_id,
                                    'job_category_icon_type',
                                    true
                                );
                                if ($category_icon_type == 'image') {
                                    $icon_image = wp_get_attachment_image_src(
                                        $category_icon,
                                        'pxp-icon'
                                    );
                                    if (is_array($icon_image)) {
                                        $return_string .= 
                                                    '<div class="' . esc_attr($v_card) . '-icon-image">
                                                        <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                                    </div>';
                                    } else {
                                        $return_string .= 
                                                    '<div class="' . esc_attr($v_card) . '-icon">
                                                        <span class="fa fa-folder-o"></span>
                                                    </div>';
                                    }
                                } else {
                                    $return_string .= 
                                                    '<div class="' . esc_attr($v_card) . '-icon">
                                                        <span class="' . esc_attr($category_icon) . '"></span>
                                                    </div>';
                                }
                            } else {
                                $return_string .= 
                                                    '<div class="' . esc_attr($v_card) . '-icon">
                                                        <span class="fa fa-folder-o"></span>
                                                    </div>';
                            }
                            $return_string .= 
                                                '</div>
                                                <div class="' . esc_attr($v_card) . '-title">
                                                    ' . esc_html($category_term->name) . '
                                                </div>
                                                <div class="' . esc_attr($v_card) . '-subtitle">
                                                    ' . esc_html($category_term->jobs_count) . ' '
                                                    . esc_html__('open positions', 'jobster') . '
                                                </div>
                                            </a>
                                        </div>';
                        }
                    }
                    $categories_count++;
                }

                $return_string .=
                            '</div>
                            <div class="mt-4 mt-md-5 ' . esc_attr($align_text) . ' ' . esc_attr($animation) . '">
                                <a 
                                    href="' . esc_url($cta_target) . '" 
                                    class="btn rounded-pill pxp-section-cta"
                                >
                                    ' . esc_html__('All Categories', 'jobster') . '
                                    <span class="fa fa-angle-right"></span>
                                </a>
                            </div>
                        </div>
                    </section>';
                break;
            case 'c':
                $return_string .= 
                    '<section class="mt-100">
                        <div class="pxp-container">
                            <div class="row justify-content-between align-items-end">
                                <div class="col-auto">';
                if (isset($data['title']) && $data['title'] != '') {
                    $return_string .=
                                    '<h2 class="pxp-section-h2">
                                        ' . esc_html($data['title']) . '
                                    </h2>';
                }
                if (isset($data['subtitle']) && $data['subtitle'] != '') {
                    $return_string .=
                                    '<p class="pxp-text-light">
                                        ' . esc_html($data['subtitle']) . '
                                    </p>';
                }
                $return_string .=
                                '</div>
                                <div class="col-auto">
                                    <div class="text-right">
                                        <a 
                                            href="' . esc_url($cta_target) . '" 
                                            class="btn pxp-section-cta-o"
                                        >
                                            ' . esc_html__('All Categories', 'jobster') . '
                                            <span class="fa fa-angle-right"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="pxp-categories-carousel owl-carousel mt-4 mt-md-5 ' . esc_attr($animation) . '">';

                $categories_count = 0;
                foreach ($category_terms as $category_term) {
                    if ($categories_count < intval($number)) {
                        $category_link = add_query_arg(
                            'category',
                            $category_term->term_id,
                            $search_jobs_url
                        );
                        $category_icon = get_term_meta(
                            $category_term->term_id,
                            'job_category_icon',
                            true
                        );

                        $return_string .= 
                                    '<a 
                                        href="' . esc_url($category_link) . '" 
                                        class="' . esc_attr($v_card) . '"
                                    >
                                        <div class="' . esc_attr($v_card) . '-icon-container">';
                        if (!empty($category_icon)) {
                            $category_icon_type = get_term_meta(
                                $category_term->term_id,
                                'job_category_icon_type',
                                true
                            );
                            if ($category_icon_type == 'image') {
                                $icon_image = wp_get_attachment_image_src(
                                    $category_icon,
                                    'pxp-icon'
                                );
                                if (is_array($icon_image)) {
                                    $return_string .= 
                                            '<div class="' . esc_attr($v_card) . '-icon-image">
                                                <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                            </div>';
                                } else {
                                    $return_string .= 
                                            '<div class="' . esc_attr($v_card) . '-icon">
                                                <span class="fa fa-folder-o"></span>
                                            </div>';
                                }
                            } else {
                                $return_string .= 
                                            '<div class="' . esc_attr($v_card) . '-icon">
                                                <span class="' . esc_attr($category_icon) . '"></span>
                                            </div>';
                            }
                        } else {
                            $return_string .= 
                                            '<div class="' . esc_attr($v_card) . '-icon">
                                                <span class="fa fa-folder-o"></span>
                                            </div>';
                        }
                        $return_string .= 
                                        '</div>
                                        <div class="' . esc_attr($v_card) . '-title">
                                            ' . esc_html($category_term->name) . '
                                        </div>
                                        <div class="' . esc_attr($v_card) . '-subtitle">
                                            ' . esc_html($category_term->jobs_count) . ' '
                                            . esc_html__('open positions', 'jobster') . '
                                        </div>
                                    </a>';
                    }
                    $categories_count++;
                }

                $return_string .=
                            '</div>
                        </div>
                    </section>';
                break;
            default: 
                $return_string .=
                    '<section class="mt-100">
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

                $categories_count = 0;
                foreach ($category_terms as $category_term) {
                    if ($categories_count < intval($number)) {
                        $category_link = add_query_arg(
                            'category',
                            $category_term->term_id,
                            $search_jobs_url
                        );
                        $category_icon = get_term_meta(
                            $category_term->term_id,
                            'job_category_icon',
                            true
                        );

                        if (isset($data['card'])) {
                            if ($data['card'] == 'h') {
                                $return_string .=
                                            '<div class="col-lg-6 col-xxl-4 pxp-categories-card-3-container">
                                                <a 
                                                    href="' . esc_url($category_link) . '" 
                                                    class="pxp-categories-card-3"
                                                >';
                                if (!empty($category_icon)) {
                                    $category_icon_type = get_term_meta(
                                        $category_term->term_id,
                                        'job_category_icon_type',
                                        true
                                    );
                                    if ($category_icon_type == 'image') {
                                        $icon_image = wp_get_attachment_image_src(
                                            $category_icon,
                                            'pxp-icon'
                                        );
                                        if (is_array($icon_image)) {
                                            $return_string .= 
                                                    '<div class="pxp-categories-card-3-icon-image">
                                                        <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                                    </div>';
                                        } else {
                                            $return_string .= 
                                                    '<div class="pxp-categories-card-3-icon">
                                                        <span class="fa fa-folder-o"></span>
                                                    </div>';
                                        }
                                    } else {
                                        $return_string .= 
                                                    '<div class="pxp-categories-card-3-icon">
                                                        <span class="' . esc_attr($category_icon) . '"></span>
                                                    </div>';
                                    }
                                } else {
                                    $return_string .= 
                                                    '<div class="pxp-categories-card-3-icon">
                                                        <span class="fa fa-folder-o"></span>
                                                    </div>';
                                }
                                $return_string .= 
                                                    '<div class="pxp-categories-card-3-text">
                                                        <div class="pxp-categories-card-3-title">
                                                            ' . esc_html($category_term->name) . '
                                                        </div>
                                                        <div class="pxp-categories-card-3-subtitle">
                                                            ' . esc_html($category_term->jobs_count) . ' '
                                                            . esc_html__('open positions', 'jobster') . '
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>';
                            } else if ($data['card'] == 'b') {
                                $return_string .= 
                                            '<div class="col-12 col-md-4 col-lg-3 col-xxl-2 pxp-categories-card-4-container">
                                                <a 
                                                    href="' . esc_url($category_link) . '" 
                                                    class="pxp-categories-card-4"
                                                >
                                                    <div class="pxp-categories-card-4-icon-container ' . esc_attr($card_align) . '">
                                                        <div>
                                                            <div class="pxp-categories-card-4-subtitle">
                                                                ' . esc_html($category_term->jobs_count) . ' '
                                                                . esc_html__('open positions', 'jobster') . '
                                                            </div>
                                                        </div>';
                                if (!empty($category_icon)) {
                                    $category_icon_type = get_term_meta(
                                        $category_term->term_id,
                                        'job_category_icon_type',
                                        true
                                    );
                                    if ($category_icon_type == 'image') {
                                        $icon_image = wp_get_attachment_image_src(
                                            $category_icon,
                                            'pxp-icon'
                                        );
                                        if (is_array($icon_image)) {
                                            $return_string .= 
                                                        '<div class="pxp-categories-card-4-icon-image ' . esc_attr($icon_bg) . '">
                                                            <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                                        </div>';
                                        } else {
                                            $return_string .= 
                                                        '<div class="pxp-categories-card-4-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                        }
                                    } else {
                                        $return_string .= 
                                                        '<div class="pxp-categories-card-4-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="' . esc_attr($category_icon) . '"></span>
                                                        </div>';
                                    }
                                } else {
                                    $return_string .= 
                                                        '<div class="pxp-categories-card-4-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                }
                                $return_string .= 
                                                    '</div>
                                                    <div class="pxp-categories-card-4-title ' . esc_attr($card_align) . '">
                                                        ' . esc_html($category_term->name) . '
                                                    </div>
                                                </a>
                                            </div>';
                            } else if ($data['card'] == 't') {
                                $return_string .= 
                                            '<div class="col-12 col-md-4 col-lg-3 col-xxl-2 pxp-categories-card-5-container">
                                                <a 
                                                    href="' . esc_url($category_link) . '" 
                                                    class="pxp-categories-card-5"
                                                >
                                                    <div class="pxp-categories-card-4-icon-container ' . esc_attr($card_align) . '">';
                                if (!empty($category_icon)) {
                                    $category_icon_type = get_term_meta(
                                        $category_term->term_id,
                                        'job_category_icon_type',
                                        true
                                    );
                                    if ($category_icon_type == 'image') {
                                        $icon_image = wp_get_attachment_image_src(
                                            $category_icon,
                                            'pxp-icon'
                                        );
                                        if (is_array($icon_image)) {
                                            $return_string .=
                                                        '<div class="pxp-categories-card-5-icon-image ' . esc_attr($icon_bg) . '">
                                                            <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                                        </div>';
                                        } else {
                                            $return_string .=
                                                        '<div class="pxp-categories-card-5-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                        }
                                    } else {
                                        $return_string .=
                                                        '<div class="pxp-categories-card-5-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="' . esc_attr($category_icon) . '"></span>
                                                        </div>';
                                    }
                                } else {
                                    $return_string .= 
                                                        '<div class="pxp-categories-card-5-icon ' . esc_attr($icon_bg) . '">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                }
                                $return_string .= 
                                                    '</div>
                                                    <div class="pxp-categories-card-5-title ' . esc_attr($card_align) . '">
                                                        ' . esc_html($category_term->name) . '
                                                    </div>
                                                    <div class="pxp-categories-card-5-subtitle ' . esc_attr($card_align) . '">
                                                        ' . esc_html($category_term->jobs_count) . ' '
                                                        . esc_html__('open positions', 'jobster') . '
                                                    </div>
                                                </a>
                                            </div>';
                            } else {
                                $return_string .= 
                                            '<div class="col-12 col-md-4 col-lg-3 col-xxl-2 ' . esc_attr($v_card) . '-container">
                                                <a 
                                                    href="' . esc_url($category_link) . '" 
                                                    class="' . esc_attr($v_card) . '"
                                                >
                                                    <div class="' . esc_attr($v_card) . '-icon-container">';
                                if (!empty($category_icon)) {
                                    $category_icon_type = get_term_meta(
                                        $category_term->term_id,
                                        'job_category_icon_type',
                                        true
                                    );
                                    if ($category_icon_type == 'image') {
                                        $icon_image = wp_get_attachment_image_src(
                                            $category_icon,
                                            'pxp-icon'
                                        );
                                        if (is_array($icon_image)) {
                                            $return_string .= 
                                                        '<div class="' . esc_attr($v_card) . '-icon-image">
                                                            <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                                        </div>';
                                        } else {
                                            $return_string .= 
                                                        '<div class="' . esc_attr($v_card) . '-icon">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                        }
                                    } else {
                                        $return_string .= 
                                                        '<div class="' . esc_attr($v_card) . '-icon">
                                                            <span class="' . esc_attr($category_icon) . '"></span>
                                                        </div>';
                                    }
                                } else {
                                    $return_string .= 
                                                        '<div class="' . esc_attr($v_card) . '-icon">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>';
                                }
                                $return_string .= 
                                                    '</div>
                                                    <div class="' . esc_attr($v_card) . '-title">
                                                        ' . esc_html($category_term->name) . '
                                                    </div>
                                                    <div class="' . esc_attr($v_card) . '-subtitle">
                                                        ' . esc_html($category_term->jobs_count) . ' '
                                                        . esc_html__('open positions', 'jobster') . '
                                                    </div>
                                                </a>
                                            </div>';
                            }
                        } else {
                            $return_string .= 
                                        '<div class="col-12 col-md-4 col-lg-3 col-xxl-2 ' . esc_attr($v_card) . '-container">
                                            <a 
                                                href="' . esc_url($category_link) . '" 
                                                class="' . esc_attr($v_card) . '"
                                            >
                                                <div class="' . esc_attr($v_card) . '-icon-container">';
                            if (!empty($category_icon)) {
                                $category_icon_type = get_term_meta(
                                    $category_term->term_id,
                                    'job_category_icon_type',
                                    true
                                );
                                if ($category_icon_type == 'image') {
                                    $icon_image = wp_get_attachment_image_src(
                                        $category_icon,
                                        'pxp-icon'
                                    );
                                    if (is_array($icon_image)) {
                                        $return_string .= 
                                                    '<div class="' . esc_attr($v_card) . '-icon-image">
                                                        <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                                    </div>';
                                    } else {
                                        $return_string .= 
                                                    '<div class="' . esc_attr($v_card) . '-icon">
                                                        <span class="fa fa-folder-o"></span>
                                                    </div>';
                                    }
                                } else {
                                    $return_string .= 
                                                    '<div class="' . esc_attr($v_card) . '-icon">
                                                        <span class="' . esc_attr($category_icon) . '"></span>
                                                    </div>';
                                }
                            } else {
                                $return_string .= 
                                                    '<div class="' . esc_attr($v_card) . '-icon">
                                                        <span class="fa fa-folder-o"></span>
                                                    </div>';
                            }
                            $return_string .= 
                                                '</div>
                                                <div class="' . esc_attr($v_card) . '-title">
                                                    ' . esc_html($category_term->name) . '
                                                </div>
                                                <div class="' . esc_attr($v_card) . '-subtitle">
                                                    ' . esc_html($category_term->jobs_count) . ' '
                                                    . esc_html__('open positions', 'jobster') . '
                                                </div>
                                            </a>
                                        </div>';
                        }
                    }
                    $categories_count++;
                }

                $return_string .=
                            '</div>
                            <div class="mt-4 mt-md-5 ' . esc_attr($align_text) . ' ' . esc_attr($animation) . '">
                                <a 
                                    href="' . esc_url($cta_target) . '" 
                                    class="btn rounded-pill pxp-section-cta"
                                >
                                    ' . esc_html__('All Categories', 'jobster') . '
                                    <span class="fa fa-angle-right"></span>
                                </a>
                            </div>
                        </div>
                    </section>';
                break;
        }

        return $return_string;
    }
endif;
?>