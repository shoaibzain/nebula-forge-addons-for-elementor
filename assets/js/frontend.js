/**
 * Nebula Forge Addons — Frontend Scripts
 *
 * Handles slider functionality for Testimonials and Logo Grid widgets.
 * Hero CTA hover effects are handled purely via CSS.
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

    $(window).on('elementor/frontend/init', () => {
        if (!window.elementorFrontend || !elementorFrontend.hooks) {
            return;
        }

        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-testimonial-grid.default', initSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-logo-grid.default', initSlider);
    });
})(jQuery);
