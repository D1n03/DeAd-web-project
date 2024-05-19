let subMenu = document.getElementById("subMenu");
let person = document.getElementById("person-icon");

function toggleMenu() {
  subMenu.classList.toggle("open-menu");
}

document.onclick = function (e) {
  if (!subMenu.contains(e.target) && !person.contains(e.target)) {
    subMenu.classList.remove("open-menu");
  }
};

function redirectToVisitInfoPage() {
  window.location.href = "../ActiveVisits/visitInfo.html";
}
