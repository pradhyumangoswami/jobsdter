(function($) {
    "use strict";

    function fixedEncodeURIComponent(str) {
        return encodeURIComponent(str).replace(/[!'()*]/g, function(c) {
            return '%' + c.charCodeAt(0).toString(16);
        });
    }

    function jsonParser(str) {
        try {
          return JSON.parse(str);
        } catch(ex) {
          return null;
        }
    }

    function getLogosData(id) {
        var logosData = {
            'logos' : []
        }
        var logos = '';
        var logosRaw = $('#' + id).val();

        if (logosRaw != '') {
            logos = jsonParser(
                decodeURIComponent(logosRaw.replace(/\+/g, ' '))
            );

            if (logos !== null) {
                logosData = logos;
            }
        }

        return logosData;
    }

    function getPhotosData(id) {
        var photosData = {
            'photos' : []
        }
        var photos = '';
        var photosRaw = $('#' + id).val();

        if (photosRaw != '') {
            photos = jsonParser(
                decodeURIComponent(photosRaw.replace(/\+/g, ' '))
            );

            if (photos !== null) {
                photosData = photos;
            }
        }

        return photosData;
    }

    function getInfoData(id) {
        var infoData = {
            'info' : []
        };
        var info = '';
        var infoRaw = $('#' + id).val();
    
        if (infoRaw != '') {
            info = jsonParser(decodeURIComponent(infoRaw.replace(/\+/g, ' ')));

            if (info !== null) {
                infoData = info;
            }
        }

        return infoData;
    }

    function getFeaturesData(id) {
        var featuresData = {
            'features' : []
        };
        var features = '';
        var featuresRaw = $('#' + id).val();
    
        if (featuresRaw != '') {
            features = jsonParser(decodeURIComponent(featuresRaw.replace(/\+/g, ' ')));

            if (features !== null) {
                featuresData = features;
            }
        }

        return featuresData;
    }

    // Header type settings
    if ($('#pxp-page-header-section').length > 0) {
        $('input[name=page_header_type]').on('change', function() {
            var selected = $(this).val();

            $('.pxp-header-settings').hide();
            $(`.pxp-header-${selected}-settings`).show();
        });

        // Animated cards header type
        var phacLogosData = getLogosData('ph_animated_cards_logos');
        logosSection(phacLogosData, 'ac', 'animated_cards');

        var phacInfoData = getInfoData('ph_animated_cards_info');
        infoSection(phacInfoData, 'ac', 'animated_cards');

        $('#pxp-ph-ac-photo-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.cards_photo_title,
                button: {
                    text: meta_vars.cards_photo_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_animated_cards_photo').val(value.id);
                    $('#pxp-ph-ac-photo-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ac-photo-placeholder-container')
                    .addClass('has-image');
                });
            });

            frame.open();
        });

        $('#pxp-ph-ac-delete-photo').on('click', function() {
            $('#ph_animated_cards_photo').val('');
            $('#pxp-ph-ac-photo-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ac-photo-placeholder-container')
            .removeClass('has-image');
        });

        // Image rotator header type
        var phirLogosData = getLogosData('ph_image_rotator_logos');
        logosSection(phirLogosData, 'ir', 'image_rotator');

        var phirPhotosData = getPhotosData('ph_image_rotator_photos');
        photosSection(phirPhotosData, 'ir', 'image_rotator');

        var phirInfoData = getInfoData('ph_image_rotator_info');
        infoSection(phirInfoData, 'ir', 'image_rotator');

        // Illustration header type
        var phiLogosData = getLogosData('ph_illustration_logos');
        logosSection(phiLogosData, 'i', 'illustration');

        $('#pxp-ph-i-photo-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.illustration_title,
                button: {
                    text: meta_vars.illustration_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_illustration_photo').val(value.id);
                    $('#pxp-ph-i-photo-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-i-photo-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-i-delete-photo').on('click', function() {
            $('#ph_illustration_photo').val('');
            $('#pxp-ph-i-photo-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-i-photo-placeholder-container').removeClass('has-image');
        });

        // Boxed header type
        var phbInfoData = getInfoData('ph_boxed_info');
        infoSection(phbInfoData, 'b', 'boxed');

        $('#pxp-ph-b-sfc-illustration-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.illustration_title,
                button: {
                    text: meta_vars.illustration_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_boxed_sfc_illustration').val(value.id);
                    $('#pxp-ph-b-sfc-illustration-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-b-sfc-illustration-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-b-sfc-delete-illustration').on('click', function() {
            $('#ph_boxed_sfc_illustration').val('');
            $('#pxp-ph-b-sfc-illustration-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-b-sfc-illustration-placeholder-container')
            .removeClass('has-image');
        });

        $('#pxp-ph-b-sfc-icon-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.icon_title,
                button: {
                    text: meta_vars.icon_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_boxed_sfc_icon').val(value.id);
                    $('#pxp-ph-b-sfc-icon-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-b-sfc-icon-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-b-sfc-delete-icon').on('click', function() {
            $('#ph_boxed_sfc_icon').val('');
            $('#pxp-ph-b-sfc-icon-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-b-sfc-icon-placeholder-container')
            .removeClass('has-image');
        });

        $('#pxp-ph-b-bfc-illustration-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.illustration_title,
                button: {
                    text: meta_vars.illustration_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_boxed_bfc_illustration').val(value.id);
                    $('#pxp-ph-b-bfc-illustration-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-b-bfc-illustration-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-b-bfc-delete-illustration').on('click', function() {
            $('#ph_boxed_bfc_illustration').val('');
            $('#pxp-ph-b-bfc-illustration-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-b-bfc-illustration-placeholder-container')
            .removeClass('has-image');
        });

        $('#pxp-ph-b-bfc-icon-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.icon_title,
                button: {
                    text: meta_vars.icon_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_boxed_bfc_icon').val(value.id);
                    $('#pxp-ph-b-bfc-icon-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-b-bfc-icon-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-b-bfc-delete-icon').on('click', function() {
            $('#ph_boxed_bfc_icon').val('');
            $('#pxp-ph-b-bfc-icon-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-b-bfc-icon-placeholder-container')
            .removeClass('has-image');
        });

        // Image background header type
        $('#pxp-ph-ib-photo-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.image_bg_title,
                button: {
                    text: meta_vars.image_bg_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_image_bg_photo').val(value.id);
                    $('#pxp-ph-ib-photo-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ib-photo-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-ib-delete-photo').on('click', function() {
            $('#ph_image_bg_photo').val('');
            $('#pxp-ph-ib-photo-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ib-photo-placeholder-container')
            .removeClass('has-image');
        });

        // Top search header type
        $('#pxp-ph-ts-photo-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.image_bg_title,
                button: {
                    text: meta_vars.image_bg_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_top_search_photo').val(value.id);
                    $('#pxp-ph-ts-photo-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ts-photo-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-ts-delete-photo').on('click', function() {
            $('#ph_top_search_photo').val('');
            $('#pxp-ph-ts-photo-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ts-photo-placeholder-container')
            .removeClass('has-image');
        });

        // Image card header type
        var phicLogosData = getLogosData('ph_image_card_logos');
        logosSection(phicLogosData, 'ic', 'image_card');

        $('#pxp-ph-ic-photo-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.card_photo_title,
                button: {
                    text: meta_vars.card_photo_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_image_card_photo').val(value.id);
                    $('#pxp-ph-ic-photo-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ic-photo-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-ic-delete-photo').on('click', function() {
            $('#ph_image_card_photo').val('');
            $('#pxp-ph-ic-photo-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ic-photo-placeholder-container')
            .removeClass('has-image');
        });

        // Half Image header type
        $('#pxp-ph-hi-photo-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.half_image_title,
                button: {
                    text: meta_vars.half_image_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_half_image_photo').val(value.id);
                    $('#pxp-ph-hi-photo-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-hi-photo-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-hi-delete-photo').on('click', function() {
            $('#ph_half_image_photo').val('');
            $('#pxp-ph-hi-photo-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-hi-photo-placeholder-container').removeClass('has-image');
        });

        var phhiFeaturesData = getFeaturesData('ph_half_image_caption_key_features');
        featuresSection(phhiFeaturesData, 'hi', 'half_image');

        // Center Image header type
        $('#pxp-ph-ci-photo-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.center_image_title,
                button: {
                    text: meta_vars.center_image_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_center_image_photo').val(value.id);
                    $('#pxp-ph-ci-photo-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ci-photo-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-ci-delete-photo').on('click', function() {
            $('#ph_center_image_photo').val('');
            $('#pxp-ph-ci-photo-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ci-photo-placeholder-container').removeClass('has-image');
        });

        $('#pxp-ph-ci-bg-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.center_image_bg_title,
                button: {
                    text: meta_vars.center_image_bg_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_center_image_bg').val(value.id);
                    $('#pxp-ph-ci-bg-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ci-bg-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-ci-delete-bg').on('click', function() {
            $('#ph_center_image_bg').val('');
            $('#pxp-ph-ci-bg-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ci-bg-placeholder-container').removeClass('has-image');
        });

        // Image  header type
        $('#pxp-ph-ip-left-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.left_pill_image_title,
                button: {
                    text: meta_vars.left_pill_image_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_image_pills_left').val(value.id);
                    $('#pxp-ph-ip-left-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ip-left-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-ip-left-delete').on('click', function() {
            $('#ph_image_pills_left').val('');
            $('#pxp-ph-ip-left-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ip-left-placeholder-container').removeClass('has-image');
        });

        $('#pxp-ph-ip-top-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.top_pill_image_title,
                button: {
                    text: meta_vars.top_pill_image_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_image_pills_top').val(value.id);
                    $('#pxp-ph-ip-top-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ip-top-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-ip-bottom-delete').on('click', function() {
            $('#ph_image_pills_bottom').val('');
            $('#pxp-ph-ip-bottom-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ip-bottom-placeholder-container').removeClass('has-image');
        });

        $('#pxp-ph-ip-bottom-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.bottom_pill_image_title,
                button: {
                    text: meta_vars.bottom_pill_image_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_image_pills_bottom').val(value.id);
                    $('#pxp-ph-ip-bottom-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ip-bottom-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-ip-bottom-delete').on('click', function() {
            $('#ph_image_pills_bottom').val('');
            $('#pxp-ph-ip-bottom-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ip-bottom-placeholder-container').removeClass('has-image');
        });

        var phipFeaturesData = getFeaturesData('ph_image_pills_caption_key_features');
        featuresSection(phipFeaturesData, 'ip', 'image_pills');

        // Right Image header type
        $('#pxp-ph-ri-photo-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.right_image_title,
                button: {
                    text: meta_vars.right_image_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_right_image_photo').val(value.id);
                    $('#pxp-ph-ri-photo-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ri-photo-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-ri-delete-photo').on('click', function() {
            $('#ph_right_image_photo').val('');
            $('#pxp-ph-ri-photo-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ri-photo-placeholder-container').removeClass('has-image');
        });

        $('#pxp-ph-ri-bg-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.right_image_bg_title,
                button: {
                    text: meta_vars.right_image_bg_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#ph_right_image_bg').val(value.id);
                    $('#pxp-ph-ri-bg-placeholder').css(
                        'background-image', `url(${value.url})`
                    );
                    $('.pxp-ph-ri-bg-placeholder-container')
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $('#pxp-ph-ri-delete-bg').on('click', function() {
            $('#ph_right_image_bg').val('');
            $('#pxp-ph-ri-bg-placeholder').css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $('.pxp-ph-ri-bg-placeholder-container').removeClass('has-image');
        });

        var phriFeaturesData = getFeaturesData('ph_right_image_caption_key_features');
        featuresSection(phriFeaturesData, 'ri', 'right_image');
    };

    function logosSection(data, shortId, longId) {
        $(`#pxp-ph-${shortId}-add-logo-btn`).on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $(`.pxp-ph-${shortId}-new-logo`).show();
        });

        $(`#pxp-ph-${shortId}-ok-logo`).on('click', function(event) {
            event.preventDefault();

            var image     = $(`#ph_${longId}_logo_image`).val();
            var image_src = $(`#ph_${longId}_logo_image`).attr('data-src');

            data.logos.push({
                'image'    : image,
                'image_src': image_src
            });

            $(`#ph_${longId}_logos`).val(
                fixedEncodeURIComponent(JSON.stringify(data))
            );

            $(`#pxp-ph-${shortId}-logos-list`).append(
                `<li class="list-group-item"
                    data-image="${image}" 
                    data-src="${image_src}"
                >
                    <div class="pxp-ph-${shortId}-logos-list-item">
                        <img src="${image_src}">
                        <div class="pxp-list-item-btns">
                            <a href="javascript:void(0);" 
                                class="pxp-list-edit-btn pxp-ph-${shortId}-edit-new-logo-btn"
                            >
                                <span class="fa fa-pencil"></span>
                            </a>
                            <a href="javascript:void(0);" 
                                class="pxp-list-del-btn pxp-ph-${shortId}-del-new-logo-btn"
                            >
                                <span class="fa fa-trash-o"></span>
                            </a>
                        </div>
                    </div>
                </li>`
            ).css('background-color', 'green');

            $(`#ph_${longId}_logo_image`).val('');

            $(`#pxp-ph-${shortId}-logo-image-placeholder`).css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/logo-placeholder.png)`
            );
            $(`.pxp-ph-${shortId}-logo-image-placeholder-container`)
            .removeClass('has-image');

            $(`.pxp-ph-${shortId}-new-logo`).hide();
            $(`#pxp-ph-${shortId}-add-logo-btn`).show();

            $(`.pxp-ph-${shortId}-del-new-logo-btn`)
            .unbind('click')
            .on('click', function(event) {
                pageHeaderDelLogo($(this), data, shortId, longId);
            });
            $(`.pxp-ph-${shortId}-edit-new-logo-btn`)
            .unbind('click')
            .on('click', function(event) {
                pageHeaderEditLogo($(this), data, shortId, longId);
            });
        });

        $(`#pxp-ph-${shortId}-cancel-logo`).on('click', function(event) {
            event.preventDefault();

            $(`#ph_${longId}_logo_image`).val('');

            $(`#pxp-ph-${shortId}-logo-image-placeholder`).css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/logo-placeholder.png)`
            );
            $(`.pxp-ph-${shortId}-logo-image-placeholder-container`)
            .removeClass('has-image');

            $(`.pxp-ph-${shortId}-new-logo`).hide();
            $(`#pxp-ph-${shortId}-add-logo-btn`).show();
        });

        $(`#pxp-ph-${shortId}-logo-image-placeholder`)
        .on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.logo_image_title,
                button: {
                    text: meta_vars.logo_image_btn
                },
                multiple: false
            });
    
            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $(`#ph_${longId}_logo_image`)
                    .val(value.id)
                    .attr('data-src', value.url);
                    $(`#pxp-ph-${shortId}-logo-image-placeholder`).css(
                        'background-image', `url(${value.url})`
                    );
                    $(`.pxp-ph-${shortId}-logo-image-placeholder-container`)
                    .addClass('has-image');
                });
            });
    
            frame.open();
        });

        $(`#pxp-ph-${shortId}-delete-logo-image`).on('click', function() {
            $(`#ph_${longId}_logo_image`).val('');
            $(`#pxp-ph-${shortId}-logo-image-placeholder`).css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/logo-placeholder.png)`
            );
            $(`.pxp-ph-${shortId}-logo-image-placeholder-container`)
            .removeClass('has-image');
        });

        $(`#pxp-ph-${shortId}-logos-list`).sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                data.logos = [];

                $(`#pxp-ph-${shortId}-logos-list li`).each(function(index, el) {
                    data.logos.push({
                        'image'    : $(this).attr('data-image'),
                        'image_src': $(this).attr('data-src')
                    });

                });
    
                $(`#ph_${longId}_logos`).val(
                    fixedEncodeURIComponent(JSON.stringify(data))
                );
            }
        }).disableSelection();

        $(`.pxp-ph-${shortId}-del-logo-btn`).on('click', function(event) {
            pageHeaderDelLogo($(this), data, shortId, longId);
        });
        $(`.pxp-ph-${shortId}-edit-logo-btn`).on('click', function(event) {
            pageHeaderEditLogo($(this), data, shortId, longId);
        });
    }

    function photosSection(data, shortId, longId) {
        $(`#pxp-ph-${shortId}-add-photo-btn`).on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $(`.pxp-ph-${shortId}-new-photo`).show();
        });

        $(`#pxp-ph-${shortId}-ok-photo`).on('click', function(event) {
            event.preventDefault();

            var image     = $(`#ph_${longId}_photo_image`).val();
            var image_src = $(`#ph_${longId}_photo_image`).attr('data-src');

            data.photos.push({
                'image'    : image,
                'image_src': image_src
            });

            $(`#ph_${longId}_photos`).val(
                fixedEncodeURIComponent(JSON.stringify(data))
            );

            $(`#pxp-ph-${shortId}-photos-list`).append(
                `<li class="list-group-item"
                    data-image="${image}" 
                    data-src="${image_src}" 
                >
                    <div class="pxp-ph-${shortId}-photos-list-item">
                        <img src="${image_src}">
                        <div class="pxp-list-item-btns">
                            <a href="javascript:void(0);" 
                                class="pxp-list-edit-btn pxp-ph-${shortId}-edit-new-photo-btn"
                            >
                                <span class="fa fa-pencil"></span>
                            </a>
                            <a href="javascript:void(0);" 
                                class="pxp-list-del-btn pxp-ph-${shortId}-del-new-photo-btn"
                            >
                                <span class="fa fa-trash-o"></span>
                            </a>
                        </div>
                    </div>
                </li>`
            );
    
            $(`#ph_${longId}_photo_image`).val('');

            $(`#pxp-ph-${shortId}-photo-image-placeholder`).css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $(`.pxp-ph-${shortId}-photo-image-placeholder-container`)
            .removeClass('has-image');

            $(`.pxp-ph-${shortId}-new-photo`).hide();
            $(`#pxp-ph-${shortId}-add-photo-btn`).show();

            $(`.pxp-ph-${shortId}-del-new-photo-btn`)
            .unbind('click')
            .on('click', function(event) {
                pageHeaderDelPhoto($(this), data, shortId, longId);
            });
            $(`.pxp-ph-${shortId}-edit-new-photo-btn`)
            .unbind('click')
            .on('click', function(event) {
                pageHeaderEditPhoto($(this), data, shortId, longId);
            });
        });

        $(`#pxp-ph-${shortId}-cancel-photo`).on('click', function(event) {
            event.preventDefault();

            $(`#ph_${longId}_photo_image`).val('');

            $(`#pxp-ph-${shortId}-photo-image-placeholder`).css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $(`.pxp-ph-${shortId}-photo-image-placeholder-container`)
            .removeClass('has-image');

            $(`.pxp-ph-${shortId}-new-photo`).hide();
            $(`#pxp-ph-${shortId}-add-photo-btn`).show();
        });

        $(`#pxp-ph-${shortId}-photo-image-placeholder`).on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: meta_vars.photo_title,
                button: {
                    text: meta_vars.photo_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $(`#ph_${longId}_photo_image`)
                    .val(value.id)
                    .attr('data-src', value.url);
                    $(`#pxp-ph-${shortId}-photo-image-placeholder`).css(
                        'background-image', `url(${value.url})`)
                    ;
                    $(`.pxp-ph-${shortId}-photo-image-placeholder-container`)
                    .addClass('has-image');
                });
            });

            frame.open();
        });

        $(`#pxp-ph-${shortId}-delete-photo-image`).on('click', function() {
            $(`#ph_${longId}_photo_image`).val('');
            $(`#pxp-ph-${shortId}-photo-image-placeholder`).css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $(`.pxp-ph-${shortId}-photo-image-placeholder-container`)
            .removeClass('has-image');
        });

        $(`#pxp-ph-${shortId}-photos-list`).sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                data.photos = [];

                $(`#pxp-ph-${shortId}-photos-list li`)
                .each(function(index, el) {
                    data.photos.push({
                        'image'    : $(this).attr('data-image'),
                        'image_src': $(this).attr('data-src')
                    });

                });

                $(`#ph_${longId}_photos`).val(
                    fixedEncodeURIComponent(JSON.stringify(data))
                );
            }
        }).disableSelection();

        $(`.pxp-ph-${shortId}-del-photo-btn`).on('click', function(event) {
            pageHeaderDelPhoto($(this), data, shortId, longId);
        });
        $(`.pxp-ph-${shortId}-edit-photo-btn`).on('click', function(event) {
            pageHeaderEditPhoto($(this), data, shortId, longId);
        });
    }

    function infoSection(data, shortId, longId) {
        $(`#pxp-ph-${shortId}-add-info-btn`).on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $(`.pxp-ph-${shortId}-new-info`).show();
        });

        $(`#pxp-ph-${shortId}-ok-info`).on('click', function(event) {
            event.preventDefault();

            var number = $(`#ph_${longId}_info_number`).val();
            var label  = $(`#ph_${longId}_info_label`).val();
            var text   = $(`#ph_${longId}_info_text`).val();

            data.info.push({
                'number': number,
                'label' : label,
                'text'  : text
            });

            $(`#ph_${longId}_info`).val(
                fixedEncodeURIComponent(JSON.stringify(data))
            );

            $(`#pxp-ph-${shortId}-info-list`).append(
                `<li class="list-group-item" 
                    data-number="${number}" 
                    data-label="${label}" 
                    data-text="${text}"
                >
                    <div class="pxp-ph-${shortId}-info-list-item">
                        <div class="pxp-ph-${shortId}-info-list-item-number-label">
                            <span>${number}</span>${label}
                        </div>
                        <div class="pxp-ph-${shortId}-info-list-item-text">
                            ${text}
                        </div>
                        <div class="pxp-list-item-btns">
                            <a href="javascript:void(0);" 
                                class="pxp-list-edit-btn pxp-ph-${shortId}-edit-new-info-btn"
                            >
                                <span class="fa fa-pencil"></span>
                            </a>
                            <a href="javascript:void(0);" 
                                class="pxp-list-del-btn pxp-ph-${shortId}-del-new-info-btn"
                            >
                                <span class="fa fa-trash-o"></span>
                            </a>
                        </div>
                    </div>
                </li>`
            );

            $(`#ph_${longId}_info_number`).val('');
            $(`#ph_${longId}_info_label`).val('');
            $(`#ph_${longId}_info_text`).val('');

            $(`.pxp-ph-${shortId}-new-info`).hide();

            if (data.info.length > 2) {
                $(`#pxp-ph-${shortId}-add-info-btn`).hide();
            } else {
                $(`#pxp-ph-${shortId}-add-info-btn`).show();
            }

            $(`.pxp-ph-${shortId}-del-new-info-btn`)
            .unbind('click')
            .on('click', function(event) {
                pageHeaderDelInfo($(this), data, shortId, longId);
            });
            $(`.pxp-ph-${shortId}-edit-new-info-btn`)
            .unbind('click')
            .on('click', function(event) {
                pageHeaderEditInfo($(this), data, shortId, longId);
            });
        });

        $(`#pxp-ph-${shortId}-cancel-info`).on('click', function(event) {
            event.preventDefault();

            $(`#ph_${longId}_info_number`).val('');
            $(`#ph_${longId}_info_label`).val('');
            $(`#ph_${longId}_info_text`).val('');

            $(`.pxp-ph-${shortId}-new-info`).hide();
            $(`#pxp-ph-${shortId}-add-info-btn`).show();
        });

        $(`#pxp-ph-${shortId}-info-list`).sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                data.info = [];

                $(`#pxp-ph-${shortId}-info-list li`).each(function(index, el) {
                    data.info.push({
                        'number': $(this).attr('data-number'),
                        'label' : $(this).attr('data-label'),
                        'text'  : $(this).attr('data-text')
                    });

                });

                $(`#ph_${longId}_info`).val(
                    fixedEncodeURIComponent(JSON.stringify(data))
                );
            }
        }).disableSelection();

        $(`.pxp-ph-${shortId}-del-info-btn`).on('click', function(event) {
            pageHeaderDelInfo($(this), data, shortId, longId);
        });
        $(`.pxp-ph-${shortId}-edit-info-btn`).on('click', function(event) {
            pageHeaderEditInfo($(this), data, shortId, longId);
        });
    }

    function featuresSection(data, shortId, longId) {
        $(`#pxp-ph-${shortId}-add-feature-btn`).on('click', function(event) {
            event.preventDefault();

            var text = $(`#ph_${longId}_caption_key_features_new`).val();

            if (text != '') {
                data.features.push({
                    'text': text
                });
    
                $(`#ph_${longId}_caption_key_features`).val(
                    fixedEncodeURIComponent(JSON.stringify(data))
                );
    
                $(`#pxp-ph-${shortId}-key-features-list`).append(
                    `<li class="list-group-item" data-text="${text}">
                        <div class="pxp-ph-${shortId}-key-features-list-item">
                            <div class="pxp-ph-${shortId}-key-features-list-item-text">
                                ${text}
                            </div>
                            <div class="pxp-list-item-btns">
                                <a href="javascript:void(0);" 
                                    class="pxp-list-del-btn pxp-ph-${shortId}-del-new-key-feature-btn"
                                >
                                    <span class="fa fa-trash-o"></span>
                                </a>
                            </div>
                        </div>
                    </li>`
                );
    
                $(`#ph_${longId}_caption_key_features_new`).val('');
    
                $(`.pxp-ph-${shortId}-del-new-key-feature-btn`)
                .unbind('click')
                .on('click', function(event) {
                    pageHeaderDelFeature($(this), data, shortId, longId);
                });
            }
        });

        $(`#pxp-ph-${shortId}-key-features-list`).sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                data.features = [];

                $(`#pxp-ph-${shortId}-key-features-list li`).each(function(index, el) {
                    data.features.push({
                        'text'  : $(this).attr('data-text')
                    });

                });

                $(`#ph_${longId}_caption_key_features`).val(
                    fixedEncodeURIComponent(JSON.stringify(data))
                );
            }
        }).disableSelection();

        $(`.pxp-ph-${shortId}-del-key-feature-btn`).on('click', function(event) {
            pageHeaderDelFeature($(this), data, shortId, longId);
        });
    }

    function pageHeaderDelLogo(btn, data, shortId, longId) {
        btn.parent().parent().parent().remove();

        data.logos = [];

        $(`#pxp-ph-${shortId}-logos-list li`).each(function(index, el) {
            data.logos.push({
                'image': $(this).attr('data-image')
            });

        });

        $(`#ph_${longId}_logos`).val(
            fixedEncodeURIComponent(JSON.stringify(data))
        );
    }

    function pageHeaderEditLogo(btn, data, shortId, longId) {
        var editImgSrc = btn.parent().parent().parent().attr('data-src');
        var editImgId  = btn.parent().parent().parent().attr('data-image');

        var hasImageClass = (editImgId != '') ? 'has-image' : '';
        var containerClasses = [
            `pxp-ph-${shortId}-edit-logo-image-placeholder-container`,
            hasImageClass
        ];

        if (editImgSrc == '') {
            editImgSrc = meta_vars.plugin_url + 'meta/images/logo-placeholder.png';
        }

        var pageHeaderEditLogoForm = 
            `<div class="pxp-ph-${shortId}-edit-logo">
                <div class="pxp-ph-${shortId}-new-logo-container">
                    <div class="pxp-ph-${shortId}-new-logo-header">
                        <b>${meta_vars.edit_logo}</b>
                    </div>
                    <div style="display: flex;">
                        <div style="width: 33%">
                            <div class="form-field pxp-is-custom">
                                <label>${meta_vars.image_label}</label>
                                <input 
                                    type="hidden" 
                                    id="ph_${longId}_edit_logo_image" 
                                    name="ph_${longId}_edit_logo_image" 
                                    data-src="${editImgSrc}" 
                                    value="${editImgId}"
                                >
                                <div class="${containerClasses.join(' ')}">
                                    <div 
                                        id="pxp-ph-${shortId}-edit-logo-image-placeholder" 
                                        style="background-image: url(${editImgSrc});"
                                    ></div>
                                    <div id="pxp-ph-${shortId}-delete-edit-logo-image">
                                        <span class="fa fa-trash-o"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-field">
                        <button 
                            type="button" 
                            id="pxp-ph-${shortId}-ok-edit-logo" 
                            class="button media-button button-primary"
                        >
                            ${meta_vars.ok_btn_label}
                        </button>
                        <button 
                            type="button" 
                            id="pxp-ph-${shortId}-cancel-edit-logo" 
                            class="button media-button button-default"
                        >
                            ${meta_vars.cancel_btn_label}
                        </button>
                    </div>
                </div>
            </div>`;

        btn.parent().parent().hide();
        btn.parent().parent().parent().append(pageHeaderEditLogoForm);

        $(`#pxp-ph-${shortId}-logos-list`).sortable('disable');
        $(`#pxp-ph-${shortId}-logos-list .list-group-item`).css(
            'cursor', 'auto'
        );
        $(`.pxp-ph-${shortId}-edit-logo-btn`).hide();
        $(`.pxp-ph-${shortId}-del-logo-btn`).hide();
        $(`.pxp-ph-${shortId}-edit-new-logo-btn`).hide();
        $(`.pxp-ph-${shortId}-del-new-logo-btn`).hide();
        $(`#pxp-ph-${shortId}-add-logo-btn`).hide();
        $(`.pxp-ph-${shortId}-new-logo`).hide();

        $(`#pxp-ph-${shortId}-edit-logo-image-placeholder`)
        .on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: pt_vars.logo_image_title,
                button: {
                    text: pt_vars.logo_image_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $(`#ph_${longId}_edit_logo_image`)
                    .val(value.id)
                    .attr('data-src', value.url);
                    $(`#pxp-ph-${shortId}-edit-logo-image-placeholder`).css(
                        'background-image', `url(${value.url})`
                    );
                    $(`.pxp-ph-${shortId}-edit-logo-image-placeholder-container`)
                    .addClass('has-image');
                });
            });

            frame.open();
        });

        $(`#pxp-ph-${shortId}-delete-edit-logo-image`).on('click', function() {
            $(`#ph_${longId}_edit_logo_image`).val('').attr('data-src', '');
            $(`#pxp-ph-${shortId}-edit-logo-image-placeholder`).css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/logo-placeholder.png)`
            );
            $(`.pxp-ph-${shortId}-edit-logo-image-placeholder-container`)
            .removeClass('has-image');
        });

        $(`#pxp-ph-${shortId}-ok-edit-logo`).on('click', function(event) {
            var eImgSrc = $(this).parent().parent()
                            .find(`#ph_${longId}_edit_logo_image`)
                            .attr('data-src');
            var eImgId = $(this).parent().parent()
                            .find(`#ph_${longId}_edit_logo_image`)
                            .val();
            var listElem = $(this).parent().parent().parent().parent();

            listElem.attr({
                'data-image': eImgId,
                'data-src'  : eImgSrc
            });

            listElem.find('img').attr('src', eImgSrc);

            $(this).parent().parent().parent().remove();
            listElem.find(`.pxp-ph-${shortId}-logos-list-item`).show();

            $(`#pxp-ph-${shortId}-logos-list`).sortable('enable');
            $(`#pxp-ph-${shortId}-logos-list .list-group-item`).css(
                'cursor', 'move'
            );
            $(`.pxp-ph-${shortId}-edit-logo-btn`).show();
            $(`.pxp-ph-${shortId}-del-logo-btn`).show();
            $(`.pxp-ph-${shortId}-edit-new-logo-btn`).show();
            $(`.pxp-ph-${shortId}-del-new-logo-btn`).show();
            $(`#pxp-ph-${shortId}-add-logo-btn`).show();

            data.logos = [];
            $(`#pxp-ph-${shortId}-logos-list li`).each(function(index, el) {
                data.logos.push({
                    'image': $(this).attr('data-image')
                });

            });

            $(`#ph_${longId}_logos`).val(
                fixedEncodeURIComponent(JSON.stringify(data))
            );
        });

        $(`#pxp-ph-${shortId}-cancel-edit-logo`).on('click', function(event) {
            var listElem = $(this).parent().parent().parent().parent();

            $(this).parent().parent().parent().remove();
            listElem.find(`.pxp-ph-${shortId}-logos-list-item`).show();

            $(`#pxp-ph-${shortId}-logos-list`).sortable('enable');
            $(`#pxp-ph-${shortId}-logos-list .list-group-item`).css(
                'cursor', 'move'
            );
            $(`.pxp-ph-${shortId}-edit-logo-btn`).show();
            $(`.pxp-ph-${shortId}-del-logo-btn`).show();
            $(`.pxp-ph-${shortId}-edit-new-logo-btn`).show();
            $(`.pxp-ph-${shortId}-del-new-logo-btn`).show();
            $(`#pxp-ph-${shortId}-add-logo-btn`).show();
        });
    }

    function pageHeaderDelPhoto(btn, data, shortId, longId) {
        btn.parent().parent().parent().remove();

        data.photos = [];

        $(`#pxp-ph-${shortId}-photos-list li`).each(function(index, el) {
            data.photos.push({
                'image': $(this).attr('data-image')
            });

        });

        $(`#ph_${longId}_photos`).val(
            fixedEncodeURIComponent(JSON.stringify(data))
        );
    }

    function pageHeaderEditPhoto(btn, data, shortId, longId) {
        var editImgSrc = btn.parent().parent().parent().attr('data-src');
        var editImgId  = btn.parent().parent().parent().attr('data-image');

        var hasImageClass = (editImgId != '') ? 'has-image' : '';
        var containerClasses = [
            `pxp-ph-${shortId}-edit-photo-image-placeholder-container`,
            hasImageClass
        ];

        if (editImgSrc == '') {
            editImgSrc = meta_vars.plugin_url + 'meta/images/photo-placeholder.png';
        }

        var pageHeaderEditPhotoForm = 
            `<div class="pxp-ph-${shortId}-edit-photo">
                <div class="pxp-ph-${shortId}-new-photo-container">
                    <div class="pxp-ph-${shortId}-new-photo-header">
                        <b>${meta_vars.edit_photo}</b>
                    </div>
                    <div style="display: flex;">
                        <div style="width: 33%">
                            <div class="form-field pxp-is-custom">
                                <label>${meta_vars.photo_title}</label>
                                <input 
                                    type="hidden" 
                                    id="ph_${longId}_edit_photo_image" 
                                    name="ph_${longId}_edit_photo_image" 
                                    data-src="${editImgSrc}" 
                                    value="${editImgId}"
                                >
                                <div class="${containerClasses.join(' ')}">
                                    <div 
                                        id="pxp-ph-${shortId}-edit-photo-image-placeholder" 
                                        style="background-image: url(${editImgSrc});"
                                    ></div>
                                    <div id="pxp-ph-${shortId}-delete-edit-photo-image">
                                        <span class="fa fa-trash-o"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-field">
                        <button 
                            type="button" 
                            id="pxp-ph-${shortId}-ok-edit-photo" 
                            class="button media-button button-primary"
                        >
                            ${meta_vars.ok_btn_label}
                        </button>
                        <button 
                            type="button" 
                            id="pxp-ph-${shortId}-cancel-edit-photo" 
                            class="button media-button button-default"
                        >
                            ${meta_vars.cancel_btn_label}
                        </button>
                    </div>
                </div>
            </div>`;

        btn.parent().parent().hide();
        btn.parent().parent().parent().append(pageHeaderEditPhotoForm);

        $(`#pxp-ph-${shortId}-photos-list`).sortable('disable');
        $(`#pxp-ph-${shortId}-photos-list .list-group-item`).css(
            'cursor', 'auto'
        );
        $(`.pxp-ph-${shortId}-edit-photo-btn`).hide();
        $(`.pxp-ph-${shortId}-del-photo-btn`).hide();
        $(`.pxp-ph-${shortId}-edit-new-photo-btn`).hide();
        $(`.pxp-ph-${shortId}-del-new-photo-btn`).hide();
        $(`#pxp-ph-${shortId}-add-photo-btn`).hide();
        $(`.pxp-ph-${shortId}-new-photo`).hide();

        $(`#pxp-ph-${shortId}-edit-photo-image-placeholder`)
        .on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: pt_vars.photo_title,
                button: {
                    text: pt_vars.photo_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $(`#ph_${longId}_edit_photo_image`)
                    .val(value.id)
                    .attr('data-src', value.url);
                    $(`#pxp-ph-${shortId}-edit-photo-image-placeholder`).css(
                        'background-image', `url(${value.url})`
                    );
                    $(`.pxp-ph-${shortId}-edit-photo-image-placeholder-container`)
                    .addClass('has-image');
                });
            });

            frame.open();
        });

        $(`#pxp-ph-${shortId}-delete-edit-photo-image`).on('click', function() {
            $(`#ph_${longId}_edit_photo_image`).val('').attr('data-src', '');
            $(`#pxp-ph-${shortId}-edit-photo-image-placeholder`).css(
                'background-image',
                `url(${meta_vars.plugin_url}meta/images/photo-placeholder.png)`
            );
            $(`.pxp-ph-${shortId}-edit-photo-image-placeholder-container`)
            .removeClass('has-image');
        });

        $(`#pxp-ph-${shortId}-ok-edit-photo`).on('click', function(event) {
            var eImgSrc = $(this).parent().parent()
                            .find(`#ph_${longId}_edit_photo_image`)
                            .attr('data-src');
            var eImgId = $(this).parent().parent()
                            .find(`#ph_${longId}_edit_photo_image`)
                            .val();
            var listElem = $(this).parent().parent().parent().parent();

            listElem.attr({
                'data-image': eImgId,
                'data-src'  : eImgSrc
            });

            listElem.find('img').attr('src', eImgSrc);

            $(this).parent().parent().parent().remove();
            listElem.find(`.pxp-ph-${shortId}-photos-list-item`).show();

            $(`#pxp-ph-${shortId}-photos-list`).sortable('enable');
            $(`#pxp-ph-${shortId}-photos-list .list-group-item`).css(
                'cursor', 'move'
            );
            $(`.pxp-ph-${shortId}-edit-photo-btn`).show();
            $(`.pxp-ph-${shortId}-del-photo-btn`).show();
            $(`.pxp-ph-${shortId}-edit-new-photo-btn`).show();
            $(`.pxp-ph-${shortId}-del-new-photo-btn`).show();
            $(`#pxp-ph-${shortId}-add-photo-btn`).show();

            data.photos = [];
            $(`#pxp-ph-${shortId}-photos-list li`).each(function(index, el) {
                data.photos.push({
                    'image': $(this).attr('data-image')
                });

            });

            $(`#ph_${longId}_photos`).val(
                fixedEncodeURIComponent(JSON.stringify(data))
            );
        });

        $(`#pxp-ph-${shortId}-cancel-edit-photo`).on('click', function(event) {
            var listElem = $(this).parent().parent().parent().parent();

            $(this).parent().parent().parent().remove();
            listElem.find(`.pxp-ph-${shortId}-photos-list-item`).show();

            $(`#pxp-ph-${shortId}-photos-list`).sortable('enable');
            $(`#pxp-ph-${shortId}-photos-list .list-group-item`).css(
                'cursor', 'move'
            );
            $(`.pxp-ph-${shortId}-edit-photo-btn`).show();
            $(`.pxp-ph-${shortId}-del-photo-btn`).show();
            $(`.pxp-ph-${shortId}-edit-new-photo-btn`).show();
            $(`.pxp-ph-${shortId}-del-new-photo-btn`).show();
            $(`#pxp-ph-${shortId}-add-photo-btn`).show();
        });
    }

    function pageHeaderDelInfo(btn, data, shortId, longId) {
        btn.parent().parent().parent().remove();

        data.info = [];

        $(`#pxp-ph-${shortId}-info-list li`).each(function(index, el) {
            data.info.push({
                'number': $(this).attr('data-number'),
                'label' : $(this).attr('data-label'),
                'text'  : $(this).attr('data-text')
            });
        });

        if (data.info.length < 3) {
            $(`#pxp-ph-${shortId}-add-info-btn`).show();
        }

        $(`#ph_${longId}_info`).val(
            fixedEncodeURIComponent(JSON.stringify(data))
        );
    }

    function pageHeaderDelFeature(btn, data, shortId, longId) {
        btn.parent().parent().parent().remove();

        data.features = [];

        $(`#pxp-ph-${shortId}-key-features-list li`).each(function(index, el) {
            data.features.push({
                'text': $(this).attr('data-text')
            });
        });

        $(`#ph_${longId}_caption_key_features`).val(
            fixedEncodeURIComponent(JSON.stringify(data))
        );
    }

    function pageHeaderEditInfo(btn, data, shortId, longId) {
        var editNumber = btn.parent().parent().parent().attr('data-number');
        var editLabel  = btn.parent().parent().parent().attr('data-label');
        var editText   = btn.parent().parent().parent().attr('data-text');

        var pageHeaderEditInfoForm = 
            `<div class="pxp-ph-${shortId}-edit-info">
                <div class="pxp-ph-${shortId}-new-info-container">
                    <div class="pxp-ph-${shortId}-new-info-header">
                        <b>${meta_vars.edit_info}</b>
                    </div>
                    <div style="display: flex;">
                        <div style="width: 10%;">
                            <div class="form-field pxp-is-custom">
                                <label for="ph_${longId}_info_edit_number">
                                    ${meta_vars.number_label}
                                </label><br />
                                <input 
                                    type="text" 
                                    id="ph_${longId}_info_edit_number" 
                                    name="ph_${longId}_info_edit_number" 
                                    value="${editNumber}"
                                >
                            </div>
                        </div>
                        <div style="width: 10%;">
                            <div class="form-field pxp-is-custom">
                                <label for="ph_${longId}_info_edit_label">
                                    ${meta_vars.label}
                                </label><br />
                                <input 
                                    type="text" 
                                    id="ph_${longId}_info_edit_label" 
                                    name="ph_${longId}_info_edit_label" 
                                    value="${editLabel}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="form-field pxp-is-custom">
                        <label for="ph_${longId}_info_edit_text">
                            ${meta_vars.text}
                        </label><br />
                        <input 
                            type="text" 
                            id="ph_${longId}_info_edit_text" 
                            name="ph_${longId}_info_edit_text" 
                            value="${editText}"
                        >
                    </div>
                    <div class="form-field">
                        <button 
                            type="button" 
                            id="pxp-ph-${shortId}-ok-edit-info" 
                            class="button media-button button-primary"
                        >
                            ${meta_vars.ok_btn_label}
                        </button>
                        <button 
                            type="button" 
                            id="pxp-ph-${shortId}-cancel-edit-info" 
                            class="button media-button button-default"
                        >
                            ${meta_vars.cancel_btn_label}
                        </button>
                    </div>
                </div>
            </div>`;

        btn.parent().parent().hide();
        btn.parent().parent().parent().append(pageHeaderEditInfoForm);

        $(`#pxp-ph-${shortId}-info-list`).sortable('disable');
        $(`#pxp-ph-${shortId}-info-list .list-group-item`).css(
            'cursor', 'auto'
        );
        $(`.pxp-ph-${shortId}-edit-info-btn`).hide();
        $(`.pxp-ph-${shortId}-del-info-btn`).hide();
        $(`.pxp-ph-${shortId}-edit-new-info-btn`).hide();
        $(`.pxp-ph-${shortId}-del-new-info-btn`).hide();
        $(`#pxp-ph-${shortId}-add-info-btn`).hide();
        $(`.pxp-ph-${shortId}-new-info`).hide();

        $(`#pxp-ph-${shortId}-ok-edit-info`).on('click', function(event) {
            var eNumber = $(this).parent().parent()
                            .find(`#ph_${longId}_info_edit_number`).val();
            var eLabel = $(this).parent().parent()
                            .find(`#ph_${longId}_info_edit_label`).val();
            var eText = $(this).parent().parent()
                            .find(`#ph_${longId}_info_edit_text`).val();
            var listElem = $(this).parent().parent().parent().parent();

            listElem.attr({
                'data-number': eNumber,
                'data-label' : eLabel,
                'data-text'  : eText
            });

            listElem.find(`.pxp-ph-${shortId}-info-list-item-number-label`)
                    .html(`<span>${eNumber}</span>${eLabel}`);
            listElem.find(`.pxp-ph-${shortId}-info-list-item-text`)
                    .text(eText);

            $(this).parent().parent().parent().remove();
            listElem.find(`.pxp-ph-${shortId}-info-list-item`).show();

            $(`#pxp-ph-${shortId}-info-list`).sortable('enable');
            $(`#pxp-ph-${shortId}-info-list .list-group-item`).css(
                'cursor', 'move'
            );
            $(`.pxp-ph-${shortId}-edit-info-btn`).show();
            $(`.pxp-ph-${shortId}-del-info-btn`).show();
            $(`.pxp-ph-${shortId}-edit-new-info-btn`).show();
            $(`.pxp-ph-${shortId}-del-new-info-btn`).show();
            $(`#pxp-ph-${shortId}-add-info-btn`).show();

            data.info = [];
            $(`#pxp-ph-${shortId}-info-list li`).each(function(index, el) {
                data.info.push({
                    'number': $(this).attr('data-number'),
                    'label' : $(this).attr('data-label'),
                    'text'  : $(this).attr('data-text')
                });

            });

            $(`#ph_${longId}_info`).val(
                fixedEncodeURIComponent(JSON.stringify(data))
            );
        });

        $(`#pxp-ph-${shortId}-cancel-edit-info`).on('click', function(event) {
            var listElem = $(this).parent().parent().parent().parent();

            $(this).parent().parent().parent().remove();
            listElem.find(`.pxp-ph-${shortId}-info-list-item`).show();

            $(`#pxp-ph-${shortId}-info-list`).sortable('enable');
            $(`#pxp-ph-${shortId}-info-list .list-group-item`).css(
                'cursor', 'move'
            );
            $(`.pxp-ph-${shortId}-edit-info-btn`).show();
            $(`.pxp-ph-${shortId}-del-info-btn`).show();
            $(`.pxp-ph-${shortId}-edit-new-info-btn`).show();
            $(`.pxp-ph-${shortId}-del-new-info-btn`).show();
            $(`#pxp-ph-${shortId}-add-info-btn`).show();
        });
    }

    // Page Header settings are not displayed for these page templates
    var pageHeaderRestrictedPages = [
        'job-search.php',
        'job-search-apis.php',
        'company-search.php',
        'candidate-search.php',
        'job-categories.php',
        'company-dashboard.php',
        'company-dashboard-profile.php',
        'company-dashboard-new-job.php',
        'company-dashboard-jobs.php',
        'company-dashboard-edit-job.php',
        'company-dashboard-candidates.php',
        'company-dashboard-subscriptions.php',
        'company-dashboard-password.php',
        'company-dashboard-inbox.php',
        'company-dashboard-notifications.php',
        'candidate-dashboard.php',
        'candidate-dashboard-profile.php',
        'candidate-dashboard-apps.php',
        'candidate-dashboard-favs.php',
        'candidate-dashboard-password.php',
        'candidate-dashboard-inbox.php',
        'candidate-dashboard-notifications.php',
        'candidate-dashboard-apply.php',
        'paypal-processor.php',
        'stripe-processor.php',
        'sign-in.php',
        'sign-up.php',
    ];

    // Toggle page custom metaboxes according to the page template
    if (wp.data) {
        const { select, subscribe } = wp.data;
        class PageTemplateSwitcher {
            constructor() {
                this.template = null;
            }
            init() {
                subscribe(() => {
                    if (select('core/editor')) {
                        const newTemplate = select('core/editor').getEditedPostAttribute('template');

                        this.template = newTemplate;
                        this.changeTemplate();
                    }
                });
            }
            changeTemplate() {
                $('#pxp-page-header-section').toggle(
                    $.inArray(this.template, pageHeaderRestrictedPages) == -1
                );
                $('#pxp-page-settings-section').toggle(
                    $.inArray(this.template, pageHeaderRestrictedPages) == -1
                );
                $('#pxp-jobs-page-settings-section').toggle(
                    this.template == 'job-search.php' || 
                    this.template == 'job-search-apis.php'
                );
                $('#pxp-job-categories-page-settings-section').toggle(
                    this.template == 'job-categories.php'
                );
                $('#pxp-companies-page-settings-section').toggle(
                    this.template == 'company-search.php'
                );
                $('#pxp-candidates-page-settings-section').toggle(
                    this.template == 'candidate-search.php'
                );
            }
        }
        new PageTemplateSwitcher().init();
    }

    $('#page_template, .editor-page-attributes__template select')
    .on('change', function() {
        $('#pxp-page-header-section').toggle(
            $.inArray($(this).val(), pageHeaderRestrictedPages) == -1
        );
        $('#pxp-page-settings-section').toggle(
            $.inArray($(this).val(), pageHeaderRestrictedPages) == -1
        );
        $('#pxp-jobs-page-settings-section').toggle(
            $(this).val() == 'job-search.php' || 
            $(this).val() == 'job-search-apis.php'
        );
        $('#pxp-job-categories-page-settings-section').toggle(
            $(this).val() == 'job-categories.php'
        );
        $('#pxp-companies-page-settings-section').toggle(
            $(this).val() == 'company-search.php'
        );
        $('#pxp-candidates-page-settings-section').toggle(
            $(this).val() == 'candidate-search.php'
        );
    }).change();

    $('.pxp-color-field').wpColorPicker();
})(jQuery);