<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Careerjet_Jobs extends \Elementor\Widget_Base {
    public function get_name() {
        return 'careerjet_jobs';
    }

    public function get_title() {
        return __('Careerjet Jobs', 'jobster');
    }

    public function get_icon() {
        return 'eicon-icon-box';
    }

    public function get_categories() {
        return ['jobster'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'title_section',
            [
                'label' => __('Title', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter title', 'jobster'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'input_type' => 'string',
                'placeholder' => __('Enter subtitle', 'jobster'),
            ]
        );

        $this->add_control(
            'keywords',
            [
                'label' => __('Keyword(s)', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter keywords', 'jobster'),
            ]
        );

        $this->add_control(
            'location',
            [
                'label' => __('Location', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter location', 'jobster'),
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => __('Number of Jobs', 'jobster'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 6,
                'placeholder' => __('Enter number of jobs', 'jobster'),
            ]
        );

        $this->add_control(
            'align',
            [
                'label' => __('Align', 'jobster'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    's' => [
                        'title' => __('Start', 'jobster'),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'c' => [
                        'title' => __('Center', 'jobster'),
                        'icon' => 'eicon-align-center-h',
                    ]
                ],
                'default' => 's',
                'toggle' => false
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => __('Card Type', 'jobster'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'b' => [
                        'title' => __('Big', 'jobster'),
                        'icon' => 'eicon-info-box',
                    ],
                    's' => [
                        'title' => __('Small', 'jobster'),
                        'icon' => 'eicon-call-to-action',
                    ],
                    'l' => [
                        'title' => __('List', 'jobster'),
                        'icon' => 'eicon-post-list',
                    ]
                ],
                'default' => 'b',
                'toggle' => false
            ]
        );

        $this->add_control(
            'design',
            [
                'label' => __('Card Design', 'jobster'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    's' => [
                        'title' => __('Shadow', 'jobster'),
                        'icon' => 'eicon-instagram-nested-gallery',
                    ],
                    'b' => [
                        'title' => __('Border', 'jobster'),
                        'icon' => 'eicon-minus-square-o',
                    ]
                ],
                'default' => 's',
                'toggle' => false
            ]
        );

        $this->add_control(
            'bg',
            [
                'label' => __('Background Color', 'jobster'),
                'type' => \Elementor\Controls_Manager::COLOR
            ]
        );

        $this->add_control(
            'margin',
            [
                'label' => __('Margin', 'jobster'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'jobster'),
                'label_off' => __('No', 'jobster'),
                'return_value' => 'y',
                'default' => 'y'
            ]
        );

        $this->add_control(
            'animation',
            [
                'label' => __('Reveal Animation', 'jobster'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'e',
                'options' => array(
                    'e' => __('Enabled', 'jobster'),
                    'd' => __('Disabled', 'jobster')
                )
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $data = $this->get_settings_for_display();

        $number =   isset($data['number']) && is_numeric($data['number'])
                    ? $data['number']
                    : '6';
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

            if (isset($data['margin']) && $data['margin'] != 'y') {
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

            if ($result->type == 'JOBS') { ?>
                <section 
                    class="<?php echo esc_attr($margin); ?> <?php echo esc_attr($section_padding); ?>" 
                    style="background-color: <?php echo esc_attr($bg_color); ?>"
                >
                    <div class="pxp-container">
                        <?php if (isset($data['title']) && $data['title'] != '') { ?>
                            <h2 class="pxp-section-h2 <?php echo esc_attr($align_text); ?>">
                                <?php echo esc_html($data['title']); ?>
                            </h2>
                        <?php } 
                        if (isset($data['subtitle']) && $data['subtitle'] != '') { ?>
                            <p class="pxp-text-light <?php echo esc_attr($align_text); ?>">
                                <?php echo esc_html($data['subtitle']); ?>
                            </p>
                        <?php } 

                        switch ($data['type']) {
                            case 'b': ?>
                                <div class="row mt-4 mt-md-5 <?php echo esc_attr($animation); ?> <?php echo esc_attr($align_cards); ?>">
                                    <?php foreach($jobs as $job) : ?>
                                        <div class="col-md-6 col-xl-4 col-xxl-3 pxp-jobs-card-1-container">
                                            <div class="pxp-jobs-card-1 <?php echo esc_attr($card_design); ?>">
                                                <div class="pxp-jobs-card-1-top">
                                                    <a 
                                                        href="<?php echo esc_url($job->url); ?>" 
                                                        class="pxp-jobs-card-1-title mt-0"
                                                    >
                                                        <?php echo esc_html($job->title); ?>
                                                    </a>
                                                    <div class="pxp-jobs-card-1-details d-block">
                                                        <?php $location_link = add_query_arg(
                                                            'location',
                                                            $job->locations,
                                                            $search_jobs_url
                                                        ); ?>
                                                        <a 
                                                            href="<?php echo esc_url($location_link); ?>" 
                                                            class="pxp-jobs-card-1-location"
                                                        >
                                                            <span class="fa fa-globe"></span>
                                                            <?php echo esc_html($job->locations); ?>
                                                        </a>
                                                        <?php if ($job->salary) { ?>
                                                            <div class="pxp-jobs-card-1-typ ps-0 mt-1">
                                                                <?php echo esc_html($job->salary); ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="pxp-jobs-card-1-bottom">
                                                    <div class="pxp-jobs-card-1-bottom-left">
                                                        <div class="pxp-jobs-card-1-date pxp-text-light">
                                                            <?php $date = strtotime($job->date);
                                                            echo esc_html(date('F j, Y', $date));

                                                            if ($job->company != '') { ?>
                                                                <span class="d-inline">
                                                                    <?php esc_html_e('by', 'jobster'); ?>
                                                                </span>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if ($job->company != '') { ?>
                                                            <div class="pxp-jobs-card-1-company">
                                                                <?php echo esc_html($job->company); ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php $company_name = $job->company != '' ? $job->company : 'C'; ?>
                                                    <div class="pxp-jobs-card-1-company-logo pxp-no-img">
                                                        <?php echo esc_html($company_name[0]); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php break;
                            case 's': ?>
                                <div class="row mt-4 mt-md-5 <?php echo esc_attr($animation) ?> <?php echo esc_attr($align_cards); ?>">
                                    <?php foreach($jobs as $job) : ?>
                                        <div class="col-xl-6 pxp-jobs-card-2-container">
                                            <div class="pxp-jobs-card-2 <?php echo esc_attr($card_design); ?>">
                                                <div class="pxp-jobs-card-2-top">
                                                    <?php $company_name = $job->company != '' ? $job->company : 'C'; ?>
                                                    <div class="pxp-jobs-card-2-company-logo pxp-no-img">
                                                        <?php echo esc_html($company_name[0]); ?>
                                                    </div>
                                                    <div class="pxp-jobs-card-2-info">
                                                        <a 
                                                            href="<?php echo esc_url($job->url); ?>" 
                                                            class="pxp-jobs-card-2-title"
                                                        >
                                                            <?php echo esc_html($job->title); ?>
                                                        </a>
                                                        <div class="pxp-jobs-card-2-details">
                                                            <?php $location_link = add_query_arg(
                                                                'location',
                                                                $job->locations,
                                                                $search_jobs_url
                                                            ); ?>
                                                            <a 
                                                                href="<?php echo esc_url($location_link); ?>" 
                                                                class="pxp-jobs-card-2-location"
                                                            >
                                                                <span class="fa fa-globe"></span>
                                                                <?php echo esc_html($job->locations); ?>
                                                            </a>
                                                            <?php if ($job->salary) { ?>
                                                                <div class="pxp-jobs-card-2-type">
                                                                    <?php echo esc_html($job->salary); ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="pxp-jobs-card-2-bottom">
                                                    <?php if ($job->company != '') { ?>
                                                        <div class="pxp-jobs-card-2-company mt-0">
                                                            <?php echo esc_html($job->company); ?>
                                                        </div>
                                                    <?php }
                                                    $date = strtotime($job->date); ?>
                                                    <div class="pxp-jobs-card-2-bottom-right">
                                                        <div class="pxp-jobs-card-2-date pxp-text-light">
                                                            <?php echo esc_html(date('F j, Y', $date)); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php break;
                            case 'l': ?>
                                <div class="mt-4 mt-md-5 <?php echo esc_attr($animation); ?> <?php echo esc_attr($align_cards); ?>">
                                    <?php foreach($jobs as $job) : ?>
                                        <div class="pxp-jobs-card-3 <?php echo esc_attr($card_design); ?>">
                                            <div class="row align-items-center justify-content-between">
                                                <?php $company_name = $job->company != '' ? $job->company : 'C'; ?>
                                                <div class="col-sm-3 col-md-2 col-xxl-auto">
                                                    <div class="pxp-jobs-card-3-company-logo pxp-no-img">
                                                        <?php echo esc_html($company_name[0]); ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-9 col-md-10 col-xxl-4">
                                                    <a 
                                                        href="<?php echo esc_url($job->url); ?>" 
                                                        class="pxp-jobs-card-3-title mt-3 mt-sm-0"
                                                    >
                                                        <?php echo esc_html($job->title); ?>
                                                    </a>
                                                    <div class="pxp-jobs-card-3-details">
                                                        <?php $location_link = add_query_arg(
                                                            'location',
                                                            $job->locations,
                                                            $search_jobs_url
                                                        ); ?>
                                                        <a 
                                                            href="<?php echo esc_url($location_link); ?>" 
                                                            class="pxp-jobs-card-3-location"
                                                        >
                                                            <span class="fa fa-globe"></span>
                                                            <?php echo esc_html($job->locations); ?>
                                                        </a>
                                                        <?php if ($job->salary) { ?>
                                                            <div class="pxp-jobs-card-3-type">
                                                                <?php echo esc_html($job->salary); ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8 col-xxl-4 mt-3 mt-xxl-0">
                                                    <?php $date = strtotime($job->date); ?>
                                                    <div class="pxp-jobs-card-3-date pxp-text-light">
                                                        <?php echo esc_html(date('F j, Y', $date)); ?>
                                                    </div>
                                                    <?php if ($job->company != '') { ?>
                                                        <div class="pxp-jobs-card-3-date-company">
                                                            <div class="pxp-jobs-card-3-company">
                                                                <?php echo esc_html($job->company); ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-sm-4 col-xxl-auto mt-3 mt-xxl-0 pxp-text-right">
                                                    <a 
                                                        href="<?php echo esc_url($job->url); ?>" 
                                                        class="btn rounded-pill pxp-card-btn"
                                                    >
                                                        <?php esc_html_e('Apply', 'jobster'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php break;
                            default: ?>
                            <div class="row mt-4 mt-md-5 <?php echo esc_attr($animation); ?> <?php echo esc_attr($align_cards); ?>">
                                <?php foreach($jobs as $job) : ?>
                                    <div class="col-md-6 col-xl-4 col-xxl-3 pxp-jobs-card-1-container">
                                        <div class="pxp-jobs-card-1 <?php echo esc_attr($card_design); ?>">
                                            <div class="pxp-jobs-card-1-top">
                                                <a 
                                                    href="<?php echo esc_url($job->url); ?>" 
                                                    class="pxp-jobs-card-1-title mt-0"
                                                >
                                                    <?php echo esc_html($job->title); ?>
                                                </a>
                                                <div class="pxp-jobs-card-1-details d-block">
                                                    <?php $location_link = add_query_arg(
                                                        'location',
                                                        $job->locations,
                                                        $search_jobs_url
                                                    ); ?>
                                                    <a 
                                                        href="<?php echo esc_url($location_link); ?>" 
                                                        class="pxp-jobs-card-1-location"
                                                    >
                                                        <span class="fa fa-globe"></span>
                                                        <?php echo esc_html($job->locations); ?>
                                                    </a>
                                                    <?php if ($job->salary) { ?>
                                                        <div class="pxp-jobs-card-1-typ ps-0 mt-1">
                                                            <?php echo esc_html($job->salary); ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="pxp-jobs-card-1-bottom">
                                                <div class="pxp-jobs-card-1-bottom-left">
                                                    <div class="pxp-jobs-card-1-date pxp-text-light">
                                                        <?php $date = strtotime($job->date);
                                                        echo esc_html(date('F j, Y', $date));

                                                        if ($job->company != '') { ?>
                                                            <span class="d-inline">
                                                                <?php esc_html_e('by', 'jobster'); ?>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if ($job->company != '') { ?>
                                                        <div class="pxp-jobs-card-1-company">
                                                            <?php echo esc_html($job->company); ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <?php $company_name = $job->company != '' ? $job->company : 'C'; ?>
                                                <div class="pxp-jobs-card-1-company-logo pxp-no-img">
                                                    <?php echo esc_html($company_name[0]); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php break;
                        } ?>

                        <div class="mt-4 mt-md-5 <?php echo esc_attr($animation); ?> <?php echo esc_attr($align_text); ?>">
                            <a 
                                href="<?php echo esc_url($search_jobs_url); ?>" 
                                class="btn rounded-pill pxp-section-cta"
                            >
                                <?php esc_html_e('All Job Offers', 'jobster'); ?>
                                <span class="fa fa-angle-right"></span>
                            </a>
                        </div>
                    </div>
                </section>
            <?php }
        }
    }
}
?>