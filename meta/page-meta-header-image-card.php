<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_image_card_header_meta')): 
    function jobster_get_image_card_header_meta($post_id, $header_types_value) {
        $hide_image_card = ($header_types_value == 'image_card') ? 'block' : 'none'; ?>

        <div class="pxp-header-settings pxp-header-image_card-settings" style="display: <?php echo esc_attr($hide_image_card); ?>">
            <p><strong><?php esc_html_e('Image Card Settings', 'jobster'); ?></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_image_card_caption_title"><?php esc_html_e('Caption title', 'jobster'); ?></label><br />
                            <input type="text" id="ph_image_card_caption_title" name="ph_image_card_caption_title" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_image_card_caption_title', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_image_card_caption_subtitle"><?php esc_html_e('Caption subtitle', 'jobster'); ?></label><br />
                            <input type="text" id="ph_image_card_caption_subtitle" name="ph_image_card_caption_subtitle" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_image_card_caption_subtitle', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_image_card_show_search_val = get_post_meta($post_id, 'ph_image_card_show_search', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_image_card_show_search_field">
                                <input type="hidden" name="ph_image_card_show_search" value="0">
                                <input type="checkbox" name="ph_image_card_show_search" id="ph_image_card_show_search_field" value="1" <?php checked($ph_image_card_show_search_val, true, true); ?>>
                                <?php esc_html_e('Show jobs search form', 'jobster'); ?>
                            </label>
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_image_card_show_popular_val = get_post_meta($post_id, 'ph_image_card_show_popular', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_image_card_show_popular_field">
                                <input type="hidden" name="ph_image_card_show_popular" value="0">
                                <input type="checkbox" name="ph_image_card_show_popular" id="ph_image_card_show_popular_field" value="1" <?php checked($ph_image_card_show_popular_val, true, true); ?>>
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
                            <label for="ph_image_card_search_system"><?php esc_html_e('Search System', 'jobster'); ?></label>
                            <select type="text" id="ph_image_card_search_system" name="ph_image_card_search_system">
                                <?php $ph_image_card_search_system = get_post_meta($post_id, 'ph_image_card_search_system', true);
                                $search_systems = array(
                                    'default' => __('Default', 'jobster'),
                                    'careerjet' => __('Careerjet', 'jobster')
                                );
                                foreach ($search_systems as $system_k => $system_value) { ?>
                                    <option value="<?php echo esc_attr($system_k); ?>" <?php selected($ph_image_card_search_system, $system_k); ?>>
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

            <?php $ph_image_card_logos_val = get_post_meta($post_id, 'ph_image_card_logos', true); 

            $ph_image_card_logos_list = array();

            if ($ph_image_card_logos_val != '') {
                $ph_image_card_logos_data = json_decode(urldecode($ph_image_card_logos_val));
    
                if (isset($ph_image_card_logos_data)) {
                    $ph_image_card_logos_list = $ph_image_card_logos_data->logos;
                }
            } ?>

            <input type="hidden" id="ph_image_card_logos" name="ph_image_card_logos" value="<?php echo esc_attr($ph_image_card_logos_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-ic-logos-list">
                            <?php if (count($ph_image_card_logos_list) > 0) {
                                foreach ($ph_image_card_logos_list as $ph_ic_logo) {
                                    $image = wp_get_attachment_image_src($ph_ic_logo->image, 'pxp-thmb');
                                    $image_src = JOBSTER_PLUGIN_PATH . '/meta/images/logo-placeholder.png';

                                    if ($image !== false) { 
                                        $image_src = $image[0];
                                    } ?>

                                    <li class="list-group-item"
                                        data-image="<?php echo esc_attr($ph_ic_logo->image); ?>"
                                        data-src="<?php echo esc_attr($image_src); ?>"
                                    >
                                        <div class="pxp-ph-ic-logos-list-item">
                                            <img src="<?php echo esc_url($image_src); ?>">
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-ph-ic-edit-logo-btn"><span class="fa fa-pencil"></span></a>
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-ic-del-logo-btn"><span class="fa fa-trash-o"></span></a>
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
                        <input id="pxp-ph-ic-add-logo-btn" type="button" class="button" value="<?php esc_html_e('Add Logo', 'jobster'); ?>">
                    </td>
                </tr>
            </table>
            <div class="pxp-ph-ic-new-logo">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top">
                            <div class="pxp-ph-ic-new-logo-container">
                                <div class="pxp-ph-ic-new-logo-header"><b><?php esc_html_e('New Logo', 'jobster'); ?></b></div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="33%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label><?php esc_html_e('Image', 'jobster'); ?></label>
                                                <input type="hidden" id="ph_image_card_logo_image" name="ph_image_card_logo_image">
                                                <div class="pxp-ph-ic-logo-image-placeholder-container">
                                                    <div id="pxp-ph-ic-logo-image-placeholder" style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'meta/images/logo-placeholder.png'); ?>);"></div>
                                                    <div id="pxp-ph-ic-delete-logo-image"><span class="fa fa-trash-o"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="67%" valign="top">&nbsp;</td>
                                    </tr>
                                </table>
                                <div class="form-field">
                                    <button type="button" id="pxp-ph-ic-ok-logo" class="button media-button button-primary"><?php esc_html_e('Add', 'jobster'); ?></button>
                                    <button type="button" id="pxp-ph-ic-cancel-logo" class="button media-button button-default"><?php esc_html_e('Cancel', 'jobster'); ?></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <br><hr>

            <p><?php esc_html_e('Card Photo', 'jobster'); ?></p>

            <?php $ph_image_card_photo_val = get_post_meta($post_id, 'ph_image_card_photo', true);
            $ph_image_card_photo = wp_get_attachment_image_src($ph_image_card_photo_val, 'pxp-thmb');
            $ph_image_card_photo_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

            $ph_image_card_has_image = '';
            if ($ph_image_card_photo !== false) { 
                $ph_image_card_photo_src = $ph_image_card_photo[0];
                $ph_image_card_has_image = 'has-image';
            } ?>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <input type="hidden" id="ph_image_card_photo" name="ph_image_card_photo" value="<?php echo esc_attr($ph_image_card_photo_val); ?>">
                            <div class="pxp-ph-ic-photo-placeholder-container <?php echo esc_attr($ph_image_card_has_image); ?>">
                                <div id="pxp-ph-ic-photo-placeholder" style="background-image: url(<?php echo esc_url($ph_image_card_photo_src); ?>);"></div>
                                <div id="pxp-ph-ic-delete-photo"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    <?php }
endif;
?>