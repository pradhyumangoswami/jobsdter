<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Check if user is company
 */
if (!function_exists('jobster_user_is_company')): 
    function jobster_user_is_company($user_id) {
        $args = array(
            'post_type' => 'company',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'   => 'company_user',
                    'value' => $user_id,
                )
            )
        );

        $query = new WP_Query($args);

        wp_reset_postdata();

        if ($query->have_posts()) {
            wp_reset_query();

            return true;
        } else {
            return false;
        }
    }
endif;

/**
 * Check if user is candidate
 */
if (!function_exists('jobster_user_is_candidate')): 
    function jobster_user_is_candidate($user_id) {
        $args = array(
            'post_type' => 'candidate',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'   => 'candidate_user',
                    'value' => $user_id,
                )
            )
        );

        $query = new WP_Query($args);

        wp_reset_postdata();

        if ($query->have_posts()) {
            wp_reset_query();

            return true;
        } else {
            return false;
        }
    }
endif;

/**
 * Get company by user id
 */
if (!function_exists('jobster_get_company_by_userid')): 
    function jobster_get_company_by_userid($user_id) {
        $args = array(
            'post_type' => 'company',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'   => 'company_user',
                    'value' => $user_id,
                )
            )
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $company_id = get_the_ID();
            }

            wp_reset_postdata();
            wp_reset_query();

            return $company_id;
        } else {
            return false;
        }
    }
endif;

/**
 * Get candidate by user id
 */
if (!function_exists('jobster_get_candidate_by_userid')): 
    function jobster_get_candidate_by_userid($user_id) {
        $args = array(
            'post_type' => 'candidate',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'   => 'candidate_user',
                    'value' => $user_id,
                )
            )
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $candidate_id = get_the_ID();
            }

            wp_reset_postdata();
            wp_reset_query();

            return $candidate_id;
        } else {
            return false;
        }
    }
endif;

/**
 * User Sign In
 */
