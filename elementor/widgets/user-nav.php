<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

class Elementor_Jobster_User_Nav extends \Elementor\Widget_Base {
    public function get_name() {
        return 'user_nav';
    }

    public function get_title() {
        return __('User Navigation', 'jobster');
    }

    public function get_icon() {
        return 'eicon-user-circle-o';
    }

    public function get_categories() {
        return ['jobster'];
    }


    protected function _register_controls() {
       
    }

    protected function render() {
        $header_type = ''; 
        $companies_settings = get_option('jobster_companies_settings');
        $company_layout =   isset($companies_settings['jobster_company_page_layout_field'])
                            ? $companies_settings['jobster_company_page_layout_field']
                            : 'wide';
        $candidates_settings = get_option('jobster_candidates_settings');
        $candidate_layout = isset($candidates_settings['jobster_candidate_page_layout_field'])
                            ? $candidates_settings['jobster_candidate_page_layout_field']
                            : 'wide';
        $user_nav_on_light_class = '';

        if (isset($post)) {
            $header_type = get_post_meta(
                $post->ID,
                'page_header_type',
                true
            );
        }
        $user_nav_on_light_class = '';
        if (    $header_type == 'image_rotator'
                || $header_type == 'boxed'
                || $header_type == 'none'
                || empty($header_type)
                || $header_type == 'half_image'
                || $header_type == 'center_image'
                || $header_type == 'image_pills'
                || $header_type == 'right_image'
                || (isset($_GET['action']) && $_GET['action'] == 'activate')
        ) {
            $user_nav_on_light_class = 'pxp-on-light';
            if (is_singular('company') && $company_layout == 'wide') {
                $user_nav_on_light_class = '';
            }
            if (is_singular('candidate') && $candidate_layout == 'wide') {
                $user_nav_on_light_class = '';
            }
        }
        
        ?>

        <nav class="pxp-user-nav <?php echo esc_attr($user_nav_on_light_class); ?> d-none d-sm-flex">
            <?php if ($header_type == 'image_bg'
            || $header_type == 'top_search'
            || (is_singular('company') && $company_layout == 'wide')
            || (is_singular('candidate') && $candidate_layout == 'wide')) {
                jobster_get_user_nav('top', true);
            } else {
                jobster_get_user_nav();
            } ?>
        </nav>
    <?php }
}
?>