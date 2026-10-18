document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.reveal');

    // Fallback: show content if IntersectionObserver isn't supported.
    if (!('IntersectionObserver' in window)) {
        elements.forEach((element) => {
            element.classList.add('is-visible');
        });
        return;
    }

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');

                // Animate only once.
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -30px 0px'
    });

    elements.forEach((element) => observer.observe(element));
});
