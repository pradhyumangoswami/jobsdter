<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_youtube_video')):
    function jobster_get_youtube_video($id) { ?>
        <div class="pxp-embed-wrapper">
            <iframe 
                width="560" 
                height="315" 
                src="https://www.youtube.com/embed/<?php echo esc_attr($id) ?>" 
                frameborder="0" 
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
            ></iframe>
        </div>
    <?php }
endif;
?>