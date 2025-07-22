document.addEventListener("DOMContentLoaded", () => {
  const contactForm = document.getElementById("contactForm");

  contactForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Show loading state
    const submitBtn = contactForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = "Sending...";
    submitBtn.disabled = true;

    try {
      // Get form data
      const formData = new FormData(contactForm);

      // Send to server
      const response = await fetch("../contact_handler.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        contactForm.reset();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("There was an error sending your message. Please try again.");
    } finally {
      // Reset button state
      submitBtn.textContent = originalText;
      submitBtn.disabled = false;
    }
  });
});
