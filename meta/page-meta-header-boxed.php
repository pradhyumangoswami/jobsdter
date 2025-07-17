<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_boxed_header_meta')): 
    function jobster_get_boxed_header_meta($post_id, $header_types_value) {
        $hide_boxed = ($header_types_value == 'boxed') ? 'block' : 'none'; ?>

        <div class="pxp-header-settings pxp-header-boxed-settings" style="display: <?php echo esc_attr($hide_boxed); ?>">
            <p><strong><?php esc_html_e('Boxed Animation Settings', 'jobster'); ?></strong></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_boxed_caption_title"><?php esc_html_e('Caption title', 'jobster'); ?></label><br />
                            <input type="text" id="ph_boxed_caption_title" name="ph_boxed_caption_title" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_boxed_caption_title', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_boxed_caption_subtitle"><?php esc_html_e('Caption subtitle', 'jobster'); ?></label><br />
                            <input type="text" id="ph_boxed_caption_subtitle" name="ph_boxed_caption_subtitle" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_boxed_caption_subtitle', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <br><hr>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_boxed_show_search_val = get_post_meta($post_id, 'ph_boxed_show_search', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_boxed_show_search_field">
                                <input type="hidden" name="ph_boxed_show_search" value="0">
                                <input type="checkbox" name="ph_boxed_show_search" id="ph_boxed_show_search_field" value="1" <?php checked($ph_boxed_show_search_val, true, true); ?>>
                                <?php esc_html_e('Show jobs search form', 'jobster'); ?>
                            </label>
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <?php $ph_boxed_show_popular_val = get_post_meta($post_id, 'ph_boxed_show_popular', true); ?>
                        <div class="form-field pxp-is-custom">
                            &nbsp;<br>
                            <label for="ph_boxed_show_popular_field">
                                <input type="hidden" name="ph_boxed_show_popular" value="0">
                                <input type="checkbox" name="ph_boxed_show_popular" id="ph_boxed_show_popular_field" value="1" <?php checked($ph_boxed_show_popular_val, true, true); ?>>
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
                            <label for="ph_boxed_search_system"><?php esc_html_e('Search System', 'jobster'); ?></label>
                            <select type="text" id="ph_boxed_search_system" name="ph_boxed_search_system">
                                <?php $ph_boxed_search_system = get_post_meta($post_id, 'ph_boxed_search_system', true);
                                $search_systems = array(
                                    'default' => __('Default', 'jobster'),
                                    'careerjet' => __('Careerjet', 'jobster')
                                );
                                foreach ($search_systems as $system_k => $system_value) { ?>
                                    <option value="<?php echo esc_attr($system_k); ?>" <?php selected($ph_boxed_search_system, $system_k); ?>>
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

            <p><?php esc_html_e('Small Feature Card', 'jobster'); ?></p>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_boxed_sfc_card_label"><?php esc_html_e('Card label', 'jobster'); ?></label><br />
                            <input type="text" id="ph_boxed_sfc_card_label" name="ph_boxed_sfc_card_label" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_boxed_sfc_card_label', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">&nbsp;</td>
                </tr>
            </table>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <?php $ph_boxed_sfc_illustration_val = get_post_meta($post_id, 'ph_boxed_sfc_illustration', true);
                            $ph_boxed_sfc_illustration = wp_get_attachment_image_src($ph_boxed_sfc_illustration_val, 'pxp-thmb');
                            $ph_boxed_sfc_illustration_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                            $ph_boxed_sfc_illustration_has_image = '';
                            if ($ph_boxed_sfc_illustration !== false) { 
                                $ph_boxed_sfc_illustration_src = $ph_boxed_sfc_illustration[0];
                                $ph_boxed_sfc_illustration_has_image = 'has-image';
                            } ?>

                            <label><?php esc_html_e('Illustration', 'jobster'); ?></label>
                            <input type="hidden" id="ph_boxed_sfc_illustration" name="ph_boxed_sfc_illustration" value="<?php echo esc_attr($ph_boxed_sfc_illustration_val); ?>">
                            <div class="pxp-ph-b-sfc-illustration-placeholder-container <?php echo esc_attr($ph_boxed_sfc_illustration_has_image); ?>">
                                <div id="pxp-ph-b-sfc-illustration-placeholder" style="background-image: url(<?php echo esc_url($ph_boxed_sfc_illustration_src); ?>);"></div>
                                <div id="pxp-ph-b-sfc-delete-illustration"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <?php $ph_boxed_sfc_icon_val = get_post_meta($post_id, 'ph_boxed_sfc_icon', true);
                            $ph_boxed_sfc_icon = wp_get_attachment_image_src($ph_boxed_sfc_icon_val, 'pxp-thmb');
                            $ph_boxed_sfc_icon_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                            $ph_boxed_sfc_icon_has_image = '';
                            if ($ph_boxed_sfc_icon !== false) { 
                                $ph_boxed_sfc_icon_src = $ph_boxed_sfc_icon[0];
                                $ph_boxed_sfc_icon_has_image = 'has-image';
                            } ?>

                            <label><?php esc_html_e('Icon', 'jobster'); ?></label>
                            <input type="hidden" id="ph_boxed_sfc_icon" name="ph_boxed_sfc_icon" value="<?php echo esc_attr($ph_boxed_sfc_icon_val); ?>">
                            <div class="pxp-ph-b-sfc-icon-placeholder-container <?php echo esc_attr($ph_boxed_sfc_icon_has_image); ?>">
                                <div id="pxp-ph-b-sfc-icon-placeholder" style="background-image: url(<?php echo esc_url($ph_boxed_sfc_icon_src); ?>);"></div>
                                <div id="pxp-ph-b-sfc-delete-icon"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>

            <br><hr>

            <p><?php esc_html_e('Big Feature Card', 'jobster'); ?></p>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_boxed_bfc_card_label"><?php esc_html_e('Card label', 'jobster'); ?></label><br />
                            <input type="text" id="ph_boxed_bfc_card_label" name="ph_boxed_bfc_card_label" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_boxed_bfc_card_label', true)); ?>">
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="ph_boxed_bfc_card_text"><?php esc_html_e('Card text', 'jobster'); ?></label><br />
                            <input type="text" id="ph_boxed_bfc_card_text" name="ph_boxed_bfc_card_text" value="<?php echo esc_attr(get_post_meta($post_id, 'ph_boxed_bfc_card_text', true)); ?>">
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <?php $ph_boxed_bfc_illustration_val = get_post_meta($post_id, 'ph_boxed_bfc_illustration', true);
                            $ph_boxed_bfc_illustration = wp_get_attachment_image_src($ph_boxed_bfc_illustration_val, 'pxp-thmb');
                            $ph_boxed_bfc_illustration_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                            $ph_boxed_bfc_illustration_has_image = '';
                            if ($ph_boxed_bfc_illustration !== false) { 
                                $ph_boxed_bfc_illustration_src = $ph_boxed_bfc_illustration[0];
                                $ph_boxed_bfc_illustration_has_image = 'has-image';
                            } ?>

                            <label><?php esc_html_e('Illustration', 'jobster'); ?></label>
                            <input type="hidden" id="ph_boxed_bfc_illustration" name="ph_boxed_bfc_illustration" value="<?php echo esc_attr($ph_boxed_bfc_illustration_val); ?>">
                            <div class="pxp-ph-b-bfc-illustration-placeholder-container <?php echo esc_attr($ph_boxed_bfc_illustration_has_image); ?>">
                                <div id="pxp-ph-b-bfc-illustration-placeholder" style="background-image: url(<?php echo esc_url($ph_boxed_bfc_illustration_src); ?>);"></div>
                                <div id="pxp-ph-b-bfc-delete-illustration"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                    <td width="25%" valign="top">
                        <div class="form-field pxp-is-custom">
                            <?php $ph_boxed_bfc_icon_val = get_post_meta($post_id, 'ph_boxed_bfc_icon', true);
                            $ph_boxed_bfc_icon = wp_get_attachment_image_src($ph_boxed_bfc_icon_val, 'pxp-thmb');
                            $ph_boxed_bfc_icon_src = JOBSTER_PLUGIN_PATH . '/meta/images/photo-placeholder.png';

                            $ph_boxed_bfc_icon_has_image = '';
                            if ($ph_boxed_bfc_icon !== false) { 
                                $ph_boxed_bfc_icon_src = $ph_boxed_bfc_icon[0];
                                $ph_boxed_bfc_icon_has_image = 'has-image';
                            } ?>

                            <label><?php esc_html_e('Icon', 'jobster'); ?></label>
                            <input type="hidden" id="ph_boxed_bfc_icon" name="ph_boxed_bfc_icon" value="<?php echo esc_attr($ph_boxed_bfc_icon_val); ?>">
                            <div class="pxp-ph-b-bfc-icon-placeholder-container <?php echo esc_attr($ph_boxed_bfc_icon_has_image); ?>">
                                <div id="pxp-ph-b-bfc-icon-placeholder" style="background-image: url(<?php echo esc_url($ph_boxed_bfc_icon_src); ?>);"></div>
                                <div id="pxp-ph-b-bfc-delete-icon"><span class="fa fa-trash-o"></span></div>
                            </div>
                        </div>
                    </td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>

            <br><hr>

            <p><?php esc_html_e('Info Cards', 'jobster'); ?></p>

            <?php $ph_boxed_info_val = get_post_meta($post_id, 'ph_boxed_info', true);

            $ph_boxed_info_list = array();

            if ($ph_boxed_info_val != '') {
                $ph_boxed_info_data = json_decode(urldecode($ph_boxed_info_val));
    
                if (isset($ph_boxed_info_data)) {
                    $ph_boxed_info_list = $ph_boxed_info_data->info;
                }
            } ?>

            <input type="hidden" id="ph_boxed_info" name="ph_boxed_info" value="<?php echo esc_attr($ph_boxed_info_val); ?>">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top">
                        <ul class="list-group" id="pxp-ph-b-info-list">
                            <?php if (count($ph_boxed_info_list) > 0) {
                                foreach ($ph_boxed_info_list as $ph_b_info) { ?>
                                    <li class="list-group-item"
                                        data-number="<?php echo esc_attr($ph_b_info->number); ?>"
                                        data-label="<?php echo esc_attr($ph_b_info->label); ?>"
                                        data-text="<?php echo esc_attr($ph_b_info->text); ?>"
                                    >
                                        <div class="pxp-ph-b-info-list-item">
                                            <div class="pxp-ph-b-info-list-item-number-label">
                                                <span><?php echo esc_html($ph_b_info->number); ?></span><?php echo esc_html($ph_b_info->label); ?>
                                            </div>
                                            <div class="pxp-ph-b-info-list-item-text">
                                                <?php echo esc_html($ph_b_info->text); ?>
                                            </div>
                                            <div class="pxp-list-item-btns">
                                                <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-ph-b-edit-info-btn"><span class="fa fa-pencil"></span></a>
                                                <a href="javascript:void(0);" class="pxp-list-del-btn pxp-ph-b-del-info-btn"><span class="fa fa-trash-o"></span></a>
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
                        <?php $add_info_btn_style = count($ph_boxed_info_list) > 2 ? 'display: none' : ''; ?>
                        <input id="pxp-ph-b-add-info-btn" style="<?php echo esc_attr($add_info_btn_style); ?>" type="button" class="button" value="<?php esc_html_e('Add Info', 'jobster'); ?>">
                    </td>
                </tr>
            </table>
            <div class="pxp-ph-b-new-info">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="100%" valign="top">
                            <div class="pxp-ph-b-new-info-container">
                                <div class="pxp-ph-b-new-info-header"><b><?php esc_html_e('New Info', 'jobster'); ?></b></div>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="10%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label for="ph_boxed_info_number"><?php esc_html_e('Number', 'jobster'); ?></label><br />
                                                <input type="text" id="ph_boxed_info_number" name="ph_boxed_info_number">
                                            </div>
                                        </td>
                                        <td width="90%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label for="ph_boxed_info_label"><?php esc_html_e('Label', 'jobster'); ?></label><br />
                                                <input type="text" id="ph_boxed_info_label" name="ph_boxed_info_label">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="100%" valign="top">
                                            <div class="form-field pxp-is-custom">
                                                <label for="ph_boxed_info_text"><?php esc_html_e('Text', 'jobster'); ?></label><br />
                                                <input type="text" id="ph_boxed_info_text" name="ph_boxed_info_text">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="form-field">
                                    <button type="button" id="pxp-ph-b-ok-info" class="button media-button button-primary"><?php esc_html_e('Add', 'jobster'); ?></button>
                                    <button type="button" id="pxp-ph-b-cancel-info" class="button media-button button-default"><?php esc_html_e('Cancel', 'jobster'); ?></button>
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