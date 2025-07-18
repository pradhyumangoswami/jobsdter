<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_jobs_fields')):
    function jobster_admin_jobs_fields() {
        add_settings_section(
            'jobster_jobs_fields_section',
            __('Jobs Custom Fields', 'jobster' ),
            'jobster_jobs_fields_section_callback',
            'jobster_jobs_fields_settings'
        );
    }
endif;

if (!function_exists('jobster_jobs_fields_section_callback')): 
    function jobster_jobs_fields_section_callback() {
        wp_nonce_field(
            'jobs_fields_ajax_nonce',
            'pxp-jobs-fields-security',
            true
        );

        $field_types = array(
            'text_field'    => __('Text', 'jobster'),
            'numeric_field' => __('Numeric', 'jobster'),
            'date_field'    => __('Date', 'jobster'),
            'list_field'    => __('List', 'jobster')
        ); ?>

        <p class="help">
            <?php esc_html_e('These fields will be displayed under Additional Info section on the job details page', 'jobster'); ?>
        </p>

        <h4><?php esc_html_e('Add New Custom Fields', 'jobster'); ?></h4>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="jobs_field_name">
                            <?php esc_html_e('Field Name', 'jobster'); ?>
                        </label>
                    </th>
                    <td>
                        <input 
                            type="text" 
                            size="40" 
                            name="jobs_field_name" 
                            id="jobs_field_name"
                        >
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jobs_field_label">
                            <?php esc_html_e('Field Label', 'jobster'); ?>
                        </label>
                    </th>
                    <td>
                        <input 
                            type="text" 
                            size="40" 
                            name="jobs_field_label" 
                            id="jobs_field_label"
                        >
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jobs_field_type">
                            <?php esc_html_e('Field Type', 'jobster'); ?>
                        </label>
                    </th>
                    <td>
                        <select 
                            name="jobs_field_type" 
                            id="jobs_field_type"
                        >
                            <?php foreach ($field_types as $ft_key => $ft_value) { ?>
                                <option value="<?php echo esc_attr($ft_key); ?>">
                                    <?php echo esc_attr($ft_value); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input 
                            type="text" 
                            size="40" 
                            name="jobs_field_list_items" 
                            id="jobs_field_list_items" 
                            style="display: none;"
                        >
                        <p 
                            class="help" 
                            style="display: none; margin-left: 96px;"
                        >
                            <?php esc_html_e('Enter the list values separated by comma.', 'jobster'); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jobs_field_mandatory">
                            <?php esc_html_e('Mandatory', 'jobster'); ?>
                        </label>
                    </th>
                    <td>
                        <select 
                            name="jobs_field_mandatory" 
                            id="jobs_field_mandatory"
                        >
                            <option value="no">
                                <?php esc_html_e('No', 'jobster'); ?>
                            </option>
                            <option value="yes">
                                <?php esc_html_e('Yes', 'jobster'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jobs_field_position">
                            <?php esc_html_e('Position', 'jobster'); ?>
                        </label>
                    </th>
                    <td>
                        <input 
                            type="text" 
                            size="4" 
                            name="jobs_field_position" 
                            id="jobs_field_position" 
                            value="0"
                        >
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <a
                href="javascript:void(0);" 
                id="pxp_add_jobs_field_btn" 
                class="button button-secondary"
            >
                <span class="fa fa-spin fa-spinner pxp-btn-preloader"></span>
                <?php esc_attr_e('Add Field', ';jobster'); ?>
            </a>
        </p>

        <h4><?php esc_html_e('Custom Fields List', 'jobs'); ?></h4>
        <table class="wp-list-table widefat fixed striped pxp-settings-table" id="pxp-custom-fields-table">
            <thead>
                <tr>
                    <th style="width: 20%;">
                        <?php esc_html_e('Field Name', 'jobster'); ?>
                    </th>
                    <th style="width: 20%;">
                        <?php esc_html_e('Field Label', 'jobster'); ?>
                    </th>
                    <th style="width: 35%;">
                        <?php esc_html_e('Field Type', 'jobster'); ?>
                    </th>
                    <th><?php esc_html_e('Mandatory', 'jobster'); ?></th>
                    <th><?php esc_html_e('Position', 'jobster'); ?></th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $options = get_option('jobster_jobs_fields_settings');

                if (is_array($options)) {
                    uasort($options, 'jobster_compare_position');

                    foreach ($options as $o_key => $o_value) { ?>
                        <tr>
                            <td>
                                <input 
                                    type="text" 
                                    name="jobster_jobs_fields_settings[<?php echo esc_attr($o_key); ?>][name]" 
                                    value="<?php echo esc_attr($o_value['name']); ?>"
                                >
                            </td>
                            <td>
                                <input 
                                    type="text" 
                                    name="jobster_jobs_fields_settings[<?php echo esc_attr($o_key); ?>][label]" 
                                    value="<?php echo esc_attr($o_value['label']); ?>"
                                >
                            </td>
                            <td>
                                <select 
                                    class="pxp-table-field-type" 
                                    name="jobster_jobs_fields_settings[<?php echo esc_attr($o_key); ?>][type]"
                                >
                                    <?php foreach ($field_types as $oft_key => $oft_value) { ?>
                                        <option 
                                            value="<?php echo esc_attr($oft_key); ?>" 
                                            <?php selected(isset($o_value['type']) && $o_value['type'] == $oft_key, true, true); ?>
                                        >
                                            <?php echo esc_html($oft_value); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <input 
                                    type="text" 
                                    name="jobster_jobs_fields_settings[<?php echo esc_attr($o_key); ?>][list]" 
                                    value="<?php echo esc_attr($o_value['list']); ?>" 
                                    style="display:none;min-width:200px;" 
                                    placeholder="<?php esc_attr_e('Comma separated values', 'jobster'); ?>"
                                >
                            </td>
                            <td>
                                <select name="jobster_jobs_fields_settings[<?php echo esc_attr($o_key); ?>][mandatory]">
                                    <option 
                                        value="no" 
                                        <?php selected(isset($o_value['mandatory']) && $o_value['mandatory'] == 'no', true, true); ?>
                                    >
                                        <?php esc_html_e('No', 'jobster'); ?>
                                    </option>
                                    <option 
                                        value="yes" 
                                        <?php selected(isset($o_value['mandatory']) && $o_value['mandatory'] == 'yes', true, true); ?>
                                    >
                                        <?php esc_html_e('Yes', 'jobster'); ?>
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input 
                                    type="text" 
                                    size="4" 
                                    name="jobster_jobs_fields_settings[<?php echo esc_attr($o_key); ?>][position]" 
                                    value="<?php echo esc_attr($o_value['position']); ?>"
                                >
                            </td>
                            <td style="text-align:right;vertical-align:middle;">
                                <a 
                                    href="javascript:void(0);" 
                                    data-row="<?php echo esc_attr($o_key); ?>" 
                                    class="pxp-list-del-btn pxp-del-job-field"
                                >
                                    <span class="fa fa-trash-o"></span>
                                </a>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    <?php }
endif;

if (!function_exists('jobster_add_jobs_fields')): 
    function jobster_add_jobs_fields() {
        check_ajax_referer('jobs_fields_ajax_nonce', 'security');

        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $label = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $list = isset($_POST['list']) ? sanitize_text_field($_POST['list']) : '';
        $mandatory = isset($_POST['mandatory']) ? sanitize_text_field($_POST['mandatory']) : '';
        $position = isset($_POST['position']) ? sanitize_text_field($_POST['position']) : '';

        if ($name == '') {
            echo json_encode(array(
                'add' => false,
                'message' => __('Field name is mandatory.', 'jobster')
            ));
            exit();
        }
        if ($label == '') {
            echo json_encode(array(
                'add' => false,
                'message' => __('Field label is mandatory.', 'jobster'))
            );
            exit();
        }
        if ($type == '') {
            echo json_encode(array(
                'add' => false,
                'message' => __('Field type is mandatory.', 'jobster'))
            );
            exit();
        }
        if ($type != '' && $type == 'list_field' && $list == '') {
            echo json_encode(array(
                'add' => false, 
                'message' => __('The list requires at least one element.', 'jobster'))
            );
            exit();
        }
        if ($position == '') {
            echo json_encode(array(
                'add' => false,
                'message' => __('Position is mandatory.', 'jobster'))
            );
            exit();
        }

        $var_name = str_replace(' ', '_', trim($name));
        $var_name = sanitize_key(strtolower($var_name));

        $jobs_fields_settings = get_option('jobster_jobs_fields_settings');

        if (!is_array($jobs_fields_settings)) {
            $jobs_fields_settings = array();
        }

        $jobs_fields_settings[$var_name]['name']      = $var_name;
        $jobs_fields_settings[$var_name]['label']     = $label;
        $jobs_fields_settings[$var_name]['type']      = $type;
        $jobs_fields_settings[$var_name]['list']      = $list;
        $jobs_fields_settings[$var_name]['mandatory'] = $mandatory;
        $jobs_fields_settings[$var_name]['position']  = $position;

        update_option('jobster_jobs_fields_settings', $jobs_fields_settings);

        echo json_encode(array('add' => true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_add_jobs_fields', 'jobster_add_jobs_fields');
add_action('wp_ajax_jobster_add_jobs_fields', 'jobster_add_jobs_fields');

if (!function_exists('jobster_delete_jobs_fields')): 
    function jobster_delete_jobs_fields() {
        check_ajax_referer('jobs_fields_ajax_nonce', 'security');

        $field_name =   isset($_POST['field_name']) 
                        ? sanitize_text_field($_POST['field_name']) 
                        : '';

        $jobs_fields_settings = get_option('jobster_jobs_fields_settings');

        unset($jobs_fields_settings[$field_name]);
        update_option('jobster_jobs_fields_settings', $jobs_fields_settings);

        echo json_encode(array('delete' => true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_delete_jobs_fields', 'jobster_delete_jobs_fields');
add_action('wp_ajax_jobster_delete_jobs_fields', 'jobster_delete_jobs_fields');
?>