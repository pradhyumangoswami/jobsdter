<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

$footer_settings = get_option('jobster_footer_settings');
$copyright =    isset($footer_settings['jobster_copyright_field'])
                ? $footer_settings['jobster_copyright_field']
                : ''; ?>
    <footer>
        <?php if ($copyright != '') { ?>
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
        <?php } ?>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>