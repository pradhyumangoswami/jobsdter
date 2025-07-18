<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_animated_cards_header_meta')): 
    function jobster_get_animated_cards_header_meta($post_id, $header_types_value) {
        $hide_animated_cards = ($header_types_value == 'animated_cards') ? 'block' : 'none'; ?>

        <div class="pxp-header-settings pxp-header-animated_cards-settings" style="display: <?php echo esc_attr($hide_animated_cards); ?>">
            <p><strong><?php esc_html_e('Animated Cards Settings', 'jobster'); ?></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_animated_cards_caption_title"><?php esc_html_e('Caption title', 'jobster'); ?></label><br />
                            <input type="text" id="ph_animated_cards_caption_title" name="ph_animated_cards_caption_title" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_animated_cards_caption_title', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_animated_cards_caption_subtitle"><?php esc_html_e('Caption subtitle', 'jobster'); ?></label><br />
                            <input type="text" id="ph_animated_cards_caption_subtitle" name="ph_animated_cards_caption_subtitle" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_animated_cards_caption_subtitle', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_animated_cards_show_search_val = get_post_meta($post_id, 'ph_animated_cards_show_search', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_animated_cards_show_search_field">
                                <input type="hidden" name="ph_animated_cards_show_search" value="0">
                                <input type="checkbox" name="ph_animated_cards_show_search" id="ph_animated_cards_show_search_field" value="1" <?php checked($ph_animated_cards_show_search_val, true, true); ?>>
                                <?php esc_html_e('Show jobs search form', 'jobster'); ?>
                            </label>
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_animated_cards_show_popular_val = get_post_meta($post_id, 'ph_animated_cards_show_popular', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_animated_cards_show_popular_field">
                                <input type="hidden" name="ph_animated_cards_show_popular" value="0">
                                <input type="checkbox" name="ph_animated_cards_show_popular" id="ph_animated_cards_show_popular_field" value="1" <?php checked($ph_animated_cards_show_popular_val, true, true); ?>>
                                <?php esc_html_e('Show popular job searches', 'jobster'); ?>
                            </label>
                        </div>
                    </td>
                </tr>
            </table>
            
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top" align="left">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="ph_animated_cards_search_system"><?php esc_html_e('Search System', 'jobster'); ?></label>
                            <select type="text" id="ph_animated_cards_search_system" name="ph_animated_cards_search_system">
                                <?php $ph_animated_cards_search_system = get_post_meta($post_id, 'ph_animated_cards_search_system', true);
                                $search_systems = array(
                                    'default' => __('Default', 'jobster'),
                                    'careerjet' => __('Careerjet', 'jobster')
                                );
                                foreach ($search_systems as $system_k => $system_value) { ?>
                                    <option value="<?php echo esc_attr($system_k); ?>" <?php selected($ph_animated_cards_search_system, $system_k); ?>>
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

            <p><?php esc_html_e('Logos List', 'jobster'); ?></p>

            <?php $ph_animated_cards_logos_val = get_post_meta($post_id, 'ph_animated_cards_logos', true); 

            $ph_animated_cards_logos_list = array();

            if ($ph_animated_cards_logos_val != '') {
                $ph_animated_cards_logos_data = json_decode(urldecode($ph_animated_cards_logos_val));
    
                if (isset($ph_animated_cards_logos_data)) {
                    $ph_animated_cards_logos_list = $ph_animated_cards_logos_data->logos;
                }
            } ?>

            <input type="hidden" id="ph_animated_cards_logos" name="ph_animated_cards_logos" value="<?php echo esc_attr($ph_animated_cards_logos_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-ac-logos-list">
                            <?php if (count($ph_animated_cards_logos_list) > 0) {
                                foreach ($ph_animated_cards_logos_list as $ph_ac_logo) {
                                    $image = wp_get_attachment_image_src($ph_ac_logo->image, 'pxp-thmb');
                                    $image_src = JOBSTER_PLUGIN_PATH . '/meta/images/logo-placeholder.png';

                                    if ($image !== false) { 
                                        $image_src = $image[0];
                                    } ?>

                                    <li class="list-group-item"
                                        data-image="<?php echo esc_attr($ph_ac_logo->image); ?>"
                                        data-src="<?php echo esc_attr($image_src); ?>"
                                    >
                                        <div class="pxp-ph-ac-logos-list-item">
                                            <img src="<?php echo esc_url($image_src); ?>">
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-ph-ac-edit-logo-btn"><span class="fa fa-pencil"></span></a>
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-ac-del-logo-btn"><span class="fa fa-trash-o"></span></a>
                                            </div>
                                        </div>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </td>
                </tr>
                <tr><td width="100%" valign="top">&nbsp;</td></tr>
                <tr>
                    <td width="100%" valign="top">
                        <input id="pxp-ph-ac-add-logo-btn" type="button" class="button" value="<?php esc_html_e('Add Logo', 'jobster'); ?>">
                    </td>
                </tr>
            </table>
            <div class="pxp-ph-ac-new-logo">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top">
                            <div class="pxp-ph-ac-new-logo-container">
                                <div class="pxp-ph-ac-new-logo-header"><b><?php esc_html_e('New Logo', 'jobster'); ?></b></div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="33%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label><?php esc_html_e('Image', 'jobster'); ?></label>
                                                <input type="hidden" id="ph_animated_cards_logo_image" name="ph_animated_cards_logo_image">
                                                <div class="pxp-ph-ac-logo-image-placeholder-container">
                                                    <div id="pxp-ph-ac-logo-image-placeholder" style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'meta/images/logo-placeholder.png'); ?>);"></div>
                                                    <div id="pxp-ph-ac-delete-logo-image"><span class="fa fa-trash-o"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="67%" valign="top">&nbsp;</td>
                                    </tr>
                                </table>
                                <div class="form-field">
                                    <button type="button" id="pxp-ph-ac-ok-logo" class="button media-button button-primary"><?php esc_html_e('Add', 'jobster'); ?></button>
                                    <button type="button" id="pxp-ph-ac-cancel-logo" class="button media-button button-default"><?php esc_html_e('Cancel', 'jobster'); ?></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <br><hr>

            <p><?php esc_html_e('Cards Photo', 'jobster'); ?></p>

            <?php $ph_animated_cards_photo_val = get_post_meta($post_id, 'ph_animated_cards_photo', true);
            $ph_animated_cards_photo = wp_get_attachment_image_src($ph_animated_cards_photo_val, 'pxp-thmb');
            $ph_animated_cards_photo_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

            $ph_animated_cards_has_image = '';
            if ($ph_animated_cards_photo !== false) { 
                $ph_animated_cards_photo_src = $ph_animated_cards_photo[0];
                $ph_animated_cards_has_image = 'has-image';
            } ?>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <input type="hidden" id="ph_animated_cards_photo" name="ph_animated_cards_photo" value="<?php echo esc_attr($ph_animated_cards_photo_val); ?>">
                            <div class="pxp-ph-ac-photo-placeholder-container <?php echo esc_attr($ph_animated_cards_has_image); ?>">
                                <div id="pxp-ph-ac-photo-placeholder" style="background-image: url(<?php echo esc_url($ph_animated_cards_photo_src); ?>);"></div>
                                <div id="pxp-ph-ac-delete-photo"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <p><?php esc_html_e('Info Cards', 'jobster'); ?></p>

            <?php $ph_animated_cards_info_val = get_post_meta($post_id, 'ph_animated_cards_info', true);

            $ph_animated_cards_info_list = array();

            if ($ph_animated_cards_info_val != '') {
                $ph_animated_cards_info_data = json_decode(urldecode($ph_animated_cards_info_val));
    
                if (isset($ph_animated_cards_info_data)) {
                    $ph_animated_cards_info_list = $ph_animated_cards_info_data->info;
                }
            } ?>

            <input type="hidden" id="ph_animated_cards_info" name="ph_animated_cards_info" value="<?php echo esc_attr($ph_animated_cards_info_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-ac-info-list">
                            <?php if (count($ph_animated_cards_info_list) > 0) {
                                foreach ($ph_animated_cards_info_list as $ph_ac_info) { ?>
                                    <li class="list-group-item"
                                        data-number="<?php echo esc_attr($ph_ac_info->number); ?>"
                                        data-label="<?php echo esc_attr($ph_ac_info->label); ?>"
                                        data-text="<?php echo esc_attr($ph_ac_info->text); ?>"
                                    >
                                        <div class="pxp-ph-ac-info-list-item">
                                            <div class="pxp-ph-ac-info-list-item-number-label">
                                                <span><?php echo esc_html($ph_ac_info->number); ?></span><?php echo esc_html($ph_ac_info->label); ?>
                                            </div>
                                            <div class="pxp-ph-ac-info-list-item-text">
                                                <?php echo esc_html($ph_ac_info->text); ?>
                                            </div>
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-ph-ac-edit-info-btn"><span class="fa fa-pencil"></span></a>
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-ac-del-info-btn"><span class="fa fa-trash-o"></span></a>
                                            </div>
                                        </div>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </td>
                </tr>
                <tr><td width="100%" valign="top">&nbsp;</td></tr>
                <tr>
                    <td width="100%" valign="top">
                        <?php $add_info_btn_style = count($ph_animated_cards_info_list) > 2 ? 'display: none' : ''; ?>
                        <input id="pxp-ph-ac-add-info-btn" style="<?php echo esc_attr($add_info_btn_style); ?>" type="button" class="button" value="<?php esc_html_e('Add Info', 'jobster'); ?>">
                    </td>
                </tr>
            </table>
            <div class="pxp-ph-ac-new-info">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top">
                            <div class="pxp-ph-ac-new-info-container">
                                <div class="pxp-ph-ac-new-info-header"><b><?php esc_html_e('New Info', 'jobster'); ?></b></div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="10%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label for="ph_animated_cards_info_number"><?php esc_html_e('Number', 'jobster'); ?></label><br />
                                                <input type="text" id="ph_animated_cards_info_number" name="ph_animated_cards_info_number">
                                            </div>
                                        </td>
                                        <td width="90%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label for="ph_animated_cards_info_label"><?php esc_html_e('Label', 'jobster'); ?></label><br />
                                                <input type="text" id="ph_animated_cards_info_label" name="ph_animated_cards_info_label">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="100%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label for="ph_animated_cards_info_text"><?php esc_html_e('Text', 'jobster'); ?></label><br />
                                                <input type="text" id="ph_animated_cards_info_text" name="ph_animated_cards_info_text">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="form-field">
                                    <button type="button" id="pxp-ph-ac-ok-info" class="button media-button button-primary"><?php esc_html_e('Add', 'jobster'); ?></button>
                                    <button type="button" id="pxp-ph-ac-cancel-info" class="button media-button button-default"><?php esc_html_e('Cancel', 'jobster'); ?></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <?php }
endif;
?>