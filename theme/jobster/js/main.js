(function($) {
    "use strict";

    // Main nav add custom classes
    $('.pxp-nav li.menu-item-has-children').addClass('dropdown');
    $('.pxp-nav li.menu-item-has-children > a').addClass('dropdown-parent').attr('data-pxp-toggle', 'dropdown');
    $('.pxp-nav li.menu-item-has-children > ul').addClass('dropdown-menu');
    $('.pxp-nav li.menu-item-has-children li.menu-item-has-children').addClass('dropend');

    $('.pxp-nav li.page_item_has_children').addClass('dropdown');
    $('.pxp-nav li.page_item_has_children > a').addClass('dropdown-parent').attr('data-pxp-toggle', 'dropdown');
    $('.pxp-nav li.page_item_has_children > ul').addClass('dropdown-menu');
    $('.pxp-nav li.page_item_has_children li.page_item_has_children').addClass('dropend');

    $('.pxp-nav .pxp-nav-item-data').each(function() {
        var contentInitial = $(this).parent().html();
        var menuItemContent = '';

        var description = $(this).attr('data-desc');
        var iconType    = $(this).attr('data-icontype');
        var faIcon      = $(this).attr('data-iconfa');
        var imgIcon     = $(this).attr('data-iconimg');
        var submenuType = $(this).attr('data-type');

        if (iconType == 'fa') {
            $(this).parent().addClass('pxp-has-icon-small');
            menuItemContent += '<div class="pxp-dropdown-icon"><span class="' + faIcon + '"></span></div>';
        }

        if (iconType == 'img') {
            $(this).parent().addClass('pxp-has-icon');
            menuItemContent += '<div class="pxp-dropdown-icon"><img src="' + imgIcon + '" alt="-"></div>';
        }

        if (description != '' && description != undefined) {
            menuItemContent += '<div class="pxp-dropdown-text">' + contentInitial + '<span>' + description + '</span></div>';
        } else {
            menuItemContent += '<div class="pxp-dropdown-text">' + contentInitial + '</div>';
        }

        if (submenuType == 'column') {
            var liElem = $(this).parent().parent();

            if (liElem.hasClass('menu-item-has-children')) {
                liElem.addClass('pxp-is-menu-column');
            }

            if (liElem.hasClass('page_item_has_children')) {
                liElem.addClass('pxp-is-menu-column');
            }
        }

        $(this).parent().html(menuItemContent);
    });

    $('.pxp-nav > div > ul > li').each(function() {
        $(this).addClass('pxp-dropdown-body');

        var navColumns = '';
        $(this).find('.pxp-is-menu-column').each(function() {
            var aElem = $(this).children('a');
            var ulElem = $(this).children('ul');
            var columnTitle = aElem.find('.pxp-dropdown-text').text();

            $(this).removeClass('dropdown');
            aElem.remove();
            ulElem.removeClass('dropdown-menu');
            if (columnTitle != '***') {
                ulElem.prepend('<li class="pxp-dropdown-header">' + columnTitle + '</li>');
            }

            navColumns += '<div class="col-auto pxp-dropdown-list">' + $(this).html() + '</div>';
        });

        if (navColumns != '') {
            $(this).children(':nth-child(2)').html(
                '<div class="pxp-dropdown-layout">' + 
                    '<div class="row gx-5 pxp-dropdown-lists">' + 
                        navColumns + 
                    '</div>' + 
                '</div>'
            );
        }
    });

    $('.pxp-nav').css('opacity', '1');

    // Mobile main nav add custom classes
    $('.pxp-nav-mobile li.menu-item-has-children').addClass('dropdown');
    $('.pxp-nav-mobile li.menu-item-has-children > a').addClass('dropdown-toggle nav-link').attr('data-bs-toggle', 'dropdown');
    $('.pxp-nav-mobile li.menu-item-has-children > ul').addClass('dropdown-menu');

    $('.pxp-nav-mobile li.page_item_has_children').addClass('dropdown');
    $('.pxp-nav-mobile li.page_item_has_children > a').addClass('dropdown-toggle nav-link').attr('data-bs-toggle', 'dropdown');
    $('.pxp-nav-mobile li.page_item_has_children > ul').addClass('dropdown-menu');

    var animateHTML = function() {
        var elems;
        var windowHeight;

        function init() {
            elems = document.querySelectorAll('.pxp-animate-in');
            windowHeight = window.innerHeight;
            addEventHandlers();
            checkPosition();
        }

        function addEventHandlers() {
            window.addEventListener('scroll', checkPosition);
            window.addEventListener('resize', init);
        }

        function checkPosition() {
            for (var i = 0; i < elems.length; i++) {
                var positionFromTop = elems[i].getBoundingClientRect().top;

                if (positionFromTop - windowHeight <= 0) {
                    elems[i].classList.add('pxp-in');
                    if ($(elems[i]).hasClass('pxp-info-stats-item')) {
                        animateBubbles(elems[i]);
                    }
                    if ($(elems[i]).hasClass('pxp-testimonials-1-circles-item')) {
                        animateTestimonialsCircles(elems[i]);
                    }
                }
            }
        }

        return {
            init: init
        };
    };

    function handlePreloader() {
        if ($('.pxp-preloader').length > 0) {
            $('.pxp-preloader').delay(200).fadeOut(500, function() {
                animateHTML().init();
                animateHeroElement('.pxp-hero-right-bg-card');
                animateHeroElement('.pxp-hero-card-info');
                animateHeroElement('.pxp-hero-cards-container');
                animateHeroElement('.pxp-hero-center-carousel');
                setTimeout(function() {
                    animateHeroElement('.pxp-hero-stats-item');
                }, 200);
                setTimeout(function() {
                    animateHeroElement('.pxp-header-side-image');
                }, 200);

                setTimeout(function() {
                    animateHeroElement('.pxp-contact-us-form');
                }, 200);

                animateHeroElement('.pxp-hero-boxed-circulars');
                animateHeroElement('.pxp-hero-boxed-icon-circle-1');
                animateHeroElement('.pxp-hero-boxed-icon-circle-2');

                animateHeroElement('.pxp-hero-boxed-info-card-big');
                animateHeroElement('.pxp-hero-boxed-info-card-small');
                animateHeroElement('.pxp-hero-boxed-info-list-container');

                animateOnMouseMove('.pxp-mouse-move');
            });
        }
    }

    function windowResizeHandler() {
        resizeHeroBoxedCirculars();
    }

    function onContentScroll() {
        if ($('.pxp-header').hasClass('pxp-bigger') || $('.pxp-header').hasClass('pxp-no-bg')) {
            if (window.pageYOffset > 20) {
                $('.pxp-header').addClass('pxp-is-sticky');
            } else {
                $('.pxp-header').removeClass('pxp-is-sticky');
            }
        } else if ($('.pxp-header').hasClass('pxp-no-bg')) {
            if (window.pageYOffset > 0) {
                $('.pxp-header').addClass('pxp-is-sticky');
            } else {
                $('.pxp-header').removeClass('pxp-is-sticky');
            }
        } else {
            if (window.pageYOffset > 93) {
                $('.pxp-header').addClass('pxp-is-sticky');
            } else {
                $('.pxp-header').removeClass('pxp-is-sticky');
            }
        }
    }

    function handleMasonry() {
        if ($('.pxp-masonry').length > 0) {
            setTimeout(function() {
                $('.pxp-masonry').masonry({
                    itemSelector: '.pxp-grid-item',
                    columnWidth: '.pxp-grid-item',
                    horizontalOrder: true,
                    isAnimated: false,
                    hiddenStyle: {
                        opacity: 0,
                        transform: ''
                    }
                });
            }, 50);
        }
    }

    window.onscroll = function() {
        onContentScroll();
    };

    $(window).on('load', function() {
        handlePreloader();

        $('#pxp-account-type-modal').modal('show');
    });

    windowResizeHandler();
    handleMasonry();

    $(window).resize(function() {
        windowResizeHandler();
        handleMasonry();
    });

    function animateHeroElement(element) {
        if ($(element).hasClass('pxp-has-animation')) {
            $(element).addClass('pxp-animate');
        }
        if ($(element).hasClass('pxp-animate-cards')) {
            setTimeout(function() {
                $(element).find('.pxp-hero-card').addClass('pxp-animate');
            }, 600);
            setTimeout(function() {
                $(element).find('.pxp-hero-card-dark').addClass('pxp-animate');
                $(element).find('.pxp-hero-card-light').addClass('pxp-animate');
            }, 1200);
        }
        if ($(element).hasClass('pxp-animate-bounce')) {
            setTimeout(function() {
                $(element).addClass('animate__animated animate__bounceIn');
            }, 1800);
        }
        if ($(element).hasClass('pxp-animate-circles-bounce')) {
            $(element).addClass('animate__animated animate__bounceIn');
        }
        if ($(element).hasClass('pxp-animate-info-card')) {
            setTimeout(function() {
                $(element).addClass('animate__animated animate__flipInX');
            }, 300);
        }
        if ($(element).hasClass('pxp-animate-icon-circle-bounce')) {
            setTimeout(function() {
                $(element).addClass('animate__animated animate__bounceIn');
            }, 800);
        }
    }

    function animateBubbles(element) {
        if ($(element).hasClass('pxp-animate-bounce')) {
            setTimeout(function() {
                $(element).addClass('animate__animated animate__bounceIn');
            }, 500);
        }
    }

    function animateTestimonialsCircles(element) {
        if ($(element).hasClass('pxp-animate-bounce')) {
            setTimeout(function() {
                $(element).addClass('animate__animated animate__bounceIn');
            }, 200);
        }
    }

    function animateOnMouseMove(element) {
        const mouseMoveElems = document.querySelectorAll(element);

        mouseMoveElems.forEach(function(mouseMoveElem) {
            var speed = mouseMoveElem.getAttribute('data-speed');

            window.addEventListener('mousemove', (evt) => {
                const x = -(window.innerWidth / 2 - evt.pageX) / parseInt(speed);
                const y = -(window.innerHeight / 2 - evt.pageY) / parseInt(speed);
                mouseMoveElem.style.transform = `translateY(${y}px) translateX(${x}px)`;
            });
        });
    }

    function resizeHeroBoxedCirculars() {
        if ($('.pxp-hero-boxed-circulars').length > 0) {
            var circularsWidth = $('.pxp-hero-boxed-circulars').width();
            $('.pxp-hero-boxed-circulars').height(circularsWidth);
        }
    }

    $('.pxp-datepicker').datepickerc({
        'format' : 'yyyy-mm-dd'
    });

    if ($('.pxp-hero-logos-carousel').length > 0) {
        $('.pxp-hero-logos-carousel').owlCarousel({
            'nav': false,
            'dots': false,
            'margin': 40,
            'loop': false,
            'responsive': {
                0: {
                    'items': 4
                },
                767: {
                    'items': 5
                },
                991: {
                    'items': 7
                },
                1200: {
                    'items': 5
                },
                1400: {
                    'items': 6
                }
            },
            'checkVisible': false,
            'smartSpeed': 600,
            'autoplay': false,
            'autoplayTimeout': 5000
        });
    }

    $('.pxp-animate-icon').hover(function() {
        $(this).find('img').addClass('animate__animated animate__jackInTheBox');
    }, function() {
        $(this).find('img').removeClass('animate__animated animate__jackInTheBox');
    });

    // Price plans switcher
    $('[name=pxp-price-plans-switcher]').on('change', function() {
        var checkedValue = $('[name=pxp-price-plans-switcher]:checked').attr('data-period');

        if (checkedValue == 'month') {
            $('.pxp-plans-price-annual').hide();
            $('.pxp-plans-price-monthly').show();
        } else {
            $('.pxp-plans-price-monthly').hide();
            $('.pxp-plans-price-annual').show();
        }
    });

    if ($('.pxp-categories-carousel').length > 0) {
        $('.pxp-categories-carousel').owlCarousel({
            'nav': false,
            'dots': true,
            'margin': 30,
            'loop': false,
            'responsive': {
                0: {
                    'items': 1
                },
                600: {
                    'items': 2
                },
                900: {
                    'items': 4
                },
                1600: {
                    'items': 6
                }
            },
            'checkVisible': false,
            'smartSpeed': 600
        });
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/job_categories.default', function($scope, $) {
            if ($('.pxp-categories-carousel').length > 0) {
                $('.pxp-categories-carousel').owlCarousel({
                    'nav': false,
                    'dots': true,
                    'margin': 30,
                    'loop': false,
                    'responsive': {
                        0: {
                            'items': 1
                        },
                        600: {
                            'items': 2
                        },
                        900: {
                            'items': 4
                        },
                        1600: {
                            'items': 6
                        }
                    },
                    'checkVisible': false,
                    'smartSpeed': 600
                });
            }
        });
    });

    // Set checked badge color for jobs list filter
    $('.pxp-jobs-list-side-filter .list-group-item input[type="checkbox"').on('change', function() {
        if ($(this).is(":checked")) {
            $(this).parent().parent().addClass('pxp-checked');
        } else {
            $(this).parent().parent().removeClass('pxp-checked');
        }
    });

    // Toogle side filter on mobile
    $('.pxp-list-side-filter-header a').on('click', function() {
        $(this).parent().parent().find('.pxp-list-side-filter-panel').slideToggle();
    });

    // Toggle job details panel on mobile
    $('.pxp-jobs-card-4').on('click', function() {
        $('.pxp-jobs-tab-content').addClass('pxp-show');
    });
    $('.pxp-jobs-tab-pane-close-btn').on('click', function() {
        $('.pxp-jobs-tab-content').removeClass('pxp-show');
    });

    // Close messages panel on mobile
    $('.pxp-dashboard-inbox-messages-close-btn').on('click', function() {
        $('.pxp-dashboard-inbox-messages-container').removeClass('pxp-show');
    });

    // User type register modal switcher
    $('[name=pxp-signup-modal-type-switcher]').on('change', function() {
        var checkedValue = $('[name=pxp-signup-modal-type-switcher]:checked').attr('data-type');

        if (checkedValue == 'company') {
            $('.pxp-signup-modal-candidate-fields').hide();
            $('.pxp-signup-modal-company-fields').show();
        }

        if (checkedValue == 'candidate') {
            $('.pxp-signup-modal-company-fields').hide();
            $('.pxp-signup-modal-candidate-fields').show();
        }
    });

     // User type register page switcher
     $('[name=pxp-signup-page-type-switcher]').on('change', function() {
        var checkedValue = $('[name=pxp-signup-page-type-switcher]:checked').attr('data-type');

        if (checkedValue == 'company') {
            $('.pxp-signup-page-candidate-fields').hide();
            $('.pxp-signup-page-company-fields').show();
        }

        if (checkedValue == 'candidate') {
            $('.pxp-signup-page-company-fields').hide();
            $('.pxp-signup-page-candidate-fields').show();
        }
    });
    if ($('#pxp-is-candidate-page-reg').length > 0) {
        $('.pxp-signup-page-company-fields').hide();
        $('.pxp-signup-page-candidate-fields').show();
    }
    if ($('#pxp-is-company-page-reg').length > 0) {
        $('.pxp-signup-page-candidate-fields').hide();
        $('.pxp-signup-page-company-fields').show();
    }

    // Manage jobs type check on jobs filter
    $('.pxp-jobs-page-type').on('change', function() {
        var checkedValues = [];

        $('.pxp-jobs-page-type').each(function() {
            var checked = document.querySelector(
                `#pxp-jobs-page-type-${$(this).val()}:checked`
            ) !== null;

            if (checked === true) {
                checkedValues.push($(this).val());
            }
        });

        $('#type').val(checkedValues.join(','));
        setTimeout(function() {
            $('#pxp-jobs-page-search-form').submit();
        }, 100);
    });
    $('select#pxp-jobs-page-type').on('change', function() {
        $('#type').val($(this).val());
        setTimeout(function() {
            $('#pxp-jobs-page-search-form').submit();
        }, 100);
    });

    // Manage jobs level check on jobs filter
    $('.pxp-jobs-page-level').on('change', function() {
        var checkedValues = [];

        $('.pxp-jobs-page-level').each(function() {
            var checked = document.querySelector(
                `#pxp-jobs-page-level-${$(this).val()}:checked`
            ) !== null;

            if (checked === true) {
                checkedValues.push($(this).val());
            }
        });

        $('#level').val(checkedValues.join(','));
        setTimeout(function() {
            $('#pxp-jobs-page-search-form').submit();
        }, 100);
    });
    $('select#pxp-jobs-page-level').on('change', function() {
        $('#level').val($(this).val());
        setTimeout(function() {
            $('#pxp-jobs-page-search-form').submit();
        }, 100);
    });

    // Careerjet - Manage jobs type check on jobs filter
    $('.pxp-careerjet-jobs-page-type').on('change', function() {
        var index = $(this).parent().parent().index();
        $('.pxp-careerjet-jobs-page-type').each(function(i) {
            if (index != i) {
                $(this).prop('checked', false);
            }
        });
        if ($(this).is(':checked')) {
            $('#type').val($(this).val());
        } else {
            $('#type').val('');
        }

        setTimeout(function() {
            $('#pxp-jobs-page-search-form').submit();
        }, 100);

    });
    $('select#pxp-careerjet-jobs-page-type').on('change', function() {
        $('#type').val($(this).val());
        setTimeout(function() {
            $('#pxp-jobs-page-search-form').submit();
        }, 100);
    });

    // Careerjet - Manage jobs period check on jobs filter
    $('.pxp-careerjet-jobs-page-period').on('change', function() {
        var index = $(this).parent().parent().index();
        $('.pxp-careerjet-jobs-page-period').each(function(i) {
            if (index != i) {
                $(this).prop('checked', false);
            }
        });
        if ($(this).is(':checked')) {
            $('#period').val($(this).val());
        } else {
            $('#period').val('');
        }

        setTimeout(function() {
            $('#pxp-jobs-page-search-form').submit();
        }, 100);

    });
    $('select#pxp-careerjet-jobs-page-period').on('change', function() {
        $('#period').val($(this).val());
        setTimeout(function() {
            $('#pxp-jobs-page-search-form').submit();
        }, 100);
    });

    // Manage sort jobs 
    $('#pxp-sort-jobs').on('change', function() {
        $('#sort').val($(this).val());
        setTimeout(function() {
            $('#pxp-jobs-page-search-form').submit();
        }, 100);
    });

    // Empty search field with floating label on focus
    $('.pxp-hero-form .pxp-has-floating-label').on('focus', function() {
        $(this).val('');
    });

    // Manage sort companies
    $('#pxp-sort-companies').on('change', function() {
        $('#sort').val($(this).val());
        setTimeout(function() {
            $('#pxp-companies-page-search-form').submit();
        }, 100);
    });

    // Manage sort candidates
    $('#pxp-sort-candidates').on('change', function() {
        $('#sort').val($(this).val());
        setTimeout(function() {
            $('#pxp-candidates-page-search-form').submit();
        }, 100);
    });

    // Init tooltips
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Scroll inbox messages container to bottom
    if ($('.pxp-dashboard-inbox-messages-content').length > 0) {
        var inboxContainer = $('.pxp-dashboard-inbox-messages-content');
        inboxContainer[0].scrollTop = inboxContainer[0].scrollHeight;
    }

    // Manage company dashboard - jobs list filter
    $('select#pxp-company-dashboard-jobs-filter-category').on('change', function() {
        $('#category').val($(this).val());
        setTimeout(function() {
            $('.pxp-company-dashboard-jobs-search-form > form').submit();
        }, 100);
    });
    $('select#pxp-company-dashboard-jobs-filter-type').on('change', function() {
        $('#type').val($(this).val());
        setTimeout(function() {
            $('.pxp-company-dashboard-jobs-search-form > form').submit();
        }, 100);
    });

    // Password reveal
    $('.pxp-password-toggle').on('click', function() {
        var inp = $(this).parent().find('.pxp-password-control');
        var inpType = inp.attr('type').toLowerCase();

        if (inpType === 'password') {
            inp.attr('type', 'text');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            inp.attr('type', 'password');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Hide Mobile Nav Panel when click on a menu item
    const menuCanvas = new bootstrap.Offcanvas('.pxp-nav-mobile-container');
    $('.pxp-nav-mobile a').on('click', function() {
        var target = $(this).attr('href');
        if (target.startsWith('#') && target.length > 1) {
            menuCanvas.hide();
        }
    });
})(jQuery);
