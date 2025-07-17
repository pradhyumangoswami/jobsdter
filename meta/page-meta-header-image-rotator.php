<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_image_rotator_header_meta')): 
    function jobster_get_image_rotator_header_meta($post_id, $header_types_value) {
        $hide_image_rotator = ($header_types_value == 'image_rotator') ? 'block' : 'none'; ?>

        <div class="pxp-header-settings pxp-header-image_rotator-settings" style="display: <?php echo esc_attr($hide_image_rotator); ?>">
            <p><strong><?php esc_html_e('Image Rotator Settings', 'jobster'); ?></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_image_rotator_caption_title"><?php esc_html_e('Caption title', 'jobster'); ?></label><br />
                            <input type="text" id="ph_image_rotator_caption_title" name="ph_image_rotator_caption_title" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_image_rotator_caption_title', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_image_rotator_caption_subtitle"><?php esc_html_e('Caption subtitle', 'jobster'); ?></label><br />
                            <input type="text" id="ph_image_rotator_caption_subtitle" name="ph_image_rotator_caption_subtitle" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_image_rotator_caption_subtitle', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_image_rotator_show_search_val = get_post_meta($post_id, 'ph_image_rotator_show_search', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_image_rotator_show_search_field">
                                <input type="hidden" name="ph_image_rotator_show_search" value="0">
                                <input type="checkbox" name="ph_image_rotator_show_search" id="ph_image_rotator_show_search_field" value="1" <?php checked($ph_image_rotator_show_search_val, true, true); ?>>
                                <?php esc_html_e('Show jobs search form', 'jobster'); ?>
                            </label>
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_image_rotator_show_popular_val = get_post_meta($post_id, 'ph_image_rotator_show_popular', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_image_rotator_show_popular_field">
                                <input type="hidden" name="ph_image_rotator_show_popular" value="0">
                                <input type="checkbox" name="ph_image_rotator_show_popular" id="ph_image_rotator_show_popular_field" value="1" <?php checked($ph_image_rotator_show_popular_val, true, true); ?>>
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
                            <label for="ph_image_rotator_search_system"><?php esc_html_e('Search System', 'jobster'); ?></label>
                            <select type="text" id="ph_image_rotator_search_system" name="ph_image_rotator_search_system">
                                <?php $ph_image_rotator_search_system = get_post_meta($post_id, 'ph_image_rotator_search_system', true);
                                $search_systems = array(
                                    'default' => __('Default', 'jobster'),
                                    'careerjet' => __('Careerjet', 'jobster')
                                );
                                foreach ($search_systems as $system_k => $system_value) { ?>
                                    <option value="<?php echo esc_attr($system_k); ?>" <?php selected($ph_image_rotator_search_system, $system_k); ?>>
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

            <?php $ph_image_rotator_logos_val = get_post_meta($post_id, 'ph_image_rotator_logos', true); 

            $ph_image_rotator_logos_list = array();

            if ($ph_image_rotator_logos_val != '') {
                $ph_image_rotator_logos_data = json_decode(urldecode($ph_image_rotator_logos_val));
    
                if (isset($ph_image_rotator_logos_data)) {
                    $ph_image_rotator_logos_list = $ph_image_rotator_logos_data->logos;
                }
            } ?>

            <input type="hidden" id="ph_image_rotator_logos" name="ph_image_rotator_logos" value="<?php echo esc_attr($ph_image_rotator_logos_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-ir-logos-list">
                            <?php if (count($ph_image_rotator_logos_list) > 0) {
                                foreach ($ph_image_rotator_logos_list as $ph_ir_logo) {
                                    $image = wp_get_attachment_image_src($ph_ir_logo->image, 'pxp-thmb');
                                    $image_src = JOBSTER_PLUGIN_PATH . '/meta/images/logo-placeholder.png';

                                    if ($image !== false) { 
                                        $image_src = $image[0];
                                    } ?>

                                    <li class="list-group-item"
                                        data-image="<?php echo esc_attr($ph_ir_logo->image); ?>"
                                        data-src="<?php echo esc_attr($image_src); ?>"
                                    >
                                        <div class="pxp-ph-ir-logos-list-item">
                                            <img src="<?php echo esc_url($image_src); ?>">
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-ph-ir-edit-logo-btn"><span class="fa fa-pencil"></span></a>
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-ir-del-logo-btn"><span class="fa fa-trash-o"></span></a>
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
                        <input id="pxp-ph-ir-add-logo-btn" type="button" class="button" value="<?php esc_html_e('Add Logo', 'jobster'); ?>">
                    </td>
                </tr>
            </table>
            <div class="pxp-ph-ir-new-logo">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top">
                            <div class="pxp-ph-ir-new-logo-container">
                                <div class="pxp-ph-ir-new-logo-header"><b><?php esc_html_e('New Logo', 'jobster'); ?></b></div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="33%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label><?php esc_html_e('Image', 'jobster'); ?></label>
                                                <input type="hidden" id="ph_image_rotator_logo_image" name="ph_image_rotator_logo_image">
                                                <div class="pxp-ph-ir-logo-image-placeholder-container">
                                                    <div id="pxp-ph-ir-logo-image-placeholder" style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'meta/images/logo-placeholder.png'); ?>);"></div>
                                                    <div id="pxp-ph-ir-delete-logo-image"><span class="fa fa-trash-o"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="67%" valign="top">&nbsp;</td>
                                    </tr>
                                </table>
                                <div class="form-field">
                                    <button type="button" id="pxp-ph-ir-ok-logo" class="button media-button button-primary"><?php esc_html_e('Add', 'jobster'); ?></button>
                                    <button type="button" id="pxp-ph-ir-cancel-logo" class="button media-button button-default"><?php esc_html_e('Cancel', 'jobster'); ?></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <br><hr>

            <p><?php esc_html_e('Photos List', 'jobster'); ?></p>

            <?php $ph_image_rotator_photos_val = get_post_meta($post_id, 'ph_image_rotator_photos', true);

            $ph_image_rotator_photos_list = array();

            if ($ph_image_rotator_photos_val != '') {
                $ph_image_rotator_photos_data = json_decode(urldecode($ph_image_rotator_photos_val));
    
                if (isset($ph_image_rotator_photos_data)) {
                    $ph_image_rotator_photos_list = $ph_image_rotator_photos_data->photos;
                }
            } ?>

            <input type="hidden" id="ph_image_rotator_photos" name="ph_image_rotator_photos" value="<?php echo esc_attr($ph_image_rotator_photos_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-ir-photos-list">
                            <?php if (count($ph_image_rotator_photos_list) > 0) {
                                foreach ($ph_image_rotator_photos_list as $ph_ir_photo) {
                                    $photo = wp_get_attachment_image_src($ph_ir_photo->image, 'pxp-thmb');
                                    $photo_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                                    if ($photo !== false) { 
                                        $photo_src = $photo[0];
                                    } ?>

                                    <li class="list-group-item"
                                        data-image="<?php echo esc_attr($ph_ir_photo->image); ?>"
                                        data-src="<?php echo esc_attr($photo_src); ?>"
                                    >
                                        <div class="pxp-ph-ir-photos-list-item">
                                            <img src="<?php echo esc_url($photo_src); ?>">
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-ph-ir-edit-photo-btn"><span class="fa fa-pencil"></span></a>
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-ir-del-photo-btn"><span class="fa fa-trash-o"></span></a>
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
                        <input id="pxp-ph-ir-add-photo-btn" type="button" class="button" value="<?php esc_html_e('Add Photo', 'jobster'); ?>">
                    </td>
                </tr>
            </table>
            <div class="pxp-ph-ir-new-photo">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top">
                            <div class="pxp-ph-ir-new-photo-container">
                                <div class="pxp-ph-ir-new-photo-header"><b><?php esc_html_e('New Photo', 'jobster'); ?></b></div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="33%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label><?php esc_html_e('Image', 'jobster'); ?></label>
                                                <input type="hidden" id="ph_image_rotator_photo_image" name="ph_image_rotator_photo_image">
                                                <div class="pxp-ph-ir-photo-image-placeholder-container">
                                                    <div id="pxp-ph-ir-photo-image-placeholder" style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'meta/images/photo-placeholder.png'); ?>);"></div>
                                                    <div id="pxp-ph-ir-delete-photo-image"><span class="fa fa-trash-o"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="67%" valign="top">&nbsp;</td>
                                    </tr>
                                </table>
                                <div class="form-field">
                                    <button type="button" id="pxp-ph-ir-ok-photo" class="button media-button button-primary"><?php esc_html_e('Add', 'jobster'); ?></button>
                                    <button type="button" id="pxp-ph-ir-cancel-photo" class="button media-button button-default"><?php esc_html_e('Cancel', 'jobster'); ?></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <br><hr><br>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_image_rotator_interval_val = get_post_meta($post_id, 'ph_image_rotator_interval', true); ?>
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="ph_image_rotator_interval"><?php esc_html_e('Rotator change interval', 'jobster'); ?></label><br>
                            <input type="number" min="0" name="ph_image_rotator_interval" id="ph_image_rotator_interval" value="<?php echo esc_attr($ph_image_rotator_interval_val); ?>" style="width: 80px;">&nbsp;<?php esc_html_e('seconds', 'jobster'); ?>
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">&nbsp;</td>
                </tr>
            </table>

            <br><hr>

            <p><?php esc_html_e('Info Cards', 'jobster'); ?></p>

            <?php $ph_image_rotator_info_val = get_post_meta($post_id, 'ph_image_rotator_info', true);

            $ph_image_rotator_info_list = array();

            if ($ph_image_rotator_info_val != '') {
                $ph_image_rotator_info_data = json_decode(urldecode($ph_image_rotator_info_val));
    
                if (isset($ph_image_rotator_info_data)) {
                    $ph_image_rotator_info_list = $ph_image_rotator_info_data->info;
                }
            } ?>

            <input type="hidden" id="ph_image_rotator_info" name="ph_image_rotator_info" value="<?php echo esc_attr($ph_image_rotator_info_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-ir-info-list">
                            <?php if (count($ph_image_rotator_info_list) > 0) {
                                foreach ($ph_image_rotator_info_list as $ph_ir_info) { ?>
                                    <li class="list-group-item"
                                        data-number="<?php echo esc_attr($ph_ir_info->number); ?>"
                                        data-label="<?php echo esc_attr($ph_ir_info->label); ?>"
                                        data-text="<?php echo esc_attr($ph_ir_info->text); ?>"
                                    >
                                        <div class="pxp-ph-ac-info-list-item">
                                            <div class="pxp-ph-ir-info-list-item-number-label">
                                                <span><?php echo esc_html($ph_ir_info->number); ?></span><?php echo esc_html($ph_ir_info->label); ?>
                                            </div>
                                            <div class="pxp-ph-ir-info-list-item-text">
                                                <?php echo esc_html($ph_ir_info->text); ?>
                                            </div>
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-ph-ir-edit-info-btn"><span class="fa fa-pencil"></span></a>
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-ir-del-info-btn"><span class="fa fa-trash-o"></span></a>
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
                        <?php $add_info_btn_style = count($ph_image_rotator_info_list) > 2 ? 'display: none' : ''; ?>
                        <input id="pxp-ph-ir-add-info-btn" style="<?php echo esc_attr($add_info_btn_style); ?>" type="button" class="button" value="<?php esc_html_e('Add Info', 'jobster'); ?>">
                    </td>
                </tr>
            </table>
            <div class="pxp-ph-ir-new-info">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top">
                            <div class="pxp-ph-ir-new-info-container">
                                <div class="pxp-ph-ir-new-info-header"><b><?php esc_html_e('New Info', 'jobster'); ?></b></div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="10%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label for="ph_ir_info_number"><?php esc_html_e('Number', 'jobster'); ?></label><br />
                                                <input type="text" id="ph_image_rotator_info_number" name="ph_image_rotator_info_number">
                                            </div>
                                        </td>
                                        <td width="90%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label for="ph_image_rotator_info_label"><?php esc_html_e('Label', 'jobster'); ?></label><br />
                                                <input type="text" id="ph_image_rotator_info_label" name="ph_image_rotator_info_label">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="100%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label for="ph_image_rotator_info_text"><?php esc_html_e('Text', 'jobster'); ?></label><br />
                                                <input type="text" id="ph_image_rotator_info_text" name="ph_image_rotator_info_text">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="form-field">
                                    <button type="button" id="pxp-ph-ir-ok-info" class="button media-button button-primary"><?php esc_html_e('Add', 'jobster'); ?></button>
                                    <button type="button" id="pxp-ph-ir-cancel-info" class="button media-button button-default"><?php esc_html_e('Cancel', 'jobster'); ?></button>
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