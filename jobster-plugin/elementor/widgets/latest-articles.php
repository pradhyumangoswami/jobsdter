<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_Latest_Articles extends \Elementor\Widget_Base {
    public function get_name() {
        return 'latest_articles';
    }

    public function get_title() {
        return __('Latest Articles', 'jobster');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
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
        $align_cards = '';
        if (isset($data['align']) && $data['align'] == 'c') {
            $align_text = 'text-center';
            $align_cards = 'justify-content-center';
        }

        $blog_url = get_permalink(get_option('page_for_posts'));

        $args = array(
            'numberposts'      => '4',
            'post_type'        => 'post',
            'order'            => 'DESC',
            'suppress_filters' => false,
            'post_status'      => 'publish'
        );

        $posts = wp_get_recent_posts($args); ?>

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
                    <?php foreach($posts as $post) : 
                        $post_image = wp_get_attachment_image_src(
                            get_post_thumbnail_id($post['ID']), 'pxp-gallery'
                        );
                        $post_excerpt = get_the_excerpt($post['ID']);
                        $post_link = get_permalink($post['ID']);

                        $categories = get_the_category($post['ID']);
                        $separator  = '&nbsp;&nbsp;&bull;&nbsp;&nbsp;';
                        $output     = '';

                        if ($categories) {
                            foreach ($categories as $category) {
                                $output .= '
                                    <a 
                                        class="pxp-posts-card-1-category" 
                                        href="' . esc_url(get_category_link($category->term_id)) . '" 
                                        title="' . esc_attr(sprintf(__('View all posts in %s','jobster'), $category->name)) . '"
                                    >
                                        ' . esc_html($category->cat_name) . '
                                    </a>' . esc_html($separator);
                            }
                            $post_categories = trim($output, $separator);
                        } ?>

                        <div class="col-md-6 col-xl-4 col-xxl-3 pxp-posts-card-1-container">
                            <div class="pxp-posts-card-1 pxp-has-border">
                                <div class="pxp-posts-card-1-top">
                                    <div class="pxp-posts-card-1-top-bg">
                                        <?php if ($post_image !== false) { ?>
                                            <div 
                                                class="pxp-posts-card-1-image pxp-cover" 
                                                style="background-image: url(<?php echo esc_url($post_image[0]); ?>);"
                                            ></div>
                                        <?php } ?>
                                        <div class="pxp-posts-card-1-info">
                                            <div class="pxp-posts-card-1-date">
                                                <?php echo get_the_date('', $post['ID']); ?>
                                            </div>
                                            <?php if (isset($post_categories)) { ?>
                                                <div class="pxp-posts-card-1-categories">
                                                    <?php echo $post_categories; ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="pxp-posts-card-1-content">
                                        <a 
                                            href="<?php echo esc_url($post_link); ?>" 
                                            class="pxp-posts-card-1-title"
                                        >
                                            <?php echo esc_html(get_the_title($post['ID'])); ?>
                                        </a>
                                        <div class="pxp-posts-card-1-summary pxp-text-light">
                                            <?php echo esc_html($post_excerpt); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="pxp-posts-card-1-bottom">
                                    <div class="pxp-posts-card-1-cta">
                                        <a href="<?php echo esc_url($post_link); ?>">
                                            <?php esc_html_e('Read more', 'jobster'); ?>
                                            <span class="fa fa-angle-right"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-4 mt-md-5 <?php echo esc_attr($animation); ?> <?php echo esc_attr($align_text); ?>">
                    <a 
                        href="<?php echo esc_url($blog_url); ?>" 
                        class="btn rounded-pill pxp-section-cta"
                    >
                        <?php esc_html_e('Read All Articles', 'jobster'); ?>
                        <span class="fa fa-angle-right"></span>
                    </a>
                </div>
            </div>
        </section>
    <?php }
}
?>