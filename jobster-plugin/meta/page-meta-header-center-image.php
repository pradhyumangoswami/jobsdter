<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_center_image_header_meta')): 
    function jobster_get_center_image_header_meta($post_id, $header_types_value) {
        $hide_center_image = ($header_types_value == 'center_image') ? 'block' : 'none'; ?>

        <div class="pxp-header-settings pxp-header-center_image-settings" style="display: <?php echo esc_attr($hide_center_image); ?>">
            <p><strong><?php esc_html_e('Center Image Settings', 'jobster'); ?></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_center_image_caption_title"><?php esc_html_e('Caption title', 'jobster'); ?></label><br />
                            <input type="text" id="ph_center_image_caption_title" name="ph_center_image_caption_title" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_center_image_caption_title', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_center_image_caption_subtitle"><?php esc_html_e('Caption subtitle', 'jobster'); ?></label><br />
                            <input type="text" id="ph_center_image_caption_subtitle" name="ph_center_image_caption_subtitle" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_center_image_caption_subtitle', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_center_image_show_search_val = get_post_meta($post_id, 'ph_center_image_show_search', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_center_image_show_search_field">
                                <input type="hidden" name="ph_center_image_show_search" value="0">
                                <input type="checkbox" name="ph_center_image_show_search" id="ph_center_image_show_search_field" value="1" <?php checked($ph_center_image_show_search_val, true, true); ?>>
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
                            <label for="ph_center_image_search_system"><?php esc_html_e('Search System', 'jobster'); ?></label>
                            <select type="text" id="ph_center_image_search_system" name="ph_center_image_search_system">
                                <?php $ph_center_image_search_system = get_post_meta($post_id, 'ph_center_image_search_system', true);
                                $search_systems = array(
                                    'default' => __('Default', 'jobster'),
                                    'careerjet' => __('Careerjet', 'jobster')
                                );
                                foreach ($search_systems as $system_k => $system_value) { ?>
                                    <option value="<?php echo esc_attr($system_k); ?>" <?php selected($ph_center_image_search_system, $system_k); ?>>
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

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <p><?php esc_html_e('Center Image', 'jobster'); ?></p>

                            <?php $ph_center_image_photo_val = get_post_meta($post_id, 'ph_center_image_photo', true);
                            $ph_center_image_photo = wp_get_attachment_image_src($ph_center_image_photo_val, 'pxp-thmb');
                            $ph_center_image_photo_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                            $ph_center_image_has_image = '';
                            if ($ph_center_image_photo !== false) { 
                                $ph_center_image_photo_src = $ph_center_image_photo[0];
                                $ph_center_image_has_image = 'has-image';
                            } ?>
                            <input type="hidden" id="ph_center_image_photo" name="ph_center_image_photo" value="<?php echo esc_attr($ph_center_image_photo_val); ?>">
                            <div class="pxp-ph-ci-photo-placeholder-container <?php echo esc_attr($ph_center_image_has_image); ?>">
                                <div id="pxp-ph-ci-photo-placeholder" style="background-image: url(<?php echo esc_url($ph_center_image_photo_src); ?>);"></div>
                                <div id="pxp-ph-ci-delete-photo"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <p><?php esc_html_e('Background Image', 'jobster'); ?></p>

                            <?php $ph_center_image_bg_val = get_post_meta($post_id, 'ph_center_image_bg', true);
                            $ph_center_image_bg = wp_get_attachment_image_src($ph_center_image_bg_val, 'pxp-thmb');
                            $ph_center_image_bg_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                            $ph_center_image_bg_has_image = '';
                            if ($ph_center_image_bg !== false) { 
                                $ph_center_image_bg_src = $ph_center_image_bg[0];
                                $ph_center_image_bg_has_image = 'has-image';
                            } ?>
                            <input type="hidden" id="ph_center_image_bg" name="ph_center_image_bg" value="<?php echo esc_attr($ph_center_image_bg_val); ?>">
                            <div class="pxp-ph-ci-bg-placeholder-container <?php echo esc_attr($ph_center_image_bg_has_image); ?>">
                                <div id="pxp-ph-ci-bg-placeholder" style="background-image: url(<?php echo esc_url($ph_center_image_bg_src); ?>);"></div>
                                <div id="pxp-ph-ci-delete-bg"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <p>
                                <label for="ph_center_image_opacity"><?php esc_html_e('Caption background opacity', 'jobster'); ?></label>
                            </p>
                            <select type="text" id="ph_center_image_opacity" name="ph_center_image_opacity">
                                <?php $ph_center_image_opacity = get_post_meta($post_id, 'ph_center_image_opacity', true);
                                $opacity = array('0' => '0%', '0.1' => '10%', '0.2' => '20%', '0.3' => '30%', '0.4' => '40%', '0.5' => '50%', '0.6' => '60%', '0.7' => '70%', '0.8' => '80%', '0.9' => '90%', '1' => '100%');
                                foreach ($opacity as $key => $value) { ?>
                                    <option value="<?php echo esc_attr($key); ?>" <?php selected($ph_center_image_opacity, $key); ?>>
                                        <?php echo esc_html($value); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td width="25%" valign="top">&nbsp;</td>
                </tr>
            </table>
        </div>
    <?php }
endif;
?>