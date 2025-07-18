<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

$footer_settings = get_option('jobster_footer_settings');
$copyright =    isset($footer_settings['jobster_copyright_field'])
                ? $footer_settings['jobster_copyright_field']
                : '';
$facebook = isset($footer_settings['jobster_facebook_field'])
            ? $footer_settings['jobster_facebook_field']
            : '';
$twitter =  isset($footer_settings['jobster_twitter_field'])
            ? $footer_settings['jobster_twitter_field']
            : '';
$instagram =    isset($footer_settings['jobster_instagram_field'])
                ? $footer_settings['jobster_instagram_field']
                : '';
$linkedin = isset($footer_settings['jobster_linkedin_field'])
            ? $footer_settings['jobster_linkedin_field']
            : '';

$show_footer_bottom =   $copyright != ''
                        || $facebook != ''
                        || $twitter != ''
                        || $instagram != ''
                        || $linkedin != '';
$show_main_footer = is_active_sidebar('pxp-first-footer-widget-area')
                    || is_active_sidebar('pxp-second-footer-widget-area')
                    || is_active_sidebar('pxp-third-footer-widget-area')
                    || is_active_sidebar('pxp-fourth-footer-widget-area')
                    || is_active_sidebar('pxp-fifth-footer-widget-area');

$margin_class = 'mt-100';
if (isset($post)) {
    $content_margin = get_post_meta(
        $post->ID,
        'page_settings_margin',
        true
    );

    if ($content_margin == '1') {
        $margin_class = '';
    }
}
if (is_page_template('sign-in.php') || is_page_template('sign-up.php')) {
    $margin_class = '';
} ?>
    <footer class="pxp-main-footer <?php echo esc_attr($margin_class); ?>">
        <?php if ($show_main_footer === true) { ?>
            <div class="pxp-main-footer-top pt-100 pb-100" style="background-color: var(--pxpMainColorLight);">
                <div class="pxp-container">
                    <div class="row">
                        <?php if (is_active_sidebar('pxp-first-footer-widget-area')) : ?>
                            <div class="col-lg-6 col-xl-5 col-xxl-4 mb-4">
                                <?php dynamic_sidebar('pxp-first-footer-widget-area'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-lg-6 col-xl-7 col-xxl-8">
                            <div class="row">
                                <?php if (is_active_sidebar('pxp-second-footer-widget-area')) : ?>
                                    <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
                                        <?php dynamic_sidebar('pxp-second-footer-widget-area'); ?>
                                    </div>
                                <?php endif;
                                if (is_active_sidebar('pxp-third-footer-widget-area')) : ?>
                                    <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
                                        <?php dynamic_sidebar('pxp-third-footer-widget-area'); ?>
                                    </div>
                                <?php endif;
                                if (is_active_sidebar('pxp-fourth-footer-widget-area')) : ?>
                                    <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
                                        <?php dynamic_sidebar('pxp-fourth-footer-widget-area'); ?>
                                    </div>
                                <?php endif;
                                if (is_active_sidebar('pxp-fifth-footer-widget-area')) : ?>
                                    <div class="col-md-6 col-xl-4 col-xxl-3 mb-4">
                                        <?php dynamic_sidebar('pxp-fifth-footer-widget-area'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
        if ($show_footer_bottom === true) { ?>
            <div class="pxp-main-footer-bottom" style="background-color: var(--pxpSecondaryColorLight);">
                <div class="pxp-container">
                    <div class="row justify-content-between align-items-center">
                        <?php if ($copyright != '') { ?>
                            <div class="col-lg-auto">
                                <div class="pxp-footer-copyright pxp-text-light">
                                    <?php $allow_tags = array(
                                        'br' => array(),
                                        'p' => array(
                                            'style' => array()
                                        ),
                                        'strong' => array(),
                                        'em' => array(),
                                        'span' => array(
                                            'style' => array()
                                        ),
                                        'a' => array(
                                            'href' => array()
                                        )
                                    );
                                    echo wp_kses($copyright, $allow_tags); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-lg-auto">
                            <div class="pxp-footer-social mt-3 mt-lg-0">
                                <ul class="list-unstyled">
                                    <?php if ($facebook != '') { ?>
                                        <li>
                                            <a href="<?php echo esc_url($facebook); ?>">
                                                <span class="fa fa-facebook"></span>
                                            </a>
                                        </li>
                                    <?php }
                                    if ($twitter != '') { ?>
                                        <li>
                                            <a href="<?php echo esc_url($twitter); ?>">
                                                <span class="fa fa-twitter"></span>
                                            </a>
                                        </li>
                                    <?php }
                                    if ($instagram != '') { ?>
                                        <li>
                                            <a href="<?php echo esc_url($instagram); ?>">
                                                <span class="fa fa-instagram"></span>
                                            </a>
                                        </li>
                                    <?php }
                                    if ($linkedin != '') { ?>
                                        <li>
                                            <a href="<?php echo esc_url($linkedin); ?>">
                                                <span class="fa fa-linkedin"></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>