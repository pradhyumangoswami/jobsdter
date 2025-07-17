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

    function ContactFormControl(props) {
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
        var email     = getObjectProperty(data_json, 'email');
        var animation = getObjectProperty(data_json, 'animation');

        var contactFormOptions = [
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
                        className: 'contact-form-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'contact-form-placeholder-subheader'
                    },
                    subtitle
                ),
                contactFormOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'contact-form-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'contact-form-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'contact-form-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/contact-form', {
        title: __('Contact Form', 'jobster'),
        description: __('Jobster contact form block.', 'jobster'),
        icon: {
            src: 'feedback',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('contact', 'jobster'),
            __('form', 'jobster'),
            __('email', 'jobster'),
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22email%22%3A%22%22%2C%22animation%22%3A%22e%22%7D'
            }
        },
        edit: withState({})(ContactFormControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);