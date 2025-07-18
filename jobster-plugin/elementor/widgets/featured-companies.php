<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Featured_Companies extends \Elementor\Widget_Base {
    public function get_name() {
        return 'featured_companies';
    }

    public function get_title() {
        return __('Featured Companies', 'jobster');
    }

    public function get_icon() {
        return 'eicon-logo';
    }

    public function get_categories() {
        return ['jobster'];
    }

    private function jobster_get_locations() {
        $loc_terms = get_terms(
            array(
                'taxonomy' => 'company_location',
                'orderby' => 'name',
                'hierarchical' => true,
                'hide_empty' => false,
            )
        );

        $top_level_locations = array();
        $children_locations  = array();
        foreach ($loc_terms as $location) {
            if (empty($location->parent)) {
                $top_level_locations[] = $location;
            } else {
                $children_locations[$location->parent][] = $location;
            }
        }
        $locations = array('0' => __('All', 'jobster'));
        foreach ($top_level_locations as $top_location) {
            $locations[$top_location->term_id . '*'] = $top_location->name;
            if (array_key_exists($top_location->term_id, $children_locations)) {
                foreach ($children_locations[$top_location->term_id] as $child_location) {
                    $locations[$child_location->term_id . '*'] = '---' . $child_location->name;
                }
            }
        }

        return $locations;
    }

    private function jobster_get_industries() {
        $industry_taxonomies = array( 
            'company_industry'
        );
        $industry_args = array(
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false
        ); 
        $industry_terms = get_terms($industry_taxonomies, $industry_args);

        $industries = array('0' => __('All', 'jobster'));
        for ($ci = 0; $ci < count($industry_terms); $ci++) {
            $industries[$industry_terms[$ci]->term_id] = $industry_terms[$ci]->name;
        }

        return $industries;
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
            'location',
            [
                'label' => __('Location', 'jobster'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '0',
                'options' => $this->jobster_get_locations()
            ]
        );

        $this->add_control(
            'industry',
            [
                'label' => __('Industry', 'jobster'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '0',
                'options' => $this->jobster_get_industries(),
            ]
        );

        $this->add_control(
            'number',
            [
                'label' => __('Number of Companies', 'jobster'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 4,
                'placeholder' => __('Enter number of companies', 'jobster'),
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
        $data_location = isset($data['location']) ? trim($data['location'], "*") : '0';
        $location = is_numeric($data_location) ? $data_location : '0';
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

        $posts = wp_get_recent_posts($args); ?>

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
                <?php } ?>

                <div class="row mt-4 mt-md-5 <?php echo esc_attr($animation); ?> <?php echo esc_attr($align_cards); ?>">
                    <?php foreach($posts as $post) : 
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
                        ); ?>

                        <div class="col-md-6 col-xl-4 col-xxl-3 pxp-companies-card-1-container">
                            <div class="pxp-companies-card-1 <?php echo esc_attr($card_design); ?>">
                                <div class="pxp-companies-card-1-top">
                                    <?php if (is_array($company_logo)) { ?>
                                        <a 
                                            href="<?php echo esc_url($company_link); ?>" 
                                            class="pxp-companies-card-1-company-logo" 
                                            style="background-image: url(<?php echo esc_url($company_logo[0]); ?>);"
                                        ></a>
                                    <?php } else { ?>
                                        <a 
                                            href="<?php echo esc_url($company_link); ?>" 
                                            class="pxp-companies-card-1-company-logo pxp-no-img"
                                        >
                                            <?php echo esc_html($company_name[0]); ?>
                                        </a>
                                    <?php } ?>
                                    <a 
                                        href="<?php echo esc_url($company_link); ?>" 
                                        class="pxp-companies-card-1-company-name"
                                    >
                                        <?php echo esc_html($company_name); ?>
                                    </a>
                                    <div class="pxp-companies-card-1-company-description pxp-text-light">
                                        <?php echo esc_html($excerpt); ?>
                                    </div>
                                </div>
                                <div class="pxp-companies-card-1-bottom">
                                    <a 
                                        href="<?php echo esc_url($company_link); ?>" 
                                        class="pxp-companies-card-1-company-jobs"
                                    >
                                        <?php echo esc_html(
                                            jobster_get_active_jobs_no_by_company_id($post['ID'])
                                        ); ?> <?php esc_html_e('jobs', 'jobster'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4 mt-md-5 <?php echo esc_attr($animation); ?> <?php echo esc_attr($align_text); ?>">
                    <a 
                        href="<?php echo esc_url($search_companies_url); ?>" 
                        class="btn rounded-pill pxp-section-cta"
                    >
                        <?php esc_html_e('All Companies', 'jobster'); ?>
                        <span class="fa fa-angle-right"></span>
                    </a>
                </div>
            </div>
        </section>
        <?php 
    }
}
?>