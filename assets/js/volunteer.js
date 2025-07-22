document.addEventListener("DOMContentLoaded", function () {
  const volunteerForm = document.getElementById("volunteerForm");

  if (volunteerForm) {
    //validations
    const nameInput = document.getElementById("name");
    const phoneInput = document.getElementById("phone");
    const nidInput = document.getElementById("nid");
    const addressInput = document.getElementById("present_address");
    const skillsInput = document.getElementById("special_skills");

    // Name validation
    nameInput.addEventListener("input", function () {
      const name = this.value.trim();
      if (name.length > 0 && name.length < 3) {
        this.setCustomValidity("Name must be at least 3 characters long.");
      } else if (/\d/.test(name)) {
        this.setCustomValidity("Name cannot contain numbers.");
      } else {
        this.setCustomValidity("");
      }
    });

    // Phone validation
    if (phoneInput) {
      phoneInput.addEventListener("input", function () {
        // Remove any non-digit characters for validation
        const phone = this.value.replace(/\D/g, "");
        const phonePattern = /^01[3-9]\d{8}$/;

        if (phone.length > 0 && !phonePattern.test(phone)) {
          this.setCustomValidity(
            "Please enter a valid Bangladeshi mobile number (11 digits starting with 01)"
          );
        } else {
          this.setCustomValidity("");
        }
      });
    }

    // NID validation
    if (nidInput) {
      nidInput.addEventListener("input", function () {
        const nid = this.value.trim();
        const nidPattern = /^(\d{10}|\d{13}|\d{17})$/;
        if (nid.length > 0 && !nidPattern.test(nid)) {
          this.setCustomValidity(
            "Please enter a valid 10, 13, or 17 digit NID number."
          );
        } else {
          this.setCustomValidity("");
        }
      });
    }

    // Full Address validation
    if (addressInput) {
      addressInput.addEventListener("input", function () {
        if (this.value.length > 255) {
          this.value = this.value.slice(0, 255);
        }
        const address = this.value.trim();
        if (address.length > 0 && address.length < 10) {
          this.setCustomValidity(
            "Full address must be at least 10 characters long."
          );
        } else {
          this.setCustomValidity("");
        }
      });
    }
    // Special Skills validation (optional field)
    if (skillsInput) {
      skillsInput.addEventListener("input", function () {
        if (this.value.length > 500) {
          this.value = this.value.slice(0, 500);
        }
        const skills = this.value.trim();
        if (skills.length > 0 && skills.length < 5) {
          this.setCustomValidity(
            "Please describe your skills in at least 5 characters."
          );
        } else {
          this.setCustomValidity("");
        }
      });
    }

    volunteerForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const submitButton = volunteerForm.querySelector('button[type="submit"]');
      const originalText = submitButton.textContent;

      // Check form validity before submitting
      if (!volunteerForm.checkValidity()) {
        // Show validation messages
        volunteerForm.reportValidity();
        return;
      }

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
