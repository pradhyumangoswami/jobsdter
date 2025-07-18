(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var Button        = wp.components.Button;

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

    function FeaturesControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var newText       = props.newText;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title         = getObjectProperty(data_json, 'title');
        var text          = getObjectProperty(data_json, 'text');
        var image         = getObjectProperty(data_json, 'image');
        var image_src     = getObjectProperty(data_json, 'image_src');
        var icard_no_1    = getObjectProperty(data_json, 'icard_no_1');
        var icard_label_1 = getObjectProperty(data_json, 'icard_label_1');
        var icard_text_1  = getObjectProperty(data_json, 'icard_text_1');
        var icard_no_2    = getObjectProperty(data_json, 'icard_no_2');
        var icard_label_2 = getObjectProperty(data_json, 'icard_label_2');
        var icard_text_2  = getObjectProperty(data_json, 'icard_text_2');
        var icard_no_3    = getObjectProperty(data_json, 'icard_no_3');
        var icard_label_3 = getObjectProperty(data_json, 'icard_label_3');
        var icard_text_3  = getObjectProperty(data_json, 'icard_text_3');
        var link          = getObjectProperty(data_json, 'link');
        var cta           = getObjectProperty(data_json, 'cta');
        var animation     = getObjectProperty(data_json, 'animation');
        var features      = getObjectProperty(data_json, 'features');

        if (!jQuery.isArray(features)) {
            features = [];
        }

        var renderFeaturesList = function() {
            var items = [];

            if (features.length > 0) {
                jQuery.each(features, function(index, elem) {
                    items.push(
                        el('li',
                            {},
                            el('div',
                                {
                                    className: 'pxp-form-row'
                                },
                                el('div',
                                    {
                                        className: 'pxp-form-col-8'
                                    },
                                    el('div',
                                        {
                                            className: 'features-list-item-text'
                                        },
                                        elem.text
                                    )
                                ),
                                el('div',
                                    {
                                        className: 'pxp-form-col-4'
                                    },
                                    el('a', 
                                        {
                                            onClick: function(event) {
                                                var target = jQuery(event.target);
                                                var elemIndex = target.parent().parent().parent().index();

                                                data_json.features.splice(elemIndex, 1);

                                                setAttributes({
                                                    data_content: encodeURIComponent(
                                                        JSON.stringify(data_json)
                                                    )
                                                });
                                            }
                                        },
                                        __('Delete', 'jobster')
                                    )
                                )
                            )
                        )
                    );
                });
            }

            return items;
        };

        var featuresOptions = [
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
                            label: __('Text', 'jobster'),
                            value: text,
                            placeholder: __('Enter text', 'jobster'),
                            onChange: function(value) {
                                data_json.text = value;
                                setAttributes({
                                    data_content: encodeURIComponent(
                                        JSON.stringify(data_json)
                                    )
                                });
                            }
                        }
                    )
                )
            ),
            el('div', 
                {
                    className: 'pxp-form-row'
                },
                el('div', 
                    {
                        className: 'pxp-form-col'
                    },
                    el(MediaUpload,
                        {
                            onSelect: function(media) {
                                jQuery('.pxp-block-features-bg-image-btn')
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
                                        className: 'pxp-block-features-bg-image-btn',
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
                )
            ),
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
                            label: __('CTA Link', 'jobster'),
                            value: link,
                            placeholder: __('Enter CTA link', 'jobster'),
                            onChange: function(value) {
                                data_json.link = value;
                                setAttributes({
                                    data_content: encodeURIComponent(
                                        JSON.stringify(data_json)
                                    )
                                });
                            }
                        }
                    ),
                ),
                el('div', 
                    {
                        className: 'pxp-form-col'
                    },
                    el(TextControl, 
                        {
                            label: __('CTA Label', 'jobster'),
                            value: cta,
                            placeholder: __('Enter CTA label', 'jobster'),
                            onChange: function(value) {
                                data_json.cta = value;
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
            ),
            el('h4', 
                {
                    className: 'features-list-header'
                },
                __('Info Cards', 'jobster')
            ),
            el('div', 
                {
                    className: 'pxp-form-row'
                },
                el('div', 
                    {
                        className: 'pxp-form-col-4'
                    },
                    el(TextControl,
                        {
                            label: __('Card 1 Number', 'jobster'),
                            value: icard_no_1,
                            placeholder: __('Enter number', 'jobster'),
                            onChange: function(value) {
                                data_json.icard_no_1 = value;
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
                        className: 'pxp-form-col-4'
                    },
                    el(TextControl,
                        {
                            label: __('Card 1 Label', 'jobster'),
                            value: icard_label_1,
                            placeholder: __('Enter label', 'jobster'),
                            onChange: function(value) {
                                data_json.icard_label_1 = value;
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
                        className: 'pxp-form-col-4'
                    },
                    el(TextControl,
                        {
                            label: __('Card 1 Text', 'jobster'),
                            value: icard_text_1,
                            placeholder: __('Enter text', 'jobster'),
                            onChange: function(value) {
                                data_json.icard_text_1 = value;
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
                        className: 'pxp-form-col-4'
                    },
                    el(TextControl,
                        {
                            label: __('Card 2 Number', 'jobster'),
                            value: icard_no_2,
                            placeholder: __('Enter number', 'jobster'),
                            onChange: function(value) {
                                data_json.icard_no_2 = value;
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
                        className: 'pxp-form-col-4'
                    },
                    el(TextControl,
                        {
                            label: __('Card 2 Label', 'jobster'),
                            value: icard_label_2,
                            placeholder: __('Enter label', 'jobster'),
                            onChange: function(value) {
                                data_json.icard_label_2 = value;
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
                        className: 'pxp-form-col-4'
                    },
                    el(TextControl,
                        {
                            label: __('Card 2 Text', 'jobster'),
                            value: icard_text_2,
                            placeholder: __('Enter text', 'jobster'),
                            onChange: function(value) {
                                data_json.icard_text_2 = value;
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
                        className: 'pxp-form-col-4'
                    },
                    el(TextControl,
                        {
                            label: __('Card 3 Number', 'jobster'),
                            value: icard_no_3,
                            placeholder: __('Enter number', 'jobster'),
                            onChange: function(value) {
                                data_json.icard_no_3 = value;
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
                        className: 'pxp-form-col-4'
                    },
                    el(TextControl,
                        {
                            label: __('Card 3 Label', 'jobster'),
                            value: icard_label_3,
                            placeholder: __('Enter label', 'jobster'),
                            onChange: function(value) {
                                data_json.icard_label_3 = value;
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
                        className: 'pxp-form-col-4'
                    },
                    el(TextControl,
                        {
                            label: __('Card 3 Text', 'jobster'),
                            value: icard_text_3,
                            placeholder: __('Enter text', 'jobster'),
                            onChange: function(value) {
                                data_json.icard_text_3 = value;
                                setAttributes({
                                    data_content: encodeURIComponent(
                                        JSON.stringify(data_json)
                                    )
                                });
                            }
                        }
                    )
                )
            ),
            el('h4', 
                {
                    className: 'features-list-header'
                },
                __('Features List', 'jobster')
            ),
            el('div',
                {
                    className: 'features-list-container'
                },
                el('ul',
                    {},
                    renderFeaturesList()
                )
            ),
            el(Button,
                {
                    className: 'features-list-new-btn',
                    isSecondary: true,
                    isLarge: true,
                    onClick: function(event) {
                        jQuery(event.target).hide();
                        jQuery('.features-list-new-form').show();
                    }
                },
                __('Add New Feature', 'jobster')
            ),
            el('div',
                {
                    className: 'features-list-new-form'
                },
                el('h5', 
                    {
                        className: 'features-list-new-header'
                    },
                    __('New Feature', 'jobster')
                ),
                el(TextControl, 
                    {
                        className: 'features-list-new-text',
                        label: __('Feature Text', 'jobster'),
                        value: newText,
                        placeholder: __('Enter feature text', 'jobster'),
                        onChange: function(newText) {
                            setState({ newText });
                        }
                    }
                ),
                el(Button,
                    {
                        isPrimary: true,
                        isLarge: true,
                        className: 'features-list-new-ok',
                        onClick: function() {
                            features.push({
                                'text': newText
                            });

                            data_json.features = features;
                            setAttributes({
                                data_content: encodeURIComponent(
                                    JSON.stringify(data_json)
                                )
                            });

                            setState({ 
                                newText: ''
                            });

                            jQuery('.features-list-new-form').hide();
                            jQuery('.features-list-new-btn').show();
                        }
                    },
                    __('Add Feature', 'jobster')
                ),
                el(Button,
                    {
                        isSecondary: true,
                        isLarge: true,
                        className: 'features-list-new-cancel',
                        onClick: function() {
                            setState({ 
                                newText: '',
                            });

                            jQuery('.features-list-new-form').hide();
                            jQuery('.features-list-new-btn').show();
                        }
                    },
                    __('Cancel', 'jobster')
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
                        className: 'features-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'features-placeholder-subheader'
                    },
                    text
                ),
                featuresOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'features-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'features-placeholder-subheader'
                    },
                    text
                ),
                el('div', 
                    {
                        className: 'features-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/features', {
        title: __('Features', 'jobster'),
        description: __('Jobster features block.', 'jobster'),
        icon: {
            src: 'lightbulb',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('features', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22text%22%3A%22%22%2C%22image%22%3A%22%22%2C%22image_src%22%3A%22%22%2C%22icard_no_1%22%3A%22%22%2C%22icard_label_1%22%3A%22%22%2C%22icard_text_1%22%3A%22%22%2C%22icard_no_2%22%3A%22%22%2C%22icard_label_2%22%3A%22%22%2C%22icard_text_2%22%3A%22%22%2C%22icard_no_3%22%3A%22%22%2C%22icard_label_3%22%3A%22%22%2C%22icard_text_3%22%3A%22%22%2C%22link%22%3A%22%22%2C%22cta%22%3A%22%22%2C%22animation%22%3A%22e%22%2C%22features%22%3A%5B%5D%7D'
            }
        },
        edit: withState({
            newText: ''
        })(FeaturesControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);