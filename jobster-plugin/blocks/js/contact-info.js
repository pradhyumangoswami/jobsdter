(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;

    var el = wp.element.createElement;

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

    function ContactInfoControl(props) {
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
        var location  = getObjectProperty(data_json, 'location');
        var phone     = getObjectProperty(data_json, 'phone');
        var email     = getObjectProperty(data_json, 'email');
        var animation = getObjectProperty(data_json, 'animation');

        var contactInfoOptions = [
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
                    el(TextControl, 
                        {
                            label: __('Location', 'jobster'),
                            value: location,
                            placeholder: __('Enter location/city', 'jobster'),
                            onChange: function(value) {
                                data_json.location = value;
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
                            label: __('Phone', 'jobster'),
                            value: phone,
                            placeholder: __('Enter phone', 'jobster'),
                            onChange: function(value) {
                                data_json.phone = value;
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
                            label: __('Email', 'jobster'),
                            value: email,
                            placeholder: __('Enter email', 'jobster'),
                            onChange: function(value) {
                                data_json.email = value;
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
                        className: 'contact-info-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'contact-info-placeholder-subheader'
                    },
                    subtitle
                ),
                contactInfoOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'contact-info-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'contact-info-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'contact-info-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/contact-info', {
        title: __('Contact Info', 'jobster'),
        description: __('Jobster contact info block.', 'jobster'),
        icon: {
            src: 'admin-site',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('contact', 'jobster'),
            __('phone', 'jobster'),
            __('email', 'jobster'),
            __('info', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22location%22%3A%22%22%2C%22phone%22%3A%22%22%2C%22email%22%3A%22%22%2C%22animation%22%3A%22e%22%7D'
            }
        },
        edit: withState({})(ContactInfoControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);