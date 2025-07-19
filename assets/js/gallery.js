document.addEventListener("DOMContentLoaded", () => {
  const filterButtons = document.querySelectorAll(
    ".gallery-filter .filter-btn"
  );
  const galleryItems = document.querySelectorAll(".gallery-item");

  filterButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const filterValue = button.getAttribute("data-filter");

      // Update active button
      filterButtons.forEach((btn) => btn.classList.remove("active"));
      button.classList.add("active");

      // Filter gallery items
      galleryItems.forEach((item) => {
        if (
          filterValue === "all" ||
          item.getAttribute("data-type") === filterValue
        ) {
          item.style.display = "block";
        } else {
          item.style.display = "none";
        }
      });
    });
  });
});
