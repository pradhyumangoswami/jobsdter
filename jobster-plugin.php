<?php
/*
* Plugin Name: Jobster Plugin
* Description: Core functionality for Jobster WP Theme.
* Text Domain: jobster
* Domain Path: /languages
* Version: 2.1
* Author: Pixel Prime
* Author URI: http://pixelprime.co
*/

define('JOBSTER_PLUGIN_PATH', plugin_dir_url( __FILE__ ));
define('JOBSYER_PLUGIN_BASENAME', plugin_basename(__FILE__));

add_action('plugins_loaded', 'jobster_load_textdomain');
function jobster_load_textdomain() {
    load_plugin_textdomain('jobster', false, dirname(plugin_basename( __FILE__ ) ) . '/languages');
}

/**
 * Scripts
 */
require_once 'scripts.php';

/**
 * Custom post types
 */
require_once 'post-types/init.php';

/**
 * Custom meta
 */
require_once 'meta/init.php';

/**
 * Custom Navigation
 */
require_once 'nav/init.php';

/**
 * Blocks
 */
require_once 'blocks/init.php';

/**
 * Widgets
 */
require_once 'widgets/init.php';

/**
 * Page templates
 */
require_once 'page-templates/init.php';

/**
 * Services
 */
require_once 'services/users.php';
require_once 'services/search-jobs.php';
require_once 'services/search-companies.php';
require_once 'services/search-candidates.php';
require_once 'services/favs.php';
require_once 'services/apps.php';
require_once 'services/contact-company.php';
require_once 'services/contact-candidate.php';
require_once 'services/inbox.php';
require_once 'services/notifications.php';
require_once 'services/visitors.php';
require_once 'services/upload-cover.php';
require_once 'services/upload-logo.php';
require_once 'services/upload-cv.php';
require_once 'services/upload-file.php';
require_once 'services/upload-gallery.php';
require_once 'services/update-company-profile.php';
require_once 'services/save-job.php';
require_once 'services/update-candidate-profile.php';
require_once 'services/subscription.php';
require_once 'services/candidate-subscription.php';
require_once 'services/candidate-subscription-handlers.php';
require_once 'admin/subscription-admin.php';
require_once 'admin/subscription-admin-fix.php';
require_once 'admin/company-subscription-enabler.php';
require_once 'services/contact-block.php';
require_once 'services/paypal.php';
require_once 'services/stripe.php';
require_once 'services/upload-doc.php';

/**
 * Views
 */
require_once 'views/init.php';
require_once 'views/user-nav.php';
require_once 'views/search-jobs-form.php';
require_once 'views/search-jobs-form-hero.php';
require_once 'views/filter-jobs-form.php';
require_once 'views/search-companies-form.php';
require_once 'views/search-candidates-form.php';
require_once 'views/social.php';
require_once 'views/similar-jobs.php';
require_once 'views/contact-company-form.php';
require_once 'views/company-jobs.php';
require_once 'views/contact-candidate-form.php';
require_once 'views/company-dashboard-side.php';
require_once 'views/company-dashboard-top.php';
require_once 'views/candidate-dashboard-side.php';
require_once 'views/candidate-dashboard-top.php';
require_once 'views/share-post.php';
require_once 'views/youtube-video.php';
require_once 'views/search-jobs-form-section.php';

/**
 * Admin
 */
require_once 'admin/settings.php';

/**
 * Elementor
 */
require_once 'elementor/init.php';

/**
 * Stripe
 */
$membership_settings = get_option('jobster_membership_settings', '');
$payment_type  =    isset($membership_settings['jobster_payment_type_field'])
                    ? $membership_settings['jobster_payment_type_field']
                    : '';
if ($payment_type == 'listing' || $payment_type == 'plan') {
    $payment_system =   isset($membership_settings['jobster_payment_system_field'])
                        ? $membership_settings['jobster_payment_system_field']
                        : '';
    if ($payment_system == 'stripe') {
        require_once 'libs/stripe-php-9.0.0/init.php';
        $stripe_sk =    isset($membership_settings['jobster_stripe_secret_key_field'])
                        ? $membership_settings['jobster_stripe_secret_key_field']
                        : '';
        if ($stripe_sk != '') {
            \Stripe\Stripe::setApiKey($stripe_sk);
        }
    }
}

/**
 * Job Board External APIs
 */

$apis_settings = get_option('jobster_apis_settings', '');
$api  = isset($apis_settings['jobster_api_field'])
        ? $apis_settings['jobster_api_field']
        : '';
if ($api == 'careerjet') {
    require_once 'libs/Careerjet_API.php';
    require_once 'services/search-jobs-careerjet.php';
}

/**
 * Custom colors
 */
if (!function_exists('jobster_add_custom_colors')): 
    function jobster_add_custom_colors() {
        echo '<style>';
        require_once 'services/colors.php';
        echo '</style>';
    }
endif;
add_action('wp_head', 'jobster_add_custom_colors');

if (!function_exists('jobster_sanitize_multi_array')) :
    function jobster_sanitize_multi_array(&$item, $key) {
        $item = sanitize_text_field($item);
    }
endif;

if (!function_exists('jobster_sanitize_array')) :
    function jobster_sanitize_array($array) {
        $new_array = array();

        foreach ($array as $value) {
            array_push($new_array, sanitize_text_field($value));
        }

        return $new_array;
    }
endif;

if (!function_exists('jobster_get_attachment')) :
    function jobster_get_attachment($id) {
        $attachment = get_post($id);

        if ($attachment) {
            return array(
                'alt'         => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
                'caption'     => $attachment->post_excerpt,
                'description' => $attachment->post_content,
                'title'       => $attachment->post_title
            );
        } else {
            return false;
        }
    }
endif;

/**
 * Custom 2nd logo
 */
if (!function_exists('jobster_add_second_logo_setting')): 
    function jobster_add_second_logo_setting($wp_customize) {
        $wp_customize->add_setting('jobster_second_logo');

        $wp_customize->add_control(
            new WP_Customize_Cropped_Image_Control(
                $wp_customize, 
                'jobster_second_logo', 
                array(
                    'label'      => __('Transparent Header Logo', 'jobster'),
                    'section'    => 'title_tagline',
                    'settings'   => 'jobster_second_logo',
                    'priority'   => 10,
                    'height'     => 300,
                    'width'      => 300,
                    'flex-width' => true,
                )
            )
        );
    }
endif;
add_action('customize_register', 'jobster_add_second_logo_setting');

if (!function_exists('jobster_compare_position')) :
    function jobster_compare_position($a, $b) {
        return intval($a["position"]) - intval($b["position"]);
    }
endif;
?>