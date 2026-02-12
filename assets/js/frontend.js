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

    $(window).on('elementor/frontend/init', () => {
        if (!window.elementorFrontend || !elementorFrontend.hooks) {
            return;
        }

        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-testimonial-grid.default', initSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-logo-grid.default', initSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-showcase-carousel.default', initShowcaseCarousel);
    });
})(jQuery);
