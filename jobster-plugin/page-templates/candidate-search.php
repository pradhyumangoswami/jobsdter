<?php
/*
Template Name: Candidate Search
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $post;
get_header();

$subtitle = get_post_meta($post->ID, 'candidates_page_subtitle', true);
$header_align = get_post_meta($post->ID, 'candidates_page_header_align', true);
$search_position = get_post_meta(
    $post->ID, 'candidates_page_search_position', true
);
$side_position = get_post_meta(
    $post->ID, 'candidates_page_side_position', true
);

$search_submit = jobster_get_page_link('candidate-search.php');

$header_align_class = ($header_align == 'center') ? 'text-center' : '';

$side_order_class = '';
$content_order_class = '';
if ($side_position == 'right') {
    $side_order_class = 'order-lg-last';
    $content_order_class = 'order-lg-first';
}

$sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'asc';
$searched_candidates = jobster_search_candidates();
$total_candidates = $searched_candidates->found_posts;

$is_company = false;
if (is_user_logged_in()) {
    global $current_user;

    $current_user = wp_get_current_user();
    $is_company = function_exists('jobster_user_is_company')
                    ? jobster_user_is_company($current_user->ID)
                    : false;
}

$candidates_settings = get_option('jobster_candidates_settings');
$restrict_list =    isset($candidates_settings['jobster_candidate_restrict_list_field']) 
                    ? $candidates_settings['jobster_candidate_restrict_list_field'] 
                    : ''; ?>

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
        <?php if (function_exists('jobster_get_search_candidates_form')
                    && $search_position == 'top') {
            jobster_get_search_candidates_form('top');
        } ?>
    </div>
</section>

<section class="mt-100">
    <div class="pxp-container">
        <?php if ($search_position == 'top') {
            $card_column_class = 'col-md-6 col-xl-4 col-xxl-3 pxp-candidates-card-1-container';
        } else {
            $card_column_class = 'col-md-6 col-lg-12 col-xl-6 col-xxl-4 pxp-candidates-card-1-container';
        } ?>
        <div class="row">
            <?php $content_column_class = 'col';
            $list_top_class = '';
            if ($search_position == 'side') {
                $content_column_class = 'col-lg-7 col-xl-8 col-xxl-9';
                $list_top_class = 'mt-4 mt-lg-0'; ?>
                <div class="col-lg-5 col-xl-4 col-xxl-3 <?php echo esc_attr($side_order_class); ?>">
                    <?php if ($search_position == 'side') {
                        jobster_get_search_candidates_form('side');
                    } ?>
                </div>
            <?php } ?>
            <div class="<?php echo esc_attr($content_column_class); ?> <?php echo esc_attr($content_order_class); ?>">
                <?php if ($restrict_list == '1' && !$is_company) { ?>
                    <p><i><?php esc_html_e('Restricted content. You need company account to have access.', 'jobster') ?></i></p>
                    <?php if (!is_user_logged_in()) { ?>
                        <button 
                            class="btn pxp-single-job-apply-btn pxp-section-cta rounded-pill" 
                            data-bs-toggle="modal" 
                            data-bs-target="#pxp-signin-modal"
                        >
                            <?php esc_html_e('Sign In Now', 'jobster'); ?>
                        </button>
                    <?php }
                } else { ?>
                    <div class="pxp-candidates-list-top <?php echo esc_attr($list_top_class); ?>">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <h2>
                                    <span class="pxp-text-light"><?php esc_html_e('Showing', 'jobster'); ?></span> 
                                    <?php echo esc_html($total_candidates); ?> 
                                    <span class="pxp-text-light"><?php esc_html_e('candidates', 'jobster'); ?></span>
                                </h2>
                            </div>
                            <div class="col-auto">
                                <select class="form-select" id="pxp-sort-candidates">
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
                        <?php while ($searched_candidates->have_posts()) {
                            $searched_candidates->the_post();
    
                            $candidate_id = get_the_ID();
                            $candidate_name = get_the_title($candidate_id);
                            $candidate_link = get_permalink($candidate_id);
                            $candidate_title = get_post_meta(
                                $candidate_id,
                                'candidate_title',
                                true
                            );
                            $location = wp_get_post_terms(
                                $candidate_id, 'candidate_location'
                            ); ?>
    
                            <div class="<?php echo esc_attr($card_column_class); ?>">
                                <div class="pxp-candidates-card-1 pxp-has-border text-center">
                                    <div class="pxp-candidates-card-1-top">
                                        <?php $candidate_photo_val = get_post_meta(
                                            $candidate_id,
                                            'candidate_photo',
                                            true
                                        );
                                        $candidate_photo = wp_get_attachment_image_src(
                                            $candidate_photo_val,
                                            'pxp-thmb'
                                        );
                                        if (is_array($candidate_photo)) { ?>
                                            <div 
                                                class="pxp-candidates-card-1-avatar pxp-cover" 
                                                style="background-image: url(<?php echo esc_url($candidate_photo[0]); ?>);"
                                            ></div>
                                        <?php } else { ?>
                                            <div class="pxp-candidates-card-1-avatar pxp-no-img">
                                                <?php echo esc_html($candidate_name[0]); ?>
                                            </div>
                                        <?php } ?>
                                        <div class="pxp-candidates-card-1-name">
                                            <?php echo esc_html($candidate_name); ?>
                                            <?php echo jobster_get_candidate_subscription_badge($candidate_id, 'small'); ?>
                                        </div>
                                        <div class="pxp-candidates-card-1-title">
                                            <?php echo esc_html($candidate_title); ?>
                                        </div>
                                        <?php if ($location) { ?>
                                            <div class="pxp-candidates-card-1-location">
                                                <span class="fa fa-globe"></span><?php echo esc_html($location[0]->name); ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="pxp-candidates-card-1-bottom">
                                        <div class="pxp-candidates-card-1-cta">
                                            <a href="<?php echo esc_url($candidate_link); ?>">
                                                <?php esc_html_e('View Profile', 'jobster'); ?>
                                                <span class="fa fa-angle-right"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <?php jobster_pagination($searched_candidates->max_num_pages);
                } ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>