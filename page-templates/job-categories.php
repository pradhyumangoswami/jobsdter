<?php
/*
Template Name: Job Categories
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;
get_header();

$subtitle      = get_post_meta($post->ID, 'job_categories_page_subtitle', true);
$header_align  = get_post_meta($post->ID, 'job_categories_page_header_align', true);
$side_position = get_post_meta($post->ID, 'job_categories_page_side_position', true);
$sort          = get_post_meta($post->ID, 'job_categories_page_sort', true);
$design        = get_post_meta($post->ID, 'job_categories_page_design', true);
$icon          = get_post_meta($post->ID, 'job_categories_page_icon', true);

$search_jobs_url = jobster_get_page_link('job-search.php');

$header_align_class = ($header_align == 'center') ? 'text-center' : '';

$side_order_class = '';
$content_order_class = '';
if ($side_position == 'right') {
    $side_order_class = 'order-lg-last';
    $content_order_class = 'order-lg-first';
}

$category_tax = array( 
    'job_category'
);
$category_args = array(
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
);

$v_card = $icon == 'o' ? 'pxp-categories-card-1' : 'pxp-categories-card-2';

$category_terms = get_terms(
    $category_tax,
    $category_args
);

foreach ($category_terms as $category_term) {
    $category_term->jobs_count = jobster_filter_form_count_jobs_by_term(
        'job_category',
        $category_term->term_id
    );
}

if ($sort === 'j') {
    usort($category_terms, function($a, $b) {
        if ($a->jobs_count == $b->jobs_count) {
            return 0;
        }
        return ($a->jobs_count < $b->jobs_count) ? 1 : -1;
    });
} ?>

<section class="pxp-page-header-simple" style="background-color: var(--pxpMainColorLight);">
    <div class="pxp-container">
        <h1 class="<?php echo esc_attr($header_align_class); ?>">
            <?php echo get_the_title(); ?>
        </h1>
        <div class="pxp-hero-subtitle pxp-text-light <?php echo esc_attr($header_align_class); ?>">
            <?php echo esc_html($subtitle); ?>
        </div>
    </div>
</section>

<section class="mt-100">
    <div class="pxp-container">
        <div class="row">
            <div class="col-lg-5 col-xl-4 col-xxl-3 <?php echo esc_attr($side_order_class); ?>">
                <?php jobster_get_search_jobs_form('side');
                jobster_get_filter_jobs_form('side', true); ?>
            </div>
            <div class="col-lg-7 col-xl-8 col-xxl-9 <?php echo esc_attr($content_order_class); ?>">
                <div class="row mt-4 mt-lg-0">
                    <?php foreach ($category_terms as $category_term) {
                        $category_link = add_query_arg(
                            'category',
                            $category_term->term_id,
                            $search_jobs_url
                        );
                        $category_icon = get_term_meta(
                            $category_term->term_id,
                            'job_category_icon',
                            true
                        );

                        if ($design == 'h') { ?>
                            <div class="col-lg-6 pxp-categories-card-3-container mt-0 mb-4">
                                <a 
                                    href="<?php echo esc_url($category_link); ?>" 
                                    class="pxp-categories-card-3"
                                >
                                    <?php if (!empty($category_icon)) {
                                        $category_icon_type = get_term_meta(
                                            $category_term->term_id,
                                            'job_category_icon_type',
                                            true
                                        );
                                        if ($category_icon_type == 'image') {
                                            $icon_image = wp_get_attachment_image_src(
                                                $category_icon,
                                                'pxp-icon'
                                            );
                                            if (is_array($icon_image)) { ?>
                                                <div class="pxp-categories-card-3-icon-image">
                                                    <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="pxp-categories-card-3-icon">
                                                    <span class="fa fa-folder-o"></span>
                                                </div>
                                            <?php }
                                        } else { ?>
                                            <div class="pxp-categories-card-3-icon">
                                                <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                            </div>
                                        <?php }
                                    } else { ?>
                                        <div class="pxp-categories-card-3-icon">
                                            <span class="fa fa-folder-o"></span>
                                        </div>
                                    <?php } ?>
                                    <div class="pxp-categories-card-3-text">
                                        <div class="pxp-categories-card-3-title">
                                            <?php echo esc_html($category_term->name); ?>
                                        </div>
                                        <div class="pxp-categories-card-3-subtitle">
                                            <?php echo esc_html($category_term->jobs_count) . ' ' . esc_html__('open positions', 'jobster'); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } else { ?>
                            <div class="col-12 col-md-4 col-lg-3 <?php echo esc_attr($v_card); ?>-container mt-0 mb-4">
                                <a 
                                    href="<?php echo esc_url($category_link); ?>" 
                                    class="<?php echo esc_attr($v_card); ?>"
                                >
                                    <div class="<?php echo esc_attr($v_card); ?>-icon-container">
                                        <?php if (!empty($category_icon)) {
                                            $category_icon_type = get_term_meta(
                                                $category_term->term_id,
                                                'job_category_icon_type',
                                                true
                                            );
                                            if ($category_icon_type == 'image') {
                                                $icon_image = wp_get_attachment_image_src(
                                                    $category_icon,
                                                    'pxp-icon'
                                                );
                                                if (is_array($icon_image)) { ?>
                                                    <div class="<?php echo esc_attr($v_card); ?>-icon-image">
                                                        <span style="background-image: url(<?php echo esc_url($icon_image[0]); ?>);"></span>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                        <span class="fa fa-folder-o"></span>
                                                    </div>
                                                <?php }
                                            } else { ?>
                                                <div class="<?php echo esc_attr($v_card); ?>-icon">
                                                    <span class="<?php echo esc_attr($category_icon); ?>"></span>
                                                </div>
                                            <?php }
                                        } else { ?>
                                            <div class="<?php echo esc_attr($v_card)?>-icon">
                                                <span class="fa fa-folder-o"></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="<?php echo esc_attr($v_card); ?>-title">
                                        <?php echo esc_html($category_term->name); ?>
                                    </div>
                                    <div class="<?php echo esc_attr($v_card); ?>-subtitle">
                                        <?php echo esc_html($category_term->jobs_count) . ' ' . esc_html__('open positions', 'jobster'); ?>
                                    </div>
                                </a>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>