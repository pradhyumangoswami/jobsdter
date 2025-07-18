<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_FAQs extends \Elementor\Widget_Base {
    public function get_name() {
        return 'faqs';
    }

    public function get_title() {
        return __('FAQs', 'jobster');
    }

    public function get_icon() {
        return 'eicon-help-o';
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
            'faqs_section',
            [
                'label' => __('Questions', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $faqs = new \Elementor\Repeater();

        $faqs->add_control(
            'faq_question',
            [
                'label' => __('Question', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter question', 'jobster'),
            ]
        );

        $faqs->add_control(
            'faq_answer',
            [
                'label' => __('Answer', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'input_type' => 'string',
                'placeholder' => __('Enter answer', 'jobster'),
            ]
        );

        $this->add_control(
            'faqs_list',
            [
                'label' => __('Questions List', 'jobster'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $faqs->get_controls(),
                'title_field' => '{{{ faq_question }}}',
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

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';

        $align_text = '';
        $align_items = '';
        if (isset($data['align']) && $data['align'] == 'c') {
            $align_text = 'text-center';
            $align_items = 'justify-content-center';
        } ?>

        <section class="pt-100">
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

                <div class="row mt-4 mt-lg-5 <?php echo esc_attr($align_items); ?>">
                    <div class="col-xxl-7">
                        <div 
                            class="accordion pxp-faqs-accordion <?php echo esc_attr($animation); ?>" 
                            id="pxpFAQsAccordion"
                        >
                            <?php if (isset($data['faqs_list']) 
                                    && is_array($data['faqs_list'])) {
                                $count_q = 1;
                                foreach ($data['faqs_list'] as $faq) { ?>
                                    <div class="accordion-item">
                                        <h3 
                                            class="accordion-header" 
                                            id="pxpFAQsHeader<?php echo esc_attr($count_q); ?>"
                                        >
                                            <button 
                                                class="accordion-button collapsed" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#pxpCollapseFAQs<?php echo esc_attr($count_q); ?>" 
                                                aria-expanded="false" 
                                                aria-controls="pxpCollapseFAQs<?php echo esc_attr($count_q); ?>"
                                            >
                                                <?php echo esc_html($faq['faq_question']); ?>
                                            </button>
                                        </h3>
                                        <div 
                                            id="pxpCollapseFAQs<?php echo esc_attr($count_q); ?>" 
                                            class="accordion-collapse collapse" 
                                            aria-labelledby="pxpFAQsHeader<?php echo esc_attr($count_q); ?>" 
                                            data-bs-parent="#pxpFAQsAccordion"
                                        >
                                            <div class="accordion-body">
                                                <?php echo esc_html($faq['faq_answer']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $count_q++;
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
?>