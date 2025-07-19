/**
 * Team page functionality
 */

// Function to toggle bio read more/less
function toggleBio(button) {
  const bioContainer = button.parentElement;
  const shortBio = bioContainer.querySelector(".bio-short");
  const fullBio = bioContainer.querySelector(".bio-full");

  if (fullBio.style.display === "none") {
    // Show full bio
    shortBio.style.display = "none";
    fullBio.style.display = "block";
    button.textContent = "Read Less";
    button.classList.add("expanded");
  } else {
    // Show short bio
    shortBio.style.display = "block";
    fullBio.style.display = "none";
    button.textContent = "Read More";
    button.classList.remove("expanded");
  }
}

// Initialize team page functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  // Add smooth scroll to social links
  const socialLinks = document.querySelectorAll(".social-links a");
  socialLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      // Add a subtle animation on click
      this.style.transform = "scale(0.95)";
      setTimeout(() => {
        this.style.transform = "";
      }, 150);
    });
  });

  // Add hover effect to team cards
  const teamCards = document.querySelectorAll(".team-card");
  teamCards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-10px)";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
    });
  });
});
