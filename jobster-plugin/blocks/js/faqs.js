(function(wp) {
    var registerBlockType = wp.blocks.registerBlockType;

    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var Button        = wp.components.Button;

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

    function FAQsControl(props) {
        var attributes    = props.attributes;
        var setAttributes = props.setAttributes;
        var setState      = props.setState;
        var className     = props.className;
        var isSelected    = props.isSelected;

        var newQuestion = props.newQuestion;
        var newAnswer   = props.newAnswer;

        var data_content = attributes.data_content;
        var data         = window.decodeURIComponent(data_content);
        var data_json    = jQuery.parseJSON(data);

        var title     = getObjectProperty(data_json, 'title');
        var subtitle  = getObjectProperty(data_json, 'subtitle');
        var align     = getObjectProperty(data_json, 'align');
        var animation = getObjectProperty(data_json, 'animation');
        var faqs      = getObjectProperty(data_json, 'faqs');

        if (!jQuery.isArray(faqs)) {
            faqs = [];
        }

        var renderFAQsList = function() {
            var items = [];

            if (faqs.length > 0) {
                jQuery.each(faqs, function(index, elem) {
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
                                            className: 'faqs-list-item-question'
                                        },
                                        elem.question
                                    ),
                                    el('div',
                                        {
                                            className: 'faqs-list-item-answer'
                                        },
                                        elem.answer
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

                                                data_json.faqs.splice(elemIndex, 1);

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

        var faqsOptions = [
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
                )
            ),
            el('h4', 
                {
                    className: 'faqs-list-header'
                },
                __('Questions List', 'jobster')
            ),
            el('div',
                {
                    className: 'faqs-list-container'
                },
                el('ul',
                    {},
                    renderFAQsList()
                )
            ),
            el(Button,
                {
                    className: 'faqs-list-new-btn',
                    isSecondary: true,
                    isLarge: true,
                    onClick: function(event) {
                        jQuery(event.target).hide();
                        jQuery('.faqs-list-new-form').show();
                    }
                },
                __('Add New Question', 'jobster')
            ),
            el('div',
                {
                    className: 'faqs-list-new-form'
                },
                el('h5', 
                    {
                        className: 'faqs-list-new-header'
                    },
                    __('New Question', 'jobster')
                ),
                el(TextControl, 
                    {
                        className: 'faqs-list-new-question',
                        label: __('Question', 'jobster'),
                        value: newQuestion,
                        placeholder: __('Enter question', 'jobster'),
                        onChange: function(newQuestion) {
                            setState({ newQuestion });
                        }
                    }
                ),
                el(TextControl, 
                    {
                        className: 'faqs-list-new-answer',
                        label: __('Answer', 'jobster'),
                        value: newAnswer,
                        placeholder: __('Enter answer', 'jobster'),
                        onChange: function(newAnswer) {
                            setState({ newAnswer });
                        }
                    }
                ),
                el(Button,
                    {
                        isPrimary: true,
                        isLarge: true,
                        className: 'faqs-list-new-ok',
                        onClick: function() {
                            faqs.push({
                                'question': newQuestion,
                                'answer'  : newAnswer
                            });

                            data_json.faqs = faqs;
                            setAttributes({
                                data_content: encodeURIComponent(
                                    JSON.stringify(data_json)
                                )
                            });

                            setState({ 
                                newQuestion: '',
                                newAnswer  : '',
                            });

                            jQuery('.faqs-list-new-form').hide();
                            jQuery('.faqs-list-new-btn').show();
                        }
                    },
                    __('Add Question', 'jobster')
                ),
                el(Button,
                    {
                        isSecondary: true,
                        isLarge: true,
                        className: 'faqs-list-new-cancel',
                        onClick: function() {
                            setState({ 
                                newQuestion: '',
                                newAnswer  : '',
                            });

                            jQuery('.faqs-list-new-form').hide();
                            jQuery('.faqs-list-new-btn').show();
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
                        className: 'faqs-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'faqs-placeholder-subheader'
                    },
                    subtitle
                ),
                faqsOptions
            );
        } else {
            return el('div', 
                {
                    className: className
                },
                el('h3', 
                    {
                        className: 'faqs-placeholder-header'
                    },
                    title
                ),
                el('h4', 
                    {
                        className: 'faqs-placeholder-subheader'
                    },
                    subtitle
                ),
                el('div', 
                    {
                        className: 'faqs-placeholder-img'
                    }
                )
            );
        }
    }

    registerBlockType('jobster-plugin/faqs', {
        title: __('FAQs', 'jobster'),
        description: __('Jobster FAQs block.', 'jobster'),
        icon: {
            src: 'editor-help',
            foreground: '#007cba',
        },
        category: 'widgets',
        keywords: [
            __('questions', 'jobster'),
            __('faqs', 'jobster'),
            __('faq', 'jobster')
        ],
        attributes: {
            data_content: {
                type: 'string',
                default: '%7B%22title%22%3A%22%22%2C%22subtitle%22%3A%22%22%2C%22align%22%3A%22s%22%2C%22animation%22%3A%22e%22%2C%22faqs%22%3A%5B%5D%7D'
            }
        },
        edit: withState({
            newQuestion: '',
            newAnswer  : '',
        })(FAQsControl),
        save: function(props) {
            return null;
        },
    });
})(window.wp);