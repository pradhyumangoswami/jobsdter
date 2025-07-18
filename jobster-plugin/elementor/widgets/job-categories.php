<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Job_Categories extends \Elementor\Widget_Base {
    public function get_name() {
        return 'job_categories';
    }

    public function get_title() {
        return __('Job Categories', 'jobster');
    }

    public function get_icon() {
        return 'eicon-folder';
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
            'number',
            [
                'label' => __('Number of Categories', 'jobster'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1000,
                'step' => 1,
                'default' => 6,
                'placeholder' => __('Enter number', 'jobster'),
            ]
        );

        $this->add_control(
            'exclude',
            [
                'label' => __('Exclude Empty Categories', 'jobster'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'n',
                'options' => array(
                    'n' => __('No', 'jobster'),
                    'y' => __('Yes', 'jobster')
                )
            ]
        );

        $this->add_control(
            'sort',
            [
                'label' => __('Sort by', 'jobster'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'n',
                'options' => array(
                    'n' => __('Name', 'jobster'),
                    'j' => __('Number of jobs', 'jobster')
                )
            ]
        );

        $this->add_control(
            'target',
            [
                'label' => __('CTA button target', 'jobster'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'j',
                'options' => array(
                    'j' => __('All jobs page', 'jobster'),
                    'c' => __('All categories page', 'jobster')
                )
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
            'layout',
            [
                'label' => __('Layout', 'jobster'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'g' => [
                        'title' => __('Grid', 'jobster'),
                        'icon' => 'eicon-gallery-grid',
                    ],
                    'c' => [
                        'title' => __('Carousel', 'jobster'),
                        'icon' => 'eicon-slider-3d',
                    ]
                ],
                'default' => 'g',
                'toggle' => false,
            ]
        );

        $this->add_control(
            'card',
            [
                'label' => __('Card Design', 'jobster'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'v' => [
                        'title' => __('Vertical', 'jobster'),
                        'icon' => 'eicon-icon-box',
                    ],
                    'h' => [
                        'title' => __('Horizontal', 'jobster'),
                        'icon' => 'eicon-call-to-action',
                    ],
                    'b' => [
                        'title' => __('Border', 'jobster'),
                        'icon' => 'eicon-header',
                    ],
                    't' => [
                        'title' => __('Transparent', 'jobster'),
                        'icon' => 'eicon-text',
                    ]
                ],
                'default' => 'v',
                'toggle' => false,
                'condition' => [
                    'layout' => 'g'
                ]
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __('Icon Background', 'jobster'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    't' => [
                        'title' => __('Transparent', 'jobster'),
                        'icon' => 'eicon-minus-square-o',
                    ],
                    'o' => [
                        'title' => __('Opaque', 'jobster'),
                        'icon' => 'eicon-square',
                    ]
                ],
                'default' => 't',
                'toggle' => false
            ]
        );

        $this->add_control(
            'align',
            [
                'label' => __('Section Align', 'jobster'),
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
                'toggle' => false,
                'condition' => [
                    'layout' => 'g'
                ]
            ]
        );

        $this->add_control(
            'card_align',
            [
                'label' => __('Card Align', 'jobster'),
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
                'toggle' => false,
                'condition' => [
                    'layout' => 'g',
                    'card' => ['t', 'b'],
                ]
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
            'job_category',
        );
        $category_args = array(
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
            'parent' => 0,
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

        switch($data['layout']) {
            case 'g': ?>
                <section class="mt-100">
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
                        <?php } ?>
                        <div class="row mt-4 mt-md-5 <?php echo esc_attr($animation); ?> <?php echo esc_attr($align_cards); ?>">
                            <?php $categories_count = 0;
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
                                        if ($data['card'] == 'h') { ?>
                                            <div class="col-lg-6 col-xxl-4 pxp-categories-card-3-container">
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="pxp-categories-card-3"
                                                >
                                                    <?php if (!empty($category_icon)) {
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
                                                            if (is_array($icon_image)) { ?>
                                                                <div class="pxp-categories-card-3-icon-image">
                                                                    <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="pxp-categories-card-3-icon">
                                                                    <span class="fa fa-folder-o"></span>
                                                                </div>
                                                            <?php }
                                                        } else { ?>
                                                            <div class="pxp-categories-card-3-icon">
                                                                <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                            </div>
                                                        <?php }
                                                    } else { ?>
                                                        <div class="pxp-categories-card-3-icon">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="pxp-categories-card-3-text">
                                                        <div class="pxp-categories-card-3-title">
                                                            <?php echo esc_html($category_term->name); ?>
                                                        </div>
                                                        <div class="pxp-categories-card-3-subtitle">
                                                            <?php echo esc_html($category_term->jobs_count); ?> 
                                                            <?php esc_html_e('open positions', 'jobster'); ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php } else if ($data['card'] == 'b') { ?>
                                            <div class="col-12 col-md-4 col-lg-3 col-xxl-2 pxp-categories-card-4-container">
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="pxp-categories-card-4"
                                                >
                                                    <div class="pxp-categories-card-4-icon-container <?php echo esc_attr($card_align); ?>">
                                                        <div>
                                                            <div class="pxp-categories-card-4-subtitle">
                                                                <?php echo esc_html($category_term->jobs_count); ?> <?php esc_html_e('open positions', 'jobster'); ?>
                                                            </div>
                                                        </div>
                                                        <?php if (!empty($category_icon)) {
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
                                                                if (is_array($icon_image)) { ?>
                                                                    <div class="pxp-categories-card-4-icon-image <?php echo esc_attr($icon_bg); ?>">
                                                                        <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="pxp-categories-card-4-icon <?php echo esc_attr($icon_bg); ?>">
                                                                        <span class="fa fa-folder-o"></span>
                                                                    </div>
                                                                <?php }
                                                            } else { ?>
                                                                <div class="pxp-categories-card-4-icon <?php echo esc_attr($icon_bg); ?>">
                                                                    <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                                </div>
                                                            <?php }
                                                        } else { ?>
                                                            <div class="pxp-categories-card-4-icon <?php echo esc_attr($icon_bg); ?>">
                                                                <span class="fa fa-folder-o"></span>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="pxp-categories-card-4-title <?php echo esc_attr($card_align); ?>">
                                                        <?php echo esc_html($category_term->name); ?>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php } else if ($data['card'] == 't') { ?>
                                            <div class="col-12 col-md-4 col-lg-3 col-xxl-2 pxp-categories-card-5-container">
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="pxp-categories-card-5"
                                                >
                                                    <div class="pxp-categories-card-5-icon-container <?php echo esc_attr($card_align); ?>">
                                                        <?php if (!empty($category_icon)) {
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
                                                                if (is_array($icon_image)) { ?>
                                                                    <div class="pxp-categories-card-5-icon-image <?php echo esc_attr($icon_bg); ?>">
                                                                        <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="pxp-categories-card-5-icon <?php echo esc_attr($icon_bg); ?>">
                                                                        <span class="fa fa-folder-o"></span>
                                                                    </div>
                                                                <?php }
                                                            } else { ?>
                                                                <div class="pxp-categories-card-5-icon <?php echo esc_attr($icon_bg); ?>">
                                                                    <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                                </div>
                                                            <?php }
                                                        } else { ?>
                                                            <div class="pxp-categories-card-5-icon <?php echo esc_attr($icon_bg); ?>">
                                                                <span class="fa fa-folder-o"></span>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="pxp-categories-card-5-title <?php echo esc_attr($card_align); ?>">
                                                        <?php echo esc_html($category_term->name); ?>
                                                    </div>
                                                    <div class="pxp-categories-card-5-subtitle <?php echo esc_attr($card_align); ?>">
                                                        <?php echo esc_html($category_term->jobs_count); ?> <?php esc_html_e('open positions', 'jobster'); ?>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-12 col-md-4 col-lg-3 col-xxl-2 <?php echo esc_attr($v_card); ?>-container">
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="<?php echo esc_attr($v_card); ?>"
                                                >
                                                    <div class="<?php esc_attr($v_card); ?>-icon-container">
                                                        <?php if (!empty($category_icon)) {
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
                                                                if (is_array($icon_image)) { ?>
                                                                    <div class="<?php echo esc_attr($v_card); ?>-icon-image">
                                                                        <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                                        <span class="fa fa-folder-o"></span>
                                                                    </div>
                                                                <?php }
                                                            } else { ?>
                                                                <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                                    <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                                </div>
                                                            <?php }
                                                        } else { ?>
                                                            <div class="<?php echo esc_attr($v_card)?>-icon">
                                                                <span class="fa fa-folder-o"></span>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="<?php echo esc_attr($v_card); ?>-title">
                                                        <?php echo esc_html($category_term->name); ?>
                                                    </div>
                                                    <div class="<?php echo esc_attr($v_card); ?>-subtitle">
                                                        <?php echo esc_html($category_term->jobs_count); ?> 
                                                        <?php esc_html_e('open positions', 'jobster'); ?>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php }
                                    } else { ?>
                                        <div class="col-12 col-md-4 col-lg-3 col-xxl-2 <?php echo esc_attr($v_card); ?>'-container">
                                            <a 
                                                href="<?php echo esc_url($category_link); ?>" 
                                                class="<?php echo esc_attr($v_card); ?>"
                                            >
                                                <div class="<?php echo esc_attr($v_card); ?>-icon-container">
                                                    <?php if (!empty($category_icon)) {
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
                                                            if (is_array($icon_image)) { ?>
                                                                <div class="<?php echo esc_attr($v_card); ?>-icon-image">
                                                                    <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                                    <span class="fa fa-folder-o"></span>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                                <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                            </div>
                                                        <?php }
                                                    } else { ?>
                                                        <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="<?php echo esc_attr($v_card); ?>-title">
                                                    <?php echo esc_html($category_term->name); ?>
                                                </div>
                                                <div class="<?php echo esc_attr($v_card); ?>-subtitle">
                                                    <?php echo esc_html($category_term->jobs_count); ?> 
                                                    <?php esc_html_e('open positions', 'jobster'); ?>
                                                </div>
                                            </a>
                                        </div>
                                    <?php }
                                }
                                $categories_count++;
                            } ?>
                        </div>

                        <div class="mt-4 mt-md-5 <?php echo esc_attr($align_text); ?> <?php echo esc_attr($animation); ?>">
                            <a 
                                href="<?php echo esc_url($cta_target); ?>" 
                                class="btn rounded-pill pxp-section-cta"
                            >
                                <?php esc_html_e('All Categories', 'jobster'); ?>
                                <span class="fa fa-angle-right"></span>
                            </a>
                        </div>
                    </div>
                </section>
                <?php break;
            case 'c': ?>
                <section class="mt-100">
                    <div class="pxp-container">
                        <div class="row justify-content-between align-items-end">
                            <div class="col-auto">
                                <?php if (isset($data['title']) && $data['title'] != '') { ?>
                                    <h2 class="pxp-section-h2">
                                        <?php echo esc_html($data['title']); ?>
                                    </h2>
                                <?php }
                                if (isset($data['subtitle']) && $data['subtitle'] != '') { ?>
                                    <p class="pxp-text-light">
                                        <?php echo esc_html($data['subtitle']); ?>
                                    </p>
                                <?php } ?>
                            </div>
                            <div class="col-auto">
                                <div class="text-right">
                                    <a 
                                        href="<?php echo esc_url($cta_target); ?>" 
                                        class="btn pxp-section-cta-o"
                                    >
                                        <?php esc_html_e('All Categories', 'jobster'); ?>
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="pxp-categories-carousel owl-carousel mt-4 mt-md-5 <?php echo esc_attr($animation); ?>">
                            <?php $categories_count = 0;
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
                                    ); ?>

                                    <a 
                                        href="<?php echo esc_url($category_link); ?>" 
                                        class="<?php echo esc_attr($v_card); ?>"
                                    >
                                        <div class="<?php echo esc_attr($v_card); ?>'-icon-container">
                                            <?php if (!empty($category_icon)) {
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
                                                    if (is_array($icon_image)) { ?>
                                                        <div class="<?php echo esc_attr($v_card); ?>-icon-image">
                                                            <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>
                                                    <?php }
                                                } else { ?>
                                                    <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                        <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                    </div>
                                                <?php }
                                            } else { ?>
                                                <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                    <span class="fa fa-folder-o"></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="<?php echo esc_attr($v_card); ?>-title">
                                            <?php echo esc_html($category_term->name); ?>
                                        </div>
                                        <div class="<?php echo esc_attr($v_card); ?>-subtitle">
                                            <?php echo esc_html($category_term->jobs_count); ?> 
                                            <?php esc_html_e('open positions', 'jobster'); ?>
                                        </div>
                                    </a>
                                <?php }
                                $categories_count++;
                            } ?>
                        </div>
                    </div>
                </section>
                <?php break;
            default: ?>
                <section class="mt-100">
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
                        <?php } ?>
                        <div class="row mt-4 mt-md-5 <?php echo esc_attr($animation); ?> <?php echo esc_attr($align_cards); ?>">
                            <?php $categories_count = 0;
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
                                        if ($data['card'] == 'h') { ?>
                                            <div class="col-lg-6 col-xxl-4 pxp-categories-card-3-container">
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="pxp-categories-card-3"
                                                >
                                                    <?php if (!empty($category_icon)) {
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
                                                            if (is_array($icon_image)) { ?>
                                                                <div class="pxp-categories-card-3-icon-image">
                                                                    <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="pxp-categories-card-3-icon">
                                                                    <span class="fa fa-folder-o"></span>
                                                                </div>
                                                            <?php }
                                                        } else { ?>
                                                            <div class="pxp-categories-card-3-icon">
                                                                <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                            </div>
                                                        <?php }
                                                    } else { ?>
                                                        <div class="pxp-categories-card-3-icon">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="pxp-categories-card-3-text">
                                                        <div class="pxp-categories-card-3-title">
                                                            <?php echo esc_html($category_term->name); ?>
                                                        </div>
                                                        <div class="pxp-categories-card-3-subtitle">
                                                            <?php echo esc_html($category_term->jobs_count); ?> 
                                                            <?php esc_html_e('open positions', 'jobster'); ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php } else if ($data['card'] == 'b') { ?>
                                            <div class="col-12 col-md-4 col-lg-3 col-xxl-2 pxp-categories-card-4-container">
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="pxp-categories-card-4"
                                                >
                                                    <div class="pxp-categories-card-4-icon-container <?php echo esc_attr($card_align); ?>">
                                                        <div>
                                                            <div class="pxp-categories-card-4-subtitle">
                                                                <?php echo esc_html($category_term->jobs_count); ?> <?php esc_html_e('open positions', 'jobster'); ?>
                                                            </div>
                                                        </div>
                                                        <?php if (!empty($category_icon)) {
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
                                                                if (is_array($icon_image)) { ?>
                                                                    <div class="pxp-categories-card-4-icon-image <?php echo esc_attr($icon_bg); ?>">
                                                                        <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="pxp-categories-card-4-icon <?php echo esc_attr($icon_bg); ?>">
                                                                        <span class="fa fa-folder-o"></span>
                                                                    </div>
                                                                <?php }
                                                            } else { ?>
                                                                <div class="pxp-categories-card-4-icon <?php echo esc_attr($icon_bg); ?>">
                                                                    <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                                </div>
                                                            <?php }
                                                        } else { ?>
                                                            <div class="pxp-categories-card-4-icon <?php echo esc_attr($icon_bg); ?>">
                                                                <span class="fa fa-folder-o"></span>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="pxp-categories-card-4-title <?php echo esc_attr($card_align); ?>">
                                                        <?php echo esc_html($category_term->name); ?>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php } else if ($data['card'] == 't') { ?>
                                            <div class="col-12 col-md-4 col-lg-3 col-xxl-2 pxp-categories-card-5-container">
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="pxp-categories-card-5"
                                                >
                                                    <div class="pxp-categories-card-5-icon-container <?php echo esc_attr($card_align); ?>">
                                                        <?php if (!empty($category_icon)) {
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
                                                                if (is_array($icon_image)) { ?>
                                                                    <div class="pxp-categories-card-5-icon-image <?php echo esc_attr($icon_bg); ?>">
                                                                        <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="pxp-categories-card-5-icon <?php echo esc_attr($icon_bg); ?>">
                                                                        <span class="fa fa-folder-o"></span>
                                                                    </div>
                                                                <?php }
                                                            } else { ?>
                                                                <div class="pxp-categories-card-5-icon <?php echo esc_attr($icon_bg); ?>">
                                                                    <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                                </div>
                                                            <?php }
                                                        } else { ?>
                                                            <div class="pxp-categories-card-5-icon <?php echo esc_attr($icon_bg); ?>">
                                                                <span class="fa fa-folder-o"></span>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="pxp-categories-card-5-title <?php echo esc_attr($card_align); ?>">
                                                        <?php echo esc_html($category_term->name); ?>
                                                    </div>
                                                    <div class="pxp-categories-card-5-subtitle <?php echo esc_attr($card_align); ?>">
                                                        <?php echo esc_html($category_term->jobs_count); ?> <?php esc_html_e('open positions', 'jobster'); ?>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-12 col-md-4 col-lg-3 col-xxl-2 <?php echo esc_attr($v_card); ?>-container">
                                                <a 
                                                    href="<?php echo esc_url($category_link); ?>" 
                                                    class="<?php echo esc_attr($v_card); ?>"
                                                >
                                                    <div class="<?php esc_attr($v_card); ?>-icon-container">
                                                        <?php if (!empty($category_icon)) {
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
                                                                if (is_array($icon_image)) { ?>
                                                                    <div class="<?php echo esc_attr($v_card); ?>-icon-image">
                                                                        <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                                        <span class="fa fa-folder-o"></span>
                                                                    </div>
                                                                <?php }
                                                            } else { ?>
                                                                <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                                    <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                                </div>
                                                            <?php }
                                                        } else { ?>
                                                            <div class="<?php echo esc_attr($v_card)?>-icon">
                                                                <span class="fa fa-folder-o"></span>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="<?php echo esc_attr($v_card); ?>-title">
                                                        <?php echo esc_html($category_term->name); ?>
                                                    </div>
                                                    <div class="<?php echo esc_attr($v_card); ?>-subtitle">
                                                        <?php echo esc_html($category_term->jobs_count); ?> 
                                                        <?php esc_html_e('open positions', 'jobster'); ?>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php }
                                    } else { ?>
                                        <div class="col-12 col-md-4 col-lg-3 col-xxl-2 <?php echo esc_attr($v_card); ?>'-container">
                                            <a 
                                                href="<?php echo esc_url($category_link); ?>" 
                                                class="<?php echo esc_attr($v_card); ?>"
                                            >
                                                <div class="<?php echo esc_attr($v_card); ?>-icon-container">
                                                    <?php if (!empty($category_icon)) {
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
                                                            if (is_array($icon_image)) { ?>
                                                                <div class="<?php echo esc_attr($v_card); ?>-icon-image">
                                                                    <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                                    <span class="fa fa-folder-o"></span>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                                <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                            </div>
                                                        <?php }
                                                    } else { ?>
                                                        <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                            <span class="fa fa-folder-o"></span>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="<?php echo esc_attr($v_card); ?>-title">
                                                    <?php echo esc_html($category_term->name); ?>
                                                </div>
                                                <div class="<?php echo esc_attr($v_card); ?>-subtitle">
                                                    <?php echo esc_html($category_term->jobs_count); ?> 
                                                    <?php esc_html_e('open positions', 'jobster'); ?>
                                                </div>
                                            </a>
                                        </div>
                                    <?php }
                                }
                                $categories_count++;
                            } ?>
                        </div>

                        <div class="mt-4 mt-md-5 <?php echo esc_attr($align_text); ?> <?php echo esc_attr($animation); ?>">
                            <a 
                                href="<?php echo esc_url($cta_target); ?>" 
                                class="btn rounded-pill pxp-section-cta"
                            >
                                <?php esc_html_e('All Categories', 'jobster'); ?>
                                <span class="fa fa-angle-right"></span>
                            </a>
                        </div>
                    </div>
                </section>
                <?php break;
        }
    }
}
?>