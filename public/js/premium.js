/* Shared behaviour for public pages: reveal animations, counters, enrollment modal,
   Pakistani mobile-number validation and the course-card image carousel. */
(() => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    window.lucide?.createIcons();

    /* ---------- Section reveal + stagger ---------- */
    const revealTargets = document.querySelectorAll('[data-reveal]');
    document.querySelectorAll('[data-stagger]').forEach(group => {
        Array.from(group.children).forEach((child, index) => child.style.setProperty('--i', index));
    });
    if ('IntersectionObserver' in window && !reducedMotion.matches) {
        const observer = new IntersectionObserver(entries => entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        }), { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        revealTargets.forEach(target => observer.observe(target));
    } else {
        revealTargets.forEach(target => target.classList.add('is-visible'));
    }

    /* ---------- Animated counters ---------- */
    const counters = document.querySelectorAll('[data-count-to]');
    const runCounter = element => {
        const target = Number(element.dataset.countTo) || 0;
        if (reducedMotion.matches || target === 0) return;
        const duration = 1800;
        const start = performance.now();
        const format = new Intl.NumberFormat('en-US');
        const tick = now => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            element.textContent = format.format(Math.round(target * eased));
            if (progress < 1) requestAnimationFrame(tick);
        };
        element.textContent = '0';
        requestAnimationFrame(tick);
    };
    if ('IntersectionObserver' in window && counters.length) {
        const counterObserver = new IntersectionObserver(entries => entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            runCounter(entry.target);
            counterObserver.unobserve(entry.target);
        }), { threshold: 0.4 });
        counters.forEach(counter => counterObserver.observe(counter));
    }

    /* ---------- Enrollment modal ---------- */
    const modal = document.querySelector('[data-enrollment-modal]');
    if (modal) {
        const courseSelect = modal.querySelector('select[name="course_id"]');
        let lastFocus = null;
        const open = courseId => {
            lastFocus = document.activeElement;
            if (courseId && courseSelect) courseSelect.value = courseId;
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
            (courseSelect?.value ? modal.querySelector('input[name="name"]') : courseSelect)?.focus();
        };
        const close = () => {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
            lastFocus?.focus?.();
        };
        document.addEventListener('click', event => {
            const trigger = event.target.closest('[data-open-enrollment]');
            if (trigger) {
                event.preventDefault();
                document.querySelector('.navigation-band nav.open')?.classList.remove('open');
                open(trigger.dataset.courseId);
            } else if (event.target.closest('[data-close-enrollment]')) {
                close();
            }
        });
        document.addEventListener('keydown', event => {
            if (!modal.classList.contains('open')) return;
            if (event.key === 'Escape') return close();
            if (event.key !== 'Tab') return;
            const focusable = Array.from(modal.querySelectorAll('button, [href], input, select, textarea')).filter(el => !el.disabled && el.offsetParent !== null);
            if (!focusable.length) return;
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        });
        if (modal.dataset.hasErrors === '1') open();
    }

    /* ---------- Pakistani mobile numbers: exactly 11 digits, 03XXXXXXXXX ---------- */
    const PK_MOBILE = /^03[0-4]\d{8}$/;
    document.querySelectorAll('[data-pk-phone]').forEach(input => {
        const message = input.parentElement.querySelector('[data-phone-error]');
        const validate = () => {
            const invalid = input.value !== '' && !PK_MOBILE.test(input.value);
            input.setCustomValidity(invalid ? 'Enter an 11-digit Pakistani mobile number, e.g. 03001234567.' : '');
            input.setAttribute('aria-invalid', String(invalid));
            if (message) message.hidden = !invalid;
            return !invalid;
        };
        input.addEventListener('input', () => {
            // Digits only, never more than 11.
            input.value = input.value.replace(/\D/g, '').slice(0, 11);
            if (input.getAttribute('aria-invalid') === 'true') validate();
        });
        input.addEventListener('blur', validate);
        input.form?.addEventListener('submit', event => {
            if (!validate()) {
                event.preventDefault();
                input.reportValidity();
            }
        });
    });

    /* ---------- Course card image carousel + image fallbacks (re-run for cards added later) ---------- */
    const initCards = (root = document) => {
    root.querySelectorAll('[data-card-carousel][data-multi]:not([data-ready])').forEach(media => {
        media.dataset.ready = '1';
        const track = media.querySelector('.course-media-track');
        const slides = Array.from(track.children);
        const dots = Array.from(media.querySelectorAll('.course-media-dots span'));
        let index = 0;

        const go = target => {
            index = (target + slides.length) % slides.length;
            track.scrollTo({ left: slides[index].offsetLeft, behavior: reducedMotion.matches ? 'auto' : 'smooth' });
        };
        media.querySelector('.course-media-prev').addEventListener('click', () => go(index - 1));
        media.querySelector('.course-media-next').addEventListener('click', () => go(index + 1));
        track.addEventListener('scroll', () => {
            const current = Math.round(track.scrollLeft / track.clientWidth);
            if (current === index && dots[index]?.classList.contains('on')) return;
            index = Math.min(Math.max(current, 0), slides.length - 1);
            dots.forEach((dot, i) => dot.classList.toggle('on', i === index));
        }, { passive: true });
        media.addEventListener('keydown', event => {
            if (event.key === 'ArrowLeft') go(index - 1);
            if (event.key === 'ArrowRight') go(index + 1);
        });
    });

    root.querySelectorAll('img[data-fallback]:not([data-fb])').forEach(img => {
        img.dataset.fb = '1';
        const fallback = () => {
            if (img.src !== img.dataset.fallback) img.src = img.dataset.fallback;
        };
        img.addEventListener('error', fallback, { once: true });
        if (img.complete && !img.naturalWidth) fallback();
    });
    };
    initCards();
    window.TechCollege = Object.assign(window.TechCollege || {}, { initCards });
})();
