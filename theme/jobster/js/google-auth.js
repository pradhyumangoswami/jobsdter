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

function decodeJwtResponse(token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');

    var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}

function handleGoogleAuthResponse(response) {
    const responsePayload = decodeJwtResponse(response.credential);

    (function($) {
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
                'action'    : 'jobster_google_auth',
                'uid'       : responsePayload.sub,
                'name'      : responsePayload.name,
                'first_name': responsePayload.given_name,
                'last_name' : responsePayload.family_name,
                'avatar'    : responsePayload.picture,
                'email'     : responsePayload.email,
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
                    } else {
                        $('.pxp-signin-page-message').empty().append(message).fadeIn('slow');
                    }
                }
            },
            error: function(errorThrown) {}
        });
    })(jQuery);
}