if (!function_exists('jobster_user_signin')): 
    function jobster_user_signin() {
        if (is_user_logged_in()) {
            echo json_encode(array('signedin' => true, 'message' => __('You are already signed in, redirecting...', 'jobster')));
            exit();
        }

        check_ajax_referer('signin_ajax_nonce', 'security');

        $signin_user = isset($_POST['signin_user']) ? sanitize_text_field($_POST['signin_user']) : '';
        $signin_pass = isset($_POST['signin_pass']) ? $_POST['signin_pass'] : '';

        if ($signin_user == '' || $signin_pass == '') {
            echo json_encode(array('signedin' => false, 'message' => __('Invalid username or password!', 'jobster')));
            exit();
        }

        $data = array();
        $data['user_login']    = $signin_user;
        $data['user_password'] = $signin_pass;

        $user_signon = wp_signon($data);

        if (is_wp_error($user_signon)) {
            echo json_encode(array('signedin' => false, 'message' => __('Invalid username or password!', 'jobster')));
            exit();
        } else {
            $auth_settings = get_option('jobster_authentication_settings', '');
            $redirect_page = 'default';

            if (jobster_user_is_company($user_signon->ID)) {
                $redirect_page = isset($auth_settings['jobster_signin_redirect_company_field'])
                                    && $auth_settings['jobster_signin_redirect_company_field'] != ''
                                ? $auth_settings['jobster_signin_redirect_company_field']
                                : 'default';
            }
            if (jobster_user_is_candidate($user_signon->ID)) {
                $redirect_page = isset($auth_settings['jobster_signin_redirect_candidate_field'])
                                    && $auth_settings['jobster_signin_redirect_candidate_field'] != ''
                                ? $auth_settings['jobster_signin_redirect_candidate_field']
                                : 'default';
            }

            echo json_encode(array(
                'signedin' => true,
                'newuser'  => $user_signon->ID,
                'redirect' =>   ($redirect_page != 'default') 
                                ? get_permalink($redirect_page)
                                :  $redirect_page,
                'message'  => __('Sign in successful, redirecting...', 'jobster'),
            ));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_user_signin', 'jobster_user_signin');
add_action('wp_ajax_jobster_user_signin', 'jobster_user_signin');

/**
 * Sign Up notifications
 */
if (!function_exists('jobster_signup_notifications')): 
    function jobster_signup_notifications($user, $user_pass = '') {
        $new_user = new WP_User($user);

        $user_login      = stripslashes($new_user->user_login);
        $user_email      = stripslashes($new_user->user_email);
        $user_first_name = stripslashes($new_user->first_name);

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option('blogname') . '<' . get_option('admin_email') . '>',
            'Reply-To: ' . get_option('blogname') . '<' . get_option('admin_email') . '>'
        );

        $email_settings = get_option('jobster_email_settings');
        $template_admin =   isset($email_settings['jobster_email_signup_notify_admin_field']) 
                            ? $email_settings['jobster_email_signup_notify_admin_field'] 
                            : '';

        if ($template_admin != '') {
            $template_admin = str_replace("{USER_NAME}", $user_login, $template_admin);
            $template_admin = str_replace("{USER_EMAIL}", $user_email, $template_admin);

            $send_admin = wp_mail(
                get_option('admin_email'),
                sprintf(__('[%s] New User Sign Up', 'jobster'), get_option('blogname') ),
                $template_admin,
                $headers
            );
        } else {
            $message_admin = sprintf( __('New user Sign Up on %s:', 'jobster'), get_option('blogname') ) . "\r\n\r\n";
            $message_admin .= sprintf( __('Username: %s', 'jobster'), esc_html($user_login) ) . "\r\n";
            $message_admin .= sprintf( __('E-mail: %s', 'jobster'), esc_html($user_email) );

            $send_admin = wp_mail(
                get_option('admin_email'),
                sprintf(__('[%s] New User Sign Up', 'jobster'), get_option('blogname') ),
                $message_admin,
                $headers
            );
        }

        if (empty($user_pass)) return;

        $template_user =    isset($email_settings['jobster_email_signup_notify_user_field']) 
                            ? $email_settings['jobster_email_signup_notify_user_field'] 
                            : '';

        if ($template_user != '') {
            $template_user = str_replace("{USER_FIRSTNAME}", $user_first_name, $template_user);
            $template_user = str_replace("{USER_NAME}", $user_login, $template_user);
            $template_user = str_replace("{USER_PASSWORD}", $user_pass, $template_user);

            $send_user = wp_mail(
                $user_email,
                sprintf( __('[%s] Your username and password', 'jobster'), get_option('blogname') ),
                $template_user,
                $headers
            );
        } else {
            $message_user = sprintf( __('Welcome, %s!', 'jobster'), esc_html($user_first_name) ) . "\r\n\r\n";
            $message_user .= __('Thank you for signing up with us. Your new account has been setup and you can now sign in using the details below.', 'jobster') . "\r\n\r\n";
            $message_user .= sprintf( __('Username: %s', 'jobster'), esc_html($user_login) ) . "\r\n";
            $message_user .= sprintf( __('Password: %s', 'jobster'), esc_html($user_pass) ) . "\r\n\r\n";
            $message_user .= __('Thank you,', 'jobster') . "\r\n";
            $message_user .= sprintf( __('%s Team', 'jobster'), get_option('blogname') );

            $send_user = wp_mail(
                $user_email,
                sprintf( __('[%s] Your username and password', 'jobster'), get_option('blogname') ),
                $message_user,
                $headers
            );
        }
    }
endif;

/**
 * User Sign Up
 */
if (!function_exists('jobster_user_signup')): 
    function jobster_user_signup() {
        check_ajax_referer('signin_ajax_nonce', 'security');

        $signup_firstname = isset($_POST['signup_firstname']) ? sanitize_text_field($_POST['signup_firstname']) : '';
        $signup_lastname  = isset($_POST['signup_lastname']) ? sanitize_text_field($_POST['signup_lastname']) : '';
        $signup_company   = isset($_POST['signup_company']) ? sanitize_text_field($_POST['signup_company']) : '';
        $signup_email     = isset($_POST['signup_email']) ? sanitize_email($_POST['signup_email']) : '';
        $signup_pass      = isset($_POST['signup_pass']) ? $_POST['signup_pass'] : '';
        $user_type        = isset($_POST['user_type']) ? sanitize_text_field($_POST['user_type']) : '';
        $terms            = isset($_POST['terms']) ? sanitize_text_field($_POST['terms']) : '';

        $auth_settings = get_option('jobster_authentication_settings');
        $terms_setting = isset($auth_settings['jobster_terms_field']) ? $auth_settings['jobster_terms_field'] : '';
        $candidate_approval =   isset($auth_settings['jobster_candidate_reg_approval_field']) 
                                && $auth_settings['jobster_candidate_reg_approval_field'] == '1';
        $company_approval = isset($auth_settings['jobster_company_reg_approval_field']) 
                            && $auth_settings['jobster_company_reg_approval_field'] == '1';
        $email_verification =   isset($auth_settings['jobster_signup_email_verification_field']) 
                                && $auth_settings['jobster_signup_email_verification_field'] == '1';

        if ($user_type == 'candidate') {
            if (empty($signup_firstname) || empty($signup_lastname) || empty($signup_pass)) {
                echo json_encode(array('signedup' => false, 'message' => __('Required form fields are empty!', 'jobster')));
                exit();
            }

            $user_data = array(
                'user_login' => $signup_email,
                'user_email' => $signup_email,
                'user_pass'  => $signup_pass,
                'first_name' => $signup_firstname,
                'last_name'  => $signup_lastname
            );
        }
        if ($user_type == 'company') {
            if (empty($signup_company) || empty($signup_pass)) {
                echo json_encode(array('signedup' => false, 'message' => __('Required form fields are empty!', 'jobster')));
                exit();
            }

            $user_data = array(
                'user_login' => $signup_email,
                'user_email' => $signup_email,
                'user_pass'  => $signup_pass,
            );
        }

        if (!is_email($signup_email)) {
            echo json_encode(array('signedup' => false, 'message' => __('Invalid Email!', 'jobster')));
            exit();
        }
        if (email_exists($signup_email)) {
            echo json_encode(array('signedup' => false, 'message' => __('Email already exists!', 'jobster')));
            exit();
        }
        if (6 > strlen($signup_pass)) {
            echo json_encode(array('signedup' => false, 'message' => __('Password too short. Please enter at least 6 characters!', 'jobster')));
            exit();
        }

        if ($terms_setting && $terms_setting != '') {
            if ($terms == '' || $terms != 'true') {
                echo json_encode(array('signedup' => false, 'message' => __('You need to agree with Terms and Conditions', 'jobster')));
                exit();
            }
        }

        if (($user_type == 'candidate' && $candidate_approval)
            || ($user_type == 'company' && $company_approval)) {
            jobster_add_user_approval_request($user_data, $user_type, $signup_company);
            jobster_notify_admin_user_approval($user_data, $user_type, $signup_company);

            echo json_encode(array(
                'signedup' => true, 
                'message' => __('Your account is pending for approval. You will be notified about your approval process.', 'jobster'),
                'is_pending' => true
            ));
            exit();
        }

        if ($email_verification) {
            $hash = md5(rand(0,1000));
            jobster_add_user_verification_request($user_data, $user_type, $hash, $signup_company);
            jobster_notify_user_verification($user_data, $user_type, $signup_company, $hash);

            echo json_encode(array(
                'signedup' => true, 
                'message' => __('Please check your email and access the received link to activate your account.', 'jobster'),
                'is_pending' => true
            ));
            exit();
        }

        $new_user = wp_insert_user($user_data);

        if (is_wp_error($new_user)) {
            echo json_encode(array('signedup' => false, 'message' => __('Something went wrong!', 'jobster')));
            exit();
        } else {
            echo json_encode(array('signedup' => true, 'message' => __('Congratulations! You have successfully signed up.', 'jobster')));

            jobster_signup_notifications($new_user, $signup_pass);

            if ($user_type != '') {
                jobster_register_user_type($new_user, $user_type, $signup_company);
            }
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_user_signup', 'jobster_user_signup');
add_action('wp_ajax_jobster_user_signup', 'jobster_user_signup');

/**
 * Register user type - candidate/company
 */
if (!function_exists('jobster_register_user_type')): 
    function jobster_register_user_type($user_id, $user_type, $company_name) {
        $user = get_user_by('id', $user_id);

        if ($user_type == 'candidate') {
            $candidate_name = $user->first_name . ' ' . $user->last_name;
            $candidate = array(
                'post_title' => $candidate_name,
                'post_type' => 'candidate',
                'post_author' => $user->ID,
                'post_status' => 'publish'
            );

            $candidate_id = wp_insert_post($candidate);
            update_post_meta($candidate_id, 'candidate_email', $user->user_email);
            update_post_meta($candidate_id, 'candidate_user', $user->ID);
        }

        if ($user_type == 'company') {
            $company = array(
                'post_title' => $company_name,
                'post_type' => 'company',
                'post_author' => $user->ID,
                'post_status' => 'publish'
            );

            $company_id = wp_insert_post($company);
            update_post_meta($company_id, 'company_email', $user->user_email);
            update_post_meta($company_id, 'company_user', $user->ID);

            // Set default payment settings
            $membership_settings = get_option('jobster_membership_settings');
            $payment_type = isset($membership_settings['jobster_payment_type_field'])
                            ? $membership_settings['jobster_payment_type_field']
                            : '';
            $free_standard =    isset($membership_settings['jobster_free_submissions_no_field'])
                                ? $membership_settings['jobster_free_submissions_no_field']
                                : '';
            $free_featured = isset($membership_settings['jobster_free_featured_submissions_no_field']) 
                            ? $membership_settings['jobster_free_featured_submissions_no_field']
                            : '';

            if ($payment_type == 'listing') {
                update_post_meta(
                    $company_id,
                    'company_free_listings',
                    $free_standard
                );
                update_post_meta(
                    $company_id,
                    'company_free_featured_listings',
                    $free_featured
                );
            }
        }
    }
endif;

/**
 * Register candidate - anonymous user
 */
if (!function_exists('jobster_register_candidate')): 
    function jobster_register_candidate($data = array()) {
        $candidate = array(
            'post_title' => $data['name'],
            'post_type' => 'candidate',
            'post_status' => 'draft'
        );

        $candidate_id = wp_insert_post($candidate);

        if ($candidate_id) {
            update_post_meta($candidate_id, 'candidate_email', $data['email']);
            update_post_meta($candidate_id, 'candidate_phone', $data['phone']);
            update_post_meta($candidate_id, 'candidate_cv', $data['cv']);
            update_post_meta($candidate_id, 'candidate_files', $data['files']);

            return $candidate_id;
        }

        return false;
    }
endif;

/**
 * Forgot Password
 */
if (!function_exists('jobster_forgot_password')): 
    function jobster_forgot_password() {
        global $wpdb, $wp_hasher;

        check_ajax_referer('signin_ajax_nonce', 'security');

        $forgot_email = isset($_POST['forgot_email']) ? sanitize_email($_POST['forgot_email']) : '';

        if ($forgot_email == '') {
            echo json_encode(array('sent' => false, 'message' => __('Invalid email address!', 'jobster')));
            exit();
        }

        $user_input = trim($forgot_email);

        if (strpos($user_input, '@')) {
            $user_data = get_user_by('email', $user_input);

            if (empty($user_data)) {
                echo json_encode(array('sent' => false, 'message' => __('Invalid email address!', 'jobster')));
                exit();
            }
        } else {
            $user_data = get_user_by('login', $user_input);

            if (empty($user_data)) {
                echo json_encode(array('sent' => false, 'message' => __('Invalid username!', 'jobster')));
                exit();
            }
        }

        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;

        $key = wp_generate_password(20, false);
        do_action('retrieve_password_key', $user_login, $key);

        if (empty($wp_hasher)) {
            require_once ABSPATH . WPINC . '/class-phpass.php';

            $wp_hasher = new PasswordHash( 8, true );
        }

        $hashed = time() . ':' . $wp_hasher->HashPassword($key);
        $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));

        $message = __('Someone has asked to reset the password for the following site and username.', 'jobster') . "\r\n\r\n";
        $message .= get_option('siteurl') . "\r\n\r\n";
        $message .= sprintf(__('Username: %s', 'jobster'), $user_login) . "\r\n\r\n";
        $message .= __('To reset your password visit the following address, otherwise just ignore this email and nothing will happen.', 'jobster') . "\r\n\r\n";
        $message .= network_site_url("?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";

        if ($message && !wp_mail($user_email, __('Password Reset Request', 'jobster'), $message)) {
            echo json_encode(array('sent' => false, 'message' => __('Email failed to be sent for some unknown reason.', 'jobster')));
            exit();
        } else {
            echo json_encode(array('sent' => true, 'message' => __('An email with password reset instructions was sent to you.', 'jobster')));
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_forgot_password', 'jobster_forgot_password');
add_action('wp_ajax_jobster_forgot_password', 'jobster_forgot_password');

/**
 * Reset Password
 */
if (!function_exists('jobster_reset_password')): 
    function jobster_reset_password() {
        check_ajax_referer('signin_ajax_nonce', 'security');

        $pass  = isset($_POST['pass']) ? sanitize_text_field($_POST['pass']) : '';
        $key   = isset($_POST['key']) ? sanitize_text_field($_POST['key']) : '';
        $login = isset($_POST['login']) ? sanitize_email(urldecode($_POST['login'])) : '';

        if ($pass == '') {
            echo json_encode(array('reset' => false, 'message' => __('Password field empty!', 'jobster')));
            exit();
        }

        $user = check_password_reset_key($key, $login);

        if (is_wp_error($user)) {
            if ($user->get_error_code() === 'expired_key') {
                echo json_encode(array('reset' => false, 'message' => __('Sorry, the link does not appear to be valid or is expired!', 'jobster')));
                exit();
            } else {
                echo json_encode(array('reset' => false, 'message' => __('Sorry, the link does not appear to be valid or is expired!', 'jobster')));
                exit();
            }
        }

        reset_password($user, $pass);
        echo json_encode(array('reset' => true, 'message' => __('Your password has been reset.', 'jobster')));

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_reset_password', 'jobster_reset_password');
add_action('wp_ajax_jobster_reset_password', 'jobster_reset_password');

/**
 * Save New Password from Dashboard
 */
if (!function_exists('jobster_save_pass')): 
    function jobster_save_pass() {
        check_ajax_referer('password_ajax_nonce', 'security');

        $old_pass        =  isset($_POST['old_pass'])
                            ? sanitize_text_field($_POST['old_pass'])
                            :'';
        $new_pass        =  isset($_POST['new_pass'])
                            ? sanitize_text_field($_POST['new_pass'])
                            : '';
        $new_pass_repeat =  isset($_POST['new_pass_repeat'])
                            ? sanitize_text_field($_POST['new_pass_repeat'])
                            : '';

        if ($old_pass == '') {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('Old password field is mandatory.', 'jobster'),
                    'field' => 'old'
                )
            );
            exit();
        }

        if ($new_pass == '') {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('New password field is mandatory.', 'jobster'),
                    'field' => 'new'
                )
            );
            exit();
        }

        if ($new_pass_repeat == '') {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('New password repeat field is mandatory.', 'jobster'),
                    'field' => 'new_r'
                )
            );
            exit();
        }

        if ($new_pass != $new_pass_repeat) {
            echo json_encode(
                array(
                    'save' => false,
                    'message' => __('The passwords do not match.', 'jobster'),
                    'field' => 'new,new_r'
                )
            );
            exit();
        }

        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();

            if ($current_user
                && wp_check_password(
                    $old_pass,
                    $current_user->data->user_pass,
                    $current_user->ID
                )) {
                wp_update_user(
                    array(
                        'ID' => $current_user->ID,
                        'user_pass' => $new_pass
                    )
                );
                echo json_encode(
                    array(
                        'save' => true,
                        'message' => __('Your password has successfuly been reset.', 'jobster')
                    )
                );
                exit();
            } else {
                echo json_encode(
                    array(
                        'save' => false,
                        'message' => __('Old password is incorrect.', 'jobster'),
                        'field' => 'old'
                    )
                );
                exit();
            }
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_save_pass', 'jobster_save_pass');
add_action('wp_ajax_jobster_save_pass', 'jobster_save_pass');

