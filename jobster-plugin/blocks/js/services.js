(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var Button        = wp.components.Button;
    var ColorPalette  = wp.components.ColorPalette;

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

    function ServicesControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var newImageSrc   = props.newImageSrc;
        var newImageId    = props.newImageId;
        var newTitle      = props.newTitle;
        var newText       = props.newText;
        var newLink       = props.newLink;
        var newLabel      = props.newLabel;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title      = getObjectProperty(data_json, 'title');
        var subtitle   = getObjectProperty(data_json, 'subtitle');
        var color      = getObjectProperty(data_json, 'color');
        var align      = getObjectProperty(data_json, 'align');
        var animation  = getObjectProperty(data_json, 'animation');
        var hanimation = getObjectProperty(data_json, 'hanimation');
        var services   = getObjectProperty(data_json, 'services');

        if (!jQuery.isArray(services)) {
            services = [];
        }

        var onNewImageSelect = function(media) {
            setState({
                newImageSrc: media.url,
                newImageId: media.id
            });
        };

        var renderServicesList = function() {
            var items = [];

            if (services.length > 0) {
                jQuery.each(services, function(index, elem) {
                    items.push(
                        el('li',
                            {},
                            el('div',
                                {
                                    className: 'pxp-form-row'
                                },
                                el('div',
                                    {
                                        className: 'pxp-form-col-2'
                                    },
                                    el('div',
                                        {
                                            className: 'services-list-item-img',
                                            style: {
                                                'background-image': 'url(' + elem.src + ')'
                                            },
                                        }
                                    )
                                ),
                                el('div',
                                    {
                                        className: 'pxp-form-col-8'
                                    },
                                    el('div',
                                        {
                                            className: 'services-list-item-title'
                                        },
                                        elem.title
                                    ),
                                    el('div',
                                        {
                                            className: 'services-list-item-text'
                                        },
                                        elem.text
                                    )
                                ),
                                el('div',
                                    {
                                        className: 'pxp-form-col-2'
                                    },
                                    el('a', 
                                        {
                                            onClick: function(event) {
                                                var target = jQuery(event.target);
                                                var elemIndex = target.parent().parent().parent().index();

                                                data_json.services.splice(elemIndex, 1);

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

        var renderCardColorSelector = el('div',
            {
                className: 'components-base-control'
            },
            el('div',
                {
                    className: 'components-base-control__field'
                },
                el('fieldset',
                    {},
                    el('legend',
                        {},
                        el('div',
                            {},
                            el('span',
                                {
                                    className: 'components-base-control__label'
                                },
                                __('Card Color', 'jobster'),
                            )
                        )
                    ),
                    el(ColorPalette,
                        {
                            value: color,
                            colors: [
                                { name: 'Red', color: '#FFF1F0' },
                                { name: 'Volcano', color: '#FFF2E7' },
                                { name: 'Orange', color: '#FFF7E5' },
                                { name: 'Gold', color: '#FFFBE5' },
                                { name: 'Yellow', color: '#FEFEE5' },
                                { name: 'Lime', color: '#FCFEE5' },
                                { name: 'Green', color: '#F6FEEC' },
                                { name: 'Cyan', color: '#E5FFFB' },
                                { name: 'Blue', color: '#E5F7FE' },
                                { name: 'Geek Blue', color: '#F0F5FE' },
                                { name: 'Purple', color: '#F9F0FE' },
                                { name: 'Magenta', color: '#FEF0F6' }
                            ],
                            onChange: function(value) {
                                data_json.color = value;
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
        );

        var servicesOptions = [
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
                )
            ),
            renderCardColorSelector,
            el('div', 
                {
                    className: 'pxp-form-row'
                },
                el('div', 
                    {
                        className: 'pxp-form-col'
                    },
                    el(SelectControl, 
                        {
                            label: __('Align', 'jobster'),
                            value: align,
                            options: [
                                { label: __('Start', 'jobster'), value: 's' },
                                { label: __('Center', 'jobster'), value: 'c' }
                            ],
                            onChange: function(value) {
                                data_json.align = value;
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
                ),
                el('div', 
                    {
                        className: 'pxp-form-col'
                    },
                    el(SelectControl, 
                        {
                            label: __('Card Hover Animation', 'jobster'),
                            value: hanimation,
                            options: [
                                { label: __('Enabled', 'jobster'), value: 'e' },
                                { label: __('Disabled', 'jobster'), value: 'd' }
                            ],
                            onChange: function(value) {
                                data_json.hanimation = value;
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
                    className: 'services-list-header'
                },
                __('Services List', 'jobster')
            ),
            el('div',
                {
                    className: 'services-list-container'
                },
                el('ul',
                    {},
                    renderServicesList()
                )
            ),
            el(Button,
                {
                    className: 'services-list-new-btn',
                    isSecondary: true,
                    isLarge: true,
                    onClick: function(event) {
                        jQuery(event.target).hide();
                        jQuery('.services-list-new-form').show();
                    }
                },
                __('Add New Service', 'jobster')
            ),
            el('div',
                {
                    className: 'services-list-new-form'
                },
                el('h5', 
                    {
                        className: 'services-list-new-header'
                    },
                    __('New Service', 'jobster')
                ),
                el('div',
                    {
                        className: 'pxp-form-row'
                    },
                    el('div',
                        {
                            className: 'pxp-form-col-4'
                        },
                        el(MediaUpload,
                            {
                                onSelect: function(media) {
                                    onNewImageSelect(media);
                                },
                                type: 'image',
                                render: function(obj) {
                                    return el(Button,
                                        {
                                            className: 'services-list-new-bg-image-btn',
                                            style: {
                                                backgroundImage: newImageSrc != '' ? 'url(' + newImageSrc + ')' : 'none'
                                            },
                                            onClick: obj.open
                                        },
                                        __('Card Image', 'jobster')
                                    );
                                }
                            }
                        )
                    ),
                    el('div',
                        {
                            className: 'pxp-form-col-8'
                        },
                        el(TextControl, 
                            {
                                className: 'services-list-new-title',
                                label: __('Service Title', 'jobster'),
                                value: newTitle,
                                placeholder: __('Enter service title', 'jobster'),
                                onChange: function(newTitle) {
                                    setState({ newTitle });
                                }
                            }
                        ),
                        el(TextControl, 
                            {
                                className: 'services-list-new-text',
                                label: __('Service Text', 'jobster'),
                                value: newText,
                                placeholder: __('Enter service text', 'jobster'),
                                onChange: function(newText) {
                                    setState({ newText });
                                }
                            }
                        ),
                        el(TextControl, 
                            {
                                className: 'services-list-new-link',
                                label: __('Service Link', 'jobster'),
                                value: newLink,
                                placeholder: __('Enter service link', 'jobster'),
                                onChange: function(newLink) {
                                    setState({ newLink });
                                }
                            }
                        ),
                        el(TextControl, 
                            {
                                className: 'services-list-new-cta',
                                label: __('Service CTA Label', 'jobster'),
                                value: newLabel,
                                placeholder: __('Enter service CTA label', 'jobster'),
                                onChange: function(newLabel) {
                                    setState({ newLabel });
                                }
                            }
                        )
                    )
                ),
                el(Button,
                    {
                        isPrimary: true,
                        isLarge: true,
                        className: 'services-list-new-ok',
                        onClick: function() {
                            services.push({
                                'src'  : newImageSrc,
                                'value': newImageId,
                                'title': newTitle,
                                'text' : newText,
                                'link' : newLink,
                                'cta'  : newLabel
                            });

                            data_json.services = services;
                            setAttributes({
                                data_content: encodeURIComponent(
                                    JSON.stringify(data_json)
                                )
                            });

                            setState({ 
                                newImageSrc: '',
                                newImageId : '',
                                newTitle   : '',
                                newText    : '',
                                newLink    : '',
                                newLabel   : ''
                            });

                            jQuery('.services-list-new-form').hide();
                            jQuery('.services-list-new-btn').show();
                        }
                    },
                    __('Add Service', 'jobster')
                ),
                el(Button,
                    {
                        isSecondary: true,
                        isLarge: true,
                        className: 'services-list-new-cancel',
                        onClick: function() {
                            setState({ 
                                newImageSrc: '',
                                newImageId : '',
                                newTitle   : '',
                                newText    : '',
                                newLink    : '',
                                newLabel   : ''
                            });

                            jQuery('.services-list-new-form').hide();
                            jQuery('.services-list-new-btn').show();
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
                        className: 'services-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'services-placeholder-subheader'
                    },
                    subtitle
                ),
                servicesOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'services-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'services-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'services-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/services', {
        title: __('Services', 'jobster'),
        description: __('Jobster services block.', 'jobster'),
        icon: {
            src: 'superhero',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('services', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22color%22%3A%22%22%2C%22align%22%3A%22s%22%2C%22animation%22%3A%22e%22%2C%22hanimation%22%3A%22e%22%2C%22services%22%3A%5B%5D%7D'
            }
        },
        edit: withState({
            newImageSrc: '',
            newImageId : '',
            newTitle   : '',
            newText    : '',
            newLink    : '',
            newLabel   : ''
        })(ServicesControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);