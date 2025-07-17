<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_half_image_header_meta')): 
    function jobster_get_half_image_header_meta($post_id, $header_types_value) {
        $hide_half_image = ($header_types_value == 'half_image') ? 'block' : 'none'; ?>

        <div class="pxp-header-settings pxp-header-half_image-settings" style="display: <?php echo esc_attr($hide_half_image); ?>">
            <p><strong><?php esc_html_e('Half Image Settings', 'jobster'); ?></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_half_image_caption_title"><?php esc_html_e('Caption title', 'jobster'); ?></label><br />
                            <input type="text" id="ph_half_image_caption_title" name="ph_half_image_caption_title" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_half_image_caption_title', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_half_image_caption_subtitle"><?php esc_html_e('Caption subtitle', 'jobster'); ?></label><br />
                            <input type="text" id="ph_half_image_caption_subtitle" name="ph_half_image_caption_subtitle" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_half_image_caption_subtitle', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_half_image_show_search_val = get_post_meta($post_id, 'ph_half_image_show_search', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_half_image_show_search_field">
                                <input type="hidden" name="ph_half_image_show_search" value="0">
                                <input type="checkbox" name="ph_half_image_show_search" id="ph_half_image_show_search_field" value="1" <?php checked($ph_half_image_show_search_val, true, true); ?>>
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
                            <label for="ph_half_image_search_system"><?php esc_html_e('Search System', 'jobster'); ?></label>
                            <select type="text" id="ph_half_image_search_system" name="ph_half_image_search_system">
                                <?php $ph_half_image_search_system = get_post_meta($post_id, 'ph_half_image_search_system', true);
                                $search_systems = array(
                                    'default' => __('Default', 'jobster'),
                                    'careerjet' => __('Careerjet', 'jobster')
                                );
                                foreach ($search_systems as $system_k => $system_value) { ?>
                                    <option value="<?php echo esc_attr($system_k); ?>" <?php selected($ph_half_image_search_system, $system_k); ?>>
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

            <p><?php esc_html_e('Image', 'jobster'); ?></p>

            <?php $ph_half_image_photo_val = get_post_meta($post_id, 'ph_half_image_photo', true);
            $ph_half_image_photo = wp_get_attachment_image_src($ph_half_image_photo_val, 'pxp-thmb');
            $ph_half_image_photo_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

            $ph_half_image_has_image = '';
            if ($ph_half_image_photo !== false) { 
                $ph_half_image_photo_src = $ph_half_image_photo[0];
                $ph_half_image_has_image = 'has-image';
            } ?>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <input type="hidden" id="ph_half_image_photo" name="ph_half_image_photo" value="<?php echo esc_attr($ph_half_image_photo_val); ?>">
                            <div class="pxp-ph-hi-photo-placeholder-container <?php echo esc_attr($ph_half_image_has_image); ?>">
                                <div id="pxp-ph-hi-photo-placeholder" style="background-image: url(<?php echo esc_url($ph_half_image_photo_src); ?>);"></div>
                                <div id="pxp-ph-hi-delete-photo"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <p><?php esc_html_e('Key Features', 'jobster'); ?></p>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <input type="text" id="ph_half_image_caption_key_features_new" name="ph_half_image_caption_key_features_new" placeholder="<?php esc_attr_e('Add new key feature', 'jobster'); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <a href="javascript:void(0);" class="button button-secondary" id="pxp-ph-hi-add-feature-btn"><?php esc_html_e('Add Feature'); ?></a>
                        </div>
                    </td>
                </tr>
            </table>

            <?php $ph_half_image_key_features_val = get_post_meta($post_id, 'ph_half_image_caption_key_features', true);

            $ph_half_image_key_features_list = array();

            if ($ph_half_image_key_features_val != '') {
                $ph_half_image_key_features_data = json_decode(urldecode($ph_half_image_key_features_val));

                if (isset($ph_half_image_key_features_data)) {
                    $ph_half_image_key_features_list = $ph_half_image_key_features_data->features;
                }
            } ?>

            <input type="hidden" id="ph_half_image_caption_key_features" name="ph_half_image_caption_key_features" value="<?php echo esc_attr($ph_half_image_key_features_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-hi-key-features-list">
                            <?php if (count($ph_half_image_key_features_list) > 0) {
                                foreach ($ph_half_image_key_features_list as $ph_hi_feature) { ?>
                                    <li class="list-group-item" data-text="<?php echo esc_attr($ph_hi_feature->text); ?>">
                                        <div class="pxp-ph-hi-key-features-list-item">
                                            <div class="pxp-ph-hi-key-features-list-item-text">
                                                <?php echo esc_html($ph_hi_feature->text); ?>
                                            </div>
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-hi-del-key-feature-btn"><span class="fa fa-trash-o"></span></a>
                                            </div>
                                        </div>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    <?php }
endif;
?>