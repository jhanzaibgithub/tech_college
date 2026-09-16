(() => {
    const hero = document.querySelector('[data-hero-banners]');
    if (!hero) return;
    const slides = Array.from(hero.querySelectorAll('.hero-banner-slide'));
    if (slides.length < 2) return;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let current = 0;
    let moving = false;
    let timer;

    async function show(index) {
        if (moving) return;
        const next = (index + slides.length) % slides.length;
        if (next === current) return;
        moving = true;
        const outgoing = slides[current];
        const incoming = slides[next];
        const direction = index > current ? 1 : -1;
        const img = incoming.querySelector('img');
        img.loading = 'eager';
        // Wait for the next image so the slide never enters as an empty panel.
        await img.decode().catch(() => {});
        incoming.hidden = false;
        outgoing.setAttribute('aria-hidden', 'true');
        incoming.removeAttribute('aria-hidden');
        if (!reducedMotion.matches) {
            const options = { duration: 600, easing: 'cubic-bezier(0.4, 0, 0.2, 1)', fill: 'both' };
            const animations = [
                outgoing.animate([{ transform: 'translateX(0)' }, { transform: `translateX(${-direction * 100}%)` }], options),
                incoming.animate([{ transform: `translateX(${direction * 100}%)` }, { transform: 'translateX(0)' }], options),
            ];
            await Promise.all(animations.map(animation => animation.finished.catch(() => {})));
            outgoing.hidden = true;
            animations.forEach(animation => animation.cancel());
        } else {
            outgoing.hidden = true;
        }
        current = next;
        moving = false;
    }
    function schedule() {
        clearInterval(timer);
        if (!reducedMotion.matches && !document.hidden) {
            timer = setInterval(() => show(current + 1), 5000);
        }
    }
    hero.querySelector('[data-banner-prev]').addEventListener('click', () => { show(current - 1); schedule(); });
    hero.querySelector('[data-banner-next]').addEventListener('click', () => { show(current + 1); schedule(); });
    document.addEventListener('visibilitychange', schedule);
    reducedMotion.addEventListener('change', schedule);
    schedule();
})();
