<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_authentication')): 
    function jobster_admin_authentication() {
        add_settings_section(
            'jobster_authentication_section', 
            __('Authentication', 'jobster'), 
            'jobster_authentication_section_callback', 
            'jobster_authentication_settings'
        );
        add_settings_field(
            'jobster_disable_auth_field', 
            __('Disable User Authentication', 'jobster'), 
            'jobster_disable_auth_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_terms_field', 
            __('Terms and Conditions Page', 'jobster'), 
            'jobster_terms_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_signin_redirect_candidate_field', 
            __('Candidate After Sign In Redirect Page', 'jobster'), 
            'jobster_signin_redirect_candidate_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_signin_redirect_company_field', 
            __('Company After Sign In Redirect Page', 'jobster'), 
            'jobster_signin_redirect_company_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_disable_candidate_field', 
            __('Disable Candidate Registration', 'jobster'), 
            'jobster_disable_candidate_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_disable_company_field', 
            __('Disable Company Registration', 'jobster'), 
            'jobster_disable_company_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_candidate_reg_approval_field', 
            __('Enable Candidate Registration Approval Process', 'jobster'), 
            'jobster_candidate_reg_approval_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_company_reg_approval_field', 
            __('Enable Company Registration Approval Process', 'jobster'), 
            'jobster_company_reg_approval_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_signup_email_verification_field', 
            __('Enable Email Verification on User Registration Process', 'jobster'), 
            'jobster_signup_email_verification_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_signin_img_field', 
            __('Sign In Image', 'jobster'), 
            'jobster_signin_img_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_signup_img_field', 
            __('Sign Up Image', 'jobster'), 
            'jobster_signup_img_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_forgot_pass_img_field', 
            __('Forgot Password Image', 'jobster'), 
            'jobster_forgot_pass_img_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_account_type_img_field', 
            __('Account Type Modal Image', 'jobster'), 
            'jobster_account_type_img_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_google_auth_field', 
            __('Enable Google Authentication', 'jobster'), 
            'jobster_google_auth_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_google_auth_client_id_field', 
            __('Google Client ID', 'jobster'), 
            'jobster_google_auth_client_id_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_google_auth_client_secret_field', 
            __('Google Client Secret', 'jobster'), 
            'jobster_google_auth_client_secret_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_fb_auth_field', 
            __('Enable Facebook Authentication', 'jobster'), 
            'jobster_fb_auth_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_fb_auth_app_id_field', 
            __('Facebook App ID', 'jobster'), 
            'jobster_fb_auth_app_id_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
        add_settings_field(
            'jobster_fb_auth_app_secret_field', 
            __('Facebook App Secret', 'jobster'), 
            'jobster_fb_auth_app_secret_field_render', 
            'jobster_authentication_settings', 
            'jobster_authentication_section'
        );
    }
endif;

if (!function_exists('jobster_authentication_section_callback')): 
    function jobster_authentication_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('jobster_disable_auth_field_render')): 
    function jobster_disable_auth_field_render() { 
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_authentication_settings[jobster_disable_auth_field]" 
            <?php if (isset($options['jobster_disable_auth_field'])) { 
                checked($options['jobster_disable_auth_field'], 1);
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_terms_field_render')): 
    function jobster_terms_field_render() {
        $options = get_option('jobster_authentication_settings'); 

        $pages_list = '';
        $selected_page =    isset($options['jobster_terms_field'])
                            ? $options['jobster_terms_field']
                            : '';

        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );

        $page_selection = new WP_Query($args);
        $page_selection_arr  = get_object_vars($page_selection);

        if (is_array($page_selection_arr['posts']) 
            && count($page_selection_arr['posts']) > 0
        ) {
            foreach ($page_selection_arr['posts'] as $page) {
                $pages_list .= '<option value="' . esc_attr($page->ID) . '"';
                if ($page->ID == $selected_page) {
                    $pages_list .= ' selected';
                }
                $pages_list .= '>' . $page->post_title . '</option>';
            }
        } ?>

        <select name="jobster_authentication_settings[jobster_terms_field]">
            <option value=""><?php esc_html_e('None', 'jobster'); ?></option>

            <?php if (is_array($page_selection_arr['posts']) 
                && count($page_selection_arr['posts']) > 0
            ) {
                foreach ($page_selection_arr['posts'] as $page) { ?>
                    <option 
                        value="<?php echo esc_attr($page->ID); ?>" 
                        <?php selected($page->ID, $selected_page); ?>
                    >
                        <?php echo esc_html($page->post_title); ?>
                    </option>
                <?php }
            } ?>
        </select>
    <?php }
endif;

if (!function_exists('jobster_signin_redirect_candidate_field_render')): 
    function jobster_signin_redirect_candidate_field_render() {
        $options = get_option('jobster_authentication_settings'); 

        $pages_list = '';
        $selected_page =    isset($options['jobster_signin_redirect_candidate_field'])
                            ? $options['jobster_signin_redirect_candidate_field']
                            : '';

        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );

        $page_selection = new WP_Query($args);
        $page_selection_arr  = get_object_vars($page_selection);

        if (is_array($page_selection_arr['posts']) 
            && count($page_selection_arr['posts']) > 0
        ) {
            foreach ($page_selection_arr['posts'] as $page) {
                $pages_list .= '<option value="' . esc_attr($page->ID) . '"';
                if ($page->ID == $selected_page) {
                    $pages_list .= ' selected';
                }
                $pages_list .= '>' . $page->post_title . '</option>';
            }
        } ?>

        <select name="jobster_authentication_settings[jobster_signin_redirect_candidate_field]">
            <option value="default">
                <?php esc_html_e('Default (Current Page)', 'jobster'); ?>
            </option>

            <?php if (is_array($page_selection_arr['posts']) 
                && count($page_selection_arr['posts']) > 0
            ) {
                foreach ($page_selection_arr['posts'] as $page) { ?>
                    <option 
                        value="<?php echo esc_attr($page->ID); ?>" 
                        <?php selected($page->ID, $selected_page); ?>
                    >
                        <?php echo esc_html($page->post_title); ?>
                    </option>
                <?php }
            } ?>
        </select>
    <?php }
