/* Images that fail to load once (a busy shared server, a dropped connection) are retried once with a fresh
   address before the fallback picture is used. This is what stops "all images missing" glitches from sticking. */
(() => {
    const RETRY_DELAY = 900;

    window.addEventListener('error', event => {
        const img = event.target;
        if (!(img instanceof HTMLImageElement)) return;

        const failing = img.currentSrc || img.src;
        if (!failing || failing.startsWith('data:')) return;

        if (!img.dataset.retried) {
            img.dataset.retried = '1';
            setTimeout(() => {
                try {
                    const url = new URL(failing, window.location.href);
                    url.searchParams.set('r', Date.now());
                    img.src = url.toString();
                } catch { /* keep the broken state; the next error shows the fallback */ }
            }, RETRY_DELAY);
            return;
        }

        const fallback = img.dataset.fallback;
        if (fallback && img.src !== fallback) img.src = fallback;
    }, true);
})();
