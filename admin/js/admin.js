(function($) {
    "use strict";

    function hex2rgb(hex) {
        return ['0x' + hex[1] + hex[2] | 0, '0x' + hex[3] + hex[4] | 0, '0x' + hex[5] + hex[6] | 0];
    }

    $('.pxp-hex-color').wpColorPicker({
        change: function (event, ui) {
            var rgb = hex2rgb(ui.color.toString());
            var rgba = 'rgba(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ',0.05)';

            $('.pxp-rgba-color').val(rgba);
        },
        clear: function (event, ui) {
            $('.pxp-rgba-color').val('');
        }
    });

    // Manage theme setup options
    $('input[name=pxp_demo_version]').change(function() {
        $('.pxp-theme-setup-btn').hide();

        if ($(this).is(':checked')) {
            $(this).parent().next('.pxp-theme-setup-btn').show();
        } else {
            $(this).parent().next('.pxp-theme-setup-btn').hide();
        }
    });

    var setup_msgs;
    var setup_success;
    var demo_version;

    $('.pxp-theme-setup-btn > input').click(function() {
        var _this  = $(this);
        var btn    = $(this).parent();

        demo_version = _this.attr('data-demo');
        setup_msgs   = btn.next('ul');
        setup_success = btn.next('ul').next('.pxp-theme-setup-done');

        btn.hide();
        setup_msgs.find('.pxp-theme-setup-permalinks-msg').show();

        $('input[name=pxp-demo_version]').attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'jobster_setup_permalinks',
                'security' : $('#pxp-theme-setup-security').val()
            },
            success: function(data) {
                if (data.setup == true) {
                    setup_msgs.find('.pxp-theme-setup-permalinks-msg .fa-spinner').hide();
                    setup_msgs.find('.pxp-theme-setup-permalinks-msg .fa-check').show();
                    setup_msgs.find('.pxp-theme-setup-permalinks-msg .msg-done').show();

                    setupReadingPages();
                }
            }
        });
    });

    function setupReadingPages() {
        setup_msgs.find('.pxp-theme-setup-homepage-msg').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'jobster_setup_reading_pages',
                'security' : $('#pxp-theme-setup-security').val()
            },
            success: function(data) {
                if (data.setup == true) {
                    setup_msgs.find('.pxp-theme-setup-homepage-msg .fa-spinner').hide();
                    setup_msgs.find('.pxp-theme-setup-homepage-msg .fa-check').show();
                    setup_msgs.find('.pxp-theme-setup-homepage-msg .msg-done').show();

                    setupMenu();
                }
            }
        });
    }

    function setupMenu() {
        setup_msgs.find('.pxp-theme-setup-menu-msg').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'jobster_setup_menu',
                'security' : $('#pxp-theme-setup-security').val()
            },
            success: function(data) {
                if (data.setup == true) {
                    setup_msgs.find('.pxp-theme-setup-menu-msg .fa-spinner').hide();
                    setup_msgs.find('.pxp-theme-setup-menu-msg .fa-check').show();
                    setup_msgs.find('.pxp-theme-setup-menu-msg .msg-done').show();

                    setupWidgets();
                }
            }
        });
    }

    function setupWidgets() {
        setup_msgs.find('.pxp-theme-setup-widgets-msg').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'jobster_setup_widgets',
                'demo'     : demo_version,
                'security' : $('#pxp-theme-setup-security').val()
            },
            success: function(data) {
                if (data.setup == true) {
                    setup_msgs.find('.pxp-theme-setup-widgets-msg .fa-spinner').hide();
                    setup_msgs.find('.pxp-theme-setup-widgets-msg .fa-check').show();
                    setup_msgs.find('.pxp-theme-setup-widgets-msg .msg-done').show();

                    setupOptions();
                }
            }
        });
    }

    function setupOptions() {
        setup_msgs.find('.pxp-theme-setup-options-msg').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'jobster_setup_options',
                'demo'     : demo_version,
                'security' : $('#pxp-theme-setup-security').val()
            },
            success: function(data) {
                if (data.setup == true) {
                    setup_msgs.find('.pxp-theme-setup-options-msg .fa-spinner').hide();
                    setup_msgs.find('.pxp-theme-setup-options-msg .fa-check').show();
                    setup_msgs.find('.pxp-theme-setup-options-msg .msg-done').show();
                    setup_success.show();
                }
            }
        });
    }

    // Manage set free submissions number for all companies
    $('.pxp-set-free-submissions-btn').on('click', function() {
        $(this).addClass('disabled');
        $('.pxp-set-free-submissions-btn-text').hide();
        $('.pxp-set-free-submissions-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'  : 'jobster_set_free_submisstions_for_all_companies',
                'security': $('#pxp-set-free-submissions-security').val()
            },
            success: function(data) {
                $('.pxp-set-free-submissions-btn').removeClass('disabled');
                $('.pxp-set-free-submissions-btn-loading').hide();
                $('.pxp-set-free-submissions-btn-text').show();

                if (data.set === true) {
                    $('.pxp-free-submissions-response').html(
                        `<span class="pxp-success">
                            ${data.message}
                        </span>`
                    );
                } else {
                    $('.pxp-free-submissions-response').html(
                        `<span class="pxp-error">
                            ${data.message}
                        </span>`
                    );
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Manage set free featured submissions number for all companies
    $('.pxp-set-free-featured-submissions-btn').on('click', function() {
        $(this).addClass('disabled');
        $('.pxp-set-free-featured-submissions-btn-text').hide();
        $('.pxp-set-free-featured-submissions-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'  : 'jobster_set_free_featured_submisstions_for_all_companies',
                'security': $('#pxp-set-free-featured-submissions-security').val()
            },
            success: function(data) {
                $('.pxp-set-free-featured-submissions-btn').removeClass('disabled');
                $('.pxp-set-free-featured-submissions-btn-loading').hide();
                $('.pxp-set-free-featured-submissions-btn-text').show();

                if (data.set === true) {
                    $('.pxp-free-featured-submissions-response').html(
                        `<span class="pxp-success">
                            ${data.message}
                        </span>`
                    );
                } else {
                    $('.pxp-free-featured-submissions-response').html(
                        `<span class="pxp-error">
                            ${data.message}
                        </span>`
                    );
                }
            },
            error: function(errorThrown) {}
        });
    });

    $(`select[name="jobster_membership_settings[jobster_payment_type_field]"],
        select[name="jobster_membership_settings[jobster_payment_system_field]"]
    `).on('change', function() {
        updateMembershipSettingsFields();
    });

    updateMembershipSettingsFields();

    function updateMembershipSettingsFields() {
        switch ($('select[name="jobster_membership_settings[jobster_payment_type_field]"]').val()) {
            case 'disabled':
                $('.pxp-membership-settings-field-all').hide();
                break;
            case 'listing':
                $('.pxp-membership-settings-field-all').hide();
                $('.pxp-membership-settings-field-currency').show();
                $('.pxp-membership-settings-field-per').show();
                $('.pxp-membership-settings-field-system').show();

                var system = $('select[name="jobster_membership_settings[jobster_payment_system_field]"]').val();
                if (system == 'paypal') {
                    $('.pxp-membership-settings-field-paypal').show();
                } else if (system == 'stripe') {
                    $('.pxp-membership-settings-field-stripe').show();
                }
                break;
            case 'plan':
                $('.pxp-membership-settings-field-all').hide();
                $('.pxp-membership-settings-field-currency').show();
                $('.pxp-membership-settings-field-system').show();

                var system = $('select[name="jobster_membership_settings[jobster_payment_system_field]"]').val();
                if (system == 'paypal') {
                    $('.pxp-membership-settings-field-paypal').show();
                } else if (system == 'stripe') {
                    $('.pxp-membership-settings-field-stripe').show();
                }
                break;
            default:
                $('.pxp-membership-settings-field-all').hide();
                break;
        }
    }

    $(`select[name="jobster_apis_settings[jobster_api_field]"],
        select[name="jobster_apis_settings[jobster_api_field]"]
    `).on('change', function() {
        updateAPIsSettingsFields();
    });

    updateAPIsSettingsFields();

    function updateAPIsSettingsFields() {
        switch ($('select[name="jobster_apis_settings[jobster_api_field]"]').val()) {
            case 'none':
                $('.pxp-apis-settings-field-all').hide();
                break;
            case 'careerjet':
                $('.pxp-apis-settings-field-all').hide();
                $('.pxp-apis-settings-field-careerjet').show();
                break;
            default:
                $('.pxp-apis-settings-field-all').hide();
                break;
        }
    }

    // Approve pending user registration
    $('.pxp-approve-btn').on('click', function(e) {
        e.stopPropagation();
        var _self = $(this);
        _self.attr('disabled', 'disabled');
        _self.find('.fa-check').hide();
        _self.find('.fa-spinner').css('display', 'inline-block');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'  : 'jobster_approve_pending_user',
                'email'   : _self.parent().parent().attr('data-key'),
                'security': $('#pxp-pending-users-security').val()
            },
            success: function(data) {
                if (data.approve === true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=users';
                } else {
                    _self.removeAttr('disabled');
                    _self.find('.fa-check').css('display', 'inline-block');
                    _self.find('.fa-spinner').hide();
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Deny pending user registration
    $('.pxp-deny-btn').on('click', function(e) {
        e.stopPropagation();
        var _self = $(this);
        _self.attr('disabled', 'disabled');
        _self.find('.fa-close').hide();
        _self.find('.fa-spinner').css('display', 'inline-block');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'  : 'jobster_deny_pending_user',
                'email'   : _self.parent().parent().attr('data-key'),
                'security': $('#pxp-pending-users-security').val()
            },
            success: function(data) {
                if (data.deny === true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=users';
                } else {
                    _self.removeAttr('disabled');
                    _self.find('.fa-close').css('display', 'inline-block');
                    _self.find('.fa-spinner').hide();
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Code editors for email templates
    if ($('.pxp-email-app-notify-field').length > 0) {
        var editorSettings =    wp.codeEditor.defaultSettings 
                                ? _.clone( wp.codeEditor.defaultSettings ) 
                                : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2
            }
        );
        var editor = wp.codeEditor.initialize(
            $('.pxp-email-app-notify-field'),
            editorSettings
        );
    }
    if ($('.pxp-email-contact-form-section-field').length > 0) {
        var editorSettings =    wp.codeEditor.defaultSettings 
                                ? _.clone( wp.codeEditor.defaultSettings ) 
                                : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2
            }
        );
        var editor = wp.codeEditor.initialize(
            $('.pxp-email-contact-form-section-field'),
            editorSettings
        );
    }
    if ($('.pxp-email-contact-candidate-field').length > 0) {
        var editorSettings =    wp.codeEditor.defaultSettings 
                                ? _.clone( wp.codeEditor.defaultSettings ) 
                                : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2
            }
        );
        var editor = wp.codeEditor.initialize(
            $('.pxp-email-contact-candidate-field'),
            editorSettings
        );
    }
    if ($('.pxp-email-contact-company-field').length > 0) {
        var editorSettings =    wp.codeEditor.defaultSettings 
                                ? _.clone( wp.codeEditor.defaultSettings ) 
                                : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2
            }
        );
        var editor = wp.codeEditor.initialize(
            $('.pxp-email-contact-company-field'),
            editorSettings
        );
    }
    if ($('.pxp-signup-notify-admin-field').length > 0) {
        var editorSettings =    wp.codeEditor.defaultSettings 
                                ? _.clone( wp.codeEditor.defaultSettings ) 
                                : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2
            }
        );
        var editor = wp.codeEditor.initialize(
            $('.pxp-signup-notify-admin-field'),
            editorSettings
        );
    }
    if ($('.pxp-signup-notify-user-field').length > 0) {
        var editorSettings =    wp.codeEditor.defaultSettings 
                                ? _.clone( wp.codeEditor.defaultSettings ) 
                                : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2
            }
        );
        var editor = wp.codeEditor.initialize(
            $('.pxp-signup-notify-user-field'),
            editorSettings
        );
    }
    if ($('.pxp-activation-notify-user-field').length > 0) {
        var editorSettings =    wp.codeEditor.defaultSettings 
                                ? _.clone( wp.codeEditor.defaultSettings ) 
                                : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2
            }
        );
        var editor = wp.codeEditor.initialize(
            $('.pxp-activation-notify-user-field'),
            editorSettings
        );
    }
    if ($('.pxp-email-job-alerts-field').length > 0) {
        var editorSettings =    wp.codeEditor.defaultSettings 
                                ? _.clone( wp.codeEditor.defaultSettings ) 
                                : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 2,
                tabSize: 2
            }
        );
        var editor = wp.codeEditor.initialize(
            $('.pxp-email-job-alerts-field'),
            editorSettings
        );
    }

    // Upload image
    $('.pxp-image-placeholder').click(function(event) {
        event.preventDefault();
        var placeholder = $(this);

        var frame = wp.media({
            title: settings_vars.image_title,
            button: {
                text: settings_vars.image_btn
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                placeholder.parent().parent().find('input').val(value.id);
                placeholder.css('background-image', 'url(' + value.url + ')');
                placeholder.parent().addClass('pxp-has-image');
            });
        });

        frame.open();
    });

    // Delete image
    $('.pxp-delete-image').click(function() {
        var delBtn = $(this);

        delBtn.parent().parent().find('input').val('');
        delBtn.parent()
            .find('.pxp-image-placeholder')
            .css(
                'background-image', 
                'url(' + settings_vars.plugin_url + 'post-types/images/logo-placeholder.png)'
            );
        delBtn.parent().removeClass('pxp-has-image');
    });

    // Jobs/Candidate/Company Custom fields
    if ($('#pxp-custom-fields-table').length > 0) {
        $('.pxp-table-field-type').each(function(index, el) {
            if ($(this).val() == 'list_field') {
                $(this).next('input').show();
            }

            $(this).on('change', function() {
                if ($(this).val() == 'list_field') {
                    $(this).next('input').show();
                } else {
                    $(this).next('input').val('').hide();
                }
            });
        });

    }

    $('#jobs_field_type').on('change', function() {
        if ($(this).val() == 'list_field') {
            $('#jobs_field_list_items').show();
            $(this).siblings('.help').show();
        } else {
            $('#jobs_field_list_items').val('').hide();
            $(this).siblings('.help').hide();
        }
    });

    $('#pxp_add_jobs_field_btn').click(function() {
        var _self = $(this);
        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'jobster_add_jobs_fields',
                'name'     : $('#jobs_field_name').val(),
                'label'    : $('#jobs_field_label').val(),
                'type'     : $('#jobs_field_type').val(),
                'list'     : $('#jobs_field_list_items').val(),
                'mandatory': $('#jobs_field_mandatory').val(),
                'position' : $('#jobs_field_position').val(),
                'security' : $('#pxp-jobs-fields-security').val()
            },
            success: function(data) {
                if (data.add == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=jobs_fields';
                } else {
                    _self.removeAttr('disabled');
                    alert(data.message);
                }
            }
        });
    });

    $(document).on('click', '.pxp-del-job-field', function() {
        var _self = $(this);
        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'    : 'jobster_delete_jobs_fields',
                'field_name': _self.attr('data-row'),
                'security'  : $('#pxp-jobs-fields-security').val()
            },
            success: function(data) {
                if (data.delete == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=jobs_fields';
                } else {
                    _self.removeAttr('disabled');
                }
            }
        });
    });

    /* Company custom fields */

    $('#companies_field_type').on('change', function() {
        if ($(this).val() == 'list_field') {
            $('#companies_field_list_items').show();
            $(this).siblings('.help').show();
        } else {
            $('#companies_field_list_items').val('').hide();
            $(this).siblings('.help').hide();
        }
    });

    $('#pxp_add_companies_field_btn').click(function() {
        var _self = $(this);
        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'jobster_add_companies_fields',
                'name'     : $('#companies_field_name').val(),
                'label'    : $('#companies_field_label').val(),
                'type'     : $('#companies_field_type').val(),
                'list'     : $('#companies_field_list_items').val(),
                'mandatory': $('#companies_field_mandatory').val(),
                'position' : $('#companies_field_position').val(),
                'security' : $('#pxp-companies-fields-security').val()
            },
            success: function(data) {
                if (data.add == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=companies_fields';
                } else {
                    _self.removeAttr('disabled');
                    alert(data.message);
                }
            }
        });
    });

    $(document).on('click', '.pxp-del-company-field', function() {
        var _self = $(this);
        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'    : 'jobster_delete_companies_fields',
                'field_name': _self.attr('data-row'),
                'security'  : $('#pxp-companies-fields-security').val()
            },
            success: function(data) {
                if (data.delete == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=companies_fields';
                } else {
                    _self.removeAttr('disabled');
                }
            }
        });
    });

    /* Candidate custom fields */

    $('#candidates_field_type').on('change', function() {
        if ($(this).val() == 'list_field') {
            $('#candidates_field_list_items').show();
            $(this).siblings('.help').show();
        } else {
            $('#candidates_field_list_items').val('').hide();
            $(this).siblings('.help').hide();
        }
    });

    $('#pxp_add_candidates_field_btn').click(function() {
        var _self = $(this);
        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'   : 'jobster_add_candidates_fields',
                'name'     : $('#candidates_field_name').val(),
                'label'    : $('#candidates_field_label').val(),
                'type'     : $('#candidates_field_type').val(),
                'list'     : $('#candidates_field_list_items').val(),
                'mandatory': $('#candidates_field_mandatory').val(),
                'position' : $('#candidates_field_position').val(),
                'security' : $('#pxp-candidates-fields-security').val()
            },
            success: function(data) {
                if (data.add == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=candidates_fields';
                } else {
                    _self.removeAttr('disabled');
                    alert(data.message);
                }
            }
        });
    });

    $(document).on('click', '.pxp-del-candidate-field', function() {
        var _self = $(this);
        _self.attr('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'    : 'jobster_delete_candidates_fields',
                'field_name': _self.attr('data-row'),
                'security'  : $('#pxp-candidates-fields-security').val()
            },
            success: function(data) {
                if (data.delete == true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=candidates_fields';
                } else {
                    _self.removeAttr('disabled');
                }
            }
        });
    });

    // Approve pending job submission
    $('.pxp-approve-job-btn').on('click', function(e) {
        e.stopPropagation();
        var _self = $(this);
        _self.attr('disabled', 'disabled');
        _self.find('.fa-check').hide();
        _self.find('.fa-spinner').css('display', 'inline-block');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'  : 'jobster_approve_pending_job',
                'id'      : _self.parent().parent().attr('data-id'),
                'email'   : _self.parent().parent().attr('data-email'),
                'security': $('#pxp-jobs-pending-security').val()
            },
            success: function(data) {
                if (data.approve === true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=jobs_pending';
                } else {
                    _self.removeAttr('disabled');
                    _self.find('.fa-check').css('display', 'inline-block');
                    _self.find('.fa-spinner').hide();
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Reject pending job submission
    $('.pxp-reject-job-btn').on('click', function(e) {
        e.stopPropagation();
        var _self = $(this);
        _self.attr('disabled', 'disabled');
        _self.find('.fa-close').hide();
        _self.find('.fa-spinner').css('display', 'inline-block');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: settings_vars.ajaxurl,
            data: {
                'action'  : 'jobster_reject_pending_job',
                'id'      : _self.parent().parent().attr('data-id'),
                'email'   : _self.parent().parent().attr('data-email'),
                'security': $('#pxp-jobs-pending-security').val()
            },
            success: function(data) {
                if (data.reject === true) {
                    document.location.href = 'admin.php?page=admin/settings.php&tab=jobs_pending';
                } else {
                    _self.removeAttr('disabled');
                    _self.find('.fa-close').css('display', 'inline-block');
                    _self.find('.fa-spinner').hide();
                }
            },
            error: function(errorThrown) {}
        });
    });
})(jQuery);