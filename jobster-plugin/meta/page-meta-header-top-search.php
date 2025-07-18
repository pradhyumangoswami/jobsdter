<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_top_search_header_meta')): 
    function jobster_get_top_search_header_meta($post_id, $header_types_value) {
        $hide_top_search = ($header_types_value == 'top_search') ? 'block' : 'none'; ?>

        <div class="pxp-header-settings pxp-header-top_search-settings" style="display: <?php echo esc_attr($hide_top_search); ?>">
            <p><strong><?php esc_html_e('Image Background Settings', 'jobster'); ?></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_top_search_caption_title"><?php esc_html_e('Caption title', 'jobster'); ?></label><br />
                            <input type="text" id="ph_top_search_caption_title" name="ph_top_search_caption_title" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_top_search_caption_title', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_top_search_caption_subtitle"><?php esc_html_e('Caption subtitle', 'jobster'); ?></label><br />
                            <input type="text" id="ph_top_search_caption_subtitle" name="ph_top_search_caption_subtitle" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_top_search_caption_subtitle', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_top_search_show_search_val = get_post_meta($post_id, 'ph_top_search_show_search', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_top_search_show_search_field">
                                <input type="hidden" name="ph_top_search_show_search" value="0">
                                <input type="checkbox" name="ph_top_search_show_search" id="ph_top_search_show_search_field" value="1" <?php checked($ph_top_search_show_search_val, true, true); ?>>
                                <?php esc_html_e('Show jobs search form', 'jobster'); ?>
                            </label>
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">&nbsp;</td>
                </tr>
            </table>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top" align="left">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="ph_top_search_system"><?php esc_html_e('Search System', 'jobster'); ?></label>
                            <select type="text" id="ph_top_search_system" name="ph_top_search_system">
                                <?php $ph_top_search_system = get_post_meta($post_id, 'ph_top_search_system', true);
                                $search_systems = array(
                                    'default' => __('Default', 'jobster'),
                                    'careerjet' => __('Careerjet', 'jobster')
                                );
                                foreach ($search_systems as $system_k => $system_value) { ?>
                                    <option value="<?php echo esc_attr($system_k); ?>" <?php selected($ph_top_search_system, $system_k); ?>>
                                        <?php echo esc_html($system_value); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td width="75%">&nbsp;</td>
                </tr>
            </table>

            <br><hr>

            <p><?php esc_html_e('Background Image', 'jobster'); ?></p>

            <?php $ph_top_search_photo_val = get_post_meta($post_id, 'ph_top_search_photo', true);
            $ph_top_search_photo = wp_get_attachment_image_src($ph_top_search_photo_val, 'pxp-thmb');
            $ph_top_search_photo_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

            $ph_top_search_photo_has_image = '';
            if ($ph_top_search_photo !== false) { 
                $ph_top_search_photo_src = $ph_top_search_photo[0];
                $ph_top_search_photo_has_image = 'has-image';
            } ?>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <input type="hidden" id="ph_top_search_photo" name="ph_top_search_photo" value="<?php echo esc_attr($ph_top_search_photo_val); ?>">
                            <div class="pxp-ph-ts-photo-placeholder-container <?php echo esc_attr($ph_top_search_photo_has_image); ?>">
                                <div id="pxp-ph-ts-photo-placeholder" style="background-image: url(<?php echo esc_url($ph_top_search_photo_src); ?>);"></div>
                                <div id="pxp-ph-ts-delete-photo"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_top_search_opacity"><?php esc_html_e('Caption background opacity', 'jobster'); ?></label><br />
                            <select type="text" id="ph_top_search_opacity" name="ph_top_search_opacity">
                                <?php $ph_top_search_opacity = get_post_meta($post_id, 'ph_top_search_opacity', true);
                                $opacity = array('0' => '0%', '0.1' => '10%', '0.2' => '20%', '0.3' => '30%', '0.4' => '40%', '0.5' => '50%', '0.6' => '60%', '0.7' => '70%', '0.8' => '80%', '0.9' => '90%', '1' => '100%');
                                foreach ($opacity as $key => $value) { ?>
                                    <option value="<?php echo esc_attr($key); ?>" <?php selected($ph_top_search_opacity, $key); ?>>
                                        <?php echo esc_html($value); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td width="50%" valign="top"></td>
                </tr>
            </table>

            <br><hr><br>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_top_search_cta_label"><?php esc_html_e('CTA button label', 'jobster'); ?></label><br />
                            <input type="text" id="ph_top_search_cta_label" name="ph_top_search_cta_label" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_top_search_cta_label', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_top_search_cta_link"><?php esc_html_e('CTA button link', 'jobster'); ?></label><br />
                            <input type="text" id="ph_top_search_cta_link" name="ph_top_search_cta_link" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_top_search_cta_link', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    <?php }
endif;
?>