/**
 * Add new user request in pending approval list
 */
if (!function_exists('jobster_add_user_approval_request')): 
    function jobster_add_user_approval_request($user_data, $user_type, $company = '') {
        $pending_users = get_option('jobster_users_settings');

        if (!is_array($pending_users)) {
            $pending_users = array();
        }

        if ($user_type == 'candidate') {
            $pending_users[$user_data['user_email']]['name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
        }
        if ($user_type == 'company') {
            $pending_users[$user_data['user_email']]['name'] = $company;
        }
        $pending_users[$user_data['user_email']]['type'] = $user_type;
        $pending_users[$user_data['user_email']]['data'] = $user_data;

        update_option('jobster_users_settings', $pending_users);
    }
endif;

/**
 * Remove user request from pending approval list
 */
if (!function_exists('jobster_remove_user_approval_request')): 
    function jobster_remove_user_approval_request($email) {
        $pending_users = get_option('jobster_users_settings');

        unset($pending_users[$email]);
        update_option('jobster_users_settings', $pending_users);
    }
endif;

/**
 * Approve pending user
 */
if (!function_exists('jobster_approve_pending_user')): 
    function jobster_approve_pending_user() {
        check_ajax_referer('add_pending_users_ajax_nonce', 'security');

        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $pending_users = get_option('jobster_users_settings');

        $user_data = $pending_users[$email]['data'];

        $new_user = wp_insert_user($user_data);

        if (is_wp_error($new_user)) {
            echo json_encode(array('approve' => false));
            exit();
        } else {
            jobster_signup_notifications($new_user, $user_data['user_pass']);

            if ($pending_users[$email]['type'] != '') {
                jobster_register_user_type(
                    $new_user,
                    $pending_users[$email]['type'],
                    $pending_users[$email]['name']
                );
            }

            jobster_remove_user_approval_request($email);

            echo json_encode(array('approve' => true));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_approve_pending_user', 'jobster_approve_pending_user');
add_action('wp_ajax_jobster_approve_pending_user', 'jobster_approve_pending_user');

/**
 * Deny pending user
 */
if (!function_exists('jobster_deny_pending_user')): 
    function jobster_deny_pending_user() {
        check_ajax_referer('add_pending_users_ajax_nonce', 'security');

        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

        jobster_remove_user_approval_request($email);
        jobster_notify_user_deny($email);

        echo json_encode(array('deny' => true));
        exit();

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_deny_pending_user', 'jobster_deny_pending_user');
add_action('wp_ajax_jobster_deny_pending_user', 'jobster_deny_pending_user');

/**
 * Admin user pending notification
 */
if (!function_exists('jobster_notify_admin_user_approval')): 
    function jobster_notify_admin_user_approval($user_data, $user_type, $signup_company) {
        $message = sprintf( __('New user pending approval on %s:', 'jobster'), get_option('blogname') ) . "\r\n\r\n";
        if ($user_type == 'company') {
            $message .= sprintf( __('Company: %s', 'jobster'), esc_html($signup_company) ) . "\r\n";
        }
        if ($user_type == 'candidate') {
            $name = $user_data['first_name'] . ' ' . $user_data['last_name'];
            $message .= sprintf( __('Candidate: %s', 'jobster'), esc_html($name) ) . "\r\n";
        }
        $message .= sprintf( __('Username: %s', 'jobster'), esc_html($user_data['user_login']) ) . "\r\n";
        $message .= sprintf( __('E-mail: %s', 'jobster'), esc_html($user_data['user_email']) );

        $admin_email = get_option('admin_email');

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option('blogname') . '<' . get_option('admin_email') . '>',
            'Reply-To: ' . get_option('blogname') . '<' . get_option('admin_email') . '>'
        );

        $send_admin = wp_mail(
            $admin_email,
            sprintf(__('[%s] New User Pending Approval', 'jobster'), get_option('blogname') ),
            $message,
            $headers
        );

        if (!$send_admin) {
            wp_mail(
                $admin_email,
                sprintf(__('[%s] New User Pending Approval', 'jobster'), get_option('blogname') ),
                $message
            );
        }
    }
endif;

/**
 * User account deny notification 
 */
if (!function_exists('jobster_notify_user_deny')): 
    function jobster_notify_user_deny($email) {
        $message = sprintf( __('Your account registration on %s was denied.', 'jobster'), get_option('blogname') );

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option('blogname') . '<' . get_option('admin_email') . '>',
            'Reply-To: ' . get_option('blogname') . '<' . get_option('admin_email') . '>'
        );

        $send = wp_mail(
            $email,
            sprintf(__('[%s] Account Registration Denied', 'jobster'), get_option('blogname') ),
            $message,
            $headers
        );

        if (!$send) {
            wp_mail(
                $email,
                sprintf(__('[%s] Account Registration Denied', 'jobster'), get_option('blogname') ),
                $message
            );
        }
    }
endif;

/**
 * Add new user request in pending verification list
 */
if (!function_exists('jobster_add_user_verification_request')): 
    function jobster_add_user_verification_request($user_data, $user_type, $hash, $company = '') {
        $pending_users = get_option('jobster_users_verification');

        if (!is_array($pending_users)) {
            $pending_users = array();
        }

        if ($user_type == 'candidate') {
            $pending_users[$user_data['user_email']]['name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
        }
        if ($user_type == 'company') {
            $pending_users[$user_data['user_email']]['name'] = $company;
        }
        $pending_users[$user_data['user_email']]['type'] = $user_type;
        $pending_users[$user_data['user_email']]['data'] = $user_data;
        $pending_users[$user_data['user_email']]['hash'] = $hash;

        update_option('jobster_users_verification', $pending_users);
    }
endif;

/**
 * User verification notification - activation link
 */
if (!function_exists('jobster_notify_user_verification')): 
    function jobster_notify_user_verification($user_data, $user_type, $signup_company, $hash) {
        $name = '';
        if ($user_type == 'company') {
            $name = $signup_company;
        }
        if ($user_type == 'candidate') {
            $name = $user_data['first_name'];
        }

        $email_settings = get_option('jobster_email_settings');
        $template =     isset($email_settings['jobster_email_activation_notify_user_field']) 
                        ? $email_settings['jobster_email_activation_notify_user_field'] 
                        : '';

        if ($template != '') {
            $template = str_replace("{USER_NAME}", $name, $template);
            $template = str_replace("{WEBSITE_NAME}", get_option('blogname'), $template);
            $template = str_replace("{ACTIVATION_URL}", network_site_url("?action=activate&key=$hash&login=" . rawurlencode($user_data['user_email']), 'activate'), $template);

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: ' . get_option('blogname') . '<' . get_option('admin_email') . '>',
                'Reply-To: ' . get_option('blogname') . '<' . get_option('admin_email') . '>'
            );

            $send = wp_mail(
                $user_data['user_email'],
                sprintf(__('[%s] Activate Your Account', 'jobster'), get_option('blogname')),
                $template,
                $headers
            );
        } else {
            $message = sprintf( __('Dear %s,', 'jobster'), $name ) . "\r\n\r\n";
            $message .= sprintf( __('Thank you for choosing %s!', 'jobster'), get_option('blogname')) . "\r\n\r\n";
            $message .= __('Click on the activation link provided below to activate your account:', 'jobster') . "\r\n\r\n";
            $message .= network_site_url("?action=activate&key=$hash&login=" . rawurlencode($user_data['user_email']), 'activate') . "\r\n";

            $headers = array(
                'Content-Type: text/plain; charset=UTF-8',
                'From: ' . get_option('blogname') . '<' . get_option('admin_email') . '>',
                'Reply-To: ' . get_option('blogname') . '<' . get_option('admin_email') . '>'
            );

            $send = wp_mail(
                $user_data['user_email'],
                sprintf(__('[%s] Activate Your Account', 'jobster'), get_option('blogname') ),
                $message,
                $headers
            );
        }
    }
endif;

/**
 * Activate user account when they access the acount activation link
 */
if (!function_exists('jobster_activate_user_account')): 
    function jobster_activate_user_account() {
        $pending_users = get_option('jobster_users_verification');

        $key  = isset($_GET['key']) ? sanitize_text_field($_GET['key']) : '';
        $email = isset($_GET['login']) ? sanitize_email(urldecode($_GET['login'])) : '';

        $message = __('Email not found. Either your account has already been activated, or the link is not valid.', 'jobster');

        if (isset($pending_users[$email])) {
            if ($pending_users[$email]['hash'] === $key) {
                $user_data = $pending_users[$email]['data'];
                $new_user = wp_insert_user($user_data);

                if (!is_wp_error($new_user)) {
                    jobster_signup_notifications($new_user, $user_data['user_pass']);

                    if ($pending_users[$email]['type'] != '') {
                        jobster_register_user_type(
                            $new_user,
                            $pending_users[$email]['type'],
                            $pending_users[$email]['name']
                        );
                    }

                    unset($pending_users[$email]);
                    update_option('jobster_users_verification', $pending_users);

                    $message = __('Your account has been successfully activated.', 'jobster');
                } else {
                    $message = __('Something went wrong when trying to activate yuor account. Please contact us.', 'jobster');
                }
            } else {
                $message = __('Account activation key not valid.', 'jobster');
            }
        }

        return $message;
    }
endif;

/**
 * Google authentication
 */
if (!function_exists('jobster_google_auth')): 
    function jobster_google_auth() {
        if (is_user_logged_in()) {
            echo json_encode(array('signedin' => true, 'message' => __('You are already signed in, redirecting...', 'jobster')));
            exit();
        }

        check_ajax_referer('signin_ajax_nonce', 'security');

        $user_id     = isset($_POST['uid']) ? sanitize_text_field($_POST['uid']) : '';
        $username    = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $name        = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $first_name  = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $last_name   = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
        $email       = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $avatar      = isset($_POST['avatar']) ? sanitize_text_field($_POST['avatar']) : '';

        $auth_settings = get_option('jobster_authentication_settings');
        $google_auth_client_secret =    isset($auth_settings['jobster_google_auth_client_secret_field'])
                                        ? $auth_settings['jobster_google_auth_client_secret_field']
                                        : '';
        $pass = $google_auth_client_secret . $user_id;

        $user = array(
            'user_id' => $user_id,
            'username' => $username,
            'name' => $name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'avatar' => $avatar,
            'pass' => $pass
        );

        jobster_social_signup($user);

        $vsessionid = session_id();
        if (empty($vsessionid)) {
            session_name('PHPSESSID');
            session_start();
        }

        wp_clear_auth_cookie();
        $data = array();
        $data['user_login'] = $username;
        $data['user_password'] = $pass;
        $data['remember'] = true;

        $user_signon = wp_signon($data, false);

        if (is_wp_error($user_signon)) {
            echo json_encode(array(
                'signedin' => false, 
                'message' => __('You already have an account, please use your email address to sign in.', 'jobster')
            ));
            exit();
        } else {
            echo json_encode(array(
                'signedin' => true,
                'newuser'  => $user_signon->ID,
                'redirect' => 'default',
                'message'  => __('Sign in successful, redirecting...', 'jobster'),
            ));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_google_auth', 'jobster_google_auth');
add_action('wp_ajax_jobster_google_auth', 'jobster_google_auth');

/**
 * Facebook authentication
 */
if (!function_exists('jobster_facebook_auth')): 
    function jobster_facebook_auth() {
        if (is_user_logged_in()) {
            echo json_encode(array('signedin' => true, 'message' => __('You are already signed in, redirecting...', 'jobster')));
            exit();
        }

        check_ajax_referer('signin_ajax_nonce', 'security');

        $user_id     = isset($_POST['uid']) ? sanitize_text_field($_POST['uid']) : '';
        $username    = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $first_name  = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $last_name   = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
        $email       = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

        $auth_settings = get_option('jobster_authentication_settings');
        $fb_auth_app_secret =   isset($auth_settings['jobster_fb_auth_app_secret_field'])
                                ? $auth_settings['jobster_fb_auth_app_secret_field']
                                : '';
        $pass = $fb_auth_app_secret . $user_id;

        $user = array(
            'user_id' => $user_id,
            'username' => $username,
            'name' => $first_name . ' ' . $last_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'pass' => $pass
        );

        jobster_social_signup($user);

        $vsessionid = session_id();
        if (empty($vsessionid)) {
            session_name('PHPSESSID');
            session_start();
        }

        wp_clear_auth_cookie();
        $data = array();
        $data['user_login'] = $username;
        $data['user_password'] = $pass;
        $data['remember'] = true;

        $user_signon = wp_signon($data, false);

        if (is_wp_error($user_signon)) {
            echo json_encode(array(
                'signedin' => false, 
                'message' => __('You already have an account, please use your email address to sign in.', 'jobster')
            ));
            exit();
        } else {
            echo json_encode(array(
                'signedin' => true,
                'newuser'  => $user_signon->ID,
                'redirect' => 'default',
                'message'  => __('Sign in successful, redirecting...', 'jobster'),
            ));
            exit();
        }

        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_facebook_auth', 'jobster_facebook_auth');
add_action('wp_ajax_jobster_facebook_auth', 'jobster_facebook_auth');

if (!function_exists('jobster_social_signup')): 
    function jobster_social_signup($user) {
        $user_data = array(
            'user_login' => $user['username'],
            'user_email' => $user['email'],
            'user_pass'  => $user['pass'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name']
        );

        if (email_exists($user['email'])) {
            if (username_exists($user['username'])) {
                return;
            } else {
                $user_data['user_email'] = ' ';
                $new_user = wp_insert_user($user_data);
                jobster_signup_notifications($new_user, $user['pass']);
            }
        } else {
            if (username_exists($user['username'])) {
                return;
            } else {
                $new_user = wp_insert_user($user_data);
                jobster_signup_notifications($new_user, $user['pass']);
            }
        }
    }
endif;

/**
 * Activate user type - candidate/company - user has already account (user signed in) or registered via social accounts
 */
if (!function_exists('jobster_activate_user_type')): 
    function jobster_activate_user_type() {
        if (!is_user_logged_in()) {
            echo json_encode(array('activated' => false));
            exit();
        }

        check_ajax_referer('signin_ajax_nonce', 'security');

        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

        $user = wp_get_current_user();
        $user_name = $user->first_name . ' ' . $user->last_name;

        if ($type == 'candidate') {
            $candidate = array(
                'post_title' => $user_name,
                'post_type' => 'candidate',
                'post_author' => $user->ID,
                'post_status' => 'publish'
            );

            $candidate_id = wp_insert_post($candidate);
            update_post_meta($candidate_id, 'candidate_email', $user->user_email);
            update_post_meta($candidate_id, 'candidate_user', $user->ID);
        }

        if ($type == 'company') {
            $company = array(
                'post_title' => $user_name,
                'post_type' => 'company',
                'post_author' => $user->ID,
                'post_status' => 'publish'
            );

            $company_id = wp_insert_post($company);
            update_post_meta($company_id, 'company_email', $user->user_email);
            update_post_meta($company_id, 'company_user', $user->ID);

            // Set default payment settings
            $membership_settings = get_option('jobster_membership_settings');
            $payment_type = isset($membership_settings['jobster_payment_type_field'])
                            ? $membership_settings['jobster_payment_type_field']
                            : '';
            $free_standard =    isset($membership_settings['jobster_free_submissions_no_field'])
                                ? $membership_settings['jobster_free_submissions_no_field']
                                : '';
            $free_featured = isset($membership_settings['jobster_free_featured_submissions_no_field']) 
                            ? $membership_settings['jobster_free_featured_submissions_no_field']
                            : '';

            if ($payment_type == 'listing') {
                update_post_meta(
                    $company_id,
                    'company_free_listings',
                    $free_standard
                );
                update_post_meta(
                    $company_id,
                    'company_free_featured_listings',
                    $free_featured
                );
            }
        }

        echo json_encode(array(
            'activated' => true, 
            'message' => __('Your account has been successfully updated.', 'jobster')
        ));

        exit();
        die();
    }
endif;
add_action('wp_ajax_nopriv_jobster_activate_user_type', 'jobster_activate_user_type');
add_action('wp_ajax_jobster_activate_user_type', 'jobster_activate_user_type');
?>