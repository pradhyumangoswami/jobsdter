<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_share_post')):
    function jobster_share_post($post_id) { ?>
        <div class="mt-100">
            <div class="pxp-single-blog-share">
                <span class="me-4">
                    <?php esc_html_e('Share this article', 'jobster'); ?>
                </span>
                <ul class="list-unstyled">
                    <li>
                        <a 
                            href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink($post_id)); ?>" 
                            onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
                            target="_blank"
                        >
                            <span class="fa fa-facebook"></span>
                        </a>
                    </li>
                    <li>
                        <a 
                            href="https://twitter.com/share?url=<?php echo esc_url(get_permalink($post_id)); ?>&amp;text=<?php echo urlencode(get_the_title($post_id)); ?>" 
                            onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
                            target="_blank"
                        >
                            <span class="fa fa-twitter"></span>
                        </a>
                    </li>
                    <li>
                        <script async defer src="//assets.pinterest.com/js/pinit.js"></script>
                        <a 
                            href="https://www.pinterest.com/pin/create/button/" 
                            data-pin-do="buttonBookmark" 
                            data-pin-custom="true"
                        >
                            <span class="fa fa-pinterest-p"></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url(get_permalink($post_id)); ?>&title=<?php echo urlencode(get_the_title($post_id)); ?>">
                            <span class="fa fa-linkedin"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    <?php }
endif;
?>