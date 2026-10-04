/* Courses page: "Load more" (6 at a time) and the animated sort dropdown. */
(() => {
    const page = document.querySelector('[data-courses-page]');
    if (!page) return;

    const grid = page.querySelector('#course-grid');
    const wrap = page.querySelector('[data-load-more-wrap]');
    const button = page.querySelector('[data-load-more]');
    const allLoaded = page.querySelector('[data-all-loaded]');
    const shownEl = page.querySelector('[data-shown]');
    const totalEl = page.querySelector('[data-total]');
    const progress = page.querySelector('[data-progress]');
    const dropdown = page.querySelector('[data-sort-dd]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let busy = false;

    const request = async url => {
        const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } });
        if (!response.ok) throw new Error('Request failed');
        return response.json();
    };

    const render = (data, { replace }) => {
        const template = document.createElement('template');
        template.innerHTML = data.html.trim();
        const cards = Array.from(template.content.children);
        cards.forEach((card, index) => {
            card.classList.add('card-in');
            card.style.setProperty('--i', index);
        });
        if (replace) grid.replaceChildren(...cards);
        else grid.append(...cards);

        window.lucide?.createIcons();
        window.TechCollege?.initCards(grid);

        shownEl.textContent = data.shown;
        totalEl.textContent = data.total;
        if (progress) progress.style.width = Math.round((data.shown / data.total) * 100) + '%';
        wrap.hidden = !data.hasMore;
        if (data.hasMore) button.href = data.nextUrl;
        if (allLoaded) allLoaded.hidden = data.hasMore || data.total <= 6;
        return cards;
    };

    /* ---- Load more ---- */
    button?.addEventListener('click', async event => {
        event.preventDefault();
        if (busy) return;
        busy = true;
        button.classList.add('is-loading');
        button.setAttribute('aria-busy', 'true');
        try {
            const cards = render(await request(button.href), { replace: false });
            // Keep keyboard users in context: move focus to the first new card's link.
            cards[0]?.querySelector('h3 a')?.focus({ preventScroll: true });
            cards[0]?.scrollIntoView({ behavior: reducedMotion.matches ? 'auto' : 'smooth', block: 'center' });
        } catch {
            window.location.href = button.href; // plain page 2 as a fallback
        } finally {
            busy = false;
            button.classList.remove('is-loading');
            button.removeAttribute('aria-busy');
        }
    });

    /* ---- Sort dropdown ---- */
    if (!dropdown) return;
    const toggle = dropdown.querySelector('.sort-dd-btn');
    const menu = dropdown.querySelector('.sort-dd-menu');
    const options = Array.from(menu.querySelectorAll('[role="option"]'));
    const label = dropdown.querySelector('#sort-current');

    const setOpen = open => {
        dropdown.classList.toggle('open', open);
        toggle.setAttribute('aria-expanded', String(open));
        if (open) (options.find(o => o.getAttribute('aria-selected') === 'true') || options[0]).focus();
    };

    const choose = async option => {
        setOpen(false);
        toggle.focus();
        if (option.getAttribute('aria-selected') === 'true' || busy) return;
        busy = true;
        options.forEach(o => o.setAttribute('aria-selected', String(o === option)));
        label.textContent = option.querySelector('span').textContent;
        const icon = toggle.querySelector('[data-sort-icon]');
        icon.setAttribute('data-lucide', option.dataset.icon);
        window.lucide?.createIcons();

        const url = new URL(page.dataset.url, window.location.origin);
        url.searchParams.set('sort', option.dataset.value);
        grid.classList.add('is-refreshing');
        try {
            render(await request(url), { replace: true });
            history.replaceState(null, '', url.pathname + url.search);
        } catch {
            window.location.href = url.toString();
        } finally {
            busy = false;
            grid.classList.remove('is-refreshing');
        }
    };

    toggle.addEventListener('click', () => setOpen(!dropdown.classList.contains('open')));
    toggle.addEventListener('keydown', event => {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') { event.preventDefault(); setOpen(true); }
    });
    options.forEach(option => {
        option.addEventListener('click', () => choose(option));
        option.addEventListener('keydown', event => {
            const index = options.indexOf(option);
            if (event.key === 'ArrowDown') { event.preventDefault(); options[(index + 1) % options.length].focus(); }
            else if (event.key === 'ArrowUp') { event.preventDefault(); options[(index - 1 + options.length) % options.length].focus(); }
            else if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); choose(option); }
            else if (event.key === 'Escape') { setOpen(false); toggle.focus(); }
            else if (event.key === 'Tab') setOpen(false);
        });
    });
    document.addEventListener('click', event => { if (!dropdown.contains(event.target)) setOpen(false); });
})();
