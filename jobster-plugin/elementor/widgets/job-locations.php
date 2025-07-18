<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Job_Locations extends \Elementor\Widget_Base {
    public function get_name() {
        return 'job_locations';
    }

    public function get_title() {
        return __('Job Locations', 'jobster');
    }

    public function get_icon() {
        return 'eicon-map-pin';
    }

    public function get_categories() {
        return ['jobster'];
    }

    private function jobster_get_locations() {
        $loc_terms = get_terms(
            array(
                'taxonomy' => 'job_location',
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
            'locations_section',
            [
                'label' => __('Locations', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $locations = new \Elementor\Repeater();

        $locations->add_control(
            'location_image',
            [
                'label' => __('Image', 'jobster'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $locations->add_control(
            'location_id',
            [
                'label' => __('Location', 'jobster'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '0',
                'options' => $this->jobster_get_locations(),
            ]
        );

        $this->add_control(
            'locations_list',
            [
                'label' => __('Locations List', 'jobster'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $locations->get_controls()
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
                    ]
                ],
                'default' => 'b',
                'toggle' => false
            ]
        );

        $this->add_control(
            'bg',
            [
                'label' => __('Card Background Color', 'jobster'),
                'type' => \Elementor\Controls_Manager::COLOR
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

        $align_text = '';
        $align_cards = '';
        if (isset($data['align']) && $data['align'] == 'c') {
            $align_text = 'text-center';
            $align_cards = 'justify-content-center';
        }

        $search_jobs_url = jobster_get_page_link('job-search.php'); ?>

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
                    <?php if (isset($data['locations_list']) && is_array($data['locations_list'])) {
                        $locations = $data['locations_list'];

                        for ($i = 0; $i < count($locations); $i++) {
                            $locations[$i]['jobs_count'] = jobster_filter_form_count_jobs_by_term(
                                'job_location',
                                $locations[$i]['location_id']
                            );
                        }

                        usort($locations, function($a, $b) {
                            if ($a['jobs_count'] == $b['jobs_count']) {
                                return 0;
                            }
                            return ($a['jobs_count'] < $b['jobs_count']) ? 1 : -1;
                        });

                        switch ($data['type']) {
                            case 'b':
                                foreach ($locations as $location) {
                                    $location_id = trim($location['location_id'], '*');
                                    $term = get_term_by(
                                        'id',
                                        $location_id,
                                        'job_location'
                                    );

                                    $location_link = add_query_arg(
                                        'location',
                                        $location_id,
                                        $search_jobs_url
                                    );

                                    $location_img = wp_get_attachment_image_src(
                                        $location['location_image']['id'],
                                        'pxp-gallery'
                                    );

                                    if ($term !== false) { ?>
                                        <div class="col-md-6 col-xl-4 col-xxl-3">
                                            <?php if (isset($data['bg']) && $data['bg'] != '') { ?>
                                                <a 
                                                    href="<?php echo esc_url($location_link); ?>" 
                                                    class="pxp-cities-card-2" 
                                                    style="background-color: <?php echo esc_attr($data['bg']); ?>"
                                                >
                                            <?php } else { ?>
                                                <a 
                                                    href="<?php echo esc_url($location_link); ?>" 
                                                    class="pxp-cities-card-2"
                                                >
                                            <?php }
                                                if (is_array($location_img)) { ?>
                                                    <div class="pxp-cities-card-2-image-container">
                                                        <div 
                                                            class="pxp-cities-card-2-image pxp-cover" 
                                                            style="background-image: url(<?php echo esc_url($location_img[0]); ?>);"
                                                        ></div>
                                                    </div>
                                                <?php } ?>
                                                <div class="pxp-cities-card-2-info">
                                                    <div class="pxp-cities-card-2-name">
                                                        <?php echo esc_html($term->name); ?>
                                                    </div>
                                                    <div class="pxp-cities-card-2-jobs">
                                                        <?php echo esc_html($location['jobs_count']); ?> 
                                                        <?php esc_html_e('open positions', 'jobster'); ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php }
                                }
                                break;
                            case 's':
                                foreach ($locations as $location) {
                                    $location_id = trim($location['location_id'], '*');
                                    $term = get_term_by(
                                        'id',
                                        $location_id,
                                        'job_location'
                                    );

                                    $location_link = add_query_arg(
                                        'location',
                                        $location_id,
                                        $search_jobs_url
                                    );
            
                                    $location_img = wp_get_attachment_image_src(
                                        $location['location_image']['id'],
                                        'pxp-gallery'
                                    );
                                    $card_container_class = '';
                                    if (!is_array($location_img)) {
                                        $card_container_class = 'pxp-no-img';
                                    }

                                    if ($term !== false) { ?>
                                        <div class="col-12 col-md-4 col-lg-3 col-xxl-2 pxp-cities-card-1-container <?php echo esc_attr($card_container_class); ?>">
                                            <?php if (isset($data['bg']) && $data['bg'] != '') { ?>
                                                <a 
                                                    href="<?php echo esc_url($location_link); ?>" 
                                                    class="pxp-cities-card-1 text-center" 
                                                    style="background-color: <?php echo esc_attr($data['bg']); ?>"
                                                >
                                            <?php } else { ?>
                                                <a 
                                                    href="<?php echo esc_url($location_link); ?>" 
                                                    class="pxp-cities-card-1 text-center"
                                                >
                                            <?php } ?>
                                                <div class="pxp-cities-card-1-top">
                                                    <?php if (is_array($location_img)) { ?>
                                                        <div 
                                                            class="pxp-cities-card-1-image pxp-cover" 
                                                            style="background-image: url(<?php echo esc_url($location_img[0]); ?>);"
                                                        ></div>
                                                    <?php } ?>
                                                    <div class="pxp-cities-card-1-name">
                                                        <?php echo esc_html($term->name); ?>
                                                    </div>
                                                </div>
                                                <div class="pxp-cities-card-1-bottom">
                                                    <div class="pxp-cities-card-1-jobs">
                                                        <?php echo esc_html($location['jobs_count']); ?> 
                                                        <?php esc_html_e('open positions', 'jobster'); ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php }
                                }
                                break;
                            default:
                                foreach ($locations as $location) {
                                    $location_id = trim($location['location_id'], '*');
                                    $term = get_term_by(
                                        'id',
                                        $location_id,
                                        'job_location'
                                    );
            
                                    $location_link = add_query_arg(
                                        'location',
                                        $location_id,
                                        $search_jobs_url
                                    );
            
                                    $location_img = wp_get_attachment_image_src(
                                        $location['location_image']['id'],
                                        'pxp-gallery'
                                    );

                                    if ($term !== false) { ?>
                                        <div class="col-md-6 col-xl-4 col-xxl-3">
                                            <?php if (isset($data['bg']) && $data['bg'] != '') { ?>
                                                <a 
                                                    href="<?php echo esc_url($location_link); ?>" 
                                                    class="pxp-cities-card-2" 
                                                    style="background-color: <?php echo esc_attr($data['bg']); ?>"
                                                >
                                            <?php } else { ?>
                                                <a 
                                                    href="<?php echo esc_url($location_link); ?>" 
                                                    class="pxp-cities-card-2"
                                                >
                                            <?php }
                                                if (is_array($location_img)) { ?>
                                                    <div class="pxp-cities-card-2-image-container">
                                                        <div 
                                                            class="pxp-cities-card-2-image pxp-cover" 
                                                            style="background-image: url(<?php echo esc_url($location_img[0]); ?>);"
                                                        ></div>
                                                    </div>
                                                <?php } ?>
                                                <div class="pxp-cities-card-2-info">
                                                    <div class="pxp-cities-card-2-name">
                                                        <?php echo esc_html($term->name); ?>
                                                    </div>
                                                    <div class="pxp-cities-card-2-jobs">
                                                        <?php echo esc_html($location['jobs_count']); ?> 
                                                        <?php esc_html_e('open positions', 'jobster'); ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php }
                                }
                                break;
                        }
                    } ?>
                </div>
            </div>
        </section>
    <?php }
}
?>