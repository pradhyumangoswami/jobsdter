<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_setup')): 
    function jobster_admin_setup() {
        add_settings_section(
            'jobster_setup_section',
            __('Theme Setup', 'jobster'),
            'jobster_setup_section_callback',
            'jobster_setup_settings'
        );
    }
endif;

if (!function_exists('jobster_setup_section_callback')): 
    function jobster_setup_section_callback() { 
        wp_nonce_field(
            'theme_setup_ajax_nonce',
            'pxp-theme-setup-security',
            true
        ); ?>

        <p class="help">
            <?php esc_html_e('If you choose to import a demo content, you can run this setup to make your website look like the theme demo.', 'jobster'); ?>
        </p>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 1', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-1"
                            >
                            <?php esc_html_e('I have imported Demo 1 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-1" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-1"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 2', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-2"
                            >
                            <?php esc_html_e('I have imported Demo 2 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-2" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-2"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 3', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-3"
                            >
                            <?php esc_html_e('I have imported Demo 3 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-3" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-3"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 4', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-4"
                            >
                            <?php esc_html_e('I have imported Demo 4 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-4" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-4"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 5', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-5"
                            >
                            <?php esc_html_e('I have imported Demo 5 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-5" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-5"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 6', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-6"
                            >
                            <?php esc_html_e('I have imported Demo 6 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-6" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-6"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 7', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-7"
                            >
                            <?php esc_html_e('I have imported Demo 7 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-7" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-7"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 8', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-8"
                            >
                            <?php esc_html_e('I have imported Demo 8 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-8" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-8"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 9', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-9"
                            >
                            <?php esc_html_e('I have imported Demo 9 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-9" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-9"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 10', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-10"
                            >
                            <?php esc_html_e('I have imported Demo 10 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-10" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-10"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 11', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-11"
                            >
                            <?php esc_html_e('I have imported Demo 11 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-11" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-11"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e('Demo 12', 'jobster'); ?>
                    </th>
                    <td>
                        <label>
                            <input 
                                type="radio" 
                                name="pxp_demo_version" 
                                value="demo-12"
                            >
                            <?php esc_html_e('I have imported Demo 12 content', 'jobster'); ?>
                        </label>
                        <p class="submit pxp-theme-setup-btn">
                            <input 
                                type="button" 
                                id="pxp-theme-setup-btn-12" 
                                class="button button-primary" 
                                value="<?php esc_attr_e('Run Setup', 'jobster'); ?>" 
                                data-demo="demo-12"
                            >
                        </p>
                        <ul class="pxp-theme-setup-msg">
                            <li class="pxp-theme-setup-permalinks-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up permalinks...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-homepage-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up homepage...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-menu-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up menu...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-widgets-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up widgets...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                            <li class="pxp-theme-setup-options-msg">
                                <span class="fa fa-spin fa-spinner"></span><span class="fa fa-check"></span> <?php esc_html_e('Setting up options...', 'jobster'); ?><span class="msg-done"><?php esc_html_e('Done', 'jobster'); ?></span>
                            </li>
                        </ul>
                        <p class="pxp-theme-setup-done"><?php esc_html_e('All done.', 'jobster'); ?> <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Have fun!', 'jobster'); ?></a></p>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php }
endif;

if (!function_exists('jobster_setup_permalinks')): 
    function jobster_setup_permalinks() {
        check_ajax_referer('theme_setup_ajax_nonce', 'security');

        global $wp_rewrite;

        $wp_rewrite->set_permalink_structure('/%postname%/');
        $wp_rewrite->flush_rules();

        echo json_encode(array('setup' => true));
        exit();

        die;
    }
endif;
add_action('wp_ajax_nopriv_jobster_setup_permalinks', 'jobster_setup_permalinks');
add_action('wp_ajax_jobster_setup_permalinks', 'jobster_setup_permalinks');

if (!function_exists('jobster_setup_reading_pages')): 
    function jobster_setup_reading_pages() {
        check_ajax_referer('theme_setup_ajax_nonce', 'security');

        update_option('show_on_front', 'page');

        $home_page = get_page_by_title('Homepage');
        $blog_page = get_page_by_title('Blog');

        if (isset($home_page->ID)) {
            update_option('page_on_front', $home_page->ID);
        }

        if (isset($blog_page->ID)) {
            update_option('page_for_posts', $blog_page->ID);
        }

        echo json_encode(array('setup' => true));
        exit();

        die;
    }
endif;
add_action('wp_ajax_nopriv_jobster_setup_reading_pages', 'jobster_setup_reading_pages');
add_action('wp_ajax_jobster_setup_reading_pages', 'jobster_setup_reading_pages');

if (!function_exists('jobster_setup_menu')): 
    function jobster_setup_menu() {
        check_ajax_referer('theme_setup_ajax_nonce', 'security');

        $demo_menus = array(
            'primary' => __('Top Menu', 'jobster')
        );

        $locations  = get_theme_mod('nav_menu_locations');
        $ep_menus   = wp_get_nav_menus();

        $menu_conf  = $demo_menus;

        if (!empty($ep_menus) && !empty($menu_conf)) {
            foreach ($ep_menus as $ep_menu) {
                if (is_object($ep_menu) && in_array($ep_menu->name, $menu_conf)) {
                    $key = array_search($ep_menu->name, $menu_conf);

                    if ($key !== false) {
                        $locations[$key] = $ep_menu->term_id;
                    }
                }
            }
        }

        set_theme_mod('nav_menu_locations', $locations);

        echo json_encode(array('setup' => true));
        exit();

        die;
    }
endif;
add_action('wp_ajax_nopriv_jobster_setup_menu', 'jobster_setup_menu');
add_action('wp_ajax_jobster_setup_menu', 'jobster_setup_menu');

if (!function_exists('jobster_setup_widgets')): 
    function jobster_setup_widgets() {
        check_ajax_referer('theme_setup_ajax_nonce', 'security');

        $demo_version = isset($_POST['demo'])
                        ? sanitize_text_field($_POST['demo'])
                        : '';

        // Export widgets using this plugin http://wordpress.org/plugins/widget-settings-importexport/

        switch ($demo_version) {
            case 'demo-1':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":1},"recent-posts":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":118},"3":{"title":"For Employers","nav_menu":119},"4":{"title":"Other Demos","nav_menu":120},"5":{"title":"About Us","nav_menu":121},"_multiwidget":1}}]';
                break;
            case 'demo-2':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":null},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":118},"3":{"title":"For Employers","nav_menu":119},"4":{"title":"Other Demos","nav_menu":120},"5":{"title":"About Us","nav_menu":121},"_multiwidget":1}}]';
                break;
            case 'demo-3':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"2":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":118},"3":{"title":"For Employers","nav_menu":119},"4":{"title":"Other Demos","nav_menu":120},"5":{"title":"About Us","nav_menu":121},"_multiwidget":1}}]';
                break;
            case 'demo-4':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"2":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":118},"3":{"title":"For Employers","nav_menu":119},"4":{"title":"Other Demos","nav_menu":120},"5":{"title":"About Us","nav_menu":121},"_multiwidget":1}}]';
                break;
            case 'demo-5':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"2":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":115},"3":{"title":"For Employers","nav_menu":116},"4":{"title":"Other Demos","nav_menu":117},"5":{"title":"About Us","nav_menu":118},"_multiwidget":1}}]';
                break;
            case 'demo-6':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"2":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":118},"3":{"title":"For Employers","nav_menu":119},"4":{"title":"Other Demos","nav_menu":120},"5":{"title":"About Us","nav_menu":121},"_multiwidget":1}}]';
                break;
            case 'demo-7':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"2":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":118},"3":{"title":"For Employers","nav_menu":119},"4":{"title":"Other Demos","nav_menu":120},"5":{"title":"About Us","nav_menu":121},"_multiwidget":1}}]';
                break;
            case 'demo-8':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":118},"3":{"title":"For Employers","nav_menu":119},"4":{"title":"Other Demos","nav_menu":120},"5":{"title":"About Us","nav_menu":121},"_multiwidget":1}}]';
                break;
            case 'demo-9':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":null},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":118},"3":{"title":"For Employers","nav_menu":119},"4":{"title":"Other Demos","nav_menu":120},"5":{"title":"About Us","nav_menu":121},"_multiwidget":1}}]';
                break;
            case 'demo-10':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"2":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":118},"3":{"title":"For Employers","nav_menu":119},"4":{"title":"Other Demos","nav_menu":120},"5":{"title":"About Us","nav_menu":121},"_multiwidget":1}}]';
                break;
            case 'demo-11':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"2":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":118},"3":{"title":"For Employers","nav_menu":119},"4":{"title":"Other Demos","nav_menu":120},"5":{"title":"About Us","nav_menu":121},"_multiwidget":1}}]';
                break;
            case 'demo-12':
                $demo_widgets = '[{"pxp-main-widget-area":["search-2","categories-2","recent-posts-2","tag_cloud-2"],"pxp-first-footer-widget-area":["custom_html-2","custom_html-3","custom_html-4"],"pxp-second-footer-widget-area":["nav_menu-2"],"pxp-third-footer-widget-area":["nav_menu-3"],"pxp-fourth-footer-widget-area":["nav_menu-4"],"pxp-fifth-footer-widget-area":["nav_menu-5"]},{"search":{"2":{"title":"Search Articles"},"_multiwidget":1},"categories":{"2":{"2":{"2":{"2":{"title":"","count":0,"hierarchical":0,"dropdown":0},"_multiwidget":null},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"recent-posts":{"2":{"2":{"2":{"title":"","number":5,"show_date":true},"_multiwidget":null},"_multiwidget":null},"_multiwidget":1},"tag_cloud":{"2":{"title":"Tags","count":0,"taxonomy":"post_tag"},"_multiwidget":1},"custom_html":{"2":{"title":"","content":"<img class=\"alignnone wp-image-17\" src=\"https:\/\/pixelprime.co\/themes\/jobster-wp\/demo-1\/wp-content\/uploads\/2022\/06\/jobster-logo.png\" alt=\"\" width=\"105\" height=\"43\" \/>"},"3":{"title":"Call us","content":"<div style=\"font-size: 22px;color: var(--pxpMainColor);font-weight: 500;\">(123) 456-7890<\/div>"},"4":{"title":"","content":"90 Fifth Avenue, 3rd Floor<br>San Francisco, CA 1980<br>office@jobster.com"},"_multiwidget":1},"nav_menu":{"2":{"title":"For Candidates","nav_menu":115},"3":{"title":"For Employers","nav_menu":116},"4":{"title":"Other Demos","nav_menu":117},"5":{"title":"About Us","nav_menu":118},"_multiwidget":1}}]';
                break;
            default:
                $demo_widgets = '';
                break;
        }

        $sidebars_widgets = wp_get_sidebars_widgets();

        foreach ($sidebars_widgets as $sidebar_id => $widgets) {
            if ($sidebar_id != 'wp_inactive_widgets') {
                $sidebars_widgets[$sidebar_id] = array();
            }
        }

        wp_set_sidebars_widgets($sidebars_widgets);

        $json_data    = json_decode($demo_widgets, true);
        $sidebar_data = $json_data[0];
        $widget_data  = $json_data[1];

        foreach ($sidebar_data as $title => $sidebar) {
            $count = count($sidebar);

            for ($i = 0; $i < $count; $i++) {
                $widget               = array();
                $widget['type']       = trim(substr($sidebar[$i], 0, strrpos($sidebar[$i], '-')));
                $widget['type-index'] = trim(substr($sidebar[$i], strrpos($sidebar[$i], '-') + 1));

                if (!isset($widget_data[$widget['type']][$widget['type-index']])) {
                    unset($sidebar_data[$title][$i]);
                }
            }

            $sidebar_data[$title] = array_values($sidebar_data[$title]);
        }

        $sidebar_data = array(array_filter($sidebar_data), $widget_data);

        jobster_import_widgets_parse_data($sidebar_data);

        echo json_encode(array('setup' => true));
        exit();

        die;
    }
