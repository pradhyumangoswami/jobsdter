<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (post_password_required()) {
    return;
}

global $current_user;
global $post;

$post_type = $post->post_type; ?>

<div class="mt-100">
    <div class="pxp-blog-comments-block">
        <div id="comments" class="comments-area pxp-blog-post-comments">
            <?php if (have_comments()) : 
                $comments_number = absint(get_comments_number()); ?>
                <h4>
                    <?php if ($comments_number === 1) {
                        echo number_format_i18n(get_comments_number())
                            . ' '
                            . esc_html__('Comment', 'jobster'); 
                    } else {
                        echo number_format_i18n(get_comments_number())
                            . ' '
                            . esc_html__('Comments', 'jobster'); 
                    } ?>
                </h4>

                <div class="mt-3 mt-md-4">
                    <ol class="comments-list pxp-comments-list">
                        <?php wp_list_comments(array(
                            'style'      => 'ol',
                            'callback'   => 'jobster_comment',
                            'short_ping' => true,
                        )); ?>
                    </ol>
                </div>

                <?php if (get_comment_pages_count() > 1 
                        && get_option('page_comments')) : ?>
                    <nav 
                        id="comment-nav-below" 
                        class="navigation comment-navigation pagination mt-3 mt-md-4" 
                        role="navigation"
                    >
                        <div class="nav-previous">
                            <?php previous_comments_link(
                                '<span class="fa fa-angle-left"></span> '
                                . esc_html__('Older Comments', 'jobster')
                            ); ?>
                        </div>
                        <div class="nav-next">
                            <?php next_comments_link(
                                esc_html__('Newer Comments', 'jobster')
                                . ' <span class="fa fa-angle-right"></span>'
                            ); ?>
                        </div>
                    </nav>
                <?php endif; ?>

                <?php if (!comments_open()) : ?>
                    <p class="no-comments">
                        <?php esc_html_e('Comments are closed.', 'jobster'); ?>
                    </p>
                <?php endif;
            endif;

            $commenter     = wp_get_current_commenter();
            $req           = get_option('require_name_email');
            $aria_req      = ($req ? " aria-required='true'" : '');
            $required_text = '  ';

            $args = array(
                'id_form' => 'commentform',
                'class_form' => 'comment-form pxp-comments-form mt-3 mt-md-4',
                'id_submit' => 'submit',
                'class_submit' => 'btn rounded-pill pxp-section-cta',
                'title_reply' => esc_html__('Leave a Reply', 'jobster'),
                'title_reply_to' => esc_html__('Leave a Reply to %s', 'jobster'),
                'title_reply_before' => have_comments()
                                        ? '<h4 id="reply-title" class="comment-reply-title mt-4 mt-md-5">'
                                        : '<h4 id="reply-title" class="comment-reply-title">',
                'title_reply_after' => '</h4>',
                'cancel_reply_link' => esc_html__('Cancel Reply', 'jobster'),
                'label_submit' => esc_html__('Post Comment', 'jobster'),
                'comment_notes_before' =>   '<p class="comment-notes">' .
                                                esc_html__('Your email address will not be published. ', 'jobster')
                                                . ($req ? esc_html($required_text) : '')
                                            . '</p>',
                'comment_field' => '<div class="mb-3">
                                        <label for="comment" class="form-label">
                                            ' . esc_html__('Comment', 'jobster') . '
                                        </label>
                                        <textarea 
                                            id="comment" 
                                            class="form-control" 
                                            name="comment" 
                                            rows="5" 
                                            aria-required="true" 
                                            placeholder="' . esc_html__('Write your comment...', 'jobster') . '"
                                        ></textarea>
                                    </div>',
                'fields' => apply_filters(
                                'comment_form_default_fields', 
                                array(
                                    'author' => '
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="mb-3">
                                                    <label for="author" class="form-label">
                                                        ' . esc_html__('Name', 'jobster') . '
                                                    </label>
                                                    <input 
                                                        id="author" 
                                                        name="author" 
                                                        type="text" 
                                                        class="form-control" 
                                                        value="' . esc_attr($commenter['comment_author']) . '" 
                                                        size="30"' . $aria_req . '"
                                                    />
                                                </div>
                                            </div>',
                                    'email' => '
                                            <div class="col-sm-12 col-md-6">
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">
                                                        ' . esc_html__('Email', 'jobster') . '
                                                    </label>
                                                    <input 
                                                        id="email" 
                                                        name="email" 
                                                        type="text" 
                                                        class="form-control" 
                                                        value="' . esc_attr($commenter['comment_author_email']) . '" 
                                                        size="30"' . $aria_req . '"
                                                    />
                                                </div>
                                            </div>
                                        </div>',
                                    'url' => '
                                        <div class="mb-3">
                                            <label for="url" class="form-label">
                                                ' . esc_html__('Website', 'jobster') . '
                                            </label>
                                            <input 
                                                id="url" 
                                                name="url" 
                                                type="text" 
                                                class="form-control" 
                                                value="' . esc_attr($commenter['comment_author_url']) . '" 
                                                size="30"
                                            />
                                        </div>'
                                )
                            )
            );

            comment_form($args); ?>
        </div>
    </div>
</div>
