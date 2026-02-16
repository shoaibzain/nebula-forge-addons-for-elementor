/**
 * Nebula Forge Addons — Frontend Scripts
 *
 * Handles slider functionality for Testimonials and Logo Grid widgets,
 * and carousel behaviour for the Showcase Carousel widget.
 *
 * @package NebulaForgeAddon
 * @since   0.1.6
 */
(function ($) {
    'use strict';

    /**
     * Initialize slider behaviour on a widget scope.
     *
     * @param {jQuery} scope The widget wrapper element.
     */
    const initSlider = (scope) => {
        const $sliders = scope.find('.nfa-slider');

        $sliders.each(function () {
            const $slider = $(this);
            const track = $slider.find('.nfa-slider__track').get(0);

            if (!track) {
                return;
            }

            // Read data attributes (validated server-side).
            const perView = Math.max(1, Math.min(6, parseInt($slider.data('slider-per-view'), 10) || 3));
            const gap = Math.max(0, parseInt($slider.data('slider-gap'), 10) || 20);
            const items = Array.from(track.children);

            /**
             * Recalculate item flex-basis and gap whenever the viewport changes.
             */
            const updateSizes = () => {
                track.style.gap = gap + 'px';
                const width = 'calc((100% - ' + (gap * (perView - 1)) + 'px) / ' + perView + ')';
                items.forEach((item) => {
                    item.style.flex = '0 0 ' + width;
                });
            };

            updateSizes();

            // Prevent double-binding on Elementor re-init.
            if ($slider.data('slider-bound')) {
                return;
            }

            $slider.data('slider-bound', true);

            // Debounced resize handler.
            let resizeTimer;
            $(window).on('resize.nfaSlider', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(updateSizes, 150);
            });

            // Navigation buttons.
            $slider.find('.nfa-slider__btn--prev').on('click', () => {
                track.scrollBy({ left: -track.clientWidth, behavior: 'smooth' });
            });

            $slider.find('.nfa-slider__btn--next').on('click', () => {
                track.scrollBy({ left: track.clientWidth, behavior: 'smooth' });
            });
        });
    };

    /**
     * Initialize Showcase Carousel behaviour on a widget scope.
     *
     * @param {jQuery} scope The widget wrapper element.
     */
    const initShowcaseCarousel = (scope) => {
        const $carousel = scope.find('.nfa-showcase');
        if (!$carousel.length) {
            return;
        }

        $carousel.each(function () {
            const $el          = $(this);
            const track        = $el.find('.nfa-showcase__track').get(0);
            const $dotsWrap    = $el.find('.nfa-showcase__dots');
            const cards        = Array.from(track.children);
            const totalSlides  = cards.length;

            if (!track || totalSlides === 0) {
                return;
            }

            // Prevent double-binding on Elementor re-init.
            if ($el.data('showcase-bound')) {
                return;
            }
            $el.data('showcase-bound', true);

            // Settings from data attributes.
            const perViewDesktop = Math.max(1, parseInt($el.data('per-view'), 10) || 3);
            const perViewTablet  = Math.max(1, parseInt($el.data('per-view-tablet'), 10) || 2);
            const perViewMobile  = Math.max(1, parseInt($el.data('per-view-mobile'), 10) || 1);
            const gap            = Math.max(0, parseInt($el.data('gap'), 10) || 20);
            const doAutoplay     = $el.data('autoplay') === 'yes';
            const autoplaySpeed  = Math.max(1000, parseInt($el.data('autoplay-speed'), 10) || 4000);
            const pauseOnHover   = $el.data('pause-on-hover') === 'yes';

            let pos = 0;
            let autoplayTimer = null;

            /** Get current per-view depending on viewport width. */
            function getPerView() {
                const w = window.innerWidth;
                if (w <= 767) return perViewMobile;
                if (w <= 1024) return perViewTablet;
                return perViewDesktop;
            }

            /** Calculate max slide index. */
            function getMaxPos() {
                return Math.max(0, totalSlides - getPerView());
            }

            /** Apply sizing and transform. */
            function render() {
                const pv = getPerView();
                const cardWidth = 'calc((100% - ' + (gap * (pv - 1)) + 'px) / ' + pv + ')';
                cards.forEach(function (card) {
                    card.style.flex = '0 0 ' + cardWidth;
                });

                // Calculate pixel offset per position.
                if (cards[0]) {
                    const rect = cards[0].getBoundingClientRect();
                    const cardPx = rect.width + gap;
                    track.style.transform = 'translateX(-' + (pos * cardPx) + 'px)';
                }

                updateDots();
            }

            /** Build / update dot indicators. */
            function updateDots() {
                if (!$dotsWrap.length) return;

                const maxPos = getMaxPos();
                const dotCount = maxPos + 1;

                // Rebuild dots if count changed.
                if ($dotsWrap.children().length !== dotCount) {
                    $dotsWrap.empty();
                    for (var i = 0; i < dotCount; i++) {
                        $dotsWrap.append('<button class="nfa-showcase__dot" data-index="' + i + '" aria-label="Slide ' + (i + 1) + '"></button>');
                    }

                    // Dot click handler.
                    $dotsWrap.find('.nfa-showcase__dot').on('click', function () {
                        pos = parseInt($(this).data('index'), 10);
                        pos = Math.min(pos, getMaxPos());
                        render();
                        resetAutoplay();
                    });
                }

                // Update active class.
                $dotsWrap.find('.nfa-showcase__dot').removeClass('nfa-showcase__dot--active');
                $dotsWrap.find('.nfa-showcase__dot').eq(pos).addClass('nfa-showcase__dot--active');
            }

            /** Go to next slide. */
            function next() {
                pos = pos >= getMaxPos() ? 0 : pos + 1;
                render();
            }

            /** Go to previous slide. */
            function prev() {
                pos = pos <= 0 ? getMaxPos() : pos - 1;
                render();
            }

            /** Start autoplay timer. */
            function startAutoplay() {
                if (!doAutoplay) return;
                stopAutoplay();
                autoplayTimer = setInterval(next, autoplaySpeed);
            }

            /** Stop autoplay timer. */
            function stopAutoplay() {
                if (autoplayTimer) {
                    clearInterval(autoplayTimer);
                    autoplayTimer = null;
                }
            }

            /** Reset autoplay after manual interaction. */
            function resetAutoplay() {
                if (!doAutoplay) return;
                stopAutoplay();
                startAutoplay();
            }

            // Arrow buttons.
            $el.find('.nfa-showcase__arrow--prev').on('click', function () {
                prev();
                resetAutoplay();
            });

            $el.find('.nfa-showcase__arrow--next').on('click', function () {
                next();
                resetAutoplay();
            });

            // Pause on hover.
            if (pauseOnHover && doAutoplay) {
                $el.on('mouseenter', stopAutoplay);
                $el.on('mouseleave', startAutoplay);
            }

            // Debounced resize.
            let resizeTimer;
            $(window).on('resize.nfaShowcaseCarousel', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    pos = Math.min(pos, getMaxPos());
                    render();
                }, 150);
            });

            // Initial render.
            render();
            startAutoplay();
        });
    };

    /**
     * Initialize Content Tabs widget.
     *
     * @param {jQuery} scope The widget wrapper element.
     */
    const initContentTabs = (scope) => {
        const $tabs = scope.find('[data-tabs]');
        if (!$tabs.length) return;

        $tabs.each(function () {
            const $el = $(this);
            if ($el.data('tabs-bound')) return;
            $el.data('tabs-bound', true);

            const $buttons = $el.find('.nfa-tabs__btn');
            const $panels  = $el.find('.nfa-tabs__panel');

            $buttons.on('click', function () {
                const $btn   = $(this);
                const target = $btn.attr('aria-controls');

                $buttons.removeClass('nfa-tabs__btn--active').attr('aria-selected', 'false');
                $btn.addClass('nfa-tabs__btn--active').attr('aria-selected', 'true');

                $panels.removeClass('nfa-tabs__panel--active').attr('hidden', true);
                $el.find('#' + target).addClass('nfa-tabs__panel--active').removeAttr('hidden');
            });
        });
    };

    /**
     * Initialize Image Comparison widget.
     *
     * @param {jQuery} scope The widget wrapper element.
     */
    const initImageComparison = (scope) => {
        const $compare = scope.find('[data-compare]');
        if (!$compare.length) return;

        $compare.each(function () {
            const el          = this;
            const $el         = $(el);
            if ($el.data('compare-bound')) return;
            $el.data('compare-bound', true);

            const orientation = $el.data('orientation') || 'horizontal';
            const isHoriz     = orientation === 'horizontal';
            const $before     = $el.find('.nfa-compare__before');
            const $slider     = $el.find('.nfa-compare__slider');
            let dragging      = false;

            function setPosition(pct) {
                pct = Math.max(0, Math.min(100, pct));
                if (isHoriz) {
                    $before.css('clip-path', 'inset(0 ' + (100 - pct) + '% 0 0)');
                    $slider.css('left', pct + '%');
                } else {
                    $before.css('clip-path', 'inset(0 0 ' + (100 - pct) + '% 0)');
                    $slider.css('top', pct + '%');
                }
            }

            function getPercent(e) {
                const rect = el.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;
                if (isHoriz) {
                    return ((clientX - rect.left) / rect.width) * 100;
                }
                return ((clientY - rect.top) / rect.height) * 100;
            }

            function onMove(e) {
                if (!dragging) return;
                e.preventDefault();
                setPosition(getPercent(e));
            }

            function onUp() {
                dragging = false;
                $(document).off('mousemove.nfaCompare touchmove.nfaCompare');
                $(document).off('mouseup.nfaCompare touchend.nfaCompare');
            }

            $el.on('mousedown touchstart', function (e) {
                dragging = true;
                setPosition(getPercent(e));
                $(document).on('mousemove.nfaCompare touchmove.nfaCompare', onMove);
                $(document).on('mouseup.nfaCompare touchend.nfaCompare', onUp);
            });
        });
    };

    /**
     * Initialize Countdown Timer widget.
     *
     * @param {jQuery} scope The widget wrapper element.
     */
    const initCountdownTimer = (scope) => {
        const $countdown = scope.find('[data-countdown]');
        if (!$countdown.length) return;

        $countdown.each(function () {
            const $el = $(this);
            if ($el.data('countdown-bound')) return;
            $el.data('countdown-bound', true);

            const targetDate = new Date($el.data('target')).getTime();
            const expiry     = $el.data('expiry') || {};
            const units      = ($el.data('units') || 'days,hours,minutes,seconds').split(',');
            const circumference = 2 * Math.PI * 44; // r=44 in SVG

            function update() {
                const now  = Date.now();
                const diff = Math.max(0, targetDate - now);

                if (diff <= 0) {
                    handleExpiry();
                    return;
                }

                const totalSec = Math.floor(diff / 1000);
                const vals = {
                    days:    Math.floor(totalSec / 86400),
                    hours:   Math.floor((totalSec % 86400) / 3600),
                    minutes: Math.floor((totalSec % 3600) / 60),
                    seconds: totalSec % 60,
                };

                units.forEach(function (unit) {
                    const $digit = $el.find('[data-digit="' + unit + '"]');
                    $digit.text(String(vals[unit]).padStart(2, '0'));

                    // Update circle progress.
                    const $progress = $el.find('[data-unit="' + unit + '"] .nfa-countdown__progress');
                    if ($progress.length) {
                        const max = parseInt($progress.data('max'), 10) || 60;
                        const fraction = vals[unit] / max;
                        const offset = circumference * (1 - fraction);
                        $progress.attr('stroke-dashoffset', offset);
                    }
                });
            }

            function handleExpiry() {
                clearInterval(timer);
                const action = expiry.action || 'none';

                if (action === 'message') {
                    $el.find('.nfa-countdown__block, .nfa-countdown__sep').hide();
                    $el.find('.nfa-countdown__expiry').show();
                } else if (action === 'hide') {
                    $el.hide();
                } else if (action === 'redirect' && expiry.redirect) {
                    window.location.href = expiry.redirect;
                }
                // 'none' — digits already show 00.
            }

            update();
            const timer = setInterval(update, 1000);
        });
    };

    $(window).on('elementor/frontend/init', () => {
        if (!window.elementorFrontend || !elementorFrontend.hooks) {
            return;
        }

        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-testimonial-grid.default', initSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-logo-grid.default', initSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-showcase-carousel.default', initShowcaseCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-content-tabs.default', initContentTabs);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-image-comparison.default', initImageComparison);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-countdown-timer.default', initCountdownTimer);
    });
})(jQuery);