endif;
add_action('wp_ajax_nopriv_jobster_setup_widgets', 'jobster_setup_widgets');
add_action('wp_ajax_jobster_setup_widgets', 'jobster_setup_widgets');

if (!function_exists('jobster_import_widgets_parse_data')): 
    function jobster_import_widgets_parse_data($import_array) {
        $sidebars_data = $import_array[0];
        $widget_data   = $import_array[1];

        $current_sidebars = get_option('sidebars_widgets');
        $new_widgets      = array();

        foreach ($sidebars_data as $import_sidebar => $import_widgets) :
            $current_sidebars[$import_sidebar] = array();

            foreach ($import_widgets as $import_widget) :
                $title               = trim(substr($import_widget, 0, strrpos($import_widget, '-')));
                $index               = trim(substr($import_widget, strrpos($import_widget, '-') + 1));
                $current_widget_data = get_option('widget_' . $title);
                $new_widget_name     = jobster_get_new_widget_name($title, $index);
                $new_index           = trim(substr($new_widget_name, strrpos($new_widget_name, '-') + 1));

                if (!empty($new_widgets[$title]) && is_array($new_widgets[$title])) {
                    while (array_key_exists($new_index, $new_widgets[$title])) {
                        $new_index++;
                    }
                }

                $current_sidebars[$import_sidebar][] = $title . '-' . $new_index;

                if (array_key_exists($title, $new_widgets)) {
                    $new_widgets[$title][$new_index] = $widget_data[$title][$index];

                    if (!empty($new_widgets[$title]['_multiwidget'])) {
                        $multiwidget = $new_widgets[$title]['_multiwidget'];
                        unset($new_widgets[$title]['_multiwidget']);
                        $new_widgets[$title]['_multiwidget'] = $multiwidget;
                    } else {
                        $new_widgets[$title]['_multiwidget'] = null;
                    }
                } else {
                    $current_widget_data[$new_index] = $widget_data[$title][$index];

                    if (!empty($current_widget_data['_multiwidget'])) {
                        $current_multiwidget = $current_widget_data['_multiwidget'];
                        $new_multiwidget     = $widget_data[$title]['_multiwidget'];
                        $multiwidget         = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
                        unset($current_widget_data['_multiwidget']);
                        $current_widget_data['_multiwidget'] = $multiwidget;
                    } else {
                        $current_widget_data['_multiwidget'] = null;
                    }

                    $new_widgets[$title] = $current_widget_data;
                }
            endforeach;
        endforeach;

        if (isset($new_widgets) && isset($current_sidebars)) {
            update_option('sidebars_widgets', $current_sidebars);

            foreach ($new_widgets as $title => $content) {
                update_option('widget_' . $title, $content);
            }
        }
    }
