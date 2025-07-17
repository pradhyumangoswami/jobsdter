<?php
/*
Template Name: Company Search
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;
get_header();

$subtitle = get_post_meta($post->ID, 'companies_page_subtitle', true);
$header_align = get_post_meta($post->ID, 'companies_page_header_align', true);
$search_position = get_post_meta(
    $post->ID, 'companies_page_search_position', true
);
$side_position = get_post_meta($post->ID, 'companies_page_side_position', true);

$search_submit = jobster_get_page_link('company-search.php');

$header_align_class = ($header_align == 'center') ? 'text-center' : '';

$side_order_class = '';
$content_order_class = '';
if ($side_position == 'right') {
    $side_order_class = 'order-lg-last';
    $content_order_class = 'order-lg-first';
}

$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'asc';
$searched_companies = jobster_search_companies();
$total_companies = $searched_companies->found_posts; ?>

<section 
    class="pxp-page-header-simple" 
    style="background-color: var(--pxpSecondaryColorLight);"
>
    <div class="pxp-container">
        <h1 class="<?php echo esc_attr($header_align_class); ?>">
            <?php echo get_the_title(); ?>
        </h1>
        <div class="pxp-hero-subtitle pxp-text-light <?php echo esc_attr($header_align_class); ?>">
            <?php echo esc_html($subtitle); ?>
        </div>
        <?php if (function_exists('jobster_get_search_companies_form')
                    && $search_position == 'top') {
            jobster_get_search_companies_form('top');
        } ?>
    </div>
</section>

<section class="mt-100">
    <div class="pxp-container">
        <?php if ($search_position == 'top') {
            $card_column_class = 'col-md-6 col-xl-4 col-xxl-3 pxp-companies-card-1-container';
        } else {
            $card_column_class = 'col-md-6 col-lg-12 col-xl-6 col-xxl-4 pxp-companies-card-1-container';
        } ?>
        <div class="row">
            <?php $content_column_class = 'col';
            $list_top_class = '';
            if ($search_position == 'side') {
                $content_column_class = 'col-lg-7 col-xl-8 col-xxl-9';
                $list_top_class = 'mt-4 mt-lg-0'; ?>
                <div class="col-lg-5 col-xl-4 col-xxl-3 <?php echo esc_attr($side_order_class); ?>">
                    <?php if ($search_position == 'side') {
                        jobster_get_search_companies_form('side');
                    } ?>
                </div>
            <?php } ?>
            <div class="<?php echo esc_attr($content_column_class); ?> <?php echo esc_attr($content_order_class); ?>">
                <div class="pxp-companies-list-top <?php echo esc_attr($list_top_class); ?>">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto">
                            <h2>
                                <span class="pxp-text-light"><?php esc_html_e('Showing', 'jobster'); ?></span> 
                                <?php echo esc_html($total_companies); ?> 
                                <span class="pxp-text-light"><?php esc_html_e('companies', 'jobster'); ?></span>
                            </h2>
                        </div>
                        <div class="col-auto">
                            <select class="form-select" id="pxp-sort-companies">
                                <option 
                                    value="asc" 
                                    <?php selected($sort, 'asc'); ?>
                                >
                                    <?php esc_html_e('Name Asc', 'jobster'); ?>
                                </option>
                                <option 
                                    value="desc" 
                                    <?php selected($sort, 'desc'); ?>
                                >
                                    <?php esc_html_e('Name Desc', 'jobster'); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php while ($searched_companies->have_posts()) {
                        $searched_companies->the_post();

                        $company_id = get_the_ID();
                        $company_name = get_the_title($company_id);
                        $company_link = get_permalink($company_id);
                        $excerpt = get_the_excerpt($company_id); ?>

                        <div class="<?php echo esc_attr($card_column_class); ?>">
                            <div class="pxp-companies-card-1 pxp-has-border">
                                <div class="pxp-companies-card-1-top">
                                    <?php $company_logo_val = get_post_meta(
                                        $company_id,
                                        'company_logo',
                                        true
                                    );
                                    $company_logo = wp_get_attachment_image_src(
                                        $company_logo_val,
                                        'pxp-thmb'
                                    );
                                    if (is_array($company_logo)) { ?>
                                        <a 
                                            href="<?php echo esc_url($company_link); ?>" 
                                            class="pxp-companies-card-1-company-logo" 
                                            style="background-image: url(<?php echo esc_url($company_logo[0]); ?>);"
                                        ></a>
                                    <?php } else { ?>
                                        <a 
                                            href="<?php echo esc_url($company_link); ?>" 
                                            class="pxp-companies-card-1-company-logo pxp-no-img" 
                                        >
                                            <?php echo esc_html($company_name[0]); ?>
                                        </a>
                                    <?php } ?>
                                    <a 
                                        href="<?php echo esc_url($company_link); ?>" 
                                        class="pxp-companies-card-1-company-name"
                                    >
                                        <?php echo esc_html($company_name); ?>
                                    </a>
                                    <div class="pxp-companies-card-1-company-description pxp-text-light">
                                        <?php echo esc_html($excerpt); ?>
                                    </div>
                                </div>
                                <div class="pxp-companies-card-1-bottom">
                                    <a 
                                        href="<?php echo esc_url($company_link); ?>" 
                                        class="pxp-companies-card-1-company-jobs"
                                    >
                                        <?php echo esc_html(
                                            jobster_get_active_jobs_no_by_company_id($company_id)
                                        ); ?> <?php esc_html_e('jobs', 'jobster'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <?php jobster_pagination($searched_companies->max_num_pages); ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>