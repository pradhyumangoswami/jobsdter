<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Elementor_Jobster_Extension {
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            return;
        }

        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
    }

    public function init_widgets() {
        // Include Widget files
        require_once(__DIR__ . '/widgets/job-categories.php');
        require_once(__DIR__ . '/widgets/recent-jobs.php');
        require_once(__DIR__ . '/widgets/featured-jobs.php');
        require_once(__DIR__ . '/widgets/careerjet-jobs.php');
        require_once(__DIR__ . '/widgets/featured-companies.php');
        require_once(__DIR__ . '/widgets/featured-candidates.php');
        require_once(__DIR__ . '/widgets/job-locations.php');
        require_once(__DIR__ . '/widgets/careerjet-locations.php');
        require_once(__DIR__ . '/widgets/subscribe.php');
        require_once(__DIR__ . '/widgets/services.php');
        require_once(__DIR__ . '/widgets/testimonials.php');
        require_once(__DIR__ . '/widgets/promo.php');
        require_once(__DIR__ . '/widgets/latest-articles.php');
        require_once(__DIR__ . '/widgets/featured-articles.php');
        require_once(__DIR__ . '/widgets/features.php');
        require_once(__DIR__ . '/widgets/faqs.php');
        require_once(__DIR__ . '/widgets/contact-info.php');
        require_once(__DIR__ . '/widgets/contact-form.php');
        require_once(__DIR__ . '/widgets/membership-plans.php');
        require_once(__DIR__ . '/widgets/search-jobs.php');
        require_once(__DIR__ . '/widgets/user-nav.php');

        // Register widgets
        $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
        $widgets_manager->register(new \Elementor_Jobster_Job_Categories());
        $widgets_manager->register(new \Elementor_Jobster_Recent_Jobs());
        $widgets_manager->register(new \Elementor_Jobster_Featured_Jobs());
        $widgets_manager->register(new \Elementor_Jobster_Careerjet_Jobs());
        $widgets_manager->register(new \Elementor_Jobster_Featured_Companies());
        $widgets_manager->register(new \Elementor_Jobster_Featured_Candidates());
        $widgets_manager->register(new \Elementor_Jobster_Job_Locations());
        $widgets_manager->register(new \Elementor_Jobster_Careerjet_Locations());
        $widgets_manager->register(new \Elementor_Jobster_Subscribe());
        $widgets_manager->register(new \Elementor_Jobster_Services());
        $widgets_manager->register(new \Elementor_Jobster_Testimonials());
        $widgets_manager->register(new \Elementor_Jobster_Promo());
        $widgets_manager->register(new \Elementor_Jobster_Latest_Articles());
        $widgets_manager->register(new \Elementor_Jobster_Featured_Articles());
        $widgets_manager->register(new \Elementor_Jobster_Features());
        $widgets_manager->register(new \Elementor_Jobster_FAQs());
        $widgets_manager->register(new \Elementor_Jobster_Contact_Info());
        $widgets_manager->register(new \Elementor_Jobster_Contact_Form());
        $widgets_manager->register(new \Elementor_Jobster_Memberhip_Plans());
        $widgets_manager->register(new \Elementor_Jobster_Search_Jobs());
        $widgets_manager->register(new \Elementor_Jobster_User_Nav());
    }
}

Elementor_Jobster_Extension::instance();

function jobster_add_elementor_widget_category($elements_manager) {
    $elements_manager->add_category(
        'jobster',
        [
            'title' => __('Jobster', 'jobster'),
            'icon' => 'eicon-site-identity',
        ]
    );
}
add_action('elementor/elements/categories_registered', 'jobster_add_elementor_widget_category');
?>