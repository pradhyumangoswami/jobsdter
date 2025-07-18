<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

$feat_args = array(
    'posts_per_page' => 4,
    'post_type'      => 'post',
    'orderby'        => 'post_date',
    'order'          => 'DESC',
    'meta_key'       => 'post_featured',
    'meta_value'     => '1',
    'post_status'    => 'publish'
);

$feat_posts = new wp_query($feat_args);
$posts_arr = array();

while ($feat_posts->have_posts()) {
    $feat_posts->the_post();

    $post_id = get_the_ID();

    $post = array();

    $post_image = wp_get_attachment_image_src(
        get_post_thumbnail_id($post_id), 'pxp-full'
    );
    $post['img'] = $post_image !== false ? $post_image[0] : false;

    $post['title'] = get_the_title($post_id);
    $post['excerpt'] = get_the_excerpt($post_id);
    $post['permalink'] = get_permalink($post_id);
    $post['date'] = get_the_date('', $post_id);

    array_push($posts_arr, $post);
}

wp_reset_postdata();

if (count($posts_arr) > 0) { ?>
    <div 
        id="pxp-blog-featured-posts-carousel" 
        class="carousel slide carousel-fade pxp-blog-featured-posts-carousel" 
        data-bs-ride="carousel"
    >
        <div class="carousel-inner">
            <?php $feat_count = 0;
            foreach ($posts_arr as $post_item) {
                $active_class = $feat_count == 0 ? 'active' : ''; ?>

                <div class="carousel-item <?php echo esc_attr($active_class); ?>">
                    <div 
                        class="pxp-featured-posts-item pxp-cover" 
                        <?php if ($post_item['img'] !== false) { ?>
                            style="background-image: url(<?php echo esc_url($post_item['img']); ?>);"
                        <?php } ?>
                    >
                        <?php if ($post_item['img'] !== false) { ?>
                            <div class="pxp-hero-opacity"></div>
                        <?php } ?>
                        <div class="pxp-featured-posts-item-caption">
                            <div class="pxp-featured-posts-item-caption-content">
                                <div class="row align-content-center justify-content-center">
                                    <div class="col-9 col-md-8 col-xl-7 col-xxl-6">
                                        <div class="pxp-featured-posts-item-date">
                                            <?php echo esc_html($post_item['date']); ?>
                                        </div>
                                        <div class="pxp-featured-posts-item-title">
                                            <?php echo esc_html($post_item['title']); ?>
                                        </div>
                                        <div class="pxp-featured-posts-item-summary mt-2">
                                            <?php echo esc_html($post_item['excerpt']); ?>
                                        </div>
                                        <div class="mt-4 mt-md-5 text-center">
                                            <a 
                                                href="<?php echo esc_url($post_item['permalink']); ?>" 
                                                class="btn rounded-pill pxp-section-cta"
                                            >
                                                <?php esc_html_e('Read Article', 'jobster'); ?>
                                                <span class="fa fa-angle-right"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $feat_count++;
            } ?>
        </div>
        <?php if (count($posts_arr) > 1) { ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#pxp-blog-featured-posts-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#pxp-blog-featured-posts-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        <?php } ?>
    </div>
<?php } ?>