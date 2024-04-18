const menu = document.querySelector("#mobile-menu");
const menuLinks = document.querySelector(".nav-list");

menu.addEventListener("click", function () {
  menu.classList.toggle("is-active");
  menuLinks.classList.toggle("active");
});


let subMenu = document.getElementById("subMenu");
let person = document.getElementById("person-icon");

function toggleMenu() {
    subMenu.classList.toggle("open-menu");
}

document.onclick = function(e) {
    if (!subMenu.contains(e.target) && !person.contains(e.target)) {
        subMenu.classList.remove("open-menu");
    }
}

function redirectToVisitInfoPage() {
    window.location.href = "visitInfo.html";
}