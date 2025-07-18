<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_image_pills_header_meta')): 
    function jobster_get_image_pills_header_meta($post_id, $header_types_value) {
        $hide_image_pills = ($header_types_value == 'image_pills') ? 'block' : 'none'; ?>

        <div class="pxp-header-settings pxp-header-image_pills-settings" style="display: <?php echo esc_attr($hide_image_pills); ?>">
            <p><strong><?php esc_html_e('Image Pills Settings', 'jobster'); ?></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_image_pills_caption_title"><?php esc_html_e('Caption title', 'jobster'); ?></label><br />
                            <input type="text" id="ph_image_pills_caption_title" name="ph_image_pills_caption_title" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_image_pills_caption_title', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_image_pills_caption_subtitle"><?php esc_html_e('Caption subtitle', 'jobster'); ?></label><br />
                            <input type="text" id="ph_image_pills_caption_subtitle" name="ph_image_pills_caption_subtitle" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_image_pills_caption_subtitle', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_image_pills_show_search_val = get_post_meta($post_id, 'ph_image_pills_show_search', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_image_pills_show_search_field">
                                <input type="hidden" name="ph_image_pills_show_search" value="0">
                                <input type="checkbox" name="ph_image_pills_show_search" id="ph_image_pills_show_search_field" value="1" <?php checked($ph_image_pills_show_search_val, true, true); ?>>
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
                            <label for="ph_image_pills_search_system"><?php esc_html_e('Search System', 'jobster'); ?></label>
                            <select type="text" id="ph_image_pills_search_system" name="ph_image_pills_search_system">
                                <?php $ph_image_pills_search_system = get_post_meta($post_id, 'ph_image_pills_search_system', true);
                                $search_systems = array(
                                    'default' => __('Default', 'jobster'),
                                    'careerjet' => __('Careerjet', 'jobster')
                                );
                                foreach ($search_systems as $system_k => $system_value) { ?>
                                    <option value="<?php echo esc_attr($system_k); ?>" <?php selected($ph_image_pills_search_system, $system_k); ?>>
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
                    <?php $ph_image_pills_left_val = get_post_meta($post_id, 'ph_image_pills_left', true);
                    $ph_image_pills_left = wp_get_attachment_image_src($ph_image_pills_left_val, 'pxp-thmb');
                    $ph_image_pills_left_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                    $ph_image_pills_left_has_image = '';
                    if ($ph_image_pills_left !== false) { 
                        $ph_image_pills_left_src = $ph_image_pills_left[0];
                        $ph_image_pills_left_has_image = 'has-image';
                    } ?>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <p><?php esc_html_e('Left Pill Image', 'jobster'); ?></p>
                            <input type="hidden" id="ph_image_pills_left" name="ph_image_pills_left" value="<?php echo esc_attr($ph_image_pills_left_val); ?>">
                            <div class="pxp-ph-ip-left-placeholder-container <?php echo esc_attr($ph_image_pills_left_has_image); ?>">
                                <div id="pxp-ph-ip-left-placeholder" style="background-image: url(<?php echo esc_url($ph_image_pills_left_src); ?>);"></div>
                                <div id="pxp-ph-ip-left-delete"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>

                    <?php $ph_image_pills_top_val = get_post_meta($post_id, 'ph_image_pills_top', true);
                    $ph_image_pills_top = wp_get_attachment_image_src($ph_image_pills_top_val, 'pxp-thmb');
                    $ph_image_pills_top_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                    $ph_image_pills_top_has_image = '';
                    if ($ph_image_pills_top !== false) { 
                        $ph_image_pills_top_src = $ph_image_pills_top[0];
                        $ph_image_pills_top_has_image = 'has-image';
                    } ?>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <p><?php esc_html_e('Top Pill Image', 'jobster'); ?></p>
                            <input type="hidden" id="ph_image_pills_top" name="ph_image_pills_top" value="<?php echo esc_attr($ph_image_pills_top_val); ?>">
                            <div class="pxp-ph-ip-top-placeholder-container <?php echo esc_attr($ph_image_pills_top_has_image); ?>">
                                <div id="pxp-ph-ip-top-placeholder" style="background-image: url(<?php echo esc_url($ph_image_pills_top_src); ?>);"></div>
                                <div id="pxp-ph-ip-top-delete"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>

                    <?php $ph_image_pills_bottom_val = get_post_meta($post_id, 'ph_image_pills_bottom', true);
                    $ph_image_pills_bottom = wp_get_attachment_image_src($ph_image_pills_bottom_val, 'pxp-thmb');
                    $ph_image_pills_bottom_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                    $ph_image_pills_bottom_has_image = '';
                    if ($ph_image_pills_bottom !== false) { 
                        $ph_image_pills_bottom_src = $ph_image_pills_bottom[0];
                        $ph_image_pills_bottom_has_image = 'has-image';
                    } ?>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <p><?php esc_html_e('Bottom Pill Image', 'jobster'); ?></p>
                            <input type="hidden" id="ph_image_pills_bottom" name="ph_image_pills_bottom" value="<?php echo esc_attr($ph_image_pills_bottom_val); ?>">
                            <div class="pxp-ph-ip-bottom-placeholder-container <?php echo esc_attr($ph_image_pills_bottom_has_image); ?>">
                                <div id="pxp-ph-ip-bottom-placeholder" style="background-image: url(<?php echo esc_url($ph_image_pills_bottom_src); ?>);"></div>
                                <div id="pxp-ph-ip-bottom-delete"><span class="fa fa-trash-o"></span></div>
                            </div>
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
                            <input type="text" id="ph_image_pills_caption_key_features_new" name="ph_image_pills_caption_key_features_new" placeholder="<?php esc_attr_e('Add new key feature', 'jobster'); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <a href="javascript:void(0);" class="button button-secondary" id="pxp-ph-ip-add-feature-btn"><?php esc_html_e('Add Feature'); ?></a>
                        </div>
                    </td>
                </tr>
            </table>

            <?php $ph_image_pills_key_features_val = get_post_meta($post_id, 'ph_image_pills_caption_key_features', true);

            $ph_image_pills_key_features_list = array();

            if ($ph_image_pills_key_features_val != '') {
                $ph_image_pills_key_features_data = json_decode(urldecode($ph_image_pills_key_features_val));

                if (isset($ph_image_pills_key_features_data)) {
                    $ph_image_pills_key_features_list = $ph_image_pills_key_features_data->features;
                }
            } ?>

            <input type="hidden" id="ph_image_pills_caption_key_features" name="ph_image_pills_caption_key_features" value="<?php echo esc_attr($ph_image_pills_key_features_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-ip-key-features-list">
                            <?php if (count($ph_image_pills_key_features_list) > 0) {
                                foreach ($ph_image_pills_key_features_list as $ph_ip_feature) { ?>
                                    <li class="list-group-item" data-text="<?php echo esc_attr($ph_ip_feature->text); ?>">
                                        <div class="pxp-ph-ip-key-features-list-item">
                                            <div class="pxp-ph-ip-key-features-list-item-text">
                                                <?php echo esc_html($ph_ip_feature->text); ?>
                                            </div>
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-ip-del-key-feature-btn"><span class="fa fa-trash-o"></span></a>
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