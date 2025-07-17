(function($) {
    "use strict";

    var max = doc_upload_vars.plupload.max_files;

    if ($('.pxp-dashboard-profile-doc').length > 0) {
        $('body').addClass('no-overflow');
    }

    if (typeof(plupload) !== 'undefined') {
        var uploader = new plupload.Uploader(doc_upload_vars.plupload);

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            var filesNo = 0;

            $.each(files, function(i, file) {
                if (filesNo < max) {
                    $('.pxp-dashboard-upload-doc-status')
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
            $('.pxp-dashboard-upload-doc-btn').hide();
            $('.pxp-dashboard-doc-file').empty();
            $('.pxp-dashboard-doc').addClass('w-100');
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
            $('.pxp-dashboard-upload-doc-status')
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
                $('.pxp-dashboard-doc').html(
                    `<div 
                        class="pxp-dashboard-doc-file" 
                        data-id="${result.attach}" 
                    >
                        ${file.name}
                    </div>`
                );
                $('#pxp-dashboard-doc').val(result.attach);
                $('#pxp-upload-container-doc').addClass('pxp-has-file');
                $('.pxp-company-dashboard-download-doc-btn').attr(
                    'href',
                    result.html
                );
            }
        });

        $('#pxp-uploader-doc').on('click', function(e) {
            uploader.start();
            e.preventDefault();
        });

    }

    $('.pxp-company-dashboard-delete-doc-btn').on('click', function(e) {
        e.preventDefault();
        $('#pxp-upload-container-doc').removeClass('pxp-has-file');
        $('.pxp-dashboard-doc-file').text(doc_upload_vars.no_doc);
        $('.pxp-dashboard-doc-file').attr('data-id', '');
        $('#pxp-dashboard-doc').val('');
        $('.pxp-dashboard-upload-doc-btn').show();
    });
})(jQuery);