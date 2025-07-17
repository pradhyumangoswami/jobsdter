(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var Button        = wp.components.Button;
    var SelectControl = wp.components.SelectControl;

    var el = wp.element.createElement;

    var MediaUpload = wp.blockEditor.MediaUpload;

    var withState = wp.compose.withState;

    var __ = wp.i18n.__;

    function getObjectProperty(obj, prop) {
        var prop = typeof prop !== 'undefined' ? prop : '';
        var obj = typeof obj !== 'undefined' ? obj : '';

        if (!prop || !obj) {
            return '';
        }

        var ret =   obj.hasOwnProperty(prop) 
                    ? (String(obj[prop]) !== '' ? obj[prop] : '')
                    : '';

        return ret;
    }

    function SubscribeControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title     = getObjectProperty(data_json, 'title');
        var subtitle  = getObjectProperty(data_json, 'subtitle');
        var image     = getObjectProperty(data_json, 'image');
        var image_src = getObjectProperty(data_json, 'image_src');
        var animation = getObjectProperty(data_json, 'animation');

        var subscribeOptions = [
            el('div', 
                {
                    className: 'pxp-form-row'
                },
                el('div', 
                    {
                        className: 'pxp-form-col'
                    },
                    el(TextControl, 
                        {
                            label: __('Title', 'jobster'),
                            value: title,
                            placeholder: __('Enter title', 'jobster'),
                            onChange: function(value) {
                                data_json.title = value;
                                setAttributes({
                                    data_content: encodeURIComponent(
                                        JSON.stringify(data_json)
                                    )
                                });
                            }
                        }
                    )
                ),
                el('div', 
                    {
                        className: 'pxp-form-col'
                    },
                    el(TextControl, 
                        {
                            label: __('Subtitle', 'jobster'),
                            value: subtitle,
                            placeholder: __('Enter subtitle', 'jobster'),
                            onChange: function(value) {
                                data_json.subtitle = value;
                                setAttributes({
                                    data_content: encodeURIComponent(
                                        JSON.stringify(data_json)
                                    )
                                });
                            }
                        }
                    )
                ),
                el('div', 
                    {
                        className: 'pxp-form-col'
                    },
                    el(MediaUpload,
                        {
                            onSelect: function(media) {
                                jQuery('.pxp-block-subscribe-bg-image-btn')
                                    .css('background-image', 'url(' + media.url + ')')
                                    .text('')
                                    .attr({
                                        'data-src': media.url,
                                        'data-id': media.id
                                    });
                                data_json.image_src = media.url;
                                data_json.image = media.id;
                                setAttributes({
                                    data_content: encodeURIComponent(
                                        JSON.stringify(data_json)
                                    )
                                });
                            },
                            type: 'image',
                            render: function(obj) {
                                return el(Button,
                                    {
                                        className: 'pxp-block-subscribe-bg-image-btn',
                                        'data-src': image_src,
                                        'data-id': image,
                                        style: {
                                            backgroundImage: 'url(' + image_src + ')',
                                        },
                                        onClick: obj.open
                                    },
                                    __('Image', 'jobster')
                                );
                            }
                        }
                    )
                ),
                el('div', 
                    {
                        className: 'pxp-form-col'
                    },
                    el(SelectControl, 
                        {
                            label: __('Reveal Animation', 'jobster'),
                            value: animation,
                            options: [
                                { label: __('Enabled', 'jobster'), value: 'e' },
                                { label: __('Disabled', 'jobster'), value: 'd' }
                            ],
                            onChange: function(value) {
                                data_json.animation = value;
                                setAttributes({
                                    data_content: encodeURIComponent(
                                        JSON.stringify(data_json)
                                    )
                                });
                            }
                        }
                    )
                )
            )
        ];

        if (isSelected) {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'subscribe-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'subscribe-placeholder-subheader'
                    },
                    subtitle
                ),
                subscribeOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'subscribe-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'subscribe-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'subscribe-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/subscribe', {
        title: __('Subscribe', 'jobster'),
        description: __('Jobster subscribe block.', 'jobster'),
        icon: {
            src: 'groups',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('subscribe', 'jobster'),
            __('newsletter', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22image%22%3A%22%22%2C%22image_src%22%3A%22%22%2C%22animation%22%3A%22e%22%7D'
            }
        },
        edit: withState({})(SubscribeControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);