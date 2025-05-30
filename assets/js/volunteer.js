document.addEventListener("DOMContentLoaded", function () {
  const volunteerForm = document.getElementById("volunteerForm");

  if (volunteerForm) {
    volunteerForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const submitButton = volunteerForm.querySelector('button[type="submit"]');
      const originalText = submitButton.textContent;

      // Show loading state
      submitButton.textContent = "Submitting...";
      submitButton.disabled = true;

      // Create FormData object
      const formData = new FormData(volunteerForm);
      // Submit form
      fetch("/smilingfoundation/volunteer_handler.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Show success message
            showMessage(data.message, "success");
            volunteerForm.reset();
          } else {
            // Show error message
            showMessage(data.message, "error");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showMessage("An error occurred. Please try again.", "error");
        })
        .finally(() => {
          // Reset button
          submitButton.textContent = originalText;
          submitButton.disabled = false;
        });
    });
  }
});

function showMessage(message, type) {
  // Remove existing messages
  const existingMessages = document.querySelectorAll(".form-message");
  existingMessages.forEach((msg) => msg.remove());

  // Create message element
  const messageDiv = document.createElement("div");
  messageDiv.className = `form-message ${type}`;
  messageDiv.textContent = message;

  // Insert message at the top of the form
  const form = document.getElementById("volunteerForm");
  form.insertBefore(messageDiv, form.firstChild);

  // Auto remove after 5 seconds
  setTimeout(() => {
    messageDiv.remove();
  }, 5000);
  // Scroll to message with offset for navbar
  setTimeout(() => {
    const rect = messageDiv.getBoundingClientRect();
    const navbarHeight = 80; // Adjust this value based on your navbar height
    window.scrollTo({
      top: window.pageYOffset + rect.top - navbarHeight,
      behavior: "smooth",
    });
  }, 100);
}
