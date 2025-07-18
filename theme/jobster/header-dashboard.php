<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="pxp-root">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>
</head>

<body 
    <?php body_class(); ?> 
    style="background-color: var(--<?php echo esc_attr($args['bg_color']) ?>)"
>
    <?php if (!function_exists('wp_body_open')) {
        function wp_body_open() {
            do_action('wp_body_open');
        }
    } ?>

    <div class="pxp-preloader">
        <span><?php esc_html_e('Loading...', 'jobster'); ?></span>
    </div>