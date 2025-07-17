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

    function FeaturedArticlesControl(props) {
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
        var align     = getObjectProperty(data_json, 'align');
        var animation = getObjectProperty(data_json, 'animation');

        var featuredArticlesOptions = [
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
                        className: 'featured-articles-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'featured-articles-placeholder-subheader'
                    },
                    subtitle
                ),
                featuredArticlesOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'featured-articles-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'featured-articles-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'featured-articles-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/featured-articles', {
        title: __('Featured Articles', 'jobster'),
        description: __('Jobster featured articles block.', 'jobster'),
        icon: {
            src: 'excerpt-view',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('featured', 'jobster'),
            __('posts', 'jobster'),
            __('articles', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22align%22%3A%22s%22%2C%22animation%22%3A%22e%22%7D'
            }
        },
        edit: withState({})(FeaturedArticlesControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);