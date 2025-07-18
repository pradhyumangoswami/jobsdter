<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Search_Jobs extends \Elementor\Widget_Base {
    public function get_name() {
        return 'search_jobs';
    }

    public function get_title() {
        return __('Search Jobs', 'jobster');
    }

    public function get_icon() {
        return 'eicon-search-bold';
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

        $this->end_controls_section();

        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'jobster'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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

        $animation =    isset($data['animation']) && $data['animation'] == 'e'
                        ? 'pxp-animate-in pxp-animate-in-top'
                        : '';
        $type = isset($data['type']) && $data['type'] == 'd'
                ? 'pxp-dark'
                : '';

        $system = isset($data['system']) && $data['system'] == 'c' ? 'c' : 'd';

        $section_padding = '';
        $form_css = 'pxp-has-border';
        if (isset($data['image']) && $data['image'] != '') {
            if (isset($data['image']['id']) && $data['image']['id'] != '') {
                $section_padding = 'pt-100 pb-100';
                $form_css = '';
            }
        }

        $margin = 'mt-100';
        if (isset($data['margin']) && $data['margin'] != 'y') {
            $margin = 'mt-5 mt-xl-0';
        } ?>

        <section class="<?php echo esc_attr($margin); ?>">
            <div class="pxp-container">
                <?php if (isset($data['image']) && $data['image'] != '') {
                    $image = wp_get_attachment_image_src(
                        $data['image']['id'],
                        'full'
                    );

                    if (is_array($image)) { ?>
                        <div 
                            class="pxp-search-jobs-img pxp-cover <?php echo esc_attr($section_padding); ?> <?php echo esc_attr($animation); ?> <?php echo esc_attr($type); ?>" 
                            style="background-image: url(<?php echo esc_url($image[0]); ?>);"
                        >
                    <?php } else { ?>
                        <div class="pxp-search-jobs-img pxp-no-img <?php echo esc_attr($animation); ?> <?php echo esc_attr($type); ?>">
                    <?php } ?>
                <?php } else { ?>
                    <div class="pxp-search-jobs-img pxp-no-img <?php echo esc_attr($animation); ?> <?php echo esc_attr($type); ?>">
                <?php } ?>
                    <h2 class="pxp-section-h2 text-center">
                        <?php echo esc_html($data['title']); ?>
                    </h2>
                    <p class="pxp-text-light text-center">
                        <?php echo esc_html($data['text']); ?>
                    </p>
                    <?php if ($system) {
                        if ($system == 'c') {
                            if (function_exists('jobster_get_careerjet_section_search_jobs_form')) {
                                print jobster_get_careerjet_section_search_jobs_form($form_css);
                            }
                        } else {
                            if (function_exists('jobster_get_section_search_jobs_form')) {
                                print  jobster_get_section_search_jobs_form($form_css);
                            }
                        }
                    } ?>
                </div>
            </div>
        </section>
    <?php }
}
?>