(function($) {
    "use strict";

    var max = file_upload_vars.plupload.max_files;

    if ($('.pxp-dashboard-profile-file').length > 0) {
        $('body').addClass('no-overflow');
    }

    if (typeof(plupload) !== 'undefined') {
        file_upload_vars.plupload.multi_selection = false;

        var uploader = new plupload.Uploader(file_upload_vars.plupload);

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            var filesNo = 0;

            $.each(files, function(i, file) {
                if (filesNo < max) {
                    $('.pxp-dashboard-upload-file-status')
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
            $('.pxp-dashboard-upload-file-placeholder').empty();
            $('#' + file.id)
            .addClass('mb-3')
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
            $('.pxp-dashboard-upload-file-status')
            .append(
                `<div>
                    Error: ${err.code}, Message: ${err.message}${err.file ? `, File: ${err.file.name}` : ``}
                </div>`
            );

            up.refresh();
        });

        uploader.bind('FileUploaded', function(up, file, response) {
            var result = $.parseJSON(response.response);

            if (result.success) {
                $('.pxp-dashboard-upload-file-placeholder').html(file.name);
                $('#pxp-candidate-dashboard-file-id').val(result.attach);
                $('#pxp-candidate-dashboard-file-url').val(result.html);
                $('.pxp-dashboard-upload-file-status').empty();
            }
        });

        $('#pxp-uploader-file').on('click', function(e) {
            uploader.start();
            e.preventDefault();
        });

    }
})(jQuery);