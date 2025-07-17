<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Subscribe extends \Elementor\Widget_Base {
    public function get_name() {
        return 'subscribe';
    }

    public function get_title() {
        return __('Subscribe', 'jobster');
    }

    public function get_icon() {
        return 'eicon-email-field';
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

        <section class="mt-100">
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
                <div class="row mt-4 mt-md-5 justify-content-center">
                    <div class="col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                        <div class="pxp-subscribe-1-container <?php echo esc_attr($animation); ?>">
                            <?php if (isset($data['image']) && $data['image'] != '') {
                                $image = wp_get_attachment_image_src($data['image']['id'], 'pxp-full');

                                if (is_array($image)) { ?>
                                    <div class="pxp-subscribe-1-image">
                                        <img 
                                            src="<?php echo esc_url($image[0]); ?>" 
                                            alt="<?php echo esc_attr($data['title']); ?>"
                                        >
                                    </div>
                                <?php }
                            } ?>
                            <div class="pxp-subscribe-1-form">
                                <form>
                                    <?php wp_nonce_field(
                                        'subscribe_ajax_nonce',
                                        'pxp-subscribe-block-security'
                                    ); ?>
                                    <div class="pxp-subscribe-1-form-response"></div>
                                    <div class="input-group">
                                        <input 
                                            type="email" 
                                            class="form-control" 
                                            id="pxp-subscribe-1-form-email" 
                                            placeholder="<?php esc_attr_e('Enter your email...', 'jobster'); ?>"
                                        >
                                        <button 
                                            class="btn btn-primary pxp-subscribe-1-form-btn" 
                                            type="button"
                                        >
                                            <span class="pxp-subscribe-1-form-btn-text">
                                                <?php esc_html_e('Subscribe', 'jobster'); ?>
                                            </span>
                                            <span class="pxp-subscribe-1-form-btn-loading pxp-btn-loading">
                                                <img 
                                                    src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                                    class="pxp-btn-loader" 
                                                    alt="..."
                                                >
                                            </span>
                                            <span class="fa fa-angle-right"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
?>