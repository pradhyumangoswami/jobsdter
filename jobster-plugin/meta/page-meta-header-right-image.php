<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_right_image_header_meta')): 
    function jobster_get_right_image_header_meta($post_id, $header_types_value) {
        $hide_right_image = ($header_types_value == 'right_image') ? 'block' : 'none'; ?>

        <div class="pxp-header-settings pxp-header-right_image-settings" style="display: <?php echo esc_attr($hide_right_image); ?>">
            <p><strong><?php esc_html_e('Right Image Settings', 'jobster'); ?></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_right_image_caption_title"><?php esc_html_e('Caption title', 'jobster'); ?></label><br />
                            <input type="text" id="ph_right_image_caption_title" name="ph_right_image_caption_title" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_right_image_caption_title', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_right_image_caption_subtitle"><?php esc_html_e('Caption subtitle', 'jobster'); ?></label><br />
                            <input type="text" id="ph_right_image_caption_subtitle" name="ph_right_image_caption_subtitle" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_right_image_caption_subtitle', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <p><?php esc_html_e('Right Image', 'jobster'); ?></p>

                            <?php $ph_right_image_photo_val = get_post_meta($post_id, 'ph_right_image_photo', true);
                            $ph_right_image_photo = wp_get_attachment_image_src($ph_right_image_photo_val, 'pxp-thmb');
                            $ph_right_image_photo_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                            $ph_right_image_has_image = '';
                            if ($ph_right_image_photo !== false) { 
                                $ph_right_image_photo_src = $ph_right_image_photo[0];
                                $ph_right_image_has_image = 'has-image';
                            } ?>
                            <input type="hidden" id="ph_right_image_photo" name="ph_right_image_photo" value="<?php echo esc_attr($ph_right_image_photo_val); ?>">
                            <div class="pxp-ph-ri-photo-placeholder-container <?php echo esc_attr($ph_right_image_has_image); ?>">
                                <div id="pxp-ph-ri-photo-placeholder" style="background-image: url(<?php echo esc_url($ph_right_image_photo_src); ?>);"></div>
                                <div id="pxp-ph-ri-delete-photo"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <p><?php esc_html_e('Background Image', 'jobster'); ?></p>

                            <?php $ph_right_image_bg_val = get_post_meta($post_id, 'ph_right_image_bg', true);
                            $ph_right_image_bg = wp_get_attachment_image_src($ph_right_image_bg_val, 'pxp-thmb');
                            $ph_right_image_bg_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                            $ph_right_image_bg_has_image = '';
                            if ($ph_right_image_bg !== false) { 
                                $ph_right_image_bg_src = $ph_right_image_bg[0];
                                $ph_right_image_bg_has_image = 'has-image';
                            } ?>
                            <input type="hidden" id="ph_right_image_bg" name="ph_right_image_bg" value="<?php echo esc_attr($ph_right_image_bg_val); ?>">
                            <div class="pxp-ph-ri-bg-placeholder-container <?php echo esc_attr($ph_right_image_bg_has_image); ?>">
                                <div id="pxp-ph-ri-bg-placeholder" style="background-image: url(<?php echo esc_url($ph_right_image_bg_src); ?>);"></div>
                                <div id="pxp-ph-ri-delete-bg"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <p>
                                <label for="ph_right_image_opacity"><?php esc_html_e('Caption background opacity', 'jobster'); ?></label>
                            </p>
                            <select type="text" id="ph_right_image_opacity" name="ph_right_image_opacity">
                                <?php $ph_right_image_opacity = get_post_meta($post_id, 'ph_right_image_opacity', true);
                                $opacity = array('0' => '0%', '0.1' => '10%', '0.2' => '20%', '0.3' => '30%', '0.4' => '40%', '0.5' => '50%', '0.6' => '60%', '0.7' => '70%', '0.8' => '80%', '0.9' => '90%', '1' => '100%');
                                foreach ($opacity as $key => $value) { ?>
                                    <option value="<?php echo esc_attr($key); ?>" <?php selected($ph_right_image_opacity, $key); ?>>
                                        <?php echo esc_html($value); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </td>
                    <td width="25%" valign="top">&nbsp;</td>
                </tr>
            </table>

            <br><hr>

            <p><?php esc_html_e('Key Features', 'jobster'); ?></p>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <input type="text" id="ph_right_image_caption_key_features_new" name="ph_right_image_caption_key_features_new" placeholder="<?php esc_attr_e('Add new key feature', 'jobster'); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <a href="javascript:void(0);" class="button button-secondary" id="pxp-ph-ri-add-feature-btn"><?php esc_html_e('Add Feature'); ?></a>
                        </div>
                    </td>
                </tr>
            </table>

            <?php $ph_right_image_key_features_val = get_post_meta($post_id, 'ph_right_image_caption_key_features', true);

            $ph_right_image_key_features_list = array();

            if ($ph_right_image_key_features_val != '') {
                $ph_right_image_key_features_data = json_decode(urldecode($ph_right_image_key_features_val));

                if (isset($ph_right_image_key_features_data)) {
                    $ph_right_image_key_features_list = $ph_right_image_key_features_data->features;
                }
            } ?>

            <input type="hidden" id="ph_right_image_caption_key_features" name="ph_right_image_caption_key_features" value="<?php echo esc_attr($ph_right_image_key_features_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-ri-key-features-list">
                            <?php if (count($ph_right_image_key_features_list) > 0) {
                                foreach ($ph_right_image_key_features_list as $ph_ip_feature) { ?>
                                    <li class="list-group-item" data-text="<?php echo esc_attr($ph_ip_feature->text); ?>">
                                        <div class="pxp-ph-ri-key-features-list-item">
                                            <div class="pxp-ph-ri-key-features-list-item-text">
                                                <?php echo esc_html($ph_ip_feature->text); ?>
                                            </div>
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-ri-del-key-feature-btn"><span class="fa fa-trash-o"></span></a>
                                            </div>
                                        </div>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </td>
                </tr>
            </table>

            <br><hr><br>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_right_image_cta_label"><?php esc_html_e('CTA button label', 'jobster'); ?></label><br />
                            <input type="text" id="ph_right_image_cta_label" name="ph_right_image_cta_label" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_right_image_cta_label', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_right_image_cta_link"><?php esc_html_e('CTA button link', 'jobster'); ?></label><br />
                            <input type="text" id="ph_right_image_cta_link" name="ph_right_image_cta_link" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_right_image_cta_link', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    <?php }
endif;
?>