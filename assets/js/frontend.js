(function ($) {
    'use strict';

    const attachButtonWiggle = (scope) => {
        $('.nfa-hero-cta__button', scope).on('mouseenter', function () {
            $(this).css('transform', 'translateY(-4px) scale(1.02)');
        }).on('mouseleave', function () {
            $(this).css('transform', '');
        });
    };

    const initSlider = (scope) => {
        const $sliders = $('.nfa-slider', scope);

        $sliders.each(function () {
            const $slider = $(this);
            const track = $slider.find('.nfa-slider__track').get(0);

            if (!track) {
                return;
            }

            const perView = Math.max(1, Math.min(6, parseInt($slider.data('slider-per-view'), 10) || 3));
            const gap = Math.max(0, parseInt($slider.data('slider-gap'), 10) || 20);
            const items = Array.from(track.children);

            const updateSizes = () => {
                track.style.gap = `${gap}px`;
                const width = `calc((100% - ${(gap * (perView - 1))}px) / ${perView})`;
                items.forEach((item) => {
                    item.style.flex = `0 0 ${width}`;
                });
            };

            updateSizes();

            if ($slider.data('slider-bound')) {
                return;
            }

            $slider.data('slider-bound', true);

            $(window).on('resize.nfaSlider', updateSizes);

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

        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-hero-cta.default', attachButtonWiggle);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-testimonial-grid.default', initSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-logo-grid.default', initSlider);
    });
})(jQuery);
