(function($) {
    "use strict";

    var max = 10;
    if ($('#pxp-candidate-profile-gallery').length > 0) {
        max = gallery_upload_vars.plupload.max_files;
    }
    if ($('#pxp-company-profile-gallery').length > 0) {
        max = gallery_upload_vars.plupload.max_files_co;
    }

    var gallery = [];

    if ($('.pxp-profile-gallery').length > 0) {
        $('body').addClass('no-overflow');

        if ($('.pxp-profile-gallery .pxp-profile-gallery-photo').length >= max) {
            $('#aaiu-uploader-gallery').hide();
        }
    }

    $(window).on('load', function() {
        $('.pxp-profile-gallery').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            start: function(event, ui) {
                $('.pxp-profile-gallery .pxp-profile-gallery-photo').removeClass('has-animation');
            },
            stop: function(event, ui) {
                gallery = [];
    
                $('.pxp-profile-gallery .pxp-profile-gallery-photo').addClass('has-animation');
                $('.pxp-profile-gallery .pxp-profile-gallery-photo').each(function(index, el) {
                    gallery.push(parseInt($(this).attr('data-id')));
                });
                $('#pxp-profile-gallery-field').val(gallery.toString());
            }
        }).disableSelection();
    });

    if (typeof(plupload) !== 'undefined') {
        var uploader = new plupload.Uploader(gallery_upload_vars.plupload);

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            var filesNo = 0;

            $.each(files, function(i, file) {
                if (filesNo < max) {
                    $('.pxp-profile-upload-gallery-status').append(
                        `<div id="${file.id}" class="pxp-profile-upload-progress"></div>`
                    );
                }

                filesNo = filesNo + 1;
            });

            up.refresh();
            uploader.start();
        });

        uploader.bind('UploadProgress', function(up, file) {
            $(`#${file.id}`).addClass('pxp-is-active').html(
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
            const filename = err.file ? `, File: ${err.file.name}` : ``;

            $('.pxp-dashboard-upload-gallery-status').append(
                `<div>
                    Error: ${err.code}, Message: ${err.message} ${filename}
                </div>`
            );

            up.refresh();
        });

        uploader.bind('FileUploaded', function(up, file, response) {
            var result = $.parseJSON(response.response);

            $(`#${file.id}`).remove();

            if (result.success) {
                if ($('.pxp-profile-gallery .pxp-profile-gallery-photo').length < max) {
                    gallery = [];

                    $('.pxp-profile-gallery').append(
                        `<div 
                            class="pxp-profile-gallery-photo has-animation" 
                            data-id="${result.attach}" 
                            style="background-image: url(${result.html})"
                        >
                            <button class="pxp-profile-gallery-delete-photo">
                                <span class="fa fa-trash-o"></span>
                            </button>
                        </div>`
                    );
                    $('.pxp-profile-gallery .pxp-profile-gallery-photo').each(function(index, el) {
                        gallery.push(parseInt($(this).attr('data-id')));
                    });
                    $('#pxp-profile-gallery-field').val(gallery.toString());

                    if ($('.pxp-profile-gallery .pxp-profile-gallery-photo').length >= max) {
                        $('#aaiu-uploader-gallery').hide();
                    }
                } else {
                    $('#aaiu-uploader-gallery').hide();
                }
            }
        });

        $('#aaiu-uploader-gallery').click(function(e) {
            uploader.start();
            e.preventDefault();
        });
    }

    $('.pxp-profile-gallery').on('click', '.pxp-profile-gallery-delete-photo', function(e) {
        e.preventDefault();

        var _self = $(this);
        var _parent = _self.parent();
        var source = $(this).attr('data-source');
        var request_data;

        _self
            .find('.fa')
            .removeClass('fa-trash-o')
            .addClass('fa-circle-o-notch fa-spin disabled');
        _parent.css('opacity', '0.5');

        if (source == 'company') {
            request_data = {
                'action'  : 'jobster_delete_company_profile_gallery_photo',
                'photo_id': _parent.attr('data-id'),
                'security': $('#pxp-company-profile-security').val()
            };
        }
        if (source == 'candidate') {
            request_data = {
                'action'  : 'jobster_delete_candidate_profile_gallery_photo',
                'photo_id': _parent.attr('data-id'),
                'security': $('#pxp-candidate-profile-security').val()
            };
        }

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: gallery_upload_vars.ajaxurl,
            data: request_data,
            success: function(data) {
                _self
                    .find('.fa')
                    .removeClass('fa-circle-o-notch fa-spin disabled')
                    .addClass('fa-trash-o');

                if (data.delete === true) {
                    gallery = [];

                    _parent
                        .removeClass('has-animation')
                        .fadeOut('slow', function() {
                            $(this).remove();

                            $('.pxp-profile-gallery .pxp-profile-gallery-photo').each(function(index, el) {
                                gallery.push(parseInt($(this).attr('data-id')));
                            });
                            $('#pxp-profile-gallery-field').val(gallery.toString());

                            if ($('.pxp-profile-gallery .pxp-profile-gallery-photo').length < max) {
                                $('#aaiu-uploader-gallery').show();
                            }
                        });
                }
            },
            error: function(errorThrown) {}
        });
    });
})(jQuery);
