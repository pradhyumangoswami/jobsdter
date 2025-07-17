<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_get_filter_jobs_form')):
    function jobster_get_filter_jobs_form($pos = 'top', $has_margin = false) {
        $type = isset($_GET['type']) 
                ? stripslashes(sanitize_text_field($_GET['type'])) 
                : '';
        $level = isset($_GET['level']) 
                ? stripslashes(sanitize_text_field($_GET['level'])) 
                : '';

        if ($pos == 'top') { 
            $margin_class = '';
            if ($has_margin) {
                $margin_class = 'mt-4 mt-lg-0';
            } ?>
            <div class="pxp-hero-form-filter pxp-has-bg-color <?php echo esc_attr($margin_class); ?>">
                <div class="row justify-content-start">

                    <!-- Type of employment field -->

                    <?php $type_tax = array( 
                        'job_type'
                    );
                    $type_args = array(
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'hide_empty' => false
                    );
                    $type_terms = get_terms(
                        $type_tax,
                        $type_args
                    );

                    if (count($type_terms) > 0) { ?>
                        <div class="col-12 col-sm-auto">
                            <div class="mb-3 mb-lg-0">
                                <select 
                                    name="pxp-jobs-page-type" 
                                    id="pxp-jobs-page-type" 
                                    class="form-select"
                                >
                                    <option value="">
                                        <?php esc_html_e('Type of employment', 'jobster') ?>
                                    </option>
                                    <?php foreach ($type_terms as $type_term) { ?>
                                        <option 
                                            value="<?php echo esc_attr($type_term->term_id); ?>"
                                            <?php selected(
                                                $type_term->term_id,
                                                $type
                                            ); ?>
                                        >
                                            <?php echo esc_html($type_term->name); ?> (<?php echo esc_html(
                                                jobster_filter_form_count_jobs_by_term(
                                                    'job_type',
                                                    $type_term->term_id
                                                )
                                            ); ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Experience level field -->

                    <?php $level_tax = array( 
                        'job_level'
                    );
                    $level_args = array(
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'hide_empty' => false
                    );
                    $level_terms = get_terms(
                        $level_tax,
                        $level_args
                    ); 

                    if (count($level_terms) > 0) { ?>
                        <div class="col-12 col-sm-auto">
                            <div class="mb-3 mb-lg-0">
                                <select 
                                    name="pxp-jobs-page-level" 
                                    id="pxp-jobs-page-level" 
                                    class="form-select"
                                >
                                    <option value="">
                                        <?php esc_html_e('Experience level', 'jobster') ?>
                                    </option>
                                    <?php foreach ($level_terms as $level_term) { ?>
                                        <option 
                                            value="<?php echo esc_attr($level_term->term_id); ?>"
                                            <?php selected(
                                                $level_term->term_id,
                                                $level
                                            ); ?>
                                        >
                                            <?php echo esc_html($level_term->name); ?> (<?php echo esc_html(
                                                jobster_filter_form_count_jobs_by_term(
                                                    'job_level',
                                                    $level_term->term_id
                                                )
                                            ); ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } else { 
            $margin_class = '';
            if ($has_margin) {
                $margin_class = 'mt-3 mt-lg-4';
            } ?>
            <div class="pxp-jobs-list-side-filter <?php echo esc_attr($margin_class); ?>">
                <div class="pxp-list-side-filter-header d-flex d-lg-none">
                    <div class="pxp-list-side-filter-header-label">
                        <?php esc_html_e('Filter Jobs', 'jobster'); ?>
                    </div>
                    <a role="button"><span class="fa fa-sliders"></span></a>
                </div>
                <div class="mt-4 mt-lg-0 d-lg-block pxp-list-side-filter-panel">

                    <!-- Type of employment field -->

                    <?php $type_tax = array( 
                        'job_type'
                    );
                    $type_args = array(
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'hide_empty' => false
                    );
                    $type_terms = get_terms(
                        $type_tax,
                        $type_args
                    ); 

                    $types = explode(',', $type);

                    if (count($type_terms) > 0) { ?>
                        <h3><?php esc_html_e('Type of Employment', 'jobster'); ?></h3>
                        <div class="list-group mt-2 mt-lg-3">
                            <?php $count_types = 0;
                            foreach ($type_terms as $type_term) {
                                $type_label_default_classes = array(
                                    'list-group-item',
                                    'd-flex',
                                    'justify-content-between',
                                    'align-items-center'
                                );
                                if (in_array($type_term->term_id, $types)) {
                                    array_push($type_label_default_classes, 'pxp-checked');
                                }
                                if ($count_types > 0) {
                                    array_push($type_label_default_classes, 'mt-2 mt-lg-3');
                                }
                                $type_label_classes = implode(' ', $type_label_default_classes); ?>

                                <label 
                                    for="pxp-jobs-page-type-<?php echo esc_attr($type_term->term_id); ?>"
                                    class="<?php echo esc_attr($type_label_classes); ?>"
                                >
                                    <span class="d-flex">
                                        <input 
                                            class="form-check-input me-2 pxp-jobs-page-type" 
                                            type="checkbox" 
                                            id="pxp-jobs-page-type-<?php echo esc_attr($type_term->term_id); ?>" 
                                            value="<?php echo esc_attr($type_term->term_id); ?>" 
                                            <?php checked(
                                                in_array(
                                                    $type_term->term_id,
                                                    $types
                                                )
                                            ); ?>
                                        >
                                        <?php echo esc_html($type_term->name); ?>
                                    </span>
                                    <span class="badge rounded-pill">
                                        <?php echo esc_html(
                                            jobster_filter_form_count_jobs_by_term(
                                                'job_type',
                                                $type_term->term_id
                                            )
                                        ); ?>
                                    </span>
                                </label>
                                <?php $count_types++;
                            } ?>
                        </div>
                    <?php } ?>

                    <!-- Level field -->

                    <?php $level_tax = array( 
                        'job_level'
                    );
                    $level_args = array(
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'hide_empty' => false
                    );
                    $level_terms = get_terms(
                        $level_tax,
                        $level_args
                    ); 

                    $levels = explode(',', $level);

                    if (count($level_terms) > 0) { ?>
                        <h3 class="mt-3 mt-lg-4"><?php esc_html_e('Experience Level', 'jobster'); ?></h3>
                        <div class="list-group mt-2 mt-lg-3">
                            <?php $count_levels = 0;
                            foreach ($level_terms as $level_term) {
                                $level_label_default_classes = array(
                                    'list-group-item',
                                    'd-flex',
                                    'justify-content-between',
                                    'align-items-center'
                                );
                                if (in_array($level_term->term_id, $levels)) {
                                    array_push($level_label_default_classes, 'pxp-checked');
                                }
                                if ($count_levels > 0) {
                                    array_push($level_label_default_classes, 'mt-2 mt-lg-3');
                                }
                                $level_label_classes = implode(' ', $level_label_default_classes); ?>

                                <label 
                                    for="pxp-jobs-page-level-<?php echo esc_attr($level_term->term_id); ?>"
                                    class="<?php echo esc_attr($level_label_classes); ?>"
                                >
                                    <span class="d-flex">
                                        <input 
                                            class="form-check-input me-2 pxp-jobs-page-level" 
                                            type="checkbox" 
                                            id="pxp-jobs-page-level-<?php echo esc_attr($level_term->term_id); ?>" 
                                            value="<?php echo esc_attr($level_term->term_id); ?>" 
                                            <?php checked(
                                                in_array(
                                                    $level_term->term_id,
                                                    $levels
                                                )
                                            ); ?>
                                        >
                                        <?php echo esc_html($level_term->name); ?>
                                    </span>
                                    <span class="badge rounded-pill">
                                        <?php echo esc_html(
                                            jobster_filter_form_count_jobs_by_term(
                                                'job_level',
                                                $level_term->term_id
                                            )
                                        ); ?>
                                    </span>
                                </label>
                                <?php $count_levels++;
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php }
    }
endif;

if (!function_exists('jobster_get_careerjet_filter_jobs_form')):
    function jobster_get_careerjet_filter_jobs_form($pos = 'top', $has_margin = false) {
        $type = isset($_GET['type']) 
                ? stripslashes(sanitize_text_field($_GET['type'])) 
                : '';
        $period = isset($_GET['period']) 
                ? stripslashes(sanitize_text_field($_GET['period'])) 
                : '';

        $types = array(
            'p' => __('Permanent job', 'jobster'),
            'c' => __('Contract', 'jobster'),
            't' => __('Temporary', 'jobster'),
            'i' => __('Training', 'jobster'),
            'v' => __('Voluntary', 'jobster')
        );
        $periods = array(
            'f' => __('Full time', 'jobster'),
            'p' => __('Part time', 'jobster')
        );

        if ($pos == 'top') { 
            $margin_class = '';
            if ($has_margin) {
                $margin_class = 'mt-4 mt-lg-0';
            } ?>
            <div class="pxp-hero-form-filter pxp-has-bg-color <?php echo esc_attr($margin_class); ?>">
                <div class="row justify-content-start">
                    <div class="col-12 col-sm-auto">
                        <div class="mb-3 mb-lg-0">
                            <select 
                                name="pxp-careerjet-jobs-page-type" 
                                id="pxp-careerjet-jobs-page-type" 
                                class="form-select"
                            >
                                <option value="">
                                    <?php esc_html_e('Contract type', 'jobster') ?>
                                </option>
                                <?php foreach ($types as $type_k => $type_v) { ?>
                                    <option 
                                        value="<?php echo esc_attr($type_k); ?>"
                                        <?php selected($type_k, $type); ?>
                                    >
                                        <?php echo esc_html($type_v); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-auto">
                        <div class="mb-3 mb-lg-0">
                            <select 
                                name="pxp-careerjet-jobs-page-period" 
                                id="pxp-careerjet-jobs-page-period" 
                                class="form-select"
                            >
                                <option value="">
                                    <?php esc_html_e('Contract period', 'jobster') ?>
                                </option>
                                <?php foreach ($periods as $period_k => $period_v) { ?>
                                    <option 
                                        value="<?php echo esc_attr($period_k); ?>"
                                        <?php selected($period_k, $period); ?>
                                    >
                                        <?php echo esc_html($period_v); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { 
            $margin_class = '';
            if ($has_margin) {
                $margin_class = 'mt-3 mt-lg-4';
            } ?>
            <div class="pxp-jobs-list-side-filter <?php echo esc_attr($margin_class); ?>">
                <div class="pxp-list-side-filter-header d-flex d-lg-none">
                    <div class="pxp-list-side-filter-header-label">
                        <?php esc_html_e('Filter Jobs', 'jobster'); ?>
                    </div>
                    <a role="button"><span class="fa fa-sliders"></span></a>
                </div>
                <div class="mt-4 mt-lg-0 d-lg-block pxp-list-side-filter-panel">
                    <h3><?php esc_html_e('Contract Type', 'jobster'); ?></h3>
                    <div class="list-group mt-2 mt-lg-3">
                        <?php $count_types = 0;
                        foreach ($types as $type_k => $type_v) {
                            $type_label_default_classes = array(
                                'list-group-item',
                                'd-flex',
                                'justify-content-between',
                                'align-items-center'
                            );
                            if ($type_k == $type) {
                                array_push($type_label_default_classes, 'pxp-checked');
                            }
                            if ($count_types > 0) {
                                array_push($type_label_default_classes, 'mt-2 mt-lg-3');
                            }
                            $type_label_classes = implode(' ', $type_label_default_classes); ?>

                            <label 
                                for="pxp-careerjet-jobs-page-type-<?php echo esc_attr($type_k); ?>"
                                class="<?php echo esc_attr($type_label_classes); ?>"
                            >
                                <span class="d-flex">
                                    <input 
                                        class="form-check-input me-2 pxp-careerjet-jobs-page-type" 
                                        type="checkbox" 
                                        id="pxp-careerjet-jobs-page-type-<?php echo esc_attr($type_k); ?>" 
                                        value="<?php echo esc_attr($type_k); ?>" 
                                        <?php checked($type_k, $type); ?>
                                    >
                                    <?php echo esc_html($type_v); ?>
                                </span>
                            </label>
                            <?php $count_types++;
                        } ?>
                    </div>

                    <h3 class="mt-3 mt-lg-4"><?php esc_html_e('Contract Period', 'jobster'); ?></h3>
                    <div class="list-group mt-2 mt-lg-3">
                        <?php $count_periods = 0;
                        foreach ($periods as $period_k => $period_v) {
                            $period_label_default_classes = array(
                                'list-group-item',
                                'd-flex',
                                'justify-content-between',
                                'align-items-center'
                            );
                            if ($period_k == $period) {
                                array_push($period_label_default_classes, 'pxp-checked');
                            }
                            if ($count_periods > 0) {
                                array_push($period_label_default_classes, 'mt-2 mt-lg-3');
                            }
                            $period_label_classes = implode(' ', $period_label_default_classes); ?>

                            <label 
                                for="pxp-careerjet-jobs-page-period-<?php echo esc_attr($period_k); ?>"
                                class="<?php echo esc_attr($period_label_classes); ?>"
                            >
                                <span class="d-flex">
                                    <input 
                                        class="form-check-input me-2 pxp-careerjet-jobs-page-period" 
                                        type="checkbox" 
                                        id="pxp-careerjet-jobs-page-period-<?php echo esc_attr($period_k); ?>" 
                                        value="<?php echo esc_attr($period_k); ?>" 
                                        <?php checked($period_k, $period); ?>
                                    >
                                    <?php echo esc_html($period_v); ?>
                                </span>
                            </label>
                            <?php $count_periods++;
                        } ?>
                    </div>
                </div>
            </div>
        <?php }
    }
endif;
?>