<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_welcome')):
    function jobster_admin_welcome() {
        add_settings_section(
            'jobster_welcome_section',
            __('Welcome', 'jobster'),
            'jobster_welcome_section_callback',
            'jobster_welcome_settings'
        );
    }
endif;

if (!function_exists('jobster_welcome_section_callback')):
    function jobster_welcome_section_callback() {
        print '
            <div class="row">
                <div class="col-xs-12 col-sm-2 mb-20">'
                    . __('Theme version', 'jobster') . ': 
                </div>
                <div class="col-xs-12 col-sm-10 mb-20">
                    <strong>' . JOBSTER_VERSION . '</strong>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 mb-20">
                    <a 
                        href="http://pixelprime.co/themes/jobster-wp/documentation/" 
                        class="ep-link" 
                        target="_blank"
                    >
                        <span class="fa fa-file-text-o"></span> '
                        . __('Read the documentation', 'jobster') . '
                    </a>
                </div>
            </div>
        ';
    }
endif;
?>