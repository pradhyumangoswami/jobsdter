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

    function JobCategoriesControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title      = getObjectProperty(data_json, 'title');
        var subtitle   = getObjectProperty(data_json, 'subtitle');
        var number     = getObjectProperty(data_json, 'number');
        var exclude    = getObjectProperty(data_json, 'exclude');
        var sort       = getObjectProperty(data_json, 'sort');
        var target     = getObjectProperty(data_json, 'target');
        var layout     = getObjectProperty(data_json, 'layout');
        var card       = getObjectProperty(data_json, 'card');
        var card_align = getObjectProperty(data_json, 'card_align');
        var icon       = getObjectProperty(data_json, 'icon');
        var align      = getObjectProperty(data_json, 'align');
        var animation  = getObjectProperty(data_json, 'animation');

        var jobCategoriesOptions = [
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
                            label: __('Number of Categories', 'jobster'),
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
                            label: __('Exclude Empty Categories', 'jobster'),
                            value: exclude,
                            options: [
                                { label: __('No', 'jobster'), value: 'n' },
                                { label: __('Yes', 'jobster'), value: 'y' }
                            ],
                            onChange: function(value) {
                                data_json.exclude = value;
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
                            label: __('Sort by', 'jobster'),
                            value: sort,
                            options: [
                                { label: __('Name', 'jobster'), value: 'n' },
                                { label: __('Number of jobs', 'jobster'), value: 'j' }
                            ],
                            onChange: function(value) {
                                data_json.sort = value;
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
                            label: __('CTA button target', 'jobster'),
                            value: target,
                            options: [
                                { label: __('All jobs page', 'jobster'), value: 'j' },
                                { label: __('All categories page', 'jobster'), value: 'c' },
                            ],
                            onChange: function(value) {
                                data_json.target = value;
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
                            label: __('Layout', 'jobster'),
                            value: layout,
                            options: [
                                { label: __('Grid', 'jobster'), value: 'g' },
                                { label: __('Carousel', 'jobster'), value: 'c' }
                            ],
                            onChange: function(value) {
                                data_json.layout = value;
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
                            display: (layout == 'g') ? 'block' : 'none'
                        }
                    },
                    el(SelectControl, 
                        {
                            label: __('Card Design', 'jobster'),
                            value: card,
                            options: [
                                { label: __('Vertical', 'jobster'), value: 'v' },
                                { label: __('Horizontal', 'jobster'), value: 'h' },
                                { label: __('Border', 'jobster'), value: 'b' },
                                { label: __('Transparent', 'jobster'), value: 't' }
                            ],
                            onChange: function(value) {
                                data_json.card = value;
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
                            display:    (layout == 'g' && (card == 't' || card == 'b')) 
                                        ? 'block'
                                        : 'none'
                        }
                    },
                    el(SelectControl, 
                        {
                            label: __('Card Align', 'jobster'),
                            value: card_align,
                            options: [
                                { label: __('Start', 'jobster'), value: 's' },
                                { label: __('Center', 'jobster'), value: 'c' }
                            ],
                            onChange: function(value) {
                                data_json.card_align = value;
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
                            label: __('Icon Background', 'jobster'),
                            value: icon,
                            options: [
                                { label: __('Transparent', 'jobster'), value: 't' },
                                { label: __('Opaque', 'jobster'), value: 'o' }
                            ],
                            onChange: function(value) {
                                data_json.icon = value;
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
                            display: (layout == 'g') ? 'block' : 'none'
                        }
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
                        className: 'job-categories-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'job-categories-placeholder-subheader'
                    },
                    subtitle
                ),
                jobCategoriesOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'job-categories-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'job-categories-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'job-categories-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/job-categories', {
        title: __('Job Categories', 'jobster'),
        description: __('Jobster job categories block.', 'jobster'),
        icon: {
            src: 'category',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('category', 'jobster'),
            __('categories', 'jobster'),
            __('job', 'jobster'),
            __('jobs', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22number%22%3A%226%22%2C%22exclude%22%3A%22n%22%2C%22sort%22%3A%22n%22%2C%22target%22%3A%22j%22%2C%22layout%22%3A%22g%22%2C%22card%22%3A%22v%22%2C%22card_align%22%3A%22s%22%2C%22icon%22%3A%22t%22%2C%22align%22%3A%22s%22%2C%22animation%22%3A%22e%22%7D'
            }
        },
        edit: withState({})(JobCategoriesControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);