endif;

if (!function_exists('jobster_signin_redirect_company_field_render')): 
    function jobster_signin_redirect_company_field_render() {
        $options = get_option('jobster_authentication_settings'); 

        $pages_list = '';
        $selected_page =    isset($options['jobster_signin_redirect_company_field'])
                            ? $options['jobster_signin_redirect_company_field']
                            : '';

        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );

        $page_selection = new WP_Query($args);
        $page_selection_arr  = get_object_vars($page_selection);

        if (is_array($page_selection_arr['posts']) 
            && count($page_selection_arr['posts']) > 0
        ) {
            foreach ($page_selection_arr['posts'] as $page) {
                $pages_list .= '<option value="' . esc_attr($page->ID) . '"';
                if ($page->ID == $selected_page) {
                    $pages_list .= ' selected';
                }
                $pages_list .= '>' . $page->post_title . '</option>';
            }
        } ?>

        <select name="jobster_authentication_settings[jobster_signin_redirect_company_field]">
            <option value="default">
                <?php esc_html_e('Default (Current Page)', 'jobster'); ?>
            </option>

            <?php if (is_array($page_selection_arr['posts']) 
                && count($page_selection_arr['posts']) > 0
            ) {
                foreach ($page_selection_arr['posts'] as $page) { ?>
                    <option 
                        value="<?php echo esc_attr($page->ID); ?>" 
                        <?php selected($page->ID, $selected_page); ?>
                    >
                        <?php echo esc_html($page->post_title); ?>
                    </option>
                <?php }
            } ?>
        </select>
    <?php }
endif;

