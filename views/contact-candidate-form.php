<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_candidate_contact_form')):
    function jobster_get_candidate_contact_form($candidate_id) {
        $candidate_firstname = trim(
            strstr(get_the_title($candidate_id), ' ', true)
        ); ?>

        <h3><?php printf(__('Contact %s ', 'jobster'), esc_html($candidate_firstname)); ?></h3>
        <form class="mt-4">
            <div class="mb-4 pxp-single-candidate-contact-response"></div>
            <input 
                type="hidden" 
                id="pxp-single-candidate-contact-candidate-id" 
                value="<?php echo esc_attr($candidate_id); ?>"
            >
            <div class="mb-3">
                <label 
                    for="pxp-single-candidate-contact-message" 
                    class="form-label"
                >
                    <?php esc_html_e('Message', 'jobster'); ?>
                </label>
                <textarea 
                    class="form-control" 
                    id="pxp-single-candidate-contact-message" 
                    placeholder="<?php esc_attr_e('Type your message here...', 'jobster'); ?>"
                ></textarea>
            </div>

            <?php wp_nonce_field(
                'contact_candidate_ajax_nonce',
                'pxp-single-candidate-contact-security',
                true
            ); ?>
            <a 
                href="javascript:void(0);" 
                class="btn rounded-pill d-block pxp-single-candidate-contact-btn"
            >
                <span class="pxp-single-candidate-contact-btn-text">
                    <?php esc_html_e('Send Message', 'jobster'); ?>
                </span>
                <span class="pxp-single-candidate-contact-btn-loading pxp-btn-loading">
                    <img 
                        src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                        class="pxp-btn-loader" 
                        alt="..."
                    >
                </span>
            </a>
        </form>
    <?php }
endif;