(() => {
    const navigation = document.getElementById('main-navigation');
    const menu = document.querySelector('.menu-toggle');
    function setMenu(open) {
        navigation?.classList.toggle('open', open);
        menu?.setAttribute('aria-expanded', String(open));
        menu?.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    }
    menu?.addEventListener('click', () => setMenu(menu.getAttribute('aria-expanded') !== 'true'));
    document.querySelector('.navigation-band .brand')?.addEventListener('click', () => setMenu(false));
    navigation?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
        setMenu(false);
        navigation.querySelector('.active')?.classList.remove('active');
        link.classList.add('active');
    }));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && menu?.getAttribute('aria-expanded') === 'true') {
            setMenu(false);
            menu.focus();
        }
    });

    // Sticky header: tighten and add a shadow once the top bar has scrolled out of view.
    const header = document.querySelector('.home-header');
    const topbar = document.querySelector('.topbar');
    if (header) {
        const update = () => header.classList.toggle('is-stuck', window.scrollY > (topbar ? topbar.offsetHeight : 0) + 8);
        window.addEventListener('scroll', update, { passive: true });
        update();
    }
})();
