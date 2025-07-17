<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

/**
 * Register subscriber custom post type
 */
if (!function_exists('jobster_register_subscriber_type')): 
    function jobster_register_subscriber_type() {
        register_post_type('subscriber', array(
            'labels' => array(
                'name'               => __('Subscribers', 'jobster'),
                'singular_name'      => __('Subscriber', 'jobster'),
                'add_new'            => __('Add New Subscriber', 'jobster'),
                'add_new_item'       => __('Add Subscriber', 'jobster'),
                'edit'               => __('Edit', 'jobster'),
                'edit_item'          => __('Edit Subscriber', 'jobster'),
                'new_item'           => __('New Subscriber', 'jobster'),
                'view'               => __('View', 'jobster'),
                'view_item'          => __('View Subscriber', 'jobster'),
                'search_items'       => __('Search Subscribers', 'jobster'),
                'not_found'          => __('No Subscribers found', 'jobster'),
                'not_found_in_trash' => __('No Subscribers found in Trash', 'jobster'),
                'parent'             => __('Parent Subscriber', 'jobster'),
            ),
            'public'               => true,
            'exclude_from_search ' => true,
            'has_archive'          => true,
            'rewrite'              => array('slug' => _x('subscribers', 'URL SLUG', 'jobster')),
            'supports'             => array('title'),
            'can_export'           => true,
            'register_meta_box_cb' => 'jobster_add_subscriber_metaboxes',
            'menu_icon'            => 'dashicons-groups',
        ));
    }
endif;
add_action('init', 'jobster_register_subscriber_type');

if (!function_exists('jobster_add_subscriber_metaboxes')): 
    function jobster_add_subscriber_metaboxes() {
        add_meta_box('subscriber-notes-section', __('Notes', 'jobster'), 'jobster_subscriber_notes_render', 'subscriber', 'normal', 'default');
    }
endif;

if (!function_exists('jobster_subscriber_notes_render')): 
    function jobster_subscriber_notes_render($post) {
        wp_nonce_field('jobster_subscriber', 'subscriber_noncename');

        print '
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" valign="top" align="left">
                        <p class="meta-options">
                            <textarea id="subscriber_notes" name="subscriber_notes" placeholder="' . __('Enter notes about subscriber', 'jobster') . '" style="width: 100%; height: 160px;">' . esc_html(get_post_meta($post->ID, 'subscriber_notes', true)) . '</textarea>
                        </p>
                    </td>
                </tr>
            </table>';
    }
endif;

if (!function_exists('jobster_subscriber_meta_save')): 
    function jobster_subscriber_meta_save($post_id) {
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['subscriber_noncename']) && wp_verify_nonce($_POST['subscriber_noncename'], 'jobster_subscriber')) ? 'true' : 'false';

        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }

        if (isset($_POST['subscriber_notes'])) {
            update_post_meta($post_id, 'subscriber_notes', sanitize_text_field($_POST['subscriber_notes']));
        }
    }
endif;
add_action('save_post', 'jobster_subscriber_meta_save');

if (!function_exists('jobster_change_subscriber_default_title')): 
    function jobster_change_subscriber_default_title($title) {
        $screen = get_current_screen();

        if ('subscriber' == $screen->post_type) {
            $title = __('Enter subscriber email address here', 'jobster');
        }
        return $title;
    }
endif;
add_filter('enter_title_here', 'jobster_change_subscriber_default_title');
?>