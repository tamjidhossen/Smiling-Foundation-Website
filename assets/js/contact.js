document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contactForm');

    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Get form data
        const formData = new FormData(contactForm);
        const formDataObject = {};
        formData.forEach((value, key) => {
            formDataObject[key] = value;
        });

        try {
            // In a real implementation, you would send this to a server
            // For demo purposes, just show success message
            console.log('Form data:', formDataObject);
            alert('Thank you for your message! We will get back to you soon.');
            contactForm.reset();
        } catch (error) {
            alert('There was an error sending your message. Please try again.');
        }
    });
});