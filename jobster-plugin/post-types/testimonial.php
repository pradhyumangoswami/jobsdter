<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Register testimonial custom post type
 */
if (!function_exists('jobster_register_testimonial_type')): 
    function jobster_register_testimonial_type() {
        register_post_type('testimonial', array(
            'labels' => array(
                'name'               => __('Testimonials', 'jobster'),
                'singular_name'      => __('Testimonial', 'jobster'),
                'add_new'            => __('Add New Testimonial', 'jobster'),
                'add_new_item'       => __('Add Testimonial', 'jobster'),
                'edit'               => __('Edit', 'jobster'),
                'edit_item'          => __('Edit Testimonial', 'jobster'),
                'new_item'           => __('New Testimonial', 'jobster'),
                'view'               => __('View', 'jobster'),
                'view_item'          => __('View Testimonial', 'jobster'),
                'search_items'       => __('Search Testimonials', 'jobster'),
                'not_found'          => __('No Testimonials found', 'jobster'),
                'not_found_in_trash' => __('No Testimonials found in Trash', 'jobster'),
                'parent'             => __('Parent Testimonial', 'jobster'),
            ),
            'public'               => true,
            'exclude_from_search ' => true,
            'has_archive'          => true,
            'rewrite'              => array('slug' => _x('testimonials', 'URL SLUG', 'jobster')),
            'supports'             => array('title'),
            'can_export'           => true,
            'register_meta_box_cb' => 'jobster_add_testimonial_metaboxes',
            'menu_icon'            => 'dashicons-format-quote',
        ));
    }
endif;
add_action('init', 'jobster_register_testimonial_type');

if (!function_exists('jobster_add_testimonial_metaboxes')): 
    function jobster_add_testimonial_metaboxes() {
        add_meta_box('testimonial-text-section', __('What the customer says', 'jobster'), 'jobster_testimonial_text_render', 'testimonial', 'normal', 'default');
        add_meta_box('testimonial-location-section', __('Customer Location/Company/Title', 'jobster'), 'jobster_testimonial_location_render', 'testimonial', 'normal', 'default');
    }
endif;

if (!function_exists('jobster_testimonial_text_render')): 
    function jobster_testimonial_text_render($post) {
        wp_nonce_field('jobster_testimonial', 'testimonial_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top" align="left">
                        <p class="meta-options">
                            <textarea id="testimonial_text" name="testimonial_text" placeholder="' . __('Enter what the customer says here', 'jobster') . '" style="width: 100%; height: 160px;">' . esc_html(get_post_meta($post->ID, 'testimonial_text', true)) . '</textarea>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_testimonial_location_render')): 
    function jobster_testimonial_location_render($post) {
        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top" align="left">
                        <div class="adminField">
                            <label for="testimonial_location">' . __('Location/Title/Company', 'jobster') . '</label><br>
                            <input type="text" class="formInput" id="testimonial_location" name="testimonial_location" placeholder="' . __('Enter customer location', 'jobster') . '" value="' . esc_html(get_post_meta($post->ID, 'testimonial_location', true)) . '">
                        </div>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_testimonial_meta_save')): 
    function jobster_testimonial_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['testimonial_noncename']) && wp_verify_nonce($_POST['testimonial_noncename'], 'jobster_testimonial')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['testimonial_text'])) {
            update_post_meta($post_id, 'testimonial_text', sanitize_text_field($_POST['testimonial_text']));
        }
        if (isset($_POST['testimonial_location'])) {
            update_post_meta($post_id, 'testimonial_location', sanitize_text_field($_POST['testimonial_location']));
        }
    }
endif;
add_action('save_post', 'jobster_testimonial_meta_save');

if (!function_exists('jobster_change_testimonial_default_title')): 
    function jobster_change_testimonial_default_title($title) {
        $screen = get_current_screen();

        if ('testimonial' == $screen->post_type) {
            $title = __('Enter customer name here', 'jobster');
        }
        return $title;
    }
endif;
add_filter('enter_title_here', 'jobster_change_testimonial_default_title');
?>