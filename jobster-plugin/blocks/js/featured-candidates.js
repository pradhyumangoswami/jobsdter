(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var ColorPalette  = wp.components.ColorPalette;

    var el = wp.element.createElement;

    var withState = wp.compose.withState;

    var __ = wp.i18n.__;

    var locations = [{
        'label' : __('All', 'jobster'),
        'value' : '0'
    }];
    var industries = [{
        'label' : __('All', 'jobster'),
        'value' : '0'
    }];

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

    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        data: {
            'action': 'jobster_get_candidate_locations_industries'
        },
        success: function(data) {
            if (data.getli === true) {
                for (var i = 0; i < data.locations.length; i++) {
                    locations.push({ 
                        'label' : data.locations[i].name,
                        'value' : data.locations[i].term_id
                    });
                }
                for (var i = 0; i < data.industries.length; i++) {
                    industries.push({
                        'label' : data.industries[i].name,
                        'value' : data.industries[i].term_id
                    });
                }
            }
        },
        error: function(errorThrown) {}
    });

    function FeaturedCandidatesControl(props) {
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
        var design    = getObjectProperty(data_json, 'design');
        var bg        = getObjectProperty(data_json, 'bg');
        var location  = getObjectProperty(data_json, 'location');
        var industry  = getObjectProperty(data_json, 'industry');
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

        var featuredCandidatesOptions = [
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
                    el(SelectControl, 
                        {
                            label: __('Location', 'jobster'),
                            value: location,
                            options: locations,
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
                    el(SelectControl, 
                        {
                            label: __('Industry', 'jobster'),
                            value: industry,
                            options: industries,
                            onChange: function(value) {
                                data_json.industry = value;
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
                            label: __('Number of Candidates', 'jobster'),
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
                        className: 'featured-candidates-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'featured-candidates-placeholder-subheader'
                    },
                    subtitle
                ),
                featuredCandidatesOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'featured-candidates-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'featured-candidates-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'featured-candidates-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/featured-candidates', {
        title: __('Featured Candidates', 'jobster'),
        description: __('Jobster featured candidates block.', 'jobster'),
        icon: {
            src: 'businessman',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('candidates', 'jobster'),
            __('featured', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22design%22%3A%22s%22%2C%22bg%22%3A%22%22%2C%22location%22%3A%220%22%2C%22industry%22%3A%220%22%2C%22number%22%3A%226%22%2C%22align%22%3A%22s%22%2C%22animation%22%3A%22e%22%2C%22margin%22%3A%22y%22%7D'
            }
        },
        edit: withState({})(FeaturedCandidatesControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);