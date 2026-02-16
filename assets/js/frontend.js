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

    /**
     * Initialize tooltips for any widget with data-nfa-tooltip attribute.
     */
    const initTooltips = () => {
        document.querySelectorAll('[data-nfa-tooltip]').forEach((el) => {
            if (el._nfaTooltipBound) {
                return;
            }
            el._nfaTooltipBound = true;

            const text = el.getAttribute('data-nfa-tooltip');
            const pos = el.getAttribute('data-nfa-tooltip-pos') || 'top';
            const trigger = el.getAttribute('data-nfa-tooltip-trigger') || 'hover';
            const showArrow = el.getAttribute('data-nfa-tooltip-arrow') !== '0';
            const duration = parseInt(el.getAttribute('data-nfa-tooltip-duration'), 10) || 250;

            // Create tooltip element.
            const tip = document.createElement('div');
            tip.className = 'nfa-tooltip nfa-tooltip--' + pos;
            if (!showArrow) {
                tip.classList.add('nfa-tooltip--no-arrow');
            }
            tip.textContent = text;
            tip.style.transitionDuration = duration + 'ms';
            tip.setAttribute('role', 'tooltip');
            tip.id = 'nfa-tooltip-' + Math.random().toString(36).substr(2, 9);

            el.setAttribute('aria-describedby', tip.id);
            el.style.position = el.style.position || 'relative';

            document.body.appendChild(tip);

            /**
             * Position the tooltip relative to the element.
             */
            function positionTip() {
                const rect = el.getBoundingClientRect();
                const tipRect = tip.getBoundingClientRect();
                const scrollY = window.pageYOffset || document.documentElement.scrollTop;
                const scrollX = window.pageXOffset || document.documentElement.scrollLeft;
                const gap = 8;

                let top, left;

                switch (pos) {
                    case 'bottom':
                        top = rect.bottom + scrollY + gap;
                        left = rect.left + scrollX + (rect.width / 2) - (tipRect.width / 2);
                        break;
                    case 'left':
                        top = rect.top + scrollY + (rect.height / 2) - (tipRect.height / 2);
                        left = rect.left + scrollX - tipRect.width - gap;
                        break;
                    case 'right':
                        top = rect.top + scrollY + (rect.height / 2) - (tipRect.height / 2);
                        left = rect.right + scrollX + gap;
                        break;
                    default: // top
                        top = rect.top + scrollY - tipRect.height - gap;
                        left = rect.left + scrollX + (rect.width / 2) - (tipRect.width / 2);
                }

                tip.style.top = top + 'px';
                tip.style.left = left + 'px';
            }

            function showTip() {
                positionTip();
                tip.classList.add('nfa-tooltip--visible');
            }

            function hideTip() {
                tip.classList.remove('nfa-tooltip--visible');
            }

            if (trigger === 'click') {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (tip.classList.contains('nfa-tooltip--visible')) {
                        hideTip();
                    } else {
                        showTip();
                    }
                });
                document.addEventListener('click', (e) => {
                    if (!el.contains(e.target) && !tip.contains(e.target)) {
                        hideTip();
                    }
                });
            } else {
                el.addEventListener('mouseenter', showTip);
                el.addEventListener('mouseleave', hideTip);
                el.addEventListener('focusin', showTip);
                el.addEventListener('focusout', hideTip);
            }
        });
    };

    /**
     * Initialize wrapper links for elements with data-nfa-wrapper-link.
     */
    const initWrapperLinks = () => {
        document.querySelectorAll('[data-nfa-wrapper-link]').forEach((el) => {
            if (el._nfaWrapperLinkBound) {
                return;
            }
            el._nfaWrapperLinkBound = true;

            const url = el.getAttribute('data-nfa-wrapper-link');
            const isExternal = el.getAttribute('data-nfa-link-external') === '1';
            const isNofollow = el.getAttribute('data-nfa-link-nofollow') === '1';

            if (!url) {
                return;
            }

            el.addEventListener('click', (e) => {
                // Don't navigate if clicking on an actual link or button inside.
                const tag = e.target.tagName.toLowerCase();
                if (tag === 'a' || tag === 'button' || tag === 'input' || tag === 'textarea' || tag === 'select') {
                    return;
                }

                if (isExternal) {
                    const win = window.open(url, '_blank');
                    if (win && isNofollow) {
                        win.opener = null;
                    }
                } else {
                    window.location.href = url;
                }
            });

            // Keyboard accessibility — Enter key triggers navigation.
            el.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    el.click();
                }
            });
        });
    };

    /**
     * Initialize hamburger menu widgets.
     */
    const initHamburgerMenu = (scope) => {
        const $menus = scope.find('.nfa-hamburger');

        $menus.each(function () {
            const $root = $(this);
            if ($root.data('nfa-hm-bound')) return;
            $root.data('nfa-hm-bound', true);

            const $toggle  = $root.find('.nfa-hamburger__toggle');
            const $panel   = $root.find('.nfa-hamburger__panel');
            const $overlay = $root.find('.nfa-hamburger__overlay');
            const $close   = $root.find('.nfa-hamburger__close');
            const closeOnLink = $root.data('close-on-link') === 1 || $root.data('close-on-link') === '1';
            const closeOnEsc  = $root.data('close-on-esc') === 1 || $root.data('close-on-esc') === '1';

            function open() {
                $root.addClass('nfa-hamburger--open');
                $toggle.attr('aria-expanded', 'true');
                $panel.attr('aria-hidden', 'false');
                // Trap focus inside panel.
                $panel.find('a, button').first().focus();
            }

            function close() {
                $root.removeClass('nfa-hamburger--open');
                $toggle.attr('aria-expanded', 'false');
                $panel.attr('aria-hidden', 'true');
                $toggle.focus();
            }

            $toggle.on('click', function (e) {
                e.preventDefault();
                if ($root.hasClass('nfa-hamburger--open')) {
                    close();
                } else {
                    open();
                }
            });

            $close.on('click', close);
            $overlay.on('click', close);

            if (closeOnEsc) {
                $(document).on('keydown', function (e) {
                    if (e.key === 'Escape' && $root.hasClass('nfa-hamburger--open')) {
                        close();
                    }
                });
            }

            if (closeOnLink) {
                $panel.on('click', '.nfa-hamburger__link, .nfa-hamburger__sub-link', function () {
                    const href = $(this).attr('href');
                    if (href && href !== '#') {
                        close();
                    }
                });
            }

            // Submenu toggle.
            $root.find('.nfa-hamburger__item--has-sub > .nfa-hamburger__link').on('click', function (e) {
                if ($(this).attr('href') === '#') {
                    e.preventDefault();
                }
                $(this).parent().toggleClass('nfa-hamburger__item--sub-open');
            });
        });
    };

    /**
     * Initialize advanced form widgets.
     */
    const initAdvancedForm = (scope) => {
        const $forms = scope.find('.nfa-form');

        $forms.each(function () {
            const $root = $(this);
            if ($root.data('nfa-form-bound')) return;
            $root.data('nfa-form-bound', true);

            const config = $root.data('nfa-form');
            if (!config) return;

            const $form   = $root.find('.nfa-form__el');
            const $submit = $root.find('.nfa-form__submit');
            const $text   = $root.find('.nfa-form__submit-text');
            const $spin   = $root.find('.nfa-form__spinner');
            const $msg    = $root.find('.nfa-form__msg');

            /**
             * Validate a single field.
             */
            function validateField(el) {
                const $el = $(el);
                const $err = $el.closest('.nfa-form__col').find('.nfa-form__field-error');
                let valid = true;
                let errMsg = '';

                // Required check.
                if (el.required) {
                    if (el.type === 'checkbox') {
                        const name = el.name;
                        const checked = $form.find('input[name="' + name + '"]:checked').length;
                        if (!checked) {
                            valid = false;
                            errMsg = config.requiredMsg || 'This field is required.';
                        }
                    } else if (!el.value.trim()) {
                        valid = false;
                        errMsg = config.requiredMsg || 'This field is required.';
                    }
                }

                // Type-specific validation.
                if (valid && el.value.trim()) {
                    if (el.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.value)) {
                        valid = false;
                        errMsg = 'Please enter a valid email address.';
                    }
                    if (el.type === 'url' && !/^https?:\/\/.+/.test(el.value)) {
                        valid = false;
                        errMsg = 'Please enter a valid URL.';
                    }
                    if (el.type === 'tel' && !/^[\d\s+\-().]+$/.test(el.value)) {
                        valid = false;
                        errMsg = 'Please enter a valid phone number.';
                    }
                    if (el.type === 'file') {
                        const maxMB = parseFloat($el.data('max-size') || 5);
                        if (el.files[0] && el.files[0].size > maxMB * 1024 * 1024) {
                            valid = false;
                            errMsg = 'File exceeds ' + maxMB + 'MB limit.';
                        }
                    }
                }

                if (!valid) {
                    $el.addClass('nfa-form__input--invalid nfa-form__select--invalid nfa-form__textarea--invalid');
                    $err.text(errMsg);
                } else {
                    $el.removeClass('nfa-form__input--invalid nfa-form__select--invalid nfa-form__textarea--invalid');
                    $err.text('');
                }

                return valid;
            }

            // Live validation on blur.
            $form.find('input, textarea, select').on('blur change', function () {
                validateField(this);
            });

            $form.on('submit', function (e) {
                e.preventDefault();

                // Validate all fields.
                let allValid = true;
                $form.find('input[required], textarea[required], select[required]').each(function () {
                    if (!validateField(this)) {
                        allValid = false;
                    }
                });

                if (!allValid) {
                    $form.find('.nfa-form__input--invalid, .nfa-form__select--invalid, .nfa-form__textarea--invalid').first().focus();
                    return;
                }

                // Gather field data.
                const fields = [];
                const seen = {};

                $form.find('[data-label]').each(function () {
                    const $el = $(this);
                    const label = $el.data('label');
                    const type = $el.attr('type') || this.tagName.toLowerCase();

                    if (type === 'checkbox') {
                        if (!seen[label]) {
                            seen[label] = true;
                            const vals = [];
                            $form.find('input[data-label="' + label + '"]:checked').each(function () {
                                vals.push($(this).val());
                            });
                            fields.push({ label: label, value: vals });
                        }
                        return;
                    }

                    if (type === 'radio') {
                        if (!seen[label]) {
                            seen[label] = true;
                            const val = $form.find('input[data-label="' + label + '"]:checked').val() || '';
                            fields.push({ label: label, value: val });
                        }
                        return;
                    }

                    if (type === 'file') {
                        // File uploads not sent via AJAX JSON — skip for now.
                        fields.push({ label: label, value: this.files[0] ? this.files[0].name : '' });
                        return;
                    }

                    fields.push({ label: label, value: $el.val() || '' });
                });

                // Show loading state.
                $submit.prop('disabled', true);
                $text.css('opacity', '0.5');
                $spin.show();
                $msg.hide();

                $.ajax({
                    url: config.ajaxUrl,
                    type: 'POST',
                    data: {
                        action:          'nfa_form_submit',
                        nonce:           config.nonce,
                        form_name:       config.formName,
                        action_save:     config.actionSave ? '1' : '',
                        action_email:    config.actionEmail ? '1' : '',
                        email_to:        config.emailTo,
                        email_subject:   config.emailSubject,
                        email_from_name: config.emailFromName,
                        email_reply_to:  config.emailReplyTo,
                        page_url:        window.location.href,
                        fields:          JSON.stringify(fields),
                    },
                    success: function (resp) {
                        $submit.prop('disabled', false);
                        $text.css('opacity', '1');
                        $spin.hide();

                        if (resp.success) {
                            $msg.removeClass('nfa-form__msg--error').addClass('nfa-form__msg--success')
                                .text(config.successMessage || resp.data.message).show();
                            $form[0].reset();
                            $form.find('.nfa-form__field-error').text('');

                            if (config.actionRedirect && config.redirectUrl) {
                                setTimeout(function () {
                                    window.location.href = config.redirectUrl;
                                }, 1000);
                            }
                        } else {
                            $msg.removeClass('nfa-form__msg--success').addClass('nfa-form__msg--error')
                                .text(config.errorMessage || resp.data.message || 'Error').show();
                        }
                    },
                    error: function () {
                        $submit.prop('disabled', false);
                        $text.css('opacity', '1');
                        $spin.hide();
                        $msg.removeClass('nfa-form__msg--success').addClass('nfa-form__msg--error')
                            .text(config.errorMessage || 'Network error. Please try again.').show();
                    },
                });
            });
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
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-hamburger-menu.default', initHamburgerMenu);
        elementorFrontend.hooks.addAction('frontend/element_ready/nfa-advanced-form.default', initAdvancedForm);

        // Initialize extensions on every element ready (global).
        elementorFrontend.hooks.addAction('frontend/element_ready/global', () => {
            initTooltips();
            initWrapperLinks();
        });
    });
})(jQuery);
