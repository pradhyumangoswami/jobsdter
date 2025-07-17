<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Contact_Info extends \Elementor\Widget_Base {
    public function get_name() {
        return 'contact_info';
    }

    public function get_title() {
        return __('Contact Info', 'jobster');
    }

    public function get_icon() {
        return 'eicon-globe';
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
            'location',
            [
                'label' => __('Location', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter location/city', 'jobster'),
            ]
        );

        $this->add_control(
            'phone',
            [
                'label' => __('Phone', 'jobster'),
                'label_block' => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'string',
                'placeholder' => __('Enter phone', 'jobster'),
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

                <div class="row mt-4 mt-md-5 justify-content-center <?php echo esc_attr($animation); ?>">
                    <?php if (isset($data['location']) && $data['location'] != '') { ?>
                        <div class="col-lg-4 col-xxl-3 pxp-contact-card-1-container">
                            <div class="pxp-contact-card-1">
                                <div class="pxp-contact-card-1-icon-container">
                                    <div class="pxp-contact-card-1-icon">
                                        <span class="fa fa-globe"></span>
                                    </div>
                                </div>
                                <div class="pxp-contact-card-1-title">
                                    <?php echo esc_html($data['location']); ?>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if (isset($data['phone']) && $data['phone'] != '') { ?>
                        <div class="col-lg-4 col-xxl-3 pxp-contact-card-1-container">
                            <a 
                                href="tel:<?php echo esc_attr($data['phone']); ?>" 
                                class="pxp-contact-card-1"
                            >
                                <div class="pxp-contact-card-1-icon-container">
                                    <div class="pxp-contact-card-1-icon">
                                        <span class="fa fa-phone"></span>
                                    </div>
                                </div>
                                <div class="pxp-contact-card-1-title">
                                    <?php echo esc_html($data['phone']); ?>
                                </div>
                            </a>
                        </div>
                    <?php }
                    if (isset($data['email']) && $data['email'] != '') { ?>
                        <div class="col-lg-4 col-xxl-3 pxp-contact-card-1-container">
                            <a 
                                href="mailto:<?php echo esc_attr($data['email']); ?>" 
                                class="pxp-contact-card-1"
                            >
                                <div class="pxp-contact-card-1-icon-container">
                                    <div class="pxp-contact-card-1-icon">
                                        <span class="fa fa-envelope-o"></span>
                                    </div>
                                </div>
                                <div class="pxp-contact-card-1-title">
                                    <?php echo esc_html($data['email']); ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
    <?php }
}
?>