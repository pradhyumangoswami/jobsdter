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

    $('.datePicker').datepickerc({
        'format' : 'yyyy-mm-dd'
    });

    // Upload company logo 
    $('.pxp-company-logo-image-placeholder').click(function(event) {
        event.preventDefault();
        var placeholder = $(this);

        var frame = wp.media({
            title: pt_vars.company_logo_title,
            button: {
                text: pt_vars.company_logo_btn
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

    // Delete company logo
    $('.pxp-delete-company-logo-image').click(function() {
        var delBtn = $(this);

        delBtn.parent().parent().find('input').val('');
        delBtn.parent().find('.pxp-company-logo-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/logo-placeholder.png)');
        delBtn.parent().removeClass('pxp-has-image');
    });

    // Upload company cover 
    $('.pxp-company-cover-image-placeholder').click(function(event) {
        event.preventDefault();
        var placeholder = $(this);

        var frame = wp.media({
            title: pt_vars.company_cover_title,
            button: {
                text: pt_vars.company_cover_btn
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

    // Delete company cover
    $('.pxp-delete-company-cover-image').click(function() {
        var delBtn = $(this);

        delBtn.parent().parent().find('input').val('');
        delBtn.parent().find('.pxp-company-cover-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/cover-placeholder.png)');
        delBtn.parent().removeClass('pxp-has-image');
    });

    // Upload candidate photo 
    $('.pxp-candidate-photo-image-placeholder').click(function(event) {
        event.preventDefault();
        var placeholder = $(this);

        var frame = wp.media({
            title: pt_vars.candidate_photo_title,
            button: {
                text: pt_vars.candidate_photo_btn
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

    // Delete candidate photo
    $('.pxp-delete-candidate-photo-image').click(function() {
        var delBtn = $(this);

        delBtn.parent().parent().find('input').val('');
        delBtn.parent().find('.pxp-candidate-photo-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/photo-placeholder.png)');
        delBtn.parent().removeClass('pxp-has-image');
    });

    // Upload candidate cover 
    $('.pxp-candidate-cover-image-placeholder').click(function(event) {
        event.preventDefault();
        var placeholder = $(this);

        var frame = wp.media({
            title: pt_vars.candidate_cover_title,
            button: {
                text: pt_vars.candidate_cover_btn
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

    // Delete candidate cover
    $('.pxp-delete-candidate-cover-image').click(function() {
        var delBtn = $(this);

        delBtn.parent().parent().find('input').val('');
        delBtn.parent().find('.pxp-candidate-cover-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/cover-placeholder.png)');
        delBtn.parent().removeClass('pxp-has-image');
    });

    // Candidate Work Experience
    if ($('#candidate_work').length > 0) {
        var dataWorks = {
            'works' : []
        }
        var work = '';
        var workRaw = $('#candidate_work').val();

        if (workRaw != '') {
            work = jsonParser(decodeURIComponent(workRaw.replace(/\+/g, ' ')));

            if (work !== null) {
                dataWorks = work;
            }
        }

        $('#pxp-add-candidate-work-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-candidate-new-work').show();
        });

        $('#pxp-ok-work').on('click', function(event) {
            event.preventDefault();

            var title       = $('#candidate_work_title').val();
            var company     = $('#candidate_work_company').val();
            var period      = $('#candidate_work_period').val();
            var description = tinyMCE.get('candidate_work_description').getContent();

            dataWorks.works.push({
                'title'      : title,
                'company'    : company,
                'period'     : period,
                'description': description
            });

            $('#candidate_work').val(fixedEncodeURIComponent(JSON.stringify(dataWorks)));

            $('#pxp-candidate-work-list').append(
                '<li class="list-group-item"' + 
                    'data-title="' + fixedEncodeURIComponent(title) + '" ' + 
                    'data-company="' + fixedEncodeURIComponent(company) + '" ' + 
                    'data-period="' + fixedEncodeURIComponent(period) + '" ' + 
                    'data-description="' + fixedEncodeURIComponent(description) + '" ' + 
                '>' + 
                    '<div class="pxp-candidate-work-list-item">' + 
                        '<div class="pxp-candidate-work-list-item-title"><b>' + title + '</b></div>' + 
                        '<div class="pxp-candidate-work-list-item-company">' + company + '</div>' + 
                        '<div class="pxp-candidate-work-list-item-btns">' + 
                            '<a href="javascript:void(0);" class="pxp-list-edit-btn pxp-edit-new-candidate-work"><span class="fa fa-pencil"></span></a>' + 
                            '<a href="javascript:void(0);" class="pxp-list-del-btn pxp-del-new-candidate-work"><span class="fa fa-trash-o"></span></a>' + 
                        '</div>' + 
                    '</div>' + 
                '</li>'
            );

            $('#candidate_work_title').val('');
            $('#candidate_work_company').val('');
            $('#candidate_work_period').val('');
            tinyMCE.get('candidate_work_description').setContent('');

            $('.pxp-candidate-new-work').hide();
            $('#pxp-add-candidate-work-btn').show();

            $('.pxp-edit-new-candidate-work').unbind('click').on('click', function(event) {
                editCandidateWorkExperience($(this));
            });
            $('.pxp-del-new-candidate-work').unbind('click').on('click', function(event) {
                delCandidateWorkExperience($(this));
            });
        });

        $('#pxp-cancel-work').on('click', function(event) {
            event.preventDefault();

            $('#candidate_work_title').val('');
            $('#candidate_work_company').val('');
            $('#candidate_work_period').val('');
            tinyMCE.get('candidate_work_description').setContent('');

            $('.pxp-candidate-new-work').hide();
            $('#pxp-add-candidate-work-btn').show();
        });

        $('#pxp-candidate-work-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                dataWorks.works = [];

                $('#pxp-candidate-work-list li').each(function(index, el) {
                    dataWorks.works.push({
                        'title'      : decodeURIComponent($(this).attr('data-title').replace(/\+/g, ' ')),
                        'company'    : decodeURIComponent($(this).attr('data-company').replace(/\+/g, ' ')),
                        'period'     : decodeURIComponent($(this).attr('data-period').replace(/\+/g, ' ')),
                        'description': decodeURIComponent($(this).attr('data-description').replace(/\+/g, ' '))
                    });

                });

                $('#candidate_work').val(fixedEncodeURIComponent(JSON.stringify(dataWorks)));
            }
        }).disableSelection();

        $('.pxp-edit-candidate-work').on('click', function(event) {
            editCandidateWorkExperience($(this));
        });
        $('.pxp-del-candidate-work').on('click', function(event) {
            delCandidateWorkExperience($(this));
        });
    }

    function delCandidateWorkExperience(btn) {
        btn.parent().parent().parent().remove();

        dataWorks.works = [];

        $('#pxp-candidate-work-list li').each(function(index, el) {
            dataWorks.works.push({
                'title'      : decodeURIComponent($(this).attr('data-title').replace(/\+/g, ' ')),
                'company'    : decodeURIComponent($(this).attr('data-company').replace(/\+/g, ' ')),
                'period'     : decodeURIComponent($(this).attr('data-period').replace(/\+/g, ' ')),
                'description': decodeURIComponent($(this).attr('data-description').replace(/\+/g, ' '))
            });
        });

        $('#candidate_work').val(fixedEncodeURIComponent(JSON.stringify(dataWorks)));
    }

    function editCandidateWorkExperience(btn) {
        var editTitle       = decodeURIComponent(btn.parent().parent().parent().attr('data-title').replace(/\+/g, ' '));
        var editCompany     = decodeURIComponent(btn.parent().parent().parent().attr('data-company').replace(/\+/g, ' '));
        var editPeriod      = decodeURIComponent(btn.parent().parent().parent().attr('data-period').replace(/\+/g, ' '));
        var editDescription = decodeURIComponent(btn.parent().parent().parent().attr('data-description').replace(/\+/g, ' '));

        var random = Math.floor(Math.random() * 10000);

        var editWorkForm = 
            '<div class="pxp-candidate-edit-work">' + 
                '<div class="pxp-candidate-new-work-container">' + 
                    '<div class="pxp-candidate-new-work-header"><b>' + pt_vars.candidate_edit_work_header + '</b></div>' + 
                    '<table width="100%" border="0" cellspacing="0" cellpadding="0">' + 
                        '<tr>' + 
                            '<td width="50%" valign="top">' + 
                                '<div class="form-field pxp-is-custom">' + 
                                    '<label for="edit_candidate_work_title">' + pt_vars.candidate_work_title_label + '</label><br>' + 
                                    '<input name="edit_candidate_work_title" id="edit_candidate_work_title" type="text" value="' + editTitle + '">' + 
                                '</div>' + 
                            '</td>' + 
                            '<td width="30%" valign="top">' + 
                                '<div class="form-field pxp-is-custom">' + 
                                    '<label for="edit_candidate_work_company">' + pt_vars.candidate_work_company_label + '</label><br>' + 
                                    '<input name="edit_candidate_work_company" id="edit_candidate_work_company" type="text" value="' + editCompany + '">' + 
                                '</div>' + 
                            '</td>' + 
                            '<td width="20%" valign="top">' + 
                                '<div class="form-field pxp-is-custom">' + 
                                    '<label for="edit_candidate_work_period">' + pt_vars.candidate_work_period_label + '</label><br>' + 
                                    '<input name="edit_candidate_work_period" id="edit_candidate_work_period" type="text" value="' + editPeriod + '">' + 
                                '</div>' + 
                            '</td>' + 
                        '</tr>' + 
                        '<tr>' + 
                            '<td width="100%" colspan="3">' + 
                                '<div class="form-field pxp-is-custom">' + 
                                    '<label for="edit_candidate_work_description' + random + '">' + pt_vars.candidate_work_description_label + '</label><br>' + 
                                    '<textarea name="edit_candidate_work_description' + random + '" id="edit_candidate_work_description' + random + '"></textarea>' + 
                                '</div>' + 
                            '</td>' + 
                        '</tr>' + 
                    '</table>' + 
                    '<div class="form-field">' + 
                        '<button type="button" id="pxp-ok-edit-work" class="button media-button button-primary">' + pt_vars.ok_btn_label + '</button>' + 
                        '<button type="button" id="pxp-cancel-edit-work" class="button media-button button-default">' + pt_vars.cancel_btn_label + '</button>' + 
                    '</div>' + 
                '</div>' + 
            '</div>';

        btn.parent().parent().hide();
        btn.parent().parent().parent().append(editWorkForm);

        tinyMCE.init({
            selector: '#edit_candidate_work_description' + random,
            plugins: 'lists, link',
            toolbar: 'bold italic underline blockquote strikethrough bullist numlist alignleft aligncenter alignright undo redo link fullscreen',
            height: 240,
            menubar: false,
        });
        tinyMCE.get('edit_candidate_work_description' + random).setContent(editDescription);

        $('#pxp-candidate-work-list').sortable('disable');
        $('#pxp-candidate-work-list .list-group-item').css('cursor', 'auto');
        $('.pxp-edit-candidate-work').hide();
        $('.pxp-del-candidate-work').hide();
        $('.pxp-edit-new-candidate-work').hide();
        $('.pxp-del-new-candidate-work').hide();
        $('#pxp-add-candidate-work-btn').hide();
        $('.pxp-candidate-new-work').hide();

        $('#pxp-ok-edit-work').on('click', function(event) {
            var eTitle       = $(this).parent().parent().find('#edit_candidate_work_title').val();
            var eCompany     = $(this).parent().parent().find('#edit_candidate_work_company').val();
            var ePeriod      = $(this).parent().parent().find('#edit_candidate_work_period').val();
            var eDescription = tinyMCE.get('edit_candidate_work_description' + random).getContent();
            var listElem     = $(this).parent().parent().parent().parent();

            listElem.attr({
                'data-title'      : fixedEncodeURIComponent(eTitle),
                'data-company'    : fixedEncodeURIComponent(eCompany),
                'data-period'     : fixedEncodeURIComponent(ePeriod),
                'data-description': fixedEncodeURIComponent(eDescription)
            });

            listElem.find('.pxp-candidate-work-list-item-title > b').text(eTitle);
            listElem.find('.pxp-candidate-work-list-item-company').text(eCompany);

            $(this).parent().parent().parent().remove();
            listElem.find('.pxp-candidate-work-list-item').show();

            $('#pxp-candidate-work-list').sortable('enable');
            $('#pxp-candidate-work-list .list-group-item').css('cursor', 'move');
            $('.pxp-edit-candidate-work').show();
            $('.pxp-del-candidate-work').show();
            $('.pxp-edit-new-candidate-work').show();
            $('.pxp-del-new-candidate-work').show();
            $('#pxp-add-candidate-work-btn').show();

            dataWorks.works = [];
            $('#pxp-candidate-work-list li').each(function(index, el) {
                dataWorks.works.push({
                    'title'      : decodeURIComponent($(this).attr('data-title').replace(/\+/g, ' ')),
                    'company'    : decodeURIComponent($(this).attr('data-company').replace(/\+/g, ' ')),
                    'period'     : decodeURIComponent($(this).attr('data-period').replace(/\+/g, ' ')),
                    'description': decodeURIComponent($(this).attr('data-description').replace(/\+/g, ' '))
                });
            });

            $('#candidate_work').val(fixedEncodeURIComponent(JSON.stringify(dataWorks)));
        });

        $('#pxp-cancel-edit-work').on('click', function(event) {
            var listElem = $(this).parent().parent().parent().parent();

            $(this).parent().parent().parent().remove();
            listElem.find('.pxp-candidate-work-list-item').show();

            $('#pxp-candidate-work-list').sortable('enable');
            $('#pxp-candidate-work-list .list-group-item').css('cursor', 'move');
            $('.pxp-edit-candidate-work').show();
            $('.pxp-del-candidate-work').show();
            $('.pxp-edit-new-candidate-work').show();
            $('.pxp-del-new-candidate-work').show();
            $('#pxp-add-candidate-work-btn').show();
        });
    }

    // Candidate Education and Training
    if ($('#candidate_edu').length > 0) {
        var dataEdu = {
            'edus' : []
        }
        var edu = '';
        var eduRaw = $('#candidate_edu').val();

        if (eduRaw != '') {
            edu = jsonParser(decodeURIComponent(eduRaw.replace(/\+/g, ' ')));

            if (edu !== null) {
                dataEdu = edu;
            }
        }

        $('#pxp-add-candidate-edu-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-candidate-new-edu').show();
        });

        $('#pxp-ok-edu').on('click', function(event) {
            event.preventDefault();

            var title       = $('#candidate_edu_title').val();
            var school      = $('#candidate_edu_school').val();
            var period      = $('#candidate_edu_period').val();
            var description = $('#candidate_edu_description').val();

            dataEdu.edus.push({
                'title'      : title,
                'school'     : school,
                'period'     : period,
                'description': description
            });

            $('#candidate_edu').val(fixedEncodeURIComponent(JSON.stringify(dataEdu)));

            $('#pxp-candidate-edu-list').append(
                '<li class="list-group-item"' + 
                    'data-title="' + title + '" ' + 
                    'data-school="' + school + '" ' + 
                    'data-period="' + period + '" ' + 
                    'data-description="' + description + '" ' + 
                '>' + 
                    '<div class="pxp-candidate-edu-list-item">' + 
                        '<div class="pxp-candidate-edu-list-item-title"><b>' + title + '</b></div>' + 
                        '<div class="pxp-candidate-edu-list-item-school">' + school + '</div>' + 
                        '<div class="pxp-candidate-edu-list-item-btns">' + 
                            '<a href="javascript:void(0);" class="pxp-list-edit-btn pxp-edit-new-candidate-edu"><span class="fa fa-pencil"></span></a>' + 
                            '<a href="javascript:void(0);" class="pxp-list-del-btn pxp-del-new-candidate-edu"><span class="fa fa-trash-o"></span></a>' + 
                        '</div>' + 
                    '</div>' + 
                '</li>'
            );

            $('#candidate_edu_title').val('');
            $('#candidate_edu_school').val('');
            $('#candidate_edu_period').val('');
            $('#candidate_edu_description').val('');

            $('.pxp-candidate-new-edu').hide();
            $('#pxp-add-candidate-edu-btn').show();

            $('.pxp-edit-new-candidate-edu').unbind('click').on('click', function(event) {
                editCandidateEducation($(this));
            });
            $('.pxp-del-new-candidate-edu').unbind('click').on('click', function(event) {
                delCandidateEducation($(this));
            });
        });

        $('#pxp-cancel-edu').on('click', function(event) {
            event.preventDefault();

            $('#candidate_edu_title').val('');
            $('#candidate_edu_school').val('');
            $('#candidate_edu_period').val('');
            $('#candidate_edu_description').val('');

            $('.pxp-candidate-new-edu').hide();
            $('#pxp-add-candidate-edu-btn').show();
        });

        $('#pxp-candidate-edu-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                dataEdu.edus = [];

                $('#pxp-candidate-edu-list li').each(function(index, el) {
                    dataEdu.edus.push({
                        'title'      : $(this).attr('data-title'),
                        'school'     : $(this).attr('data-school'),
                        'period'     : $(this).attr('data-period'),
                        'description': $(this).attr('data-description')
                    });
                });

                $('#candidate_edu').val(fixedEncodeURIComponent(JSON.stringify(dataEdu)));
            }
        }).disableSelection();

        $('.pxp-edit-candidate-edu').on('click', function(event) {
            editCandidateEducation($(this));
        });
        $('.pxp-del-candidate-edu').on('click', function(event) {
            delCandidateEducation($(this));
        });
    }

    function delCandidateEducation(btn) {
        btn.parent().parent().parent().remove();

        dataEdu.edus = [];

        $('#pxp-candidate-edu-list li').each(function(index, el) {
            dataEdu.edus.push({
                'title'      : $(this).attr('data-title'),
                'school'     : $(this).attr('data-school'),
                'period'     : $(this).attr('data-period'),
                'description': $(this).attr('data-description')
            });
        });

        $('#candidate_edu').val(fixedEncodeURIComponent(JSON.stringify(dataEdu)));
    }

    function editCandidateEducation(btn) {
        var editTitle       = btn.parent().parent().parent().attr('data-title');
        var editSchool      = btn.parent().parent().parent().attr('data-school');
        var editPeriod      = btn.parent().parent().parent().attr('data-period');
        var editDescription = btn.parent().parent().parent().attr('data-description');

        var editEducationForm = 
            '<div class="pxp-candidate-edit-edu">' + 
                '<div class="pxp-candidate-new-edu-container">' + 
                    '<div class="pxp-candidate-new-edu-header"><b>' + pt_vars.candidate_edit_edu_header + '</b></div>' + 
                    '<table width="100%" border="0" cellspacing="0" cellpadding="0">' + 
                        '<tr>' + 
                            '<td width="50%" valign="top">' + 
                                '<div class="form-field pxp-is-custom">' + 
                                    '<label for="edit_candidate_edu_title">' + pt_vars.candidate_edu_title_label + '</label><br>' + 
                                    '<input name="edit_candidate_edu_title" id="edit_candidate_edu_title" type="text" value="' + editTitle + '">' + 
                                '</div>' + 
                            '</td>' + 
                            '<td width="30%" valign="top">' + 
                                '<div class="form-field pxp-is-custom">' + 
                                    '<label for="edit_candidate_edu_school">' + pt_vars.candidate_edu_school_label + '</label><br>' + 
                                    '<input name="edit_candidate_edu_school" id="edit_candidate_edu_school" type="text" value="' + editSchool + '">' + 
                                '</div>' + 
                            '</td>' + 
                            '<td width="20%" valign="top">' + 
                                '<div class="form-field pxp-is-custom">' + 
                                    '<label for="edit_candidate_edu_period">' + pt_vars.candidate_edu_period_label + '</label><br>' + 
                                    '<input name="edit_candidate_edu_period" id="edit_candidate_edu_period" type="text" value="' + editPeriod + '">' + 
                                '</div>' + 
                            '</td>' + 
                        '</tr>' + 
                        '<tr>' + 
                            '<td width="100%" colspan="3">' + 
                                '<div class="form-field pxp-is-custom">' + 
                                    '<label for="edit_candidate_edu_description">' + pt_vars.candidate_edu_description_label + '</label><br>' + 
                                    '<textarea name="edit_candidate_edu_description" id="edit_candidate_edu_description" style="width: 100%; height: 100px;">' + editDescription + '</textarea>' + 
                                '</div>' + 
                            '</td>' + 
                        '</tr>' + 
                    '</table>' + 
                    '<div class="form-field">' + 
                        '<button type="button" id="pxp-ok-edit-edu" class="button media-button button-primary">' + pt_vars.ok_btn_label + '</button>' + 
                        '<button type="button" id="pxp-cancel-edit-edu" class="button media-button button-default">' + pt_vars.cancel_btn_label + '</button>' + 
                    '</div>' + 
                '</div>' + 
            '</div>';

        btn.parent().parent().hide();
        btn.parent().parent().parent().append(editEducationForm);

        $('#pxp-candidate-edu-list').sortable('disable');
        $('#pxp-candidate-edu-list .list-group-item').css('cursor', 'auto');
        $('.pxp-edit-candidate-edu').hide();
        $('.pxp-del-candidate-edu').hide();
        $('.pxp-edit-new-candidate-edu').hide();
        $('.pxp-del-new-candidate-edu').hide();
        $('#pxp-add-candidate-edu-btn').hide();
        $('.pxp-candidate-new-edu').hide();

        $('#pxp-ok-edit-edu').on('click', function(event) {
            var eTitle       = $(this).parent().parent().find('#edit_candidate_edu_title').val();
            var eSchool      = $(this).parent().parent().find('#edit_candidate_edu_school').val();
            var ePeriod      = $(this).parent().parent().find('#edit_candidate_edu_period').val();
            var eDescription = $(this).parent().parent().find('#edit_candidate_edu_description').val();
            var listElem     = $(this).parent().parent().parent().parent();

            listElem.attr({
                'data-title'      : eTitle,
                'data-school'     : eSchool,
                'data-period'     : ePeriod,
                'data-description': eDescription
            });

            listElem.find('.pxp-candidate-edu-list-item-title > b').text(eTitle);
            listElem.find('.pxp-candidate-edu-list-item-school').text(eSchool);

            $(this).parent().parent().parent().remove();
            listElem.find('.pxp-candidate-edu-list-item').show();

            $('#pxp-candidate-edu-list').sortable('enable');
            $('#pxp-candidate-edu-list .list-group-item').css('cursor', 'move');
            $('.pxp-edit-candidate-edu').show();
            $('.pxp-del-candidate-edu').show();
            $('.pxp-edit-new-candidate-edu').show();
            $('.pxp-del-new-candidate-edu').show();
            $('#pxp-add-candidate-edu-btn').show();

            dataEdu.edus = [];
            $('#pxp-candidate-edu-list li').each(function(index, el) {
                dataEdu.edus.push({
                    'title'      : $(this).attr('data-title'),
                    'school'     : $(this).attr('data-school'),
                    'period'     : $(this).attr('data-period'),
                    'description': $(this).attr('data-description'),
                });
            });

            $('#candidate_edu').val(fixedEncodeURIComponent(JSON.stringify(dataEdu)));
        });

        $('#pxp-cancel-edit-edu').on('click', function(event) {
            var listElem  = $(this).parent().parent().parent().parent();

            $(this).parent().parent().parent().remove();
            listElem.find('.pxp-candidate-edu-list-item').show();

            $('#pxp-candidate-edu-list').sortable('enable');
            $('#pxp-candidate-edu-list .list-group-item').css('cursor', 'move');
            $('.pxp-edit-candidate-edu').show();
            $('.pxp-del-candidate-edu').show();
            $('.pxp-edit-new-candidate-edu').show();
            $('.pxp-del-new-candidate-edu').show();
            $('#pxp-add-candidate-edu-btn').show();
        });
    }

    // Upload candidate resume 
    $('#pxp-add-candidate-cv-btn').click(function(event) {
        event.preventDefault();

        var frame = wp.media({
            title: pt_vars.candidate_cv_title,
            button: {
                text: pt_vars.candidate_cv_btn
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                $('#candidate_cv').val(value.id);
                $('.pxp-download-candidate-cv').attr('href', value.url);
                $('.pxp-candidate-cv-filename').text(value.filename);
                $('.pxp-candidate-cv-container').addClass('pxp-show');
            });
        });

        frame.open();
    });

    // Delete candidate resume
    $('.pxp-del-candidate-cv').on('click', function() {
        $('#candidate_cv').val('');
        $('.pxp-candidate-cv-filename').empty();
        $('.pxp-download-candidate-cv').attr('href', '#');
        $('.pxp-candidate-cv-container').removeClass('pxp-show');
    });

    // Upload job cover 
    $('.pxp-job-cover-image-placeholder').click(function(event) {
        event.preventDefault();
        var placeholder = $(this);

        var frame = wp.media({
            title: pt_vars.job_cover_title,
            button: {
                text: pt_vars.job_cover_btn
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

    // Delete job cover
    $('.pxp-delete-job-cover-image').click(function() {
        var delBtn = $(this);

        delBtn.parent().parent().find('input').val('');
        delBtn.parent().find('.pxp-job-cover-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/cover-placeholder.png)');
        delBtn.parent().removeClass('pxp-has-image');
    });

    // Icons modal
    function openIconsWindow(btn) {
        var faIcons = ['fa fa-500px', 'fa fa-address-book', 'fa fa-address-book-o', 'fa fa-address-card', 'fa fa-address-card-o', 'fa fa-adjust', 'fa fa-adn', 'fa fa-align-center', 'fa fa-align-justify', 'fa fa-align-left', 'fa fa-align-right', 'fa fa-amazon', 'fa fa-ambulance', 'fa fa-american-sign-language-interpreting', 'fa fa-anchor', 'fa fa-android', 'fa fa-angellist', 'fa fa-angle-double-down', 'fa fa-angle-double-left', 'fa fa-angle-double-right', 'fa fa-angle-double-up', 'fa fa-angle-down', 'fa fa-angle-left', 'fa fa-angle-right', 'fa fa-angle-up', 'fa fa-apple', 'fa fa-archive', 'fa fa-area-chart', 'fa fa-arrow-circle-down', 'fa fa-arrow-circle-left', 'fa fa-arrow-circle-o-down', 'fa fa-arrow-circle-o-left', 'fa fa-arrow-circle-o-right', 'fa fa-arrow-circle-o-up', 'fa fa-arrow-circle-right', 'fa fa-arrow-circle-up', 'fa fa-arrow-down', 'fa fa-arrow-left', 'fa fa-arrow-right', 'fa fa-arrow-up', 'fa fa-arrows', 'fa fa-arrows-alt', 'fa fa-arrows-h', 'fa fa-arrows-v', 'fa fa-asl-interpreting', 'fa fa-assistive-listening-systems', 'fa fa-asterisk', 'fa fa-at', 'fa fa-audio-description', 'fa fa-automobile', 'fa fa-backward', 'fa fa-balance-scale', 'fa fa-ban', 'fa fa-bandcamp', 'fa fa-bank', 'fa fa-bar-chart', 'fa fa-bar-chart-o', 'fa fa-barcode', 'fa fa-bars', 'fa fa-bath', 'fa fa-bathtub', 'fa fa-battery', 'fa fa-battery-0', 'fa fa-battery-1', 'fa fa-battery-2', 'fa fa-battery-3', 'fa fa-battery-4', 'fa fa-battery-empty', 'fa fa-battery-full', 'fa fa-battery-half', 'fa fa-battery-quarter', 'fa fa-battery-three-quarters', 'fa fa-bed', 'fa fa-beer', 'fa fa-behance', 'fa fa-behance-square', 'fa fa-bell', 'fa fa-bell-o', 'fa fa-bell-slash', 'fa fa-bell-slash-o', 'fa fa-bicycle', 'fa fa-binoculars', 'fa fa-birthday-cake', 'fa fa-bitbucket', 'fa fa-bitbucket-square', 'fa fa-bitcoin', 'fa fa-black-tie', 'fa fa-blind', 'fa fa-bluetooth', 'fa fa-bluetooth-b', 'fa fa-bold', 'fa fa-bolt', 'fa fa-bomb', 'fa fa-book', 'fa fa-bookmark', 'fa fa-bookmark-o', 'fa fa-braille', 'fa fa-briefcase', 'fa fa-btc', 'fa fa-bug', 'fa fa-building', 'fa fa-building-o', 'fa fa-bullhorn', 'fa fa-bullseye', 'fa fa-bus', 'fa fa-buysellads', 'fa fa-cab', 'fa fa-calculator', 'fa fa-calendar', 'fa fa-calendar-check-o', 'fa fa-calendar-minus-o', 'fa fa-calendar-o', 'fa fa-calendar-plus-o', 'fa fa-calendar-times-o', 'fa fa-camera', 'fa fa-camera-retro', 'fa fa-car', 'fa fa-caret-down', 'fa fa-caret-left', 'fa fa-caret-right', 'fa fa-caret-square-o-down', 'fa fa-caret-square-o-left', 'fa fa-caret-square-o-right', 'fa fa-caret-square-o-up', 'fa fa-caret-up', 'fa fa-cart-arrow-down', 'fa fa-cart-plus', 'fa fa-cc', 'fa fa-cc-amex', 'fa fa-cc-diners-club', 'fa fa-cc-discover', 'fa fa-cc-jcb', 'fa fa-cc-mastercard', 'fa fa-cc-paypal', 'fa fa-cc-stripe', 'fa fa-cc-visa', 'fa fa-certificate', 'fa fa-chain', 'fa fa-chain-broken', 'fa fa-check', 'fa fa-check-circle', 'fa fa-check-circle-o', 'fa fa-check-square', 'fa fa-check-square-o', 'fa fa-chevron-circle-down', 'fa fa-chevron-circle-left', 'fa fa-chevron-circle-right', 'fa fa-chevron-circle-up', 'fa fa-chevron-down', 'fa fa-chevron-left', 'fa fa-chevron-right', 'fa fa-chevron-up', 'fa fa-child', 'fa fa-chrome', 'fa fa-circle', 'fa fa-circle-o', 'fa fa-circle-o-notch', 'fa fa-circle-thin', 'fa fa-clipboard', 'fa fa-clock-o', 'fa fa-clone', 'fa fa-close', 'fa fa-cloud', 'fa fa-cloud-download', 'fa fa-cloud-upload', 'fa fa-cny', 'fa fa-code', 'fa fa-code-fork', 'fa fa-codepen', 'fa fa-codiepie', 'fa fa-coffee', 'fa fa-cog', 'fa fa-cogs', 'fa fa-columns', 'fa fa-comment', 'fa fa-comment-o', 'fa fa-commenting', 'fa fa-commenting-o', 'fa fa-comments', 'fa fa-comments-o', 'fa fa-compass', 'fa fa-compress', 'fa fa-connectdevelop', 'fa fa-contao', 'fa fa-copy', 'fa fa-copyright', 'fa fa-creative-commons', 'fa fa-credit-card', 'fa fa-credit-card-alt', 'fa fa-crop', 'fa fa-crosshairs', 'fa fa-css3', 'fa fa-cube', 'fa fa-cubes', 'fa fa-cut', 'fa fa-cutlery', 'fa fa-dashboard', 'fa fa-dashcube', 'fa fa-database', 'fa fa-deaf', 'fa fa-deafness', 'fa fa-dedent', 'fa fa-delicious', 'fa fa-desktop', 'fa fa-deviantart', 'fa fa-diamond', 'fa fa-digg', 'fa fa-dollar', 'fa fa-dot-circle-o', 'fa fa-download', 'fa fa-dribbble', 'fa fa-drivers-license', 'fa fa-drivers-license-o', 'fa fa-dropbox', 'fa fa-drupal', 'fa fa-edge', 'fa fa-edit', 'fa fa-eercast', 'fa fa-eject', 'fa fa-ellipsis-h', 'fa fa-ellipsis-v', 'fa fa-empire', 'fa fa-envelope', 'fa fa-envelope-o', 'fa fa-envelope-open', 'fa fa-envelope-open-o', 'fa fa-envelope-square', 'fa fa-envira', 'fa fa-eraser', 'fa fa-etsy', 'fa fa-eur', 'fa fa-euro', 'fa fa-exchange', 'fa fa-exclamation', 'fa fa-exclamation-circle', 'fa fa-exclamation-triangle', 'fa fa-expand', 'fa fa-expeditedssl', 'fa fa-external-link', 'fa fa-external-link-square', 'fa fa-eye', 'fa fa-eye-slash', 'fa fa-eyedropper', 'fa fa-fa', 'fa fa-facebook', 'fa fa-facebook-f', 'fa fa-facebook-official', 'fa fa-facebook-square', 'fa fa-fast-backward', 'fa fa-fast-forward', 'fa fa-fax', 'fa fa-feed', 'fa fa-female', 'fa fa-fighter-jet', 'fa fa-file', 'fa fa-file-archive-o', 'fa fa-file-audio-o', 'fa fa-file-code-o', 'fa fa-file-excel-o', 'fa fa-file-image-o', 'fa fa-file-movie-o', 'fa fa-file-o', 'fa fa-file-pdf-o', 'fa fa-file-photo-o', 'fa fa-file-picture-o', 'fa fa-file-powerpoint-o', 'fa fa-file-sound-o', 'fa fa-file-text', 'fa fa-file-text-o', 'fa fa-file-video-o', 'fa fa-file-word-o', 'fa fa-file-zip-o', 'fa fa-files-o', 'fa fa-film', 'fa fa-filter', 'fa fa-fire', 'fa fa-fire-extinguisher', 'fa fa-firefox', 'fa fa-first-order', 'fa fa-flag', 'fa fa-flag-checkered', 'fa fa-flag-o', 'fa fa-flash', 'fa fa-flask', 'fa fa-flickr', 'fa fa-floppy-o', 'fa fa-folder', 'fa fa-folder-o', 'fa fa-folder-open', 'fa fa-folder-open-o', 'fa fa-font', 'fa fa-font-awesome', 'fa fa-fonticons', 'fa fa-fort-awesome', 'fa fa-forumbee', 'fa fa-forward', 'fa fa-foursquare', 'fa fa-free-code-camp', 'fa fa-frown-o', 'fa fa-futbol-o', 'fa fa-gamepad', 'fa fa-gavel', 'fa fa-gbp', 'fa fa-ge', 'fa fa-gear', 'fa fa-gears', 'fa fa-genderless', 'fa fa-get-pocket', 'fa fa-gg', 'fa fa-gg-circle', 'fa fa-gift', 'fa fa-git', 'fa fa-git-square', 'fa fa-github', 'fa fa-github-alt', 'fa fa-github-square', 'fa fa-gitlab', 'fa fa-gittip', 'fa fa-glass', 'fa fa-glide', 'fa fa-glide-g', 'fa fa-globe', 'fa fa-google', 'fa fa-google-plus', 'fa fa-google-plus-circle', 'fa fa-google-plus-official', 'fa fa-google-plus-square', 'fa fa-google-wallet', 'fa fa-graduation-cap', 'fa fa-gratipay', 'fa fa-grav', 'fa fa-group', 'fa fa-h-square', 'fa fa-hacker-news', 'fa fa-hand-grab-o', 'fa fa-hand-lizard-o', 'fa fa-hand-o-down', 'fa fa-hand-o-left', 'fa fa-hand-o-right', 'fa fa-hand-o-up', 'fa fa-hand-paper-o', 'fa fa-hand-peace-o', 'fa fa-hand-pointer-o', 'fa fa-hand-rock-o', 'fa fa-hand-scissors-o', 'fa fa-hand-spock-o', 'fa fa-hand-stop-o', 'fa fa-handshake-o', 'fa fa-hard-of-hearing', 'fa fa-hashtag', 'fa fa-hdd-o', 'fa fa-header', 'fa fa-headphones', 'fa fa-heart', 'fa fa-heart-o', 'fa fa-heartbeat', 'fa fa-history', 'fa fa-home', 'fa fa-hospital-o', 'fa fa-hotel', 'fa fa-hourglass', 'fa fa-hourglass-1', 'fa fa-hourglass-2', 'fa fa-hourglass-3', 'fa fa-hourglass-end', 'fa fa-hourglass-half', 'fa fa-hourglass-o', 'fa fa-hourglass-start', 'fa fa-houzz', 'fa fa-html5', 'fa fa-i-cursor', 'fa fa-id-badge', 'fa fa-id-card', 'fa fa-id-card-o', 'fa fa-ils', 'fa fa-image', 'fa fa-imdb', 'fa fa-inbox', 'fa fa-indent', 'fa fa-industry', 'fa fa-info', 'fa fa-info-circle', 'fa fa-inr', 'fa fa-instagram', 'fa fa-institution', 'fa fa-internet-explorer', 'fa fa-intersex', 'fa fa-ioxhost', 'fa fa-italic', 'fa fa-joomla', 'fa fa-jpy', 'fa fa-jsfiddle', 'fa fa-key', 'fa fa-keyboard-o', 'fa fa-krw', 'fa fa-language', 'fa fa-laptop', 'fa fa-lastfm', 'fa fa-lastfm-square', 'fa fa-leaf', 'fa fa-leanpub', 'fa fa-legal', 'fa fa-lemon-o', 'fa fa-level-down', 'fa fa-level-up', 'fa fa-life-bouy', 'fa fa-life-buoy', 'fa fa-life-ring', 'fa fa-life-saver', 'fa fa-lightbulb-o', 'fa fa-line-chart', 'fa fa-link', 'fa fa-linkedin', 'fa fa-linkedin-square', 'fa fa-linode', 'fa fa-linux', 'fa fa-list', 'fa fa-list-alt', 'fa fa-list-ol', 'fa fa-list-ul', 'fa fa-location-arrow', 'fa fa-lock', 'fa fa-long-arrow-down', 'fa fa-long-arrow-left', 'fa fa-long-arrow-right', 'fa fa-long-arrow-up', 'fa fa-low-vision', 'fa fa-magic', 'fa fa-magnet', 'fa fa-mail-forward', 'fa fa-mail-reply', 'fa fa-mail-reply-all', 'fa fa-male', 'fa fa-map', 'fa fa-map-marker', 'fa fa-map-o', 'fa fa-map-pin', 'fa fa-map-signs', 'fa fa-mars', 'fa fa-mars-double', 'fa fa-mars-stroke', 'fa fa-mars-stroke-h', 'fa fa-mars-stroke-v', 'fa fa-maxcdn', 'fa fa-meanpath', 'fa fa-medium', 'fa fa-medkit', 'fa fa-meetup', 'fa fa-meh-o', 'fa fa-mercury', 'fa fa-microchip', 'fa fa-microphone', 'fa fa-microphone-slash', 'fa fa-minus', 'fa fa-minus-circle', 'fa fa-minus-square', 'fa fa-minus-square-o', 'fa fa-mixcloud', 'fa fa-mobile', 'fa fa-mobile-phone', 'fa fa-modx', 'fa fa-money', 'fa fa-moon-o', 'fa fa-mortar-board', 'fa fa-motorcycle', 'fa fa-mouse-pointer', 'fa fa-music', 'fa fa-navicon', 'fa fa-neuter', 'fa fa-newspaper-o', 'fa fa-object-group', 'fa fa-object-ungroup', 'fa fa-odnoklassniki', 'fa fa-odnoklassniki-square', 'fa fa-opencart', 'fa fa-openid', 'fa fa-opera', 'fa fa-optin-monster', 'fa fa-outdent', 'fa fa-pagelines', 'fa fa-paint-brush', 'fa fa-paper-plane', 'fa fa-paper-plane-o', 'fa fa-paperclip', 'fa fa-paragraph', 'fa fa-paste', 'fa fa-pause', 'fa fa-pause-circle', 'fa fa-pause-circle-o', 'fa fa-paw', 'fa fa-paypal', 'fa fa-pencil', 'fa fa-pencil-square', 'fa fa-pencil-square-o', 'fa fa-percent', 'fa fa-phone', 'fa fa-phone-square', 'fa fa-photo', 'fa fa-picture-o', 'fa fa-pie-chart', 'fa fa-pied-piper', 'fa fa-pied-piper-alt', 'fa fa-pied-piper-pp', 'fa fa-pinterest', 'fa fa-pinterest-p', 'fa fa-pinterest-square', 'fa fa-plane', 'fa fa-play', 'fa fa-play-circle', 'fa fa-play-circle-o', 'fa fa-plug', 'fa fa-plus', 'fa fa-plus-circle', 'fa fa-plus-square', 'fa fa-plus-square-o', 'fa fa-podcast', 'fa fa-power-off', 'fa fa-print', 'fa fa-product-hunt', 'fa fa-puzzle-piece', 'fa fa-qq', 'fa fa-qrcode', 'fa fa-question', 'fa fa-question-circle', 'fa fa-question-circle-o', 'fa fa-quora', 'fa fa-quote-left', 'fa fa-quote-right', 'fa fa-ra', 'fa fa-random', 'fa fa-ravelry', 'fa fa-rebel', 'fa fa-recycle', 'fa fa-reddit', 'fa fa-reddit-alien', 'fa fa-reddit-square', 'fa fa-refresh', 'fa fa-registered', 'fa fa-remove', 'fa fa-renren', 'fa fa-reorder', 'fa fa-repeat', 'fa fa-reply', 'fa fa-reply-all', 'fa fa-resistance', 'fa fa-retweet', 'fa fa-rmb', 'fa fa-road', 'fa fa-rocket', 'fa fa-rotate-left', 'fa fa-rotate-right', 'fa fa-rouble', 'fa fa-rss', 'fa fa-rss-square', 'fa fa-rub', 'fa fa-ruble', 'fa fa-rupee', 'fa fa-s15', 'fa fa-safari', 'fa fa-save', 'fa fa-scissors', 'fa fa-scribd', 'fa fa-search', 'fa fa-search-minus', 'fa fa-search-plus', 'fa fa-sellsy', 'fa fa-send', 'fa fa-send-o', 'fa fa-server', 'fa fa-share', 'fa fa-share-alt', 'fa fa-share-alt-square', 'fa fa-share-square', 'fa fa-share-square-o', 'fa fa-shekel', 'fa fa-sheqel', 'fa fa-shield', 'fa fa-ship', 'fa fa-shirtsinbulk', 'fa fa-shopping-bag', 'fa fa-shopping-basket', 'fa fa-shopping-cart', 'fa fa-shower', 'fa fa-sign-in', 'fa fa-sign-language', 'fa fa-sign-out', 'fa fa-signal', 'fa fa-signing', 'fa fa-simplybuilt', 'fa fa-sitemap', 'fa fa-skyatlas', 'fa fa-skype', 'fa fa-slack', 'fa fa-sliders', 'fa fa-slideshare', 'fa fa-smile-o', 'fa fa-snapchat', 'fa fa-snapchat-ghost', 'fa fa-snapchat-square', 'fa fa-snowflake-o', 'fa fa-soccer-ball-o', 'fa fa-sort', 'fa fa-sort-alpha-asc', 'fa fa-sort-alpha-desc', 'fa fa-sort-amount-asc', 'fa fa-sort-amount-desc', 'fa fa-sort-asc', 'fa fa-sort-desc', 'fa fa-sort-down', 'fa fa-sort-numeric-asc', 'fa fa-sort-numeric-desc', 'fa fa-sort-up', 'fa fa-soundcloud', 'fa fa-space-shuttle', 'fa fa-spinner', 'fa fa-spoon', 'fa fa-spotify', 'fa fa-square', 'fa fa-square-o', 'fa fa-stack-exchange', 'fa fa-stack-overflow', 'fa fa-star', 'fa fa-star-half', 'fa fa-star-half-empty', 'fa fa-star-half-full', 'fa fa-star-half-o', 'fa fa-star-o', 'fa fa-steam', 'fa fa-steam-square', 'fa fa-step-backward', 'fa fa-step-forward', 'fa fa-stethoscope', 'fa fa-sticky-note', 'fa fa-sticky-note-o', 'fa fa-stop', 'fa fa-stop-circle', 'fa fa-stop-circle-o', 'fa fa-street-view', 'fa fa-strikethrough', 'fa fa-stumbleupon', 'fa fa-stumbleupon-circle', 'fa fa-subscript', 'fa fa-subway', 'fa fa-suitcase', 'fa fa-sun-o', 'fa fa-superpowers', 'fa fa-superscript', 'fa fa-support', 'fa fa-table', 'fa fa-tablet', 'fa fa-tachometer', 'fa fa-tag', 'fa fa-tags', 'fa fa-tasks', 'fa fa-taxi', 'fa fa-telegram', 'fa fa-television', 'fa fa-tencent-weibo', 'fa fa-terminal', 'fa fa-text-height', 'fa fa-text-width', 'fa fa-th', 'fa fa-th-large', 'fa fa-th-list', 'fa fa-themeisle', 'fa fa-thermometer', 'fa fa-thermometer-0', 'fa fa-thermometer-1', 'fa fa-thermometer-2', 'fa fa-thermometer-3', 'fa fa-thermometer-4', 'fa fa-thermometer-empty', 'fa fa-thermometer-full', 'fa fa-thermometer-half', 'fa fa-thermometer-quarter', 'fa fa-thermometer-three-quarters', 'fa fa-thumb-tack', 'fa fa-thumbs-down', 'fa fa-thumbs-o-down', 'fa fa-thumbs-o-up', 'fa fa-thumbs-up', 'fa fa-ticket', 'fa fa-times', 'fa fa-times-circle', 'fa fa-times-circle-o', 'fa fa-times-rectangle', 'fa fa-times-rectangle-o', 'fa fa-tint', 'fa fa-toggle-down', 'fa fa-toggle-left', 'fa fa-toggle-off', 'fa fa-toggle-on', 'fa fa-toggle-right', 'fa fa-toggle-up', 'fa fa-trademark', 'fa fa-train', 'fa fa-transgender', 'fa fa-transgender-alt', 'fa fa-trash', 'fa fa-trash-o', 'fa fa-tree', 'fa fa-trello', 'fa fa-tripadvisor', 'fa fa-trophy', 'fa fa-truck', 'fa fa-try', 'fa fa-tty', 'fa fa-tumblr', 'fa fa-tumblr-square', 'fa fa-turkish-lira', 'fa fa-tv', 'fa fa-twitch', 'fa fa-twitter', 'fa fa-twitter-square', 'fa fa-umbrella', 'fa fa-underline', 'fa fa-undo', 'fa fa-universal-access', 'fa fa-university', 'fa fa-unlink', 'fa fa-unlock', 'fa fa-unlock-alt', 'fa fa-unsorted', 'fa fa-upload', 'fa fa-usb', 'fa fa-usd', 'fa fa-user', 'fa fa-user-circle', 'fa fa-user-circle-o', 'fa fa-user-md', 'fa fa-user-o', 'fa fa-user-plus', 'fa fa-user-secret', 'fa fa-user-times', 'fa fa-users', 'fa fa-vcard', 'fa fa-vcard-o', 'fa fa-venus', 'fa fa-venus-double', 'fa fa-venus-mars', 'fa fa-viacoin', 'fa fa-viadeo', 'fa fa-viadeo-square', 'fa fa-video-camera', 'fa fa-vimeo', 'fa fa-vimeo-square', 'fa fa-vine', 'fa fa-vk', 'fa fa-volume-control-phone', 'fa fa-volume-down', 'fa fa-volume-off', 'fa fa-volume-up', 'fa fa-warning', 'fa fa-wechat', 'fa fa-weibo', 'fa fa-weixin', 'fa fa-whatsapp', 'fa fa-wheelchair', 'fa fa-wheelchair-alt', 'fa fa-wifi', 'fa fa-wikipedia-w', 'fa fa-window-close', 'fa fa-window-close-o', 'fa fa-window-maximize', 'fa fa-window-minimize', 'fa fa-window-restore', 'fa fa-windows', 'fa fa-won', 'fa fa-wordpress', 'fa fa-wpbeginner', 'fa fa-wpexplorer', 'fa fa-wpforms', 'fa fa-wrench', 'fa fa-xing', 'fa fa-xing-square', 'fa fa-y-combinator', 'fa fa-y-combinator-square', 'fa fa-yahoo', 'fa fa-yc', 'fa fa-yc-square', 'fa fa-yelp', 'fa fa-yen', 'fa fa-yoast', 'fa fa-youtube', 'fa fa-youtube-play', 'fa fa-youtube-square'];

        var iconsWindow = 
            `<div 
                tabindex="0" 
                role="dialog" 
                id="pxp-icons-modal" 
                style="position: relative; display: none;"
            >
                <div class="media-modal wp-core-ui">
                    <button type="button" class="media-modal-close">
                        <span class="media-modal-icon">
                            <span class="screen-reader-text">Close</span>
                        </span>
                    </button>
                    <div class="media-modal-content">
                        <div class="media-frame mode-select wp-core-ui">
                            <div class="media-frame-title">
                                <h1>${pt_vars.icons_label}</h1>
                                <div class="pxp-search-icons-wrapper">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-4 pxp-search-icons">
                                            <span class="fa fa-search"></span>
                                            <input 
                                                type="text" 
                                                id="pxp-icons-modal-search-input" 
                                                class="form-control" 
                                                placeholder="${pt_vars.search_icons_placeholder}"
                                            >
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="media-frame-content">
                                <div class="pxp-icons-modal-frame-content">
                                    <div class="pxp-icons-modal-subtitle">
                                        Font Awesome
                                    </div>
                                    <div class="pxp-icons-modal-list pxp-icons-modal-list-fa"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="media-modal-backdrop"></div>
            </div>`;

        $('body').append(iconsWindow);

        $('#pxp-icons-modal .media-modal-close').on('click', function(e) {
            $('#pxp-icons-modal').hide().remove();
            e.preventDefault();
        });
        $('#pxp-icons-modal').on('keyup',function(e) {
            if (e.keyCode == 27) {
               $(this).hide().remove();
               e.preventDefault();
            }
        });

        var faList = '<div class="row">';
        for (var i = 0; i < faIcons.length; i++) {
            faList += 
                `<div class="col-xs-6 col-sm-4 col-md-3 pxp-is-icon">
                    <div class="pxp-icons-modal-item">
                        <span class="${faIcons[i]}"></span> ${faIcons[i]} 
                    </div>
                </div>`;
        }
        faList += '</div>';
        $('#pxp-icons-modal .pxp-icons-modal-list-fa').append(faList);

        $('#pxp-icons-modal-search-input').on('keyup', function() {
            var value = this.value;

            $('.pxp-is-icon').each(function(index) {
                var id = $(this).text();
                $(this).toggle(id.indexOf(value) !== -1);
            });
        });

        var selected = $(btn).find('span').attr('class');
        $('.pxp-icons-modal-item span').each(function() {
            if ($(this).hasClass(selected)) {
                $(this).parent().addClass('pxp-is-selected');
            }
        });

        $('.pxp-icons-modal-item').on('click', function(e) {
            var cName = $(this).find('span').attr('class');

            $(btn).html(`<span class="${cName}"></span> ${cName}`);
            $(btn).prev('input').val(cName);

            $('#pxp-icons-modal').hide().remove();
            e.preventDefault();
        });

        $('#pxp-icons-modal').show().focus();
    }

    $('.pxp-open-icons').click(function(event) {
        openIconsWindow(this);
    });

    $('.pxp-icons-field').each(function(index, el) {
        var fieldValue = $(this).val();

        if (fieldValue != '') {
            $(this)
            .next('.pxp-open-icons')
            .html(
                `<span class="${fieldValue}"></span> ${fieldValue}`
            );
        }
    });

    $('#pxp-set-plan-manually-btn').click(function() {
        var _self = $(this);

        _self.attr('disabled', 'disabled');
        _self.find('.pxp-set-plan-manually-btn-text').hide();
        _self.find('.pxp-set-plan-manually-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: pt_vars.ajaxurl,
            data: {
                'action'    : 'jobster_set_company_membership_manually',
                'company_id': _self.attr('data-id'),
                'plan_id'   : $('#company_plan_manual').val(),
                'security'  : $('#company_noncename').val()
            },
            success: function(data) {
                _self.removeAttr('disabled');
                _self.find('.pxp-set-plan-manually-btn-loading').hide();
                _self.find('.pxp-set-plan-manually-btn-text').show();
            },
            error: function(errorThrown) {}
        });
    });

    // Candidate Gallery / Portfolio
    if ($('#candidate_gallery').length > 0) {
        var galleryFrame;
        var galleryEditFrame;
        var photos = [];
        var editPhotoID;
        var editPhotoItem;
        var galleryIDs = $('#candidate_gallery').val().split(',');

        if (galleryIDs.length > 0) {
            galleryIDs.forEach(function(id) {
                if (id != '') {
                    photos.push(parseInt(id));
                }
            });
        }

        $('#candidate-gallery-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                photos = [];
                $('#candidate-gallery-list li').each(function(index, el) {
                    photos.push(parseInt($(this).attr('data-id')));
                });
                $('#candidate_gallery').val(photos.toString());
            }
        }).disableSelection();

        $('#pxp-add-candidate-gallery-photo-btn').click(function(event) {
            event.preventDefault();

            if(galleryFrame) {
                galleryFrame.open();
                return;
            }

            galleryFrame = wp.media({
                title: pt_vars.candidate_gallery_title,
                button: {
                    text: pt_vars.candidate_gallery_btn
                },
                library: {
                    type: 'image'
                },
                multiple: true
            });

            galleryFrame.on('select', function() {
                var attachment = galleryFrame.state().get('selection').toJSON();

                $.each(attachment, function(index, value) {
                    if ($.inArray(value.id, photos) == -1 && value.id != '') {
                        photos.push(value.id);

                        $('#candidate-gallery-list').append(
                            `<li class="list-group-item" data-id="${value.id}">
                                <div class="pxp-candidate-gallery-list-item">
                                    <img src="${value.url}" />
                                    <div class="list-group-item-info">
                                        <div class="list-group-item-info-title">${value.name}</div>
                                        <div class="list-group-item-info-caption">${value.caption}</div>
                                    </div>
                                    <div class="pxp-list-item-btns">
                                        <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-candidate-gallery-edit-photo-btn"><span class="fa fa-pencil"></span></a>
                                        <a href="javascript:void(0);" class="pxp-list-del-btn pxp-candidate-gallery-delete-photo-btn"><span class="fa fa-trash-o"></span></a>
                                    </div>
                                </div>
                            </li>`
                        );
                    }
                });

                $('#candidate_gallery').val(photos.toString());
            });

            galleryFrame.on('open', function() {
                var selection = galleryFrame.state().get('selection');
                var ids = $('#candidate_gallery').val().split(',');

                ids.forEach(function(id) {
                    var attachment = wp.media.attachment(id);

                    attachment.fetch();
                    selection.add( attachment ? [ attachment ] : [] );
                });
            });

            galleryFrame.open();
        });

        $(document).on('click', '.pxp-candidate-gallery-edit-photo-btn', function(event) {
            event.preventDefault();
            editPhotoItem = $(this);
            editPhotoID = editPhotoItem.parent().parent().parent().attr('data-id');

            if(galleryEditFrame) {
                galleryEditFrame.open();
                return;
            }

            galleryEditFrame = wp.media({
                title: pt_vars.candidate_gallery_title,
                button: {
                    text: pt_vars.candidate_gallery_btn
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });

            galleryEditFrame.on('select', function() {
                var attachment = galleryEditFrame.state().get('selection').toJSON();

                $.each(attachment, function(index, value) {
                    var editPhotoIndex = photos.indexOf(parseInt(editPhotoID));

                    if (editPhotoIndex !== -1 && $.inArray(value.id, photos) === -1) {
                        photos[editPhotoIndex] = value.id;

                        editPhotoItem.parent().parent().parent().attr('data-id', value.id);
                        editPhotoItem.parent().parent().children('img').attr('src', value.url);
                        editPhotoItem.parent().parent().children('.list-group-item-info').children('.list-group-item-info-title').text(value.name);
                        editPhotoItem.parent().parent().children('.list-group-item-info').children('.list-group-item-info-caption').text(value.caption);
                    }

                    $('#candidate_gallery').val(photos.toString());
                });
            });

            galleryEditFrame.on('open', function() {
                var selection = galleryEditFrame.state().get('selection');
                var attachment = wp.media.attachment(editPhotoID);

                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });

            galleryEditFrame.open();
        });

        $(document).on('click', '.pxp-candidate-gallery-delete-photo-btn', function() {
            var delImage = $(this).parent().parent().parent().attr('data-id');

            photos = $.grep(photos, function(id) {
                return id != delImage;
            });

            $('#candidate_gallery').val(photos.toString());
            $(this).parent().parent().parent().remove();
        });
    }

    // Company Gallery
    if ($('#company_gallery').length > 0) {
        var galleryFrame;
        var galleryEditFrame;
        var photos = [];
        var editPhotoID;
        var editPhotoItem;
        var galleryIDs = $('#company_gallery').val().split(',');

        if (galleryIDs.length > 0) {
            galleryIDs.forEach(function(id) {
                if (id != '') {
                    photos.push(parseInt(id));
                }
            });
        }

        $('#company-gallery-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                photos = [];
                $('#company-gallery-list li').each(function(index, el) {
                    photos.push(parseInt($(this).attr('data-id')));
                });
                $('#company_gallery').val(photos.toString());
            }
        }).disableSelection();

        $('#pxp-add-company-gallery-photo-btn').click(function(event) {
            event.preventDefault();

            if(galleryFrame) {
                galleryFrame.open();
                return;
            }

            galleryFrame = wp.media({
                title: pt_vars.company_gallery_title,
                button: {
                    text: pt_vars.company_gallery_btn
                },
                library: {
                    type: 'image'
                },
                multiple: true
            });

            galleryFrame.on('select', function() {
                var attachment = galleryFrame.state().get('selection').toJSON();

                $.each(attachment, function(index, value) {
                    if ($.inArray(value.id, photos) == -1 && value.id != '') {
                        photos.push(value.id);

                        $('#company-gallery-list').append(
                            `<li class="list-group-item" data-id="${value.id}">
                                <div class="pxp-company-gallery-list-item">
                                    <img src="${value.url}" />
                                    <div class="list-group-item-info">
                                        <div class="list-group-item-info-title">${value.name}</div>
                                        <div class="list-group-item-info-caption">${value.caption}</div>
                                    </div>
                                    <div class="pxp-list-item-btns">
                                        <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-company-gallery-edit-photo-btn"><span class="fa fa-pencil"></span></a>
                                        <a href="javascript:void(0);" class="pxp-list-del-btn pxp-company-gallery-delete-photo-btn"><span class="fa fa-trash-o"></span></a>
                                    </div>
                                </div>
                            </li>`
                        );
                    }
                });

                $('#company_gallery').val(photos.toString());
            });

            galleryFrame.on('open', function() {
                var selection = galleryFrame.state().get('selection');
                var ids = $('#company_gallery').val().split(',');

                ids.forEach(function(id) {
                    var attachment = wp.media.attachment(id);

                    attachment.fetch();
                    selection.add( attachment ? [ attachment ] : [] );
                });
            });

            galleryFrame.open();
        });

        $(document).on('click', '.pxp-company-gallery-edit-photo-btn', function(event) {
            event.preventDefault();
            editPhotoItem = $(this);
            editPhotoID = editPhotoItem.parent().parent().parent().attr('data-id');

            if(galleryEditFrame) {
                galleryEditFrame.open();
                return;
            }

            galleryEditFrame = wp.media({
                title: pt_vars.company_gallery_title,
                button: {
                    text: pt_vars.company_gallery_btn
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });

            galleryEditFrame.on('select', function() {
                var attachment = galleryEditFrame.state().get('selection').toJSON();

                $.each(attachment, function(index, value) {
                    var editPhotoIndex = photos.indexOf(parseInt(editPhotoID));

                    if (editPhotoIndex !== -1 && $.inArray(value.id, photos) === -1) {
                        photos[editPhotoIndex] = value.id;

                        editPhotoItem.parent().parent().parent().attr('data-id', value.id);
                        editPhotoItem.parent().parent().children('img').attr('src', value.url);
                        editPhotoItem.parent().parent().children('.list-group-item-info').children('.list-group-item-info-title').text(value.name);
                        editPhotoItem.parent().parent().children('.list-group-item-info').children('.list-group-item-info-caption').text(value.caption);
                    }

                    $('#candidate_gallery').val(photos.toString());
                });
            });

            galleryEditFrame.on('open', function() {
                var selection = galleryEditFrame.state().get('selection');
                var attachment = wp.media.attachment(editPhotoID);

                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });

            galleryEditFrame.open();
        });

        $(document).on('click', '.pxp-company-gallery-delete-photo-btn', function() {
            var delImage = $(this).parent().parent().parent().attr('data-id');

            photos = $.grep(photos, function(id) {
                return id != delImage;
            });

            $('#company_gallery').val(photos.toString());
            $(this).parent().parent().parent().remove();
        });
    }

    // Upload company document 
    $('#pxp-add-company-doc-btn').click(function(event) {
        event.preventDefault();
        var placeholder = $(this);

        var frame = wp.media({
            title: pt_vars.company_doc_title,
            button: {
                text: pt_vars.company_doc_btn
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                $('#company_doc').val(value.id);
                $('.pxp-company-doc-filename').text(value.filename);
                $('.pxp-company-doc-container').addClass('pxp-show');
            });
        });

        frame.open();
    });

    // Delete company document
    $('.pxp-del-company-doc').on('click', function() {
        $('#company_doc').val('');
        $('.pxp-company-doc-filename').empty();
        $('.pxp-company-doc-container').removeClass('pxp-show');
    });

    // Candidate Additional Files
    if ($('#candidate_files').length > 0) {
        var dataFiles = {
            'files' : []
        }
        var files = '';
        var filesRaw = $('#candidate_files').val();

        if (filesRaw != '') {
            files = jsonParser(decodeURIComponent(filesRaw.replace(/\+/g, ' ')));

            if (files !== null) {
                dataFiles = files;
            }
        }

        $('#pxp-add-candidate-file-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-candidate-new-file').show();
        });

        $('#pxp-ok-file').on('click', function(event) {
            event.preventDefault();

            var name = $('#candidate_file_name').val();
            var id   = $('#candidate_file_id').val();
            var url  = $('#candidate_file_url').val();

            dataFiles.files.push({
                'name': name,
                'id'  : id,
                'url' : url
            });

            $('#candidate_files').val(fixedEncodeURIComponent(JSON.stringify(dataFiles)));

            $('#pxp-candidate-files-list').append(
                '<li class="list-group-item"' + 
                    'data-name="' + name + '" ' + 
                    'data-id="' + id + '" ' + 
                    'data-url="' + url + '" ' + 
                '>' + 
                    '<div class="pxp-candidate-files-list-item">' + 
                        '<div class="pxp-candidate-files-list-item-name"><b>' + name + '</b></div>' + 
                        '<div class="pxp-candidate-files-btns">' + 
                            '<a href="' + url + '" class="pxp-list-edit-btn"><span class="fa fa-file-text-o"></span></a>' + 
                            '<a href="javascript:void(0);" class="pxp-list-del-btn pxp-del-new-candidate-file"><span class="fa fa-trash-o"></span></a>' + 
                        '</div>' + 
                    '</div>' + 
                '</li>'
            );

            $('#candidate_file_name').val('');
            $('#candidate_file_id').val('');
            $('#candidate_file_url').val('');
            $('#candidate_filename').text('');

            $('.pxp-candidate-new-file').hide();
            $('#pxp-add-candidate-file-btn').show();

            $('.pxp-del-new-candidate-file').unbind('click').on('click', function(event) {
                delCandidateFile($(this));
            });
        });

        $('#pxp-cancel-file').on('click', function(event) {
            event.preventDefault();

            $('#candidate_file_name').val('');
            $('#candidate_file_id').val('');
            $('#candidate_file_url').val('');
            $('#candidate_filename').text('');

            $('.pxp-candidate-new-file').hide();
            $('#pxp-add-candidate-file-btn').show();
        });

        $('.pxp-del-candidate-file').on('click', function(event) {
            delCandidateFile($(this));
        });
    }

    function delCandidateFile(btn) {
        btn.parent().parent().parent().remove();

        dataFiles.files = [];

        $('#pxp-candidate-files-list li').each(function(index, el) {
            dataFiles.files.push({
                'name': $(this).attr('data-name'),
                'id'  : $(this).attr('data-id'),
                'url' : $(this).attr('data-url')
            });
        });

        $('#candidate_files').val(fixedEncodeURIComponent(JSON.stringify(dataFiles)));
    }

    // Upload candidate file 
    $('#pxp-candidate-new-file-upload-btn').click(function(event) {
        event.preventDefault();

        var frame = wp.media({
            title: pt_vars.candidate_file_title,
            button: {
                text: pt_vars.candidate_file_btn
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                $('#candidate_file_id').val(value.id);
                $('#candidate_file_url').val(value.url);
                $('#candidate_filename').text(value.filename);
            });
        });

        frame.open();
    });

    // Upload job category image
    $('.pxp-job-category-image-placeholder').click(function(event) {
        event.preventDefault();
        var placeholder = $(this);

        var frame = wp.media({
            title: pt_vars.job_category_image_title,
            button: {
                text: pt_vars.job_category_image_btn
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                $('#job_category_icon').val(value.id);
                placeholder.css('background-image', 'url(' + value.url + ')');
                placeholder.parent().addClass('pxp-has-image');
            });
        });

        frame.open();
    });

    // Delete job category image
    $('.pxp-delete-job-category-image').click(function() {
        var delBtn = $(this);

        $('#job_category_icon').val('');
        delBtn.parent().find('.pxp-job-category-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/logo-placeholder.png)');
        delBtn.parent().removeClass('pxp-has-image');
    });

    // Job category icon type selector
    $('#job_category_icon_type').on('change', function() {
        if ($(this).val() == 'font') {
            $('.pxp-job-category-image-placeholder-container').hide();
            $('.pxp-open-icons').show().html(pt_vars.job_category_icons_btn);
            $('#job_category_icon').val('');
        }
        if ($(this).val() == 'image') {
            $('.pxp-open-icons').hide();
            $('.pxp-job-category-image-placeholder-container')
                .removeClass('pxp-has-image')
                .show();
            $('.pxp-job-category-image-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/logo-placeholder.png)');
            $('#job_category_icon').val('');
        }
    });

    // Job Benefits
    if ($('#job_benefits').length > 0) {
        var dataBenefits = {
            'benefits' : []
        }
        var benefits = '';
        var benefitsRaw = $('#job_benefits').val();

        if (benefitsRaw != '') {
            benefits = jsonParser(decodeURIComponent(benefitsRaw.replace(/\+/g, ' ')));

            if (benefits !== null) {
                dataBenefits = benefits;
            }
        }

        $('#pxp-add-job-benefit-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-job-new-benefit').show();
        });

        $('#pxp-ok-benefit').on('click', function(event) {
            event.preventDefault();

            var title    = $('#job_benefit_title').val();
            var icon     = $('#job_benefit_icon').val();
            var icon_src = $('#job_benefit_icon').attr('data-src');

            dataBenefits.benefits.push({
                'title'   : title,
                'icon'    : icon,
                'icon_src': icon_src
            });

            $('#job_benefits').val(fixedEncodeURIComponent(JSON.stringify(dataBenefits)));

            $('#pxp-job-benefits-list').append(
                `<li class="list-group-item" 
                    data-title="${title}" 
                    data-icon="${icon}" 
                    data-src="${icon_src}"
                >
                    <div class="pxp-job-benefits-list-item">
                        <img src="${icon_src}">
                        <div class="pxp-job-benefits-list-item-title">
                            <b>${title}</b>
                        </div>
                        <div class="pxp-job-benefits-list-item-btns">
                            <a href="javascript:void(0);" class="pxp-list-edit-btn pxp-edit-new-job-benefit">
                                <span class="fa fa-pencil"></span>
                            </a>
                            <a href="javascript:void(0);" class="pxp-list-del-btn pxp-del-new-job-benefit">
                                <span class="fa fa-trash-o"></span>
                            </a>
                        </div>
                    </div>
                </li>`
            );

            $('#job_benefit_title').val('');
            $('#job_benefit_icon').val('');
            $('#job_benefit_icon').attr('data-src', '');

            $('.pxp-job-benefit-icon-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/photo-placeholder.png)');
            $('.pxp-job-benefit-icon-placeholder-container').removeClass('pxp-has-image');

            $('.pxp-job-new-benefit').hide();
            $('#pxp-add-job-benefit-btn').show();

            $('.pxp-edit-new-job-benefit').unbind('click').on('click', function(event) {
                editJobBenefit($(this));
            });
            $('.pxp-del-new-job-benefit').unbind('click').on('click', function(event) {
                delJobBenefit($(this));
            });
        });

        $('#pxp-cancel-benefit').on('click', function(event) {
            event.preventDefault();

            $('#job_benefit_title').val('');
            $('#job_benefit_icon').val('').attr('data-src', '');

            $('.pxp-job-benefit-icon-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/photo-placeholder.png)');
            $('.pxp-job-benefit-icon-placeholder-container').removeClass('pxp-has-image');

            $('.pxp-job-new-benefit').hide();
            $('#pxp-add-job-benefit-btn').show();
        });

        $('.pxp-job-benefit-icon-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: pt_vars.job_benefit_icon_title,
                button: {
                    text: pt_vars.job_benefit_icon_btn
                },
                multiple: false
            });
    
            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#job_benefit_icon').val(value.id).attr('data-src', value.url);
                    $('.pxp-job-benefit-icon-placeholder').css('background-image', 'url(' + value.url + ')');
                    $('.pxp-job-benefit-icon-placeholder-container').addClass('pxp-has-image');
                });
            });

            frame.open();
        });

        $('.pxp-delete-job-benefit-icon').on('click', function() {
            $('#job_benefit_icon').val('').attr('data-src', '');
            $('.pxp-job-benefit-icon-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/photo-placeholder.png)');
            $('.pxp-job-benefit-icon-placeholder-container').removeClass('pxp-has-image');
        });

        $('#pxp-job-benefits-list').sortable({
            placeholder: 'sortable-placeholder',
            opacity: 0.7,
            stop: function(event, ui) {
                dataBenefits.benefits = [];

                $('#pxp-job-benefits-list li').each(function(index, el) {
                    dataBenefits.benefits.push({
                        'title'   : $(this).attr('data-title'),
                        'icon'    : $(this).attr('data-icon'),
                        'icon_src': $(this).attr('data-src')
                    });
                });

                $('#job_benefits').val(fixedEncodeURIComponent(JSON.stringify(dataBenefits)));
            }
        }).disableSelection();

        $('.pxp-edit-job-benefit').on('click', function(event) {
            editJobBenefit($(this));
        });
        $('.pxp-del-job-benefit').on('click', function(event) {
            delJobBenefit($(this));
        });
    }

    function delJobBenefit(btn) {
        btn.parent().parent().parent().remove();

        dataBenefits.benefits = [];

        $('#pxp-job-benefits-list li').each(function(index, el) {
            dataBenefits.benefits.push({
                'title'   : $(this).attr('data-title'),
                'icon'    : $(this).attr('data-icon'),
                'icon_src': $(this).attr('data-src')
            });
        });

        $('#job_benefits').val(fixedEncodeURIComponent(JSON.stringify(dataBenefits)));
    }

    function editJobBenefit(btn) {
        var editTitle   = btn.parent().parent().parent().attr('data-title');
        var editIcon    = btn.parent().parent().parent().attr('data-icon');
        var editIconSrc = btn.parent().parent().parent().attr('data-src');
        
        var editJobBenefitForm = 
            `<div class="pxp-job-edit-benefit">
                <div class="pxp-job-edit-benefit-container">
                    <div class="pxp-job-edit-benefit-header">
                        <b>${pt_vars.job_edit_benefit_header}</b>
                    </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="50%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="edit_job_benefit_title">${pt_vars.job_benefit_title_label}</label><br>
                                    <input name="edit_job_benefit_title" id="edit_job_benefit_title" type="text" value="${editTitle}">
                                </div>
                            </td>
                            <td width="50%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="50%" valign="top">
                                <div class="form-field pxp-is-custom">
                                    <label for="edit_job_benefit_icon">${pt_vars.job_benefit_icon_label}</label>
                                    <input type="hidden" id="edit_job_benefit_icon" name="edit_job_benefit_icon" value="${editIcon}" data-src="${editIconSrc}">
                                    <div class="pxp-edit-job-benefit-icon-placeholder-container pxp-has-image">
                                        <div class="pxp-edit-job-benefit-icon-placeholder" style="background-image: url(${editIconSrc});"></div>
                                        <div class="pxp-delete-job-edit-benefit-icon"><span class="fa fa-trash-o"></span></div>
                                    </div>
                                </div>
                            </td>
                            <td width="50%">&nbsp;</td>
                        </tr>
                    </table>
                    <div class="form-field">
                        <button type="button" id="pxp-ok-edit-benefit" class="button media-button button-primary">${pt_vars.job_benefit_ok_btn}</button>
                        <button type="button" id="pxp-cancel-edit-benefit" class="button media-button button-default">${pt_vars.job_benefit_cancel_btn}</button>
                    </div>
                </div>
            </div>`;

        btn.parent().parent().hide();
        btn.parent().parent().parent().append(editJobBenefitForm);

        $('#pxp-job-benefits-list').sortable('disable');
        $('#pxp-job-benefits-list .list-group-item').css('cursor', 'auto');
        $('.pxp-edit-job-benefit').hide();
        $('.pxp-del-job-benefit').hide();
        $('.pxp-edit-new-job-benefit').hide();
        $('.pxp-del-new-job-benefit').hide();
        $('#pxp-add-job-benefit-btn').hide();
        $('.pxp-job-new-benefit').hide();

        $('.pxp-edit-job-benefit-icon-placeholder').on('click', function(event) {
            event.preventDefault();

            var frame = wp.media({
                title: pt_vars.job_benefit_icon_title,
                button: {
                    text: pt_vars.job_benefit_icon_btn
                },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').toJSON();
                $.each(attachment, function(index, value) {
                    $('#edit_job_benefit_icon').val(value.id).attr('data-src', value.url);
                    $('.pxp-edit-job-benefit-icon-placeholder').css('background-image', 'url(' + value.url + ')');
                    $('.pxp-edit-job-benefit-icon-placeholder-container').addClass('pxp-has-image');
                });
            });

            frame.open();
        });

        $('.pxp-delete-job-edit-benefit-icon').on('click', function() {
            $('#edit_job_benefit_icon').val('').attr('data-src', '');
            $('.pxp-edit-job-benefit-icon-placeholder').css('background-image', 'url(' + pt_vars.plugin_url + 'post-types/images/photo-placeholder.png)');
            $('.pxp-edit-job-benefit-icon-placeholder-container').removeClass('pxp-has-image');
        });

        $('#pxp-ok-edit-benefit').on('click', function(event) {
            var eTitle   = $(this).parent().parent().find('#edit_job_benefit_title').val();
            var eIcon    = $(this).parent().parent().find('#edit_job_benefit_icon').val();
            var eIconSrc = $(this).parent().parent().find('#edit_job_benefit_icon').attr('data-src');
            var listElem = $(this).parent().parent().parent().parent();

            listElem.attr({
                'data-title': eTitle,
                'data-icon' : eIcon,
                'data-src'  : eIconSrc
            });

            listElem.find('.pxp-job-benefits-list-item-title > b').text(eTitle);
            listElem.find('img').attr('src', eIconSrc);

            $(this).parent().parent().parent().remove();
            listElem.find('.pxp-job-benefits-list-item').show();

            $('#pxp-job-benefits-list').sortable('enable');
            $('#pxp-job-benefits-list .list-group-item').css('cursor', 'move');
            $('.pxp-edit-job-benefit').show();
            $('.pxp-del-job-benefit').show();
            $('.pxp-edit-new-job-benefit').show();
            $('.pxp-del-new-job-benefit').show();
            $('#pxp-add-job-benefit-btn').show();

            dataBenefits.benefits = [];
            $('#pxp-job-benefits-list li').each(function(index, el) {
                dataBenefits.benefits.push({
                    'title'   : $(this).attr('data-title'),
                    'icon'    : $(this).attr('data-icon'),
                    'icon_src': $(this).attr('data-src')
                });
            });

            $('#job_benefits').val(fixedEncodeURIComponent(JSON.stringify(dataBenefits)));
        });

        $('#pxp-cancel-edit-benefit').on('click', function(event) {
            var listElem  = $(this).parent().parent().parent().parent();

            $(this).parent().parent().parent().remove();
            listElem.find('.pxp-job-benefits-list-item').show();

            $('#pxp-job-benefits-list').sortable('enable');
            $('#pxp-job-benefits-list .list-group-item').css('cursor', 'move');
            $('.pxp-edit-job-benefit').show();
            $('.pxp-del-job-benefit').show();
            $('.pxp-edit-new-job-benefit').show();
            $('.pxp-del-new-job-benefit').show();
            $('#pxp-add-job-benefit-btn').show();
        });
    }
})(jQuery);