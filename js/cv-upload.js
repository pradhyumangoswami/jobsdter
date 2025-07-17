(function($) {
    "use strict";

    var max = cv_upload_vars.plupload.max_files;

    if ($('.pxp-dashboard-profile-cv').length > 0) {
        $('body').addClass('no-overflow');
    }

    if (typeof(plupload) !== 'undefined') {
        cv_upload_vars.plupload.multi_selection = false;

        var uploader = new plupload.Uploader(cv_upload_vars.plupload);

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            var filesNo = 0;

            $.each(files, function(i, file) {
                if (filesNo < max) {
                    $('.pxp-dashboard-upload-cv-status')
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
            $('.pxp-dashboard-upload-cv-btn').hide();
            $('.pxp-dashboard-cv-file').empty();
            $('.pxp-dashboard-cv').addClass('w-100');
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
            $('.pxp-dashboard-upload-cv-status')
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
                $('.pxp-dashboard-cv').html(
                    `<div 
                        class="pxp-dashboard-cv-file" 
                        data-id="${result.attach}" 
                    >
                        ${file.name}
                    </div>`
                );
                $('#pxp-dashboard-cv').val(result.attach);
                $('#pxp-upload-container-cv').addClass('pxp-has-file');
                $('.pxp-candidate-dashboard-download-cv-btn').attr(
                    'href',
                    result.html
                );
            }
        });

        $('#pxp-uploader-cv').on('click', function(e) {
            uploader.start();
            e.preventDefault();
        });

    }

    $('.pxp-candidate-dashboard-delete-cv-btn').on('click', function(e) {
        e.preventDefault();
        $('#pxp-upload-container-cv').removeClass('pxp-has-file');
        $('.pxp-dashboard-cv-file').text(cv_upload_vars.no_cv);
        $('.pxp-dashboard-cv-file').attr('data-id', '');
        $('#pxp-dashboard-cv').val('');
        $('.pxp-dashboard-upload-cv-btn').show();
    });
})(jQuery);