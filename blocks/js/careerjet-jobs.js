(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var ColorPalette  = wp.components.ColorPalette;

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

    function CareerjetJobsControl(props) {
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
        var keywords  = getObjectProperty(data_json, 'keywords');
        var design    = getObjectProperty(data_json, 'design');
        var type      = getObjectProperty(data_json, 'type');
        var bg        = getObjectProperty(data_json, 'bg');
        var location  = getObjectProperty(data_json, 'location');
        var number    = getObjectProperty(data_json, 'number');
        var align     = getObjectProperty(data_json, 'align');
        var animation = getObjectProperty(data_json, 'animation');
        var margin    = getObjectProperty(data_json, 'margin');

        var renderBgColorSelector = el('div',
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
                                __('Background Color', 'jobster'),
                            )
                        )
                    ),
                    el(ColorPalette,
                        {
                            value: bg,
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
                                data_json.bg = value;
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

        var careerjetJobsOptions = [
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
                            label: __('Card Type', 'jobster'),
                            value: type,
                            options: [
                                { label: __('Big', 'jobster'), value: 'b' },
                                { label: __('Small', 'jobster'), value: 's' },
                                { label: __('List', 'jobster'), value: 'l' }
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
                    )
                ),
                el('div', 
                    {
                        className: 'pxp-form-col'
                    },
                    el(SelectControl, 
                        {
                            label: __('Card Design', 'jobster'),
                            value: design,
                            options: [
                                { label: __('Shadow', 'jobster'), value: 's' },
                                { label: __('Border', 'jobster'), value: 'b' }
                            ],
                            onChange: function(value) {
                                data_json.design = value;
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
            renderBgColorSelector,
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
                            label: __('Keyword(s)', 'jobster'),
                            value: keywords,
                            placeholder: __('Enter keywords', 'jobster'),
                            onChange: function(value) {
                                data_json.keywords = value;
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
                            placeholder: __('Enter location', 'jobster'),
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
                            label: __('Number of Jobs', 'jobster'),
                            value: number,
                            placeholder: __('Enter number', 'jobster'),
                            onChange: function(value) {
                                data_json.number = value;
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
                ),
                el('div', 
                    {
                        className: 'pxp-form-col',
                        style: {
                            display: (bg != '') ? 'block' : 'none'
                        }
                    },
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
                        className: 'careerjet-jobs-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'careerjet-jobs-placeholder-subheader'
                    },
                    subtitle
                ),
                careerjetJobsOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'careerjet-jobs-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'careerjet-jobs-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'careerjet-jobs-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/careerjet-jobs', {
        title: __('Careerjet Jobs', 'jobster'),
        description: __('Careerjet jobs block.', 'jobster'),
        icon: {
            src: 'portfolio',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('jobs', 'jobster'),
            __('careerjet', 'jobster'),
            __('offers', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22type%22%3A%22b%22%2C%22design%22%3A%22s%22%2C%22bg%22%3A%22%22%2C%22keywords%22%3A%22%22%2C%22location%22%3A%22%22%2C%22number%22%3A%226%22%2C%22align%22%3A%22s%22%2C%22animation%22%3A%22e%22%2C%22margin%22%3A%22y%22%7D'
            }
        },
        edit: withState({})(CareerjetJobsControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);