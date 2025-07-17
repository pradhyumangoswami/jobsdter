<?php
/*
Template Name: Company Dashboard - Inbox
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

global $current_user;
global $company_id;
global $post;

if (!is_user_logged_in()) {
    wp_redirect(home_url());
}

$current_user = wp_get_current_user();

$is_company = jobster_user_is_company($current_user->ID);
if ($is_company) {
    $company_id = jobster_get_company_by_userid($current_user->ID);
} else {
    wp_redirect(home_url());
}

get_header('dashboard', array('bg_color' => 'pxpMainColorLight'));

$this_url = jobster_get_page_link('company-dashboard-inbox.php');

$id_get =   isset($_GET['id'])
            ? sanitize_text_field($_GET['id'])
            : '';
$keywords = isset($_GET['keywords'])
            ? stripslashes(sanitize_text_field($_GET['keywords']))
            : '';

$details_panel_class = '';
if ($id_get != '') {
    jobster_update_inbox_message_status($company_id, $id_get);
    $details_panel_class = 'position-relative';
}

jobster_get_company_dashboard_side($company_id, 'inbox'); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_company_dashboard_top($company_id); ?>

    <div class="pxp-dashboard-content-details <?php echo esc_attr($details_panel_class); ?>">
        <h1><?php esc_html_e('Inbox', 'jobster'); ?></h1>
        <p class="pxp-text-light">
            <?php esc_html_e('Keep in touch with your candidates.', 'jobster'); ?>
        </p>

        <div class="row mt-4 mt-lg-5">
            <div class="col-xxl-4">
                <div class="pxp-dashboard-inbox-search-form">
                    <form 
                        role="search" 
                        method="get" 
                        action="<?php echo esc_url($this_url); ?>"
                    >
                        <div class="input-group">
                            <span class="input-group-text">
                                <span class="fa fa-search"></span>
                            </span>
                            <input 
                                type="text" 
                                name="keywords" 
                                id="keywords" 
                                class="form-control" 
                                value="<?php echo esc_attr($keywords); ?>" 
                                placeholder="<?php esc_attr_e('Search messages...', 'jobster'); ?>"
                            >
                        </div>
                    </form>
                </div>

                <div class="mt-3 mt-lg-4 pxp-dashboard-inbox-list">
                    <ul class="list-unstyled">
                        <?php $conversations = array();

                        if ($keywords != '') {
                            $messages_args = array(
                                'post_id' => $company_id
                            );
                            $messages = get_comments($messages_args);

                            if (is_array($messages) 
                                && count($messages) > 0) {
                                foreach($messages as $message) {
                                    $candidate_id = get_comment_meta(
                                        $message->comment_ID,
                                        'candidate_id',
                                        true
                                    );

                                    $candidate = get_post($candidate_id);

                                    if (isset($candidate)) {
                                        $candidate_name = get_the_title($candidate_id);

                                        if (stripos($candidate_name, $keywords) !== false) {
                                            $conversations[$candidate_id][] = $message->comment_ID;
                                        }
                                    }
                                }

                                if (count($conversations) == 0) {
                                    $messages_byc_args = array(
                                        'post_id' => $company_id,
                                        'search' => $keywords
                                    );
                                    $messages_byc = get_comments($messages_byc_args);

                                    if (is_array($messages_byc) 
                                        && count($messages_byc) > 0) {
                                        foreach($messages_byc as $message_byc) {
                                            $byc_candidate_id = get_comment_meta(
                                                $message_byc->comment_ID,
                                                'candidate_id',
                                                true
                                            );

                                            $byc_candidate = get_post($byc_candidate_id);

                                            if (isset($byc_candidate)) {
                                                $conversations[$byc_candidate_id][] = $message_byc->comment_ID;
                                            }
                                        }
                                    } else { ?>
                                        <li>
                                            <?php esc_html_e('No messages found.', 'jobster'); ?>
                                        </li>
                                    <?php }
                                }
                            } else { ?>
                                <li>
                                    <?php esc_html_e('No messages found.', 'jobster'); ?>
                                </li>
                            <?php }

                        } else {
                            $messages_args = array(
                                'post_id' => $company_id
                            );
                            $messages = get_comments($messages_args);

                            if (is_array($messages) 
                                && count($messages) > 0) {
                                foreach($messages as $message) {
                                    $candidate_id = get_comment_meta(
                                        $message->comment_ID,
                                        'candidate_id',
                                        true
                                    );

                                    $candidate = get_post($candidate_id);

                                    if (isset($candidate)) {
                                        $conversations[$candidate_id][] = $message->comment_ID;
                                    }
                                }
                            } else { ?>
                                <li>
                                    <?php esc_html_e('Your inbox is empty.', 'jobster'); ?>
                                </li>
                            <?php }
                        }

                        foreach ($conversations as $conv_key => $conv_value) {
                            $photo_val = get_post_meta(
                                $conv_key,
                                'candidate_photo',
                                true
                            );
                            $photo = wp_get_attachment_image_src(
                                        $photo_val, 'pxp-thmb'
                                    );
                            $name = get_the_title($conv_key);

                            if (count($conv_value) > 0) {
                                $last_message = get_comment($conv_value[0]);
                                $last_message_excerpt = get_comment_excerpt($conv_value[0]);

                                $count_unread_messages = 0;
                                foreach ($conv_value as $conv_value_id) {
                                    $read = get_comment_meta(
                                        $conv_value_id,
                                        'read',
                                        true
                                    );

                                    if (empty($read)) {
                                        $count_unread_messages++;
                                    }
                                }

                                if ($keywords != '') {
                                    $messages_link = add_query_arg(
                                        array (
                                            'id' => $conv_key,
                                            'keywords' => $keywords
                                        ),
                                        $this_url
                                    );
                                } else {
                                    $messages_link = add_query_arg(
                                        'id',
                                        $conv_key,
                                        $this_url
                                    ); 
                                }

                                $selected_class =   $id_get == $conv_key
                                                    ? 'pxp-active'
                                                    : ''; ?>

                                <li class="<?php echo esc_attr($selected_class); ?>">
                                    <a 
                                        href="<?php echo esc_url($messages_link); ?>" 
                                        class="pxp-dashboard-inbox-list-item"
                                    >
                                        <div class="pxp-dashboard-inbox-list-item-top">
                                            <div class="pxp-dashboard-inbox-list-item-left">
                                                <?php if (is_array($photo)) { ?>
                                                    <div 
                                                        class="pxp-dashboard-inbox-list-item-avatar pxp-cover" 
                                                        style="background-image: url(<?php echo esc_url($photo[0]); ?>);"
                                                    ></div>
                                                <?php } else { ?>
                                                    <div class="pxp-dashboard-inbox-list-item-avatar pxp-no-img">
                                                        <?php echo esc_html($name[0]); ?>
                                                    </div>
                                                <?php } ?>
                                                <div class="pxp-dashboard-inbox-list-item-name ms-2">
                                                    <?php echo esc_html($name); ?>
                                                </div>
                                            </div>
                                            <div class="pxp-dashboard-inbox-list-item-right">
                                                <div class="pxp-dashboard-inbox-list-item-time">
                                                    <?php $today = date("Y-m-d");
                                                    $date = get_comment_date("Y-m-d", $conv_value[0]);
                                                    $time = date("H:i", strtotime($last_message->comment_date));

                                                    if ($date == $today) {
                                                        echo esc_html($time);
                                                    } else {
                                                        echo esc_html($date);
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 d-flex justify-content-between">
                                            <div class="pxp-dashboard-inbox-list-item-text pxp-text-light">
                                                <?php echo esc_html($last_message_excerpt); ?>
                                            </div>
                                            <div class="pxp-dashboard-inbox-list-item-no ms-3">
                                                <?php if ($count_unread_messages > 0) { ?>
                                                    <span class="badge rounded-pill">
                                                        <?php echo esc_html($count_unread_messages); ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php }
                        } ?>
                    </ul>
                </div>
            </div>

            <div class="col-xxl-8">
                <?php $show_for_mobile =    $id_get == ''
                                            ? ''
                                            : 'pxp-show'; ?>
                <div class="pxp-dashboard-inbox-messages-container <?php echo esc_attr($show_for_mobile); ?>">
                    <?php if ($id_get != '') {
                        $candidate_photo_val = get_post_meta(
                            $id_get,
                            'candidate_photo',
                            true
                        );
                        $candidate_photo = wp_get_attachment_image_src(
                            $candidate_photo_val,
                            'pxp-thmb'
                        );
                        $candidate_name = get_the_title($id_get);
                        $candidate_email = get_post_meta(
                            $id_get,
                            'candidate_email',
                            true
                        );

                        $company_logo_val = get_post_meta(
                            $company_id,
                            'company_logo',
                            true
                        );
                        $company_logo = wp_get_attachment_image_src(
                            $company_logo_val,
                            'pxp-thmb'
                        );
                        $company_name = get_the_title($company_id);
                        $company_email = get_post_meta(
                            $company_id,
                            'company_email',
                            true
                        ); ?>

                        <div class="pxp-dashboard-inbox-messages-header">
                            <div class="pxp-dashboard-inbox-list-item-left">
                                <?php if (is_array($candidate_photo)) { ?>
                                    <a 
                                        href="<?php echo esc_url(get_permalink($id_get)); ?>" 
                                        class="pxp-dashboard-inbox-list-item-avatar pxp-cover" 
                                        style="background-image: url(<?php echo esc_url($candidate_photo[0]); ?>);"
                                    ></a>
                                <?php } else { ?>
                                    <a 
                                        href="<?php echo esc_url(get_permalink($id_get)); ?>" 
                                        class="pxp-dashboard-inbox-list-item-avatar pxp-no-img"
                                    >
                                        <?php echo esc_html($candidate_name[0]); ?>
                                    </a>
                                <?php } ?>
                                <a 
                                    href="<?php echo esc_url(get_permalink($id_get)); ?>" 
                                    class="pxp-dashboard-inbox-list-item-name ms-2"
                                >
                                    <?php echo esc_html($candidate_name); ?>
                                </a>
                            </div>
                            <div class="d-flex align-items-center">
                                <button 
                                    type="button" 
                                    class="btn-close pxp-dashboard-inbox-messages-close-btn d-inline-block d-xxl-none" 
                                    aria-label="<?php echo esc_attr('Close', 'jobster'); ?>"
                                ></button>
                            </div>
                        </div>

                        <div class="pxp-dashboard-inbox-messages-content">
                            <?php $all_messages_args = array(
                                'post_in' => array($company_id, $id_get),
                                'meta_query' => array(
                                    'relation' => 'AND',
                                    array(
                                        'key' => 'candidate_id',
                                        'value' => $id_get
                                    ),
                                    array(
                                        'key' => 'company_id',
                                        'value' => $company_id
                                    )
                                ),
                                'order' => 'ASC'
                            );

                            $all_messages = get_comments($all_messages_args);

                            $count_messages = 0;
                            foreach ($all_messages as $single_message) {
                                $message_type = get_post_type($single_message->comment_post_ID);

                                $item_align_class = 'justify-content-end';
                                $item_order_class = 'flex-row-reverse';
                                $item_name_margin_class = 'me-2';
                                $item_time_margin_class = 'me-3';
                                $item_message_class = 'pxp-is-self';
                                if ($message_type == 'company') {
                                    $item_align_class = '';
                                    $item_order_class = '';
                                    $item_name_margin_class = 'ms-2';
                                    $item_time_margin_class = 'ms-3';
                                    $item_message_class = 'pxp-is-other';
                                }

                                $item_margin_class =    $count_messages == 0
                                                        ? ''
                                                        : 'mt-4'; ?>
                                <div class="pxp-dashboard-inbox-messages-item <?php echo esc_attr($item_margin_class); ?>">
                                    <div class="row <?php echo esc_attr($item_align_class); ?>">
                                        <div class="col-7">
                                            <div class="pxp-dashboard-inbox-messages-item-header <?php echo esc_attr($item_order_class); ?>">
                                                <?php if ($message_type == 'company') {
                                                    if (is_array($candidate_photo)) { ?>
                                                        <div 
                                                            class="pxp-dashboard-inbox-messages-item-avatar pxp-cover" 
                                                            style="background-image: url(<?php echo esc_url($candidate_photo[0]); ?>);"
                                                        ></div>
                                                    <?php } else { ?>
                                                        <div class="pxp-dashboard-inbox-messages-item-avatar pxp-no-img">
                                                            <?php echo esc_html($candidate_name[0]); ?>
                                                        </div>
                                                    <?php }
                                                } else {
                                                    if (is_array($company_logo)) { ?>
                                                        <div 
                                                            class="pxp-dashboard-inbox-messages-item-avatar pxp-cover" 
                                                            style="background-image: url(<?php echo esc_url($company_logo[0]); ?>);"
                                                        ></div>
                                                    <?php } else { ?>
                                                        <div class="pxp-dashboard-inbox-messages-item-avatar pxp-no-img">
                                                            <?php echo esc_html($company_name[0]); ?>
                                                        </div>
                                                    <?php }
                                                } ?>
                                                <div class="pxp-dashboard-inbox-messages-item-name <?php echo esc_attr($item_name_margin_class); ?>">
                                                    <?php if ($message_type == 'company') {
                                                        echo esc_html($candidate_name);
                                                    } else {
                                                        echo esc_html($company_name);
                                                    } ?>
                                                </div>
                                                <div class="pxp-dashboard-inbox-messages-item-time pxp-text-light <?php echo esc_attr($item_time_margin_class); ?>">
                                                    <?php $item_today = date("Y-m-d");
                                                    $item_date = get_comment_date("Y-m-d", $single_message->comment_ID);
                                                    $item_time = date("H:i", strtotime($single_message->comment_date));

                                                    if ($item_date == $item_today) {
                                                        echo esc_html($item_time);
                                                    } else {
                                                        echo esc_html($item_date);
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="pxp-dashboard-inbox-messages-item-message mt-2 <?php echo esc_attr($item_message_class); ?>">
                                                <?php echo esc_html($single_message->comment_content); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $count_messages++;
                            } ?>
                        </div>

                        <div class="pxp-dashboard-inbox-messages-footer">
                            <div class="pxp-dashboard-inbox-messages-footer-field">
                                <input 
                                    type="hidden" 
                                    id="pxp-company-dashboard-inbox-user-id" 
                                    value="<?php echo esc_attr($current_user->ID); ?>"
                                >
                                <input 
                                    type="hidden" 
                                    id="pxp-company-dashboard-inbox-company-id" 
                                    value="<?php echo esc_attr($company_id); ?>"
                                >
                                <input 
                                    type="hidden" 
                                    id="pxp-company-dashboard-inbox-company-name" 
                                    value="<?php echo esc_attr($company_name); ?>"
                                >
                                <input 
                                    type="hidden" 
                                    id="pxp-company-dashboard-inbox-company-email" 
                                    value="<?php echo esc_attr($company_email); ?>"
                                >
                                <input 
                                    type="hidden" 
                                    id="pxp-company-dashboard-inbox-candidate-id" 
                                    value="<?php echo esc_attr($id_get); ?>"
                                >
                                <input 
                                    type="hidden" 
                                    id="pxp-company-dashboard-inbox-candidate-email" 
                                    value="<?php echo esc_attr($candidate_email); ?>"
                                >
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="pxp-company-dashboard-inbox-message-field" 
                                    placeholder="<?php esc_attr_e('Type your message here...', 'jobster'); ?>"
                                >
                                <div class="d-none pxp-company-dashboard-inbox-avatar-holder">
                                    <?php if (is_array($company_logo)) { ?>
                                        <div 
                                            class="pxp-dashboard-inbox-messages-item-avatar pxp-cover" 
                                            style="background-image: url(<?php echo esc_url($company_logo[0]); ?>);"
                                        ></div>
                                    <?php } else { ?>
                                        <div class="pxp-dashboard-inbox-messages-item-avatar pxp-no-img">
                                            <?php echo esc_html($company_name[0]); ?>
                                        </div>
                                    <?php } ?>
                                    <div class="pxp-dashboard-inbox-messages-item-name me-2">
                                        <?php echo esc_html($company_name); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="pxp-dashboard-inbox-messages-footer-btn">
                                <?php wp_nonce_field(
                                    'contact_candidate_ajax_nonce',
                                    'pxp-company-inbox-security',
                                    true
                                ); ?>
                                <a 
                                    href="javascript:void(0);" 
                                    class="btn rounded-pill pxp-submit-btn pxp-company-dashboard-inbox-send-btn"
                                >
                                    <span class="pxp-company-dashboard-inbox-send-btn-text">
                                        <?php esc_html_e('Send', 'jobster'); ?>
                                    </span>
                                    <span class="pxp-company-dashboard-inbox-send-btn-loading pxp-btn-loading">
                                        <img 
                                            src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                            class="pxp-btn-loader" 
                                            alt="..."
                                        >
                                    </span>
                                </a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="p-4">
                            <?php esc_html_e('No conversation selected', 'jobster'); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php get_footer('dashboard'); ?>
</div>