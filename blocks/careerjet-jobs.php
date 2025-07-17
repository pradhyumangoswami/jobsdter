<?php
/**
 * Careerjet jobs block
 */
if (!function_exists('jobster_careerjet_jobs_block')): 
    function jobster_careerjet_jobs_block() {
        wp_register_script(
            'jobster-careerjet-jobs-block',
            plugins_url('js/careerjet-jobs.js', __FILE__),
            array(
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-editor',
                'wp-i18n'
            )
        );

        wp_enqueue_style(
            'jobster-careerjet-jobs-block-editor',
            plugins_url('css/careerjet-jobs.css', __FILE__),
            array()
        );

        register_block_type('jobster-plugin/careerjet-jobs', array(
            'editor_script' => 'jobster-careerjet-jobs-block',
            'attributes' => array(
                'data_content' => array('type' => 'string')
            ),
            'render_callback' => 'jobster_careerjet_jobs_block_render'
        ));
    }
endif;
add_action('init', 'jobster_careerjet_jobs_block');

if (!function_exists('jobster_careerjet_jobs_block_render')): 
    function jobster_careerjet_jobs_block_render($attrs, $content = null) {
        extract(shortcode_atts(array('data_content'), $attrs));

        if (!isset($attrs['data_content'])) {
            return null;
        }

        $data = json_decode(urldecode($attrs['data_content']), true);

        $number =   isset($data['number']) && is_numeric($data['number'])
                    ? $data['number']
                    : 6;
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

        $search_jobs_url = jobster_get_page_link('job-search-apis.php');
        $apis_settings = get_option('jobster_apis_settings', '');
        $api_locale  =  isset($apis_settings['jobster_api_careerjet_locale_field'])
                    ? $apis_settings['jobster_api_careerjet_locale_field']
                    : '';
        $api_aff_id =   isset($apis_settings['jobster_api_careerjet_affid_field'])
                        ? $apis_settings['jobster_api_careerjet_affid_field']
                        : '';
        if ($api_locale == '') {
            $api_locale = 'en_GB';
        }

        if ($api_aff_id != '') {
            $api = new Careerjet_API($api_locale);

            $args = array(
                'pagesize' => $number,
                'page' => 1,
                'affid' => $api_aff_id
            );

            if (!empty($data['keywords'])) $args['keywords'] = $data['keywords'];
            if (!empty($data['location'])) $args['location'] = $data['location'];

            $result = $api->search($args);

            $jobs = $result->jobs;

            if ($result->type == 'JOBS') {
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
                        foreach($jobs as $job) : 
                            $return_string .=
                                '<div class="col-md-6 col-xl-4 col-xxl-3 pxp-jobs-card-1-container">
                                    <div class="pxp-jobs-card-1 ' . esc_attr($card_design) . '">
                                        <div class="pxp-jobs-card-1-top">
                                            <a 
                                                href="' . esc_url($job->url) . '" 
                                                class="pxp-jobs-card-1-title mt-0"
                                            >
                                                ' . esc_html($job->title) . '
                                            </a>
                                            <div class="pxp-jobs-card-1-details d-block">';
                            $location_link = add_query_arg(
                                'location',
                                $job->locations,
                                $search_jobs_url
                            );
                            $return_string .=
                                                '<a 
                                                    href="' . esc_url($location_link) . '" 
                                                    class="pxp-jobs-card-1-location"
                                                >
                                                    <span class="fa fa-globe"></span>
                                                    ' . esc_html($job->locations) . '
                                                </a>';
                            if ($job->salary) {
                                $return_string .= 
                                                '<div class="pxp-jobs-card-1-typ ps-0 mt-1">
                                                    ' . esc_html($job->salary) . '
                                                </div>';
                            }
                            $return_string .= 
                                            '</div>
                                        </div>
                                        <div class="pxp-jobs-card-1-bottom">
                                            <div class="pxp-jobs-card-1-bottom-left">
                                                <div class="pxp-jobs-card-1-date pxp-text-light">';
                            $date = strtotime($job->date);
                            $return_string .= esc_html(date('F j, Y', $date));
                            if ($job->company != '') {
                                $return_string .= 
                                                    '<span class="d-inline">
                                                        ' . esc_html__('by', 'jobster') . '
                                                    </span>';
                            }
                            $return_string .=
                                                '</div>';
                            if ($job->company != '') {
                                $return_string .=
                                                '<div class="pxp-jobs-card-1-company">
                                                    ' . esc_html($job->company) . '
                                                </div>';
                            }
                            $return_string .= 
                                            '</div>';
                            $company_name = $job->company != '' ? $job->company : 'C';
                            $return_string .=
                                            '<div class="pxp-jobs-card-1-company-logo pxp-no-img">
                                                ' . esc_html($company_name[0]) . '
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        endforeach;
                        $return_string .=
                            '</div>';
                        break;
                    case 's':
                        $return_string .= 
                            '<div class="row mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_cards) . '">';
                        foreach($jobs as $job) : 
                            $return_string .=
                                '<div class="col-xl-6 pxp-jobs-card-2-container">
                                    <div class="pxp-jobs-card-2 ' . esc_attr($card_design) . '">
                                        <div class="pxp-jobs-card-2-top">';
                            $company_name = $job->company != '' ? $job->company : 'C';
                            $return_string .=
                                            '<div class="pxp-jobs-card-2-company-logo pxp-no-img">
                                                ' . esc_html($company_name[0]) . '
                                            </div>';
                            $return_string .=
                                            '<div class="pxp-jobs-card-2-info">
                                                <a 
                                                    href="' . esc_url($job->url) . '" 
                                                    class="pxp-jobs-card-2-title"
                                                >
                                                    ' . esc_html($job->title) . '
                                                </a>
                                                <div class="pxp-jobs-card-2-details">';
                            $location_link = add_query_arg(
                                'location',
                                $job->locations,
                                $search_jobs_url
                            );
                            $return_string .=
                                                    '<a 
                                                        href="' . esc_url($location_link) . '" 
                                                        class="pxp-jobs-card-2-location"
                                                    >
                                                        <span class="fa fa-globe"></span>
                                                        ' . esc_html($job->locations) . '
                                                    </a>';
                            if ($job->salary) {
                                $return_string .=
                                                    '<div class="pxp-jobs-card-2-type">
                                                        ' . esc_html($job->salary) . '
                                                    </div>';
                            }
                            $return_string .=
                                                '</div>
                                            </div>
                                        </div>
                                        <div class="pxp-jobs-card-2-bottom">';
                            if ($job->company != '') {
                                $return_string .=
                                            '<div class="pxp-jobs-card-2-company mt-0">
                                                ' . esc_html($job->company) . '
                                            </div>';
                            }
                            $date = strtotime($job->date);
                            $return_string .=
                                            '<div class="pxp-jobs-card-2-bottom-right">
                                                <div class="pxp-jobs-card-2-date pxp-text-light">
                                                    ' . esc_html(date('F j, Y', $date)) . '
                                                </div>
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
                        foreach($jobs as $job) : 
                            $return_string .=
                                '<div class="pxp-jobs-card-3 ' . esc_attr($card_design) . '">
                                    <div class="row align-items-center justify-content-between">';
                            $company_name = $job->company != '' ? $job->company : 'C';
                            $return_string .=
                                        '<div class="col-sm-3 col-md-2 col-xxl-auto">
                                            <div class="pxp-jobs-card-3-company-logo pxp-no-img">
                                                ' . esc_html($company_name[0]) . '
                                            </div>
                                        </div>';
                            $return_string .=
                                        '<div class="col-sm-9 col-md-10 col-xxl-4">
                                            <a 
                                                href="' . esc_url($job->url) . '" 
                                                class="pxp-jobs-card-3-title mt-3 mt-sm-0"
                                            >
                                                ' . esc_html($job->title) . '
                                            </a>
                                            <div class="pxp-jobs-card-3-details">';
                            $location_link = add_query_arg(
                                'location',
                                $job->locations,
                                $search_jobs_url
                            );
                            $return_string .=
                                                '<a 
                                                    href="' . esc_url($location_link) . '" 
                                                    class="pxp-jobs-card-3-location"
                                                >
                                                    <span class="fa fa-globe"></span>
                                                    ' . esc_html($job->locations) . '
                                                </a>';
                            if ($job->salary) {
                                $return_string .=
                                                '<div class="pxp-jobs-card-3-type">
                                                    ' . esc_html($job->salary) . '
                                                </div>';
                            }
                            $return_string .=
                                            '</div>
                                        </div>
                                        <div class="col-sm-8 col-xxl-4 mt-3 mt-xxl-0">';
                            $date = strtotime($job->date);
                            $return_string .=
                                            '<div class="pxp-jobs-card-3-date pxp-text-light">
                                                ' . esc_html(date('F j, Y', $date)) . '
                                            </div>';
                            if ($job->company != '') {
                                $return_string .=
                                            '<div class="pxp-jobs-card-3-date-company">
                                                <div class="pxp-jobs-card-3-company">
                                                    ' . esc_html($job->company) . '
                                                </div>
                                            </div>';
                            }
                            $return_string .=
                                        '</div>
                                        <div class="col-sm-4 col-xxl-auto mt-3 mt-xxl-0 pxp-text-right">
                                            <a 
                                                href="' . esc_url($job->url) . '" 
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
                    default:
                        $return_string .=
                            '<div class="row mt-4 mt-md-5 ' . esc_attr($animation) . ' ' . esc_attr($align_cards) . '">';
                        foreach($jobs as $job) : 
                            $return_string .=
                                '<div class="col-md-6 col-xl-4 col-xxl-3 pxp-jobs-card-1-container">
                                    <div class="pxp-jobs-card-1 ' . esc_attr($card_design) . '">
                                        <div class="pxp-jobs-card-1-top">
                                            <a 
                                                href="' . esc_url($job->url) . '" 
                                                class="pxp-jobs-card-1-title mt-0"
                                            >
                                                ' . esc_html($job->title) . '
                                            </a>
                                            <div class="pxp-jobs-card-1-details d-block">';
                            $location_link = add_query_arg(
                                'location',
                                $job->locations,
                                $search_jobs_url
                            );
                            $return_string .=
                                                '<a 
                                                    href="' . esc_url($location_link) . '" 
                                                    class="pxp-jobs-card-1-location"
                                                >
                                                    <span class="fa fa-globe"></span>
                                                    ' . esc_html($job->locations) . '
                                                </a>';
                            if ($job->salary) {
                                $return_string .= 
                                                '<div class="pxp-jobs-card-1-typ ps-0 mt-1">
                                                    ' . esc_html($job->salary) . '
                                                </div>';
                            }
                            $return_string .= 
                                            '</div>
                                        </div>
                                        <div class="pxp-jobs-card-1-bottom">
                                            <div class="pxp-jobs-card-1-bottom-left">
                                                <div class="pxp-jobs-card-1-date pxp-text-light">';
                            $date = strtotime($job->date);
                            $return_string .= esc_html(date('F j, Y', $date));
                            if ($job->company != '') {
                                $return_string .= 
                                                    '<span class="d-inline">
                                                        ' . esc_html__('by', 'jobster') . '
                                                    </span>';
                            }
                            $return_string .=
                                                '</div>';
                            if ($job->company != '') {
                                $return_string .=
                                                '<div class="pxp-jobs-card-1-company">
                                                    ' . esc_html($job->company) . '
                                                </div>';
                            }
                            $return_string .= 
                                            '</div>';
                            $company_name = $job->company != '' ? $job->company : 'C';
                            $return_string .=
                                            '<div class="pxp-jobs-card-1-company-logo pxp-no-img">
                                                ' . esc_html($company_name[0]) . '
                                            </div>
                                        </div>
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
        }

        return '';
    }
endif;
?>