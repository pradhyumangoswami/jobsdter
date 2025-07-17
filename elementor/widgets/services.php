<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Services extends \Elementor\Widget_Base {
    public function get_name() {
        return 'services';
    }

    public function get_title() {
        return __('Services', 'jobster');
    }

    public function get_icon() {
        return 'eicon-image-box';
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

        $this->end_controls_section();

        $this->start_controls_section(
            'services_section',
            [
                'label' => __('Services', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $services = new \Elementor\Repeater();

        $services->add_control(
            'service_image',
            [
                'label' => __('Card Image', 'jobster'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $services->add_control(
            'service_title',
            [
                'label' => __('Title', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter service title', 'jobster'),
            ]
        );

        $services->add_control(
            'service_text',
            [
                'label' => __('Text', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'input_type' => 'string',
                'placeholder' => __('Enter service text', 'jobster'),
            ]
        );

        $services->add_control(
            'service_link',
            [
                'label' => __('Service Link', 'jobster'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('Enter service link', 'jobster'),
                'show_external' => true,
            ]
        );

        $services->add_control(
            'service_cta',
            [
                'label' => __('CTA Label', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter service CTA label', 'jobster'),
            ]
        );

        $this->add_control(
            'services_list',
            [
                'label' => __('Services List', 'jobster'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $services->get_controls(),
                'title_field' => '{{{ service_title }}}',
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
            'color',
            [
                'label' => __('Card Color', 'jobster'),
                'type' => \Elementor\Controls_Manager::COLOR
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

        $this->add_control(
            'hanimation',
            [
                'label' => __('Card Hover Animation', 'jobster'),
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
        $hanimation =    isset($data['hanimation']) && $data['hanimation'] == 'e'
                        ? 'pxp-animate-icon'
                        : '';

        $align_text = '';
        $align_cards = 'justify-content-between';
        if (isset($data['align']) && $data['align'] == 'c') {
            $align_text = 'text-center';
            $align_cards = 'justify-content-evenly';
        }

        $section_id = uniqid(); ?>

        <section 
            class="mt-100" 
            id="pxp-services-1-<?php echo esc_attr($section_id); ?>"
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
                    <?php if (isset($data['services_list']) && is_array($data['services_list'])) {
                        foreach ($data['services_list'] as $service) {
                            $service_img = wp_get_attachment_image_src(
                                $service['service_image']['id'],
                                'pxp-full'
                            ); ?>

                            <div class="col-lg-4 col-xl-3 pxp-services-1-item-container">
                                <div class="pxp-services-1-item <?php echo esc_attr($align_text); ?> <?php echo esc_attr($hanimation); ?>">
                                    <?php if (is_array($service_img)) { ?>
                                        <div class="pxp-services-1-item-icon">
                                            <img 
                                                src="<?php echo esc_url($service_img[0]); ?>" 
                                                alt="<?php echo esc_attr($service['service_title']); ?>"
                                            >
                                        </div>
                                    <?php } ?>
                                    <div class="pxp-services-1-item-title">
                                        <?php echo esc_html($service['service_title']); ?>
                                    </div>
                                    <div class="pxp-services-1-item-text pxp-text-light">
                                        <?php echo esc_html($service['service_text']); ?>
                                    </div>
                                    <?php if ($service['service_cta'] != '') {
                                        $target =   $service['service_link']['is_external']
                                                    ? ' target="_blank"'
                                                    : '';
                                        $nofollow = $service['service_link']['nofollow']
                                                    ? ' rel="nofollow"'
                                                    : '';
                                        if ($service['service_link']['url'] != '') { ?>
                                            <div class="pxp-services-1-item-cta">
                                                <a href="<?php echo esc_url($service['service_link']['url']); ?>" <?php echo $target; ?> <?php echo $nofollow; ?>>
                                                    <?php echo esc_html($service['service_cta']); ?>
                                                    <span class="fa fa-angle-right"></span>
                                                </a>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>
        </section>

        <?php if (isset($data['color']) && $data['color'] != '') { ?>
            <style>
                #pxp-services-1-<?php echo esc_html($section_id); ?> .pxp-services-1-item-icon::before {
                    background-color: <?php echo esc_html($data['color']); ?>;
                }
            </style>
        <?php }
    }
}
?>