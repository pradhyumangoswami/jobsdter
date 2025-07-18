<?php
/**
 * Featured jobs block
 */
if (!function_exists('jobster_featured_jobs_block')): 
    function jobster_featured_jobs_block() {
        wp_register_script(
            'jobster-featured-jobs-block',
            plugins_url('js/featured-jobs.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-featured-jobs-block-editor',
            plugins_url('css/featured-jobs.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/featured-jobs', array(
            'editor_script' => 'jobster-featured-jobs-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_featured_jobs_block_render'
        ));
    }
endif;
add_action('init', 'jobster_featured_jobs_block');

if (!function_exists('jobster_featured_jobs_block_render')): 
    function jobster_featured_jobs_block_render($attrs, $content = null) {
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
        $category = isset($data['category']) && is_numeric($data['category'])
                    ? $data['category']
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

        $jobs_settings = get_option('jobster_jobs_settings');
        $show_salary =  isset($jobs_settings['jobster_job_card_salary_field']) 
                        && $jobs_settings['jobster_job_card_salary_field'] == '1';
        $show_valid =   isset($jobs_settings['jobster_job_expiration_field']) 
                        && $jobs_settings['jobster_job_expiration_field'] == '1';
        $date_format =  isset($jobs_settings['jobster_job_date_format_field']) 
                        ? $jobs_settings['jobster_job_date_format_field'] 
                        : 'date';

        $search_jobs_url = jobster_get_page_link('job-search.php');

        $args = array(
            'numberposts'      => $number,
            'post_type'        => 'job',
            'order'            => 'DESC',
            'suppress_filters' => false,
            'post_status'      => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'job_featured',
                    'value' => '1'
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'job_valid',
                        'compare' => '>=',
                        'value' => date('Y-m-d'),
                        'type' => 'DATE'
                    ),
                    array(
                        'key' => 'job_valid',
                        'compare' => '==',
                        'value' => ''
                    ),
                    array(
                        'key' => 'job_valid',
                        'compare' => 'NOT EXISTS'
                    )
                )
            )
        );

        if ($location != '0' && $category != '0') {
            $args['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'job_location',
                    'field'    => 'term_id',
                    'terms'    => $location,
                ),
                array(
                    'taxonomy' => 'job_category',
                    'field'    => 'term_id',
                    'terms'    => $category,
                ),
            );
        } else if ($location != '0') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'job_location',
                    'field'    => 'term_id',
                    'terms'    => $location,
                ),
            );
        } else if ($category != '0') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'job_category',
                    'field'    => 'term_id',
                    'terms'    => $category,
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

        switch ($data['type']) {
            case 'b':
                $return_string .=
                    '<div class="row mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_cards) . '">';
                foreach($posts as $post) : 
                    $job_link = get_permalink($post['ID']);

                    $job_category = wp_get_post_terms(
                        $post['ID'], 'job_category'
                    );
                    $job_category_id =  $job_category
                                        ? $job_category[0]->term_id
                                        : '';
                    $job_category_icon = 'fa fa-folder-o';
                    if ($job_category_id != '') {
                        $job_category_icon_type = get_term_meta(
                            $job_category_id, 'job_category_icon_type', true
                        );
                        $job_category_icon = get_term_meta(
                            $job_category_id, 'job_category_icon', true
                        );
                    }

                    $job_location = wp_get_post_terms(
                        $post['ID'], 'job_location'
                    );
                    $job_location_id = $job_location ? $job_location[0]->term_id : '';

                    $job_type = wp_get_post_terms(
                        $post['ID'], 'job_type'
                    );

                    $job_company_id = get_post_meta(
                        $post['ID'], 'job_company', true
                    );
                    $job_company = ($job_company_id != '')
                                ? get_post($job_company_id) :
                                '';

                    $salary = get_post_meta(
                        $post['ID'], 'job_salary', true
                    );
                    $valid = get_post_meta(
                        $post['ID'], 'job_valid', true
                    );
                    $jc1_mt_class = !empty($valid) && $show_valid ? '' : 'mt-1';

                    $return_string .=
                        '<div class="col-md-6 col-xl-4 col-xxl-3 pxp-jobs-card-1-container">
                            <div class="pxp-jobs-card-1 ' . esc_attr($card_design) . '">
                                <div class="pxp-jobs-card-1-top">';
                    if ($job_category_id != '') {
                        $job_category_link = add_query_arg(
                            'category',
                            $job_category_id,
                            $search_jobs_url
                        );
                        $return_string .=
                                    '<a 
                                        href="' . esc_url($job_category_link) . '" 
                                        class="pxp-jobs-card-1-category"
                                    >';
                        if ($job_category_icon_type == 'image') {
                            $icon_image = wp_get_attachment_image_src($job_category_icon, 'pxp-icon');
                            if (is_array($icon_image)) {
                                $return_string .= 
                                        '<div class="pxp-jobs-card-1-category-icon-image">
                                            <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                        </div>';
                            } else {
                                $return_string .=
                                        '<div class="pxp-jobs-card-1-category-icon">
                                            <span class="' . esc_attr($job_category_icon) . '"></span>
                                        </div>';
                            }
                        } else {
                            $return_string .=
                                        '<div class="pxp-jobs-card-1-category-icon">
                                            <span class="' . esc_attr($job_category_icon) . '"></span>
                                        </div>';
                        }
                        $return_string .=
                                        '<div class="pxp-jobs-card-1-category-label">
                                            ' . esc_html($job_category[0]->name) . '
                                        </div>
                                    </a>';
                    }
                    $return_string .=
                                    '<a 
                                        href="' . esc_url($job_link) . '" 
                                        class="pxp-jobs-card-1-title"
                                    >
                                        ' . esc_html($post['post_title']) . '
                                    </a>
                                    <div class="pxp-jobs-card-1-details">';
                    if ($job_location_id != '') { 
                        $job_location_link = add_query_arg(
                            'location',
                            $job_location_id,
                            $search_jobs_url
                        );
                        $return_string .= 
                                        '<a 
                                            href="' . esc_url($job_location_link) . '" 
                                            class="pxp-jobs-card-1-location"
                                        >
                                            <span class="fa fa-globe"></span>
                                            ' . esc_html($job_location[0]->name) . '
                                        </a>';
                    }
                    if ($job_type) {
                        $return_string .= 
                                        '<div class="pxp-jobs-card-1-type">
                                            ' . $job_type[0]->name . '
                                        </div>';
                    }
                    $return_string .= 
                                    '</div>';
                    if ($show_salary && $salary != '') {
                        $return_string .= 
                                        '<div class="pxp-jobs-card-1-salary">
                                            <span class="fa fa-money"></span>
                                            ' . esc_html($salary) . '
                                        </div>';
                    }
                    $return_string .= 
                                '</div>
                                <div class="pxp-jobs-card-1-bottom">
                                    <div class="pxp-jobs-card-1-bottom-left">
                                        <div class="pxp-jobs-card-1-date pxp-text-light">';
                    $job_date = get_the_date('', $post['ID']);
                    if ($date_format == 'time') {
                        $time_ago = jobster_get_time_ago(
                            strtotime($job_date)
                        );
                        $return_string .= esc_html($time_ago) . ' ' . esc_html__('ago', 'jobster');
                    } else {
                        $return_string .= esc_html($job_date);
                    }
                    if ($job_company != '') {
                        $return_string .=
                                            '<span class="d-inline">
                                                ' . esc_html__('by', 'jobster') . '
                                            </span>';
                    }
                    $return_string .=
                                        '</div>';
                    if ($job_company != '') {
                        $return_string .=
                                        '<a 
                                            href="' . esc_url(get_permalink($job_company_id)) . '" 
                                            class="pxp-jobs-card-1-company ' . esc_attr($jc1_mt_class) . '"
                                        >
                                            ' . esc_html($job_company->post_title) . '
                                        </a>';
                    }
                    if (!empty($valid) && $show_valid) {
                        $return_string .=
                                        '<div class="pxp-jobs-card-1-date-small pxp-text-light mt-1">
                                            ' . __('Valid until:', 'jobster') . ' ' . esc_html(date_i18n(__('F j, Y', 'jobster'), strtotime($valid))) . '
                                        </div>';
                    }
                    $return_string .=
                                    '</div>';
                    if ($job_company != '') {
                        $job_company_logo_val = get_post_meta(
                            $job_company_id,
                            'company_logo',
                            true
                        );
                        $job_company_logo = wp_get_attachment_image_src(
                            $job_company_logo_val,
                            'pxp-thmb'
                        );
                        if (is_array($job_company_logo)) {
                            $return_string .=
                                    '<a 
                                        href="' . esc_url(get_permalink($job_company_id)) . '" 
                                        class="pxp-jobs-card-1-company-logo" 
                                        style="background-image: url(' . esc_url($job_company_logo[0]) . ');"
                                    ></a>';
                        } else {
                            $job_company_name = $job_company->post_title;
                            $return_string .=
                                    '<a 
                                        href="' . esc_url(get_permalink($job_company_id)) . '" 
                                        class="pxp-jobs-card-1-company-logo pxp-no-img"
                                    >
                                        ' . esc_html($job_company_name[0]) . '
                                    </a>';
                        }
                    }
                    $return_string .=
                                '</div>
                            </div>
                        </div>';
                endforeach;
                $return_string .=
                    '</div>';
                break;
            case 's':
                $return_string .= 
                    '<div class="row mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_cards) . '">';
                foreach($posts as $post) : 
                    $job_link = get_permalink($post['ID']);

                    $job_category = wp_get_post_terms(
                        $post['ID'], 'job_category'
                    );
                    $job_category_id =  $job_category
                                        ? $job_category[0]->term_id
                                        : '';

                    $job_location = wp_get_post_terms(
                        $post['ID'], 'job_location'
                    );
                    $job_location_id = $job_location ? $job_location[0]->term_id : '';

                    $job_type = wp_get_post_terms(
                        $post['ID'], 'job_type'
                    );

                    $job_company_id = get_post_meta(
                        $post['ID'], 'job_company', true
                    );
                    $job_company = ($job_company_id != '')
                                ? get_post($job_company_id) :
                                '';

                    $salary = get_post_meta(
                        $post['ID'], 'job_salary', true
                    );
                    $valid = get_post_meta(
                        $post['ID'], 'job_valid', true
                    );

                    $return_string .=
                        '<div class="col-xl-6 pxp-jobs-card-2-container">
                            <div class="pxp-jobs-card-2 ' . esc_attr($card_design) . '">
                                <div class="pxp-jobs-card-2-top">';
                    if ($job_company != '') {
                        $job_company_logo_val = get_post_meta(
                            $job_company_id,
                            'company_logo',
                            true
                        );
                        $job_company_logo = wp_get_attachment_image_src(
                            $job_company_logo_val,
                            'pxp-thmb'
                        );
                        if (is_array($job_company_logo)) {
                            $return_string .=
                                    '<a 
                                        href="' . esc_url(get_permalink($job_company_id)) . '" 
                                        class="pxp-jobs-card-2-company-logo" 
                                        style="background-image: url(' . esc_url($job_company_logo[0]) . ');"
                                    ></a>';
                        } else {
                            $job_company_name = $job_company->post_title;
                            $return_string .=
                                    '<a 
                                        href="' . esc_url(get_permalink($job_company_id)) . '" 
                                        class="pxp-jobs-card-2-company-logo pxp-no-img"
                                    >
                                        ' . esc_html($job_company_name[0]) . '
                                    </a>';
                        }
                    }
                    $return_string .=
                                    '<div class="pxp-jobs-card-2-info">
                                        <a 
                                            href="' . esc_url($job_link) . '" 
                                            class="pxp-jobs-card-2-title"
                                        >
                                            ' . esc_html($post['post_title']) . '
                                        </a>
                                        <div class="pxp-jobs-card-2-details">';
                    if ($job_location_id != '') {
                        $job_location_link = add_query_arg(
                            'location',
                            $job_location_id,
                            $search_jobs_url
                        );
                        $return_string .=
                                            '<a 
                                                href="' . esc_url($job_location_link) . '" 
                                                class="pxp-jobs-card-2-location"
                                            >
                                                <span class="fa fa-globe"></span>
                                                ' . esc_html($job_location[0]->name) . '
                                            </a>';
                    }
                    if ($job_type) {
                        $return_string .=
                                            '<div class="pxp-jobs-card-2-type">
                                                ' . esc_html($job_type[0]->name) . '
                                            </div>';
                    }
                    $return_string .=
                                        '</div>';
                    if ($show_salary && $salary != '') {
                        $return_string .= 
                                        '<div class="pxp-jobs-card-2-salary">
                                            <span class="fa fa-money"></span>
                                            ' . esc_html($salary) . '
                                        </div>';
                    }
                    $return_string .=
                                    '</div>
                                </div>
                                <div class="pxp-jobs-card-2-bottom">';
                    if ($job_category_id != '') {
                        $job_category_link = add_query_arg(
                            'category',
                            $job_category_id,
                            $search_jobs_url
                        );
                        $return_string .=
                                    '<a 
                                        href="' . esc_url($job_category_link) . '" 
                                        class="pxp-jobs-card-2-category"
                                    >
                                        <div class="pxp-jobs-card-2-category-label">
                                            ' . esc_html($job_category[0]->name) . '
                                        </div>
                                    </a>';
                    }
                    $return_string .=
                                    '<div class="pxp-jobs-card-2-bottom-right">
                                        <div>
                                            <span class="pxp-jobs-card-2-date pxp-text-light">';
                    $job_date = get_the_date('', $post['ID']);
                    if ($date_format == 'time') {
                        $time_ago = jobster_get_time_ago(
                            strtotime($job_date)
                        );
                        $return_string .= esc_html($time_ago) . ' ' . esc_html__('ago', 'jobster');
                    } else {
                        $return_string .= esc_html($job_date);
                    }
                    if ($job_company != '') {
                        $return_string .=
                                                '<span class="d-inline">
                                                    ' . esc_html__('by', 'jobster') . '
                                                </span>';
                    }
                    $return_string .=
                                            '</span>';
                    if ($job_company != '') {
                        $return_string .=
                                            '<a 
                                                href="' . esc_url(get_permalink($job_company_id)) . '" 
                                                class="pxp-jobs-card-2-company"
                                            >
                                                ' . esc_html($job_company->post_title) . '
                                            </a>';
                    }
                    if (!empty($valid) && $show_valid) {
                        $return_string .=
                                            '<div class="pxp-jobs-card-2-date-small pxp-text-light mt-1">
                                                ' . __('Valid until:', 'jobster') . ' ' . esc_html(date_i18n(__('F j, Y', 'jobster'), strtotime($valid))) . '
                                            </div>';
                    }
                    $return_string .=
                                        '</div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                endforeach;
                $return_string .=
                    '</div>';
                break;
            case 'l':
                $return_string .= 
                    '<div class="mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_cards) . '">';
                foreach($posts as $post) : 
                    $job_link = get_permalink($post['ID']);

                    $job_category = wp_get_post_terms(
                        $post['ID'], 'job_category'
                    );
                    $job_category_id =  $job_category
                                        ? $job_category[0]->term_id
                                        : '';

                    $job_location = wp_get_post_terms(
                        $post['ID'], 'job_location'
                    );
                    $job_location_id = $job_location ? $job_location[0]->term_id : '';

                    $job_type = wp_get_post_terms(
                        $post['ID'], 'job_type'
                    );

                    $job_company_id = get_post_meta(
                        $post['ID'], 'job_company', true
                    );
                    $job_company = ($job_company_id != '')
                                ? get_post($job_company_id) :
                                '';

                    $salary = get_post_meta(
                        $post['ID'], 'job_salary', true
                    );
                    $valid = get_post_meta(
                        $post['ID'], 'job_valid', true
                    );
                    $jc3_mt_class = !empty($valid) && $show_valid ? '' : 'mt-2';

                    $return_string .=
                        '<div class="pxp-jobs-card-3 ' . esc_attr($card_design) . '">
                            <div class="row align-items-center justify-content-between">';
                    if ($job_company != '') { 
                        $job_company_logo_val = get_post_meta(
                            $job_company_id,
                            'company_logo',
                            true
                        );
                        $job_company_logo = wp_get_attachment_image_src(
                            $job_company_logo_val,
                            'pxp-thmb'
                        );
                        if (is_array($job_company_logo)) {
                            $return_string .=
                                '<div class="col-sm-3 col-md-2 col-xxl-auto">
                                    <a 
                                        href="' . esc_url(get_permalink($job_company_id)) . '" 
                                        class="pxp-jobs-card-3-company-logo" 
                                        style="background-image: url(' . esc_url($job_company_logo[0]) . ');"
                                    ></a>
                                </div>';
                        } else {
                            $job_company_name = $job_company->post_title;
                            $return_string .=
                                '<div class="col-sm-3 col-md-2 col-xxl-auto">
                                    <a 
                                        href="' . esc_url(get_permalink($job_company_id)) . '" 
                                        class="pxp-jobs-card-3-company-logo pxp-no-img"
                                    >
                                        ' . esc_html($job_company_name[0]) . '
                                    </a>
                                </div>';
                        }
                    }
                    $return_string .=
                                '<div class="col-sm-9 col-md-10 col-xxl-4">
                                    <a 
                                        href="' . esc_url($job_link) . '" 
                                        class="pxp-jobs-card-3-title mt-3 mt-sm-0"
                                    >
                                        ' . esc_html($post['post_title']) . '
                                    </a>
                                    <div class="pxp-jobs-card-3-details">';
                    if ($job_location_id != '') {
                        $job_location_link = add_query_arg(
                            'location',
                            $job_location_id,
                            $search_jobs_url
                        );
                        $return_string .=
                                        '<a 
                                            href="' . esc_url($job_location_link) . '" 
                                            class="pxp-jobs-card-3-location"
                                        >
                                            <span class="fa fa-globe"></span>
                                            ' . esc_html($job_location[0]->name) . '
                                        </a>';
                    }
                    if ($job_type) {
                        $return_string .=
                                        '<div class="pxp-jobs-card-3-type">
                                            ' . esc_html($job_type[0]->name) . '
                                        </div>';
                    }
                    $return_string .=
                                    '</div>';
                    if ($show_salary && $salary != '') {
                        $return_string .=
                                    '<div class="pxp-jobs-card-3-salary">
                                        <span class="fa fa-money"></span>
                                        ' . esc_html($salary) . '
                                    </div>';
                    }
                    $return_string .=
                                '</div>
                                <div class="col-sm-8 col-xxl-4 mt-3 mt-xxl-0">';
                    if ($job_category_id != '') { 
                        $job_category_link = add_query_arg(
                            'category',
                            $job_category_id,
                            $search_jobs_url
                        );
                        $return_string .=
                                    '<a 
                                        href="' . esc_url($job_category_link) . '" 
                                        class="pxp-jobs-card-3-category"
                                    >
                                        <div class="pxp-jobs-card-3-category-label">
                                            ' . esc_html($job_category[0]->name) . '
                                        </div>
                                    </a>';
                    }
                    $return_string .=
                                    '<div class="pxp-jobs-card-3-date-company ' . esc_attr($jc3_mt_class) . '">
                                        <span class="pxp-jobs-card-3-date pxp-text-light">';
                    $job_date = get_the_date('', $post['ID']);
                    if ($date_format == 'time') {
                        $time_ago = jobster_get_time_ago(
                            strtotime($job_date)
                        );
                        $return_string .= esc_html($time_ago) . ' ' . esc_html__('ago', 'jobster');
                    } else {
                        $return_string .= esc_html($job_date);
                    }
                    if ($job_company != '') {
                        $return_string .=
                                            '<span class="d-inline">
                                                ' . esc_html__('by', 'jobster') . '
                                            </span>';
                    }
                    $return_string .=
                                        '</span>';
                    if ($job_company != '') {
                        $return_string .=
                                        '<a 
                                            href="' . esc_url(get_permalink($job_company_id)) . '" 
                                            class="pxp-jobs-card-3-company"
                                        >
                                            ' . esc_html($job_company->post_title) . '
                                        </a>';
                    }
                    $return_string .=
                                    '</div>';
                    if (!empty($valid) && $show_valid) {
                        $return_string .=
                                    '<div class="pxp-jobs-card-3-date-small pxp-text-light mt-2">
                                        ' . __('Valid until:', 'jobster') . ' ' . esc_html(date_i18n(__('F j, Y', 'jobster'), strtotime($valid))) . '
                                    </div>';
                    }
                    $return_string .=
                                '</div>
                                <div class="col-sm-4 col-xxl-auto mt-3 mt-xxl-0 pxp-text-right">
                                    <a 
                                        href="' . esc_url($job_link) . '" 
                                        class="btn rounded-pill pxp-card-btn"
                                    >
                                        ' . esc_html__('Apply', 'jobster') . '
                                    </a>
                                </div>
                            </div>
                        </div>';
                endforeach;
                $return_string .=
                    '</div>';
                break;
            case 'c':
                $return_string .=
                    '<div class="row mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_cards) . '">';
                foreach($posts as $post) : 
                    $job_link = get_permalink($post['ID']);

                    $job_category = wp_get_post_terms(
                        $post['ID'], 'job_category'
                    );
                    $job_category_id =  $job_category
                                        ? $job_category[0]->term_id
                                        : '';
                    $job_category_icon = 'fa fa-folder-o';
                    if ($job_category_id != '') {
                        $job_category_icon_type = get_term_meta(
                            $job_category_id, 'job_category_icon_type', true
                        );
                        $job_category_icon = get_term_meta(
                            $job_category_id, 'job_category_icon', true
                        );
                    }

                    $job_location = wp_get_post_terms(
                        $post['ID'], 'job_location'
                    );
                    $job_location_id = $job_location ? $job_location[0]->term_id : '';

                    $job_type = wp_get_post_terms(
                        $post['ID'], 'job_type'
                    );

                    $job_cover_val = get_post_meta(
                        $post['ID'],
                        'job_cover',
                        true
                    );
                    $job_cover = wp_get_attachment_image_src(
                        $job_cover_val,
                        'pxp-gallery'
                    );

                    $job_company_id = get_post_meta(
                        $post['ID'], 'job_company', true
                    );
                    $job_company = ($job_company_id != '')
                                ? get_post($job_company_id) :
                                '';

                    $salary = get_post_meta(
                        $post['ID'], 'job_salary', true
                    );
                    $valid = get_post_meta(
                        $post['ID'], 'job_valid', true
                    );
                    $jc1_mt_class = !empty($valid) && $show_valid ? '' : 'mt-1';

                    $return_string .=
                        '<div class="col-md-6 col-xl-4 col-xxl-3 pxp-jobs-card-5-container">
                            <div class="pxp-jobs-card-5 ' . esc_attr($card_design) . '">
                                <div class="pxp-jobs-card-5-top">';
                    
                    $return_string .= 
                                    '<div ';
                    if (is_array($job_cover)) {
                        $return_string .= 
                                        'class="pxp-jobs-card-5-cover pxp-cover" 
                                        style="background-image: url(' . esc_url($job_cover[0]) . ');"';
                    } else {
                        $return_string .= 
                                        'class="pxp-jobs-card-5-cover pxp-no-cover"';
                    }
                    $return_string .=
                                    '>';
                    if ($job_company != '') {
                        $job_company_logo_val = get_post_meta(
                            $job_company_id,
                            'company_logo',
                            true
                        );
                        $job_company_logo = wp_get_attachment_image_src(
                            $job_company_logo_val,
                            'pxp-thmb'
                        );
                        if (is_array($job_company_logo)) {
                            $return_string .=
                                        '<a 
                                            href="' . esc_url(get_permalink($job_company_id)) . '" 
                                            class="pxp-jobs-card-5-company-logo" 
                                            style="background-image: url(' . esc_url($job_company_logo[0]) . ');"
                                        ></a>';
                        } else {
                            $job_company_name = $job_company->post_title;
                            $return_string .=
                                        '<a 
                                            href="' . esc_url(get_permalink($job_company_id)) . '" 
                                            class="pxp-jobs-card-5-company-logo pxp-no-img"
                                        >
                                            ' . esc_html($job_company_name[0]) . '
                                        </a>';
                        }
                    }
                    $return_string .=
                                    '</div>';
                    if ($job_category_id != '') {
                        $job_category_link = add_query_arg(
                            'category',
                            $job_category_id,
                            $search_jobs_url
                        );
                        $return_string .=
                                    '<a 
                                        href="' . esc_url($job_category_link) . '" 
                                        class="pxp-jobs-card-5-category"
                                    >';
                        if ($job_category_icon_type == 'image') {
                            $icon_image = wp_get_attachment_image_src($job_category_icon, 'pxp-icon');
                            if (is_array($icon_image)) {
                                $return_string .= 
                                        '<div class="pxp-jobs-card-5-category-icon-image">
                                            <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                        </div>';
                            } else {
                                $return_string .=
                                        '<div class="pxp-jobs-card-5-category-icon">
                                            <span class="' . esc_attr($job_category_icon) . '"></span>
                                        </div>';
                            }
                        } else {
                            $return_string .=
                                        '<div class="pxp-jobs-card-5-category-icon">
                                            <span class="' . esc_attr($job_category_icon) . '"></span>
                                        </div>';
                        }
                        $return_string .=
                                        '<div class="pxp-jobs-card-5-category-label">
                                            ' . esc_html($job_category[0]->name) . '
                                        </div>
                                    </a>';
                    }
                    $return_string .=
                                    '<a 
                                        href="' . esc_url($job_link) . '" 
                                        class="pxp-jobs-card-5-title"
                                    >
                                        ' . esc_html($post['post_title']) . '
                                    </a>
                                    <div class="pxp-jobs-card-5-details">';
                    if ($job_location_id != '') { 
                        $job_location_link = add_query_arg(
                            'location',
                            $job_location_id,
                            $search_jobs_url
                        );
                        $return_string .= 
                                        '<a 
                                            href="' . esc_url($job_location_link) . '" 
                                            class="pxp-jobs-card-5-location"
                                        >
                                            <span class="fa fa-globe"></span>
                                            ' . esc_html($job_location[0]->name) . '
                                        </a>';
                    }
                    if ($job_type) {
                        $return_string .= 
                                        '<div class="pxp-jobs-card-5-type">
                                            ' . $job_type[0]->name . '
                                        </div>';
                    }
                    $return_string .= 
                                    '</div>';
                    if ($show_salary && $salary != '') {
                        $return_string .= 
                                        '<div class="pxp-jobs-card-5-salary">
                                            <span class="fa fa-money"></span>
                                            ' . esc_html($salary) . '
                                        </div>';
                    }
                    $return_string .= 
                                '</div>
                                <div class="pxp-jobs-card-5-bottom">
                                    <div class="pxp-jobs-card-5-bottom-left">
                                        <div class="pxp-jobs-card-5-date pxp-text-light">';
                    $job_date = get_the_date('', $post['ID']);
                    if ($date_format == 'time') {
                        $time_ago = jobster_get_time_ago(
                            strtotime($job_date)
                        );
                        $return_string .= esc_html($time_ago) . ' ' . esc_html__('ago', 'jobster');
                    } else {
                        $return_string .= esc_html($job_date);
                    }
                    if ($job_company != '') {
                        $return_string .=
                                            '<span class="d-inline">
                                                ' . esc_html__('by', 'jobster') . '
                                            </span>';
                    }
                    $return_string .=
                                        '</div>';
                    if ($job_company != '') {
                        $return_string .=
                                        '<a 
                                            href="' . esc_url(get_permalink($job_company_id)) . '" 
                                            class="pxp-jobs-card-5-company ' . esc_attr($jc1_mt_class) . '"
                                        >
                                            ' . esc_html($job_company->post_title) . '
                                        </a>';
                    }
                    if (!empty($valid) && $show_valid) {
                        $return_string .=
                                        '<div class="pxp-jobs-card-5-date-small pxp-text-light mt-1">
                                            ' . __('Valid until:', 'jobster') . ' ' . esc_html(date_i18n(__('F j, Y', 'jobster'), strtotime($valid))) . '
                                        </div>';
                    }
                    $return_string .=
                                    '</div>';
                    $return_string .=
                                '</div>
                            </div>
                        </div>';
                endforeach;
                $return_string .=
                    '</div>';
                break;
            default:
                $return_string .=
                    '<div class="row mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_cards) . '">';
                foreach($posts as $post) : 
                    $job_link = get_permalink($post['ID']);

                    $job_category = wp_get_post_terms(
                        $post['ID'], 'job_category'
                    );
                    $job_category_id =  $job_category
                                        ? $job_category[0]->term_id
                                        : '';
                    $job_category_icon = 'fa fa-folder-o';
                    if ($job_category_id != '') {
                        $job_category_icon_type = get_term_meta(
                            $job_category_id, 'job_category_icon_type', true
                        );
                        $job_category_icon = get_term_meta(
                            $job_category_id, 'job_category_icon', true
                        );
                    }

                    $job_location = wp_get_post_terms(
                        $post['ID'], 'job_location'
                    );
                    $job_location_id = $job_location ? $job_location[0]->term_id : '';

                    $job_type = wp_get_post_terms(
                        $post['ID'], 'job_type'
                    );

                    $job_company_id = get_post_meta(
                        $post['ID'], 'job_company', true
                    );
                    $job_company = ($job_company_id != '')
                                ? get_post($job_company_id) :
                                '';

                    $salary = get_post_meta(
                        $post['ID'], 'job_salary', true
                    );
                    $valid = get_post_meta(
                        $post['ID'], 'job_valid', true
                    );
                    $jc1_mt_class = !empty($valid) && $show_valid ? '' : 'mt-1';

                    $return_string .=
                        '<div class="col-md-6 col-xl-4 col-xxl-3 pxp-jobs-card-1-container">
                            <div class="pxp-jobs-card-1 ' . esc_attr($card_design) . '">
                                <div class="pxp-jobs-card-1-top">';
                    if ($job_category_id != '') {
                        $job_category_link = add_query_arg(
                            'category',
                            $job_category_id,
                            $search_jobs_url
                        );
                        $return_string .=
                                    '<a 
                                        href="' . esc_url($job_category_link) . '" 
                                        class="pxp-jobs-card-1-category"
                                    >';
                        if ($job_category_icon_type == 'image') {
                            $icon_image = wp_get_attachment_image_src($job_category_icon, 'pxp-icon');
                            if (is_array($icon_image)) {
                                $return_string .= 
                                        '<div class="pxp-jobs-card-1-category-icon-image">
                                            <span style="background-image: url(' .  esc_url($icon_image[0]) . ');"></span>
                                        </div>';
                            } else {
                                $return_string .=
                                        '<div class="pxp-jobs-card-1-category-icon">
                                            <span class="' . esc_attr($job_category_icon) . '"></span>
                                        </div>';
                            }
                        } else {
                            $return_string .=
                                        '<div class="pxp-jobs-card-1-category-icon">
                                            <span class="' . esc_attr($job_category_icon) . '"></span>
                                        </div>';
                        }
                        $return_string .=
                                        '<div class="pxp-jobs-card-1-category-label">
                                            ' . esc_html($job_category[0]->name) . '
                                        </div>
                                    </a>';
                    }
                    $return_string .=
                                    '<a 
                                        href="' . esc_url($job_link) . '" 
                                        class="pxp-jobs-card-1-title"
                                    >
                                        ' . esc_html($post['post_title']) . '
                                    </a>
                                    <div class="pxp-jobs-card-1-details">';
                    if ($job_location_id != '') { 
                        $job_location_link = add_query_arg(
                            'location',
                            $job_location_id,
                            $search_jobs_url
                        );
                        $return_string .= 
                                        '<a 
                                            href="' . esc_url($job_location_link) . '" 
                                            class="pxp-jobs-card-1-location"
                                        >
                                            <span class="fa fa-globe"></span>
                                            ' . esc_html($job_location[0]->name) . '
                                        </a>';
                    }
                    if ($job_type) {
                        $return_string .= 
                                        '<div class="pxp-jobs-card-1-type">
                                            ' . $job_type[0]->name . '
                                        </div>';
                    }
                    $return_string .= 
                                    '</div>';
                    if ($show_salary && $salary != '') {
                        $return_string .= 
                                        '<div class="pxp-jobs-card-1-salary">
                                            <span class="fa fa-money"></span>
                                            ' . esc_html($salary) . '
                                        </div>';
                    }
                    $return_string .= 
                                '</div>
                                <div class="pxp-jobs-card-1-bottom">
                                    <div class="pxp-jobs-card-1-bottom-left">
                                        <div class="pxp-jobs-card-1-date pxp-text-light">';
                    $job_date = get_the_date('', $post['ID']);
                    if ($date_format == 'time') {
                        $time_ago = jobster_get_time_ago(
                            strtotime($job_date)
                        );
                        $return_string .= esc_html($time_ago) . ' ' . esc_html__('ago', 'jobster');
                    } else {
                        $return_string .= esc_html($job_date);
                    }
                    if ($job_company != '') {
                        $return_string .=
                                            '<span class="d-inline">
                                                ' . esc_html__('by', 'jobster') . '
                                            </span>';
                    }
                    $return_string .=
                                        '</div>';
                    if ($job_company != '') {
                        $return_string .=
                                        '<a 
                                            href="' . esc_url(get_permalink($job_company_id)) . '" 
                                            class="pxp-jobs-card-1-company' . esc_attr($jc1_mt_class) . '"
                                        >
                                            ' . esc_html($job_company->post_title) . '
                                        </a>';
                    }
                    if (!empty($valid) && $show_valid) {
                        $return_string .=
                                        '<div class="pxp-jobs-card-1-date-small pxp-text-light mt-1">
                                            ' . __('Valid until:', 'jobster') . ' ' . esc_html(date_i18n(__('F j, Y', 'jobster'), strtotime($valid))) . '
                                        </div>';
                    }
                    $return_string .=
                                    '</div>';
                    if ($job_company != '') {
                        $job_company_logo_val = get_post_meta(
                            $job_company_id,
                            'company_logo',
                            true
                        );
                        $job_company_logo = wp_get_attachment_image_src(
                            $job_company_logo_val,
                            'pxp-thmb'
                        );
                        if (is_array($job_company_logo)) {
                            $return_string .=
                                    '<a 
                                        href="' . esc_url(get_permalink($job_company_id)) . '" 
                                        class="pxp-jobs-card-1-company-logo" 
                                        style="background-image: url(' . esc_url($job_company_logo[0]) . ');"
                                    ></a>';
                        } else {
                            $job_company_name = $job_company->post_title;
                            $return_string .=
                                    '<a 
                                        href="' . esc_url(get_permalink($job_company_id)) . '" 
                                        class="pxp-jobs-card-1-company-logo pxp-no-img"
                                    >
                                        ' . esc_html($job_company_name[0]) . '
                                    </a>';
                        }
                    }
                    $return_string .=
                                '</div>
                            </div>
                        </div>';
                endforeach;
                $return_string .=
                    '</div>';
                break;
        }

        $return_string .=
                    '<div class="mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_text) . '">
                        <a 
                            href="' . esc_url($search_jobs_url) . '" 
                            class="btn rounded-pill pxp-section-cta"
                        >
                            ' . esc_html__('All Job Offers', 'jobster') . '
                            <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
                </div>
            </section>';


        return $return_string;
    }
endif;
?>