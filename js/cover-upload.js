(function($) {
    "use strict";

    var max = cover_upload_vars.plupload.max_files;

    if ($('.pxp-dashboard-profile-cover').length > 0) {
        $('body').addClass('no-overflow');
    }

    if (typeof(plupload) !== 'undefined') {
        var uploader = new plupload.Uploader(cover_upload_vars.plupload);

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            var filesNo = 0;

            $.each(files, function(i, file) {
                if (filesNo < max) {
                    $('.pxp-dashboard-upload-cover-status')
                    .append(
                        `<div 
                            id="${file.id}" 
                            class="pxp-dashboard-upload-progress"
                        ></div>`
                    );
                }

                filesNo = filesNo + 1;
            });

            up.refresh();
            uploader.start();
        });

        uploader.bind('UploadProgress', function(up, file) {
            $('.pxp-dashboard-upload-cover-btn').empty();
            $('#' + file.id)
            .addClass('pxp-is-active')
            .html(
                `<div class="progress">
                    <div 
                        class="progress-bar" 
                        role="progressbar" 
                        aria-valuenow="${file.percent}" 
                        aria-valuemin="0" 
                        aria-valuemax="100" 
                        style="width: ${file.percent}%"
                    >
                        ${file.percent}%
                    </div>
                </div>`
            );
        });

        // On error occur
        uploader.bind('Error', function(up, err) {
            $('.pxp-dashboard-upload-cover-status')
            .append(
                `<div>
                    Error: ${err.code}, Message: ${err.message}${err.file ? `, File: ${err.file.name}` : ``}
                </div>`
            );

            up.refresh();
        });

        uploader.bind('FileUploaded', function(up, file, response) {
            var result = $.parseJSON(response.response);

            $('#' + file.id).remove();

            if (result.success) {
                $('.pxp-dashboard-cover').html(
                    `<div 
                        class="pxp-dashboard-cover-photo pxp-cover has-animation pxp-no-border" 
                        data-id="${result.attach}" 
                        style="background-image: url(${result.html})"
                    ></div>`
                );
                $('#pxp-upload-container-cover').addClass('pxp-has-image');
                $('#pxp-dashboard-cover').val(result.attach);
            }
        });

        $('#pxp-uploader-cover').click(function(e) {
            uploader.start();
            e.preventDefault();
        });
    }

    $('.pxp-company-profile-cover-delete-btn').on('click', function() {
        var _self = $(this);
        _self
            .find('.fa')
            .removeClass('fa-trash-o')
            .addClass('fa-circle-o-notch fa-spin disabled');


        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: cover_upload_vars.ajaxurl,
            data: {
                'action'    : 'jobster_delete_company_profile_cover',
                'cover_id'  : $('#pxp-dashboard-cover').val(),
                'company_id': $('#pxp-company-profile-id').val(),
                'security'  : $('#pxp-company-profile-security').val()
            },
            success: function(data) {
                _self
                    .find('.fa')
                    .removeClass('fa-circle-o-notch fa-spin disabled')
                    .addClass('fa-trash-o');

                if (data.delete === true) {
                    $('#pxp-upload-container-cover').removeClass('pxp-has-image');
                    $('.pxp-dashboard-cover').html(
                        `<div 
                            class="pxp-dashboard-cover-photo pxp-cover has-animation" 
                            data-id=""
                        ></div>`
                    );
                    $('#pxp-dashboard-cover').val('');
                }
            },
            error: function(errorThrown) {}
        });
    });

    $('.pxp-company-new-job-cover-delete-btn').on('click', function() {
        var _self = $(this);
        _self
            .find('.fa')
            .removeClass('fa-trash-o')
            .addClass('fa-circle-o-notch fa-spin disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: cover_upload_vars.ajaxurl,
            data: {
                'action'  : 'jobster_new_job_delete_cover',
                'cover_id': $('#pxp-dashboard-cover').val(),
                'security': $('#pxp-company-new-job-security').val()
            },
            success: function(data) {
                _self
                    .find('.fa')
                    .removeClass('fa-circle-o-notch fa-spin disabled')
                    .addClass('fa-trash-o');

                if (data.delete === true) {
                    $('#pxp-upload-container-cover').removeClass('pxp-has-image');
                    $('.pxp-dashboard-cover').html(
                        `<div 
                            class="pxp-dashboard-cover-photo pxp-cover has-animation" 
                            data-id=""
                        ></div>`
                    );
                    $('#pxp-dashboard-cover').val('');
                }
            },
            error: function(errorThrown) {}
        });
    });

    $('.pxp-company-edit-job-cover-delete-btn').on('click', function() {
        var _self = $(this);
        _self
            .find('.fa')
            .removeClass('fa-trash-o')
            .addClass('fa-circle-o-notch fa-spin disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: cover_upload_vars.ajaxurl,
            data: {
                'action'  : 'jobster_edit_job_delete_cover',
                'job_id'  : $('#pxp-company-edit-job-id').val(),
                'cover_id': $('#pxp-dashboard-cover').val(),
                'security': $('#pxp-company-edit-job-security').val()
            },
            success: function(data) {
                _self
                    .find('.fa')
                    .removeClass('fa-circle-o-notch fa-spin disabled')
                    .addClass('fa-trash-o');

                if (data.delete === true) {
                    $('#pxp-upload-container-cover').removeClass('pxp-has-image');
                    $('.pxp-dashboard-cover').html(
                        `<div 
                            class="pxp-dashboard-cover-photo pxp-cover has-animation" 
                            data-id=""
                        ></div>`
                    );
                    $('#pxp-dashboard-cover').val('');
                }
            },
            error: function(errorThrown) {}
        });
    });

    $('.pxp-candidate-profile-cover-delete-btn').on('click', function() {
        var _self = $(this);
        _self
            .find('.fa')
            .removeClass('fa-trash-o')
            .addClass('fa-circle-o-notch fa-spin disabled');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: cover_upload_vars.ajaxurl,
            data: {
                'action'      : 'jobster_delete_candidate_profile_cover',
                'cover_id'    : $('#pxp-dashboard-cover').val(),
                'candidate_id': $('#pxp-candidate-profile-id').val(),
                'security'    : $('#pxp-candidate-profile-security').val()
            },
            success: function(data) {
                _self
                    .find('.fa')
                    .removeClass('fa-circle-o-notch fa-spin disabled')
                    .addClass('fa-trash-o');

                if (data.delete === true) {
                    $('#pxp-upload-container-cover').removeClass('pxp-has-image');
                    $('.pxp-dashboard-cover').html(
                        `<div 
                            class="pxp-dashboard-cover-photo pxp-cover has-animation" 
                            data-id=""
                        ></div>`
                    );
                    $('#pxp-dashboard-cover').val('');
                }
            },
            error: function(errorThrown) {}
        });
    });
})(jQuery);