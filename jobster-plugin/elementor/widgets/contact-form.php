<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Contact_Form extends \Elementor\Widget_Base {
    public function get_name() {
        return 'contact_form';
    }

    public function get_title() {
        return __('Contact Form', 'jobster');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
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
            'email',
            [
                'label' => __('Email', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter email', 'jobster'),
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
                        : ''; ?>

        <section class="pt-100">
            <div class="pxp-container">
                <?php if (isset($data['title']) && $data['title'] != '') { ?>
                    <h2 class="pxp-section-h2 text-center">
                        <?php echo esc_html($data['title']); ?>
                    </h2>
                <?php }
                if (isset($data['subtitle']) && $data['subtitle'] != '') { ?>
                    <p class="pxp-text-light text-center">
                        <?php echo esc_html($data['subtitle']); ?>
                    </p>
                <?php } ?>

                <div class="row justify-content-center <?php echo esc_attr($animation); ?>">
                    <div class="col-lg-6 col-xxl-4">
                        <div class="pxp-contact-form-block-form">
                            <div class="mb-4 pxp-contact-form-block-response"></div>
                            <form class="mt-4">
                                <input 
                                    type="hidden" 
                                    id="pxp-contact-form-block-company-email" 
                                    value="<?php echo esc_attr($data['email']); ?>"
                                >
                                <div class="mb-3">
                                    <label 
                                        for="pxp-contact-form-block-name" 
                                        class="form-label"
                                    >
                                        <?php esc_html_e('Name', 'jobster'); ?>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="pxp-contact-form-block-name" 
                                        placeholder="<?php esc_attr_e('Enter your name', 'jobster'); ?>"
                                    >
                                </div>
                                <div class="mb-3">
                                    <label 
                                        for="pxp-contact-form-block-email" 
                                        class="form-label"
                                    >
                                        <?php esc_html_e('Email', 'jobster'); ?>
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="pxp-contact-form-block-email" 
                                        placeholder="<?php esc_attr_e('Enter your email address', 'jobster'); ?>"
                                    >
                                </div>
                                <div class="mb-3">
                                    <label 
                                        for="pxp-contact-form-block-message" 
                                        class="form-label"
                                    >
                                        <?php esc_html_e('Message', 'jobster'); ?>
                                    </label>
                                    <textarea 
                                        class="form-control" 
                                        id="pxp-contact-form-block-message" 
                                        placeholder="<?php esc_attr_e('Type your message here...', 'jobster'); ?>"
                                    ></textarea>
                                </div>
                                <?php wp_nonce_field(
                                    'contact_form_block_ajax_nonce',
                                    'pxp-contact-form-block-security'
                                ); ?>
                                <a 
                                    href="javascript:void(0);" 
                                    class="btn rounded-pill d-block pxp-contact-form-block-btn"
                                >
                                    <span class="pxp-contact-form-block-btn-text">
                                        <?php esc_html_e('Send Message', 'jobster'); ?>
                                    </span>
                                    <span class="pxp-contact-form-block-btn-loading pxp-btn-loading">
                                        <img 
                                            src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                            class="pxp-btn-loader" 
                                            alt="..."
                                        >
                                    </span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
?>