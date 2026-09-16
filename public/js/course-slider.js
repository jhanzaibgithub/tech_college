(() => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    document.querySelectorAll('.course-slider-wrap').forEach(wrapper => {
        const slider = wrapper.querySelector('.popular-course-slider');
        const controls = wrapper.querySelectorAll('[data-course-direction]');
        if (!slider) return;

        slider.querySelectorAll('img[data-fallback]').forEach(img => {
            const fallback = () => {
                if (img.src !== img.dataset.fallback) img.src = img.dataset.fallback;
            };
            img.addEventListener('error', fallback, { once: true });
            if (img.complete && !img.naturalWidth) fallback();
        });

        const updateControls = () => controls.forEach(button => {
            button.disabled = Number(button.dataset.courseDirection) < 0
                ? slider.scrollLeft <= 2
                : slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 2;
        });
        controls.forEach(button => button.addEventListener('click', () => {
            const card = slider.querySelector('.popular-course-card');
            const step = card ? card.getBoundingClientRect().width + parseFloat(getComputedStyle(slider).gap) : slider.clientWidth;
            slider.scrollBy({ left: Number(button.dataset.courseDirection) * step, behavior: reducedMotion.matches ? 'instant' : 'smooth' });
        }));
        slider.addEventListener('scroll', updateControls, { passive: true });
        new ResizeObserver(updateControls).observe(slider);
        updateControls();
    });
})();
