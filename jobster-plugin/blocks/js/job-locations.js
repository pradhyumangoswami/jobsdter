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

    function JobLocationsControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var newImageSrc   = props.newImageSrc;
        var newImageId    = props.newImageId;
        var newLocation   = props.newLocation;
        var newLocationId = props.newLocationId;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title     = getObjectProperty(data_json, 'title');
        var subtitle  = getObjectProperty(data_json, 'subtitle');
        var type      = getObjectProperty(data_json, 'type');
        var bg        = getObjectProperty(data_json, 'bg');
        var align     = getObjectProperty(data_json, 'align');
        var animation = getObjectProperty(data_json, 'animation');
        var locations = getObjectProperty(data_json, 'locations');

        if (!jQuery.isArray(locations)) {
            locations = [];
        }

        var locations_list = jl_vars.locations.replace(/\+/g, '%20');
        locations_list =    locations_list != '' 
                            ? jQuery.parseJSON(
                                decodeURIComponent(locations_list)
                            )
                            : [];

        var renderLocationField = function() {
            var locationsList = [
                { label: __('All', 'jobster'), value: '0' }
            ];

            jQuery.each(locations_list, function(index, value) {
                locationsList.push({
                    label: value.name,
                    value: value.id
                });
            });

            return el(SelectControl, 
                {
                    id: 'locations-list-new-location',
                    label: __('Location', 'jobster'),
                    value: newLocationId,
                    options: locationsList,
                    onChange: function(newLocationId) {
                        var new_location = '';
                        jQuery.each(locations_list, function(index, value) {
                            if (value.id == newLocationId) {
                                new_location = value.name;
                            }
                        });
                        setState({ newLocation: new_location, newLocationId });
                    }
                }
            );
        };

        var onNewImageSelect = function(media) {
            setState({
                newImageSrc: media.url,
                newImageId: media.id,
            });
        };

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
                                __('Card Background Color', 'jobster'),
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

        var renderLocationsList = function() {
            var items = [];

            if (locations.length > 0) {
                jQuery.each(locations, function(index, elem) {
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
                                            className: 'locations-list-item-img',
                                            style: {
                                                'background-image': 'url(' + elem.src + ')'
                                            }
                                        }
                                    )
                                ),
                                el('div',
                                    {
                                        className: 'pxp-form-col-8'
                                    },
                                    el('div',
                                        {
                                            className: 'locations-list-item'
                                        },
                                        elem.location
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

                                                data_json.locations.splice(elemIndex, 1);

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

        var jobLocationsOptions = [
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
                            label: __('Card Type', 'jobster'),
                            value: type,
                            options: [
                                { label: __('Big', 'jobster'), value: 'b' },
                                { label: __('Small', 'jobster'), value: 's' }
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
            ),
            el('h4', 
                {
                    className: 'locations-list-header'
                },
                __('Locations List', 'jobster')
            ),
            el('div',
                {
                    className: 'locations-list-container'
                },
                el('ul',
                    {},
                    renderLocationsList()
                )
            ),
            el(Button,
                {
                    className : 'locations-list-add-form-btn',
                    isSecondary: true,
                    isLarge: true,
                    onClick: function(event) {
                        jQuery(event.target).hide();
                        jQuery('.locations-list-new-form').show();
                    }
                },
                __('Add New Location', 'jobster')
            ),
            el('div',
                {
                    className: 'locations-list-new-form'
                },
                el('h5', 
                    {
                        className: 'locations-list-header'
                    },
                    __('New Location', 'jobster')
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
                                            className: 'locations-list-new-upload',
                                            style: {
                                                backgroundImage: newImageSrc != '' ? 'url(' + newImageSrc + ')' : 'none',
                                            },
                                            onClick: obj.open
                                        },
                                        __('Add Image', 'jobster')
                                    );
                                }
                            }
                        )
                    ),
                    el('div',
                        {
                            className: 'pxp-form-col-8'
                        },
                        renderLocationField()
                    )
                ),
                el(Button,
                    {
                        isPrimary: true,
                        isLarge: true,
                        className : 'locations-list-add-btn',
                        onClick: function(event) {
                            locations.push({
                                'src'        : newImageSrc,
                                'id'         : newImageId,
                                'location'   : newLocation,
                                'location_id': newLocationId,
                            });

                            data_json.locations = locations;
                            setAttributes({
                                data_content: encodeURIComponent(
                                    JSON.stringify(data_json)
                                )
                            });

                            setState({
                                newImageSrc: '',
                                newImageId: '',
                                newLocation: '',
                                newLocationId: '',
                            });

                            jQuery('.locations-list-new-form').hide();
                            jQuery('.locations-list-add-form-btn').show();
                        }
                    },
                    __('Add Location', 'jobster')
                ),
                el(Button,
                    {
                        isSecondary: true,
                        isLarge: true,
                        className : 'locations-list-cancel-btn',
                        onClick: function(event) {
                            setState({
                                newImageSrc: '',
                                newImageId: '',
                                newLocation: '',
                                newLocationId: ''
                            });

                            jQuery('.locations-list-new-form').hide();
                            jQuery('.locations-list-add-form-btn').show();
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
                        className: 'job-locations-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'job-locations-placeholder-subheader'
                    },
                    subtitle
                ),
                jobLocationsOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'job-locations-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'job-locations-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'job-locations-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/job-locations', {
        title: __('Job Locations', 'jobster'),
        description: __('Jobster job locations block.', 'jobster'),
        icon: {
            src: 'location',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('locations', 'jobster'),
            __('job', 'jobster'),
            __('cities', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22type%22%3A%22b%22%2C%22bg%22%3A%22%22%2C%22align%22%3A%22s%22%2C%22animation%22%3A%22e%22%2C%22locations%22%3A%5B%5D%7D'
            }
        },
        edit: withState({
            newImageSrc: '',
            newImageId: '',
            newLocation: '',
            newLocationId: ''
        })(JobLocationsControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);