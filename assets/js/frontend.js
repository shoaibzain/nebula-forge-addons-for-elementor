(function ($) {
    'use strict';

    const attachButtonWiggle = (scope) => {
        $('.nfa-hero-cta__button', scope).on('mouseenter', function () {
            $(this).css('transform', 'translateY(-4px) scale(1.02)');
        }).on('mouseleave', function () {
            $(this).css('transform', '');
        });
    };

    $(window).on('elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-hero-cta.default', attachButtonWiggle);
    });
})(jQuery);
