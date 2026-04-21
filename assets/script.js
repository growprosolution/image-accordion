/**
 * Image Accordion – JS behavior
 * - Desktop: trigger can be hover or click (from wrapper data attribute)
 * - Mobile / touch: always click
 * - No hard dependency on Elementor; if Elementor exists, will hook into it safely.
 */

(function ($) {
    'use strict';

    function iaInitAccordions(context) {
        var $root = context ? $(context) : $(document);
        var $accordions = $root.find('.ia-accordion');
        if (!$accordions.length) {
            return;
        }

        $accordions.each(function () {
            var $wrapper = $(this);
            var triggerDesktop = $wrapper.data('trigger-desktop') || 'hover';
            var defaultIndex = parseInt($wrapper.data('default-index'), 10) || 0;
            var $items = $wrapper.find('.ia-item');

            if (!$items.length) {
                return;
            }

            // Default active
            $items.removeClass('is-active');
            if (defaultIndex > 0 && defaultIndex <= $items.length) {
                $items.filter('[data-index="' + defaultIndex + '"]').addClass('is-active');
            }

            // Detect touch
            var isTouch = false;
            try {
                if (window.matchMedia && window.matchMedia('(pointer: coarse)').matches) {
                    isTouch = true;
                } else if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
                    isTouch = true;
                }
            } catch (e) {
                isTouch = false;
            }

            function setActive($item) {
                if (!$item || !$item.length) {
                    return;
                }
                $items.removeClass('is-active');
                $item.addClass('is-active');
            }

            // Bind events
            $items.off('.iaAcc');

            $items.each(function () {
                var $item = $(this);

                if (isTouch) {
                    // Mobile / touch – always click
                    $item.on('click.iaAcc', function (e) {
                        e.preventDefault();
                        setActive($item);
                    });
                } else {
                    // Desktop
                    if (triggerDesktop === 'click') {
                        $item.on('click.iaAcc', function (e) {
                            e.preventDefault();
                            setActive($item);
                        });
                    } else {
                        // hover
                        $item.on('mouseenter.iaAcc', function () {
                            setActive($item);
                        });
                    }
                }
            });
        });
    }

    // Basic init
    $(document).ready(function () {
        try {
            iaInitAccordions(document);
        } catch (e) {
            if (window.console && console.error) {
                console.error('Image Accordion init error:', e);
            }
        }
    });

    // Optional: if Elementor frontend exists, hook into it in a safe way
    $(window).on('elementor/frontend/init', function () {
        try {
            if (window.elementorFrontend && elementorFrontend.hooks) {
                elementorFrontend.hooks.addAction(
                    'frontend/element_ready/image-accordion.default',
                    function ($scope) {
                        iaInitAccordions($scope[0]);
                    }
                );
            }
        } catch (e) {
            if (window.console && console.warn) {
                console.warn('Image Accordion Elementor hook error:', e);
            }
        }
    });

})(jQuery);

(function ($) {
    'use strict';

    function iaInitAccordion($scope) {
        var $acc = $scope.find('.ia-accordion');
        if (!$acc.length) return;

        $acc.each(function () {
            var $wrapper = $(this);
            var direction = $wrapper.data('direction') || 'horizontal';
            var triggerDesktop = $wrapper.data('trigger-desktop') || 'hover';
            var defaultIndex = parseInt($wrapper.data('default-index'), 10) || 0;

            var $items = $wrapper.find('.ia-item');
            if (!$items.length) return;

            // Initialize default state
            function applyDefaultState() {
                $items.removeClass('is-active');

                if (defaultIndex > 0 && defaultIndex <= $items.length) {
                    $items.filter('[data-index="' + defaultIndex + '"]').addClass('is-active');
                }
            }

            applyDefaultState();

            // device detection
            var isTouch = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;

            function setActive($item) {
                if (!$item || !$item.length) return;
                $items.removeClass('is-active');
                $item.addClass('is-active');
            }

            // Bind events
            $items.off('.ia-accordion');

            $items.each(function () {
                var $item = $(this);

                if (isTouch) {
                    // Mobile always uses click
                    $item.on('click.ia-accordion', function (e) {
                        e.preventDefault();
                        setActive($item);
                    });
                } else {
                    // Desktop
                    if (triggerDesktop === 'click') {
                        $item.on('click.ia-accordion', function (e) {
                            e.preventDefault();
                            setActive($item);
                        });
                    } else {
                        // hover
                        $item.on('mouseenter.ia-accordion', function () {
                            setActive($item);
                        });
                    }
                }
            });

            // NEW: restore initial state when mouse leaves accordion
            $wrapper.on('mouseleave.ia-accordion', function () {
                applyDefaultState();
            });

        });
    }

    // Load on Elementor frontend
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== "undefined") {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/image-accordion.default',
                iaInitAccordion
            );
        }
    });

})(jQuery);
