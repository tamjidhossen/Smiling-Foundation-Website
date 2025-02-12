document.addEventListener('DOMContentLoaded', () => {
    const faqItems = document.querySelectorAll('.faq-question');

    faqItems.forEach(item => {
        item.addEventListener('click', () => {
            const answer = item.nextElementSibling;
            const icon = item.querySelector('i');
            
            // Toggle active class
            item.classList.toggle('active');
            
            // Toggle icon
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
            
            // Toggle answer visibility
            if (answer.style.maxHeight) {
                answer.style.maxHeight = null;
            } else {
                answer.style.maxHeight = answer.scrollHeight + "px";
            }
        });
    });
});