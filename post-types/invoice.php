<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Register invoice custom post type
 */
if (!function_exists('jobster_register_invoice_type')): 
    function jobster_register_invoice_type() {
        register_post_type('invoice', array(
            'labels' => array(
                'name'                  => __('Invoices','jobster'),
                'singular_name'         => __('Invoice','jobster'),
                'add_new'               => __('Add New Invoice','jobster'),
                'add_new_item'          => __('Add Invoice','jobster'),
                'edit'                  => __('Edit','jobster'),
                'edit_item'             => __('Edit Invoice','jobster'),
                'new_item'              => __('New Invoice','jobster'),
                'view'                  => __('View','jobster'),
                'view_item'             => __('View Invoice','jobster'),
                'search_items'          => __('Search Invoices','jobster'),
                'not_found'             => __('No Invoices found','jobster'),
                'not_found_in_trash'    => __('No Invoices found in Trash','jobster'),
                'parent'                => __('Parent Invoice', 'jobster'),
            ),
            'public'                => true,
            'exclude_from_search '  => true,
            'has_archive'           => true,
            'rewrite'               => array('slug' => _x('invoices', 'URL SLUG', 'jobster')),
            'supports'              => array('title'),
            'can_export'            => true,
            'register_meta_box_cb'  => 'jobster_add_invoice_metaboxes',
            'menu_icon'             => 'dashicons-media-spreadsheet'
        ));
    }
endif;
add_action('init', 'jobster_register_invoice_type');

if (!function_exists('jobster_add_invoice_metaboxes')): 
    function jobster_add_invoice_metaboxes() {
        add_meta_box(
            'invoice-details-section',
            __('Details', 'jobster'),
            'jobster_invoice_details_render',
            'invoice',
            'normal',
            'default'
        );
    }
endif;

if (!function_exists('jobster_invoice_details_render')): 
    function jobster_invoice_details_render($post) {
        wp_nonce_field('jobster_invoice', 'invoice_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label style="font-weight: bold;">' . __('Invoice ID', 'jobster') . ': ' . $post->ID . '</label> 
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="invoice_item_type">' . __('Item Type', 'jobster') . '</label><br />';
                            print jobster_item_types(esc_html(get_post_meta($post->ID, 'invoice_item_type', true)));
                            print '
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom">
                            <label for="invoice_item_id">' . __('Item ID', 'jobster') . '</label><br />
                            <input type="text" class="formInput" id="invoice_item_id" name="invoice_item_id" placeholder="' . __('Enter Item ID', 'jobster') . '" value="' . esc_attr(get_post_meta($post->ID, 'invoice_item_id', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="invoice_item_price">' . __('Item Price', 'jobster') . '</label><br />
                            <input type="text" class="formInput" id="invoice_item_price" name="invoice_item_price" placeholder="' . __('Enter Item Price', 'jobster') . '" value="' . esc_attr(get_post_meta($post->ID, 'invoice_item_price', true)) . '" />
                        </div>
                    </td>
                    <td width="50%" valign="top" align="left">
                        <div class="form-field pxp-is-custom pxp-is-last">
                            <label for="invoice_company_id">' . __('Company ID', 'jobster') . '</label><br />
                            <input type="text" class="formInput" id="invoice_company_id" name="invoice_company_id" placeholder="' . __('Enter Company ID', 'jobster') . '" value="' . esc_attr(get_post_meta($post->ID, 'invoice_company_id', true)) . '" />
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_invoice_meta_save')): 
    function jobster_invoice_meta_save($post_id) {
        $is_autosave    = wp_is_post_autosave($post_id);
        $is_revision    = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['invoice_noncename']) && wp_verify_nonce($_POST['invoice_noncename'], 'jobster_invoice')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['invoice_item_type'])) {
            update_post_meta($post_id, 'invoice_item_type', sanitize_text_field($_POST['invoice_item_type']));
        }
        if (isset($_POST['invoice_item_id'])) {
            update_post_meta($post_id, 'invoice_item_id', sanitize_text_field($_POST['invoice_item_id']));
        }
        if (isset($_POST['invoice_item_price'])) {
            update_post_meta($post_id, 'invoice_item_price', sanitize_text_field($_POST['invoice_item_price']));
        }
        if (isset($_POST['invoice_company_id'])) {
            update_post_meta($post_id, 'invoice_company_id', sanitize_text_field($_POST['invoice_company_id']));
        }
    }
endif;
add_action('save_post', 'jobster_invoice_meta_save');