if (!function_exists('jobster_disable_candidate_field_render')): 
    function jobster_disable_candidate_field_render() { 
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_authentication_settings[jobster_disable_candidate_field]" 
            <?php if (isset($options['jobster_disable_candidate_field'])) { 
                checked($options['jobster_disable_candidate_field'], 1);
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_disable_company_field_render')): 
    function jobster_disable_company_field_render() { 
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_authentication_settings[jobster_disable_company_field]" 
            <?php if (isset($options['jobster_disable_company_field'])) { 
                checked($options['jobster_disable_company_field'], 1); 
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_candidate_reg_approval_field_render')): 
    function jobster_candidate_reg_approval_field_render() { 
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_authentication_settings[jobster_candidate_reg_approval_field]" 
            <?php if (isset($options['jobster_candidate_reg_approval_field'])) { 
                checked($options['jobster_candidate_reg_approval_field'], 1);
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_company_reg_approval_field_render')): 
    function jobster_company_reg_approval_field_render() { 
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_authentication_settings[jobster_company_reg_approval_field]" 
            <?php if (isset($options['jobster_company_reg_approval_field'])) { 
                checked($options['jobster_company_reg_approval_field'], 1);
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_signup_email_verification_field_render')): 
    function jobster_signup_email_verification_field_render() { 
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_authentication_settings[jobster_signup_email_verification_field]" 
            <?php if (isset($options['jobster_signup_email_verification_field'])) { 
                checked($options['jobster_signup_email_verification_field'], 1);
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_signin_img_field_render')): 
    function jobster_signin_img_field_render() { 
        $options = get_option('jobster_authentication_settings');

        $img_src = JOBSTER_PLUGIN_PATH . 'post-types/images/logo-placeholder.png';
        $img_val =  isset($options['jobster_signin_img_field'])
                    ? $options['jobster_signin_img_field']
                    : '';
        $img = wp_get_attachment_image_src($img_val, 'pxp-icon');
        $has_image = '';

        if (is_array($img)) {
            $has_image = 'pxp-has-image';
            $img_src = $img[0];
        } ?>

        <div class="pxp-signin-image-settings">
            <input 
                name="jobster_authentication_settings[jobster_signin_img_field]"
                id="jobster_authentication_settings[jobster_signin_img_field]" 
                class="pxp-signin-image-field"
                type="hidden" 
                value="<?php echo esc_attr($img_val); ?>"
            >
            <div class="pxp-image-placeholder-container <?php echo esc_attr($has_image); ?>">
                <div 
                    class="pxp-image-placeholder" 
                    style="background-image: url(<?php echo esc_url($img_src); ?>);"
                ></div>
                <div class="pxp-delete-image">
                    <span class="fa fa-trash-o"></span>
                </div>
            </div>
        </div>
    <?php }
endif;

if (!function_exists('jobster_signup_img_field_render')): 
    function jobster_signup_img_field_render() { 
        $options = get_option('jobster_authentication_settings');

        $img_src = JOBSTER_PLUGIN_PATH . 'post-types/images/logo-placeholder.png';
        $img_val =  isset($options['jobster_signup_img_field'])
                    ? $options['jobster_signup_img_field']
                    : '';
        $img = wp_get_attachment_image_src($img_val, 'pxp-icon');
        $has_image = '';

        if (is_array($img)) {
            $has_image = 'pxp-has-image';
            $img_src = $img[0];
        } ?>

        <div class="pxp-signup-image-settings">
            <input 
                name="jobster_authentication_settings[jobster_signup_img_field]"
                id="jobster_authentication_settings[jobster_signup_img_field]" 
                class="pxp-signup-image-field"
                type="hidden" 
                value="<?php echo esc_attr($img_val); ?>"
            >
            <div class="pxp-image-placeholder-container <?php echo esc_attr($has_image); ?>">
                <div 
                    class="pxp-image-placeholder" 
                    style="background-image: url(<?php echo esc_url($img_src); ?>);"
                ></div>
                <div class="pxp-delete-image">
                    <span class="fa fa-trash-o"></span>
                </div>
            </div>
        </div>
    <?php }
endif;

if (!function_exists('jobster_forgot_pass_img_field_render')): 
    function jobster_forgot_pass_img_field_render() { 
        $options = get_option('jobster_authentication_settings');

        $img_src = JOBSTER_PLUGIN_PATH . 'post-types/images/logo-placeholder.png';
        $img_val =  isset($options['jobster_forgot_pass_img_field'])
                    ? $options['jobster_forgot_pass_img_field']
                    : '';
        $img = wp_get_attachment_image_src($img_val, 'pxp-icon');
        $has_image = '';

        if (is_array($img)) {
            $has_image = 'pxp-has-image';
            $img_src = $img[0];
        } ?>

        <div class="pxp-forgot-pass-image-settings">
            <input 
                name="jobster_authentication_settings[jobster_forgot_pass_img_field]"
                id="jobster_authentication_settings[jobster_forgot_pass_img_field]" 
                class="pxp-forgot-pass-image-field"
                type="hidden" 
                value="<?php echo esc_attr($img_val); ?>"
            >
            <div class="pxp-image-placeholder-container <?php echo esc_attr($has_image); ?>">
                <div 
                    class="pxp-image-placeholder" 
                    style="background-image: url(<?php echo esc_url($img_src); ?>);"
                ></div>
                <div class="pxp-delete-image">
                    <span class="fa fa-trash-o"></span>
                </div>
            </div>
        </div>
    <?php }
endif;

if (!function_exists('jobster_account_type_img_field_render')): 
    function jobster_account_type_img_field_render() { 
        $options = get_option('jobster_authentication_settings');

        $img_src = JOBSTER_PLUGIN_PATH . 'post-types/images/logo-placeholder.png';
        $img_val =  isset($options['jobster_account_type_img_field'])
                    ? $options['jobster_account_type_img_field']
                    : '';
        $img = wp_get_attachment_image_src($img_val, 'pxp-icon');
        $has_image = '';

        if (is_array($img)) {
            $has_image = 'pxp-has-image';
            $img_src = $img[0];
        } ?>

        <div class="pxp-account-type-image-settings">
            <input 
                name="jobster_authentication_settings[jobster_account_type_img_field]"
                id="jobster_authentication_settings[jobster_account_type_img_field]" 
                class="pxp-account-type-image-field"
                type="hidden" 
                value="<?php echo esc_attr($img_val); ?>"
            >
            <div class="pxp-image-placeholder-container <?php echo esc_attr($has_image); ?>">
                <div 
                    class="pxp-image-placeholder" 
                    style="background-image: url(<?php echo esc_url($img_src); ?>);"
                ></div>
                <div class="pxp-delete-image">
                    <span class="fa fa-trash-o"></span>
                </div>
            </div>
        </div>
    <?php }
endif;

if (!function_exists('jobster_google_auth_field_render')): 
    function jobster_google_auth_field_render() { 
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_authentication_settings[jobster_google_auth_field]" 
            <?php if (isset($options['jobster_google_auth_field'])) {
                checked($options['jobster_google_auth_field'], 1);
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_google_auth_client_id_field_render')): 
    function jobster_google_auth_client_id_field_render() {
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            name="jobster_authentication_settings[jobster_google_auth_client_id_field]" 
            id="jobster_authentication_settings[jobster_google_auth_client_id_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_google_auth_client_id_field'])) {
                echo esc_attr($options['jobster_google_auth_client_id_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_google_auth_client_secret_field_render')): 
    function jobster_google_auth_client_secret_field_render() {
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            name="jobster_authentication_settings[jobster_google_auth_client_secret_field]" 
            id="jobster_authentication_settings[jobster_google_auth_client_secret_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_google_auth_client_secret_field'])) {
                echo esc_attr($options['jobster_google_auth_client_secret_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_fb_auth_field_render')): 
    function jobster_fb_auth_field_render() { 
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            type="checkbox" 
            name="jobster_authentication_settings[jobster_fb_auth_field]" 
            <?php if (isset($options['jobster_fb_auth_field'])) {
                checked($options['jobster_fb_auth_field'], 1);
            } ?> 
            value="1"
        >
    <?php }
endif;

if (!function_exists('jobster_fb_auth_app_id_field_render')): 
    function jobster_fb_auth_app_id_field_render() {
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            name="jobster_authentication_settings[jobster_fb_auth_app_id_field]" 
            id="jobster_authentication_settings[jobster_fb_auth_app_id_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_fb_auth_app_id_field'])) {
                echo esc_attr($options['jobster_fb_auth_app_id_field']);
            } ?>"
        >
    <?php }
endif;

if (!function_exists('jobster_fb_auth_app_secret_field_render')): 
    function jobster_fb_auth_app_secret_field_render() {
        $options = get_option('jobster_authentication_settings'); ?>

        <input 
            name="jobster_authentication_settings[jobster_fb_auth_app_secret_field]" 
            id="jobster_authentication_settings[jobster_fb_auth_app_secret_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_fb_auth_app_secret_field'])) {
                echo esc_attr($options['jobster_fb_auth_app_secret_field']);
            } ?>"
        >
    <?php }
endif;
?>