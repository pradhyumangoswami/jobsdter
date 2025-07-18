<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Testimonials extends \Elementor\Widget_Base {
    public function get_name() {
        return 'testimonials';
    }

    public function get_title() {
        return __('Testimonials', 'jobster');
    }

    public function get_icon() {
        return 'eicon-blockquote';
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

        $args = array(
            'numberposts'      => -1,
            'post_type'        => 'testimonial',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'suppress_filters' => false,
            'post_status'      => 'publish'
        );

        $posts = wp_get_recent_posts($args); ?>

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
                <div class="pxp-testimonials-1">
                    <div class="pxp-testimonials-1-circles d-none d-md-block">
                        <div 
                            class="pxp-testimonials-1-circles-item pxp-item-1 pxp-cover pxp-animate-in pxp-animate-bounce" 
                            style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-1.png'); ?>);"
                        ></div>
                        <div class="pxp-testimonials-1-circles-item pxp-item-2 pxp-animate-in pxp-animate-bounce"></div>
                        <div class="pxp-testimonials-1-circles-item pxp-item-3 pxp-animate-in pxp-animate-bounce"></div>
                        <div 
                            class="pxp-testimonials-1-circles-item pxp-item-4 pxp-cover pxp-animate-in pxp-animate-bounce" 
                            style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-2.png'); ?>);"
                        ></div>
                        <div 
                            class="pxp-testimonials-1-circles-item pxp-item-5 pxp-cover pxp-animate-in pxp-animate-bounce" 
                            style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-3.png'); ?>);"
                        ></div>
                        <div class="pxp-testimonials-1-circles-item pxp-item-6 pxp-animate-in pxp-animate-bounce"></div>
                        <div 
                            class="pxp-testimonials-1-circles-item pxp-item-7 pxp-cover pxp-animate-in pxp-animate-bounce" 
                            style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-4.png'); ?>);"
                        ></div>
                        <div class="pxp-testimonials-1-circles-item pxp-item-8 pxp-animate-in pxp-animate-bounce"></div>
                        <div 
                            class="pxp-testimonials-1-circles-item pxp-item-9 pxp-cover pxp-animate-in pxp-animate-bounce" 
                            style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-5.png'); ?>);"
                        ></div>
                        <div 
                            class="pxp-testimonials-1-circles-item pxp-item-10 pxp-cover pxp-animate-in pxp-animate-bounce" 
                            style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'images/customer-6.png'); ?>);"
                        ></div>
                    </div>

                    <div class="pxp-testimonials-1-carousel-container">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-10 col-md-6 col-lg-6 col-xl-5 col-xxl-4">
                                <div class="pxp-testimonials-1-carousel <?php echo esc_attr($animation); ?>">
                                    <div 
                                        id="pxpTestimonials1Carousel" 
                                        class="carousel slide" data-bs-ride="carousel"
                                    >
                                        <div class="carousel-inner">
                                            <?php $count = 0;
                                            foreach ($posts as $post) {
                                                $text = get_post_meta($post['ID'], 'testimonial_text', true);
                                                $location = get_post_meta($post['ID'], 'testimonial_location', true);

                                                $active_class = $count == 0 ? 'active' : ''; ?>

                                                <div class="carousel-item text-center <?php echo esc_attr($active_class); ?>">
                                                    <div class="pxp-testimonials-1-carousel-item-text">
                                                        <?php echo esc_html($text); ?>
                                                    </div>
                                                    <div class="pxp-testimonials-1-carousel-item-name">
                                                        <?php echo esc_html($post['post_title']); ?>
                                                    </div>
                                                    <div class="pxp-testimonials-1-carousel-item-company">
                                                        <?php echo esc_html($location); ?>
                                                    </div>
                                                </div>
                                                <?php $count++;
                                            } ?>
                                        </div>
                                        <button 
                                            class="carousel-control-prev" 
                                            type="button" 
                                            data-bs-target="#pxpTestimonials1Carousel" 
                                            data-bs-slide="prev"
                                        >
                                            <span class="fa fa-angle-left" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button 
                                            class="carousel-control-next" 
                                            type="button" 
                                            data-bs-target="#pxpTestimonials1Carousel" 
                                            data-bs-slide="next"
                                        >
                                            <span class="fa fa-angle-right" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }
}
?>