(function($) {

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

    $('.pxp-fb-signin-btn').on('click', function(e) {
        e.preventDefault();
        $(this).find('.pxp-fb-signin-btn-text').text(services_vars.fb_wait_btn_text);
        fbLogin();
    });

    function fbLogin() {
        var message;
        var isModal = $('.pxp-signin-page').length > 0 ? false : true;

        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                var newUser = getUserInfo(function(user) {
                    wpFBLogin(user);
                });
            } else if (response.status === 'not_authorized') {
                FB.login(function(response) {
                    if (response.authResponse) {
                        var newUser = getUserInfo(function(user) {
                            wpFBLogin(user);
                        });
                    } else {
                        message = showSuccessMessage(services_vars.fb_signin_error);

                        if (isModal) {
                            $('.pxp-signin-modal-message').empty().append(message).fadeIn('slow');
                            $('.pxp-fb-signin-btn-text').text(services_vars.fb_signin_btn_text);
                        } else {
                            $('.pxp-signin-page-message').empty().append(message).fadeIn('slow');
                        }
                    }
                }, {
                    scope: 'public_profile, email'
                });
            } else {
                FB.login(function(response) {
                    if (response.authResponse) {
                        var newUser = getUserInfo(function(user) {
                            wpFBLogin(user);
                        });
                    } else {
                        message = showSuccessMessage(services_vars.fb_signin_error);

                        if (isModal) {
                            $('.pxp-signin-modal-message').empty().append(message).fadeIn('slow');
                            $('.pxp-fb-signin-btn-text').text(services_vars.fb_signin_btn_text);
                        } else {
                            $('.pxp-signin-page-message').empty().append(message).fadeIn('slow');
                        }
                    }
                }, {
                    scope: 'public_profile, email'
                });
            }
        });
    }

    function getUserInfo(callback) {
        FB.api('/me', {fields: 'last_name, first_name, email'}, function(response) {
            callback(response);
        });
    }

    function wpFBLogin(user) {
        var message;
        var securityElem = $('#pxp-signin-modal-security');
        var isModal = true;

        if ($('.pxp-signin-page').length > 0) {
            $('.pxp-signin-page-btn').hide();
            securityElem = $('#pxp-signin-page-security');
            isModal = false;
        } else {
            $('.pxp-signin-modal-btn').hide();
        }

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: services_vars.ajaxurl,
            data: {
                'action'    : 'jobster_facebook_auth',
                'uid'       : user.id,
                'first_name': user.first_name,
                'last_name' : user.last_name,
                'email'     : user.email,
                'security'  : securityElem.val()
            },
            success: function(data) {
                if (isModal) {
                    $('.pxp-signin-modal-btn').show();
                } else {
                    $('.pxp-signin-page-btn').show();
                }

                if (data.signedin === true) {
                    message = showSuccessMessage(data.message);

                    if (isModal) {
                        $('.pxp-signin-modal-message').empty().append(message).fadeIn('slow');
                    } else {
                        $('.pxp-signin-page-message').empty().append(message).fadeIn('slow');
                    }
                    if (data.redirect == 'default') {
                        if (isModal) {
                            document.location.href = $('#pxp-signin-modal-redirect').val();
                        } else {
                            document.location.href = $('#pxp-signin-page-redirect').val();
                        }
                    } else {
                        document.location.href = data.redirect;
                    }
                } else {
                    message = showErrorMessage(data.message);

                    if (isModal) {
                        $('.pxp-signin-modal-message').empty().append(message).fadeIn('slow');
                        $('.pxp-fb-signin-btn-text').text(services_vars.fb_signin_btn_text);
                    } else {
                        $('.pxp-signin-page-message').empty().append(message).fadeIn('slow');
                    }
                }
            },
            error: function(errorThrown) {}
        });
    }
})(jQuery);