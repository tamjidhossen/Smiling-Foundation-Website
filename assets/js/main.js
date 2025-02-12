// Animation Observer
const observeElements = () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    const animatedElements = document.querySelectorAll('.fade-in, .slide-in, .scale-in');
    animatedElements.forEach(element => observer.observe(element));
};

// Mobile Navigation Toggle
const initMobileNav = () => {
    const menuButton = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (menuButton) {
        menuButton.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    observeElements();
    initMobileNav();
});