endif;

if (!function_exists('jobster_get_new_widget_name')): 
    function jobster_get_new_widget_name($widget_name, $widget_index) {
        $current_sidebars = get_option('sidebars_widgets');
        $all_widget_array = array();

        foreach ($current_sidebars as $sidebar => $widgets) {
            if (!empty($widgets) && is_array($widgets) && $sidebar != 'wp_inactive_widgets') {
                foreach ($widgets as $widget) {
                    $all_widget_array[] = $widget;
                }
            }
        }

        while (in_array($widget_name . '-' . $widget_index, $all_widget_array)) {
            $widget_index ++;
        }

        $new_widget_name = $widget_name . '-' . $widget_index;

        return $new_widget_name;
    }
endif;

if (!function_exists('jobster_setup_options')): 
    function jobster_setup_options() {
        check_ajax_referer('theme_setup_ajax_nonce', 'security');

        $demo_version = isset($_POST['demo'])
                        ? sanitize_text_field($_POST['demo'])
                        : '';

        // Export theme settings using this plugin https://wordpress.org/plugins/options-importer/ and copy only the theme settings array from the json file

        switch ($demo_version) {
            case 'demo-1':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:5:\"cards\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:4:\"wide\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:4:\"wide\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:4:\"wide\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:1;s:6:\"Senior\";i:1;s:11:\"Team leader\";i:1;s:6:\"Writer\";i:1;s:8:\"Engineer\";i:1;s:6:\"Expert\";i:1;s:13:\"Web developer\";i:1;s:6:\"Editor\";i:1;s:9:\"Fullstack\";i:1;}"}';
                break;
            case 'demo-2':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:4:\"list\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:4:\"side\";}","jobster_colors_settings": "a:7:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#691f74\";s:35:\"jobster_main_tranparent_color_field\";s:21:\"rgba(105,31,116,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#44195d\";s:30:\"jobster_main_color_light_field\";s:7:\"#f0e9f1\";s:29:\"jobster_secondary_color_field\";s:7:\"#e34b32\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#fff0eb\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:4:\"side\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:4:\"side\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:1;s:6:\"Senior\";i:1;s:11:\"Team leader\";i:1;s:6:\"Writer\";i:1;s:8:\"Engineer\";i:1;s:6:\"Expert\";i:1;s:13:\"Web developer\";i:1;s:6:\"Editor\";i:1;s:9:\"Fullstack\";i:1;}"}';
                break;
            case 'demo-3':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:5:\"boxed\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:6:\"center\";}","jobster_colors_settings": "a:7:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#55bc7e\";s:35:\"jobster_main_tranparent_color_field\";s:21:\"rgba(85,188,126,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#1b8756\";s:30:\"jobster_main_color_light_field\";s:7:\"#e6f6ec\";s:29:\"jobster_secondary_color_field\";s:7:\"#ffb401\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#fedb8e\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:6:\"center\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:6:\"center\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:2;s:6:\"Senior\";i:2;s:11:\"Team leader\";i:2;s:6:\"Writer\";i:2;s:8:\"Engineer\";i:2;s:6:\"Expert\";i:2;s:13:\"Web developer\";i:2;s:6:\"Editor\";i:2;s:9:\"Fullstack\";i:2;}"}';
                break;
            case 'demo-4':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:5:\"cards\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:4:\"wide\";}","jobster_colors_settings": "a:7:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#21c0e0\";s:35:\"jobster_main_tranparent_color_field\";s:21:\"rgba(33,192,224,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#48a7ba\";s:30:\"jobster_main_color_light_field\";s:7:\"#dcf2f2\";s:29:\"jobster_secondary_color_field\";s:7:\"#ff9bd0\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#ffe2f1\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:4:\"wide\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:4:\"wide\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:1;s:6:\"Senior\";i:1;s:11:\"Team leader\";i:1;s:6:\"Writer\";i:1;s:8:\"Engineer\";i:1;s:6:\"Expert\";i:1;s:13:\"Web developer\";i:1;s:6:\"Editor\";i:1;s:9:\"Fullstack\";i:1;}"}';
                break;
            case 'demo-5':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:4:\"list\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:4:\"side\";}","jobster_colors_settings": "a:7:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#ffbd41\";s:35:\"jobster_main_tranparent_color_field\";s:21:\"rgba(255,189,65,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#dba936\";s:30:\"jobster_main_color_light_field\";s:7:\"#fdf4e4\";s:29:\"jobster_secondary_color_field\";s:7:\"#efe4a7\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#efeee8\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:4:\"side\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:4:\"side\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:1;s:6:\"Senior\";i:1;s:11:\"Team leader\";i:1;s:6:\"Writer\";i:1;s:8:\"Engineer\";i:1;s:6:\"Expert\";i:1;s:13:\"Web developer\";i:1;s:6:\"Editor\";i:1;s:9:\"Fullstack\";i:1;}"}';
                break;
            case 'demo-6':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:5:\"boxed\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:6:\"center\";}","jobster_colors_settings": "a:7:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#ef3e23\";s:35:\"jobster_main_tranparent_color_field\";s:20:\"rgba(239,62,35,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#dc2a0e\";s:30:\"jobster_main_color_light_field\";s:7:\"#ffdad6\";s:29:\"jobster_secondary_color_field\";s:7:\"#eb7efe\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#fbe8ff\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:6:\"center\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:6:\"center\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:1;s:6:\"Senior\";i:1;s:11:\"Team leader\";i:1;s:6:\"Writer\";i:1;s:8:\"Engineer\";i:1;s:6:\"Expert\";i:1;s:13:\"Web developer\";i:1;s:6:\"Editor\";i:1;s:9:\"Fullstack\";i:1;}"}';
                break;
            case 'demo-7':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:5:\"cards\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:4:\"wide\";}","jobster_colors_settings": "a:7:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#262e5d\";s:35:\"jobster_main_tranparent_color_field\";s:19:\"rgba(38,46,93,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#0a0549\";s:30:\"jobster_main_color_light_field\";s:7:\"#e9f8fe\";s:29:\"jobster_secondary_color_field\";s:7:\"#eec0a6\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#ffe4d4\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:4:\"wide\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:4:\"wide\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:1;s:6:\"Senior\";i:1;s:11:\"Team leader\";i:1;s:6:\"Writer\";i:1;s:8:\"Engineer\";i:1;s:6:\"Expert\";i:1;s:13:\"Web developer\";i:1;s:6:\"Editor\";i:1;s:9:\"Fullstack\";i:1;}"}';
                break;
            case 'demo-8':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:5:\"cards\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:4:\"wide\";}","jobster_colors_settings": "a:9:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#f06f35\";s:35:\"jobster_main_tranparent_color_field\";s:21:\"rgba(240,111,53,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#c95b29\";s:30:\"jobster_main_color_light_field\";s:7:\"#ede5d8\";s:29:\"jobster_secondary_color_field\";s:7:\"#cbbefb\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#e9ebfa\";s:31:\"jobster_feat_job_label_bg_field\";s:7:\"#f06f35\";s:33:\"jobster_feat_job_label_text_field\";s:7:\"#ffffff\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:4:\"wide\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:4:\"wide\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:1;s:6:\"Senior\";i:1;s:11:\"Team leader\";i:1;s:6:\"Writer\";i:1;s:8:\"Engineer\";i:1;s:6:\"Expert\";i:1;s:13:\"Web developer\";i:1;s:6:\"Editor\";i:1;s:9:\"Fullstack\";i:1;}"}';
                break;
            case 'demo-9':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:4:\"list\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:4:\"side\";}","jobster_colors_settings": "a:9:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#fcb237\";s:35:\"jobster_main_tranparent_color_field\";s:21:\"rgba(252,178,55,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#e29931\";s:30:\"jobster_main_color_light_field\";s:7:\"#f7daab\";s:29:\"jobster_secondary_color_field\";s:7:\"#afc081\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#ced9b3\";s:31:\"jobster_feat_job_label_bg_field\";s:7:\"#c66735\";s:33:\"jobster_feat_job_label_text_field\";s:7:\"#ffffff\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:4:\"side\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:4:\"side\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:1;s:6:\"Senior\";i:1;s:11:\"Team leader\";i:1;s:6:\"Writer\";i:1;s:8:\"Engineer\";i:1;s:6:\"Expert\";i:1;s:13:\"Web developer\";i:1;s:6:\"Editor\";i:1;s:9:\"Fullstack\";i:1;}"}';
                break;
            case 'demo-10':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:5:\"boxed\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:6:\"center\";}","jobster_colors_settings": "a:9:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#47c5d1\";s:35:\"jobster_main_tranparent_color_field\";s:21:\"rgba(71,197,209,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#2c4d78\";s:30:\"jobster_main_color_light_field\";s:7:\"#cff3f7\";s:29:\"jobster_secondary_color_field\";s:7:\"#afcd81\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#ddf0bf\";s:31:\"jobster_feat_job_label_bg_field\";s:7:\"#9fc500\";s:33:\"jobster_feat_job_label_text_field\";s:7:\"#ffffff\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:6:\"center\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:6:\"center\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:11:{s:14:\"Administration\";i:2;s:6:\"Senior\";i:2;s:11:\"Team leader\";i:3;s:6:\"Writer\";i:2;s:8:\"Engineer\";i:2;s:6:\"Expert\";i:3;s:13:\"Web developer\";i:2;s:6:\"Editor\";i:2;s:9:\"Fullstack\";i:2;s:8:\"business\";i:2;s:28:\"Business Development Manager\";i:1;}"}';
                break;
            case 'demo-11':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:5:\"cards\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:4:\"wide\";}","jobster_colors_settings": "a:9:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#00c59a\";s:35:\"jobster_main_tranparent_color_field\";s:20:\"rgba(0,197,154,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#1c2830\";s:30:\"jobster_main_color_light_field\";s:7:\"#baf8ea\";s:29:\"jobster_secondary_color_field\";s:7:\"#d9e2e4\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#ecf1f2\";s:31:\"jobster_feat_job_label_bg_field\";s:7:\"#00c798\";s:33:\"jobster_feat_job_label_text_field\";s:7:\"#ffffff\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:4:\"wide\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:4:\"wide\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:1;s:6:\"Senior\";i:1;s:11:\"Team leader\";i:1;s:6:\"Writer\";i:1;s:8:\"Engineer\";i:1;s:6:\"Expert\";i:1;s:13:\"Web developer\";i:1;s:6:\"Editor\";i:1;s:9:\"Fullstack\";i:1;}"}';
                break;
            case 'demo-12':
                $theme_settings = '{"jobster_blog_settings": "a:6:{s:24:\"jobster_blog_title_field\";s:17:\"Top Career Advice\";s:27:\"jobster_blog_subtitle_field\";s:32:\"Browse the latest career advices\";s:23:\"jobster_blog_list_field\";s:4:\"list\";s:32:\"jobster_blog_related_posts_field\";s:1:\"1\";s:38:\"jobster_blog_related_posts_title_field\";s:16:\"Related Articles\";s:41:\"jobster_blog_related_posts_subtitle_field\";s:32:\"Browse the latest career advices\";}","jobster_candidates_settings": "a:2:{s:33:\"jobster_candidates_per_page_field\";s:1:\"8\";s:35:\"jobster_candidate_page_layout_field\";s:4:\"side\";}","jobster_colors_settings": "a:9:{s:24:\"jobster_text_color_field\";s:0:\"\";s:24:\"jobster_main_color_field\";s:7:\"#ff6a13\";s:35:\"jobster_main_tranparent_color_field\";s:21:\"rgba(255,106,19,0.05)\";s:29:\"jobster_main_color_dark_field\";s:7:\"#cb5501\";s:30:\"jobster_main_color_light_field\";s:7:\"#fff4ef\";s:29:\"jobster_secondary_color_field\";s:7:\"#e14784\";s:35:\"jobster_secondary_color_light_field\";s:7:\"#fceff4\";s:31:\"jobster_feat_job_label_bg_field\";s:7:\"#ff6a00\";s:33:\"jobster_feat_job_label_text_field\";s:7:\"#ffffff\";}","jobster_companies_settings": "a:2:{s:32:\"jobster_companies_per_page_field\";s:1:\"8\";s:33:\"jobster_company_page_layout_field\";s:4:\"side\";}","jobster_footer_settings": "a:5:{s:23:\"jobster_copyright_field\";s:36:\"\u00a9 2022 Jobster. All Right Reserved.\";s:22:\"jobster_facebook_field\";s:1:\"#\";s:21:\"jobster_twitter_field\";s:1:\"#\";s:23:\"jobster_instagram_field\";s:1:\"#\";s:22:\"jobster_linkedin_field\";s:1:\"#\";}","jobster_jobs_settings": "a:5:{s:27:\"jobster_jobs_per_page_field\";s:1:\"9\";s:29:\"jobster_job_page_layout_field\";s:4:\"side\";s:30:\"jobster_job_page_similar_field\";s:1:\"1\";s:36:\"jobster_job_page_similar_title_field\";s:12:\"Related Jobs\";s:39:\"jobster_job_page_similar_subtitle_field\";s:42:\"Other similar jobs that might interest you\";}","jobster_popular_searches": "a:9:{s:14:\"Administration\";i:1;s:6:\"Senior\";i:1;s:11:\"Team leader\";i:1;s:6:\"Writer\";i:1;s:8:\"Engineer\";i:1;s:6:\"Expert\";i:1;s:13:\"Web developer\";i:1;s:6:\"Editor\";i:1;s:9:\"Fullstack\";i:1;}"}';
                break;
            default:
                $theme_settings = '';
                break;
        }

        $imported_settings = json_decode(wp_specialchars_decode($theme_settings), true);

        foreach ($imported_settings as $key => $data) {
            update_option($key, maybe_unserialize($data));
        }

        echo json_encode(array('setup' => true));
        exit();

        die;
    }
endif;
add_action('wp_ajax_nopriv_jobster_setup_options', 'jobster_setup_options');
add_action('wp_ajax_jobster_setup_options', 'jobster_setup_options');
?>