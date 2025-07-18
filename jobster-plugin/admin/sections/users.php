<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_users')): 
    function jobster_admin_users() {
        add_settings_section('jobster_users_section', __('Pending Users', 'jobster'), 'jobster_users_section_callback', 'jobster_users_settings');
    }
endif;

if (!function_exists('jobster_users_section_callback')): 
    function jobster_users_section_callback() { 
        wp_nonce_field('add_pending_users_ajax_nonce', 'pxp-pending-users-security', true);

        $pending_users = get_option('jobster_users_settings');

        if (is_array($pending_users) && count($pending_users) > 0) { ?>
            <table class="wp-list-table widefat fixed striped" style="margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Name', 'jobster'); ?></th>
                        <th><?php esc_html_e('Type', 'jobster'); ?></th>
                        <th><?php esc_html_e('Email', 'jobster'); ?></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_users as $key => $value) { ?>
                        <tr data-key="<?php echo esc_attr($key); ?>">
                            <td><b><?php echo esc_html($value['name']); ?></b></td>
                            <td>
                                <?php if ($value['type'] == 'candidate') {
                                    esc_html_e('Candidate', 'jobster');
                                }
                                if ($value['type'] == 'company') {
                                    esc_html_e('Company', 'jobster');
                                } ?>
                            </td>
                            <td>
                                <a href="mailto:<?php echo esc_attr($key); ?>">
                                    <?php echo esc_html($key); ?>
                                </a>
                            </td>
                            <td style="text-align:right;">
                                <a 
                                    href="javascript:void(0);" 
                                    class="button pxp-approve-btn"
                                >
                                    <span class="fa fa-check"></span>
                                    <span class="fa fa-spin fa-spinner" style="display: none;"></span>
                                    <?php esc_html_e('Approve', 'jobster'); ?>
                                </a>
                                <a 
                                    href="javascript:void(0);" 
                                    class="button pxp-deny-btn"
                                >
                                    <span class="fa fa-close"></span>
                                    <span class="fa fa-spin fa-spinner" style="display: none;"></span>
                                    <?php esc_html_e('Deny', 'jobster'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p><i><?php esc_html_e('No pending users.', 'jobster'); ?></i></p>
        <?php }
    }
endif;
?>