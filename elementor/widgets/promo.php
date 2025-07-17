<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Promo extends \Elementor\Widget_Base {
    public function get_name() {
        return 'promo';
    }

    public function get_title() {
        return __('Promo', 'jobster');
    }

    public function get_icon() {
        return 'eicon-banner';
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
            'text',
            [
                'label' => __('Text', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'input_type' => 'string',
                'placeholder' => __('Enter text', 'jobster'),
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __('Background Image', 'jobster'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('CTA Link', 'jobster'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('Enter link', 'jobster'),
                'show_external' => true,
            ]
        );

        $this->add_control(
            'cta',
            [
                'label' => __('CTA Label', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter label', 'jobster'),
            ]
        );

        $this->add_control(
            'position',
            [
                'label' => __('Position', 'jobster'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    's' => [
                        'title' => __('Start', 'jobster'),
                        'icon' => 'eicon-align-start-h',
                    ],
                    'e' => [
                        'title' => __('End', 'jobster'),
                        'icon' => 'eicon-align-end-h',
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
                'label' => __('Caption Type', 'jobster'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'l',
                'options' => array(
                    'l' => __('Light', 'jobster'),
                    'd' => __('Dark', 'jobster')
                )
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
        $type = isset($data['type']) && $data['type'] == 'd'
                ? 'pxp-dark'
                : '';

        $section_position = '';
        $row_position = '';
        if (isset($data['position']) && $data['position'] == 'e') {
            $section_position = 'pxp-end';
            $row_position = 'justify-content-end';
        }

        $cta_id = uniqid(); ?>

        <section class="mt-100">
            <div class="pxp-container">
                <?php if (isset($data['image']) && $data['image'] != '') {
                    $image = wp_get_attachment_image_src(
                        $data['image']['id'],
                        'full'
                    );

                    if (is_array($image)) { ?>
                        <div 
                            class="pxp-promo-img pxp-cover pt-100 pb-100 <?php echo esc_attr($animation); ?> <?php echo esc_attr($type); ?> <?php echo esc_attr($section_position); ?>" 
                            style="background-image: url(<?php echo esc_url($image[0]); ?>);"
                        >
                    <?php } else { ?>
                        <div class="pxp-promo-img pt-100 pb-100 <?php echo esc_attr($animation); ?> <?php echo esc_attr($type); ?> <?php echo esc_attr($section_position); ?>">
                    <?php } ?>
                <?php } else { ?>
                    <div class="pxp-promo-img pt-100 pb-100 <?php echo esc_attr($animation); ?> <?php echo esc_attr($type); ?> <?php echo esc_attr($section_position); ?>">
                <?php } ?>

                    <div class="row <?php echo esc_attr($row_position); ?>">
                        <div class="col-sm-7 col-lg-5">
                            <h2 class="pxp-section-h2">
                                <?php echo esc_html($data['title']); ?>
                            </h2>
                            <p class="pxp-text-light">
                                <?php echo esc_html($data['text']); ?>
                            </p>
                            <?php if (isset($data['cta']) && $data['cta'] != '') {
                                if ($data['link']['url'] != '') { 
                                    $target =   $data['link']['is_external']
                                                ? ' target="_blank"'
                                                : '';
                                    $nofollow = $data['link']['nofollow']
                                                ? ' rel="nofollow"'
                                                : ''; ?>

                                    <div class="mt-4 mt-md-5">
                                        <a 
                                            href="<?php echo esc_url($data['link']['url']); ?>" 
                                            id="pxp-promo-cta-<?php echo esc_attr($cta_id); ?>" 
                                            class="btn rounded-pill pxp-section-cta" 
                                            <?php echo $target; ?> <?php echo $nofollow; ?>
                                        >
                                            <?php echo esc_html($data['cta']); ?>
                                            <span class="fa fa-angle-right"></span>
                                        </a>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
?>