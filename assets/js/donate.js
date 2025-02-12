document.addEventListener('DOMContentLoaded', () => {
    const donateForm = document.getElementById('donateForm');
    const amountButtons = document.querySelectorAll('.amount-btn');
    const customAmountInput = document.getElementById('customAmount');

    // Handle amount button clicks
    amountButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons
            amountButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            button.classList.add('active');
            // Set custom amount input value
            customAmountInput.value = button.getAttribute('data-amount');
        });
    });

    // Handle custom amount input
    customAmountInput.addEventListener('input', () => {
        // Remove active class from all buttons when custom amount is entered
        amountButtons.forEach(btn => btn.classList.remove('active'));
    });

    // Handle form submission
    donateForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(donateForm);
        const amount = formData.get('amount');
        
        if (!amount || amount <= 0) {
            alert('Please enter a valid donation amount');
            return;
        }

        // Here you would typically integrate with a payment gateway
        // For now, just show a success message
        alert('Thank you for your donation! This is a demo - no actual payment was processed.');
        donateForm.reset();
        amountButtons.forEach(btn => btn.classList.remove('active'));
    });
});