(() => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const ticker = document.querySelector('.news-ticker-bar');
    const tickerButton = document.querySelector('.ticker-toggle');
    const track = document.getElementById('newsTicker');
    if (track) {
        new ResizeObserver(() => {
            track.style.animationDuration = `${Math.max(30, track.scrollWidth / 2 / 40)}s`;
        }).observe(track);
    }
    tickerButton?.addEventListener('click', () => {
        const paused = ticker.classList.toggle('is-paused');
        tickerButton.setAttribute('aria-pressed', String(paused));
        tickerButton.setAttribute('aria-label', paused ? 'Resume news ticker' : 'Pause news ticker');
        tickerButton.firstElementChild.textContent = paused ? '\u25b6' : '\u275a\u275a';
    });

    document.addEventListener('click', event => {
        const button = event.target.closest('.testimonial-more');
        if (!button) return;
        const expanded = button.getAttribute('aria-expanded') !== 'true';
        button.setAttribute('aria-expanded', String(expanded));
        button.closest('.tcard').querySelector('.tcard-text').classList.toggle('is-collapsed', !expanded);
        button.textContent = expanded ? 'Show less \u2212' : 'Read full story +';
    });
    document.querySelectorAll('.testimonial-more').forEach(button => {
        button.closest('.tcard').querySelector('.tcard-text').classList.add('is-collapsed');
    });

    if (window.jQuery && jQuery.fn.owlCarousel) {
        const arrow = direction => `<svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="${direction === 'left' ? 'M19 12H5m7-7-7 7 7 7' : 'M5 12h14m-7-7 7 7-7 7'}"/></svg>`;
        const initializeCarousel = (selector, cardSelector, responsive, name) => {
            const carousel = jQuery(selector);
            if (!carousel.find(cardSelector).length) return;
            carousel.owlCarousel({
                loop: false, rewind: true, margin: 24, nav: true, dots: true,
                autoplay: !reducedMotion.matches, autoplayTimeout: 4500, autoplayHoverPause: true,
                smartSpeed: reducedMotion.matches ? 0 : 500,
                navText: [arrow('left'), arrow('right')], responsive,
                onInitialized: labelControls, onRefreshed: labelControls
            });
            // Let visitors read a card or use its controls without it moving away.
            const updatePlayback = () => {
                const paused = reducedMotion.matches || carousel[0].matches(':hover, :focus-within');
                carousel.trigger(paused ? 'stop.owl.autoplay' : 'play.owl.autoplay');
            };
            carousel.on('focusin', () => carousel.trigger('stop.owl.autoplay'));
            carousel.on('focusout mouseleave', () => requestAnimationFrame(updatePlayback));
            reducedMotion.addEventListener('change', () => {
                const instance = carousel.data('owl.carousel');
                instance.options.autoplay = !reducedMotion.matches;
                instance.options.smartSpeed = reducedMotion.matches ? 0 : 500;
                carousel.trigger('refresh.owl.carousel');
                updatePlayback();
            });
            function labelControls() {
                carousel.find('.owl-prev').attr('aria-label', `Previous ${name}`);
                carousel.find('.owl-next').attr('aria-label', `Next ${name}`);
                carousel.find('.owl-dot').each(function (index) {
                    jQuery(this).attr('aria-label', `${name} page ${index + 1}`);
                });
            }
        };
        initializeCarousel('.tcard-carousel', '.tcard', { 0: { items: 1 }, 680: { items: 2 }, 1024: { items: 3 } }, 'student stories');
        initializeCarousel('.news-carousel', '.news-card-v2', { 0: { items: 1 }, 560: { items: 2 }, 900: { items: 3 } }, 'news');
    }
    window.lucide?.createIcons();
})();
