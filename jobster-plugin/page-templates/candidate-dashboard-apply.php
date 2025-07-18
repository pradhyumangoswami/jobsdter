<?php
/*
Template Name: Candidate Dashboard - Profile
*/

/**
 * @package WordPress
 * @subpackage Jobster
 */

if (is_user_logged_in()) {
    wp_redirect(home_url());
}

$job_id =   isset($_GET['id'])
            ? sanitize_text_field($_GET['id'])
            : '';
$job = get_post($job_id);

if ($job) {
    if ($job->post_type != 'job') {
        wp_redirect(home_url());
    }
} else {
    wp_redirect(home_url());
}

$job_search_link = jobster_get_page_link('job-search.php');

get_header('dashboard', array('bg_color' => 'pxpSecondaryColorLight'));

jobster_get_candidate_dashboard_side_apply(); ?>

<div class="pxp-dashboard-content">
    <?php jobster_get_candidate_dashboard_top_apply(); ?>

    <div class="pxp-dashboard-content-details">
        <div 
            class="alert alert-success pxp-dashboard-section-alert d-none" 
            role="alert" 
            id="pxp-dashboard-anonymous-apply-alert" 
        >
            <h4 class="alert-heading">
                <?php esc_html_e('Success!', 'jobster'); ?>
            </h4>
            <p>
                <?php esc_html_e('You have successfully applied for the job.', 'jobster'); ?>
            </p>
            <a 
                href="<?php echo esc_url($job_search_link); ?>" 
                class="btn pxp-section-cta-o alert-link"
            >
                <?php esc_html_e('Search Jobs', 'jobster'); ?>
                <span class="fa fa-angle-right"></span>
            </a>
        </div>

        <div class="pxp-dashboard-content-details-form">
            <h1>
                <?php esc_html_e('Apply to', 'jobster'); ?> <i>
                <?php echo esc_html($job->post_title); ?></i>
            </h1>
            <p class="pxp-text-light">
                <?php esc_html_e('Fill in your info to submit your application.', 'jobster'); ?>
            </p>

            <form class="pxp-dashboard-form">
                <?php wp_nonce_field(
                    'candidate_apply_ajax_nonce',
                    'pxp-candidate-apply-security',
                    true
                ); ?>

                <input 
                    type="hidden" 
                    id="pxp-candidate-apply-id" 
                    value="<?php echo esc_attr($job_id); ?>"
                >

                <div class="mt-4 mt-lg-5">
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-apply-name" 
                            class="form-label"
                        >
                            <?php esc_html_e('Name', 'jobster'); ?>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-candidate-apply-name" 
                            class="form-control pxp-is-required" 
                            placeholder="<?php esc_html_e('Add your name', 'jobster'); ?>" 
                            required
                        >
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-apply-email" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Email', 'jobster'); ?>
                                </label>
                                <input 
                                    type="email" 
                                    id="pxp-candidate-apply-email" 
                                    class="form-control pxp-is-required" 
                                    placeholder="candidate@email.com" 
                                >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label 
                                    for="pxp-candidate-apply-phone" 
                                    class="form-label"
                                >
                                    <?php esc_html_e('Phone', 'jobster'); ?>
                                </label>
                                <input 
                                    type="tel" 
                                    id="pxp-candidate-apply-phone" 
                                    class="form-control" 
                                    placeholder="(+12) 345 6789" 
                                >
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-apply-message" 
                            class="form-label"
                        >
                            <?php esc_html_e('Message (optional)', 'jobster'); ?>
                        </label>
                        <textarea
                            id="pxp-candidate-apply-message" 
                            class="form-control" 
                            placeholder="<?php esc_html_e('Type your message here...', 'jobster'); ?>" 
                        ></textarea>
                    </div>
                </div>

                <?php $candidates_settings = get_option('jobster_candidates_settings');
                $resume_field = isset($candidates_settings['jobster_candidate_resume_field'])
                                ? $candidates_settings['jobster_candidate_resume_field'] 
                                : 'required';

                if ($resume_field != 'disabled') { ?>
                    <div class="mt-4 mt-lg-5">
                        <h2><?php esc_html_e('Resume', 'jobster'); ?></h2>

                        <div id="pxp-upload-container-cv">
                            <div class="pxp-candidate-dashboard-cv-icon">
                                <span class="fa fa-file-pdf-o"></span>
                            </div>
                            <div class="pxp-dashboard-cv w-100">
                                <div 
                                    class="pxp-dashboard-cv-file" 
                                    data-id=""
                                >
                                    <?php esc_html_e('No resume uploaded.', 'jobster'); ?>
                                </div>
                                <div class="pxp-dashboard-upload-cv-status"></div>
                            </div>
                            <a 
                                role="button" 
                                id="pxp-uploader-cv" 
                                class="btn rounded-pill pxp-subsection-cta pxp-dashboard-upload-cv-btn"
                            >
                                <?php esc_html_e('Upload PDF', 'jobster'); ?>
                            </a>
                            <input 
                                type="hidden" 
                                name="pxp-dashboard-cv" 
                                id="pxp-dashboard-cv" 
                            >
                            <div class="pxp-candidate-dashboard-cv-options">
                                <ul class="list-unstyled">
                                    <li>
                                        <a 
                                            href="#" 
                                            target="_blank" 
                                            class="pxp-candidate-dashboard-download-cv-btn" 
                                            title="<?php esc_html_e('Download', 'jobster'); ?>"
                                        >
                                            <span class="fa fa-download"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <button 
                                            class="pxp-candidate-dashboard-delete-cv-btn" 
                                            title="<?php esc_html_e('Delete', 'jobster'); ?>"
                                        >
                                            <span class="fa fa-trash-o"></span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="mt-4 mt-lg-5">
                    <h2><?php esc_html_e('Additional Files', 'jobster'); ?></h2>

                    <div class="table-responsive">
                        <table class="table align-middle pxp-candidate-dashboard-files-list">
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <input 
                        type="hidden" 
                        id="pxp-candidate-dashboard-files" 
                        name="pxp-candidate-dashboard-files" 
                    >
                    <div class="pxp-candidate-dashboard-file-form mt-3 mt-lg-4 d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label 
                                        for="pxp-candidate-dashboard-file-name" 
                                        class="form-label"
                                    >
                                        <?php esc_html_e('Name', 'jobster'); ?>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="pxp-candidate-dashboard-file-name" 
                                        class="form-control pxp-is-required" 
                                        placeholder="<?php esc_attr_e('Add file name', 'jobster'); ?>"
                                    >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <?php esc_html_e('File upload', 'jobster'); ?>(PDF)
                                    </label>
                                    <div id="pxp-upload-container-file">
                                        <div class="pxp-dashboard-upload-file w-100">
                                            <div class="pxp-dashboard-upload-file-status"></div>
                                        </div>
                                        <a 
                                            role="button" 
                                            id="pxp-uploader-file" 
                                            class="btn rounded-pill pxp-subsection-cta pxp-dashboard-upload-file-btn"
                                        >
                                            <?php esc_html_e('Upload File', 'jobster'); ?>
                                        </a>
                                        <div class="pxp-dashboard-upload-file-placeholder d-inline-block ms-3"></div>
                                        <input 
                                            type="hidden" 
                                            id="pxp-candidate-dashboard-file-id"
                                        >
                                        <input 
                                            type="hidden" 
                                            id="pxp-candidate-dashboard-file-url"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a 
                            href="javascript:void(0);" 
                            class="btn rounded-pill pxp-subsection-cta pxp-candidate-dashboard-ok-file-btn"
                        >
                            <?php esc_html_e('Add', 'jobster'); ?>
                        </a>
                        <a 
                            href="javascript:void(0);" 
                            class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-candidate-dashboard-cancel-file-btn"
                        >
                            <?php esc_html_e('Cancel', 'jobster'); ?>
                        </a>
                    </div>
                    <a 
                        href="javascript:void(0);" 
                        class="btn mt-3 mt-lg-4 rounded-pill pxp-subsection-cta pxp-candidate-dashboard-add-file-btn"
                    >
                        <?php esc_html_e('Add File', 'jobster'); ?>
                    </a>
                </div>

                <div class="mt-4 mt-lg-5">
                    <div class="pxp-candidate-apply-response"></div>
                    <a 
                        href="javascript:void(0);" 
                        class="btn rounded-pill pxp-submit-btn pxp-candidate-apply-btn"
                    >
                        <span class="pxp-candidate-apply-btn-text">
                            <?php esc_html_e('Apply', 'jobster'); ?>
                        </span>
                        <span class="pxp-candidate-apply-btn-loading pxp-btn-loading">
                            <img 
                                src="<?php echo esc_url(JOBSTER_LOCATION . '/images/loader-light.svg'); ?>" 
                                class="pxp-btn-loader" 
                                alt="..."
                            >
                        </span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <?php get_footer('dashboard'); ?>
</div>