<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_custom_fields')): 
    function jobster_custom_fields($item_id, $item) {
        wp_nonce_field('custom_menu_meta_nonce', '_custom_menu_meta_nonce_name');

        $desc_field_value = get_post_meta($item_id, 'menu_item_pxp_desc', true);
        $icon_type_field_value = get_post_meta(
            $item_id, 'menu_item_pxp_icontype', true
        );
        $icon_fa_field_value = get_post_meta(
            $item_id, 'menu_item_pxp_iconfa', true
        );
        $icon_img_field_value = get_post_meta(
            $item_id, 'menu_item_pxp_iconimg', true
        );
        $submenu_type = get_post_meta($item_id, 'menu_item_pxp_type', true); ?>

        <p class="description description-wide">
            <label for="edit-menu-item-pxp-desc-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Description', 'jobster'); ?><br>
                <input 
                    type="text" 
                    id="edit-menu-item-pxp-desc-<?php echo esc_attr($item_id); ?>" 
                    class="widefat" 
                    name="menu-item-pxp-desc[<?php echo esc_attr($item_id); ?>]" 
                    value="<?php echo esc_attr($desc_field_value); ?>"
                >
            </label>
        </p>

        <p class="description description-thin">
            <label for="edit-menu-item-pxp-icontype-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Icon Type', 'jobster'); ?><br>
                <select 
                    name="menu-item-pxp-icontype[<?php echo esc_attr($item_id); ?>]" 
                    id="edit-menu-item-pxp-icontype-<?php echo esc_attr($item_id); ?>" 
                    class="widefat pxp-menu-item-icontype"
                >
                    <option 
                        value="none" 
                        <?php selected($icon_type_field_value == 'none' 
                                    || $icon_type_field_value == ''); ?>
                    >
                        <?php esc_html_e('None', 'jobster'); ?>
                    </option>
                    <option 
                        value="fa" 
                        <?php selected($icon_type_field_value == 'fa'); ?>
                    >
                        <?php esc_html_e('Font Awesome', 'jobster'); ?>
                    </option>
                    <option 
                        value="img" 
                        <?php selected($icon_type_field_value == 'img'); ?>
                    >
                        <?php esc_html_e('Image', 'jobster'); ?>
                    </option>
                </select>
            </label>
        </p>

        <?php $iconfa_class = 'style="display:none;"';
        $iconimg_class = 'style="display:none;"';

        if ($icon_type_field_value == 'fa') {
            $iconfa_class = '';
        }

        $icon_src = JOBSTER_PLUGIN_PATH . 'nav/images/icon-placeholder.png';
        $has_image = '';
        if ($icon_type_field_value == 'img') {
            $iconimg_class = '';
            $icon = wp_get_attachment_image_src(
                $icon_img_field_value, 'pxp-icon'
            );

            if (is_array($icon)) {
                $has_image = 'pxp-has-image';
                $icon_src = $icon[0];
            }
        } ?>

        <p 
            class="description description-thin pxp-is-menu-iconfa" 
            <?php echo $iconfa_class; ?>
        >
            <label for="edit-menu-item-pxp-iconfa-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Icon', 'jobster'); ?><br>
                <input 
                    type="hidden" 
                    id="edit-menu-item-pxp-iconfa-<?php echo esc_attr($item_id); ?>" 
                    class="widefat pxp-icons-field" 
                    name="menu-item-pxp-iconfa[<?php echo esc_attr($item_id); ?>]" 
                    value="<?php echo esc_attr($icon_fa_field_value); ?>"
                >
                <a class="button button-secondary pxp-open-icons">
                    <?php echo esc_html('Browse Icons...', 'jobster'); ?>
                </a>
            </label>
        </p>

        <div 
            class="description description-thin pxp-is-menu-iconimg" 
            <?php echo $iconimg_class; ?>
        >
            <label for="edit-menu-item-pxp-iconfa-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Icon Image', 'jobster'); ?><br>
                <input 
                    type="hidden" 
                    id="edit-menu-item-pxp-iconimg-<?php echo esc_attr($item_id); ?>" 
                    name="menu-item-pxp-iconimg[<?php echo esc_attr($item_id); ?>]" 
                    value="<?php echo esc_attr($icon_img_field_value); ?>"
                >
            </label>
            <div 
                class="pxp-icon-placeholder-container 
                <?php echo esc_attr($has_image); ?>"
            >
                <div 
                    class="pxp-icon-image-placeholder" 
                    style="background-image: url(<?php print esc_url($icon_src); ?>);"
                ></div>
                <div class="pxp-delete-icon-image">
                    <span class="fa fa-trash-o"></span>
                </div>
            </div>
        </div>

        <p class="description description-thin">
            <label for="edit-menu-item-pxp-type-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Submenu Type', 'jobster'); ?><br>
                <select 
                    name="menu-item-pxp-type[<?php echo esc_attr($item_id); ?>]" 
                    id="edit-menu-item-pxp-type-<?php echo esc_attr($item_id); ?>" 
                    class="widefat pxp-menu-item-type"
                >
                    <option 
                        value="dropdown" 
                        <?php selected($submenu_type == 'dropdown' 
                                        || $submenu_type == '') ?>
                        
                    >
                        <?php esc_html_e('Dropdown', 'jobster'); ?>
                    </option>
                    <option 
                        value="column" 
                        <?php selected($submenu_type == 'column'); ?>
                    >
                        <?php esc_html_e('Column', 'jobster'); ?>
                    </option>
                </select>
            </label>
        </p>
    <?php }