if (!function_exists('jobster_item_types')): 
    function jobster_item_types($selected) {
        $types = array(
            'standard_job_posting' => __('Standard Job Posting', 'jobster'),
            'job_upgraded_featured' => __('Job Upgraded to Featured', 'jobster'),
            'featured_job' => __('Featured Job', 'jobster'),
            'membership_plan' => __('Membership Plan', 'jobster'),
        );

        $type_select = '<select id="invoice_item_type" name="invoice_item_type">';

        foreach ($types as $t_key => $t_value) {
            $type_select .= '<option value="' . esc_attr($t_key) . '"';

            if ($selected == $t_key) {
                $type_select .= 'selected="selected"';
            }

            $type_select .= '>' . esc_html($t_value) . '</option>';
        }

        $type_select .= '</select>';

        return $type_select;
    }
endif;

if (!function_exists('jobster_insert_invoice')):
    function jobster_insert_invoice($item_type, $item_id, $company_id, $is_featured, $is_upgrade) {
        $post = array(
            'post_type'   => 'invoice', 
            'post_status' => 'publish',
        );

        $post_id = wp_insert_post($post);

        $membership_settings       = get_option('jobster_membership_settings');
        $submission_price          = isset($membership_settings['jobster_submission_price_field']) ? floatval($membership_settings['jobster_submission_price_field']) : 0;
        $featured_submission_price = isset($membership_settings['jobster_featured_price_field']) ? floatval($membership_settings['jobster_featured_price_field']) : 0;

        if ($item_type == 'membership_plan') {
            $price = get_post_meta($item_id, 'membership_plan_price', true);
        } else {
            if ($is_upgrade == 1) {
                $price = $featured_submission_price;
            } else {
                if ($is_featured == 1) {
                    $price = $submission_price + $featured_submission_price;
                } else {
                    $price = $submission_price;
                }
            }
        }

        update_post_meta($post_id, 'invoice_item_type', $item_type);
        update_post_meta($post_id, 'invoice_item_id', $item_id);
        update_post_meta($post_id, 'invoice_item_price', $price);
        update_post_meta($post_id, 'invoice_company_id', $company_id);

        $new_post = array(
           'ID'         => $post_id,
           'post_title' => 'Invoice ' . $post_id,
        );

        wp_update_post($new_post);
    }
endif;

/**
 * Add custom columns in invoices list
 */
if (!function_exists('jobster_invoices_columns')): 
    function jobster_invoices_columns($columns) {
        $date  = $columns['date'];

        unset($columns['date']);

        $columns['invoice_type']  = __('Item Type', 'jobster');
        $columns['invoice_price'] = __('Price', 'jobster');
        $columns['invoice_company'] = __('Purchased By', 'jobster');
        $columns['date']          = $date;

        return $columns;
    }
endif;
add_filter('manage_invoice_posts_columns', 'jobster_invoices_columns');

if (!function_exists('jobster_invoices_custom_column')): 
    function jobster_invoices_custom_column($column, $post_id) {
        switch ($column) {
            case 'invoice_type':
                $type = get_post_meta($post_id, 'invoice_item_type', true);

                echo esc_html($type);

                break;
            case 'invoice_price':
                $price = get_post_meta($post_id, 'invoice_item_price', true);

                echo esc_html($price);

                break;
            case 'invoice_company':
                $company_id   = get_post_meta($post_id, 'invoice_company_id', true);
                $company_name = get_the_title($company_id);

                echo esc_html($company_name);

                break;
        }
    }
endif;
add_action('manage_invoice_posts_custom_column', 'jobster_invoices_custom_column', 10, 2);

if (!function_exists('jobster_invoices_sortable_columns')): 
    function jobster_invoices_sortable_columns($columns) {
        $columns['invoice_type']  = 'invoice_type';
        $columns['invoice_price'] = 'invoice_price';
        $columns['invoice_company'] = 'invoice_company';

        return $columns;
    }
endif;
add_filter('manage_edit-invoice_sortable_columns', 'jobster_invoices_sortable_columns');

if (!function_exists('jobster_invoices_custom_orderby')): 
    function jobster_invoices_custom_orderby($query) {
        if (!is_admin()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('invoice_type' == $orderby) {
            $query->set('meta_key', 'invoice_item_type');
            $query->set('orderby', 'meta_value');
        }

        if ('invoice_price' == $orderby) {
            $query->set('meta_key', 'invoice_item_price');
            $query->set('orderby', 'meta_value_no');
        }

        if ('invoice_company' == $orderby) {
            $query->set('meta_key', 'invoice_company_id');
            $query->set('orderby', 'meta_value_no');
        }
    }
endif;
add_action('pre_get_posts', 'jobster_invoices_custom_orderby');
?>