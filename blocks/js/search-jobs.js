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

    function SearchJobsControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title     = getObjectProperty(data_json, 'title');
        var text      = getObjectProperty(data_json, 'text');
        var image     = getObjectProperty(data_json, 'image');
        var image_src = getObjectProperty(data_json, 'image_src');
        var type      = getObjectProperty(data_json, 'type');
        var animation = getObjectProperty(data_json, 'animation');
        var system    = getObjectProperty(data_json, 'system');
        var margin    = getObjectProperty(data_json, 'margin');

        var searchJobsOptions = [
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
                                jQuery('.pxp-block-search-jobs-bg-image-btn')
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
                                        className: 'pxp-block-search-jobs-bg-image-btn',
                                        'data-src': image_src,
                                        'data-id': image,
                                        style: {
                                            backgroundImage: 'url(' + image_src + ')',
                                        },
                                        onClick: obj.open
                                    },
                                    __('Background Image', 'jobster')
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
                            label: __('Caption Type', 'jobster'),
                            value: type,
                            options: [
                                { label: __('Light', 'jobster'), value: 'l' },
                                { label: __('Dark', 'jobster'), value: 'd' }
                            ],
                            onChange: function(value) {
                                data_json.type = value;
                                setAttributes({
                                    data_content: encodeURIComponent(
                                        JSON.stringify(data_json)
                                    )
                                });
                            }
                        }
                    ),
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
                    ),
                    el(SelectControl, 
                        {
                            label: __('Search System', 'jobster'),
                            value: system,
                            options: [
                                { label: __('Default', 'jobster'), value: 'd' },
                                { label: __('Careerjet', 'jobster'), value: 'c' }
                            ],
                            onChange: function(value) {
                                data_json.system = value;
                                setAttributes({
                                    data_content: encodeURIComponent(
                                        JSON.stringify(data_json)
                                    )
                                });
                            }
                        }
                    ),
                    el(SelectControl, 
                        {
                            label: __('Margin', 'jobster'),
                            value: margin,
                            options: [
                                { label: __('Yes', 'jobster'), value: 'y' },
                                { label: __('No', 'jobster'), value: 'n' }
                            ],
                            onChange: function(value) {
                                data_json.margin = value;
                                setAttributes({
                                    data_content: encodeURIComponent(
                                        JSON.stringify(data_json)
                                    )
                                });
                            }
                        }
                    )
                ),
            )
        ];

        if (isSelected) {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'search-jobs-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'search-jobs-placeholder-subheader'
                    },
                    text
                ),
                searchJobsOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'search-jobs-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'search-jobs-placeholder-subheader'
                    },
                    text
                ),
                el('div', 
                    {
                        className: 'search-jobs-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/search-jobs', {
        title: __('Search Jobs', 'jobster'),
        description: __('Jobster search jobs block.', 'jobster'),
        icon: {
            src: 'search',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('search', 'jobster'),
            __('jobs', 'jobster'),
            __('find', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22text%22%3A%22%22%2C%22image%22%3A%22%22%2C%22image_src%22%3A%22%22%2C%22type%22%3A%22l%22%2C%22animation%22%3A%22e%22%2C%22system%22%3A%22d%22%2C%22margin%22%3A%22y%22%7D'
            }
        },
        edit: withState({})(SearchJobsControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);