endif;
add_action('wp_nav_menu_item_custom_fields', 'jobster_custom_fields', 10, 2);

if (!function_exists('jobster_nav_update')): 
    function jobster_nav_update($menu_id, $menu_item_db_id) {
        if (!isset($_POST['_custom_menu_meta_nonce_name']) 
            || !wp_verify_nonce(
                    $_POST['_custom_menu_meta_nonce_name'], 
                    'custom_menu_meta_nonce')
                ) {
            return $menu_id;
        }

        if (isset($_POST['menu-item-pxp-desc'][$menu_item_db_id])) {
            $desc_field_data = sanitize_text_field(
                $_POST['menu-item-pxp-desc'][$menu_item_db_id]
            );

            update_post_meta(
                $menu_item_db_id,
                'menu_item_pxp_desc',
                $desc_field_data);
        } else {
            delete_post_meta($menu_item_db_id, 'menu_item_pxp_desc');
        }

        if (isset($_POST['menu-item-pxp-icontype'][$menu_item_db_id])) {
            $icontype_field_data = sanitize_text_field(
                $_POST['menu-item-pxp-icontype'][$menu_item_db_id]
            );

            update_post_meta(
                $menu_item_db_id,
                'menu_item_pxp_icontype',
                $icontype_field_data
            );
        } else {
            delete_post_meta($menu_item_db_id, 'menu_item_pxp_icontype');
        }

        if (isset($_POST['menu-item-pxp-iconfa'][$menu_item_db_id])) {
            $iconfa_field_data = sanitize_text_field(
                $_POST['menu-item-pxp-iconfa'][$menu_item_db_id]
            );

            update_post_meta(
                $menu_item_db_id,
                'menu_item_pxp_iconfa',
                $iconfa_field_data
            );
        } else {
            delete_post_meta($menu_item_db_id, 'menu_item_pxp_iconfa');
        }

        if (isset($_POST['menu-item-pxp-iconimg'][$menu_item_db_id])) {
            $iconimg_field_data = sanitize_text_field(
                $_POST['menu-item-pxp-iconimg'][$menu_item_db_id]
            );

            update_post_meta(
                $menu_item_db_id,
                'menu_item_pxp_iconimg',
                $iconimg_field_data
            );
        } else {
            delete_post_meta($menu_item_db_id, 'menu-item-pxp-iconimg');
        }

        if (isset($_POST['menu-item-pxp-type'][$menu_item_db_id])) {
            $submenu_type_data = sanitize_text_field(
                $_POST['menu-item-pxp-type'][$menu_item_db_id]
            );

            update_post_meta(
                $menu_item_db_id,
                'menu_item_pxp_type',
                $submenu_type_data
            );
        } else {
            delete_post_meta($menu_item_db_id, 'menu_item_pxp_type');
        }
    }
endif;
add_action('wp_update_nav_menu_item', 'jobster_nav_update', 10, 2);

if (!function_exists('jobster_custom_menu_title')): 
    function jobster_custom_menu_title($title, $item) {
        if (is_object($item) && isset($item->ID)) {
            if ($item->menu_item_parent == '0') return $title;

            $custom_field = '';
            $custom_data = '';

            $desc_field_value = get_post_meta(
                $item->ID, 'menu_item_pxp_desc', true
            );
            $icon_type_field_value = get_post_meta(
                $item->ID, 'menu_item_pxp_icontype', true
            );
            $icon_fa_field_value = get_post_meta(
                $item->ID, 'menu_item_pxp_iconfa', true
            );
            $icon_img_field_value = get_post_meta(
                $item->ID, 'menu_item_pxp_iconimg', true
            );
            $submenu_type = get_post_meta(
                $item->ID, 'menu_item_pxp_type', true
            );

            if (!empty($desc_field_value)) {
                $custom_data .= 
                    'data-desc="' . esc_attr($desc_field_value) . '" ';
            }

            if (!empty($icon_type_field_value) 
                && $icon_type_field_value != 'none') {
                $custom_data .= '
                    data-icontype="' . esc_attr($icon_type_field_value) . '"';

                if (!empty($icon_fa_field_value) 
                    && $icon_type_field_value == 'fa') {
                    $custom_data .= 
                        'data-iconfa="fa ' . esc_attr($icon_fa_field_value) . '" ';
                }

                if (!empty($icon_img_field_value) 
                    && $icon_type_field_value == 'img') {
                    $icon = wp_get_attachment_image_src(
                        $icon_img_field_value, 'pxp-icon'
                    );

                    if (is_array($icon)) {
                        $custom_data .= 
                            'data-iconimg="' . esc_url($icon[0]) . '" ';
                    }

                }
            }

            if (!empty($submenu_type)) {
                $custom_data .= 
                    'data-type="' . esc_attr($submenu_type) . '" ';
            }

            if ($custom_data != '') {
                $custom_field .= 
                    '<span 
                        class="pxp-nav-item-data d-none" ' 
                        . $custom_data 
                    . '></span>';
            }

            $title .= $custom_field;
        }

        return $title;
    }
endif;
add_filter('nav_menu_item_title', 'jobster_custom_menu_title', 10, 2);
?>