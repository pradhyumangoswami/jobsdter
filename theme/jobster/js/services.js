(function($) {
    "use strict";

    function showSuccessMessage(message) {
        return '<div class="alert alert-success fade in show" role="alert">' +
                    '<div><span class="fa fa-check"></span></div>' +
                    message +
                '</div>';
    }

    function showErrorMessage(message) {
        return '<div class="alert alert-danger fade in show" role="alert">' +
                    '<div><span class="fa fa-exclamation"></span></div>' +
                    message +
                '</div>';
    }

    function urlParam(name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results == null) {
           return null;
        } else {
           return results[1] || 0;
        }
    }

    function getPathFromUrl(url) {
        return url.split("?")[0];
    }

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

    function userSignin() {
        var message;
        $('.pxp-signin-modal-btn').addClass('disabled');
        $('.pxp-signin-modal-btn-text').hide();
        $('.pxp-signin-modal-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'     : 'jobster_user_signin',
                'signin_user': $('#pxp-signin-modal-email').val(),
                'signin_pass': $('#pxp-signin-modal-password').val(),
                'security'   : $('#pxp-signin-modal-security').val()
            },
            success: function(data) {
                $('.pxp-signin-modal-btn').removeClass('disabled');
                $('.pxp-signin-modal-btn-loading').hide();
                $('.pxp-signin-modal-btn-text').show();

                if (data.signedin === true) {
                    message = showSuccessMessage(data.message);

                    $('.pxp-signin-modal-message').empty().append(message).fadeIn('slow');
                    if (data.redirect == 'default') {
                        document.location.href = $('#pxp-signin-modal-redirect').val();
                    } else {
                        document.location.href = data.redirect;
                    }
                } else {
                    message = showErrorMessage(data.message);

                    $('.pxp-signin-modal-message').empty().append(message).fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    }

    function userPageSignin() {
        var message;
        $('.pxp-signin-page-btn').addClass('disabled');
        $('.pxp-signin-page-btn-text').hide();
        $('.pxp-signin-page-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'     : 'jobster_user_signin',
                'signin_user': $('#pxp-signin-page-email').val(),
                'signin_pass': $('#pxp-signin-page-password').val(),
                'security'   : $('#pxp-signin-page-security').val()
            },
            success: function(data) {
                $('.pxp-signin-page-btn').removeClass('disabled');
                $('.pxp-signin-page-btn-loading').hide();
                $('.pxp-signin-page-btn-text').show();

                if (data.signedin === true) {
                    message = showSuccessMessage(data.message);

                    $('.pxp-signin-page-message').empty().append(message).fadeIn('slow');
                    if (data.redirect == 'default') {
                        document.location.href = $('#pxp-signin-page-redirect').val();
                    } else {
                        document.location.href = data.redirect;
                    }
                } else {
                    message = showErrorMessage(data.message);

                    $('.pxp-signin-page-message').empty().append(message).fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    }

    function userSignup() {
        $('.pxp-signup-modal-btn').addClass('disabled');
        $('.pxp-signup-modal-btn-text').hide();
        $('.pxp-signup-modal-btn-loading').show();

        var userType = $('[name=pxp-signup-modal-type-switcher]:checked').attr('data-type');
        if ($('#pxp-is-candidate-reg').length > 0) {
            userType = 'candidate';
        }
        if ($('#pxp-is-company-reg').length > 0) {
            userType = 'company';
        }

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'          : 'jobster_user_signup',
                'signup_firstname': $('#pxp-signup-modal-firstname').val(),
                'signup_lastname' : $('#pxp-signup-modal-lastname').val(),
                'signup_company'  : $('#pxp-signup-modal-company-name').val(),
                'signup_email'    : $('#pxp-signup-modal-email').val(),
                'signup_pass'     : $('#pxp-signup-modal-password').val(),
                'user_type'       : userType,
                'terms'           : $('#pxp-signup-modal-terms').is(':checked'),
                'security'        : $('#pxp-signup-modal-security').val()
            },
            success: function(data) {
                $('.pxp-signup-modal-btn').removeClass('disabled');
                $('.pxp-signup-modal-btn-loading').hide();
                $('.pxp-signup-modal-btn-text').show();

                if (data.signedup === true) {
                    var message = showSuccessMessage(data.message);

                    if (data.hasOwnProperty('is_pending')) {
                        $('.pxp-signup-modal-message').empty().append(message).fadeIn('slow');

                        $('#pxp-signup-modal-firstname').val('');
                        $('#pxp-signup-modal-lastname').val('');
                        $('#pxp-signup-modal-company-name').val('');
                        $('#pxp-signup-modal-email').val('');
                        $('#pxp-signup-modal-password').val('');
                    } else {
                        $('#pxp-signup-modal').modal('hide');
                        $('#pxp-signin-modal').modal('show').on('shown.bs.modal', function(e) {
                            $('.pxp-signin-modal-message').empty().append(message).fadeIn('slow');
                        });
                    }
                } else {
                    var message = showErrorMessage(data.message);

                    $('.pxp-signup-modal-message').empty().append(message).fadeIn('slow');
                }
            },
            error: function(errorThrown) {

            }
        });
    }

    function userPageSignup() {
        $('.pxp-signup-page-btn').addClass('disabled');
        $('.pxp-signup-page-btn-text').hide();
        $('.pxp-signup-page-btn-loading').show();

        var userType = $('[name=pxp-signup-page-type-switcher]:checked').attr('data-type');
        if ($('#pxp-is-candidate-page-reg').length > 0) {
            userType = 'candidate';
        }
        if ($('#pxp-is-company-page-reg').length > 0) {
            userType = 'company';
        }

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'          : 'jobster_user_signup',
                'signup_firstname': $('#pxp-signup-page-firstname').val(),
                'signup_lastname' : $('#pxp-signup-page-lastname').val(),
                'signup_company'  : $('#pxp-signup-page-company-name').val(),
                'signup_email'    : $('#pxp-signup-page-email').val(),
                'signup_pass'     : $('#pxp-signup-page-password').val(),
                'user_type'       : userType,
                'terms'           : $('#pxp-signup-page-terms').is(':checked'),
                'security'        : $('#pxp-signup-page-security').val()
            },
            success: function(data) {
                $('.pxp-signup-page-btn').removeClass('disabled');
                $('.pxp-signup-page-btn-loading').hide();
                $('.pxp-signup-page-btn-text').show();

                if (data.signedup === true) {
                    var message = showSuccessMessage(data.message);

                    if (data.hasOwnProperty('is_pending')) {
                        $('.pxp-signup-page-message').empty().append(message).fadeIn('slow');
                    } else {
                        document.location.href = $('#pxp-signup-page-redirect').val();
                    }
                } else {
                    var message = showErrorMessage(data.message);

                    $('.pxp-signup-page-message').empty().append(message).fadeIn('slow');
                }
            },
            error: function(errorThrown) {

            }
        });
    }

    function forgotPassword() {
        $('.pxp-forgot-modal-btn').addClass('disabled');
        $('.pxp-forgot-modal-btn-text').hide();
        $('.pxp-forgot-modal-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'      : 'jobster_forgot_password',
                'forgot_email': $('#pxp-forgot-modal-email').val(),
                'security'    : $('#pxp-forgot-modal-security').val()
            },

            success: function(data) {
                $('.pxp-forgot-modal-btn').removeClass('disabled');
                $('.pxp-forgot-modal-btn-loading').hide();
                $('.pxp-forgot-modal-btn-text').show();
                $('#pxp-forgot-modal-email').val('');

                if (data.sent === true) {
                    var message = showSuccessMessage(data.message);

                    $('.pxp-forgot-modal-message').empty().append(message).fadeIn('slow');
                } else {
                    var message = showErrorMessage(data.message);

                    $('.pxp-forgot-modal-message').empty().append(message).fadeIn('slow');
                }
            },
            error: function(errorThrown) {

            }
        });
    }

    function resetPassword() {
        $('.pxp-reset-modal-btn').addClass('disabled');
        $('.pxp-reset-modal-btn-text').hide();
        $('.pxp-reset-modal-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'  : 'jobster_reset_password',
                'pass'    : $('#pxp-reset-modal-password').val(),
                'key'     : urlParam('key'),
                'login'   : urlParam('login'),
                'security': $('#pxp-reset-modal-security').val()
            },

            success: function(data) {
                $('.pxp-reset-modal-btn').removeClass('disabled');
                $('.pxp-reset-modal-btn-loading').hide();
                $('.pxp-reset-modal-btn-text').show();
                $('#pxp-reset-modal-password').val('');

                if (data.reset === true) {
                    var message = showSuccessMessage(data.message);

                    $('#pxp-reset-modal').modal('hide');
                    $('#pxp-signin-modal').modal('show').on('shown.bs.modal', function(e) {
                        $('.pxp-signin-modal-message').empty().append(message).fadeIn('slow');
                    });
                } else {
                    var message = showErrorMessage(data.message);

                    $('.pxp-reset-modal-message').empty().append(message).fadeIn('slow');
                }
            },
            error: function(errorThrown) {

            }
        });
    }

    /* Signin Modal */
    $('.pxp-signin-trigger').click(function() {
        $('#pxp-signup-modal').modal('hide');
        $('#pxp-signin-modal').modal('show');
    });
    $('#pxp-signin-modal').on('shown.bs.modal', function () {
        $('body').addClass('modal-open');
        $('#pxp-signin-modal-redirect').val(window.location.href.split(/\?|#/)[0]);
    });
    $('#pxp-signin-modal').on('hidden.bs.modal', function(e) {
        $('.pxp-signin-modal-message').empty();
        $('#pxp-signin-modal-email').val('');
        $('#pxp-signin-modal-password').val('');
    });
    $('.pxp-signin-modal-btn').click(function() {
        userSignin();
    });
    $('#pxp-signin-modal-email, #pxp-signin-modal-password').keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            userSignin();
        }
    });
    $('.pxp-signin-page-btn').click(function() {
        userPageSignin();
    });
    $('#pxp-signin-page-email, #pxp-signin-page-password').keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            userPageSignin();
        }
    });

    /* Signin Modal for elements that need signin before access */
    $('.pxp-signin-item a, a.pxp-signin-item').on('click', function(e) {
        e.preventDefault();
        var url = $(this).prop('href');

        if (services_vars.user_logged_in === '1') {
            document.location = url;
        } else {
            $('#pxp-signup-modal').modal('hide');
            $('#pxp-signin-modal').modal('show');
        }
    });

    /* Signup Modal */
    $('.pxp-signup-trigger').click(function() {
        $('#pxp-signin-modal').modal('hide');
        $('#pxp-signup-modal').modal('show');
    });
    
    $('#pxp-signup-modal').on('shown.bs.modal', function () {
        $('body').addClass('modal-open');
    });
    $('#pxp-signup-modal').on('show.bs.modal', function () {
        if ($('#pxp-is-candidate-reg').length > 0) {
            $('.pxp-signup-modal-company-fields').hide();
            $('.pxp-signup-modal-candidate-fields').show();
        }
        if ($('#pxp-is-company-reg').length > 0) {
            $('.pxp-signup-modal-candidate-fields').hide();
            $('.pxp-signup-modal-company-fields').show();
        }
    });
    $('#pxp-signup-modal').on('hidden.bs.modal', function(e) {
        $('.pxp-signup-modal-message').empty();
        $('#pxp-signup-modal-firstname').val('');
        $('#pxp-signup-modal-lastname').val('');
        $('#pxp-signup-modal-company').val('');
        $('#pxp-signup-modal-email').val('');
        $('#pxp-signup-modal-password').val('');
    });
    $('.pxp-signup-modal-btn').click(function() {
        userSignup();
    });
    $(`#pxp-signup-modal-firstname, 
        #pxp-signup-modal-lastname, 
        #pxp-signup-modal-company, 
        #pxp-signup-modal-email, 
        #pxp-signup-modal-password`
    ).keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            userSignup();
        }
    });
    $('.pxp-signup-page-btn').click(function() {
        userPageSignup();
    });
    $(`#pxp-signup-page-firstname, 
        #pxp-signup-page-lastname, 
        #pxp-signup-page-company, 
        #pxp-signup-page-email, 
        #pxp-signup-page-password`
    ).keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            userPageSignup();
        }
    });

    /* Forgot Password Modal */
    $('.pxp-forgot-trigger').click(function() {
        $('#pxp-signin-modal').modal('hide');
        $('#pxp-forgot-modal').modal('show');
    });
    $('#pxp-forgot-modal').on('shown.bs.modal', function () {
        $('body').addClass('modal-open');
    });
    $('#pxp-forgot-modal').on('hidden.bs.modal', function(e) {
        $('.pxp-forgot-modal-message').empty();
        $('#pxp-forgot-modal-email').val('');
    });
    $('.pxp-forgot-modal-btn').click(function() {
        forgotPassword();
    });
    $('#pxp-forgot-modal-email').keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            forgotPassword();
        }
    });

    /* Reset Password Modal */
    $(document).ready(function() {
        if (urlParam('action') && urlParam('action') == 'rp') {
            $('#pxp-reset-modal').modal('show');
        }
    });
    $('#pxp-reset-modal').on('hidden.bs.modal', function(e) {
        $('.pxp-reset-modal-message').empty();
        $('#pxp-reset-modal-password').val('');
    });
    $('.pxp-reset-modal-btn').click(function() {
        resetPassword();
    });
    $('#pxp-reset-modal-password').keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            resetPassword();
        }
    });

    // Save/Remove job to/from candidate favs
    $('.pxp-single-job-save-btn').on('click', function() {
        var _self = $(this);
        var saved = _self.attr('data-saved');

        if (saved == 'true') {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'  : 'jobster_candidate_remove_fav',
                    'job_ids' : [_self.attr('data-pid')],
                    'security': $('#pxp-single-job-favs-security').val()
                },
                success: function(data) {
                    if (data.removed === true) {
                        _self
                        .removeClass('pxp-saved')
                        .attr('data-saved', 'false')
                        .html(
                            `<span class="fa fa-heart-o"></span>`
                        );
                    }
                },
                error: function(errorThrown) {}
            });
        } else {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'  : 'jobster_candidate_add_fav',
                    'job_id'  : _self.attr('data-pid'),
                    'security': $('#pxp-single-job-favs-security').val()
                },
                success: function(data) {
                    if (data.saved === true) {
                        _self
                        .addClass('pxp-saved')
                        .attr('data-saved', 'true')
                        .html(
                            `<span class="fa fa-heart"></span>`
                        );
                    }
                },
                error: function(errorThrown) {}
            });
        }
    });

    // Manage candidate job application
    $('button.pxp-single-job-apply-btn').on('click', function() {
        var _self = $(this);
        var saved = _self.attr('data-saved');

        if (saved == 'false') {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'  : 'jobster_job_apply',
                    'job_id'  : _self.attr('data-pid'),
                    'security': $('#pxp-single-job-apps-security').val()
                },
                success: function(data) {
                    if (data.saved === true) {
                        $('.pxp-single-job-apply-btn')
                        .addClass('pxp-saved')
                        .attr('data-saved', 'true')
                        .attr('disabled', 'disabled')
                        .html(
                            `<span class="fa fa-check"></span>
                            ${services_vars.applied}`
                        );
                    }
                },
                error: function(errorThrown) {}
            });
        }
    });

    // Manage single company page - contact form
    $('.pxp-single-company-contact-btn').on('click', function() {
        var message;
        $(this).addClass('disabled');
        $('.pxp-single-company-contact-btn-text').hide();
        $('.pxp-single-company-contact-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'    : 'jobster_contact_company',
                'company_id': $('#pxp-single-company-contact-company-id').val(),
                'message'   : $('#pxp-single-company-contact-message').val(),
                'security'  : $('#pxp-single-company-contact-security').val()
            },
            success: function(data) {
                $('.pxp-single-company-contact-btn').removeClass('disabled');
                $('.pxp-single-company-contact-btn-loading').hide();
                $('.pxp-single-company-contact-btn-text').show();

                if (data.sent === true) {
                    message = showSuccessMessage(data.message);

                    $('.pxp-single-company-contact-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');

                    $('#pxp-single-company-contact-message').val('');
                } else {
                    message = showErrorMessage(data.message);

                    $('.pxp-single-company-contact-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Manage single candidate page - contact form
    $('.pxp-single-candidate-contact-btn').on('click', function() {
        var message;
        $(this).addClass('disabled');
        $('.pxp-single-candidate-contact-btn-text').hide();
        $('.pxp-single-candidate-contact-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'      : 'jobster_contact_candidate',
                'candidate_id': $('#pxp-single-candidate-contact-candidate-id').val(),
                'message'     : $('#pxp-single-candidate-contact-message').val(),
                'security'    : $('#pxp-single-candidate-contact-security').val()
            },
            success: function(data) {
                $('.pxp-single-candidate-contact-btn').removeClass('disabled');
                $('.pxp-single-candidate-contact-btn-loading').hide();
                $('.pxp-single-candidate-contact-btn-text').show();

                if (data.sent === true) {
                    message = showSuccessMessage(data.message);

                    $('.pxp-single-candidate-contact-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                    $('#pxp-single-candidate-contact-message').val('');
                } else {
                    message = showErrorMessage(data.message);

                    $('.pxp-single-candidate-contact-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Set company notifications as read when click on bell icon
    $('.pxp-company-notifications > a').on('click', function() {
        var companyId = $(this).attr('data-id');
        var _self = $(this);

        if (_self.find('.pxp-user-notifications-counter').length > 0) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'    : 'jobster_set_company_read_notifications',
                    'company_id': companyId,
                    'security'  : $('#pxp-notifications-security').val()
                },
                success: function(data) {
                    if (data.set === true) {
                        _self.find('.pxp-user-notifications-counter').remove();
                        $('.badge.pxp-unread-notificatons').remove();
                        $('.pxp-dashboard-stats-card-info-number.pxp-is-notify').text('0');
                        _self.next('ul').find('li').removeClass('pxp-unread');
                    }
                },
                error: function(errorThrown) {}
            });
        }
    });

    // Company dashboard charts
    if ($('#pxp-company-dashboard-visitors-chart').length > 0) {
        var companyVisitsChartElem = document
                                    .getElementById('pxp-company-dashboard-visitors-chart')
                                    .getContext('2d');

        var gradient = companyVisitsChartElem.createLinearGradient(0, 250, 0, 0);
        gradient.addColorStop(0, 'rgba(255, 255, 255, 0)');
        gradient.addColorStop(.5, 'rgba(0, 112, 201, 0.09)');
        gradient.addColorStop(1, 'rgba(0, 112, 201, 0.12)');

        var companyVisitsChart = new Chart(companyVisitsChartElem, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', '', ''],
                datasets: [{
                    label: services_vars.visitors,
                    data: [0, 0, 0, 0, 0, 0, 0],
                    borderWidth: 3,
                    borderColor: 'rgba(0, 112, 201, 1)',
                    pointBackgroundColor: 'rgba(255, 255, 255, 0)',
                    pointHoverBackgroundColor: 'rgba(255, 255, 255, 1)',
                    pointBorderColor: 'rgba(66, 133, 244, 0)',
                    pointHoverBorderColor: 'rgba(0, 112, 201, 1)',
                    pointBorderWidth: 10,
                    pointHoverBorderWidth: 3,
                    pointHitRadius: 20,
                    cubicInterpolationMode: 'monotone',
                    fill: true,
                    backgroundColor: gradient
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        ticks: {
                            fontColor: 'rgba(153, 153, 153, 1)',
                            maxTicksLimit: 7,
                            maxRotation: 0
                        },
                        gridLines: {
                            zeroLineColor: 'rgba(232, 232, 232, 1)',
                            drawOnChartArea: false,
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: 'rgba(153, 153, 153, 1)',
                            callback: function(value, index, values) {
                                if (Math.floor(value) === value) {
                                    return value;
                                }
                            }
                        },
                        gridLines: {
                            zeroLineColor: 'rgba(232, 232, 232, 0)',
                        },
                    }],
                },
                responsive: true,
                tooltips: {
                    backgroundColor: 'rgba(0, 39, 69, 1)',
                    cornerRadius: 7,
                    mode: 'index',
                    intersect: false,
                    displayColors: false,
                    xPadding: 10,
                    yPadding: 10,
                    titleFontColor: 'rgba(255, 255, 255, .7)',
                    bodyFontColor: 'rgba(255, 255, 255, 1)',
                    titleFontStyle: 'normal',
                    bodyFontStyle: 'bold',
                },
                legend: {
                    display: false,
                }
            }
        });
    }

    function getCompanyVisitorsByPeriod(period = '-7 days') {
        if ($('#pxp-company-dashboard-visitors-chart').length > 0) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'  : 'jobster_get_jobs_visitors',
                    'company_id': $('#pxp-company-id').val(),
                    'period'  : period,
                    'security': $('#pxp-charts-security').val()
                },
                success: function(data) {
                    if (data.getvisitors === true) {
                        var visitors_no = [];
                        var visitors_dates = [];
                        var percent;
                        var versus = {
                            '-7 days'   : services_vars.vs_7_days,
                            '-30 days'  : services_vars.vs_30_days,
                            '-60 days'  : services_vars.vs_60_days,
                            '-90 days'  : services_vars.vs_90_days,
                            '-12 months': services_vars.vs_12_months,
                        };

                        $.each(data.visitors, function(date, visitor) {
                            visitors_no.push(visitor);
                            visitors_dates.push(date);
                        });

                        companyVisitsChart.data.labels = visitors_dates;
                        companyVisitsChart.data.datasets[0].data = visitors_no;

                        companyVisitsChart.update();

                        $('.pxp-company-dashboard-visitors-chart-percent')
                        .removeClass('text-success')
                        .removeClass('text-danger');
                        $('.pxp-company-dashboard-visitors-number-total')
                        .text(data.total_visitors);

                        if (data.total_visitors_prev == 0) {
                            percent = parseInt(data.total_visitors) * 100;
                        } else {
                            percent = (
                                (parseInt(data.total_visitors) - parseInt(data.total_visitors_prev)) * 100
                            ) / parseInt(data.total_visitors_prev);
                        }

                        if (percent >= 0) {
                            $('.pxp-company-dashboard-visitors-chart-percent')
                            .addClass('text-success')
                            .html(`<span class="fa fa-long-arrow-up"></span> ${Math.abs(percent.toFixed(1))}%`);
                        } else {
                            $('.pxp-company-dashboard-visitors-chart-percent')
                            .addClass('text-danger')
                            .html(`<span class="fa fa-long-arrow-down"></span> ${Math.abs(percent.toFixed(1))}%`);
                        }

                        $('.pxp-company-dashboard-visitors-vs').html(versus[period]);
                    }
                },
                error: function(errorThrown) {}
            });
        }
    }

    getCompanyVisitorsByPeriod();

    $('#pxp-company-visitors-period').on('change', function() {
        getCompanyVisitorsByPeriod($(this).val());
    });

    if ($('#pxp-company-dashboard-apps-chart').length > 0) {
        var companyAppsChartElem =  document
                                    .getElementById('pxp-company-dashboard-apps-chart')
                                    .getContext('2d');

        var gradient = companyAppsChartElem.createLinearGradient(0, 250, 0, 0);
        gradient.addColorStop(0, 'rgba(255, 255, 255, 0)');
        gradient.addColorStop(.5, 'rgba(255, 168, 35, 0.09)');
        gradient.addColorStop(1, 'rgba(255, 168, 35, 0.12)');

        var companyAppsChart = new Chart(companyAppsChartElem, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', '', ''],
                datasets: [{
                    label: services_vars.applications,
                    data: [0, 0, 0, 0, 0, 0, 0],
                    borderWidth: 3,
                    borderColor: 'rgba(255, 168, 35, 1)',
                    pointBackgroundColor: 'rgba(255, 255, 255, 0)',
                    pointHoverBackgroundColor: 'rgba(255, 255, 255, 1)',
                    pointBorderColor: 'rgba(66, 133, 244, 0)',
                    pointHoverBorderColor: 'rgba(255, 168, 35, 1)',
                    pointBorderWidth: 10,
                    pointHoverBorderWidth: 3,
                    pointHitRadius: 20,
                    cubicInterpolationMode: 'monotone',
                    fill: true,
                    backgroundColor: gradient
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        ticks: {
                            fontColor: 'rgba(153, 153, 153, 1)',
                            maxTicksLimit: 7,
                            maxRotation: 0
                        },
                        gridLines: {
                            zeroLineColor: 'rgba(232, 232, 232, 1)',
                            drawOnChartArea: false,
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: 'rgba(153, 153, 153, 1)',
                            callback: function(value, index, values) {
                                if (Math.floor(value) === value) {
                                    return value;
                                }
                            }
                        },
                        gridLines: {
                            zeroLineColor: 'rgba(232, 232, 232, 0)',
                        },
                    }],
                },
                responsive: true,
                tooltips: {
                    backgroundColor: 'rgba(0, 39, 69, 1)',
                    cornerRadius: 7,
                    mode: 'index',
                    intersect: false,
                    displayColors: false,
                    xPadding: 10,
                    yPadding: 10,
                    titleFontColor: 'rgba(255, 255, 255, .7)',
                    bodyFontColor: 'rgba(255, 255, 255, 1)',
                    titleFontStyle: 'normal',
                    bodyFontStyle: 'bold',
                },
                legend: {
                    display: false,
                }
            }
        });
    }

    function getAppsByPeriod(period = '-7 days') {
        if ($('#pxp-company-dashboard-apps-chart').length > 0) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'  : 'jobster_get_company_jobs_apps',
                    'company_id': $('#pxp-company-id').val(),
                    'period'  : period,
                    'security': $('#pxp-charts-security').val()
                },
                success: function(data) {
                    if (data.getapps === true) {
                        var apps_no = [];
                        var apps_dates = [];
                        var percent;
                        var versus = {
                            '-7 days'   : services_vars.vs_7_days,
                            '-30 days'  : services_vars.vs_30_days,
                            '-60 days'  : services_vars.vs_60_days,
                            '-90 days'  : services_vars.vs_90_days,
                            '-12 months': services_vars.vs_12_months,
                        };

                        $.each(data.apps, function(date, app) {
                            apps_no.push(app);
                            apps_dates.push(date);
                        });

                        companyAppsChart.data.labels = apps_dates;
                        companyAppsChart.data.datasets[0].data = apps_no;

                        companyAppsChart.update();

                        $('.pxp-company-dashboard-apps-chart-percent')
                        .removeClass('text-success')
                        .removeClass('text-danger');
                        $('.pxp-company-dashboard-apps-number-total')
                        .text(data.total_apps);

                        if (data.total_apps_prev == 0) {
                            percent = parseInt(data.total_apps) * 100;
                        } else {
                            percent = (
                                (parseInt(data.total_apps) - parseInt(data.total_apps_prev)) * 100
                            ) / parseInt(data.total_apps_prev);
                        }

                        if (percent >= 0) {
                            $('.pxp-company-dashboard-apps-chart-percent')
                            .addClass('text-success')
                            .html(`<span class="fa fa-long-arrow-up"></span> ${Math.abs(percent.toFixed(1))}%`);
                        } else {
                            $('.pxp-company-dashboard-apps-chart-percent')
                            .addClass('text-danger')
                            .html(`<span class="fa fa-long-arrow-down"></span> ${Math.abs(percent.toFixed(1))}%`);
                        }

                        $('.pxp-company-dashboard-apps-vs').html(versus[period]);
                    }
                },
                error: function(errorThrown) {}
            });
        }
    }

    getAppsByPeriod();

    $('#pxp-company-apps-period').on('change', function() {
        getAppsByPeriod($(this).val());
    });

    function getTinymceContent(id) {
        if ($('.pxp-is-tinymce').length > 0) {
            var content;
            var inputid = id;

            tinyMCE.triggerSave();

            var editor = tinyMCE.get(inputid);
            var textArea = jQuery('textarea#' + inputid);

            if (textArea.length > 0 && textArea.is(':visible')) {
                content = textArea.val();
            } else {
                content = editor.getContent();
            }

            return content;
        } else {
            return '';
        }
    }

    // Manage update company profile
    $('.pxp-company-profile-update-btn').on('click', function() {
        var message;
        $(this).addClass('disabled');
        $('.pxp-company-profile-update-btn-text').hide();
        $('.pxp-company-profile-update-btn-loading').show();
        $('.pxp-is-required').removeClass('is-invalid');

        var cfields = [];
        $('.pxp-company-profile-custom-field').each(function(index) {
            cfields.push({
                field_name     : $(this).attr('id'),
                field_value    : $(this).val(),
                field_label    : $(this).attr('data-label'),
                field_mandatory: $(this).attr('data-mandatory')
            });
        });

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'       : 'jobster_update_company_profile',
                'company_id'   : $('#pxp-company-profile-id').val(),
                'name'         : $('#pxp-company-profile-name').val(),
                'email'        : $('#pxp-company-profile-email').val(),
                'phone'        : $('#pxp-company-profile-phone').val(),
                'website'      : $('#pxp-company-profile-website').val(),
                'redirect'     : $('#pxp-company-profile-redirect:checked').val(),
                'cover'        : $('#pxp-dashboard-cover').val(),
                'cover_type'   : $('#pxp-company-profile-cover-type').val(),
                'cover_color'  : $('#pxp-company-profile-cover-color').val(),
                'logo'         : $('#pxp-dashboard-logo').val(),
                'about'        : getTinymceContent('pxp-company-profile-about'),
                'industry'     : $('#pxp-company-profile-industry').val(),
                'location'     : $('#pxp-company-profile-location').val(),
                'founded'      : $('#pxp-company-profile-founded').val(),
                'size'         : $('#pxp-company-profile-size').val(),
                'facebook'     : $('#pxp-company-profile-facebook').val(),
                'twitter'      : $('#pxp-company-profile-twitter').val(),
                'instagram'    : $('#pxp-company-profile-instagram').val(),
                'linkedin'     : $('#pxp-company-profile-linkedin').val(),
                'doc'          : $('#pxp-dashboard-doc').val(),
                'doc_title'    : $('#pxp-company-profile-doc-title').val(),
                'app_notify'   : $('#pxp-company-profile-app-notify:checked').val(),
                'gallery'      : $('#pxp-profile-gallery-field').val(),
                'gallery_title': $('#pxp-company-profile-gallery-title').val(),
                'video'        : $('#pxp-company-profile-video').val(),
                'video_title'  : $('#pxp-company-profile-video-title').val(),
                'cfields'      : cfields,
                'security'     : $('#pxp-company-profile-security').val()
            },
            success: function(data) {
                $('.pxp-company-profile-update-btn').removeClass('disabled');
                $('.pxp-company-profile-update-btn-loading').hide();
                $('.pxp-company-profile-update-btn-text').show();

                if (data.update === true) {
                    document.location.href = services_vars.company_profile_url;
                } else {
                    if (data.field) {
                        $(`#${data.field}`).addClass('is-invalid');
                    }

                    message = showErrorMessage(data.message);

                    $('.pxp-company-profile-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Manage save new job
    $('.pxp-company-new-job-save-btn').on('click', function() {
        var message;
        var _self = $(this);
        var cfields = [];

        _self.addClass('disabled');
        _self.find('.pxp-company-new-job-save-btn-text').hide();
        _self.find('.pxp-company-new-job-save-btn-loading').show();
        $('.pxp-is-required').removeClass('is-invalid');

        $('.pxp-company-new-job-custom-field').each(function(index) {
            cfields.push({
                field_name     : $(this).attr('id'),
                field_value    : $(this).val(),
                field_label    : $(this).attr('data-label'),
                field_mandatory: $(this).attr('data-mandatory')
            });
        });

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'     : 'jobster_save_new_job',
                'company_id' : $('#pxp-company-new-job-company-id').val(),
                'title'      : $('#pxp-company-new-job-title').val(),
                'category'   : $('#pxp-company-new-job-category').val(),
                'location'   : $('#pxp-company-new-job-location').val(),
                'cover'      : $('#pxp-dashboard-cover').val(),
                'description': getTinymceContent('pxp-company-new-job-description'),
                'type'       : $('#pxp-company-new-job-type').val(),
                'level'      : $('#pxp-company-new-job-level').val(),
                'experience' : $('#pxp-company-new-job-experience').val(),
                'salary'     : $('#pxp-company-new-job-salary').val(),
                'draft'      : _self.hasClass('pxp-is-draft'),
                'featured'   : $('#pxp-company-new-job-featured').is(':checked'),
                'valid'      : $('#pxp-company-new-job-valid').val(),
                'btn_action' : $('#pxp-company-new-job-action').val(),
                'benefits'   : $('#pxp-company-new-job-benefits').val(),
                'cfields'    : cfields,
                'security'   : $('#pxp-company-new-job-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-company-new-job-save-btn-loading').hide();
                _self.find('.pxp-company-new-job-save-btn-text').show();

                if (data.save === true) {
                    document.location.href = services_vars.company_jobs_url;
                } else {
                    if (data.field) {
                        $(`#${data.field}`).addClass('is-invalid');
                    }

                    message = showErrorMessage(data.message);

                    $('.pxp-company-new-job-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Manage jobs - bulk actions
    $('.pxp-company-dashboard-check-all-jobs').on('change', function() {
        let checks = document.querySelectorAll('.pxp-company-dashboard-check');

        if ($(this).is(':checked')) {
            checks.forEach(elem => {
                elem.checked = true;
            });
        } else {
            checks.forEach(elem => {
                elem.checked = false;
            });
        }
    });
    $('#pxp-company-dashboard-jobs-bulk-actions').on('change', function() {
        if ($(this).val() == 'publish' || $(this).val() == 'delete') {
            $('.pxp-company-dashboard-jobs-bulk-actions-apply').removeClass('disabled');
        } else {
            $('.pxp-company-dashboard-jobs-bulk-actions-apply').addClass('disabled');
        }
    });
    $('.pxp-company-dashboard-jobs-bulk-actions-apply').on('click', function() {
        var action = $('#pxp-company-dashboard-jobs-bulk-actions').val();
        var jobs = [];
        var _self = $(this);

        if (action == 'publish') {
            $('.pxp-company-dashboard-check').each(function() {
                if ($(this).is(':checked')) {
                    jobs.push($(this).attr('data-id'));
                }
            });

            if (jobs.length > 0) {
                _self.addClass('disabled');
                _self.find('.pxp-company-dashboard-jobs-bulk-actions-apply-text').hide();
                _self.find('.pxp-company-dashboard-jobs-bulk-actions-apply-loading').show();

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: services_vars.ajaxurl,
                    data: {
                        'action'  : 'jobster_publish_jobs',
                        'jobs'    : jobs.join(),
                        'security': $('#pxp-company-bulk-jobs-security').val()
                    },
                    success: function(data) {
                        _self.removeClass('disabled');
                        _self.find('.pxp-company-dashboard-jobs-bulk-actions-apply-loading').hide();
                        _self.find('.pxp-company-dashboard-jobs-bulk-actions-apply-text').show();

                        document.location.href = services_vars.company_jobs_url;
                    },
                    error: function(errorThrown) {}
                });
            }
        } else if (action == 'delete') {
            $('.pxp-company-dashboard-check').each(function() {
                if ($(this).is(':checked')) {
                    jobs.push($(this).attr('data-id'));
                }
            });

            if (jobs.length > 0) {
                _self.addClass('disabled');
                _self.find('.pxp-company-dashboard-jobs-bulk-actions-apply-text').hide();
                _self.find('.pxp-company-dashboard-jobs-bulk-actions-apply-loading').show();

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: services_vars.ajaxurl,
                    data: {
                        'action'  : 'jobster_delete_jobs',
                        'jobs'    : jobs.join(),
                        'security': $('#pxp-company-bulk-jobs-security').val()
                    },
                    success: function(data) {
                        _self.removeClass('disabled');
                        _self.find('.pxp-company-dashboard-jobs-bulk-actions-apply-loading').hide();
                        _self.find('.pxp-company-dashboard-jobs-bulk-actions-apply-text').show();

                        document.location.href = services_vars.company_jobs_url;
                    },
                    error: function(errorThrown) {}
                });
            }
        }
    });
    $('.pxp-company-dashboard-job-delete').on('click', function() {
        var _self = $(this);
        _self.addClass('disabled');
        _self.find('.pxp-company-dashboard-job-delete-text').hide();
        _self.find('.pxp-company-dashboard-job-delete-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'  : 'jobster_delete_jobs',
                'jobs'    : _self.attr('data-id'),
                'security': $('#pxp-company-bulk-jobs-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-company-dashboard-job-delete-loading').hide();
                _self.find('.pxp-company-dashboard-job-delete-text').show();

                document.location.href = services_vars.company_jobs_url;
            },
            error: function(errorThrown) {}
        });
    });

    // Manage update existing job
    $('.pxp-company-edit-job-save-btn').on('click', function() {
        var message;
        var _self = $(this);
        var cfields = [];

        _self.addClass('disabled');
        _self.find('.pxp-company-edit-job-save-btn-text').hide();
        _self.find('.pxp-company-edit-job-save-btn-loading').show();
        $('.pxp-is-required').removeClass('is-invalid');

        $('.pxp-company-edit-job-custom-field').each(function(index) {
            cfields.push({
                field_name     : $(this).attr('id'),
                field_value    : $(this).val(),
                field_label    : $(this).attr('data-label'),
                field_mandatory: $(this).attr('data-mandatory')
            });
        });

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'     : 'jobster_update_job',
                'company_id' : $('#pxp-company-edit-job-company-id').val(),
                'job_id'     : $('#pxp-company-edit-job-id').val(),
                'title'      : $('#pxp-company-edit-job-title').val(),
                'category'   : $('#pxp-company-edit-job-category').val(),
                'location'   : $('#pxp-company-edit-job-location').val(),
                'cover'      : $('#pxp-dashboard-cover').val(),
                'description': getTinymceContent('pxp-company-edit-job-description'),
                'type'       : $('#pxp-company-edit-job-type').val(),
                'level'      : $('#pxp-company-edit-job-level').val(),
                'experience' : $('#pxp-company-edit-job-experience').val(),
                'salary'     : $('#pxp-company-edit-job-salary').val(),
                'valid'      : $('#pxp-company-edit-job-valid').val(),
                'btn_action' : $('#pxp-company-edit-job-action').val(),
                'draft'      : _self.hasClass('pxp-is-draft'),
                'benefits'   : $('#pxp-company-edit-job-benefits').val(),
                'cfields'    : cfields,
                'security'   : $('#pxp-company-edit-job-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-company-edit-job-save-btn-loading').hide();
                _self.find('.pxp-company-edit-job-save-btn-text').show();

                if (data.update === true) {
                    document.location.href = services_vars.company_jobs_url;
                } else {
                    if (data.field) {
                        $(`#${data.field}`).addClass('is-invalid');
                    }

                    message = showErrorMessage(data.message);

                    $('.pxp-company-edit-job-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Company Dashboard - Application status
    $('.pxp-company-dashboard-app-set-status-btn').on('click', function() {
        var _self = $(this);
        _self.addClass('disabled');
        _self.find('.fa').hide();
        _self.find('.pxp-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'      : 'jobster_set_app_status',
                'apps'        : [{
                    'job_id'      : _self.attr('data-job-id'),
                    'candidate_id': _self.attr('data-candidate-id')
                }],
                'status'      : _self.attr('data-status'),
                'security'    : $('#pxp-company-candidates-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-btn-loading').hide();
                _self.find('.fa').show();

                if (data.set === true) {
                    var statusContainer =   _self
                                            .parent().parent().parent().parent().parent()
                                            .find('.pxp-company-dashboard-job-status');
                    switch (_self.attr('data-status')) {
                        case 'approved':
                            statusContainer.html(
                                `<span class="badge rounded-pill bg-success">
                                    ${services_vars.approved_status}
                                </span>`
                            );
                            break;
                        case 'rejected':
                            statusContainer.html(
                                `<span class="badge rounded-pill bg-danger">
                                    ${services_vars.rejected_status}
                                </span>`
                            );
                            break;
                        default:
                            statusContainer.html(
                                `<span class="badge rounded-pill bg-secondary">
                                    ${services_vars.na_status}
                                </span>`
                            );
                            break;
                    }
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Company Dashboard - Delete Application
    $('.pxp-company-dashboard-delete-app-btn').on('click', function() {
        var _self = $(this);
        _self.addClass('disabled');
        _self.find('.fa').hide();
        _self.find('.pxp-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'      : 'jobster_delete_app',
                'apps'        : [{
                    'job_id'      : _self.attr('data-job-id'),
                    'candidate_id': _self.attr('data-candidate-id')
                }],
                'security'    : $('#pxp-company-candidates-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-btn-loading').hide();
                _self.find('.fa').show();

                if (data.deleted === true) {
                    document.location.href = services_vars.company_candidates_url;
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Manage candidates - bulk actions
    $('.pxp-company-dashboard-check-all-candidates').on('change', function() {
        let checks = document.querySelectorAll('.pxp-company-dashboard-check');

        if ($(this).is(':checked')) {
            checks.forEach(elem => {
                elem.checked = true;
            });
        } else {
            checks.forEach(elem => {
                elem.checked = false;
            });
        }
    });
    $('#pxp-company-dashboard-candidates-bulk-actions')
    .on('change', function() {
        if ($(this).val() == 'approve'
            || $(this).val() == 'reject'
            || $(this).val() == 'delete') {
            $('.pxp-company-dashboard-candidates-bulk-actions-apply')
            .removeClass('disabled');
        } else {
            $('.pxp-company-dashboard-candidates-bulk-actions-apply')
            .addClass('disabled');
        }
    });
    $('.pxp-company-dashboard-candidates-bulk-actions-apply')
    .on('click', function() {
        var action = $('#pxp-company-dashboard-candidates-bulk-actions').val();
        var apps = [];
        var _self = $(this);

        $('.pxp-company-dashboard-check').each(function() {
            if ($(this).is(':checked')) {
                apps.push({
                    job_id      : $(this).attr('data-job-id'),
                    candidate_id: $(this).attr('data-candidate-id')
                });
            }
        });

        if (action == 'approve') {
            if (apps.length > 0) {
                _self.addClass('disabled');
                _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-text').hide();
                _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-loading').show();

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: services_vars.ajaxurl,
                    data: {
                        'action'  : 'jobster_set_app_status',
                        'apps'    : apps,
                        'status'  : 'approved',
                        'security': $('#pxp-company-candidates-security').val()
                    },
                    success: function(data) {
                        if (data.set === true) {
                            $('.pxp-company-dashboard-check').each(function() {
                                if ($(this).is(':checked')) {
                                    $(this)
                                    .parent().parent()
                                    .find('.pxp-company-dashboard-job-status')
                                    .html(
                                        `<span class="badge rounded-pill bg-success">
                                            ${services_vars.approved_status}
                                        </span>`
                                    );
                                    $(this)[0].checked = false;
                                }
                            });
                        }

                        _self.removeClass('disabled');
                        _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-loading').hide();
                        _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-text').show();

                        $('#pxp-company-dashboard-candidates-bulk-actions').val('');
                        $('.pxp-company-dashboard-check-all-candidates')[0].checked = false;
                    },
                    error: function(errorThrown) {}
                });
            }
        } else if (action == 'reject') {
            if (apps.length > 0) {
                _self.addClass('disabled');
                _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-text').hide();
                _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-loading').show();

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: services_vars.ajaxurl,
                    data: {
                        'action'  : 'jobster_set_app_status',
                        'apps'    : apps,
                        'status'  : 'rejected',
                        'security': $('#pxp-company-candidates-security').val()
                    },
                    success: function(data) {
                        if (data.set === true) {
                            $('.pxp-company-dashboard-check').each(function() {
                                if ($(this).is(':checked')) {
                                    $(this)
                                    .parent().parent()
                                    .find('.pxp-company-dashboard-job-status')
                                    .html(
                                        `<span class="badge rounded-pill bg-danger">
                                            ${services_vars.rejected_status}
                                        </span>`
                                    );
                                    $(this)[0].checked = false;
                                }
                            });
                        }

                        _self.removeClass('disabled');
                        _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-loading').hide();
                        _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-text').show();

                        $('#pxp-company-dashboard-candidates-bulk-actions').val('');
                        $('.pxp-company-dashboard-check-all-candidates')[0].checked = false;
                    },
                    error: function(errorThrown) {}
                });
            }
        } else if (action == 'delete') {
            if (apps.length > 0) {
                _self.addClass('disabled');
                _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-text').hide();
                _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-loading').show();

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: services_vars.ajaxurl,
                    data: {
                        'action'  : 'jobster_delete_app',
                        'apps'    : apps,
                        'security': $('#pxp-company-candidates-security').val()
                    },
                    success: function(data) {
                        _self.removeClass('disabled');
                        _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-loading').hide();
                        _self.find('.pxp-company-dashboard-candidates-bulk-actions-apply-text').show();

                        document.location.href = services_vars.company_candidates_url;
                    },
                    error: function(errorThrown) {}
                });
            }
        }
    });

    // Manage company new password
    $('.pxp-company-save-password-btn').on('click', function() {
        var message;
        var _self = $(this);
        _self.addClass('disabled');
        _self.find('.pxp-company-save-password-btn-text').hide();
        _self.find('.pxp-company-save-password-btn-loading').show();
        $('.pxp-is-required').removeClass('is-invalid');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'         : 'jobster_save_pass',
                'old_pass'       : $('#pxp-company-password-old').val(),
                'new_pass'       : $('#pxp-company-password-new').val(),
                'new_pass_repeat': $('#pxp-company-password-new-repeat').val(),
                'security'       : $('#pxp-password-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-company-save-password-btn-loading').hide();
                _self.find('.pxp-company-save-password-btn-text').show();

                if (data.save === true) {
                    message = showSuccessMessage(data.message);

                    $('.pxp-company-password-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');

                    $('#pxp-company-password-old').val('');
                    $('#pxp-company-password-new').val('');
                    $('#pxp-company-password-new-repeat').val('');
                } else {
                    if (data.field) {
                        var fields = data.field.split(',');

                        fields.forEach(field => {
                            if (field == 'old') {
                                $('#pxp-company-password-old')
                                .addClass('is-invalid');
                            } else if (field == 'new') {
                                $('#pxp-company-password-new')
                                .addClass('is-invalid');
                            } else if (field == 'new_r') {
                                $('#pxp-company-password-new-repeat')
                                .addClass('is-invalid');
                            }
                        });
                    }

                    message = showErrorMessage(data.message);

                    $('.pxp-company-password-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Company inbox - send message
    $('#pxp-company-dashboard-inbox-message-field').on('keydown', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            companyInboxSendMessage();
        }
    });
    $('.pxp-company-dashboard-inbox-send-btn').on('click', function(e) {
        e.preventDefault();
        companyInboxSendMessage();
    });
    function companyInboxSendMessage() {
        $('.pxp-company-dashboard-inbox-send-btn').addClass('disabled');
        $('.pxp-company-dashboard-inbox-send-btn-text').hide();
        $('.pxp-company-dashboard-inbox-send-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'         : 'jobster_contact_candidate',
                'user_id'        : $('#pxp-company-dashboard-inbox-user-id').val(),
                'candidate_id'   : $('#pxp-company-dashboard-inbox-candidate-id').val(),
                'company_id'     : $('#pxp-company-dashboard-inbox-company-id').val(),
                'name'           : $('#pxp-company-dashboard-inbox-company-name').val(),
                'email'          : $('#pxp-company-dashboard-inbox-company-email').val(),
                'candidate_email': $('#pxp-company-dashboard-inbox-candidate-email').val(),
                'message'        : $('#pxp-company-dashboard-inbox-message-field').val(),
                'security'       : $('#pxp-company-inbox-security').val()
            },
            success: function(data) {
                $('.pxp-company-dashboard-inbox-send-btn').removeClass('disabled');
                $('.pxp-company-dashboard-inbox-send-btn-loading').hide();
                $('.pxp-company-dashboard-inbox-send-btn-text').show();

                if (data.sent === true) {
                    var avatarHolder = $('.pxp-company-dashboard-inbox-avatar-holder').html();
                    var message = $('#pxp-company-dashboard-inbox-message-field').val();
                    var newMessageHTML = 
                        `<div class="pxp-dashboard-inbox-messages-item mt-4">
                            <div class="row justify-content-end">
                                <div class="col-7">
                                    <div class="pxp-dashboard-inbox-messages-item-header flex-row-reverse">
                                        ${avatarHolder}
                                        <div class="pxp-dashboard-inbox-messages-item-time pxp-text-light me-3">
                                            ${data.time}
                                        </div>
                                    </div>
                                    <div class="pxp-dashboard-inbox-messages-item-message mt-2 pxp-is-self">
                                        ${message}
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    $('.pxp-dashboard-inbox-messages-content').append(newMessageHTML);
                    $('#pxp-company-dashboard-inbox-message-field').val('');

                    var inboxContainer = $('.pxp-dashboard-inbox-messages-content');
                    inboxContainer[0].scrollTop = inboxContainer[0].scrollHeight;
                }
            },
            error: function(errorThrown) {}
        });
    }

    // Company Dashboard - Delete Notification
    $('.pxp-company-dashboard-delete-notify-btn').on('click', function() {
        var _self = $(this);
        _self.addClass('disabled');
        _self.find('.fa').hide();
        _self.find('.pxp-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'    : 'jobster_delete_company_notify',
                'offset'    : _self.attr('data-offset'),
                'company_id': _self.attr('data-company-id'),
                'security'  : $('#pxp-company-notifications-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-btn-loading').hide();
                _self.find('.fa').show();

                if (data.deleted === true) {
                    document.location.href = services_vars.company_notifications_url;
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Set candidate notifications as read when click on bell icon
    $('.pxp-candidate-notifications > a').on('click', function() {
        var candidateId = $(this).attr('data-id');
        var _self = $(this);

        if (_self.find('.pxp-user-notifications-counter').length > 0) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'      : 'jobster_set_candidate_read_notifications',
                    'candidate_id': candidateId,
                    'security'    : $('#pxp-notifications-security').val()
                },
                success: function(data) {
                    if (data.set === true) {
                        _self.find('.pxp-user-notifications-counter').remove();
                        $('.badge.pxp-unread-notificatons').remove();
                        $('.pxp-dashboard-stats-card-info-number.pxp-is-notify').text('0');
                        _self.next('ul').find('li').removeClass('pxp-unread');
                    }
                },
                error: function(errorThrown) {}
            });
        }
    });

    // Candidate dashboard charts
    if ($('#pxp-candidate-dashboard-visitors-chart').length > 0) {
        var candidateVisitsChartElem = document
                                    .getElementById('pxp-candidate-dashboard-visitors-chart')
                                    .getContext('2d');

        var gradient = candidateVisitsChartElem.createLinearGradient(0, 250, 0, 0);
        gradient.addColorStop(0, 'rgba(255, 255, 255, 0)');
        gradient.addColorStop(.5, 'rgba(0, 112, 201, 0.09)');
        gradient.addColorStop(1, 'rgba(0, 112, 201, 0.12)');

        var candidateVisitsChart = new Chart(candidateVisitsChartElem, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', '', ''],
                datasets: [{
                    label: services_vars.visitors,
                    data: [0, 0, 0, 0, 0, 0, 0],
                    borderWidth: 3,
                    borderColor: 'rgba(0, 112, 201, 1)',
                    pointBackgroundColor: 'rgba(255, 255, 255, 0)',
                    pointHoverBackgroundColor: 'rgba(255, 255, 255, 1)',
                    pointBorderColor: 'rgba(66, 133, 244, 0)',
                    pointHoverBorderColor: 'rgba(0, 112, 201, 1)',
                    pointBorderWidth: 10,
                    pointHoverBorderWidth: 3,
                    pointHitRadius: 20,
                    cubicInterpolationMode: 'monotone',
                    fill: true,
                    backgroundColor: gradient
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        ticks: {
                            fontColor: 'rgba(153, 153, 153, 1)',
                            maxTicksLimit: 7,
                            maxRotation: 0
                        },
                        gridLines: {
                            zeroLineColor: 'rgba(232, 232, 232, 1)',
                            drawOnChartArea: false,
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: 'rgba(153, 153, 153, 1)',
                            callback: function(value, index, values) {
                                if (Math.floor(value) === value) {
                                    return value;
                                }
                            }
                        },
                        gridLines: {
                            zeroLineColor: 'rgba(232, 232, 232, 0)',
                        },
                    }],
                },
                responsive: true,
                tooltips: {
                    backgroundColor: 'rgba(0, 39, 69, 1)',
                    cornerRadius: 7,
                    mode: 'index',
                    intersect: false,
                    displayColors: false,
                    xPadding: 10,
                    yPadding: 10,
                    titleFontColor: 'rgba(255, 255, 255, .7)',
                    bodyFontColor: 'rgba(255, 255, 255, 1)',
                    titleFontStyle: 'normal',
                    bodyFontStyle: 'bold',
                },
                legend: {
                    display: false,
                }
            }
        });
    }

    function getCandidateVisitorsByPeriod(period = '-7 days') {
        if ($('#pxp-candidate-dashboard-visitors-chart').length > 0) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'      : 'jobster_get_candidate_visitors',
                    'candidate_id': $('#pxp-candidate-id').val(),
                    'period'      : period,
                    'security'    : $('#pxp-charts-security').val()
                },
                success: function(data) {
                    if (data.getvisitors === true) {
                        var visitors_no = [];
                        var visitors_dates = [];
                        var percent;
                        var versus = {
                            '-7 days'   : services_vars.vs_7_days,
                            '-30 days'  : services_vars.vs_30_days,
                            '-60 days'  : services_vars.vs_60_days,
                            '-90 days'  : services_vars.vs_90_days,
                            '-12 months': services_vars.vs_12_months,
                        };

                        $.each(data.visitors, function(date, visitor) {
                            visitors_no.push(visitor);
                            visitors_dates.push(date);
                        });

                        candidateVisitsChart.data.labels = visitors_dates;
                        candidateVisitsChart.data.datasets[0].data = visitors_no;

                        candidateVisitsChart.update();

                        $('.pxp-candidate-dashboard-visitors-chart-percent')
                        .removeClass('text-success')
                        .removeClass('text-danger');
                        $('.pxp-candidate-dashboard-visitors-number-total')
                        .text(data.total_visitors);

                        if (data.total_visitors_prev == 0) {
                            percent = parseInt(data.total_visitors) * 100;
                        } else {
                            percent = (
                                (parseInt(data.total_visitors) - parseInt(data.total_visitors_prev)) * 100
                            ) / parseInt(data.total_visitors_prev);
                        }

                        if (percent >= 0) {
                            $('.pxp-candidate-dashboard-visitors-chart-percent')
                            .addClass('text-success')
                            .html(`<span class="fa fa-long-arrow-up"></span> ${Math.abs(percent.toFixed(1))}%`);
                        } else {
                            $('.pxp-candidate-dashboard-visitors-chart-percent')
                            .addClass('text-danger')
                            .html(`<span class="fa fa-long-arrow-down"></span> ${Math.abs(percent.toFixed(1))}%`);
                        }

                        $('.pxp-candidate-dashboard-visitors-vs').html(versus[period]);
                    }
                },
                error: function(errorThrown) {}
            });
        }
    }

    getCandidateVisitorsByPeriod();

    $('#pxp-candidate-visitors-period').on('change', function() {
        getCandidateVisitorsByPeriod($(this).val());
    });

    if ($('#pxp-candidate-dashboard-apps-chart').length > 0) {
        var candidateAppsChartElem =  document
                                    .getElementById('pxp-candidate-dashboard-apps-chart')
                                    .getContext('2d');

        var gradient = candidateAppsChartElem.createLinearGradient(0, 250, 0, 0);
        gradient.addColorStop(0, 'rgba(255, 255, 255, 0)');
        gradient.addColorStop(.5, 'rgba(255, 168, 35, 0.09)');
        gradient.addColorStop(1, 'rgba(255, 168, 35, 0.12)');

        var candidateAppsChart = new Chart(candidateAppsChartElem, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', '', ''],
                datasets: [{
                    label: services_vars.applications,
                    data: [0, 0, 0, 0, 0, 0, 0],
                    borderWidth: 3,
                    borderColor: 'rgba(255, 168, 35, 1)',
                    pointBackgroundColor: 'rgba(255, 255, 255, 0)',
                    pointHoverBackgroundColor: 'rgba(255, 255, 255, 1)',
                    pointBorderColor: 'rgba(66, 133, 244, 0)',
                    pointHoverBorderColor: 'rgba(255, 168, 35, 1)',
                    pointBorderWidth: 10,
                    pointHoverBorderWidth: 3,
                    pointHitRadius: 20,
                    cubicInterpolationMode: 'monotone',
                    fill: true,
                    backgroundColor: gradient
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        ticks: {
                            fontColor: 'rgba(153, 153, 153, 1)',
                            maxTicksLimit: 7,
                            maxRotation: 0
                        },
                        gridLines: {
                            zeroLineColor: 'rgba(232, 232, 232, 1)',
                            drawOnChartArea: false,
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: 'rgba(153, 153, 153, 1)',
                            callback: function(value, index, values) {
                                if (Math.floor(value) === value) {
                                    return value;
                                }
                            }
                        },
                        gridLines: {
                            zeroLineColor: 'rgba(232, 232, 232, 0)',
                        },
                    }],
                },
                responsive: true,
                tooltips: {
                    backgroundColor: 'rgba(0, 39, 69, 1)',
                    cornerRadius: 7,
                    mode: 'index',
                    intersect: false,
                    displayColors: false,
                    xPadding: 10,
                    yPadding: 10,
                    titleFontColor: 'rgba(255, 255, 255, .7)',
                    bodyFontColor: 'rgba(255, 255, 255, 1)',
                    titleFontStyle: 'normal',
                    bodyFontStyle: 'bold',
                },
                legend: {
                    display: false,
                }
            }
        });
    }

    function getCandidateAppsByPeriod(period = '-7 days') {
        if ($('#pxp-candidate-dashboard-apps-chart').length > 0) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'      : 'jobster_get_candidate_jobs_apps',
                    'candidate_id': $('#pxp-candidate-id').val(),
                    'period'      : period,
                    'security'    : $('#pxp-charts-security').val()
                },
                success: function(data) {
                    if (data.getapps === true) {
                        var apps_no = [];
                        var apps_dates = [];
                        var percent;
                        var versus = {
                            '-7 days'   : services_vars.vs_7_days,
                            '-30 days'  : services_vars.vs_30_days,
                            '-60 days'  : services_vars.vs_60_days,
                            '-90 days'  : services_vars.vs_90_days,
                            '-12 months': services_vars.vs_12_months,
                        };

                        $.each(data.apps, function(date, app) {
                            apps_no.push(app);
                            apps_dates.push(date);
                        });

                        candidateAppsChart.data.labels = apps_dates;
                        candidateAppsChart.data.datasets[0].data = apps_no;

                        candidateAppsChart.update();

                        $('.pxp-candidate-dashboard-apps-chart-percent')
                        .removeClass('text-success')
                        .removeClass('text-danger');
                        $('.pxp-candidate-dashboard-apps-number-total')
                        .text(data.total_apps);

                        if (data.total_apps_prev == 0) {
                            percent = parseInt(data.total_apps) * 100;
                        } else {
                            percent = (
                                (parseInt(data.total_apps) - parseInt(data.total_apps_prev)) * 100
                            ) / parseInt(data.total_apps_prev);
                        }

                        if (percent >= 0) {
                            $('.pxp-candidate-dashboard-apps-chart-percent')
                            .addClass('text-success')
                            .html(`<span class="fa fa-long-arrow-up"></span> ${Math.abs(percent.toFixed(1))}%`);
                        } else {
                            $('.pxp-candidate-dashboard-apps-chart-percent')
                            .addClass('text-danger')
                            .html(`<span class="fa fa-long-arrow-down"></span> ${Math.abs(percent.toFixed(1))}%`);
                        }

                        $('.pxp-candidate-dashboard-apps-vs').html(versus[period]);
                    }
                },
                error: function(errorThrown) {}
            });
        }
    }

    getCandidateAppsByPeriod();

    $('#pxp-candidate-apps-period').on('change', function() {
        getCandidateAppsByPeriod($(this).val());
    });

    // Manage add skill to candidate dashboard profile
    $('.pxp-candidate-dashboard-add-skill-btn').on('click', function() {
        var newSkill = $('#pxp-candidate-profile-skills').val();

        if (newSkill != '') {
            $('.pxp-candidate-dashboard-skills ul').append(
                `<li data-id="">
                    ${newSkill}
                    <span class="fa fa-trash-o"></span>
                </li>`
            );
            $('#pxp-candidate-profile-skills').val('');
        }

        $('.pxp-candidate-dashboard-skills ul li span').on('click', function() {
            $(this).parent().remove();
        });
    });

    $('#pxp-candidate-profile-skills').keydown(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();

            var newSkill = $('#pxp-candidate-profile-skills').val();

            if (newSkill != '') {
                $('.pxp-candidate-dashboard-skills ul').append(
                    `<li data-id="">
                        ${newSkill}
                        <span class="fa fa-trash-o"></span>
                    </li>`
                );
                $('#pxp-candidate-profile-skills').val('');
            }
        }

        $('.pxp-candidate-dashboard-skills ul li span').on('click', function() {
            $(this).parent().remove();
        });
    });

    // Manage delete skill from candidate dashboard profile
    $('.pxp-candidate-dashboard-skills ul li span').on('click', function() {
        $(this).parent().remove();
    });

    // Manage candidate dashboard work experience
    if ($('#pxp-candidate-dashboard-work').length > 0) {
        var dataWorks = {
            'works' : []
        }
        var work = '';
        var workRaw = $('#pxp-candidate-dashboard-work').val();

        if (workRaw != '') {
            work = jsonParser(decodeURIComponent(workRaw.replace(/\+/g, ' ')));

            if (work !== null) {
                dataWorks = work;
            }
        }

        $('.pxp-candidate-dashboard-add-work-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-candidate-dashboard-work-form').removeClass('d-none');
        });

        $('.pxp-candidate-dashboard-ok-work-btn').on('click', function(event) {
            event.preventDefault();

            var title       = $('#pxp-candidate-dashboard-work-title').val();
            var company     = $('#pxp-candidate-dashboard-work-company').val();
            var period      = $('#pxp-candidate-dashboard-work-time').val();
            var description = tinyMCE.get('pxp-candidate-dashboard-work-about').getContent();

            $('.pxp-candidate-dashboard-work-form .pxp-is-required')
            .removeClass('is-invalid');

            var error = false;
            if (title == '') {
                $('#pxp-candidate-dashboard-work-title').addClass('is-invalid');
                error = true;
            }
            if (company == '') {
                $('#pxp-candidate-dashboard-work-company').addClass('is-invalid');
                error = true;
            }
            if (period == '') {
                $('#pxp-candidate-dashboard-work-time').addClass('is-invalid');
                error = true;
            }
            if (description == '') {
                $('#pxp-candidate-dashboard-work-about').addClass('is-invalid');
                error = true;
            }

            if (error === false) {
                dataWorks.works.push({
                    'title'      : title,
                    'company'    : company,
                    'period'     : period,
                    'description': description
                });

                $('#pxp-candidate-dashboard-work').val(
                    fixedEncodeURIComponent(JSON.stringify(dataWorks))
                );

                $('.pxp-candidate-dashboard-work-list tbody').append(
                    `<tr>
                        <td style="width: 30%;">
                            <div class="pxp-candidate-dashboard-work-cell-title">
                                ${title}
                            </div>
                        </td>
                        <td style="width: 25%;">
                            <div class="pxp-candidate-dashboard-work-cell-company">
                                ${company}
                            </div>
                        </td>
                        <td style="width: 25%;">
                            <div class="pxp-candidate-dashboard-work-cell-time">
                                ${period}
                            </div>
                        </td>
                        <td>
                            <div class="pxp-dashboard-table-options">
                                <ul 
                                    class="list-unstyled" 
                                    data-title="${fixedEncodeURIComponent(title)}" 
                                    data-company="${fixedEncodeURIComponent(company)}" 
                                    data-period="${fixedEncodeURIComponent(period)}" 
                                    data-description="${fixedEncodeURIComponent(description)}"
                                >
                                    <li>
                                        <button 
                                            class="pxp-candidate-dashboard-edit-new-work-btn" 
                                            title="${services_vars.edit}"
                                        >
                                            <span class="fa fa-pencil"></span>
                                        </button>
                                    </li>
                                    <li>
                                        <button 
                                            class="pxp-candidate-dashboard-delete-new-work-btn" 
                                            title="${services_vars.delete}"
                                        >
                                            <span class="fa fa-trash-o"></span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>`
                );

                $('#pxp-candidate-dashboard-work-title').val('');
                $('#pxp-candidate-dashboard-work-company').val('');
                $('#pxp-candidate-dashboard-work-time').val('');
                tinyMCE.get('pxp-candidate-dashboard-work-about').setContent('');

                $('.pxp-candidate-dashboard-work-form').addClass('d-none');
                $('.pxp-candidate-dashboard-add-work-btn').show();

                $('.pxp-candidate-dashboard-edit-new-work-btn')
                .unbind('click')
                .on('click', function(event) {
                    event.preventDefault();
                    editCandidateWorkExperience($(this));
                });
                $('.pxp-candidate-dashboard-delete-new-work-btn')
                .unbind('click')
                .on('click', function(event) {
                    event.preventDefault();
                    delCandidateWorkExperience($(this));
                });
            }
        });

        $('.pxp-candidate-dashboard-cancel-work-btn').on('click', function(event) {
            event.preventDefault();

            $('#pxp-candidate-dashboard-work-title').val('');
            $('#pxp-candidate-dashboard-work-company').val('');
            $('#pxp-candidate-dashboard-work-time').val('');
            tinyMCE.get('pxp-candidate-dashboard-work-about').setContent('');

            $('.pxp-candidate-dashboard-work-form').addClass('d-none');
            $('.pxp-candidate-dashboard-add-work-btn').show();
        });

        $('.pxp-candidate-dashboard-edit-work-btn').on('click', function(event) {
            event.preventDefault();
            editCandidateWorkExperience($(this));
        });
        $('.pxp-candidate-dashboard-delete-work-btn').on('click', function(event) {
            event.preventDefault();
            delCandidateWorkExperience($(this));
        });
    }

    function delCandidateWorkExperience(btn) {
        btn.parent().parent().parent().parent().parent().remove();

        dataWorks.works = [];

        $('.pxp-candidate-dashboard-work-list .pxp-dashboard-table-options')
        .each(function(index, el) {
            var elem = $(this).find('ul');

            dataWorks.works.push({
                'title'      : decodeURIComponent(elem.attr('data-title').replace(/\+/g, ' ')),
                'company'    : decodeURIComponent(elem.attr('data-company').replace(/\+/g, ' ')),
                'period'     : decodeURIComponent(elem.attr('data-period').replace(/\+/g, ' ')),
                'description': decodeURIComponent(elem.attr('data-description').replace(/\+/g, ' '))
            });
        });

        $('#pxp-candidate-dashboard-work').val(
            fixedEncodeURIComponent(JSON.stringify(dataWorks))
        );
    }

    function editCandidateWorkExperience(btn) {
        var dataElem = btn.parent().parent();

        var editTitle       = decodeURIComponent(dataElem.attr('data-title').replace(/\+/g, ' '));
        var editCompany     = decodeURIComponent(dataElem.attr('data-company').replace(/\+/g, ' '));
        var editPeriod      = decodeURIComponent(dataElem.attr('data-period').replace(/\+/g, ' '));
        var editDescription = decodeURIComponent(dataElem.attr('data-description').replace(/\+/g, ' '));

        var random = Math.floor(Math.random() * 10000);

        $('.pxp-candidate-dashboard-edit-work-form').append(
            `<div class="row mt-3 mt-lg-4">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-dashboard-edit-work-title" 
                            class="form-label"
                        >
                            ${services_vars.job_title}
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-candidate-dashboard-edit-work-title" 
                            class="form-control pxp-is-required" 
                            placeholder="${services_vars.job_title_placeholder}" 
                            value="${editTitle}"
                        >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-dashboard-edit-work-company" 
                            class="form-label"
                        >
                            ${services_vars.company}
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-candidate-dashboard-edit-work-company" 
                            class="form-control pxp-is-required" 
                            placeholder="${services_vars.company_placeholder}" 
                            value="${editCompany}"
                        >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-dashboard-edit-work-time" 
                            class="form-label"
                        >
                            ${services_vars.time_period}
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-candidate-dashboard-edit-work-time" 
                            class="form-control pxp-is-required" 
                            placeholder="E.g. 2005 - 2013" 
                            value="${editPeriod}"
                        >
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label 
                    for="pxp-candidate-dashboard-edit-work-about${random}" 
                    class="form-label"
                >
                    ${services_vars.description}
                    <span class="text-danger">*</span>
                </label>
                <textarea 
                    class="form-control pxp-smaller pxp-is-required" 
                    id="pxp-candidate-dashboard-edit-work-about${random}" 
                    placeholder="${services_vars.type_description}"
                >${editDescription}</textarea>
            </div>

            <a 
                href="javascript:void(0);" 
                class="btn rounded-pill pxp-subsection-cta pxp-candidate-dashboard-ok-edit-work-btn"
            >
                ${services_vars.update}
            </a>
            <a 
                href="javascript:void(0);" 
                class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-candidate-dashboard-cancel-edit-work-btn"
            >
                ${services_vars.cancel}
            </a>`
        );

        tinyMCE.init({
            selector: '#pxp-candidate-dashboard-edit-work-about' + random,
            plugins: 'lists, link',
            toolbar: 'bold italic underline blockquote strikethrough bullist numlist alignleft aligncenter alignright undo redo link fullscreen',
            height: 240,
            menubar: false,
        });
        tinyMCE.get('pxp-candidate-dashboard-edit-work-about' + random).setContent(editDescription);

        $('.pxp-candidate-dashboard-edit-work-btn').hide();
        $('.pxp-candidate-dashboard-delete-work-btn').hide();
        $('.pxp-candidate-dashboard-edit-new-work-btn').hide();
        $('.pxp-candidate-dashboard-delete-new-work-btn').hide();
        $('.pxp-candidate-dashboard-work-form').addClass('d-none');
        $('.pxp-candidate-dashboard-add-work-btn').hide();

        $('.pxp-candidate-dashboard-edit-work-form')[0].scrollIntoView();

        $('.pxp-candidate-dashboard-ok-edit-work-btn').on('click', function() {
            var eTitle       = $('#pxp-candidate-dashboard-edit-work-title').val();
            var eCompany     = $('#pxp-candidate-dashboard-edit-work-company').val();
            var ePeriod      = $('#pxp-candidate-dashboard-edit-work-time').val();
            var eDescription = tinyMCE.get('pxp-candidate-dashboard-edit-work-about' + random).getContent();

            dataElem.attr({
                'data-title'      : fixedEncodeURIComponent(eTitle),
                'data-company'    : fixedEncodeURIComponent(eCompany),
                'data-period'     : fixedEncodeURIComponent(ePeriod),
                'data-description': fixedEncodeURIComponent(eDescription)
            });

            var dataElemRow = dataElem.parent().parent().parent();
            dataElemRow.find('.pxp-candidate-dashboard-work-cell-title').text(eTitle);
            dataElemRow.find('.pxp-candidate-dashboard-work-cell-company').text(eCompany);
            dataElemRow.find('.pxp-candidate-dashboard-work-cell-time').text(ePeriod);

            $('.pxp-candidate-dashboard-edit-work-form').empty();
            $('.pxp-candidate-dashboard-edit-work-btn').show();
            $('.pxp-candidate-dashboard-delete-work-btn').show();
            $('.pxp-candidate-dashboard-edit-new-work-btn').show();
            $('.pxp-candidate-dashboard-delete-new-work-btn').show();
            $('.pxp-candidate-dashboard-add-work-btn').show();

            dataWorks.works = [];

            $('.pxp-candidate-dashboard-work-list .pxp-dashboard-table-options')
            .each(function(index, el) {
                var elem = $(this).find('ul');

                dataWorks.works.push({
                    'title'      : decodeURIComponent(elem.attr('data-title').replace(/\+/g, ' ')),
                    'company'    : decodeURIComponent(elem.attr('data-company').replace(/\+/g, ' ')),
                    'period'     : decodeURIComponent(elem.attr('data-period').replace(/\+/g, ' ')),
                    'description': decodeURIComponent(elem.attr('data-description').replace(/\+/g, ' '))
                });
            });

            $('#pxp-candidate-dashboard-work').val(
                fixedEncodeURIComponent(JSON.stringify(dataWorks))
            );

            $('.pxp-candidate-dashboard-work-list')[0].scrollIntoView();
        });

        $('.pxp-candidate-dashboard-cancel-edit-work-btn').on('click', function() {
            $('.pxp-candidate-dashboard-edit-work-form').empty();
            $('.pxp-candidate-dashboard-edit-work-btn').show();
            $('.pxp-candidate-dashboard-delete-work-btn').show();
            $('.pxp-candidate-dashboard-edit-new-work-btn').show();
            $('.pxp-candidate-dashboard-delete-new-work-btn').show();
            $('.pxp-candidate-dashboard-add-work-btn').show();

            $('.pxp-candidate-dashboard-work-list')[0].scrollIntoView();
        });
    };

    // Manage candidate dashboard education
    if ($('#pxp-candidate-dashboard-edu').length > 0) {
        var dataEdu = {
            'edus' : []
        }
        var edu = '';
        var eduRaw = $('#pxp-candidate-dashboard-edu').val();

        if (eduRaw != '') {
            edu = jsonParser(decodeURIComponent(eduRaw.replace(/\+/g, ' ')));

            if (edu !== null) {
                dataEdu = edu;
            }
        }

        $('.pxp-candidate-dashboard-add-edu-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-candidate-dashboard-edu-form').removeClass('d-none');
        });

        $('.pxp-candidate-dashboard-ok-edu-btn').on('click', function(event) {
            event.preventDefault();

            var title       = $('#pxp-candidate-dashboard-edu-title').val();
            var school      = $('#pxp-candidate-dashboard-edu-school').val();
            var period      = $('#pxp-candidate-dashboard-edu-time').val();
            var description = $('#pxp-candidate-dashboard-edu-about').val();

            $('.pxp-candidate-dashboard-edu-form .pxp-is-required')
            .removeClass('is-invalid');

            var error = false;
            if (title == '') {
                $('#pxp-candidate-dashboard-edu-title').addClass('is-invalid');
                error = true;
            }
            if (school == '') {
                $('#pxp-candidate-dashboard-edu-school').addClass('is-invalid');
                error = true;
            }
            if (period == '') {
                $('#pxp-candidate-dashboard-edu-time').addClass('is-invalid');
                error = true;
            }
            if (description == '') {
                $('#pxp-candidate-dashboard-edu-about').addClass('is-invalid');
                error = true;
            }

            if (error === false) {
                dataEdu.edus.push({
                    'title'      : title,
                    'school'     : school,
                    'period'     : period,
                    'description': description
                });

                $('#pxp-candidate-dashboard-edu').val(
                    fixedEncodeURIComponent(JSON.stringify(dataEdu))
                );

                $('.pxp-candidate-dashboard-edu-list tbody').append(
                    `<tr>
                        <td style="width: 30%;">
                            <div class="pxp-candidate-dashboard-edu-cell-title">
                                ${title}
                            </div>
                        </td>
                        <td style="width: 25%;">
                            <div class="pxp-candidate-dashboard-edu-cell-school">
                                ${school}
                            </div>
                        </td>
                        <td style="width: 25%;">
                            <div class="pxp-candidate-dashboard-edu-cell-time">
                                ${period}
                            </div>
                        </td>
                        <td>
                            <div class="pxp-dashboard-table-options">
                                <ul 
                                    class="list-unstyled" 
                                    data-title="${title}" 
                                    data-school="${school}" 
                                    data-period="${period}" 
                                    data-description="${description}"
                                >
                                    <li>
                                        <button 
                                            class="pxp-candidate-dashboard-edit-new-edu-btn" 
                                            title="${services_vars.edit}"
                                        >
                                            <span class="fa fa-pencil"></span>
                                        </button>
                                    </li>
                                    <li>
                                        <button 
                                            class="pxp-candidate-dashboard-delete-new-edu-btn" 
                                            title="${services_vars.delete}"
                                        >
                                            <span class="fa fa-trash-o"></span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>`
                );

                $('#pxp-candidate-dashboard-edu-title').val('');
                $('#pxp-candidate-dashboard-edu-school').val('');
                $('#pxp-candidate-dashboard-edu-time').val('');
                $('#pxp-candidate-dashboard-edu-about').val('');

                $('.pxp-candidate-dashboard-edu-form').addClass('d-none');
                $('.pxp-candidate-dashboard-add-edu-btn').show();

                $('.pxp-candidate-dashboard-edit-new-edu-btn')
                .unbind('click')
                .on('click', function(event) {
                    event.preventDefault();
                    editCandidateEducation($(this));
                });
                $('.pxp-candidate-dashboard-delete-new-edu-btn')
                .unbind('click')
                .on('click', function(event) {
                    event.preventDefault();
                    delCandidateEducation($(this));
                });
            }
        });

        $('.pxp-candidate-dashboard-cancel-edu-btn').on('click', function(event) {
            event.preventDefault();

            $('#pxp-candidate-dashboard-edu-title').val('');
            $('#pxp-candidate-dashboard-edu-school').val('');
            $('#pxp-candidate-dashboard-edu-time').val('');
            $('#pxp-candidate-dashboard-edu-about').val('');

            $('.pxp-candidate-dashboard-edu-form').addClass('d-none');
            $('.pxp-candidate-dashboard-add-edu-btn').show();
        });

        $('.pxp-candidate-dashboard-edit-edu-btn').on('click', function(event) {
            event.preventDefault();
            editCandidateEducation($(this));
        });
        $('.pxp-candidate-dashboard-delete-edu-btn').on('click', function(event) {
            event.preventDefault();
            delCandidateEducation($(this));
        });
    }

    function delCandidateEducation(btn) {
        btn.parent().parent().parent().parent().parent().remove();

        dataEdu.edus = [];

        $('.pxp-candidate-dashboard-edu-list .pxp-dashboard-table-options')
        .each(function(index, el) {
            var elem = $(this).find('ul');

            dataEdu.edus.push({
                'title'      : elem.attr('data-title'),
                'school'     : elem.attr('data-school'),
                'period'     : elem.attr('data-period'),
                'description': elem.attr('data-description')
            });
        });

        $('#pxp-candidate-dashboard-edu').val(
            fixedEncodeURIComponent(JSON.stringify(dataEdu))
        );
    }

    function editCandidateEducation(btn) {
        var dataElem = btn.parent().parent();

        var editTitle       = dataElem.attr('data-title');
        var editSchool      = dataElem.attr('data-school');
        var editPeriod      = dataElem.attr('data-period');
        var editDescription = dataElem.attr('data-description');

        $('.pxp-candidate-dashboard-edit-edu-form').append(
            `<div class="row mt-3 mt-lg-4">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-dashboard-edit-edu-title" 
                            class="form-label"
                        >
                            ${services_vars.edu_title}
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-candidate-dashboard-edit-edu-title" 
                            class="form-control pxp-is-required" 
                            placeholder="${services_vars.edu_title_placeholder}" 
                            value="${editTitle}"
                        >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-dashboard-edit-edu-school" 
                            class="form-label"
                        >
                            ${services_vars.edu_school}
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-candidate-dashboard-edit-edu-school" 
                            class="form-control pxp-is-required" 
                            placeholder="${services_vars.edu_school_placeholder}" 
                            value="${editSchool}"
                        >
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label 
                            for="pxp-candidate-dashboard-edit-edu-time" 
                            class="form-label"
                        >
                            ${services_vars.time_period}
                            <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="pxp-candidate-dashboard-edit-edu-time" 
                            class="form-control pxp-is-required" 
                            placeholder="E.g. 2005 - 2013" 
                            value="${editPeriod}"
                        >
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label 
                    for="pxp-candidate-dashboard-edit-edu-about" 
                    class="form-label"
                >
                    ${services_vars.description}
                    <span class="text-danger">*</span>
                </label>
                <textarea 
                    class="form-control pxp-smaller pxp-is-required" 
                    id="pxp-candidate-dashboard-edit-edu-about" 
                    placeholder="${services_vars.type_description}"
                >${editDescription}</textarea>
            </div>

            <a 
                href="javascript:void(0);" 
                class="btn rounded-pill pxp-subsection-cta pxp-candidate-dashboard-ok-edit-edu-btn"
            >
                ${services_vars.update}
            </a>
            <a 
                href="javascript:void(0);" 
                class="btn rounded-pill pxp-subsection-cta-o ms-e pxp-candidate-dashboard-cancel-edit-edu-btn"
            >
                ${services_vars.cancel}
            </a>`
        );

        $('.pxp-candidate-dashboard-edit-edu-btn').hide();
        $('.pxp-candidate-dashboard-delete-edu-btn').hide();
        $('.pxp-candidate-dashboard-edit-new-edu-btn').hide();
        $('.pxp-candidate-dashboard-delete-new-edu-btn').hide();
        $('.pxp-candidate-dashboard-edu-form').addClass('d-none');
        $('.pxp-candidate-dashboard-add-edu-btn').hide();

        $('.pxp-candidate-dashboard-edit-edu-form')[0].scrollIntoView();

        $('.pxp-candidate-dashboard-ok-edit-edu-btn').on('click', function() {
            var eTitle       = $('#pxp-candidate-dashboard-edit-edu-title').val();
            var eSchool      = $('#pxp-candidate-dashboard-edit-edu-school').val();
            var ePeriod      = $('#pxp-candidate-dashboard-edit-edu-time').val();
            var eDescription = $('#pxp-candidate-dashboard-edit-edu-about').val();

            dataElem.attr({
                'data-title'      : eTitle,
                'data-school'     : eSchool,
                'data-period'     : ePeriod,
                'data-description': eDescription
            });

            var dataElemRow = dataElem.parent().parent().parent();
            dataElemRow.find('.pxp-candidate-dashboard-edu-cell-title').text(eTitle);
            dataElemRow.find('.pxp-candidate-dashboard-edu-cell-school').text(eSchool);
            dataElemRow.find('.pxp-candidate-dashboard-edu-cell-time').text(ePeriod);

            $('.pxp-candidate-dashboard-edit-edu-form').empty();
            $('.pxp-candidate-dashboard-edit-edu-btn').show();
            $('.pxp-candidate-dashboard-delete-edu-btn').show();
            $('.pxp-candidate-dashboard-edit-new-edu-btn').show();
            $('.pxp-candidate-dashboard-delete-new-edu-btn').show();
            $('.pxp-candidate-dashboard-add-edu-btn').show();

            dataEdu.edus = [];

            $('.pxp-candidate-dashboard-edu-list .pxp-dashboard-table-options')
            .each(function(index, el) {
                var elem = $(this).find('ul');

                dataEdu.edus.push({
                    'title'      : elem.attr('data-title'),
                    'school'     : elem.attr('data-school'),
                    'period'     : elem.attr('data-period'),
                    'description': elem.attr('data-description')
                });
            });

            $('#pxp-candidate-dashboard-edu').val(
                fixedEncodeURIComponent(JSON.stringify(dataEdu))
            );

            $('.pxp-candidate-dashboard-edu-list')[0].scrollIntoView();
        });

        $('.pxp-candidate-dashboard-cancel-edit-edu-btn').on('click', function() {
            $('.pxp-candidate-dashboard-edit-edu-form').empty();
            $('.pxp-candidate-dashboard-edit-edu-btn').show();
            $('.pxp-candidate-dashboard-delete-edu-btn').show();
            $('.pxp-candidate-dashboard-edit-new-edu-btn').show();
            $('.pxp-candidate-dashboard-delete-new-edu-btn').show();
            $('.pxp-candidate-dashboard-add-edu-btn').show();

            $('.pxp-candidate-dashboard-edu-list')[0].scrollIntoView();
        });
    };

    // Manage update candidate profile
    $('.pxp-candidate-profile-update-btn').on('click', function() {
        var message;
        $(this).addClass('disabled');
        $('.pxp-candidate-profile-update-btn-text').hide();
        $('.pxp-candidate-profile-update-btn-loading').show();
        $('.pxp-is-required').removeClass('is-invalid');

        var skills = [];
        $('.pxp-candidate-dashboard-skills ul li').each(function() {
            skills.push({
                'id': $(this).attr('data-id'),
                'name': $(this).text().trim()
            });
        });

        var cfields = [];
        $('.pxp-candidate-profile-custom-field').each(function(index) {
            cfields.push({
                field_name     : $(this).attr('id'),
                field_value    : $(this).val(),
                field_label    : $(this).attr('data-label'),
                field_mandatory: $(this).attr('data-mandatory')
            });
        });

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'         : 'jobster_update_candidate_profile',
                'candidate_id'   : $('#pxp-candidate-profile-id').val(),
                'name'           : $('#pxp-candidate-profile-name').val(),
                'email'          : $('#pxp-candidate-profile-email').val(),
                'phone'          : $('#pxp-candidate-profile-phone').val(),
                'title'          : $('#pxp-candidate-profile-title').val(),
                'website'        : $('#pxp-candidate-profile-website').val(),
                'cover'          : $('#pxp-dashboard-cover').val(),
                'cover_type'     : $('#pxp-candidate-profile-cover-type').val(),
                'cover_color'    : $('#pxp-candidate-profile-cover-color').val(),
                'logo'           : $('#pxp-dashboard-logo').val(),
                'about'          : getTinymceContent('pxp-candidate-profile-about'),
                'industry'       : $('#pxp-candidate-profile-industry').val(),
                'location'       : $('#pxp-candidate-profile-location').val(),
                'skills'         : fixedEncodeURIComponent(JSON.stringify(skills)),
                'work'           : $('#pxp-candidate-dashboard-work').val(),
                'education'      : $('#pxp-candidate-dashboard-edu').val(),
                'files'          : $('#pxp-candidate-dashboard-files').val(),
                'facebook'       : $('#pxp-candidate-profile-facebook').val(),
                'twitter'        : $('#pxp-candidate-profile-twitter').val(),
                'instagram'      : $('#pxp-candidate-profile-instagram').val(),
                'linkedin'       : $('#pxp-candidate-profile-linkedin').val(),
                'gallery'        : $('#pxp-profile-gallery-field').val(),
                'gallery_title'  : $('#pxp-candidate-profile-gallery-title').val(),
                'video'          : $('#pxp-candidate-profile-video').val(),
                'video_title'    : $('#pxp-candidate-profile-video-title').val(),
                'alerts'         : $('#pxp-candidate-profile-alerts:checked').val(),
                'alerts_location': $('#pxp-candidate-profile-alerts-location').val(),
                'alerts_category': $('#pxp-candidate-profile-alerts-category').val(),
                'alerts_type'    : $('#pxp-candidate-profile-alerts-type').val(),
                'alerts_level'   : $('#pxp-candidate-profile-alerts-level').val(),
                'cv'             : $('#pxp-dashboard-cv').val(),
                'cfields'        : cfields,
                'security'       : $('#pxp-candidate-profile-security').val()
            },
            success: function(data) {
                $('.pxp-candidate-profile-update-btn').removeClass('disabled');
                $('.pxp-candidate-profile-update-btn-loading').hide();
                $('.pxp-candidate-profile-update-btn-text').show();

                if (data.update === true) {
                    document.location.href = services_vars.candidate_profile_url;
                } else {
                    if (data.field) {
                        $(`#${data.field}`).addClass('is-invalid');
                    }

                    message = showErrorMessage(data.message);

                    $('.pxp-candidate-profile-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Candidate Dashboard - Delete Application
    $('.pxp-candidate-dashboard-delete-app-btn').on('click', function() {
        var _self = $(this);
        _self.addClass('disabled');
        _self.find('.fa').hide();
        _self.find('.pxp-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'      : 'jobster_delete_app',
                'apps'        : [{
                    'job_id'      : _self.attr('data-job-id'),
                    'candidate_id': _self.attr('data-candidate-id')
                }],
                'security'    : $('#pxp-candidate-apps-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-btn-loading').hide();
                _self.find('.fa').show();

                if (data.deleted === true) {
                    document.location.href = services_vars.candidate_apps_url;
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Manage candidate applications - bulk actions
    $('.pxp-candidate-dashboard-check-all-apps').on('change', function() {
        let checks = document.querySelectorAll('.pxp-candidate-dashboard-check');

        if ($(this).is(':checked')) {
            checks.forEach(elem => {
                elem.checked = true;
            });
        } else {
            checks.forEach(elem => {
                elem.checked = false;
            });
        }
    });
    $('#pxp-candidate-dashboard-apps-bulk-actions')
    .on('change', function() {
        if ($(this).val() == 'delete') {
            $('.pxp-candidate-dashboard-apps-bulk-actions-apply')
            .removeClass('disabled');
        } else {
            $('.pxp-candidate-dashboard-apps-bulk-actions-apply')
            .addClass('disabled');
        }
    });
    $('.pxp-candidate-dashboard-apps-bulk-actions-apply')
    .on('click', function() {
        var action = $('#pxp-candidate-dashboard-apps-bulk-actions').val();
        var apps = [];
        var _self = $(this);

        $('.pxp-candidate-dashboard-check').each(function() {
            if ($(this).is(':checked')) {
                apps.push({
                    job_id      : $(this).attr('data-job-id'),
                    candidate_id: $(this).attr('data-candidate-id')
                });
            }
        });

        if (action == 'delete') {
            if (apps.length > 0) {
                _self.addClass('disabled');
                _self.find('.pxp-candidate-dashboard-apps-bulk-actions-apply-text').hide();
                _self.find('.pxp-candidate-dashboard-apps-bulk-actions-apply-loading').show();

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: services_vars.ajaxurl,
                    data: {
                        'action'  : 'jobster_delete_app',
                        'apps'    : apps,
                        'security': $('#pxp-candidate-apps-security').val()
                    },
                    success: function(data) {
                        _self.removeClass('disabled');
                        _self.find('.pxp-candidate-dashboard-apps-bulk-actions-apply-loading').hide();
                        _self.find('.pxp-candidate-dashboard-apps-bulk-actions-apply-text').show();

                        document.location.href = services_vars.candidate_apps_url;
                    },
                    error: function(errorThrown) {}
                });
            }
        }
    });

    // Candidate Dashboard - Delete Favourite Job
    $('.pxp-candidate-dashboard-delete-fav-btn').on('click', function() {
        var _self = $(this);
        _self.addClass('disabled');
        _self.find('.fa').hide();
        _self.find('.pxp-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'  : 'jobster_candidate_remove_fav',
                'job_ids' : [_self.attr('data-job-id')],
                'security': $('#pxp-candidate-favs-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-btn-loading').hide();
                _self.find('.fa').show();

                if (data.removed === true) {
                    document.location.href = services_vars.candidate_favs_url;
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Manage candidate favs - bulk actions
    $('.pxp-candidate-dashboard-check-all-favs').on('change', function() {
        let checks = document.querySelectorAll('.pxp-candidate-dashboard-check');

        if ($(this).is(':checked')) {
            checks.forEach(elem => {
                elem.checked = true;
            });
        } else {
            checks.forEach(elem => {
                elem.checked = false;
            });
        }
    });
    $('#pxp-candidate-dashboard-favs-bulk-actions')
    .on('change', function() {
        if ($(this).val() == 'delete') {
            $('.pxp-candidate-dashboard-favs-bulk-actions-apply')
            .removeClass('disabled');
        } else {
            $('.pxp-candidate-dashboard-favs-bulk-actions-apply')
            .addClass('disabled');
        }
    });
    $('.pxp-candidate-dashboard-favs-bulk-actions-apply')
    .on('click', function() {
        var action = $('#pxp-candidate-dashboard-favs-bulk-actions').val();
        var job_ids = [];
        var _self = $(this);

        $('.pxp-candidate-dashboard-check').each(function() {
            if ($(this).is(':checked')) {
                job_ids.push($(this).attr('data-job-id'));
            }
        });

        if (action == 'delete') {
            if (job_ids.length > 0) {
                _self.addClass('disabled');
                _self.find('.pxp-candidate-dashboard-favs-bulk-actions-apply-text').hide();
                _self.find('.pxp-candidate-dashboard-favs-bulk-actions-apply-loading').show();

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: services_vars.ajaxurl,
                    data: {
                        'action'  : 'jobster_candidate_remove_fav',
                        'job_ids' : job_ids,
                        'security': $('#pxp-candidate-favs-security').val()
                    },
                    success: function(data) {
                        _self.removeClass('disabled');
                        _self.find('.pxp-candidate-dashboard-favs-bulk-actions-apply-loading').hide();
                        _self.find('.pxp-candidate-dashboard-favs-bulk-actions-apply-text').show();

                        document.location.href = services_vars.candidate_favs_url;
                    },
                    error: function(errorThrown) {}
                });
            }
        }
    });

    // Manage candidate new password
    $('.pxp-candidate-save-password-btn').on('click', function() {
        var message;
        var _self = $(this);
        _self.addClass('disabled');
        _self.find('.pxp-candidate-save-password-btn-text').hide();
        _self.find('.pxp-candidate-save-password-btn-loading').show();
        $('.pxp-is-required').removeClass('is-invalid');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'         : 'jobster_save_pass',
                'old_pass'       : $('#pxp-candidate-password-old').val(),
                'new_pass'       : $('#pxp-candidate-password-new').val(),
                'new_pass_repeat': $('#pxp-candidate-password-new-repeat').val(),
                'security'       : $('#pxp-password-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-candidate-save-password-btn-loading').hide();
                _self.find('.pxp-candidate-save-password-btn-text').show();

                if (data.save === true) {
                    message = showSuccessMessage(data.message);

                    $('.pxp-candidate-password-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');

                    $('#pxp-candidate-password-old').val('');
                    $('#pxp-candidate-password-new').val('');
                    $('#pxp-candidate-password-new-repeat').val('');
                } else {
                    if (data.field) {
                        var fields = data.field.split(',');

                        fields.forEach(field => {
                            if (field == 'old') {
                                $('#pxp-candidate-password-old')
                                .addClass('is-invalid');
                            } else if (field == 'new') {
                                $('#pxp-candidate-password-new')
                                .addClass('is-invalid');
                            } else if (field == 'new_r') {
                                $('#pxp-candidate-password-new-repeat')
                                .addClass('is-invalid');
                            }
                        });
                    }

                    message = showErrorMessage(data.message);

                    $('.pxp-candidate-password-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Candidate inbox - send message
    $('#pxp-candidate-dashboard-inbox-message-field').on('keydown', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            candidateInboxSendMessage();
        }
    });
    $('.pxp-candidate-dashboard-inbox-send-btn').on('click', function(e) {
        e.preventDefault();
        candidateInboxSendMessage();
    });
    function candidateInboxSendMessage() {
        $('.pxp-candidate-dashboard-inbox-send-btn').addClass('disabled');
        $('.pxp-candidate-dashboard-inbox-send-btn-text').hide();
        $('.pxp-candidate-dashboard-inbox-send-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'         : 'jobster_contact_company',
                'user_id'        : $('#pxp-candidate-dashboard-inbox-user-id').val(),
                'candidate_id'   : $('#pxp-candidate-dashboard-inbox-candidate-id').val(),
                'company_id'     : $('#pxp-candidate-dashboard-inbox-company-id').val(),
                'name'           : $('#pxp-candidate-dashboard-inbox-candidate-name').val(),
                'email'          : $('#pxp-candidate-dashboard-inbox-candidate-email').val(),
                'company_email'  : $('#pxp-candidate-dashboard-inbox-company-email').val(),
                'message'        : $('#pxp-candidate-dashboard-inbox-message-field').val(),
                'security'       : $('#pxp-candidate-inbox-security').val()
            },
            success: function(data) {
                $('.pxp-candidate-dashboard-inbox-send-btn').removeClass('disabled');
                $('.pxp-candidate-dashboard-inbox-send-btn-loading').hide();
                $('.pxp-candidate-dashboard-inbox-send-btn-text').show();

                if (data.sent === true) {
                    var avatarHolder = $('.pxp-candidate-dashboard-inbox-avatar-holder').html();
                    var message = $('#pxp-candidate-dashboard-inbox-message-field').val();
                    var newMessageHTML = 
                        `<div class="pxp-dashboard-inbox-messages-item mt-4">
                            <div class="row justify-content-end">
                                <div class="col-7">
                                    <div class="pxp-dashboard-inbox-messages-item-header flex-row-reverse">
                                        ${avatarHolder}
                                        <div class="pxp-dashboard-inbox-messages-item-time pxp-text-light me-3">
                                            ${data.time}
                                        </div>
                                    </div>
                                    <div class="pxp-dashboard-inbox-messages-item-message mt-2 pxp-is-self">
                                        ${message}
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    $('.pxp-dashboard-inbox-messages-content').append(newMessageHTML);
                    $('#pxp-candidate-dashboard-inbox-message-field').val('');

                    var inboxContainer = $('.pxp-dashboard-inbox-messages-content');
                    inboxContainer[0].scrollTop = inboxContainer[0].scrollHeight;
                }
            },
            error: function(errorThrown) {}
        });
    }

    // Candidate Dashboard - Delete Notification
    $('.pxp-candidate-dashboard-delete-notify-btn').on('click', function() {
        var _self = $(this);
        _self.addClass('disabled');
        _self.find('.fa').hide();
        _self.find('.pxp-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'      : 'jobster_delete_candidate_notify',
                'offset'      : _self.attr('data-offset'),
                'candidate_id': _self.attr('data-candidate-id'),
                'security'    : $('#pxp-candidate-notifications-security').val()
            },
            success: function(data) {
                _self.removeClass('disabled');
                _self.find('.pxp-btn-loading').hide();
                _self.find('.fa').show();

                if (data.deleted === true) {
                    document.location.href = services_vars.candidate_notifications_url;
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Subscribe form service
    $('.pxp-subscribe-1-form-btn').click(function() {
        var message;
        $(this).addClass('disabled');
        $('.pxp-subscribe-1-form-btn-text').hide();
        $('.pxp-subscribe-1-form-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'  : 'jobster_save_subscription',
                'email'   : $('#pxp-subscribe-1-form-email').val(),
                'security': $('#pxp-subscribe-block-security').val()
            },
            success: function(data) {
                $('.pxp-subscribe-1-form-btn').removeClass('disabled');
                $('.pxp-subscribe-1-form-btn-loading').hide();
                $('.pxp-subscribe-1-form-btn-text').show();

                if (data.save === true) {
                    message = showSuccessMessage(data.message);

                    $('.pxp-subscribe-1-form-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                } else {
                    message = showErrorMessage(data.message);

                    $('.pxp-subscribe-1-form-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Manage send message with contact form block
    $('.pxp-contact-form-block-btn').on('click', function() {
        var message;
        $(this).addClass('disabled');
        $('.pxp-contact-form-block-btn-text').hide();
        $('.pxp-contact-form-block-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'       : 'jobster_contact_block_send',
                'name'         : $('#pxp-contact-form-block-name').val(),
                'email'        : $('#pxp-contact-form-block-email').val(),
                'company_email': $('#pxp-contact-form-block-company-email').val(),
                'message'      : $('#pxp-contact-form-block-message').val(),
                'security'     : $('#pxp-contact-form-block-security').val()
            },
            success: function(data) {
                $('.pxp-contact-form-block-btn').removeClass('disabled');
                $('.pxp-contact-form-block-btn-loading').hide();
                $('.pxp-contact-form-block-btn-text').show();

                if (data.sent === true) {
                    message = showSuccessMessage(data.message);

                    $('.pxp-contact-form-block-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                } else {
                    message = showErrorMessage(data.message);

                    $('.pxp-contact-form-block-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Manage pay per posting 
    var sPrice  = $('#pxp-company-dashboard-jobs-standard-price').val();
    var fPrice  = $('#pxp-company-dashboard-jobs-featured-price').val();
    var sfPrice = parseFloat(sPrice) + parseFloat(fPrice);

    $('.pxp-dashboard-table-options-dropdown').click(function(event) {
        event.stopPropagation();
    });

    $('.pxp-company-dashboard-jobs-featured').on('change', function() {
        var parentDropdown = $(this).parent().parent();
        var payFeatured = parentDropdown.find('.pxp-company-dashboard-jobs-pay-featured');
        var payBtn = parentDropdown.find('.pxp-company-dashboard-jobs-payment-btn');
        var payTotal = parentDropdown.find('.pxp-company-dashboard-jobs-payment-btn-total');

        if ($(this).is(':checked')) {
            if (payFeatured.length > 0) {
                payBtn.attr('data-featured', '').show();
            } else {
                payTotal.text(sfPrice.toFixed(2));
                payBtn.attr('data-featured', '1');
            }
        } else {
            payBtn.attr('data-featured', '');

            if (payFeatured.length > 0) {
                payBtn.hide();
            } else {
                payTotal.text(parseFloat(sPrice));
            }
        }
    });

    $('.pxp-company-dashboard-jobs-featured-free').on('change', function() {
        var parentDropdown = $(this).parent().parent();
        var upgradeBtn = parentDropdown.find('.pxp-company-dashboard-jobs-payment-upgrade-btn');

        if ($(this).is(':checked')) {
            upgradeBtn.show();
        } else {
            upgradeBtn.hide();
        }
    });

    $('.pxp-company-dashboard-jobs-payment-upgrade-btn').on('click', function() {
        var _self = $(this);

        _self.addClass('disabled');
        $('.pxp-company-dashboard-jobs-payment-upgrade-btn-text').hide();
        $('.pxp-company-dashboard-jobs-payment-upgrade-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'    : 'jobster_upgrade_job_featured',
                'job_id'    : _self.attr('data-id'),
                'company_id': _self.attr('data-company-id'),
                'security'  : $('#pxp-upgrade-job-security').val()
            },
            success: function(data) {
                if (data.upgrade === true) {
                    document.location.href = services_vars.company_jobs_url;
                } else {
                    $('.pxp-company-dashboard-jobs-payment-upgrade-btn-loading').hide();
                    $('.pxp-company-dashboard-jobs-payment-upgrade-btn-text').show();
                    _self.removeClass('disabled');
                }
            },
            error: function(errorThrown) {}
        });
    });

    $('.pxp-company-dashboard-jobs-payment-btn').click(function() {
        var _self = $(this);
        var system = _self.attr('data-system');

        _self.addClass('disabled');
        $('.pxp-company-dashboard-jobs-payment-btn-text').hide();
        $('.pxp-company-dashboard-jobs-payment-btn-loading').show();

        if (system == 'paypal') {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'     : 'jobster_paypal_pay_listing',
                    'job_id'    : _self.attr('data-id'),
                    'is_featured': _self.attr('data-featured'),
                    'is_upgrade' : _self.attr('data-upgrade')
                },
                success: function(data) {
                    if (data) {
                        window.location = data.url;
                    } else {
                        _self.removeClass('disabled');
                        $('.pxp-company-dashboard-jobs-payment-btn-loading').hide();
                        $('.pxp-company-dashboard-jobs-payment-btn-text').show();
                    }
                },
                error: function(errorThrown) {}
            });
        }
        if (system == 'stripe') {
            var stripe = Stripe(services_vars.stripe_pk);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'     : 'jobster_stripe_pay_listing',
                    'job_id'    : _self.attr('data-id'),
                    'is_featured': _self.attr('data-featured'),
                    'is_upgrade' : _self.attr('data-upgrade')
                },
                success: function(data) {
                    if (data.success === true) {
                        stripe.redirectToCheckout({
                            sessionId: data.sessionId
                        });
                    } else {
                        _self.removeClass('disabled');
                        $('.pxp-company-dashboard-jobs-payment-btn-loading').hide();
                        $('.pxp-company-dashboard-jobs-payment-btn-text').show();
                    }
                },
                error: function(errorThrown) {}
            });
        }
    });

    $('.pxp-activate-plan-btn').click(function() {
        var _self = $(this);

        _self.addClass('disabled');
        $('.pxp-activate-plan-btn-text').hide();
        $('.pxp-activate-plan-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action' : 'jobster_activate_membership_plan',
                'plan_id': _self.attr('data-id')
            },
            success: function(data) {
                if (data) {
                    window.location = data.url;
                } else {
                    $('.pxp-activate-plan-btn-loading').hide();
                    $('.pxp-activate-plan-btn-text').show();
                    _self.removeClass('disabled');
                }
            },
            error: function(errorThrown) {}
        });
    });

    $('.pxp-pay-plan-btn').click(function() {
        var _self = $(this);
        var system = _self.attr('data-system');

        _self.addClass('disabled');
        _self.find('.pxp-pay-plan-btn-text').hide();
        _self.find('.pxp-pay-plan-btn-loading').show();


        if (system == 'paypal') {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'  : 'jobster_paypal_pay_membership_plan',
                    'plan_id' : _self.attr('data-id')
                },
                success: function(data) {
                    if (data) {
                        window.location = data.url;
                    } else {
                        _self.find('.pxp-pay-plan-btn-loading').hide();
                        _self.find('.pxp-pay-plan-btn-text').show();
                        _self.removeClass('disabled');
                    }
                },
                error: function(errorThrown) {}
            });
        }
        if (system == 'stripe') {
            var stripe = Stripe(services_vars.stripe_pk);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'  : 'jobster_stripe_pay_membership_plan',
                    'plan_id' : _self.attr('data-id')
                },
                success: function(data) {
                    if (data.success === true) {
                        stripe.redirectToCheckout({
                            sessionId: data.sessionId
                        });
                    } else {
                        _self.find('.pxp-pay-plan-btn-loading').hide();
                        _self.find('.pxp-pay-plan-btn-text').show();
                        _self.removeClass('disabled');
                    }
                },
                error: function(errorThrown) {}
            });
        }
    });

    $('.pxp-company-dashboard-jobs-payment-featured-btn').on('click', function() {
        var _self = $(this);

        _self.addClass('disabled');
        _self.find('.pxp-company-dashboard-jobs-payment-featured-btn-text').hide();
        _self.find('.pxp-company-dashboard-jobs-payment-featured-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'    : 'jobster_set_job_featured',
                'job_id'    : _self.attr('data-id'),
                'company_id': _self.attr('data-company-id'),
                'security'  : $('#pxp-featured-job-security').val()
            },
            success: function(data) {
                if (data.upgrade === true) {
                    document.location.href = services_vars.company_jobs_url;
                } else {
                    _self.find('.pxp-company-dashboard-jobs-payment-featured-btn-loading').hide();
                    _self.find('.pxp-company-dashboard-jobs-payment-featured-btn-text').show();
                    _self.removeClass('disabled');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Anonymous job application
    $('.pxp-candidate-apply-btn').on('click', function() {
        var message;
        $(this).addClass('disabled');
        $('.pxp-candidate-apply-btn-text').hide();
        $('.pxp-candidate-apply-btn-loading').show();
        $('.pxp-is-required').removeClass('is-invalid');

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'  : 'jobster_job_apply_anonymous',
                'job_id'  : $('#pxp-candidate-apply-id').val(),
                'name'    : $('#pxp-candidate-apply-name').val(),
                'email'   : $('#pxp-candidate-apply-email').val(),
                'phone'   : $('#pxp-candidate-apply-phone').val(),
                'message' : $('#pxp-candidate-apply-message').val(),
                'cv'      : $('#pxp-dashboard-cv').val(),
                'files'   : $('#pxp-candidate-dashboard-files').val(),
                'security': $('#pxp-candidate-apply-security').val()
            },
            success: function(data) {
                $('.pxp-candidate-apply-btn').removeClass('disabled');
                $('.pxp-candidate-apply-btn-loading').hide();
                $('.pxp-candidate-apply-btn-text').show();

                if (data.saved === true) {
                    $('.pxp-dashboard-content-details-form').empty();
                    $('#pxp-dashboard-anonymous-apply-alert').removeClass('d-none');
                } else {
                    if (data.field) {
                        $(`#${data.field}`).addClass('is-invalid');
                    }

                    message = showErrorMessage(data.message);

                    $('.pxp-candidate-apply-response')
                    .empty()
                    .append(message)
                    .fadeIn('slow');
                }
            },
            error: function(errorThrown) {}
        });
    });

    // Candidate dashboard - profile new location
    $('.pxp-candidate-dashboard-add-location-btn').on('click', function(event) {
        event.preventDefault();

        $(this).hide();
        $('.pxp-candidate-dashboard-location-select').addClass('d-none');
        $('.pxp-candidate-dashboard-location-new').removeClass('d-none');
        $('#pxp-candidate-profile-location-new').focus();
    });

    $('.pxp-candidate-location-ok-btn').on('click', function(event) {
        event.preventDefault();
        var _self = $(this);

        _self.addClass('disabled');
        $('.pxp-candidate-location-ok-btn-text').hide();
        $('.pxp-candidate-location-cancel-btn').hide();
        $('.pxp-candidate-location-ok-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'  : 'jobster_add_candidate_location',
                'name'    : $('#pxp-candidate-profile-location-new').val(),
                'parent'  : $('#pxp-candidate-profile-location-parent').val(),
                'security': $('#pxp-candidate-profile-security').val()
            },
            success: function(data) {
                if (data.add === true) {
                    var locations = data.locations;
                    var options = '';
                    for (let i = 0; i < locations.length; i++) {
                        options += `<option value="${locations[i]['id']}">${locations[i]['label']}</option>`;
                    }
                    $('#pxp-candidate-profile-location').html(options).val(data.location_id);

                    $('#pxp-candidate-profile-location-new').val('');
                    $('#pxp-candidate-profile-location-parent').val('0');
                    _self.removeClass('disabled');
                    $('.pxp-candidate-location-ok-btn-loading').hide();
                    $('.pxp-candidate-location-ok-btn-text').show();
                    $('.pxp-candidate-location-cancel-btn').show();

                    $('.pxp-candidate-dashboard-add-location-btn').show();
                    $('.pxp-candidate-dashboard-location-new').addClass('d-none');
                    $('.pxp-candidate-dashboard-location-select').removeClass('d-none');
                } else {
                    if (data.field) {
                        $(`#${data.field}`).addClass('is-invalid');
                    }

                    _self.removeClass('disabled');
                    $('.pxp-candidate-location-ok-btn-loading').hide();
                    $('.pxp-candidate-location-ok-btn-text').show();
                    $('.pxp-candidate-location-cancel-btn').show();
                }

            },
            error: function(errorThrown) {}
        });
    });
    $('.pxp-candidate-location-cancel-btn').on('click', function(event) {
        event.preventDefault();

        $('#pxp-candidate-profile-location-new').val('').removeClass('is-invalid');
        $('#pxp-candidate-profile-location-parent').val('0');
        $('.pxp-candidate-dashboard-location-new').addClass('d-none');
        $('.pxp-candidate-dashboard-location-select').removeClass('d-none');
        $('.pxp-candidate-dashboard-add-location-btn').show();
    });

    // Company dashboard - profile new location
    $('.pxp-company-dashboard-add-location-btn').on('click', function(event) {
        event.preventDefault();

        $(this).hide();
        $('.pxp-company-dashboard-location-select').addClass('d-none');
        $('.pxp-company-dashboard-location-new').removeClass('d-none');
        $('#pxp-company-profile-location-new').focus();
    });

    $('.pxp-company-location-ok-btn').on('click', function(event) {
        event.preventDefault();
        var _self = $(this);

        _self.addClass('disabled');
        $('.pxp-company-location-ok-btn-text').hide();
        $('.pxp-company-location-cancel-btn').hide();
        $('.pxp-company-location-ok-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'  : 'jobster_add_company_location',
                'name'    : $('#pxp-company-profile-location-new').val(),
                'parent'  : $('#pxp-company-profile-location-parent').val(),
                'security': $('#pxp-company-profile-security').val()
            },
            success: function(data) {
                if (data.add === true) {
                    var locations = data.locations;
                    var options = '';
                    for (let i = 0; i < locations.length; i++) {
                        options += `<option value="${locations[i]['id']}">${locations[i]['label']}</option>`;
                    }
                    $('#pxp-company-profile-location').html(options).val(data.location_id);

                    $('#pxp-company-profile-location-new').val('');
                    $('#pxp-company-profile-location-parent').val('0');
                    _self.removeClass('disabled');
                    $('.pxp-company-location-ok-btn-loading').hide();
                    $('.pxp-company-location-ok-btn-text').show();
                    $('.pxp-company-location-cancel-btn').show();

                    $('.pxp-company-dashboard-add-location-btn').show();
                    $('.pxp-company-dashboard-location-new').addClass('d-none');
                    $('.pxp-company-dashboard-location-select').removeClass('d-none');
                } else {
                    if (data.field) {
                        $(`#${data.field}`).addClass('is-invalid');
                    }

                    _self.removeClass('disabled');
                    $('.pxp-company-location-ok-btn-loading').hide();
                    $('.pxp-company-location-ok-btn-text').show();
                    $('.pxp-company-location-cancel-btn').show();
                }

            },
            error: function(errorThrown) {}
        });
    });
    $('.pxp-company-location-cancel-btn').on('click', function(event) {
        event.preventDefault();

        $('#pxp-company-profile-location-new').val('').removeClass('is-invalid');
        $('#pxp-company-profile-location-parent').val('0');
        $('.pxp-company-dashboard-location-new').addClass('d-none');
        $('.pxp-company-dashboard-location-select').removeClass('d-none');
        $('.pxp-company-dashboard-add-location-btn').show();
    });

    // Company dashboard - new job - new location
    $('.pxp-company-new-job-add-location-btn').on('click', function(event) {
        event.preventDefault();

        $(this).hide();
        $('.pxp-company-new-job-location-select').addClass('d-none');
        $('.pxp-company-new-job-location-new').removeClass('d-none');
        $('#pxp-company-new-job-location-new').focus();
    });

    $('.pxp-company-new-job-location-ok-btn').on('click', function(event) {
        event.preventDefault();
        var _self = $(this);

        _self.addClass('disabled');
        $('.pxp-company-new-job-location-ok-btn-text').hide();
        $('.pxp-company-new-job-location-cancel-btn').hide();
        $('.pxp-company-new-job-location-ok-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'  : 'jobster_add_job_location_new',
                'name'    : $('#pxp-company-new-job-location-new').val(),
                'parent'  : $('#pxp-company-new-job-location-parent').val(),
                'security': $('#pxp-company-new-job-location-security').val()
            },
            success: function(data) {
                if (data.add === true) {
                    var locations = data.locations;
                    var options = '';
                    for (let i = 0; i < locations.length; i++) {
                        options += `<option value="${locations[i]['id']}">${locations[i]['label']}</option>`;
                    }
                    $('#pxp-company-new-job-location').html(options).val(data.location_id);

                    $('#pxp-company-new-job-location-new').val('');
                    $('#pxp-company-new-job-location-parent').val('0');
                    _self.removeClass('disabled');
                    $('.pxp-company-new-job-location-ok-btn-loading').hide();
                    $('.pxp-company-new-job-location-ok-btn-text').show();
                    $('.pxp-company-new-job-location-cancel-btn').show();

                    $('.pxp-company-new-job-add-location-btn').show();
                    $('.pxp-company-new-job-location-new').addClass('d-none');
                    $('.pxp-company-new-job-location-select').removeClass('d-none');
                } else {
                    if (data.field) {
                        $(`#${data.field}`).addClass('is-invalid');
                    }

                    _self.removeClass('disabled');
                    $('.pxp-company-new-job-location-ok-btn-loading').hide();
                    $('.pxp-company-new-job-location-ok-btn-text').show();
                    $('.pxp-company-new-job-location-cancel-btn').show();
                }

            },
            error: function(errorThrown) {}
        });
    });
    $('.pxp-company-new-job-location-cancel-btn').on('click', function(event) {
        event.preventDefault();

        $('#pxp-company-new-job-location-new').val('').removeClass('is-invalid');
        $('#pxp-company-new-job-location-parent').val('0');
        $('.pxp-company-new-job-location-new').addClass('d-none');
        $('.pxp-company-new-job-location-select').removeClass('d-none');
        $('.pxp-company-new-job-add-location-btn').show();
    });

    // Company dashboard - edit job - new location
    $('.pxp-company-edit-job-add-location-btn').on('click', function(event) {
        event.preventDefault();

        $(this).hide();
        $('.pxp-company-edit-job-location-select').addClass('d-none');
        $('.pxp-company-edit-job-location-new').removeClass('d-none');
        $('#pxp-company-edit-job-location-new').focus();
    });

    $('.pxp-company-edit-job-location-ok-btn').on('click', function(event) {
        event.preventDefault();
        var _self = $(this);

        _self.addClass('disabled');
        $('.pxp-company-edit-job-location-ok-btn-text').hide();
        $('.pxp-company-edit-job-location-cancel-btn').hide();
        $('.pxp-company-edit-job-location-ok-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'  : 'jobster_add_job_location_edit',
                'name'    : $('#pxp-company-edit-job-location-new').val(),
                'parent'  : $('#pxp-company-edit-job-location-parent').val(),
                'security': $('#pxp-company-edit-job-location-security').val()
            },
            success: function(data) {
                if (data.add === true) {
                    var locations = data.locations;
                    var options = '';
                    for (let i = 0; i < locations.length; i++) {
                        options += `<option value="${locations[i]['id']}">${locations[i]['label']}</option>`;
                    }
                    $('#pxp-company-edit-job-location').html(options).val(data.location_id);

                    $('#pxp-company-edit-job-location-new').val('');
                    $('#pxp-company-edit-job-location-parent').val('0');
                    _self.removeClass('disabled');
                    $('.pxp-company-edit-job-location-ok-btn-loading').hide();
                    $('.pxp-company-edit-job-location-ok-btn-text').show();
                    $('.pxp-company-edit-job-location-cancel-btn').show();

                    $('.pxp-company-edit-job-add-location-btn').show();
                    $('.pxp-company-edit-job-location-new').addClass('d-none');
                    $('.pxp-company-edit-job-location-select').removeClass('d-none');
                } else {
                    if (data.field) {
                        $(`#${data.field}`).addClass('is-invalid');
                    }

                    _self.removeClass('disabled');
                    $('.pxp-company-edit-job-location-ok-btn-loading').hide();
                    $('.pxp-company-edit-job-location-ok-btn-text').show();
                    $('.pxp-company-edit-job-location-cancel-btn').show();
                }

            },
            error: function(errorThrown) {}
        });
    });
    $('.pxp-company-edit-job-location-cancel-btn').on('click', function(event) {
        event.preventDefault();

        $('#pxp-company-edit-job-location-new').val('').removeClass('is-invalid');
        $('#pxp-company-edit-job-location-parent').val('0');
        $('.pxp-company-edit-job-location-new').addClass('d-none');
        $('.pxp-company-edit-job-location-select').removeClass('d-none');
        $('.pxp-company-edit-job-add-location-btn').show();
    });

    // Manage candidate dashboard files
    if ($('#pxp-candidate-dashboard-files').length > 0) {
        var dataFiles = {
            'files' : []
        }
        var files = '';
        var filesRaw = $('#pxp-candidate-dashboard-files').val();

        if (filesRaw != '') {
            files = jsonParser(decodeURIComponent(filesRaw.replace(/\+/g, ' ')));

            if (files !== null) {
                dataFiles = files;
            }
        }

        $('.pxp-candidate-dashboard-add-file-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-candidate-dashboard-file-form').removeClass('d-none');
        });

        $('.pxp-candidate-dashboard-ok-file-btn').on('click', function(event) {
            event.preventDefault();

            var name = $('#pxp-candidate-dashboard-file-name').val();
            var url  = $('#pxp-candidate-dashboard-file-url').val();
            var id   = $('#pxp-candidate-dashboard-file-id').val();

            $('.pxp-candidate-dashboard-file-form .pxp-is-required')
            .removeClass('is-invalid');

            var error = false;
            if (name == '') {
                $('#pxp-candidate-dashboard-file-name').addClass('is-invalid');
                error = true;
            }

            if (error === false) {
                dataFiles.files.push({
                    'name': name,
                    'id'  : id,
                    'url' : url
                });

                $('#pxp-candidate-dashboard-files').val(
                    fixedEncodeURIComponent(JSON.stringify(dataFiles))
                );

                $('.pxp-candidate-dashboard-files-list tbody').append(
                    `<tr>
                        <td style="width: 80%;">
                            <div class="pxp-candidate-dashboard-files-cell-name">
                                ${name}
                            </div>
                        </td>
                        <td>
                            <div class="pxp-dashboard-table-options">
                                <ul 
                                    class="list-unstyled" 
                                    data-name="${name}" 
                                    data-id="${id}" 
                                    data-url="${url}"
                                >
                                    <li>
                                        <a 
                                            href="${url}"
                                            class="pxp-candidate-dashboard-download-file-btn" 
                                            title="${services_vars.download}"
                                        >
                                            <span class="fa fa-download"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <button 
                                            class="pxp-candidate-dashboard-delete-new-file-btn" 
                                            title="${services_vars.delete}"
                                        >
                                            <span class="fa fa-trash-o"></span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>`
                )


                $('#pxp-candidate-dashboard-file-name').val('');
                $('.pxp-dashboard-upload-file-placeholder').empty();
                $('#pxp-candidate-dashboard-file-id').val('');
                $('#pxp-candidate-dashboard-file-url').val('');

                $('.pxp-candidate-dashboard-file-form').addClass('d-none');
                $('.pxp-candidate-dashboard-add-file-btn').show();

                $('.pxp-candidate-dashboard-delete-new-file-btn')
                .unbind('click')
                .on('click', function(event) {
                    event.preventDefault();
                    delCandidateFile($(this));
                });
            }
        });

        $('.pxp-candidate-dashboard-cancel-file-btn').on('click', function(event) {
            event.preventDefault();

            $('#pxp-candidate-dashboard-file-name').val('');
            $('.pxp-dashboard-upload-file-placeholder').empty();
            $('#pxp-candidate-dashboard-file-id').val('');
            $('#pxp-candidate-dashboard-file-url').val('');

            $('.pxp-candidate-dashboard-file-form').addClass('d-none');
            $('.pxp-candidate-dashboard-add-file-btn').show();
        });

        $('.pxp-candidate-dashboard-delete-file-btn').on('click', function(event) {
            event.preventDefault();
            delCandidateFile($(this));
        });
    }

    function delCandidateFile(btn) {
        btn.parent().parent().parent().parent().parent().remove();

        dataFiles.files = [];

        $('.pxp-candidate-dashboard-files-list .pxp-dashboard-table-options')
        .each(function(index, el) {
            var elem = $(this).find('ul');

            dataFiles.files.push({
                'name': elem.attr('data-name'),
                'id'  : elem.attr('data-id'),
                'url' : elem.attr('data-url')
            });
        });

        $('#pxp-candidate-dashboard-files').val(
            fixedEncodeURIComponent(JSON.stringify(dataFiles))
        );
    }

    /* Autocomplete */

    function autocomplete(inp, arr) {
        var currentFocus;

        inp.addEventListener('input', function(e) {
            var a, b, i, val = this.value;

            closeAllLists();
            if (!val) { 
                $(inp).parent().prev('.pxp-autocomplete-value').val('0');
                return false; 
            }
            currentFocus = -1;

            a = document.createElement('div');
            a.setAttribute('id', this.id + 'pxp-autocomplete-list');
            a.setAttribute('class', 'pxp-autocomplete-items');

            this.parentNode.appendChild(a);

            Object.keys(arr).forEach(key => {
                if (arr[key].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    b = document.createElement('div');

                    b.innerHTML = `<strong>${arr[key].substr(0, val.length)}</strong>${arr[key].substr(val.length)}<input type="hidden" value="${arr[key]}" data-id="${key}">`;

                    b.addEventListener('click', function(e) {
                        inp.value = this.getElementsByTagName('input')[0].value;
                        var dataId = this.getElementsByTagName('input')[0].getAttribute('data-id');
                        $(inp).parent().prev('.pxp-autocomplete-value').val(dataId);
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            });

            if (a.childNodes.length === 0) {
                b = document.createElement('div');
                b.innerHTML = `<i>${services_vars.locations_empty}</i>`;
                a.appendChild(b);
                $(inp).parent().prev('.pxp-autocomplete-value').val('0');
            }
        });

        inp.addEventListener('keydown', function(e) {
            var x = document.getElementById(this.id + 'pxp-autocomplete-list');
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                currentFocus++;
                addActive(x);
            } else if (e.keyCode == 38) {
                currentFocus--;
                addActive(x);
            } else if (e.keyCode == 13) {
                e.preventDefault();
                if (currentFocus > -1) {
                    if (x) x[currentFocus].click();
                }
            }
        });

        function addActive(x) {
            if (!x) return false;
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            x[currentFocus].classList.add('pxp-autocomplete-active');
        }

        function removeActive(x) {
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove('pxp-autocomplete-active');
            }
        }
        function closeAllLists(elmnt) {
            var x = document.getElementsByClassName('pxp-autocomplete-items');

            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }

        document.addEventListener('click', function(e) {
            closeAllLists(e.target);
        });
    }

    if ($('.pxp-autocomplete-jobs').length > 0) {
        autocomplete(
            document.querySelector('.pxp-autocomplete-jobs'),
            services_vars.job_locations
        );
    }
    if ($('.pxp-autocomplete-companies').length > 0) {
        autocomplete(
            document.querySelector('.pxp-autocomplete-companies'),
            services_vars.company_locations
        );
    }
    if ($('.pxp-autocomplete-candidates').length > 0) {
        autocomplete(
            document.querySelector('.pxp-autocomplete-candidates'),
            services_vars.candidate_locations
        );
    }
    $('#pxp-keywords-field-floating').on('keyup', function() {
        var keywords = $(this).val();
        $('#keywords').val(keywords);
    });

    // Manage company dashboard dashboard - new job - Benefits
    if ($('#pxp-company-new-job-benefits').length > 0) {
        var dataBenefits = {
            'benefits' : []
        }
        var benefits = '';
        var benefitsRaw = $('#pxp-company-new-job-benefits').val();

        if (benefitsRaw != '') {
            benefits = jsonParser(decodeURIComponent(benefitsRaw.replace(/\+/g, ' ')));

            if (benefits !== null) {
                dataBenefits = benefits;
            }
        }

        $('.pxp-company-new-job-add-benefit-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-company-new-job-benefit-form').removeClass('d-none');
        });

        $('.pxp-company-new-job-ok-benefit-btn').on('click', function(event) {
            event.preventDefault();

            var title   = $('#pxp-company-new-job-benefit-title').val();
            var icon    = $('.pxp-dashboard-logo-photo').attr('data-id');
            var iconSrc = $('.pxp-dashboard-logo-photo').attr('data-src');

            $('.pxp-company-new-job-benefit-form .pxp-is-required')
            .removeClass('is-invalid');

            var error = false;
            if (title == '') {
                $('#pxp-company-new-job-benefit-title').addClass('is-invalid');
                error = true;
            }

            if (error === false) {
                dataBenefits.benefits.push({
                    'title'   : title,
                    'icon'    : icon,
                    'icon_src': iconSrc
                });

                $('#pxp-company-new-job-benefits').val(
                    fixedEncodeURIComponent(JSON.stringify(dataBenefits))
                );

                var iconHTML = `<span>
                                    <img src="${iconSrc}" alt="${title}">
                                </span>`;
                if (icon == '') {
                    iconHTML = `<span class="fa fa-star-o"></span>`;
                }

                $('.pxp-company-new-job-benefits-list tbody').append(
                    `<tr>
                        <td style="width: 5%;">
                            <div class="pxp-company-new-job-benefits-cell-icon">
                                ${iconHTML}
                            </div>
                        </td>
                        <td style="width: 70%;">
                            <div class="pxp-company-new-job-benefits-cell-title">
                                ${title}
                            </div>
                        </td>
                        <td>
                            <div class="pxp-dashboard-table-options">
                                <ul 
                                    class="list-unstyled" 
                                    data-title="${title}" 
                                    data-icon="${icon}" 
                                    data-src="${iconSrc}"
                                >
                                    <li>
                                        <button 
                                            type="button"
                                            class="pxp-company-dashboard-delete-new-job-benefit-btn" 
                                            title="${services_vars.delete}"
                                        >
                                            <span class="fa fa-trash-o"></span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>`
                );

                $('#pxp-company-new-job-benefit-title').val('');
                $('#pxp-upload-container-logo').removeClass('pxp-has-image');
                    $('.pxp-dashboard-logo').html(
                        `<div 
                            class="pxp-dashboard-logo-photo pxp-cover has-animation" 
                            data-id=""
                        ></div>`
                    );
                $('#pxp-dashboard-logo').val('');
                $('#pxp-uploader-logo').text(services_vars.upload_icon);

                $('.pxp-company-new-job-benefit-form').addClass('d-none');
                $('.pxp-company-new-job-add-benefit-btn').show();

                $('.pxp-company-dashboard-delete-new-job-benefit-btn')
                .unbind('click')
                .on('click', function(event) {
                    event.preventDefault();
                    delNewJobBenefit($(this));
                });
            }
        });

        $('.pxp-company-new-job-cancel-benefit-btn').on('click', function(event) {
            event.preventDefault();

            $('#pxp-company-new-job-benefit-title').val('');
            $('#pxp-upload-container-logo').removeClass('pxp-has-image');
                $('.pxp-dashboard-logo').html(
                    `<div 
                        class="pxp-dashboard-logo-photo pxp-cover has-animation" 
                        data-id=""
                    ></div>`
                );
            $('#pxp-dashboard-logo').val('');
            $('#pxp-uploader-logo').text(services_vars.upload_icon);

            $('.pxp-company-new-job-benefit-form').addClass('d-none');
            $('.pxp-company-new-job-add-benefit-btn').show();
        });
    }

    function delNewJobBenefit(btn) {
        var iconId = btn.parent().parent().attr('data-icon');

        if (iconId != '') {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'  : 'jobster_delete_new_job_benefit_icon',
                    'icon_id' : iconId,
                    'security': $('#pxp-company-new-job-security').val()
                },
                success: function(data) {
                    updateNewJobBenefitsList(btn);
                },
                error: function(errorThrown) {}
            });
        } else {
            updateNewJobBenefitsList(btn);
        }
    }

    function updateNewJobBenefitsList(btn) {
        btn.parent().parent().parent().parent().parent().remove();

        dataBenefits.benefits = [];

        $('.pxp-company-new-job-benefits-list .pxp-dashboard-table-options')
        .each(function(index, el) {
            var elem = $(this).find('ul');

            dataBenefits.benefits.push({
                'title'   : elem.attr('data-title'),
                'icon'    : elem.attr('data-icon'),
                'icon_src': elem.attr('data-src')
            });
        });

        $('#pxp-company-new-job-benefits').val(
            fixedEncodeURIComponent(JSON.stringify(dataBenefits))
        );
    }

    // Manage company dashboard dashboard - edit job - Benefits
    if ($('#pxp-company-edit-job-benefits').length > 0) {
        var dataBenefits = {
            'benefits' : []
        }
        var benefits = '';
        var benefitsRaw = $('#pxp-company-edit-job-benefits').val();

        if (benefitsRaw != '') {
            benefits = jsonParser(decodeURIComponent(benefitsRaw.replace(/\+/g, ' ')));

            if (benefits !== null) {
                dataBenefits = benefits;
            }
        }

        $('.pxp-company-edit-job-add-benefit-btn').on('click', function(event) {
            event.preventDefault();

            $(this).hide();
            $('.pxp-company-edit-job-benefit-form').removeClass('d-none');
        });

        $('.pxp-company-edit-job-ok-benefit-btn').on('click', function(event) {
            event.preventDefault();

            var title   = $('#pxp-company-edit-job-benefit-title').val();
            var icon    = $('.pxp-dashboard-logo-photo').attr('data-id');
            var iconSrc = $('.pxp-dashboard-logo-photo').attr('data-src');

            $('.pxp-company-edit-job-benefit-form .pxp-is-required')
            .removeClass('is-invalid');

            var error = false;
            if (title == '') {
                $('#pxp-company-edit-job-benefit-title').addClass('is-invalid');
                error = true;
            }

            if (error === false) {
                dataBenefits.benefits.push({
                    'title'   : title,
                    'icon'    : icon,
                    'icon_src': iconSrc
                });

                $('#pxp-company-edit-job-benefits').val(
                    fixedEncodeURIComponent(JSON.stringify(dataBenefits))
                );

                var iconHTML = `<span>
                                    <img src="${iconSrc}" alt="${title}">
                                </span>`;
                if (icon == '') {
                    iconHTML = `<span class="fa fa-star-o"></span>`;
                }

                $('.pxp-company-edit-job-benefits-list tbody').append(
                    `<tr>
                        <td style="width: 5%;">
                            <div class="pxp-company-edit-job-benefits-cell-icon">
                                ${iconHTML}
                            </div>
                        </td>
                        <td style="width: 70%;">
                            <div class="pxp-company-edit-job-benefits-cell-title">
                                ${title}
                            </div>
                        </td>
                        <td>
                            <div class="pxp-dashboard-table-options">
                                <ul 
                                    class="list-unstyled" 
                                    data-title="${title}" 
                                    data-icon="${icon}" 
                                    data-src="${iconSrc}"
                                >
                                    <li>
                                        <button 
                                            type="button" 
                                            class="pxp-company-dashboard-delete-edit-job-benefit-btn" 
                                            title="${services_vars.delete}"
                                        >
                                            <span class="fa fa-trash-o"></span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>`
                );

                $('#pxp-company-edit-job-benefit-title').val('');
                $('#pxp-upload-container-logo').removeClass('pxp-has-image');
                    $('.pxp-dashboard-logo').html(
                        `<div 
                            class="pxp-dashboard-logo-photo pxp-cover has-animation" 
                            data-id=""
                        ></div>`
                    );
                $('#pxp-dashboard-logo').val('');
                $('#pxp-uploader-logo').text(services_vars.upload_icon);

                $('.pxp-company-edit-job-benefit-form').addClass('d-none');
                $('.pxp-company-edit-job-add-benefit-btn').show();

                $('.pxp-company-dashboard-delete-edit-job-benefit-btn')
                .unbind('click')
                .on('click', function(event) {
                    event.preventDefault();
                    delEditJobBenefit($(this));
                });
            }
        });

        $('.pxp-company-edit-job-cancel-benefit-btn').on('click', function(event) {
            event.preventDefault();

            $('#pxp-company-edit-job-benefit-title').val('');
            $('#pxp-upload-container-logo').removeClass('pxp-has-image');
                $('.pxp-dashboard-logo').html(
                    `<div 
                        class="pxp-dashboard-logo-photo pxp-cover has-animation" 
                        data-id=""
                    ></div>`
                );
            $('#pxp-dashboard-logo').val('');
            $('#pxp-uploader-logo').text(services_vars.upload_icon);

            $('.pxp-company-edit-job-benefit-form').addClass('d-none');
            $('.pxp-company-edit-job-add-benefit-btn').show();
        });

        $('.pxp-company-dashboard-delete-job-benefit-btn').on('click', function(event) {
            event.preventDefault();
            delEditJobBenefit($(this));
        });
    }

    function delEditJobBenefit(btn) {
        var iconId = btn.parent().parent().attr('data-icon');

        if (iconId != '') {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: services_vars.ajaxurl,
                data: {
                    'action'  : 'jobster_delete_edit_job_benefit_icon',
                    'icon_id' : iconId,
                    'security': $('#pxp-company-edit-job-security').val()
                },
                success: function(data) {
                    updateEditJobBenefitsList(btn);
                },
                error: function(errorThrown) {}
            });
        } else {
            updateEditJobBenefitsList(btn);
        }
    }

    function updateEditJobBenefitsList(btn) {
        btn.parent().parent().parent().parent().parent().remove();

        dataBenefits.benefits = [];

        $('.pxp-company-edit-job-benefits-list .pxp-dashboard-table-options')
        .each(function(index, el) {
            var elem = $(this).find('ul');

            dataBenefits.benefits.push({
                'title'   : elem.attr('data-title'),
                'icon'    : elem.attr('data-icon'),
                'icon_src': elem.attr('data-src')
            });
        });

        $('#pxp-company-edit-job-benefits').val(
            fixedEncodeURIComponent(JSON.stringify(dataBenefits))
        );
    }

    $('.pxp-account-type-modal-btn').on('click', function(event) {
        var btn = $(this);

        btn.addClass('disabled');
        $('.pxp-account-type-modal-btn').addClass('disabled');
        btn.find('.pxp-account-type-modal-btn-text').hide();
        btn.find('.pxp-account-type-modal-btn-loading').show();

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'  : 'jobster_activate_user_type',
                'type'    : btn.attr('data-type'),
                'security': $('#pxp-signin-modal-security').val()
            },
            success: function(data) {
                var redirect = window.location.href.split(/\?|#/)[0];
                document.location.href = redirect;
            },
            error: function(errorThrown) {}
        });
    });
})(jQuery);