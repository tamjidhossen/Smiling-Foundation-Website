document.addEventListener("DOMContentLoaded", () => {
  const donateForm = document.getElementById("donateForm");
  const amountButtons = document.querySelectorAll(".amount-btn");
  const customAmountInput = document.getElementById("customAmount");

  // Handle amount button clicks
  amountButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // Remove active class from all buttons
      amountButtons.forEach((btn) => btn.classList.remove("active"));
      // Add active class to clicked button
      button.classList.add("active");
      // Set custom amount input value
      customAmountInput.value = button.getAttribute("data-amount");
    });
  });

  // Handle custom amount input
  customAmountInput.addEventListener("input", () => {
    // Remove active class from all buttons when custom amount is entered
    amountButtons.forEach((btn) => btn.classList.remove("active"));
  }); // Handle form submission
  donateForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    console.log("Donation form submitted - Version 2.0");

    const submitButton = donateForm.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;

    // Show loading state
    submitButton.textContent = "Processing...";
    submitButton.disabled = true;

    try {
      const formData = new FormData(donateForm);
      const amount = formData.get("amount");
      console.log("Form data:", {
        amount: amount,
        purpose: formData.get("purpose"),
        name: formData.get("name"),
        email: formData.get("email"),
        timestamp: new Date().toISOString(),
      });

      if (!amount || amount <= 0) {
        throw new Error("Please enter a valid donation amount");
      }

      console.log("Making request to donation handler...");

      // Add cache busting and headers
      const response = await fetch(
        "../donation_handler.php?" + new Date().getTime(),
        {
          method: "POST",
          body: formData,
          headers: {
            "Cache-Control": "no-cache, no-store, must-revalidate",
            Pragma: "no-cache",
            Expires: "0",
          },
        }
      );
      console.log("Response received:", {
        status: response.status,
        statusText: response.statusText,
        ok: response.ok,
        headers: Object.fromEntries(response.headers.entries()),
      });

      if (!response.ok) {
        throw new Error(
          `Server error: ${response.status} ${response.statusText}`
        );
      }

      // Get response as text first to debug potential JSON issues
      const responseText = await response.text();
      console.log("Raw response:", responseText.substring(0, 500));

      let data;
      try {
        data = JSON.parse(responseText);
      } catch (parseError) {
        console.error("JSON parse error:", parseError);
        console.log("Full response text:", responseText);
        throw new Error("Invalid response from server. Please try again.");
      }

      console.log("Parsed response data:", data);

      if (data.success) {
        console.log("Donation successful! Processing redirect...");
        const redirectUrl = `donation-success.php?id=${
          data.donation_id
        }&txn=${encodeURIComponent(data.transaction_id)}`;
        console.log("Redirect URL:", redirectUrl);

        // Clear any existing messages
        const existingMessages = document.querySelectorAll(".form-message");
        existingMessages.forEach((msg) => msg.remove());

        // Show success message with enhanced styling
        showMessage(
          "Donation received successfully! Redirecting to thank you page...",
          "success"
        );

        // Update button text
        submitButton.textContent = "Success! Redirecting...";
        // Redirect after a short delay
        setTimeout(() => {
          console.log("Redirecting now...");
          window.location.href = redirectUrl;
        }, 2000);
      } else {
        console.error("Donation failed:", data.message);
        showMessage(`Error: ${data.message}`, "error");
      }
    } catch (error) {
      console.error("Error occurred:", error);
      showMessage(
        `Error: ${
          error.message || "An unexpected error occurred. Please try again."
        }`,
        "error"
      );
    } finally {
      // Reset button after delay to allow redirect
      setTimeout(() => {
        if (submitButton.textContent !== "Success! Redirecting...") {
          submitButton.textContent = originalText;
          submitButton.disabled = false;
        }
      }, 3000);
    }
  });
  function showMessage(message, type) {
    console.log(`Showing ${type} message:`, message);

    // Remove existing messages
    const existingMessages = document.querySelectorAll(".form-message");
    existingMessages.forEach((msg) => {
      console.log("Removing existing message");
      msg.remove();
    });

    // Create message element with enhanced styling
    const messageDiv = document.createElement("div");
    messageDiv.className = `form-message ${type}`;
    messageDiv.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 1.2em;">${
                  type === "success" ? "✓" : "✗"
                }</span>
                <span>${message}</span>
            </div>
        `;

    // Add specific styling to ensure visibility
    messageDiv.style.cssText = `
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            border: 2px solid;
            animation: slideIn 0.3s ease-out;
            position: relative;
            z-index: 1000;
            ${
              type === "success"
                ? "background: #d4edda; color: #155724; border-color: #c3e6cb;"
                : "background: #f8d7da; color: #721c24; border-color: #f5c6cb;"
            }
        `;

    // Insert message at the top of the form
    donateForm.insertBefore(messageDiv, donateForm.firstChild);
    console.log("Message inserted into DOM");

    // Auto remove after longer delay for debugging
    setTimeout(
      () => {
        if (messageDiv.parentNode) {
          console.log("Auto-removing message after timeout");
          messageDiv.remove();
        }
      },
      type === "success" ? 8000 : 10000
    );

    // Scroll to message with offset for navbar
    setTimeout(() => {
      const rect = messageDiv.getBoundingClientRect();
      const navbarHeight = 80;
      window.scrollTo({
        top: window.pageYOffset + rect.top - navbarHeight,
        behavior: "smooth",
      });
      console.log("Scrolled to message");
    }, 100);
  }
});
