<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Features extends \Elementor\Widget_Base {
    public function get_name() {
        return 'features';
    }

    public function get_title() {
        return __('Features', 'jobster');
    }

    public function get_icon() {
        return 'eicon-posts-ticker';
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
                'label' => __('Image', 'jobster'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'card_1_section',
            [
                'label' => __('Info Card 1', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'icard_no_1',
            [
                'label' => __('Card Number', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter number', 'jobster'),
            ]
        );

        $this->add_control(
            'icard_label_1',
            [
                'label' => __('Card Label', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter label', 'jobster'),
            ]
        );

        $this->add_control(
            'icard_text_1',
            [
                'label' => __('Card Text', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter text', 'jobster'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'card_2_section',
            [
                'label' => __('Info Card 2', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'icard_no_2',
            [
                'label' => __('Card Number', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter number', 'jobster'),
            ]
        );

        $this->add_control(
            'icard_label_2',
            [
                'label' => __('Card Label', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter label', 'jobster'),
            ]
        );

        $this->add_control(
            'icard_text_2',
            [
                'label' => __('Card Text', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter text', 'jobster'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'card_3_section',
            [
                'label' => __('Info Card 3', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'icard_no_3',
            [
                'label' => __('Card Number', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter number', 'jobster'),
            ]
        );

        $this->add_control(
            'icard_label_3',
            [
                'label' => __('Card Label', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter label', 'jobster'),
            ]
        );

        $this->add_control(
            'icard_text_3',
            [
                'label' => __('Card Text', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter text', 'jobster'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'cta_section',
            [
                'label' => __('CTA', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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

        $this->end_controls_section();

        $this->start_controls_section(
            'features_section',
            [
                'label' => __('Features', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $features = new \Elementor\Repeater();

        $features->add_control(
            'feature_text',
            [
                'label' => __('Feature Text', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter text', 'jobster'),
            ]
        );

        $this->add_control(
            'features_list',
            [
                'label' => __('Features List', 'jobster'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $features->get_controls()
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

        $animation_right = '';
        $animation_top = '';
        $animation_bounce = '';
        if (isset($data['animation']) && $data['animation'] == 'e') {
            $animation_right = 'pxp-animate-in pxp-animate-in-right';
            $animation_top = 'pxp-animate-in pxp-animate-in-top';
            $animation_bounce = 'pxp-animate-in pxp-animate-bounce';
        } ?>

        <section class="mt-100">
            <div class="pxp-container">
                <div class="row justify-content-between align-items-center mt-4 mt-md-5">
                    <div class="col-lg-6 col-xxl-5">
                        <div class="pxp-info-fig '<?php echo esc_attr($animation_right); ?>">
                            <?php if (isset($data['image']) && $data['image'] != '') {
                                $image = wp_get_attachment_image_src($data['image']['id'], 'full'); ?>

                                <div 
                                    class="pxp-info-fig-image pxp-cover" 
                                    style="background-image: url(<?php echo esc_url($image[0]); ?>);"
                                ></div>
                            <?php } else { ?>
                                <div class="pxp-info-fig-image"></div>
                            <?php } ?>

                            <div class="pxp-info-stats">
                                <?php if (isset($data['icard_text_1']) 
                                        && $data['icard_text_1'] != '') { ?>
                                    <div class="pxp-info-stats-item <?php echo esc_attr($animation_bounce); ?>">
                                        <div class="pxp-info-stats-item-number">
                                            <?php if (isset($data['icard_no_1']) 
                                                    && $data['icard_no_1'] != '') {
                                                echo esc_html($data['icard_no_1']);
                                            } ?>
                                            <span>
                                                <?php echo esc_html($data['icard_label_1']); ?>
                                            </span>
                                        </div>
                                        <div class="pxp-info-stats-item-description">
                                            <?php echo esc_html($data['icard_text_1']); ?>
                                        </div>
                                    </div>
                                <?php }
                                if (isset($data['icard_text_2']) 
                                    && $data['icard_text_2'] != '') { ?>
                                    <div class="pxp-info-stats-item <?php echo esc_attr($animation_bounce); ?>">
                                        <div class="pxp-info-stats-item-number">
                                            <?php if (isset($data['icard_no_2']) 
                                                    && $data['icard_no_2'] != '') {
                                                echo esc_html($data['icard_no_2']);
                                            } ?>
                                            <span>
                                                <?php echo esc_html($data['icard_label_2']); ?>
                                            </span>
                                        </div>
                                        <div class="pxp-info-stats-item-description">
                                            <?php echo esc_html($data['icard_text_2']); ?>
                                        </div>
                                    </div>
                                <?php }
                                if (isset($data['icard_text_3']) 
                                    && $data['icard_text_3'] != '') { ?>
                                    <div class="pxp-info-stats-item <?php echo esc_attr($animation_bounce); ?>">
                                        <div class="pxp-info-stats-item-number">
                                            <?php if (isset($data['icard_no_3']) 
                                                    && $data['icard_no_3'] != '') {
                                                echo esc_html($data['icard_no_3']);
                                            } ?>
                                            <span>
                                                <?php echo esc_html($data['icard_label_3']); ?>
                                            </span>
                                        </div>
                                        <div class="pxp-info-stats-item-description">
                                            <?php echo esc_html($data['icard_text_3']); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xxl-6">
                        <div class="pxp-info-caption mt-4 mt-sm-5 mt-lg-0 <?php echo esc_attr($animation_top); ?>">
                            <?php if (isset($data['title']) 
                                    && $data['title'] != '') { ?>
                                <h2 class="pxp-section-h2">
                                    <?php echo esc_html($data['title']); ?>
                                </h2>
                            <?php }
                            if (isset($data['text']) 
                                && $data['text'] != '') { ?>
                                <p class="pxp-text-light">
                                    <?php echo esc_html($data['text']); ?>
                                </p>
                            <?php }
                            if (isset($data['features_list']) 
                                && is_array($data['features_list'])) { ?>
                                <div class="pxp-info-caption-list">
                                    <?php foreach ($data['features_list'] as $feature) { ?>
                                        <div class="pxp-info-caption-list-item">
                                            <img 
                                                src="<?php echo  esc_url(JOBSTER_PLUGIN_PATH . 'images/check.svg'); ?>" 
                                                alt="-"
                                            >
                                            <span><?php echo esc_html($feature['feature_text']); ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php }
                            if (isset($data['cta']) && $data['cta'] != '') {
                                if ($data['link']['url'] != '') { 
                                    $target =   $data['link']['is_external']
                                                ? ' target="_blank"'
                                                : '';
                                    $nofollow = $data['link']['nofollow']
                                                ? ' rel="nofollow"'
                                                : ''; ?>

                                    <div class="pxp-info-caption-cta">
                                        <a 
                                            href="<?php echo esc_url($data['link']['url']); ?>" 
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