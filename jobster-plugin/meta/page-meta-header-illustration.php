<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_illustration_header_meta')): 
    function jobster_get_illustration_header_meta($post_id, $header_types_value) {
        $hide_illustration = ($header_types_value == 'illustration') ? 'block' : 'none'; ?>

        <div class="pxp-header-settings pxp-header-illustration-settings" style="display: <?php echo esc_attr($hide_illustration); ?>">
            <p><strong><?php esc_html_e('Illustration Settings', 'jobster'); ?></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_illustration_caption_title"><?php esc_html_e('Caption title', 'jobster'); ?></label><br />
                            <input type="text" id="ph_illustration_caption_title" name="ph_illustration_caption_title" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_illustration_caption_title', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_illustration_caption_subtitle"><?php esc_html_e('Caption subtitle', 'jobster'); ?></label><br />
                            <input type="text" id="ph_illustration_caption_subtitle" name="ph_illustration_caption_subtitle" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_illustration_caption_subtitle', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_illustration_show_search_val = get_post_meta($post_id, 'ph_illustration_show_search', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_illustration_show_search_field">
                                <input type="hidden" name="ph_illustration_show_search" value="0">
                                <input type="checkbox" name="ph_illustration_show_search" id="ph_illustration_show_search_field" value="1" <?php checked($ph_illustration_show_search_val, true, true); ?>>
                                <?php esc_html_e('Show jobs search form', 'jobster'); ?>
                            </label>
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_illustration_show_popular_val = get_post_meta($post_id, 'ph_illustration_show_popular', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_illustration_show_popular_field">
                                <input type="hidden" name="ph_illustration_show_popular" value="0">
                                <input type="checkbox" name="ph_illustration_show_popular" id="ph_illustration_show_popular_field" value="1" <?php checked($ph_illustration_show_popular_val, true, true); ?>>
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
                            <label for="ph_illustration_search_system"><?php esc_html_e('Search System', 'jobster'); ?></label>
                            <select type="text" id="ph_illustration_search_system" name="ph_illustration_search_system">
                                <?php $ph_illustration_search_system = get_post_meta($post_id, 'ph_illustration_search_system', true);
                                $search_systems = array(
                                    'default' => __('Default', 'jobster'),
                                    'careerjet' => __('Careerjet', 'jobster')
                                );
                                foreach ($search_systems as $system_k => $system_value) { ?>
                                    <option value="<?php echo esc_attr($system_k); ?>" <?php selected($ph_illustration_search_system, $system_k); ?>>
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

            <?php $ph_illustration_logos_val = get_post_meta($post_id, 'ph_illustration_logos', true); 

            $ph_illustration_logos_list = array();

            if ($ph_illustration_logos_val != '') {
                $ph_illustration_logos_data = json_decode(urldecode($ph_illustration_logos_val));
    
                if (isset($ph_illustration_logos_data)) {
                    $ph_illustration_logos_list = $ph_illustration_logos_data->logos;
                }
            } ?>

            <input type="hidden" id="ph_illustration_logos" name="ph_illustration_logos" value="<?php echo esc_attr($ph_illustration_logos_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-i-logos-list">
                            <?php if (count($ph_illustration_logos_list) > 0) {
                                foreach ($ph_illustration_logos_list as $ph_i_logo) {
                                    $image = wp_get_attachment_image_src($ph_i_logo->image, 'pxp-thmb');
                                    $image_src = JOBSTER_PLUGIN_PATH . '/meta/images/logo-placeholder.png';

                                    if ($image !== false) { 
                                        $image_src = $image[0];
                                    } ?>

                                    <li class="list-group-item"
                                        data-image="<?php echo esc_attr($ph_i_logo->image); ?>"
                                        data-src="<?php echo esc_attr($image_src); ?>"
                                    >
                                        <div class="pxp-ph-i-logos-list-item">
                                            <img src="<?php echo esc_url($image_src); ?>">
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-ph-i-edit-logo-btn"><span class="fa fa-pencil"></span></a>
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-i-del-logo-btn"><span class="fa fa-trash-o"></span></a>
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
                        <input id="pxp-ph-i-add-logo-btn" type="button" class="button" value="<?php esc_html_e('Add Logo', 'jobster'); ?>">
                    </td>
                </tr>
            </table>
            <div class="pxp-ph-i-new-logo">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top">
                            <div class="pxp-ph-i-new-logo-container">
                                <div class="pxp-ph-i-new-logo-header"><b><?php esc_html_e('New Logo', 'jobster'); ?></b></div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="33%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label><?php esc_html_e('Image', 'jobster'); ?></label>
                                                <input type="hidden" id="ph_illustration_logo_image" name="ph_illustration_logo_image">
                                                <div class="pxp-ph-i-logo-image-placeholder-container">
                                                    <div id="pxp-ph-i-logo-image-placeholder" style="background-image: url(<?php echo esc_url(JOBSTER_PLUGIN_PATH . 'meta/images/logo-placeholder.png'); ?>);"></div>
                                                    <div id="pxp-ph-i-delete-logo-image"><span class="fa fa-trash-o"></span></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="67%" valign="top">&nbsp;</td>
                                    </tr>
                                </table>
                                <div class="form-field">
                                    <button type="button" id="pxp-ph-i-ok-logo" class="button media-button button-primary"><?php esc_html_e('Add', 'jobster'); ?></button>
                                    <button type="button" id="pxp-ph-i-cancel-logo" class="button media-button button-default"><?php esc_html_e('Cancel', 'jobster'); ?></button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <br><hr>

            <p><?php esc_html_e('Illustration', 'jobster'); ?></p>

            <?php $ph_illustration_photo_val = get_post_meta($post_id, 'ph_illustration_photo', true);
            $ph_illustration_photo = wp_get_attachment_image_src($ph_illustration_photo_val, 'pxp-thmb');
            $ph_illustration_photo_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

            $ph_illustration_has_image = '';
            if ($ph_illustration_photo !== false) { 
                $ph_illustration_photo_src = $ph_illustration_photo[0];
                $ph_illustration_has_image = 'has-image';
            } ?>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <input type="hidden" id="ph_illustration_photo" name="ph_illustration_photo" value="<?php echo esc_attr($ph_illustration_photo_val); ?>">
                            <div class="pxp-ph-i-photo-placeholder-container <?php echo esc_attr($ph_illustration_has_image); ?>">
                                <div id="pxp-ph-i-photo-placeholder" style="background-image: url(<?php echo esc_url($ph_illustration_photo_src); ?>);"></div>
                                <div id="pxp-ph-i-delete-photo"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    <?php }
endif;
?>