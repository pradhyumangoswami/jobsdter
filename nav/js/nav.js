(function($) {
    "use strict";

    $('.pxp-menu-item-icontype').on('change', function() {
        var iconfa = $(this).parent().parent().parent().find('.pxp-is-menu-iconfa');
        var iconimg = $(this).parent().parent().parent().find('.pxp-is-menu-iconimg');
        if ($(this).val() == 'none') {
            iconfa.hide();
            iconimg.hide();
        }
        if ($(this).val() == 'fa') {
            iconfa.show();
            iconimg.hide();
        }
        if ($(this).val() == 'img') {
            iconfa.hide();
            iconimg.show();
        }
    });

    // Upload menu item icon
    $('.pxp-icon-image-placeholder').click(function(event) {
        event.preventDefault();
        var placeholder = $(this);

        var frame = wp.media({
            title: nav_vars.icon_title,
            button: {
                text: nav_vars.icon_btn
            },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').toJSON();
            $.each(attachment, function(index, value) {
                placeholder.parent().prev().find('input').val(value.id);
                placeholder.css('background-image', 'url(' + value.url + ')');
                placeholder.parent().addClass('pxp-has-image');
            });
        });

        frame.open();
    });

    // Delete menu item icon
    $('.pxp-delete-icon-image').click(function() {
        var delBtn = $(this);

        delBtn.parent().prev().find('input').val('');
        delBtn.parent().find('.pxp-icon-image-placeholder').css('background-image', 'url(' + nav_vars.plugin_url + 'post-types/images/icon-placeholder.png)');
        delBtn.parent().removeClass('pxp-has-image');
    });
})(jQuery);