<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class JobsterPageTemplater {
    private static $instance;
    protected $templates;

    public static function get_instance() {
        if(null == self::$instance) {
            self::$instance = new JobsterPageTemplater();
        }

        return self::$instance;
    }

    private function __construct() {
        $this->templates = array();

        if (version_compare(floatval(get_bloginfo('version')), '4.7', '<')) {
            add_filter('page_attributes_dropdown_pages_args', array($this, 'register_project_templates'));
        } else {
            add_filter('theme_page_templates', array($this, 'add_new_template'));
        }

        add_filter('wp_insert_post_data', array($this, 'register_project_templates'));
        add_filter('template_include', array($this, 'view_project_template'));

        $this->templates = array(
            'full-width.php'                        => __('Full Width', 'jobster'),
            'job-search.php'                        => __('Job Search', 'jobster'),
            'job-search-apis.php'                   => __('Job Search External APIs', 'jobster'),
            'company-search.php'                    => __('Company Search', 'jobster'),
            'candidate-search.php'                  => __('Candidate Search', 'jobster'),
            'job-categories.php'                    => __('Job Categories', 'jobster'),
            'company-dashboard.php'                 => __('Company Dashboard', 'jobster'),
            'company-dashboard-profile.php'         => __('Company Dashboard - Profile', 'jobster'),
            'company-dashboard-new-job.php'         => __('Company Dashboard - New Job', 'jobster'),
            'company-dashboard-jobs.php'            => __('Company Dashboard - Manage Jobs', 'jobster'),
            'company-dashboard-edit-job.php'        => __('Company Dashboard - Edit Job', 'jobster'),
            'company-dashboard-candidates.php'      => __('Company Dashboard - Candidates', 'jobster'),
            'company-dashboard-subscriptions.php'   => __('Company Dashboard - Subscriptions', 'jobster'),
            'company-dashboard-password.php'        => __('Company Dashboard - Change Password', 'jobster'),
            'company-dashboard-inbox.php'           => __('Company Dashboard - Inbox', 'jobster'),
            'company-dashboard-notifications.php'   => __('Company Dashboard - Notifications', 'jobster'),
            'candidate-dashboard.php'               => __('Candidate Dashboard', 'jobster'),
            'candidate-dashboard-profile.php'       => __('Candidate Dashboard - Profile', 'jobster'),
            'candidate-dashboard-apps.php'          => __('Candidate Dashboard - Applications', 'jobster'),
            'candidate-dashboard-favs.php'          => __('Candidate Dashboard - Favourite Jobs', 'jobster'),
            'candidate-dashboard-password.php'      => __('Candidate Dashboard - Change Password', 'jobster'),
            'candidate-dashboard-inbox.php'         => __('Candidate Dashboard - Inbox', 'jobster'),
            'candidate-dashboard-notifications.php' => __('Candidate Dashboard - Notifications', 'jobster'),
            'candidate-dashboard-subscriptions.php' => __('Candidate Dashboard - Subscriptions', 'jobster'),
            'candidate-dashboard-apply.php'         => __('Candidate Dashboard - Apply', 'jobster'),
            'paypal-processor.php'                  => __('PayPal Processor', 'jobster'),
            'stripe-processor.php'                  => __('Stripe Processor', 'jobster'),
            'sign-in.php'                           => __('Sign In', 'jobster'),
            'sign-up.php'                           => __('Sign Up', 'jobster'),
        );
    }

    public function add_new_template($posts_templates) {
        $posts_templates = array_merge($posts_templates, $this->templates);

        return $posts_templates;
    }

    public function register_project_templates($atts) {
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());
        $templates = wp_get_theme()->get_page_templates();

        if (empty($templates)) {
            $templates = array();
        }

        wp_cache_delete($cache_key , 'themes');

        $templates = array_merge($templates, $this->templates);

        wp_cache_add($cache_key, $templates, 'themes', 1800);

        return $atts;
    }

    public function view_project_template($template) {
        global $post;

        if (!$post) {
            return $template;
        }

        if (!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])) {
            return $template;
        }

        $file = plugin_dir_path( __FILE__ ) . get_post_meta($post->ID, '_wp_page_template', true);

        if (file_exists($file)) {
            return $file;
        } else {
            echo $file;
        }

        return $template;
    }
}
add_action('plugins_loaded', array('JobsterPageTemplater', 'get_instance'));

/**
 * Get page link by template file
 */
if (!function_exists('jobster_get_page_link')): 
    function jobster_get_page_link($template_file) {
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $template_file
        ));

        $page_link = '';
        if ($pages) {
            $page_link = get_permalink($pages[0]->ID);
        }

        return $page_link;
    }
endif;