// Additional animations for specific sections
document.addEventListener('DOMContentLoaded', () => {
    // Counter animation for impact stats
    const animateCounters = () => {
        const counters = document.querySelectorAll('.stat-item h3');
        counters.forEach(counter => {
            const target = parseInt(counter.innerText);
            const increment = target / 50;
            let current = 0;

            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.innerText = Math.floor(current) + '+';
                    setTimeout(updateCounter, 30);
                } else {
                    counter.innerText = target + '+';
                }
            };

            updateCounter();
        });
    };

    // Parallax effect for hero sections
    const parallaxHero = () => {
        const heroSections = document.querySelectorAll('.hero, .page-hero');
        window.addEventListener('scroll', () => {
            heroSections.forEach(section => {
                const scrolled = window.pageYOffset;
                const rate = scrolled * 0.5;
                section.style.backgroundPosition = `center ${rate}px`;
            });
        });
    };

    // Initialize animations
    if (document.querySelector('.stat-item')) {
        animateCounters();
    }
    parallaxHero();
});