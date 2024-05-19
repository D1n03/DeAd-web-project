document.addEventListener("DOMContentLoaded", function () {
  const searchForm = document.getElementById("searchForm");
  const inmateShowDiv = document.getElementById("inmateShow");

  // Attach click event listener to a parent element
  document.addEventListener("click", function (event) {
    const deleteButton = event.target.closest(".delete-show");
    if (deleteButton) {
      // Check if the clicked element or any of its ancestors is a delete button
      const inmateShowBanner = document.querySelector(".inmate__show-banner");
      if (inmateShowBanner) {
        inmateShowBanner.style.display = "none";
      }
    }
  });

  searchForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(searchForm);

    fetch("searchinmate_script.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        inmateShowDiv.innerHTML = data;
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });
});
