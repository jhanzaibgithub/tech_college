/* Page loader: shown on every page load, hidden once the page is ready (never a flash, never stuck). */
(() => {
    const root = document.documentElement;
    const loader = document.querySelector('[data-page-loader]');

    if (!loader) {
        root.classList.add('loader-done');
        return;
    }

    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const minimum = reduced ? 150 : 700; // ms since the page started loading
    let finished = false;

    const finish = () => {
        if (finished) return;
        finished = true;
        setTimeout(() => {
            loader.classList.add('is-done');
            root.classList.add('loader-done');
            setTimeout(() => loader.remove(), 700);
        }, Math.max(0, minimum - performance.now()));
    };

    if (document.readyState === 'complete') finish();
    else window.addEventListener('load', finish, { once: true });

    setTimeout(finish, 5000); // never trap a visitor behind a slow third-party script
})();
